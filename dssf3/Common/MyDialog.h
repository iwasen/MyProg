#pragma once

class CMyDialog : public CDialog
{
public:
	CMyDialog(UINT IDD = NULL, CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

protected:
	virtual BOOL OnInitDialog();
	BOOL OnToolTipNotify(UINT id, NMHDR * pNMHDR, LRESULT * pResult);
	DECLARE_MESSAGE_MAP()
};
