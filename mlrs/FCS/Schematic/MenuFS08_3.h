#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS08_3.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ȉʒu�E�p���W�葕�u�|���ː����|�d�͕��z��i�g���x�����b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_3 �_�C�A���O

class CMenuFS08_3 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_3)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,		// �������
		STATUS_WORKING		// ���쒆
	};

public:
	CMenuFS08_3(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS08_3 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// ����X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction28();
	afx_msg void OnBnClickedButtonAction29();
	afx_msg void OnBnClickedButtonAction30();

	CMenuButton m_cButtonAction28;
	CMenuButton m_cButtonAction29;
	CMenuButton m_cButtonAction30;
	CMenuButton m_cButtonExit;
};
