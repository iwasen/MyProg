// comm.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "EcuRecv.h"
#include "comm.h"
#include <process.h>

// defile
#define BUFSIZE		1024	// 送受信バッファサイズ

// 通信コード
#define CODE_STX	0x02
#define CODE_ETX	0x03
#define CODE_FF		0xff

// 文字間タイムアウト時間
#define TIMEOUT_CHAR	1

// フレームコード
#define FRAME_RESPONSE	'R'
#define FRAME_COMMTEST	'T'
#define FRAME_TESTSTART	'S'
#define FRAME_HEADER	'H'
#define FRAME_DATA		'D'
#define FRAME_DATAEND	'F'
#define FRAME_TESTEND	'E'

// レスポンスコード
#define RESPONSE_NORMAL		'0'
#define RESPONSE_BCCERR		'1'
#define RESPONSE_NOTREADY	'2'
#define RESPONSE_SEQERR		'3'

// データ受信モード
#define MODE_STOP		0
#define MODE_START		1
#define MODE_HEADER		2
#define MODE_DATA		3

// フレーム受信ステート
#define STATE_IDLE	0
#define STATE_DATA	1
#define STATE_BCC	2

/* ＰＡＭデータ・ヘッダ構造体 */
struct PAM_HEADER {
	char	date[10];	/* 記録開始日付 */
	char	time[8];	/* 記録開始時刻 */
	char	mode[2];	/* PAM動作ﾓｰﾄﾞ */
	char	rec[4];		/* ﾚｺｰﾄﾞ数 */
	char	dummy[8];	/* 予備 */
};

/* ファイルヘッダ構造体 */
struct FILE_HEADER {
	char seqNo[3];
	char state[1];
	struct PAM_HEADER pamHeader;
};

/* ＰＡＭデータ・レコード内容 */
struct PAM_DATA {
	BYTE	clk;		/* 55msecｶｳﾝﾀ */
	BYTE	CmdHit;		/* ｺﾏﾝﾄﾞ打刻ﾌﾗｸﾞ */
    BYTE	sds;        /* ｿﾌﾄﾄﾞｯｸ */
	BYTE	dummy1[5];	/* ﾀﾞﾐｰ */
	struct SLM_DATA {
		BYTE	ls;			/* ﾘﾐｯﾄｽｲｯﾁｽﾃｰﾀｽ */
		BYTE	pwm;		/* 指示PWM値 */
		WORD	rad;		/* 回転角 */
		short   vr;			/* 実速度 */	
		WORD	vo;			/* 指示速度 */
		WORD	bibun;		/* 微分値 */
		WORD	sekibun;	/* 積分値 */
		BYTE	elec;		/* 実電流 */
		BYTE	sdj;     	/* ｿﾌﾄﾄﾞｯｸ状態ｽﾃｰﾀｽ */
	} slmdat[4];
};

// 内部スタティック変数
static int mode;
static HANDLE handle;
static HANDLE hThread;
static CString sSaveFolder;
static CFile file;
static CWnd *pDlgWnd;

// 内部関数プロトタイプ
static BOOL OpenComm();
static void ControlComm(LPVOID param);
static void ReceiveHeader(FILE_HEADER *pFileHeader);
static void ReceiveData(PAM_DATA *pPamData);
static void EndData();
static PBYTE SetFrame(PBYTE pBuf, BYTE c);
static BOOL PutComm(PBYTE pBuf, int len);
static int GetFrame(PBYTE pData);
static int GetComm();
static void SendResponse(int response);
static void SendNotify(WPARAM wParam, LPARAM lParam);

//===================================================================
//
//	機能　：通信ハンドラ起動処理
//
//	引数　：無し
//
//	戻り値：TRUE  正常
//			FALSE 起動失敗
//
//===================================================================
BOOL StartCommHandler()
{
	// シリアルポートオープン
	if (!OpenComm()) {
		AfxMessageBox("シリアルポート(COM1)のオープンに失敗しました。");
		return FALSE;
	}

	// シリアルポート制御スレッド作成
	if ((hThread = (HANDLE)_beginthread(ControlComm, 0, NULL)) == (HANDLE)-1) {
		AfxMessageBox("シリアルポート制御スレッドの作成に失敗しました。");
		return FALSE;
	}

	return TRUE;
}

//===================================================================
//
//	機能　：通信開始処理
//
//	引数　：pWnd - メッセージ通知ウィンドウ
//
//	戻り値：TRUE 存在する, FALSE 存在しない
//
//===================================================================
void StartTest(CWnd *pWnd, LPCTSTR pSaveFolder)
{
	// 通知ウィンドウ保存
	pDlgWnd = pWnd;

	// フォルダ名保存
	sSaveFolder = pSaveFolder;

	// 処理モードを開始状態へセット
	mode = MODE_START;

	// 受信状態表示
	SendNotify(NOTIFY_RECVSTATUS, (LPARAM)"データ受信待ち");
}

