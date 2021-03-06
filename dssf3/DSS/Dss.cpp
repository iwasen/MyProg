// Dss.cpp : アプリケーションのクラス動作を定義します。
//

#include "stdafx.h"
#include "Dss.h"
#include "DssDlg.h"
#include "UpdateAp.h"

// CDssApp

BEGIN_MESSAGE_MAP(CDssApp, CWinApp)
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()


// CDssApp コンストラクション

CDssApp::CDssApp()
{
	// TODO: この位置に構築用コードを追加してください。
	// ここに InitInstance 中の重要な初期化処理をすべて記述してください。
}


// 唯一の CDssApp オブジェクトです。

CDssApp theApp;


// CDssApp 初期化

BOOL CDssApp::InitInstance()
{
	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	CWinApp::InitInstance();

	CDssDlg dlg;
	m_pMainWnd = &dlg;
	INT_PTR nResponse = dlg.DoModal();
	if (nResponse == IDOK)
	{
		g_DssData.SaveData();
	}

	return FALSE;
}

void CDssApp::OnOnlineUpdate()
{
	OnlineUpdateFunc pOnlineUpdate;
	HINSTANCE hModule;
	CString exeFileName;

	if ((hModule = ::LoadLibrary("YMUPDATE.DLL")) == NULL) {
		AfxMessageBox(IDS_ERR_YMUPDATE);
		return;
	}

	if ((pOnlineUpdate = (OnlineUpdateFunc)::GetProcAddress(hModule, "OnlineUpdate")) == NULL) {
		AfxMessageBox(IDS_ERR_ONLINEUPDATE);
		::FreeLibrary(hModule);
		return;
	}

	::GetModuleFileName(m_hInstance, exeFileName.GetBuffer(MAX_PATH), MAX_PATH);
	exeFileName.ReleaseBuffer();

	CString sOnlineUpdate;
	sOnlineUpdate.LoadString(IDS_ONLINEUPDATE);
#ifdef _WIN64
	sOnlineUpdate += "64";
#else
	sOnlineUpdate += "32";
#endif
#ifdef BUILD_DEV
	sOnlineUpdate += "D";
#endif
	pOnlineUpdate(sOnlineUpdate, exeFileName, m_lpCmdLine, LoadIcon(IDR_MAINFRAME), "Setting Utility", "Analyzing System for Sound Fields Ver5");
}
