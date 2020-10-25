/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3tin.c
 *		ROUTINE		: 表操作  ｎ行挿入
 *		REVISION	:
 *		REMARKS		: 88.01.09
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp(), *d3gtra();
extern	char	*d3allc();

static	char	sccsid[]="@(#)d3tin.c 1.4 88/06/12 14:57:45";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3tin()
 *		INPUT		: dsp = data set pointer
 *				: row = 挿入行位置
 *				: n   = 挿入行数
 *				: templete = templete pointer
 *		OUTPUT		: return value = error code
 */

d3tin(dsp, row, n, templete)
DSET	*dsp;
int	row, n;
T_TEMPLETE	*templete;
{
	int	st;
	struct	dsm	*labp, *axip, *trap, *datp, *dimp, *datap;
	int	rn, cn, i, j, *rp, *wp, *dp, size1, size2, code;
	static	int	na = PD_NA;

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL) /* search "Lab" */
		return(DE_DS_FORMAT);

	if ((axip = d3gcmp(labp, "Axi2")) == NULL)	/* search "Axi2" */
		return(DE_DS_FORMAT);

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
	dp = datap->ptr.i;			/* set data pointer */

	if (row < 0 || row > rn)		/* パラメータチェック */
		return(DE_PRM);

	rp = datap->ptr.i + rn * cn;

	size1 = d3gsiz(datap->length);
	size2 = d3gsiz(datap->length + n * cn);
	if (size1 < size2) {
		if ((dp = (int *)d3allc(dsp->ds_memno, size2 * sizeof(int))) == NULL)
			return(DE_MEM);
		datap->ptr.i = dp;
	}

	rn += n;
	wp = datap->ptr.i + rn * cn;

	for (i = cn; i > 0; i--) {
		for (j = rn; j > 0; j--) {
			if (j > row && j <= (row + n))
				*--wp = PD_NA;
			else
				*--wp = *--rp;
		}
	}
	dimp->ptr.i[0] = rn;
	datap->length += cn * n;

	st = tup10(dsp, axip, datap, rn, row+1, n, templete);
	if (st != 0)
		return(st);

	for (i = 0; i < cn; i++) {
		if (dp[row + i * rn] == PD_NA) {
			if ((trap = d3gtra(axip,i+1)) != NULL) {
				switch (trap->type) {
				case DS_L:
				case DS_I:
				case DS_R:
					st = tup20(axip, &na, i+1, dsp->ds_memno, &code);
					if (st != 0)
						return(st);
					for (j = row; j < row + n; j++)
						dp[j + i * rn] = code;
					break;
				case DS_C:
					st = tup20(axip, "", i+1, dsp->ds_memno, &code);
					if (st != 0)
						return(st);
					for (j = row; j < row + n; j++)
						dp[j + i * rn] = code;
					break;
				}
			}
		}
	}
	return(0);
}

