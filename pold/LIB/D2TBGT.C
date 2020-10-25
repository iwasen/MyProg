/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2tbgt.c
 *		ROUTINE		: 表形式ファイルの出力
 *		REVISION	:
 *		REMARKS		: 88.07.11
 */

#include	<stdio.h>
#include	<string.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d2lerr.h"

extern	DSET	*d3lbgn();
extern	char	*d3allc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION
 *
 *			メイン処理
 *
 *		FUNC.NAME	: d2tbgt()
 *		INPUT		: ladir = ＬＡデータベースディレクトリ名
 *				: lads  = ＬＡデータセット名
 *				: tbl_file = 表形式ファイル名
 *				:            （ＮＵＬＬの時は標準出力）
 *				: col_tbl  = 列番号テーブル
 *				: sprt     = セパレータ
 *		OUTPUT		: return value = error code
 */

d2tbgt(ladir, lads, tbl_file, col_tbl, sprt)
char	*ladir, *lads, *tbl_file;
int	*col_tbl;
char	*sprt;
{
	DSET	*dsp;
	FILE	*fp;
	int	col, row, col_n, err;
	char	lads_path[128];

	/* ＬＡデータセット使用開始 */
	d3path(ladir, lads, lads_path);
	if ((dsp = d3lbgn(lads_path, "r", 0, &err)) == 0)
		return(d3cver(err, "ds"));

	/* 表形式チェック */
	if (d3istb(dsp) == 3)
		return(E_DS_F);

	/* 列数取り出し */
	if ((col = d3tcol(dsp)) < 0) {
		d3lend(dsp, "q", 0);
		return(d3cver(err, "ds"));
	}

	/* 行数取り出し */
	if ((row = d3trow(dsp)) < 0) {
		d3lend(dsp, "q", 0);
		return(d3cver(err, "ds"));
	}

	/* 列番号チェック */
	if (col_tbl != 0) {
		for (col_n = 0; col_tbl[col_n]; col_n++) {
			if (col_tbl[col_n] <= 0 || col_tbl[col_n] > col) {
				d3lend(dsp, "q", 0);
				return(E_P);
			}
		}
	} else
		col_n = col;

	/* 表形式ファイルオープン */
	if (tbl_file != 0) {
		if ((fp = fopen(tbl_file, "w")) == 0) {
			d3lend(dsp, "q", 0);
			return(E_KF_N);
		}
	} else
		fp = stdout;

	/* 表形式ファイルの出力 */
	err = tbgt10(dsp, fp, col_tbl, col_n, row, sprt);

	if (tbl_file != 0)
		fclose(fp);		/* 表形式ファイルクローズ */

	d3lend(dsp, "q", 0);		/* ＬＡデータセット使用終了 */

	return(err);
}

/*	FUNCTION DESCRIPTION
 *
 *			表形式ファイルの出力
 *
 *		FUNC.NAME	: tbgt10()
 *		INPUT		: dsp     = データセットポインタ
 *				: fp      = 表形式ファイルポインタ
 *				: col_tbl = 列番号テーブル
 *				: col_n   = 出力列数
 *				: row     = 行数
 *				: sprt    = セパレータ
 *		OUTPUT		: return value = error code
 */

static
tbgt10(dsp, fp, col_tbl, col_n, row, sprt)
DSET	*dsp;
FILE	*fp;
int	*col_tbl;
int	col_n;
int	row;
char	*sprt;
{
	int	i, err;
	T_TEMPLETE	*tp;

	/* テンプレートエリア確保 */
	if ((tp = (T_TEMPLETE *)d3allc(dsp->ds_memno,
			sizeof(T_TEMPLETE) + sizeof(TEMPLETE) * col_n)) == 0)
		return(E_M);

	/* テンプレートセット */
	tp->tp_cnum = col_n;
	for (i = 0; i < col_n; i++) {
		if (col_tbl != 0)
			tp->tp_templete[i].tp_col = col_tbl[i];
		else
			tp->tp_templete[i].tp_col = i + 1;
	}
	if ((err = d3mktp(dsp, tp)) != 0)
		return(d3cver(err,"ds"));

	/* 表データ読み込みバッファ確保 */
	if ((tp->tp_bufp = d3allc(dsp->ds_memno, tp->tp_rlen)) == 0)
		return(E_M);

	for (i = 0; i < row; i++) {
		/* 表データ読み込み */
		if ((err = d3trd(dsp, i+1, 1, tp)) != 0)
			return(d3cver(err, "ds"));

		/* 表形式ファイル出力 */
		if ((err = tbgt20(fp, tp, sprt)) != 0)
			return(err);
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			表形式ファイルの出力
 *
 *		FUNC.NAME	: tbgt20()
 *		INPUT		: fp   = 表形式ファイルポインタ
 *				: tp   = テンプレートポインタ
 *				: sprt = セパレータ
 *		OUTPUT		: return value = error code
 */

static
tbgt20(fp, tp, sprt)
FILE	*fp;
T_TEMPLETE	*tp;
char	*sprt;
{
	union	{
		int	*i;
		float	*f;
		char	*c;
	} val;
	int	i, j, len, sp_len;
	char	str[256];

	for (i = 0; i < tp->tp_cnum; i++) {
		if (i != 0) {
			if (sprt != 0)
				fputs(sprt, fp);
			else {
				sp_len = len - strlen(str);
				for (j = 0; j < sp_len; j++)
					str[j] = ' ';
				str[j] = '\0';
				fputs(str, fp);
			}
		}

		val.c = tp->tp_bufp + tp->tp_templete[i].tp_offset;
		switch (tp->tp_templete[i].tp_mode) {
		case DS_L:
			if (*val.i == 0)
				strcpy(str, "F");
			else if (*val.i == PD_NA)
				strcpy(str, "NA");
			else
				strcpy(str, "T");
			len = 3;
			break;
		case DS_I:
			if (*val.i == PD_NA)
				strcpy(str, "NA");
			else
				sprintf(str, "%d", *val.i);
			len = 12;
			break;
		case DS_R:
			if (*val.i == PD_NA)
				strcpy(str, "NA");
			else
				sprintf(str, "%g", *val.f);
			len = 14;
			break;
		case DS_C:
			strcpy(str, val.c);
			len = tp->tp_templete[i].tp_len;
			break;
		}

		fputs(str, fp);
	}

	fputs("\n", fp);

	return(0);
}

