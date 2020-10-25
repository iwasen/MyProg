#pragma once

#include "MyCtrl.h"


// CAutoRecDlg ダイアログ

class CAutoRecDlg : public CDialog
{
	DECLARE_DYNAMIC(CAutoRecDlg)

public:
	CAutoRecDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CAutoRecDlg();

// ダイアログ データ
	enum { IDD = IDD_AUTO_REC };

protected:
	static int CALLBACK BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	CMyEdit m_cEditSaveFolder;
	CMyButton m_cCheckInputData;
	CMyButton m_cCheckOutputData;

	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedButtonReference();
};
