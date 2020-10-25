
/************************************************************
 *	FILE DESCRIPTION
 *		データセットの成分の要素の削除
 *	FILE	:	d3delm.c
 *		:	d3delm
 *	REMARKS	:	1988-04-30	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

static	char	sccsid[]="@(#)d3delm.c 1.2 88/06/12 15:01:43";

d3delm(mp, comp_name, from, nitems)
DMEM	*mp;
char	*comp_name;
int	from, nitems;
{
	PD_DENT	box;
	int	err, f;

	err = d3gent(mp, comp_name, &box);
	if (err != 0)
		return(err);
	err = d3elck(box.ent_mode, box.ent_length, from, nitems);
	if (err != 0)
		return(err);
	
	if (box.ent_length == nitems) {	/* 成分を NULL にする */
		PD_MODE(box.ent_entp)   = DS_N;
		PD_LENGTH(box.ent_entp) = 0;
		PD_VALUE(box.ent_entp)  = NULL;	/* 将来ここを
						コメントにしよう */
	} else {			/* 前詰処理 */
		for (f = from + nitems - 1; f < box.ent_length; f++) {
			PD_VALUE(box.ent_entp)[f - nitems] =
				PD_VALUE(box.ent_entp)[f];
		}		

		PD_LENGTH(box.ent_entp) -= nitems;	/* 要素数を減らす */
	}

	return(0);
}
