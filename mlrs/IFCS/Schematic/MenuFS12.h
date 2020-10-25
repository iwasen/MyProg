#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS12.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ＬＰ／Ｃ－弾頭連接器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS12 ダイアログ

class CMenuFS12 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS12)

	// 電源併入動作ステータス
	enum EPowerStatus {
		STATUS_POWER_OFF,		// OFF
		STATUS_POWER_ON,		// ON
	};

	// パネル電源動作ステータス
	enum EPanelStatus {
		STATUS_PANEL_OFF,		// OFF
		STATUS_PANEL_ON,		// ON
	};

public:
	CMenuFS12(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS12 };

protected:
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EPowerStatus ePowerStatus, EPanelStatus ePanelStatus);
	void SetMenuButton();

	EPowerStatus m_ePowerStatus;		// 電源併入動作ステータス
	EPanelStatus m_ePanelStatus;		// 射撃統制パネル電源動作ステータス
	BOOL m_bWorking;					// 動作中フラグ

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

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
