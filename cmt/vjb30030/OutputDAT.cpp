// OutputDAT.cpp: COutputDAT クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputDAT.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

COutputDAT::COutputDAT()
{

}

COutputDAT::~COutputDAT()
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
int COutputDAT::CheckVolumeName(Queue *pQueue)
{
	// DATはボリュームラベルチェック無し
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
int COutputDAT::OutputFiles(Queue *pQueue)
{
	CString sCmdLine;
	char cCurrentDir[256];
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

	sCmdLine.Format("ntbackup backup %s /hc:off /t copy", m_sSrcDir);
	memset(&si, 0, sizeof(si));
	si.cb = sizeof(si);
	si.dwFlags = STARTF_USESHOWWINDOW;
	si.wShowWindow = SW_SHOW;
	GetCurrentDirectory(sizeof(cCurrentDir), cCurrentDir);
	CreateProcess(NULL, (LPTSTR)(LPCTSTR)sCmdLine, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, cCurrentDir, &si, &pi);
	CloseHandle(pi.hThread);

	ProcessWait(pi.hProcess);

	if (DispMessage(IDS_MSG_DAT_END, MB_YESNO, TRUE) == IDNO)
		return RC_IOERROR;

	return RC_NORMAL;
}
