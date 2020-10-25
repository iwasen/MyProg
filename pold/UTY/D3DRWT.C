/*		FILE DESCRIPTION

			file name : d3drwt.c
			routine	  : dir_name write 
			revision  :
			remarks	  :		87.10.26 	N.MATSUO

*/

#include	"d3drwt.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC. DESCRIPTION

			func.name: d3drwt()
			input	 : none
			output	 : error code
			remarks	 : 		87.10.29	S.Takahashi
				 : update	88.05.23	S.Aizawa

*/

d3drwt()
{
static	char  t_name[15] = "/tmp/la_crd";
	FILE	*fp ;
	char	fname [100] ;

/* open current directory file */

	strcpy ( fname , t_name ) ;
	strcat ( fname , TTY_NM ) ;

	fp = fopen ( fname , "w" ) ;		/* open current file */
	if ( fp == NULL ) {			/* none exist ? */
		return ( E_CF_A );
	}

	/* write current ladb & group */
	if (fprintf(fp, "%s\n%s\n", cur_ladb, cur_grp) < 0) {
		fclose(fp);
		return(E_CF_A);
	}

	fclose ( fp ) ;
	return(NOERR);

}
/******************************** eof ***************************************/
