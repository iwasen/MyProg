/*		FILE DESCRIPTION

			FILE NAME: d3msed.c
			ROUTINE	 : error message hyoji
			REVITION : 
			REMARKS	 :		87.10.22		N.MATSUO

*/
#include 	"d3msed.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC. DESCRIPTION

			FUNC.NAME: d3msed()
			INPUT	 : win		pad for display
				   msno		message no(ERROR CODE)
			OUTPUT	 : none
			REMARKS	 :		87.10.20	N.MATSUO

*/

d3msed(win,msno)
WINDOW 	*win;
int	msno;
{
	int	l,c;
	char	msg_buf[128];

	getyx(win,l,c); wmove(win,l,0); wclrtoeol(win) ; 
	/*waddstr(win,"	                                       ");*/
	/*wmove(win,l,0);*/		/* set cursor position */
	d3erms(msno, msg_buf);		/* get error message */
	waddstr(win,msg_buf);		/* display error message */
}
