#pragma once

#include "MyCtrl.h"

class CSasView;

class CMainFrame : public CFrameWnd
{
public:
	CView *GetListView();
	CView *GetTreeView();

protected: // シリアライズ機能のみから作成します。
	CMainFrame();
	DECLARE_DYNCREATE(CMainFrame)

	CSplitterWnd m_wndSplitter;

	CSasView* GetRightPane();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	CStatusBar	m_wndStatusBar;
	CMyToolBar	m_wndToolBar;

	ULONG m_nCheckData;

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext);
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnClose();
	afx_msg void OnUpdateViewStyles(CCmdUI* pCmdUI);
	afx_msg void OnViewStyle(UINT nCommandID);
	afx_msg LRESULT OnGetCheckLicense1(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnGetCheckLicense2(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnRegistLicense(WPARAM wParam, LPARAM lParam);
};
