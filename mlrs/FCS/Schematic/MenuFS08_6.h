#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08_6.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置－発射制御器－電力分配器（ブームモータ・ホイストモータ）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_6 ダイアログ

class CMenuFS08_6 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_6)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,			// 初期状態
		STATUS_BOOM_MENU,		// ブーム制御メニュー
		STATUS_STRAGE,			// 発射機部収納
		STATUS_READY_LAMP,		// 「使用可」表示灯
		STATUS_LEFT,			// 左
		STATUS_BOTH,			// 同時
		STATUS_RIGHT,			// 右
		STATUS_BOOM_SHORT,		// ブーム縮
		STATUS_BOOM_LONG,		// ブーム伸
		STATUS_HOOK_UP,			// フック上
		STATUS_HOOK_DOWN		// フック下
	};

public:
	CMenuFS08_6(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS08_6 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// 動作ステータス

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction40();
	afx_msg void OnBnClickedButtonAction41();
	afx_msg void OnBnClickedButtonAction42();
	afx_msg void OnBnClickedButtonAction43();
	afx_msg void OnBnClickedButtonAction44();
	afx_msg void OnBnClickedButtonAction45();
	afx_msg void OnBnClickedButtonAction46();
	afx_msg void OnBnClickedButtonAction47();
	afx_msg void OnBnClickedButtonAction48();
	afx_msg void OnBnClickedButtonAction49();
	afx_msg void OnBnClickedButtonAction50();

	CMenuButton m_cButtonAction40;
	CMenuButton m_cButtonAction41;
	CMenuButton m_cButtonAction42;
	CMenuButton m_cButtonAction43;
	CMenuButton m_cButtonAction44;
	CMenuButton m_cButtonAction45;
	CMenuButton m_cButtonAction46;
	CMenuButton m_cButtonAction47;
	CMenuButton m_cButtonAction48;
	CMenuButton m_cButtonAction49;
	CMenuButton m_cButtonAction50;
	CMenuButton m_cButtonExit;
};
