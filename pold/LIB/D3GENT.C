/************************************************************
 *	FILE	DESCRIPTION
 *	COMMENT	:	return PD_DSENT from dset on memory
 *	FILE	:	d3gent.c
 *		:	d3gent()
 *	REMARKS	:	1988-04-26	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	DMEM	*d3gcmp();
static	char	sccsid[]="@(#)d3gent.c 1.2 88/06/12 15:00:46";

d3gent(dsm, comp_name, attr_p)
DMEM	*dsm;
char	*comp_name;
PD_DENT	*attr_p;
{
	int	n, err, k;
	char	**seibun;
	DMEM	*mp, *parent;

	if ((err = d3cmnm(comp_name, &n, &seibun)) !=0)
		return(err);
	for (mp=dsm, k=0; k<n; k++) {
		parent = mp;
		mp = d3gcmp(mp, seibun[k]);
		if (mp == NULL) break;
	}
	if (k<n) return(DE_PATH);	/* ƒpƒX–¼‚ªÕØ‚ê‚Ä‚¢‚é */

	attr_p->ent_entp   = mp;
	attr_p->ent_pos    = mp - (DMEM *)PD_VALUE(parent) - PD_HEADERNUM + 1;
	attr_p->ent_parent = parent;
	attr_p->ent_sname  = (char *)PD_VALUE((DMEM *)PD_VALUE(parent));
	attr_p->ent_name   = PD_NAME(mp);
	attr_p->ent_mode   = PD_MODE(mp);
	attr_p->ent_length = PD_LENGTH(mp);
	attr_p->ent_value  = PD_VALUE(mp);

	return(0);
}
