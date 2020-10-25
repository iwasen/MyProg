// ExcelKyotsuKotei.cpp: CExcelKyotsuKotei クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKyotsuKotei.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小	最大	有効文字
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// 工程名
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// 要素作業群名
	{NULL,	eDataTypeAll,			6,		0,		0,		0,		NULL},		// 自動/手動
	{NULL,	eDataTypeNumber,		8,		0,		0,		99999,	NULL}		// 共通工程ｸﾞﾙｰﾌﾟ番号
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelKyotsuKotei::CExcelKyotsuKotei()
{
	m_sFileName = "com_process.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 11;

	SetKeys(3, eColSublineName, eColKoteiName, eColYosoSagyogunName);
}

BOOL CExcelKyotsuKotei::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}

BOOL CExcelKyotsuKotei::ReadFile()
{
	if (!CExcelFile::ReadFile())
		return FALSE;

	GetKishuCols(eColKyotsuKoteiGroupNo);

	return TRUE;
}
