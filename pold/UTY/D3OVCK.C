/*		FILE DESCRIPTION

		FILE NAME: d3ovck.c
		ROUTINE	 : ladataset over write check 
		REVISION :
		REMARKS	 :		87.11.02	S.Takahashi

*/

#include	"d3ovck.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC. DESCRIPTION

		FUNC.NAME: d3ovck()
		INPUT	 : ladir , ladata , dsfp , w 
		OUTPUT	 : return code 
		REMARKS	 :		87.11.02	S.Takahashi
*/
/*****************************************************************************/
/*		main processing						     */
/*****************************************************************************/
d3ovck ( ladir , ladata , dsfp , w )

	char	*ladir ; 
	char	*ladata ;
	FILE	*dsfp ;
	int	*w ;
{
	struct	stat 	buff ;
	char 	passname[100] ;
	int	err ;

/* ladataset exist check */

	strncpy ( passname , ladir , strlen(ladir) );
	strcat ( passname , ladata ) ;
	stat ( passname , buff ) ;

	if ( buff.st_mode != 0 ) {	

/* dose exist */

		if ( *w == 0 ) return ( E_DS_U ) ;	/* over write error */
		err = unlink ( passname ) ;
		if ( err != 0 ) return ( E_DS_A );
		dsfp = fopen ( passname , "w" ) ;
		if ( dsfp == 0 ) return ( E_DS_A );
		return ( 0 ) ;
	} else {

/* dose not exist */

		dsfp = fopen ( passname , "w" ) ;
		if ( dsfp == 0 ) return ( E_DS_A );
		return ( 0 );
	}
}
/*************************** eof *********************************************/


		
