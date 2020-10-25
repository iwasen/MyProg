//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS19.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�[�����[�^�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************
#pragma once

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS19 �_�C�A���O

class CMenuFS19 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS19)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,			// �������
		STATUS_BOOM_CONTROL,	// �u�[�����䃁�j���[ON
		STATUS_BOOM_SELECT,		// �u�[���I���X�C�b�`ON
		STATUS_BOOM_LEFT,		// ��
		STATUS_BOOM_BOTH,		// ����
		STATUS_BOOM_RIGHT,		// �E
		STATUS_BOOM_SHORT_1,	// �u�[���k����1
		STATUS_BOOM_SHORT_2,	// �u�[���k����2
		STATUS_BOOM_LONG_1,		// �u�[���L����1
		STATUS_BOOM_LONG_2,		// �u�[���L����2
		STATUS_HOOK_UP,			// �t�b�N��
		STATUS_HOOK_DOWN		// �t�b�N��
	};

public:
	CMenuFS19(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS19 };

protected:
	void SendAction(int nActionNo, int nParam2 = 0);
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus, BOOL bWorking = FALSE);
	void SetMenuButton();

	EStatus m_eStatus;			// ����X�e�[�^�X
	BOOL m_bWorking;			// ���쒆�t���O
	BOOL m_bPause;				// �ꎞ��~�t���O

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnPushedButtonAction3();
	afx_msg void OnBnUnpushedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
	afx_msg void OnBnPushedButtonAction12();
	afx_msg void OnBnClickedButtonAction12();
	afx_msg void OnBnPushedButtonAction13();
	afx_msg void OnBnClickedButtonAction13();
	afx_msg void OnBnClickedButtonAction14();
	afx_msg void OnTimer(UINT_PTR nIDEvent);

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
};
