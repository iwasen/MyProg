/*	FILE DESCRIPTION

		FILE NAME	: d3puta.c
		ROUTINE		: write asciiz data
		REVISION	:
		REMARKS		: 87.10.27	s.aizawa
*/

#include	<stdio.h>

static	char	sccsid[]="@(#)d3puta.c 1.2 88/06/12 14:55:17";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3puta()
		INPUT		: fp = file pointer
				  s  = write data area
		OUTPUT		: return value
					=0 normal
					=1 file access error
*/

d3puta(fp,s)
FILE	*fp;
char	*s;
{
	int	c;

	for (;;) {
		if ((c = fputc(*s++,fp)) == -1)	/* put 1 character */
			return(1);		/* error */
		if (c == '\0')			/* NULL ? */
			break;			/* yes */
	}

	return(0);				/* normal end */
}
