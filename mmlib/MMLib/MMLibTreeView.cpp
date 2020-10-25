// MMLibTreeView.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "MMLib.h"
#include "MMLibTreeView.h"
#include "MMLibDoc.h"
#include "MyOleData.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMLibTreeView

IMPLEMENT_DYNCREATE(CMMLibTreeView, CTreeView)

CMMLibTreeView::CMMLibTreeView()
{
	m_bInUpdate = FALSE;
}

CMMLibTreeView::~CMMLibTreeView()
{
}


BEGIN_MESSAGE_MAP(CMMLibTreeView, CTreeView)
	ON_NOTIFY_REFLECT(TVN_SELCHANGED, OnSelchanged)
	ON_WM_DESTROY()
	ON_WM_CREATE()
	ON_NOTIFY_REFLECT(TVN_ITEMEXPANDING, OnItemexpanding)
	ON_NOTIFY_REFLECT(NM_RCLICK, OnRclick)
	ON_NOTIFY_REFLECT(TVN_BEGINLABELEDIT, OnBeginlabeledit)
	ON_NOTIFY_REFLECT(TVN_ENDLABELEDIT, OnEndlabeledit)
	ON_NOTIFY_REFLECT(TVN_BEGINDRAG, OnBegindrag)
	ON_MESSAGE(WM_GETSELECTEDITEM, OnGetSelectedItem)
	ON_COMMAND(ID_FILE_PRINT, CTreeView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_DIRECT, CTreeView::OnFilePrint)
	ON_COMMAND(ID_FILE_PRINT_PREVIEW, CTreeView::OnFilePrintPreview)
	ON_UPDATE_COMMAND_UI(ID_FILE_PRINT, &CMMLibTreeView::OnUpdateFilePrint)
	ON_UPDATE_COMMAND_UI(ID_FILE_PRINT_PREVIEW, &CMMLibTreeView::OnUpdateFilePrintPreview)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMLibTreeView �`��

void CMMLibTreeView::OnDraw(CDC* pDC)
{
	CDocument* pDoc = GetDocument();
	// TODO: ���̈ʒu�ɕ`��p�̃R�[�h��ǉ����Ă�������
}

/////////////////////////////////////////////////////////////////////////////
// CMMLibTreeView �f�f

#ifdef _DEBUG
void CMMLibTreeView::AssertValid() const
{
	CTreeView::AssertValid();
}

void CMMLibTreeView::Dump(CDumpContext& dc) const
{
	CTreeView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMLibTreeView ���b�Z�[�W �n���h��

int CMMLibTreeView::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	lpCreateStruct->style |= TVS_HASLINES | TVS_HASBUTTONS | TVS_SHOWSELALWAYS | TVS_EDITLABELS;
	if (CTreeView::OnCreate(lpCreateStruct) == -1)
		return -1;

	g_pTreeCtrl = &GetTreeCtrl();

	m_DropTarget.Register(this);

	return 0;
}

void CMMLibTreeView::OnInitialUpdate() 
{
	CTreeView::OnInitialUpdate();

	SetViewFont();

	m_Order = AfxGetApp()->GetProfileString(g_SectionOptions, "ViewOrder", "0123456");
	m_nTextMode = AfxGetApp()->GetProfileInt(g_SectionOptions, "ViewText", 0);

	CTreeCtrl &tvc = GetTreeCtrl();
	tvc.SetImageList(&g_ImageList, TVSIL_NORMAL);

	CItemRoot *pItemRoot = new CItemRoot;
	m_hTreeRoot = tvc.InsertItem(TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT,
			GetString(IDS_MULTIMEDIA_LIBRARY), IMAGE_ROOT, IMAGE_ROOT, TVIS_EXPANDED, TVIS_EXPANDED,
			(LPARAM)pItemRoot, TVI_ROOT, TVI_LAST);
	pItemRoot->m_hItem = m_hTreeRoot;

	g_pItemInbox = ::OpenInbox();
	if (g_pItemInbox != NULL) {
		TV_INSERTSTRUCT tvis;
		memset(&tvis, 0, sizeof(tvis));
		tvis.hParent = m_hTreeRoot;
		tvis.hInsertAfter = TVI_LAST;
		tvis.item.mask = TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_TEXT | TVIF_CHILDREN;
		tvis.item.pszText = (LPTSTR)(LPCTSTR)g_pItemInbox->m_FolderRec.folderName;
		tvis.item.iImage = IMAGE_INBOX;
		tvis.item.iSelectedImage = IMAGE_INBOX_OPEN;
		tvis.item.cChildren = 1;
		tvis.item.lParam = (LPARAM)g_pItemInbox;
		g_pItemInbox->m_hItem = tvc.InsertItem(&tvis);
	}
}

void CMMLibTreeView::OnSelchanged(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_TREEVIEW* pNMTreeView = (NM_TREEVIEW*)pNMHDR;
	HTREEITEM hItem = pNMTreeView->itemNew.hItem;
	CTreeCtrl &tvc = GetTreeCtrl();
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();

	CItem *pItem = (CItem *)tvc.GetItemData(hItem);

	if (pItem->m_nItemType == ITEM_PENDING)
		pItem = OpenLibrary((CItemPending *)pItem);

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
	case ITEM_INBOX:
		ReadChildren((CItemFolder *)pItem);
		break;
	case ITEM_FIND:
		FindData((CItemFind *)pItem);
		break;
	}

	g_StatusInfo = tvc.GetItemText(hItem);

	if (!m_bInUpdate) {
		pDoc->UpdateAllViews(this, VIEWHINT_DRAWALL);
		((CFrameWnd *)AfxGetMainWnd())->SetActiveView(this);
	}

	*pResult = 0;
}


void CMMLibTreeView::OnDestroy() 
{
	CTreeView::OnDestroy();

	CItem::RemoveAll();
}

void CMMLibTreeView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
{
	m_bInUpdate = TRUE;

	switch (lHint) {
	case VIEWHINT_ADDITEM:
		AddItem((CAddItem *)pHint);
		break;
	case VIEWHINT_SELECTITEM:
		GetTreeCtrl().SelectItem(((CItem *)pHint)->m_hItem);
		break;
	case VIEWHINT_OPENITEM:
		GetTreeCtrl().Expand(((CItem *)pHint)->m_hItem, TVE_EXPAND);
		break;
	case VIEWHINT_CLOSEITEM:
		GetTreeCtrl().Expand(((CItem *)pHint)->m_hItem, TVE_COLLAPSE);
		break;
	case VIEWHINT_REMOVEITEM:
		RemoveItem((CItem *)pHint);
		break;
	case VIEWHINT_UPDATEITEM:
		UpdateItem((CItem *)pHint);
		break;
	case VIEWHINT_RELOADITEM:
		ReloadAllItems();
		break;
	case VIEWHINT_REFINDITEM:
		ReFindItem((CItem *)pHint);
		break;
	case VIEWHINT_SETFONT:
		SetViewFont();
		break;
	case VIEWHINT_SORTITEM:
		SortAllItems();
		break;
	case VIEWHINT_SETTEXT:
		SetTextDataItems();
		break;
	case VIEWHINT_UPDATEWINDOW:
		UpdateWindow();
		break;
	case VIEWHINT_SELECTDATA:
		SelectData((CSelectItem *)pHint);
		break;
	}

	m_bInUpdate = FALSE;
}

