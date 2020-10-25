/***************************************************************
	Read Seibun Suu Of "Axi"
	FUNC.NAME	: d3axin() 
	INPUT		: ds_id
	OUTPUT		: Seibun Suu Of "Des"
	REMARKS		:	87.12.21	S.Takahashi
****************************************************************/
#include	"d3axin.h"

static	char	sccsid[]="@(#)d3axin.c 1.2 88/06/12 14:47:18";

d3axin( ds_id )
DSET	*ds_id ;
{
	struct	dsm	*dp ;
	int	i ;
	int	lab_num ;
	int	axi_num ;

/* move memory pointer to "Axi" */

	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;

/* "Lab" exist check */

	if ( strcmp ( dp[3].seibun , "Lab" ) != 0 )  return ( DE_NONE_MEM ) ;

/* return with seibun suu */

	lab_num = dp[3].length ;
	dp = dp[3].ptr.d ;
	axi_num = 0 ;
	for ( i=2 ; i<=lab_num+1 ; i++ ) {
		if ( strncmp ( dp[i].seibun , "Axi" , 3 ) == 0 ) axi_num++ ;
	}
	return ( axi_num ) ;
}
