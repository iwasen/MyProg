// MMPictView.cpp : CMMPictView クラスの動作の定義を行います。
//

#include "stdafx.h"
#include "MMView.h"
#include "MainFrm.h"
#include "MMViewDoc.h"
#include "MMPictView.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMPictView

IMPLEMENT_DYNCREATE(CMMPictView, CScrollView)

BEGIN_MESSAGE_MAP(CMMPictView, CScrollView)
	ON_WM_ERASEBKGND()
	ON_COMMAND(IDM_PICT_LEFT_ROTATE, OnPictLeftRotate)
	ON_UPDATE_COMMAND_UI(IDM_PICT_LEFT_ROTATE, OnUpdatePictLeftRotate)
	ON_COMMAND(IDM_PICT_RIGHT_ROTATE, OnPictRightRotate)
	ON_UPDATE_COMMAND_UI(IDM_PICT_RIGHT_ROTATE, OnUpdatePictRightRotate)
	ON_COMMAND(IDM_PICT_ENLARGE, OnPictEnlarge)
	ON_UPDATE_COMMAND_UI(IDM_PICT_ENLARGE, OnUpdatePictEnlarge)
	ON_COMMAND(IDM_PICT_REDUCE, OnPictReduce)
	ON_UPDATE_COMMAND_UI(IDM_PICT_REDUCE, OnUpdatePictReduce)
	ON_COMMAND(IDM_PICT_AUTOSIZE, OnPictAutosize)
	ON_UPDATE_COMMAND_UI(IDM_PICT_AUTOSIZE, OnUpdatePictAutosize)
	ON_COMMAND(IDM_PICT_AUTOSIZE2, OnPictAutosize2)
	ON_UPDATE_COMMAND_UI(IDM_PICT_AUTOSIZE2, OnUpdatePictAutosize2)
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	ON_WM_SETCURSOR()
	ON_UPDATE_COMMAND_UI(IDW_COMBO, OnUpdateCombo)
	ON_COMMAND(ID_EDIT_COPY, OnEditCopy)
	ON_UPDATE_COMMAND_UI(ID_EDIT_COPY, OnUpdateEditCopy)
	// 標準印刷コマンド
	ON_COMMAND(ID_FILE_PRINT, CScrollView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_DIRECT, CScrollView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_PREVIEW, CScrollView::OnFilePrintPreview)
	ON_CBN_SELCHANGE(IDW_COMBO, OnSelchangeCombo)
	ON_CBN_EDITUPDATE(IDW_COMBO, OnEditchangeCombo)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMPictView クラスの構築/消滅

CMMPictView::CMMPictView()
{
	m_fPictRatio = 1.0;
	m_nAutoSize = AfxGetApp()->GetProfileInt(g_SectionSettings, "AutoSize", AUTOSIZE_BOTH);
	m_bScrollPicture = FALSE;
	m_bIgnoreUpdate = FALSE;

	m_BrushBkgndPict.CreateSolidBrush(::GetSysColor(COLOR_3DFACE));
	m_BrushBkgndIcon.CreateSolidBrush(::GetSysColor(COLOR_3DFACE));
}

CMMPictView::~CMMPictView()
{
	AfxGetApp()->WriteProfileInt(g_SectionSettings, "AutoSize", m_nAutoSize);
}

BOOL CMMPictView::PreCreateWindow(CREATESTRUCT& cs)
{
	cs.dwExStyle |= WS_EX_CLIENTEDGE;

	return CScrollView::PreCreateWindow(cs);
}

/////////////////////////////////////////////////////////////////////////////
// CMMPictView クラスの描画

void CMMPictView::OnDraw(CDC* pDC)
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();

	switch (pDoc->m_nViewMode) {
	case VIEWMODE_ICON:
		DrawIcon(pDC);
		break;
	case VIEWMODE_PICT:
		DrawPicture(pDC);
		break;
	}
}

void CMMPictView::OnInitialUpdate()
{
//	CScrollView::OnInitialUpdate();

	CSize sizeTotal;
	// TODO: このビューのサイズの合計を計算します。
	sizeTotal.cx = sizeTotal.cy = 100;
	SetScrollSizes(MM_TEXT, sizeTotal);
}

