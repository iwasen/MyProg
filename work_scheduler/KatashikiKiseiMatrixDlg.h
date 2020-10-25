#if !defined(AFX_KATASHIKIKISEIMATRIXDLG_H__3D27E2EC_F298_4620_8346_87F79D0AD1BA__INCLUDED_)
#define AFX_KATASHIKIKISEIMATRIXDLG_H__3D27E2EC_F298_4620_8346_87F79D0AD1BA__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// KatashikiKiseiMatrixDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CKatashikiKiseiMatrixDlg ダイアログ

class CKatashikiKiseiMatrixDlg : public CDialogEx
{
// コンストラクション
public:
	CKatashikiKiseiMatrixDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CKatashikiKiseiMatrixDlg)
	enum { IDD = IDD_KTASHIKI_KISEI_MATRIX };
	CMyButton	m_cButtonOK;
	//}}AFX_DATA
	CMyButton	m_aCheckKatashiki[MAX_KISHU];


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CKatashikiKiseiMatrixDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CKatashikiKiseiMatrixDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_KATASHIKIKISEIMATRIXDLG_H__3D27E2EC_F298_4620_8346_87F79D0AD1BA__INCLUDED_)
