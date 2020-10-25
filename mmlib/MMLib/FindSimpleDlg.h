#pragma once

// FindSimpleDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CFindSimpleDlg ダイアログ

class CFindSimpleDlg : public CDialog
{
// コンストラクション
public:
	CFindSimpleDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_FIND_SIMPLE };
	CButton	m_cFindExpert;
	CMyEdit	m_cFindNum;
	CEdit	m_cSearchText;
	CComboBox	m_cLibrary;
	CString	m_sFindFolder;
	BOOL	m_bSave;
	CString	m_sSearchText;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnFindExpert();
	afx_msg void OnGetfindnum();
	DECLARE_MESSAGE_MAP()

public:
	CItemFolder *m_pItemFolder;
	BOOL m_bUpdateMode;
};
