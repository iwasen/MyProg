/******************************************************************************	
		グループ索引ファイルの登録
		FUNC.NAME	: d2gsrg() 
		INPUT		: ladir		; user directory 
				  ifile		; hyou data file ( ascii ) 
		OUTPUT		: error code
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
#include	"d2gsrg.h"
extern	DSET	*d3lbgn() ;
static	char	sccsid[]="@(#)d2gsrg.c 1.4 88/06/12 14:42:44";

d2gsrg ( ladir , ifile )
char	*ladir ;
char	*ifile ;
{
	DSET	*dsp ;
	FILE	*edt_fp = 0;
	int	rtn ;

	dsp = NULL ;

/* error handling */

	rtn = setjmp ( pd_gsrg_env ) ;
	if ( rtn != 0 ) {
		gsrg99 ( dsp , edt_fp ) ;
		return ( rtn ) ;
	}

/* initialize */
	
	gsrg10 ( ladir , &dsp , ifile , &edt_fp ) ;

/* write header */

	gsrg20 ( dsp ) ;

/* write Axi2 */

	gsrg30 ( dsp ) ;

/* write Des and Dat */

	gsrg40 ( dsp , edt_fp ) ;

/* end prosess */

	return ( gsrg90 ( dsp , edt_fp ) )  ;
}
/******************************************************************************	
		Initialize
		FUNC.NAME	: gsrg10() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
gsrg10 ( ladir , dsp , ifile , edt_fp ) 
	char	*ladir ;	/* (i) users directory */
	DSET	**dsp ;		/* (o) dataset table pointer */
	char	*ifile ;	/* (i) edit file */
	FILE	**edt_fp ;	/* (o) edit file pointer */
{
	char	path[128] ;
	int	err ; 

/* begin of group sakuin file */

	d3path ( ladir , GROUP_INDEX , path ) ;
	*dsp = d3lbgn ( path , "c+" , 0 , &err ) ;
	if ( *dsp == NULL ) ERR ( d3cver ( err , "gi") ) ;

/* open edit file */

	*edt_fp = fopen ( ifile , "r" ) ;
	if ( *edt_fp == NULL ) ERR ( d3cver ( DE_FILE_NONE , "gf" ) ) ;
}
/******************************************************************************	
		Write Header
		FUNC.NAME	: gsrg20() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
gsrg20 ( dsp ) 
	DSET	*dsp ;		/* (i) */
{
 	int	err ;

/* "Comm" = "GRP.IND" */

	err = d3comm ( dsp , GROUP_INDEX_CM ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "gi" ) ) ;
}
/******************************************************************************	
		Write Axi2
		FUNC.NAME	: gsrg30() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
gsrg30 ( dsp ) 
	DSET	*dsp ;		/* (i) */
{
	int 	err ;

	err = d3tinc ( dsp , 0 , DS_C , GRP_PREFIX ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "gi" ) ) ;
	err = d3tinc ( dsp , 1 , DS_C , GRP_NAME ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "gi" ) ) ;
	err = d3tinc ( dsp , 2 , DS_C , GRP_INDEX ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "gi" ) ) ;
}
/******************************************************************************	
		Write Des and Dat
		FUNC.NAME	: gsrg40() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
gsrg40 ( dsp , edt_fp ) 
	DSET	*dsp ;			/* (i) */
	FILE	*edt_fp ;		/* (i) edit file pointer */
{
	int	err ;

/* seek edit file to "#D" */

	err = d3sked ( edt_fp , PD_DAT_MARK ) ;
	if ( err == DE_FILE_IO ) return ( 0 ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "gf" ) ) ;
	
/* create "Lab" and "Dat" */
	
	err = d2outh ( dsp , edt_fp ) ;
	if ( err != 0 ) ERR ( d3cver ( err , "gf" ) ) ;
}
/******************************************************************************	
		End Prosess
		FUNC.NAME	: gsrg90() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
gsrg90 ( dsp , edt_fp ) 
	DSET	*dsp ;			/* (i) */
	FILE	*edt_fp ;		/* (i) */
{
	if (edt_fp != 0)
		fclose ( edt_fp ) ;
	return ( d3lend ( dsp , "wq" , 1 ) ) ; 	/* write ladataset */
}	
/******************************************************************************	
		End Prosess On Error
		FUNC.NAME	: gsrg99() 
		REMARKS		:	88.02.01	S.Takahashi
******************************************************************************/
static
gsrg99 ( dsp , edt_fp ) 
	DSET	*dsp ;
	FILE	*edt_fp ;
{
	if (edt_fp != 0)
		fclose ( edt_fp ) ;
	if ( dsp != NULL ) d3lend ( dsp , "q" , 0 );/*does node write request */
}
