#pragma once

#include	"GlobalData.h"

typedef struct TAG_SEN_TBL {
	int	sen_id;
	int	ch;
	int	type;
	double	item[10];
} SEN_TBL;

class CSenTbl
{
public:
	CSenTbl(void);
	~CSenTbl(void);

private:
	CStringArray	m_Dat;	// ファイルの内容を全て読み込むエリア
	CArray<SEN_TBL, SEN_TBL>m_tbl;

public:
	// テーブル生成
	bool SetTbl(void);

private:
	// テーブルデータ読み込み
	bool Read(void);
	// 読み込みデータから各アイテムを取得
	bool GetItem(LPCSTR dat, SEN_TBL * tbl);
public:
	// Sensor情報から係数を取得する
	bool GetTbl(int id, int ch, KEISUU_INFO* info);
};
