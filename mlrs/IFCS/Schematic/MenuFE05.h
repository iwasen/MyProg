#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFE05.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����Օ����u��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE05 �_�C�A���O

class CMenuFE05 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE05)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_BOOM_LONG,		// �k�o�^�b�Œ�
		STATUS_BOOM_SHORT,		// �k�o�^�b���
		STATUS_BOOM_WORKING		// �k�o�^�b���쒆
	};

public:
	CMenuFE05(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FE05 };

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
