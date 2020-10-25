/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2back.c
 *		ROUTINE		: バックアップファイルメンテナンス
 *		REVISION	:
 *		REMARKS		: 88.04.13
 */

#include	<stdio.h>
#include	<string.h>
#include	"la_ws/include/d3libc.h"

static char	sccsid[]="@(#)d2back.c 1.4 88/06/30 18:16:02";

/*	FUNCTION DESCRIPTION
 *
 *			バックアップファイルメンテナンス
 *
 *		FUNC.NAME	: d2back()
 *		INPUT		: ladir = directory name
 *				: lads = la data set name
 *				: mode = 0 fukugen
 *				       = 1 ichiran
 *				       = 2 sakujo
 *		OUTPUT		: return value = error code
 */

d2back(ladir, lads, mode)
char	*ladir;
char	*lads;
int	*mode;
{
	int	err;

	switch (*mode) {		/* check mode */
	case 0:				/* fukugen */
		err = back10(ladir, lads);
		break;
	case 1:				/* ichiran */
		err = back20(ladir);
		break;
	case 2:				/* sakujo */
		err = back30(ladir, lads);
		break;
	default:
		return(d3cver(DE_PRM, ""));
	}

	return(err);
}

/*	FUNCTION DESCRIPTION
 *
 *			復元
 *
 *		FUNC.NAME	: back10()
 *		INPUT		: ladir = directory name
 *				: lads = la data set name
 *		OUTPUT		: return value = error code
 */

static
back10(ladir, lads)
char	*ladir;
char	*lads;
{
	char	lads_name[128];
	char	back_name[128];
	char	sys_index[128];
	int	err;

	d3path(ladir, lads, lads_name);		/* make lads path name */
	back40(ladir, lads, back_name);		/* make backup file name */

	if (d3fchk(back_name) != 0)		/* backup file check */
		return(d3cver(DE_FILE_NONE, "bk"));

	if (d3fchk(lads_name) == 0) {		/* check lads */
		if (unlink(lads_name) != 0)	/* unlink lads */
			return(d3cver(DE_FILE_IO, "ds"));
	}

	if (link(back_name, lads_name) != 0)	/* link lads */
		return(d3cver(DE_FILE_IO, "ds"));

	if (unlink(back_name) != 0)		/* unlink backup file */
		return(d3cver(DE_FILE_IO, "bk"));

	if (d3xgrp(lads))			/* group index */
		;
	else if (d3xind(lads)) {		/* system index */
		err = d2idmk(ladir, lads);	/* make system index */
		if (err != 0)
			return(err);
	} else {
		err = d2ichk(ladir, lads, sys_index);	/* get system index */
		if (err != 0)
			return(err);
		err = d2idup(ladir, sys_index, lads);	/* update system idx */
		if (err != 0)
			return(err);
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			一覧出力
 *
 *		FUNC.NAME	: back20()
 *		INPUT		: ladir = directory name
 *		OUTPUT		: return value = error code
 */

static
back20(ladir)
char	*ladir;
{
	char	cmd[128];
	FILE	*pfp;
	char	 *p;
	char	s[128];

	sprintf(cmd, "find %s -name \".B*\" -print|sort 2>/dev/null", ladir);
	if ((pfp = popen(cmd, "r")) == 0)	/* execute find command */
		return(d3cver(DE_FILE_IO, "tf"));

	while (fgets(s, 128, pfp) != 0) {	/* get backup file name */
		if ((p = strrchr(s, '/')) == 0)	/* search '/' */
			p = s + 2;
		else
			p += 3;			/* skip "/.B" */
		printf("%s", p);		/* output file name */
	}

	pclose(pfp);

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			バックアップファイル削除
 *
 *		FUNC.NAME	: back30()
 *		INPUT		: ladir = directory name
 *				: lads = la data set name
 *		OUTPUT		: return value = error code
 */

static
back30(ladir, lads)
char	*ladir;
char	*lads;
{
	char	back_name[128];

	back40(ladir, lads, back_name);		/* name backup file name */

	if (d3fchk(back_name) != 0)		/* check backup file */
		return(d3cver(DE_FILE_NONE, "bk"));

	if (unlink(back_name) != 0)		/* unlink backup file */
		return(d3cver(DE_FILE_IO, "bk"));

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			バックアップファイル名作成
 *
 *		FUNC.NAME	: back40()
 *		INPUT		: ladir = directory name
 *				: lads = la data set name
 *		OUTPUT		: back_path = backup file path name
 */

static
back40(ladir, lads, back_path)
char	*ladir;
char	*lads;
char	*back_path;
{
	char	back_name[128];

	strcpy(back_name, ".B");
	strcat(back_name, lads);

	d3path(ladir, back_name, back_path);
}

