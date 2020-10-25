// SpreadEx.h: CSpreadEx クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_SPREADEX_H__4AD9171A_CD5A_49D6_AC9D_E74B03D8B3FD__INCLUDED_)
#define AFX_SPREADEX_H__4AD9171A_CD5A_49D6_AC9D_E74B03D8B3FD__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "spreadsheet.h"

class CSpreadEx : public CSpreadSheet
{
public:
	void SetValue(int nRow, int nCol, LPCTSTR pValue);
	void SetValue(int nRow, int nCol, double fValue);
	void SetValue(int nRow, int nCol, int nValue);
	void GetValue(int nRow, int nCol, CString &sValue);
	void GetValue(int nRow, int nCol, double &fValue);
	void GetValue(int nRow, int nCol, int &nValue);
	BOOL IsEmpty(int nRow, int nCol);
	void SetBkColor(int nRow, int nCol, COLORREF rgbColor);
	void SetComboList(int nRow, int nCol, LPCTSTR pList, int nSelIndex = -1);
};

#endif // !defined(AFX_SPREADEX_H__4AD9171A_CD5A_49D6_AC9D_E74B03D8B3FD__INCLUDED_)
