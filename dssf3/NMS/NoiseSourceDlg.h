#pragma once

class CNoiseSourceDlg : public CDialog
{
public:
	CNoiseSourceDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

protected:
	enum { IDD = IDD_NOISE_SOURCE };

	CString	m_sNoiseSource;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void OnOK();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
