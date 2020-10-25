/************************************************************
 *	FILE DESCRIPTION
 *		ê¨ï™ÇÃóvëfÇÃíuä∑ÇÇ®Ç±Ç»Ç§
 *	FILE	:	d3uelm.c
 *		:	d3uelm()
 *	REMARKS	:	1988-04-28	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	char	*d3allc();

static	char	sccsid[]="@(#)d3uelm.c 1.3 88/06/12 15:02:08";

d3uelm(mp, comp_name, mode, nitems, ptr, from, memno)
DMEM	*mp;
char	*comp_name;
int	mode, nitems, from, memno;
int	*ptr;
{
	PD_DENT	box;
	int	err, f;
	char	**p, *data;

	err = d3gent(mp, comp_name, &box);
	if (err != 0) return(err);

	if (mode != box.ent_mode)
		return(DE_PRM);
	err = d3elck(box.ent_mode, box.ent_length, from, nitems);
	if (err != 0) return(err);
	
	switch (box.ent_mode) {
	case	DS_L:
	case	DS_I:
	case	DS_R:
		for (f = from -1; f < from + nitems - 1; f++)
			PD_VALUE(box.ent_entp)[f] = *((int *)ptr)++;
		break;
	case	DS_C:
		p = (char **)ptr;
		for (f = from - 1; f < from + nitems - 1; f++) {
			data = d3allc(memno, strlen(*p) + 1);
			if (data == NULL)
				return(DE_MEM);
			strcpy(data, *p++);
			(char *)(PD_VALUE(box.ent_entp))[f] = data;
		}		
		break;
	}

	return(0);
}
