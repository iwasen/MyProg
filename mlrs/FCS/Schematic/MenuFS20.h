#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS20.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ホイストモータ（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS20 ダイアログ

class CMenuFS20 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS20)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,			// 初期状態
		STATUS_BOOM_CONTROL,	// ブーム制御メニューON
		STATUS_BOOM_SELECT,		// ブーム選択スイッチON
		STATUS_BOOM_LEFT,		// 左
		STATUS_BOOM_BOTH,		// 同時
		STATUS_BOOM_RIGHT,		// 右
		STATUS_BOOM_SHORT,		// ブーム縮
		STATUS_BOOM_LONG,		// ブーム伸
		STATUS_HOOK_UP_1,		// フック上動作1
		STATUS_HOOK_UP_2,		// フック上動作2
		STATUS_HOOK_DOWN_1,		// フック下動作1
		STATUS_HOOK_DOWN_2,		// フック下動作2
		STATUS_HOOK_STOP,		// フック停止
	};

public:
	CMenuFS20(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS20 };

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
	afx_msg void OnBnPushedButtonAction2();
	afx_msg void OnBnUnpushedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnPushedButtonAction7();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnPushedButtonAction8();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
	afx_msg void OnBnClickedButtonAction12();
	afx_msg void OnBnClickedButtonAction13();
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
};
