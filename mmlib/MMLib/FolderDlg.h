#pragma once

// FolderDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CFolderDlg ダイアログ

class CFolderDlg : public CDialog
{
// コンストラクション
public:
	CFolderDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_FOLDER };
	CButton	m_cCopyData;
	CString	m_sCreator;
	CString	m_sFolderName;
	CString	m_sRemark;
	CString	m_sKeyword;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	afx_msg void OnTextData();
	afx_msg void OnCopyData();
	DECLARE_MESSAGE_MAP()

public:
	CString m_Caption;
	CString m_sTextData;
	FOLDERREC m_FolderRec;
};

/////////////////////////////////////////////////////////////////////////////
// CFolderNewDlg ダイアログ

class CFolderNewDlg : public CDialog
{
// コンストラクション
public:
	CFolderNewDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_FOLDERNEW };
	CButton	m_cCopyData;
	CString	m_sCreator;
	CString	m_sFolderName;
	int		m_iFolderType;
	CString	m_sRemark;
	CString	m_sKeyword;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual void OnOK();
	afx_msg void OnTextData();
	afx_msg void OnCopyData();
	virtual BOOL OnInitDialog();
	DECLARE_MESSAGE_MAP()

public:
	CString m_sTextData;
	FOLDERREC m_FolderRec;
};
