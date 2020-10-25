/***************************************************************	
	Clear "Axi"
	FUNC.NAME	: d3caxi() 
	INPUT		: ds_id
	OUTPUT		: error code
	REMARKS		:	88.01.16	S.Takahashi
***************************************************************/
#include	"d3caxi.h"

static	char	sccsid[]="@(#)d3caxi.c 1.2 88/06/12 14:47:29";

d3caxi ( ds_id )
DSET	*ds_id ;
{
	struct	dsm	*dp ;
	int	lab_suu ;
	int	axi_suu ;
	int	move_ptr ;
	int	i ;

/* move memory pointer to "Axi" */

	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;

/* "Axi" exist check */

	if ( strcmp ( dp[3].seibun , "Lab" ) != 0 )  return ( DE_NONE_MEM );

/* clear "Axi" */

	lab_suu = dp[3].length ;
	axi_suu = d3axin ( ds_id ) ;
	if ( axi_suu < 0 ) return ( axi_suu ) ;
	if ( dp[3].length == axi_suu ) {	/* "Lab" = all "Axi" ?*/
		dp[3].type = 0 ;		/* --- yes */
		dp[3].length = 0 ;
		*(int*)&dp[3].ptr = 0 ;
	} else { 				/* ---  no */
		dp[3].length -= axi_suu ; 	/* "Lab" seibun suu -= axi_suu*/
		dp = dp[3].ptr.d ;
		dp[0].length -= axi_suu ;
		dp[1].length -= axi_suu ;
		move_ptr = 2 ;			/* clear earch axi "*/
		for ( i=2 ; i<=lab_suu+1 ; i++ ) {
			if ( strncmp (dp[i].seibun,"Axi",3) != 0 ) {
				dp[move_ptr].seibun = dp[i].seibun ;
				dp[move_ptr].type = dp[i].type ;
				dp[move_ptr].length = dp[i].length ;
				dp[move_ptr].ptr = dp[i].ptr ;
				move_ptr ++ ;
			}
		}
	}
	return ( 0 ) ;
}
