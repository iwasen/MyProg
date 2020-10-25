/******************************************************************************	
		固定欄形式データセットの表示
		FUNC.NAME	: d2kdgt() 
		INPUT		: ladir		; la directory 
				  ladata	; koteiran keisiki dataset
		OUTPUT		: error code
		REMARKS		:	88.01.28	S.Takahashi
******************************************************************************/
#include	"d2kdgt.h"
extern	DSET	*d3lbgn() ;
static	char	sccsid[]="@(#)d2kdgt.c 1.3 88/06/12 14:43:53";

d2kdgt ( ladir , ladata )
char	*ladir ;
char	*ladata ;
{
	DSET	*dsp ;
	int	err ;
	int	rtn ;

	dsp = NULL ;

/* error handling */

	rtn = setjmp ( pd_kdgt_env ) ;
	if ( rtn != 0 ) {
		kdgt90 ( dsp ) ;
		return ( rtn ) ;
	}
	
/* intialize */
	
	err = kdgt10 ( ladir , ladata , &dsp ) ;
	
/* output to stdout */
	
	if ( err == 0 ) {
		err = d2dsph ( dsp , "all" ) ;/* display koteiran type data */ 
		if ( err != 0 ) ERR ( d3cver( err, "ds" ) ) ;
	} else {
		if ( err == DE_FILE_NONE ) { 
			d2dsph ( dsp , "hed" ) ;/* output header only */
		} else {
			ERR ( d3cver( err, "ds" ) ) ;
		}
	}

/* end */
	kdgt90 ( dsp ) ;
	return ( 0 ) ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: kdgt10() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
kdgt10 ( ladir , ladata , dsp )
	char	*ladir ;	/* (i) la directory */ 
	char	*ladata ;	/* (i) user index file */
	DSET	**dsp ;		/* (i) pointer of ladataset table */
{
	char	path_name[128] ;
	int	err ;

/* ladata set name check */

	err = d3xdat ( ladata ) ;
	if ( err == 0 ) return ( err ) ;

/* begin ladataset */
	
	d3path ( ladir , ladata , path_name ) ;
	*dsp = d3lbgn ( path_name , "r" , 0 , &err ) ;
	return ( err ) ;
}
/******************************************************************************	
		End prosess
		FUNC.NAME	: kdgt90() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
kdgt90 ( dsp )
	DSET	*dsp ;
{
	if ( dsp != NULL ) return ( d3lend ( dsp , "q" , 0 ) ) ;
	return ( 0 ) ;
}
