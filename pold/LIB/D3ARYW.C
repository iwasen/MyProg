/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3aryw.c
 *		ROUTINE		: 配列書き込み処理
 *		REVISION	:
 *		REMARKS		: 88.01.19
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp();
extern	char	*d3allc(), *d3csav();

static	char	sccsid[]="@(#)d3aryw.c 1.3 88/06/12 14:46:46";

/*	FUNCTION DESCRIPTION
 *
 *			配列書き込み
 *
 *		FUNC.NAME	: d3aryw()
 *		INPUT		: dsp = data set pointer
 *				: attr = 配列データ
 *		OUTPUT		: return value = error code
 */

d3aryw(dsp, attr)
DSET	*dsp;
PD_ARYREC	*attr;
{
	struct	dsm	*datp, *sp;
	int	memno, i, size, *dp;

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* search "Dat" */
		return(DE_DS_FORMAT);

	memno = dsp->ds_memno;

	if ((sp = (struct dsm *)d3allc(memno, sizeof(struct dsm) * 4)) == NULL)
		return(DE_MEM);

	datp->type = DS_STR;
	datp->length = 2;
	datp->ptr.d = sp;

	sp->seibun = 0;
	sp->type = DS_DIR;
	sp->length = 2;
	if ((sp->ptr.s = d3csav(memno, "Dat")) == NULL)
		return(DE_MEM);

	sp++;
	sp->seibun = 0;
	sp->type = 21;
	sp->length = 0;
	*(int *)&sp->ptr = 2;

	sp++;
	if ((sp->seibun = d3csav(memno, "Dim")) == NULL)
		return(DE_MEM);
	sp->type = DS_I;
	sp->length = attr->array_dimno;
	if ((sp->ptr.i = (int *)d3allc(memno, sizeof(int) * 4)) == NULL)
		return(DE_MEM);
	sp->ptr.i[0] = attr->array_dim1;
	sp->ptr.i[1] = attr->array_dim2;
	sp->ptr.i[2] = attr->array_dim3;

	size = 1;
	for (i = 0; i < sp->length; i++)
		size *= sp->ptr.i[i];

	sp++;
	if ((sp->seibun = d3csav(memno, "Data")) == NULL)
		return(DE_MEM);
	sp->type = attr->array_attr;
	sp->length = size;
	if ((dp = (int *)d3allc(memno, sizeof(int) * d3gsiz(size))) == NULL)
		return(DE_MEM);
	for (i = 0; i < size; i++)
		dp[i] = attr->array_ptr[i];
	sp->ptr.i = dp;

	return(0);
}

