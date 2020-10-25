/************************************************************
 *	FILE DESCRIPTION
 *		表データセットの行コピー機能
 *              指定された範囲の行を dst 行の直後にコピーする
 *	FILE	:	d3tcpr.c
 *		:	d3tcpr
 *			dsp	:	DSET ポインタ
 *			from	:	コピー開始行番号 ( from >= 1 )
 *			to	:	コピー終了行番号 ( to <= 行数 )
 *			dst	:	コピー先行番号 ( 0 <= dst <= 行数 )
 *	REMARKS	:	1988-07-11	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>

#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	char	*d3allc();
extern	DMEM	*d3gcmp(), *d3gtra();
extern	char	*malloc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";
static	DMEM	*datp, *dimp, *datap;

d3tcpr(dsp, from, to, dst)
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
						/* データ部のコピー */
	if ((err = tcpr70(memno, from, dst, rn, cn, nline)) != 0)
		return(err);

	return(0);
}

/****************************************************
*	チェックルーチン
****************************************************/
tcpr10(dsp, from, to, dst, rn, cn, datp, dimp, datap)
DSET	*dsp;
int	from, to, dst;
int	*rn, *cn;
DMEM	**datp, **dimp, **datap;
{
	if ((*datp = d3gcmp(PD_DMEM(dsp), "Dat")) == NULL)
		return(DE_DS_FORMAT);

	if ((*datap = d3gcmp(*datp, "Data")) == NULL)
		return(DE_PRM);

	if ((*dimp = d3gcmp(*datp, "Dim")) == NULL)
		return(DE_PRM);

	if (PD_LENGTH(*dimp) != 2)
		return(DE_PRM);

	*rn = *(PD_VALUE(*dimp) + 0);
	*cn = *(PD_VALUE(*dimp) + 1);

	if (1 <= from	&&
		from <= to	&&
		to   <= *rn	&&
		0    <= dst	&&
		dst  <= *rn)
		;			/* OK */
	else
		return(DE_PRM);

	return(0);
}

/****************************************************
*	データ部のコピー
****************************************************/
static	tcpr70(memno, from, dst, rn, cn, nline)
int	memno, from, dst, rn, cn, nline;
{
	int	*work, *data, *d, *s, *w;
	int	*p, *q;
	int	i, j, k , size;
	int	*source, *destin;

					/* Dim 成分の更新 */
	*PD_VALUE(dimp) += nline;
					/* Data 成分の更新 */

	source = PD_VALUE(datap);	/* オリジナルデータへのポインタ
					   を保存する */

					/* データ部に空きがあるか */
	size = d3gsiz((rn + nline) * cn);

	if (size != d3gsiz(rn * cn)) {
		if ((data = (int *)d3allc(memno, size * sizeof(int))) == NULL)
			return(DE_MEM);

		PD_VALUE(datap) = data;	/* 新しいデータ領域へのポインタ */
	}

	destin = PD_VALUE(datap);	/* 転送先領域へのアドレス */

					/* ワーク領域をアロケート */
	if ((work = (int *)malloc(nline * cn * sizeof(int))) == NULL)
		return(DE_MEM);

					/* 行データをワークにコピー */
	q = work;
	for (i=1; i<=cn; i++) {
		p = source + rn*(i - 1) + (from - 1);	/* オリジナルデータ */

		for (j=0; j<nline; j++)
			*q++ = *p++;
	}

				/* ワークの行データをデータ部にコピー */
	s = source + rn * cn;		/* 転送元アドレス */
	d = destin + (rn + nline) * cn;	/* 転送先アドレス */
	w = work + nline * cn;		/* ワークアドレス */

	for (i=cn; i>0; i--) {

		k = rn - dst;
		for (j=0; j<k; j++)
			*--d = *--s;

		for (j=0; j<nline; j++)
			*--d = *--w;

		k = dst;
		for (j=0; j<k; j++)
			*--d = *--s;
	}

					/* Data 成分の要素数を更新する */
	PD_LENGTH(datap) += nline * cn;

	free(work);
	return(0);
}
