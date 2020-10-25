#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS10.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		制御演算器－弾頭連接器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS10 ダイアログ

class CMenuFS10 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS10)

public:
	CMenuFS10(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS10 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
