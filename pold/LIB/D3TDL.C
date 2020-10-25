/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3tdl.c
 *		ROUTINE		: 表操作 １行削除
 *		REVISION	:
 *		REMARKS		: 88.01.09
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp();

static	char	sccsid[]="@(#)d3tdl.c 1.3 88/06/12 14:57:01";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3tdl()
 *		INPUT		: dsp = data set pointer
 *				: row = 削除行
 *				: n = 削除行数
 *		OUTPUT		: return value = error code
 */

d3tdl(dsp,row,n)
DSET	*dsp;
int	row, n;
{
	struct	dsm	*datp, *dimp, *datap;
	int	rn, cn, i, j, *rp, *wp;

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* search "Dat" */
		return(DE_DS_FORMAT);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)	/* search "Dim" */
		return(DE_DS_FORMAT);

	if ((datap = d3gcmp(datp, "Data")) == NULL)	/* search "Data" */
		return(DE_DS_FORMAT);

	if (dimp->length != 2)			/* 次元数チェック */
		return(DE_DS_FORMAT);

	rn = dimp->ptr.i[0];			/* 行数取り出し */
	cn = dimp->ptr.i[1];			/* 列数取り出し */

	if (row + n - 1 > rn)			/* パラメータチェック */
		return(DE_PRM);

	rp = wp = datap->ptr.i;			/* set data pointer */
	for (i = 1; i <= cn; i++) {
		for (j = 1; j <= rn; j++) {
			if (j >= row && j < (row+n))
				rp++;
			else
				*wp++ = *rp++;
		}
	}

	dimp->ptr.i[0] -= n;
	datap->length -= cn * n;
	return(0);
}

