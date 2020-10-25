/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3free.c
 *		ROUTINE		: free dymanic memory
 *		REVISION	:
 *		REMARKS		: 87.10.28	s.aizawa
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	mem_tbl	{
	char	*sb_ptr;
	char	*eb_ptr;
	int	eb_size;
	int	eb_offset;
} memtbl[];

static	char	sccsid[]="@(#)d3free.c 1.3 88/06/12 14:51:16";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3free()
 *		INPUT		: mgno = memory block No.
 *		OUTPUT		: return value
 *					=0 normal
 *					=1 error
 */

d3free(mgno)
unsigned int	mgno;
{
	char	*p,*p2;
	struct	mem_tbl	*mp;

	if (mgno >= MEMGRP)		/* memory group No. over ? */
		return(1);		/* yes */

	mp = &memtbl[mgno];		/* set memory management table ptr */

	p = mp->sb_ptr;			/* set start block pointer */
	while (p) {
		p2 = *(char **)p;	/* get next chain pointer */
		free(p);		/* memory free */
		p = p2;
	}

	mp->sb_ptr = 0;			/* clear start block pointer */
	mp->eb_ptr = 0;			/* clear end block pointer */
	mp->eb_size = 0;		/* clear block size */
	mp->eb_offset = 0;		/* clear offset */

	return(0);			/* normal end */
}
