//*****************************************************************************************************
//  1. �t�@�C����
//		ManualSchedulingDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualSchedulingDlg �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "ManualSchedulingDlg001.h"
#include "ManualSchedulingDlg.h"
#include "ManualEditDocument.h"
#include "ManualEditFrameWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

//*****************************************************************************************************
//  1. �֐���
//		CManualSchedulingDlg::CManualSchedulingDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CWnd		*pParent			[I] �e�E�B���h�E
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		����
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CManualSchedulingDlg::CManualSchedulingDlg(CWnd* pParent /*=NULL*/)
	: CSubMenuDlg(CManualSchedulingDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CManualSchedulingDlg)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_DATA_INIT
}


void CManualSchedulingDlg::DoDataExchange(CDataExchange* pDX)
{
	CSubMenuDlg::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualSchedulingDlg)
	DDX_Control(pDX, IDC_BUTTON_MANUAL, m_cButtonManual);
	//}}AFX_DATA_MAP
	DDX_Control(pDX, IDC_CHECK_SUBLINE_1, m_aCheckSubline[0]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_2, m_aCheckSubline[1]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_3, m_aCheckSubline[2]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_4, m_aCheckSubline[3]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_5, m_aCheckSubline[4]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_6, m_aCheckSubline[5]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_7, m_aCheckSubline[6]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_8, m_aCheckSubline[7]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_9, m_aCheckSubline[8]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_10, m_aCheckSubline[9]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_11, m_aCheckSubline[10]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_12, m_aCheckSubline[11]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_13, m_aCheckSubline[12]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_14, m_aCheckSubline[13]);
	DDX_Control(pDX, IDC_CHECK_SUBLINE_15, m_aCheckSubline[14]);
}


BEGIN_MESSAGE_MAP(CManualSchedulingDlg, CSubMenuDlg)
	//{{AFX_MSG_MAP(CManualSchedulingDlg)
	ON_BN_CLICKED(IDC_BUTTON_MANUAL, OnButtonManual)
	ON_BN_CLICKED(IDC_BUTTON_ZENSENTAKU, OnButtonZensentaku)
	ON_BN_CLICKED(IDC_BUTTON_ZENKAIJO, OnButtonZenkaijo)
	ON_WM_SHOWWINDOW()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CManualSchedulingDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���j���[�{�^���̔w�i�F��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CManualSchedulingDlg::OnInitDialog()
{
	CSubMenuDlg::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_ManualSchedulingDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// ���j���[�{�^���̔w�i�F��ݒ�
	m_cButtonManual.SetBackGroundColor(COLOR_SUBMENU_BUTTON);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CManualSchedulingDlg::OnButtonManual
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�蓮�Ґ��{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�蓮�Ґ��E�B���h�E��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CManualSchedulingDlg::OnButtonManual()
{
	// �`�F�b�N����Ă���T�u���C�����擾
	BOOL bChecked = FALSE;
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		pSubline->bManualEditFlag = m_aCheckSubline[nSublineId];
		if (pSubline->bManualEditFlag)
			bChecked = TRUE;
	}

	// �T�u���C���I���`�F�b�N
	if (!bChecked) {
		g_pSystem->DispMessage("E010001");
		return;
	}

	// ���C�� MDI �t���[�� �E�B���h�E���쐬
	CManualEditFrameWnd *pMainFrame = new CManualEditFrameWnd;
	pMainFrame->LoadFrame(IDR_MANUAL_FRAME, WS_OVERLAPPEDWINDOW | FWS_ADDTOTITLE, g_pSystem->m_pMainDlg);

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( pMainFrame, Menu_ManualSchedulingFrame );
// By Y.Itabashi (xxxxx) 2007.01.26

	pMainFrame->DisplayViews(FALSE);
	pMainFrame->ShowWindow(SW_SHOW);
}

void CManualSchedulingDlg::OnButtonZensentaku()
{
	SetAllSublixxxheck(TRUE);
}

void CManualSchedulingDlg::OnButtonZenkaijo()
{
	SetAllSublixxxheck(FALSE);
}

void CManualSchedulingDlg::OnShowWindow(BOOL bShow, UINT nStatus)
{
	CSubMenuDlg::OnShowWindow(bShow, nStatus);

	// �T�u���C���̃`�F�b�N�{�b�N�X��\��
	if (bShow) {
		int nSublineId;
		int nSublineNum = g_pDataManager->GetSublineNum();

		for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
			SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
			CMyButton &cCheckSubline = m_aCheckSubline[nSublineId];
			cCheckSubline.SetWindowText(pSubline->sSublineName);
			if (pSubline->bSagyoshaWaritsuke) {
//				cCheckSubline = 1;
				cCheckSubline.EnableWindow(TRUE);
				cCheckSubline.ShowWindow(SW_SHOW);
// Modify ... ( ADD )
				cCheckSubline.SetCheck( 1 );
// By Y.Itabashi (xxxxx) 2007.03.06
			} else {
			    m_aCheckSubline[nSublineId] = FALSE;		// 20070214 xxxxx kuwa
				cCheckSubline.EnableWindow(FALSE);
			}
		}

		for (; nSublineId < MAX_SUBLINE; nSublineId++)
			m_aCheckSubline[nSublineId].ShowWindow(SW_HIDE);
	}
}

void CManualSchedulingDlg::SetAllSublixxxheck(BOOL bCheck)
{
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		if (m_aCheckSubline[nSublineId].IsWindowEnabled())
			m_aCheckSubline[nSublineId] = bCheck;
	}
}
