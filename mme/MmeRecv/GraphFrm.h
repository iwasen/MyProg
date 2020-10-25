#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		GraphFrm.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフのフレームウィンドウクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "GraphWnd.h"
#include "GraphBar.h"
#include "afxmt.h"

// CGraphFrm フレーム

class CGraphFrm : public CFrameWnd
{
	friend class CGraphBar;

	DECLARE_DYNCREATE(CGraphFrm)
public:
	CGraphFrm();
	virtual ~CGraphFrm();

	void DispGraph(LPCTSTR pReceiveFilePath, SGraphLine &oGraphLine, LPCTSTR pTitle, CWnd *pParentWnd, double fTempRange1, double fTempRange2, double fHeaterRange1, double fHeaterRange2);
	void DispGraph(CGraphData &oGraphData, SGraphLine &oGraphLine, LPCTSTR pTitle, CWnd *pParentWnd, double fTempRange1, double fTempRange2, double fHeaterRange1, double fHeaterRange2);
	void ChangeGraphLine(const SGraphLine &oGraphLine);
	void ScrollTime(double fScrollPos);
	void ZoomTime(double fZoomPos);
	void ScrollLevel(double fScrollPos);
	void ZoomLevel(double fZoomPos);
	void MouseScroll(double fTimeScroll, double fLevelScroll);
	void MouseZoom(double fTimePos1, double fTimePos2, double fLevelPos1, double fLevelPos2);
	void ZoomReset();

protected:
	CGraphWnd m_wndGraphView;
	CStatusBar m_wndStatusBar;
	CGraphBar m_wndGraphBar;
	CGraphData m_oGraphData;
	SGraphLine m_oGraphLine;
	CString m_sReceiveFilePath;
	CGraphData *m_pGraphData;
	double m_fTimeRange1;
	double m_fTimeRange2;
	double m_fTempRange1;
	double m_fTempRange2;
	double m_fHeaterRange1;
	double m_fHeaterRange2;
	HANDLE m_hThread;
	CEvent m_oEvent;
	BOOL m_bExit;
	double m_fTimeZoom;
	double m_fLevelZoom;
	double m_fTimeOffset;
	double m_fLevelOffset;

	static UINT ReadFileThreadEntry(LPVOID pParam);
	void ReadFileThread();
	void ReadFile(LPCTSTR pReceiveNamePath);
	static UINT CopyGraphDataThreadEntry(LPVOID pParam);
	void CopyGraphDataThread();
	void UpdateGraph();

	DECLARE_MESSAGE_MAP()
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnClose();
	afx_msg LRESULT OnReadyGraph(WPARAM wParam, LPARAM lParam);
};


