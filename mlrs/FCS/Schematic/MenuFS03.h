#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS03.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS03 ダイアログ

class CMenuFS03 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS03)

	// トラベルロック動作ステータス
	enum ETravelStatus {
		STATUS_TRAVEL_INITIAL,		// 初期状態
		STATUS_TRAVEL_CAGE_UP,		// CAGE UP
		STATUS_TRAVEL_CAGE_DOWN		// CAGE DOWN
	};

	// ホイストモータ動作ステータス
	enum EHoistStatus {
		STATUS_HOIST_INITIAL,		// 初期状態
		STATUS_HOIST_BOOM_MENU,		// ブーム制御メニュー
		STATUS_HOIST_READY_LAMP,	// 「使用可」表示灯
		STATUS_HOIST_LEFT,			// 左
		STATUS_HOIST_BOTH,			// 同時
		STATUS_HOIST_RIGHT,			// 右
		STATUS_HOIST_HOOK_UP,		// フック上
		STATUS_HOIST_HOOK_DOWN		// フック下
	};

public:
	CMenuFS03(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS03 };

protected:
	void SetStatus(ETravelStatus eTravelStatus, EHoistStatus eHoistStatus);
	void SetMenuButton();

	ETravelStatus m_eTravelStatus;		// トラベルロック動作ステータス
	EHoistStatus m_eHoistStatus;		// ホイストモータ動作ステータス

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
};