/////////////////////////////////////////////////////////////////////////////
// CMMPictView クラスの印刷

BOOL CMMPictView::OnPreparePrinting(CPrintInfo* pInfo)
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();

	switch (pDoc->m_nViewMode) {
	case VIEWMODE_PICT:
		if (pDoc->GetCurrentItem()->m_hDIB1 == NULL)
			return FALSE;

		pInfo->SetMaxPage(1);
		break;
	case VIEWMODE_ICON:
		if (pDoc->m_nMaxPage == 0)
			return FALSE;

		pInfo->SetMaxPage(pDoc->m_nMaxPage);
		break;
	}
	return DoPreparePrinting(pInfo);
}

void CMMPictView::OnBeginPrinting(CDC* /*pDC*/, CPrintInfo* /*pInfo*/)
{
	// TODO: 印刷前の特別な初期化処理を追加してください。
}

void CMMPictView::OnEndPrinting(CDC* /*pDC*/, CPrintInfo* /*pInfo*/)
{
	// TODO: 印刷後の後処理を追加してください。
}

/////////////////////////////////////////////////////////////////////////////
// CMMPictView クラスの診断

#ifdef _DEBUG
void CMMPictView::AssertValid() const
{
	CScrollView::AssertValid();
}

void CMMPictView::Dump(CDumpContext& dc) const
{
	CScrollView::Dump(dc);
}

CMMViewDoc* CMMPictView::GetDocument() // 非デバッグ バージョンはインラインです。
{
	ASSERT(m_pDocument->IsKindOf(RUNTIME_CLASS(CMMViewDoc)));
	return (CMMViewDoc*)m_pDocument;
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMPictView クラスのメッセージ ハンドラ

void CMMPictView::DrawPicture(CDC *pDC)
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();
	int widthSrc, heightSrc;
	int widthDst, heightDst;
	int xDst, yDst;
	CRect rcView;

	if (m_bIgnoreUpdate) {
		m_bIgnoreUpdate = FALSE;
		return;
	}

	if (pItem == NULL || pItem->m_hDIB1 == NULL)
		return;

	GetClientRect(rcView);

	short width, height, color;
	MMPictureGetInfo(pItem->m_hDIB1, &width, &height, &color);
	widthSrc = width;
	heightSrc = height;

	switch (m_nAutoSize) {
	case AUTOSIZE_NONE:
		widthDst = (int)(widthSrc * m_fPictRatio + 0.5);
		heightDst = (int)(heightSrc * m_fPictRatio + 0.5);
		break;
	case AUTOSIZE_WIDTH:
		widthDst = rcView.right;
		heightDst = heightSrc * rcView.right / widthSrc;
		m_fPictRatio = (double)rcView.right / widthSrc;
		break;
	case AUTOSIZE_BOTH:
		widthDst = rcView.right;
		heightDst = heightSrc * rcView.right / widthSrc;
		m_fPictRatio = (double)rcView.right / widthSrc;
		if (heightDst > rcView.bottom) {
			widthDst = widthSrc * rcView.bottom / heightSrc;
			heightDst = rcView.bottom;
			m_fPictRatio = (double)rcView.bottom / heightSrc;
		}
		break;
	}

	CString str;
	CComboBox &comboBox = ((CMainFrame *)AfxGetMainWnd())->GetComboBox();
	str.Format("%d%%", (int)(m_fPictRatio * 100 + 0.5));
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

		if (m_nAutoSize == AUTOSIZE_WIDTH && rcView.right > rcView2.right) {
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

	MMPictureDraw(pItem->m_hDIB1, pDC->m_hDC, xDst, yDst, widthDst, heightDst, 0, 0, widthSrc, heightSrc);
}

void CMMPictView::DrawIcon(CDC *pDC)
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CRect rcDIB, rcDest;
	int i;
	int	count = 0;
	int	x, y, mod, row;
	short width, height,color;
	CRect rcVisible;
	CRect rcText;
	CSize sizeScroll(0, 0);
	CItem *pItem;

	if (pDoc->m_pMMFile == NULL)
		return;

	pDC->SetBkMode(TRANSPARENT);

	CRect rect;
	GetClientRect(&rect);

	int nColumn = max((rect.right - PICT_MARGIN) / (PICT_WIDTH + PICT_MARGIN), 1);

	CFont *pFontOrg = pDC->SelectObject(&pDoc->m_Font);

	for (i = 0; i < pDoc->m_nMaxPage; i++) {
		mod = count % nColumn;
		x = (PICT_WIDTH + PICT_MARGIN) * mod + PICT_MARGIN;
		rcDest.left = x;
		rcDest.right = x + PICT_WIDTH;

		row = count / nColumn;
		y = (PICT_HEIGHT + PICT_TEXT + PICT_MARGIN) * row + PICT_MARGIN;
		rcDest.top = y;
		rcDest.bottom = y + PICT_HEIGHT;

		rcVisible.left = rcDest.left - PICT_FRAME;
		rcVisible.top = rcDest.top - PICT_FRAME;
		rcVisible.right = rcDest.right + PICT_FRAME;
		rcVisible.bottom = rcDest.bottom + PICT_TEXT + PICT_MARGIN;

		if (rcVisible.right > sizeScroll.cx)
			sizeScroll.cx = rcVisible.right;

		if (rcVisible.bottom > sizeScroll.cy)
			sizeScroll.cy = rcVisible.bottom;

		count++;

		if (!pDC->RectVisible(rcVisible))
			continue;

		pItem = &pDoc->m_pItem[i];

		if (pItem->m_hDIB2 != NULL) {
			MMPictureGetInfo(pItem->m_hDIB2, &width, &height, &color);

			MMPictureDraw(pItem->m_hDIB2, pDC->m_hDC, (short)(rcDest.left + (PICT_WIDTH - width) / 2),
					(short)(rcDest.top + (PICT_HEIGHT - height) / 2), width, height, 0, 0, -1, -1);
		}

		rcDest.left -= PICT_FRAME;
		rcDest.top -= PICT_FRAME;
		rcDest.right += PICT_FRAME;
		rcDest.bottom += PICT_FRAME;
		pDC->DrawEdge(rcDest, BDR_RAISEDINNER, BF_RECT);

		pItem->m_IconRect = rcDest;

		rcText.left = x;
		rcText.top = y + PICT_HEIGHT + PICT_FRAME + 1;
		rcText.right = rcText.left + PICT_WIDTH;
		rcText.bottom = rcText.top + PICT_TEXT;
		pDC->DrawText(pItem->m_Title, rcText, DT_WORDBREAK);
	}

	SetScrollSizes(MM_TEXT, sizeScroll);

	pDC->SelectObject(pFontOrg);

	m_sizeDraw.cx = m_sizeDraw.cy = 0;
}

