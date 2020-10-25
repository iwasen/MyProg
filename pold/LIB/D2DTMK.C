/******************************************************************************
	データファイルからのＬＡデータセット生成・登録
	FUNC.NAME	: d2dtmk() 
	INPUT		: ladir		; user directory 
			  ladata	; user index file name 
			  sfile		; setsumei-bu file
			  lfile		; label-bu file
			  dfile		; data file 
			  dkind		; data file kind 
					; 1...生データファイル
					; 2...解析データファイル
			  w		; over write check
	OUTPUT		: error code
	REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
#include	"d2dtmk.h"
extern	DSET	*d3lbgn() ;
extern 	struct	dsm	*d3gcmp() ;
static char	sccsid[]="@(#)d2dtmk.c 1.5 88/06/13 17:20:24";

d2dtmk ( ladir , ladata , sfile , lfile , dfile , dkind , w )
	char	*ladir , *ladata , *sfile , *lfile , *dfile ;
	int	dkind ;
	int 	*w ;
{
	DSET	*dsp ;
	FILE	*lab_fp = 0 , *des_fp = 0 , *d_fp = 0 ;
	int	rtn ;
	char	index[128] ;

	dsp = NULL ;

/* parameter check */

	rtn = dtmk01 ( ladir , ladata , sfile , lfile , dfile , w ) ;
	if ( rtn != 0 ) return ( rtn ) ;

/* error handling */

	rtn = setjmp ( pd_dtmk_env ) ;
	if ( rtn != 0 ) {
		dtmk99 ( dsp , des_fp , lab_fp , d_fp ) ;
		return ( rtn ) ;
	}

/* initialize */
	
	dtmk10 ( ladir , ladata , &dsp , sfile , lfile , dfile , dkind , 
					&des_fp , &lab_fp , &d_fp , index ) ;

/* write Des */

	if ( sfile != NULL ) dtmk20 ( dsp , des_fp ) ;

/* write Lab */

	if ( lfile != NULL )  dtmk30 ( dsp , lab_fp ) ;

/* write Dat */

	switch ( dkind ) {
		case NAMA_DATA  :
			if ( dfile != NULL ) dtmk40 ( dsp , d_fp ) ;
			break ;
		case KAISEKI_DATA :
			if ( dfile != NULL ) dtmk50 ( dsp , d_fp ) ;
			break ;
		default :
			break ;
	}

/* end prosess */

	dtmk90 ( dsp , des_fp , lab_fp , d_fp , ladir , ladata , index ) ;
	return ( 0 ) ;
}
/******************************************************************************	
		Parameter Check 
		FUNC.NAME	: dtmk01() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
dtmk01 ( ladir , ladata , sfile , lfile , dfile , w )
	char	*ladir , *ladata , *sfile , *lfile , *dfile ;	
	int	*w ;
{
	int	exist ;
	char	path[128] ;

	if (d3xdat(ladata) == 0)
		return(d3cver(DE_DSNAME,""));
	d3path ( ladir , ladata , path ) ;
	exist = d3fchk ( path ) ;
	if ( *w == 0 && exist == 0 ) return ( d3cver(DE_FILE_EXIST,"ds" ) ) ;
	return ( 0 ) ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: dtmk10() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
dtmk10 ( ladir,ladata,dsp,sfile,lfile,dfile,dkind,des_fp,lab_fp,d_fp,index ) 
	char	*ladir , *ladata ;
	DSET	**dsp ;
	char	*sfile , *lfile , *dfile ;
	int	dkind ;
	FILE	**des_fp , **lab_fp , **d_fp ;
	char	*index ;
{
	char	path[128] ;
	int	err ; 

/* file type check */

	err = d3xdat ( ladata ) ;
	if ( err == 0 ) ERR (d3cver(err," ") ) ;

/* check index file */

	err = d2ichk ( ladir , ladata , index ) ;
	if ( err != 0 ) ERR ( err ) ;

/* begin of user ladataset file */

	d3path ( ladir , ladata , path ) ;
	*dsp = d3lbgn ( path , "c+" , 0 , &err ) ;
	if ( *dsp == NULL ) ERR ( d3cver ( err , "ds") ) ;

