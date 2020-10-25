// ExcelDSTRSanshutsu.cpp: CExcelDSTRSanshutsu クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelDSTRSanshutsu.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型					桁数	小数	最小	最大		有効文字
	{NULL,	eDataTypeAlphaNum,	6,		0,		0,		0,			"全機種"},		// 機種
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 標準時間
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 抜取り標準時間
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 定期点検時間
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 品質ﾛｽ
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// ﾊﾞｯｸｱｯﾌﾟﾛｽ
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 設備不具合ﾛｽ
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 習熟ﾛｽ
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 混流ﾛｽ
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// ﾗｲﾝ停止ﾛｽ
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 編成ﾛｽ
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 編成+混流+ﾗｲﾝ停止ﾛｽ
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 複合能率分
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL},		// 管理停止時間
	{"0",	eDataTypeNumber,	64,		4,		0,		166666666,	NULL}		// DST
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelDSTRSanshutsu::CExcelDSTRSanshutsu()
{
	m_sFileName = "dstr.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo) - 3;
	m_nFileNo = 4;

	SetKeys(1, eColKishuName);
}

BOOL CExcelDSTRSanshutsu::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

CString CExcelDSTRSanshutsu::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
