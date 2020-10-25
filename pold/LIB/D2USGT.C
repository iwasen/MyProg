/***************************************************************	
	ユーザ索引ファイルの表示
	FUNC.NAME	: d2usgt() 
	INPUT		: ladir		; la directory 
			  uidfile	; user index file
	OUTPUT		: error code
	REMARKS		:	88.01.28	S.Takahashi
***************************************************************/
#include	"d2usgt.h"
extern	DSET	*d3lbgn() ;

static	char	sccsid[]="@(#)d2usgt.c 1.4 88/06/12 16:23:34";

d2usgt ( ladir , uidfile )
char	*ladir ;
char	*uidfile ;
{
	DSET	*dsp ;
	int	err ;
	int	rtn ;
	
	dsp = NULL ;

/* error handling */

	rtn = setjmp ( pd_usgt_env ) ;
	if ( rtn != 0 ) {
		usgt90 ( dsp ) ;
		return ( rtn ) ;
	}
	
/* intialize */
	
	err = usgt10 ( ladir , uidfile , &dsp ) ;

/* output to stdout */
	
	if ( err == 0 ) {
		err = d2dsph ( dsp , "all" ) ;/* display koteiran type data */ 
		if ( err != 0 ) ERR ( d3cver ( err , "uf" ) ) ;
	} else {
		if ( err == DE_FILE_NONE ) { 
			err = d2dsph ( dsp , "hed" ) ;/* output header only */
			return ( 0 ) ;
		} else {
			ERR ( d3cver ( err , "uf" ) ) ;
		}
	}

/* end */

	err =  usgt90 ( dsp )  ;
	return ( d3cver ( err , "uf " ) ) ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: usgt10() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
usgt10 ( ladir , uidfile , dsp )
	char	*ladir ;	/* (i) la directory */ 
	char	*uidfile ;	/* (i) user index file */
	DSET	**dsp ;		/* (i) pointer of ladataset table */
{
	char	path_name[128] ;
	int	err ;

/* user index file name check */

	if ( d3xusr ( uidfile ) == 0 ) 
		return ( DE_DSNAME ) ;

/* begin ladataset */
	
	d3path ( ladir , uidfile , path_name ) ;
	*dsp = d3lbgn ( path_name , "r" , 0 , &err ) ;
	return ( err ) ;
}
/******************************************************************************	
		End prosess
		FUNC.NAME	: usgt90() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
usgt90 ( dsp )
	DSET	*dsp ;
{
	if ( dsp != NULL ) return ( d3lend ( dsp , "q" , 0 ) ) ;
	return ( 0 ) ;
}
