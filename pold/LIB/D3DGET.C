/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3dget.c
 *		ROUTINE		: 外部形式データ読み込み
 *		REVISION	:
 *		REMARKS		: 88.01.19	s.aizawa
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

#define	DSMSZ	sizeof(struct dsm)

extern	char	*d3allc(), *d3csav();
extern	double	atof();
char	*dget90();
static	char	sccsid[]="@(#)d3dget.c 1.5 88/06/21 11:37:30";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3dget()
 *		INPUT		: fp = file pointer
 *				  mgno = memory group No.
 *		OUTPUT		: mp = memory pointer
 *				  return value
 *					=0 normal
 *				    not	=0 abnormal
 */

d3dget(fp, mgno, mp)
FILE	*fp;
int	mgno;
char	**mp;
{
	struct dsm *dp;
	int	status;

	if ((dp = (struct dsm *)d3allc(mgno, DSMSZ)) == 0) /* memory allocate */
		return(DE_MEM);			/* error */

	if ((status = dget10(fp, mgno, dp)) != 0) { /* read data set */
		/* d3free(mgno); */		/* error , memory free */
		return(status);			/* abnormal end */
	}

	*mp = (char *)dp;			/* set data pointer */
	return(0);				/* normal end */
}


/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: dget10()
 *		INPUT		: fp = file pointer
 *				  mgno = memory group No.
 *				  dp = data pointer
 *		OUTPUT		: return value
 *					=0 normal
 *				    not =0 abnormal
 */

static
dget10(fp, mgno, dp)
FILE	*fp;
int	mgno;
struct dsm *dp;
{
	int	status, len, i, ss, type;
	char	*sp;
	struct	dsm	*dp2;

	if (dget20(fp) != 0)
		return(DE_DS_FORMAT);

	if (dget80(fp, &sp) != 0)		/* get seibun mei */
		return(DE_DS_FORMAT);

	if ((dp->seibun = d3csav(mgno, sp)) == 0) /* save seibunmei */
		return(DE_MEM);			/* error */

	if (dget40(fp, &type) != 0)		/* set data type */
		return(DE_DS_FORMAT);
	dp->type = type;

	switch (type) {			/* data type ? */
	case DS_N:				/* null */
	case DS_MISS:				/* miss */
		if (dget60(fp, &dp->length) != 0)
			return(DE_DS_FORMAT);
		dp->ptr.i = 0;
		break;
	case DS_L:				/* logical data */
		if (dget60(fp, &dp->length) != 0)
			return(DE_DS_FORMAT);
		if ((dp->ptr.i = (int *)d3allc(mgno, sizeof(int)*d3gsiz(dp->length))) == 0)
			return(DE_MEM);
		for (i = 0; i < dp->length; i++) {
			if (dget50(fp, &dp->ptr.i[i]) != 0)
				return(DE_DS_FORMAT);
		}
		break;
	case DS_I:			/* integer data */
		if (dget60(fp, &dp->length) != 0)
			return(DE_DS_FORMAT);
		if ((dp->ptr.i = (int *)d3allc(mgno, sizeof(int)*d3gsiz(dp->length))) == 0)
			return(DE_MEM);
		for (i = 0; i < dp->length; i++) {
			if (dget60(fp, &dp->ptr.i[i]) != 0)
				return(DE_DS_FORMAT);
		}
		break;
	case DS_R:			/* real data */
		if (dget60(fp, &dp->length) != 0)
			return(DE_DS_FORMAT);
		if ((dp->ptr.i = (int *)d3allc(mgno, sizeof(float)*d3gsiz(dp->length))) == 0)
			return(DE_MEM);
		for (i = 0; i < dp->length; i++) {
			if (dget70(fp, &dp->ptr.r[i]) != 0)
				return(DE_DS_FORMAT);
		}
		break;
	case DS_C:			/* string data */
		if (dget60(fp, &dp->length) != 0)
			return(DE_DS_FORMAT);
		if ((dp->ptr.c = (char **)d3allc(mgno, sizeof(char *)*d3gsiz(dp->length))) == 0)
			return(DE_MEM);
		for (i = 0; i < dp->length; i++) {
			if (dget80(fp, &sp) != 0)
				return(DE_DS_FORMAT);
			if ((dp->ptr.c[i] = d3csav(mgno, sp)) == NULL)
				return(DE_MEM);
		}
		break;
	case DS_STR:			/* structure */
		if (dget80(fp, &sp) != 0)	/* get structure name */
			return(DE_DS_FORMAT);
		if ((sp = d3csav(mgno, sp)) == NULL) /* save structure name */
			return(DE_MEM);

		if (dget60(fp, &ss) != 0)	/* get structure type */
			return(DE_DS_FORMAT);

		if (dget60(fp, &len) != 0)	/* get length */
			return(DE_DS_FORMAT);

		if ((dp2 = (struct dsm *)d3allc(mgno, DSMSZ * (d3gsiz(len) + 2))) == 0)						/* alloc. DIR area */
			return(DE_MEM);		/* error */

		dp->length = len;		/* set length */
		dp->ptr.d = dp2;		/* set DIR pointer */

		dp2->seibun = 0;
		dp2->type = DS_DIR;

		dp2->ptr.s = sp;		/* set seibun name */

		dp2->length = len;		/* set length */

		dp2++;
		dp2->seibun = 0;
		dp2->type = ss;			/* set structure type */
		dp2->length = 0;
		*(int *) & dp2->ptr = len;

		dp2++;
		while (len--) {
			if ((status = dget10(fp, mgno, dp2)) != 0)
				/* read data set */
				return(status);	/* error */
			dp2++;
		}
		break;
	default:
		return(DE_DS_FORMAT);
	}
	if (dget30(fp) != 0)
		return(DE_DS_FORMAT);

