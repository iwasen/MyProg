/****************************************************************
	Read Fields Number Of "Des" On memory 
	FUNC.NAME	: d3defn() 
	INPUT		: ds_id 	; dataset id  
			  cmpno		; seibun no 
	OUTPUT		: return code	; number of field no
	REMARKS		:	87.12.24	S.Takahashi
****************************************************************/
#include	"d3defn.h"

static	char	sccsid[]="@(#)d3defn.c 1.2 88/06/12 14:49:10";

d3defn ( ds_id , cmpno ) 
DSET	*ds_id ;
int	cmpno ;
{
	struct	dsm	*dp ;

/* move memory pointer to "Des" field */

	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;

/* "Des" exist check */

	if ( strcmp ( dp[2].seibun , "Des" ) != 0 ) return ( DE_NONE_MEM );

/* return with field number */
	
	if ( 1 <= cmpno && cmpno <= dp[2].length ){
		dp = dp[2].ptr.d ;	
		return ( dp[cmpno+1].length ) ;		 
	} else { 
		return ( DE_NONE_DAT ) ;
	}
}
