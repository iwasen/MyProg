#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFE01.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ＬＰ／Ｃ固定装置画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE01 ダイアログ

class CMenuFE01 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE01)

	// 動作ステータス
	enum EStatus {
		STATUS_LPC_FIX,			// ＬＰ／Ｃ固定
		STATUS_LPC_REL,			// ＬＰ／Ｃ解放
		STATUS_LPC_WORKING		// ＬＰ／Ｃ動作中
	};

public:
	CMenuFE01(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FE01 };

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
