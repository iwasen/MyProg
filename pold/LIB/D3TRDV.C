/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3trdv.c
 *		ROUTINE		: 表操作  任意行読み込み
 *		REVISION	:
 *		REMARKS		: 88.01.12
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3trdv.c 1.3 88/06/12 14:59:27";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3trdv()
 *		INPUT		: dsp = data set pointer
 *				: rowvec = 行番号ベクトル
 *				: templete = templete pointer
 *		OUTPUT		: return value = error code
 */

d3trdv(dsp, rowvec, templete)
DSET	*dsp;
PD_LNREC	*rowvec;
T_TEMPLETE	*templete;
{
	int	i, err;
	char	*bufp_save;

	bufp_save = templete->tp_bufp;
	err = 0;

	for (i = 0; i < rowvec->ln_n; i++) {
		if ((err = d3trd(dsp, rowvec->ln_vec[i], 1, templete)) != 0)
			break;
		templete->tp_bufp += templete->tp_rlen;
	}

	templete->tp_bufp = bufp_save;
	return(err);
}

