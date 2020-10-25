// ExcelKatashikiKiseiError.h: CExcelKatashikiKiseiError クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELKATASHIKIKISEIERROR_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_)
#define AFX_EXCELKATASHIKIKISEIERROR_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelKatashikiKiseiError : public CExcelFile
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
		eColKoteiNoTo				= 8,
		eColKoteiNameTo				= 9,
		eColYosoSagyogunNameTo		= 10,
		eColSublineNg				= 11,
		eColKoteiNoNg				= 12,
		eColKoteiNameNg				= 13,
		eColYosoSagyogunNameNg		= 14,
		eColRiyu					= 15,
	};

	CExcelKatashikiKiseiError();

	BOOL AccessCheck(LPCTSTR pFolderName);
	BOOL ReadFile();
	static void SetColumnName();

// Modify ... ( ADD )
	int nErrorDataSize;
// By Y.Itabashi (xxxxx) 2007.02.19
};

#endif // !defined(AFX_ExcelKatashikiKiseiError_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_)
