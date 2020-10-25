#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		GraphWnd.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフウィンドウクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "MmeData.h"
#include "GraphData.h"

// CGraphWnd

class CGraphWnd : public CWnd
{
	DECLARE_DYNAMIC(CGraphWnd)

public:
	static const COLORREF m_aGraphColor[N_UNIT][N_GRAPH];

	CGraphWnd();

	void DispGraph(CGraphData &oGraphData, SGraphLine &oGraphLine, double fTimeRange1, double fTimeRange2, double fTempRange1, double fTempRange2, double fHeaterRange1, double fHeaterRange2);
	void SetHighlight(int nUnit, int nGraph);

protected:
	CGraphData *m_pGraphData;
	SGraphLine *m_pGraphLine;
	CDC m_dcGraph;
	CBitmap m_bmpGraph;
	CRect m_rectView;
	CSize m_sizeView;
	BOOL m_bSetBitmap;
	CPen m_penGraphScale;
	CPen m_penGray;
	CPen m_penLightGray;
	CPen m_penZoom;
	CPen m_penDataLine[N_UNIT][N_GRAPH];
	CPen m_penDataLine2[N_UNIT][N_GRAPH];
	CFont m_fontGraph;
	CFont m_fontGraph2;
	CFont m_fontGraph3;
	double m_fTimeRange1;
	double m_fTimeRange2;
	double m_fTempRange1;
	double m_fTempRange2;
	double m_fHeaterRange1;
	double m_fHeaterRange2;
	CRect m_rectScale;
	CPoint m_pointScroll;
	CPoint m_pointZoom1;
	CPoint m_pointZoom2;
	HCURSOR	m_hCursorSave;
	int m_nMouseMode;
	int m_nHighlightUnit;
	int m_nHighlightGraph;

	void DrawGraph();
	void SetScaleRect();
	void CreateBitmap();
	void GetDataRange(int &nStartIndex, int &nEndIndex);
	void DrawScale();
	void DrawData(int nUnit, int nGraph, int nStartIndex, int nEndIndex, CPen &oPen);
	double GetLinearScaleStep(double fRange, int nPixel, int *pStep);

	DECLARE_MESSAGE_MAP()
	afx_msg void OnPaint();
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	virtual void PreSubclassWindow();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg void OnRButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnRButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnRButtonDblClk(UINT nFlags, CPoint point);
};


