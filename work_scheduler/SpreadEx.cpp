// SpreadEx.cpp: CSpreadEx クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "SpreadEx.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

void CSpreadEx::SetValue(int nRow, int nCol, LPCTSTR pValue)
{
	SetText(nCol, nRow, (variant_t)pValue);
}

void CSpreadEx::SetValue(int nRow, int nCol, double fValue)
{
	SetText(nCol, nRow, (variant_t)fValue);
}

void CSpreadEx::SetValue(int nRow, int nCol, int nValue)
{
	SetText(nCol, nRow, (variant_t)(long)nValue);
}

void CSpreadEx::GetValue(int nRow, int nCol, CString &sValue)
{
	variant_t vValue;

	GetText(nCol, nRow, &vValue);
	sValue = (LPCTSTR)(_bstr_t)vValue;
}

void CSpreadEx::GetValue(int nRow, int nCol, double &fValue)
{
	variant_t vValue;

	GetText(nCol, nRow, &vValue);
	fValue = vValue;
}

void CSpreadEx::GetValue(int nRow, int nCol, int &nValue)
{
	variant_t vValue;

	GetText(nCol, nRow, &vValue);
	nValue = (long)vValue;
}

BOOL CSpreadEx::IsEmpty(int nRow, int nCol)
{
	variant_t vValue;

	GetText(nCol, nRow, &vValue);
	return vValue.vt == VT_EMPTY;
}

void CSpreadEx::SetBkColor(int nRow, int nCol, COLORREF rgbColor)
{
	SetCol(nCol);
	SetRow(nRow);
	SetBackColor(rgbColor);
}

void CSpreadEx::SetComboList(int nRow, int nCol, LPCTSTR pList, int nSelIndex)
{
	SetCol(nCol);
	SetRow(nRow);
	SetTypeComboBoxList(pList);
	SetTypeComboBoxCurSel(nSelIndex);
}
