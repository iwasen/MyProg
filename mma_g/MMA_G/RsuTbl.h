#pragma once

#include	"GlobalData.h"

typedef struct TAG_RSU_TBL {
	int	rsu_id;
	int	ch;
	int	cutoff;
	double	item[15];
} RSU_TBL;

class CRsuTbl
{
public:
	CRsuTbl(void);
	~CRsuTbl(void);

private:
	CStringArray	m_Dat;	// ファイルの内容を全て読み込むエリア
	CArray<RSU_TBL, RSU_TBL>m_tbl;

public:
	// テーブル生成
	bool SetTbl(void);

private:
	// テーブルデータ読み込み
	bool Read(void);
	// 読み込みデータから各アイテムを取得
	bool GetItem(LPCSTR dat, RSU_TBL * tbl);
public:
	// RSU情報から係数を取得する
	bool GetTbl(int id, int ch, int cutoff, KEISUU_INFO* info);
	void EditData(RSU_TBL *tbl, KEISUU_INFO* info);
	void EditData(int cutoff, int from_cutoff, int to_cutoff, RSU_TBL* from_tbl, RSU_TBL* to_tbl, KEISUU_INFO* info);
};
