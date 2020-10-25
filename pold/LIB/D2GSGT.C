/******************************************************************************	
		グループ索引ファイルの表示
		FUNC.NAME	: d2gsgt() 
		INPUT		: ladir
		OUTPUT		: error code
		REMARKS		:	88.01.24	S.Takahashi
******************************************************************************/
#include	"d2gsgt.h"
extern	DSET	*d3lbgn() ;

static	char	sccsid[]="@(#)d2gsgt.c 1.3 88/06/12 14:42:30";

d2gsgt ( ladir )
char	*ladir ;
{
	DSET	*dsp ;
	int	err ;
	int	rtn ;

	dsp = NULL ;

/* error handling */

	rtn = setjmp ( pd_gsgt_env ) ;
	if ( rtn != 0 ) {
		gsgt90 ( dsp ) ;
		return ( rtn ) ;
	}
	
/* intialize */
	
	err = gsgt10 ( ladir , &dsp ) ;
	
/* output to stdout */
	
	if ( err == 0 ) {
		err = d2dsph ( dsp , "all" ) ;/* display koteiran type data */ 
		if ( err != 0 ) ERR ( d3cver ( err , "gi" ) ) ;
	} else {
		if ( err == DE_FILE_NONE ) { 
			err = d2dsph ( dsp , "hed" ) ;/* output header only */
			gsgt20 () ;
			return ( 0 ) ;
		} else {
			ERR ( d3cver ( err , "gi" ) ) ;
		}
	}

/* end */

	err = gsgt90 ( dsp ) ;
	return ( d3cver ( err , "gi" ) ) ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: gsgt10() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
gsgt10 ( ladir , dsp )
	char	*ladir ;	/* (i) la directory */
	DSET	**dsp ;		/* (i) pointer of ladataset table */
{
	char	path_name[128] ;
	int	err ;

/* begin ladataset */
	
	d3path ( ladir , GROUP_INDEX , path_name ) ;
	*dsp = d3lbgn ( path_name , "r" , 0 , &err ) ;
	return ( err ) ;
}
/******************************************************************************	
		Output Header to STDOUT 
		FUNC.NAME	: gsgt20() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
gsgt20 ()  
{	
	printf ( "%s","/* " ) ;
	printf ( "%c%s%c," , DBLQ , GRP_PREFIX , DBLQ ) ;
	printf ( "%c%s%c," , DBLQ , GRP_NAME , DBLQ ) ;
	printf ( "%c%s%c" , DBLQ , GRP_INDEX , DBLQ ) ;
	printf ( "%s\n"," */" ) ;
}
/******************************************************************************	
		End prosess
		FUNC.NAME	: gsgt90() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
gsgt90 ( dsp )
	DSET	*dsp ;
{
	if ( dsp != NULL ) return ( d3lend ( dsp , "q" , 0 ) ) ;
	return ( 0 ) ;
}