BOOL CMMPictView::OnEraseBkgnd(CDC* pDC) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CRect rect;

	if (m_bIgnoreUpdate)
		return TRUE;

	pDC->GetClipBox(rect);
	pDC->FillRect(rect, pDoc->m_nViewMode == VIEWMODE_PICT ? &m_BrushBkgndPict : &m_BrushBkgndIcon);
	return TRUE;
}

void CMMPictView::OnPictLeftRotate() 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	if (pItem != NULL && pItem->m_hDIB1 != NULL) {
		MMPictureRotate(pItem->m_hDIB1, 3);
		Invalidate(TRUE);
	}
}

void CMMPictView::OnUpdatePictLeftRotate(CCmdUI* pCmdUI) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	pCmdUI->Enable(pDoc->m_nViewMode == VIEWMODE_PICT && pItem != NULL && pItem->m_hDIB1 != NULL);
}

void CMMPictView::OnPictRightRotate() 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	if (pItem != NULL && pItem->m_hDIB1 != NULL) {
		MMPictureRotate(pItem->m_hDIB1, 1);
		Invalidate(TRUE);
	}
}

void CMMPictView::OnUpdatePictRightRotate(CCmdUI* pCmdUI) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	pCmdUI->Enable(pDoc->m_nViewMode == VIEWMODE_PICT && pItem != NULL && pItem->m_hDIB1 != NULL);
}

void CMMPictView::OnPictEnlarge() 
{
	m_nAutoSize = AUTOSIZE_NONE;
	m_fPictRatio *= 1.5;
	Invalidate(TRUE);
}