void CMMLibTreeView::AddItem(CAddItem *pAddItem)
{
	CItemFolder *pItemFolder;

	switch (pAddItem->m_nAddItemType) {
	case ADDITEM_LIBRARY:
		AddLibrary(((CAddItemLibrary *)pAddItem)->m_pPcsDB);
		SortItem((CItem *)GetTreeCtrl().GetItemData(m_hTreeRoot));
		break;
	case ADDITEM_FOLDER:
		pItemFolder = ((CAddItemFolder *)pAddItem)->m_pItemFolder;
		SetChildren(pItemFolder->m_hItem, TRUE);
		if (!pItemFolder->m_bReadChildren)
			return;
		((CAddItemFolder *)pAddItem)->m_pItemAddFolder = AddFolder(pItemFolder, ((CAddItemFolder *)pAddItem)->m_pFolderRec);
		SortItem(pItemFolder);
		break;
	case ADDITEM_DATA:
		pItemFolder = ((CAddItemData *)pAddItem)->m_pItemFolder;
		SetChildren(pItemFolder->m_hItem, TRUE);
		if (!pItemFolder->m_bReadChildren)
			return;
		((CAddItemData *)pAddItem)->m_pItemAddData = AddData(pItemFolder, ((CAddItemData *)pAddItem)->m_pDataRec);
		SortItem(pItemFolder);
		break;
	case ADDITEM_PENDING:
		AddPendingLibrary((CAddItemPending *)pAddItem);
		break;
	case ADDITEM_FIND:
		AddFind((CAddItemFind *)pAddItem);
		break;
	}
}

void CMMLibTreeView::AddLibrary(CMMPcsDB *pPcsDB)
{
	CWaitCursor waitCursor;
	CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
	CTreeCtrl &tvc = GetTreeCtrl();
	TV_INSERTSTRUCT tvis;

	CItemFolder *pItem = new CItemFolder(pPcsDB);
	pItem->m_nFolderType = FTYPE_LIBRARY;
	if (pPcsDB->ReadLibraryEx(&pItem->m_FolderRec)) {
		memset(&tvis, 0, sizeof(tvis));
		tvis.hParent = m_hTreeRoot;
		tvis.hInsertAfter = TVI_LAST;
		tvis.item.mask = TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT | TVIF_CHILDREN;
		tvis.item.state = 0;
		tvis.item.stateMask = 0;
		tvis.item.pszText = (LPTSTR)(LPCTSTR)pItem->m_FolderRec.folderName;
		tvis.item.iImage = IMAGE_LIBRARY;
		tvis.item.iSelectedImage = IMAGE_LIBRARY;
		tvis.item.cChildren = 1;
		tvis.item.lParam = (LPARAM)pItem;
		pItem->m_hItem = tvc.InsertItem(&tvis);
		pDoc->OpenFind(pItem);
	} else
		delete pItem;
}

void CMMLibTreeView::AddPendingLibrary(CAddItemPending *pAddItemPending)
{
	CTreeCtrl &tvc = GetTreeCtrl();
	TV_INSERTSTRUCT tvis;

	CItemPending *pItem = new CItemPending;

	pItem->m_Title = pAddItemPending->m_Title;
	pItem->m_ServerName = pAddItemPending->m_ServerName;
	pItem->m_nChType = pAddItemPending->m_nChType;
	pItem->m_DirName = pAddItemPending->m_DirName;
	pItem->m_LibraryID = pAddItemPending->m_LibraryID;
	pItem->m_nCacheMode = pAddItemPending->m_nCacheMode;

	memset(&tvis, 0, sizeof(tvis));
	tvis.hParent = m_hTreeRoot;
	tvis.hInsertAfter = TVI_LAST;
	tvis.item.mask = TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT | TVIF_CHILDREN;
	tvis.item.state = 0;
	tvis.item.stateMask = 0;
	tvis.item.pszText = (LPTSTR)(LPCTSTR)pItem->m_Title;
	tvis.item.iImage = IMAGE_PENDING;
	tvis.item.iSelectedImage = IMAGE_PENDING;
	tvis.item.cChildren = 1;
	tvis.item.lParam = (LPARAM)pItem;
	pItem->m_hItem = tvc.InsertItem(&tvis);
}

CItem *CMMLibTreeView::OpenLibrary(CItemPending *pItemPending)
{
	CWaitCursor wait;
	CTreeCtrl &tvc = GetTreeCtrl();
	CMMPcsDB *pPcsDB = NULL;
	int nError = 0;

	if (!g_bParamOffline) {
		if ((pPcsDB = g_pMMLibDB->OpenMMLibrary(pItemPending->m_nChType, pItemPending->m_ServerName, pItemPending->m_DirName, pItemPending->m_nCacheMode)) == 0)
			nError = g_nLastPcserveError;
	}

	if (pPcsDB == NULL && nError >= 0) {
		if ((pPcsDB = g_pMMLibDB->OpenMMLibrary(pItemPending->m_nChType, pItemPending->m_ServerName, pItemPending->m_DirName, CACHEMODE_OFFLINE, pItemPending->m_LibraryID)) != NULL) {
			if (!g_bParamOffline) {
				AfxMessageBox(IDS_OFFLINE_MODE);
			}
		}
	}

	if (pPcsDB != NULL) {
		CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
		CItemFolder *pItemFolder = new CItemFolder(pPcsDB);

		pItemFolder->m_nFolderType = FTYPE_LIBRARY;
		pItemFolder->m_hItem = pItemPending->m_hItem;
		pPcsDB->ReadLibraryEx(&pItemFolder->m_FolderRec);
		tvc.SetItemImage(pItemPending->m_hItem, IMAGE_LIBRARY, IMAGE_LIBRARY);
		tvc.SetItemData(pItemPending->m_hItem, (DWORD_PTR)pItemFolder);
		delete pItemPending;
		pDoc->OpenFind(pItemFolder);
		return pItemFolder;
	} else
		return pItemPending;
}

CItemFolder *CMMLibTreeView::AddFolder(CItemFolder *pItemParent, FOLDERREC *pFolderRec)
{
	return AddFolder(pItemParent->m_hItem, pItemParent->m_pPcsDB, pFolderRec);
}

