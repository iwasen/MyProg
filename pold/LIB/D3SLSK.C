/*	FILE DESCRIPTION

		FILE NAME	: d3slsk.c
		ROUTINE		: setsumei bu or label bu skip
		REVISION	:
		REMARKS		: 87.10.26	s.aizawa
*/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3slsk.c 1.3 88/06/12 14:56:16";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3slsk()
		INPUT		: fp = file pointer
		OUTPUT		: error code
					=0 normal
				     not=0 abnormal
*/

d3slsk(fp)
FILE	*fp;
{
	char	cn[256];
	int	ds;
	int	l;
	int	ss;
	char	sn[256];
	int	status;

	if (d3geth(fp, cn, &ds, &l, &ss, sn) != 0)	/* get header */
		return(DE_FILE_IO);		/* error */

	switch (ds) {			/* data type ? */
	case DS_L:			/* logical data */
	case DS_I:			/* integer data */
	case DS_R:			/* real data */
		if (fseek(fp, (long)(l * sizeof(int)), 1) != 0)
			return(DE_FILE_IO);	/* error */
		break;
	case DS_C:			/* string data */
		while (l--) {
			if (d3geta(fp, cn) != 0)	/* get string data */
				return(DE_FILE_IO);	/* error */
		}
		break;
	case DS_STR:			/* structure */
		while (l--) {
			status = d3slsk(fp);	/* structure skip */
			if (status != 0)	/* error ? */
				return(status);	/* yes */
		}
		break;
	}

	return(0);			/* normal end */
}
