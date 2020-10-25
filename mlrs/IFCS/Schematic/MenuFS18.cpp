//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS18.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�z�C�X�g���[�^�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS18.h"

// �^�C�}�[ID
#define IDT_HOOK_UP_STOP		1
#define IDT_HOOK_DOWN_STOP		2

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
	m_bWorking = FALSE;
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
}


BEGIN_MESSAGE_MAP(CMenuFS18, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFS18::OnBnClickedButtonAction1)
	ON_CONTROL(BN_PUSHED, IDC_BUTTON_ACTION_2, &CMenuFS18::OnBnPushedButtonAction2)
	ON_CONTROL(BN_UNPUSHED, IDC_BUTTON_ACTION_2, &CMenuFS18::OnBnUnpushedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFS18::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFS18::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFS18::OnBnClickedButtonAction5)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_6, &CMenuFS18::OnBnClickedButtonAction6)
	ON_CONTROL(BN_PUSHED, IDC_BUTTON_ACTION_7, &CMenuFS18::OnBnPushedButtonAction7)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_7, &CMenuFS18::OnBnClickedButtonAction7)
	ON_CONTROL(BN_PUSHED, IDC_BUTTON_ACTION_8, &CMenuFS18::OnBnPushedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_8, &CMenuFS18::OnBnClickedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_9, &CMenuFS18::OnBnClickedButtonAction9)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_10, &CMenuFS18::OnBnClickedButtonAction10)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_11, &CMenuFS18::OnBnClickedButtonAction11)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_12, &CMenuFS18::OnBnClickedButtonAction12)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_13, &CMenuFS18::OnBnClickedButtonAction13)
	ON_WM_TIMER()
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
	SetStatus(STATUS_INITIAL, FALSE);

	return FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction1
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
void CMenuFS18::OnBnClickedButtonAction1()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_BOOM_CONTROL, TRUE);

	// Flash�֑���w�����M
	SendAction(ACTION_1);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnPushedButtonAction2
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
void CMenuFS18::OnBnPushedButtonAction2()
{
	// Flash�֑���w�����M
	SendAction(ACTION_2, 1);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnUnpushedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�g�p�\�����v�{�^�������[�X����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnUnpushedButtonAction2()
{
	// Flash�֑���w�����M
	SendAction(ACTION_2, 0);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction3
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�u�[���I���X�C�b�`�v�{�^���N���b�N����
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
	SetStatus(STATUS_BOOM_SELECT, TRUE);

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
	SetStatus(STATUS_BOOM_LEFT, TRUE);

	// Flash�֑���w�����M
	SendAction(ACTION_4);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction5
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
void CMenuFS18::OnBnClickedButtonAction5()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_BOOM_BOTH, TRUE);

	// Flash�֑���w�����M
	SendAction(ACTION_5);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction6
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
void CMenuFS18::OnBnClickedButtonAction6()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_BOOM_RIGHT, TRUE);

	// Flash�֑���w�����M
	SendAction(ACTION_6);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnPushedButtonAction7
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�u�[���k�v�{�^���v�b�V������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnPushedButtonAction7()
{
	// Flash�֑���w�����M
	SendAction(ACTION_7, 1);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction7
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
void CMenuFS18::OnBnClickedButtonAction7()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_BOOM_SHORT, TRUE);

	// Flash�֑���w�����M
	SendAction(ACTION_7);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnPushedButtonAction8
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�u�[���L�v�{�^���v�b�V������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnBnPushedButtonAction8()
{
	// Flash�֑���w�����M
	SendAction(ACTION_8, 1);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction8
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
void CMenuFS18::OnBnClickedButtonAction8()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_BOOM_LONG, TRUE);

	// Flash�֑���w�����M
	SendAction(ACTION_8);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction9
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�t�b�N��v�{�^���N���b�N����
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
	SetStatus(STATUS_HOOK_UP_1, TRUE);

	// Flash�֑���w�����M
	SendAction(ACTION_9);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction10
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�t�b�N���~�v�{�^���N���b�N����
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
	SetStatus(STATUS_HOOK_STOP);

	// Flash�֑���w�����M
	SendAction(ACTION_10);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction11
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�t�b�N���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
// 
void CMenuFS18::OnBnClickedButtonAction11()
{
	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_HOOK_DOWN_1, TRUE);

	// Flash�֑���w�����M
	SendAction(ACTION_11);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction12
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�t�b�N����~�v�{�^���N���b�N����
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
	SetStatus(STATUS_HOOK_STOP);

	// Flash�֑���w�����M
	SendAction(ACTION_12);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnBnClickedButtonAction13
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
void CMenuFS18::OnBnClickedButtonAction13()
{
	// �u�ᑬ�v�u�ʏ�v�{�^���̗L����
	EnableSpeedButton(m_bPause);

	// �ꎞ��~�X�e�[�^�X�ݒ�
	m_bPause = !m_bPause;
	SetMenuButton();

	// Flash�֑���w�����M
	SendAction(ACTION_13);
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
void CMenuFS18::SendAction(int nActionNo, int nParam2)
{
	CUIntArray aParams;

	aParams.Add(m_bPause);
	aParams.Add(nParam2);
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
	if (m_bWorking) {
		switch (nActionNo) {
		case ACTION_9:
			if (m_eStatus == STATUS_HOOK_UP_1)
				SetTimer(IDT_HOOK_UP_STOP, 5000, NULL);
			break;
		case ACTION_11:
			if (m_eStatus == STATUS_HOOK_DOWN_1)
				SetTimer(IDT_HOOK_DOWN_STOP, 5000, NULL);
			break;
		}

		m_bWorking = FALSE;

		SetMenuButton();
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
//		EStatus		eStatus		[I] �ݒ�X�e�[�^�X
//		BOOL		bWorking	[I] ���쒆�t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::SetStatus(EStatus eStatus, BOOL bWorking)
{
	// ����X�e�[�^�X�ݒ�
	m_eStatus = eStatus;
	m_bWorking |= bWorking;

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
	switch (m_eStatus) {
	case STATUS_INITIAL:
		m_cButtonAction1.SetEnableAndCheck(TRUE);
		m_cButtonAction2.SetEnable(TRUE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnableAndCheck(FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_CONTROL:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnableAndCheck(FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_SELECT:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnableAndCheck(TRUE);
		m_cButtonAction5.SetEnableAndCheck(TRUE);
		m_cButtonAction6.SetEnableAndCheck(TRUE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_LEFT:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction5.SetEnableAndCheck(FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(TRUE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_BOTH:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnableAndCheck(FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction6.SetEnableAndCheck(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_RIGHT:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnableAndCheck(FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_SHORT:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(TRUE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_LONG:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_UP_1:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(TRUE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_UP_2:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(TRUE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_DOWN_1:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(TRUE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_DOWN_2:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(TRUE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_STOP:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(TRUE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	}

	if (m_bWorking) {
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(TRUE);
	}

	m_cButtonAction13 = m_bPause ? "��~����" : "�ꎞ��~";
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS18::OnTimer
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j���[�{�^���ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		UINT_PTR	nIDEvent		[I] �^�C�}�[�C�x���gID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS18::OnTimer(UINT_PTR nIDEvent)
{
	switch (nIDEvent) {
	case IDT_HOOK_UP_STOP:
		if (m_eStatus == STATUS_HOOK_UP_1) {
			// ����X�e�[�^�X�ݒ�
			SetStatus(STATUS_HOOK_UP_2, TRUE);

			// Flash�֑���w�����M
			SendAction(ACTION_9, 1);
		}
		break;
	case IDT_HOOK_DOWN_STOP:
		if (m_eStatus == STATUS_HOOK_DOWN_1) {
			// ����X�e�[�^�X�ݒ�
			SetStatus(STATUS_HOOK_DOWN_2, TRUE);

			// Flash�֑���w�����M
			SendAction(ACTION_11, 1);
		}
		break;
	}

	// �^�C�}�[��~
	KillTimer(nIDEvent);

	CMenuCommon::OnTimer(nIDEvent);
}
