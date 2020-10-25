// MMLibListView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "MMLibListView.h"
#include "MMLibDoc.h"
#include "MainFrm.h"
#include "MyOleData.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMLibListView

IMPLEMENT_DYNCREATE(CMMLibListView, CListView)

CMMLibListView::CMMLibListView()
{
	m_pCurrentItem = NULL;
	m_nColumn = 0;
	m_nSortItem = -1;
	m_nCurrentDetailDisp = DETAIL_NONE;
}

CMMLibListView::~CMMLibListView()
{
}


BEGIN_MESSAGE_MAP(CMMLibListView, CListView)
	ON_WM_CREATE()
	ON_NOTIFY_REFLECT(NM_DBLCLK, OnDblclk)
	ON_NOTIFY_REFLECT(LVN_ENDLABELEDIT, OnEndlabeledit)
	ON_NOTIFY_REFLECT(NM_RCLICK, OnRclick)
	ON_NOTIFY_REFLECT(LVN_BEGINDRAG, OnBegindrag)
	ON_NOTIFY_REFLECT(LVN_COLUMNCLICK, OnColumnclick)
	ON_WM_DESTROY()
	ON_MESSAGE(WM_GETSELECTEDITEM, OnGetSelectedItem)
	ON_COMMAND(ID_FILE_PRINT, CListView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_DIRECT, CListView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_PREVIEW, CListView::OnFilePrintPreview)
	ON_UPDATE_COMMAND_UI(ID_FILE_PRINT, &CMMLibListView::OnUpdateFilePrint)
	ON_UPDATE_COMMAND_UI(ID_FILE_PRINT_PREVIEW, &CMMLibListView::OnUpdateFilePrintPreview)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMLibListView 描画

void CMMLibListView::OnDraw(CDC* pDC)
{
}

/////////////////////////////////////////////////////////////////////////////
// CMMLibListView 診断

#ifdef _DEBUG
void CMMLibListView::AssertValid() const
{
	CListView::AssertValid();
}

void CMMLibListView::Dump(CDumpContext& dc) const
{
	CListView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMLibListView メッセージ ハンドラ

int CMMLibListView::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	lpCreateStruct->style |= LVS_REPORT | LVS_EDITLABELS | LVS_SHOWSELALWAYS;
	if (CListView::OnCreate(lpCreateStruct) == -1)
		return -1;

//	SetViewFont();

	GetLixxxtrl().SetImageList(&g_ImageList, LVSIL_SMALL);
	GetLixxxtrl().SetImageList(&g_ImageList2, LVSIL_NORMAL);

	m_DropTarget.Register(this);

	return 0;
}

void CMMLibListView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
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
	case VIEWHINT_DRAWLIST:
		DrawItems();
		break;
	case VIEWHINT_SELECTALL:
		SelectAllItems();
		break;
	case VIEWHINT_SETFONT:
		SetViewFont();
		break;
	case VIEWHINT_UPDATEWINDOW:
		UpdateWindow();
		break;
	}
}

void CMMLibListView::DrawItems()
{
	CWaitCursor waitCursor;
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	CMainFrame *pMainFrame = (CMainFrame *)AfxGetApp()->m_pMainWnd;
	HTREEITEM hItem = g_pTreeCtrl->GetSelectedItem();

	if (hItem != NULL) {
		m_pCurrentItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);

		switch (m_pCurrentItem->m_nItemType) {
		case ITEM_ROOT:
			g_nDetailMode = DETAIL_LIBRARY;
			break;
		case ITEM_FOLDER:
		case ITEM_INBOX:
			switch (((CItemFolder *)m_pCurrentItem)->m_nFolderType) {
			case FTYPE_LIBRARY:
			case FTYPE_GFOLDER:
				g_nDetailMode = DETAIL_FOLDER;
				break;
			case FTYPE_DFOLDER:
				g_nDetailMode = DETAIL_DATA;
				break;
			}
			break;
		case ITEM_FIND:
			if (((CItemFind *)m_pCurrentItem)->m_nFindType == FIND_TYPE_FOLDER)
				g_nDetailMode = DETAIL_FOLDER;
			else
				g_nDetailMode = DETAIL_DATA;
			break;
		default:
			g_nDetailMode = DETAIL_NONE;
			return;
		}

		switch (g_nDetailMode) {
		case DETAIL_LIBRARY:
			pMainFrame->ShowPictView(FALSE);
			DrawLibrary((CItemRoot *)m_pCurrentItem);
			break;
		case DETAIL_FOLDER:
			pMainFrame->ShowPictView(FALSE);
			DrawGroupFolder(m_pCurrentItem);
			break;
		case DETAIL_DATA:
			if (pDoc->m_nViewMode == VIEWMODE_PICT)
				return;
			pMainFrame->ShowPictView(FALSE);
			DrawDataFolder(m_pCurrentItem);
			break;
		}
	}

	m_nSortItem = -1;

	pDoc->m_bPictView = FALSE;

	SetViewMode();
}

