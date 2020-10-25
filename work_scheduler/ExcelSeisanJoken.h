// ExcelSeisanJoken.h: CExcelSeisanJoken クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELSEISANJOKEN_H__70E459E6_31A8_4787_93FE_4F44CCACCFE9__INCLUDED_)
#define AFX_EXCELSEISANJOKEN_H__70E459E6_31A8_4787_93FE_4F44CCACCFE9__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelSeisanJoken : public CExcelFile
{
public:
	enum ColumnNo {
		eColSublineName = 1,
		eColKinmuTaisei = 2,
		eColKyusokuJikan = 3,
		eColKadoNissu = 4,
		eColKotaiKinmu = 5,
		eColJogen = 6,
		eColKagen = 7
	};

	CExcelSeisanJoken();

	BOOL AccessCheck(LPCTSTR pFolderName);
};

#endif // !defined(AFX_EXCELSEISANJOKEN_H__70E459E6_31A8_4787_93FE_4F44CCACCFE9__INCLUDED_)
