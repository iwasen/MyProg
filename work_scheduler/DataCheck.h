// DataCheck.h: CDataCheck クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_DATACHECK_H__4533ED04_A687_4A77_A70A_F3D4C67B17C0__INCLUDED_)
#define AFX_DATACHECK_H__4533ED04_A687_4A77_A70A_F3D4C67B17C0__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

// データの型
enum EDataType {
	eDataTypeNone,
	eDataTypeAll,
	eDataTypeNumber,
	eDataTypeAlphaNum,
	eDataTypeAlphaNumKana,
	eDataTypeFileName,
	eDataTypeKoteiName
};

class CDataCheck
{
public:
	EDataType m_nDataType;
	int m_nLength;
	int m_nDecimal;
	double m_fMin;
	double m_fMax;
	LPCTSTR m_pVaridChar;

	BOOL BlankCheck(LPCTSTR pData);
	BOOL LengthCheck(LPCTSTR pData);
	BOOL ValidCharCheck(LPCTSTR pData);
	BOOL RangeCheck(LPCTSTR pData);
	BOOL RangeCheck(double fData);

	static BOOL IsNumber(LPCTSTR pData);
	static BOOL IsFloat(LPCTSTR pData);
	static BOOL IsAlphaNum(LPCTSTR pData);
	static BOOL IsAlphaNumKana(LPCTSTR pData);
	static BOOL IsFileName(LPCTSTR pData);
	static BOOL IsKoteiName(LPCTSTR pData);
};

#endif // !defined(AFX_DATACHECK_H__4533ED04_A687_4A77_A70A_F3D4C67B17C0__INCLUDED_)
