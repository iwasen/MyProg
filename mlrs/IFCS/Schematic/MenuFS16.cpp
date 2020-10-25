//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS16.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ʁE����ʒu���o��y�ы쓮�T�[�{���[�^�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS16.h"


// CMenuFS16 �_�C�A���O

IMPLEMENT_DYNCREATE(CMenuFS16, CMenuCommon)

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::CMenuFS16
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
CMenuFS16::CMenuFS16(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS16::IDD, pParent)
{
}

void CMenuFS16::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_BUTTON_ACTION_1, m_cButtonAction1);
	DDX_Control(pDX, IDC_BUTTON_ACTION_2, m_cButtonAction2);
	DDX_Control(pDX, IDC_BUTTON_ACTION_3, m_cButtonAction3);
	DDX_Control(pDX, IDC_BUTTON_ACTION_4, m_cButtonAction4);
	DDX_Control(pDX, IDC_BUTTON_ACTION_5, m_cButtonAction5);
}


BEGIN_MESSAGE_MAP(CMenuFS16, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFS16::OnBnClickedButtonAction1)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS16::OnBnClickedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFS16::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFS16::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFS16::OnBnClickedButtonAction5)
END_MESSAGE_MAP()


// CMenuFS16 ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::OnInitDialog
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
BOOL CMenuFS16::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// ���쒆�t���O���Z�b�g
	m_bWorking = FALSE;

	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_LPC_INITIAL, STATUS_ANZEN_INITIAL);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::OnBnClickedButtonAction1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�k�o�^�b����v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS16::OnBnClickedButtonAction1()
{
	// ���쒆�t���O�Z�b�g
	m_bWorking = TRUE;

	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_LPC_KAIHO, m_eAnzenStatus);

	// Flash�֑���w�����M
	SendAction(ACTION_1);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::OnBnClickedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�k�o�^�b�Œ�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS16::OnBnClickedButtonAction2()
{
	// ���쒆�t���O�Z�b�g
	m_bWorking = TRUE;

	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_LPC_KOTEI, m_eAnzenStatus);

	// Flash�֑���w�����M
	SendAction(ACTION_2);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::OnBnClickedButtonAction3
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���S�x����t�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS16::OnBnClickedButtonAction3()
{
	// ���쒆�t���O�Z�b�g
	m_bWorking = TRUE;

	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eLpcStatus, STATUS_ANZEN_TORITSUKE);

	// Flash�֑���w�����M
	SendAction(ACTION_3);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::OnBnClickedButtonAction4
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���S�x����O���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS16::OnBnClickedButtonAction4()
{
	// ���쒆�t���O�Z�b�g
	m_bWorking = TRUE;

	// ����X�e�[�^�X�ݒ�
	SetStatus(m_eLpcStatus, STATUS_ANZEN_TORIHAZUSHI);

	// Flash�֑���w�����M
	SendAction(ACTION_4);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::OnBnClickedButtonAction5
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
void CMenuFS16::OnBnClickedButtonAction5()
{
	// ���쒆�t���O���Z�b�g
	m_bWorking = FALSE;

	// ����X�e�[�^�X�ݒ�
	SetStatus(STATUS_LPC_INITIAL, STATUS_ANZEN_INITIAL);

	// Flash�֑���w�����M
	SendAction(ACTION_5);
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::ActionEnd
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
void CMenuFS16::ActionEnd(int nActionNo)
{
	if (m_bWorking) {
		// ���쒆�t���O���Z�b�g
		m_bWorking = FALSE;

		// ���j���[�{�^���ݒ�
		SetMenuButton();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::SetStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		����X�e�[�^�X�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		EStatus		eStatus		[I] �ݒ�X�e�[�^�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMenuFS16::SetStatus(ELpcStatus eLpcStatus, EAnzenStatus eAnzenStatus)
{
	// ����X�e�[�^�X�ݒ�
	m_eLpcStatus = eLpcStatus;
	m_eAnzenStatus = eAnzenStatus;

	// ���j���[�{�^���ݒ�
	SetMenuButton();
}

//*****************************************************************************************************
//  1. �֐���
//		CMenuFS16::SetMenuButton
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
void CMenuFS16::SetMenuButton()
{
	switch (m_eLpcStatus) {
	case STATUS_LPC_INITIAL:
		m_cButtonAction1.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction2.SetEnableAndCheck(TRUE, FALSE);
		break;
	case STATUS_LPC_KAIHO:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnableAndCheck(TRUE, FALSE);
		break;
	case STATUS_LPC_KOTEI:
		m_cButtonAction1.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction2.SetEnableAndCheck(TRUE, TRUE);
		break;
	}

	switch (m_eAnzenStatus) {
	case STATUS_ANZEN_INITIAL:
		m_cButtonAction3.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction4.SetEnableAndCheck(TRUE, FALSE);
		break;
	case STATUS_ANZEN_TORITSUKE:
		m_cButtonAction3.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction4.SetEnableAndCheck(TRUE, FALSE);
		break;
	case STATUS_ANZEN_TORIHAZUSHI:
		m_cButtonAction3.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction4.SetEnableAndCheck(FALSE, TRUE);
		break;
	}

	if (m_bWorking) {
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(TRUE);
	} else {
		m_cButtonAction5.SetEnable(m_eLpcStatus != STATUS_LPC_INITIAL || m_eAnzenStatus != STATUS_ANZEN_INITIAL);
	}
}