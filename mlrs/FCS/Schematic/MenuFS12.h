#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS12.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ＬＰ／Ｃ－弾頭連接器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS12 ダイアログ

class CMenuFS12 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS12)

public:
	CMenuFS12(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS12 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
