#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS16.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		方位・高低位置検出器及び駆動サーボモータ（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS16 ダイアログ

class CMenuFS16 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS16)

public:
	CMenuFS16(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS16 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
};