void CMMLibListView::DrawLibrary(CItemRoot *pItem)
{
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	HTREEITEM hItem;
	CItem *pItemChild;
	CItemFolder *pItemFolder;
	CItemPending *pItemPending;
	int i, j;
	int row, column;
	CString str;
	LPCTSTR p;

	InsertColumns(DETAIL_LIBRARY);

	row = 0;
	for (i = 0, hItem = g_pTreeCtrl->GetChildItem(pItem->m_hItem); hItem != NULL; i++, hItem = g_pTreeCtrl->GetNextSiblingItem(hItem)) {
		pItemChild = (CItem *)g_pTreeCtrl->GetItemData(hItem);

		switch (pItemChild->m_nItemType) {
		case ITEM_FOLDER:
		case ITEM_INBOX:
			pItemFolder = (CItemFolder *)pItemChild;

			column = 0;
			for (j = 0; j < DETAIL_NUM; j++) {
				if (!pDoc->m_bDetailFlag[DETAIL_LIBRARY][j])
					continue;

				switch (j) {
				case DETAIL_NAME:
					p = pItemFolder->m_FolderRec.folderName;
					break;
				case DETAIL_CDATE:
					FormatDate(pItemFolder->m_FolderRec.createDate, str);
					p = str;
					break;
				case DETAIL_UDATE:
					FormatDate(pItemFolder->m_FolderRec.updateDate, str);
					p = str;
					break;
				case DETAIL_CREATOR:
					p = pItemFolder->m_FolderRec.creator;
					break;
				case DETAIL_REMARK:
					p = pItemFolder->m_FolderRec.remark;
					break;
				case DETAIL_KEYWORD:
					p = pItemFolder->m_FolderRec.keyword;
					break;
				case DETAIL_SERVER:
					p = pItemFolder->m_pPcsDB->m_pPcserve->m_ServerName;
					break;
				case DETAIL_DIRECTORY:
					p = pItemFolder->m_pPcsDB->m_DirName;
					break;
				default:
					p = "";
					break;
				}

				if (column == 0)
					lixxxtrl.InsertItem(row, p, pItemFolder->m_nItemType == ITEM_FOLDER ? IMAGE_LIBRARY : IMAGE_INBOX);
				else
					lixxxtrl.SetItemText(row, column, p);

				column++;
			}
			break;
		case ITEM_PENDING:
			pItemPending = (CItemPending *)pItemChild;

			column = 0;
			for (j = 0; j < DETAIL_NUM; j++) {
				if (!pDoc->m_bDetailFlag[DETAIL_LIBRARY][j])
					continue;

				switch (j) {
				case DETAIL_NAME:
					p = pItemPending->m_Title;
					break;
				case DETAIL_CDATE:
				case DETAIL_UDATE:
				case DETAIL_CREATOR:
				case DETAIL_REMARK:
				case DETAIL_KEYWORD:
					p = "-----";
					break;
				case DETAIL_SERVER:
					if (pItemPending->m_nChType == CHT_LOCAL)
						p = "(Local)";
					else
						p = pItemPending->m_ServerName;
					break;
				case DETAIL_DIRECTORY:
					p = pItemPending->m_DirName;
					break;
				default:
					p = "";
					break;
				}

				if (column == 0)
					lixxxtrl.InsertItem(row, p, IMAGE_PENDING);
				else
					lixxxtrl.SetItemText(row, column, p);

				column++;
			}
			break;
		default:
			continue;
		}

		lixxxtrl.SetItemData(row, (DWORD_PTR)pItemChild);

		row++;
	}
}

