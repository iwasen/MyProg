// ExcelBuhinhyoLink.h: CExcelBuhinhyoLink クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELBUHINHYOLINK_H__4EA4CA7C_6ADD_4DFE_8769_9D3293AD1AD8__INCLUDED_)
#define AFX_EXCELBUHINHYOLINK_H__4EA4CA7C_6ADD_4DFE_8769_9D3293AD1AD8__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelFile.h"

class CExcelBuhinhyoLink : public CExcelFile
{
public:
	enum ColumnNo {
		eColKatashiki = 1,
		eColKishu = 2,
		eColBlockNo = 3,
		eColBuhinNo = 4,
		eColBuhinName = 5,
		eColPC = 6,
		eColBoltFlag = 7,
		eColLinkSakiSublineName = 8,
		eColLinkSakiKoteiKigo = 9,
		eColLinkSakiKoteiNo = 10,
		eColLinkSakiKoteiName = 11,
		eColLinkSakiYosoSagyogunName = 12
	};

	CExcelBuhinhyoLink();

	BOOL AccessCheck(LPCTSTR pFolderName);
	virtual CString GetNewFileName();
};

#endif // !defined(AFX_EXCELBUHINHYOLINK_H__4EA4CA7C_6ADD_4DFE_8769_9D3293AD1AD8__INCLUDED_)