CItemFolder *CMMLibTreeView::AddFolder(CItemFind *pItemParent, FOLDERREC *pFolderRec)
{
	return AddFolder(pItemParent->m_hItem, pItemParent->m_pPcsDB, pFolderRec);
}

CItemFolder *CMMLibTreeView::AddFolder(HTREEITEM hItem, CMMPcsDB *pPcsDB, FOLDERREC *pFolderRec)
{
	CItemFolder *pItem = new CItemFolder(pPcsDB);
	pItem->m_nFolderType = pFolderRec->folderType[0];
	pItem->m_FolderRec = *pFolderRec;

	TV_INSERTSTRUCT tvis;
	memset(&tvis, 0, sizeof(tvis));
	tvis.hParent = hItem;
	tvis.hInsertAfter = TVI_LAST;
	tvis.item.mask = TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_TEXT | TVIF_CHILDREN;
	tvis.item.pszText = (LPTSTR)(LPCTSTR)pItem->m_FolderRec.folderName;
	tvis.item.iImage = (pItem->m_nFolderType == FTYPE_GFOLDER) ? IMAGE_GFOLDER : IMAGE_DFOLDER;
	tvis.item.iSelectedImage = (pItem->m_nFolderType == FTYPE_GFOLDER) ? IMAGE_GFOLDER_OPEN : IMAGE_DFOLDER_OPEN;
	tvis.item.cChildren = 1;
	tvis.item.lParam = (LPARAM)pItem;
	pItem->m_hItem = GetTreeCtrl().InsertItem(&tvis);

	return pItem;
}

CItemData *CMMLibTreeView::AddData(CItemFolder *pItemParent, DATAREC *pDataRec)
{
	return AddDataSub(pItemParent->m_hItem, pItemParent->m_pPcsDB, pDataRec);
}

CItemData *CMMLibTreeView::AddData(CItemFind *pItemParent, DATAREC *pDataRec)
{
	return AddDataSub(pItemParent->m_hItem, pItemParent->m_pPcsDB, pDataRec);
}

CItemData *CMMLibTreeView::AddDataSub(HTREEITEM hItem, CMMPcsDB *pPcsDB, DATAREC *pDataRec)
{
	CItemData *pItem = new CItemData(pPcsDB);
	pItem->m_DataRec = *pDataRec;

	TV_INSERTSTRUCT tvis;
	memset(&tvis, 0, sizeof(tvis));
	tvis.hParent = hItem;
	tvis.hInsertAfter = TVI_LAST;
	tvis.item.mask = TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_TEXT;
	tvis.item.pszText = (LPSTR)DataItemText(pItem);
	tvis.item.iImage = IMAGE_DATA;
	tvis.item.iSelectedImage = IMAGE_DATA;
	tvis.item.lParam = (LPARAM)pItem;
	pItem->m_hItem = GetTreeCtrl().InsertItem(&tvis);

	return pItem;
}

void CMMLibTreeView::AddFind(CAddItemFind *pAddItemFind)
{
	CItemFind *pItem = new CItemFind(pAddItemFind->m_pItemFolder->m_pPcsDB);
	pItem->m_FindRec = *pAddItemFind->m_pFindRec;
	pItem->m_nFindType = pAddItemFind->m_pFindRec->findType[0];
	pItem->m_bSave = pAddItemFind->m_bSave;

	TV_INSERTSTRUCT tvis;
	memset(&tvis, 0, sizeof(tvis));
	tvis.hParent = pAddItemFind->m_pItemFolder->m_hItem;
	tvis.hInsertAfter = TVI_LAST;
	tvis.item.mask = TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT | TVIF_CHILDREN;
	tvis.item.state = 0;
	tvis.item.stateMask = 0;
	tvis.item.pszText = (LPTSTR)(LPCTSTR)pItem->m_FindRec.folderName;
	tvis.item.iImage = pItem->m_nFindType == FIND_TYPE_FOLDER ? IMAGE_FFOLDER : IMAGE_FDATA;
	tvis.item.iSelectedImage = pItem->m_nFindType == FIND_TYPE_FOLDER ? IMAGE_FFOLDER_OPEN : IMAGE_FDATA_OPEN;
	tvis.item.cChildren = 1;
	tvis.item.lParam = (LPARAM)pItem;
	pItem->m_hItem = GetTreeCtrl().InsertItem(&tvis);

	SortItem(pAddItemFind->m_pItemFolder);

	if (pAddItemFind->m_bSelect)
		GetTreeCtrl().SelectItem(pItem->m_hItem);
}

void CMMLibTreeView::OnItemexpanding(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_TREEVIEW* pNMTreeView = (NM_TREEVIEW*)pNMHDR;
	HTREEITEM hItem = pNMTreeView->itemNew.hItem;
	CTreeCtrl &tvc = GetTreeCtrl();

	CItem *pItem = (CItem *)tvc.GetItemData(hItem);

	if (pItem->m_nItemType == ITEM_PENDING) {
		CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
		pItem = OpenLibrary((CItemPending *)pItem);
		pDoc->UpdateAllViews(this, VIEWHINT_DRAWALL);
	}

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
	case ITEM_INBOX:
		ReadChildren((CItemFolder *)pItem);
		break;
	case ITEM_FIND:
		FindData((CItemFind *)pItem);
		break;
	}

	*pResult = 0;
}

void CMMLibTreeView::ReadChildren(CItemFolder *pItemFolder)
{
	if (pItemFolder->m_bReadChildren)
		return;

	CWaitCursor waitCursor;

	LPCTSTR pFolderID = pItemFolder->m_FolderRec.folderID;
	HTREEITEM hItem = pItemFolder->m_hItem;
	CMMPcsDB *pPcsDB = pItemFolder->m_pPcsDB;
	BOOL bFirst;
	FOLDERREC folderRec;
	DATAREC dataRec;
	BOOL bHasChild = FALSE;

	switch (pItemFolder->m_nFolderType) {
	case FTYPE_LIBRARY:
	case FTYPE_GFOLDER:
		bFirst = TRUE;
		while (pPcsDB->ReadChildFolderEx(pFolderID, &folderRec, bFirst)) {
			AddFolder(pItemFolder, &folderRec);

			bFirst = FALSE;
			bHasChild = TRUE;
		}
		break;
	case FTYPE_DFOLDER:
		bFirst = TRUE;
		while (pPcsDB->ReadChildDataEx(pFolderID, &dataRec, bFirst)) {
			AddData(pItemFolder, &dataRec);

			bFirst = FALSE;
			bHasChild = TRUE;
		}
		break;
	}

	pItemFolder->m_bReadChildren = TRUE;

	SetChildren(hItem, bHasChild);

	SortItem(pItemFolder);
}

