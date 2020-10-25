// ExcelLineKiseiError.cpp: CExcelLineKiseiError クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelLineKiseiError.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小	最大	有効文字
	{NULL,	eDataTypeAlphaNum,		10,		0,		0,		0,		"/"},		// 日時
	{NULL,	eDataTypeAlphaNum,		8,		0,		0,		0,		":"},		// 時間
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// 移動ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// 移動工程番号
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// 移動工程名
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// 移動要素作業群名
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// 移動先ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// 移動先の前の工程番号
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// 移動先の前の工程名
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// 移動先の前の要素作業群名
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// 移動先の後の工程番号
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// 移動先の後の工程名
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// 移動先の後の要素作業群名
	{NULL,	eDataTypeAll,			0,		0,		0,		0,		NULL},		// 工順規制違反

};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelLineKiseiError::CExcelLineKiseiError()
{
	m_sFileName = "e-mtx-check.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nMaxSheet = MAX_KISHU;
	m_nFileNo = 12;
	m_bSetSheetName = 1;

// Modify ... ( ADD )
	m_bFileLock = 0;
	nErrorDataSize = 0;
// By Y.Itabashi (xxxxx) 2007.02.19
}

BOOL CExcelLineKiseiError::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}

BOOL CExcelLineKiseiError::ReadFile()
{
	m_sPathName = CGlobal::MakePathName(m_sFolderName, m_sFileName);

	if (!CExcelFile::ReadFile()){
		m_sPathName.Empty();
		return FALSE;
	}

	return TRUE;
}
