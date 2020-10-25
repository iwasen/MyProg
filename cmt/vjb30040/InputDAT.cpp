// InputDAT.cpp: CInputDAT クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30040.h"
#include "InputDAT.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CInputDAT::CInputDAT()
{

}

CInputDAT::~CInputDAT()
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
int CInputDAT::KakuninMessage(Param *pParam)
{
	if (DispMessage(IDS_MSG_DEVICE_SET1, MB_OKCANCEL, FALSE,
			pParam->sShubetsuCode, pParam->sShikibetsuCode) == IDCANCEL)
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
int CInputDAT::CheckVolumeName(Param *pParam)
{
	// DAT はラベルチェック無し
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
int CInputDAT::InputFiles(Param *pParam)
{
	CString sDstDir;
	CString sCmdLine;
	char cCurrentDir[256];
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

	// コピー先ディレクトリ名取得
	sDstDir = g_sDatDir;

	// コピー先ディレクトリ削除
	DeleteDirectory(g_sDatDir, FALSE);

	// コピー先ディレクトリ作成
	CreateDirectory(sDstDir, NULL);

	// NTBackup.exe を起動
	sCmdLine.Format("ntbackup");
	memset(&si, 0, sizeof(si));
	si.cb = sizeof(si);
	si.dwFlags = STARTF_USESHOWWINDOW;
	si.wShowWindow = SW_SHOW;
	GetCurrentDirectory(sizeof(cCurrentDir), cCurrentDir);
	CreateProcess(NULL, (LPTSTR)(LPCTSTR)sCmdLine, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, cCurrentDir, &si, &pi);
	CloseHandle(pi.hThread);
	ProcessWait(pi.hProcess);

	// 結果入力メッセージ
	if (DispMessage(IDS_MSG_NTBACKUP_END, MB_YESNO, FALSE) == IDNO)
		return RC_CANCEL;

	return RC_NORMAL;
}
