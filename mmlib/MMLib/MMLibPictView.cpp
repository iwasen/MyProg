// MMLibPictView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "MMLibPictView.h"
#include "MMLibDoc.h"
#include "MainFrm.h"
#include "PictDoc.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define DISPMODE_LIST	0
#define DISPMODE_PICT	1

/////////////////////////////////////////////////////////////////////////////
// CMMLibPictView

IMPLEMENT_DYNCREATE(CMMLibPictView, CScrollView)

CMMLibPictView::CMMLibPictView()
{
	m_hDIB = NULL;
	m_bScrollPicture = FALSE;
	m_pSelectItem = NULL;
	m_pCurrentItem = NULL;
	m_fPictRatio = 1.0;
	m_hBrushBkgnd.CreateSolidBrush(::GetSysColor(COLOR_3DFACE));
	m_hBrushSelect.CreateSolidBrush(RGB(0, 0, 255));
	m_bIgnoreUpdate = FALSE;
	m_bModify = FALSE;
	SetViewFont();
}

CMMLibPictView::~CMMLibPictView()
{
	if (m_hDIB != NULL)
		MMPictureFree(m_hDIB);
}


BEGIN_MESSAGE_MAP(CMMLibPictView, CScrollView)
	ON_WM_ERASEBKGND()
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	ON_WM_SETCURSOR()
	ON_WM_LBUTTONDBLCLK()
	ON_WM_RBUTTONUP()
	ON_COMMAND(ID_EDIT_COPY, OnEditCopy)
	ON_UPDATE_COMMAND_UI(ID_EDIT_COPY, OnUpdateEditCopy)
	ON_WM_CREATE()
	ON_WM_RBUTTONDOWN()
	ON_MESSAGE(WM_GETSELECTEDITEM, OnGetSelectedItem)
	ON_MESSAGE(WM_UPDATEPICT, OnUpdatePict)
	ON_COMMAND(ID_FILE_PRINT, CScrollView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_DIRECT, CScrollView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_PREVIEW, CScrollView::OnFilePrintPreview)
	ON_UPDATE_COMMAND_UI(ID_FILE_PRINT, &CMMLibPictView::OnUpdateFilePrint)
	ON_UPDATE_COMMAND_UI(ID_FILE_PRINT_PREVIEW, &CMMLibPictView::OnUpdateFilePrintPreview)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMLibPictView 描画

void CMMLibPictView::OnInitialUpdate()
{
	CScrollView::OnInitialUpdate();

	CSize sizeTotal;
	// TODO: このビュー全体のサイズを計算してください。
	sizeTotal.cx = sizeTotal.cy = 100;
	SetScrollSizes(MM_TEXT, sizeTotal);

	m_nTextMode = AfxGetApp()->GetProfileInt(g_SectionOptions, "ViewText", 0);
	m_nSmallSize = AfxGetApp()->GetProfileInt(g_SectionOptions, "SmallSize", 0);
}

void CMMLibPictView::OnDraw(CDC* pDC)
{
	switch (m_nDispMode) {
	case DISPMODE_LIST:
		DrawList(pDC, m_pCurrentItem);
		break;
	case DISPMODE_PICT:
		DrawPicture(pDC, (CItemData *)m_pCurrentItem);
		break;
	}
}

/////////////////////////////////////////////////////////////////////////////
// CMMLibPictView 診断

#ifdef _DEBUG
void CMMLibPictView::AssertValid() const
{
	CScrollView::AssertValid();
}

void CMMLibPictView::Dump(CDumpContext& dc) const
{
	CScrollView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMLibPictView メッセージ ハンドラ

int CMMLibPictView::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	if (CScrollView::OnCreate(lpCreateStruct) == -1)
		return -1;
	
	m_DropTarget.Register(this);
	
	return 0;
}

