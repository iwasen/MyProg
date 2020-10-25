/***************************************************************	
	Begin prosess  To Use of Ladataset
	FUNC.NAME	: d3lgbn() 
	INPUT		: pass_name	; ladataset name 
			: mode		; prosess mode
					;  "u","u+"	( update )
					;  "c","c+"	( create )
					;  "r"		( read all )
					;  "r1"		( read Des )
					;  "r2"		( read Lab )
					;  "r3"		( read Dat )
			: option	; option mode
	OUTPUT		: err		; error code 
			: return code	; DSET address
***************************************************************/
#include	"d3lbgn.h"
#define		DBG(x)	printf ( "x\n" )
extern		int	d3dsrd() ;
extern		int	d3mgno() ;

static	char	sccsid[]="@(#)d3lbgn.c 1.3 88/06/12 14:53:19";
DSET	ds_mrec[DSET_TBL_MAX] ;

DSET *d3lbgn ( pass_name , mode , option , err ) 
	char	*pass_name ;
	char	*mode ;
	int	option ;
	int	*err ;
{
	int	pmode ;
	int	tbl_no ; 
	char	*mptr ;
	int	mgno ;
	FILE	*fp ;

/* initialize */

	*err = 0 ;

/* dataset table entry check */

	tbl_no = lbgn10 ( pass_name ) ;
	if ( tbl_no < 0 ) { *err = tbl_no ; return ( NULL ) ; }

/* make prosess mode */

	pmode = lbgn20 ( pass_name , mode ) ;
	if ( pmode < 0 ) { *err = pmode ; return ( NULL ) ; }
	
/* open ladataset */

	switch ( pmode ) {
		case UPDATE : fp = fopen ( pass_name , "r+" ) ; break ;
		case CREATE : fp = fopen ( pass_name , "a" ) ; break ;
		case READ   : fp = fopen ( pass_name , "r" ) ; break ;
	}
	if ( fp == NULL ) { *err = DE_FILE_IO ; return ( NULL ) ; }

/* lock ladataset */

	*err = lbgn30 ( pmode , fp ) ;
	if ( *err < 0 ) return ( NULL ) ;

/* read ladatset to dynamic memory */

	*err = lbgn40 ( pmode , mode , fp , &mptr , &mgno , pass_name ) ;
	if ( *err < 0 ) return ( NULL ) ;

/* entry to ds-table */

	lbgn50 ( pass_name , tbl_no , fp , mptr , mgno , pmode , option) ;
	
/* close file */

	if ( pmode == READ ) fclose ( fp ) ;

/* return with DSET address */

	return ( &ds_mrec[tbl_no] ) ;

}
/******************************************************************************	
		Dataset Table entry check 
		FUNC.NAME	: lgbn10() 
		INPUT		: pass_name	; ladataset name 
		OUTPUT		: tbl_no 	;  
******************************************************************************/
static
lbgn10 ( pass_name ) 
	char	*pass_name ;
{
	int	tbl_no ;
	int	i ;

/* free table no exist check */

	tbl_no = -1 ;
	for ( i=0 ; i<DSET_TBL_MAX ; i ++ ) {
	 	if ( ds_mrec[i].ds_pmode == 0 ) {	/* free table ? */
			tbl_no = i ;			/* --- yes */
			break ;
		}  					/* --- no */
	}
	if ( tbl_no == -1 ) return ( DE_DTBL_FULL ) ;
	return ( tbl_no ) ;
}
/******************************************************************************	
		Make Prosess Mode
		FUNC.NAME	: lgbn20() 
		INPUT		: pass_name	; ladataset name 
				: mode		; prosess mode
		OUTPUT		: pmode 	;  
******************************************************************************/
static
lbgn20 ( pass_name , mode ) 
	char	*pass_name ;
	char	*mode ;
{
	int	sts ;
	struct	stat buf ; 

/* make prosess mode  */

	buf.st_mode = 0 ;
	sts = stat ( pass_name , &buf ) ;		/* file exist check */
	/*************
		case  7563  : sts = 1 ; break ;
		case  33188 : sts = 0 ; break ;
		case  33261 : sts = 0 ; break ;
		default : goto retry ; break ;
	**************/
	if ( strcmp ( mode , "u" ) == 0 ) {		/* update mode */
		if ( sts == 0 ) return ( UPDATE ) ;
		else return ( DE_FILE_NONE ) ;
	}						
	if ( strcmp ( mode , "u+" ) == 0 ) {		/* update+ mode */
		if ( sts == 0 ) return ( UPDATE ) ;
		else return ( CREATE ) ;
	}					
	if ( strcmp ( mode , "c" ) == 0 ) {		/* create mode */
		if ( sts == 0 ) return ( DE_FILE_EXIST ) ; 
		else return ( CREATE ) ;
	}						
	if ( strcmp ( mode , "c+" ) == 0 ) {		/* create+ mode */
		return ( CREATE ) ;
	}						
	if ( strcmp ( mode , "r" ) == 0 ||		/* read all mode */
	     strcmp ( mode , "r1" ) == 0 || 		/* read Des mode */
	     strcmp ( mode , "r2" ) == 0 || 		/* read Lab mode */
	     strcmp ( mode , "r3" ) == 0 ) {  		/* read Dat mode */
		if ( sts == 0 ) return ( READ ) ;
		else return ( DE_FILE_NONE ) ;  
	} 
	return ( DE_PRM ) ; 				/* mode error */
}
/******************************************************************************	
		Lock Ladataset 	
		FUNC.NAME	: lgbn30() 
		INPUT		: pmode		; prosess mode 
				: fp		; ladataset file ptr
		OUTPUT		: error code   
******************************************************************************/
static
lbgn30 ( pmode  , fp ) 
	int	pmode ;
	FILE	*fp ;
{
	
	struct	flock lck ;

/* lock ladataset with none wait mode */

	if (pmode == UPDATE || pmode == CREATE) { 
		lck.l_type = F_WRLCK ;
		lck.l_whence = 0 ;
		lck.l_start = 0l ;
		lck.l_len = 0l ;
		if ( fcntl ( fileno(fp)  , F_SETLK , &lck ) < 0 ) {
			fclose ( fp ) ;
			return ( DE_LOCK ) ;
		}
	}
	return ( 0 ) ;
}
/******************************************************************************	
		Read Ladataset To Dynamic Memory 
		FUNC.NAME	: lgbn40() 
		INPUT		: pmode		;	
				: fp		; ladataset file ptr
		OUTPUT		: mptr		; memory pointer   
				: mgno		; memory group number   
******************************************************************************/
static
lbgn40 ( pmode , mode , fp , mptr , mgno , pass_name ) 
	int	pmode ;
	char	*mode ; 
	FILE	*fp ;
	char	**mptr ;
	int	*mgno ;
	char	*pass_name ;
{
	int	rtn ;
	int	name_ptr ;
	struct	dsm	*dp , *dum ;
	char	*part ;

/* allocate memory group no */ 

	*mgno = d3mgno () ; 	
	if ( *mgno == -1 )  return ( DE_MEM ) ;

/* read ladataset to dynamic memory */

	switch ( pmode ) {
		case UPDATE : 
						/* dataset all read */
			rtn = d3dsrd ( fp , *mgno , mptr ) ; RTNCHK ;
			break ;
		case READ   : 
			if ( strcmp ( mode , "r" ) == 0 ) {
						/* dataset all read */
				rtn = d3dsrd ( fp , *mgno , mptr ) ;RTNCHK; 
			} else {		
						/* dataset parts read */
						/* make dummy ladataset */
				name_ptr = lbgn60 ( pass_name ) ;
				rtn = d3ldmy( &pass_name[name_ptr],*mgno,mptr);
				RTNCHK;
						/* seek ladataset to "Des" */
				rtn = d3hdck ( fp ) ; RTNCHK ;
				dp = ( struct dsm * ) *mptr ;
				dp = dp->ptr.d ;
				dp++ ;
				dp++ ; 
				switch ( *(mode+1) ) {
						/* Des -> break */
					case '1' : 
						break ;
						/* Lab -> seek to "Lab" */
					case '2' : 
						dp++ ; 
						rtn = d3slsk ( fp ) ; RTNCHK ;
					 	break ;
						/* Dat -> seek to "Dat" */
					case '3' : 	
						dp++ ; 	
						dp++ ; 	
						rtn = d3slsk ( fp ) ; RTNCHK ;
						rtn = d3slsk ( fp ) ; RTNCHK ;
						break ;	
				}
						/* ladataset parts read */
				rtn = d3dsrd ( fp , *mgno , &part ) ; RTNCHK ;
						/* copy to dummy ladataset */
				dum = ( struct dsm * ) part ;
				dp->seibun = dum->seibun ; 
				dp->type = dum->type ; 
				dp->length = dum->length ;	
				dp->ptr.d=dum->ptr.d ;
			}
			break ;
		case CREATE : 
						/* make dummy ladataset */
			name_ptr = lbgn60 ( pass_name ) ;
			rtn = d3ldmy ( &pass_name[name_ptr] , *mgno , mptr ) ; 
			RTNCHK ;
			break ;
	}
	return ( 0 ) ;
}
/******************************************************************************	
		Entry to Ladataset Table  	
		FUNC.NAME	: lgbn50() 
		INPUT		: tbl_no	; ladataset table no
				: pass_name	; ladataset name
				: fp		; ladataset file ptr
				: mptr		; memory pointer   
				: mgno		; memory group number   
				: pmode		; prosess mode 
		OUTPUT		: none  
******************************************************************************/
static
lbgn50 ( pass_name , tbl_no , fp , mptr , mgno , pmode , option) 
	char	*pass_name ;
	int	tbl_no ;
	FILE	*fp ;
	char	*mptr ;
	int	mgno ;
	int	pmode ;
	int	option ;
{
	DSET	*dsp;

/* entry to ds-table */

	dsp = &ds_mrec[tbl_no] ;
	dsp->ds_pmode = pmode ;
	dsp->ds_entry = ( struct dsm* ) mptr ;
	dsp->ds_memno = mgno ;
	dsp->ds_fp = fp ;
	dsp->ds_fid = (int)fp->_file ;
	dsp->ds_option = option ;
	switch ( pmode ) {
		case UPDATE : 
		case CREATE : dsp->ds_flock = 'w' ; break ;
		case READ   : dsp->ds_flock = ' ' ; break ;
	}
	strcpy ( dsp->ds_name , pass_name ) ; 
}
/******************************************************************************	
		Get File_name start Pointer On Pass_name 
		FUNC.NAME	: lgbn60() 
		INPUT		: pass_name	; ladataset pass name
		OUTPUT		: name_ptr 	;     "	    file name pointer 
******************************************************************************/
static
lbgn60 ( pass_name ) 
	char	*pass_name ;
{
	int	i , ptr ;

	ptr = 0 ;
	for ( i=0 ; i<strlen(pass_name) ; i++ ) {/* find last '/' position */
		if ( pass_name[i] == '/' ) ptr = i ;
	}
	if ( ptr == 0 ) return ( ptr ) ;
	else return ( ptr+1 ) ;			
}
