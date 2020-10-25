#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08_3.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置－発射制御器－電力分配器（トラベルロック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_3 ダイアログ

class CMenuFS08_3 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_3)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,		// 初期状態
		STATUS_WORKING		// 動作中
	};

public:
	CMenuFS08_3(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS08_3 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// 動作ステータス

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction28();
	afx_msg void OnBnClickedButtonAction29();
	afx_msg void OnBnClickedButtonAction30();

	CMenuButton m_cButtonAction28;
	CMenuButton m_cButtonAction29;
	CMenuButton m_cButtonAction30;
	CMenuButton m_cButtonExit;
};