	return(0);			/* normal end */
}

static
dget20(fp)
FILE	*fp;
{
	char	*cp;

	if ((cp = dget90(fp)) == NULL)
		return(1);

	if (strcmp(cp, "(") != 0)
		return(1);

	return(0);
}

static
dget30(fp)
FILE	*fp;
{
	char	*cp;

	if ((cp = dget90(fp)) == NULL)
		return(1);

	if (strcmp(cp, ")") != 0)
		return(1);

	return(0);
}

static
dget40(fp, ip)
FILE	*fp;
int	*ip;
{
	char	*cp;

	if ((cp = dget90(fp)) == NULL)
		return(1);

	if (strcmp(cp, "N") == 0)
		*ip = DS_N;
	else if (strcmp(cp, "L") == 0)
		*ip = DS_L;
	else if (strcmp(cp, "I") == 0)
		*ip = DS_I;
	else if (strcmp(cp, "R") == 0)
		*ip = DS_R;
	else if (strcmp(cp, "C") == 0)
		*ip = DS_C;
	else if (strcmp(cp, "S") == 0)
		*ip = DS_STR;
	else if (strcmp(cp, "*") == 0)
		*ip = DS_MISS;
	else
		return(1);

	return(0);
}

static
dget50(fp, ip)
FILE	*fp;
int	*ip;
{
	char	*cp;

	if ((cp = dget90(fp)) == NULL)
		return(1);

	if (strcmp(cp, "T") == 0)
		*ip = 1;
	else if (strcmp(cp, "F") == 0)
		*ip = 0;
	else if (strcmp(cp, "NA") == 0)
		*ip = PD_NA;
	else
		return(1);

	return(0);
}

static
dget60(fp, ip)
FILE	*fp;
int	*ip;
{
	char	*cp;

	if ((cp = dget90(fp)) == NULL)
		return(1);

	if (strcmp(cp, "NA") == 0)
		*ip = PD_NA;
	else
		*ip = atoi(cp);

	return(0);
}

static
dget70(fp, rp)
FILE	*fp;
float	*rp;
{
	char	*cp;

	if ((cp = dget90(fp)) == NULL)
		return(1);

	if (strcmp(cp, "NA") == 0)
		*(int *)rp = PD_NA;
	else
		*rp = atof(cp);

	return(0);
}

static
dget80(fp, cpp)
FILE	*fp;
char	**cpp;
{
	char	*cp;
	int	len;

	if ((cp = dget90(fp)) == NULL)
		return(1);

	if (*cp == '"') {
		cp++;
		len = strlen(cp);
		if (cp[len - 1] != '"')
			return(1);

		cp[len - 1] = '\0';
	}

	*cpp = cp;

	return(0);
}

static	char	*
dget90(fp)
FILE	*fp;
{
	int	c, sf;
	char	*bp;
	static	char	buf[256];

	for (;;) {
		if ((c = fgetc(fp)) == EOF)
			return(0);
		if (c != ' ' && c != '\t' && c != '\n')
			break;
	}

	sf = 0;
	bp = buf;
	for (;;) {
		if (c == '"')
			sf = !sf;
		*bp++ = c;
		if ((c = fgetc(fp)) == EOF)
			return(0);
		if ((c == ' ' || c == '\t' || c == '\n') && sf == 0)
			break;
	}
	*bp = '\0';

	return(buf);
}

