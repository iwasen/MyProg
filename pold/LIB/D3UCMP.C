/************************************************************
 *	FILE DESCRIPTION
 *		ê¨ï™ÇÃíuä∑ÇÇ®Ç±Ç»Ç§
 *	FILE	:	d3ucmp.c
 *		:	d3ucmp()
 *	REMARKS	:	1988-04-30	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	char	*d3allc();

static	char	sccsid[]="@(#)d3ucmp.c 1.3 88/06/12 15:01:12";

d3ucmp(mp, comp_name, cname, mode, nitems, ptr, memno)
DMEM	*mp;
char	*comp_name, *cname;
int	mode, nitems, memno, *ptr;
{
	PD_DENT	box;
	int	err, f, *p, *p0, *p1;
	char	**q0, **q1, **q, *data;

	err = d3gent(mp, comp_name, &box);
	if (err != 0) return(err);

	if (mode < DS_N || mode > DS_C)
		return(DE_PRM);

	if (nitems == 0) {
		strcpy(PD_NAME(box.ent_entp), cname);
		PD_MODE(box.ent_entp)   = DS_N;
		PD_LENGTH(box.ent_entp) = 0;
		PD_VALUE(box.ent_entp)  = NULL;
	} else {
		strcpy(PD_NAME(box.ent_entp), cname);
		PD_MODE(box.ent_entp)   = mode;
		PD_LENGTH(box.ent_entp) = nitems;

		switch (mode) {
		case	DS_L:
		case	DS_I:
		case	DS_R:
			p0 = (int *)d3allc(memno,
					d3gsiz(nitems) * sizeof(long));
			if (p0 == NULL)
				return(DE_MEM);
			p1 = p0; p = ptr;
			for (f = 0; f < nitems; f++)
				*p1++ = *p++;
			PD_VALUE(box.ent_entp) = p0;
			break;
		case	DS_C:
			q0 = (char **)d3allc(memno,
					d3gsiz(nitems) * sizeof(long));
			if (q0 == NULL)
				return(DE_MEM);
			q1 = q0; q = (char **)ptr;
			for (f = 0; f < nitems; f++) {
				data = d3allc(memno, strlen(*q) + 1);
				strcpy(data, *q++);
				*q1++ = data;
			}		
			PD_VALUE(box.ent_entp) = (int *)q0;
			break;
		defalut:
			PD_VALUE(box.ent_entp) = NULL;
			break;
		}
	}

	return(0);
}
