// ExcelKinmuTaisei.h: CExcelKinmuTaisei クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELKINMUTAISEI_H__DC215AB0_D07C_4068_8735_5DA21C44252B__INCLUDED_)
#define AFX_EXCELKINMUTAISEI_H__DC215AB0_D07C_4068_8735_5DA21C44252B__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelKinmuTaisei : public CExcelFile
{
public:
	enum ColumnNo {
		eColSublineName = 1,
		eColGekkanKadoJikan = 2,
		eColKyoseiKudoFlag = 3,
		eColNukitoriritsu = 4,
		eColKadoritsu = 5
	};

	CExcelKinmuTaisei();

	BOOL AccessCheck(LPCTSTR pFolderName);
	virtual CString GetNewFileName();
};

#endif // !defined(AFX_EXCELKINMUTAISEI_H__DC215AB0_D07C_4068_8735_5DA21C44252B__INCLUDED_)
