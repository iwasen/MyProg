/************************************************************
 *	FILE DESCRIPTION
 *		create system-index
 *	FILE	:	d2mksi.c
 *		:	d2mksi
 *	REMARKS	:	1988-04-26	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>
#include "la_ws/include/d2lerr.h"
#include "la_ws/include/d3libc.h"

extern	DSET	*d3lbgn();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d2mksi(path_name)
char	*path_name;
{
	DSET	*dsp;
	int	l_flag=0, err;

	if ((dsp=d3lbgn(path_name, "c", l_flag, &err)) == NULL)
		return(d3cver(err, "if"));

	if ((err=mksi10(dsp)) != 0) {
		d3lend(dsp, "q", 0);
		return(d3cver(err, "if"));
	}

	if ((err=d3lend(dsp, "wq", 0)) != 0)
		return(d3cver(err, "if"));

	return(0);
}

static	mksi10(dsp)
DSET	*dsp;
{
	int	err;

	if ((err = d3comm(dsp, SYSTEM_INDEX_CM)) != 0)
		return(err);

	if((err=d3tinc(dsp, 0, DS_C, DSET_NAME)) != 0)
		return(err);

	return(0);
}
