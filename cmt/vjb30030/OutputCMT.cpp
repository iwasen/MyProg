// OutputCMT.cpp: COutputCMT クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputCMT.h"
#include <ctype.h>

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

COutputCMT::COutputCMT()
{
	int nHAdapter, nScsiID, nScsiLUN;

	m_bLabelCheck = FALSE;

	// INIファイルよりCMTの設定情報取得
	nHAdapter = GetIniData("CMT", "HAdapter", 0);
	nScsiID = GetIniData("CMT", "ScsiID", 0);
	nScsiLUN = GetIniData("CMT", "ScsiLUN", 0);
	m_sSystemID = GetIniData("CMT", "SystemID", "HITACVOS3");

	// CMT装置オープン
	if (m_MTDriver.Open(nHAdapter, nScsiID, nScsiLUN) == 0)
		m_bOpen = TRUE;
	else
		m_bOpen = FALSE;
}

COutputCMT::~COutputCMT()
{
	// CMT装置クローズ
	if (m_bOpen)
		m_MTDriver.Close();
}

//
//	機能	：	ボリュームラベルチェック処理
//
//	引数	：	pQueue - キュー情報
//
//	復帰値	：	結果コード
//
//	機能説明：	外部媒体のボリュームラベルをチェックする。
//
//	備考	：	無し
//
int COutputCMT::CheckVolumeName(Queue *pQueue)
{
	// CMT装置がオープンされていなければエラー
	if (!m_bOpen)
		return RC_IOERROR;

	m_bLabelCheck = TRUE;

	return RC_NORMAL;
}