void CMMLibListView::DrawGroupFolder(CItem *pItem)
{
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	HTREEITEM hItem;
	CItem *pItemChild;
	CItemFolder *pItemFolder;
	CItemFind *pItemFind;
	int i, j;
	int row, column;
	CString str;
	LPCTSTR p;
	int nDetailNo;

	InsertColumns(DETAIL_FOLDER);

	row = 0;
	for (i = 0, hItem = g_pTreeCtrl->GetChildItem(pItem->m_hItem); hItem != NULL; i++, hItem = g_pTreeCtrl->GetNextSiblingItem(hItem)) {
		pItemChild = (CItem *)g_pTreeCtrl->GetItemData(hItem);
		switch (pItemChild->m_nItemType) {
		case ITEM_FOLDER:
			pItemFolder = (CItemFolder *)pItemChild;
			column = 0;
			for (j = 0; j < DETAIL_NUM; j++) {
				nDetailNo = g_tColumnOrder[j];

				if (!pDoc->m_bDetailFlag[DETAIL_FOLDER][nDetailNo])
					continue;

				switch (nDetailNo) {
				case DETAIL_NAME:
					p = pItemFolder->m_FolderRec.folderName;
					break;
				case DETAIL_FIRSTDATA:
					str = pItemFolder->GetFirstData();
					p = str;
					break;
				case DETAIL_CDATE:
					FormatDate(pItemFolder->m_FolderRec.createDate, str);
					p = str;
					break;
				case DETAIL_UDATE:
					FormatDate(pItemFolder->m_FolderRec.updateDate, str);
					p = str;
					break;
				case DETAIL_CREATOR:
					p = pItemFolder->m_FolderRec.creator;
					break;
				case DETAIL_REMARK:
					p = pItemFolder->m_FolderRec.remark;
					break;
				case DETAIL_KEYWORD:
					p = pItemFolder->m_FolderRec.keyword;
					break;
				default:
					p = "";
					break;
				}

				if (column == 0)
					lixxxtrl.InsertItem(row, p, pItemFolder->m_nFolderType == FTYPE_GFOLDER ? IMAGE_GFOLDER : IMAGE_DFOLDER);
				else
					lixxxtrl.SetItemText(row, column, p);

				column++;
			}
			break;
		case ITEM_FIND:
			pItemFind = (CItemFind *)pItemChild;
			if (pDoc->m_bDetailFlag[DETAIL_FOLDER][DETAIL_NAME])
				lixxxtrl.InsertItem(row, pItemFind->m_FindRec.folderName,
						pItemFind->m_nFindType == FIND_TYPE_FOLDER ? IMAGE_FFOLDER : IMAGE_FDATA);
			break;
		}

		lixxxtrl.SetItemData(row, (DWORD_PTR)pItemChild);

		row++;
	}
}

void CMMLibListView::DrawDataFolder(CItem *pItem)
{
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	HTREEITEM hItem;
	CItem *pItemChild;
	CItemData *pItemData;
	int i, j;
	int row, column;
	CString str;
	LPCTSTR p;

	InsertColumns(DETAIL_DATA);

	row = 0;
	for (i = 0, hItem = g_pTreeCtrl->GetChildItem(pItem->m_hItem); hItem != NULL; i++, hItem = g_pTreeCtrl->GetNextSiblingItem(hItem)) {
		pItemChild = (CItem *)g_pTreeCtrl->GetItemData(hItem);
		if (pItemChild->m_nItemType != ITEM_DATA)
			continue;

		pItemData = (CItemData *)pItemChild;

		column = 0;
		for (j = 0; j < DETAIL_NUM; j++) {
			if (!pDoc->m_bDetailFlag[DETAIL_DATA][j])
				continue;

			switch (j) {
			case DETAIL_NAME:
				p = pItemData->m_DataRec.title;
				break;
			case DETAIL_CODE:
				p = pItemData->m_DataRec.code;
				break;
			case DETAIL_CDATE:
				FormatDate(pItemData->m_DataRec.createDate, str);
				p = str;
				break;
			case DETAIL_UDATE:
				FormatDate(pItemData->m_DataRec.updateDate, str);
				p = str;
				break;
			case DETAIL_CREATOR:
				p = pItemData->m_DataRec.creator;
				break;
			case DETAIL_REMARK:
				p = pItemData->m_DataRec.remark;
				break;
			case DETAIL_KEYWORD:
				p = pItemData->m_DataRec.keyword;
				break;
			default:
				p = "";
				break;
			}

			if (column == 0)
				lixxxtrl.InsertItem(row, p, IMAGE_DATA);
			else
				lixxxtrl.SetItemText(row, column, p);

			column++;
		}

		lixxxtrl.SetItemData(row, (DWORD_PTR)pItemChild);

		row++;
	}
}

