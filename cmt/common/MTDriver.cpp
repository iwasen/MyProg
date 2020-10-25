// MTDriver.cpp: CMTDriver クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "MTDriver.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

#define RETRY_READY		10			// Ready のリトライ回数

// VOL1 ラベル
struct VOL1 {
	char	LabelID[4];				// ラベル識別名
	char	VolumeID[6];			// ボリューム識別名
	char	Reserved1[31];			// 未使用
	char	Owner[10];				// 所有者
	char	Reserved2[29];			// 未使用
};

// HDR1,EOF1,EOV1 ラベル
struct LABEL1 {
	char	LabelID[4];				// ラベル識別名
	char	FileID[17];				// ファイル識別名
	char	VolumeID[6];			// ボリューム識別名
	char	VolSeqNo[4];			// ボリューム順序番号
	char	FileSeqNo[4];			// ファイル順序番号
	char	GenerationNo[4];		// 世代番号
	char	VersionNo[2];			// バージョン番号
	char	CreationDate[6];		// 作成日付
	char	ExpirationDate[6];		// 満了日付
	char	Accessibility[1];		// 機密保護表示
	char	BlockCount[6];			// ブロック数
	char	SystemCode[13];			// システム識別名
	char	Reserved1[7];			// 未使用
};

// HDR2,EOF2,EOV2 ラベル
struct LABEL2 {
	char	LabelID[4];				// ラベル識別名
	char	RecordFormat[1];		// レコード形式
	char	BlockLength[5];			// ブロック長
	char	RecordLength[5];		// レコード長
	char	Density[1];				// 記録密度
	char	VolumeStatus[1];		// ボリューム状態
	char	JobStep[17];			// ジョブ／ステップ名
	char	Compressed[2];			// 記録方式
	char	ControlChar[1];			// 制御文字
	char	Reserved1[1];			// 未使用
	char	Blocking[1];			// ブロック化
	char	Reserved2[11];			// 未使用
	char	BufferOffet[2];			// バッファオフセット
	char	Reserved3[28];			// 未使用
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CMTDriver::CMTDriver()
{
	m_hHandle = -1;
	m_nErrorCode = 0;
	m_nReadAhead = 0;
	m_pUpdateWnd = NULL;
	m_bBOT = TRUE;
}

CMTDriver::~CMTDriver()
{
	Close();
}

//
//	機能	：　MTオープン処理
//
//	引数	：	nHAdapter - ホストアダプタ番号
//				nScsiID - SCSI ID
//				nScsiLUN - SCSI LUN
//
//	復帰値	：	結果コード
//
//	機能説明：	MT装置のオープンを行う。
//
//	備考	：	なし
//
int CMTDriver::Open(int nHAdapter, int nScsiID, int nScsiLUN)
{
	int nResult;

	nResult = MT_open(nHAdapter, nScsiID, nScsiLUN);
	if (nResult >= 0) {
		m_hHandle = nResult;
		nResult = 0;
	} else
		m_nErrorCode = nResult;

	return nResult;
}

//
//	機能	：　MTクローズ処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MT装置のクローズを行う。
//
//	備考	：	無し
//
int CMTDriver::Close()
{
	int nResult;

	if (m_hHandle != -1) {
		nResult = MT_close(m_hHandle);
		m_hHandle = -1;
	} else
		nResult = 0;

	return nResult;
}

//
//	機能	：　エラー取得処理
//
//	引数	：	sErrorMsg - エラーメッセージ
//
//	復帰値	：	エラーコード
//
//	機能説明：	エラーコードとエラーメッセージを取得する。
//
//	備考	：	無し
//
int CMTDriver::GetError(CString &sErrorMsg)
{
	int nErrorCode;
	BYTE msg[256 + 1];

	nErrorCode = m_nErrorCode;
	MT_message2(nErrorCode, msg);
	sErrorMsg = msg;

	m_nErrorCode = 0;

	return nErrorCode;
}

//
//	機能	：　アンロード処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MTをアンロードする。
//
//	備考	：	無し
//
int CMTDriver::Unload()
{
	int nResult;

	// アンロード
	if ((nResult = MT_unload(m_hHandle, MT_OPERATE_STD, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	機能	：　リワインド処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MTをリワインドする。
//
//	備考	：	無し
//
int CMTDriver::Rewind()
{
	int nResult;

	// リワインド
	if ((nResult = MT_rewind(m_hHandle, MT_OPERATE_STD, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	機能	：　ファイルマーク書き込み処理
//
//	引数	：	nCount - 書き込むファイルマークの数
//
//	復帰値	：	結果コード
//
//	機能説明：	MTに指定された数だけファイルマークを書き込む。
//
//	備考	：	無し
//
int CMTDriver::WriteFmk(int nCount)
{
	int nResult;

	// ファイルマーク書き込み
	if ((nResult = MT_writefmk(m_hHandle, nCount, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	機能	：　１ブロック読み込み処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MTからバッファに１ブロック読み込む。
//
//	備考	：	無し
//
int CMTDriver::Read()
{
	int nResult;

	// １ブロック読み込み
	if ((nResult = MT_read(m_hHandle, m_IOBuf, BUF_SIZE, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	機能	：　１ブロック書き込み処理
//
//	引数	：	nDataLen - 書き込むブロック長
//
//	復帰値	：	結果コード
//
//	機能説明：	MTにバッファから１ブロック書き込む。
//
//	備考	：	無し
//
int CMTDriver::Write(int nDataLen)
{
	int nResult;

	// １ブロック書き込み
	if ((nResult = MT_write(m_hHandle, m_IOBuf, nDataLen, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	機能	：　ボリューム識別名取得処理
//
//	引数	：	sVolLabel - 取得されたボリューム識別名
//
//	復帰値	：	結果コード
//
//	機能説明：	MTからボリューム識別名を取得する。
//
//	備考	：	無し
//
int CMTDriver::ReadVolumeLabel(CString &sVolLabel)
{
	int nResult;
	VOL1 *pVol1;

	// Ready 待ち
	if ((nResult = WaitReady()) != 0)
		return nResult;

	// リワインド
	if ((nResult = Rewind()) != 0)
		return nResult;

	// １ブロック(VOL1)読み込み
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// ラベル識別名チェック
	pVol1 = (VOL1 *)m_IOBuf;
	if (strncmp(pVol1->LabelID, "VOL1", 4) == 0)
		sVolLabel = CString(pVol1->VolumeID, sizeof(pVol1->VolumeID));
	else
		sVolLabel = "";

	return 0;
}

//
//	機能	：　書き込み開始処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MTに書き込みを行う際の初期処理。
//
//	備考	：	無し
//
int CMTDriver::WriteStart()
{
	// ファイルがクローズされていなかったらクローズ
	if (m_File.m_hFile != CFile::hFileNull)
		m_File.Close();

	m_bBOT = TRUE;
	m_nVolSeqNo = 1;
	m_nFileSeqNo = 1;

	return 0;
}

//
//	機能	：　書き込み終了処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MTに書き込終了時の後処理。
//
//	備考	：	無し
//
int CMTDriver::WriteEnd()
{
	int nResult;

	WaitReady();

	// BOT位置に無ければテープマーク書き込み
	if (!m_bBOT) {
		if ((nResult = WriteFmk(1)) != 0 && nResult != MT_FOUND_EOM)
			return nResult;
	}

	// アンロード
	Unload();

	return 0;
}

//
//	機能	：　ラベル有りでファイル書き込み処理
//
//	引数	：	pFileName - 書き込むディスク上のファイル名
//				pFileID - ファイル識別名
//				pSystemID - システム識別名
//				nRecordLength - レコード長
//				nBlockingFactor - ブロック化係数
//
//	復帰値	：	結果コード
//
//	機能説明：	指定されたファイルの内容をMTにラベル有りで書き込む。
//
//	備考	：	無し
//
int CMTDriver::WriteFileWithLabel(LPCTSTR pFileName, LPCTSTR pFileID, LPCTSTR pSystemID, int nRecordLength, int nBlockingFactor)
{
	int nResult, nResult2;
	VOL1 *pVol1;

	m_sFileID = pFileID;
	m_sSystemID = pSystemID;
	m_nRecordLength = nRecordLength;
	m_nBlockingFactor = nBlockingFactor;
	m_nBlockLength = nRecordLength * nBlockingFactor;
	m_nBlockCount = 0;

	if (m_File.m_hFile == CFile::hFileNull) {
		if (!m_File.Open(pFileName, CFile::modeRead | CFile::shareDenyWrite))
			return RESULT_FILE_ERROR;

		m_sVolumeID = m_sVolumeID2;
		m_cVolumeStatus = '0';
	} else
		m_cVolumeStatus = '1';

	if (m_bBOT) {
		// Ready 待ち
		if ((nResult = WaitReady()) != 0)
			return nResult;

		// リワインド
		if ((nResult = Rewind()) != 0)
			return nResult;

		// １ブロック(VOL1)読み込み
		if ((nResult = ReadConvert()) < 0)
			return nResult;

		// ボリューム識別名を保存
		pVol1 = (VOL1 *)m_IOBuf;
		m_sVolumeID2 = CString(pVol1->VolumeID, sizeof(pVol1->VolumeID));
		if (m_nVolSeqNo == 1)
			m_sVolumeID = m_sVolumeID2;

		m_bBOT = FALSE;
	}

	// HDR1書き込み
	if ((nResult = WriteLabel1("HDR1")) != 0)
		return nResult;

	// HDR2書き込み
	if ((nResult = WriteLabel2("HDR2")) != 0)
		return nResult;

	// テープマーク書き込み
	if ((nResult = WriteFmk(1)) != 0)
		return nResult;

	// ファイルコピー
	if ((nResult = WriteFileToMT()) < 0)
		return nResult;

	// テープマーク書き込み
	if ((nResult2 = WriteFmk(1)) != 0 && nResult2 != MT_FOUND_EOM)
		return nResult2;

	if (nResult == RESULT_FILE_END) {
		// EOF1書き込み
		if ((nResult2 = WriteLabel1("EOF1")) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// EOF2書き込み
		if ((nResult2 = WriteLabel2("EOF2")) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// テープマーク書き込み
		if ((nResult2 = WriteFmk(1)) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// コピー元ファイルクローズ
		m_File.Close();

		// ファイル順序番号更新
		m_nFileSeqNo++;
	} else if (nResult == RESULT_VOLUME_END) {
		// EOV1書き込み
		if ((nResult2 = WriteLabel1("EOV1")) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// EOV2書き込み
		if ((nResult2 = WriteLabel2("EOV2")) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// テープマーク書き込み
		if ((nResult2 = WriteFmk(2)) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// アンロード
		if ((nResult2 = Unload()) != 0)
			return nResult2;

		// ボリューム順序番号更新
		m_nVolSeqNo++;

		m_bBOT = TRUE;
	}

	return nResult;
}

//
//	機能	：　ラベル無しでファイル書き込み処理
//
//	引数	：	pFileName - 書き込むディスク上のファイル名
//				nRecordLength - レコード長
//				nBlockingFactor - ブロック化係数
//
//	復帰値	：	結果コード
//
//	機能説明：	指定されたファイルの内容をMTにラベル無しで書き込む。
//
//	備考	：	無し
//
int CMTDriver::WriteFileNonLabel(LPCTSTR pFileName, int nRecordLength, int nBlockingFactor)
{
	int nResult, nResult2;

	// ブロック長設定
	m_nBlockLength = nRecordLength * nBlockingFactor;

	// コピー元ファイルオープン
	if (m_File.m_hFile == CFile::hFileNull) {
		if (!m_File.Open(pFileName, CFile::modeRead | CFile::shareDenyWrite))
			return RESULT_FILE_ERROR;
	}

	if (m_bBOT) {
		// Ready 待ち
		if ((nResult = WaitReady()) != 0)
			return nResult;

		// リワインド
		if ((nResult = Rewind()) != 0)
			return nResult;

		m_bBOT = FALSE;
	}

	// ファイルコピー
	if ((nResult = WriteFileToMT()) < 0)
		return nResult;

	if (nResult == RESULT_FILE_END) {
		// テープマーク書き込み
		if ((nResult2 = WriteFmk(1)) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// コピー元ファイルクローズ
		m_File.Close();
	} else if (nResult == RESULT_VOLUME_END) {
		// テープマーク書き込み
		if ((nResult2 = WriteFmk(2)) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// アンロード
		if ((nResult2 = Unload()) != 0)
			return nResult2;

		m_bBOT = TRUE;
	}

	return nResult;
}

//
//	機能	：　ラベル有りでファイル読み込み処理
//
//	引数	：	pDirName - 書き込むディスク上のディレクトリ名
//
//	復帰値	：	結果コード
//
//	機能説明：	ラベル有りのMTからファイルを読み込み、指定されたディレクトリに書き込む。
//
//	備考	：	無し
//
int CMTDriver::ReadFileWithLabel(LPCTSTR pDirName)
{
	int nResult, nResult2;
	VOL1 *pVol1;
	CString sFileID;
	CString sFileName;

	// リワインド
	if ((nResult = Rewind()) != 0)
		return nResult;

	// １ブロック(VOL1)読み込み
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// "VOL1"のチェック
	pVol1 = (VOL1 *)m_IOBuf;
	if (CString(pVol1->LabelID, sizeof(pVol1->LabelID)) != "VOL1")
		return RESULT_MT_ERROR;

	while (true) {
		// ヘッダ（HDR1,HDR2,TM)チェック
		if ((nResult = CheckHeader(sFileID)) == MT_FOUND_FMK) {
			nResult = 0;
			break;
		} else if (nResult != 0)
			return nResult;

		// コピー先ファイルオープン
		sFileName = (CString)pDirName + "\\" + sFileID;
		if (!m_File.Open(sFileName, CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareExclusive))
			return RESULT_FILE_ERROR;
		m_File.Seek(0, CFile::end);

		// ファイルコピー
		if ((nResult = ReadFileFromMT()) != 0) {
			m_File.Close();
			return nResult;
		}

		// コピー先ファイルクローズ
		m_File.Close();

		// EOF or EOV チェック
		if ((nResult = CheckEOForEOV()) < 0)
			return nResult;

		// EOVならループを抜ける
		if (nResult == RESULT_VOLUME_END)
			break;
	}

	// アンロード
	if ((nResult2 = Unload()) != 0)
		return nResult2;

	return nResult;
}

//
//	機能	：　ラベル無しでファイル読み込み処理
//
//	引数	：	pFileName - 書き込むディスク上のファイル名
//
//	復帰値	：	結果コード
//
//	機能説明：	ラベル無しのMTからファイルを読み込み、指定されたファイルに書き込む。
//
//	備考	：	無し
//
int CMTDriver::ReadFileNonLabel(LPCTSTR pFileName)
{
	int nResult;

	if (m_bBOT) {
		// Ready 待ち
		if ((nResult = WaitReady()) != 0)
			return nResult;

		// リワインド
		if ((nResult = Rewind()) != 0)
			return nResult;

		m_bBOT = FALSE;
	}

	// コピー先ファイルオープン
	if (!m_File.Open(pFileName, CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareExclusive))
		return RESULT_FILE_ERROR;
	m_File.Seek(0, CFile::end);

	// ファイルコピー
	if ((nResult = ReadFileFromMT()) != 0) {
		m_File.Close();
		return nResult;
	}

	// コピー先ファイルクローズ
	m_File.Close();

	// テープマークのチェック
	if ((nResult = Read()) == MT_FOUND_FMK || nResult == MT_KEY_MEDIUMERR) {
		// アンロード
		if ((nResult = Unload()) != 0)
			return nResult;

		// ボリューム終了
		m_bBOT = TRUE;
		nResult = RESULT_VOLUME_END;
	} else if (nResult < 0) {
		// I/Oエラー
		return nResult;
	} else {
		// 先読みデータ保存
		m_nReadAhead = nResult;

		// ファイル終了
		nResult = RESULT_FILE_END;
	}

	return nResult;
}

//
//	機能	：　ヘッダ(HDR1,HDR2,TM)のチェック処理
//
//	引数	：	sFileID - ヘッダに書かれたファイル識別名
//
//	復帰値	：	結果コード
//
//	機能説明：	ラベル有りのMTからヘッダを読み込む。
//
//	備考	：	無し
//
int CMTDriver::CheckHeader(CString &sFileID)
{
	int nResult;
	LABEL1 *pLabel1;
	LABEL2 *pLabel2;

	// １ブロック(HDR1)読み込み
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// "HDR1"のチェック
	pLabel1 = (LABEL1 *)m_IOBuf;
	if (CString(pLabel1->LabelID, sizeof(pLabel1->LabelID)) != "HDR1")
		return RESULT_MT_ERROR;

	// ファイル識別名保存
	sFileID = CString(pLabel1->FileID, sizeof(pLabel1->FileID));
	sFileID.TrimRight();

	// １ブロック(HDR2)読み込み
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// "HDR2"のチェック
	pLabel2 = (LABEL2 *)m_IOBuf;
	if (CString(pLabel2->LabelID, sizeof(pLabel2->LabelID)) != "HDR2")
		return RESULT_MT_ERROR;

	// テープマークチェック
	if ((nResult = CheckTapeMark()) != 0)
		return nResult;

	return 0;
}

//
//	機能	：　EOFまたはEOVのチェック処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	ラベル有りのMTからEOFまたはEOVを読み込む。
//
//	備考	：	無し
//
int CMTDriver::CheckEOForEOV()
{
	int nResult;
	LABEL1 *pLabel1;
	LABEL2 *pLabel2;
	CString sHeader;

	// １ブロック(EOF1orEOV1)読み込み
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// "EOF1"or"EOV1"のチェック
	pLabel1 = (LABEL1 *)m_IOBuf;
	sHeader = CString(pLabel1->LabelID, sizeof(pLabel1->LabelID));
	if (sHeader == "EOF1") {
		// １ブロック(EOF2)読み込み
		if ((nResult = ReadConvert()) < 0)
			return nResult;

		// "EOF2"のチェック
		pLabel2 = (LABEL2 *)m_IOBuf;
		if (CString(pLabel2->LabelID, sizeof(pLabel2->LabelID)) != "EOF2")
			return RESULT_MT_ERROR;

		// テープマークチェック
		if ((nResult = CheckTapeMark()) != 0)
			return nResult;

		return RESULT_FILE_END;
	} else if (sHeader == "EOV1") {
		// １ブロック(EOV2)読み込み
		if ((nResult = ReadConvert()) < 0)
			return nResult;

		// "EOV2"のチェック
		pLabel2 = (LABEL2 *)m_IOBuf;
		if (CString(pLabel2->LabelID, sizeof(pLabel2->LabelID)) != "EOV2")
			return RESULT_MT_ERROR;

		return RESULT_VOLUME_END;
	} else
		return RESULT_MT_ERROR;
}

//
//	機能	：　Ready待ち処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MT装置がReadyになるのを待つ。
//
//	備考	：	無し
//
int CMTDriver::WaitReady()
{
	int nResult;
	int i;

	// リトライループ
	for (i = 0; i < RETRY_READY; i++) {
		// Readyチェック
		if ((nResult = MT_ready(m_hHandle, &m_StatusData)) == 0)
			break;

		// １秒待つ
		Sleep(1000);
	}

	if (nResult < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	機能	：　ファイルからMTにコピー処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	ファイルからMTに１ブロックずつコピーする。
//
//	備考	：	無し
//
int CMTDriver::WriteFileToMT()
{
	int nResult;
	int nRead;

	// ファイルから１ブロックずつ読んでCMTに書く
	while ((nRead = m_File.Read(m_IOBuf, m_nBlockLength)) != 0) {
		m_nBlockCount++;
		if ((nResult = Write(nRead)) < 0) {
			if (nResult == MT_FOUND_EOM) {
				//m_File.Seek(-nRead, CFile::current);
				return RESULT_VOLUME_END;
			} else
				return nResult;
		}

		// コピー中のウィンドウ更新
		if (m_pUpdateWnd != NULL)
			m_pUpdateWnd->UpdateWindow();
	}

	return RESULT_FILE_END;
}

//
//	機能	：　MTからファイルにコピー処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MTからファイルに１ブロックずつコピーする。
//
//	備考	：	無し
//
int CMTDriver::ReadFileFromMT()
{
	int nResult;

	// CMTから１ブロックずつ読んでファイルに書く
	while (true) {
		if (m_nReadAhead == 0) {
			// CMTから１ブロック読み込み
			if ((nResult = Read()) < 0)
				break;
		} else {
			// 先読みしたデータを書く
			nResult = m_nReadAhead;
			m_nReadAhead = 0;
		}

		try {
			m_File.Write(m_IOBuf, nResult);
		} catch (CFileException *fe) {
			fe->Delete();
			return RESULT_FILE_ERROR;
		}

		// コピー中のウィンドウ更新
		if (m_pUpdateWnd != NULL)
			m_pUpdateWnd->UpdateWindow();

	}

	if (nResult != MT_FOUND_FMK)
		return nResult;

	return 0;
}

//
//	機能	：　HDR1,EOF1,EOV1書き込み処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	HDR1またはEOF1またはEOV1をMTに書き込む。
//
//	備考	：	無し
//
int CMTDriver::WriteLabel1(LPCTSTR pLabelID)
{
	LABEL1 *pLabel;
	int nResult;

	// ラベル１バッファスペースクリア
	pLabel = (LABEL1 *)m_IOBuf;
	memset(pLabel, ' ', sizeof(LABEL1));

	// 各項目のセット
	SetString(pLabel->LabelID, pLabelID);
	SetString(pLabel->FileID, m_sFileID);
	SetString(pLabel->VolumeID, m_sVolumeID);
	SetNumber(pLabel->VolSeqNo, m_nVolSeqNo, sizeof(pLabel->VolSeqNo));
	SetNumber(pLabel->FileSeqNo, m_nFileSeqNo, sizeof(pLabel->FileSeqNo));
	//SetString(pLabel->GenerationNo, "0000");
	// takazawa 20001031
	//SetString(pLabel->VersionNo, "00");
	SetSystemDate(pLabel->CreationDate);
	SetString(pLabel->ExpirationDate, " 00000");
	pLabel->Accessibility[0] = '0';
	SetNumber(pLabel->BlockCount, m_nBlockCount % 1000000, sizeof(pLabel->BlockCount));
	SetString(pLabel->SystemCode, m_sSystemID);

	// CMTに書き込み
	if ((nResult = WriteConvert(sizeof(LABEL1))) < 0)
		return nResult;

	return 0;
}

//
//	機能	：　HDR2,EOF2,EOV2書き込み処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	HDR2またはEOF2またはEOV2をMTに書き込む。
//
//	備考	：	無し
//
int CMTDriver::WriteLabel2(LPCTSTR pLabelID)
{
	LABEL2 *pLabel;
	int nResult;

	// ラベル２バッファスペースクリア
	pLabel = (LABEL2 *)m_IOBuf;
	memset(pLabel, ' ', sizeof(LABEL2));

	// 各項目のセット
	SetString(pLabel->LabelID, pLabelID);
	pLabel->RecordFormat[0] = 'F';
	SetNumber(pLabel->BlockLength, m_nBlockLength, sizeof(pLabel->BlockLength));
	SetNumber(pLabel->RecordLength, m_nRecordLength, sizeof(pLabel->RecordLength));
	pLabel->VolumeStatus[0] = m_cVolumeStatus;
	SetString(pLabel->JobStep, "Mt_Sys/Mt_Ctl");
	pLabel->Blocking[0] = (m_nRecordLength == m_nBlockLength) ? ' ' : 'B';

	// CMTに書き込み
	if ((nResult = WriteConvert(sizeof(LABEL2))) < 0)
		return nResult;

	return 0;
}

//
//	機能	：　文字列セット処理
//
//	引数	：	pBuf - バッファ
//				pStr - セットする文字列
//
//	復帰値	：	無し
//
//	機能説明：	pBufにpStrの長さだけコピーする。（終端の'\0'は無し）
//
//	備考	：	無し
//
void CMTDriver::SetString(LPTSTR pBuf, LPCTSTR pStr)
{
	strncpy(pBuf, pStr, strlen(pStr));
}

//
//	機能	：　数値セット処理
//
//	引数	：	pBuf - バッファ
//				nNum - セットする数値
//				nLength - 長さ
//
//	復帰値	：	無し
//
//	機能説明：	数値を 9999 形式でバッファにセットする。
//
//	備考	：	無し
//
void CMTDriver::SetNumber(LPTSTR pBuf, int nNum, int nLength)
{
	CString sFmt, sNum;

	sFmt.Format("%%0%dd", nLength);
	sNum.Format(sFmt, nNum);
	strncpy(pBuf, sNum, nLength);
}

//
//	機能	：　システム日付セット処理
//
//	引数	：	pBuf - バッファ
//
//	復帰値	：	無し
//
//	機能説明：	バッファにシステム日付をセットする。
//
//	備考	：	無し
//
void CMTDriver::SetSystemDate(LPTSTR pBuf)
{
	CTime t = CTime::GetCurrentTime();
	CTimeSpan ts = t - CTime(t.GetYear(), 1, 1, 0, 0, 0);
	CString sDate;

	sDate.Format("%03d%03d", t.GetYear() % 1000, ts.GetDays() + 1);
	// takazawa
	strncpy(pBuf,sDate,6);
}

//
//	機能	：　１ブロック読み込み＆コード変換処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	MTから１ブロック読み込み、それをEBCDICからJISに変換する。
//
//	備考	：	無し
//
int CMTDriver::ReadConvert()
{
	int nResult;

	// １ブロック読み込み
	if ((nResult = Read()) < 0)
		return nResult;

	// JISコードに変換
	EbcdicToJis(m_IOBuf, nResult);

	return nResult;
}

//
//	機能	：　コード変換＆１ブロック書き込み処理
//
//	引数	：	nBufSize - 書き込むブロック長
//
//	復帰値	：	結果コード
//
//	機能説明：	バッファの内容をJISからEBCDICに変換し、それをMTに書き込む。
//
//	備考	：	無し
//
int CMTDriver::WriteConvert(int nBufSize)
{
	// EBCDICコードに変換
	JisToEbcdic(m_IOBuf, nBufSize);

	// CMTに書き込み
	return Write(nBufSize);
}

//
//	機能	：　テープマークチェック処理
//
//	引数	：	無し
//
//	復帰値	：	結果コード
//
//	機能説明：	次にテープマークが存在するかチェックする。
//
//	備考	：	無し
//
int CMTDriver::CheckTapeMark()
{
	int nResult;

	// １ブロック読み込み
	if ((nResult = Read()) != MT_FOUND_FMK) {
		if (nResult < 0)
			return nResult;
		else
			return RESULT_MT_ERROR;
	}

	return 0;
}

//
//	機能	：　EBCDICからJISにコード変換処理
//
//	引数	：	buf - バッファ
//				len - buf にデータ長
//
//	復帰値	：	無し
//
//	機能説明：	バッファの内容をEBCDICからJISにコード変換する。
//
//	備考	：	無し
//
void CMTDriver::EbcdicToJis(BYTE *buf, int len)
{
	MT_convert(MT_CONV_EBCDIK_A8, buf, len);
}

//
//	機能	：　JISからEBCDICにコード変換処理
//
//	引数	：	buf - バッファ
//				len - buf にデータ長
//
//	復帰値	：	無し
//
//	機能説明：	バッファの内容をJISからEBCDICにコード変換する。
//
//	備考	：	無し
//
void CMTDriver::JisToEbcdic(BYTE *buf, int len)
{
	MT_convert(MT_CONV_A8_EBCDIK, buf, len);
}
