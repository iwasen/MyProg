
#pragma once

#include "FactorWnd.h"
#include "MyCtrl.h"
#include "FileIO.h"
#include "DialogEx.h"
#include "ToolBarEx.h"

#define FACTOR_PHI0		0
#define FACTOR_TAUE		1
#define FACTOR_TAU1		2
#define FACTOR_PHI1		3
#define FACTOR_SPL		4
#define FACTOR_IACC		5
#define FACTOR_TIACC	6
#define FACTOR_WIACC	7

class CGraphFactor : public CDialogEx
{
public:
	CGraphFactor(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	void ReDraw(int nSel);

	int m_nFactor;
	AcfFactor *m_pAcfFactor;
	IAcfFactor *m_pIAcfFactor;
	int m_nData;
	double m_fStepTime;
	double m_fAdjustPhi0;
	double m_fDispTime;
	double m_fStartTime;
	double m_fStartValue;
	double m_fDispValue;
	DbAcfMarkerRec **m_pAcfMarkerRec;
	int *m_nAcfMarkerRec;

protected:
	enum { IDD = IDD_GRAPH_FACTOR };

	void CalcData();
	void DispGraphWindow();
	void SetHScroll();
	void SetVScroll();
	static void CALLBACK GraphCallBack(WPARAM code, LPARAM data, double param1, double param2);
	void DispZoomGraph(CFactorWnd *pWnd);
	void CloseZoomWnd();
	void SetZoomArea();

	CFactorWnd	m_cGraphFactor;
	CScrollBar	m_cScrollH;
	CScrollBar	m_cScrollV;
	CMyEdit	m_cZoomH;
	CMyEdit	m_cZoomV;
	CString m_sTitle;
	CToolBarEx m_tbZoomH;
	CToolBarEx m_tbZoomV;
	int m_nSel;
	BOOL m_bReDraw;
	double m_fTotalTime;
	double m_fMinValue;
	double m_fTotalValue;
	int m_nHScrollPos;
	int m_nHScrollSize;
	int m_nVScrollPos;
	int m_nVScrollSize;
	double *m_pfData;
	CString m_sFactor;
	CString m_sAxis;
	int m_nZoomMode;
	CFactorWnd	*m_pZoomGraph;
	double m_fZoomStartTime;
	double m_fZoomDispTime;
	double m_fZoomMinValue;
	double m_fZoomMaxValue;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg void OnZoomFullH();
	afx_msg void OnZoomInH();
	afx_msg void OnZoomOutH();
	afx_msg void OnZoomFullV();
	afx_msg void OnZoomInV();
	afx_msg void OnZoomOutV();
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnDestroy();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
	afx_msg LRESULT OnMarkerCursor(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnSelectMarker(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnGraphDrag(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnResize(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnCloseGraph(WPARAM wParam, LPARAM lParam);
};
