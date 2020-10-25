// FindView.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "mmlib.h"
#include "MMLibDoc.h"
#include "FindDoc.h"
#include "FindFrame.h"
#include "FindView.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CFindView

IMPLEMENT_DYNCREATE(CFindView, CListView)

CFindView::CFindView()
{
}

CFindView::~CFindView()
{
}


BEGIN_MESSAGE_MAP(CFindView, CListView)
	ON_WM_CREATE()
	ON_NOTIFY_REFLECT(LVN_COLUMNCLICK, OnColumnclick)
	ON_NOTIFY_REFLECT(LVN_ITEMCHANGED, OnItemchanged)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CFindView 描画

void CFindView::OnDraw(CDC* pDC)
{
	CDocument* pDoc = GetDocument();
	// TODO: この位置に描画用のコードを追加してください
}

/////////////////////////////////////////////////////////////////////////////
// CFindView 診断

#ifdef _DEBUG
void CFindView::AssertValid() const
{
	CListView::AssertValid();
}

void CFindView::Dump(CDumpContext& dc) const
{
	CListView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CFindView メッセージ ハンドラ

int CFindView::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	lpCreateStruct->style |= LVS_REPORT | LVS_SINGLESEL | LVS_SHOWSELALWAYS;
	if (CListView::OnCreate(lpCreateStruct) == -1)
		return -1;

	GetLixxxtrl().SetExtendedStyle(LVS_EX_FULLROWSELECT);
	GetLixxxtrl().SetImageList(&g_ImageList, LVSIL_SMALL);
	InsertColumns();

	return 0;
}

void CFindView::InsertColumns()
{
	struct LIxxxOLUMN {
		UINT text;
		int fmt;
		int width;
	} tColumn[] = {
		{IDS_NAME, LVCFMT_LEFT, 130},
		{IDS_LIBRARY, LVCFMT_LEFT, 100},
		{IDS_CREATOR, LVCFMT_LEFT, 100},
		{IDS_REMARK, LVCFMT_LEFT, 100},
		{IDS_KEYWORD, LVCFMT_LEFT, 100},
		{IDS_CREATE_DATE, LVCFMT_LEFT, 110}
	};
	int i;
	LV_COLUMN lvc;

	for (i = 0; i < sizeof(tColumn) / sizeof(LIxxxOLUMN); i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = tColumn[i].fmt;
		lvc.pszText = (LPTSTR)(LPCTSTR)GetString(tColumn[i].text);
		lvc.cx = tColumn[i].width;
		GetLixxxtrl().InsertColumn(i, &lvc);
	}
}

void CFindView::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint) 
{
	if (lHint == 1) {
		CFindDoc *pDoc = (CFindDoc *)GetDocument();
		CString keywords, filter;

		keywords = pDoc->m_sKeyword;
		keywords.Replace("　", " ");
		keywords.TrimLeft();
		keywords.TrimRight();
		if (!keywords.IsEmpty()) {
			filter = GetFilter(keywords, pDoc->m_nAndOr);

			if (!FindKeyword(filter))
				AfxMessageBox(IDS_FIND_ERROR);
		}
	}
}

CString CFindView::GetFilter(CString &keywords, int nAndOr)
{
	CString filter;
	CString keyword;

	while (!keywords.IsEmpty()) {
		keyword = keywords.SpanExcluding(" ");
		keywords = keywords.Mid(keyword.GetLength());
		keywords.TrimLeft();

		if (!filter.IsEmpty()) {
			if (nAndOr == 0)
				filter += "&";
			else
				filter += "|";
		}
		filter += "*SKEYWORD='" + keyword + "'";
	}

	return filter;
}

