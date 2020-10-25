/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3allc.c
 *		ROUTINE		: allocate dymanic memory
 *		REVISION	:
 *		REMARKS		: 87.10.28	s.aizawa
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

#define	BLKSIZE	0x1000

extern	char	*malloc();

struct	mem_tbl	{
	char	*sb_ptr;
	char	*eb_ptr;
	int	eb_size;
	int	eb_offset;
} memtbl[MEMGRP];

static	char	sccsid[]="@(#)d3allc.c 1.3 88/06/12 14:46:16";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3allc()
 *		INPUT		: mgno = memory block No.
 *				  size = allocation size
 *		OUTPUT		: return value = memory address
 *					( =0 alloction error)
 */

char *d3allc(mgno,size)
unsigned int	mgno;
unsigned int	size;
{
	struct	mem_tbl	*mp;
	unsigned int	allcsize;
	char	*p;

/* printf("allc size = %d\n",size); */	/* for debug */
	size = (size + 1) & ~1;

	if (mgno >= MEMGRP)		/* memory group No. over ? */
		return(0);		/* yes */

	mp = &memtbl[mgno];		/* set memory management table ptr */

	if (mp->sb_ptr == 0) {
		allcsize = (size + sizeof(char *) + BLKSIZE-1) & ~(BLKSIZE-1);
		if ((p = malloc(allcsize)) == 0) /* memory allocation */
			return(0);		/* error */
		mp->sb_ptr = mp->eb_ptr = p;	/* set start & end block ptr */
		mp->eb_size = allcsize;		/* set block size */
		mp->eb_offset = sizeof(char *) + size;	/* set offset */
		*((char **)p)++ = 0;		/* clear next chain pointer */
	} else {
		if (size <= mp->eb_size - mp->eb_offset) {/* free area exist */
			p = mp->eb_ptr + mp->eb_offset;	/* set free area ptr */
			mp->eb_offset += size;	/* set next offset */
		} else {
			allcsize = (size + sizeof(char *) + BLKSIZE-1) & ~(BLKSIZE-1);
			if ((p = malloc(allcsize)) == 0) /* memory alloction */
				return(0);	/* error */
			*(char **)mp->eb_ptr = p; /* set next chain pointer */
			mp->eb_ptr = p;		/* set end block pointer */
			mp->eb_size = allcsize;	/* set block size */
			mp->eb_offset = sizeof(char *) + size;	/* set offset */
			*((char **)p)++ = 0;	/* clear next chain pointer */
		}
	}
	return(p);			/* normal end */
}
