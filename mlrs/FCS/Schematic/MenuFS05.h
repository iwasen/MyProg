#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS05.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS05 �_�C�A���O

class CMenuFS05 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS05)

	// ���m�X�C�b�`����X�e�[�^�X
	enum ESwitchStatus {
		STATUS_SWITCH_INITIAL,		// �������
		STATUS_SWITCH_WORKING		// ���m�X�C�b�`���쒆
	};

	// �쓮�T�[�{���[�^����X�e�[�^�X
	enum EMotorStatus {
		STATUS_MOTOR_INITIAL,		// �������
		STATUS_MOTOR_WORKING		// �쓮�T�[�{���[�^���쒆
	};

	// ���ʁE��������o���u����X�e�[�^�X
	enum EBulbStatus {
		STATUS_BULB_INITIAL,		// �������
		STATUS_BULB_WORKING			// ���ʁE��������o���u���쒆
	};

public:
	CMenuFS05(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS05 };

protected:
	void SetStatus(ESwitchStatus eSwitchStatus, EMotorStatus eMotorStatus, EBulbStatus eBulbStatus);
	void SetMenuButton();

	ESwitchStatus m_eSwitchStatus;	// ���m�X�C�b�`����X�e�[�^�X
	EMotorStatus m_eMotorStatus;	// �쓮�T�[�{���[�^����X�e�[�^�X
	EBulbStatus m_eBulbStatus;		// ���ʁE��������o���u����X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
	afx_msg void OnBnClickedButtonAction12();
	afx_msg void OnBnClickedButtonAction13();
	afx_msg void OnBnClickedButtonAction14();
	afx_msg void OnBnClickedButtonAction15();
	afx_msg void OnBnClickedButtonAction16();
	afx_msg void OnBnClickedButtonAction17();
	afx_msg void OnBnClickedButtonAction18();
	afx_msg void OnBnClickedButtonAction19();
	afx_msg void OnBnClickedButtonAction20();
	afx_msg void OnBnClickedButtonAction21();
	afx_msg void OnBnClickedButtonAction22();
	afx_msg void OnBnClickedButtonAction23();
	afx_msg void OnBnClickedButtonAction24();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonAction7;
	CMenuButton m_cButtonAction8;
	CMenuButton m_cButtonAction9;
	CMenuButton m_cButtonAction10;
	CMenuButton m_cButtonAction11;
	CMenuButton m_cButtonAction12;
	CMenuButton m_cButtonAction13;
	CMenuButton m_cButtonAction14;
	CMenuButton m_cButtonAction15;
	CMenuButton m_cButtonAction16;
	CMenuButton m_cButtonAction17;
	CMenuButton m_cButtonAction18;
	CMenuButton m_cButtonAction19;
	CMenuButton m_cButtonAction20;
	CMenuButton m_cButtonAction21;
	CMenuButton m_cButtonAction22;
	CMenuButton m_cButtonAction23;
	CMenuButton m_cButtonAction24;
};
