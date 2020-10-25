// ExcelKittingTaishogai.h: CExcelKittingTaishogai クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELKITTINGTAISHOGAI_H__8928895F_DE9D_4EBE_9725_A7619A8EF6AD__INCLUDED_)
#define AFX_EXCELKITTINGTAISHOGAI_H__8928895F_DE9D_4EBE_9725_A7619A8EF6AD__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelKittingTaishogai : public CExcelFile
{
public:
	enum ColumnNo {
		eColTaishoGaiBuhinNo = 1,
		eColComment = 2
	};

	CExcelKittingTaishogai();

	BOOL AccessCheck(LPCTSTR pFolderName);
};

#endif // !defined(AFX_EXCELKITTINGTAISHOGAI_H__8928895F_DE9D_4EBE_9725_A7619A8EF6AD__INCLUDED_)
