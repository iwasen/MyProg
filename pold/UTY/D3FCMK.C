/*	FILE DESCRIPTION

		FILE NAME	: d3fcmk.c
		ROUTINE		: funcsion key settei
		REVITION	:
		REMARKS		:	87.10.13	N.MATSUO
*/

#include	"d3fcmk.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

 /*

			func.name: d3fcmk()
			input	 : func		display string
			output	 : none
			remarks	 : 		87.10.29	N.MATSUO

*/


d3fcmk(func)
char	*func[];
{
	static	char	dummy[] = "Å@Å@Å@";
	/*static	char	dummy[] = "";*/
	int	i;

	for(i = 1; i <= 8; i++){
		if (*func)
			slk_set(i,*func,1);		/* function key set   */
		else
			slk_set(i,dummy,1);		/* blank key */
		func++;
	}
	slk_noutrefresh();
}

