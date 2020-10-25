// Item.h: CItem クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#pragma once

#include "MMLibDB.h"

class CItem : public CObject
{
public:
	CItem();
	virtual ~CItem();

public:
	int m_nItemType;
	HTREEITEM m_hItem;
	BOOL m_bReadChildren;
	BOOL m_bCheck;
	CItem *m_pNextSelected;

	static void RemoveAll();
	CItem *GetParent();
	virtual LPCTSTR GetID() = 0;
	static CItem *GetHeadItem();
	static CItem *GetNextItem();

protected:
	static CObList m_ItemList;
	static POSITION m_Position;
};

class CItemRoot : public CItem  
{
public:
	CItemRoot();
	virtual ~CItemRoot();
	virtual LPCTSTR GetID() { return ""; }
};

class CItemFolder : public CItem  
{
public:
	CItemFolder(CMMPcsDB *pPcsDB);
	virtual ~CItemFolder();

public:
	CMMPcsDB *m_pPcsDB;
	FOLDERREC m_FolderRec;
	int m_nFolderType;
	CString m_sFirstData;

	virtual LPCTSTR GetID() { return m_FolderRec.folderID; }
	CString GetFirstData();
};

class CItemInbox : public CItemFolder  
{
public:
	CItemInbox(CMMPcsDB *pPcsDB);
	virtual ~CItemInbox();
};

class CItemData : public CItem  
{
public:
	CItemData(CMMPcsDB *pPcsDB);
	virtual ~CItemData();

public:
	CMMPcsDB *m_pPcsDB;
	DATAREC m_DataRec;
	HDIB m_hDIB;
	CSize m_Size;
	CRect m_Rect;

	virtual LPCTSTR GetID() { return m_DataRec.dataID; }
};

class CItemFind : public CItem  
{
public:
	CItemFind(CMMPcsDB *pPcsDB);
	virtual ~CItemFind();

public:
	CMMPcsDB *m_pPcsDB;
	FINDREC m_FindRec;
	int m_nFindType;
	BOOL m_bSave;

	virtual LPCTSTR GetID() { return m_FindRec.findID; }
};

class CItemPending : public CItem  
{
public:
	CItemPending();
	virtual ~CItemPending();

public:
	CString m_Title;
	CString m_ServerName;
	int m_nChType;
	CString m_DirName;
	CString m_LibraryID;
	int m_nCacheMode;

	virtual LPCTSTR GetID() { return m_LibraryID; }
};

class CAddItem : public CObject
{
public:
	int m_nAddItemType;
};

class CAddItemLibrary : public CAddItem
{
public:
	CAddItemLibrary() { m_nAddItemType = ADDITEM_LIBRARY; }

	CMMPcsDB *m_pPcsDB;
};

class CAddItemPending : public CAddItem
{
public:
	CAddItemPending() { m_nAddItemType = ADDITEM_PENDING; }

	CString m_Title;
	CString m_ServerName;
	int m_nChType;
	CString m_DirName;
	CString m_LibraryID;
	int m_nCacheMode;
};

class CAddItemFolder : public CAddItem
{
public:
	CAddItemFolder() { m_nAddItemType = ADDITEM_FOLDER; }

	CItemFolder *m_pItemFolder;
	FOLDERREC *m_pFolderRec;
	CItemFolder *m_pItemAddFolder;
};

class CAddItemData : public CAddItem
{
public:
	CAddItemData() { m_nAddItemType = ADDITEM_DATA; }

	CItemFolder *m_pItemFolder;
	DATAREC *m_pDataRec;
	CItemData *m_pItemAddData;
};

class CAddItemFind : public CAddItem
{
public:
	CAddItemFind() { m_nAddItemType = ADDITEM_FIND; }

	CItemFolder *m_pItemFolder;
	FINDREC *m_pFindRec;
	BOOL m_bSelect;
	BOOL m_bSave;
};

class CSelectItem : public CObject
{
public:
	CString m_sLibraryID;
	CString m_sFolderID;
	CString m_sDataID;
};
