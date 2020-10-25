#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		FftSpectrumView.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スペクトル解析グラフのウィンドウクラス定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "FftSpectrumDraw.h"

// CFftSpectrumView

class CFftSpectrumView : public CWnd
{
	DECLARE_DYNAMIC(CFftSpectrumView)

public:
	CFftSpectrumView();

	void Initialize();
	void DispGraph(const double *pDataX, const double *pDataY, const double *pDataZ, const double *pDataG, int nData, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);
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
	CFftSpectrumDraw m_cFftSpectrumDraw;
	CSize m_sizeBitmap;
	BOOL m_bDispGraph;
	int m_nCursorX;
	CPen m_penCursor;
	BOOL m_bTrackMouse;
};
