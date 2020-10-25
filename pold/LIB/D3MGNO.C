/*	FILE DESCRIPTION

		FILE NAME	: d3mgno.c
		ROUTINE		: search free memory group No.
		REVISION	:
		REMARKS		: 87.10.29	s.aizawa
*/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	mem_tbl	{
	char	*sb_ptr;
	char	*eb_ptr;
	int	eb_size;
	int	eb_offset;
} memtbl[];

static	char	sccsid[]="@(#)d3mgno.c 1.3 88/06/12 14:54:06";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3mgno()
		INPUT		: nothing
		OUTPUT		: return value
					not=-1 memory group No.
					   =-1 error
*/

d3mgno()
{
	int	i;

	for (i = 0; i < MEMGRP; i++) {
		if (memtbl[i].sb_ptr == 0)	/* free memory group ? */
			return(i);		/* yes */
	}

	return(-1);
}
