#if !defined(AFX_MANUALVIEWTYPEDLG_H__F8AADF66_18F3_479C_8CA3_522A5C008CFE__INCLUDED_)
#define AFX_MANUALVIEWTYPEDLG_H__F8AADF66_18F3_479C_8CA3_522A5C008CFE__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualViewTypeDlg.h : ヘッダー ファイル
//

#include "DialogEx.h"

/////////////////////////////////////////////////////////////////////////////
// CManualViewTypeDlg ダイアログ

class CManualViewTypeDlg : public CDialogEx
{
// コンストラクション
public:
	CManualViewTypeDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CManualViewTypeDlg)
	enum { IDD = IDD_MANUAL_VIEW_TYPE };
	CComboBox	m_cComboView3;
	CComboBox	m_cComboView2;
	CComboBox	m_cComboView1;
	int		m_nRadioViewType;
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualViewTypeDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualViewTypeDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnRadioViewType1();
	afx_msg void OnRadioViewType2();
	afx_msg void OnRadioViewType3();
	afx_msg void OnRadioViewType4();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void EnableViewType();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MANUALVIEWTYPEDLG_H__F8AADF66_18F3_479C_8CA3_522A5C008CFE__INCLUDED_)
