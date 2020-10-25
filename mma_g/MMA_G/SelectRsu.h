#pragma once


// CSelectRsu �_�C�A���O

class CSelectRsu : public CDialog
{
	DECLARE_DYNAMIC(CSelectRsu)

public:
	CSelectRsu(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CSelectRsu();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_SELECT_RSU };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()

public:
	void SetRsu(CStringArray * arr);
	bool GetRsu(CString & Rsu);

protected:
	CToolTipCtrl m_tooltip;
	int	m_nIndex;
	CStringArray m_Rsu;
	CComboBox m_Cmb_Rsu;
	CButton m_cButtonOK;

	afx_msg void OnBnClickedOk();
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedCancel();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	afx_msg void OnCbnSelchangeComboSensor();
};
