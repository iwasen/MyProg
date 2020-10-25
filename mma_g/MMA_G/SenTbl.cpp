#include "StdAfx.h"
#include "SenTbl.h"
#include "GlobalData.h"

#define	FN_SEN_TBL	"sen_tbl.tbl"
#define	CNT_ITEM	13

#define	ITEM_C4		0
#define	ITEM_C3		1
#define	ITEM_C2		2
#define	ITEM_C1		3
#define	ITEM_C0		4
#define	ITEM_D4		5
#define	ITEM_D3		6
#define	ITEM_D2		7
#define	ITEM_D1		8
#define	ITEM_D0		9

CSenTbl::CSenTbl(void)
{
}

CSenTbl::~CSenTbl(void)
{
	m_Dat.RemoveAll();
	m_tbl.RemoveAll();
}

// テーブル生成
bool CSenTbl::SetTbl(void)
{
	INT_PTR	i, cnt;
	SEN_TBL	tbl;

	m_Dat.RemoveAll();
	m_tbl.RemoveAll();

	// テーブルデータ読み込み
	if (! Read())
		return false;

	cnt = m_Dat.GetSize();
	// データが１件もなければリターン
	if (cnt < 2)
		return false;

	cnt--;
	for(i = 0; i < cnt; i++) {
		// 読み込みデータから各アイテムを取得
		if (! GetItem(m_Dat.GetAt(i + 1), &tbl))
			return false;
		m_tbl.Add(tbl);
	}
	return true;
}

// テーブルデータ読み込み
bool CSenTbl::Read(void)
{
	CStdioFile	fp;
	CFileException fileException;
	CString path;

	m_Dat.RemoveAll();

	path.Format("%s\\%s", gExePath, FN_SEN_TBL);
	if (! fp.Open(path, CFile::modeRead, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", path, fileException.m_cause );
		return false;
	}

	CString	str;
	for(;;) {
		if (! fp.ReadString(str))
			break;
		m_Dat.Add(str);
	}
	fp.Close();

	return true;
}

// 読み込みデータから各アイテムを取得
bool CSenTbl::GetItem(LPCSTR dat, SEN_TBL * tbl)
{
	char buf[256], *token, *nexttoken;
	char *sep = ",\0";
	int	tokenCnt;

	strcpy_s(buf, dat);
	tokenCnt = 0;
	// トークン取得
	token = strtok_s( buf, sep, &nexttoken);
	while (token != NULL) {
		switch(tokenCnt) {
		case 0:	// SENSOR_ID
			tbl->sen_id = atoi(token);
			break;
		case 1:	// CH
			tbl->ch = atoi(token);
			break;
		case 2:	// TYPE
			tbl->type = atoi(token);
			break;
		default:	// 定数
			tbl->item[tokenCnt - 3] = strtod(token, NULL);
			break;
		}
		tokenCnt++;
		if (tokenCnt >= CNT_ITEM)
			break;
		token = strtok_s( NULL, sep, &nexttoken);
	}
	return (tokenCnt == CNT_ITEM);
}

// RSU情報から係数を取得する
bool CSenTbl::GetTbl(int id, int ch, KEISUU_INFO* info)
{
	INT_PTR	i, cnt;

	cnt = m_tbl.GetSize();

	for(i = 0; i < cnt; i++) {
		if (id == m_tbl[i].sen_id && ch == m_tbl[i].ch) {
			info->C4 = m_tbl[i].item[ITEM_C4];
			info->C3 = m_tbl[i].item[ITEM_C3];
			info->C2 = m_tbl[i].item[ITEM_C2];
			info->C1 = m_tbl[i].item[ITEM_C1];
			info->C0 = m_tbl[i].item[ITEM_C0];
			info->D4 = m_tbl[i].item[ITEM_D4];
			info->D3 = m_tbl[i].item[ITEM_D3];
			info->D2 = m_tbl[i].item[ITEM_D2];
			info->D1 = m_tbl[i].item[ITEM_D1];
			info->D0 = m_tbl[i].item[ITEM_D0];
			return true;
		}
	}
	return false;
}