void CMMLibTreeView::FindData(CItemFind *pItemFind)
{
	if (pItemFind->m_bReadChildren)
		return;

	CWaitCursor waitCursor;

	CTreeCtrl &tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CMMPcsDB *pPcsDB = pItemFind->m_pPcsDB;
	FOLDERREC folderRec;
	DATAREC dataRec;
	BOOL bHasChild = FALSE;
	BOOL bRead;

	while ((hItem = tvc.GetChildItem(pItemFind->m_hItem)) != NULL) {
		delete (CItem *)tvc.GetItemData(hItem);
		tvc.DeleteItem(hItem);
	}

	CString filter;
	if (pItemFind->m_nFindType == FIND_TYPE_SIMPLE)
		filter = MakeFilterString2(pItemFind->m_FindRec.conditions[0].keyword);
	else
		filter = MakeFilterString(pItemFind->m_FindRec.conditions, pItemFind->m_nFindType);

	if (pItemFind->m_nFindType == FIND_TYPE_FOLDER) {
		if (!pPcsDB->FilterFolderEx(filter))
			return;

		while (pPcsDB->ReadFolderAllEx(&folderRec, &bRead)) {
			if (!bRead)
				break;

			AddFolder(pItemFind, &folderRec);

			bHasChild = TRUE;
		}

		pPcsDB->FilterFolderEx(NULL);
	} else {
		if (!pPcsDB->FilterDataEx(filter))
			return;

		while (pPcsDB->ReadDataAllEx(&dataRec, &bRead)) {
			if (!bRead)
				break;

			AddData(pItemFind, &dataRec);

			bHasChild = TRUE;
		}

		pPcsDB->FilterDataEx(NULL);
	}

	pItemFind->m_bReadChildren = TRUE;

	SetChildren(pItemFind->m_hItem, bHasChild);

	SortItem(pItemFind);
}

void CMMLibTreeView::ReFindItem(CItem *pItem)
{
	if (pItem->m_nItemType != ITEM_FIND)
		return;

	((CItemFind *)pItem)->m_bReadChildren = FALSE;
	FindData((CItemFind *)pItem);
}

void CMMLibTreeView::SetChildren(HTREEITEM hItem, BOOL bChild)
{
	TV_ITEM tvi;

	memset(&tvi, 0, sizeof(tvi));
	tvi.hItem = hItem;
	tvi.mask = TVIF_CHILDREN;
	tvi.cChildren = bChild;
	GetTreeCtrl().SetItem(&tvi);
}

void CMMLibTreeView::RemoveItem(CItem *pItem)
{
	CMMPcsDB *pPcsDB;

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
		pPcsDB = ((CItemFolder *)pItem)->m_pPcsDB;
		break;
	case ITEM_DATA:
		pPcsDB = ((CItemData *)pItem)->m_pPcsDB;
		break;
	default:
		RemoveItemSub(pItem);
		return;
	}

	CString id = pItem->GetID();

	for (pItem = CItem::GetHeadItem(); pItem != NULL; pItem = CItem::GetNextItem()) {
		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			if (pPcsDB == ((CItemFolder *)pItem)->m_pPcsDB && id == pItem->GetID()) {
				RemoveItemSub(pItem);
				pItem = CItem::GetHeadItem();
			}
			break;
		case ITEM_DATA:
			if (pPcsDB == ((CItemData *)pItem)->m_pPcsDB && id == pItem->GetID()) {
				RemoveItemSub(pItem);
				pItem = CItem::GetHeadItem();
			}
			break;
		}
	}
}

void CMMLibTreeView::RemoveItemSub(CItem *pItem)
{
	CTreeCtrl &tvc = GetTreeCtrl();
	HTREEITEM hItem;

	while ((hItem = tvc.GetChildItem(pItem->m_hItem)) != NULL) {
		RemoveItemSub((CItem *)tvc.GetItemData(hItem));
	}

	tvc.DeleteItem(pItem->m_hItem);

	delete pItem;
}

void CMMLibTreeView::UpdateItem(CItem *pItem)
{
	CMMPcsDB *pPcsDB;

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
		pPcsDB = ((CItemFolder *)pItem)->m_pPcsDB;
		break;
	case ITEM_DATA:
		pPcsDB = ((CItemData *)pItem)->m_pPcsDB;
		break;
	default:
		UpdateItemSub(pItem);
		return;
	}

	CString id = pItem->GetID();
	CItem *pItem2;

	for (pItem2 = CItem::GetHeadItem(); pItem2 != NULL; pItem2 = CItem::GetNextItem()) {
		if (pItem->m_nItemType != pItem2->m_nItemType)
			continue;

		switch (pItem2->m_nItemType) {
		case ITEM_FOLDER:
			if (pPcsDB == ((CItemFolder *)pItem2)->m_pPcsDB && id == pItem2->GetID()) {
				if (pItem != pItem2)
					((CItemFolder *)pItem2)->m_FolderRec = ((CItemFolder *)pItem)->m_FolderRec;
				UpdateItemSub(pItem2);
			}
			break;
		case ITEM_DATA:
			if (pPcsDB == ((CItemData *)pItem2)->m_pPcsDB && id == pItem2->GetID()) {
				if (pItem != pItem2)
					((CItemData *)pItem2)->m_DataRec = ((CItemData *)pItem)->m_DataRec;
				UpdateItemSub(pItem2);
			}
			break;
		}
	}
}

void CMMLibTreeView::UpdateItemSub(CItem *pItem)
{
	CTreeCtrl &tvc = GetTreeCtrl();

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
	case ITEM_INBOX:
		tvc.SetItemText(pItem->m_hItem, ((CItemFolder *)pItem)->m_FolderRec.folderName);
		break;
	case ITEM_DATA:
		tvc.SetItemText(pItem->m_hItem, DataItemText((CItemData *)pItem));
		break;
	case ITEM_FIND:
		tvc.SetItemText(pItem->m_hItem, ((CItemFind *)pItem)->m_FindRec.folderName);
		if (((CItemFind *)pItem)->m_nFindType == FIND_TYPE_FOLDER)
			tvc.SetItemImage(pItem->m_hItem, IMAGE_FFOLDER, IMAGE_FFOLDER_OPEN);
		else
			tvc.SetItemImage(pItem->m_hItem, IMAGE_FDATA, IMAGE_FDATA_OPEN);
		break;
	}

	CItem *pItemParent;
	if ((pItemParent = pItem->GetParent()) != NULL)
		SortItem(pItemParent);
}

