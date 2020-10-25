/*	FILE DESCRIPTION

		FILE NAME	: d3putw.c
		ROUTINE		: write integer data
		REVISION	:
		REMARKS		: 87.10.27	s.aizawa
*/

#include	<stdio.h>

static	char	sccsid[]="@(#)d3putw.c 1.2 88/06/12 14:55:29";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3putw()
		INPUT		: fp = file pointer
				  w  = write data
		OUTPUT		: return value
					=0 normal
					=1 file access error
*/

d3putw(fp,w)
FILE	*fp;
int	w;
{
	putw(w,fp);				/* put 1 word */
	if (ferror(fp) != 0)			/* error */
		return(1);			/* yes */

	return(0);				/* normal end */
}