void CMMLibPictView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
{
	switch (lHint) {
	case VIEWHINT_ADDITEM:
	case VIEWHINT_SELECTITEM:
	case VIEWHINT_OPENITEM:
	case VIEWHINT_CLOSEITEM:
	case VIEWHINT_REMOVEITEM:
	case VIEWHINT_UPDATEITEM:
	case VIEWHINT_RELOADITEM:
	case VIEWHINT_REFINDITEM:
	case VIEWHINT_DRAWALL:
	case VIEWHINT_DRAWPICT:
		DrawItems();
		Invalidate();
		break;
	case VIEWHINT_ENLARGE:
		m_fPictRatio *= 1.5;
		Invalidate();
		break;
	case VIEWHINT_REDUCE:
		m_fPictRatio /= 1.5;
		Invalidate();
		break;
	case VIEWHINT_RROTATION:
		MMPictureRotate(m_hDIB, 1);
		ReadPictureInfo();
		Invalidate();
		break;
	case VIEWHINT_LROTATION:
		MMPictureRotate(m_hDIB, 3);
		ReadPictureInfo();
		Invalidate();
		break;
	case VIEWHINT_SETPICTRATIO:
		{
			CString str;
			((CMainFrame *)AfxGetMainWnd())->GetComboBox().GetWindowText(str);
			m_fPictRatio = atof(str) / 100;
		}
		Invalidate();
		break;
	case VIEWHINT_SETFONT:
		SetViewFont();
		Invalidate();
		break;
	case VIEWHINT_SETTEXT:
		m_nTextMode = AfxGetApp()->GetProfileInt(g_SectionOptions, "ViewText", 0);
		Invalidate();
		break;
	case VIEWHINT_UPDATEWINDOW:
		UpdateWindow();
		Invalidate();
		break;
	case VIEWHINT_SETSMALLSIZE:
		m_nSmallSize = AfxGetApp()->GetProfileInt(g_SectionOptions, "SmallSize", 0);
		Invalidate();
		break;
	case VIEWHINT_PICTFRAME:
		OpenPictFrame();
		break;
	case VIEWHINT_SAVEDATA:
		SaveData((CItem *)pHint);
		break;
	case VIEWHINT_EDITPICT:
		EditPicture();
		break;
	}
}

void CMMLibPictView::DrawItems()
{
	CWaitCursor waitCursor;
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	CMainFrame *pMainFrame = (CMainFrame *)AfxGetApp()->m_pMainWnd;
	HTREEITEM hItem;
	m_pSelectItem = NULL;

	hItem = g_pTreeCtrl->GetSelectedItem();
	if (hItem != NULL) {
		m_pCurrentItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);

		switch (m_pCurrentItem->m_nItemType) {
		case ITEM_ROOT:
			return;
		case ITEM_FOLDER:
		case ITEM_INBOX:
			if (pDoc->m_nViewMode != VIEWMODE_PICT || ((CItemFolder *)m_pCurrentItem)->m_nFolderType != FTYPE_DFOLDER)
				return;
			pMainFrame->ShowPictView(TRUE);
			m_nDispMode = DISPMODE_LIST;
			pDoc->m_bPictView = FALSE;
			break;
		case ITEM_DATA:
			pMainFrame->ShowPictView(TRUE);
			ReadPicture((CItemData *)m_pCurrentItem);
			m_nDispMode = DISPMODE_PICT;
			pDoc->m_bPictView = TRUE;
			break;
		case ITEM_FIND:
			if (pDoc->m_nViewMode != VIEWMODE_PICT || ((CItemFind *)m_pCurrentItem)->m_nFindType == FIND_TYPE_FOLDER)
				return;
			pMainFrame->ShowPictView(TRUE);
			m_nDispMode = DISPMODE_LIST;
			pDoc->m_bPictView = FALSE;
			break;
		}
	}
}

BOOL CMMLibPictView::ReadPicture(CItemData *pItem)
{
	if (m_hDIB != NULL) {
		MMPictureFree(m_hDIB);
		m_hDIB = NULL;
	}

	pItem->m_pPcsDB->ReadPictureDataEx(pItem->m_DataRec.dataID, &m_hDIB, NULL);

	if (m_hDIB == NULL)
		return FALSE;

	ReadPictureInfo();

	m_bModify = FALSE;

	return TRUE;
}

void CMMLibPictView::ReadPictureInfo()
{
	short width, height, color;

	MMPictureGetInfo(m_hDIB, &width, &height, &color);
	m_sizePict.cx = width;
	m_sizePict.cy = height;
}

