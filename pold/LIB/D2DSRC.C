/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2dsrc.c
 *		ROUTINE		: 外部表現ファイルの格納
 *		REVISION	:
 *		REMARKS		: 88.02.08
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	DSET	*d3lbgn();

static char	sccsid[]="@(#)d2dsrc.c 1.3 88/06/12 14:40:41";

/*	FUNCTION DESCRIPTION
 *
 *			send data set
 *
 *		FUNC.NAME	: d2dsrc()
 *		INPUT		: ladir = directory name
 *				: ds_name = data set name
 *				: in_file = out put file name
 *				: ow_flag = over write flag
 *		OUTPUT		: return value = error code
 */

d2dsrc(ladir, ds_name, in_file, ow_flag)
char	*ladir, *ds_name, *in_file;
int	*ow_flag;
{
	DSET	*dsp;
	FILE	*fp;
	int	mgno, flag, err;
	struct	dsm	*mp;
	char	ds_path[128], index_file[128];

	if ((mgno = d3mgno()) < 0)
		return(d3cver(DE_MEM, ""));

	if ((fp = fopen(in_file, "r")) == NULL)
		return(d3cver(DE_FILE_NONE, "gh"));

	if ((err = d3dget(fp, mgno, &mp)) != 0) {
		fclose(fp);
		d3free(mgno);
		return(d3cver(err, "gh"));
	} 

	fclose(fp);

	if (ds_name == NULL || strcmp(ds_name, "-") == 0)
		ds_name = mp->ptr.d->ptr.s;
	else
		mp->ptr.d->ptr.s = ds_name;

	if (d3xgrp(ds_name) || d3xind(ds_name))
		flag = 0;
	else {
		if ((err = d2ichk(ladir, ds_name, index_file)) != 0) {
			d3free(mgno);
			return(err);
		}
		flag = 1;
	}

	d3path(ladir, ds_name, ds_path);
	dsp = d3lbgn(ds_path, *ow_flag == 0 ? "c" : "c+", 0, &err);
	if (err != 0) {
		d3free(mgno);
		return(d3cver(err, "ds"));
	}

	dsp->ds_entry = mp;

	if ((err = d3lend(dsp, "wq", 0)) != 0) {
		d3free(mgno);
		return(d3cver(err, "ds"));
	}

	if (flag != 0) {
		if ((err = d2idup(ladir, index_file, ds_name)) != 0) {
			d3free(mgno);
			return(err);
		}
	}

	d3free(mgno);

	return(0);
}