BOOL CFindView::FindKeyword(LPCTSTR filter)
{
	CPcserve pcserve;
	CPSDB db;
	CString dir, path;
	int nRead;
	KEYWORDBUF keywordBuf;
	KEYWORDREC keywordRec;
	CLixxxtrl &lc = GetLixxxtrl();
	int nItem = 0;
	int nImage;
	CString str;
	CSelectItem *pSelectItem;
	int nRecSize;

	DeleteAllItems();

	if (pcserve.Conxxxt(CHT_LOCAL, "", "MMLIB") != 0)
		return FALSE;

	::GetKeywordDir(dir);
	path.Format("%s\\%s", dir, FN_KEYWORD_DBF);
	if (db.DBOpen(pcserve, path) != 0) {
		AfxMessageBox(IDS_NO_KEYWORD_INDEX);
		return TRUE;
	}

	if (db.DBRecSize(&nRecSize) != 0)
		return FALSE;

	if (nRecSize - 1 != sizeof(KEYWORDBUF)) {
		AfxMessageBox(IDS_OLD_KEYWORD_INDEX);
		return TRUE;
	}

	CWaitCursor wait;

	if (db.DBSetFilter(filter) != 0)
		return FALSE;

	while (db.DBReadNext(1, &keywordBuf, &nRead) == 0) {
		if (nRead == 0)
			break;

		db.DBGetFieldBuf(&keywordBuf, &keywordRec);

		pSelectItem = new CSelectItem;
		pSelectItem->m_sLibraryID = keywordRec.libraryID;

		if (keywordRec.idType == FIND_TYPE_FOLDER) {
			pSelectItem->m_sFolderID = keywordRec.id;
			if (keywordRec.folderType == FTYPE_GFOLDER)
				nImage = IMAGE_GFOLDER;
			else
				nImage = IMAGE_DFOLDER;
		} else {
			pSelectItem->m_sDataID = keywordRec.id;
			nImage = IMAGE_DATA;
		}

		lc.InsertItem(nItem, keywordRec.title, nImage);
		lc.SetItemText(nItem, 1, keywordRec.libraryName);
		lc.SetItemText(nItem, 2, keywordRec.creator);
		lc.SetItemText(nItem, 3, keywordRec.remark);
		lc.SetItemText(nItem, 4, keywordRec.keyword);
		FormatDate(keywordRec.createDate, str);
		lc.SetItemText(nItem, 5, str);

		lc.SetItemData(nItem, (DWORD_PTR)pSelectItem);
		nItem++;
	}

	str.Format(IDS_FIND_RESULT, nItem);
	GetParent()->SetWindowText(str);

	m_nSortItem = 0;
	m_nSortDir = 1;
	lc.SortItems(CompareItems, (LPARAM)this);

	return TRUE;
}

void CFindView::DeleteAllItems()
{
	CLixxxtrl &lc = GetLixxxtrl();
	int nItem = lc.GetItemCount();

	for (int i = 0; i < nItem; i++)
		delete (CSelectItem *)lc.GetItemData(i);

	lc.DeleteAllItems();
}

void CFindView::OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult) 
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

int CALLBACK CFindView::CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort)
{
	CFindView *pView = (CFindView *)lParamSort;
	CLixxxtrl &lc = pView->GetLixxxtrl();
	LV_FINDINFO findInfo;
	int index1, index2;

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam1;
	index1 = lc.FindItem(&findInfo);

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam2;
	index2 = lc.FindItem(&findInfo);

	return lc.GetItemText(index1, pView->m_nSortItem).CompareNoCase(lc.GetItemText(index2, pView->m_nSortItem)) * pView->m_nSortDir;
}

void CFindView::OnItemchanged(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if (pNMListView->uNewState & LVIS_SELECTED) {
		CLixxxtrl &lc = GetLixxxtrl();
		CFindDoc *pFindDoc = (CFindDoc *)GetDocument();
		pFindDoc->m_pMMLibDoc->UpdateAllViews(NULL, VIEWHINT_SELECTDATA, (CSelectItem *)lc.GetItemData(pNMListView->iItem));
	}

	*pResult = 0;
}

void CFindView::OnDestroy() 
{
	DeleteAllItems();

	CListView::OnDestroy();
}