void CMMLibPictView::DrawList(CDC *pDC, CItem *pItem)
{
	CRect rcDIB, rcDest;
	int	count = 0;
	int	x, y, mod, row;
	short width, height,color;
	HDIB hDIB;
	HTREEITEM hItem;
	CItem *pItemChild;
	CItemData *pItemData;
	CRect rcVisible;
	CRect rcText;
	CSize sizeScroll(0, 0);
	CString text;
	int nPictWidth, nPictHeight;

	pDC->SetBkMode(TRANSPARENT);

	CRect rect;
	GetClientRect(&rect);

	if (m_nSmallSize == SMALL_NORMAL) {
		nPictWidth = PICT_WIDTH;
		nPictHeight = PICT_HEIGHT;
	} else {
		nPictWidth = PICT_WIDTH * 2;
		nPictHeight = PICT_HEIGHT * 2;
	}

	int nColumn = max((rect.right - PICT_MARGIN) / (nPictWidth + PICT_MARGIN), 1);

	CFont *pFontOrg = pDC->SelectObject(&m_Font);

	for (hItem = g_pTreeCtrl->GetChildItem(pItem->m_hItem) ; hItem != NULL; hItem = g_pTreeCtrl->GetNextSiblingItem(hItem)) {
		pItemChild = (CItem *)g_pTreeCtrl->GetItemData(hItem);

		if (pItemChild->m_nItemType != ITEM_DATA)
			continue;

		pItemData = (CItemData *)pItemChild;

		mod = count % nColumn;
		x = (nPictWidth + PICT_MARGIN) * mod + PICT_MARGIN;
		rcDest.left = x;
		rcDest.right = x + nPictWidth;

		row = count / nColumn;
		y = (nPictHeight + PICT_TEXT + PICT_MARGIN) * row + PICT_MARGIN;
		rcDest.top = y;
		rcDest.bottom = y + nPictHeight;

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

		if (pItemData->m_hDIB == NULL) {
			if (m_nSmallSize == SMALL_HIRES) {
				HDIB hDIB2;
				if (!pItemData->m_pPcsDB->ReadPictureDataEx(pItemData->m_DataRec.dataID, &hDIB2, NULL))
					continue;
				hDIB = MakeSmallPicture(hDIB2, nPictWidth, nPictHeight);
				MMPictureFree(hDIB2);
			} else {
				if (!pItemData->m_pPcsDB->ReadPictureDataEx(pItemData->m_DataRec.dataID, NULL, &hDIB))
					continue;
			}
			pItemData->m_hDIB = hDIB;
			MMPictureGetInfo(hDIB, &width, &height, &color);
			pItemData->m_Size.cx = width;
			pItemData->m_Size.cy = height;
		} else {
			hDIB = pItemData->m_hDIB;
			width = (short)pItemData->m_Size.cx;
			height = (short)pItemData->m_Size.cy;
		}

		if (m_nSmallSize == SMALL_HISPD) {
			width *= 2;
			height *= 2;
		}

		MMPictureDraw(hDIB, pDC->m_hDC, (short)(rcDest.left + (nPictWidth - width) / 2),
				(short)(rcDest.top + (nPictHeight - height) / 2), width, height, 0, 0, -1, -1);

		pItemData->m_Rect = rcDest;

		rcDest.left -= PICT_FRAME;
		rcDest.top -= PICT_FRAME;
		rcDest.right += PICT_FRAME;
		rcDest.bottom += PICT_FRAME;
		pDC->DrawEdge(rcDest, EDGE_RAISED, BF_RECT);

		rcText.left = x;
		rcText.top = y + nPictHeight + PICT_FRAME + 1;
		rcText.right = rcText.left + nPictWidth;
		rcText.bottom = rcText.top + PICT_TEXT;
		if (m_nTextMode == TEXTMODE_TITLE_ONLY || pItemData->m_DataRec.code.IsEmpty())
			pDC->DrawText(pItemData->m_DataRec.title, rcText, DT_WORDBREAK);
		else {
			text.Format("%s (%s)", (LPCTSTR)pItemData->m_DataRec.title, (LPCTSTR)pItemData->m_DataRec.code);
			pDC->DrawText(text, rcText, DT_WORDBREAK);
		}
	}

	SetScrollSizes(MM_TEXT, sizeScroll);

	DrawSelect(pDC);

	pDC->SelectObject(pFontOrg);

	m_sizeDraw.cx = m_sizeDraw.cy = 0;
}

