/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2idmk.c
 *		ROUTINE		: make index file
 *		REVISION	:
 *		REMARKS		: 88.02.09
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d3nmty.h"

extern	DSET	*d3lbgn();
extern	char	*strrchr();

static	char	sccsid[]="@(#)d2idmk.c 1.5 88/06/30 18:16:07";
static	DSET	*grp_dsp, *idx_dsp;

/*	FUNCTION DESCRIPTION
 *
 *			make index file main routine
 *
 *		FUNC.NAME	: d2idmk()
 *		INPUT		: ladir = directory name
 *				: id_name  = index file name
 *		OUTPUT		: return value = error code
 */

d2idmk(ladir, id_name)
char	*ladir, *id_name;
{
	int	err;

	grp_dsp = idx_dsp = 0;

	err = idmk10(ladir, id_name);		/* make index file */

	if (grp_dsp != 0)
		d3lend(grp_dsp, "q", 0);

	if (idx_dsp != 0) {
		if (err == 0)
			err = d3lend(idx_dsp, "wq", 1);
		else
			d3lend(idx_dsp, "q", 0);
	}

	return(err);
}

/*	FUNCTION DESCRIPTION
 *
 *			make index file
 *
 *		FUNC.NAME	: idmk10()
 *		INPUT		: ladir = directory name
 *				: id_name = index file name
 *		OUTPUT		: return value = error code
 */

static
idmk10(ladir, id_name)
char	*ladir, *id_name;
{
	char	grp_path[128], idx_path[128], cmdbuf[1024];
	int	err;

	d3path(ladir, GROUP_INDEX, grp_path); /* make group index path name */
	grp_dsp = d3lbgn(grp_path, "r", 0, &err); /* begin using group index */
	if (err != 0)
		return(d3cver(err, "gi"));

	err = idmk20(ladir, id_name, cmdbuf);	/* make "find" command */
	if (err != 0)
		return(err);

	d3path(ladir, id_name, idx_path);	/* make index path name */
	idx_dsp = d3lbgn(idx_path, "u", DSO_NBAK, &err); /* begin using index file */
	if (err != 0)
		return(d3cver(err, "if"));

	err = idmk30(cmdbuf);			/* make index file */

	return(err);
}

/*	FUNCTION DESCRIPTION
 *
 *			make "find" command
 *
 *		FUNC.NAME	: idmk20()
 *		INPUT		: ladir = directory name
 *				: id_name = index file name
 *		OUTPUT		: cmdp = command buffer pointer
 *				: return value = error code
 */

static
idmk20(ladir, id_name, cmdp)
char	*ladir, *id_name, *cmdp;
{
	int	row_n, err, col_index, col_prefix, keyw_n, i;
	char	**keyw_p;
	PD_CNDREC	cond;
	PD_TFREC	tfv;
	PD_LNREC	lnv;
	T_TEMPLETE	templete;
	char	buf[256];

	/* get line number */
	if ((row_n = d3trow(grp_dsp)) < 0)
		return(d3cver(row_n, "gi"));

	/* get key word */
	if ((err = d3tkey(grp_dsp, &keyw_n, &keyw_p)) != 0)
		return(d3cver(err, "gi"));

	/* get column of index file name */
	if ((col_index = idmk25(keyw_p, keyw_n, GRP_INDEX)) == 0)
		return(d3cver(DE_DS_FORMAT, "gi"));

	/* get column of prefix */
	if ((col_prefix = idmk25(keyw_p, keyw_n, GRP_PREFIX)) == 0)
		return(d3cver(DE_DS_FORMAT, "gi"));

	/* search prefix */
	cond.cnd_relop = PD_CND_AND;
	cond.cnd_col = col_index;
	cond.cnd_op = PD_CND_EQ;
	cond.cnd_val.c_ptr = id_name;
	if ((err = d3srch(grp_dsp, &cond, 0, &tfv)) != 0)
		return(d3cver(err, "gi"));

	if (tfv.tf_n == 0)
		return(d3cver(DE_NONE_GRP, ""));

	d3toln(&tfv, &lnv, row_n);		/* convert to line No. */

	/* make templete */
	templete.tp_bufp = buf;
	templete.tp_cnum = 1;
	templete.tp_templete[0].tp_col = col_prefix;
	d3mktp(grp_dsp, &templete);

	/* make "find" command */
	sprintf(cmdp, "find %s -type f \\( ", ladir);
	for (i = 0; i < lnv.ln_n; i++) {
		if ((err = d3trd(grp_dsp, lnv.ln_vec[i], 1, &templete)) != 0)
			return(d3cver(err, "gi"));
		if (i != 0)
			strcat(cmdp, "-o ");
		strcat(cmdp, "-name \"");
		strcat(cmdp, buf);
		strcat(cmdp, "*\" ");
	}
	strcat(cmdp, "\\) -print|sort");

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			search keyword
 *
 *		FUNC.NAME	: idmk25()
 *		INPUT		: keyw_p = keyword buffer pointer
 *				: keyw_n = keyword number
 *				: keyword = keyword
 *		OUTPUT		: return value = column
 */

static
idmk25(keyw_p, keyw_n, keyword)
char	**keyw_p;
int	keyw_n;
char	*keyword;
{
	int	i;

	for (i = 0; i < keyw_n; i++) {
		if (strcmp(*keyw_p++, keyword) == 0)
			return(i + 1);
	}
	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			make index file
 *
 *		FUNC.NAME	: idmk30()
 *		INPUT		: cmdp = "find" command buffer
 *		OUTPUT		: return value = error code
 */

static
idmk30(cmdp)
char	*cmdp;
{
	int	row_n, err, row;
	DSET	*ds_dsp;
	FILE	*fp;
	char	tmp_buf[4096];
	T_TEMPLETE	*tp = (T_TEMPLETE *)tmp_buf;
	char	t_buf[4096];
	char	s[128];
	char	*idmk35();

	if ((row_n = d3trow(idx_dsp)) < 0)		/* get line number */
		return(d3cver(row_n, "if"));

	if ((err = d3tdl(idx_dsp, 1, row_n)) != 0)	/* all line clear */
		return(d3cver(err, "if"));

	if ((fp = popen(cmdp, "r")) == 0)		/* pipe open */
		return(d3cver(DE_FILE_IO, "tf"));

	row = 0;
	tp->tp_bufp = t_buf;
	while (fgets(s, 128, fp) != 0) {
		s[strlen(s) - 1] = '\0';
		ds_dsp = d3lbgn(s, "r1", 0, &err);
		if (err != 0) {
			pclose(fp);
			return(d3cver(err, "ds"));
		}
		idup10(idx_dsp, ds_dsp, idmk35(s), tp);	/* set templete */
		d3lend(ds_dsp, "q", 0);
		err = d3tin(idx_dsp, row++, 1, tp);	/* insert 1 line */
		if (err != 0) {
			pclose(fp);
			return(d3cver(err, "if"));
		}
	}
	pclose(fp);
	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			get data set name
 *
 *		FUNC.NAME	: idmk35()
 *		INPUT		: s = data set path name
 *		OUTPUT		: return value = data set name
 */

static char *
idmk35(s)
char	*s;
{
	char	*p;

	if ((p = strrchr(s, '/')) == 0)
		return(s);
	else
		return(p + 1);
}

