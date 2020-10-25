// ExcelLineKiseiError.h: CExcelLineKiseiError クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELLINEKISEIERROR_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_)
#define AFX_EXCELLINEKISEIERROR_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelLineKiseiError : public CExcelFile
{
public:
	enum ColumnNo {
		eColDay						= 1,
		eColTime					= 2,
		eColSublineFrom				= 3,
		eColKoteiNoFrom				= 4,
		eColKoteiNameFrom			= 5,
		eColYosoSagyogunNameFrom	= 6,
		eColSublineTo				= 7,
		eColKoteiNoToB				= 8,
		eColKoteiNameToB			= 9,
		eColYosoSagyogunNameToB		= 10,
		eColKoteiNoToA				= 11,
		eColKoteiNameToA			= 12,
		eColYosoSagyogunNameToA		= 13,
		eColRiyu					= 14,
	};

	CExcelLineKiseiError();

	BOOL AccessCheck(LPCTSTR pFolderName);
	BOOL ReadFile();
	static void SetColumnName();

// Modify ... ( ADD )
	int nErrorDataSize;
// By Y.Itabashi (xxxxx) 2007.02.19
};

#endif // !defined(AFX_ExcelLineKiseiERROR_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_)
