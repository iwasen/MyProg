#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08_4.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置－発射制御器－電力分配器（方位・高低位置検出器及びサーボモータ）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_4 ダイアログ

class CMenuFS08_4 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_4)

	// 動作ステータス
	enum EStatus {
		STATUS_INITIAL,		// 初期状態
		STATUS_WORKING		// 動作中
	};

public:
	CMenuFS08_4(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS08_4 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// 動作ステータス

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction31();
	afx_msg void OnBnClickedButtonAction32();
	afx_msg void OnBnClickedButtonAction33();
	afx_msg void OnBnClickedButtonAction34();
	afx_msg void OnBnClickedButtonAction35();
	afx_msg void OnBnClickedButtonAction36();
	afx_msg void OnBnClickedButtonAction37();

	CMenuButton m_cButtonAction31;
	CMenuButton m_cButtonAction32;
	CMenuButton m_cButtonAction33;
	CMenuButton m_cButtonAction34;
	CMenuButton m_cButtonAction35;
	CMenuButton m_cButtonAction36;
	CMenuButton m_cButtonAction37;
	CMenuButton m_cButtonExit;
};
