/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2tbrg.c
 *		ROUTINE		: 表形式ファイルの格納
 *		REVISION	:
 *		REMARKS		: 88.06.21
 */

#include	<stdio.h>
#include	<string.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d2lerr.h"

extern	DSET	*d3lbgn();
extern	char	*d3csav();
extern	char	*d3allc();
extern	double	atof();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

struct	tb_fmt	{
	char	*name;
	int	mode;
	int	col;
	int	len;
};

static	FILE	*des_fp;		/* 記述ファイルポインタ */
static	FILE	*tbl_fp;		/* 表形式ファイルポインタ */
static	DSET	*dsp;			/* データセットポインタ */
static	int	mgno;			/* メモリ番号 */
static	char	line_buf[1024];		/* １行バッファ */
static	char	*c_ptr;			/* １行バッファポインタ */
static	int	tb_type;		/* 表形式種別 */
static	int	dlimit;			/* デリミッタ */
static	int	col_n;			/* 列数 */
static	struct	tb_fmt	fmt_tbl[256];	/* フォーマットテーブル */

/*	FUNCTION DESCRIPTION
 *
 *			メイン処理
 *
 *		FUNC.NAME	: d2tbrg()
 *		INPUT		: ladir = ＬＡデータベースディレクトリ名
 *				: lads  = ＬＡデータセット名
 *				: tbl_file = 表形式ファイル名
 *				: des_file = 記述ファイル名
 *				: ovw_flag = 上書き許可フラグ
 *		OUTPUT		: return value = error code
 */

d2tbrg(ladir, lads, tbl_file, des_file, ovw_flag)
char	*ladir;
char	*lads;
char	*tbl_file;
char	*des_file;
int	*ovw_flag;
{
	int	err;
	char	lads_path[128];

	/* 表形式ファイルオープン */
	if ((tbl_fp = fopen(tbl_file, "r")) == 0)
		return(E_KF_N);

	/* 記述ファイルオープン */
	if ((des_fp = fopen(des_file, "r")) == 0) {
		fclose(tbl_fp);
		return(E_KF_N);
	}

	/* ＬＡデータセット使用開始 */
	d3path(ladir, lads, lads_path);
	if ((dsp = d3lbgn(lads_path, *ovw_flag ? "c+" : "c", 0, &err)) == 0) {
		fclose(tbl_fp);
		fclose(des_fp);
		return(d3cver(err, "ds"));
	}

	mgno = dsp->ds_memno;	/* メモリ番号取り出し */

	err = tbrg10();		/* 記述ファイル＆表形式ファイル読み込み */
	if (err == 0)
		err = d3cver(d3lend(dsp, "wq", 1), "ds");
	else
		d3lend(dsp, "q", 0);

	fclose(tbl_fp);		/* 表形式ファイルクローズ */
	fclose(des_fp);		/* 記述ファイルクローズ */

	if (err == 0)
		err = tbrg90(ladir, lads);	/* システム索引更新 */

	return(err);
}

/*	FUNCTION DESCRIPTION
 *
 *			記述ファイル＆表形式ファイル読み込み
 *
 *		FUNC.NAME	: tbrg10()
 *		INPUT		: none
 *		OUTPUT		: return value = error code
 */