//===================================================================
//
//	機能　：通信終了処理
//
//	引数　：無し
//
//	戻り値：無し
//
//===================================================================
void StopTest()
{
	// 処理モードを停止状態へセット
	mode = MODE_STOP;

	// 通知ウィンドウリセット
	pDlgWnd = NULL;
}

//===================================================================
//
//	機能　：シリアルポートオープン処理
//
//	引数　：無し
//
//	戻り値：TRUE 正常, FALSE オープン失敗
//
//===================================================================
static BOOL OpenComm()
{
	DCB	dcb;
	COMMTIMEOUTS	ctmo;

	// オープン済みなら何もしない
	if (handle != NULL)
		return TRUE;

	// シリアルポートオープン
	if ((handle = CreateFile("COM1", GENERIC_READ | GENERIC_WRITE, 0,
			NULL, OPEN_EXISTING, 0, NULL))
			== INVALID_HANDLE_VALUE) {
		handle = NULL;
		return FALSE;
	}

	// 送受信バッファサイズ設定
	if (!SetupComm(handle, 1024, 1024)) {
		CloseHandle(handle);
		handle = NULL;
		return FALSE;
	}

	// 通信条件設定
	memset(&dcb, 0, sizeof(dcb));
	dcb.DCBlength = sizeof(DCB);
	dcb.BaudRate = CBR_9600;
	dcb.fBinary = TRUE;
//	dcb.fOutxCtsFlow = TRUE;
	dcb.fDtrControl = DTR_CONTROL_ENABLE;
	dcb.fRtsControl = RTS_CONTROL_ENABLE;
	dcb.ByteSize = 8;
	dcb.Parity = NOPARITY;
	dcb.StopBits = ONESTOPBIT;
	if (!SetCommState(handle, &dcb)) {
		CloseHandle(handle);
		handle = NULL;
		return FALSE;
	}

	// タイムアウト時間設定
	memset(&ctmo, 0, sizeof(ctmo));
	ctmo.ReadTotalTimeoutConstant = TIMEOUT_CHAR * 1000;
	ctmo.WriteTotalTimeoutConstant = TIMEOUT_CHAR * 1000;
	if (!SetCommTimeouts(handle, &ctmo)) {
		CloseHandle(handle);
		handle = NULL;
		return FALSE;
	}

	return TRUE;
}

//===================================================================
//
//	機能　：通信制御処理
//
//	引数　：param - スレッドパラメータ（不使用）
//
//	戻り値：無し
//
//===================================================================
static void ControlComm(LPVOID param)
{
	BYTE recvBuf[BUFSIZE];
	int len;

	for (;;) {
		// １フレーム受信
		len = GetFrame(recvBuf);
		if (len == 0)
			continue;

		// フレームコードによる振り分け
		switch (recvBuf[0]) {
		case FRAME_COMMTEST:	// 通信テストフレーム
			// Normal を送信
			SendResponse(RESPONSE_NORMAL);
			break;
		case FRAME_TESTSTART:	// 試験開始フレーム
			// 処理モードによる振り分け
			switch (mode) {
			case MODE_START:	// 試験開始フレーム待ち
				// 試験回数を表示
				recvBuf[4] = '\0';
				SendNotify(NOTIFY_TExxxOUNT, (LPARAM)(recvBuf + 1));

				// 試験開始フレームを受信したら NORMAL を返す
				SendResponse(RESPONSE_NORMAL);
				mode = MODE_HEADER;
				break;
			case MODE_STOP:
				// 試験開始していなかったら Not Ready を返す
				SendResponse(RESPONSE_NOTREADY);
				break;
			default:
				// それ以外はシーケンスエラー
				SendResponse(RESPONSE_SEQERR);
				break;
			}
			break;
		case FRAME_HEADER:		// ヘッダフレーム
			// 処理モードによる振り分け
			switch (mode) {
			case MODE_HEADER:
				// ヘッダ受信処理
				SendNotify(NOTIFY_RECVSTATUS, (LPARAM)"データ受信中");
				ReceiveHeader((FILE_HEADER *)(recvBuf + 1));
				SendResponse(RESPONSE_NORMAL);
				mode = MODE_DATA;
				break;
			case MODE_STOP:
				// 試験開始していなかったら Not Ready を返す
				SendResponse(RESPONSE_NOTREADY);
				break;
			default:
				// それ以外はシーケンスエラー
				SendResponse(RESPONSE_SEQERR);
				break;
			}
			break;
		case FRAME_DATA:	// データフレーム
			// 処理モードによる振り分け
			switch (mode) {
			case MODE_DATA:
				// データ受信処理
				ReceiveData((PAM_DATA *)(recvBuf + 1));
				SendResponse(RESPONSE_NORMAL);
				break;
			case MODE_STOP:
				// 試験開始していなかったら Not Ready を返す
				SendResponse(RESPONSE_NOTREADY);
				break;
			default:
				// それ以外はシーケンスエラー
				SendResponse(RESPONSE_SEQERR);
				break;
			}
			break;
		case FRAME_DATAEND:	// レコード終了フレーム
			// 処理モードによる振り分け
			switch (mode) {
			case MODE_DATA:
				// データ受信終了処理
				SendNotify(NOTIFY_RECVSTATUS, (LPARAM)"データ受信待ち");
				EndData();
				SendResponse(RESPONSE_NORMAL);
				mode = MODE_HEADER;
				break;
			case MODE_STOP:
				// 試験開始していなかったら Not Ready を返す
				SendResponse(RESPONSE_NOTREADY);
				break;
			default:
				// それ以外はシーケンスエラー
				SendResponse(RESPONSE_SEQERR);
				break;
			}
			break;
		case FRAME_TESTEND:	// 試験終了フレーム
			// ファイルがオープン中ならクローズする
			if (file.m_hFile != CFile::hFileNull)
				file.Close();

			// Normal を送信
			SendResponse(RESPONSE_NORMAL);

			// 処理モードを停止状態に設定
			mode = MODE_STOP;

			// 通信終了メッセージ表示
			SendNotify(NOTIFY_RECVSTATUS, (LPARAM)"通信終了");

			// ダイアログに試験終了を送信
			SendNotify(NOTIFY_TESTEND, 0);
			break;
		}
	}
}

