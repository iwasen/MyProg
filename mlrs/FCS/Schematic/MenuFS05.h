#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS05.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS05 ダイアログ

class CMenuFS05 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS05)

	// 検知スイッチ動作ステータス
	enum ESwitchStatus {
		STATUS_SWITCH_INITIAL,		// 初期状態
		STATUS_SWITCH_WORKING		// 検知スイッチ動作中
	};

	// 駆動サーボモータ動作ステータス
	enum EMotorStatus {
		STATUS_MOTOR_INITIAL,		// 初期状態
		STATUS_MOTOR_WORKING		// 駆動サーボモータ動作中
	};

	// 方位・高低油圧バルブ動作ステータス
	enum EBulbStatus {
		STATUS_BULB_INITIAL,		// 初期状態
		STATUS_BULB_WORKING			// 方位・高低油圧バルブ動作中
	};

public:
	CMenuFS05(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS05 };

protected:
	void SetStatus(ESwitchStatus eSwitchStatus, EMotorStatus eMotorStatus, EBulbStatus eBulbStatus);
	void SetMenuButton();

	ESwitchStatus m_eSwitchStatus;	// 検知スイッチ動作ステータス
	EMotorStatus m_eMotorStatus;	// 駆動サーボモータ動作ステータス
	EBulbStatus m_eBulbStatus;		// 方位・高低油圧バルブ動作ステータス

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
	afx_msg void OnBnClickedButtonAction16();
	afx_msg void OnBnClickedButtonAction17();
	afx_msg void OnBnClickedButtonAction18();
	afx_msg void OnBnClickedButtonAction19();
	afx_msg void OnBnClickedButtonAction20();
	afx_msg void OnBnClickedButtonAction21();
	afx_msg void OnBnClickedButtonAction22();
	afx_msg void OnBnClickedButtonAction23();
	afx_msg void OnBnClickedButtonAction24();

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
	CMenuButton m_cButtonAction16;
	CMenuButton m_cButtonAction17;
	CMenuButton m_cButtonAction18;
	CMenuButton m_cButtonAction19;
	CMenuButton m_cButtonAction20;
	CMenuButton m_cButtonAction21;
	CMenuButton m_cButtonAction22;
	CMenuButton m_cButtonAction23;
	CMenuButton m_cButtonAction24;
};
