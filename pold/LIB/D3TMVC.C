/************************************************************
 *	FILE DESCRIPTION
 *		表構造体の列の移動機能
 *	FILE	:	d3tmvc.c
 *		:	d3tmvc
 *	REMARKS	:	1988-07-12	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

#define	PREV	1
#define	SUCC	0

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3tmvc(dsp, from, to, dst)
DSET	*dsp;
int	from, to, dst;
{
	int	err, i, ncol, shift;

	if (to < dst)
		shift = PREV;			/* 前詰め */
	else if (dst < from - 1)
		shift = SUCC;			/* 後詰め */
	else
		return(0);			/* 何もしない */

					/* 列のコピー */
	if ((err = d3tcpc(dsp, from, to, dst)) != 0) {
		return(err);
	}

	ncol = to - from + 1;
					/* 列の削除 */
	if (shift == PREV) {
		for (i=to; i>=from; i--) {
			err = d3tdlc(dsp, i);
			if (err != 0) {
				return(err);
			}
		}
	} else {
		for (i=to; i>=from; i--) {
			err = d3tdlc(dsp, i + (to - from + 1));
			if (err != 0) {
				return(err);
			}
		}
	}

	return(0);
}
