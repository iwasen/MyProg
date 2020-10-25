/**********************************************************
 *
 *	FUNCTION.DESCRIPTION	tbmnpl to parts error code convert
 *	FILE		d3tbcv.c
 *	ROUTINE		d3tbcv()
 *	REMARKS		1988/04/20	M.MOTOKI
 *
 **********************************************************/

#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/tbdefs.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3tbcv(err)
int	err;
{
	switch (err) {
	case CD_CANCEL:		/* Cancel key pressed */
	case CD_NORMAL:		/* Normal end */
		return(0);
	case CD_ERACCS:		/* Access error */
		return(DE_FILE_IO);
	case CD_DTFOMT:		/* Illigal format data */
		return(DE_DS_FORMAT);
	case CD_LOCKED:		/* Dataset locked out */
		return(DE_LOCK);
	case CD_ERINDX:		/* Cannot restore to LA_data_base */
		return(DE_NONE_GRP);
	default:
		return(DE_PRM);
	}
}
