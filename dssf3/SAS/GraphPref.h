#pragma once

#include "PrefWnd.h"
#include "FileIO.h"
#include "MyCtrl.h"

class CGraphPref : public CDialog
{
public:
	CGraphPref(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	void ReDraw();

	DbImpulseRec *m_pDbImpulseRec;
	DbAcParamRec *m_pDbAcParamRec;
	double m_fScale;
	double m_fAdjustSPL;

protected:
	enum { IDD = IDD_GRAPH_PREF };

	void DispPrefWindow();
	void DispPreference();
	void GetParameter();

	CMyEdit	m_cBestTauE;
	CMyEdit	m_cS4;
	CMyEdit	m_cS3;
	CMyEdit	m_cS2;
	CMyEdit	m_cS1;
	CMyEdit	m_cS;
	CMyEdit	m_cTauE;
	CMyEdit	m_cBestSPL;
	CPrefWnd	m_cGraphPref;
	double *m_pData;
	BOOL m_bReDraw;
	double m_fSPL, m_fDT1, m_fTsub, m_fIACC, m_fA;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnCancel() {};
	virtual void OnOK() {};
	afx_msg void OnDestroy();
	afx_msg void OnCalc();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
