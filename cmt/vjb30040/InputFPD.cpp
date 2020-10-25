// InputFPD.cpp: CInputFPD クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30040.h"
#include "InputFPD.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CInputFPD::CInputFPD()
{

}

CInputFPD::~CInputFPD()
{

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
int CInputFPD::KakuninMessage(Param *pParam)
{
	if (DispMessage(IDS_MSG_DEVICE_SET2, MB_OKCANCEL, FALSE,
			pParam->sShubetsuCode, pParam->sShikibetsuCode, pParam->sVolLabel) == IDCANCEL)
		return RC_CANCEL;

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
int CInputFPD::CheckVolumeName(Param *pParam)
{
	CString sVolumeName;

	// ボリューム名取得
	if (!GetVolumeName(g_sPathFPD, sVolumeName))
		return RC_IOERROR;

	// ボリューム名チェック
	if (pParam->sVolLabel == "000000") {
		if (sVolumeName != "")
			return RC_VOLUMENAME;
	} else {
		if (sVolumeName != pParam->sVolLabel)
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
int CInputFPD::InputFiles(Param *pParam)
{
	CString sDstDir;

	// コピー先ディレクトリ名取得
	sDstDir = CreatePathName(g_sInputDir, pParam->sShikibetsuCode + pParam->sVolLabel);

	// コピー先ディレクトリ削除
	DeleteDirectory(sDstDir, FALSE);

	// コピー先ディレクトリ作成
	CreateDirectory(sDstDir, NULL);

	// ディレクトリごとファイルコピー
	if (!CopyDirectory(g_sPathFPD, sDstDir, m_pUpdateWnd))
		return RC_IOERROR;

	return RC_NORMAL;
}
