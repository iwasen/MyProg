#pragma once

#include "MyCtrl.h"

// CDataBackupDlg �_�C�A���O

class CDataBackupDlg : public CDialog
{
public:
	CDataBackupDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CDataBackupDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_DATA_BACKUP };

protected:
	CStringArray m_oBackupList;

	CMyEdit m_cEditComment;
	CLixxxtrl m_cListBackup;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedDeleteBackup();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
