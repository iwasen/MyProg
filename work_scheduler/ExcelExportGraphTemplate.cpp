// ExcelExportGraphTemplate.cpp: CExcelExportGraphTemplate クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelExportGraphTemplate.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小			最大			有効文字
/*
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,				0,				NULL},		// ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// 作業者
	{NULL,	eDataTypeAll,			128,	0,		0,				0,				NULL},		// 作業内容
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// 自動／手動(要素作業群)
	{NULL,	eDataTypeNumber,		2,		0,		0,				99,				NULL},		// 姿勢
	{NULL,	eDataTypeAll,			256,	0,		0,				0,				NULL},		// 工数
	{NULL,	eDataTypeAll,			256,	0,		0,				0,				NULL},		// 備考
	*/
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// 作業者
	{NULL,	eDataTypeAll,		128,		0,		0,				0,				NULL},		// 作業内容
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// 自動／手動(要素作業群)
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// 姿勢
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// 工数
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// 備考
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// 作業者
	{NULL,	eDataTypeAll,		128,		0,		0,				0,				NULL},		// 作業内容
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// 自動／手動(要素作業群)
	{NULL,	eDataTypeAll,		256,		0,		0,				0,				NULL},		// 自動／手動(要素作業群)
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelExportGraphTemplate::CExcelExportGraphTemplate()
{
	m_sFileName = "prints.xls";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = 36;
	m_nMaxSheet = 121;
	m_nFileNo = 15;
	m_bSetSheetName = 1;
	m_bDeleteSheet = 1;
	m_bColumnCheck = 0;
	m_bFileLock = 0;
}

CExcelExportGraphTemplate::~CExcelExportGraphTemplate()
{

}

BOOL CExcelExportGraphTemplate::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, TRUE);
}

BOOL CExcelExportGraphTemplate::AccessCheck2(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, "??????_??????_" + m_sFileName, TRUE);
}


CString CExcelExportGraphTemplate::GetNewFileName()
{
	return m_sTemplateName;
}

CString CExcelExportGraphTemplate::GetNewFileName2()
{
	return CTime::GetCurrentTime().Format("%y%m%d_%H%M%S_") + m_sFileName;
}


void CExcelExportGraphTemplate::SetExportCondition(bool* aSubline, bool* aKishu, bool bWaritsuke, bool bZenkishu, bool bKajuHeikin)
{
	int iSubline, iKishu;
	for ( iSubline=0; iSubline < MAX_SUBLINE; iSubline++ )
		m_aCheckedSubline[iSubline] = aSubline[iSubline];

	for ( iKishu=0; iKishu < MAX_KISHU; iKishu++ )
		m_aCheckedKishu[iKishu] = aKishu[iKishu];

	m_bCheckedZenkishu = bZenkishu;
	m_bCheckedKajuHeikin = bKajuHeikin;
	m_bCheckedWaritsuke = bWaritsuke;

}
