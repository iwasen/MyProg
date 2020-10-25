#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS08_1.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ȉʒu�E�p���W�葕�u�|���ː����|�d�͕��z���ʁi���ˋ@�쓮���u�j�̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_1 �_�C�A���O

class CMenuFS08_1 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_1)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,		// �������
		STATUS_WORKING		// ���쒆
	};

public:
	CMenuFS08_1(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS08_1 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// ����X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();

	CMenuButton m_cButtonAction7;
	CMenuButton m_cButtonAction8;
	CMenuButton m_cButtonAction9;
	CMenuButton m_cButtonAction10;
	CMenuButton m_cButtonAction11;
	CMenuButton m_cButtonExit;
};
