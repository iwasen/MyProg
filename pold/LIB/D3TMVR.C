/************************************************************
 *	FILE DESCRIPTION
 *		表データセットの行移動機能
 *              指定された範囲の行を dst 行の直後に移動する
 *	FILE	:	d3tmvr.c
 *		:	d3tmvr
 *			dsp	:	DSET ポインタ
 *			from	:	移動開始行番号 ( from >= 1 )
 *			to	:	移動終了行番号 ( to <= 行数 )
 *			dst	:	移動先行番号 ( 0 <= dst <= 行数 )
 *	REMARKS	:	1988-07-14	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

#define	PREV	1
#define	SUCC	0

extern	char	*d3allc();
extern	DMEM	*d3gcmp(), *d3gtra();
extern	char	*malloc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";
static	DMEM	*datp, *dimp, *datap;

d3tmvr(dsp, from, to, dst)
DSET	*dsp;
int	from, to, dst;
{
	int	err, rn, cn, memno, nline;

						/* チェック */
	err = tcpr10(dsp, from, to, dst, &rn, &cn, &datp, &dimp, &datap);
	if (err != 0)
		return(err);

	nline = to - from + 1;
	memno = PD_MEMNO(dsp);
						/* データ部の移動 */
	if ((err = tmvr70(memno, from, to, dst, rn, cn, nline)) != 0)
		return(err);

	return(0);
}

/****************************************************
*	データ部の移動・移動
****************************************************/
static	tmvr70(memno, from, to, dst, rn, cn, nline)
int	memno, from, to, dst, rn, cn, nline;
{
	int	*work;
	int	*p, *q;
	int	newdst, i, j, k , shift;

	if (to < dst)
		shift = PREV;		/* 前詰め */
	else if (dst < from - 1)
		shift = SUCC;		/* 後詰め */
	else
		return(0);		/* 何もする必要がない */

	/* Data 成分の更新 */
					/* ワーク領域をアロケート */
	if ((work = (int *)malloc(nline * cn * sizeof(int))) == NULL)
		return(DE_MEM);

					/* 行データをワークにコピー */
	q = work;
	for (i=1; i<=cn; i++) {
		p = PD_VALUE(datap) + rn*(i - 1) + (from - 1);
		for (j=0; j<nline; j++)
			*q++ = *p++;
	}

					/* データのシフト*/
	if (shift == PREV) {		/* 前詰め */
		for (i=1; i<=cn; i++) {
			p = PD_VALUE(datap) + rn*(i - 1) + to;
			q = PD_VALUE(datap) + rn*(i - 1) + (from - 1);
			k = dst - to;
			for (j=0; j<k; j++)
				*q++ = *p++;
		}
		newdst = dst - nline;
	} else {			/* 後詰め */
		for (i=1; i<=cn; i++) {
			p = PD_VALUE(datap) + rn*(i - 1) + (from - 1);
			q = PD_VALUE(datap) + rn*(i - 1) + to;
			k = from - dst - 1;
			for (j=0; j<k; j++)
				*--q = *--p;
		}
		newdst = dst;
	}
				/* ワークの行データをデータ部にコピー */
	p = work;
	for (i=1; i<=cn; i++) {
		q = PD_VALUE(datap) + rn*(i - 1) + newdst;
		for (j=0; j<nline; j++)
			*q++ = *p++;
	}

	free(work);
	return(0);
}
