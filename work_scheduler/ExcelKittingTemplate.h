// ExcelKittingTemplate.h: CExcelKittingTemplate クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELKITTINGTEMPLATE_H__2B37DE4D_CDCF_4996_B1CB_E8B3012ABC10__INCLUDED_)
#define AFX_EXCELKITTINGTEMPLATE_H__2B37DE4D_CDCF_4996_B1CB_E8B3012ABC10__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelKittingTemplate : public CExcelFile
{
public:
	enum ColumnNo {
		eColSakujoFlag = 1,
		eColTsuikaFlag = 2,
		eColSublineName = 3,
		eColKoteiKigo = 4,
		eColKoteiNo = 5,
		eColKoteiName = 6,
		eColYosoSagyogunName = 7,
		eColJidoShudoYosoSagyogun = 8,
		eColSagyosha = 9,
		eColSagyoNo = 10,
		eColSagyoNaiyo = 11,
		eColYosoSagyoNo = 12,
		eColDaiatariYosoSagyoJikan = 21,
		eColJidoShudoKotei = 24,
		eColYosoSagyogunJunban = 25,
		eColSagyogunGokeiJikan = 36,
		eColHiritsukeisangoSagyogunGokeiJikan = 37
	};

	CExcelKittingTemplate();

	BOOL AccessCheck(LPCTSTR pFolderName);
	BOOL ReadFile();
	BOOL FileDataCheck();
};

#endif // !defined(AFX_EXCELKITTINGTEMPLATE_H__2B37DE4D_CDCF_4996_B1CB_E8B3012ABC10__INCLUDED_)
