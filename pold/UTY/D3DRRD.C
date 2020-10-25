/*		FILE DESCRIPTION

	file name : d3drrd.c
	routine	  : dir_name read
	revision  :
	remarks	  :		87.10.26 	N.MATSUO

*/

#include	"d3drrd.h"
static	char	sccsid[]="%Z%%M% %I% %E% %U%";


/*		FUNC. DESCRIPTION

	func.name: d3drrd()
	input	 : none
	output	 : error code
	remarks	 : 		87.10.29	N.MATSUO
		 : update	88.05.23	S.AIZAWA

*/


d3drrd()
{
	char	str[30];
	static	char  t_name[15] = "/tmp/la_crd";
	FILE	*fp;

	strcpy(str,t_name);
	strcat(str,TTY_NM);

	if ((fp = fopen(str,"r")) == NULL) {	/* open current file */
		return(E_CF_A);
	}

	/* read current ladb & group */
	if (fscanf(fp, "%s%s", cur_ladb, cur_grp) == 0) {
		fclose(fp);
		return(E_CF_A);
	}

	fclose(fp);

	return(NOERR);
}