void CMMLibListView::InsertColumns(int nDetailDisp)
{
	static UINT header[] = {
		IDS_NAME, IDS_CODE, IDS_CREATE_DATE, IDS_UPDATE_DATE, IDS_CREATOR,
		IDS_REMARK, IDS_KEYWORD, IDS_SERVER_NAME, IDS_DIRECTORY, IDS_FIRST_DATA
	};

	int i, nDetailNo;
	LVCOLUMN lvc;
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();

	SaveColumnWidth();

	lixxxtrl.DeleteAllItems();

	for (i = 0; i < m_nColumn; i++)
		lixxxtrl.DeleteColumn(0);

	m_nColumn = 0;
	for (i = 0; i < DETAIL_NUM; i++) {
		nDetailNo = g_tColumnOrder[i];
		if (pDoc->m_bDetailFlag[nDetailDisp][nDetailNo]) {
			lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
			lvc.fmt = LVCFMT_LEFT;
			lvc.pszText = (LPTSTR)(LPCTSTR)GetString(header[nDetailNo]);
			lvc.cx = pDoc->m_nDetailWidth[nDetailDisp][nDetailNo];
			lixxxtrl.InsertColumn(m_nColumn, &lvc);
			m_nCurrentDetailNo[m_nColumn] = nDetailNo;
			m_nColumn++;
		}
	}

	m_nCurrentDetailDisp = nDetailDisp;

	m_bTitleDisp = pDoc->m_bDetailFlag[nDetailDisp][0];
}

void CMMLibListView::SaveColumnWidth()
{
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	int i, nDetailNo;
	LVCOLUMN lvc;

	if (m_nCurrentDetailDisp != DETAIL_NONE) {
		for (i = 0; i < m_nColumn; i++) {
			lvc.mask = LVCF_WIDTH;
			lixxxtrl.GetColumn(i, &lvc); 
			nDetailNo = m_nCurrentDetailNo[i];
			pDoc->m_nDetailWidth[m_nCurrentDetailDisp][nDetailNo] = lvc.cx;
		}
	}
}

void CMMLibListView::SetViewMode()
{
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	DWORD dwStyle = GetWindowLong(m_hWnd, GWL_STYLE);

	switch (pDoc->m_nViewMode) {
	case VIEWMODE_PICT:
		dwStyle &= ~(LVS_ICON | LVS_LIST | LVS_REPORT);
		dwStyle |= LVS_ICON | LVS_EDITLABELS | LVS_SHOWSELALWAYS;
		break;
	case VIEWMODE_LIST:
		dwStyle &= ~(LVS_ICON | LVS_LIST | LVS_REPORT);
		dwStyle |= LVS_LIST | LVS_EDITLABELS | LVS_SHOWSELALWAYS;
		break;
	case VIEWMODE_DETAIL:
		dwStyle &= ~(LVS_ICON | LVS_LIST | LVS_REPORT);
		dwStyle |= LVS_REPORT | LVS_EDITLABELS | LVS_SHOWSELALWAYS;
		break;
	}

	SetWindowLong(m_hWnd, GWL_STYLE, dwStyle);
}

void CMMLibListView::OnDblclk(NMHDR* pNMHDR, LRESULT* pResult) 
{
	CLixxxtrl &lixxxtrl = GetLixxxtrl();
	int i, nItem;

	nItem = lixxxtrl.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (lixxxtrl.GetItemState(i, LVIS_SELECTED) != 0)
			break;
	}

	if (i < nItem) {
		CItem *pItem = (CItem *)lixxxtrl.GetItemData(i);
		GetDocument()->UpdateAllViews(NULL, VIEWHINT_SELECTITEM, pItem);
	}

	*pResult = 0;
}

