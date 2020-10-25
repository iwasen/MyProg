#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		StartMenuDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CStartMenuDlg クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "DialogEx.h"
#include "ColorBUtton.h"

// 編成開始メニューボタンのサブメニュー画面
class CStartMenuDlg : public CSubMenuDlg
{
// コンストラクション
public:
	CStartMenuDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CStartMenuDlg)
	enum { IDD = IDD_START_MENU };
	CColorButton	m_cButtonReadData;
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CStartMenuDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CStartMenuDlg)
	afx_msg void OnButtonReadData();
	virtual BOOL OnInitDialog();
	afx_msg void OnButtonDebug1();
	afx_msg void OnButtonDebug2();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void ReadData(LPCTSTR pFolderName);
	void OnButtonDebug(int nJokenSetteiSosa);
	void EnableMenuButton();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
