/************************************************************
 *	FILE DESCRIPTION
 *		ê¨ï™ñºÇÃïœçX
 *	FILE	:	d3ucnm.c
 *		:	d3ucnm
 *	REMARKS	:	1988-05-02	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include <string.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	char	*d3allc();
static	char	sccsid[]="@(#)d3ucnm.c 1.3 88/06/12 15:01:26";

d3ucnm(mp, comp_name, new_name, memno)
DMEM	*mp;
char	*comp_name, *new_name;
int	memno;
{
	int	err;
	PD_DENT	box;
	char	*data;

	err = d3gent(mp, comp_name, &box);
	if (err != 0) return(err);

	data = d3allc(memno, strlen(new_name) + 1);
	if (data == NULL) return(DE_MEM);

	strcpy(data, new_name);
	PD_NAME(box.ent_entp) = data;

	return(0);
}
