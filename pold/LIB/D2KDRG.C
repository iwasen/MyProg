/******************************************************************	
	固定欄形式データセットの登録	
	FUNC.NAME	: d2kdrg() 
	INPUT		: ladir		; user directory 
			  ladata	; user index file name 
			  sfile		; setsumei-bu file
			  kfile		; hyou data file ( ascii ) 
			  w		; over write check
	OUTPUT		: error code
	REMARKS		:	88.02.02	S.Takahashi
******************************************************************/
#include	"d2kdrg.h"
extern	DSET	*d3lbgn() ;

static	char	sccsid[]="@(#)d2kdrg.c 1.4 88/06/12 14:44:08";

d2kdrg ( ladir , ladata , sfile , kfile , w )
char	*ladir ;
char	*ladata ;
char	*sfile ;
char	*kfile ;
char	*w ;
{
	DSET	*dsp ;
	FILE	*edt_fp = 0;
	FILE	*des_fp = 0;
	int	rtn ;
	char	index[128] ;

	dsp = NULL ;

/* parameter check */

	rtn = kdrg01 ( ladir , ladata , sfile , kfile , w ) ;
	if ( rtn != 0 ) return ( rtn ) ;

/* error handling */

	rtn = setjmp ( pd_kdrg_env ) ;
	if ( rtn != 0 ) {
		kdrg99 ( dsp , edt_fp , des_fp ) ;
		return ( rtn ) ;
	}

/* initialize */
	
	kdrg10 ( ladir , ladata , w , &dsp , sfile , kfile , 
				&edt_fp , &des_fp , index ) ;

/* write Des */

	if ( sfile != NULL ) kdrg20 ( dsp , des_fp ) ;

/* write Axi2 */

	if ( kfile != NULL )  kdrg30 ( dsp , edt_fp ) ;

/* write Des and Dat */

	if ( kfile != NULL ) kdrg40 ( dsp , edt_fp ) ;

/* end prosess */

	return ( kdrg90 ( dsp , edt_fp , des_fp , ladir , ladata , index ) ) ;
}
/******************************************************************************	
		Parameter Check 
		FUNC.NAME	: kdrg01() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
kdrg01 ( ladir , ladata , sfile , kfile , w )
	char	*ladir ;
	char	*ladata ; 
	char	*sfile ;
	char	*kfile ;	
	char	*w ;
{
	int	exist ;
	char	path[128] ;

	d3path ( ladir , ladata , path ) ;
	exist = d3fchk ( path ) ;
	if ( strcmp ( w , "u" ) == 0 ) {
		if ( sfile == NULL && kfile == NULL )return(d3cver(DE_PRM," "));
		if ( exist != 0 ) return ( d3cver(DE_FILE_NONE,"kf") ) ;
	} else if ( strcmp ( w , "c+" ) == 0 ) {
	} else if ( strcmp ( w , "c" ) == 0 ) {
		if ( exist == 0 ) return ( d3cver(DE_FILE_EXIST,"kf") ) ;
	} else 
		return ( d3cver ( DE_PRM," " ) ) ;
	return ( 0 ) ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: kdrg10() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
kdrg10 ( ladir , ladata , w , dsp , sfile , kfile , edt_fp , des_fp , index ) 
	char	*ladir ;	/* (i) users directory */
	char	*ladata ;	/* (i) user index file name */
	char	*w ;		/* (i) over write flag */
	DSET	**dsp ;		/* (o) dataset table pointer */
	char	*sfile ;	/* (i) edit file */
	char	*kfile ;	/* (i) edit file */
	FILE	**edt_fp ;	/* (o) edit file pointer */
	FILE	**des_fp ;	/* (o) des file pointer */
	char	*index ;	/* (o) index file name */
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
	*dsp = d3lbgn ( path , w , 0 , &err ) ;
	if ( *dsp == NULL ) ERR ( d3cver ( err , "kf") ) ;

