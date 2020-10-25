#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		FftOctaveView.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		1/3�I�N�^�[�u��̓O���t�̃E�B���h�E�N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
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
