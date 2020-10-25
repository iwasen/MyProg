// LeftView.cpp : CLeftView �N���X�̓���̒�`���s���܂��B
//

#include "stdafx.h"
#include "Sas.h"

#include "SasDoc.h"
#include "LeftView.h"

// CTreeCtrl�̃o�O�΍�
class CTreeCtrlEx : public CTreeCtrl
{
public:
	HTREEITEM InsertItem(UINT nMask, LPCTSTR lpszItem, int nImage,
		int nSelectedImage, UINT nState, UINT nStateMask, LPARAM lParam,
		HTREEITEM hParent, HTREEITEM hInsertAfter)
	{
		TVINSERTSTRUCT tvis;
		tvis.hParent = hParent;
		tvis.hInsertAfter = hInsertAfter;
		tvis.item.mask = nMask;
		tvis.item.pszText = (LPTSTR) lpszItem;
		tvis.item.iImage = nImage;
		tvis.item.iSelectedImage = nSelectedImage;
		tvis.item.state = nState;
		tvis.item.stateMask = nStateMask;
		tvis.item.lParam = lParam;
		tvis.item.cChildren = 1;
		return (HTREEITEM)::SendMessage(m_hWnd, TVM_INSERTITEM, 0, (LPARAM)&tvis);
	}
};

/////////////////////////////////////////////////////////////////////////////
// CLeftView

IMPLEMENT_DYNCREATE(CLeftView, CTreeView)

BEGIN_MESSAGE_MAP(CLeftView, CTreeView)
	ON_NOTIFY_REFLECT(TVN_SELCHANGED, OnSelchanged)
	ON_COMMAND(ID_FILE_OPEN, OnFileOpen)
	ON_UPDATE_COMMAND_UI(ID_FILE_OPEN, OnUpdateFileOpen)
	ON_COMMAND(IDM_FILE_DELETE, &CLeftView::OnFileDelete)
	ON_UPDATE_COMMAND_UI(IDM_FILE_DELETE, &CLeftView::OnUpdateFileDelete)
	ON_COMMAND(IDM_FILE_CHANGE_TITLE, &CLeftView::OnFileChangeTitle)
	ON_UPDATE_COMMAND_UI(IDM_FILE_CHANGE_TITLE, &CLeftView::OnUpdateFileChangeTitle)
	ON_NOTIFY_REFLECT(TVN_BEGINLABELEDIT, &CLeftView::OnTvnBeginlabeledit)
	ON_NOTIFY_REFLECT(TVN_ENDLABELEDIT, &CLeftView::OnTvnEndlabeledit)
	ON_NOTIFY_REFLECT(NM_RCLICK, &CLeftView::OnNMRclick)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLeftView �N���X�̍\�z/����

CLeftView::CLeftView()
{
	// TODO: ���̏ꏊ�ɍ\�z�p�̃R�[�h��ǉ����Ă��������B

}

BOOL CLeftView::PreCreateWindow(CREATESTRUCT& cs)
{
	cs.style |= TVS_HASLINES | TVS_HASBUTTONS | TVS_SHOWSELALWAYS;

	return CTreeView::PreCreateWindow(cs);
}

/////////////////////////////////////////////////////////////////////////////
// CLeftView �N���X�̕`��

void CLeftView::OnDraw(CDC* /*pDC*/)
{
	CSasDoc* pDoc = GetDocument();
	ASSERT_VALID(pDoc);

	// TODO: ���̏ꏊ�Ƀl�C�e�B�u �f�[�^�p�̕`��R�[�h��ǉ����܂��B
}


void CLeftView::OnInitialUpdate()
{
	CTreeView::OnInitialUpdate();

	CTreeCtrl &treeCtrl = GetTreeCtrl();
	treeCtrl.SetImageList(&imageList, TVSIL_NORMAL);

	SetTreeItem();
}

/////////////////////////////////////////////////////////////////////////////
// CLeftView �N���X�̐f�f

#ifdef _DEBUG
void CLeftView::AssertValid() const
{
	CTreeView::AssertValid();
}

