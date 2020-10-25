#pragma once


// CAccMonitor �_�C�A���O

class CAccMonitor : public CDialog
{
	DECLARE_DYNAMIC(CAccMonitor)

public:
	CAccMonitor(CWnd* pParent, SDigitalData &oDIgitalData);   // �W���R���X�g���N�^
	virtual ~CAccMonitor();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_ACC_MONITOR };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	SDigitalData &m_oDIgitalData;

	DECLARE_MESSAGE_MAP()
	CString m_sEditUnit1AccX;
	CString m_sEditUnit1AccY;
	CString m_sEditUnit1AccZ;
	CString m_sEditUnit2AccX;
	CString m_sEditUnit2AccY;
	CString m_sEditUnit2AccZ;
	CString m_sEditUnit3AccX;
	CString m_sEditUnit3AccY;
	CString m_sEditUnit3AccZ;
	CString m_sEditSeqCount;
public:
	virtual BOOL OnInitDialog();
protected:
	virtual void OnOK();
};
