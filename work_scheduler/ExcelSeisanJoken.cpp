// ExcelSeisanJoken.cpp: CExcelSeisanJoken クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelSeisanJoken.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小	最大	有効文字
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// サブライン名
	{NULL,	eDataTypeNumber,		6,		1,		0,		24,		NULL},		// 勤務体制
	{NULL,	eDataTypeNumber,		6,		0,		0,		60,		NULL},		// 休憩時間
	{NULL,	eDataTypeNumber,		6,		0,		0,		31,		NULL},		// 稼働日数
	{NULL,	eDataTypeNumber,		6,		0,		0,		10,		NULL},		// 交代勤務
	{NULL,	eDataTypeNumber,		6,		1,		0,		100,	NULL},		// 上限
	{NULL,	eDataTypeNumber,		6,		1,		0,		100,	NULL}		// 下限
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelSeisanJoken::CExcelSeisanJoken()
{
	m_sFileName = "worktime-data.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 9;

	SetKeys(1, eColSublineName);
}

BOOL CExcelSeisanJoken::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}