void CLeftView::Dump(CDumpContext& dc) const
{
	CTreeView::Dump(dc);
}

CSasDoc* CLeftView::GetDocument() // ��f�o�b�O �o�[�W�����̓C�����C���ł��B
{
	ASSERT(m_pDocument->IsKindOf(RUNTIME_CLASS(CSasDoc)));
	return (CSasDoc*)m_pDocument;
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CLeftView �N���X�̃��b�Z�[�W �n���h��

void CLeftView::SetTreeItem()
{
	CTreeCtrlEx &treeCtrl = (CTreeCtrlEx &)GetTreeCtrl();
	HTREEITEM hTreeRoot, hTreeIR, hTreeACF, hTreeNMS;
	CDbFolder dbFolder;
	DbFolderRec dbFolderRec;
	CString str;
	int i;

	treeCtrl.DeleteAllItems();

	str.LoadString(IDS_TYPE_DB);
	hTreeRoot = treeCtrl.InsertItem(TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT,
			str, IMAGE_SYSTEM, IMAGE_SYSTEM, TVIS_EXPANDED, TVIS_EXPANDED,
			ID_SYSTEM, TVI_ROOT, TVI_LAST);

	hTreeIR = NULL;
	hTreeACF = NULL;
	hTreeNMS = NULL;

	if (!dbFolder.Open())
		return;

	for (i = 0; ; i++) {
		if (!dbFolder.ReadRecNext(&dbFolderRec))
			break;

		switch (dbFolderRec.sType[0]) {
		case FOLDER_TYPE_IR:
			if (hTreeIR == NULL) {
				str.LoadString(IDS_TYPE_IR);
				hTreeIR = treeCtrl.InsertItem(
						TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT | TVIF_CHILDREN,
						str, IMAGE_DB_IR, IMAGE_DB_IR, 0, 0,
						ID_DB_IR, hTreeRoot, TVI_FIRST);
			}
			treeCtrl.InsertItem(
					TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_TEXT,
					dbFolderRec.sTitle, IMAGE_FOLDER_IR, IMAGE_FOLDER2_IR, 0, 0,
					ID_FOLDER | dbFolderRec.nFolderID, hTreeIR, TVI_LAST);
			break;
		case FOLDER_TYPE_ACF:
			if (hTreeACF == NULL) {
				str.LoadString(IDS_TYPE_ACF);
				hTreeACF = treeCtrl.InsertItem(
						TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT | TVIF_CHILDREN,
						str, IMAGE_DB_ACF, IMAGE_DB_ACF, 0, 0,
						ID_DB_ACF, hTreeRoot, hTreeIR ? hTreeIR : TVI_LAST);
			}
			treeCtrl.InsertItem(
					TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_TEXT,
					dbFolderRec.sTitle, IMAGE_FOLDER_ACF, IMAGE_FOLDER2_ACF, 0, 0,
					ID_FOLDER | dbFolderRec.nFolderID, hTreeACF, TVI_LAST);
			break;
		case FOLDER_TYPE_NMS:
			if (hTreeNMS == NULL) {
				str.LoadString(IDS_TYPE_NMS);
				hTreeNMS = treeCtrl.InsertItem(
						TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_STATE | TVIF_TEXT | TVIF_CHILDREN,
						str, IMAGE_DB_NMS, IMAGE_DB_NMS, 0, 0,
						ID_DB_NMS, hTreeRoot, hTreeACF ? hTreeACF : (hTreeACF ? hTreeACF : TVI_LAST));
			}
			treeCtrl.InsertItem(
					TVIF_HANDLE | TVIF_IMAGE | TVIF_PARAM | TVIF_SELECTEDIMAGE | TVIF_TEXT,
					dbFolderRec.sTitle, IMAGE_FOLDER_NMS, IMAGE_FOLDER2_NMS, 0, 0,
					ID_FOLDER | dbFolderRec.nFolderID, hTreeNMS, TVI_LAST);
			break;
		}
	}

	if (hTreeIR)
		treeCtrl.SortChildren(hTreeIR);

	if (hTreeACF)
		treeCtrl.SortChildren(hTreeACF);

	if (hTreeNMS)
		treeCtrl.SortChildren(hTreeNMS);
}

void CLeftView::OnSelchanged(NMHDR* pNMHDR, LRESULT* pResult)
{
	NM_TREEVIEW* pNMTreeView = (NM_TREEVIEW*)pNMHDR;
	HTREEITEM hItem = pNMTreeView->itemNew.hItem;
	CTreeCtrl &tc = GetTreeCtrl();
	CSasDoc *pDoc = GetDocument();
	DWORD nItemData = (DWORD)tc.GetItemData(hItem);

	switch (nItemData & ID_KIND) {
	case ID_SYSTEM:
		pDoc->SelectSystem();
		break;
	case ID_FOLDER:
		pDoc->SelectFolder((int)tc.GetItemData(hItem) & ID_VALUE);
		break;
	case ID_DB_IR:
		pDoc->SelectDB(FOLDER_TYPE_IR);
		break;
	case ID_DB_ACF:
		pDoc->SelectDB(FOLDER_TYPE_ACF);
		break;
	case ID_DB_NMS:
		pDoc->SelectDB(FOLDER_TYPE_NMS);
		break;
	}
	*pResult = 0;
}

void CLeftView::OnUpdate(CView* /*pSender*/, LPARAM lHint, CObject* pHint)
{
	CTreeCtrl &tc = GetTreeCtrl();

	if (pHint == PHINT_DELETE) {
		DeleteItem((long)lHint);
		return;
	} else if (pHint != NULL) {
		ChangeTitle((long)lHint, tc.GetRootItem(), (LPCTSTR)pHint);
	} else {
		switch (lHint & ID_KIND) {
		case ID_SYSTEM:
			SetTreeItem();
			break;
		case ID_FOLDER:
			SelectItem(tc.GetRootItem(), (DWORD)lHint);
			break;
		}
	}
}

void CLeftView::SelectItem(HTREEITEM hItem, DWORD dwItemData)
{
	CTreeCtrl &tc = GetTreeCtrl();

	for (hItem = tc.GetChildItem(hItem); hItem != NULL; hItem = tc.GetNextSiblingItem(hItem)) {
		if (tc.GetItemData(hItem) == dwItemData)
			tc.SelectItem(hItem);
		SelectItem(hItem, dwItemData);
	}
}

void CLeftView::OnFileOpen()
{
	CTreeCtrl &tc = GetTreeCtrl();
	HTREEITEM hItem;

	if ((hItem = tc.GetSelectedItem()) != NULL)
		tc.Expand(hItem, TVE_TOGGLE);
}

void CLeftView::OnUpdateFileOpen(CCmdUI* pCmdUI)
{
	CTreeCtrl &tc = GetTreeCtrl();
	HTREEITEM hItem;
	BOOL bEnable = FALSE;

	if ((hItem = tc.GetSelectedItem()) != NULL) {
		switch ((tc.GetItemData(hItem) & ID_KIND)) {
		case ID_DB_IR:
		case ID_DB_ACF:
		case ID_DB_NMS:
			bEnable = TRUE;
		}
	}

	pCmdUI->Enable(bEnable);
}

void CLeftView::DeleteItem(long nItemID)
{
	CTreeCtrl &tc = GetTreeCtrl();

	DeleteItemSub(nItemID, tc.GetRootItem());
}

BOOL CLeftView::DeleteItemSub(long nItemID, HTREEITEM hTreeItem)
{
	CTreeCtrl &tc = GetTreeCtrl();

	if ((long)tc.GetItemData(hTreeItem) == nItemID) {
		tc.DeleteItem(hTreeItem);
		return TRUE;
	} else {
		HTREEITEM hChildItem = tc.GetChildItem(hTreeItem);
		while (hChildItem != NULL) {
			if (DeleteItemSub(nItemID, hChildItem))
				return TRUE;

			hChildItem = tc.GetNextSiblingItem(hChildItem);
		}
		return FALSE;
	}
}

void CLeftView::OnFileDelete()
{
	CTreeCtrl &tc = GetTreeCtrl();
	CSasDoc *pDoc = GetDocument();

	HTREEITEM hTreeItem = tc.GetSelectedItem();
	if (hTreeItem != NULL) {
		if (AfxMessageBox(IDS_MSG_DELETEFOLDER, MB_OKCANCEL | MB_ICONQUESTION) == IDOK)
			pDoc->DeleteItem((long)tc.GetItemData(hTreeItem));
	}
}

void CLeftView::OnUpdateFileDelete(CCmdUI *pCmdUI)
{
	CTreeCtrl &tc = GetTreeCtrl();

	BOOL bEnable = FALSE;
	HTREEITEM hTreeItem = tc.GetSelectedItem();
	if (hTreeItem != NULL) {
		if (((long)tc.GetItemData(hTreeItem) & ID_KIND) == ID_FOLDER)
			bEnable = TRUE;
	}

	pCmdUI->Enable(bEnable);
}

BOOL CLeftView::ChangeTitle(long nItemID, HTREEITEM hTreeItem, LPCTSTR pTitle)
{
	CTreeCtrl &tc = GetTreeCtrl();

	if ((long)tc.GetItemData(hTreeItem) == nItemID) {
		tc.SetItemText(hTreeItem, pTitle);
		return TRUE;
	} else {
		HTREEITEM hChildItem = tc.GetChildItem(hTreeItem);
		while (hChildItem != NULL) {
			if (ChangeTitle(nItemID, hChildItem, pTitle))
				return TRUE;

			hChildItem = tc.GetNextSiblingItem(hChildItem);
		}
		return FALSE;
	}
}

void CLeftView::OnFileChangeTitle()
{
	CTreeCtrl &tc = GetTreeCtrl();

	HTREEITEM hTreeItem = tc.GetSelectedItem();
	if (hTreeItem != NULL) {
		ModifyStyle(0, TVS_EDITLABELS);
		tc.EditLabel(hTreeItem);
	}
}

void CLeftView::OnUpdateFileChangeTitle(CCmdUI *pCmdUI)
{
	CTreeCtrl &tc = GetTreeCtrl();

	BOOL bEnable = FALSE;
	HTREEITEM hTreeItem = tc.GetSelectedItem();
	if (hTreeItem != NULL) {
		if (((long)tc.GetItemData(hTreeItem) & ID_KIND) == ID_FOLDER)
			bEnable = TRUE;
	}

	pCmdUI->Enable(bEnable);
}

void CLeftView::OnTvnBeginlabeledit(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMTVDISPINFO pTVDispInfo = reinterpret_cast<LPNMTVDISPINFO>(pNMHDR);
	CTreeCtrl &tc = GetTreeCtrl();

	if (((long)tc.GetItemData(pTVDispInfo->item.hItem) & ID_KIND) == ID_FOLDER) {
		m_cEditTitle.SetMaxLength(LEN_TITLE);
		m_cEditTitle.SubclassWindow(tc.GetEditControl()->GetSafeHwnd());

		*pResult = 0;
	} else
		*pResult = 1;
}

void CLeftView::OnTvnEndlabeledit(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMTVDISPINFO pTVDispInfo = reinterpret_cast<LPNMTVDISPINFO>(pNMHDR);

	CTreeCtrl &tc = GetTreeCtrl();
	CSasDoc *pDoc = GetDocument();

	CString sTitle = m_cEditTitle;
	pDoc->ChangeTitle((long)tc.GetItemData(pTVDispInfo->item.hItem), sTitle);

	m_cEditTitle.UnsubclassWindow();

	ModifyStyle(TVS_EDITLABELS, 0);

	*pResult = 0;
}

void CLeftView::OnNMRclick(NMHDR * /*pNMHDR*/, LRESULT *pResult)
{
	CPoint posCursor;
	::GetCursorPos(&posCursor);

	CMenu menu;
	menu.LoadMenu(IDR_LEFTVIEW);
	CMenu* pPopup = menu.GetSubMenu(0);
	pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, posCursor.x, posCursor.y, AfxGetMainWnd());

	*pResult = 0;
}
