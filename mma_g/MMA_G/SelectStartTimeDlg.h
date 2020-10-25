#pragma once


// CSelectStartTimeDlg ダイアログ

class CSelectStartTimeDlg : public CDialog
{
	DECLARE_DYNAMIC(CSelectStartTimeDlg)

public:
	CSelectStartTimeDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CSelectStartTimeDlg();

// ダイアログ データ
	enum { IDD = IDD_SELECT_START_TIME };

	CString GetMaiFileName(const CString &sRsuNo, const CStringArray &saFileName);

protected:
	struct SMaiFile {
		CString sFileName;
		CString sStartTime;
		CString sEndTime;
	};
	CArray <SMaiFile, SMaiFile&> m_aoMaiFile;
	int m_nSelectIndex;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	CComboBox m_cComboSensor;
	CButton m_cButtonOK;
	afx_msg void OnCbnSelchangeComboSensor();
	virtual void OnOK();
	virtual void OnCancel();
};
