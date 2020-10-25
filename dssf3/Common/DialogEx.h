#pragma once

class CDialogEx : public CDialog
{
public:
	CDialogEx(UINT nIDTemplate, CWnd* pParentWnd);

protected:
	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL OnInitDialog();
	afx_msg BOOL OnToolTipText(UINT, NMHDR* pNMHDR, LRESULT* pResult);
};
