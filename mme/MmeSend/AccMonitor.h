#pragma once


// CAccMonitor ダイアログ

class CAccMonitor : public CDialog
{
	DECLARE_DYNAMIC(CAccMonitor)

public:
	CAccMonitor(CWnd* pParent, SDigitalData &oDIgitalData);   // 標準コンストラクタ
	virtual ~CAccMonitor();

// ダイアログ データ
	enum { IDD = IDD_ACC_MONITOR };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

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
