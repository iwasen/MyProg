#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFE03.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ホイストＡｓｓｙ画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE03 ダイアログ

class CMenuFE03 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE03)

	// 動作ステータス
	enum EStatus {
		STATUS_HOOK_DOWN,		// フック下
		STATUS_HOOK_UP,			// フック上
		STATUS_HOOK_WORKING		// フック動作中
	};

public:
	CMenuFE03(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FE03 };

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
