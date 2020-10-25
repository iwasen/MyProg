// ExcelYosoSagyoHyo.cpp: CExcelYosoSagyoHyo ƒNƒ‰ƒX‚ÌƒCƒ“ƒvƒŠƒƒ“ƒe[ƒVƒ‡ƒ“
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelYosoSagyoHyo.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// ƒGƒNƒZƒ‹ƒJƒ‰ƒ€î•ñ
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ÃÞÌ«ÙÄ	Œ^						Œ…”	¬”	Å¬			Å‘å			—LŒø•¶Žš
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"123"},		// íœÌ×¸Þ
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"123"},		// ’Ç‰ÁÌ×¸Þ
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,				0,				NULL},		// »ÌÞ×²Ý–¼
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,				0,				NULL},		// H’ö‹L†
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,				0,				NULL},		// H’ö”Ô†
	{NULL,	eDataTypeKoteiName,		128,	0,		0,				0,				NULL},		// H’ö–¼Ì
	{NULL,	eDataTypeKoteiName,		128,	0,		0,				0,				NULL},		// —v‘fì‹ÆŒQ–¼Ì
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// Ž©“®^Žè“®(—v‘fì‹ÆŒQ)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// ì‹ÆŽÒ
	{NULL,	eDataTypeNumber,		8,		0,		0,				9999,			NULL},		// ì‹ÆNo.
	{NULL,	eDataTypeAll,			128,	0,		0,				0,				NULL},		// ì‹Æ“à—e
	{NULL,	eDataTypeNumber,		8,		0,		0,				0,				NULL},		// —v‘fì‹ÆNo.
	{NULL,	eDataTypeAlphaNum,		/*8*/32,0,		0,				0,				"/*+-"},	// ‰‰ŽZŽ®
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S1
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S2
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// M
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// N
	{"0",	eDataTypeNumber,		12,		6,		-9999.999999,	9999.999999,	NULL},		// ‰‰ŽZŒã‚Ìì‹ÆŽžŠÔ
	{"1",	eDataTypeNumber,		4,		0,		1,				9999,			NULL},		// ”­¶•p“x
	{"0",	eDataTypeNumber,		4,		0,		0,				9999,			NULL},		// ŒJ‚è•Ô‚µ‰ñ”
// Modify ... ( CHANGE )
	{"0",	eDataTypeNumber,		21,		6,		-9999.999999,	9999.999999,	NULL},		// ‘ä‚ ‚½‚è‚Ì—v‘fì‹Æ–ˆì‹ÆŽžŠÔ
//	{"0",	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// ‘ä‚ ‚½‚è‚Ì—v‘fì‹Æ–ˆì‹ÆŽžŠÔ
// By Y.Itabashi (xxxxx) 2007.02.05
	{NULL,	eDataTypeAll,			256,	0,		0,				0,				NULL},		// ºÒÝÄ
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"1"},		// ÊÞ¯¸±¯ÌßH’ö
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// Ž©“®^Žè“®(H’ö)
	{NULL,	eDataTypeNumber,		12,		0,		0,				999999999999,	NULL},		// —v‘fì‹ÆŒQ‡”Ô
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// FMEAÌ×¸Þ
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// ’èŠú“_ŒŸÌ×¸Þ
	{NULL,	eDataTypeAll,			32,		0,		0,				0,				NULL},		// ’èŠú“_ŒŸ•p“x
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// ŒÅ’è•”/•Ï“®•”Ì×¸Þ
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// ÎÞÄÙÌ×¸Þ
	{NULL,	eDataTypeAll,			10,		0,		0,				0,				NULL},		// ì‹ÆÌ×¸Þ
	{NULL,	eDataTypeAll,			1,		0,		0,				0,				NULL},		// Ü-¸‘Ò‚¿Ì×¸Þ
	{NULL,	eDataTypeAll,			1,		0,		0,				0,				NULL},		// Šî€(1)orH’ö(2)Ì×¸Þ
	{NULL,	eDataTypeNumber,		21,		0,		0,				0,				NULL},		// •ªŠ„”Ô†
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// •ªŠ„‡Œv
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// ì‹ÆŒQ‡ŒvŽžŠÔ
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// ”ä—¦ŒvŽZŒãì‹ÆŒQ‡ŒvŽžŠÔ
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.1000000,	NULL},		// H’ö“à‡Œv•W€ŽžŠÔ
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				9999.1000000,	NULL},		// —§‚¿ˆÊ’u(”Ô†)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// ´Ý¼ÞÝŒü‚«(‹L†)
	{NULL,	eDataTypeAlphaNum,		256,	0,		0,				0,				NULL},		// Žp¨No
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// d—ÊNo
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"1234"},	// ‹æ•ª
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// ‰Ë‘ä‚‚³
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// ÊßÚ¯Ä‚‚³
	{"0",	eDataTypeNone,			1,		0,		0,				0,				"01"},		// •—Í‘•’u
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// Ý”õ(‚‚³/•)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL}		// H‹ï(‹——£/d—Ê)
};

//////////////////////////////////////////////////////////////////////
// \’z/Á–Å
//////////////////////////////////////////////////////////////////////

CExcelYosoSagyoHyo::CExcelYosoSagyoHyo()
{
	m_sFileName = "element.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 5;
}

BOOL CExcelYosoSagyoHyo::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

BOOL CExcelYosoSagyoHyo::ReadFile()
{
	if (!CExcelFile::ReadFile())
		return FALSE;

	GetKishuCols(eColSagyosha);
	GetKishuCols(eColYosoSagyogunJunban);
	GetKishuCols(eColSagyogunGokeiJikan);
	GetKishuCols(eColHiritsukeisangoSagyogunGokeiJikan);

	return TRUE;
}

CString CExcelYosoSagyoHyo::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
