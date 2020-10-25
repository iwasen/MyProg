// ExcelSeisanDaisu.cpp: CExcelSeisanDaisu クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelSeisanDaisu.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型					桁数	小数	最小	最大		有効文字
	{NULL,	eDataTypeAlphaNum,	6,		0,		0,		0,			NULL},		// 機種
	{NULL,	eDataTypeNumber,	6,		0,		0,		0,			NULL},		// 年月
	{NULL,	eDataTypeNumber,	8,		0,		0,		99999999,	NULL}		// 生産台数
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelSeisanDaisu::CExcelSeisanDaisu()
{
	m_sFileName = "prdnum.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 1;
}

BOOL CExcelSeisanDaisu::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

CString CExcelSeisanDaisu::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
