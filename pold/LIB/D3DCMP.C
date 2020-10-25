/************************************************************
 *	FILE DESCRIPTION
 *		データセットの成分の削除処理
 *	FILE	:	d3dcmp.c
 *		:	d3dcmp
 *	REMARKS	:	1988-04-30	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

static	char	sccsid[]="@(#)d3dcmp.c 1.2 88/06/12 15:01:01";

d3dcmp(mp, comp_name)
DMEM	*mp;
char	*comp_name;
{
	PD_DENT	box;
	int	err, ln, nitems=1, j;
	DMEM	*p1, *p2;

	err = d3gent(mp, comp_name, &box);
	if (err != 0) return(err);

	ln = PD_LENGTH((DMEM *)PD_VALUE(box.ent_parent));
	if (ln == nitems) {		/* 親成分を NULL成分にする */
		PD_MODE(box.ent_parent)   = DS_N;	/* 注意 ! */
		PD_LENGTH(box.ent_parent) = 0;
		PD_VALUE(box.ent_parent)  = NULL;	/* 将来ここを
					コメントにしよう */
	} else {			/* 前詰処理 */
		p1 = box.ent_entp;
		p2 = box.ent_entp + nitems;
		for (j = 0; j < ln - box.ent_pos; j++)
			*p1++ = *p2++;

		PD_LENGTH((DMEM *)PD_VALUE(box.ent_parent)) -= nitems;
		PD_LENGTH(box.ent_parent) -= nitems;
	}

	return(0);
}
