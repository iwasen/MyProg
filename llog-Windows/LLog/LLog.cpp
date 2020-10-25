// LLog.cpp : アプリケーションのクラス動作を定義します。
//

#include "stdafx.h"
#include "LLog.h"
#include "LLogDlg.h"
#include "atlimage.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CLLogApp

BEGIN_MESSAGE_MAP(CLLogApp, CWinApp)
	ON_COMMAND(ID_HELP, &CWinApp::OnHelp)
END_MESSAGE_MAP()


// CLLogApp コンストラクション

CLLogApp::CLLogApp()
{
	// TODO: この位置に構築用コードを追加してください。
	// ここに InitInstance 中の重要な初期化処理をすべて記述してください。
}


// 唯一の CLLogApp オブジェクトです。

CLLogApp theApp;


// CLLogApp 初期化

BOOL CLLogApp::InitInstance()
{
	//* プログラムの複数起動抑止
	{
		CWnd *pWnd = CWnd::FindWindow("#32770", "LLog");
		if (pWnd != NULL) {
			pWnd->ShowWindow(SW_RESTORE);
			pWnd->SetForegroundWindow();
			return FALSE;
		}
	}

	// アプリケーション マニフェストが visual スタイルを有効にするために、
	// ComCtl32.dll Version 6 以降の使用を指定する場合は、
	// Windows XP に InitCommonControlsEx() が必要です。さもなければ、ウィンドウ作成はすべて失敗します。
	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	// アプリケーションで使用するすべてのコモン コントロール クラスを含めるには、
	// これを設定します。
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	CWinApp::InitInstance();

	::AfxEnableControlContainer();

	//AfxInitRichEdit2();

	// 標準初期化
	// これらの機能を使わずに最終的な実行可能ファイルの
	// サイズを縮小したい場合は、以下から不要な初期化
	// ルーチンを削除してください。
	// 設定が格納されているレジストリ キーを変更します。
	// TODO: 会社名または組織名などの適切な文字列に
	// この文字列を変更してください。
	//SetRegistryKey(_T("アプリケーション ウィザードで生成されたローカル アプリケーション"));

	CoInitialize(NULL);

	ULONG_PTR m_Token;
	Gdiplus::GdiplusStartupInput m_StartupInput;
	Gdiplus::GdiplusStartup(&m_Token, &m_StartupInput, NULL);

	CLLogDlg dlg;
	m_pMainWnd = &dlg;
	dlg.DoModal();

	Gdiplus::GdiplusShutdown(m_Token);

	::CoUninitialize();

	return FALSE;
}

BOOL CLLogApp::PreTranslateMessage(MSG* pMsg)
{
	// ESCで閉じるのを防止
	if (pMsg->message == WM_KEYDOWN && pMsg->wParam == VK_ESCAPE)
		return TRUE;

	return CWinApp::PreTranslateMessage(pMsg);
}
