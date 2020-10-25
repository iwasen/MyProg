// Item.cpp: CItem クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "MMView.h"
#include "Item.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CItem::CItem()
{
	m_bRead = FALSE;
	m_hDIB1 = NULL;
	m_hDIB2 = NULL;
}

CItem::~CItem()
{
	if (m_hDIB1 != NULL)
		::MMPictureFree(m_hDIB1);
	if (m_hDIB2 != NULL)
		::MMPictureFree(m_hDIB2);
}

void CItem::ReadItem(CMmlRead *pMmlRead, int nPage)
{
	char itemName[11];
	LPVOID itemData;
	long itemLen;

	if (m_bRead)
		return;

	if (!pMmlRead->SetPage(nPage))
		return;

	while (pMmlRead->ReadItem(itemName, &itemData, &itemLen)) {
		if (strcmp(itemName, "CDATE") == 0)
			m_CreateDate = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "UDATE") == 0)
			m_UpdateDate = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "CREATOR") == 0)
			m_Creator = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "CODE") == 0)
			m_Code = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "TITLE") == 0)
			m_Title = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "REMARK1") == 0)
			m_Remark1 = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "REMARK2") == 0)
			m_Remark2 = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "PICT1") == 0) {
			MMPictureReadMem(&m_hDIB1, itemData, itemLen);
			if (m_hDIB1 != NULL)
				m_hDIB2 = MakeSmallPicture(m_hDIB1, PICT_WIDTH, PICT_HEIGHT);
		} else if (strcmp(itemName, "TEXT") == 0)
			m_Text = CString((LPCTSTR)itemData, itemLen);

		delete itemData;
	}

	m_bRead = TRUE;
}