//
//	機能	：	外部媒体出力処理
//
//	引数	：	pQueue - キュー情報
//
//	復帰値	：	結果コード
//
//	機能説明：	外部媒体にファイルを出力する。
//
//	備考	：	無し
//
int COutputCMT::OutputFiles(Queue *pQueue)
{
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	int i;
	char (*pFileBuf)[LEN_FILENAME];
	int nFileCount;
	CString sVolLabel[MAX_VOLUME_LABEL];
	int nVolLabel;
	int nResult;

	// CMT装置がオープンされていなければエラー
	if (!m_bOpen)
		return RC_IOERROR;

	// カンマで区切られたボリュームラベルを分離
	nVolLabel = SplitVolumeLabel(pQueue, sVolLabel);

	// ファイル数取得＆ファイル名チェック
	nFileCount = 0;
	hdir = FindFirstFile(CreatePathName(m_sSrcDir, "*"), &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
				if (!CheckFileName(findBuf.cFileName)) {
					FindClose(hdir);
					DispMessage(IDS_MSG_ILLEGAL_FILENAME, MB_OK, TRUE, findBuf.cFileName);
					return RC_ILLEGALFILENAME;
				}
				nFileCount++;
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	// ファイル名バッファ確保
	pFileBuf = new char[nFileCount][LEN_FILENAME];

	// ファイル名をファイル名バッファに格納
	i = 0;
	hdir = FindFirstFile(CreatePathName(m_sSrcDir, "*"), &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
				strcpy(pFileBuf[i++], findBuf.cFileName);
				if (i >= nFileCount)
					break;
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	// ファイル名末尾の "n" でソート
	qsort(pFileBuf, nFileCount, LEN_FILENAME, CompareFileName);

	// CMT書き込み処理
	nResult = WriteCMT(m_sSrcDir, pFileBuf, nFileCount, sVolLabel, nVolLabel);

	// ファイル名バッファ開放
	delete [] pFileBuf;

	return nResult;
}

//
//	機能	：	CMT出力処理
//
//	引数	：	sSrcDir - コピー元ディレクトリ
//				pFileBuf - 出力ファイル名バッファ
//				nFileCount - 出力ファイル数
//				sVolLabel - ボリュームラベル
//				nVolLabel - ボリュームラベル数
//
//	復帰値	：	結果コード
//
//	機能説明：	CMTにファイルを出力する。
//
//	備考	：	無し
//
int COutputCMT::WriteCMT(CString &sSrcDir, char (*pFileBuf)[LEN_FILENAME], int nFileCount, CString *sVolLabel, int nVolLabel)
{
	CString sSrcFile;
	BOOL bBOT;
	CString sReadVolLabel;
	CString sFileID;
	CString sMsg;
	int nRecordLength;
	int nBlockingFactor;
	int nVolCount;
	int i;
	int nResult;

	// CMT書き込み開始処理
	if (m_MTDriver.WriteStart() != 0) {
		return RC_IOERROR;
	}

	// CMT書き込み処理
	bBOT = TRUE;
	nVolCount = 0;
	for (i = 0; i < nFileCount; i++) {
		// ファイル名からファイル識別名、レコード長、ブロック化係数を分離
		if (!AnalyzeFileName(pFileBuf[i], &sFileID, &nRecordLength, &nBlockingFactor))
			continue;

		// コピー元ファイルのパス名取得
		sSrcFile = CreatePathName(sSrcDir, pFileBuf[i]);

		// １ファイル書き込み処理
		while (TRUE) {
			if (bBOT) {
				// 媒体セットメッセージ
				if (nVolCount < nVolLabel) {
					if (DispMessage(IDS_MSG_DEVICE_SET2, MB_OKCANCEL, FALSE,
							"CMT", sVolLabel[nVolCount]) == IDCANCEL)
					return RC_CANCEL;
				} else {
					if (DispMessage(IDS_MSG_DEVICE_SET, MB_OKCANCEL, FALSE,
							"CMT") == IDCANCEL)
					return RC_CANCEL;
				}

				// ボリュームラベルチェック
				if (m_bLabelCheck && nVolCount < nVolLabel) {
					while (true) {
						if ((nResult = m_MTDriver.ReadVolumeLabel(sReadVolLabel)) != 0) {
							if (nResult == MT_KEY_NOTREADY) {
								if (DispMessage(IDS_MSG_NOTREADY, MB_RETRYCANCEL, FALSE) == IDCANCEL)
									return RC_CANCEL;
								continue;
							}
							return RC_IOERROR;
						}

						if (sReadVolLabel == "")
							sReadVolLabel = "000000";

						if (sReadVolLabel != sVolLabel[nVolCount]) {
							m_MTDriver.Unload();
							if (DispMessage(IDS_MSG_DIFF_VOLLABEL, MB_RETRYCANCEL, FALSE,
									sReadVolLabel, sVolLabel[nVolCount]) == IDCANCEL) {
								return RC_VOLUMENAME;
							}
						} else
							break;
					}
				}
			}
retry:
			// CMTにファイルの内容を書き込み
			if (sVolLabel[0] == "000000")
				nResult = m_MTDriver.WriteFileNonLabel(sSrcFile, nRecordLength, nBlockingFactor);
			else
				nResult = m_MTDriver.WriteFileWithLabel(sSrcFile, sFileID, m_sSystemID, nRecordLength, nBlockingFactor);

			if (nResult < 0) {
				if (nResult == MT_KEY_NOTREADY) {
					if (DispMessage(IDS_MSG_NOTREADY, MB_RETRYCANCEL, FALSE) == IDCANCEL)
						return RC_CANCEL;
					goto retry;
				}
				return RC_IOERROR;
			} else if (nResult == RESULT_FILE_END) {
				// ファイル書き込み終了
				bBOT = FALSE;
				break;
			} else if (nResult == RESULT_VOLUME_END) {
				// ラベル無しの場合、マルチファイル／マルチボリュームはサポートしない
				if (sVolLabel[0] == "000000" && nFileCount > 1)
					return RC_MULTIFILEVOL;

				nVolCount++;
				bBOT = TRUE;
			}
		}
	}

	// CMT書き込み終了処理
	if (m_MTDriver.WriteEnd() != 0)
		return RC_IOERROR;

	return RC_NORMAL;
}

//
//	機能	：	出力ファイルソート用比較処理
//
//	引数	：	arg1 - 出力ファイル名１
//				arg2 - 出力ファイル名２
//
//	復帰値	：	比較結果
//
//	機能説明：	出力ファイルをソートするために比較する。
//
//	備考	：	無し
//
int COutputCMT::CompareFileName(const void *arg1, const void *arg2)
{
	char *p;
	int n1, n2;

	// ファイル名末尾の "n" を取得
	if ((p = strrchr((const char *)arg1, '.')) != NULL)
		n1 = atoi(p + 1);
	else
		n1 = 0;

	// ファイル名末尾の "n" を取得
	if ((p = strrchr((const char *)arg2, '.')) != NULL)
		n2 = atoi(p + 1);
	else
		n2 = 0;

	return n1 - n2;
}

//
//	機能	：	ボリュームラベル分離処理
//
//	引数	：	pQueue - キュー情報
//				pVolLabel - 分離されたボリュームラベル
//
//	復帰値	：	ボリュームラベル数
//
//	機能説明：	カンマで区切られたボリュームラベルを分離する。
//
//	備考	：	無し
//
int COutputCMT::SplitVolumeLabel(Queue *pQueue, CString *pVolLabel)
{
	int i;
	CString sVolLabel;

	sVolLabel = pQueue->sVolLabel;
	for (i = 0; i < MAX_VOLUME_LABEL; i++) {
		if (sVolLabel.GetLength() == 0)
			break;

		*pVolLabel = sVolLabel.SpanExcluding(",");
		sVolLabel = sVolLabel.Mid(min((*pVolLabel).GetLength() + 1, sVolLabel.GetLength()));
		pVolLabel++;
	}

	return i;
}

//
//	機能	：	出力ファイル名チェック
//
//	引数	：	pFileName - 出力ファイル名
//
//	復帰値	：	正常ならTRUE
//
//	機能説明：　出力ファイル名が正しい形式であるかチェックする。
//
//	備考	：	無し
//
BOOL COutputCMT::CheckFileName(LPCTSTR pFileName)
{
	char cFileName[LEN_FILENAME];
	char *p;

	// ".n" の "." を探す
	strcpy(cFileName, pFileName);
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// n の数字チェック
	*p = '\0';
	if (!IsNumber(p))
		return FALSE;

	// ".ブロック化係数" の "." を探す
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// ブロック化係数の数字チェック
	*p++ = '\0';
	if (!IsNumber(p))
		return FALSE;

	// ".レコード長" の "." を探す
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// レコード長の数字チェック
	*p++ = '\0';
	if (!IsNumber(p))
		return FALSE;

	return TRUE;
}

//
//	機能	：	出力ファイル名解析処理
//
//	引数	：	pFileName - 出力ファイル名
//				pFileID - ファイル識別名
//				pRecordLength - レコード長
//				pBlockingFactor - ブロック化係数
//
//	復帰値	：	正常ならTRUE
//
//	機能説明：	出力ファイルに付けられたレコード長、ブロック化係数を取り出す。
//
//	備考	：	無し
//
BOOL COutputCMT::AnalyzeFileName(LPCTSTR pFileName, CString *pFileID, int *pRecordLength, int *pBlockingFactor)
{
	char cFileName[LEN_FILENAME];
	char *p;

	// ".n" の "." を探す
	strcpy(cFileName, pFileName);
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// ".ブロック化係数" の "." を探す
	*p = '\0';
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// ブロック化係数の取得
	*p++ = '\0';
	*pBlockingFactor = atoi(p);

	// ".レコード長" の "." を探す
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// レコード長の取得
	*p++ = '\0';
	*pRecordLength = atoi(p);

	// ファイル識別名の取得
	*pFileID = cFileName;

	return TRUE;
}

//
//	機能	：　数字チェック処理
//
//	引数	：	pNum - チェックする文字列
//
//	復帰値	：	TRUE - OK, FALSE - NG
//
//	機能説明：	エラーコードとその内容（メッセージ）を取得する。
//
//	備考	：	無し
BOOL COutputCMT::IsNumber(LPCTSTR pNum)
{
	while (*pNum != '\0') {
		if (!isdigit(*pNum++))
			return FALSE;
	}

	return TRUE;
}

//
//	機能	：　エラー取得処理
//
//	引数	：	nErrorCode - エラーコード
//				sErrorMsg - エラー内容
//
//	復帰値	：	無し
//
//	機能説明：	エラーコードとその内容（メッセージ）を取得する。
//
//	備考	：	無し
//
void COutputCMT::GetError(int &nErrorCode, CString &sErrorMsg)
{
	nErrorCode = m_MTDriver.GetError(sErrorMsg);
}
