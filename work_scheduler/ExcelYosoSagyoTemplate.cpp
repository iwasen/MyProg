// ExcelYosoSagyoTemplate.cpp: CExcelYosoSagyoTemplate クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelYosoSagyoTemplate.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小			最大			有効文字
	{NULL,	eDataTypeNumber,		8,		0,		0,				0,				NULL},		// 要素作業No.
	{NULL,	eDataTypeAll,			128,	0,		0,				0,				NULL},		// 作業内容
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,				0,				"/*+-"},	// 演算式
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S1
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S2
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// M
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// N
	{"0",	eDataTypeNumber,		12,		6,		-9999.999999,	9999.999999,	NULL},		// 演算後の作業時間
	{"1",	eDataTypeNumber,		4,		0,		1,				9999,			NULL},		// 発生頻度
	{"0",	eDataTypeNumber,		4,		0,		0,				9999,			NULL},		// 繰り返し回数
	{"0",	eDataTypeNumber,		21,		6,		-9999.999999,	9999.999999,	NULL},		// 台あたりの要素作業毎作業時間
	{NULL,	eDataTypeAll,			256,	0,		0,				0,				NULL},		// ｺﾒﾝﾄ
	{NULL,	eDataTypeAll,			10,		0,		0,				0,				NULL},		// 作業ﾌﾗｸﾞ
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelYosoSagyoTemplate::CExcelYosoSagyoTemplate()
{
	m_sFileName = "";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
// Modify ... ( CHANGE )
	m_nFileNo = 17;
//	m_nFileNo = 0;
// By Y.Itabashi (xxxxx) 2007.02.20
}
