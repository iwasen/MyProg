// ExcelLineKiseiMatrix.cpp: CExcelLineKiseiMatrix クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelLineKiseiMatrix.h"

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
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,				NULL},		// 工程名
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,				NULL},		// 要素作業群名
	{NULL,	eDataTypeAll,			6,		0,		0,		0,				NULL},		// ENG姿勢-現状
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG姿勢-正立Fr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG姿勢-正立Rr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG姿勢-倒立Fr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG姿勢-倒立Rr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENG姿勢-直立ﾄｯﾌﾟ
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL}		// ENG姿勢-直立ﾎﾞﾄﾑ
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelLineKiseiMatrix::CExcelLineKiseiMatrix()
{
	m_sFileName = "e-mtx.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 6;
}

BOOL CExcelLineKiseiMatrix::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "*_" + m_sFileName, TRUE);
}

// --Add 2007/03/07 aizawa 不懸D-147（追加修正）
CString CExcelLineKiseiMatrix::GetNewFileName()
{
	return CGlobal::GetFileNameFromPath(m_sPathName);
}
// --Add
