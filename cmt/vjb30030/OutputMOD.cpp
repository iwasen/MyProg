// OutputMOD.cpp: COutputMOD クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputMOD.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

COutputMOD::COutputMOD()
{

}

COutputMOD::~COutputMOD()
{

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
int COutputMOD::CheckVolumeName(Queue *pQueue)
{
	CString sVolumeName;

	// ボリューム名取得
	if (!GetVolumeName(g_sPathMOD, sVolumeName))
		return RC_IOERROR;

	// ボリューム名チェック
	if (pQueue->sVolLabel == "000000") {
		if (sVolumeName != "")
			return RC_VOLUMENAME;
	} else {
		if (sVolumeName != pQueue->sVolLabel)
			return RC_VOLUMENAME;
	}

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
int COutputMOD::OutputFiles(Queue *pQueue)
{
	// コピー先のディレクトリ（ファイル）を削除
	if (!DeleteDirectory(g_sPathMOD, TRUE))
		return RC_IOERROR;

	// ディレクトリごとファイルコピー
	if (!CopyDirectory(m_sSrcDir, g_sPathMOD, NULL))
		return RC_IOERROR;

	return RC_NORMAL;
}
