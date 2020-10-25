/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3toln.c
 *		ROUTINE		: 真偽値ベクトル－＞行番号ベクトル変換
 *		REVISION	:
 *		REMARKS		: 88.01.16
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3toln.c 1.3 88/06/12 14:58:45";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3toln(tf, line, n)
 *		INPUT		: tf   = 真偽値ベクトル
 *				: n    = 行数
 *		OUTPUT		: line = 行番号ベクトル
 */

d3toln(tf, line, n)
PD_TFREC	*tf;
PD_LNREC	*line;
int		n;
{
	int	i, tn, *lp;

	lp = line->ln_vec;
	for (i = 0, tn = 0; i < n; i++) {
		if (tf->tf_vec[i]) {
			*lp++ = i + 1;
			tn++;
		}
	}
	line->ln_n = tn;
	return(0);
}

