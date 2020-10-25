#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFE04.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�[�����u��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE04 �_�C�A���O

class CMenuFE04 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE04)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_BOOM_LONG,		// �u�[���L
		STATUS_BOOM_SHORT,		// �u�[���k
		STATUS_BOOM_WORKING		// �u�[�����쒆
	};

public:
	CMenuFE04(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FE04 };

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