#pragma once

#include "FreqWnd.h"
#include "FileIO.h"

class CGraphFreq : public CDialog
{
public:
	CGraphFreq(CWnd* pParent = NULL);   // 標準のコンストラクタ

	void ReDraw();

	DbImpulseRec *m_pDbImpulseRec;
	double *m_pWaveLeft;
	double *m_pWaveRight;
	double m_fRate;

protected:
	enum { IDD = IDD_GRAPH_FREQ };

	void DispFreqWindow(BOOL bCalc);

	CFreqWnd	m_cFreqRight;
	CFreqWnd	m_cFreqLeft;
	BOOL	m_bMean;
	double *m_pFreqLeft;
	double *m_pFreqRight;
	int m_nData;
	BOOL m_bReDraw;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL OnInitDialog();
	virtual void OnCancel() {};
	virtual void OnOK() {};
	afx_msg void OnDestroy();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg void OnMean();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
