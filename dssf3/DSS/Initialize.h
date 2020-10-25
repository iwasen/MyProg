#pragma once


// CInitialize ダイアログ

class CInitialize : public CPropertyPage
{
public:
	CInitialize();
	virtual ~CInitialize();

protected:
	enum { IDD = IDD_INITIALIZE };

	CString m_sInitialize;

	static int CALLBACK BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedInitSettings();
	afx_msg void OnBnClickedInitDatabase();
public:
	BOOL m_bCheckInitRta;
	BOOL m_bCheckInitSas;
	BOOL m_bCheckInitNms;
};
