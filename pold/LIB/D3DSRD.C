/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3dsrd.c
 *		ROUTINE		: read data set
 *		REVISION	:
 *		REMARKS		: 87.10.29	s.aizawa
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

#define	DSMSZ	sizeof(struct dsm)

extern	char	*d3allc();

static	char	sccsid[]="@(#)d3dsrd.c 1.3 88/06/12 14:50:18";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3dsrd()
 *		INPUT		: fp = file pointer
 *				  mgno = memory group No.
 *		OUTPUT		: mp = memory pointer
 *				  return value
 *					=0 normal
 *				    not	=0 abnormal
 */

d3dsrd(fp,mgno,mp)
FILE	*fp;
int	mgno;
char	**mp;
{
	struct	dsm	*dp;
	int	status;

	if ((dp = (struct dsm *)d3allc(mgno,DSMSZ)) == 0) /* memory allocate */
		return(DE_MEM);			/* error */

	if ((status = dsrd10(fp,mgno,dp)) != 0) { /* read data set */
		/* d3free(mgno); */		/* error , memory free */
		return(status);			/* abnormal end */
	}

	*mp = (char *)dp;			/* set data pointer */
	return(0);				/* normal end */
}


/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: dsrd10()
 *		INPUT		: fp = file pointer
 *				  mgno = memory group No.
 *				  dp = data pointer
 *		OUTPUT		: return value
 *					=0 normal
 *					=1 file access error
 *					=2 memory allocation error
 */

static
dsrd10(fp,mgno,dp)
FILE	*fp;
int	mgno;
struct	dsm	*dp;
{
	char	cn[256],sn[256];
	int	ds,len,ss;
	struct	dsm	*dp2;
	char	**cp;
	int	status;

	if (d3geth(fp,cn,&ds,&len,&ss,sn) != 0)	/* read header */
		return(DE_FILE_IO);

	if ((dp->seibun = d3allc(mgno,strlen(cn)+1)) == 0)
						/* alloc. seibunmei area */
		return(DE_MEM);		/* error */
	strcpy(dp->seibun,cn);		/* copy seibunmei */

	dp->type = ds;			/* set data type */

	dp->length = len;			/* set length */

	switch (ds) {			/* data type ? */
	case DS_N:			/* null */
		dp->ptr.i = 0;
		break;
	case DS_L:			/* logical data */
	case DS_I:			/* integer data */
	case DS_R:			/* real data */
		if ((dp->ptr.i = (int *)d3allc(mgno,sizeof(int)*d3gsiz(len))) == 0)
					/* alloc. integer data area */
			return(DE_MEM);	/* error */
		if (fread((char *)dp->ptr.i,sizeof(int),len,fp) != len)
					/* read integer data */
			return(DE_FILE_IO);	/* error */
		break;
	case DS_C:			/* string data */
		if ((cp = (char **)d3allc(mgno,sizeof(char *)*d3gsiz(len))) == 0)
					/* alloc. string pointer table area */
			return(DE_MEM);	/* error */

		dp->ptr.c = cp;		/* set string pointer table address */

		while (len--) {
			if (d3geta(fp,cn) != 0)	/* read string data */
				return(DE_FILE_IO);	/* error */
			if ((*cp = d3allc(mgno,strlen(cn)+1)) == 0)
						/* alloc. string data area */
				return(DE_MEM);	/* error */
			strcpy(*cp,cn);		/* copy string data */
			cp++;
		}
		break;
	case DS_STR:			/* structure */
		if ((dp2 = (struct dsm *)d3allc(mgno,DSMSZ*(d3gsiz(len)+2))) == 0)
						/* alloc. DIR area */
			return(DE_MEM);	/* error */

		dp->ptr.d = dp2;	/* set DIR pointer */

		dp2->seibun = 0;
		dp2->type = DS_DIR;
		dp2->length = len;
		if ((dp2->ptr.s = d3allc(mgno,strlen(sn)+1)) == 0)
					/* alloc. structure name area */
			return(DE_MEM);	/* error */
		strcpy(dp2->ptr.s,sn);	/* copy structure name */

		dp2++;
		dp2->seibun = 0;
		dp2->type = ss;		/* set structure type */
		dp2->length = 0;
		*(int *)&dp2->ptr = len;

		dp2++;
		while (len--) {
			if ((status = dsrd10(fp,mgno,dp2)) != 0)
						/* read data set */
				return(status);	/* error */
			dp2++;
		}
		break;
	case DS_MISS:
		break;
	default:
		return(DE_DS_FORMAT);
	}

	return(0);			/* normal end */
}

