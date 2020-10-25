// FindDB.cpp: CFindDB クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "MMLib.h"
#include "FindDB.h"
#include "MMLibDB.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CFindDB::CFindDB()
{
	m_Pcserve.Conxxxt(CHT_LOCAL, "", "MMLIB");
}

CFindDB::~CFindDB()
{
	m_FindDB.DBClose();
	m_Pcserve.Disconxxxt();
}

BOOL CFindDB::Open(BOOL bCreate)
{
	static DBF_I   field[] = {
		{"FINDID",	    'C', LEN_ID,       0},	// 検索データＩＤ
		{"LIBRARYID",	'C', LEN_ID,       0},	// 検索対象ライブラリＩＤ
		{"FINDTYPE",	'C', LEN_FINDTYPE, 0},	// 検索種別
		{"FNAME",		'C', LEN_TITLE,    0},	// フォルダ名称

		{"CDATEFROM1",	'C', LEN_DATE,     0},	// 撮影年月日時分From
		{"CDATETO1",	'C', LEN_DATE,	   0},	// 撮影年月日時分To
		{"CDATEBEFO1",	'C', LEN_DATE,     0},	// 撮影年月日時分Before
		{"UDATEFROM1",	'C', LEN_DATE,     0},	// 更新年月日時分From
		{"UDATETO1",	'C', LEN_DATE,     0},	// 更新年月日時分To
		{"UDATEBEFO1",	'C', LEN_DATE,     0},	// 更新年月日時分Before
		{"CREATOR1",	'C', LEN_CREATOR,  0},	// 作成者
		{"CODE1",		'C', LEN_CODE,     0},	// コード
		{"TITLE1",		'C', LEN_TITLE,    0},	// 画像タイトル
		{"REMARK11",	'C', LEN_REMARK,   0},	// 備考
		{"REMARK21",	'C', LEN_KEYWORD,  0},	// キーワード
		{"TEXT1",		'C', LEN_TEXT,	   0},	// テキストデータ
		{"MATCHING1",	'C', LEN_MATCHING, 0},	// マッチングオプション

		{"CDATEFROM2",	'C', LEN_DATE,     0},	// 撮影年月日時分From
		{"CDATETO2",	'C', LEN_DATE,	   0},	// 撮影年月日時分To
		{"CDATEBEFO2",	'C', LEN_DATE,     0},	// 撮影年月日時分Before
		{"UDATEFROM2",	'C', LEN_DATE,     0},	// 更新年月日時分From
		{"UDATETO2",	'C', LEN_DATE,     0},	// 更新年月日時分To
		{"UDATEBEFO2",	'C', LEN_DATE,     0},	// 更新年月日時分Before
		{"CREATOR2",	'C', LEN_CREATOR,  0},	// 作成者
		{"CODE2",		'C', LEN_CODE,     0},	// コード
		{"TITLE2",		'C', LEN_TITLE,    0},	// 画像タイトル
		{"REMARK12",	'C', LEN_REMARK,   0},	// 備考
		{"REMARK22",	'C', LEN_KEYWORD,  0},	// キーワード
		{"TEXT2",		'C', LEN_TEXT,	   0},	// テキストデータ
		{"MATCHING2",	'C', LEN_MATCHING, 0},	// マッチングオプション

		{"CDATEFROM3",	'C', LEN_DATE,     0},	// 撮影年月日時分From
		{"CDATETO3",	'C', LEN_DATE,	   0},	// 撮影年月日時分To
		{"CDATEBEFO3",	'C', LEN_DATE,     0},	// 撮影年月日時分Before
		{"UDATEFROM3",	'C', LEN_DATE,     0},	// 更新年月日時分From
		{"UDATETO3",	'C', LEN_DATE,     0},	// 更新年月日時分To
		{"UDATEBEFO3",	'C', LEN_DATE,     0},	// 更新年月日時分Before
		{"CREATOR3",	'C', LEN_CREATOR,  0},	// 作成者
		{"CODE3",		'C', LEN_CODE,     0},	// コード
		{"TITLE3",		'C', LEN_TITLE,    0},	// 画像タイトル
		{"REMARK13",	'C', LEN_REMARK,   0},	// 備考
		{"REMARK23",	'C', LEN_KEYWORD,  0},	// キーワード
		{"TEXT3",		'C', LEN_TEXT,	   0},	// テキストデータ
		{"MATCHING3",	'C', LEN_MATCHING, 0},	// マッチングオプション

		{"CDATEFROM4",	'C', LEN_DATE,     0},	// 撮影年月日時分From
		{"CDATETO4",	'C', LEN_DATE,	   0},	// 撮影年月日時分To
		{"CDATEBEFO4",	'C', LEN_DATE,     0},	// 撮影年月日時分Before
		{"UDATEFROM4",	'C', LEN_DATE,     0},	// 更新年月日時分From
		{"UDATETO4",	'C', LEN_DATE,     0},	// 更新年月日時分To
		{"UDATEBEFO4",	'C', LEN_DATE,     0},	// 更新年月日時分Before
		{"CREATOR4",	'C', LEN_CREATOR,  0},	// 作成者
		{"CODE4",		'C', LEN_CODE,     0},	// コード
		{"TITLE4",		'C', LEN_TITLE,    0},	// 画像タイトル
		{"REMARK14",	'C', LEN_REMARK,   0},	// 備考
		{"REMARK24",	'C', LEN_KEYWORD,  0},	// キーワード
		{"TEXT4",		'C', LEN_TEXT,	   0},	// テキストデータ
		{"MATCHING4",	'C', LEN_MATCHING, 0},	// マッチングオプション

		{"CDATEFROM5",	'C', LEN_DATE,     0},	// 撮影年月日時分From
		{"CDATETO5",	'C', LEN_DATE,	   0},	// 撮影年月日時分To
		{"CDATEBEFO5",	'C', LEN_DATE,     0},	// 撮影年月日時分Before
		{"UDATEFROM5",	'C', LEN_DATE,     0},	// 更新年月日時分From
		{"UDATETO5",	'C', LEN_DATE,     0},	// 更新年月日時分To
		{"UDATEBEFO5",	'C', LEN_DATE,     0},	// 更新年月日時分Before
		{"CREATOR5",	'C', LEN_CREATOR,  0},	// 作成者
		{"CODE5",		'C', LEN_CODE,     0},	// コード
		{"TITLE5",		'C', LEN_TITLE,    0},	// 画像タイトル
		{"REMARK15",	'C', LEN_REMARK,   0},	// 備考
		{"REMARK25",	'C', LEN_KEYWORD,  0},	// キーワード
		{"TEXT5",		'C', LEN_TEXT,	   0},	// テキストデータ
		{"MATCHING5",	'C', LEN_MATCHING, 0}	// マッチングオプション
	};
	CString dirName;
	CString pathName;
	BOOL bCreateIndex = FALSE;
	int nIdxNo;
	int err;

	dirName = g_pMMLibDB->m_CacheDir;
open:
	pathName = dirName + FN_FIND_DBF;
	if ((err = m_FindDB.DBOpen(m_Pcserve, pathName)) != 0) {
		bCreateIndex = TRUE;
		if (err = ERROR_DB_NOFILE && bCreate)
			err = m_FindDB.DBCreate(m_Pcserve, pathName, field, sizeof(field) / sizeof(DBF_I));
		if (err != 0)
			return FALSE;
	} else {
		int nRecSize;
		CPSDB newDB;

		if ((err = m_FindDB.DBRecSize(&nRecSize)) != 0)
			return err;

		if (nRecSize - 1 < sizeof(FINDBUF)) {
			CString pathName2 = dirName + "FINDTMP.DBF";
			if ((err = newDB.DBCreate(m_Pcserve, pathName2, field, sizeof(field) / sizeof(DBF_I))) == 0) {
				if ((err = newDB.DBCopy(m_FindDB)) != 0)
					return err;
				m_FindDB.DBClose();
				newDB.DBClose();
				m_Pcserve.CFDelete(pathName);
				m_Pcserve.CFRename(pathName2, pathName);
				goto open;
			}
		}
	}

	pathName = dirName + FN_FIND_NDX;
	if (bCreateIndex || m_FindDB.DBIndex(pathName, &nIdxNo) != 0) {
		if ((err = m_FindDB.DBIdxCreate(pathName, "FINDID", 1, &nIdxNo)) != 0)
			return FALSE;
	}

	return TRUE;
}

