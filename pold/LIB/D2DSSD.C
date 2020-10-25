/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2dssd.c
 *		ROUTINE		: 外部表現ファイルの抽出
 *		REVISION	:
 *		REMARKS		: 88.02.08
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	DSET	*d3lbgn();

static char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION
 *
 *			send data set
 *
 *		FUNC.NAME	: d2dssd()
 *		INPUT		: ladir = directory name
 *				: ds_name = data set name
 *				: out_file = out put file name
 *		OUTPUT		: return value = error code
 */

d2dssd(ladir, ds_name, out_file)
char	*ladir, *ds_name, *out_file;
{
	DSET	*dsp;
	int	err;
	FILE	*fp;
	char	ds_path[128];

	d3path(ladir, ds_name, ds_path);
	dsp = d3lbgn(ds_path, "r", 0, &err);
	if (err != 0)
		return(d3cver(err, "ds"));

	if ((fp = fopen(out_file, "w")) == NULL) {
		d3lend(dsp, "q", 0);
		return(d3cver(DE_FILE_IO, "gh"));
	}

	err = d3dput(dsp->ds_entry, fp);
	if (err != 0) {
		d3lend(dsp, "q", 0);
		fclose(fp);
		return(d3cver(err, "ds"));
	}

	fclose(fp);

	d3lend(dsp, "q", 0);

	chmod(out_file, 0666);

	return(0);
}

