/***************************************************************	
	ê‡ñæïîÅEÉâÉxÉãïîÇÃï\é¶
	FUNC.NAME	: d2slgt() 
	INPUT		: ladir		; la directory 
			  ladata	; la dataset
			  sfile 	; setsumei-bu output file
			  lfile 	; label-bu output file
	OUTPUT		: error code
	REMARKS		:	88.01.28	S.Takahashi
***************************************************************/
#include	"d2slgt.h"
extern	DSET	*d3lbgn() ;

static	char	sccsid[]="@(#)d2slgt.c 1.4 88/06/12 14:45:08";

d2slgt ( ladir , ladata , sfile , lfile )
char	*ladir ;
char	*ladata ;
char	*sfile ;
char	*lfile ;
{
	DSET	*des_dsp ;
	DSET	*lab_dsp ;
	int	rtn ;
	int	err ;
	FILE	*des_fp = 0;
	FILE	*lab_fp = 0;

	des_dsp = lab_dsp = NULL ;

/* parameter check */

	if ( sfile == NULL && lfile == NULL ) return ( d3cver(DE_PRM," ") ) ;

/* error handling */

	rtn = setjmp ( pd_slgt_env ) ;
	if ( rtn != 0 ) {
		slgt90 ( des_dsp , lab_dsp , des_fp , lab_fp ) ;
		return ( rtn ) ;
	}
	
/* intialize */
	
	err = slgt10 ( ladir , ladata , &des_dsp , &lab_dsp , sfile , lfile
						, &des_fp , &lab_fp ) ;
	
/* output to file */
	
	if ( err == 0 ) {
		if ( sfile != NULL ) {
			d2head ( 2 , des_fp ) ; 
			err = d2desd ( des_dsp , des_fp ) ;
			if ( err != 0 ) ERR ( err ) ;
		}
		if ( lfile != NULL ) {
			d2head ( 3 , lab_fp ) ; 
			err = d2axid ( lab_dsp , lab_fp ) ;
			if ( err != 0 ) ERR ( err ) ;
		}
	} else {
		if ( err == DE_FILE_NONE ) { 
			if ( sfile != NULL ) d2head ( 2 , des_fp );
			if ( lfile != NULL ) d2head ( 3 , lab_fp );
		} else {
			ERR ( d3cver ( err , "ds" ) ) ;
		}
	}

/* end */

	return ( slgt90 ( des_dsp , lab_dsp , des_fp , lab_fp ) ) ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: slgt10() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
slgt10 ( ladir , ladata , des_dsp , lab_dsp , sfile , lfile , des_fp , lab_fp )
	char	*ladir ;	/* (i) la directory */ 
	char	*ladata ;	/* (i) user index file */
	DSET	**des_dsp ;	/* (o) pointer of ladataset table */
	DSET	**lab_dsp ;	/* (o) pointer of ladataset table */
	char	*sfile ;	/* (i) */
	char	*lfile ;	/* (i) */
	FILE	**des_fp ;	/* (o) setsumie-bu file pointer */
	FILE	**lab_fp ;	/* (o) label-bu file pointer */
{
	char	path_name[128] ;
	int	err ;

/* open output file */

	if ( sfile != NULL ) {
		*des_fp = fopen ( sfile , "w" ) ;
		if ( *des_fp == NULL ) ERR ( d3cver ( DE_FILE_IO,"ef" ) ) ;
	}
	if ( lfile != NULL ) {
		*lab_fp = fopen ( lfile , "w" ) ;
		if ( *lab_fp == NULL ) ERR ( d3cver ( DE_FILE_IO,"lf" ) ) ;
	}

/* begin ladataset */
	
	d3path ( ladir , ladata , path_name ) ;
	*des_dsp = d3lbgn ( path_name , "r1" , 0 , &err ) ;
	if ( err != 0 ) return ( err ) ;
	*lab_dsp = d3lbgn ( path_name , "r2" , 0 , &err ) ;
	return ( err ) ;
}
/******************************************************************************	
		End prosess
		FUNC.NAME	: slgt90() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
slgt90 ( des_dsp , lab_dsp , des_fp , lab_fp )
	DSET	*des_dsp ;
	DSET	*lab_dsp ;
	FILE	*des_fp ;
	FILE	*lab_fp ;
{
	int	err ;

	if (des_fp != 0)
		fclose ( des_fp ) ;
	if (lab_fp != 0)
		fclose ( lab_fp ) ;
	if ( des_dsp != NULL ) { 
		err = d3lend ( des_dsp , "q" , 0 ) ;
		if ( err != 0 ) d3lend ( lab_dsp , "q" , 0 ) ;
		return ( d3cver ( err , "ds" ) ) ;
	}
	if ( lab_dsp != NULL ) { 
		err = d3lend ( lab_dsp , "q" , 0 ) ;
		if ( err != 0 ) return ( d3cver ( err , "ds" ) ) ;
	}
	return ( 0 ) ;
}
