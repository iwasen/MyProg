/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3tupk.c
 *		ROUTINE		: 
 *		REVISION	:
 *		REMARKS		: 88.02.04
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp();
extern	char	*d3allc();

static	char	sccsid[]="@(#)d3tupk.c 1.4 88/06/12 15:00:22";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3tupk()
 *		INPUT		: dsp = data set pointer
 *				: col = coloumn number
 *				: label = keyword name
 *		OUTPUT		: return value = error code
 */

d3tupk(dsp,col,label)
DSET	*dsp;
int	col;
char	*label;
{
	struct	dsm	*labp, *axip, *labsp;
	char	*label_p;

	if ((labp=d3gcmp(dsp->ds_entry,"Lab"))==NULL)
		return(DE_DS_FORMAT);

	if ((axip=d3gcmp(labp,"Axi2"))==NULL)
		return(DE_DS_FORMAT);

	if ((labsp=d3gcmp(axip,"Labs"))==NULL)
		return(DE_DS_FORMAT);

	if (col <= 0 || labsp->length < col)
		return(DE_PRM);

	if ((label_p=d3allc(dsp->ds_memno,strlen(label)+1))==NULL)
		return(DE_NONE_MEM);

	strcpy(label_p,label);
	labsp->ptr.c[col-1]=label_p;

	return(0);
}
