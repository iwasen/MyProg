#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		FftOctaveView.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		1/3オクターブ解析グラフのウィンドウクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "FftOctaveDraw.h"

// CFftOctaveView

class CFftOctaveView : public CWnd
{
	DECLARE_DYNAMIC(CFftOctaveView)

public:
	CFftOctaveView();

	void Initialize();
	void DispGraph(const double *pDataX, const double *pDataY, const double *pDataZ, const double *pDataG, const double *pLimit1, const double *pLimit2, int nData, const double *pOctaveBandFreq, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);
	void HideGraph();
	void DispCursor(double fPosX);

protected:
	DECLARE_MESSAGE_MAP()
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnPaint();
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	virtual void PreSubclassWindow();
	LRESULT OnMouseLeave(WPARAM wParam, LPARAM lParam);

protected:
	CDC m_dcGraph;
	CBitmap m_bmpGraph;
	CFftOctaveDraw m_cFftOctaveDraw;
	CSize m_sizeBitmap;
	BOOL m_bDispGraph;
	int m_nCursorX;
	CPen m_penCursor;
	BOOL m_bTrackMouse;
};
