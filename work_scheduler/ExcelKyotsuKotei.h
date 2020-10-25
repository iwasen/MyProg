// ExcelKyotsuKotei.h: CExcelKyotsuKotei クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELKYOTSUKOTEI_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_)
#define AFX_EXCELKYOTSUKOTEI_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelKyotsuKotei : public CExcelFile
{
public:
	enum ColumnNo {
		eColSublineName = 1,
		eColKoteiName = 2,
		eColYosoSagyogunName = 3,
		eColJodoShudo = 4,
		eColKyotsuKoteiGroupNo = 5
	};

	CExcelKyotsuKotei();

	BOOL AccessCheck(LPCTSTR pFolderName);
	BOOL ReadFile();
	static void SetColumnName();
};

#endif // !defined(AFX_EXCELKYOTSUKOTEI_H__60EA4CE7_1125_4365_8270_4F0EBD704225__INCLUDED_)
