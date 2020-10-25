#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS08_5.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ȉʒu�E�p���W�葕�u�|���ː����|�d�͕��z��i���ʁE��������o���u�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_5 �_�C�A���O

class CMenuFS08_5 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_5)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,		// �������
		STATUS_WORKING		// ���쒆
	};

public:
	CMenuFS08_5(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS08_5 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// ����X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction38();
	afx_msg void OnBnClickedButtonAction39();

	CMenuButton m_cButtonAction38;
	CMenuButton m_cButtonAction39;
	CMenuButton m_cButtonExit;
};
