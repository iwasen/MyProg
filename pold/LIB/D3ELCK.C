
/************************************************************
 *	FILE DESCRIPTION
 *		成分の要素を置換・削除するときの前チェック
 *	FILE	:	d3elck.c
 *		:	d2elck()
 *	REMARKS	:	1988-04-28	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3elck.c 1.2 88/06/12 15:02:36";

d3elck(mode, length, from, nitems)
int	mode, length, from, nitems;
{
	if ((from <= 0) || (nitems <= 0))
		return(DE_PRM);
	if (mode == DS_STR)
		return(DE_PRM);
	else if (mode == DS_N)
		return(DE_PRM);
	else if (mode == DS_MISS)
		return(DE_PRM);
	if (length < from + nitems - 1)
		return(DE_PRM);
	return(0);
}