void CMMLibTreeView::OnRclick(NMHDR* pNMHDR, LRESULT* pResult) 
{
	CTreeCtrl &tvc = GetTreeCtrl();
	POINT posScreen, posClient;
	UINT flag;
	HTREEITEM hItem;
	UINT nMenuID;

	::GetCursorPos(&posScreen);

	posClient = posScreen;
	ScreenToClient(&posClient);

	if ((hItem = tvc.HitTest(posClient, &flag)) != NULL) {
		CItem *pItem = (CItem *)tvc.GetItemData(hItem);
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
		default:
			return;
		}

		tvc.SelectItem(hItem);

		CMenu menu;
		menu.LoadMenu(nMenuID);
		CMenu* pPopup = menu.GetSubMenu(0);
		pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, posScreen.x, posScreen.y, AfxGetMainWnd());
	}

	*pResult = 0;
}

void CMMLibTreeView::OnBeginlabeledit(NMHDR* pNMHDR, LRESULT* pResult) 
{
	TV_DISPINFO* pTVDispInfo = (TV_DISPINFO*)pNMHDR;

	CItem *pItem = (CItem *)GetTreeCtrl().GetItemData(pTVDispInfo->item.hItem);

	if ((m_nTextMode == TEXTMODE_TITLE_ONLY || pItem->m_nItemType != ITEM_DATA) &&
			pItem->m_nItemType != ITEM_ROOT && pItem->m_nItemType != ITEM_PENDING)
		*pResult = 0;
	else
		*pResult = 1;
}

void CMMLibTreeView::OnEndlabeledit(NMHDR* pNMHDR, LRESULT* pResult) 
{
	TV_DISPINFO* pTVDispInfo = (TV_DISPINFO*)pNMHDR;

	if (pTVDispInfo->item.pszText != NULL) {
		CMMLibDoc *pDoc = (CMMLibDoc *)GetDocument();
		pDoc->NameChange((CItem *)GetTreeCtrl().GetItemData(pTVDispInfo->item.hItem), pTVDispInfo->item.pszText);
	}

	*pResult = 0;
}

void CMMLibTreeView::OnBegindrag(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_TREEVIEW* pNMTreeView = (NM_TREEVIEW*)pNMHDR;
	CTreeCtrl& tvc = GetTreeCtrl();
	HGLOBAL hMem;

	CItem *pItem = (CItem *)tvc.GetItemData(pNMTreeView->itemNew.hItem);
	if (pItem == NULL || pItem->m_nItemType != ITEM_FOLDER && pItem->m_nItemType != ITEM_DATA) {
		*pResult = 1;
		return;
	}

	tvc.SelectItem(pNMTreeView->itemNew.hItem);

	pItem->m_pNextSelected = NULL;

	hMem = ::GlobalAlloc(GMEM_DDESHARE, sizeof(CItem *));
	CItem **ppItem = (CItem **)::GlobalLock(hMem);
	*ppItem = pItem;
	::GlobalUnlock(hMem);

	CMyOleDataSource* pods = new CMyOleDataSource;
	pods->m_pItem = pItem;

	pods->CacheGlobalData(g_nCFMMLibrary, hMem);
	pods->DelayRenderData(CF_HDROP);
	pods->DoDragDrop(DROPEFFECT_COPY | DROPEFFECT_MOVE);

	delete pods;

	tvc.SelectDropTarget(NULL);

	*pResult = 0;
}

DROPEFFECT CMMLibTreeView::OnDragOver(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point) 
{
	if (pDataObject->IsDataAvailable(g_nCFMMLibrary))
		return DragOverItem(pDataObject, dwKeyState, point);
	else if (pDataObject->IsDataAvailable(CF_HDROP))
		return DragOverFile(pDataObject, dwKeyState, point);
	else
		return DROPEFFECT_NONE;
}

DROPEFFECT CMMLibTreeView::DragOverItem(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point)
{
	CTreeCtrl& tvc = GetTreeCtrl();
	CItem *pItemSrc, *pItemDst, *pItemParent;
	CMMPcsDB *pPcsDBSrc, *pPcsDBDst;

	if ((pItemDst = GetTargetItem(point)) == NULL)
		return DROPEFFECT_NONE;

	if (pItemDst->m_nItemType != ITEM_FOLDER && pItemDst->m_nItemType != ITEM_INBOX)
		return DROPEFFECT_NONE;

	if ((pItemSrc = GetSourceItem(pDataObject)) == NULL)
		return DROPEFFECT_NONE;

	if (pItemSrc == pItemDst)
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

	tvc.SelectDropTarget(pItemDst->m_hItem);

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

DROPEFFECT CMMLibTreeView::DragOverFile(COleDataObject* pDataObject, DWORD dwKeyState, CPoint point)
{
	CTreeCtrl& tvc = GetTreeCtrl();
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

	tvc.SelectDropTarget(pItemDst->m_hItem);

	return DROPEFFECT_COPY;
}

BOOL CMMLibTreeView::OnDrop(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point) 
{
	GetTreeCtrl().SelectDropTarget(NULL);

	if (pDataObject->IsDataAvailable(g_nCFMMLibrary))
		return DropItem(pDataObject, dropEffect, point);
	else if (pDataObject->IsDataAvailable(CF_HDROP))
		return DropFile(pDataObject, dropEffect, point);
	else
		return FALSE;
}

BOOL CMMLibTreeView::DropItem(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point)
{
	CItem *pItemSrc, *pItemDst;
	
	if ((pItemDst = GetTargetItem(point)) == NULL)
		return FALSE;

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

BOOL CMMLibTreeView::DropFile(COleDataObject* pDataObject, DROPEFFECT dropEffect, CPoint point)
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

CItem *CMMLibTreeView::GetTargetItem(CPoint point)
{
	CTreeCtrl& tvc = GetTreeCtrl();
	UINT flags;

	HTREEITEM hItem = tvc.HitTest(point, &flags);
	if (hItem == NULL)
		return NULL;

	return (CItem *)tvc.GetItemData(hItem);
}

CItem *CMMLibTreeView::GetSourceItem(COleDataObject* pDataObject)
{
	HGLOBAL hGlobal;

	if ((hGlobal = pDataObject->GetGlobalData(g_nCFMMLibrary, NULL)) == NULL)
		return NULL;

	CItem *pItem = *(CItem **)::GlobalLock(hGlobal);
	::GlobalUnlock(hGlobal);
	::GlobalFree(hGlobal);

	return pItem;
}

int CMMLibTreeView::GetSourceFileType(COleDataObject* pDataObject)
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

void CMMLibTreeView::ReloadAllItems()
{
	CTreeCtrl& tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CItem *pItem;
	FOLDERREC folderRec;

	for (hItem = tvc.GetChildItem(tvc.GetRootItem()); hItem != NULL; hItem = tvc.GetNextSiblingItem(hItem)) {
		pItem = (CItem *)tvc.GetItemData(hItem);
		if (pItem->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItem)->m_pPcsDB->ReadLibraryEx(&folderRec)) {
				tvc.SetItemText(pItem->m_hItem, folderRec.folderName);
				ReloadItem((CItemFolder *)pItem, TRUE);
			} else {
				CMMPcsDB *pPcsDB = ((CItemFolder *)pItem)->m_pPcsDB;
				RemoveItem(pItem);
				g_pMMLibDB->CloseMMLibrary(pPcsDB);
			}
		}
	}
}

