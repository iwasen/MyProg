#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS08_4.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ȉʒu�E�p���W�葕�u�|���ː����|�d�͕��z��i���ʁE����ʒu���o��y�уT�[�{���[�^�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_4 �_�C�A���O

class CMenuFS08_4 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_4)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,		// �������
		STATUS_WORKING		// ���쒆
	};

public:
	CMenuFS08_4(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS08_4 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// ����X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction31();
	afx_msg void OnBnClickedButtonAction32();
	afx_msg void OnBnClickedButtonAction33();
	afx_msg void OnBnClickedButtonAction34();
	afx_msg void OnBnClickedButtonAction35();
	afx_msg void OnBnClickedButtonAction36();
	afx_msg void OnBnClickedButtonAction37();

	CMenuButton m_cButtonAction31;
	CMenuButton m_cButtonAction32;
	CMenuButton m_cButtonAction33;
	CMenuButton m_cButtonAction34;
	CMenuButton m_cButtonAction35;
	CMenuButton m_cButtonAction36;
	CMenuButton m_cButtonAction37;
	CMenuButton m_cButtonExit;
};
