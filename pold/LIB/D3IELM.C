/************************************************************
 *	FILE DESCRIPTION
 *		ê¨ï™ÇÃóvëfÇÃí«â¡ÇÇ®Ç±Ç»Ç§
 *	FILE	:	d3ielm.c
 *		:	d3ielm()
 *	REMARKS	:	1988-05-31	S.AIZAWA
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	char	*d3allc(), *d3csav();
extern	int	*d3ient();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3ielm(mp, comp_name, mode, nitems, ptr, pos, memno)
DMEM	*mp;
char	*comp_name;
int	mode, nitems;
int	*ptr;
int	pos, memno;
{
	PD_DENT	box;
	int	err, i;
	PD_ELM	dp;

	if ((err = d3gent(mp, comp_name, &box)) != 0)
		return(err);

	if (box.ent_mode != DS_N && box.ent_mode != mode || mode == DS_STR)
		return(DE_PRM);

	if (pos < 0 || pos > box.ent_length)
		return(DE_PRM);

	if (box.ent_mode == DS_N)
		box.ent_entp->type = mode;

	if (nitems == 0)
		return(0);

	if ((dp.ptr_i = d3ient(memno, box.ent_entp, pos, nitems)) == 0)
		return(DE_MEM);

	switch (mode) {
	case DS_L:
	case DS_I:
	case DS_R:
		for (i = 0; i < nitems; i++)
			dp.ptr_i[i] = ptr[i];
		break;
	case DS_C:
		for (i = 0; i < nitems; i++) {
			if ((dp.ptr_c[i] = d3csav(memno, ptr[i])) == 0)
				return(DE_MEM);
		}
		break;
	default:
		return(DE_PRM);
	}

	return(0);
}
