#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		AutoSchedulingDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CAutoSchedulingDlg クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "DialogEx.h"

// 編成実行メニューボタンのサブメニュー画面
class CAutoSchedulingDlg : public CSubMenuDlg
{
// コンストラクション
public:
	CAutoSchedulingDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CAutoSchedulingDlg)
	enum { IDD = IDD_AUTO_SCHEDULING };
	CComboBox	m_cComboKijunKishu;
	CColorButton	m_cButtonExec;
	int		m_nRadioKojun;
	int		m_nRadioTactKeisan;
	//}}AFX_DATA
	CMyButton	m_aCheckSubline[MAX_SUBLINE];


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CAutoSchedulingDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CAutoSchedulingDlg)
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg void OnButtonZensentaku();
	afx_msg void OnButtonZenkaijo();
	afx_msg void OnCheckSubline1();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void SetAllSublixxxheck(BOOL bCheck);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
