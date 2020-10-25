// ManualEditChildWnd.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "workscheduler.h"
#include "ManualEditWnd002.h"
#include "ManualEditChildWnd.h"
#include "ManualEditDocument.h"
#include "ManualEditView1.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CManualEditChildWnd

IMPLEMENT_DYNCREATE(CManualEditChildWnd, CMDIChildWnd)

CManualEditChildWnd::CManualEditChildWnd()
{
// Modify ... ( ADD )
	m_nGraphKishu = 0;
	m_nRefIndex = 0;				// �Q�ƍH���̔ԍ�
// By Y.Itabashi (xxxxx) 2007.02.26
}

CManualEditChildWnd::~CManualEditChildWnd()
{
}


BEGIN_MESSAGE_MAP(CManualEditChildWnd, CMDIChildWnd)
	//{{AFX_MSG_MAP(CManualEditChildWnd)
	ON_WM_CREATE()
	ON_WM_SYSCOMMAND()
	ON_CBN_SELCHANGE(IDC_COMBO_KISHU, OnSelchangeComboKishu)
	ON_BN_CLICKED(IDC_BACK_REF_KOTEI, OnBackRefKotei)
	ON_BN_CLICKED(IDC_NEXT_REF_KOTEI, OnNextRefKotei)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualEditChildWnd ���b�Z�[�W �n���h��

BOOL CManualEditChildWnd::PreCreateWindow(CREATESTRUCT& cs)
{
	cs.style &= ~(LONG)FWS_ADDTOTITLE;

	return CMDIChildWnd::PreCreateWindow(cs);
}

int CManualEditChildWnd::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CMDIChildWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	CMenu *pSysMenu = GetSystemMenu(FALSE);
	pSysMenu->EnableMenuItem (SC_CLOSE, MF_BYCOMMAND | MF_GRAYED);

// Modify ... ( ADD )
	// �_�C�A���O�o�[�쐬
	m_wndDlgBar.Create(this, IDD_MANUAL_KISHU_BAR, WS_VISIBLE | CBRS_TOP, AFX_IDW_TOOLBAR + 1);

	// �@��R���{�{�b�N�X�\��
	CComboBox *pComboKishu = (CComboBox *)m_wndDlgBar.GetDlgItem(IDC_COMBO_KISHU);
	for (int i = 0; i < g_pDataManager->GetKishuNum(); i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);
		pComboKishu->SetItemData(pComboKishu->AddString(pKishu->sKishuName), i);
	}

	g_pSystem->m_cMenuDisp.SetMenuText( &m_wndDlgBar, Menu_ManualWindowBar );
	pComboKishu->SetItemData(pComboKishu->GetCount() - 1, -1);
	pComboKishu->SetCurSel(0);
	pComboKishu->ShowWindow(SW_SHOW);
// By Y.Itabashi (xxxxx) 2007.02.26


	return 0;
}

void CManualEditChildWnd::OnSysCommand(UINT nID, LPARAM lParam)
{
	if (nID == SC_CLOSE)
		return;

	CMDIChildWnd::OnSysCommand(nID, lParam);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditChildWnd::OnSelchangeComboKishu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@��R���{�{�b�N�X�I��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�O���t��I�����ꂽ�@��ɕύX����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.02.26 Y.Itabashi(xxxxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditChildWnd::OnSelchangeComboKishu()
{
	if( !m_wndDlgBar )
		return;

	CComboBox *pComboKishu = (CComboBox *)m_wndDlgBar.GetDlgItem(IDC_COMBO_KISHU);
	m_nGraphKishu = pComboKishu->GetItemData(pComboKishu->GetCurSel());

	// �O���t��I�����ꂽ�@��ɕύX
	((CManualEditDocument *)GetActiveDocument())->ChangeGraphKishu(m_nGraphKishu, this);
}

//*****************************************************************************************************
//  1. �֐���
//		CManualEditChildWnd::DestroyKishuBar
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@��R���{�{�b�N�X�_�C�A���O�o�[�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�@��R���{�{�b�N�X�_�C�A���O�o�[�����
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2007.02.26 Y.Itabashi(xxxxx) �V�K�쐬
//*****************************************************************************************************
void CManualEditChildWnd::DestroyKishuBar()
{
	if( !m_wndDlgBar.GetSafeHwnd() )
		return;

	m_wndDlgBar.DestroyWindow();
	m_wndDlgBar.m_hWnd = NULL;
}

void CManualEditChildWnd::OnBackRefKotei() 
{
	CManualEditDocument *pDoc = (CManualEditDocument *)GetActiveDocument();
	CManualEditView1 *pView = (CManualEditView1 *)GetActiveView();

	for( int nSelect = m_nRefIndex-1; nSelect >= 0; nSelect-- ){

		if( m_nGraphKishu == pDoc->m_aRefYosoSagyogun[nSelect].nKishuId ){
			m_nRefIndex = nSelect;

// Modify ... ( CHANGE )
			SZahyoSagyosha cZahyoSagyosha;
			cZahyoSagyosha.nSublineId = pDoc->m_aRefYosoSagyogun[nSelect].nSublineId;
			cZahyoSagyosha.nSagyoshaId = pDoc->m_aRefYosoSagyogun[nSelect].nSagyoshaId;
			pDoc->UpdateAllViews(NULL, UPDATE_SCROLL_ACTIVE, (CObject *)&cZahyoSagyosha);
//			pDoc->UpdateAllViews(NULL, UPDATE_SCROLL, (CObject *)&cZahyoSagyosha);
// By Y.Itabashi (xxxxx) 2007.03.04
			return;
		}
	}
}

void CManualEditChildWnd::OnNextRefKotei() 
{
	CManualEditDocument *pDoc = (CManualEditDocument *)GetActiveDocument();
	CManualEditView *pView = (CManualEditView *)GetActiveView();

	for( int nSelect = m_nRefIndex+1; nSelect < pDoc->m_aRefYosoSagyogun.GetSize(); nSelect++ ){

		if( m_nGraphKishu == pDoc->m_aRefYosoSagyogun[nSelect].nKishuId ){
			m_nRefIndex = nSelect;

// Modify ... ( CHANGE )
			SZahyoSagyosha cZahyoSagyosha;
			cZahyoSagyosha.nSublineId = pDoc->m_aRefYosoSagyogun[nSelect].nSublineId;
			cZahyoSagyosha.nSagyoshaId = pDoc->m_aRefYosoSagyogun[nSelect].nSagyoshaId;
			pDoc->UpdateAllViews(NULL, UPDATE_SCROLL_ACTIVE, (CObject *)&cZahyoSagyosha);
//			pDoc->UpdateAllViews(NULL, UPDATE_SCROLL, (CObject *)&cZahyoSagyosha);
// By Y.Itabashi (xxxxx) 2007.03.04
			return;
		}
	}
}
