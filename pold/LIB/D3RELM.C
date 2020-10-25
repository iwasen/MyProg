/************************************************************
 *	FILE DESCRIPTION
 *		ê¨ï™ÇÃóvëfÇÃì«Ç›çûÇ›ÇÇ®Ç±Ç»Ç§
 *	FILE	:	d3relm.c
 *		:	d3relm()
 *	REMARKS	:	1988-05-31	S.AIZAWA
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

static	char	sccsid[]="@(#)d3relm.c 1.2 88/06/12 15:03:49";

d3relm(mp, comp_name, mode, nitems, ptr, pos)
DMEM	*mp;
char	*comp_name;
int	mode, nitems;
int	**ptr;
int	pos;
{
	PD_DENT	box;
	int	err;

	if ((err = d3gent(mp, comp_name, &box)) != 0)
		return(err);

	switch (mode) {
	case DS_N:
		break;
	case DS_L:
	case DS_I:
	case DS_R:
	case DS_C:
		if (mode != box.ent_mode)
			return(DE_PRM);
		break;
	default:
		return(DE_PRM);
	}

	if (pos <= 0 || nitems < 0 || pos+nitems > box.ent_length+1)
		return(DE_PRM);

	*ptr = box.ent_value + pos - 1;

	return(0);
}
