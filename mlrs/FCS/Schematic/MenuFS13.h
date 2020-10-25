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
		STATUS_START,		// 開始中
		STATUS_READY,		// 準備完了
		STATUS_WORKING,		// 動作中
		STATUS_COMPLETE,	// 動作完了
		STATUS_STRAGE		// 収納中
	};

public:
	CMenuFS13(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS13 };

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
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnClickedButtonAction8();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonAction7;
	CMenuButton m_cButtonAction8;
};
