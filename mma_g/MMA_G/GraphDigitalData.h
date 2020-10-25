#pragma once

#include "DialogEx.h"
#include "ProgressBar.h"

// CGraphDigitalData ダイアログ

class CGraphDigitalData : public CDialogEx
{
	DECLARE_DYNAMIC(CGraphDigitalData)

public:
	CGraphDigitalData(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CGraphDigitalData();

// ダイアログ データ
	enum { IDD = IDD_GRAPH_DIGITAL_DATA };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnLvnGetdispinfoDataList(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnBnClickedButtonCsv();
	afx_msg void OnDestroy();
	virtual BOOL OnInitDialog();
	virtual BOOL PreTranslateMessage(MSG* pMsg);

public:
	void SetListHeader();
	void SetDataInfo(int mode, int nCount, const double *pX, const double *pY, const double *pZ, const double *pG, LPCSTR title, long period, double step, const double *pStepTable);

protected:
	static BOOL WriteFileThread(LPVOID pParam, CProgressBar *pProgressBar);
	BOOL WriteFileThread2(CProgressBar *pProgressBar);

protected:
	CToolTipCtrl m_tooltip;

	CLixxxtrl m_List;
	int m_nCount;
	const double *m_pX;
	const double *m_pY;
	const double *m_pZ;
	const double *m_pG;
	CString	m_sTitle;
	CButton m_Button_Csv;
	CString m_sCsvFName;
	long m_nPeriod;
	int m_nMode;
	double m_fStep;
	const double *m_pStepTable;
};
