// MMListView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMView.h"
#include "MMListView.h"
#include "MMViewDoc.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMListView

IMPLEMENT_DYNCREATE(CMMListView, CListView)

CMMListView::CMMListView()
{
	static int defaultFlag[DETAIL_NUM] = {
		1, 1, 1, 0, 1, 1, 0
	};
	static int defaultWidth[DETAIL_NUM] = {
		200, 100, 110, 110, 100, 100, 100
	};
	CMMViewApp *pApp = (CMMViewApp *)AfxGetApp();
	int i;
	CString entry;

	m_nColumn = 0;
	m_nSortItem = -1;
	m_nSortDir = 1;

	for (i = 0; i < DETAIL_NUM; i++) {
		entry.Format("Flag%d", i);
		m_bDetailFlag[i] = pApp->GetProfileInt(g_SectionDetails, entry, defaultFlag[i]);

		entry.Format("Width%d", i);
		m_nDetailWidth[i] = pApp->GetProfileInt(g_SectionDetails, entry, defaultWidth[i]);
	}
}

CMMListView::~CMMListView()
{
	CMMViewApp *pApp = (CMMViewApp *)AfxGetApp();
	int i;
	CString entry;

	for (i = 0; i < DETAIL_NUM; i++) {
		entry.Format("Flag%d", i);
		pApp->WriteProfileInt(g_SectionDetails, entry, m_bDetailFlag[i]);

		entry.Format("Width%d", i);
		pApp->WriteProfileInt(g_SectionDetails, entry, m_nDetailWidth[i]);
	}
}


BEGIN_MESSAGE_MAP(CMMListView, CListView)
	ON_WM_CREATE()
	ON_NOTIFY_REFLECT(LVN_COLUMNCLICK, OnColumnclick)
	ON_NOTIFY_REFLECT(NM_DBLCLK, OnDblclk)
	ON_COMMAND(IDM_DETAIL_CDATE, OnDetailCdate)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_CDATE, OnUpdateDetailCdate)
	ON_COMMAND(IDM_DETAIL_CODE, OnDetailCode)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_CODE, OnUpdateDetailCode)
	ON_COMMAND(IDM_DETAIL_CREATOR, OnDetailCreator)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_CREATOR, OnUpdateDetailCreator)
	ON_COMMAND(IDM_DETAIL_NAME, OnDetailName)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_NAME, OnUpdateDetailName)
	ON_COMMAND(IDM_DETAIL_REMARK1, OnDetailRemark1)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_REMARK1, OnUpdateDetailRemark1)
	ON_COMMAND(IDM_DETAIL_REMARK2, OnDetailRemark2)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_REMARK2, OnUpdateDetailRemark2)
	ON_COMMAND(IDM_DETAIL_UDATE, OnDetailUdate)
	ON_UPDATE_COMMAND_UI(IDM_DETAIL_UDATE, OnUpdateDetailUdate)
	ON_COMMAND(ID_FILE_PRINT, CListView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_DIRECT, CListView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_PREVIEW, CListView::OnFilePrintPreview)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMListView 描画

void CMMListView::OnDraw(CDC* pDC)
{
	CDocument* pDoc = GetDocument();
	// TODO: この位置に描画用のコードを追加してください
}

/////////////////////////////////////////////////////////////////////////////
// CMMListView 診断

#ifdef _DEBUG
void CMMListView::AssertValid() const
{
	CListView::AssertValid();
}

void CMMListView::Dump(CDumpContext& dc) const
{
	CListView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMListView メッセージ ハンドラ

int CMMListView::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	lpCreateStruct->style |= LVS_REPORT | LVS_SHOWSELALWAYS;
	if (CListView::OnCreate(lpCreateStruct) == -1)
		return -1;
	
//	GetLixxxtrl().SetImageList(&g_ImageList, LVSIL_SMALL);
//	GetLixxxtrl().SetImageList(&g_ImageList2, LVSIL_NORMAL);
	
	return 0;
}

void CMMListView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();

	switch (lHint) {
	case VIEWHINT_SETFONT:
		SetFont((CFont *)pHint, TRUE);
		break;
	default:
		if (pDoc->m_nViewMode == VIEWMODE_LIST)
			DrawItems();
		break;
	}
}

void CMMListView::DrawItems()
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	CItem *pItem;
	int i, j;
	int row, column;
	CString str;
	LPCTSTR p;

	InsertColumns();

	row = 0;
	for (i = 0 ; i < pDoc->m_nMaxPage; i++) {
		pItem = &pDoc->m_pItem[i];

		column = 0;
		for (j = 0; j < DETAIL_NUM; j++) {
			if (!m_bDetailFlag[j])
				continue;

			switch (j) {
			case DETAIL_NAME:
				p = pItem->m_Title;
				break;
			case DETAIL_CODE:
				p = pItem->m_Code;
				break;
			case DETAIL_CDATE:
				FormatDate(pItem->m_CreateDate, str);
				p = str;
				break;
			case DETAIL_UDATE:
				FormatDate(pItem->m_UpdateDate, str);
				p = str;
				break;
			case DETAIL_CREATOR:
				p = pItem->m_Creator;
				break;
			case DETAIL_REMARK1:
				p = pItem->m_Remark1;
				break;
			case DETAIL_REMARK2:
				p = pItem->m_Remark2;
				break;
			default:
				p = "";
				break;
			}

			if (column == 0)
				lixxxtrl.InsertItem(row, p);
			else
				lixxxtrl.SetItemText(row, column, p);

			column++;
		}

		lixxxtrl.SetItemData(row, (DWORD)i);

		row++;
	}
}

