// PictView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "PictView.h"
#include "PictDoc.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CPictView

IMPLEMENT_DYNCREATE(CPictView, CScrollView)

CPictView::CPictView()
{
	m_bIgnoreUpdate = FALSE;
	m_bScrollPicture = FALSE;
	m_hBrushBkgnd.CreateSolidBrush(::GetSysColor(COLOR_3DFACE));
}

CPictView::~CPictView()
{
}


BEGIN_MESSAGE_MAP(CPictView, CScrollView)
	ON_WM_ERASEBKGND()
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	ON_COMMAND(ID_FILE_PRINT, CScrollView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_DIRECT, CScrollView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_PREVIEW, CScrollView::OnFilePrintPreview)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPictView 描画

void CPictView::OnInitialUpdate()
{
	CScrollView::OnInitialUpdate();

	CSize sizeTotal;
	// TODO: このビュー全体のサイズを計算してください。
	sizeTotal.cx = sizeTotal.cy = 100;
	SetScrollSizes(MM_TEXT, sizeTotal);
}

void CPictView::OnDraw(CDC* pDC)
{
	DrawPicture(pDC);
}

/////////////////////////////////////////////////////////////////////////////
// CPictView 診断

#ifdef _DEBUG
void CPictView::AssertValid() const
{
	CScrollView::AssertValid();
}

void CPictView::Dump(CDumpContext& dc) const
{
	CScrollView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CPictView メッセージ ハンドラ

void CPictView::DrawPicture(CDC *pDC)
{
	int widthSrc, heightSrc;
	int widthDst, heightDst;
	int xDst, yDst;
	CRect rcView;
	CPictDoc *pDoc = (CPictDoc *)GetDocument();

	if (pDoc->m_hDIB == NULL)
		return;

	if (m_bIgnoreUpdate) {
		m_bIgnoreUpdate = FALSE;
		return;
	}

	GetClientRect(rcView);

	short width, height, color;
	MMPictureGetInfo(pDoc->m_hDIB, &width, &height, &color);
	widthSrc = width;
	heightSrc = height;

	switch (pDoc->m_nAutoSize) {
	case AUTOSIZE_NONE:
		widthDst = (int)(widthSrc * pDoc->m_fPictRatio + 0.5);
		heightDst = (int)(heightSrc * pDoc->m_fPictRatio + 0.5);
		break;
	case AUTOSIZE_WIDTH:
		widthDst = rcView.right;
		heightDst = heightSrc * rcView.right / widthSrc;
		pDoc->m_fPictRatio = (double)rcView.right / widthSrc;
		break;
	case AUTOSIZE_BOTH:
		widthDst = rcView.right;
		heightDst = heightSrc * rcView.right / widthSrc;
		pDoc->m_fPictRatio = (double)rcView.right / widthSrc;
		if (heightDst > rcView.bottom) {
			widthDst = widthSrc * rcView.bottom / heightSrc;
			heightDst = rcView.bottom;
			pDoc->m_fPictRatio = (double)rcView.bottom / heightSrc;
		}
		break;
	}

	CString str;
	CComboBox &comboBox = ((CPictFrame *)GetParent())->GetComboBox();
	str.Format("%d%%", (int)(pDoc->m_fPictRatio * 100 + 0.5));
	comboBox.SetWindowText(str);
	comboBox.SetEditSel(0, -1);

	xDst = (rcView.right - widthDst) / 2;
	if (xDst < 0)
		xDst = 0;

	yDst = (rcView.bottom - heightDst) / 2;
	if (yDst < 0)
		yDst = 0;
	
	if (m_sizeDraw.cx != widthDst || m_sizeDraw.cy != heightDst) {
		m_sizeDraw.cx = widthDst;
		m_sizeDraw.cy = heightDst;
		SetScrollSizes(MM_TEXT, m_sizeDraw);

		CRect rcView2;
		GetClientRect(rcView2);
		if (pDoc->m_nAutoSize == AUTOSIZE_WIDTH && rcView.right > rcView2.right) {
			m_sizeDraw.cx = rcView2.right;
			m_sizeDraw.cy = heightDst;
			SetScrollSizes(MM_TEXT, m_sizeDraw);
			m_bIgnoreUpdate = TRUE;
			UpdateWindow();
		} else {
			if (rcView.right != rcView2.right || rcView.bottom != rcView2.bottom)
				return;
		}
	}

	MMPictureDraw(pDoc->m_hDIB, pDC->m_hDC, xDst, yDst, widthDst, heightDst, 0, 0, widthSrc, heightSrc);
}

BOOL CPictView::OnEraseBkgnd(CDC* pDC) 
{
	if (m_bIgnoreUpdate)
		return TRUE;

	CRect rect;
	pDC->GetClipBox(rect);
	pDC->FillRect(rect, &m_hBrushBkgnd);
	return TRUE;
}

void CPictView::OnLButtonDown(UINT nFlags, CPoint point) 
{
	m_bScrollPicture = TRUE;
	CPoint scrollPos = GetScrollPosition();
	m_posPicture = (CPoint)(scrollPos + point);
	::SetCursor(theApp.m_hCursorHandClose);

	SetCapture();
}

void CPictView::OnLButtonUp(UINT nFlags, CPoint point) 
{
	CPoint pointOrg;

	if (GetCapture() != this)
		return;

	ReleaseCapture();
	m_bScrollPicture = FALSE;
	::SetCursor(theApp.m_hCursorHandOpen);
}

void CPictView::OnMouseMove(UINT nFlags, CPoint point) 
{
	if (m_bScrollPicture) {
		CPoint scrollPos = m_posPicture - point;

		CRect rectView;
		GetClientRect(rectView);
		if (rectView.right >= m_sizeDraw.cx)
			scrollPos.x = 0;
		if (rectView.bottom >= m_sizeDraw.cy)
			scrollPos.y = 0;
		ScrollToPosition(scrollPos);
		::SetCursor(theApp.m_hCursorHandClose);
	} else
		::SetCursor(theApp.m_hCursorHandOpen);
}

BOOL CPictView::OnPreparePrinting(CPrintInfo* pInfo) 
{
	pInfo->SetMaxPage(1);
	return DoPreparePrinting(pInfo);
}

void CPictView::OnPrint(CDC* pDC, CPrintInfo* pInfo) 
{
	CPictDoc *pDoc = (CPictDoc *)GetDocument();

	PrintPicture(pDC, pDoc->m_hDIB);
}
