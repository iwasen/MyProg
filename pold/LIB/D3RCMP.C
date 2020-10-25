/************************************************************
 *	FILE DESCRIPTION
 *		ê¨ï™ÇÃì«Ç›çûÇ›ÇÇ®Ç±Ç»Ç§
 *	FILE	:	d3rcmp.c
 *		:	d3rcmp()
 *	REMARKS	:	1988-05-31	S.AIZAWA
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

static	char	sccsid[]="@(#)d3rcmp.c 1.2 88/06/12 15:03:17";

d3rcmp(mp, comp_name, mode, nitems, ptr)
DMEM	*mp;
char	*comp_name;
int	*mode, *nitems;
int	**ptr;
{
	PD_DENT	box;
	int	err;

	if ((err = d3gent(mp, comp_name, &box)) != 0)
		return(err);

	*mode = box.ent_mode;
	*nitems = box.ent_length;
	switch (box.ent_mode) {
	case DS_N:
		*ptr = NULL;
		break;
	case DS_L:
	case DS_I:
	case DS_R:
	case DS_C:
		*ptr = box.ent_value;
		break;
	case DS_STR:
		*ptr = (int *)((DMEM *)box.ent_value + 2);
		break;
	default:
		return(DE_DS_FORMAT);
	}

	return(0);
}
