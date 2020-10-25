/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2rgst.c
 *		ROUTINE		: 索引の更新処理
 *		REVISION	:
 *		REMARKS		: 88.01.25
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#define		SIND	1
#define		UIND	2

extern	DSET	*d3lbgn();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION
 *
 *			索引ファイル更新
 *
 *		FUNC.NAME	: d2rgst()
 *		INPUT		: ladir = directory name
 *				: id_name = 索引ファイル名
 *				: ds_name = data set name
 *		OUTPUT		: return value = error code
 */

d2rgst(ladir, id_name, ds_name, nitems)
char	*ladir, *id_name, *ds_name[];
int	nitems;
{
	DSET	*id_dsp, *ds_dsp;
	int	rn, row, err, retry_count, ind=0, n;
	char	s[256], tf_buf[4096], tmp_buf[4096], buf[4096], *index_type;
	PD_TFREC	*tfp = (PD_TFREC *)tf_buf;
	PD_CNDREC	cond;
	T_TEMPLETE	*templete = (T_TEMPLETE *)tmp_buf;
	TEMPLETE	*tp;
	PD_LNREC	line;

	if (d3xind(id_name)) {
		ind = SIND; index_type = "if";
	} else if (d3xusr(id_name)) {
		ind = UIND; index_type = "uf";
	} else if (d3xsys(id_name)) {
		ind = UIND; index_type = "uf";
	} else
		return(DE_PRM);

	d3path(ladir, id_name, s);		/* make index path name */
	retry_count = 0;
	for (;;) {
		id_dsp = d3lbgn(s, "u", DSO_NBAK, &err); /* 索引ファイル使用 */
		if (err == 0)
			break;
		else if (err != DE_LOCK || retry_count++ == 10)
			return(d3cver(err, index_type));
		sleep(1);
	}

	for (n = 0; n < nitems; n++) {
		rn = d3trow(id_dsp);			/* 行数取り出し */

		cond.cnd_relop = PD_CND_AND;
		cond.cnd_col = 1;
		cond.cnd_op = PD_CND_EQ;
		cond.cnd_val.c_ptr = ds_name[n];
							/* 条件検索 */
		if ((err = d3srch(id_dsp, &cond, 0, tfp)) != 0) {
			d3lend(id_dsp, "q", 0);
			return(d3cver(err, index_type));
		}

		if (tfp->tf_n != 0) {
			d3toln(tfp, &line, rn);		/* ベクトル変換 */
			row = line.ln_vec[0];		/* 行位置セット */
			if ((err = d3tdl(id_dsp, row, 1)) != 0) {
							/* １行削除 */
				d3lend(id_dsp, "q", 0);
				return(d3cver(err, index_type));
			}
			rn--;
			row--;
		} else
			row = rn;

		if (ind = SIND) {
						/* システム索引への登録 */
			d3path(ladir, ds_name[n], s);
			ds_dsp = d3lbgn(s, "r1", 0, &err);
					/* ＬＡデータセット使用開始 */
			if (err == 0) {
				templete->tp_bufp = buf;
				idup10(id_dsp, ds_dsp, ds_name[n], templete);
						/* 行データ作成 */
				d3lend(ds_dsp, "q", 0);
						/* ＬＡデータセット使用終了 */
				err = d3tin(id_dsp, row, 1, templete);
						/* １行挿入 */
				if (err != 0) {
					d3lend(id_dsp, "q", 0);
					return(d3cver(err, index_type));
				}
			} else if (err != DE_FILE_NONE) {
				d3lend(id_dsp, "q", 0);
				return(d3cver(err, "ds"));
			}
		} else if (ind = UIND) {
						/* ユーザ索引への登録 */
			templete -> tp_bufp = buf;
			tp = templete ->tp_templete;
			tp -> tp_col    = 1;
			tp -> tp_mode   = DS_C;
			tp -> tp_len    = (strlen(ds_name)+2) & ~1;
			tp -> tp_offset = 0;

			strcpy(buf, ds_name[n]);
			err = d3tin(id_dsp, row, 1, templete);
			if (err != 0)
				return(d3cver(err, index_type));
		}
	}			/* repeat NITEMS */

	if ((err = d3lend(id_dsp, "wq", 1)) != 0) /* 索引ファイル使用終了 */
		return(d3cver(err, index_type));

	return(0);
}
