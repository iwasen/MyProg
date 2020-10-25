//*****************************************************************************************************
//  1. ファイル名
//		ManualEditFrameWnd.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditFrameWnd クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "ManualEditWnd002.h"
#include "ManualEditFrameWnd.h"
#include "ManualEditChildWnd.h"
#include "ManualEditDocument.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define IDT_BLINK		1

IMPLEMENT_DYNAMIC(CManualEditFrameWnd, CMDIFrameWnd)

//*****************************************************************************************************
//  1. 関数名
//		CManualEditFrameWnd::CManualEditFrameWnd
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		クラス変数を初期化する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CManualEditFrameWnd::CManualEditFrameWnd()
{
	// クラス変数初期化
	m_pChildWnd1 = NULL;
	m_pChildWnd2 = NULL;
	m_pChildWnd3 = NULL;
	m_bBlink = FALSE;
	m_bStopBlink = FALSE;
}


BEGIN_MESSAGE_MAP(CManualEditFrameWnd, CMDIFrameWnd)
	//{{AFX_MSG_MAP(CManualEditFrameWnd)
	ON_WM_CREATE()
	ON_WM_DESTROY()
	ON_COMMAND(IDM_FILE_CLOSE, OnFileClose)
	ON_WM_SIZE()
	ON_WM_TIMER()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

static UINT indicators[] =
{
	ID_SEPARATOR,           // ステータス ライン インジケータ
	ID_INDICATOR_KANA,
	ID_INDICATOR_CAPS,
	ID_INDICATOR_NUM,
	ID_INDICATOR_SCRL,
};

//*****************************************************************************************************
//  1. 関数名
//		CManualEditFrameWnd::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フレームウィンドウ生成
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCREATESTRUCT	lpCreateStruct			[I] CREATESTRUCT構造体ポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		0:正常，1:エラー
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ツールバー作成、機種・グラフ種類コンボボックス表示、目標タクト表示、ステータスバー作成を行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CManualEditFrameWnd::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CMDIFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	if (g_pSystem->m_cEditWindowInfo.bSaved)
		MoveWindow(g_pSystem->m_cEditWindowInfo.rectMain, FALSE);

	// AfxMessageBox関数でこのフレームウィンドウを親にするための処置
	CWinApp *pWinApp = AfxGetApp();
	m_pMainWnd = pWinApp->m_pMainWnd;
	pWinApp->m_pMainWnd = this;

	// ツールバー作成
	m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP);
	m_wndToolBar.LoadToolBar(IDR_MANUAL_FRAME);
// Modify ... ( ADD )
	m_wndToolBar.EnableToolTips( TRUE );
// By Y.Itabashi (xxxxx) 2007.03.08

	// ダイアログバー作成
	m_wndDlgBar.Create(this, IDD_MANUAL_BAR, WS_VISIBLE | CBRS_TOP, AFX_IDW_TOOLBAR + 1);

// Modify ... ( DELETE )
	// 機種コンボボックス表示
/*	CComboBox *pComboKishu = (CComboBox *)m_wndDlgBar.GetDlgItem(IDC_COMBO_KISHU);
	for (int i = 0; i < g_pDataManager->GetKishuNum(); i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);
		pComboKishu->SetItemData(pComboKishu->AddString(pKishu->sKishuName), i);
	}

// Modify ... ( CHANGE )
	g_pSystem->m_cMenuDisp.SetMenuText( &m_wndDlgBar, Menu_ManualWindowBar );
//	pComboKishu->SetItemData(pComboKishu->AddString(CGlobal::GetString(IDS_JAKU_HEIKIN)), -1);
// By Y.Itabashi (xxxxx) 2007.01.26
	pComboKishu->SetItemData(pComboKishu->GetCount() - 1, -1);
	pComboKishu->SetCurSel(0);
	pComboKishu->SetFont(m_pMainWnd->GetFont(), FALSE);
	pComboKishu->ShowWindow(SW_SHOW);*/
// By Y.Itabashi (xxxxx) 2007.02.26

	// グラフ種類ラジオボックス初期選択
	CButton *pButtonGraph = (CButton *)m_wndDlgBar.GetDlgItem(IDC_RADIO_GRAPH_KOTEI);
	pButtonGraph->SetCheck(1);

	// ステータスバー作成
	m_wndStatusBar.Create(this);
	m_wndStatusBar.SetIndicators(indicators, sizeof(indicators)/sizeof(UINT));

	// ドキュメントクラス生成
	CRuntimeClass* pDocClass = RUNTIME_CLASS(CManualEditDocument);
	m_pDocument = (CDocument *)pDocClass->CreateObject();

	// 点滅用タイマー起動
	SetTimer(IDT_BLINK, 400, NULL);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditFrameWnd::DestroyWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ破棄
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	ウィンドウが破棄された場合は 0 以外
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		OnCreateで変更したm_pMainWndを元に戻す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

