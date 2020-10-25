/*	FILE DESCRIPTION

		FILE NAME	: d3geth.c
		ROUTINE		: read data set header
		REVISION	:
		REMARKS		: 87.10.27	s.aizawa
*/

#include	<stdio.h>

static	char	sccsid[]="@(#)d3geth.c 1.2 88/06/12 14:51:54";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3geth()
		INPUT		: fp = file pointer
		OUTPUT		: cn = seibun mei
				  ds = data type
				  l  = length
				  ss = structure type
				  sn = structure name
				  return value
					=0 normal
					=1 EOF
*/

d3geth(fp,cn,ds,l,ss,sn)
FILE	*fp;
char	*cn;
int	*ds;
int	*l;
int	*ss;
char	*sn;
{
	if (d3geta(fp,cn) != 0)		/* read seibun mei */
		return(1);		/* error */

	if (d3getw(fp,ds) != 0)		/* read data type */
		return(1);		/* error */

	if (d3getw(fp,l) != 0)		/* read length */
		return(1);		/* error */

	if (*ds == 6) {			/* structure ? */
		if (d3getw(fp,ss) != 0)		/* read structure type */
			return(1);		/* error */

		if (d3geta(fp,sn) != 0)		/* read structure name */
			return(1);		/* error */
	}

	return(0);			/* normal end */
}
