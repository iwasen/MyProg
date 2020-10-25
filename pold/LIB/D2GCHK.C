#include <stdio.h>
#include "la_ws/include/d3libc.h"

#define	ERROR(err,typ)	{if (dsp!=NULL) d3lend(dsp,"q",0); return(d3cver(err,typ));}
	
extern	DSET	*d3lbgn();
static	char	sccsid[]="@(#)d2gchk.c 1.2 88/06/12 14:42:12";

d2gchk(ladb,sys_idx)
char *ladb, *sys_idx;
{
	DSET	*dsp;
	char	path_name[128];
	int	err;

	dsp = NULL;

	d3path(ladb, "GRP.IND", path_name);

	if ((dsp=d3lbgn(path_name, "r", 0, &err)) == NULL) ERROR(err,"gi")

	if ((err=d3gidx(dsp,sys_idx)) != 0) ERROR(err," ")

	if ((err=d3lend(dsp, "q", 0)) != 0) ERROR(err,"gi")

	return(0);
}
