//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS18.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ˋ@�쓮���u�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS18.h"


// CMenuFS18 �_�C�A���O

IMPLEMENT_DYNCREATE(CMenuFS18, CMenuCommon)

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::CMenuFS18
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
CMenuFS18::CMenuFS18(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS18::IDD, pParent)
{
	m_bPause = FALSE;
}

void CMenuFS18::DoDataExchange(CDataExchange* pDX)
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
	DDX_Control(pDX, IDC_BUTTON_ACTION_13, m_cButtonAction13);
	DDX_Control(pDX, IDC_BUTTON_ACTION_14, m_cButtonAction14);
	DDX_Control(pDX, IDC_BUTTON_ACTION_15, m_cButtonAction15);
	DDX_Control(pDX, IDC_STATIC_FIRE_PANEL, m_cStaticFirePanel);
	DDX_Control(pDX, IDC_STATIC_BOOM_PANEL, m_cStaticBoomPanel);
}


BEGIN_MESSAGE_MAP(CMenuFS18, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFS18::OnBnClickedButtonAction1)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS18::OnBnClickedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFS18::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFS18::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFS18::OnBnClickedButtonAction5)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_6, &CMenuFS18::OnBnClickedButtonAction6)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_7, &CMenuFS18::OnBnClickedButtonAction7)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_8, &CMenuFS18::OnBnClickedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_9, &CMenuFS18::OnBnClickedButtonAction9)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_10, &CMenuFS18::OnBnClickedButtonAction10)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_11, &CMenuFS18::OnBnClickedButtonAction11)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_12, &CMenuFS18::OnBnClickedButtonAction12)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_13, &CMenuFS18::OnBnClickedButtonAction13)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_14, &CMenuFS18::OnBnClickedButtonAction14)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_15, &CMenuFS18::OnBnClickedButtonAction15)
END_MESSAGE_MAP()


