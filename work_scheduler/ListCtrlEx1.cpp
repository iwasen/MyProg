// LixxxtrlEx1.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "LixxxtrlEx1.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CLixxxtrlEx1

CLixxxtrlEx1::CLixxxtrlEx1()
{
	m_pDragImage = NULL;
	m_bDragging = FALSE;
	m_bEnableDrag = FALSE;
}

CLixxxtrlEx1::~CLixxxtrlEx1()
{
}


BEGIN_MESSAGE_MAP(CLixxxtrlEx1, CLixxxtrl)
	//{{AFX_MSG_MAP(CLixxxtrlEx1)
	ON_NOTIFY_REFLECT(LVN_BEGINDRAG, OnBegindrag)
	ON_WM_MOUSEMOVE()
	ON_WM_LBUTTONUP()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLixxxtrlEx1 メッセージ ハンドラ

void CLixxxtrlEx1::OnBegindrag(NMHDR* pNMHDR, LRESULT* pResult)
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if (m_bEnableDrag) {
		m_nDragIndex = pNMListView->iItem;
		CPoint pt(8, 8);

		m_pDragImage = CreateDragImage(m_nDragIndex, &pt);

		m_pDragImage->BeginDrag(0, CPoint(8, 8));
		m_pDragImage->DragEnter(GetParent(), pNMListView->ptAction);

		m_bDragging = TRUE;
		m_nDropIndex = -1;
		m_pDropWnd = this;

		SetCapture();
	}

	*pResult = 0;
}

void CLixxxtrlEx1::OnMouseMove(UINT nFlags, CPoint point)
{
	if (m_bDragging) {
		CPoint pointScreen(point);
		ClientToScreen(&pointScreen);

		CPoint pointParent(pointScreen);
		CRect rect;
		GetParent()->GetWindowRect(rect);
		pointParent -= rect.TopLeft();

		m_pDragImage->DragMove(pointParent);
		m_pDragImage->DragShowNolock(FALSE);

		CWnd* pDropWnd = WindowFromPoint(pointScreen);
/*
		if (pDropWnd != m_pDropWnd) {
			if (m_nDropIndex != -1) {
				CLixxxtrl* pList = (CLixxxtrl*)m_pDropWnd;
				pList->SetItemState(m_nDropIndex, 0, LVIS_DROPHILITED);
				pList->RedrawItems(m_nDropIndex, m_nDropIndex);
				pList->UpdateWindow();
				m_nDropIndex = -1;
			}
		}
*/
		m_pDropWnd = pDropWnd;

		CPoint pointDropWnd(pointScreen);
		pDropWnd->ScreenToClient(&pointDropWnd);

		if (pDropWnd->IsKindOf(RUNTIME_CLASS(CLixxxtrl))) {
			UINT uFlags;
			CLixxxtrl* pList = (CLixxxtrl*)pDropWnd;

			if (m_nDropIndex != -1) {
				pList->SetItemState(m_nDropIndex, 0, LVIS_DROPHILITED);
				pList->RedrawItems(m_nDropIndex, m_nDropIndex);
				pList->UpdateWindow();
			}

			m_nDropIndex = ((CLixxxtrl*)pDropWnd)->HitTest(pointDropWnd, &uFlags);
			if (m_nDropIndex != -1) {
				pList->SetItemState(m_nDropIndex, LVIS_DROPHILITED, LVIS_DROPHILITED);
				pList->RedrawItems(m_nDropIndex, m_nDropIndex);
				pList->UpdateWindow();
			}
		}

		m_pDragImage->DragShowNolock(TRUE);
	}

	CLixxxtrl::OnMouseMove(nFlags, point);
}

void CLixxxtrlEx1::OnLButtonUp(UINT nFlags, CPoint point)
{
	if (m_bDragging) {
		::ReleaseCapture();
		m_bDragging = FALSE;

		m_pDragImage->DragLeave(GetParent());
		m_pDragImage->EndDrag();

		CPoint pt(point);
		ClientToScreen(&pt);

		CWnd* pDropWnd = WindowFromPoint(pt);
		if (pDropWnd->IsKindOf(RUNTIME_CLASS(CLixxxtrl)))
			DropItemOnList((CLixxxtrl*)pDropWnd);

		if(m_pDragImage) {
			delete m_pDragImage;
			m_pDragImage = NULL;
		}
	}

	CLixxxtrl::OnLButtonUp(nFlags, point);
}

void CLixxxtrlEx1::DropItemOnList(CLixxxtrl* pDropList)
{
	pDropList->SetItemState(m_nDropIndex, 0, LVIS_DROPHILITED);

	CUIntArray aSelected;
	int i, j;
	for (i = 0; i < GetItemCount(); i++) {
		if (GetItemState(i, LVIS_SELECTED))
			aSelected.Add(i);
	}

	for (i = 0; i < pDropList->GetItemCount(); i++)
		pDropList->SetItemState(i, 0, LVIS_SELECTED);

	CString sText;
	int nColumn = GetHeaderCtrl()->GetItemCount();

	BOOL bSame = m_hWnd == pDropList->m_hWnd;
	int nDelete = 0;
	int nDropIndex = (m_nDropIndex == -1) ? pDropList->GetItemCount() : m_nDropIndex;
	for (i = 0; i < aSelected.GetSize(); i++) {
		int nDragIndex = aSelected[i] - nDelete;

		for (j = 0; j < nColumn; j++) {
			sText = GetItemText(nDragIndex, j);

			if (j == 0) {
				pDropList->InsertItem(nDropIndex, sText);

				if (bSame && nDragIndex >= nDropIndex)
					nDragIndex++;
				else
					nDelete++;
			} else
				pDropList->SetItemText(nDropIndex, j, sText);
		}
		pDropList->SetItemState(nDropIndex, LVIS_SELECTED, LVIS_SELECTED);
		pDropList->SetItemData(nDropIndex, GetItemData(nDragIndex));

		if (!bSame || nDragIndex >= nDropIndex)
			nDropIndex++;

		DeleteItem(nDragIndex);
	}

	GetParent()->SendMessage(WM_DRAG_DROP, (WPARAM)m_hWnd, (LPARAM)pDropList->m_hWnd);
}

void CLixxxtrlEx1::SetColumnText(int nColumn, LPCTSTR pText)
{
	LVCOLUMN lvc;

	memset(&lvc, 0, sizeof(lvc));
	lvc.mask = LVCF_TEXT;
	lvc.pszText = (LPTSTR)pText;
	SetColumn(nColumn, &lvc);
}

void CLixxxtrlEx1::EnableDrag(BOOL bEnableDrag)
{
	m_bEnableDrag = bEnableDrag;
}
