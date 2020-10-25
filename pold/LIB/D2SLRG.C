/***************************************************************	
	ê‡ñæïîÅEÉâÉxÉãïîÇÃìoò^
	FUNC.NAME	: d2slrg() 
	INPUT		: ladir		; user directory 
			  ladata	; user index file name 
			  sfile		; setsumei-bu file
			  lfile		; label-bu file 
			  w		; over write check
	OUTPUT		: error code
	REMARKS		:	88.02.02	S.Takahashi
***************************************************************/
#include	"d2slrg.h"
extern	DSET	*d3lbgn() ;

static	char	sccsid[]="@(#)d2slrg.c 1.4 88/06/12 14:45:30";

d2slrg ( ladir , ladata , sfile , lfile , w )
char	*ladir ;
char	*ladata ;
char	*sfile ;
char	*lfile ;
int	*w ;
{
	DSET	*dsp ;
	FILE	*des_fp = 0;
	FILE	*lab_fp = 0;
	int	rtn ;
	char	index[128] ;
	int	err ;


	dsp = NULL ;

/* parameter check */

	rtn = slrg01 ( ladir , ladata , sfile , lfile , w ) ;
	if ( rtn != 0 ) return ( rtn ) ;

/* error handling */

	rtn = setjmp ( pd_slrg_env ) ;
	if ( rtn != 0 ) {
		slrg99 ( dsp , des_fp , lab_fp ) ;
		return ( rtn ) ;
	}

/* initialize */
	
	slrg10 ( ladir , ladata , w , &dsp , sfile , lfile ,
				 &des_fp , &lab_fp , index ) ;

/* write Des */

	if ( sfile != NULL ) {
		err = d2desw ( dsp , des_fp )  ;
		if ( err != 0 ) ERR ( err ) ;
	}

/* write Axi */

	if ( lfile != NULL ) {
		err = d2axiw ( dsp , lab_fp )  ;
		if ( err != 0 ) ERR ( err ) ; 
	}

/* end prosess */

	return ( slrg90 ( dsp , des_fp , lab_fp ,  ladir , ladata , index ) );
}
/******************************************************************************	
		Parameter Check 
		FUNC.NAME	: slrg01() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
slrg01 ( ladir , ladata , sfile , lfile , w )
	char	*ladir ;
	char	*ladata ; 
	char	*sfile ;
	char	*lfile ;	
	int	*w ;
{
	int	exist ;
	char	path[128] ;

	d3path ( ladir , ladata , path ) ;
	exist = d3fchk ( path ) ;
	if ( sfile == NULL && lfile == NULL ) return ( d3cver (DE_PRM," ") );
	if ( exist != 0 ) return ( d3cver ( DE_FILE_NONE , "ds" ) ) ;
	if ( *w == 0 && exist == 0 ) return ( d3cver(DE_FILE_EXIST,"ds") ) ;
	return ( 0 ) ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: slrg10() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
slrg10 ( ladir , ladata , w , dsp , sfile , lfile , des_fp , lab_fp , index ) 
	char	*ladir ;	/* (i) users directory */
	char	*ladata ;	/* (i) user index file name */
	int	*w ;		/* (i) over write flag */
	DSET	**dsp ;		/* (o) dataset table pointer */
	char	*sfile ;	/* (i) edit file */
	char	*lfile ;	/* (i) edit file */
	FILE	**des_fp ;	/* (o) edit file pointer */
	FILE	**lab_fp ;	/* (o) edit file pointer */
	char	*index ;	/* (o) index file name */
{
	char	path[128] ;
	int	err ; 

/* check index file */

	if ( d3xdat ( ladata ) != 0 ) {
		err = d2ichk ( ladir , ladata , index ) ;
		if ( err != 0 ) ERR ( err ) ;
	}

/* begin of user ladataset file */

	d3path ( ladir , ladata , path ) ;
	*dsp = d3lbgn ( path , "u" , 0 , &err ) ;
	if ( *dsp == NULL ) ERR ( d3cver ( err , "ds") ) ;

/* clear "Lab" and "Des" when update mode */

	if ( sfile != NULL ) {
		err = d3clcm ( *dsp , "Des" ) ;
		if ( err != 0 ) ERR ( d3cver ( err , "  " ) ) ;
	}
	if ( lfile != NULL ) {
		err = d3caxi ( *dsp ) ;
		if ( err != 0 ) ERR ( d3cver ( err , "  " ) ) ;
	}

/* open edit file */

	if ( sfile != NULL ) {
		*des_fp = fopen ( sfile , "r" ) ;
		if ( *des_fp == NULL ) ERR ( d3cver ( DE_FILE_NONE , "ef" ) ) ;
	}
	if ( lfile != NULL ) {
		*lab_fp = fopen ( lfile , "r" ) ;
		if ( *lab_fp == NULL ) ERR ( d3cver ( DE_FILE_NONE , "lf" ) ) ;
	}
}
/******************************************************************************	
		End Prosess
		FUNC.NAME	: slrg90() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
slrg90 ( dsp , des_fp , lab_fp ,  ladir , ladata , index ) 
	DSET	*dsp ;			/* (i) */
	FILE	*des_fp ;		/* (i) */
	FILE	*lab_fp ;		/* (i) */
	char	*ladir ;		/* (i) */ 
	char	*ladata ;		/* (i) */
	char	*index ;		/* (i) */
{
	int	err ;

	if (des_fp != 0)
		fclose ( des_fp ) ;
	if (lab_fp != 0)
		fclose ( lab_fp ) ;
	err =  ( d3lend ( dsp , "wq" , 0 ) ) ; 	/* write ladataset */
	if ( err != 0 ) ERR ( d3cver ( err , "ds" ) ) ;
	if ( d3xdat ( ladata ) != 0 ) {
		err = d2idup ( ladir , index , ladata );/*update to index file*/
		if ( err != 0 ) ERR ( err ) ;
	}
	return ( 0 ) ;
}	
/******************************************************************************	
		End Prosess On Error
		FUNC.NAME	: slrg99() 
		REMARKS		:	88.02.01	S.Takahashi
******************************************************************************/
static
slrg99 ( dsp , des_fp , lab_fp ) 
	DSET	*dsp ;
	FILE	*des_fp ;
	FILE	*lab_fp ;
{
	if (des_fp != 0)
		fclose ( des_fp ) ;
	if (lab_fp != 0)
		fclose ( lab_fp ) ;
	if ( dsp != NULL ) d3lend ( dsp , "q" , 0 );/* does not write request */
}
