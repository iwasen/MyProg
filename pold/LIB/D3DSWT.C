/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3dswt.c
 *		ROUTINE		: write data set
 *		REVISION	:
 *		REMARKS		: 87.10.29	s.aizawa
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3dswt.c 1.3 88/06/12 14:50:32";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3dswt()
 *		INPUT		: fp = file pointer
 *				  mp = memory pointer
 *		OUTPUT		: return value
 *					=0 normal
 *					=1 file access error
 */

d3dswt(fp,mp)
FILE	*fp;
struct	dsm	*mp;
{
	struct	dsm	*dp;
	char	**cp;
	int	len;
	int	status;

	if (d3puta(fp,mp->seibun) != 0)	/* write seibun mei */
		return(DE_FILE_IO);		/* error */

	if (d3putw(fp,mp->type) != 0)	/* write data type */
		return(DE_FILE_IO);		/* error */

	if (d3putw(fp,mp->length) != 0)	/* write length */
		return(DE_FILE_IO);		/* error */

	switch (mp->type) {		/* data type ? */
	case DS_N:			/* null */
		break;
	case DS_L:			/* logical data */
	case DS_I:			/* integer data */
	case DS_R:			/* real data */
		if (fwrite((char *)mp->ptr.i,sizeof(int),mp->length,fp)
				!= mp->length)	/* write integer data */
			return(DE_FILE_IO);	/* error */
		break;
	case DS_C:			/* string data */
		len = mp->length;		/* get length */
		cp = mp->ptr.c;		/* get string pointer table address */
		while (len--) {
			if (d3puta(fp,*cp) != 0)	/* write string data */
				return(DE_FILE_IO);	/* error */
			cp++;
		}
		break;
	case DS_STR:			/* structure */
		dp = mp->ptr.d;
		if (d3putw(fp,dp[1].type) != 0)	/* write structure type */
			return(DE_FILE_IO);		/* error */
		if (d3puta(fp,dp->ptr.s) != 0)	/* write structure name */
			return(DE_FILE_IO);		/* error */

		len = dp->length;		/* get length */
		dp += 2;
		while (len--) {
			if ((status = d3dswt(fp,dp)) != 0) /* write data set */
				return(status);	/* error */
			dp++;
		}
		break;
	case DS_MISS:
		break;
	default:
		return(DE_DS_FORMAT);
	}

	return(0);			/* normal end */
}
