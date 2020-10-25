/***************************************************************
	Read Key Word All
	FUNC.NAME	: d3tkey() 
	INPUT		: dsp		; DSET pointer
	OUTPUT		: n		; number of key word
		 	: keyword	; key word 
			: return code	; error code
	REMARKS		:	88.01.20	S.Takahashi
***************************************************************/
#include	"d3tkey.h"
extern	struct	dsm	*d3gcmp() ;	

static	char	sccsid[]="@(#)d3tkey.c 1.3 88/06/12 14:58:13";

d3tkey ( dsp , n , keyword )
	DSET	*dsp ;
	int	*n ;
	char	**keyword[] ;
{
	struct	dsm	*dp ;

/* move pointer to "Axi2" */

	dp = d3gcmp ( dsp->ds_entry , "Lab" ) ;
	if ( dp == NULL ) return ( DE_DS_FORMAT ) ;
	dp = d3gcmp ( dp , "Axi2" ) ;
	if ( dp == NULL ) return ( DE_DS_FORMAT ) ;
	dp = d3gcmp ( dp , "Labs" ) ;
	if ( dp == NULL ) return ( DE_DS_FORMAT ) ;

/* copy data pointer to parameter */

	*keyword = dp->ptr.c ;
	*n = dp->length ;
	return ( 0 ) ;
}	