//===================================================================
//
//	機能　：ヘッダフレーム受信処理
//
//	引数　：pFileHeader - ヘッダデータ
//
//	戻り値：無し
//
//===================================================================
static void ReceiveHeader(FILE_HEADER *pFileHeader)
{
	char fileName[6];

	// ファイルがオープン中ならクローズ
	if (file.m_hFile != CFile::hFileNull)
		file.Close();

	// 保存ファイル名を編集
	memcpy(fileName, pFileHeader->seqNo, 3);
	fileName[3] = '-';
	fileName[4] = pFileHeader->state[0];
	fileName[5] = '\0';

	// 保存ファイルをオープン
	file.Open(sSaveFolder + "\\" + fileName + ".DAT", CFile::modeCreate | CFile::modeWrite | CFile::shareExclusive);

	// ファイルにヘッダを書き込み
	file.Write(&pFileHeader->pamHeader, sizeof(PAM_HEADER));

	// ダイアログにデータ受信を送信
	SendNotify(NOTIFY_RECVCOUNT, (LPARAM)fileName);
}

//===================================================================
//
//	機能　：PAMデータフレーム受信処理
//
//	引数　：pPamData - PAM受信データ
//
//	戻り値：無し
//
//===================================================================
static void ReceiveData(PAM_DATA *pPamData)
{
	// ファイルがオープンされていなければ何もしない
	if (file.m_hFile == CFile::hFileNull)
		return;

	// ファイルにデータを書き込み
	file.Write(pPamData, sizeof(PAM_DATA));
}

//===================================================================
//
//	機能　：データ終了フレーム受信処理
//
//	引数　：無し
//
//	戻り値：無し
//
//===================================================================
static void EndData()
{
	// ファイルがオープンされていなければ何もしない
	if (file.m_hFile == CFile::hFileNull)
		return;

	// ファイルにデータを書き込み
	file.Close();
}

//===================================================================
//
//	機能　：フレーム送信処理
//
//	引数　：pData - 送信データ
//			len - 送信データ長
//
//	戻り値：TRUE - 正常
//			FALSE - 送信失敗
//
//===================================================================
static BOOL PutFrame(PBYTE pData, int len)
{
	BYTE sendBuf[BUFSIZE];
	PBYTE pBuf;
	BYTE bcc;
	int i;

	// 送信バッファにSTX,データ,ETXをセット
	pBuf = sendBuf;
	*pBuf++ = CODE_STX;
	bcc = CODE_STX + CODE_ETX;
	for (i = 0; i < len; i++) {
		bcc += *pData;
		pBuf = SetFrame(pBuf, *pData++);
	}
	*pBuf++ = CODE_ETX;

	// チェックサムをセット
	*pBuf++ = bcc;

	// データ送信
	return PutComm(sendBuf, pBuf - sendBuf);
}

