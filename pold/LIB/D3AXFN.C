/****************************************************************
	Read Fields Number Of "Axin" On memory 
	FUNC.NAME	: d3axfn() 
	INPUT		: ds_id 	; dataset id  
			  cmpno		; seibun no ( 1~ )
	OUTPUT		: return code	; number of field no
	REMARKS		:	87.12.24	S.Takahashi
****************************************************************/
#include	"d3axfn.h"

static	char	sccsid[]="@(#)d3axfn.c 1.2 88/06/12 14:47:00";

d3axfn ( ds_id , cmpno ) 
DSET	*ds_id ;
int	cmpno ;
{
	struct	dsm	*dp ;
	int	ptr ;
	int	i ;

/* move memory pointer to "Axi" field */

	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;

/* "Lab" exist check */

	if ( strcmp ( dp[3].seibun , "Lab" ) != 0 ) return ( DE_NONE_MEM );

/* return with field number */

	dp = dp[3].ptr.d ;
	if ( 1 <= cmpno && cmpno <= d3axin ( ds_id ) ){  /* cmpno hani check */
		ptr = -1 ;				 /* --- ok */
		for ( i=1 ; i<=cmpno ; i++ ) {
			for ( ;; ) {
				ptr ++ ;
				if ( strncmp(dp[ptr+2].seibun,"Axi",3) == 0 ) 
					break ;
			}
		}
		return ( dp[ptr+2].length ) ;
	} else { 					/* --- ng */
		return ( DE_NONE_DAT ) ;
	}
}
