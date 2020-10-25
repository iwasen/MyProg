/* ----- @(#)d2cdrf.c 1.1 88/04/05 17:52:52 ----- */
/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2cdrf.c
 *		ROUTINE		: 条件検索
 *		REVISION	:
 *		REMARKS		: 88.01.28
 */

#include	<stdio.h>
#include	<setjmp.h>
#include	"/la_ws/include/d3libc.h"
#include	"/la_ws/include/d2lerr.h"

#define	ERROR(err)	cdrf99(err)

extern	DSET	*d3lbgn();
extern	char	*malloc();
extern	float	atof();

static	DSET	*dsp;
static	FILE	*fp;
static	PD_TFREC	*tfp1, *tfp2, *tfp3;
static	int	rn;
static	jmp_buf	jmpbuf;

/*	FUNCTION DESCRIPTION
 *
 *			条件検索
 *
 *		FUNC.NAME	: d2cdrf()
 *		INPUT		: laidr = directory name
 *				: id_file = index file name
 *				: kj_file = 検索条件 file name
 *				: i_flag = データセット名指定フラグ
 *		OUTPUT		: return value = error code
 */

d2cdrf(ladir, id_file, kj_file, i_flag)
char	*ladir, *id_file, *kj_file;
int	*i_flag;
{
	int	rst, err;
	char	s[256];

	dsp = 0;			/* clear data set ponter */
	fp = 0;				/* clear file pointer */
	tfp1 = tfp2 = tfp3 = 0;		/* clear t/f vector pointer */

	if ((rst = setjmp(jmpbuf)) != 0)
		return(rst);

	d3path(ladir, id_file, s);	/* make index file path name */
	dsp = d3lbgn(s, "r", &err);	/* 索引ファイル使用開始 */
	if (err != 0)
		ERROR(d3cver(err, "if"));

	if ((rn = d3trow(dsp)) < 0)	/* 行数取り出し */
		ERROR(d3cver(rn, "if"));

	if ((tfp1 = (PD_TFREC *)malloc(rn + 4)) == NULL)
		ERROR(E_M);
	if ((tfp2 = (PD_TFREC *)malloc(rn + 4)) == NULL)
		ERROR(E_M);
	if ((tfp3 = (PD_TFREC *)malloc(rn + 4)) == NULL)
		ERROR(E_M);

	cdrf10(*i_flag);		/* get LA dataset name */

	cdrf20(kj_file);		/* 検索処理１ */

	cdrf30();			/* output LA dataset name */

	cdrf90();			/* 後処理 */

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			データセット名取り込み
 *
 *		FUNC.NAME	: cdrf10()
 *		INPUT		: i_flag = データセット名指定フラグ
 *		OUTPUT		: none
 */

static
cdrf10(i_flag)
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
 *			検索処理１
 *
 *		FUNC.NAME	: cdrf20()
 *		INPUT		: kj_file = 検索条件 file name
 *		OUTPUT		: none
 */

static
cdrf20(kj_file)
char	*kj_file;
{
	int	blk_no, blk_relop, rec_no, rec_relop;
	int	i, j, k;
	int	err, kwn, cn;
	PD_CNDREC	cond;
	char	keyword[256], val[256], **kwp;
	char	tp_buf[4096];
	T_TEMPLETE	*tp = (T_TEMPLETE *)tp_buf;

	if ((fp = fopen(kj_file, "r")) == NULL)	/* 検索条件ファイル open */
		ERROR(E_KJ_N);

	d3tkey(dsp, &kwn, &kwp);	/* キーワード一覧取り出し */

	cn = d3tcol(dsp);		/* 列数取り出し */
	tp->tp_cnum = cn;
	for (i = 0; i < cn; i++)
		tp->tp_templete[i].tp_col = i + 1;
	d3mktp(dsp, tp);

	blk_no = cdrf40();		/* ブロック数取り出し */
	blk_relop = cdrf41();		/* ブロック間関係演算子取り出し */

	for (i = 0; i < rn; i++)	/* Ｔ／Ｆベクトル２初期化 */
		tfp2->tf_vec[i] = (blk_relop == PD_CND_AND) ? 1 : 0;

	for (i = 0; i < blk_no; i++) {
		rec_no = cdrf40();	/* レコード数取り出し */
		rec_relop = cdrf41();	/* レコード間関係演算子取り出し */

		for (j = 0; j < rn; j++)	/* Ｔ／Ｆベクトル３初期化 */
			tfp3->tf_vec[j] = (rec_relop == PD_CND_AND) ? 1 : 0;

		for (j = 0; j < rec_no; j++) {
			cond.cnd_relop = rec_relop; /* 関係演算子セット */
			cdrf42(keyword);	/* get keyword */
			for (k = 0; k < kwn; k++) {
				if (strcmp(keyword, kwp[k]) == 0)
					break;
			}
			if (k == kwn)
				continue;
			cond.cnd_col = k + 1;	/* 列番号セット */

			cond.cnd_op = cdrf43();	/* 比較演算子セット */

			cdrf42(val);		/* 比較値取り出し */
			switch (tp->tp_templete[k].tp_mode) {
			case DS_L:
				if (strcmp(val, "T") == 0)
					cond.cnd_val.i = 1;
				else if (strcmp(val, "F") == 0)
					cond.cnd_val.i = 0;
				else
					ERROR(E_KJ_F);
				break;
			case DS_I:
				cond.cnd_val.i = atoi(val);
				break;
			case DS_R:
				cond.cnd_val.r = atof(val);
				break;
			case DS_C:
				cond.cnd_val.c_ptr = val;
				break;
			}

			if ((err = d3srch(dsp, &cond, tfp3, tfp3)) != 0)
				ERROR(d3cver(err, "if"));
		}

		for (j = 0; j < rn; j++) {
			if (blk_relop == PD_CND_AND)
				tfp2->tf_vec[j] &= tfp3->tf_vec[j];
			else
				tfp2->tf_vec[j] |= tfp3->tf_vec[j];
		}
	}
}

/*	FUNCTION DESCRIPTION
 *
 *			ＬＡデータセット名出力
 *
 *		FUNC.NAME	: cdrf30()
 *		INPUT		: none
 *		OUTPUT		: none
 */

static
cdrf30()
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
 *			検索条件ファイル読み込み　（数値）
 *
 *		FUNC.NAME	: cdrf40()
 *		INPUT		: none
 *		OUTPUT		: return value = 数値
 */

static
cdrf40()
{
	char	buf[256];

	cdrf42(buf);

	return(atoi(buf));
}

/*	FUNCTION DESCRIPTION
 *
 *			検索条件ファイル読み込み　（関係演算子）
 *
 *		FUNC.NAME	: cdrf41()
 *		INPUT		: none
 *		OUTPUT		: return value = 関係演算子
 */

static
cdrf41()
{
	char	buf[256];

	cdrf42(buf);

	if (strcmp(buf, "a") == 0)
		return(PD_CND_AND);
	else if (strcmp(buf, "o") == 0)
		return(PD_CND_OR);
	else
		ERROR(E_KJ_F);
}

/*	FUNCTION DESCRIPTION
 *
 *			検索条件ファイル読み込み　（文字列）
 *
 *		FUNC.NAME	: cdrf42()
 *		INPUT		: none
 *		OUTPUT		: buf = 文字列
 */

static
cdrf42(buf)
char	*buf;
{
	int	c, bs_flag;

	do {
		if ((c = fgetc(fp)) == EOF)
			ERROR(E_KJ_F);

	} while (c == ' ' || c == '\n');

	bs_flag = 0;
	do {
		if (bs_flag != 0) {
			*buf++ = c;
			bs_flag = 0;
		} else {
			switch (c) {
			case ' ':
			case ',':
			case '\n':
				goto end;
			case '\\':
				bs_flag = 1;
				break;
			default:
				*buf++ = c;
			}
		}

	} while ((c = fgetc(fp)) != EOF);
end:
	*buf = '\0';
}

/*	FUNCTION DESCRIPTION
 *
 *			検索条件ファイル読み込み　（比較演算子）
 *
 *		FUNC.NAME	: cdrf43()
 *		INPUT		: none
 *		OUTPUT		: return value = 数値
 */

static
cdrf43()
{
	char	buf[256];

	cdrf42(buf);

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
		ERROR(E_KJ_F);
}

/*	FUNCTION DESCRIPTION
 *
 *			後処理
 *
 *		FUNC.NAME	: cdrf90()
 *		INPUT		: none
 *		OUTPUT		: none
 */

static
cdrf90()
{
	if (dsp != 0)
		d3lend(dsp, "q", 0);

	if (fp != 0)
		fclose(fp);

	if (tfp1 != 0)
		free(tfp1);

	if (tfp2 != 0)
		free(tfp2);

	if (tfp3 != 0)
		free(tfp3);
}

/*	FUNCTION DESCRIPTION
 *
 *			エラー処理
 *
 *		FUNC.NAME	: cdrf99()
 *		INPUT		: err = error code
 *		OUTPUT		: none
 */

static
cdrf99(err)
int	err;
{
	cdrf90();		/* 後処理 */

	longjmp(jmpbuf, err);
}

