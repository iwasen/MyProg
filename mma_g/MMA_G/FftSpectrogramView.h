#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		FftSpectrogramView.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スペクトログラム解析グラフのウィンドウクラス定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "FftSpectrogramDraw.h"

// CFftSpectrogramView

class CFftSpectrogramView : public CWnd
{
	DECLARE_DYNAMIC(CFftSpectrogramView)

public:
	CFftSpectrogramView();

	void Initialize();
	void DispGraph(const double *pDataG, int nFreqData, int nTimeData, double fSampleRate, double fTimeMin, double fTimeMax, double fTimeOffset, double fTimeZoom, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);
	void HideGraph();
	void DispCursor(double fPosX, double fPosY);
	void SetTimeScrollBar(double fTimeOffset, double fTimeZoom);

	BOOL m_bAutoScroll;

protected:
	DECLARE_MESSAGE_MAP()
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnPaint();
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	virtual void PreSubclassWindow();
	LRESULT OnMouseLeave(WPARAM wParam, LPARAM lParam);
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg LRESULT OnNcHitTest(CPoint point);
	afx_msg void OnNcLButtonDown(UINT nHitTest, CPoint point);
	afx_msg void OnNcLButtonUp(UINT nHitTest, CPoint point);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);

protected:
	CDC m_dcGraph;
	CBitmap m_bmpGraph;
	CFftSpectrogramDraw m_cFftSpectrogramDraw;
	CSize m_sizeBitmap;
	BOOL m_bDispGraph;
	int m_nCursorX;
	int m_nCursorY;
	CPen m_penCursor;
	BOOL m_bTrackMouse;
};
