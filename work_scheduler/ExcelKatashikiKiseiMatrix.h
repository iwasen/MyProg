// ExcelKatashikiKiseiMatrix.h: CExcelKatashikiKiseiMatrix クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELKATASHIKIKISEIMATRIX_H__D381AFDC_0EA5_43C3_85B0_90921D9B6590__INCLUDED_)
#define AFX_EXCELKATASHIKIKISEIMATRIX_H__D381AFDC_0EA5_43C3_85B0_90921D9B6590__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelKatashikiKiseiMatrix : public CExcelFile
{
public:
	enum ColumnNo {
		eColNo = 1,
		eColSublineName = 2,
		eColKoteiKigo = 3,
		eColKoteiNo = 4,
		eColReserve = 5,
		eColShashuName = 6,
		eColYosoSagyohyoSagyogunJunban = 7,
		eColYosoSagyougunGokei = 8,
		eColJodoShudo = 9,
		eColBuhinName = 10,
		eColKoteiName = 11,
		eColYosoSagyogunName = 12,
		eColReserve2 = 13,
		eColKisei = 14
	};

	enum RowNo {
		eRowSublineName = 1,
		eRowKoteiNo = 3,
		eRowShashuName = 5,
		eRowKoteiName = 10,
		eRowYosoSagyogunName = 11,
		eRowKisei = 13
	};

	CExcelKatashikiKiseiMatrix();

	BOOL AccessCheck(LPCTSTR pFolderName);
};

#endif // !defined(AFX_EXCELKATASHIKIKISEIMATRIX_H__D381AFDC_0EA5_43C3_85B0_90921D9B6590__INCLUDED_)
