/************************************************************
 *	FILE DESCRIPTION
 *		XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
 *	FILE	:	XXXXXXXX
 *		:	XXXXXXXX
 *	REMARKS	:	1988-04-26	X.XXXXXX
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"

extern	char	*malloc(), *regcmp(), *regex(), *strrchr();
static	char	sccsid[]="@(#)d3rgex.c 1.1 88/06/12 15:04:42";

d3rgex(key_val, val, op)
char	*key_val, *val;
int	op;
{
	char	ws[128], *name, *point;
	int	cf;

	d3cvre(val, ws, 0);
	if ((name = regcmp(ws, 0)) == NULL)
		return(0);
	point = regex(name, key_val);
	free(name);
	if (point == 0)
		cf = 0;
	else
		cf = 1;

	if (op == PD_CND_NE)
		cf = !cf;

	return(cf);
}

