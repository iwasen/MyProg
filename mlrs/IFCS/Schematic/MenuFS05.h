#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS05.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS05 ダイアログ

class CMenuFS05 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS05)

public:
	CMenuFS05(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS05 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
