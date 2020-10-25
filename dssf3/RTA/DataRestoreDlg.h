#pragma once
#include "afxcmn.h"


// CDataRestoreDlg ダイアログ

class CDataRestoreDlg : public CDialog
{
public:
	CDataRestoreDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CDataRestoreDlg();

// ダイアログ データ
	enum { IDD = IDD_DATA_RESTORE };

protected:
	CStringArray m_oBackupList;

	CLixxxtrl m_cListBackup;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
