// ExcelBuhinKitting.cpp: CExcelBuhinKitting クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelBuhinKitting.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小	最大	有効文字
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,		NULL},		// BLOCK
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,		0,		NULL},		// 部番
	{NULL,	eDataTypeAlphaNumKana,	64,		0,		0,		0,		NULL},		// 名称
	{"1",	eDataTypeNone,			1,		0,		0,		0,		"01"}		// ｷｯﾄ化ﾌﾗｸﾞ
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelBuhinKitting::CExcelBuhinKitting()
{
	m_sFileName = "kitting_parts.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 10;

	SetKeys(0);
}

BOOL CExcelBuhinKitting::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}
