#pragma once


// CFolder ダイアログ

class CFolder : public CPropertyPage
{
public:
	CFolder();
	virtual ~CFolder();

protected:
	enum { IDD = IDD_FOLDER };

	CString m_sDatabaseFolder;
	CString m_sBackupFolder;

	void SelectFolder(CString &sFolder, int nTitleId);
	static int CALLBACK BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedReferenceDatabase();
	afx_msg void OnBnClickedDefaultDatabase();
	afx_msg void OnBnClickedReferenceBackup();
	afx_msg void OnBnClickedDefaultBackup();
};
