#pragma once

#include "MyCtrl.h"

// CDataBackupDlg ダイアログ

class CDataBackupDlg : public CDialog
{
public:
	CDataBackupDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CDataBackupDlg();

// ダイアログ データ
	enum { IDD = IDD_DATA_BACKUP };

protected:
	CStringArray m_oBackupList;

	CMyEdit m_cEditComment;
	CLixxxtrl m_cListBackup;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedDeleteBackup();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
