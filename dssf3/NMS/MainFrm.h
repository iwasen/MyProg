#pragma once

#include "MyCtrl.h"

class CMainFrame : public CFrameWnd
{
protected: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CMainFrame();
	DECLARE_DYNCREATE(CMainFrame)

	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

	virtual ~CMainFrame();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	CStatusBar	m_wndStatusBar;
	CMyToolBar	m_wndToolBar;
	ULONG m_nCheckData;

	DECLARE_MESSAGE_MAP()
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnClose();
	afx_msg LRESULT OnGetCheckLicense1(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnGetCheckLicense2(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnRegistLicense(WPARAM wParam, LPARAM lParam);
};
