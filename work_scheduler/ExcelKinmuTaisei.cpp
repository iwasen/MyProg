// ExcelKinmuTaisei.cpp: CExcelKinmuTaisei クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKinmuTaisei.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小	最大		有効文字
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,		0,			NULL},		// ｻﾌﾞﾗｲﾝ名称
	{NULL,	eDataTypeNumber,		6,		0,		0,		999999,		NULL},		// 月間稼働時間
	{NULL,	eDataTypeNumber,		1,		0,		0,		1,			NULL},		// 強制駆動ﾌﾗｸﾞ
	{"0",	eDataTypeNumber,		10,		4,		0,		100,		NULL},		// 抜き取り率
	{"100",	eDataTypeNumber,		64,		1,		0,		100,		NULL}		// 稼働率
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelKinmuTaisei::CExcelKinmuTaisei()
{
	m_sFileName = "worktime.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 2;

	SetKeys(1, eColSublineName);
}

BOOL CExcelKinmuTaisei::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}

CString CExcelKinmuTaisei::GetNewFileName()
{
	return g_pSystem->m_cSaveTime.Format("%y%m%d_%H%M%S_") + m_sFileName;
}
