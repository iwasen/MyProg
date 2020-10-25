#pragma once

#include "MyCtrl.h"

class CSplWnd : public CDialog
{
public:
	CSplWnd(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	void ShowSpl(double fSpl);
	void Clear();

protected:
	enum { IDD = IDD_SPL };

	CStatic	m_cDB;
	CMyEdit	m_cSpl;
	CFont m_Font;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void PostNcDestroy();
	virtual BOOL OnInitDialog();
	virtual void OnCancel();
	virtual void OnOK();
	afx_msg void OnDestroy();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
