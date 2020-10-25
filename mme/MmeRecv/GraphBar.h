#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		GraphBar.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフウィンドウのダイアログバークラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "MyCtrl.h"
#include "GraphData.h"

// CGraphBar ダイアログ

class CGraphBar : public CDialogBar
{
	friend class CGraphFrm;

	DECLARE_DYNAMIC(CGraphBar)

public:
// ダイアログ データ
	enum { IDD = IDD_GRAPH_BAR };

	void DispHorizontalAxis(double fTimeBegin, double fTimeEnd);
	void DispVerticalAxis(double fTempBegin, double fTempEnd, double fHeaterBegin, double fHeaterEnd);
	void DispDataLine(const SGraphLine &oGraphLine);
	void SetTimeZoom(double fZoom);
	void SetLevelZoom(double fZoom);

protected:
	CEditEx m_cEditTimeBegin;
	CEditEx m_cEditTimeEnd;
	CSliderCtrl m_cSliderTimeZoom;
	CEditEx m_cEditTempBegin;
	CEditEx m_cEditTempEnd;
	CEditEx m_cEditHeaterBegin;
	CEditEx m_cEditHeaterEnd;
	CSliderCtrl m_cSliderLevelZoom;
	CButtonEx m_cCheckGraph[N_UNIT][N_GRAPH];
	CStaticEx m_cStaticGraph[N_UNIT][N_GRAPH];

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg LRESULT OnInitDialog(WPARAM wParam, LPARAM lParam);
	afx_msg void OnBnClickedCheckGraph();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg LRESULT OnMouseIn(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnMouseOut(WPARAM wParam, LPARAM lParam);
};
