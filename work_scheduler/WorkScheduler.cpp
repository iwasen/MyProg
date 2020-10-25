//*****************************************************************************************************
//  1. ファイル名
//		WorkScheduler.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アプリケーション用クラスの定義を行います。
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"
#include "WorkSchedulerDlg.h"
#include "ManualEditDocument.h"
#include "ManualEditChildWnd.h"
#include "ManualEditChildWnd1.h"
#include "ManualEditView1.h"
#include "ManualEditView2.h"
#include "ManualEditView3.h"
#include "System.h"
#include "DataManager.h"
#include "Const.h"
#include "LoginDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

BEGIN_MESSAGE_MAP(CWorkSchedulerApp, CWinApp)
	//{{AFX_MSG_MAP(CWorkSchedulerApp)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

// 唯一の CWorkSchedulerApp オブジェクト
CWorkSchedulerApp theApp;

CConst *g_pConst;
CSystem *g_pSystem;
CDataManager *g_pDataManager;

//*****************************************************************************************************
//  1. 関数名
//		CWorkSchedulerApp::InitInstance
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		インスタンス初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		FALSE
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		作業編成ダイアログを表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CWorkSchedulerApp::InitInstance()
{
	AfxEnableControlContainer();

	// 標準的な初期化処理
	// もしこれらの機能を使用せず、実行ファイルのサイズを小さくしたけ
	//  れば以下の特定の初期化ルーチンの中から不必要なものを削除して
	//  ください。

#ifdef _AFXDLL
	Enable3dControls();			// 共有 DLL 内で MFC を使う場合はここをコールしてください。
#else
	Enable3dControlsStatic();	// MFC と静的にリンクする場合はここをコールしてください。
#endif

	::CoInitialize(NULL);

	// システム管理クラス生成
	g_pSystem = new CSystem;
	if (!g_pSystem->Initialize())
		return FALSE;

	// 定数クラス生成
	g_pConst = new CConst;

	// データ管理クラス生成
	g_pDataManager = new CDataManager;

	// ログイン処理
	{
		CLoginDlg cLoginDlg;
		if (cLoginDlg.DoModal() == IDCANCEL)
			return FALSE;
	}

	// 手動編成ウィンドウ用ドキュメントテンプレート作成
	m_pManualDocTemplate1 = new CMultiDocTemplate(
		IDR_MANUAL_FRAME,
		RUNTIME_CLASS(CManualEditDocument),
		RUNTIME_CLASS(CManualEditChildWnd1),
		RUNTIME_CLASS(CManualEditView1));
	AddDocTemplate(m_pManualDocTemplate1);
	DestroyMenu(m_pManualDocTemplate1->m_hMenuShared);
	m_pManualDocTemplate1->m_hMenuShared = NULL;

	m_pManualDocTemplate2 = new CMultiDocTemplate(
		IDR_MANUAL_FRAME,
		RUNTIME_CLASS(CManualEditDocument),
		RUNTIME_CLASS(CManualEditChildWnd),
		RUNTIME_CLASS(CManualEditView2));
	AddDocTemplate(m_pManualDocTemplate2);
	DestroyMenu(m_pManualDocTemplate2->m_hMenuShared);
	m_pManualDocTemplate2->m_hMenuShared = NULL;

	m_pManualDocTemplate3 = new CMultiDocTemplate(
		IDR_MANUAL_FRAME,
		RUNTIME_CLASS(CManualEditDocument),
		RUNTIME_CLASS(CManualEditChildWnd),
		RUNTIME_CLASS(CManualEditView3));
	AddDocTemplate(m_pManualDocTemplate3);
	DestroyMenu(m_pManualDocTemplate3->m_hMenuShared);
	m_pManualDocTemplate3->m_hMenuShared = NULL;

	CWorkSchedulerDlg dlg;
	m_pMainWnd = g_pSystem->m_pMainDlg = new CWorkSchedulerDlg;
	((CDialog *)m_pMainWnd)->Create(IDD_WORKSCHEDULER_DIALOG);

	// ダイアログが閉じられてからアプリケーションのメッセージ ポンプを開始するよりは、
	// アプリケーションを終了するために FALSE を返してください。
	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CWorkSchedulerApp::ExitInstance
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		インスタンス終了処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		FALSE
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		データ管理クラスを解放する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CWorkSchedulerApp::ExitInstance()
{
	if (g_pConst != NULL) {
		delete g_pConst;
		g_pConst = NULL;
	}

	if (g_pSystem != NULL) {
		delete g_pSystem;
		g_pSystem = NULL;
	}

	if (g_pDataManager != NULL) {
		delete g_pDataManager;
		g_pDataManager = NULL;
	}

	::CoUninitialize();

	return CWinApp::ExitInstance();
}
