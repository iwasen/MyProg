/*	FILE DESCRIPTION

		FILE NAME	: d3hdck.c
		ROUTINE		: check LA data set header
		REVISION	:
		REMARKS		: 87.10.22	s.aizawa
*/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3hdck.c 1.5 88/06/12 14:52:52";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3hdck()
		ROUTINE		: check LA data set header
		INPUT		: fp = file pointer
		OUTPUT		: error code
					=0 normal
				     not=0 abnormal
*/


d3hdck(fp)
FILE	*fp;
{
	char	cn[256];	/* seibun mei */
	int	ds;		/* data type */
	int	l;		/* length */
	int	ss;		/* structure type */
	char	sn[256];	/* structure name */
	int	status;		/* return status */

	status = d3geth(fp,cn,&ds,&l,&ss,sn);	/* get LA data set header */
	if (status != 0)			/* error ? */
		return(status);			/* yes */

	/*if (ds != 6 || l != 3)*/		/* format error ? */
		/*return(DE_DS_FORMAT);*/	/* yes */

	return(0);			/* normal end */
}
