#pragma once
#include "afxwin.h"

/////////////////////////////////////////////////////////////////////////////
// CPresetDlg ダイアログ

class CPresetDlg : public CDialog
{
public:
	CPresetDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

	int m_nPresetID;

protected:
	enum { IDD = IDD_PRESET };

	void SetPresetList();

	CListBox	m_cPresetList;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSave();
	afx_msg void OnOverwrite();
	afx_msg void OnDelete();
	afx_msg void OnDblclkPresetList();
	afx_msg void OnBnClickedMakeShortcut();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
public:
	CButton m_cButtonOverwrite;
	CButton m_cButtonDelete;
	afx_msg void OnLbnSelchangePresetList();
};

/////////////////////////////////////////////////////////////////////////////
// CPresetSaveDlg ダイアログ

class CPresetSaveDlg : public CDialog
{
public:
	CPresetSaveDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

	CString	m_sTitle;

protected:
	enum { IDD = IDD_PRESET_SAVE };

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual void OnOK();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
