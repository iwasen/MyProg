/*		FILE DESCRIPTION

		FILE NAME : d3dred.c
		ROUTINE	  : directory name output
		DEVISION  :
		REMARKS	  :		87.10.20	N.MATSUO

*/

#include 	"d3dred.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC. DESCRIPTION

		FUNC.NAME: d3dred()
		INPUT	 : none
		OUTPUT	 : error code
		REMARKS	 : 		87.10.20		N.MATSUO
		REMARK   : update	87.11.16		S.Takahashi
			 : update	88.05.23		S.Aizawa

*/

d3dred()
{
	int	len;
	char	*p, dir[80];

/* diaplay current directory ex < /users/xxxx/ladb > */

	if ((len = strlen(cur_ladb)) > 36) {
		sprintf(dir, "***%s", strchr(&cur_ladb[len - 33], '/'));
		p = dir;
	} else
		p = cur_ladb;
		
	wmove(pad,0,40);
	wclrtoeol(pad);
	waddstr(pad,"< ");
	waddstr(pad, p);
	waddstr(pad," >");
	/*pnoutrefresh(pad,0,0,0,0,l_num,80);*/

	return(NOERR);
}