void CMMLibListView::OnEndlabeledit(NMHDR* pNMHDR, LRESULT* pResult) 
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)pNMHDR;

	if (pDispInfo->item.pszText != NULL) {
		CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
		pDoc->NameChange((CItem *)GetLixxxtrl().GetItemData(pDispInfo->item.iItem), pDispInfo->item.pszText);
	}

	*pResult = 0;
}

void CMMLibListView::OnRclick(NMHDR* pNMHDR, LRESULT* pResult) 
{
	CLixxxtrl &lvc = GetLixxxtrl();
	POINT posScreen, posClient;
	UINT flag;
	int iItem;
	UINT nMenuID = 0;
	CItem *pItem;

	::GetCursorPos(&posScreen);

	posClient = posScreen;
	ScreenToClient(&posClient);

	if ((iItem = lvc.HitTest(posClient, &flag)) != -1)
		pItem = (CItem *)lvc.GetItemData(iItem);
	else
		pItem = m_pCurrentItem;

	switch (pItem->m_nItemType) {
	case ITEM_ROOT:
		nMenuID = IDR_ROOT;
		break;
	case ITEM_INBOX:
		nMenuID = IDR_INBOX;
		break;
	case ITEM_FOLDER:
		switch (((CItemFolder *)pItem)->m_nFolderType) {
		case FTYPE_LIBRARY:
			nMenuID = IDR_LIBRARY;
			break;
		case FTYPE_GFOLDER:
			nMenuID = IDR_GFOLDER;
			break;
		case FTYPE_DFOLDER:
			nMenuID = IDR_DFOLDER;
			break;
		}
		break;
	case ITEM_DATA:
		nMenuID = IDR_DATA;
		break;
	case ITEM_PENDING:
		nMenuID = IDR_GFOLDER;
		break;
	case ITEM_FIND:
		nMenuID = IDR_FIND;
		break;
	}

	if (nMenuID != 0) {
		CMenu menu;
		menu.LoadMenu(nMenuID);
		CMenu* pPopup = menu.GetSubMenu(0);
		pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, posScreen.x, posScreen.y, AfxGetMainWnd());
	}

	*pResult = 0;
}

void CMMLibListView::OnBegindrag(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;
	CLixxxtrl &lvc = GetLixxxtrl();

	CItem *pItem = GetSelectedItems();
	if (pItem == NULL || pItem->m_nItemType != ITEM_FOLDER && pItem->m_nItemType != ITEM_DATA) {
		*pResult = 1;
		return;
	}

	HGLOBAL hMem = ::GlobalAlloc(GMEM_DDESHARE, sizeof(CItem *));
	CItem **ppItem = (CItem **)::GlobalLock(hMem);
	*ppItem = pItem;
	::GlobalUnlock(hMem);

	CMyOleDataSource* pods = new CMyOleDataSource;
	pods->m_pItem = pItem;

	pods->CacheGlobalData(g_nCFMMLibrary, hMem);
	pods->DelayRenderData(CF_HDROP);
	pods->DoDragDrop(DROPEFFECT_COPY | DROPEFFECT_MOVE);

	delete pods;

	*pResult = 0;
}

DROPEFFECT CMMLibListView::OnDragOver(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point) 
{
	if (pDataObject->IsDataAvailable(g_nCFMMLibrary))
		return DragOverItem(pDataObject, dwKeyState, point);
	else if (pDataObject->IsDataAvailable(CF_HDROP))
		return DragOverFile(pDataObject, dwKeyState, point);
	else
		return DROPEFFECT_NONE;
}

