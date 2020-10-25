/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3dput.c
 *		ROUTINE		: 外部形式データ書き込み
 *		REVISION	:
 *		REMARKS		: 88.01.19	s.aizawa
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

#define	LIMIT_LEN	128		/* １行の最大文字数 */

static	char	sccsid[]="@(#)d3dput.c 1.5 88/06/12 14:50:04";
static	int	out_count;		/* １行の文字数カウンタ */

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3dput()
 *		INPUT		: mp = memory pointer
 *				: fp = file pointer
 *		OUTPUT		: return value
 *					=0 normal
 *				    not	=0 abnormal
 */

d3dput(mp, fp)
struct	dsm	*mp;
FILE	*fp;
{
	return(dput10(mp, fp,2));
}

static dput10(mp, fp,lv)
struct	dsm	*mp;
FILE	*fp;
int	lv;
{
	char	**cp;
	int	i, len, *ip;
	float	*rp;
	char	s[300];

	dput30(fp, lv);

	sprintf(s, "( \"%s\" ",mp->seibun);	/* write seibun mei */
	dput20(fp, s, lv);

	switch (mp->type) {
	case DS_N:
		i = 'N';
		break;
	case DS_L:
		i = 'L';
		break;
	case DS_I:
		i = 'I';
		break;
	case DS_R:
		i = 'R';
		break;
	case DS_D:
		i = 'D';
		break;
	case DS_C:
		i = 'C';
		break;
	case DS_STR:
		i = 'S';
		break;
	default:
		i = '*';
	}
	sprintf(s, "%c ",i);
	dput20(fp, s, lv);

	len = mp->length;
	switch (mp->type) {		/* data type ? */
	case DS_N:			/* null */
		sprintf(s, "%d ",len);
		dput20(fp, s, lv);
		break;
	case DS_L:			/* logical data */
		sprintf(s, "%d ",len);
		dput20(fp, s, lv);
		ip = mp->ptr.i;
		while (len--) {
			if (*ip == PD_NA)
				dput20(fp, " NA ", lv);
			else if (*ip)
				dput20(fp, " T ", lv);
			else
				dput20(fp, " F ", lv);
			ip++;
		}
		break;
	case DS_I:			/* integer data */
		sprintf(s, "%d ",len);
		dput20(fp, s, lv);
		ip = mp->ptr.i;
		while (len--) {
			if (*ip == PD_NA)
				dput20(fp, " NA ", lv);
			else {
				sprintf(s, " %d ",*ip);
				dput20(fp, s, lv);
			}
			ip++;
		}
		break;
	case DS_R:			/* real data */
		sprintf(s, "%d ",len);
		dput20(fp, s, lv);
		rp = mp->ptr.r;
		while (len--) {
			if (*((int *)rp) == PD_NA)
				dput20(fp, " NA ", lv);
			else {
				sprintf(s, " %.7g ",*rp);
				dput20(fp, s, lv);
			}
			rp++;
		}
		break;
	case DS_C:			/* string data */
		sprintf(s, "%d ",len);
		dput20(fp, s, lv);
		cp = mp->ptr.c;		/* get string pointer table address */
		while (len--) {
			sprintf(s, "\"%s\" ",*cp++);
			dput20(fp, s, lv);
		}
		break;
	case DS_STR:			/* structure */
		mp = mp->ptr.d;
		sprintf(s, "\"%s\" ",mp->ptr.s);
		dput20(fp, s, lv);
		mp++;
		sprintf(s, "%d ",mp->type);
		dput20(fp, s, lv);
		mp++;
		sprintf(s, "%d\n",len);
		dput20(fp, s, lv);
		while (len--)
			dput10(mp++, fp,lv+1);
		dput30(fp, lv);
		break;
	default:
		sprintf(s, "%d ",len);
		dput20(fp, s, lv);
		/* return(DE_DS_FORMAT); */
	}
	dput20(fp, ")\n", lv);

	return(0);			/* normal end */
}

dput20(fp, data, lv)
FILE	*fp;
char	*data;
int	lv;
{
	if (out_count > LIMIT_LEN) {
		fputs("\n", fp);
		dput30(fp, lv);
	}
	out_count += strlen(data);

	fputs(data, fp);
}

dput30(fp, lv)
FILE	*fp;
int	lv;
{
	int	i;

	out_count = 0;
	for (i = 0; i < lv; i++)
		dput20(fp, " ", lv);
}