void CMMPictView::OnUpdatePictEnlarge(CCmdUI* pCmdUI) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	pCmdUI->Enable(pDoc->m_nViewMode == VIEWMODE_PICT && pItem != NULL && pItem->m_hDIB1 != NULL);
}

void CMMPictView::OnPictReduce() 
{
	m_nAutoSize = AUTOSIZE_NONE;
	m_fPictRatio /= 1.5;
	Invalidate(TRUE);
}

void CMMPictView::OnUpdatePictReduce(CCmdUI* pCmdUI) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	pCmdUI->Enable(pDoc->m_nViewMode == VIEWMODE_PICT && pItem != NULL && pItem->m_hDIB1 != NULL);
}

void CMMPictView::OnPictAutosize() 
{
	m_nAutoSize = (m_nAutoSize == AUTOSIZE_BOTH ? AUTOSIZE_NONE : AUTOSIZE_BOTH);
	Invalidate(TRUE);
}

void CMMPictView::OnUpdatePictAutosize(CCmdUI* pCmdUI) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	pCmdUI->Enable(pDoc->m_nViewMode == VIEWMODE_PICT && pItem != NULL && pItem->m_hDIB1 != NULL);
	pCmdUI->SetCheck(m_nAutoSize == AUTOSIZE_BOTH);
}

void CMMPictView::OnPictAutosize2() 
{
	m_nAutoSize = (m_nAutoSize == AUTOSIZE_WIDTH ? AUTOSIZE_NONE : AUTOSIZE_WIDTH);
	Invalidate(TRUE);
}

void CMMPictView::OnUpdatePictAutosize2(CCmdUI* pCmdUI) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	pCmdUI->Enable(pDoc->m_nViewMode == VIEWMODE_PICT && pItem != NULL && pItem->m_hDIB1 != NULL);
	pCmdUI->SetCheck(m_nAutoSize == AUTOSIZE_WIDTH);
}

void CMMPictView::OnSelchangeCombo() 
{
	CComboBox &comboBox = ((CMainFrame *)AfxGetMainWnd())->GetComboBox();

	int n = comboBox.GetCurSel();
	if (n != LB_ERR) {
		CString str;

		comboBox.GetLBText(n, str);
		comboBox.SetWindowText(str);
		m_nAutoSize = AUTOSIZE_NONE;
		comboBox.GetWindowText(str);
		m_fPictRatio = atof(str) / 100;
		Invalidate(TRUE);
	}
}

void CMMPictView::OnEditchangeCombo() 
{
	CComboBox &comboBox = ((CMainFrame *)AfxGetMainWnd())->GetComboBox();
	CString str1, str2;

	comboBox.GetWindowText(str1);
	str2 = str1.SpanIncluding("0123456789%");
	if (str1.GetLength() != str2.GetLength()) {
		comboBox.SetWindowText(str2);
		comboBox.SetEditSel(str2.GetLength(), str2.GetLength());
	}
}

void CMMPictView::OnPrint(CDC* pDC, CPrintInfo* pInfo) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem;

	switch (pDoc->m_nViewMode) {
	case VIEWMODE_PICT:
		pItem = pDoc->GetCurrentItem();
		if (pItem != NULL && pItem->m_hDIB1 != NULL)
			PrintPicture(pDC, pItem->m_hDIB1);
		break;
	case VIEWMODE_ICON:
		pItem = &pDoc->m_pItem[pInfo->m_nCurPage - 1];
		if (pItem->m_hDIB1 != NULL)
			PrintPicture(pDC, pItem->m_hDIB1);
		break;
	}
}

void CMMPictView::OnLButtonDown(UINT nFlags, CPoint point) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CMMViewApp *pApp = (CMMViewApp *)AfxGetApp();

	switch (pDoc->m_nViewMode) {
	case VIEWMODE_ICON:
		break;
	case VIEWMODE_PICT:
		m_bScrollPicture = TRUE;
		CPoint scrollPos = GetScrollPosition();
		m_posPicture = (CPoint)(scrollPos + point);
		::SetCursor(pApp->m_hCursorHandClose);
		break;
	}

	SetCapture();
}

