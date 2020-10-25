// ExcelKittingTaishoBuhin.h: CExcelKittingTaishoBuhin クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELKITTINGTAISHOBUHIN_H__CEC49E96_A13A_4E1D_B24B_5F8723F1BB60__INCLUDED_)
#define AFX_EXCELKITTINGTAISHOBUHIN_H__CEC49E96_A13A_4E1D_B24B_5F8723F1BB60__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelKittingTaishoBuhin : public CExcelFile  
{
public:
	enum ColumnNo {
		eColSublineName = 1,
		eColBlock = 2,
		eColBuhinNo = 3,
		eColBuhinName = 4,
		eColPC = 5
	};

	CExcelKittingTaishoBuhin();
};

#endif // !defined(AFX_EXCELKITTINGTAISHOBUHIN_H__CEC49E96_A13A_4E1D_B24B_5F8723F1BB60__INCLUDED_)