void CMMLibTreeView::ReloadItem(CItemFolder *pItemFolder, BOOL bSubitem)
{
	CWaitCursor waitCursor;
	CTreeCtrl& tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CItem *pItemChild;
	CItemFolder *pItemChildFolder;
	CItemData *pItemData;
	BOOL bFirst;
	FOLDERREC folderRec;
	DATAREC dataRec;
	BOOL bHasChild = FALSE;

	if (!pItemFolder->m_bReadChildren)
		return;

	for (hItem = tvc.GetChildItem(pItemFolder->m_hItem); hItem != NULL; hItem = tvc.GetNextSiblingItem(hItem)) {
		pItemChild = (CItem *)tvc.GetItemData(hItem);
		pItemChild->m_bCheck = (pItemChild->m_nItemType == ITEM_FIND) ? TRUE : FALSE;
	}

	LPCTSTR pFolderID = pItemFolder->m_FolderRec.folderID;
	CMMPcsDB *pPcsDB = pItemFolder->m_pPcsDB;

	switch (pItemFolder->m_nFolderType) {
	case FTYPE_LIBRARY:
	case FTYPE_GFOLDER:
		bFirst = TRUE;
		while (pPcsDB->ReadChildFolderEx(pFolderID, &folderRec, bFirst)) {
			if ((pItemChildFolder = FindChildFolder(pItemFolder, folderRec.folderID)) != NULL) {
				pItemChildFolder->m_FolderRec = folderRec;
				tvc.SetItemText(pItemChildFolder->m_hItem, folderRec.folderName);
				pItemChildFolder->m_bCheck = TRUE;
			} else
				pItemChildFolder = AddFolder(pItemFolder, &folderRec);

			pItemChildFolder->m_bCheck = TRUE;
			bFirst = FALSE;
			bHasChild = TRUE;
		}
		break;
	case FTYPE_DFOLDER:
		bFirst = TRUE;
		while (pPcsDB->ReadChildDataEx(pFolderID, &dataRec, bFirst)) {
			if ((pItemData = FindChildData(pItemFolder, dataRec.dataID)) != NULL) {
				pItemData->m_DataRec = dataRec;
				tvc.SetItemText(pItemData->m_hItem, dataRec.title);
				pItemData->m_bCheck = TRUE;
			} else
				pItemData = AddData(pItemFolder, &dataRec);

			pItemData->m_bCheck = TRUE;
			bFirst = FALSE;
			bHasChild = TRUE;
		}
		break;
	}

	SetChildren(pItemFolder->m_hItem, bHasChild);

	for (hItem = tvc.GetChildItem(pItemFolder->m_hItem); hItem != NULL; ) {
		pItemChild = (CItem *)tvc.GetItemData(hItem);
		hItem = tvc.GetNextSiblingItem(hItem);

		if (pItemChild->m_bCheck) {
			switch (pItemChild->m_nItemType) {
			case ITEM_FOLDER:
				if (bSubitem)
					ReloadItem((CItemFolder *)pItemChild, bSubitem);
				break;
			case ITEM_DATA:
				if (((CItemData *)pItemChild)->m_hDIB != NULL) {
					MMPictureFree(((CItemData *)pItemChild)->m_hDIB);
					((CItemData *)pItemChild)->m_hDIB = NULL;
				}
				break;
			case ITEM_FIND:
				ReFindItem((CItemFind *)pItemChild);
				break;
			}
		} else
			RemoveItem(pItemChild);
	}

	SortItem(pItemFolder);
}

CItemFolder *CMMLibTreeView::FindChildFolder(CItem *pItem, LPCTSTR pFolderID)
{
	CTreeCtrl& tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CItem *pItemChild;

	for (hItem = tvc.GetChildItem(pItem->m_hItem); hItem != NULL; hItem = tvc.GetNextSiblingItem(hItem)) {
		pItemChild = (CItem *)tvc.GetItemData(hItem);
		if (pItemChild->m_nItemType == ITEM_FOLDER) {
			if (((CItemFolder *)pItemChild)->m_FolderRec.folderID == pFolderID)
				return (CItemFolder *)pItemChild;
		}
	}

	return NULL;
}

CItemData *CMMLibTreeView::FindChildData(CItem *pItem, LPCTSTR pDataID)
{
	CTreeCtrl& tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CItem *pItemChild;

	for (hItem = tvc.GetChildItem(pItem->m_hItem); hItem != NULL; hItem = tvc.GetNextSiblingItem(hItem)) {
		pItemChild = (CItem *)tvc.GetItemData(hItem);
		if (pItemChild->m_nItemType == ITEM_DATA) {
			if (((CItemData *)pItemChild)->m_DataRec.dataID == pDataID)
				return (CItemData *)pItemChild;
		}
	}

	return NULL;
}

void CMMLibTreeView::SortItem(CItem *pItem)
{
	TVSORTCB tvSort;

	tvSort.hParent = pItem->m_hItem;
	tvSort.lpfnCompare = CompareFunc;
	tvSort.lParam = (LPARAM)this;

	GetTreeCtrl().SortChildrenCB(&tvSort);
}

int CALLBACK CMMLibTreeView::CompareFunc(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort)
{
	CMMLibTreeView *pView = (CMMLibTreeView *)lParamSort;
	CTreeCtrl &tvc = pView->GetTreeCtrl();
	CItem *pItem1 = (CItem *)lParam1;
	CItem *pItem2 = (CItem *)lParam2;

	if (pItem1->m_nItemType == ITEM_INBOX)
		return -1;

	if (pItem2->m_nItemType == ITEM_INBOX)
		return 1;

	if (pItem1->m_nItemType == ITEM_FOLDER && pItem2->m_nItemType == ITEM_FOLDER)
		return pView->CompareFolder((CItemFolder *)pItem1, (CItemFolder *)pItem2);

	if (pItem1->m_nItemType == ITEM_DATA && pItem2->m_nItemType == ITEM_DATA)
		return pView->CompareData((CItemData *)pItem1, (CItemData *)pItem2);

	if (pItem1->m_nItemType == ITEM_FIND) {
		if (pItem2->m_nItemType == ITEM_FIND)
			return CompareTitle(tvc.GetItemText(pItem1->m_hItem), tvc.GetItemText(pItem2->m_hItem));
		else
			return 1;
	} else {
		if (pItem2->m_nItemType != ITEM_FIND)
			return CompareTitle(tvc.GetItemText(pItem1->m_hItem), tvc.GetItemText(pItem2->m_hItem));
		else
			return -1;
	}
}

