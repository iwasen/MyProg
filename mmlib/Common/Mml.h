// Mml.h: CMmlWrite クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#pragma once

struct PageList {
	PageList *pChain;
	long nOffset;
	long nItem;
};

struct ItemNameList {
	ItemNameList *pChain;
	CString itemName;
	int nItemNo;
};

#pragma pack(1)
struct FILE_HEADER {
	char fileType[4];
	long nPageTableOffset;
	long nPageTableNum;
	long nItemTableOffset;
	long nItemTableNum;
};

struct PAGE_TABLE {
	long nOffset;
	long nItem;
};

struct ITEM_HEADER {
	short nItemNo;
	long nLength;
};
#pragma pack()

class CMmlWrite : public CObject  
{
public:
	CMmlWrite();
	virtual ~CMmlWrite();

public:
	BOOL Create(LPCTSTR pFileName);
	BOOL WriteItem(LPCTSTR pItemName, LPCVOID pData, long len);
	void NextPage();
	void Close();

protected:
	CFile m_File;
	PageList *m_pPageListTop;
	PageList *m_pPageListBottom;
	ItemNameList *m_pItemNameListTop;
	ItemNameList *m_pItemNameListBottom;
	BOOL m_bNewPage;

	void NewPage();
	int GetItemNo(LPCTSTR pItemName);
};


class CMmlRead : public CObject  
{
public:
	CMmlRead();
	virtual ~CMmlRead();

	int m_nPage;

	BOOL Open(LPCTSTR pFileName);
	BOOL SetPage(int nPage);
	BOOL ReadItem(LPTSTR pItemName, LPVOID *pData, long *pLen);
	void Close();

protected:
	CFile m_File;
	FILE_HEADER m_FileHeader;
	PAGE_TABLE *m_pPageTable;
	CString *m_pItemTable;
	int m_nMaxItem;
	int m_nCurrentItem;
};