// CMenuFS18 ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnInitDialog
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
BOOL CMenuFS18::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_PANEL_OFF, STATUS_POWER_OFF, STATUS_FIRE_HIDE, STATUS_BOOM_HIDE);

	return FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�n�e�e�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction1()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_PANEL_OFF, m_ePowerStatus, STATUS_FIRE_HIDE, STATUS_BOOM_HIDE);

	// Flash�֑���w�����M
	SendAction(ACTION_1);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�n�m�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction2()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_PANEL_ON, m_ePowerStatus, STATUS_FIRE_HIDE, STATUS_BOOM_HIDE);

	// Flash�֑���w�����M
	SendAction(ACTION_2);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction3
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�؁v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction3()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_PANEL_OFF, STATUS_POWER_OFF, STATUS_FIRE_HIDE, STATUS_BOOM_HIDE);

	// Flash�֑���w�����M
	SendAction(ACTION_3);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction4
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
void CMenuFS18::OnBnClickedButtonAction4()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_PANEL_OFF, STATUS_POWER_ON, STATUS_FIRE_HIDE, STATUS_BOOM_HIDE);

	// Flash�֑���w�����M
	SendAction(ACTION_4);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction5
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@���@�E�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction5()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, STATUS_FIRE_WORKING, m_eBoomStatus);

	// ���[�{�^��OFF
	m_cButtonAction10.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_5);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction6
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@���@���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction6()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, STATUS_FIRE_WORKING, m_eBoomStatus);

	// ���[�{�^��OFF
	m_cButtonAction10.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_6);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction7
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@������i�E����j�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction7()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, STATUS_FIRE_WORKING, m_eBoomStatus);

	// ���[�{�^��OFF
	m_cButtonAction10.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_7);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction8
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@������i������j�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction8()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, STATUS_FIRE_WORKING, m_eBoomStatus);

	// ���[�{�^��OFF
	m_cButtonAction10.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_8);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction9
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�u�[������푀��v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction9()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, STATUS_FIRE_WORKING, m_eBoomStatus);

	// ���[�{�^��OFF
	m_cButtonAction10.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_9);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction10
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���[�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction10()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, STATUS_FIRE_WORKING, STATUS_BOOM_HIDE);

	// ���ˋ@���{�^��OFF
	m_cButtonAction5.SetCheck(FALSE);
	m_cButtonAction6.SetCheck(FALSE);
	m_cButtonAction7.SetCheck(FALSE);
	m_cButtonAction8.SetCheck(FALSE);
	m_cButtonAction9.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_10);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction11
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@���@�E�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction11()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, m_eFireStatus, STATUS_BOOM_WORKING);

	// �u�[�������{�^��OFF
	m_cButtonAction12.SetCheck(FALSE);
	m_cButtonAction13.SetCheck(FALSE);
	m_cButtonAction14.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_11);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction12
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@���@���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction12()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, m_eFireStatus, STATUS_BOOM_WORKING);

	// �u�[�������{�^��OFF
	m_cButtonAction11.SetCheck(FALSE);
	m_cButtonAction13.SetCheck(FALSE);
	m_cButtonAction14.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_12);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction13
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@���@��v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction13()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, m_eFireStatus, STATUS_BOOM_WORKING);

	// �u�[�������{�^��OFF
	m_cButtonAction11.SetCheck(FALSE);
	m_cButtonAction12.SetCheck(FALSE);
	m_cButtonAction14.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_13);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction14
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���ˋ@���@���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction14()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(m_ePanelStatus, m_ePowerStatus, m_eFireStatus, STATUS_BOOM_WORKING);

	// �u�[�������{�^��OFF
	m_cButtonAction11.SetCheck(FALSE);
	m_cButtonAction12.SetCheck(FALSE);
	m_cButtonAction13.SetCheck(FALSE);

	// Flash�֑���w�����M
	SendAction(ACTION_14);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction15
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�ꎞ��~�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction15()
{
	// �u�ᑬ�v�u�ʏ�v�{�^���̗L����
	EnableSpeedButton(m_bPause);

	// �ꎞ��~�X�e�[�^�X�ݒ�
	m_bPause = !m_bPause;
	SetMenuButton();

	// Flash�֑���w�����M
	SendAction(ACTION_15);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::SendAction
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j���[�{�^���ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		int		nActionNo		[I] �A�N�V�����ԍ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::SendAction(int nActionNo)
{
	CUIntArray aParams;

	aParams.Add(m_bPause);
	CMenuCommon::SendAction(nActionNo, &aParams);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::ActionEnd
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Flash����̓���I���ʒm��M����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		int		nActionNo		[I] �A�N�V�����ԍ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::ActionEnd(int nActionNo)
{
	switch (nActionNo) {
	case ACTION_2:
		SetStatus(STATUS_PANEL_READY, m_ePowerStatus, STATUS_FIRE_INITIAL, STATUS_BOOM_HIDE);
		break;
	case ACTION_4:
		SetStatus(m_ePanelStatus, STATUS_POWER_READY, STATUS_FIRE_HIDE, STATUS_BOOM_HIDE);
		break;
	case ACTION_5:
	case ACTION_6:
	case ACTION_7:
	case ACTION_8:
	case ACTION_9:
		SetStatus(m_ePanelStatus, m_ePowerStatus, STATUS_FIRE_STOP, STATUS_BOOM_INITIAL);
		break;
	case ACTION_10:
		SetStatus(m_ePanelStatus, m_ePowerStatus, STATUS_FIRE_STRAGE, STATUS_BOOM_HIDE);
		break;
	case ACTION_11:
	case ACTION_12:
	case ACTION_13:
	case ACTION_14:
		SetStatus(m_ePanelStatus, m_ePowerStatus, m_eFireStatus, STATUS_BOOM_STOP);
		break;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::SetStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		����X�e�[�^�X�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		EPanelStatus	ePanelStatus		[I] �ˌ������p�l���d������X�e�[�^�X
//		EPowerStatus	ePowerStatus		[I] �d����������X�e�[�^�X
//		EFireStatus		eFireStatus			[I] �ˌ������p�l������X�e�[�^�X
//		EBoomStatus		eBoomStatus			[I] �ˌ������p�l������X�e�[�^�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::SetStatus(EPanelStatus ePanelStatus, EPowerStatus ePowerStatus, EFireStatus eFireStatus, EBoomStatus eBoomStatus)
{
	// ����X�e�[�^�X�ݒ�
	m_ePanelStatus = ePanelStatus;
	m_ePowerStatus = ePowerStatus;
	m_eFireStatus = eFireStatus;
	m_eBoomStatus = eBoomStatus;

	// ���j���[�{�^���ݒ�
	SetMenuButton();
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::SetMenuButton
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
void CMenuFS18::SetMenuButton()
{
	BOOL bEnablePause = FALSE;

	switch (m_ePanelStatus) {
	case STATUS_PANEL_OFF:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnableAndCheck(m_ePowerStatus == STATUS_POWER_READY);
		break;
	case STATUS_PANEL_ON:
		m_cButtonAction1.SetEnableAndCheck(FALSE);
		m_cButtonAction2.SetEnableAndCheck(FALSE, TRUE);

		bEnablePause = TRUE;
		break;
	case STATUS_PANEL_READY:
		m_cButtonAction1.SetEnableAndCheck(m_ePowerStatus == STATUS_POWER_READY && (m_eFireStatus == STATUS_FIRE_INITIAL || m_eFireStatus == STATUS_FIRE_STRAGE));
		m_cButtonAction2.SetEnableAndCheck(FALSE, TRUE);
		break;
	}

	switch (m_ePowerStatus) {
	case STATUS_POWER_OFF:
		m_cButtonAction3.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction4.SetEnableAndCheck(TRUE);
		break;
	case STATUS_POWER_ON:
		m_cButtonAction3.SetEnableAndCheck(FALSE);
		m_cButtonAction4.SetEnableAndCheck(FALSE, TRUE);

		bEnablePause = TRUE;
		break;
	case STATUS_POWER_READY:
		m_cButtonAction3.SetEnableAndCheck(m_ePanelStatus == STATUS_PANEL_OFF);
		m_cButtonAction4.SetEnableAndCheck(FALSE, TRUE);
		break;
	}

	BOOL bShowFire = TRUE;
	switch (m_eFireStatus) {
	case STATUS_FIRE_HIDE:
		bShowFire = FALSE;
		break;
	case STATUS_FIRE_INITIAL:
		m_cButtonAction5.SetEnableAndCheck(TRUE);
		m_cButtonAction6.SetEnableAndCheck(TRUE);
		m_cButtonAction7.SetEnableAndCheck(TRUE);
		m_cButtonAction8.SetEnableAndCheck(TRUE);
		m_cButtonAction9.SetEnableAndCheck(TRUE);
		m_cButtonAction10.SetEnableAndCheck(FALSE);
		break;
	case STATUS_FIRE_STOP:		// ����I��
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnableAndCheck(TRUE);
		break;
	case STATUS_FIRE_WORKING:	// ���쒆
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);

		bEnablePause = TRUE;
		break;
	case STATUS_FIRE_STRAGE:
		m_cButtonAction5.SetEnableAndCheck(TRUE);
		m_cButtonAction6.SetEnableAndCheck(TRUE);
		m_cButtonAction7.SetEnableAndCheck(TRUE);
		m_cButtonAction8.SetEnableAndCheck(TRUE);
		m_cButtonAction9.SetEnableAndCheck(TRUE);
		m_cButtonAction10.SetEnableAndCheck(FALSE, TRUE);
		break;
	}

	m_cButtonAction5.ShowWindow(bShowFire);
	m_cButtonAction6.ShowWindow(bShowFire);
	m_cButtonAction7.ShowWindow(bShowFire);
	m_cButtonAction8.ShowWindow(bShowFire);
	m_cButtonAction9.ShowWindow(bShowFire);
	m_cButtonAction10.ShowWindow(bShowFire);
	m_cStaticFirePanel.ShowWindow(bShowFire);

	BOOL bShowBoom = TRUE;
	switch (m_eBoomStatus) {
	case STATUS_BOOM_HIDE:
		bShowBoom = FALSE;
		break;
	case STATUS_BOOM_INITIAL:
		m_cButtonAction11.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction12.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction13.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction14.SetEnableAndCheck(TRUE, FALSE);
		break;
	case STATUS_BOOM_WORKING:
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		m_cButtonAction14.SetEnable(FALSE);
		bEnablePause = TRUE;
		break;
	case STATUS_BOOM_STOP:
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(TRUE);
		m_cButtonAction13.SetEnable(TRUE);
		m_cButtonAction14.SetEnable(TRUE);
		break;
	}

	m_cButtonAction11.ShowWindow(bShowBoom);
	m_cButtonAction12.ShowWindow(bShowBoom);
	m_cButtonAction13.ShowWindow(bShowBoom);
	m_cButtonAction14.ShowWindow(bShowBoom);
	m_cStaticBoomPanel.ShowWindow(bShowBoom);

	m_cButtonAction15.SetEnable(bEnablePause);
	m_cButtonAction15 = m_bPause ? "��~����" : "�ꎞ��~";
}
