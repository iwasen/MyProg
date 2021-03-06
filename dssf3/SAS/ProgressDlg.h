#pragma once

class CProgressDlg : public CDialog
{
public:
	CProgressDlg(CWnd* pParent = NULL, LPCTSTR title = NULL);   // 標準のコンストラクタ
	~CProgressDlg();

	void SetRange(int nMaxValue);
	void SetProgressBar(int nPos);
	void SetProgressBarSub(int percent);
	void SetMessage(LPCTSTR msg);
	BOOL CheckAbort();

protected:
	enum { IDD = IDD_PROGRESS };

	CProgressCtrl	m_Progress;
	CString	m_sMessage;
	int m_nMaxValue;
	int m_nPos;
	BOOL m_bAbort;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	afx_msg void OnAbort();
};
