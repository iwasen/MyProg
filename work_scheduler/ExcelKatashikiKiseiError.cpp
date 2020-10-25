// ExcelKatashikiKiseiError.cpp: CExcelKatashikiKiseiError ƒNƒ‰ƒX‚ÌƒCƒ“ƒvƒŠƒƒ“ƒe[ƒVƒ‡ƒ“
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKatashikiKiseiError.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// ƒGƒNƒZƒ‹ƒJƒ‰ƒ€î•ñ
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ÃŞÌ«ÙÄ	Œ^						Œ…”	¬”	Å¬	Å‘å	—LŒø•¶š
	{NULL,	eDataTypeAlphaNum,		10,		0,		0,		0,		"/"},		// “ú‚É‚¿
	{NULL,	eDataTypeAlphaNum,		8,		0,		0,		0,		":"},		// ŠÔ
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// ˆÚ“®»ÌŞ×²İ–¼
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// ˆÚ“®H’ö”Ô†
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// ˆÚ“®H’ö–¼
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// ˆÚ“®—v‘fì‹ÆŒQ–¼
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// ˆÚ“®æ»ÌŞ×²İ–¼
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// ˆÚ“®æH’ö”Ô†
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// ˆÚ“®æH’ö–¼
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// ˆÚ“®æ—v‘fì‹ÆŒQ–¼
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,		NULL},		// ˆá”½»ÌŞ×²İ–¼
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,		NULL},		// ˆá”½H’ö”Ô†
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// ˆá”½H’ö–¼
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,		NULL},		// ˆá”½—v‘fì‹ÆŒQ–¼
	{NULL,	eDataTypeAll,			0,		0,		0,		0,		NULL},		// H‡‹K§ˆá”½
};

//////////////////////////////////////////////////////////////////////
// \’z/Á–Å
//////////////////////////////////////////////////////////////////////

CExcelKatashikiKiseiError::CExcelKatashikiKiseiError()
{
	m_sFileName = "p-mtx-check.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nMaxSheet = MAX_KISHU;
	m_nFileNo = 13;
	m_bSetSheetName = 1;

// Modify ... ( ADD )
	m_bFileLock = 0;
	nErrorDataSize = 0;
// By Y.Itabashi (xxxxx) 2007.02.19
}

BOOL CExcelKatashikiKiseiError::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, FALSE);
}

BOOL CExcelKatashikiKiseiError::ReadFile()
{
	m_sPathName = CGlobal::MakePathName(m_sFolderName, m_sFileName);

	if (!CExcelFile::ReadFile()){
		m_sPathName.Empty();
		return FALSE;
	}

	return TRUE;
}