//===================================================================
//
//	機能　：送信フレームセット
//
//	引数　：pBuf - 送信バッファ
//			c - 送信データ
//
//	戻り値：送信バッファの次のアドレス
//
//===================================================================
static PBYTE SetFrame(PBYTE pBuf, BYTE c)
{
	// STX,ETX,0xff だったら 0xffをセットして反転データをセット
	if (c == CODE_STX || c == CODE_ETX || c == CODE_FF) {
		*pBuf++ = CODE_FF;
		*pBuf++ = ~c;
	} else
		*pBuf++ = c;

	return pBuf;
}

//===================================================================
//
//	機能　：データ送信処理
//
//	引数　：pBuf - 送信データ
//
//	戻り値：TRUE - 正常
//			FALSE - 送信失敗
//
//===================================================================
static BOOL PutComm(PBYTE pBuf, int len)
{
	DWORD numBytes;

	// シリアルポートに書き込み
	WriteFile(handle, pBuf, len, &numBytes, NULL);

	// 全データ書き込めたら正常
	return (numBytes == (DWORD)len) ? TRUE : FALSE;
}

//===================================================================
//
//	機能　：フレーム受信処理
//
//	引数　：pData - 受信フレームバッファ
//
//	戻り値：受信バイト数
//
//===================================================================
static int GetFrame(PBYTE pData)
{
	PBYTE pBuf;
	int c;
	int timeout;
	int state;
	int count;
	BYTE bcc;
	BOOL bCodeFF;
	BOOL bLoopSw;

	// 変数初期化
	timeout = 0;
	bCodeFF = FALSE;
	state = STATE_IDLE;

	// １フレーム分受信するまでループ
	bLoopSw = TRUE;
	while (bLoopSw) {
		// １バイト受信
		c = GetComm();

		// -1 だったら１秒のタイムアウト
		if (c != -1) {
			// 受信状態(state)による処理振り分け
			switch (state) {
			case STATE_IDLE:		// アイドル状態
				// STX を受信したらデータ受信状態に移行
				if (c == CODE_STX) {
					pBuf = pData;
					count = 0;
					bcc = CODE_STX;
					bCodeFF = FALSE;
					state = STATE_DATA;
				}
				break;
			case STATE_DATA:		// データ受信状態
				// ETX を受信するまで受信データをバッファに保存
				if (c == CODE_ETX)
					state = STATE_BCC;
				else {
					// 0xff を受信したら次の文字を反転する
					if (bCodeFF) {
						c ^= 0xff;
						bCodeFF = FALSE;
					} else {
						if (c == CODE_FF) {
							bCodeFF = TRUE;
							break;
						}
					}

					// データサイズチェック
					// バッファサイズを越えた場合はゴミを受信したとみなして捨てる
					if (++count == BUFSIZE)
						state = STATE_IDLE;
					else
						*pBuf++ = c;
				}

				// チェックサムの計算
				bcc += c;
				break;
			case STATE_BCC:			// BCC 受信待ち状態
				// チェックサムをチェック。一致しない場合は NAK を送信
				if (bcc != c) {
					SendResponse(RESPONSE_BCCERR);
					state = STATE_IDLE;
				} else
					bLoopSw = FALSE;
				break;
			}
				
			timeout = 0;
		} else {
			// 規定時間データがこなかったら初期状態に戻す
			state = STATE_IDLE;
		}
	}

	return pBuf - pData;
}

//===================================================================
//
//	機能　：１バイト受信処理
//
//	引数　：無し
//
//	戻り値：受信データ（１秒タイムアウトしたら -1）
//
//===================================================================
static int GetComm()
{
	int	c;
	DWORD	numBytes;

	c = 0;
	ReadFile(handle, &c, 1, &numBytes, NULL);
	if (numBytes == 0)
		c = -1;

	return c;
}

//===================================================================
//
//	機能　：レスポンス送信処理
//
//	引数　：response - 送信レスポンス
//
//	戻り値：無し
//
//===================================================================
static void SendResponse(int response)
{
	BYTE sendBuf[2];

	// レスポンスを編集
	sendBuf[0] = FRAME_RESPONSE;
	sendBuf[1] = (BYTE)response;

	// 送信
	PutFrame(sendBuf, sizeof(sendBuf));
}

//===================================================================
//
//	機能　：ダイアログにメッセージ通知処理
//
//	引数　：wParam - メッセージパラメータ１
//			lParam - メッセージパラメータ２
//
//	戻り値：無し
//
//===================================================================
static void SendNotify(WPARAM wParam, LPARAM lParam)
{
	if (pDlgWnd != NULL)
		pDlgWnd->SendMessage(WM_RECV_NOTIFY, wParam, lParam);
}
