
/************************************************************
 *	FILE DESCRIPTION
 *			find system-index in the GRP.IND	
 *	FILE	:	d3gidx.c
 *		:	d3gidx
 *	REMARKS	:	1988-04-26	MMOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"

#define	ERROR(err)	{if (tf_p != NULL) free(tf_p); return(err);}
extern	char	*malloc();

static	char	sccsid[]="@(#)d3gidx.c 1.2 88/06/12 14:52:33";

d3gidx(dsp,sys_idx)
DSET	*dsp;
char	*sys_idx;
{
	PD_CNDREC	srch_0;
	PD_TFREC	*tf_p;
	int	err, row;

	tf_p = NULL;

	if ((row=d3trow(dsp)) < 0) ERROR(row)
	else if (row==0) ERROR(DE_NONE_GRP)

	tf_p=(PD_TFREC *)malloc(sizeof(int) + sizeof(char) * row);
	if (tf_p == NULL) ERROR(DE_MEM)

	srch_0.cnd_relop = PD_CND_AND;
	srch_0.cnd_col  = 3;
	srch_0.cnd_op   = PD_CND_EQ;
	srch_0.cnd_val.c_ptr = sys_idx;

	err=d3srch(dsp, &srch_0, NULL, tf_p);
	if (err != 0) ERROR(err)

	if (tf_p->tf_n == 0) ERROR(DE_NONE_GRP)

	free(tf_p);
	return(0);
}
