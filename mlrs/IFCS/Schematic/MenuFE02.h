#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFE02.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		トラベルロック画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE02 ダイアログ

class CMenuFE02 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE02)

	// 動作ステータス
	enum EStatus {
		STATUS_TRAVEL_FIX,		// トラベルロック固定
		STATUS_TRAVEL_REL,		// トラベルロック解放
		STATUS_TRAVEL_WORKING	// トラベルロック動作中
	};

public:
	CMenuFE02(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FE02 };

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
