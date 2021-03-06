/************************************************************
 *	FILE DESCRIPTION
 *		LA-DATASET NAMEING CHECK by DSET-NAME
 *
 *		:	d3nmty
 *	REMARKS	:	1988-06-10	M.MOTOKI
 *
 ************************************************************/
#include	<string.h>
#include	<ctype.h>
#include	"la_ws/include/d3nmty.h"

static	char	sccsid[]="@(#)d3nmty.c 1.2 88/06/30 18:16:17";

d3nmty(name)
char	*name;
{
	int	file_tp;

	file_tp = 0;

	if (*name == '.') {			/* 隠しファイル */
		name++;
		if (*name == 'B')
			file_tp |= TYPE_BACKF;
		else if (*name == 'T')
			file_tp |= TYPE_TEMPF;
		else
			return(0);
		name++;
	}

	if (strcmp(name, GROUP_INDEX) == 0) {
		file_tp |= TYPE_GROUP;
	} else  if (strncmp(name, INDEX_PFIX, strlen(INDEX_PFIX)) == 0) {
		file_tp |= TYPE_SINDX;
	} else  if (strncmp(name, SUSER_PFIX, strlen(SUSER_PFIX)) == 0) {
		file_tp |= TYPE_SUSER;
	} else  if (strncmp(name, UUSER_PFIX, strlen(UUSER_PFIX)) == 0) {
		file_tp |= TYPE_UUSER;
	} else if (*name < 0 ||			/* 日本語であるかまたは、 */
		(isascii(*name) && islower(*name)))	/* 英小文字であるなら */
		file_tp |= TYPE_UDATA;
	else
		return(0);	/* システムで認識していないファイル名 */

	if (nmty00(name))	/* 最後に、ＰＯＬＫで認識できる名前かチェック */
		return(file_tp);
	else
		return(0);	/* システムで認識していないファイル名 */

}
/************************************/
/* Is Group-Index used by System    */
/************************************/
d3xgrp(name)
char	*name;
{
	if (strcmp(name, GROUP_INDEX) == 0)
		return(1);
	else
		return(0);
}

/************************************/
/* Is System-Index used by System   */
/************************************/
d3xind(name)
char	*name;
{
	if (strncmp(name, INDEX_PFIX, strlen(INDEX_PFIX)) == 0 &&
			nmty00(name) != 0)
		return(1);
	else
		return(0);
}

/************************************/
/* Is User-Index used by System     */
/************************************/
d3xsys(name)
char	*name;
{
	if (strncmp(name, SUSER_PFIX, strlen(SUSER_PFIX)) == 0 &&
			nmty00(name) != 0)
		return(1);
	else
		return(0);
}
/************************************/
/* Is User Index used by User       */
/************************************/
d3xusr(name)
char	*name;
{
	if (strncmp(name, UUSER_PFIX, strlen(UUSER_PFIX)) == 0 &&
			nmty00(name) != 0)
		return(1);
	else
		return(0);
}
/************************************/
/* Is User-Data used by User        */
/************************************/
d3xdat(name)
char	*name;
{
	if (*name < 0 ||		/* １字めが日本語であるかまたは、 */
	   (isascii(*name) && islower(*name))) {	/* 英小文字であるとき */
	   	if (nmty00(name))	/* 全体が、ＰＯＬＫで認められた名前 */
			return(1);
	}

	return(0);
}
/************************************/
/* Is Temp-File used by System      */
/************************************/
d3xtmp(name)
char	*name;
{
	if (strncmp(name, TEMP_PFIX, strlen(TEMP_PFIX)) == 0 &&
			nmty00(name) != 0)
		return(1);
	else
		return(0);
}
/************************************/
/* Is back-up File used by System   */
/************************************/
d3xbak(name)
char	*name;
{
	if (strncmp(name, BACK_PFIX, strlen(BACK_PFIX)) == 0 &&
			nmty00(name) != 0)
		return(1);
	else
		return(0);
}
/************************************/
/* Is Dataset-Name-Composed Characters   */
/************************************/
static	nmty00(name)
char	*name;
{
	char	*p;
	int	i, j;

	j = *name == '.' ? 14 : 12;
	for (p = name, i=0; *p && i<j; p++, i++) {
		if (*p < 0)		/* 日本語ならＯＫ */
			continue;
		if (isascii(*p) && isalnum(*p))	/* 英字・数字ならＯＫ */
			continue;
		if (*p == '.')		/* "." ならＯＫ */
			continue;
		else
			break;		/* ＬＡデータセット名ではない */
	}
	if (*p == '\0')
		return(1);			/* O.K. */
	else
		return(0);			/* N.G. */
}
