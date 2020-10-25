/*	FILE DESCRIPTION

		FILE NAME	: d3getw.c
		ROUTINE		: read integer data
		REVISION	:
		REMARKS		: 87.10.27	s.aizawa
*/

#include	<stdio.h>

static	char	sccsid[]="@(#)d3getw.c 1.2 88/06/12 14:52:23";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3getw()
		INPUT		: fp = file pointer
				  w  = read data area
		OUTPUT		: return value
					=0 normal
					=1 EOF
*/

d3getw(fp,w)
FILE	*fp;
int	*w;
{
	if (feof(fp) != 0)			/* EOF ? */
		return(1);			/* yes */
	*w = getw(fp);				/* get 1 word */

	return(0);				/* normal end */
}
