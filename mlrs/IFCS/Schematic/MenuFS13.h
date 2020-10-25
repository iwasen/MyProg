#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS13.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リミットスイッチ（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS13 ダイアログ

class CMenuFS13 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS13)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,		// 初期状態
		STATUS_WORKING,		// 動作中
		STATUS_COMPLETE,	// 動作完了
	};

public:
	CMenuFS13(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS13 };

protected:
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

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
};
