/************************************************************
 *	FILE DESCRIPTION
 *		XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
 *	FILE	:	XXXXXXXX
 *		:	XXXXXXXX
 *	REMARKS	:	1988-04-26	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include <jcurses.h>
#include "functn.h"
#include "d3item.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3slct(item_n, cell_p, c_pos)
int	item_n, c_pos;
D_CELL 	*cell_p;
{
	int	i;

	for (i=c_pos; i<item_n; i++) {
		if (((cell_p+i)->flag) & 0x01) return(i);
	}

	return(-1);			/* not found */
}
