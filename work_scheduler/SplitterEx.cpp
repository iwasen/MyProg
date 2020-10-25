// SplitterEx.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"
#include "SplitterEx.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CSplitterEx

BEGIN_MESSAGE_MAP(CSplitterEx, CSplitterWnd)
	//{{AFX_MSG_MAP(CSplitterEx)
	ON_WM_CREATE()
	ON_WM_SIZE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CSplitterEx メッセージ ハンドラ

int CSplitterEx::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CSplitterWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	CRect rect(0, 0, 0, 0);
	m_wndLeft.Create(NULL, "", WS_CHILD, rect, this, -1);
	m_wndTop.Create(NULL, "", WS_CHILD, rect, this, -1);
	m_wndRight.Create(NULL, "", WS_CHILD, rect, this, -1);
	m_wndBottom.Create(NULL, "", WS_CHILD, rect, this, -1);

	return 0;
}

void CSplitterEx::ChangeSplit(CPoint point)
{
	if (GetColumnCount() == 1)
		SplitColumn(point.x);
	else {
		int nCol;
		if (GetActivePane(NULL, &nCol) != NULL)
			DeleteColumn(nCol == 0 ? 1 : 0);
	}
}

void CSplitterEx::RecalcLayout()
{
	CSplitterWnd::RecalcLayout();

	DisplayActiveFrame();
}

void CSplitterEx::OnSize(UINT nType, int cx, int cy)
{
	CSplitterWnd::OnSize(nType, cx, cy);

	DisplayActiveFrame();
}

void CSplitterEx::DisplayActiveFrame()
{
	CWnd *pWnd = GetActivePane(NULL, NULL);
	if (GetColumnCount() == 2 && pWnd != NULL) {
		CRect rect;
		pWnd->GetWindowRect(rect);
		ScreenToClient(rect);

		m_wndLeft.SetWindowPos(NULL, rect.left, rect.top, 1, rect.Height(), SWP_NOZORDER | SWP_SHOWWINDOW);
		m_wndTop.SetWindowPos(NULL, rect.left, rect.top, rect.Width(), 1, SWP_NOZORDER | SWP_SHOWWINDOW);
		m_wndRight.SetWindowPos(NULL, rect.right - 1, rect.top, 1, rect.Height(), SWP_NOZORDER | SWP_SHOWWINDOW);
		m_wndBottom.SetWindowPos(NULL, rect.left, rect.bottom - 1, rect.Width(), 1, SWP_NOZORDER | SWP_SHOWWINDOW);
	} else {
		m_wndLeft.ShowWindow(SW_HIDE);
		m_wndTop.ShowWindow(SW_HIDE);
		m_wndRight.ShowWindow(SW_HIDE);
		m_wndBottom.ShowWindow(SW_HIDE);
	}
}

BOOL CSplitterEx::IsActivePane(CWnd *pView)
{
	CWnd *pActiveWnd = GetActivePane(NULL, NULL);
	if (pActiveWnd != NULL) {
		if (pActiveWnd->m_hWnd == pView->m_hWnd)
			return TRUE;
	}

	return FALSE;
}
