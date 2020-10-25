#pragma once

#include "IACCWnd.h"
#include "SetData.h"

class CGraphIACF : public CDialog
{
public:
	CGraphIACF(CWnd* pParent = NULL);   // 標準のコンストラクタ

	void ReDraw(int nStep);

	double m_fRate;
	HWAVEDATA m_hWaveData;
	AcfCondition *m_pAcfCondition;
	IAcfCondition *m_pIAcfCondition;
	NmsMicCal *m_pNmsMicCal;
	IAcfFactor *m_pIAcfFactor;
	int m_nStep;

protected:
	enum { IDD = IDD_GRAPH_IACF };

	void CalcGraphWindow();
	void DispGraphWindow();

	CIACCWnd	m_cGraphIACF;
	int m_nData;
	double *m_pIAcfData;
	BOOL m_bReDraw;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL OnInitDialog();
	afx_msg void OnDestroy();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
