// ExcelYosoSagyoTemplate.h: CExcelYosoSagyoTemplate クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELYOSOSAGYOTEMPLATE_H__05E5CA9D_6B31_403B_8D9B_F490D890AB24__INCLUDED_)
#define AFX_EXCELYOSOSAGYOTEMPLATE_H__05E5CA9D_6B31_403B_8D9B_F490D890AB24__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelYosoSagyoTemplate : public CExcelFile  
{
public:
	enum ColumnNo {
		eColYosoSagyoNo = 1,
		eColSagyoNaiyo = 2,
		eColEnzanShiki = 3,
		eColS1 = 4,
		eColS2 = 5,
		eCxxx = 6,
		eColN = 7,
		eColEnzangoSagyoJikan = 8,
		eColHassexxxndo = 9,
		eColKurikaeshiKaisu = 10,
		eColDaiatariYosoSagyoJikan = 11,
		eColComment = 12,
		eColSagyoFlag = 13
	};

	CExcelYosoSagyoTemplate();
};

#endif // !defined(AFX_EXCELYOSOSAGYOTEMPLATE_H__05E5CA9D_6B31_403B_8D9B_F490D890AB24__INCLUDED_)
