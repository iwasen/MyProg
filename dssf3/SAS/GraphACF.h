#pragma once

#include "AcfWnd.h"
#include "MyCtrl.h"
#include "SetData.h"
#include "DialogEx.h"
#include "ToolBarEx.h"

class CGraphACF : public CDialogEx
{
public:
	CGraphACF(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	void ReDraw(int nStep);

	HWAVEDATA m_hWaveData;
	AcfCondition *m_pAcfCondition;
	IAcfCondition *m_pIAcfCondition;
	NmsMicCal *m_pNmsMicCal;
	AcfFactor *m_pAcfFactor;
	double m_fRate;
	int m_nStep;
	double m_fStartTime;
	double m_fDispTime;

protected:
	enum { IDD = IDD_GRAPH_ACF };

	void CalcGraphWindow();
	void DispGraphWindow();
	static void CALLBACK GraphCallBack(WPARAM code, LPARAM data, double param1, double param2);

	CMyEdit	m_cZoomH;
	CScrollBar	m_cScrollH;
	CAcfWnd	m_cGraphAcf;
	BOOL	m_bLog;
	CToolBarEx m_tbZoomH;
	double *m_pAcfData;
	double *m_pAcfDataLog;
	int m_nData;
	double m_fTotalTime;
	int m_nScrollPos;
	int m_nScrollSize;
	BOOL m_bReDraw;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	afx_msg void OnZoomFullH();
	afx_msg void OnZoomInH();
	afx_msg void OnZoomOutH();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnDestroy();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg void OnLog();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
