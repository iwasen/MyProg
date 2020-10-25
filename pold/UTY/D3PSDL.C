/*
		
		FUNC.NAME	: d3psdl(dirfile)
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.11.10	S.Takahashi
*/

#include	"d3psdl.h"
static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*****************************************************************************/
/*	cut pass name from directioy file (/xxxx/ladb/aaa.grp -> aaa.grp)    */
/*****************************************************************************/
d3psdl ( dirfile ) 
	char	*dirfile ;
{
	FILE	*tmpfp ; 
	FILE	*dirfp ;
 	char	c ;
	char	buf[100] ;
	char	*cerr ;
	int	i ;
	int	ptr ;
	char	fname[20] ;
	char	*tmp_name ;
	int	len;

/* open temporary file & directory file */

	tmp_name = tmpnam ( NULL ) ;
	tmpfp = fopen ( tmp_name , "w" ) ;
	if ( tmpfp == NULL ) return ( E_TF_A ) ;

	dirfp = fopen ( dirfile , "r" ) ;
	if ( dirfp == NULL ) return ( E_TF_A ) ;

/* read dirfile & write temporary file */

	for ( ;; ) {
		cerr = fgets ( buf , 99 , dirfp ) ;
		if ( cerr == NULL ) { 
		 	break ;
		} else { 
			ptr = -1;
			len = strlen(buf);
			for ( i=0 ; i<=len-1 ; i++ ) {
				if ( buf[i] == '/' ) {
					ptr = i ;
				}
			}
			strncpy ( fname , &buf[ptr+1] , len-ptr+1 );
			fputs ( fname , tmpfp ) ;
		}	
	} 

/* reopen directory file */

	fclose ( dirfp ) ;
	dirfp = fopen ( dirfile , "w" ) ;
	fclose ( tmpfp ) ;
	tmpfp = fopen ( tmp_name , "r" ) ;

/* copy temporary file to dirfile */  

	rewind ( tmpfp ) ;
	for ( ;; ) {
		c = getc ( tmpfp ) ;	
		if ( c == -1 ) { 
			 break ;
		} else { 
			putc ( c , dirfp ) ;
		}
	}
	
/* close all file */

	fclose ( dirfp ) ;
	fclose ( tmpfp ) ;
	unlink ( tmp_name ) ; 
	return ( NULL ) ;
}
