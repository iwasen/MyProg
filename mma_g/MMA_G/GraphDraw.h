#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		GraphDraw.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータグラフの描画クラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "GraphData.h"
#include "BaseDraw.h"

class CGraphDraw : public CBaseDraw
{
public:
	CGraphDraw(CDC *pDC = NULL, int nPenWidth = 1);

	void DrawGraph(CDC &dc, CRect rectView, const CGraphData &cGraphData, BOOL bDrawSelect, int nGraphKind);
	void DrawTimeCursor(CDC &dc, const CGraphData &cGraphData, int nGraphKind);
	void DrawSelect(CDC &dc, const CGraphData &cGraphData, int nGraphKind);
	void DispScrollBar(CScrollBar &cScrollBarX, CScrollBar &cScrollBarY, CScrollBar &cScrollBarZ);
	int CheckGraphPoint(CPoint point);

protected:
	void SetScaleRect(CDC &dc, int nScaleNum);
	void DrawScale(CDC &dc, const CGraphData &cGraphData, CRect rectScale, const SDispRange &stLevelRange, LPCTSTR pXYZ, int nYAxis);
	void DrawData(CDC &dc, const CGraphData &cGraphData, CRect rectScale, const SDispRange &stLevelRange, const double *pData, CPen &penGraphLine, CBrush &brushGraphLine);
	CRect GetScaleRect(int nScaleNo);
	void DrawTimeCursorSub(CDC &dc, const CGraphData &cGraphData, CRect rectScale);
	void DrawSelectSub(CDC &dc, const CGraphData &cGraphData, CRect rectScale);
	void DispScrollBarSub(CScrollBar &cScrollBar, CRect rectScale);

public:
	CPen m_penGraphLineX;
	CPen m_penGraphLineY;
	CPen m_penGraphLineZ;
	CPen m_penGraphLineD;

protected:
	CPen m_penGraphScale;
	CPen m_penGray;
	CPen m_penLightGray;
	CPen m_penCursor;
	CPen m_penSelect;
	CFont m_fontGraph;
	CFont m_fontGraph2;
	CBrush m_brushGraphLineX;
	CBrush m_brushGraphLineY;
	CBrush m_brushGraphLineZ;
	CBrush m_brushGraphLineD;
	CBrush m_brushSelect;
	double m_fSelectBegin;
	double m_fSelectEnd;
	int m_nScaleHeight;
};
