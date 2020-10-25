#pragma once


// CAnalogMonitor ダイアログ

class CAnalogMonitor : public CDialog
{
	DECLARE_DYNAMIC(CAnalogMonitor)

public:
	CAnalogMonitor(CWnd* pParent, SAnalogData &oAnalogData);   // 標準コンストラクタ
	virtual ~CAnalogMonitor();

// ダイアログ データ
	enum { IDD = IDD_ANALOG_MONITOR };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	SAnalogData &m_oAnalogData;

	DECLARE_MESSAGE_MAP()
	CString m_sEditUnit1AccX;
	CString m_sEditUnit1AccY;
	CString m_sEditUnit1AccZ;
	CString m_sEditUnit1TempX;
	CString m_sEditUnit1TempY;
	CString m_sEditUnit1TempZ;
	CString m_sEditUnit1TempSB;
	CString m_sEditUnit1Heater;
	CString m_sEditUnit2AccX;
	CString m_sEditUnit2AccY;
	CString m_sEditUnit2AccZ;
	CString m_sEditUnit2TempX;
	CString m_sEditUnit2TempY;
	CString m_sEditUnit2TempZ;
	CString m_sEditUnit2TempSB;
	CString m_sEditUnit2Heater;
	CString m_sEditUnit3AccX;
	CString m_sEditUnit3AccY;
	CString m_sEditUnit3AccZ;
	CString m_sEditUnit3TempX;
	CString m_sEditUnit3TempY;
	CString m_sEditUnit3TempZ;
	CString m_sEditUnit3TempSB;
	CString m_sEditUnit3Heater;
	virtual BOOL OnInitDialog();
	virtual void OnOK();
};
