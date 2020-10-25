#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08_2.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置－発射制御器－電力分配器（検知スイッチ）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_2 ダイアログ

class CMenuFS08_2 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_2)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,		// 初期状態
		STATUS_WORKING		// 動作中
	};

public:
	CMenuFS08_2(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS08_2 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// 動作ステータス

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
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
	afx_msg void OnBnClickedButtonAction25();
	afx_msg void OnBnClickedButtonAction26();
	afx_msg void OnBnClickedButtonAction27();

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
	CMenuButton m_cButtonAction25;
	CMenuButton m_cButtonAction26;
	CMenuButton m_cButtonAction27;
	CMenuButton m_cButtonExit;
public:
	virtual BOOL OnInitDialog();
};
