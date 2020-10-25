#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS06.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS06 ダイアログ

class CMenuFS06 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS06)

	// 射撃統制パネル電源動作ステータス
	enum EPanelStatus {
		STATUS_PANEL_OFF,		// OFF
		STATUS_PANEL_ON,		// ON
	};

	// 電源併入動作ステータス
	enum EPowerStatus {
		STATUS_POWER_OFF,		// OFF
		STATUS_POWER_ON,		// ON
	};

public:
	CMenuFS06(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS06 };

protected:
	void SetStatus(EPanelStatus ePanelStatus, EPowerStatus ePowerStatus);
	void SetMenuButton();

	EPanelStatus m_ePanelStatus;		// 射撃統制パネル電源動作ステータス
	EPowerStatus m_ePowerStatus;		// 電源併入動作ステータス

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
};
