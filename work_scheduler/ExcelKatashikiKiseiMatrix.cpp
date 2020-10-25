// ExcelKatashikiKiseiMatrix.cpp: CExcelKatashikiKiseiMatrix クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKatashikiKiseiMatrix.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小	最大			有効文字
	{NULL,	eDataTypeNumber,		8,		0,		0,		0,				NULL},		// No.
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,				NULL},		// サブライン名称
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,				NULL},		// 工程記号
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,				NULL},		// 工程番号
	{NULL,	eDataTypeAll,			0,		0,		0,		0,				NULL},		// （予備）
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,				NULL},		// 車種名
	{NULL,	eDataTypeNumber,		12,		0,		0,		999999999999,	NULL},		// 要素作業表作業群順番
	{NULL,	eDataTypeNumber,		21,		6,		0,		9999.999999,	NULL},		// 要素作業群合計
	{NULL,	eDataTypeAll,			6,		0,		0,		0,				NULL},		// 自/手
	{NULL,	eDataTypeAlphaNumKana,	64,		0,		0,		0,				NULL},		// 部品名/設備名
	{NULL,	eDataTypeAll,			128,	0,		0,		0,				NULL},		// 工程名
	{NULL,	eDataTypeAll,			128,	0,		0,		0,				NULL},		// 要素作業群名
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,				NULL},		// （規制内容）上段
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,				NULL},		// （規制理由）下段
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelKatashikiKiseiMatrix::CExcelKatashikiKiseiMatrix()
{
	m_sFileName = "p-mtx.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nMaxSheet = 0;
	m_nFileNo = 7;
// Modify ... ( CHANGE )
	m_bColumnCheck = 0;
// By Y.Itabashi (xxxxx) 2007.02.20
}

BOOL CExcelKatashikiKiseiMatrix::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, TRUE);
}
