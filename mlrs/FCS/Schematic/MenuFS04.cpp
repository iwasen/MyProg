//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS04.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�[�����[�^�n�i�����}�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS04.h"

// CMenuFS04 �_�C�A���O

IMPLEMENT_DYNCREATE(CMenuFS04, CMenuCommon)

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::CMenuFS04
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CWnd*	pParent		[I] �e�E�B���h�E
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CMenuFS04::CMenuFS04(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS04::IDD, pParent)
{
}

void CMenuFS04::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_BUTTON_ACTION_1, m_cButtonAction1);
	DDX_Control(pDX, IDC_BUTTON_ACTION_2, m_cButtonAction2);
	DDX_Control(pDX, IDC_BUTTON_ACTION_3, m_cButtonAction3);
	DDX_Control(pDX, IDC_BUTTON_ACTION_4, m_cButtonAction4);
	DDX_Control(pDX, IDC_BUTTON_ACTION_5, m_cButtonAction5);
	DDX_Control(pDX, IDC_BUTTON_ACTION_6, m_cButtonAction6);
	DDX_Control(pDX, IDC_BUTTON_ACTION_7, m_cButtonAction7);
	DDX_Control(pDX, IDC_BUTTON_ACTION_8, m_cButtonAction8);
	DDX_Control(pDX, IDC_BUTTON_ACTION_9, m_cButtonAction9);
	DDX_Control(pDX, IDC_BUTTON_ACTION_10, m_cButtonAction10);
	DDX_Control(pDX, IDC_BUTTON_ACTION_11, m_cButtonAction11);
	DDX_Control(pDX, IDC_BUTTON_ACTION_12, m_cButtonAction12);
}


BEGIN_MESSAGE_MAP(CMenuFS04, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFS04::OnBnClickedButtonAction1)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS04::OnBnClickedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFS04::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFS04::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFS04::OnBnClickedButtonAction5)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_6, &CMenuFS04::OnBnClickedButtonAction6)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_7, &CMenuFS04::OnBnClickedButtonAction7)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_8, &CMenuFS04::OnBnClickedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_9, &CMenuFS04::OnBnClickedButtonAction9)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_10, &CMenuFS04::OnBnClickedButtonAction10)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_11, &CMenuFS04::OnBnClickedButtonAction11)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_12, &CMenuFS04::OnBnClickedButtonAction12)
END_MESSAGE_MAP()


// CMenuFS04 ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CMenuFS04::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_TRAVEL_INITIAL, STATUS_HOIST_INITIAL);

	return FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�uCAGE UP�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction1()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_TRAVEL_CAGE_UP, m_eHoistStatus);

	// Flash�֑���w�����M
	SendAction(1);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�uCAGE DOWN�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction2()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_TRAVEL_CAGE_DOWN, m_eHoistStatus);

	// Flash�֑���w�����M
	SendAction(2);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction3
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���Z�b�g�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction3()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_TRAVEL_INITIAL, m_eHoistStatus);

	// Flash�֑���w�����M
	SendAction(3);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction4
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�u�[�����䃁�j���[�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction4()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_BOOM_MENU);

	// Flash�֑���w�����M
	SendAction(4);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction5
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@���@���[�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction5()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_STRAGE);

	// Flash�֑���w�����M
	SendAction(5);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction6
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�g�p�\�����v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction6()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_READY_LAMP);

	// Flash�֑���w�����M
	SendAction(6);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction7
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction7()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_LEFT);

	// Flash�֑���w�����M
	SendAction(7);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction8
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�����v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction8()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_BOTH);

	// Flash�֑���w�����M
	SendAction(8);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction9
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�E�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction9()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_RIGHT);

	// Flash�֑���w�����M
	SendAction(9);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction10
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�u�[���k�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction10()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_BOOM_SHORT);

	// Flash�֑���w�����M
	SendAction(10);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction11
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�u�[���L�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::OnBnClickedButtonAction11()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_BOOM_LONG);

	// Flash�֑���w�����M
	SendAction(11);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::OnBnClickedButtonAction12
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���Z�b�g�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
// 
void CMenuFS04::OnBnClickedButtonAction12()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eTravelStatus, STATUS_HOIST_INITIAL);

	// Flash�֑���w�����M
	SendAction(12);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::SetStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		����X�e�[�^�X�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		ETravelStatus	eTravelStatus		[I] �g���x�����b�N����X�e�[�^�X
//		EHoistStatus	eHoistStatus		[I] �z�C�X�g���[�^����X�e�[�^�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::SetStatus(ETravelStatus eTravelStatus, EHoistStatus eHoistStatus)
{
	// ����X�e�[�^�X�ݒ�
	m_eTravelStatus = eTravelStatus;
	m_eHoistStatus = eHoistStatus;

	// ���j���[�{�^���ݒ�
	SetMenuButton();
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS04::SetMenuButton
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j���[�{�^���ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS04::SetMenuButton()
{
	switch (m_eTravelStatus) {
	case STATUS_TRAVEL_INITIAL:
		m_cButtonAction1.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction2.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction3.SetEnableAndCheck(FALSE, FALSE);
		break;
	case STATUS_TRAVEL_CAGE_UP:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		break;
	case STATUS_TRAVEL_CAGE_DOWN:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		break;
	}

	switch (m_eHoistStatus) {
	case STATUS_HOIST_INITIAL:
		m_cButtonAction4.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction5.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction6.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction7.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction8.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction9.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction10.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction11.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction12.SetEnableAndCheck(FALSE, FALSE);
		break;
	case STATUS_HOIST_BOOM_MENU:
	case STATUS_HOIST_STRAGE:
	case STATUS_HOIST_READY_LAMP:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(TRUE);
		break;
	case STATUS_HOIST_LEFT:
	case STATUS_HOIST_BOTH:
	case STATUS_HOIST_RIGHT:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(TRUE);
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(TRUE);
		break;
	case STATUS_HOIST_BOOM_SHORT:
	case STATUS_HOIST_BOOM_LONG:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(TRUE);
		break;
	}
}
