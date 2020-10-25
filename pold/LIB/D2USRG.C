/***************************************************************	
	ユーザ索引ファイルの登録
	FUNC.NAME	: d2usrg() 
	INPUT		: ladir		; user directory 
			  ladata	; user index file name 
			  uidfile	; hyou data file ( ascii ) 
			  w		; over write check
	OUTPUT		: error code
	REMARKS		:	88.02.02	S.Takahashi
***************************************************************/
#include	"d2usrg.h"
extern	DSET	*d3lbgn() ;

static	char	sccsid[]="@(#)d2usrg.c 1.5 88/06/12 16:23:56";

d2usrg ( ladir , ladata , uidfile , w )
char	*ladir ;
char	*ladata ;
char	*uidfile ;
int	*w ;
{
	DSET	*dsp ;
	FILE	*edt_fp = 0;
	int	rtn ;
	char	index[128];

	dsp = NULL ;

/* error handling */

	rtn = setjmp ( pd_usrg_env ) ;
	if ( rtn != 0 ) {
		usrg99 ( dsp , edt_fp ) ;
		return ( rtn ) ;
	}

/* initialize */
	
	usrg10 ( ladir , ladata , w , &dsp , uidfile , &edt_fp , index ) ;

/* write header */

	usrg20 ( dsp ) ;

/* write Axi2 */

	usrg30 ( dsp , edt_fp ) ;

/* write Des and Dat */

	usrg40 ( dsp , edt_fp ) ;

/* end prosess */

	return ( usrg90 ( dsp , edt_fp , ladir, ladata, index ) ) ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: usrg10() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
usrg10 ( ladir , ladata , w , dsp , uidfile , edt_fp , index) 
	char	*ladir ;	/* (i) users directory */
	char	*ladata ;	/* (i) user index file name */
	int	*w ;
	DSET	**dsp ;		/* (o) dataset table pointer */
	char	*uidfile ;	/* (i) edit file */
	FILE	**edt_fp ;	/* (o) edit file pointer */
	char	*index ;	/* system index name ("IND.USR") */
{
	char	path[128] ;
	int	err ; 

/* file type check */

	if ( d3xusr (ladata) == 0 ) 
		ERR (d3cver(DE_DSNAME," ") ) ;
	
/* check index file */

	err = d2ichk ( ladir , ladata , index ) ;
	if ( err != 0 ) ERR ( err ) ;

/* over write check */
	
	d3path ( ladir , ladata , path ) ;
	if ( *w == 0 && d3fchk ( path ) == 0 ) ERR ( d3cver(DE_FILE_EXIST,"uf"));

/* begin of user sakuin file */

	*dsp = d3lbgn ( path , "c+" , 0 , &err ) ;
	if ( *dsp == NULL ) ERR ( d3cver ( err , "gi") ) ;

/* open edit file */

	*edt_fp = fopen ( uidfile , "r" ) ;
	if ( *edt_fp == NULL ) ERR ( d3cver ( DE_FILE_NONE , "ue" ) ) ;
}
/******************************************************************************	
		Write Header
		FUNC.NAME	: usrg20() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
usrg20 ( dsp , ladata ) 
	DSET	*dsp ;		/* (i) */
	char	*ladata ;
{
 	int	err ;

/* "Comm" = user index file name */
	
	err = d3comm ( dsp , USER_INDEX_CM ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "uf" ) ) ;
}
/******************************************************************************	
		Write Axi2
		FUNC.NAME	: usrg30() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
usrg30 ( dsp , edt_fp ) 
	DSET	*dsp ;		/* (i) */
	FILE	*edt_fp ;	/* (i) */
{
	int 	err ;

	err = d2out2 ( dsp , edt_fp ) ;
	if ( err != 0 ) ERR ( d3cver ( DE_VI_FMT , "ue" ) ) ;
}
/******************************************************************************	
		Write Des and Dat
		FUNC.NAME	: usrg40() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
usrg40 ( dsp , edt_fp ) 
	DSET	*dsp ;			/* (i) */
	FILE	*edt_fp ;		/* (i) edit file pointer */
{
	int	err ;

/* seek edit file to "#D" */
	
	rewind ( edt_fp ) ;
	err = d3sked ( edt_fp , PD_DAT_MARK ) ;
	if ( err == DE_FILE_IO ) return ( 0 ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "uf" ) ) ;
	
/* create "Lab" and "Dat" */
	
	err = d2outh ( dsp , edt_fp ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "uf" ) ) ;
}
/******************************************************************************	
		End Prosess
		FUNC.NAME	: usrg90() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
usrg90 ( dsp , edt_fp , ladir, ladata, index ) 
	DSET	*dsp ;			/* (i) */
	FILE	*edt_fp ;		/* (i) */
	char	*ladir ;
	char	*ladata ;
	char	*index ;
{
	int	err;

	if (edt_fp != 0)
		fclose ( edt_fp ) ;

	err =  ( d3lend ( dsp , "wq" , 1 ) ) ; 	/* write ladataset */
	if ( err != 0 ) ERR ( d3cver ( err , "ds" ) ) ;

	err = d2idup ( ladir , index , ladata ) ;/* update to index file */
	if ( err != 0 ) ERR ( err ) ;

	return ( 0 ) ;
}	
/******************************************************************************	
		End Prosess On Error
		FUNC.NAME	: usrg99() 
		REMARKS		:	88.02.01	S.Takahashi
******************************************************************************/
static
usrg99 ( dsp , edt_fp ) 
	DSET	*dsp ;
	FILE	*edt_fp ;
{
	if (edt_fp != 0)
		fclose ( edt_fp ) ;
	if ( dsp != NULL ) d3lend ( dsp , "q" , 0 );/* does not write request */
}
