#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFE01.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�k�o�^�b�Œ葕�u��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE01 �_�C�A���O

class CMenuFE01 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE01)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_LPC_FIX,			// �k�o�^�b�Œ�
		STATUS_LPC_REL,			// �k�o�^�b���
		STATUS_LPC_WORKING		// �k�o�^�b���쒆
	};

public:
	CMenuFE01(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FE01 };

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