static
tbrg10()
{
	int	err;

	if ((err = tbrg20()) != 0)	/* 記述ファイル読み込み */
		return(err);

	if ((err = tbrg30()) != 0)	/* 列作成 */
		return(err);

	if ((err = tbrg40()) != 0)	/* 表形式ファイル読み込み */
		return(err);

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			記述ファイル読み込み
 *
 *		FUNC.NAME	: tbrg20()
 *		INPUT		: none
 *		OUTPUT		: return value = error code
 */

static
tbrg20()
{
	int	err;

	if (tbrg50(des_fp) != 0)	/* １行読み込み */
		return(E_KF_F);

	/* ”－ｆ％”判定 */
	if (line_buf[0] == '-' && line_buf[1] == 'f') {
		if (line_buf[2] == '\0')
			tb_type = 1;
		else {
			dlimit = line_buf[2];
			tb_type = 2;
		}
		if (tbrg50(des_fp) != 0)
			return(E_KF_F);
	} else
		tb_type = 0;

	err = tbrg25();			/* 列情報読み込み */

	return(err);
}

/*	FUNCTION DESCRIPTION
 *
 *			列情報読み込み
 *
 *		FUNC.NAME	: tbrg25()
 *		INPUT		: none
 *		OUTPUT		: return value = error code
 */

static
tbrg25()
{
	struct	tb_fmt	*ftp;
	int	i;
	char	buf[256];

	col_n = 0;
	ftp = fmt_tbl;
	do {
		/* 列名取り出し */
		if (tbrg60(buf) != 0)
			continue;
		if ((ftp->name = d3csav(mgno, buf)) == 0)
			return(E_M);

		/* モード取り出し */
		if (tbrg60(buf) != 0)
			return(E_KF_F);
		for (i = 0; buf[i]; i++)
			buf[i] = toupper(buf[i]);
		if (strcmp(buf, "CHAR") == 0 || strcmp(buf, "C") == 0)
			ftp->mode = DS_C;
		else if (strcmp(buf, "INT") == 0 || strcmp(buf, "I") == 0)
			ftp->mode = DS_I;
		else if (strcmp(buf, "REAL") == 0 || strcmp(buf, "R") == 0)
			ftp->mode = DS_R;

		/* 列位置取り出し */
		if (tbrg60(buf) != 0)
			return(E_KF_F);
		if ((ftp->col = atoi(buf)) == 0)
			return(E_KF_F);

		/* 長さ取り出し */
		if (tb_type == 0) {
			if (tbrg60(buf) != 0)
				return(E_KF_F);
			ftp->len = atoi(buf);
		}

		col_n++;
		ftp++;
	} while (tbrg50(des_fp) == 0);	/* １行読み込み */

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			列作成
 *
 *		FUNC.NAME	: tbrg30()
 *		INPUT		: none
 *		OUTPUT		: return value = error code
 */

static
tbrg30()
{
	int	i, err;
	struct	tb_fmt	*ftp;

	for (i = 0, ftp = fmt_tbl; i < col_n; i++, ftp++) {
		/* 列挿入 */
		if ((err = d3tinc(dsp, i, ftp->mode, ftp->name)) != 0)
			return(d3cver(err, "ds"));
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			表形式ファイル読み込み
 *
 *		FUNC.NAME	: tbrg40()
 *		INPUT		: none
 *		OUTPUT		: return value = error code
 */

static
tbrg40()
{
	T_TEMPLETE	*tp;
	int	row, err, buf_siz;
	char	*buf;

	if ((tp = (T_TEMPLETE *)d3allc(mgno,
			sizeof(T_TEMPLETE)+sizeof(TEMPLETE)*col_n)) == 0)
		return(E_M);

	buf_siz = 128 * col_n;
	if ((buf = d3allc(mgno, buf_siz)) == 0)
		return(E_M);

	tp->tp_bufp = buf;
	tp->tp_rlen = buf_siz;
	tp->tp_cnum = col_n;

	for (row = 0; tbrg50(tbl_fp) == 0; row++) {
		/* １行分読み込み */
		if ((err = tbrg45(tp->tp_templete, buf)) != 0)
			return(err);
		/* 行挿入 */
		if ((err = d3tin(dsp, row, 1, tp)) != 0)
			return(d3cver(err, "ds"));
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			表形式ファイル１行読み込み
 *
 *		FUNC.NAME	: tbrg45()
 *		INPUT		: tp = テンプレートポインタ
 *				: bp = バッファポインタ
 *		OUTPUT		: return value = error code
 */

static
tbrg45(tp, bp)
TEMPLETE *tp;
char	*bp;
{
	int	i, j, err, offset;
	struct	tb_fmt	*ftp;
	char	buf[256];

	offset = 0;
	for (i = 0, ftp = fmt_tbl; i < col_n; i++, ftp++, tp++) {
		tp->tp_col = i+1;		/* 列番号セット */
		tp->tp_mode = ftp->mode;	/* モードセット */

		/* １要素読み込み */
		switch (tb_type) {		/* フォーマット判定 */
		case 0:				/* 固定欄 */
			err = tbrg80(buf, ftp->col, ftp->len);
			break;
		case 1:				/* 不定長（空白，タブ） */
			c_ptr = line_buf;
			for (j = 0; j < ftp->col; j++) {
				if ((err = tbrg60(buf)) != 0)
					break;
			}
			break;
		case 2:				/* 不定長（デリミッタ） */
			c_ptr = line_buf;
			for (j = 0; j < ftp->col; j++) {
				if ((err = tbrg70(buf, dlimit)) != 0)
					break;
			}
			break;
		}

		/* テンプレートバッファにセット */
		switch (tp->tp_mode) {
		case DS_I:
			if (err == 0)
				*(int *)&bp[offset] = atoi(buf);
			else
				*(int *)&bp[offset] = PD_NA;
			tp->tp_len = sizeof(int);
			break;
		case DS_R:
			if (err == 0)
				*(float *)&bp[offset] = atof(buf);
			else
				*(int *)&bp[offset] = PD_NA;
			tp->tp_len = sizeof(int);
			break;
		case DS_C:
			if (err == 0)
				strcpy(&bp[offset], buf);
			else
				strcpy(&bp[offset], "");
			tp->tp_len = (strlen(&bp[offset]) + 2) & ~1;
			break;
		}

		tp->tp_offset = offset;
		offset += tp->tp_len;
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			１行読み込み
 *
 *		FUNC.NAME	: tbrg50()
 *		INPUT		: fp = ファイルポインタ
 *		OUTPUT		: return value = error code
 */

static
tbrg50(fp)
FILE	*fp;
{
	char	*p;

	if (fgets(line_buf, sizeof(line_buf), fp) == 0)	/* １行読み込み */
		return(-1);

	if ((p = strrchr(line_buf, '\n')) != 0)		/* LFを取り除く */
		*p = '\0';

	c_ptr = line_buf;		/* ポインタセット */

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			空白，タブで区切られた１ワード取り出し
 *
 *		FUNC.NAME	: tbrg60()
 *		INPUT		: none
 *		OUTPUT		: bp = バッファポインタ
 *				: return value = error code
 */

static
tbrg60(bp)
char	*bp;
{
	/* 空白とタブを読み飛ばす */
	for (;;) {
		if (*c_ptr == '\0')
			return(-1);
		if (*c_ptr != ' ' && *c_ptr != '\t')
			break;
		c_ptr++;
	}

	/* 空白またはタブまでのデータを取り出す */
	for (;;) {
		if (*c_ptr == '\0' || *c_ptr == ' ' || *c_ptr == '\t')
			break;
		*bp++ = *c_ptr++;
	}

	*bp = '\0';

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			デリミッタで区切られた１ワード取り出し
 *
 *		FUNC.NAME	: tbrg70()
 *		INPUT		: none
 *		OUTPUT		: bp   = バッファポインタ
 *				: dlmt = デリミッタ
 *				: return value = error code
 */

static
tbrg70(bp, dlmt)
char	*bp;
int	dlmt;
{
	if (*c_ptr == '\0')
		return(-1);

	/* デリミッタまでのデータを取り出す */
	for (;;) {
		if (*c_ptr == '\0')
			break;
		if (*c_ptr == dlmt) {
			c_ptr++;
			break;
		}
		*bp++ = *c_ptr++;
	}

	*bp = '\0';

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			指定した位置と長さの１ワード取り出し
 *
 *		FUNC.NAME	: tbrg80()
 *		INPUT		: pos  = 位置
 *				: len  = 長さ
 *		OUTPUT		: bp   = バッファポインタ
 *				: return value = error code
 */

static
tbrg80(bp, pos, len)
char	*bp;
int	pos;
int	len;
{
	char	*lbp;

	if (pos > strlen(line_buf))
		return(-1);

	lbp = &line_buf[pos - 1];
	while (len--) {
		if (*lbp == 0)
			break;
		*bp++ = *lbp++;
	}

	*bp = '\0';

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			システム索引更新
 *
 *		FUNC.NAME	: tbrg90()
 *		INPUT		: ladir = ＬＡデータベースディレクトリ名
 *				: lads  = ＬＡデータセット名
 *				: return value = error code
 */

static
tbrg90(ladir, lads)
char	*ladir, *lads;
{
	char	sys_index[16];
	int	err;

	err = d2ichk(ladir, lads, sys_index);	/* get system index */
	if (err != 0)
		return(err);

	err = d2idup(ladir, sys_index, lads);	/* update system idx */
	if (err != 0)
		return(err);

	return(0);
}
