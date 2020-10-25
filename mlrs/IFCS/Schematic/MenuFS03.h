#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS03.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS03 ダイアログ

class CMenuFS03 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS03)

public:
	CMenuFS03(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS03 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
