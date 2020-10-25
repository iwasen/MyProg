/***************************************************************	
	Convert Library Error Code to Utility Error Code
	FUNC.NAME	: d3cver() 
	INPUT		: lib_err	; library error code
			: file_type	; Ladataset , index file ...
	OUTPUT		: uty_err	; utility error code
	REMARKS		:	88.01.25	S.Takahashi
***************************************************************/
#include	"d3cver.h"
#define		FILE_NUM 	16

static	char	sccsid[]="@(#)d3cver.c 1.3 88/06/12 14:48:55";

d3cver ( lib_err , file_type )
int	lib_err ;
char	*file_type ;
{	
	int	i ;
static	char	*file_tbl[FILE_NUM] = { 
				"gi" ,		/* qroup sakuin file */
				"if" ,		/* sakuin file */
				"ds" ,		/* ladataset */
				"uf" ,		/* user sakuin file */
				"ef" ,		/* setumei bu file */
				"lf" ,		/* label bu file */
				"rf" ,		/* nama data file */
				"af" ,		/* kaiseki data file */
				"df" ,		/* la data file */
				"kf" ,		/* koteiran keisiki file */
				"gf" ,		/* group sakuin edit file */
				"ue" ,		/* user sakuin edit file */
				"tf" ,		/* temporary file */
				"cf" ,		/* current file */
				"kj" ,		/* kensaku jouken file */
				"gh" } ;	/* gaibu hyougen keishiki */

	switch ( lib_err ) {

/* memory error */
 
	case	0		: return ( NOERR ) ;
	case	DE_MEM 		: return ( E_M ) ;
	case	DE_PRM 		: return ( E_P ) ;
	case	DE_IF_MEMOVR 	: return ( E_M ) ; 
	case	DE_DTBL_FULL 	: return ( E_D ) ; 
	case	DE_NONE_GRP 	: return ( E_GRP_NONE_ENTRY ) ; 
	case	DE_DSNAME 	: return ( E_DSNAME ) ; 

/* file error */

	default :
		for ( i=0 ; i<FILE_NUM ; i++ ) {
			if ( strcmp ( file_type , file_tbl[i] ) == 0 ) {
				switch ( lib_err ) {
				   case	DE_FILE_NONE 	: return ( 0x10 | i ) ;
				   case	DE_FILE_IO 	: return ( 0x20 | i ) ; 
				   case	DE_FILE_EXIST 	: return ( 0x30 | i ) ; 
				   case	DE_DS_FORMAT 	:
				   case	DE_NONE_MEM 	:
				   case	DE_NONE_DAT 	:
				   case	DE_VI_FMT 	: return ( 0x40 | i ) ; 
				   case	DE_LOCK 	: return ( 0x50 | i ) ;
				  }
		 	}
		}
		return ( E_P ) ;/* illegal library error code */
	}
}
