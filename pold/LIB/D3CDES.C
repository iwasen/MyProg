/***************************************************************	
	Clear "Des"
	FUNC.NAME	: d3cdes() 
	INPUT		: ds_id
	OUTPUT		: error code
	REMARKS		:	87.12.22	S.Takahashi
***************************************************************/
#include	"d3cdes.h"

static	char	sccsid[]="@(#)d3cdes.c 1.2 88/06/12 14:48:00";

d3cdes ( ds_id )
DSET	*ds_id ;
{
	struct	dsm	*dp ;
	char	**cp ;

/* move memory pointer to "Des" */

	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;

/* "Des" exist check */

	if ( strcmp ( dp[2].seibun , "Des" ) != NULL )  return ( DE_NONE_MEM );

/* clear "Des" by NULL */

	dp[2].type = NULL  ;
	dp[2].length = NULL ;  
	cp = dp[2].ptr.c ;
	cp = NULL ;
	return ( 0 );
}
