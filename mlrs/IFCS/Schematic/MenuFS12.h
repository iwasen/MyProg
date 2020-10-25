#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS12.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�k�o�^�b�|�e���A�ڊ��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS12 �_�C�A���O

class CMenuFS12 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS12)

	// �d����������X�e�[�^�X
	enum EPowerStatus {
		STATUS_POWER_OFF,		// OFF
		STATUS_POWER_ON,		// ON
	};

	// �p�l���d������X�e�[�^�X
	enum EPanelStatus {
		STATUS_PANEL_OFF,		// OFF
		STATUS_PANEL_ON,		// ON
	};

public:
	CMenuFS12(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS12 };

protected:
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EPowerStatus ePowerStatus, EPanelStatus ePanelStatus);
	void SetMenuButton();

	EPowerStatus m_ePowerStatus;		// �d����������X�e�[�^�X
	EPanelStatus m_ePanelStatus;		// �ˌ������p�l���d������X�e�[�^�X
	BOOL m_bWorking;					// ���쒆�t���O

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