/* clear "Lab" and "Des" when update mode */

	if ( strncmp ( w , "u" , 1 ) == 0 ) {
		if ( sfile != NULL ) {
			err = d3clcm ( *dsp , "Des" ) ;
			if ( err != 0 ) ERR ( d3cver ( err , "kf" ) ) ;
		}
		if ( kfile != NULL ) {
			err = d3clcm ( *dsp , "Lab" ) ;
			if ( err != 0 ) ERR ( d3cver ( err , "kf" ) ) ;
			err = d3clcm ( *dsp , "Dat" ) ;
			if ( err != 0 ) ERR ( d3cver ( err , "kf" ) ) ;
		}
	}

/* open edit file */

	if ( kfile != NULL ) {
		*edt_fp = fopen ( kfile , "r" ) ;
		if ( *edt_fp == NULL ) ERR ( d3cver ( DE_FILE_NONE , "kf" ) ) ;
	}
	if ( sfile != NULL ) {
		*des_fp = fopen ( sfile , "r" ) ;
		if ( *des_fp == NULL ) ERR ( d3cver ( DE_FILE_NONE , "ef" ) ) ;
	}
}
/******************************************************************************	
		Write Des From Des file 
		FUNC.NAME	: kdrg20() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
kdrg20 ( dsp , des_fp ) 
	DSET	*dsp ;
	FILE	*des_fp ;
{
	int	err ;

	err = d2desw ( dsp , des_fp ) ;
	if ( err != 0 ) ERR ( err ) ; 
}
/******************************************************************************	
		Write Axi2
		FUNC.NAME	: kdrg30() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
kdrg30 ( dsp , edt_fp ) 
	DSET	*dsp ;		/* (i) */
	FILE	*edt_fp ;		/* (i) */
{
	int 	err ;
	
	err = d2out2 ( dsp , edt_fp ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "kf" ) ) ;
}
/******************************************************************************	
		Write Des and Dat
		FUNC.NAME	: kdrg40() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
kdrg40 ( dsp , edt_fp ) 
	DSET	*dsp ;			/* (i) */
	FILE	*edt_fp ;		/* (i) edit file pointer */
{
	int	err ;

/* seek edit file to "#D" */
	
	rewind ( edt_fp ) ;
	err = d3sked ( edt_fp , PD_DAT_MARK ) ;
	if ( err == DE_FILE_IO ) return ( 0 ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "kf" ) ) ;
	
/* create "Lab" and "Dat" */
	
	err = d2outh ( dsp , edt_fp ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "kf" ) ) ;
}
/******************************************************************************	
		End Prosess
		FUNC.NAME	: kdrg90() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
kdrg90 ( dsp , edt_fp , des_fp , ladir , ladata , index ) 
	DSET	*dsp ;			/* (i) */
	FILE	*edt_fp ;		/* (i) */
	FILE	*des_fp ;		/* (i) */
	char	*ladir ;		/* (i) */ 
	char	*ladata ;		/* (i) */
	char	*index ;		/* (i) */
{
	int	err ;

	if (edt_fp != 0)
		fclose ( edt_fp ) ;
	if (des_fp != 0)
		fclose ( des_fp ) ;
	err =  ( d3lend ( dsp , "wq" , 1 ) ) ; 	/* write ladataset */
	if ( err != 0 ) ERR ( d3cver ( err , "ds" ) ) ;
	err = d2idup ( ladir , index , ladata ) ;/* update to index file */
	if ( err != 0 ) ERR ( err ) ;
	return ( 0 ) ;
}	
/******************************************************************************	
		End Prosess On Error
		FUNC.NAME	: kdrg99() 
		REMARKS		:	88.02.01	S.Takahashi
******************************************************************************/
static
kdrg99 ( dsp , edt_fp , des_fp ) 
	DSET	*dsp ;
	FILE	*edt_fp ;
	FILE	*des_fp ;
{
	if (edt_fp != 0)
		fclose ( edt_fp ) ;
	if (des_fp != 0)
		fclose ( des_fp ) ;
	if ( dsp != NULL ) d3lend ( dsp , "q" , 0 );/* does not write request */
}
