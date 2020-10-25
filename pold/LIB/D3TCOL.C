/*	FILE DESCROPTION
 *
 *		FILE NAME	: d3tcol.c
 *		ROUTINE		: —ñ”Žæ‚èo‚µ
 *		REVISION	:
 *		REMARKS		: 88.01.09	s.aizawa
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp();

static	char	sccsid[]="@(#)d3tcol.c 1.3 88/06/12 14:56:47";

/*	FUNCTION DESCRIPTION
 *		
 *		FUNC.NAME	: d3tcol()
 *		INPUT		: dsp = data set pointer
 *		OUTPUT		: return value = error code
 *		REMARKS		:
 */

d3tcol(dsp)
DSET	*dsp;
{
	struct	dsm	*datp, *dimp;

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* search "Dat" */
		return(DE_DS_FORMAT);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)	/* seatch "Dim" */
		return(0);

	return(dimp->ptr.i[1]);
}

