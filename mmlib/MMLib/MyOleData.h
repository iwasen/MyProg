// MyOleData.h: CMyOleDataSource �N���X�̃C���^�[�t�F�C�X
//
//////////////////////////////////////////////////////////////////////

#pragma once

#include "Item.h"

class CMyOleDataSource : public COleDataSource  
{
public:
	CMyOleDataSource();
	virtual ~CMyOleDataSource();

public:
	CItem *m_pItem;

protected:
	CString m_TempFileName;

	virtual BOOL OnRenderGlobalData(LPFORMATETC lpFormatEtc, HGLOBAL* phGlobal); 
};
