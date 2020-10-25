#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFE03.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�z�C�X�g�`��������ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE03 �_�C�A���O

class CMenuFE03 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE03)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_HOOK_DOWN,		// �t�b�N��
		STATUS_HOOK_UP,			// �t�b�N��
		STATUS_HOOK_WORKING		// �t�b�N���쒆
	};

public:
	CMenuFE03(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FE03 };

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