#define N_ITEM_ORDER	7

int CMMLibTreeView::CompareFolder(CItemFolder *pItemFolder1, CItemFolder *pItemFolder2)
{
	int i;
	int rc;

	for (i = 0; i < N_ITEM_ORDER; i++) {
		switch (m_Order[i]) {
		case '0':	// ����
			rc = CompareTitle(pItemFolder1->m_FolderRec.folderName, pItemFolder2->m_FolderRec.folderName);
			if (rc != 0)
				return rc;
			break;
		case '1':	// �R�[�h
			break;
		case '2':	// �쐬����
			rc = pItemFolder1->m_FolderRec.createDate.CompareNoCase(pItemFolder2->m_FolderRec.createDate);
			if (rc != 0)
				return rc;
			break;
		case '3':	// �X�V����
			rc = pItemFolder1->m_FolderRec.updateDate.CompareNoCase(pItemFolder2->m_FolderRec.updateDate);
			if (rc != 0)
				return rc;
			break;
		case '4':	// �쐬��
			rc = pItemFolder1->m_FolderRec.creator.CompareNoCase(pItemFolder2->m_FolderRec.creator);
			if (rc != 0)
				return rc;
			break;
		case '5':	// ���l
			rc = pItemFolder1->m_FolderRec.remark.CompareNoCase(pItemFolder2->m_FolderRec.remark);
			if (rc != 0)
				return rc;
			break;
		case '6':	// �L�[���[�h
			rc = pItemFolder1->m_FolderRec.keyword.CompareNoCase(pItemFolder2->m_FolderRec.keyword);
			if (rc != 0)
				return rc;
			break;
		}
	}

	return 0;
}

int CMMLibTreeView::CompareData(CItemData *pItemData1, CItemData *pItemData2)
{
	int i;
	int rc;

	for (i = 0; i < N_ITEM_ORDER; i++) {
		switch (m_Order[i]) {
		case '0':	// ����
			rc = CompareTitle(pItemData1->m_DataRec.title, pItemData2->m_DataRec.title);
			if (rc != 0)
				return rc;
			break;
		case '1':	// �R�[�h
			rc = pItemData1->m_DataRec.code.CompareNoCase(pItemData2->m_DataRec.code);
			if (rc != 0)
				return rc;
			break;
		case '2':	// �쐬����
			rc = pItemData1->m_DataRec.createDate.CompareNoCase(pItemData2->m_DataRec.createDate);
			if (rc != 0)
				return rc;
			break;
		case '3':	// �X�V����
			rc = pItemData1->m_DataRec.updateDate.CompareNoCase(pItemData2->m_DataRec.updateDate);
			if (rc != 0)
				return rc;
			break;
		case '4':	// �쐬��
			rc = pItemData1->m_DataRec.creator.CompareNoCase(pItemData2->m_DataRec.creator);
			if (rc != 0)
				return rc;
			break;
		case '5':	// ���l
			rc = pItemData1->m_DataRec.remark.CompareNoCase(pItemData2->m_DataRec.remark);
			if (rc != 0)
				return rc;
			break;
		case '6':	// �L�[���[�h
			rc = pItemData1->m_DataRec.keyword.CompareNoCase(pItemData2->m_DataRec.keyword);
			if (rc != 0)
				return rc;
			break;
		}
	}

	return 0;
}

LRESULT CMMLibTreeView::OnGetSelectedItem(WPARAM wParam, LPARAM lParam)
{
	CTreeCtrl& tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CItem *pItem = NULL;

	if ((hItem = tvc.GetSelectedItem()) != NULL) {
		pItem = (CItem *)tvc.GetItemData(hItem);
		pItem->m_pNextSelected = NULL;
	}

	return (LRESULT)pItem;
}

BOOL CMMLibTreeView::OnPreparePrinting(CPrintInfo* pInfo) 
{
	CTreeCtrl& tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CItem *pItem;
	int count;

	if ((hItem = tvc.GetSelectedItem()) == NULL)
		return FALSE;

	pItem = (CItem *)tvc.GetItemData(hItem);

	switch (pItem->m_nItemType) {
	case ITEM_DATA:
		pInfo->SetMaxPage(1);
		break;
	case ITEM_FOLDER:
		count = 0;
		if (((CItemFolder *)pItem)->m_nFolderType == FIND_TYPE_DATA) {
			for (hItem = tvc.GetChildItem(pItem->m_hItem); hItem != NULL; hItem = g_pTreeCtrl->GetNextSiblingItem(hItem))
				count++;
			pInfo->SetMaxPage(count);
		} else {
			AfxMessageBox(IDS_PRINT_FOLDER);
			return FALSE;
		}
		break;
	case ITEM_FIND:
		count = 0;
		for (hItem = tvc.GetChildItem(pItem->m_hItem); hItem != NULL; hItem = g_pTreeCtrl->GetNextSiblingItem(hItem))
			count++;
		pInfo->SetMaxPage(count);
		break;
	default:
		AfxMessageBox(IDS_PRINT_FOLDER);
		return FALSE;
	}

	return DoPreparePrinting(pInfo);
}

void CMMLibTreeView::OnPrint(CDC* pDC, CPrintInfo* pInfo) 
{
	CTreeCtrl& tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CItem *pItem;
	CItemFolder *pItemFolder;
	CItemData *pItemData = NULL;
	HDIB hDIB;

	if ((hItem = tvc.GetSelectedItem()) == NULL)
		return;

	pItem = (CItem *)tvc.GetItemData(hItem);

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
		pItemFolder = (CItemFolder *)pItem;
		if (pItemFolder->m_nFolderType == FTYPE_DFOLDER) {
			hItem = tvc.GetChildItem(pItem->m_hItem);
			for (UINT i = 1; i < pInfo->m_nCurPage; i++) {
				hItem = g_pTreeCtrl->GetNextSiblingItem(hItem);
				if (hItem == NULL)
					return;
			}
		}
		if (hItem != NULL)
			pItemData = (CItemData *)tvc.GetItemData(hItem);
		break;
	case ITEM_FIND:
		hItem = tvc.GetChildItem(pItem->m_hItem);
		for (UINT i = 1; i < pInfo->m_nCurPage; i++) {
			hItem = g_pTreeCtrl->GetNextSiblingItem(hItem);
			if (hItem == NULL)
				return;
		}
		if (hItem != NULL)
			pItemData = (CItemData *)tvc.GetItemData(hItem);
		break;
	case ITEM_DATA:
		pItemData = (CItemData *)pItem;
		break;
	}

	if (pItemData != NULL) {
		pItemData->m_pPcsDB->ReadPictureDataEx(pItemData->m_DataRec.dataID, &hDIB, NULL);
		PrintPicture(pDC, hDIB);
		MMPictureFree(hDIB);
	}
}

