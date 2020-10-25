//*****************************************************************************************************
//  1. �t�@�C����
//		SetConditionsDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CSetConditionsDlg �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"
#include "WorkSchedulerDlg.h"

#include "MenuDispCtrl.h"

#include "SetConditionsDlg001.h"
#include "SetConditionsDlg.h"
#include "SetSeisanJoken1Dlg.h"
#include "SetSeisanJoken2Dlg.h"
#include "SetKittingDlg.h"
#include "SetKyotsuKoteiDlg.h"
#include "KatashikiKiseiMatrixDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

//*****************************************************************************************************
//  1. �֐���
//		CSetConditionsDlg::CSetConditionsDlg
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
CSetConditionsDlg::CSetConditionsDlg(CWnd* pParent /*=NULL*/)
	: CSubMenuDlg(CSetConditionsDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSetConditionsDlg)
	//}}AFX_DATA_INIT
}


void CSetConditionsDlg::DoDataExchange(CDataExchange* pDX)
{
	CSubMenuDlg::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetConditionsDlg)
	DDX_Control(pDX, IDC_BUTTON_SET_SEISAN_JOKEN, m_cButtonSetSeisanJoken);
	DDX_Control(pDX, IDC_BUTTON_SET_LINE_KISEI, m_cButtonSetLineKisei);
	DDX_Control(pDX, IDC_BUTTON_SET_KYOTU_KOTEI, m_cButtonSetKyotsuKotei);
	DDX_Control(pDX, IDC_BUTTON_SET_KATASHIKI_KISEI, m_cButtonSetKatashikiKisei);
	DDX_Control(pDX, IDC_BUTTON_SET_KITTING, m_cButtonSetKitting);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CSetConditionsDlg, CSubMenuDlg)
	//{{AFX_MSG_MAP(CSetConditionsDlg)
	ON_BN_CLICKED(IDC_BUTTON_SET_KITTING, OnButtonSetKitting)
	ON_BN_CLICKED(IDC_BUTTON_SET_SEISAN_JOKEN, OnButtonSetSeisanJoken)
	ON_BN_CLICKED(IDC_BUTTON_SET_LINE_KISEI, OnButtonSetLineKisei)
	ON_BN_CLICKED(IDC_BUTTON_SET_KATASHIKI_KISEI, OnButtonSetKatashikiKisei)
	ON_BN_CLICKED(IDC_BUTTON_SET_KYOTU_KOTEI, OnButtonSetKyotuKotei)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CSetConditionsDlg::OnInitDialog
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
//		�{�^���̐F��ݒ肵�A�{�^���L��������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CSetConditionsDlg::OnInitDialog()
{
	CSubMenuDlg::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_SetConditionsDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// �{�^���̐F��ݒ�
	m_cButtonSetSeisanJoken.SetBackGroundColor(COLOR_SUBMENU_BUTTON);
	m_cButtonSetKitting.SetBackGroundColor(COLOR_SUBMENU_BUTTON);
	m_cButtonSetLineKisei.SetBackGroundColor(COLOR_SUBMENU_BUTTON);
	m_cButtonSetKatashikiKisei.SetBackGroundColor(COLOR_SUBMENU_BUTTON);
	m_cButtonSetKyotsuKotei.SetBackGroundColor(COLOR_SUBMENU_BUTTON);

	// ���j���[�L����
	g_pSystem->EnableMenu();

// Modify ... ( ADD )
	// UP4�p���W���[��
//	GetDlgItem( IDC_BUTTON_SET_KITTING )->EnableWindow( FALSE );
// By Y.Itabashi (xxxxx) 2007.02.22

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CSetConditionsDlg::OnButtonSetSeisanJoken
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Y�����ݒ�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���Y�����ݒ�_�C�A���O��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetConditionsDlg::OnButtonSetSeisanJoken()
{
	CWorkSchedulerDlg *pMainWnd = (CWorkSchedulerDlg *)AfxGetMainWnd();

	// ���C���E�B���h�E�̃T�u���j���[�X�e�[�^�X���Z�b�g
	pMainWnd->SetSubMenuStatus(m_cButtonSetSeisanJoken);

	// ���Y�䐔�o�^�_�C�A���O�\��
	if (g_pSystem->m_cUserInfo.m_nJokenSetteiSosa == 1) {
		CSetSeisanJoken1Dlg dlg;
		dlg.DoModal();
	} else {
		CSetSeisanJoken2Dlg dlg;
		dlg.DoModal();
	}

	// ���j���[�L����
	g_pSystem->EnableMenu();

	// ���C���E�B���h�E�̃T�u���j���[�X�e�[�^�X������
	pMainWnd->ClearSubMenuStatus();
}

//*****************************************************************************************************
//  1. �֐���
//		CSetConditionsDlg::OnButtonSetKitting
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�b�g���ݒ�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�L�b�g���ݒ�_�C�A���O��\������B���ɃL�b�g������Ă���ꍇ�͉�������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetConditionsDlg::OnButtonSetKitting()
{
	CWorkSchedulerDlg *pMainWnd = (CWorkSchedulerDlg *)AfxGetMainWnd();

	// ���C���E�B���h�E�̃T�u���j���[�X�e�[�^�X���Z�b�g
	pMainWnd->SetSubMenuStatus(m_cButtonSetKitting);

	// Undo�o�b�t�@���Z�b�g
	g_pDataManager->ResetUndoBuf();

	// �L�b�g���ݒ�_�C�A���O�\��
	CSetKittingDlg dlg;
	dlg.DoModal();

	// ���j���[�L����
	g_pSystem->EnableMenu();

	// ���C���E�B���h�E�̃T�u���j���[�X�e�[�^�X������
	pMainWnd->ClearSubMenuStatus();
}

void CSetConditionsDlg::OnButtonSetLineKisei()
{
	g_pDataManager->OpenLineKiseiMatrix();
}

void CSetConditionsDlg::OnButtonSetKatashikiKisei()
{
	CKatashikiKiseiMatrixDlg dlg;
	if (dlg.DoModal() == IDOK)
		g_pDataManager->OpenKatashikiKiseiMatrix();
}

void CSetConditionsDlg::OnButtonSetKyotuKotei()
{
	CWorkSchedulerDlg *pMainWnd = (CWorkSchedulerDlg *)AfxGetMainWnd();

	// ���C���E�B���h�E�̃T�u���j���[�X�e�[�^�X���Z�b�g
	pMainWnd->SetSubMenuStatus(m_cButtonSetKyotsuKotei);

	// Undo�o�b�t�@���Z�b�g
	g_pDataManager->ResetUndoBuf();

	// ���ʍH���ݒ�_�C�A���O�\��
	CSetKyotsuKoteiDlg dlg;
	dlg.DoModal();

	// ���j���[�L����
	g_pSystem->EnableMenu();

	// ���C���E�B���h�E�̃T�u���j���[�X�e�[�^�X������
	pMainWnd->ClearSubMenuStatus();
}

//*****************************************************************************************************
//  1. �֐���
//		CSetConditionsDlg::EnableMenuButton
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j���[�{�^���̗L�����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�ݒ��Ԃɂ��T�u���j���[�̃{�^����L��������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSetConditionsDlg::EnableMenuButton()
{
	m_cButtonSetLineKisei.EnableWindow(!g_pDataManager->m_bOpenLineKiseiMatrix);
	m_cButtonSetKatashikiKisei.EnableWindow(!g_pDataManager->m_bOpenKatashikiKiseiMatrix);
}
