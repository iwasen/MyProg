#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS20.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�z�C�X�g���[�^�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS20 �_�C�A���O

class CMenuFS20 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS20)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,			// �������
		STATUS_BOOM_CONTROL,	// �u�[�����䃁�j���[ON
		STATUS_BOOM_SELECT,		// �u�[���I���X�C�b�`ON
		STATUS_BOOM_LEFT,		// ��
		STATUS_BOOM_BOTH,		// ����
		STATUS_BOOM_RIGHT,		// �E
		STATUS_BOOM_SHORT,		// �u�[���k
		STATUS_BOOM_LONG,		// �u�[���L
		STATUS_HOOK_UP_1,		// �t�b�N�㓮��1
		STATUS_HOOK_UP_2,		// �t�b�N�㓮��2
		STATUS_HOOK_DOWN_1,		// �t�b�N������1
		STATUS_HOOK_DOWN_2,		// �t�b�N������2
		STATUS_HOOK_STOP,		// �t�b�N��~
	};

public:
	CMenuFS20(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS20 };

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
	afx_msg void OnBnPushedButtonAction2();
	afx_msg void OnBnUnpushedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnPushedButtonAction7();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnPushedButtonAction8();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
	afx_msg void OnBnClickedButtonAction12();
	afx_msg void OnBnClickedButtonAction13();
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
};
