#if !defined(AFX_LOGINDLG_H__C08ED5CC_069D_497C_A3D0_19478621B600__INCLUDED_)
#define AFX_LOGINDLG_H__C08ED5CC_069D_497C_A3D0_19478621B600__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LoginDlg.h : ヘッダー ファイル
//

#include "DialogEx.h"
#include "MyCtrl.h"

/////////////////////////////////////////////////////////////////////////////
// CLoginDlg ダイアログ

class CLoginDlg : public CDialogEx
{
// コンストラクション
public:
	CLoginDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CLoginDlg)
	enum { IDD = IDD_LOGIN };
	CMyEdit	m_cEditShokuban;
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CLoginDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CLoginDlg)
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_LOGINDLG_H__C08ED5CC_069D_497C_A3D0_19478621B600__INCLUDED_)
