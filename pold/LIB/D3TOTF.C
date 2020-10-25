/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3totf.c
 *		ROUTINE		: �s�ԍ��x�N�g���|���^�U�l�x�N�g���ϊ�
 *		REVISION	:
 *		REMARKS		: 88.01.16
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3totf.c 1.3 88/06/12 14:59:01";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3totf(line, tf, n)
 *		INPUT		: line = �s�ԍ��x�N�g��
 *				: n    = �s��
 *		OUTPUT		: tf   = �^�U�l�x�N�g��
 */

d3totf(line, tf, n)
PD_LNREC	*line;
PD_TFREC	*tf;
int		n;
{
	int	i;

	tf->tf_n = line->ln_n;

	for (i = 0; i < n; i++)
		tf->tf_vec[i] = 0;

	for (i = 0; i < tf->tf_n; i++)
		tf->tf_vec[line->ln_vec[i] - 1] = 1;

	return(0);
}
