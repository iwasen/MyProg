#include "StdAfx.h"
#include "RsuTbl.h"
#include "GlobalData.h"
#include <math.h>

#define	FN_RSU_TBL	"rsu_tbl.tbl"
#define	CNT_ITEM	18

#define	ITEM_A4		0
#define	ITEM_A3		1
#define	ITEM_A2		2
#define	ITEM_A1		3
#define	ITEM_A0		4
#define	ITEM_B4		5
#define	ITEM_B3		6
#define	ITEM_B2		7
#define	ITEM_B1		8
#define	ITEM_B0		9
#define	ITEM_E4		10
#define	ITEM_E3		11
#define	ITEM_E2		12
#define	ITEM_E1		13
#define	ITEM_E0		14

CRsuTbl::CRsuTbl(void)
{
}

CRsuTbl::~CRsuTbl(void)
{
	m_Dat.RemoveAll();
	m_tbl.RemoveAll();
}

// テーブル生成
bool CRsuTbl::SetTbl(void)
{
	INT_PTR	i, cnt;
	RSU_TBL	tbl;

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
bool CRsuTbl::Read(void)
{
	CStdioFile	fp;
	CFileException fileException;
	CString path;

	m_Dat.RemoveAll();

	path.Format("%s\\%s", gExePath, FN_RSU_TBL);
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
bool CRsuTbl::GetItem(LPCSTR dat, RSU_TBL * tbl)
{
	char buf[1024], *token, *nexttoken;
	char *sep = ",\0";
	int	tokenCnt;

	strcpy_s(buf, dat);
	tokenCnt = 0;
	// トークン取得
	token = strtok_s( buf, sep, &nexttoken);
	while (token != NULL) {
		switch(tokenCnt) {
		case 0:	// RSU_ID
			tbl->rsu_id = atoi(token);
			break;
		case 1:	// CH
			tbl->ch = atoi(token);
			break;
		case 2:	// CUTOFF
			tbl->cutoff = atoi(token);
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
bool CRsuTbl::GetTbl(int id, int ch, int cutoff, KEISUU_INFO* info)
{
	int	i, j, cnt;
	CArray<RSU_TBL, RSU_TBL>tbl_w;
	CArray<RSU_TBL, RSU_TBL>tbl;

	cnt = (int)m_tbl.GetSize();
	// 該当CH、RSUのテーブルを取得する。
	for (i = 0; i < cnt; i++) {
		// 該当CH、RSUである
		if (id == m_tbl[i].rsu_id && ch == m_tbl[i].ch) {
			// 取得
			tbl_w.Add(m_tbl[i]);
			// そのデータはカットオフ一致？
			if (cutoff == m_tbl[i].cutoff) {
				// 該当カットオフ周波数があったのでデータセットしてリターン
				EditData(&m_tbl[i], info);
				return true;
			}
		}
	}

	cnt = (int)tbl_w.GetSize();
	// 該当テーブルがなければリターン
	if (cnt == 0)
		return false;

	// 抽出データソート
	int min_c = -1;
	int	max_c, idx;
	for (i = 0; i < cnt; i++) {
		max_c = 0x7FFFFFFF;
		idx = -1;
		for (j = 0; j < cnt; j++) {
			if (min_c < tbl_w[j].cutoff
				&& tbl_w[j].cutoff < max_c) {
				idx = (int)j;
				max_c = tbl_w[j].cutoff;
			}
		}
		tbl.Add(tbl_w[idx]);
		min_c = tbl_w[idx].cutoff;
	}

	cnt = (int)tbl.GetSize();

	if (cutoff <= tbl[0].cutoff) {
		// 該当カットオフ周波数があったのでデータセットしてリターン
		EditData(&tbl[0], info);
		return true;
	}
	if (cutoff >= tbl[cnt - 1].cutoff) {
		// 該当カットオフ周波数があったのでデータセットしてリターン
		EditData(&tbl[cnt - 1], info);
		return true;
	}

	int	from, from_idx, to, to_idx;
	// 次に該当カットオフがテーブル内のどこに位置するか調べる
	for (i = 0; i < cnt; i++) {
		if (cutoff > tbl[i].cutoff) {
			from = tbl[i].cutoff;
			from_idx = i;
		}
		if (cutoff < tbl[i].cutoff) {
			to = tbl[i].cutoff;
			to_idx = i;
		}
	}

	// テーブルデータ編集
	EditData(cutoff, from, to, &tbl[from_idx], &tbl[to_idx], info);

	return true;
}

// テーブルデータ編集
void CRsuTbl::EditData(RSU_TBL *tbl, KEISUU_INFO* info)
{
	info->A4 = tbl->item[ITEM_A4];
	info->A3 = tbl->item[ITEM_A3];
	info->A2 = tbl->item[ITEM_A2];
	info->A1 = tbl->item[ITEM_A1];
	info->A0 = tbl->item[ITEM_A0];
	info->B4 = tbl->item[ITEM_B4];
	info->B3 = tbl->item[ITEM_B3];
	info->B2 = tbl->item[ITEM_B2];
	info->B1 = tbl->item[ITEM_B1];
	info->B0 = tbl->item[ITEM_B0];
	info->E4 = tbl->item[ITEM_E4];
	info->E3 = tbl->item[ITEM_E3];
	info->E2 = tbl->item[ITEM_E2];
	info->E1 = tbl->item[ITEM_E1];
	info->E0 = tbl->item[ITEM_E0];
}

// テーブルデータ編集
void CRsuTbl::EditData(int cutoff, int from_cutoff, int to_cutoff, RSU_TBL* from_tbl, RSU_TBL* to_tbl, KEISUU_INFO* info)
{
	double	Fllog, Fhlog, Fclog;
	double	LogRange;
	double	CoeffHighRatio, CoeffLowRatio;

	Fllog = log10((double)from_cutoff);
	Fhlog = log10((double)to_cutoff);
	Fclog = log10((double)cutoff);

	LogRange = Fhlog - Fllog;

	CoeffHighRatio = (Fhlog - Fclog) / LogRange;
	CoeffLowRatio = (Fclog - Fllog) / LogRange;

	info->A4 = from_tbl->item[ITEM_A4] * CoeffLowRatio + to_tbl->item[ITEM_A4] * CoeffHighRatio;
	info->A3 = from_tbl->item[ITEM_A3] * CoeffLowRatio + to_tbl->item[ITEM_A3] * CoeffHighRatio;
	info->A2 = from_tbl->item[ITEM_A2] * CoeffLowRatio + to_tbl->item[ITEM_A2] * CoeffHighRatio;
	info->A1 = from_tbl->item[ITEM_A1] * CoeffLowRatio + to_tbl->item[ITEM_A1] * CoeffHighRatio;
	info->A0 = from_tbl->item[ITEM_A0] * CoeffLowRatio + to_tbl->item[ITEM_A0] * CoeffHighRatio;
	info->B4 = from_tbl->item[ITEM_B4] * CoeffLowRatio + to_tbl->item[ITEM_B4] * CoeffHighRatio;
	info->B3 = from_tbl->item[ITEM_B3] * CoeffLowRatio + to_tbl->item[ITEM_B3] * CoeffHighRatio;
	info->B2 = from_tbl->item[ITEM_B2] * CoeffLowRatio + to_tbl->item[ITEM_B2] * CoeffHighRatio;
	info->B1 = from_tbl->item[ITEM_B1] * CoeffLowRatio + to_tbl->item[ITEM_B1] * CoeffHighRatio;
	info->B0 = from_tbl->item[ITEM_B0] * CoeffLowRatio + to_tbl->item[ITEM_B0] * CoeffHighRatio;
	info->E4 = from_tbl->item[ITEM_E4] * CoeffLowRatio + to_tbl->item[ITEM_E4] * CoeffHighRatio;
	info->E3 = from_tbl->item[ITEM_E3] * CoeffLowRatio + to_tbl->item[ITEM_E3] * CoeffHighRatio;
	info->E2 = from_tbl->item[ITEM_E2] * CoeffLowRatio + to_tbl->item[ITEM_E2] * CoeffHighRatio;
	info->E1 = from_tbl->item[ITEM_E1] * CoeffLowRatio + to_tbl->item[ITEM_E1] * CoeffHighRatio;
	info->E0 = from_tbl->item[ITEM_E0] * CoeffLowRatio + to_tbl->item[ITEM_E0] * CoeffHighRatio;
}
