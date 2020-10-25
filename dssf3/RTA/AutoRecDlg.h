#pragma once

#include "MyCtrl.h"


// CAutoRecDlg �_�C�A���O

class CAutoRecDlg : public CDialog
{
	DECLARE_DYNAMIC(CAutoRecDlg)

public:
	CAutoRecDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CAutoRecDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_AUTO_REC };

protected:
	static int CALLBACK BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	CMyEdit m_cEditSaveFolder;
	CMyButton m_cCheckInputData;
	CMyButton m_cCheckOutputData;

	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedButtonReference();
};