void CMMLibPictView::DrawPicture(CDC *pDC, CItemData *pItem)
{
	if (m_hDIB == NULL)
		return;

	int widthSrc, heightSrc;
	int widthDst, heightDst;
	int xDst, yDst;
	CRect rcView;
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();

	if (m_bIgnoreUpdate) {
		m_bIgnoreUpdate = FALSE;
		return;
	}

	GetClientRect(rcView);

	widthSrc = m_sizePict.cx;
	heightSrc = m_sizePict.cy;

	switch (pDoc->m_nAutoSize) {
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

	MMPictureDraw(m_hDIB, pDC->m_hDC, xDst, yDst, widthDst, heightDst, 0, 0, widthSrc, heightSrc);
}

BOOL CMMLibPictView::OnEraseBkgnd(CDC* pDC) 
{
	if (m_bIgnoreUpdate)
		return TRUE;

	CRect rect;
	pDC->GetClipBox(rect);
	pDC->FillRect(rect, &m_hBrushBkgnd);
	return TRUE;
}

void CMMLibPictView::OnLButtonDown(UINT nFlags, CPoint point) 
{
	switch (m_nDispMode) {
	case DISPMODE_LIST:
		break;
	case DISPMODE_PICT:
		m_bScrollPicture = TRUE;
		CPoint scrollPos = GetScrollPosition();
		m_posPicture = (CPoint)(scrollPos + point);
		::SetCursor(theApp.m_hCursorHandClose);
		break;
	}

	SetCapture();
}

void CMMLibPictView::OnLButtonUp(UINT nFlags, CPoint point) 
{
	CItemData *pItem;
	CPoint pointOrg;

	if (GetCapture() != this)
		return;

	ReleaseCapture();

	switch (m_nDispMode) {
	case DISPMODE_LIST:
		pointOrg = GetDeviceScrollPosition();
		if ((pItem = GetItemByPoint(point + (CSize)pointOrg)) != NULL) {
			if (pItem != m_pSelectItem) {
				CDC *pDC = GetDC();
				pDC->SetViewportOrg(-pointOrg);
				DrawSelect(pDC, FALSE);
				m_pSelectItem = pItem;
				DrawSelect(pDC, TRUE);
				ReleaseDC(pDC);
			}
		}
		break;
	case DISPMODE_PICT:
		m_bScrollPicture = FALSE;
		::SetCursor(theApp.m_hCursorHandOpen);
		break;
	}
}

void CMMLibPictView::OnLButtonDblClk(UINT nFlags, CPoint point) 
{
	switch (m_nDispMode) {
	case DISPMODE_LIST:
		if (m_pSelectItem != NULL)
			GetDocument()->UpdateAllViews(NULL, VIEWHINT_SELECTITEM, m_pSelectItem);
		break;
	case DISPMODE_PICT:
		if (AfxGetApp()->GetProfileInt(g_SectionOptions, "EditDblClick", 1))
			EditPicture();
		break;
	default:
		CScrollView::OnLButtonDblClk(nFlags, point);
		break;
	}
}

void CMMLibPictView::OnMouseMove(UINT nFlags, CPoint point) 
{
	switch (m_nDispMode) {
	case DISPMODE_LIST:
		break;
	case DISPMODE_PICT:
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
		break;
	}
}

BOOL CMMLibPictView::OnSetCursor(CWnd* pWnd, UINT nHitTest, UINT message) 
{
	if (m_nDispMode == DISPMODE_PICT && nHitTest == HTCLIENT)
		return TRUE;

	return CScrollView::OnSetCursor(pWnd, nHitTest, message);
}

CItemData *CMMLibPictView::GetItemByPoint(CPoint point)
{
	CItem *pItem;
	HTREEITEM hItem;

	if (m_pCurrentItem == NULL)
		return NULL;

	hItem = g_pTreeCtrl->GetChildItem(m_pCurrentItem->m_hItem);
	for ( ; hItem != NULL; hItem = g_pTreeCtrl->GetNextSiblingItem(hItem)) {
		pItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);

		if (pItem->m_nItemType != ITEM_DATA)
			continue;

		if (((CItemData *)pItem)->m_Rect.PtInRect(point))
			return (CItemData *)pItem;
	}

	return NULL;
}

void CMMLibPictView::DrawSelect(CDC* pDC, BOOL bSelect)
{
	CRect rect;

	if (m_pSelectItem != NULL) {
		rect = m_pSelectItem->m_Rect;
		rect.InflateRect(3, 3);
		pDC->FrameRect(&rect, bSelect ? &m_hBrushSelect : &m_hBrushBkgnd);
	}
}

