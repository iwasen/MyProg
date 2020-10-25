// ExcelSeisanDaisu.h: CExcelSeisanDaisu クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELSEISANDAISU_H__E017D366_9A80_4940_8417_57857F4AB9D2__INCLUDED_)
#define AFX_EXCELSEISANDAISU_H__E017D366_9A80_4940_8417_57857F4AB9D2__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelSeisanDaisu : public CExcelFile
{
public:
	enum ColumnNo {
		eColKishu = 1,
		eColNengetsu = 2,
		eColSeisanDaisu = 3
	};

	CExcelSeisanDaisu();

	BOOL AccessCheck(LPCTSTR pFolderName);
	int GetColumnNo(LPCTSTR pColumnName);
	virtual CString GetNewFileName();
};

#endif // !defined(AFX_EXCELSEISANDAISU_H__E017D366_9A80_4940_8417_57857F4AB9D2__INCLUDED_)
