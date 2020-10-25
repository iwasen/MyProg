// ExcelLineKiseiMatrix.h: CExcelLineKiseiMatrix クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELLINEKISEIMATRIX_H__1325A4AC_8548_4534_A7B4_B842D7441240__INCLUDED_)
#define AFX_EXCELLINEKISEIMATRIX_H__1325A4AC_8548_4534_A7B4_B842D7441240__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelLineKiseiMatrix : public CExcelFile
{
public:
	enum ColumnNo {
		eColNo = 1,
		eColSublineName = 2,
		eColKoteiKigo = 3,
		eColKoteiNo = 4,
		eColShashuName = 6,
		eColYosoSagyoHyoSagyogunJunban = 7,
		eColYosoSagyogunGokei = 8,
		eColJidoShudo = 9,
		eColBuhinName = 10,
		eColKoteiName = 11,
		eColYosoSagyogunName = 12,
		eColEngGenjo = 13,
		eColEngSeiritsuFr = 14,
		eColEngSeiritsuRr = 15,
		eColEngToritsuFr = 16,
		eColEngToritsuRr = 17,
		eColEngChokuritsuTop = 18,
		eColEngChokuritsuBottom = 19
	};

	CExcelLineKiseiMatrix();

	BOOL AccessCheck(LPCTSTR pFolderName);
// --Add 2007/03/07 aizawa 不懸D-147（追加修正）
	virtual CString GetNewFileName();
// --Add
};

#endif // !defined(AFX_EXCELLINEKISEIMATRIX_H__1325A4AC_8548_4534_A7B4_B842D7441240__INCLUDED_)
