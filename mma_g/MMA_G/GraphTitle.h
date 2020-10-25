#pragma once


// CGraphTitle �_�C�A���O

class CGraphTitle : public CDialog
{
	DECLARE_DYNAMIC(CGraphTitle)

public:
	CGraphTitle(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CGraphTitle();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_GRAPH_TITLE };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedOk();
	afx_msg void OnDestroy();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	DECLARE_MESSAGE_MAP()

protected:
	CToolTipCtrl m_tooltip;

	CEdit m_cEditTitle;
	CString	m_sTitle;

public:
	void SetTitle(CString title);
	void GetTitle(CString& title);
};
