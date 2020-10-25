// ManualEditSublineInfo.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"
#include "ManualEditSublineInfo.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define MARGIN	3

/////////////////////////////////////////////////////////////////////////////
// CManualEditSublineInfo

CManualEditSublineInfo::CManualEditSublineInfo()
{
	m_bMoving = FALSE;
	m_fontText.CreatePointFont(80, g_pConst->m_sDefaultFontName);
}

CManualEditSublineInfo::~CManualEditSublineInfo()
{
}


BEGIN_MESSAGE_MAP(CManualEditSublineInfo, CWnd)
	//{{AFX_MSG_MAP(CManualEditSublineInfo)
	ON_WM_ERASEBKGND()
	ON_WM_PAINT()
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CManualEditSublineInfo メッセージ ハンドラ

void CManualEditSublineInfo::Create(CWnd *pParentWnd)
{
	CWnd::Create(NULL, "", WS_CHILD, CRect(0, 0, 0, 0), pParentWnd, -1);
}

void CManualEditSublineInfo::SetText(LPCTSTR pText1, LPCTSTR pText2, LPCTSTR pText3)
{
	m_sText[0] = pText1;
	m_sText[1] = pText2;
	m_sText[2] = pText3;

	CDC *pDC = GetDC();
	CFont *pFontOrg = pDC->SelectObject(&m_fontText);

	CSize sizeText;
	CSize sizeWindow(0, 0);
	for (int i = 0; i < MAX_LINES; i++) {
		sizeText = pDC->GetTextExtent(m_sText[i]);
		if (sizeText.cx > sizeWindow.cx)
			sizeWindow.cx = sizeText.cx;
		sizeWindow.cy += sizeText.cy + 1;
	}

	pDC->SelectObject(pFontOrg);
	ReleaseDC(pDC);

	sizeWindow += CSize(MARGIN * 2, MARGIN * 2);

	SetWindowPos(NULL, 0, 0, sizeWindow.cx, sizeWindow.cy, SWP_NOMOVE | SWP_NOZORDER);
	Invalidate();
}

BOOL CManualEditSublineInfo::OnEraseBkgnd(CDC* pDC) 
{
	return TRUE;
}

void CManualEditSublineInfo::OnPaint() 
{
	CPaintDC dc(this); // 描画用のデバイス コンテキスト

	CRect rect;
	GetClientRect(rect);

	CFont *pFontOrg = dc.SelectObject(&m_fontText);
	CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);

	dc.FillSolidRect(rect, RGB(255, 255, 204));
	dc.Rectangle(rect);

	int x;
	int y = MARGIN;
	for (int i = 0; i < MAX_LINES; i++) {
		CSize sizeText;
		sizeText = dc.GetTextExtent(m_sText[i]);
		if (i == 0) {
			x = (rect.Width() - sizeText.cx) / 2;
		} else
			x = MARGIN;

		dc.TextOut(x, y, m_sText[i]);
		y += sizeText.cy + 1;
	}

	dc.SelectObject(pBrushOrg);
	dc.SelectObject(pFontOrg);
}

void CManualEditSublineInfo::OnLButtonDown(UINT nFlags, CPoint point) 
{
	WINDOWPLACEMENT wpl;

	GetWindowPlacement(&wpl);
	m_rectMove = wpl.rcNormalPosition;
	m_pointMove = point;
	
	m_bMoving = TRUE;
	SetCapture();
	
	CWnd::OnLButtonDown(nFlags, point);
}

void CManualEditSublineInfo::OnLButtonUp(UINT nFlags, CPoint point) 
{
	m_bMoving = FALSE;
	ReleaseCapture();
	
	CWnd::OnLButtonUp(nFlags, point);
}

void CManualEditSublineInfo::OnMouseMove(UINT nFlags, CPoint point) 
{
	if (m_bMoving && point != m_pointMove) {
		m_rectMove += point - m_pointMove;
		SetWindowPos(NULL, m_rectMove.left, m_rectMove.top, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
		UpdateWindow();
	}
	
	CWnd::OnMouseMove(nFlags, point);
}