LRESULT CMMLibPictView::OnGetSelectedItem(WPARAM wParam, LPARAM lParam)
{
	CItem *pItem = NULL;

	switch (m_nDispMode) {
	case DISPMODE_LIST:
		pItem = m_pSelectItem;
		if (pItem == NULL)
			pItem = m_pCurrentItem;
		break;
	case DISPMODE_PICT:
		pItem = m_pCurrentItem;
		break;
	}
	if (pItem != NULL)
		pItem->m_pNextSelected = NULL;

	return (LRESULT)pItem;
}

void CMMLibPictView::OnRButtonUp(UINT nFlags, CPoint point) 
{
	POINT posScreen;
	::GetCursorPos(&posScreen);

	CMenu menu;
	menu.LoadMenu(IDR_DATA);
	CMenu* pPopup = menu.GetSubMenu(0);
	pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, posScreen.x, posScreen.y, AfxGetMainWnd());
	
	CScrollView::OnRButtonUp(nFlags, point);
}

void CMMLibPictView::OnEditCopy() 
{
	switch (m_nDispMode) {
	case DISPMODE_LIST:
		if (m_pSelectItem != NULL)
			ReadPicture(m_pSelectItem);
		break;
	}

	if (m_hDIB != NULL) {
		if (AfxGetApp()->m_pMainWnd->OpenClipboard()) {
			BeginWaitCursor();
			::EmptyClipboard();
			::SetClipboardData(CF_DIB, CopyHandle((HANDLE)m_hDIB));
			::CloseClipboard();
			EndWaitCursor();
		}
	}
}

void CMMLibPictView::OnUpdateEditCopy(CCmdUI* pCmdUI) 
{
	BOOL bEnable = FALSE;

	switch (m_nDispMode) {
	case DISPMODE_LIST:
		if (m_pSelectItem != NULL)
			bEnable = TRUE;
		break;
	case DISPMODE_PICT:
		if (m_hDIB != NULL)
			bEnable = TRUE;
		break;
	}

	pCmdUI->Enable(bEnable);
}

BOOL CMMLibPictView::OnPreparePrinting(CPrintInfo* pInfo) 
{
	if (m_hDIB == NULL)
		return FALSE;

	pInfo->SetMaxPage(1);
	return DoPreparePrinting(pInfo);
}

void CMMLibPictView::OnPrint(CDC* pDC, CPrintInfo* pInfo) 
{
	switch (m_nDispMode) {
	case DISPMODE_LIST:
		if (m_pSelectItem != NULL)
			ReadPicture(m_pSelectItem);
		break;
	}

	PrintPicture(pDC, m_hDIB);
}

void CMMLibPictView::SetViewFont()
{
	m_Font.DeleteObject();
	m_Font.CreatePointFont(g_nViewFontSize, g_ViewFontName);
}

void CMMLibPictView::OpenPictFrame()
{
	if (m_nDispMode != DISPMODE_PICT)
		return;

	if (m_hDIB == NULL || m_pCurrentItem == NULL)
		return;

	SIZE_T nSize = ::GlobalSize(m_hDIB);
	HDIB hDIB = ::GlobalAlloc(GMEM_MOVEABLE, nSize);
	memcpy(::GlobalLock(hDIB), ::GlobalLock(m_hDIB), nSize);
	::GlobalUnlock(hDIB);
	::GlobalUnlock(m_hDIB);

	CMMLibApp *pWinApp = (CMMLibApp *)AfxGetApp();
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	CDocTemplate *pTemplate = pWinApp->m_pPictDocTemplate;
	CRuntimeClass* pDocClass = RUNTIME_CLASS(CPictDoc);
	CPictDoc *pPictDoc = (CPictDoc *)(pDocClass->CreateObject());
	CFrameWnd *pFrame = pTemplate->CreateNewFrame(pPictDoc, NULL);
    pTemplate->InitialUpdateFrame(pFrame, pPictDoc);
	pPictDoc->ShowPicture(((CItemData *)m_pCurrentItem)->m_DataRec.title, hDIB, pDoc->m_nAutoSize, m_fPictRatio);
}

DROPEFFECT CMMLibPictView::OnDragOver(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point) 
{
	if (m_nDispMode == DISPMODE_PICT && pDataObject->IsDataAvailable(CF_HDROP) && !pDataObject->IsDataAvailable(g_nCFMMLibrary))
		return DROPEFFECT_COPY;
	else
		return DROPEFFECT_NONE;
}

