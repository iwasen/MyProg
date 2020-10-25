#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		SetConditionsDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CSetConditionsDlg クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "DialogEx.h"
#include "ColorBUtton.h"

// 編成条件設定メニューボタンのサブメニュー画面
class CSetConditionsDlg : public CSubMenuDlg
{
// コンストラクション
public:
	CSetConditionsDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CSetConditionsDlg)
	enum { IDD = IDD_SET_CONDITIONS };
	CColorButton	m_cButtonSetSeisanJoken;
	CColorButton	m_cButtonSetLineKisei;
	CColorButton	m_cButtonSetKyotsuKotei;
	CColorButton	m_cButtonSetKatashikiKisei;
	CColorButton	m_cButtonSetKitting;
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CSetConditionsDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CSetConditionsDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnButtonSetKitting();
	afx_msg void OnButtonSetSeisanJoken();
	afx_msg void OnButtonSetLineKisei();
	afx_msg void OnButtonSetKatashikiKisei();
	afx_msg void OnButtonSetKyotuKotei();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void EnableMenuButton();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
