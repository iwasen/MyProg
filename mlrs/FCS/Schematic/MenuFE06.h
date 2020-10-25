#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFE06.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		制御演算器－日本語変換器－射撃統制パネル画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE06 ダイアログ

class CMenuFE06 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE06)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,			// 初期状態
		STATUS_WORKING,			// 動作中
		STATUS_END				// 動作終了
	};

public:
	CMenuFE06(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FE06 };

protected:
	void SendAction(int nActionNo);
	void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;			// 動作ステータス
	BOOL m_bPause;				// 一時停止フラグ

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
};
