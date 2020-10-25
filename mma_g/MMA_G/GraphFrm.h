#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		GraphFrm.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータグラフのメインフレームウィンドウクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "GraphView.h"
#include "GraphBar.h"
#include "GraphData.h"
#include "MyCtrl.h"
#include "DigitalFilter.h"
#include "FftOctaveDlg.h"
#include "FftSpectrumDlg.h"
#include "FftSpectrogramDlg.h"

class CGraphFrame : public CFrameWnd
{
protected: 
	DECLARE_DYNAMIC(CGraphFrame)

public:
	CGraphFrame();
	BOOL DispGraph(LPCTSTR pFilePath, int nGraphKind, int nDataType);
	BOOL DispRealtimeGraph(LPCTSTR pFilePath, int nGraphKind, BOOL bExistLossFile);
	void DispZeroOffsetGraph(CGraphFrame *pGraphFrame);
	BOOL UpdateRealtimeGraph(BOOL bExistLossFile);
	void UpdateGraph();
	void SetZoomTime(double fTimeZoom, BOOL bCheck = TRUE);
	void SetZoomLevel(SDispRange &stDispRange, double fLevelZoom);
	void SetTimeCursor(double fTimeCursor);
	void UpdateSelectArea();
	static void CloseAll();
	void SetRealtimeDispEnd(double fTimeDispEnd);

protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	virtual BOOL OnCmdMsg(UINT nID, int nCode, void* pExtra, AFX_CMDHANDLERINFO* pHandlerInfo);

public:
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

public:
	CGraphData m_cGraphData;
	int m_nGraphKind;
	BOOL m_bRealtime;

protected:
	void AutoScroll();
	void DispTimeCursor();
	void PrintSample(CDC &dc, CRect rectView, CPen *x, CPen *y, CPen *z, CPen *d);
	void PrintGraphInfo(CDC &dc, CRect rectView);
	void DispRangeCheck(SDispRange &stDispRange);
	void FilterProcess();
	void SetLevelBegin(SDispRange &stDispRange, double fLevelBegin);
	void SetLevelEnd(SDispRange &stDispRange, double fLevelEnd);
	void UpdateRealtimeDisp();

	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnSetFocus(CWnd *pOldWnd);
	afx_msg void OnBnClickedButtonTimeZoomAll();
	afx_msg void OnBnClickedButtonTimeZoomIn();
	afx_msg void OnBnClickedButtonTimeZoomOut();
	afx_msg void OnBnClickedButtonLevelZoomAll();
	afx_msg void OnBnClickedButtonLevelZoomIn();
	afx_msg void OnBnClickedButtonLevelZoomOut();
	afx_msg void OnBnClickedCheckScrollStart();
	afx_msg void OnBnClickedButtonFftOctave();
	afx_msg void OnBnClickedButtonFftSpectrum();
	afx_msg void OnBnClickedButtonFftSpectrogram();
	afx_msg void OnBnClickedButtonSelectAll();
	afx_msg void OnBnClickedButtonSelectManual();
	afx_msg void OnBnClickedButtonInitialize();
	afx_msg void OnPrint();
	afx_msg void OnClose();
	afx_msg void OnTimer(UINT_PTR nIDEvent);
	afx_msg void OnUpdateButtonFft(CCmdUI *pCmdUI);
	afx_msg void OnUpdateButtonTimeZoom(CCmdUI *pCmdUI);
	afx_msg void OnUpdateButtonLevelZoom(CCmdUI *pCmdUI);
	afx_msg void OnUpdateButtonSelect(CCmdUI *pCmdUI);
	afx_msg void OnCbnSelchangeComboSampleRate();
	afx_msg void OnBnClickedButtonTimeCursorToLeft();
	afx_msg void OnBnClickedButtonTimeCursorToRight();
	afx_msg void OnEnChangeEditTimeZoom();
	afx_msg void OnEnChangeEditLevelZoomX();
	afx_msg void OnEnChangeEditLevelZoomY();
	afx_msg void OnEnChangeEditLevelZoomZ();
	afx_msg void OnBnClickedButtonFilterSet();
	afx_msg void OnCbnSelchangeComboFilterBand();
	afx_msg void OnUpdateButtonFilterSet(CCmdUI *pCmdUI);
	afx_msg LRESULT OnRetKey(WPARAM wParam, LPARAM lParam);
	afx_msg void OnDestroy();
	afx_msg LRESULT OnUserOpenGraph(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserCloseGraph(WPARAM wParam, LPARAM lParam);
	afx_msg void OnBnClickedCheckDispD();
	afx_msg void OnBnClickedCheckDispX();
	afx_msg void OnBnClickedCheckDispY();
	afx_msg void OnBnClickedCheckDispZ();
	afx_msg BOOL OnMouseWheel(UINT nFlags, short zDelta, CPoint pt);
	afx_msg void OnBnClickedCheckZeroPointGraph();
	afx_msg LRESULT OnUserCloseZeroOffset(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

	void OnRetKeyEditLevelBeginX();
	void OnRetKeyEditLevelBeginY();
	void OnRetKeyEditLevelBeginZ();
	void OnRetKeyEditLevelEndX();
	void OnRetKeyEditLevelEndY();
	void OnRetKeyEditLevelEndZ();

protected:
	CStatusBar  m_wndStatusBar;
	CToolBarEx	m_wndToolBar;
	CGraphView	m_wndGraphView;
	CGraphBar	m_wndGraphBar;
	CString m_sFilePath;
	BOOL m_bInUpdateGraph;
	CDigitalFilter m_cFilter;
	BOOL m_bFilter;
	CString m_sTitle;
	CArray <HWND, HWND> m_aryGraphWnd;
	HWND m_hWndParent;
	static CArray <HWND, HWND> m_aFrameWnd;
	CFftSpectrogramDlg *m_pRealtimeSpectrogramDlg;
	DWORD m_dwAutoScrollTick;
	CGraphFrame *m_pZeroOffsetGraph;
	HWND m_hAcclGraphWnd;
};
