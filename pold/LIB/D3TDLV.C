/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3tdlv.c
 *		ROUTINE		: 表操作 任意行削除
 *		REVISION	:
 *		REMARKS		: 88.01.12
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3tdlv.c 1.3 88/06/12 14:57:34";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3tdlv()
 *		INPUT		: dsp = data set pointer
 *				: rowvec = 行番号ベクトル
 *		OUTPUT		: return value = error code
 */

d3tdlv(dsp,rowvec)
DSET	*dsp;
PD_LNREC	*rowvec;
{
	int	i, err;

	for (i = rowvec->ln_n; i != 0; ) {
		if ((err = d3tdl(dsp, rowvec->ln_vec[--i], 1)) != 0)
			return(err);
	}
	return(0);
}

