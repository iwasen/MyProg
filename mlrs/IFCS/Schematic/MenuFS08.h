#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置－発射制御器－電力分配器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS08 ダイアログ

class CMenuFS08 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS08)

public:
	CMenuFS08(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS08 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
