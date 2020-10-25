#pragma once

#include "DialogEx.h"
#include "DataDateTime.h"
#include "ProgressBar.h"


// CGraphDigitalDataRowCol ダイアログ

class CGraphDigitalDataRowCol : public CDialogEx
{
	DECLARE_DYNAMIC(CGraphDigitalDataRowCol)

public:
	CGraphDigitalDataRowCol(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CGraphDigitalDataRowCol();

// ダイアログ データ
	enum { IDD = IDD_GRAPH_DIGITAL_DATA_ROWCOL };

	void SetDataInfo(const double *pDat, int nRow, int nCol, LPCSTR title, CDataDateTime tm, double step, double freqStep, long period);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	void SetListHeader();
	afx_msg void OnBnClickedButtonCsv();
	afx_msg void OnLvnGetdispinfoDataLixxxol(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnLvnGetdispinfoDataListRow(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnLvnItemchangedDataListRow(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnDestroy();

	static BOOL WriteFileThread(LPVOID pParam, CProgressBar *pProgressBar);
	BOOL WriteFileThread2(CProgressBar *pProgressBar);

protected:
	CToolTipCtrl m_tooltip;
	CLixxxtrl m_ListRow;
	CLixxxtrl m_Lixxxol;
	const double *m_pData;
	int m_nRow;
	int m_nCol;
	CString m_sTitle;
	CDataDateTime	m_StartTime;
	double m_fStep;
	CString m_sCsvFName;
	long m_nPeriod;
	double m_fFreqStep;
};
