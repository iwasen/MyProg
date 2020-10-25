#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS13.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���~�b�g�X�C�b�`�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS13 �_�C�A���O

class CMenuFS13 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS13)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,		// �������
		STATUS_START,		// �J�n��
		STATUS_READY,		// ��������
		STATUS_WORKING,		// ���쒆
		STATUS_COMPLETE,	// ���슮��
		STATUS_STRAGE		// ���[��
	};

public:
	CMenuFS13(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS13 };

protected:
	void SendAction(int nActionNo);
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// ����X�e�[�^�X
	BOOL m_bPause;			// �ꎞ��~�t���O

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

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonAction7;
	CMenuButton m_cButtonAction8;
};
