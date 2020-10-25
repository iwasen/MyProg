// DataCheck.cpp: CDataCheck クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "DataCheck.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

BOOL CDataCheck::BlankCheck(LPCTSTR pData)
{
	CString sData(pData);
	sData.Replace(" ", "");
	sData.Replace("　", "");
	return !sData.IsEmpty();
}

BOOL CDataCheck::LengthCheck(LPCTSTR pData)
{
	if (m_nLength != 0 && (int)_mbslen((unsigned char *)pData) > m_nLength)
		return FALSE;

	if (m_nDecimal != 0) {
		LPCTSTR p = strchr(pData, '.');
		if (p != NULL && (int)strlen(p + 1) > m_nDecimal)
			return FALSE;
	}

	return TRUE;
}

BOOL CDataCheck::ValidCharCheck(LPCTSTR pData)
{
	switch (m_nDataType) {
	case eDataTypeAll:
		return TRUE;
	case eDataTypeNumber:
		if (m_nDecimal == 0) {
			if (IsNumber(pData))
				return TRUE;
		} else {
			if (IsFloat(pData))
				return TRUE;
		}
		break;
	case eDataTypeAlphaNum:
		if (IsAlphaNum(pData))
			return TRUE;
		break;
	case eDataTypeAlphaNumKana:
		if (IsAlphaNumKana(pData))
			return TRUE;
		break;
	case eDataTypeFileName:
		if (IsFileName(pData))
			return TRUE;
		break;
	case eDataTypeKoteiName:
		if (IsKoteiName(pData))
			return TRUE;
		break;
	}

	if (m_pVaridChar != NULL) {
		int nLen = (int)strlen(pData);
		for (int i = 0; i < nLen; i++, pData++) {
			if (strchr(m_pVaridChar, *pData) != NULL)
				return TRUE;
		}
	}

	return FALSE;
}

BOOL CDataCheck::RangeCheck(LPCTSTR pData)
{
	if (m_nDataType == eDataTypeNumber && (m_fMin != 0 || m_fMax != 0)) {
		if (!RangeCheck(atof(pData)))
			return FALSE;
	}

	return TRUE;
}

BOOL CDataCheck::RangeCheck(double fData)
{
	if (fData < m_fMin || fData > m_fMax)
		return FALSE;

	return TRUE;
}

BOOL CDataCheck::IsNumber(LPCTSTR pData)
{
	int i;

	int nLen = (int)strlen(pData);
	for (i = 0; i < nLen; i++, pData++) {
		int c = *pData;
		if (!(isdigit(c) || c == '-'))
			return FALSE;
	}

	return TRUE;
}

BOOL CDataCheck::IsFloat(LPCTSTR pData)
{
	int i;

	int nLen = (int)strlen(pData);
	for (i = 0; i < nLen; i++, pData++) {
		int c = *pData;
		if (!(isdigit(c) || c == '-' || c == '.' || c == 'E'))
			return FALSE;
	}

	return TRUE;
}

BOOL CDataCheck::IsAlphaNum(LPCTSTR pData)
{
	int i;

	int nLen = (int)strlen(pData);
	for (i = 0; i < nLen; i++, pData++) {
		int c = *pData;
		if (!(iscsym(c) || c == ' ' || c == '-'))
			return FALSE;
	}

	return TRUE;
}

BOOL CDataCheck::IsAlphaNumKana(LPCTSTR pData)
{
	int i;

	int nLen = (int)strlen(pData);
	for (i = 0; i < nLen; i++, pData++) {
		int c = *pData;
		if (!(isprint(c) || (c >= 0xa1 && c <= 0xdf)))
			return FALSE;
	}

	return TRUE;
}

BOOL CDataCheck::IsFileName(LPCTSTR pData)
{
	int i;

	int nLen = (int)strlen(pData);
	for (i = 0; i < nLen; i++, pData++) {
		if (strchr("\\/:*?\"<>|", *pData) != NULL)
			return FALSE;
	}

	return TRUE;
}

BOOL CDataCheck::IsKoteiName(LPCTSTR pData)
{
	int i;

	int nLen = (int)strlen(pData);
	for (i = 0; i < nLen; i++, pData++) {
		if (strchr("#.;*", *pData) != NULL)
			return FALSE;
	}

	return TRUE;
}
