// ExcelKittingTaishogai.cpp: CExcelKittingTaishogai クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKittingTaishogai.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型					桁数	小数	最小	最大	有効文字
	{NULL,	eDataTypeAlphaNum,	32,		0,		0,		0,		NULL},		// 対象外部品番号
	{NULL,	eDataTypeAll,		256,	0,		0,		0,		NULL}		// コメント
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelKittingTaishogai::CExcelKittingTaishogai()
{
	m_sFileName = "kitt_del_parts.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 14;
}

BOOL CExcelKittingTaishogai::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}
