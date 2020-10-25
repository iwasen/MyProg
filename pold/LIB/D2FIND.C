/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2find.c
 *		ROUTINE		: 条件検索
 *		REVISION	:
 *		REMARKS		: 88.01.29
 */

#include	<stdio.h>
#include	<setjmp.h>
#include	"la_ws/include/d3libc.h"

#define	ERROR(err)	find99(err)

extern	DSET	*d3lbgn();
extern	char	*malloc();
extern	double	atof();

static char	sccsid[]="@(#)d2find.c 1.3 88/06/12 14:41:57";
static	DSET	*dsp;
static	FILE	*fp;
static	PD_TFREC	*tfp1, *tfp2;
static	int	rn, fi_flag;
static	char	*readp;
static	jmp_buf	jmpbuf;

/*	FUNCTION DESCRIPTION
 *
 *			条件検索
 *
 *		FUNC.NAME	: d2find()
 *		INPUT		: laidr = directory name
 *				: id_file = index file name
 *				: f_flag = 直接／ファイル指定フラグ
 *				: cond = 条件／ファイル名
 *				: i_flag = データセット名指定フラグ
 *		OUTPUT		: return value = error code
 */

d2find(ladir, id_file, f_flag, cond, i_flag)
char	*ladir, *id_file;
int	*f_flag;
char	*cond;
int	*i_flag;
{
	int	rst, err;
	char	s[256];

	dsp = 0;			/* clear data set ponter */
	fp = 0;				/* clear file pointer */
	tfp1 = tfp2 = 0;		/* clear t/f vector pointer */

	if ((rst = setjmp(jmpbuf)) != 0)
		return(rst);

	d3path(ladir, id_file, s);	/* make index file path name */
	dsp = d3lbgn(s, "r", 0, &err);	/* 索引ファイル使用開始 */
	if (err != 0)
		ERROR(d3cver(err, "if"));

	if (*f_flag == 0) {
		readp = cond;
	} else {
		if ((fp = fopen(cond, "r")) == NULL)	/* 検索条件ファイル */
			ERROR(d3cver(DE_FILE_NONE, "kj"));
	}
	fi_flag = *f_flag;

	if ((rn = d3trow(dsp)) < 0)	/* 行数取り出し */
		ERROR(d3cver(rn, "if"));

	if ((tfp1 = (PD_TFREC *)malloc(rn + 4)) == NULL)
		ERROR(d3cver(DE_MEM, ""));
	if ((tfp2 = (PD_TFREC *)malloc(rn + 4)) == NULL)
		ERROR(d3cver(DE_MEM, ""));

	find10(*i_flag);		/* ＬＡデータセット名取り込み */

	find20();			/* 検索処理 */

	find30();			/* ＬＡデータセット名出力 */

	find90();			/* 後処理 */

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			データセット名取り込み
 *
 *		FUNC.NAME	: find10()
 *		INPUT		: i_flag = データセット名指定フラグ
 *		OUTPUT		: none
 */

static
find10(i_flag)
int	i_flag;
{
	int	i, err;
	PD_CNDREC	cond;
	char	buf[256];

	if (i_flag == 0) {
		for (i = 0; i < rn; i++)
			tfp1->tf_vec[i] = 1;
	} else {
		for (i = 0; i < rn; i++)
			tfp1->tf_vec[i] = 0;

		cond.cnd_relop = PD_CND_OR;
		cond.cnd_col = 1;
		cond.cnd_op = PD_CND_EQ;
		cond.cnd_val.c_ptr = buf;
		while (gets(buf) != 0) {
			if ((err = d3srch(dsp, &cond, tfp1, tfp1)) != 0)
				ERROR(d3cver(err, "if"));
		}
	}
}

/*	FUNCTION DESCRIPTION
 *
 *			検索処理
 *
 *		FUNC.NAME	: find20()
 *		INPUT		: none
 *		OUTPUT		: none
 */

static
find20()
{
	int	relop;
	int	i, k;
	int	err, kwn, cn;
	PD_CNDREC	cond;
	char	keyword[256], val[256], **kwp;
	char	tp_buf[4096];
	T_TEMPLETE	*tp = (T_TEMPLETE *)tp_buf;

	d3tkey(dsp, &kwn, &kwp);	/* キーワード一覧取り出し */

	cn = d3tcol(dsp);		/* 列数取り出し */
	tp->tp_cnum = cn;
	for (i = 0; i < cn; i++)
		tp->tp_templete[i].tp_col = i + 1;
	d3mktp(dsp, tp);

	for (i = 0; i < rn; i++)	/* Ｔ／Ｆベクトル２初期化 */
		tfp2->tf_vec[i] = 0;

	relop = PD_CND_OR;
	for (;;) {
		cond.cnd_relop = relop; /* 関係演算子セット */
		find40(keyword);	/* キーワード取り出し */
		for (k = 0; k < kwn; k++) {
			if (strcmp(keyword, kwp[k]) == 0)
				break;
		}
		if (k == kwn)
			ERROR(d3cver(DE_PRM, ""));
		cond.cnd_col = k + 1;	/* 列番号セット */

		cond.cnd_op = find43();	/* 比較演算子セット */

		find40(val);		/* 比較値取り出し */
		switch (tp->tp_templete[k].tp_mode) {
		case DS_L:
			if (strcmp(val, "T") == 0)
				cond.cnd_val.i = 1;
			else if (strcmp(val, "F") == 0)
				cond.cnd_val.i = 0;
			else if (strcmp(val, "NA") == 0)
				cond.cnd_val.i = PD_NA;
			else
				ERROR(d3cver(DE_PRM));
			break;
		case DS_I:
			if (strcmp(val, "NA") == 0)
				cond.cnd_val.i = PD_NA;
			else
				cond.cnd_val.i = atoi(val);
			break;
		case DS_R:
			if (strcmp(val, "NA") == 0)
				cond.cnd_val.i = PD_NA;
			else
				cond.cnd_val.r = atof(val);
			break;
		case DS_C:
			cond.cnd_val.c_ptr = val;
			break;
		}

		if ((err = d3srch(dsp, &cond, tfp2, tfp2)) != 0)
			ERROR(d3cver(err, "if"));

		if ((relop = find41()) == -1)
			break;
	}
}

/*	FUNCTION DESCRIPTION
 *
 *			ＬＡデータセット名出力
 *
 *		FUNC.NAME	: find30()
 *		INPUT		: none
 *		OUTPUT		: none
 */

static
find30()
{
	T_TEMPLETE	templete;
	int	i;
	char	buf[256];

	templete.tp_bufp = buf;
	templete.tp_cnum = 1;
	templete.tp_templete[0].tp_col = 1;

	d3mktp(dsp, &templete);

	for (i = 0; i < rn; i++) {
		if (tfp1->tf_vec[i] && tfp2->tf_vec[i]) {
			d3trd(dsp, i+1, 1, &templete);
			puts(buf);
		}
	}
}

/*	FUNCTION DESCRIPTION
 *
 *			検索条件読み込み　（文字列）
 *
 *		FUNC.NAME	: find40()
 *		INPUT		: none
 *		OUTPUT		: buf = 文字列
 */

static
find40(buf)
char	*buf;
{
	int	c, bs_flag;

	*buf = '\0';

	do {
		if ((c = find45()) == EOF)
			return;

	} while (c == ' ' || c == '\n');

	bs_flag = 0;
	do {
		if (bs_flag != 0) {
			*buf++ = c;
			bs_flag = 0;
		} else {
			if (c == ' ' || c == '\n')
				break;
			else if (c == '\\')
				bs_flag = 1;
			else
				*buf++ = c;
		}

	} while ((c = find45()) != EOF);

	*buf = '\0';
}

/*	FUNCTION DESCRIPTION
 *
 *			検索条件読み込み　（関係演算子）
 *
 *		FUNC.NAME	: find41()
 *		INPUT		: none
 *		OUTPUT		: return value = 関係演算子
 */

static
find41()
{
	char	buf[256];

	find40(buf);

	if (strcmp(buf, "&") == 0)
		return(PD_CND_AND);
	else if (strcmp(buf, "|") == 0)
		return(PD_CND_OR);
	else if (strcmp(buf, "") == 0)
		return(-1);
	else
		ERROR(d3cver(DE_PRM, ""));
}

/*	FUNCTION DESCRIPTION
 *
 *			検索条件読み込み　（比較演算子）
 *
 *		FUNC.NAME	: find43()
 *		INPUT		: none
 *		OUTPUT		: return value = 数値
 */

static
find43()
{
	char	buf[256];

	find40(buf);

	if (strcmp(buf, "=") == 0)
		return(PD_CND_EQ);
	else if (strcmp(buf, "!=") == 0)
		return(PD_CND_NE);
	else if (strcmp(buf, ">") == 0)
		return(PD_CND_GT);
	else if (strcmp(buf, "!>") == 0)
		return(PD_CND_LE);
	else if (strcmp(buf, "<") == 0)
		return(PD_CND_LT);
	else if (strcmp(buf, "!<") == 0)
		return(PD_CND_GE);
	else if (strcmp(buf, ">=") == 0)
		return(PD_CND_GE);
	else if (strcmp(buf, "!>=") == 0)
		return(PD_CND_LT);
	else if (strcmp(buf, "<=") == 0)
		return(PD_CND_LE);
	else if (strcmp(buf, "!<=") == 0)
		return(PD_CND_GT);
	else
		ERROR(d3cver(DE_PRM, ""));
}

/*	FUNCTION DESCRIPTION
 *
 *			検索条件読み込み　（１文字）
 *
 *		FUNC.NAME	: find45()
 *		INPUT		: none
 *		OUTPUT		: return value = 読み込みデータ
 */

static
find45()
{
	int	c;

	if (fi_flag == 0) {
		c = *readp;
		if (c == '\0')
			return(EOF);
		readp++;
		return(c);
	} else
		return(fgetc(fp));
}

/*	FUNCTION DESCRIPTION
 *
 *			後処理
 *
 *		FUNC.NAME	: find90()
 *		INPUT		: none
 *		OUTPUT		: none
 */

static
find90()
{
	if (dsp != 0)
		d3lend(dsp, "q", 0);

	if (fp != 0)
		fclose(fp);

	if (tfp1 != 0)
		free(tfp1);

	if (tfp2 != 0)
		free(tfp2);
}

/*	FUNCTION DESCRIPTION
 *
 *			エラー処理
 *
 *		FUNC.NAME	: find99()
 *		INPUT		: err = error code
 *		OUTPUT		: none
 */

static
find99(err)
int	err;
{
	find90();		/* 後処理 */

	longjmp(jmpbuf, err);
}

