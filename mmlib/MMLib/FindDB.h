// FindDB.h: CFindDB クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#pragma once

class CFindDB  
{
public:
	CFindDB();
	virtual ~CFindDB();

public:
	BOOL Open(BOOL bCreate);
	BOOL ReadFind(LPCTSTR pLibraryID, FINDREC *pFindRec);
	BOOL AddFind(FINDREC *pFindRec);
	BOOL UpdateFind(FINDREC *pFindRec);
	BOOL DeleteFind(LPCTSTR did);

private:
	CPcserve m_Pcserve;
	CPSDB m_FindDB;

	int MakeFindID(CString &id);
};
