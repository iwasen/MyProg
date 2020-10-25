#pragma once

#include "CsvFile.h"

class CDstManualWnd : public CWnd
{
public:
	CDstManualWnd();

	void Initialize();
	void Resize();
	void DispGraph(const double *pLeftDst, const double *pRightDst, int nChannel, int nMaxHxxxonics);
	void CsvOutput(CCsvFile &cCsvFile, const double *pLeftDst, const double *pRightDst, int nMaxHxxxonics);
	HBITMAP GetBitmap();

protected:
	void Initialize2();
	void Uninitialize2();
	void SetBitmap(int nChannel, int nMaxHxxxonics);
	void DrawScaleDst(int nLeft, int nTop, int nRight, int nBottom, int nMaxHxxxonics);

	BOOL m_bInitialized;
	int m_nWidth;
	int m_nHeight;
	int m_nScaleLeft;
	int m_nScaleTop;
	int m_nScaleRight;
	int m_nScaleBottom;
	int m_nScaleWidth;
	int m_nScaleHeight;
	CDC m_dcMem;
	CDC m_dcMem2;
	CBitmap m_bitmapMem;
	CBitmap m_bitmapLeft;
	CBitmap m_bitmapRight;
	CBitmap m_bitmapLeftTotal;
	CBitmap m_bitmapRightTotal;
	CPen m_penBlack;
	CPen m_penGray;
	CPen m_penLightGray;
	CPen m_penLeft;
	CPen m_penRight;
	CFont m_Font;
	CFont m_Font2;
	CBrush m_brushWhite;

	DECLARE_MESSAGE_MAP()
	afx_msg void OnPaint();
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
};