BOOL CMMLibPictView::OnDrop(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point) 
{
	int nNumFiles;
	char fileNameBuf[256];
	HDROP hDrop;
	HDIB hDIB;

	if ((hDrop = (HDROP)pDataObject->GetGlobalData(CF_HDROP, NULL)) == NULL)
		return FALSE;

	nNumFiles = ::DragQueryFile(hDrop, -1, NULL, 0);
	if (nNumFiles > 0) {
		::DragQueryFile(hDrop, 0, fileNameBuf, sizeof(fileNameBuf));
		if (MMPictureReadFile(&hDIB, fileNameBuf) == 0)
			ChangePicture(hDIB);
	}

	::GlobalFree(hDrop);

	return TRUE;
}

void CMMLibPictView::ChangePicture(HDIB hDIB, BOOL bUpdateView)
{
	if (m_hDIB != NULL)
		MMPictureFree(m_hDIB);

	m_hDIB = hDIB;
	ReadPictureInfo();
	Invalidate();

	if (bUpdateView)
		GetDocument()->UpdateAllViews(NULL, VIEWHINT_MODIFYDATA);

	m_bModify = TRUE;
}

void CMMLibPictView::SaveData(CItem *pItem)
{
	if (pItem != NULL && m_bModify && m_nDispMode == DISPMODE_PICT) {
		if (pItem->m_nItemType == ITEM_DATA) {
			CItemData *pItemData = (CItemData *)pItem;
			WritePicture(pItemData->m_pPcsDB, pItemData->m_DataRec.dataID, m_hDIB);
			m_bModify = FALSE;
		}
	}
}

void CMMLibPictView::EditPicture()
{
	if (m_pCurrentItem == NULL)
		return;

	if (g_sImageEditor.IsEmpty())
		return;

	::AfxBeginThread(EditPictureThread, this, THREAD_PRIORITY_NORMAL);
}

UINT CMMLibPictView::EditPictureThread(LPVOID pParam)
{
	((CMMLibPictView *)pParam)->EditPictureSub();
	return 0;
}

void CMMLibPictView::EditPictureSub() 
{
	CString sTempFile;
	CString execCmd;

	m_pCurrentItem->m_pNextSelected = NULL;
	if (!MakeTempFile(m_pCurrentItem, sTempFile, PICTFILE_ALL, FALSE, FALSE))
		return;

	if (sTempFile.IsEmpty()) {
		AfxMessageBox(IDS_NO_IMAGE_DATA);
		return;
	}

	sTempFile.Replace(';', ' ');
	sTempFile.TrimRight();
	execCmd.Format("\"%s\" %s", (LPCTSTR)g_sImageEditor, (LPCTSTR)sTempFile);

	CWnd *pMainWnd = ::AfxGetMainWnd();
	pMainWnd->EnableWindow(FALSE);

	ExecProcess(execCmd);

	DWORD attr = ::GetFileAttributes(sTempFile);
	if (attr & FILE_ATTRIBUTE_ARCHIVE) {
		HDIB hDIB;
		if (MMPictureReadFile(&hDIB, (LPTSTR)(LPCTSTR)sTempFile) == 0)
			SendMessage(WM_UPDATEPICT, 0, (LPARAM)hDIB);
	}

	DeleteTempFile(sTempFile);

	pMainWnd->EnableWindow(TRUE);
	pMainWnd->SetForegroundWindow();
}

LRESULT CMMLibPictView::OnUpdatePict(WPARAM wParam, LPARAM lParam)
{
	ChangePicture((HDIB)lParam, FALSE);
	SaveData(m_pCurrentItem);
	return 0;
}

void CMMLibPictView::OnRButtonDown(UINT nFlags, CPoint point) 
{
	// TODO: この位置にメッセージ ハンドラ用のコードを追加するかまたはデフォルトの処理を呼び出してください
	
	CScrollView::OnRButtonDown(nFlags, point);
}

void CMMLibPictView::OnUpdateFilePrint(CCmdUI *pCmdUI)
{
	pCmdUI->Enable(m_hDIB != NULL);
}

void CMMLibPictView::OnUpdateFilePrintPreview(CCmdUI *pCmdUI)
{
	pCmdUI->Enable(m_hDIB != NULL);
}
