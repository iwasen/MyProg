#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS09.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		制御演算器－発射制御器－ＬＰ／Ｃ画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS09 ダイアログ

class CMenuFS09 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS09)

public:
	CMenuFS09(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS09 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
