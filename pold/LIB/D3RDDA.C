/***************************************************************
	Read Fields From "Des" or "Axi" On memory 
	FUNC.NAME	: d3rdda() 
	INPUT		: kubun 	; Des or Lab
			  ds_id 	; dataset id  
			  cmpno 	; seibun no 
			  sfno  	; request start field no 
			  efno 		; request end field no 
			  flen 		; limit of field length 
			  fnum 		; field numver 
	OUTPUT		: efno		; acutual end field no
		 	: field		; field buffer ( asciiz ) 
			  fnum 		; field numver 
	REMARKS		:	87.12.22	S.Takahashi
******************************************************************************/
#include	"d3rdda.h"

static	char	sccsid[]="@(#)d3rdda.c 1.2 88/06/12 14:55:45";

d3rdda ( kubun , ds_id , cmpno , sfno , efno , flen , field , fnum ) 
char	*kubun ;
DSET	*ds_id ;
int	cmpno ;
int	sfno ;
int	*efno ;
int	flen ;
char	*field ;
int	*fnum ;
{
	struct	dsm	*dp ;
	int	mem_fnum ;			/* numver of field on memory*/
	char	**mem_fptr ;			/* field pointer */	
	int	mem_flen ;			/* read fields length */
	int	i ;
	int	one_len	;			/* length of one field */

/* move memory pointer to "Des" or "Lab" field */

	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;
	if ( strcmp ( kubun , "Des" ) == 0 )  {
		dp = dp[2].ptr.d ;		/* move to "Des" */
	} else {
		dp = dp[3].ptr.d ;		/* move to "Lab" */
	}
	mem_fnum = dp[cmpno+1].length ;		 
	if ( mem_fnum == 0 ) return ( DE_NONE_DAT ) ;/* not exist fields */
	mem_fptr = dp[cmpno+1].ptr.c ;

/* read field and save prameter */

	mem_flen = 0 ;
	for ( i=sfno-1 ; i<=*efno-1 ; i++ ) {
		if ( i> mem_fnum-1 ) { 			/* illegal field no */
			*efno = i ;
			return ( DE_NONE_DAT );		/* over memory */
		}
		one_len = strlen ( mem_fptr[i] ) ;	/* get one field len */
		if ( mem_flen+one_len+1 > flen ) { 	/* save ok ? */
			*efno = i ;
			return ( DE_IF_MEMOVR );	/* ------- no */
 		} else { 
							/* ------- yes */
			strncpy ( &field[mem_flen],mem_fptr[i],one_len ) ;	
			mem_flen = mem_flen + one_len + 1 ; /* +1 = NULLlength*/
			*fnum  = *fnum + 1 ;
		}
	}
	return ( 0 ) ;
}