void CMMPictView::OnLButtonUp(UINT nFlags, CPoint point) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CMMViewApp *pApp = (CMMViewApp *)AfxGetApp();
	int nPage;

	if (GetCapture() != this)
		return;

	ReleaseCapture();

	switch (pDoc->m_nViewMode) {
	case VIEWMODE_ICON:
		if ((nPage = GetItemByPoint(point + (CSize)GetDeviceScrollPosition())) != -1)
			pDoc->ShowPicture(nPage);
		break;
	case VIEWMODE_PICT:
		m_bScrollPicture = FALSE;
		::SetCursor(pApp->m_hCursorHandOpen);
		break;
	}
}

void CMMPictView::OnMouseMove(UINT nFlags, CPoint point) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CMMViewApp *pApp = (CMMViewApp *)AfxGetApp();

	switch (pDoc->m_nViewMode) {
	case VIEWMODE_ICON:
		break;
	case VIEWMODE_PICT:
		if (m_bScrollPicture) {
			CPoint scrollPos = m_posPicture - point;

			CRect rectView;
			GetClientRect(rectView);
			if (rectView.right >= m_sizeDraw.cx)
				scrollPos.x = 0;
			if (rectView.bottom >= m_sizeDraw.cy)
				scrollPos.y = 0;
			ScrollToPosition(scrollPos);
			::SetCursor(pApp->m_hCursorHandClose);
		} else
			::SetCursor(pApp->m_hCursorHandOpen);
		break;
	}
}

int CMMPictView::GetItemByPoint(CPoint point)
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	int i;

	for (i = 0; i < pDoc->m_nMaxPage; i++) {
		if (pDoc->m_pItem[i].m_IconRect.PtInRect(point))
			return i;
	}

	return -1;
}

BOOL CMMPictView::OnSetCursor(CWnd* pWnd, UINT nHitTest, UINT message) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();

	if (pDoc->m_nViewMode == VIEWMODE_PICT && nHitTest == HTCLIENT)
		return TRUE;

	return CScrollView::OnSetCursor(pWnd, nHitTest, message);
}

void CMMPictView::OnUpdateCombo(CCmdUI* pCmdUI) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();

	if (pDoc->m_nViewMode == VIEWMODE_PICT)
		pCmdUI->Enable(TRUE);
	else {
		pCmdUI->Enable(FALSE);
		CComboBox &comboBox = ((CMainFrame *)AfxGetMainWnd())->GetComboBox();
		comboBox.SetEditSel(-1, -1);
	}
}

void CMMPictView::OnEditCopy() 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	if (pItem != NULL && pItem->m_hDIB1 != NULL) {
		if (AfxGetApp()->m_pMainWnd->OpenClipboard()) {
			BeginWaitCursor();
			::EmptyClipboard();
			::SetClipboardData(CF_DIB, CopyHandle((HANDLE)pItem->m_hDIB1));
			::CloseClipboard();
			EndWaitCursor();
		}
	}
}

void CMMPictView::OnUpdateEditCopy(CCmdUI* pCmdUI) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CItem *pItem = pDoc->GetCurrentItem();

	pCmdUI->Enable(pDoc->m_nViewMode == VIEWMODE_PICT && pItem != NULL && pItem->m_hDIB1 != NULL);
}

void CMMPictView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
{
	switch (lHint) {
	case VIEWHINT_SETFONT:
		DrawItems();
		break;
	default:
		DrawItems();
		break;
	}
}

void CMMPictView::DrawItems()
{
	POINT point;

	if (m_nMapMode > 0) {
		point.x = 0;
		point.y = 0;
		ScrollToPosition(point);
	}

	Invalidate(TRUE);
}

BOOL CMMPictView::OnCmdMsg(UINT nID, int nCode, void* pExtra, AFX_CMDHANDLERINFO* pHandlerInfo) 
{
	if (nID == 1 && nCode == 0) {
		CString str;
		m_nAutoSize = AUTOSIZE_NONE;
		((CMainFrame *)GetParent())->GetComboBox().GetWindowText(str);
		m_fPictRatio = atof(str) / 100;
		Invalidate(TRUE);
	}
	
	return CScrollView::OnCmdMsg(nID, nCode, pExtra, pHandlerInfo);
}
