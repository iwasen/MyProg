// ExcelKittingTemplate.cpp: CExcelKittingTemplate クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelKittingTemplate.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

// エクセルカラム情報
static CExcelFile::SColumnInfo s_aColumnInfo[] = {
//	ﾃﾞﾌｫﾙﾄ	型						桁数	小数	最小			最大			有効文字
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"123"},		// 削除ﾌﾗｸﾞ
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"123"},		// 追加ﾌﾗｸﾞ
	{NULL,	eDataTypeAlphaNumKana,	32,		0,		0,				0,				NULL},		// ｻﾌﾞﾗｲﾝ名
	{NULL,	eDataTypeAlphaNum,		80,		0,		0,				0,				NULL},		// 工程記号
	{NULL,	eDataTypeAlphaNum,		32,		0,		0,				0,				NULL},		// 工程番号
	{NULL,	eDataTypeKoteiName,		128,	0,		0,				0,				NULL},		// 工程名称
	{NULL,	eDataTypeKoteiName,		128,	0,		0,				0,				NULL},		// 要素作業群名称
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// 自動／手動(要素作業群)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// 作業者
	{NULL,	eDataTypeNumber,		8,		0,		0,				9999,			NULL},		// 作業No.
	{NULL,	eDataTypeAll,			128,	0,		0,				0,				NULL},		// 作業内容
	{NULL,	eDataTypeNumber,		8,		0,		0,				0,				NULL},		// 要素作業No.
	{NULL,	eDataTypeAlphaNum,		/*8*/32,0,		0,				0,				"/*+-"},	// 演算式
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S1
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// S2
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// M
	{"1",	eDataTypeNumber,		10,		4,		-9999.9999,		9999.9999,		NULL},		// N
	{"0",	eDataTypeNumber,		12,		6,		-9999.999999,	9999.999999,	NULL},		// 演算後の作業時間
	{"1",	eDataTypeNumber,		4,		0,		1,				9999,			NULL},		// 発生頻度
	{"0",	eDataTypeNumber,		4,		0,		0,				9999,			NULL},		// 繰り返し回数
	{"0",	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// 台あたりの要素作業毎作業時間
	{NULL,	eDataTypeAll,			256,	0,		0,				0,				NULL},		// ｺﾒﾝﾄ
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"1"},		// ﾊﾞｯｸｱｯﾌﾟ工程
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// 自動／手動(工程)
	{NULL,	eDataTypeNumber,		12,		0,		0,				999999999999,	NULL},		// 要素作業群順番
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// FMEAﾌﾗｸﾞ
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// 定期点検ﾌﾗｸﾞ
	{NULL,	eDataTypeAll,			32,		0,		0,				0,				NULL},		// 定期点検頻度
	{NULL,	eDataTypeAll,			6,		0,		0,				0,				NULL},		// 固定部/変動部ﾌﾗｸﾞ
	{NULL,	eDataTypeNone,			2,		0,		0,				0,				"Yy"},		// ﾎﾞﾄﾙﾌﾗｸﾞ
	{NULL,	eDataTypeAll,			10,		0,		0,				0,				NULL},		// 作業ﾌﾗｸﾞ
	{NULL,	eDataTypeAll,			1,		0,		0,				0,				NULL},		// ﾜ-ｸ待ちﾌﾗｸﾞ
	{NULL,	eDataTypeAll,			1,		0,		0,				0,				NULL},		// 基準(1)or工程(2)ﾌﾗｸﾞ
	{NULL,	eDataTypeNumber,		21,		0,		0,				0,				NULL},		// 分割番号
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// 分割合計
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// 作業群合計時間
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.999999,	NULL},		// 比率計算後作業群合計時間
	{NULL,	eDataTypeNumber,		21,		6,		0,				9999.1000000,	NULL},		// 工程内合計標準時間
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				9999.1000000,	NULL},		// 立ち位置(番号)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// ｴﾝｼﾞﾝ向き(記号)
	{NULL,	eDataTypeAlphaNum,		256,	0,		0,				0,				NULL},		// 姿勢No
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// 重量No
	{NULL,	eDataTypeNone,			1,		0,		0,				0,				"1234"},	// 区分
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// 架台高さ
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// ﾊﾟﾚｯﾄ高さ
	{"0",	eDataTypeNone,			1,		0,		0,				0,				"01"},		// 助力装置
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL},		// 設備(高さ/幅)
	{NULL,	eDataTypeAlphaNum,		64,		0,		0,				0,				NULL}		// 工具(距離/重量)
};

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CExcelKittingTemplate::CExcelKittingTemplate()
{
	m_sFileName = "";
	m_pColumnInfo = s_aColumnInfo;
	m_nColumnInfo = sizeof(s_aColumnInfo) / sizeof(CExcelFile::SColumnInfo);
	m_nFileNo = 8;
}

BOOL CExcelKittingTemplate::AccessCheck(LPCTSTR pFolderName)
{
	return FileAccessCheck(pFolderName, m_sFileName, TRUE);
}

BOOL CExcelKittingTemplate::ReadFile()
{
	if (!CExcelFile::ReadFile())
		return FALSE;

	GetKishuCols(eColSagyosha);
	GetKishuCols(eColYosoSagyogunJunban);
	GetKishuCols(eColSagyogunGokeiJikan);
	GetKishuCols(eColHiritsukeisangoSagyogunGokeiJikan);

	return TRUE;
}

BOOL CExcelKittingTemplate::FileDataCheck()
{
	CString sData;
	BOOL bError = FALSE;
	_variant_t vEmpty;

	SetCurrentSheet(0);

	// 行によるループ
	int nUsedRows = GetUsedRows();
	for (int nRow = 3; nRow <= nUsedRows; nRow++) {
		GetElement(nRow, eColSublineName, sData);
		if (sData.CollateNoCase("kitting") == 0) {
			// 列によるループ
			for (int i = 0; i < m_nColumnInfo; i++) {
				SColumnInfo *pColumnInfo = m_pColumnInfo + i;
				int nCol = GetColumnNo(i + 1);

				// データ取得
				GetElement(nRow, nCol, sData);

				// エラーチェック
				if (!sData.IsEmpty()) {
					if ((pColumnInfo->cDataCheck.m_nDataType != eDataTypeNumber && !pColumnInfo->cDataCheck.LengthCheck(sData))
							|| !pColumnInfo->cDataCheck.ValidCharCheck(sData)
							|| !pColumnInfo->cDataCheck.RangeCheck(sData)) {
						// エラーログ
						ErrorFileLog("入力値不正", nRow, nCol);

						// エラーならデフォルト値をセット
						if (pColumnInfo->pDefault == NULL)
							PutElement(nRow, nCol, vEmpty);
						else
							PutElement(nRow, nCol, pColumnInfo->pDefault);

						bError = TRUE;
					}
				}
			}
		}
	}

	return !bError;
}