void CMMLibTreeView::SetViewFont()
{
	m_Font.DeleteObject();
	m_Font.CreatePointFont(g_nViewFontSize, g_ViewFontName);
	SetFont(&m_Font);
}

void CMMLibTreeView::SortAllItems()
{
	CItem *pItem;

	m_Order = AfxGetApp()->GetProfileString(g_SectionOptions, "ViewOrder", "0123456");

	for (pItem = CItem::GetHeadItem(); pItem != NULL; pItem = CItem::GetNextItem())
		SortItem(pItem);
}

void CMMLibTreeView::SetTextDataItems()
{
	CItem *pItem;

	m_nTextMode = AfxGetApp()->GetProfileInt(g_SectionOptions, "ViewText", 0);

	for (pItem = CItem::GetHeadItem(); pItem != NULL; pItem = CItem::GetNextItem()) {
		if (pItem->m_nItemType == ITEM_DATA)
			GetTreeCtrl().SetItemText(pItem->m_hItem, DataItemText((CItemData *)pItem));
	}
}

LPCTSTR CMMLibTreeView::DataItemText(CItemData *pItemData)
{
	static CString text;

	if (m_nTextMode == TEXTMODE_TITLE_ONLY || pItemData->m_DataRec.code.IsEmpty())
		return pItemData->m_DataRec.title;
	else {
		text.Format("%s (%s)", (LPCTSTR)pItemData->m_DataRec.title, (LPCTSTR)pItemData->m_DataRec.code);
		return text;
	}
}

BOOL CMMLibTreeView::OnScroll(UINT nScrollCode, UINT nPos, BOOL bDoScroll) 
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

void CMMLibTreeView::SelectData(CSelectItem *pSelectItem)
{
	if (!SelectDataSub(pSelectItem))
		AfxMessageBox(IDS_DATA_NOT_FOUND);
}

BOOL CMMLibTreeView::SelectDataSub(CSelectItem *pSelectItem)
{
	HTREEITEM hItem;
	CItem *pItem;
	LPCTSTR pLibraryID;
	CMMPcsDB *pPcsDB;
	int i;
	BOOL bOK = FALSE;
	CTreeCtrl &tc = GetTreeCtrl();

	hItem = g_pTreeCtrl->GetChildItem(g_pTreeCtrl->GetRootItem());
	for (i = 0; hItem != NULL; i++) {
		pItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);
		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
		case ITEM_INBOX:
			pLibraryID = ((CItemFolder *)pItem)->m_pPcsDB->m_LibraryID;
			break;
		case ITEM_PENDING:
			pLibraryID = ((CItemPending *)pItem)->m_LibraryID;
			break;
		default:
			pLibraryID = "";
			break;
		}

		if (pSelectItem->m_sLibraryID == pLibraryID) {
			if (pItem->m_nItemType == ITEM_PENDING) {
				OpenLibrary((CItemPending *)pItem);
				pItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);
			}

			if (pItem->m_nItemType == ITEM_FOLDER || pItem->m_nItemType == ITEM_INBOX) {
				pPcsDB = ((CItemFolder *)pItem)->m_pPcsDB;
				if (!pSelectItem->m_sDataID.IsEmpty()) {
					DATAREC dataRec;
					if (!pPcsDB->ReadDataEx(pSelectItem->m_sDataID, &dataRec))
						return FALSE;
					if ((hItem = SelectFolder(hItem, ((CItemFolder *)pItem)->m_pPcsDB, dataRec.folderID, 0)) == NULL)
						return FALSE;
					tc.Expand(hItem, TVE_EXPAND);
					hItem = tc.GetChildItem(hItem);
					while (hItem != NULL) {
						pItem = (CItem *)tc.GetItemData(hItem);
						if (pItem->m_nItemType == ITEM_DATA) {
							if (((CItemData *)pItem)->m_DataRec.dataID == pSelectItem->m_sDataID)
								break;
						}
						hItem = tc.GetNextSiblingItem(hItem);
					}
					if (hItem == NULL)
						return FALSE;
				} else {
					if ((hItem = SelectFolder(hItem, ((CItemFolder *)pItem)->m_pPcsDB, pSelectItem->m_sFolderID, 0)) == NULL)
						return FALSE;
				}

				GetDocument()->UpdateAllViews(NULL, VIEWHINT_SELECTITEM, (CItem *)tc.GetItemData(hItem));

				bOK = TRUE;
			}
			break;
		}

		hItem = g_pTreeCtrl->GetNextSiblingItem(hItem);
	}

	return bOK;
}

HTREEITEM CMMLibTreeView::SelectFolder(HTREEITEM hItemLibrary, CMMPcsDB *pPcsDB, CString &folderID, int count)
{
	HTREEITEM hItem;
	CItem *pItem;
	FOLDERREC folderRec;
	CTreeCtrl &tc = GetTreeCtrl();

	if (folderID == FID_LIBRARY)
		return hItemLibrary;

	if (count > 20)
		return NULL;

	if (!pPcsDB->ReadFolderEx(folderID, &folderRec))
		return NULL;

	if ((hItem = SelectFolder(hItemLibrary, pPcsDB, folderRec.parentFolderID, count + 1)) == NULL)
		return NULL;

	tc.Expand(hItem, TVE_EXPAND);

	hItem = tc.GetChildItem(hItem);
	while (hItem != NULL) {
		pItem = (CItem *)tc.GetItemData(hItem);
		if (pItem->m_nItemType == ITEM_FOLDER || pItem->m_nItemType == ITEM_INBOX) {
			if (((CItemFolder *)pItem)->m_FolderRec.folderID == folderID)
				return hItem;
		}
		hItem = tc.GetNextSiblingItem(hItem);
	}

	return NULL;
}

void CMMLibTreeView::OnUpdateFilePrint(CCmdUI *pCmdUI)
{
	CTreeCtrl& tvc = GetTreeCtrl();
	HTREEITEM hItem;
	CItem *pItem;
	BOOL bEnable = FALSE;

	if ((hItem = tvc.GetSelectedItem()) != NULL) {
		pItem = (CItem *)tvc.GetItemData(hItem);

		switch (pItem->m_nItemType) {
		case ITEM_DATA:
		case ITEM_FIND:
			bEnable = TRUE;
			break;
		case ITEM_FOLDER:
			if (((CItemFolder *)pItem)->m_nFolderType == FTYPE_DFOLDER)
				bEnable = TRUE;
			break;
		}
	}

	pCmdUI->Enable(bEnable);
}

void CMMLibTreeView::OnUpdateFilePrintPreview(CCmdUI *pCmdUI)
{
	OnUpdateFilePrint(pCmdUI);
}
