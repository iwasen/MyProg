// Mml.cpp: CMmlWrite クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "Mml.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

#define FILE_TYPE	"MML1"

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CMmlWrite::CMmlWrite()
{
	m_pPageListTop = NULL;
	m_pPageListBottom = NULL;
	m_pItemNameListTop = NULL;
	m_pItemNameListBottom = NULL;
	m_bNewPage = FALSE;
}

CMmlWrite::~CMmlWrite()
{
	Close();
}

BOOL CMmlWrite::Create(LPCTSTR pFileName)
{
	FILE_HEADER fileHeader;

	if (!m_File.Open(pFileName, CFile::modeCreate | CFile::modeWrite | CFile::shareExclusive))
		return FALSE;

	m_bNewPage = TRUE;

	memset(&fileHeader, 0, sizeof(fileHeader));
	m_File.Write(&fileHeader, sizeof(fileHeader));

	return TRUE;
}

BOOL CMmlWrite::WriteItem(LPCTSTR pItemName, LPCVOID pData, long len)
{
	ITEM_HEADER itemHeader;

	if (m_File.m_hFile == CFile::hFileNull)
		return FALSE;

	if (len == 0)
		return TRUE;

	if (m_bNewPage) {
		NewPage();
		m_bNewPage = FALSE;
	}

	m_pPageListBottom->nItem++;

	itemHeader.nItemNo = GetItemNo(pItemName);
	itemHeader.nLength = len;
	m_File.Write(&itemHeader, sizeof(itemHeader));

	m_File.Write(pData, len);

	return TRUE;
}

void CMmlWrite::NextPage()
{
	if (!m_bNewPage)
		m_bNewPage = TRUE;
}

void CMmlWrite::Close()
{
	FILE_HEADER fileHeader;
	PageList *pPageList, *pPageListNext;
	ItemNameList *pItemNameList, *pItemNameListNext;
	int n;
	PAGE_TABLE pageTable;
	char itemName[10];

	if (m_File.m_hFile == CFile::hFileNull)
		return;

	memcpy(fileHeader.fileType, FILE_TYPE, 4);
	fileHeader.nPageTableOffset = (long)m_File.Seek(0, CFile::current);

	for (pPageList = m_pPageListTop, n = 0; pPageList != NULL; pPageList = pPageListNext, n++) {
		pPageListNext = pPageList->pChain;
		pageTable.nOffset = pPageList->nOffset;
		pageTable.nItem = pPageList->nItem;
		m_File.Write(&pageTable, sizeof(pageTable));
		delete pPageList;
	}

	fileHeader.nPageTableNum = n;
	fileHeader.nItemTableOffset = (long)m_File.Seek(0, CFile::current);

	for (pItemNameList = m_pItemNameListTop, n = 0; pItemNameList != NULL; pItemNameList = pItemNameListNext, n++) {
		pItemNameListNext = pItemNameList->pChain;
		memset(itemName, ' ', sizeof(itemName));
		memcpy(itemName, (LPCTSTR)pItemNameList->itemName, min(pItemNameList->itemName.GetLength(), sizeof(itemName)));
		m_File.Write(itemName, sizeof(itemName));
		delete pItemNameList;
	}

	fileHeader.nItemTableNum = n;

	m_File.SeekToBegin();
	m_File.Write(&fileHeader, sizeof(fileHeader));

	m_File.Close();
}

void CMmlWrite::NewPage()
{
	PageList *pPageList = new PageList;

	pPageList->pChain = NULL;
	pPageList->nOffset = (long)m_File.Seek(0, CFile::current);
	pPageList->nItem = 0;

	if (m_pPageListTop == NULL)
		m_pPageListTop = pPageList;

	if (m_pPageListBottom != NULL)
		m_pPageListBottom->pChain = pPageList;
	m_pPageListBottom = pPageList;
}

