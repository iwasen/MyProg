/*	FILE DESCRIPTION

		FILE NAME	: d3geta.c
		ROUTINE		: read asciiz data
		REVISION	:
		REMARKS		: 87.10.27	s.aizawa
*/

#include	<stdio.h>

static	char	sccsid[]="@(#)d3geta.c 1.2 88/06/12 14:51:37";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3geta()
		INPUT		: fp = file pointer
				  s  = read data area
		OUTPUT		: return value
					=0 normal
					=1 EOF
*/

d3geta(fp,s)
FILE	*fp;
char	*s;
{
	int	c;

	for (;;) {
		if ((c = fgetc(fp)) == -1)	/* get 1 character */
			return(1);		/* EOF */
		*s++ = c;			/* set read data */
		if (c == '\0')			/* NULL ? */
			break;			/* yes */
	}

	return(0);				/* normal end */
}
