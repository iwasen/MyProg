#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFE05.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		爆風遮蔽装置画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE05 ダイアログ

class CMenuFE05 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE05)

	// 動作ステータス
	enum EStatus {
		STATUS_BOOM_LONG,		// ＬＰ／Ｃ固定
		STATUS_BOOM_SHORT,		// ＬＰ／Ｃ解放
		STATUS_BOOM_WORKING		// ＬＰ／Ｃ動作中
	};

public:
	CMenuFE05(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FE05 };

protected:
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;			// 動作ステータス

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
};
