#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFE07.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		制御演算器－電源制御器－発射制御器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE07 ダイアログ

class CMenuFE07 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE07)

	// 動作ステータス
	enum EStatus {
		STATUS_STOP,			// 停止
		STATUS_NEUTRAL,			// 中立
		STATUS_FORWARD,			// 前進
		STATUS_REVERSE,			// 後進
		STATUS_STATIONARY_TURN	// 超信地旋回
	};

public:
	CMenuFE07(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FE07 };

protected:
	void SendAction(int nActionNo);
	void SetMenuButton();

	EStatus m_eStatus;			// 動作ステータス
	int m_nClutch;				// クラッチの位置
	BOOL m_bArrow;				// 矢印の表示フラグ
	BOOL m_bWait;				// 動作完了待ちフラグ
	BOOL m_bTurn;				// 旋回中フラグ
	BOOL m_bBrake;				// ブレーキフラグ

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
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
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
public:
	virtual BOOL OnInitDialog();
};
