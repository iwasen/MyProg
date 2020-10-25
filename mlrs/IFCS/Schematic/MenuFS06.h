#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS06.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS06 ダイアログ

class CMenuFS06 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS06)

public:
	CMenuFS06(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS06 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
