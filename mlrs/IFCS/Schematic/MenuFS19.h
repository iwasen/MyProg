#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS19.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		検知スイッチ（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS19 ダイアログ

class CMenuFS19 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS19)

	// 検知スイッチ動作ステータス
	enum EStatus {
		STATUS_INITIAL,		// 初期状態
		STATUS_WORKING,		// スタートボタン動作中
		STATUS_ENABLE		// 検知スイッチ有効
	};

public:
	CMenuFS19(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS19 };

protected:
	void SetStatus(EStatus eStatus);
	void ActionEnd(int nActionNo);
	void SetMenuButton();

	EStatus m_eStatus;		// 動作ステータス

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
};
