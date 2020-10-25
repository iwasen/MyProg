#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuCS01.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuCS01 ダイアログ

class CMenuCS01 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuCS01)

public:
	CMenuCS01(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_CS01 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
public:
	afx_msg void OnBnClickedButtonAction1();
};
