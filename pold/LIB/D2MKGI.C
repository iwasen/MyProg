/************************************************************
 *	FILE DESCRIPTION
 *		グループ索引の初期作成ルーチン
 *	FILE	:	d2mkgi.c
 *		:	d2mkgi
 *	REMARKS	:	1988-04-26	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>
#include "la_ws/include/d3nmty.h"
#include "la_ws/include/d3libc.h"

#define PD_ERROR(err)	return(d3cver(err,"gi"))

extern	DSET	*d3lbgn();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/************************************************	
	CREATE GROUP INDEX
*************************************************/
d2mkgi(ladb)
char	*ladb;
{
	int 	err, option=0;
	char	path_name[128];
	DSET	*dsp;

	d3path(ladb, GROUP_INDEX, path_name);

	dsp = d3lbgn(path_name, "c", option, &err);
	if (dsp == NULL) PD_ERROR(err);

	if ((err = mkgi10(dsp)) != 0) {
		d3lend(dsp, "q", 0);
		PD_ERROR(err);
	}

	err = d3lend(dsp, "wq", 1);	/* end of process table */
	if (err != 0) PD_ERROR(err);

	return(0);
}

static	mkgi10(dsp)
DSET	*dsp;
{
	int	err;

	err = d3comm(dsp, GROUP_INDEX_CM);
	if (err != 0) return(err);

	err = d3tinc (dsp, 0, DS_C, GRP_PREFIX);
	if (err != 0) return(err);

	err = d3tinc (dsp, 1, DS_C, GRP_NAME);
	if (err != 0) return(err);

	err = d3tinc (dsp, 2, DS_C, GRP_INDEX);
	if (err != 0) return(err);

	err = d3tinc (dsp, 3, DS_C, GRP_COMMENT);
	if (err != 0) return(err);

	return(0);
}
