// ExcelBuhinKitting.h: CExcelBuhinKitting クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELBUHINKITTING_H__C05E64AB_1449_42E6_A54B_DAA4E739A754__INCLUDED_)
#define AFX_EXCELBUHINKITTING_H__C05E64AB_1449_42E6_A54B_DAA4E739A754__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelBuhinKitting : public CExcelFile
{
public:
	enum ColumnNo {
		eColSublineName = 1,	// ｻﾌﾞﾗｲﾝ名
		eColBlock = 2,			// BLOCK
		eColBuhinNo = 3,		// 部番
		eColName = 4,			// 名称
		eColKitingFlag = 5		// ｷｯﾄ化ﾌﾗｸﾞ
	};

	CExcelBuhinKitting();

	BOOL AccessCheck(LPCTSTR pFolderName);
};

#endif // !defined(AFX_EXCELBUHINKITTING_H__C05E64AB_1449_42E6_A54B_DAA4E739A754__INCLUDED_)
