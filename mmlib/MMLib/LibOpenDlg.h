#pragma once

// LibOpenDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CLibOpenDlg ダイアログ

class CLibOpenDlg : public CDialog
{
// コンストラクション
public:
	CLibOpenDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_LIBOPEN };
	CListBox	m_cLibraryList;
	CButton	m_cExternal;
	CStatic	m_cSeparator;
	CComboBox	m_cDirectory;
	CComboBox	m_cServerName;
	CButton	m_cCache;
	CString	m_sDirectory;
	int		m_iChType;
	CString	m_sServerName;
	BOOL	m_bCache;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	afx_msg void OnTcpip();
	afx_msg void OnNetbios();
	afx_msg void OnLocal();
	virtual void OnOK();
	afx_msg void OnReference();
	afx_msg void OnExternal();
	afx_msg void OnSelchangeLibraryList();
	afx_msg void OnDestroy();
	afx_msg void OnDblclkLibraryList();
	DECLARE_MESSAGE_MAP()

private:
	CRect m_rectDlg;

	void SetLibraryList();
	void ReferenceLocal();
	void ReferenceNetwork();
};