/* open edit file */

	if ( sfile != NULL ) {
		*des_fp = fopen ( sfile , "r" ) ;
		if ( *des_fp == NULL ) ERR ( d3cver ( DE_FILE_NONE , "ef" ) ) ;
	}
	if ( lfile != NULL ) {
		*lab_fp = fopen ( lfile , "r" ) ;
		if ( *lab_fp == NULL ) ERR ( d3cver ( DE_FILE_NONE , "lf" ) ) ;
	}

/* open data file */

	if ( dfile != NULL ) {
		*d_fp = fopen ( dfile , "r" ) ;
		if ( *d_fp == NULL ) {
			switch ( dkind ) {
				case NAMA_DATA :
					ERR ( d3cver ( DE_FILE_NONE , "rf" ) ) ;
				case KAISEKI_DATA :
					ERR ( d3cver ( DE_FILE_NONE , "af" ) ) ;
				default :
					break ;
			}
		}
	}
}
/******************************************************************************	
		Write Des From Des file 
		FUNC.NAME	: dtmk20() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
dtmk20 ( dsp , des_fp ) 
	DSET	*dsp ;
	FILE	*des_fp ;
{
	int	err ;

	err = d2desw ( dsp , des_fp ) ;
	if ( err != 0 ) ERR ( err ) ; 
}
/******************************************************************************	
		Write Lab
		FUNC.NAME	: dtmk30() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
dtmk30 ( dsp , lab_fp ) 
	DSET	*dsp ;		
	FILE	*lab_fp ;
{
	int 	err ;
	
	err = d2axiw ( dsp , lab_fp ) ;
	if ( err != 0 ) ERR ( err ) ;
}
/******************************************************************************	
		生データファイルからＤｅｓ部作成
		FUNC.NAME	: dtmk40() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
dtmk40 ( dsp , d_fp ) 
	DSET	*dsp ;	
	FILE	*d_fp ;	
{
	/******************* pending *******************/
}
/******************************************************************************	
		解析データファイルからＤｅｓ部作成
		FUNC.NAME	: dtmk50() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
dtmk50 ( dsp , d_fp ) 
	DSET	*dsp ;	
	FILE	*d_fp ;	
{
	struct	dsm	*dat_ptr , *k_ptr ;
	int	err ;

/* read kaiseki data file */
	
	err = d3dsrd ( d_fp , dsp->ds_memno , &k_ptr ) ;
	if ( err != 0 ) ERR ( d3cver(err,"af" ) ) ;

/* copy pointer to Dat */

	dat_ptr = d3gcmp ( dsp->ds_entry , "Dat" ) ;
	PD_ENTCPY ( dat_ptr , k_ptr ) ;
}
/******************************************************************************	
		End Prosess
		FUNC.NAME	: dtmk90() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
dtmk90 ( dsp , des_fp , lab_fp , d_fp , ladir , ladata , index ) 
	DSET	*dsp ;	
	FILE	*des_fp , *lab_fp , *d_fp ;	
	char	*ladir , *ladata , *index ;	
{
	int	err ;

	if (lab_fp != 0)
		fclose ( lab_fp ) ;
	if (des_fp != 0)
		fclose ( des_fp ) ;
	if (d_fp != 0)
		fclose ( d_fp ) ;
	err =  ( d3lend ( dsp , "wq" , 1 ) ) ; 	/* write ladataset */
	if ( err != 0 ) ERR ( d3cver ( err , "ds" ) ) ;
	err = d2idup ( ladir , index , ladata ) ;/* update to index file */
	if ( err != 0 ) ERR ( err ) ;
}	
/******************************************************************************	
		End Prosess On Error
		FUNC.NAME	: dtmk99() 
		REMARKS		:	88.02.01	S.Takahashi
******************************************************************************/
static
dtmk99 ( dsp , des_fp , lab_fp , d_fp ) 
	DSET	*dsp ;
	FILE	*des_fp , *lab_fp , *d_fp ;	
{
	if (lab_fp != 0)
		fclose ( lab_fp ) ;
	if (des_fp != 0)
		fclose ( des_fp ) ;
	if (d_fp != 0)
		fclose ( d_fp ) ;
	if ( dsp != NULL ) d3lend ( dsp , "q" , 0 );/* does not write request */
}
