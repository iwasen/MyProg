#pragma once
#include "afxcmn.h"


// CDataRestoreDlg �_�C�A���O

class CDataRestoreDlg : public CDialog
{
public:
	CDataRestoreDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CDataRestoreDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_DATA_RESTORE };

protected:
	CStringArray m_oBackupList;

	CLixxxtrl m_cListBackup;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
