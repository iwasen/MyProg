#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS04.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS04 ダイアログ

class CMenuFS04 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS04)

public:
	CMenuFS04(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS04 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
