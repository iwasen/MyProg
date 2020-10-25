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
#include "MenuButton.h"

// CMenuFS08 ダイアログ

class CMenuFS08 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS08)

public:
	CMenuFS08(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS08 };

protected:
	void OpenSubMenu(CMenuCommon *pSubMenu);

public:
	CMenuCommon *m_pSubMenu;		// 表示中のサブメニュー

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	LRESULT OnUserZoomIn(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserZoomOut(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserSizeReset(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserPrint(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserExit(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserAction(WPARAM wParam, LPARAM lParam);

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonExit;
};
