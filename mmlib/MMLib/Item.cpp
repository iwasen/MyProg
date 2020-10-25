// Item.cpp: CItem クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "MMLib.h"
#include "Item.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// CItem クラス
//////////////////////////////////////////////////////////////////////

CObList CItem::m_ItemList;
POSITION CItem::m_Position;

CItem::CItem()
{
	m_hItem = NULL;
	m_bReadChildren = FALSE;

	m_ItemList.AddTail(this);
}

CItem::~CItem()
{
	POSITION pos;
	HANDLE hMem;
	CItem *pItem;

	if ((pos = m_ItemList.Find(this)) != NULL)
		m_ItemList.RemoveAt(pos);

	if (AfxGetApp()->m_pMainWnd->OpenClipboard()) {
		if ((hMem = ::GetClipboardData(g_nCFMMLibrary)) != NULL) {
			pItem = *(CItem **)::GlobalLock(hMem);
			::GlobalUnlock(hMem);
			if (pItem == this) {
				::EmptyClipboard();
				::CloseClipboard();
			}
		}
	}
}

void CItem::RemoveAll()
{
	CItem *pItem;
	while (!m_ItemList.IsEmpty()) {
		pItem = (CItem *)m_ItemList.GetHead();
		delete pItem;
	}
}

CItem *CItem::GetParent()
{
	HTREEITEM hItem = g_pTreeCtrl->GetParentItem(m_hItem);
	if (hItem == NULL)
		return NULL;

	return (CItem *)g_pTreeCtrl->GetItemData(hItem);
}

CItem *CItem::GetHeadItem()
{
	m_Position = m_ItemList.GetHeadPosition();
	return GetNextItem();
}

CItem *CItem::GetNextItem()
{
	if (m_Position == NULL)
		return NULL;

	return (CItem *)m_ItemList.GetNext(m_Position);
}

//////////////////////////////////////////////////////////////////////
// CItemRoot クラス
//////////////////////////////////////////////////////////////////////

CItemRoot::CItemRoot()
{
	m_nItemType = ITEM_ROOT;
}

CItemRoot::~CItemRoot()
{
}

//////////////////////////////////////////////////////////////////////
// CItemInbox クラス
//////////////////////////////////////////////////////////////////////

CItemInbox::CItemInbox(CMMPcsDB *pPcsDB) : CItemFolder(pPcsDB)
{
	m_nItemType = ITEM_INBOX;
}

CItemInbox::~CItemInbox()
{
}

//////////////////////////////////////////////////////////////////////
// CItemFolder クラス
//////////////////////////////////////////////////////////////////////

CItemFolder::CItemFolder(CMMPcsDB *pPcsDB)
{
	m_nItemType = ITEM_FOLDER;
	m_pPcsDB = pPcsDB;
}

CItemFolder::~CItemFolder()
{
}

CString CItemFolder::GetFirstData()
{
	if (m_sFirstData.IsEmpty()) {
		if (m_FolderRec.folderType == FTYPE_DFOLDER) {
			DATAREC dataRec;
			if (m_pPcsDB->ReadChildDataEx(m_FolderRec.folderID, &dataRec, TRUE))
				m_sFirstData = dataRec.title;
		} else {
			FOLDERREC folderRec;
			if (m_pPcsDB->ReadChildFolderEx(m_FolderRec.folderID, &folderRec, TRUE))
				m_sFirstData = folderRec.folderName;
		}
	}

	return m_sFirstData;
}

//////////////////////////////////////////////////////////////////////
// CItemData クラス
//////////////////////////////////////////////////////////////////////

CItemData::CItemData(CMMPcsDB *pPcsDB)
{
	m_nItemType = ITEM_DATA;
	m_pPcsDB = pPcsDB;
	m_hDIB = NULL;
}

CItemData::~CItemData()
{
	if (m_hDIB != NULL)
		MMPictureFree(m_hDIB);
}

//////////////////////////////////////////////////////////////////////
// CItemFind クラス
//////////////////////////////////////////////////////////////////////

CItemFind::CItemFind(CMMPcsDB *pPcsDB)
{
	m_nItemType = ITEM_FIND;
	m_pPcsDB = pPcsDB;
}

CItemFind::~CItemFind()
{
}

//////////////////////////////////////////////////////////////////////
// CItemPending クラス
//////////////////////////////////////////////////////////////////////

CItemPending::CItemPending()
{
	m_nItemType = ITEM_PENDING;
}

CItemPending::~CItemPending()
{
}
