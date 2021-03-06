// ExcelLineKiseiMatrix.cpp: CExcelLineKiseiMatrix NXΜCve[V
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

// GNZJξρ
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ΓήΜ«ΩΔ	^							¬	Ε¬	Εε			LψΆ
	{NULL,	eDataTypeNumber,		8,		0,		0,		0,				NULL},		// No.
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,				NULL},		// TuCΌΜ
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,		0,				NULL},		// HφL
	{NULL,	eDataTypeNumber,		32,		0,		0,		0,				NULL},		// HφΤ
	{NULL,	eDataTypeAll,			0,		0,		0,		0,				NULL},		// i\υj
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,				NULL},		// ΤνΌ
	{NULL,	eDataTypeNumber,		12,		0,		0,		999999999999,	NULL},		// vfμΖ\μΖQΤ
	{NULL,	eDataTypeNumber,		21,		6,		0,		9999.999999,	NULL},		// vfμΖQv
	{NULL,	eDataTypeAll,			6,		0,		0,		0,				NULL},		// ©/θ
	{NULL,	eDataTypeAlphaNumKana,	64,		0,		0,		0,				NULL},		// iΌ/έυΌ
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,				NULL},		// HφΌ
	{NULL,	eDataTypeKoteiName,		128,	0,		0,		0,				NULL},		// vfμΖQΌ
	{NULL,	eDataTypeAll,			6,		0,		0,		0,				NULL},		// ENGp¨-»σ
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENGp¨-³§Fr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENGp¨-³§Rr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENGp¨-|§Fr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENGp¨-|§Rr
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL},		// ENGp¨-Ό§Δ―Μί
	{NULL,	eDataTypeAll,			1,		0,		0,		0,				NULL}		// ENGp¨-Ό§ΞήΔΡ
};

//////////////////////////////////////////////////////////////////////
// \z/ΑΕ
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

// --Add 2007/03/07 aizawa sD-147iΗΑC³j
CString CExcelLineKiseiMatrix::GetNewFileName()
{
	return CGlobal::GetFileNameFromPath(m_sPathName);
}
// --Add
