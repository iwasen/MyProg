/***************************************************************
	Read Seibun Suu Of "Des"
	FUNC.NAME	: d3desn() 
	INPUT		: ds_id
	OUTPUT		: Seibun Suu Of "Des"
	REMARKS		:	87.12.21	S.Takahashi
****************************************************************/
#include	"d3desn.h"

static	char	sccsid[]="@(#)d3desn.c 1.2 88/06/12 14:49:24";

d3desn( ds_id )
DSET	*ds_id ;
{
	struct	dsm	*dp ;

/* move memory pointer to "Des" */

	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;

/* "Des" exist check */

	if ( strcmp ( dp[2].seibun , "Des" ) != 0 )  return ( DE_NONE_MEM ) ;

/* return with seibun suu */

	return ( dp[2].length ) ;
}
