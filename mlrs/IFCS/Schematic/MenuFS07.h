#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS07.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS07 ダイアログ

class CMenuFS07 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS07)

public:
	CMenuFS07(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS07 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
