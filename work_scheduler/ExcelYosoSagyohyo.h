// ExcelYosoSagyoHyo.h: CExcelYosoSagyoHyo クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELYOSOSAGYOHYO_H__AD9E48F7_42CF_4FB5_B3D0_81671A961B7C__INCLUDED_)
#define AFX_EXCELYOSOSAGYOHYO_H__AD9E48F7_42CF_4FB5_B3D0_81671A961B7C__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelYosoSagyoHyo : public CExcelFile
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
		eColEnzanShiki = 13,
		eColS1 = 14,
		eColS2 = 15,
		eCxxx = 16,
		eColN = 17,
		eColEnzangoSagyoJikan = 18,
		eColHassexxxndo = 19,
		eColKurikaeshiKaisu = 20,
		eColKoteibuHendobu = 29,
		eColKijunKotei = 33,
		eColDaiatariYosoSagyoJikan = 21,
		eColJidoShudoKotei = 24,
		eColYosoSagyogunJunban = 25,
		eColSagyoFlag = 31,
		eColSagyogunGokeiJikan = 36,
		eColHiritsukeisangoSagyogunGokeiJikan = 37
	};

	CExcelYosoSagyoHyo();

	BOOL AccessCheck(LPCTSTR pFolderName);
	BOOL ReadFile();
	virtual CString GetNewFileName();
};

#endif // !defined(AFX_EXCELYOSOSAGYOHYO_H__AD9E48F7_42CF_4FB5_B3D0_81671A961B7C__INCLUDED_)
