// FindFrame.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "mmlib.h"
#include "FindFrame.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CFindFrame

IMPLEMENT_DYNCREATE(CFindFrame, CFrameWnd)

CFindFrame::CFindFrame()
{
}

CFindFrame::~CFindFrame()
{
}


BEGIN_MESSAGE_MAP(CFindFrame, CFrameWnd)
	ON_WM_CLOSE()
	ON_WM_CREATE()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CFindFrame メッセージ ハンドラ

BOOL CFindFrame::PreCreateWindow(CREATESTRUCT& cs) 
{
	CWinApp *pWinApp = AfxGetApp();

	int left = pWinApp->GetProfileInt(g_SectionFindWindow, "Left", 0);
	int top = pWinApp->GetProfileInt(g_SectionFindWindow, "Top", 0);
	int right = pWinApp->GetProfileInt(g_SectionFindWindow, "Right", 0);
	int bottom = pWinApp->GetProfileInt(g_SectionFindWindow, "Bottom", 0);

	if (right != 0 && bottom != 0) {
		cs.x = left;
		cs.y = top;
		cs.cx = right - left;
		cs.cy = bottom - top;
	}

	cs.style &= ~FWS_ADDTOTITLE;

	return CFrameWnd::PreCreateWindow(cs);
}

void CFindFrame::OnClose() 
{
	CWinApp *pWinApp = AfxGetApp();

	WINDOWPLACEMENT winpl;
	winpl.length = sizeof(WINDOWPLACEMENT);
	GetWindowPlacement(&winpl);

	pWinApp->WriteProfileInt(g_SectionFindWindow, "Left", winpl.rcNormalPosition.left);
	pWinApp->WriteProfileInt(g_SectionFindWindow, "Top", winpl.rcNormalPosition.top);
	pWinApp->WriteProfileInt(g_SectionFindWindow, "Right", winpl.rcNormalPosition.right);
	pWinApp->WriteProfileInt(g_SectionFindWindow, "Bottom", winpl.rcNormalPosition.bottom);
	
	CFrameWnd::OnClose();
}

int CFindFrame::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	CFont *pFont;
	LOGFONT lf;

	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	if (!m_wndReBar.Create(this)) {
		TRACE0("Failed to create rebar\n");
		return -1;
	}

	pFont = m_wndReBar.GetFont();
	pFont->GetLogFont(&lf);
	int lfHeight = -MulDiv(lf.lfHeight, GetDeviceCaps(GetDC()->m_hDC, LOGPIXELSY), 72);

	if (!m_wndKeyword.Create(WS_CHILD | WS_TABSTOP, CRect(0, 0, 100, lfHeight + 2), this, IDC_KEYWORD)) {
		TRACE0("Failed to create combobox\n");
		return -1;      // fail to create
	}
	if (!m_wndFind.Create(GetString(IDS_FIND), WS_CHILD | WS_TABSTOP | BS_DEFPUSHBUTTON, CRect(0, 0, 60, lfHeight + 4), this, IDC_FIND)) {
		TRACE0("Failed to create combobox\n");
		return -1;      // fail to create
	}

	m_wndKeyword.ModifyStyleEx(0, WS_EX_CLIENTEDGE);
	m_wndFind.EnableWindow();

	m_wndKeyword.SetFont(pFont);
	m_wndFind.SetFont(pFont);

	m_wndReBar.AddBar(&m_wndKeyword, GetString(IDS_KEYWORD), NULL, RBBS_NOGRIPPER);
	m_wndReBar.AddBar(&m_wndFind, NULL, NULL, RBBS_NOGRIPPER);

	SetWindowText(GetString(IDS_KEYWORD_FIND));

	return 0;
}
