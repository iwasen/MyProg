// ExcelBuhinhyoLink.cpp: CExcelBuhinhyoLink クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelBuhinhyoLink.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小	最大		有効文字
	{NULL,	eDataTypeAlphaNum,		6,		0,		0,		0,			NULL},		// 型式
	{NULL,	eDataTypeAlphaNum,		6,		0,		0,		0,			NULL},		// 機種
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,			NULL},		// ﾌﾞﾛｯｸNo
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,		0,			NULL},		// 部品番号
	{NULL,	eDataTypeAlphaNumKana,	64,		0,		0,		0,			NULL},		// 部品名称
	{NULL,	eDataTypeNumber,		32,		2,		0,		9999999999,	NULL},		// P/C
	{NULL,	eDataTypeNone,			1,		0,		0,		0,			"1"},		// ﾎﾞﾄﾙﾌﾗｸﾞ
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,			NULL},		// ﾘﾝｸ先ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,			NULL},		// ﾘﾝｸ先工程記号
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,		0,			NULL},		// ﾘﾝｸ先工程番号
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,			NULL},		// ﾘﾝｸ先工程名称
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,			NULL},		// ﾘﾝｸ先要素作業群名
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelBuhinhyoLink::CExcelBuhinhyoLink()
{
	m_sFileName = "parts.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nMaxSheet = MAX_KISHU;
	m_nFileNo = 3;
}

BOOL CExcelBuhinhyoLink::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

CString CExcelBuhinhyoLink::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