DROPEFFECT CMMLibListView::DragOverItem(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point)
{
	CLixxxtrl& lvc = GetLixxxtrl();
	CItem *pItemSrc, *pItemDst, *pItemParent;
	CMMPcsDB *pPcsDBSrc, *pPcsDBDst;
	
	if ((pItemDst = GetTargetItem(point)) == NULL)
		pItemDst = m_pCurrentItem;

	if (pItemDst->m_nItemType != ITEM_FOLDER && pItemDst->m_nItemType != ITEM_INBOX)
		return DROPEFFECT_NONE;

	if ((pItemSrc = GetSourceItem(pDataObject)) == NULL)
		return DROPEFFECT_NONE;

	switch (pItemSrc->m_nItemType) {
	case ITEM_FOLDER:
		if (((CItemFolder *)pItemDst)->m_nFolderType == FTYPE_DFOLDER)
			return DROPEFFECT_NONE;
		pPcsDBSrc = ((CItemFolder *)pItemSrc)->m_pPcsDB;
		break;
	case ITEM_DATA:
		if (((CItemFolder *)pItemDst)->m_nFolderType != FTYPE_DFOLDER)
			return DROPEFFECT_NONE;
		pPcsDBSrc = ((CItemData *)pItemSrc)->m_pPcsDB;
		break;
	}

	if (dwKeyState & MK_CONTROL)
		return DROPEFFECT_COPY;

	if (dwKeyState & MK_SHIFT)
		return DROPEFFECT_MOVE;

	pItemParent = pItemSrc;
	while ((pItemParent = pItemParent->GetParent()) != NULL) {
		if (pItemParent->m_nItemType == ITEM_INBOX)
			return DROPEFFECT_MOVE;
	} 

	pPcsDBDst = ((CItemFolder *)pItemDst)->m_pPcsDB;
	if (pPcsDBSrc == pPcsDBDst)
		return DROPEFFECT_MOVE;
	else
		return DROPEFFECT_COPY;
}

DROPEFFECT CMMLibListView::DragOverFile(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point)
{
	CItem *pItemDst;
	int nFileType;

	if ((pItemDst = GetTargetItem(point)) == NULL)
		return DROPEFFECT_NONE;

	if (pItemDst->m_nItemType != ITEM_FOLDER && pItemDst->m_nItemType != ITEM_INBOX)
		return DROPEFFECT_NONE;

	nFileType = GetSourceFileType(pDataObject);

	if (nFileType & SRCFILETYPE_MML)
		;
	else if (nFileType & SRCFILETYPE_PICT) {
		if (((CItemFolder *)pItemDst)->m_nFolderType != FTYPE_DFOLDER)
			return DROPEFFECT_NONE;
	} else
		return DROPEFFECT_NONE;

	return DROPEFFECT_COPY;
}

BOOL CMMLibListView::OnDrop(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point) 
{
	if (pDataObject->IsDataAvailable(g_nCFMMLibrary))
		return DropItem(pDataObject, dropEffect, point);
	else if (pDataObject->IsDataAvailable(CF_HDROP))
		return DropFile(pDataObject, dropEffect, point);
	else
		return FALSE;
}

BOOL CMMLibListView::DropItem(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point) 
{
	CItem *pItemSrc, *pItemDst;
	
	if ((pItemDst = GetTargetItem(point)) == NULL)
		pItemDst = m_pCurrentItem;

	if (pItemDst->m_nItemType != ITEM_FOLDER && pItemDst->m_nItemType != ITEM_INBOX)
		return FALSE;

	if ((pItemSrc = GetSourceItem(pDataObject)) == NULL)
		return FALSE;

	if (pItemSrc == pItemDst)
		return FALSE;

	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();

	switch (dropEffect) {
	case DROPEFFECT_COPY:
		pDoc->CopyItem(pItemSrc, pItemDst);
		break;
	case DROPEFFECT_MOVE:
		pDoc->MoveItem(pItemSrc, pItemDst);
		break;
	default:
		return FALSE;
	}

	return TRUE;
}

BOOL CMMLibListView::DropFile(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point)
{
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	CItem *pItemDst;
	HDROP hDrop;
	int nNumFiles;
	int i;
	int nFileType = 0;
	int nPictFile = 0;
	char fileNameBuf[256];
	
	if ((pItemDst = GetTargetItem(point)) == NULL)
		return FALSE;

	if (pItemDst->m_nItemType != ITEM_FOLDER && pItemDst->m_nItemType != ITEM_INBOX)
		return FALSE;

	if ((hDrop = (HDROP)pDataObject->GetGlobalData(CF_HDROP, NULL)) == NULL)
		return FALSE;

	nNumFiles = ::DragQueryFile(hDrop, -1, NULL, 0);

	for (i = 0; i < nNumFiles; i++) {
		::DragQueryFile(hDrop, i, fileNameBuf, sizeof(fileNameBuf));
		nFileType = CheckFileType(fileNameBuf);

		if (nFileType & SRCFILETYPE_MML)
			pDoc->ImportMmlFile(fileNameBuf, (CItemFolder *)pItemDst);

		if (nFileType & SRCFILETYPE_PICT) {
			if (((CItemFolder *)pItemDst)->m_nFolderType == FTYPE_DFOLDER)
				nPictFile++;
		}
	}

	if (nPictFile != 0) {
		CString *pFileName = new CString[nPictFile];

		int n = 0;
		for (i = 0; i < nNumFiles; i++) {
			::DragQueryFile(hDrop, i, fileNameBuf, sizeof(fileNameBuf));
			nFileType = CheckFileType(fileNameBuf);

			if (nFileType & SRCFILETYPE_PICT)
				pFileName[n++] = fileNameBuf;
		}

		pDoc->ImportPictFile(pFileName, nPictFile, (CItemFolder *)pItemDst);
	}

	::GlobalFree(hDrop);

	return TRUE;
}

