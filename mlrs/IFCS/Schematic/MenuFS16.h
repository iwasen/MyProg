#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuFS16.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		方位・高低位置検出器及び駆動サーボモータ（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS16 ダイアログ

class CMenuFS16 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS16)

	// ＬＰ／Ｃ動作ステータス
	enum ELpcStatus {
		STATUS_LPC_INITIAL,			// 初期状態
		STATUS_LPC_KAIHO,			// 解放
		STATUS_LPC_KOTEI			// 固定
	};

	// 安全支柱動作ステータス
	enum EAnzenStatus {
		STATUS_ANZEN_INITIAL,			// 初期状態
		STATUS_ANZEN_TORITSUKE,			// 取付
		STATUS_ANZEN_TORIHAZUSHI		// 取外し
	};

public:
	CMenuFS16(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MENU_FS16 };

protected:
	virtual void ActionEnd(int nActionNo);
	void SetStatus(ELpcStatus eLpcStatus, EAnzenStatus eAnzenStatus);
	void SetMenuButton();

	ELpcStatus m_eLpcStatus;			// ＬＰＣ動作ステータス
	EAnzenStatus m_eAnzenStatus;		// 安全支柱動作ステータス
	BOOL m_bWorking;					// 動作中フラグ

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
};