void CManualEditFrameWnd::OnDestroy()
{
	g_pSystem->m_cEditWindowInfo.bSaved = TRUE;
	GetWindowRect(g_pSystem->m_cEditWindowInfo.rectMain);
	if (m_pChildWnd1 != NULL)
		m_pChildWnd1->GetWindowPlacement(&g_pSystem->m_cEditWindowInfo.wpChild1);
	if (m_pChildWnd2 != NULL)
		m_pChildWnd2->GetWindowPlacement(&g_pSystem->m_cEditWindowInfo.wpChild2);
	if (m_pChildWnd3 != NULL)
		m_pChildWnd3->GetWindowPlacement(&g_pSystem->m_cEditWindowInfo.wpChild3);

	// OnCreateで変更したm_pMainWndを元に戻す
	CWinApp *pWinApp = AfxGetApp();
	pWinApp->m_pMainWnd = m_pMainWnd;
	m_pMainWnd->EnableWindow(TRUE);
	m_pMainWnd->ShowWindow(SW_RESTORE);
	m_pMainWnd->SetForegroundWindow();

	CMDIFrameWnd::OnDestroy();
}

// Modify ... ( DELETE )
// 選択中の機種取得
/*int CManualEditFrameWnd::GetSelectedKishu()
{
	CComboBox *pComboKishu = (CComboBox *)m_wndDlgBar.GetDlgItem(IDC_COMBO_KISHU);
	return pComboKishu->GetItemData(pComboKishu->GetCurSel());
}*/
// By Y.Itabashi (xxxxx) 2007.02.26

void CManualEditFrameWnd::DisplayViews(BOOL bForce)
{
	CreateChildWnd(1, g_pSystem->m_cIniUser.m_nWindowNo1DisplayType, m_pChildWnd1, m_nChildWndType1, bForce);
	CreateChildWnd(2, g_pSystem->m_cIniUser.m_nWindowNo2DisplayType, m_pChildWnd2, m_nChildWndType2, bForce);
	CreateChildWnd(3, g_pSystem->m_cIniUser.m_nWindowNo3DisplayType, m_pChildWnd3, m_nChildWndType3, bForce);
}

void CManualEditFrameWnd::CreateChildWnd(int nWindowNo, int nDisplayType, CMDIChildWnd *&pChildWnd, int &nChildWndType, BOOL bForce)
{
	BOOL bDisplay = FALSE;

	switch (g_pSystem->m_cIniUser.m_nWindowType) {
	case 1:		// 1画面
		if (nWindowNo == 1)
			bDisplay = TRUE;
		break;
	case 2:		// 上下２画面
		if (nWindowNo == 1 || nWindowNo == 2)
			bDisplay = TRUE;
		break;
	case 3:		// 上２画面下1画面
	case 4:		// 上1画面下２画面
		bDisplay = TRUE;
		break;
	}

	if (bDisplay) {
		CWnd *pRemoveWnd = NULL;
		if (pChildWnd != NULL) {
			if (nDisplayType != nChildWndType) {
				pRemoveWnd = pChildWnd;
				pChildWnd = NULL;
			}
		}

		if (pChildWnd == NULL) {
			CWorkSchedulerApp *pWinApp = (CWorkSchedulerApp *)AfxGetApp();
			CDocTemplate *pTemplate = NULL;
			switch (nDisplayType) {
			case 1:		// 編集画面
				pTemplate = pWinApp->m_pManualDocTemplate1;
				break;
			case 2:		// 作業者一覧表示画面
				pTemplate = pWinApp->m_pManualDocTemplate2;
				break;
			case 3:		// 全情報表示画面
				pTemplate = pWinApp->m_pManualDocTemplate3;
				break;
			}
			if (pTemplate != NULL) {
				pChildWnd = (CMDIChildWnd *)pTemplate->CreateNewFrame(m_pDocument, NULL);
// Modify ... ( ADD )
				if( nDisplayType == 3 )
					((CManualEditChildWnd *)pChildWnd)->DestroyKishuBar();
// By Y.Itabashi (xxxxx) 2007.02.26
				SetChildWndPos(pChildWnd, nWindowNo, bForce);
				pTemplate->InitialUpdateFrame(pChildWnd, m_pDocument);
				nChildWndType = nDisplayType;
			}

			if (pRemoveWnd != NULL)
				pRemoveWnd->DestroyWindow();
		} else
			SetChildWndPos(pChildWnd, nWindowNo, bForce);
	} else {
		if (pChildWnd != NULL) {
			pChildWnd->DestroyWindow();
			pChildWnd = NULL;
		}
	}
}

