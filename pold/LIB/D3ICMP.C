/************************************************************
 *	FILE DESCRIPTION
 *		ê¨ï™ÇÃí«â¡ÇÇ®Ç±Ç»Ç§
 *	FILE	:	d3icmp.c
 *		:	d3icmp()
 *	REMARKS	:	1988-05-31	S.AIZAWA
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	char	*d3allc(), *d3csav();
extern	DMEM	*d3ient();

static	char	sccsid[]="@(#)d3icmp.c 1.2 88/06/12 15:03:30";

d3icmp(mp, comp_name, new_comp_name, pos, memno)
DMEM	*mp;
char	*comp_name, *new_comp_name;
int	pos, memno;
{
	PD_DENT	box;
	int	err;
	DMEM	*dp, *dp2;

	if ((err = d3gent(mp, comp_name, &box)) != 0)
		return(err);

	if (pos < 0 || pos > box.ent_length)
		return(DE_PRM);

	switch (box.ent_mode) {
	case DS_N:
		dp = box.ent_entp;
		dp->type = DS_STR;
		dp->length = 0;
		if ((dp2 = (DMEM *)d3allc(memno, sizeof(DMEM) * 2)) == 0)
			return(DE_MEM);
		dp->ptr.d = dp2;

		dp2->seibun = 0;
		dp2->type = DS_DIR;
		dp2->length = 0;
		if ((dp2->ptr.s = d3csav(memno, dp->seibun)) == NULL)
			return(DE_MEM);

		dp2++;
		dp2->seibun = 0;
		dp2->type = 10;
		dp2->length = 0;
		*(int *)&dp2->ptr = 0;
		break;
	case DS_STR:
		break;
	default:
		return(DE_PRM);
	}

	if ((dp = d3ient(memno, box.ent_entp, pos, 1)) == 0)
		return(DE_MEM);
	if ((dp->seibun = d3csav(memno, new_comp_name)) == 0)
		return(DE_MEM);
	dp->type = DS_N;
	dp->length = 0;
	dp->ptr.i = 0;

	return(0);
}
