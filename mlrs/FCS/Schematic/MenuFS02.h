#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS02.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS02 �_�C�A���O

class CMenuFS02 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS02)

	// �ˌ������p�l���d������X�e�[�^�X
	enum EPanelStatus {
		STATUS_PANEL_OFF,		// OFF
		STATUS_PANEL_ON,		// ON
	};

	// �d����������X�e�[�^�X
	enum EPowerStatus {
		STATUS_POWER_OFF,		// OFF
		STATUS_POWER_ON,		// ON
	};

public:
	CMenuFS02(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS02 };

protected:
	void SetStatus(EPanelStatus ePanelStatus, EPowerStatus ePowerStatus);
	void SetMenuButton();

	EPanelStatus m_ePanelStatus;		// �ˌ������p�l���d������X�e�[�^�X
	EPowerStatus m_ePowerStatus;		// �d����������X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
};