CItem *CMMLibListView::GetTargetItem(CPoint point)
{
	CLixxxtrl& lvc = GetLixxxtrl();
	UINT flags;

	int iItem = lvc.HitTest(point, &flags);
	if (iItem == -1)
		return m_pCurrentItem;

	return (CItem *)lvc.GetItemData(iItem);
}

CItem *CMMLibListView::GetSourceItem(COleDataObject* pDataObject)
{
	HGLOBAL hGlobal;

	if ((hGlobal = pDataObject->GetGlobalData(g_nCFMMLibrary, NULL)) == NULL)
		return NULL;

	CItem *pItem = *(CItem **)GlobalLock(hGlobal);
	GlobalUnlock(hGlobal);
	GlobalFree(hGlobal);

	return pItem;
}

int CMMLibListView::GetSourceFileType(COleDataObject* pDataObject)
{
	HDROP hDrop;
	int nNumFiles;
	int i;
	int nFileType = 0;
	char fileNameBuf[256];

	if ((hDrop = (HDROP)pDataObject->GetGlobalData(CF_HDROP, NULL)) == NULL)
		return 0;

	nNumFiles = ::DragQueryFile(hDrop, -1, NULL, 0);

	for (i = 0; i < nNumFiles; i++) {
		::DragQueryFile(hDrop, i, fileNameBuf, sizeof(fileNameBuf));
		nFileType |= CheckFileType(fileNameBuf);
	}

	::GlobalFree(hDrop);

	return nFileType;
}

LRESULT CMMLibListView::OnGetSelectedItem(WPARAM wParam, LPARAM lParam)
{
	CItem *pItem = GetSelectedItems();

	if (pItem == NULL && m_pCurrentItem != NULL) {
		pItem = m_pCurrentItem;
		pItem->m_pNextSelected = NULL;
	}

	return (LRESULT)pItem;
}

CItem *CMMLibListView::GetSelectedItems()
{
	CLixxxtrl& lvc = GetLixxxtrl();
	int iItem;
	CItem *pItem = NULL, **ppItem;

	ppItem = &pItem;
	iItem = -1;
	for (;;) {
		iItem = lvc.GetNextItem(iItem, LVNI_ALL | LVNI_SELECTED);
		if (iItem == -1)
			break;
		*ppItem = (CItem *)lvc.GetItemData(iItem);
		ppItem = &(*ppItem)->m_pNextSelected;
		*ppItem = NULL;
	}

	return pItem;
}

void CMMLibListView::OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult) 
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

int CALLBACK CMMLibListView::CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort)
{
	CMMLibListView *pView = (CMMLibListView *)lParamSort;
	CLixxxtrl &lvc = pView->GetLixxxtrl();
	LV_FINDINFO findInfo;
	int index1, index2;

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam1;
	index1 = lvc.FindItem(&findInfo);

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam2;
	index2 = lvc.FindItem(&findInfo);

	if (pView->m_bTitleDisp && pView->m_nSortItem == 0)
		return CompareTitle(lvc.GetItemText(index1, pView->m_nSortItem), lvc.GetItemText(index2, pView->m_nSortItem)) * pView->m_nSortDir;
	else
		return lvc.GetItemText(index1, pView->m_nSortItem).CompareNoCase(lvc.GetItemText(index2, pView->m_nSortItem)) * pView->m_nSortDir;
}

