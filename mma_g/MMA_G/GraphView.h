#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		GraphView.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�O���t�̃E�B���h�E�N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "GraphDraw.h"

// CGraphView �E�B���h�E

class CGraphView : public CWnd
{
// �R���X�g���N�V����
public:
	CGraphView();

	void UpdateGraph();
	void DispSelectArea();
	BOOL OnMouseWheel(UINT nFlags, short zDelta, CPoint pt);

	BOOL m_bAutoScroll;

protected:
	void CreateBitmap(CSize sizeBitmap);
	void SetScrollBar();
	void MakeScrollInfo(const SDispRange &stDispRange, SCROLLINFO &si, BOOL bDir);

	DECLARE_MESSAGE_MAP()
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	afx_msg void OnPaint();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg void OnRButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnRButtonUp(UINT nFlags, CPoint point);

protected:
	CDC m_dcGraph;
	CBitmap m_bmpGraph;
	CSize m_sizeBitmap;
	BOOL m_bSetBitmap;
	CPoint m_pointScroll;
	HCURSOR	m_hCursorHandClose;
	HCURSOR	m_hCursorSave;
	int m_nMouseMoveMode;
	CGraphDraw m_cDrawGraph;
	CScrollBar m_cScrollBarX;
	CScrollBar m_cScrollBarY;
	CScrollBar m_cScrollBarZ;
	int m_nScroolGraph;
	double m_fSelectStartTime;
};