void CMMListView::InsertColumns()
{
	static UINT header[] = {
		IDS_NAME, IDS_CODE, IDS_CREATE_DATE, IDS_UPDATE_DATE, IDS_CREATOR, IDS_REMARK, IDS_KEYWORD
	};
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	int i;
	LV_COLUMN lvc;
	CLixxxtrl &lixxxtrl = GetLixxxtrl();

	lixxxtrl.DeleteAllItems();

	for (i = 0; i < m_nColumn; i++)
		lixxxtrl.DeleteColumn(0);

	m_nColumn = 0;
	for (i = 0; i < DETAIL_NUM; i++) {
		if (m_bDetailFlag[i]) {
			lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
			lvc.fmt = LVCFMT_LEFT;
			lvc.pszText = (LPTSTR)(LPCTSTR)GetString(header[i]);
			lvc.cx = m_nDetailWidth[i];
			lixxxtrl.InsertColumn(i, &lvc);
			m_nColumn++;
		}
	}
}

void CMMListView::OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if (m_nSortItem != pNMListView->iSubItem)
		m_nSortDir = 1;
	else
		m_nSortDir *= -1;

	m_nSortItem = pNMListView->iSubItem;
	GetLixxxtrl().SortItems(CompareItems, (LPARAM)this);

	*pResult = 0;
}

int CALLBACK CMMListView::CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort)
{
	CMMListView *pView = (CMMListView *)lParamSort;
	CLixxxtrl &lvc = pView->GetLixxxtrl();
	LV_FINDINFO findInfo;
	int index1, index2;

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam1;
	index1 = lvc.FindItem(&findInfo);

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam2;
	index2 = lvc.FindItem(&findInfo);

	if (pView->m_bDetailFlag[0] && pView->m_nSortItem == 0)
		return CompareTitle(lvc.GetItemText(index1, pView->m_nSortItem), lvc.GetItemText(index2, pView->m_nSortItem)) * pView->m_nSortDir;
	else
		return lvc.GetItemText(index1, pView->m_nSortItem).CompareNoCase(lvc.GetItemText(index2, pView->m_nSortItem)) * pView->m_nSortDir;
}

void CMMListView::OnDblclk(NMHDR* pNMHDR, LRESULT* pResult) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	int i, nItem;

	nItem = lixxxtrl.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (lixxxtrl.GetItemState(i, LVIS_SELECTED) != 0)
			break;
	}

	if (i < nItem)
		pDoc->ShowPicture((int)lixxxtrl.GetItemData(i));

	*pResult = 0;
}

BOOL CMMListView::OnPreparePrinting(CPrintInfo* pInfo) 
{
	int nSelected;

	nSelected = GetLixxxtrl().GetSelectedCount();
	if (nSelected == 0)
		return FALSE;

	pInfo->SetMaxPage(nSelected);

	return DoPreparePrinting(pInfo);
}

void CMMListView::OnPrint(CDC* pDC, CPrintInfo* pInfo) 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	CLixxxtrl& lvc = GetLixxxtrl();
	int iItem;
	int nPage;
	UINT i;

	iItem = -1;
	for (i = 0; i < pInfo->m_nCurPage; i++)
		iItem = lvc.GetNextItem(iItem, LVNI_ALL | LVNI_SELECTED);

	nPage = (int)lvc.GetItemData(iItem);
	pDoc->ReadPage((int)lvc.GetItemData(iItem));

	PrintPicture(pDC, pDoc->m_pItem[nPage].m_hDIB1);
}

void CMMListView::OnDetailCdate() 
{
	m_bDetailFlag[DETAIL_CDATE] = !m_bDetailFlag[DETAIL_CDATE];
	DrawItems();
}

void CMMListView::OnUpdateDetailCdate(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_bDetailFlag[DETAIL_CDATE]);
}

void CMMListView::OnDetailCode() 
{
	m_bDetailFlag[DETAIL_CODE] = !m_bDetailFlag[DETAIL_CODE];
	DrawItems();
}

void CMMListView::OnUpdateDetailCode(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_bDetailFlag[DETAIL_CODE]);
}

void CMMListView::OnDetailCreator() 
{
	m_bDetailFlag[DETAIL_CREATOR] = !m_bDetailFlag[DETAIL_CREATOR];
	DrawItems();
}

void CMMListView::OnUpdateDetailCreator(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_bDetailFlag[DETAIL_CREATOR]);
}

void CMMListView::OnDetailName() 
{
	m_bDetailFlag[DETAIL_NAME] = !m_bDetailFlag[DETAIL_NAME];
	DrawItems();
}

void CMMListView::OnUpdateDetailName(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_bDetailFlag[DETAIL_NAME]);
}

void CMMListView::OnDetailRemark1() 
{
	m_bDetailFlag[DETAIL_REMARK1] = !m_bDetailFlag[DETAIL_REMARK1];
	DrawItems();
}

void CMMListView::OnUpdateDetailRemark1(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_bDetailFlag[DETAIL_REMARK1]);
}

void CMMListView::OnDetailRemark2() 
{
	m_bDetailFlag[DETAIL_REMARK2] = !m_bDetailFlag[DETAIL_REMARK2];
	DrawItems();
}

void CMMListView::OnUpdateDetailRemark2(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_bDetailFlag[DETAIL_REMARK2]);
}

void CMMListView::OnDetailUdate() 
{
	m_bDetailFlag[DETAIL_UDATE] = !m_bDetailFlag[DETAIL_UDATE];
	DrawItems();
}

void CMMListView::OnUpdateDetailUdate(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(m_bDetailFlag[DETAIL_UDATE]);
}

void CMMListView::OnInitialUpdate() 
{
	CMMViewDoc *pDoc = (CMMViewDoc *)GetDocument();
	SetFont(&pDoc->m_Font, FALSE);

	CListView::OnInitialUpdate();
}
