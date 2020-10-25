#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS15.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		トラベルロック（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS15 ダイアログ

class CMenuFS15 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS15)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,			// 初期状態
		STATUS_CAGE_UP,			// CAGE UP (CAGE UNLCK)
		STATUS_CAGE_DOWN,		// CAGE DOWN (CAGE LOCK)
		STATUS_CAGE_WORKING		// 動作中
	};

public:
	CMenuFS15(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS15 };

protected:
	void SendAction(int nActionNo);
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// 動作ステータス
	BOOL m_bPause;			// 一時停止フラグ

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
};