BOOL CMMLibListView::OnPreparePrinting(CPrintInfo* pInfo) 
{
	int nSelected;
	CItem *pItem;

	if (m_pCurrentItem == NULL || m_pCurrentItem->m_nItemType != ITEM_FOLDER ||
				((CItemFolder *)m_pCurrentItem)->m_nFolderType != FTYPE_DFOLDER) {
		AfxMessageBox(IDS_PRINT_FOLDER);
		return FALSE;
	}

	if ((pItem = GetSelectedItems()) == NULL) {
		AfxMessageBox(IDS_SELECT_PRINT_DATA);
		return FALSE;
	}

	m_pPrintTreeItemData = (CItemData *)pItem;

	nSelected = 0;
	for ( ; pItem != NULL; pItem = pItem->m_pNextSelected)
		nSelected++;

	pInfo->SetMaxPage(nSelected);

	return DoPreparePrinting(pInfo);
}

void CMMLibListView::OnPrint(CDC* pDC, CPrintInfo* pInfo) 
{
	HDIB hDIB;
	CItemData *pItemData;
	UINT i;

	pItemData = m_pPrintTreeItemData;
	for (i = 1; i < pInfo->m_nCurPage; i++) {
		pItemData = (CItemData *)pItemData->m_pNextSelected;
		if (pItemData == NULL)
			return;
	}

	pItemData->m_pPcsDB->ReadPictureDataEx(pItemData->m_DataRec.dataID, &hDIB, NULL);
	PrintPicture(pDC, hDIB);
	MMPictureFree(hDIB);
}

void CMMLibListView::SelectAllItems() 
{
	CLixxxtrl& lvc = GetLixxxtrl();
	int iItem;

	iItem = -1;
	for (;;) {
		iItem = lvc.GetNextItem(iItem, LVNI_ALL);
		if (iItem == -1)
			break;

		lvc.SetItemState(iItem, LVIS_SELECTED, LVIS_SELECTED);
	}

	((CFrameWnd *)AfxGetMainWnd())->SetActiveView(this);
}

void CMMLibListView::SetViewFont()
{
	m_Font.DeleteObject();
	m_Font.CreatePointFont(g_nViewFontSize, g_ViewFontName);
	SetFont(&m_Font);
}

BOOL CMMLibListView::OnScroll(UINT nScrollCode, UINT nPos, BOOL bDoScroll) 
{
	int nMinPos, nMaxPos;
	BOOL bScroll = FALSE;

	int y = GetScrollPos(SB_VERT);
	int nLimit = GetScrollLimit(SB_VERT);
	GetScrollRange(SB_VERT, &nMinPos, &nMaxPos);

	switch (HIBYTE(nScrollCode)) {
	case SB_TOP:
	case SB_LINEUP:
	case SB_PAGEUP:
		bScroll = (y > nMinPos);
		break;
	case SB_BOTTOM:
	case SB_LINEDOWN:
	case SB_PAGEDOWN:
		bScroll = (y < nLimit);
		break;
	}

	if (bScroll) {
		if (bDoScroll)
			SendMessage(WM_VSCROLL, HIBYTE(nScrollCode), 0);
	}

	return bScroll;
}

BOOL CMMLibListView::PreCreateWindow(CREATESTRUCT& cs) 
{
	cs.dwExStyle |= WS_EX_CLIENTEDGE;
	
	return CListView::PreCreateWindow(cs);
}

void CMMLibListView::OnDestroy() 
{
	SaveColumnWidth();

	CListView::OnDestroy();
}

void CMMLibListView::OnInitialUpdate()
{
	CListView::OnInitialUpdate();

	SetViewFont();
}

void CMMLibListView::OnUpdateFilePrint(CCmdUI *pCmdUI)
{
	CItem *pItem;
	BOOL bEnable = TRUE;

	if (m_pCurrentItem == NULL || m_pCurrentItem->m_nItemType != ITEM_FOLDER ||
				((CItemFolder *)m_pCurrentItem)->m_nFolderType != FTYPE_DFOLDER) {
		bEnable = FALSE;
	}

	if ((pItem = GetSelectedItems()) == NULL)
		bEnable = FALSE;

	pCmdUI->Enable(bEnable);
}

void CMMLibListView::OnUpdateFilePrintPreview(CCmdUI *pCmdUI)
{
	OnUpdateFilePrint(pCmdUI);
}
