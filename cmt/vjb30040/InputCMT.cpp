// InputCMT.cpp: CInputCMT クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30040.h"
#include "InputCMT.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CInputCMT::CInputCMT()
{
	int nHAdapter, nScsiID, nScsiLUN;

	// INIファイルよりCMTの設定情報取得
	nHAdapter = GetIniData("CMT", "HAdapter", 0);
	nScsiID = GetIniData("CMT", "ScsiID", 0);
	nScsiLUN = GetIniData("CMT", "ScsiLUN", 0);

	// CMT装置オープン
	if (m_MTDriver.Open(nHAdapter, nScsiID, nScsiLUN) == 0)
		m_bOpen = TRUE;
	else
		m_bOpen = FALSE;
}

CInputCMT::~CInputCMT()
{
	// CMT装置クローズ
	if (m_bOpen)
		m_MTDriver.Close();
}

//
//	機能	：　実行確認メッセージ処理
//
//	引数	：	pParam - 入力パラメータ
//
//	復帰値	：	処理結果コード
//
//	機能説明：	実行確認メッセージを表示し、オペレータの支持を待つ。
//
//	備考	：	無し
//
int CInputCMT::KakuninMessage(Param *pParam)
{
	CString sFirstVolLabel;

	// 最初のボリューム名を取り出す
	sFirstVolLabel = pParam->sVolLabel.SpanExcluding(",");

	if (pParam->bLabel) {
		// ラベル有りの確認メッセージ
		if (DispMessage(IDS_MSG_DEVICE_SET2, MB_OKCANCEL, FALSE,
				pParam->sShubetsuCode, pParam->sShikibetsuCode, sFirstVolLabel) == IDCANCEL)
			return RC_CANCEL;
	} else {
		// ラベル無しの確認メッセージ
		if (DispMessage(IDS_MSG_DEVICE_SET3, MB_OKCANCEL, FALSE,
				pParam->sShubetsuCode, pParam->sShikibetsuCode, sFirstVolLabel) == IDCANCEL)
			return RC_CANCEL;
	}

	return RC_NORMAL;
}

//
//	機能	：　ボリュームラベルチェック処理
//
//	引数	：	pParam - 入力パラメータ
//
//	復帰値	：	処理結果コード
//
//	機能説明：	入力されたボリュームラベルと媒体のボリュームラベルが一致するかチェックする。
//
//	備考	：	無し
//
int CInputCMT::CheckVolumeName(Param *pParam)
{
	CString sFirstVolLabel;
	CString sReadVolLabel;

	// CMT装置がオープンされていなければエラー
	if (!m_bOpen)
		return RC_IOERROR;

	if (pParam->bLabel) {
		// 最初のボリューム名を取り出す
		sFirstVolLabel = pParam->sVolLabel.SpanExcluding(",");

		// CMTからボリューム名を取得
		if (m_MTDriver.ReadVolumeLabel(sReadVolLabel) != 0)
			return RC_IOERROR;

		// 一致チェック
		if (sReadVolLabel != sFirstVolLabel)
			return RC_VOLUMENAME;
	}

	return RC_NORMAL;
}

//
//	機能	：　媒体入力処理
//
//	引数	：	pParam - 入力パラメータ
//
//	復帰値	：	処理結果コード
//
//	機能説明：	媒体からファイルを入力する。
//
//	備考	：	無し
//
int CInputCMT::InputFiles(Param *pParam)
{
	CString sDstDir;
	int nResult;

	// CMT装置がオープンされていなければエラー
	if (!m_bOpen)
		return RC_IOERROR;

	// コピー先ディレクトリ名取得
	sDstDir = CreatePathName(g_sInputDir,
			pParam->sShikibetsuCode + (pParam->bLabel ? pParam->sVolLabel.SpanExcluding(",") : "000000"));

	// コピー先ディレクトリ削除
	DeleteDirectory(sDstDir, FALSE);

	// コピー先ディレクトリ作成
	CreateDirectory(sDstDir, NULL);

	// リフレッシュウィンドウ設定
	m_MTDriver.m_pUpdateWnd = m_pUpdateWnd;

	// ラベル有り無しで処理を分ける
	if (pParam->bLabel)
		nResult = InputFilesWithLabel(pParam, sDstDir);
	else
		nResult = InputFilesNonLabel(pParam, sDstDir);

	return nResult;
}

