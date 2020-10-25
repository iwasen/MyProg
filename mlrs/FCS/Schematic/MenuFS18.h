#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS18.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		発射機駆動装置（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS18 ダイアログ

class CMenuFS18 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS18)

	// 射撃統制パネル電源動作ステータス
	enum EPanelStatus {
		STATUS_PANEL_OFF,		// OFF
		STATUS_PANEL_ON,		// ON
		STATUS_PANEL_READY		// 動作完了
	};

	// 電源併入動作ステータス
	enum EPowerStatus {
		STATUS_POWER_OFF,		// OFF
		STATUS_POWER_ON,		// ON
		STATUS_POWER_READY		// 動作完了
	};

	// 射撃統制パネル動作ステータス
	enum EFireStatus {
		STATUS_FIRE_HIDE,		// 非表示
		STATUS_FIRE_INITIAL,	// 初期状態
		STATUS_FIRE_WORKING,	// 動作中
		STATUS_FIRE_STOP,		// 動作終了
		STATUS_FIRE_STRAGE		// 収納
	};

	// 射撃統制パネル動作ステータス
	enum EBoomStatus {
		STATUS_BOOM_HIDE,		// 非表示
		STATUS_BOOM_INITIAL,	// 初期状態
		STATUS_BOOM_WORKING,	// 動作中
		STATUS_BOOM_STOP		// 動作終了
	};

public:
	CMenuFS18(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS18 };

protected:
	void SendAction(int nActionNo);
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EPanelStatus ePanelStatus, EPowerStatus ePowerStatus, EFireStatus eFireStatus, EBoomStatus eBoomStatus);
	void SetMenuButton();

	EPanelStatus m_ePanelStatus;	// 射撃統制パネル電源動作ステータス
	EPowerStatus m_ePowerStatus;	// 電源併入動作ステータス
	EFireStatus m_eFireStatus;		// 射撃統制パネル動作ステータス
	EBoomStatus m_eBoomStatus;		// 射撃統制パネル動作ステータス
	BOOL m_bPause;					// 一時停止フラグ

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
	afx_msg void OnBnClickedButtonAction12();
	afx_msg void OnBnClickedButtonAction13();
	afx_msg void OnBnClickedButtonAction14();
	afx_msg void OnBnClickedButtonAction15();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonAction7;
	CMenuButton m_cButtonAction8;
	CMenuButton m_cButtonAction9;
	CMenuButton m_cButtonAction10;
	CMenuButton m_cButtonAction11;
	CMenuButton m_cButtonAction12;
	CMenuButton m_cButtonAction13;
	CMenuButton m_cButtonAction14;
	CMenuButton m_cButtonAction15;
	CStatic m_cStaticFirePanel;
	CStatic m_cStaticBoomPanel;
};
