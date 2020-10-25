#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08_5.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置－発射制御器－電力分配器（方位・高低油圧バルブ）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_5 ダイアログ

class CMenuFS08_5 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_5)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,		// 初期状態
		STATUS_WORKING		// 動作中
	};

public:
	CMenuFS08_5(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS08_5 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// 動作ステータス

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction38();
	afx_msg void OnBnClickedButtonAction39();

	CMenuButton m_cButtonAction38;
	CMenuButton m_cButtonAction39;
	CMenuButton m_cButtonExit;
};
