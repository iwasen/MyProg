//*****************************************************************************************************
//  1. ファイル名
//		MenuFS17.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ブームモータ（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************
#pragma once

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS17 ダイアログ

class CMenuFS17 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS17)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,			// 初期状態
		STATUS_BOOM_CONTROL,	// ブーム制御メニューON
		STATUS_BOOM_SELECT,		// ブーム選択スイッチON
		STATUS_BOOM_LEFT,		// 左
		STATUS_BOOM_BOTH,		// 同時
		STATUS_BOOM_RIGHT,		// 右
		STATUS_BOOM_SHORT_1,	// ブーム縮動作1
		STATUS_BOOM_SHORT_2,	// ブーム縮動作2
		STATUS_BOOM_LONG_1,		// ブーム伸動作1
		STATUS_BOOM_LONG_2,		// ブーム伸動作2
		STATUS_HOOK_UP,			// フック上
		STATUS_HOOK_DOWN		// フック下
	};

public:
	CMenuFS17(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS17 };

protected:
	void SendAction(int nActionNo, int nParam2 = 0);
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus, BOOL bWorking = FALSE);
	void SetMenuButton();

	EStatus m_eStatus;			// 動作ステータス
	BOOL m_bWorking;			// 動作中フラグ
	BOOL m_bPause;				// 一時停止フラグ

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnPushedButtonAction3();
	afx_msg void OnBnUnpushedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
	afx_msg void OnBnPushedButtonAction12();
	afx_msg void OnBnClickedButtonAction12();
	afx_msg void OnBnPushedButtonAction13();
	afx_msg void OnBnClickedButtonAction13();
	afx_msg void OnBnClickedButtonAction14();
	afx_msg void OnTimer(UINT_PTR nIDEvent);

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonAction7;
	CMenuButton m_cButtonAction8;
	CMenuButton m_cButtonAction9;
	CMenuButton m_cButtonAction10;
	CMenuButton m_cButtonAction11;
	CMenuButton m_cButtonAction12;
	CMenuButton m_cButtonAction13;
	CMenuButton m_cButtonAction14;
};
