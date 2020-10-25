//*****************************************************************************************************
//  1. �t�@�C����
//		ManualEditFrameWnd.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualEditFrameWnd �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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
//  1. �֐���
//		CManualEditFrameWnd::CManualEditFrameWnd
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�N���X�ϐ�������������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CManualEditFrameWnd::CManualEditFrameWnd()
{
	// �N���X�ϐ�������
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
	ID_SEPARATOR,           // �X�e�[�^�X ���C�� �C���W�P�[�^
	ID_INDICATOR_KANA,
	ID_INDICATOR_CAPS,
	ID_INDICATOR_NUM,
	ID_INDICATOR_SCRL,
};

//*****************************************************************************************************
//  1. �֐���
//		CManualEditFrameWnd::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t���[���E�B���h�E����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCREATESTRUCT	lpCreateStruct			[I] CREATESTRUCT�\���̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		0:����C1:�G���[
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�c�[���o�[�쐬�A�@��E�O���t��ރR���{�{�b�N�X�\���A�ڕW�^�N�g�\���A�X�e�[�^�X�o�[�쐬���s���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
int CManualEditFrameWnd::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CMDIFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	if (g_pSystem->m_cEditWindowInfo.bSaved)
		MoveWindow(g_pSystem->m_cEditWindowInfo.rectMain, FALSE);

	// AfxMessageBox�֐��ł��̃t���[���E�B���h�E��e�ɂ��邽�߂̏��u
	CWinApp *pWinApp = AfxGetApp();
	m_pMainWnd = pWinApp->m_pMainWnd;
	pWinApp->m_pMainWnd = this;

	// �c�[���o�[�쐬
	m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP);
	m_wndToolBar.LoadToolBar(IDR_MANUAL_FRAME);
// Modify ... ( ADD )
	m_wndToolBar.EnableToolTips( TRUE );
// By Y.Itabashi (xxxxx) 2007.03.08

	// �_�C�A���O�o�[�쐬
	m_wndDlgBar.Create(this, IDD_MANUAL_BAR, WS_VISIBLE | CBRS_TOP, AFX_IDW_TOOLBAR + 1);

// Modify ... ( DELETE )
	// �@��R���{�{�b�N�X�\��
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

	// �O���t��ރ��W�I�{�b�N�X�����I��
	CButton *pButtonGraph = (CButton *)m_wndDlgBar.GetDlgItem(IDC_RADIO_GRAPH_KOTEI);
	pButtonGraph->SetCheck(1);

	// �X�e�[�^�X�o�[�쐬
	m_wndStatusBar.Create(this);
	m_wndStatusBar.SetIndicators(indicators, sizeof(indicators)/sizeof(UINT));

	// �h�L�������g�N���X����
	CRuntimeClass* pDocClass = RUNTIME_CLASS(CManualEditDocument);
	m_pDocument = (CDocument *)pDocClass->CreateObject();

	// �_�ŗp�^�C�}�[�N��
	SetTimer(IDT_BLINK, 400, NULL);

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditFrameWnd::DestroyWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�j��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	�E�B���h�E���j�����ꂽ�ꍇ�� 0 �ȊO
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		OnCreate�ŕύX����m_pMainWnd�����ɖ߂��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
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

	// OnCreate�ŕύX����m_pMainWnd�����ɖ߂�
	CWinApp *pWinApp = AfxGetApp();
	pWinApp->m_pMainWnd = m_pMainWnd;
	m_pMainWnd->EnableWindow(TRUE);
	m_pMainWnd->ShowWindow(SW_RESTORE);
	m_pMainWnd->SetForegroundWindow();

	CMDIFrameWnd::OnDestroy();
}

// Modify ... ( DELETE )
// �I�𒆂̋@��擾
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
	case 1:		// 1���
		if (nWindowNo == 1)
			bDisplay = TRUE;
		break;
	case 2:		// �㉺�Q���
		if (nWindowNo == 1 || nWindowNo == 2)
			bDisplay = TRUE;
		break;
	case 3:		// ��Q��ʉ�1���
	case 4:		// ��1��ʉ��Q���
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
			case 1:		// �ҏW���
				pTemplate = pWinApp->m_pManualDocTemplate1;
				break;
			case 2:		// ��Ǝ҈ꗗ�\�����
				pTemplate = pWinApp->m_pManualDocTemplate2;
				break;
			case 3:		// �S���\�����
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
		case 1:		// 1���
			pWnd->ShowWindow(SW_SHOWMAXIMIZED);
			break;
		case 2:		// �㉺�Q���
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
		case 3:		// ��Q��ʉ�1���
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
		case 4:		// ��1��ʉ��Q���
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
