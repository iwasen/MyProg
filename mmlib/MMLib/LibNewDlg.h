#pragma once

// LibNewDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CLibNewDlg ダイアログ

class CLibNewDlg : public CDialog
{
// コンストラクション
public:
	CLibNewDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_LIBNEW };
	CStatic	m_cSeparator;
	CButton	m_cExternal;
	CButton	m_cReference;
	CComboBox	m_cServerName;
	CComboBox	m_cDirectory;
	CButton	m_cCache;
	CString	m_sCreator;
	CString	m_sDirectory;
	CString	m_sLibName;
	int		m_iChType;
	CString	m_sServerName;
	CString	m_sRemark;
	CString	m_sKeyword;
	BOOL	m_bCache;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	afx_msg void OnLocal();
	afx_msg void OnTcpip();
	afx_msg void OnNetbios();
	virtual void OnOK();
	afx_msg void OnReference();
	afx_msg void OnExternal();
	DECLARE_MESSAGE_MAP()

private:
	CRect m_rectDlg;
};
