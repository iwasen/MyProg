/*	FILE DESCRIPTION

		FILE NAME	: d3mktp.c
		ROUTONE		: make templet
		REMARKS		:
*/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp(), *d3gtra();

static	char	sccsid[]="@(#)d3mktp.c 1.4 88/06/12 14:54:18";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3mktp()
		ROUTINE		: make templet
		INPUT		: dsp      = data set pointer
				: templete = templet
		OUTPUT		: return value = error code
*/

d3mktp(dsp, templete)
DSET	*dsp;
T_TEMPLETE	*templete;
{
	struct	dsm	*labp, *axip, *trap;
	TEMPLETE	*tp;
	int	offset, n;

	tp = templete->tp_templete;
	n = templete->tp_cnum;

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == 0)
		return(DE_DS_FORMAT);

	if ((axip = d3gcmp(labp, "Axi2")) == 0)
		return(DE_DS_FORMAT);

	offset = 0;

	while (n--) {
		if ((trap = d3gtra(axip, tp->tp_col)) == 0) {
			tp->tp_mode = DS_I;
			tp->tp_len = sizeof(int);
		} else {
			tp->tp_mode = trap->type;
			switch (trap->type) {
			case DS_L:
			case DS_I:
			case DS_R:
				tp->tp_len = sizeof(int);
				break;
			case DS_C:
				tp->tp_len = mktp10(trap);
				break;
			default:
				return(DE_DS_FORMAT);
			}
		}
		tp->tp_offset = offset;
		offset += (tp->tp_len + 1) & ~1;
		tp++;
	}
	templete->tp_rlen = offset;
	return(0);
}

static
mktp10(trap)
struct	dsm	*trap;
{
	int	max_len, n, len;
	char	**cp;

	max_len = 0;
	n = trap->length;
	cp = trap->ptr.c;
	while (n--) {
		len = strlen(*cp++);
		if (len > max_len)
			max_len = len;
	}
	return(max_len + 1);
}


