#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS02.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS02 ダイアログ

class CMenuFS02 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS02)

public:
	CMenuFS02(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS02 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