void CManualEditFrameWnd::SetChildWndPos(CMDIChildWnd *pWnd, int nWindowNo, BOOL bForce)
{
	if (bForce || !g_pSystem->m_cEditWindowInfo.bSaved) {
		CRect rectClient;
		::GetClientRect(m_hWndMDIClient, rectClient);
		int nWidth = rectClient.Width();
		int nWidth2 = nWidth / 2;
		int nHeight = rectClient.Height();
		int nHeight2 = nHeight / 2;

		switch (g_pSystem->m_cIniUser.m_nWindowType) {
		case 1:		// 1画面
			pWnd->ShowWindow(SW_SHOWMAXIMIZED);
			break;
		case 2:		// 上下２画面
			switch (nWindowNo) {
			case 1:
				pWnd->SetWindowPos(NULL, 0, 0, nWidth, nHeight2, SWP_NOZORDER);
				break;
			case 2:
				pWnd->SetWindowPos(NULL, 0, nHeight2, nWidth, nHeight2, SWP_NOZORDER);
				break;
			}
			pWnd->ShowWindow(SW_RESTORE);
			break;
		case 3:		// 上２画面下1画面
			switch (nWindowNo) {
			case 1:
				pWnd->SetWindowPos(NULL, 0, 0, nWidth2, nHeight2, SWP_NOZORDER);
				break;
			case 2:
				pWnd->SetWindowPos(NULL, nWidth2, 0, nWidth2, nHeight2, SWP_NOZORDER);
				break;
			case 3:
				pWnd->SetWindowPos(NULL, 0, nHeight2, nWidth, nHeight2, SWP_NOZORDER);
				break;
			}
			pWnd->ShowWindow(SW_RESTORE);
			break;
		case 4:		// 上1画面下２画面
			switch (nWindowNo) {
			case 1:
				pWnd->SetWindowPos(NULL, 0, 0, nWidth, nHeight2, SWP_NOZORDER);
				break;
			case 2:
				pWnd->SetWindowPos(NULL, 0, nHeight2, nWidth2, nHeight2, SWP_NOZORDER);
				break;
			case 3:
				pWnd->SetWindowPos(NULL, nWidth2, nHeight2, nWidth2, nHeight2, SWP_NOZORDER);
				break;
			}
			pWnd->ShowWindow(SW_RESTORE);
			break;
		}
	} else {
		WINDOWPLACEMENT *wp;
		switch (nWindowNo) {
		case 1:
			wp = &g_pSystem->m_cEditWindowInfo.wpChild1;
			break;
		case 2:
			wp = &g_pSystem->m_cEditWindowInfo.wpChild2;
			break;
		case 3:
			wp = &g_pSystem->m_cEditWindowInfo.wpChild3;
			break;
		}
		wp->showCmd = SW_SHOW;
		pWnd->SetWindowPlacement(wp);
	}
}

void CManualEditFrameWnd::OnFileClose() 
{
	SendMessage(WM_CLOSE);
}

BOOL CManualEditFrameWnd::PreCreateWindow(CREATESTRUCT& cs) 
{
	cs.style &= ~(FWS_PREFIXTITLE | FWS_ADDTOTITLE);
	
	return CMDIFrameWnd::PreCreateWindow(cs);
}

void CManualEditFrameWnd::OnSize(UINT nType, int cx, int cy) 
{
	CMDIFrameWnd::OnSize(nType, cx, cy);

	CWnd *pWnd = g_pSystem->m_pMainDlg;
	if (pWnd != NULL) {
		switch (nType) {
		case SIZE_MINIMIZED:
			pWnd->ShowWindow(SW_MINIMIZE);
			pWnd->EnableWindow(TRUE);
			break;
		case SIZE_RESTORED:
			pWnd->EnableWindow(FALSE);
			break;
		}
	}
}

void CManualEditFrameWnd::OnTimer(UINT nIDEvent) 
{
	if (nIDEvent == IDT_BLINK) {
		if (!m_bStopBlink) {
			m_bBlink = !m_bBlink;
			m_pDocument->UpdateAllViews(NULL, UPDATE_SELECT_BLINK, (CObject *)m_bBlink);
		}
	}

	CMDIFrameWnd::OnTimer(nIDEvent);
}

void CManualEditFrameWnd::StopBlink(BOOL bStopBlink) 
{
	m_bStopBlink = bStopBlink;

	m_pDocument->UpdateAllViews(NULL, UPDATE_SELECT_BLINK, (CObject *)TRUE);
	m_bBlink = TRUE;
}