//
//	機能	：　ラベルあり入力処理
//
//	引数	：	pParam - 入力パラメータ
//				pDirName - 入力先ディレクトリ名
//
//	復帰値	：	処理結果コード
//
//	機能説明：	ラベルありCMTからファイルを入力する。
//
//	備考	：	無し
//
int CInputCMT::InputFilesWithLabel(Param *pParam, LPCTSTR pDirName)
{
	CString sVolLabel;
	CString sCurrentVolLabel;
	CString sReadVolLabel;
	BOOL bFirst = TRUE;
	int nResult;

	sVolLabel = pParam->sVolLabel;
	while (sVolLabel.GetLength() != 0) {
		// カレントボリュームラベル取り出し
		sCurrentVolLabel = sVolLabel.SpanExcluding(",");

		if (bFirst)
			bFirst = FALSE;
		else {
			// ２つめのボリューム以降の確認メッセージ
			if (DispMessage(IDS_MSG_DEVICE_SET2, MB_OKCANCEL, FALSE,
					pParam->sShubetsuCode, pParam->sShikibetsuCode, sCurrentVolLabel) == IDCANCEL)
				return RC_CANCEL;
		}

		while (true) {
			// CMTからボリュームラベル取得
			if (m_MTDriver.ReadVolumeLabel(sReadVolLabel) != 0)
				return RC_IOERROR;

			// ボリュームラベル一致チェック
			if (sReadVolLabel != sCurrentVolLabel) {
				m_MTDriver.Unload();
				if (DispMessage(IDS_MSG_DIFF_VOLLABEL2, MB_RETRYCANCEL, FALSE,
						sReadVolLabel, sCurrentVolLabel) == IDCANCEL) {
					return RC_CANCEL;
				}
			} else
				break;
		}

		// CMTからファイルにコピー
		if ((nResult = m_MTDriver.ReadFileWithLabel(pDirName)) < 0 || nResult == RESULT_FILE_ERROR)
			return RC_IOERROR;
		else if (nResult != RESULT_VOLUME_END)
			break;

		// 次のボリュームラベルに移動
		sVolLabel = sVolLabel.Mid(min(sCurrentVolLabel.GetLength() + 1, sVolLabel.GetLength()));
	}

	return RC_NORMAL;
}


//
//	機能	：　ラベル無し入力処理
//
//	引数	：	pParam - 入力パラメータ
//				pDirName - 入力先ディレクトリ名
//
//	復帰値	：	処理結果コード
//
//	機能説明：	ラベル無しCMTからファイルを入力する。
//
//	備考	：	無し
//
int CInputCMT::InputFilesNonLabel(Param *pParam, LPCTSTR pDirName)
{
	CString sVolLabel;
	CString sCurrentVolLabel;
	CString sFileName;
	CString sTemp;
	int nRecordLength;
	int nVolume;
	int nNextPos;
	BOOL bBOT = FALSE;
	int nVolCount = 0;
	BOOL bLoop = TRUE;
	int i;
	int nResult;

	sVolLabel = pParam->sVolLabel;
	while (sVolLabel.GetLength() != 0 && bLoop) {
		// ファイル名／レコード長／本数を取り出し
		sCurrentVolLabel = sVolLabel.SpanExcluding(",");

		// ファイル名／レコード長／本数を分解
		sFileName = sCurrentVolLabel.SpanExcluding("/");
		nNextPos = sFileName.GetLength() + 1;
		sTemp = sCurrentVolLabel.Mid(nNextPos).SpanExcluding("/");
		nRecordLength = atoi(sTemp);
		nNextPos += sTemp.GetLength() + 1;
		nVolume = atoi(sCurrentVolLabel.Mid(nNextPos));

		// コピー先ファイルのパス名を作成
		sFileName = CreatePathName(pDirName, sFileName);

		for (i = 0; i < nVolume; i++) {
			// ボリューム本数チェック
			if (nVolCount >= pParam->nVolCount) {
				DispMessage(IDS_MSG_NOTEXIST_DAT, MB_OK, FALSE, sFileName, nRecordLength, nVolume);
				bLoop = FALSE;
				break;
			}

			if (bBOT) {
				// ２つめのボリューム以降の確認メッセージ
				if (DispMessage(IDS_MSG_DEVICE_SET3, MB_OKCANCEL, FALSE,
						pParam->sShubetsuCode, pParam->sShikibetsuCode, sCurrentVolLabel) == IDCANCEL)
					return RC_CANCEL;
			}

			// CMTからファイルにコピー
			if ((nResult = m_MTDriver.ReadFileNonLabel(sFileName)) < 0 || nResult == RESULT_FILE_ERROR)
				return RC_IOERROR;
			else if (nResult == RESULT_FILE_END && i + 1 != nVolume) {
				DispMessage(IDS_MSG_DIFF_VOLNUM, MB_OK, FALSE);
				bLoop = FALSE;
				break;
			} else if (nResult == RESULT_VOLUME_END) {
				nVolCount++;
				bBOT = TRUE;
			}
		}

		// 次のボリュームラベルに移動
		sVolLabel = sVolLabel.Mid(min(sCurrentVolLabel.GetLength() + 1, sVolLabel.GetLength()));
	}

	return RC_NORMAL;
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
void CInputCMT::GetError(int &nErrorCode, CString &sErrorMsg)
{
	nErrorCode = m_MTDriver.GetError(sErrorMsg);
}
