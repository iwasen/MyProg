#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS11.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		発射制御器－弾頭連接器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS11 ダイアログ

class CMenuFS11 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS11)

public:
	CMenuFS11(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS11 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
