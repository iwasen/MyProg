#pragma once


// CSettingDevice �_�C�A���O

class CSettingDevice : public CPropertyPage
{
public:
	CSettingDevice();
	virtual ~CSettingDevice();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_SETTING_DEVICE };

protected:
	int m_iInputBit;
	int m_iOutputBit;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
