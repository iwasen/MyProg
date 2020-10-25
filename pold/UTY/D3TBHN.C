/**********************************************************
 *
 *	FUNCTION.DESCRIPTION	exec table handler ( tbmnpl )
 *	FILE		d3tbhn.c
 *	ROUTINE		d3tbhn()
 *	COMMENT		we are in curses mode !
 *			we consider "pad" and "w1" already exist
 *	REMARKS		1988/04/20	M.MOTOKI
 *
 **********************************************************/

#include <jcurses.h>
#include "la_ws/include/d2lerr.h"
#include "functn.h"
#include "d3data.h"
#include "la_ws/include/d3libc.h"

#define	PD_ERROR(err)	d3errw(err)

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/***************************************
 *	d3tbhn	TABLE MANUPLATION
 ***************************************/
d3tbhn(ladir, table, mode, label, minlen, maxlen, title)
char	*ladir;
char	*table;
int	mode;
char	*label;
int	minlen;
int	maxlen;
char	*title;
{
	int	err;
	char	s_mode[20], s_minlen[20], s_maxlen[20];

	sprintf(s_mode, "%d", mode);
	sprintf(s_minlen, "%d", minlen);
	sprintf(s_maxlen, "%d", maxlen);

	endwin();		/* shell mode <== curses mode */

	err = (d3exec("d0_tbhn", ladir, table, s_mode,
			label, s_minlen, s_maxlen, title, 0) >> 8) & 0xff;

	if (err != 0) PD_ERROR(err);

	return(err);
}
