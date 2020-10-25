#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFE02.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�g���x�����b�N��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE02 �_�C�A���O

class CMenuFE02 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE02)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_TRAVEL_FIX,		// �g���x�����b�N�Œ�
		STATUS_TRAVEL_REL,		// �g���x�����b�N���
		STATUS_TRAVEL_WORKING	// �g���x�����b�N���쒆
	};

public:
	CMenuFE02(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FE02 };

protected:
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;			// ����X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
};
