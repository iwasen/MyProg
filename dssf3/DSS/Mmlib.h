#pragma once


// CMmlib �_�C�A���O

class CMmlib : public CPropertyPage
{
	DECLARE_DYNAMIC(CMmlib)

public:
	CMmlib();
	virtual ~CMmlib();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MMLIB };

protected:
	CString m_sMmlibPath;

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedReferenceMmlib();
};