BOOL CFindDB::ReadFind(LPCTSTR pLibraryID, FINDREC *pFindRec)
{
	FINDBUF findBuf;
	int nRead;
	int err;

	if (pLibraryID != NULL) {
		CString filter;
		filter.Format("LIBRARYID=%s", pLibraryID);
		if ((err = m_FindDB.DBSetFilter(filter)) != 0)
			return FALSE;
	}

	if ((err = m_FindDB.DBReadNext(1, &findBuf, &nRead)) != 0)
		return FALSE;

	if (nRead == 0)
		return FALSE;

	m_FindDB.DBGetFieldBuf(&findBuf, pFindRec);

	return TRUE;
}

BOOL CFindDB::AddFind(FINDREC *pFindRec)
{
	FINDBUF findBuf;
	FINDREC findRec;
	int err;

	if ((err = MakeFindID(pFindRec->findID)) != 0)
		return FALSE;

	m_FindDB.DBSetFieldBuf(&findBuf, pFindRec);

	if ((err = m_FindDB.DBSetFilter(NULL)) != 0)
		return FALSE;

	if ((err = m_FindDB.DBStoreUniq(&findBuf)) != 0)
		return FALSE;

	return TRUE;
}

BOOL CFindDB::UpdateFind(FINDREC *pFindRec)
{
	FINDBUF findBuf;
	int err;

	m_FindDB.DBSetFieldBuf(&findBuf, pFindRec);

	if ((err = m_FindDB.DBSetFilter(NULL)) != 0)
		return FALSE;

	if ((err = m_FindDB.DBUpdateKey(pFindRec->findID, LEN_ID, &findBuf)) != 0)
		return FALSE;

	return TRUE;
}

BOOL CFindDB::DeleteFind(LPCTSTR did)
{
	int err;

	if ((err = m_FindDB.DBSetFilter(NULL)) != 0)
		return FALSE;

	if ((err = m_FindDB.DBDeleteKey(did, LEN_ID, 1)) != 0)
		return FALSE;

	return TRUE;
}

int CFindDB::MakeFindID(CString &id)
{
	time_t ltime;
	int find;
	int err;

	time(&ltime);

	if ((err = m_FindDB.DBSetFilter(NULL)) != 0)
		return err;

	for (;;) {
		MakeID('S', (DWORD)ltime, id);

		if ((err = m_FindDB.DBSearch2(id, LEN_ID, &find)) != 0)
			return err;

		if (find != 1)
			break;

		ltime++;
	}

	return 0;
}