int CMmlWrite::GetItemNo(LPCTSTR pItemName)
{
	ItemNameList *pItemNameList;
	int n;

	for (pItemNameList = m_pItemNameListTop, n = 0; pItemNameList != NULL; pItemNameList = pItemNameList->pChain, n++) {
		if (pItemNameList->itemName == pItemName)
			return pItemNameList->nItemNo;
	}

	pItemNameList = new ItemNameList;
	pItemNameList->pChain = NULL;
	pItemNameList->itemName = pItemName;
	pItemNameList->nItemNo = n;

	if (m_pItemNameListTop == NULL)
		m_pItemNameListTop = pItemNameList;

	if (m_pItemNameListBottom != NULL)
		m_pItemNameListBottom->pChain = pItemNameList;
	m_pItemNameListBottom = pItemNameList;

	return n;
}

//////////////////////////////////////////////////////////////////////
// CMmlRead クラス
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CMmlRead::CMmlRead()
{
	m_pPageTable = NULL;
	m_pItemTable = NULL;
	m_nPage = 0;
}

CMmlRead::~CMmlRead()
{
	Close();
}

BOOL CMmlRead::Open(LPCTSTR pFileName)
{
	char (*pItemTable)[10];
	int i;

	if (!m_File.Open(pFileName, CFile::modeRead | CFile::shareDenyWrite))
		return FALSE;

	m_File.Read(&m_FileHeader, sizeof(m_FileHeader));

	if (memcmp(m_FileHeader.fileType, FILE_TYPE, 4) != 0) {
		m_File.Close();
		return FALSE;
	}

	m_nPage = m_FileHeader.nPageTableNum;

	m_pPageTable = new PAGE_TABLE[m_FileHeader.nPageTableNum];
	m_File.Seek(m_FileHeader.nPageTableOffset, CFile::begin);
	m_File.Read(m_pPageTable, m_FileHeader.nPageTableNum * sizeof(PAGE_TABLE));

	pItemTable = new char[m_FileHeader.nItemTableNum][10];
	m_File.Seek(m_FileHeader.nItemTableOffset, CFile::begin);
	m_File.Read(pItemTable, m_FileHeader.nItemTableNum * 10);

	m_pItemTable = new CString[m_FileHeader.nItemTableNum];
	for (i = 0; i < m_FileHeader.nItemTableNum; i++) {
		m_pItemTable[i] = CString(pItemTable[i], 10);
		m_pItemTable[i].TrimRight();
	}

	delete [] pItemTable;

	SetPage(0);

	return TRUE;
}

BOOL CMmlRead::SetPage(int nPage)
{
	if (m_File.m_hFile == CFile::hFileNull)
		return FALSE;

	if (nPage >= m_FileHeader.nPageTableNum)
		return FALSE;

	m_File.Seek(m_pPageTable[nPage].nOffset, CFile::begin);
	m_nMaxItem = m_pPageTable[nPage].nItem;
	m_nCurrentItem = 0;

	return TRUE;
}

BOOL CMmlRead::ReadItem(LPTSTR pItemName, LPVOID *pData, long *pLen)
{
	ITEM_HEADER itemHeader;

	if (m_File.m_hFile == CFile::hFileNull)
		return FALSE;

	if (m_nCurrentItem == m_nMaxItem)
		return FALSE;

	m_nCurrentItem++;

	m_File.Read(&itemHeader, sizeof(itemHeader));

	strcpy(pItemName, m_pItemTable[itemHeader.nItemNo]);

	*pData = new char[itemHeader.nLength];
	*pLen = itemHeader.nLength;
	m_File.Read(*pData, itemHeader.nLength);

	return TRUE;
}

void CMmlRead::Close()
{
	if (m_File.m_hFile != CFile::hFileNull)
		m_File.Close();

	if (m_pPageTable != NULL) {
		delete [] m_pPageTable;
		m_pPageTable = NULL;
	}

	if (m_pItemTable != NULL) {
		delete [] m_pItemTable;
		m_pItemTable = NULL;
	}
}

