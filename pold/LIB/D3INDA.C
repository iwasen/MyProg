/****************************************************************
	Insert Fields To "Des" or "Axi" On memory 
	FUNC.NAME	: d3inda() 
	INPUT		: kubun 	; Des or Lab
			  ds_id 	; dataset id  
			  cmpno 	; seibun no 
			  ino  		; insert start no ( 0-> ) 
			  inum		; insert fields number 
			  ifiled 	; insert fields 
	OUTPUT		: return code	; error code 
	REMARKS		:	87.12.25	S.Takahashi
****************************************************************/
#include	"d3inda.h"
#define		DBG(x)		printf("x/n")
extern	char	*d3allc() ;

static	char	sccsid[]="@(#)d3inda.c 1.2 88/06/12 14:53:04";

d3inda ( kubun , ds_id , cmpno , ino , inum , ifield ) 
char	*kubun ;
DSET	*ds_id ;
int	cmpno ;
int	ino ;
int	inum ;
char	ifield[] ;
{
	struct	dsm	*dp ;
	int	crnt_fnum ;
	int	new_fnum ;
	char	**crnt_fptr ;
	char	**new_fptr ;
	int	i ;
	int	n ;
	int	ifield_ptr ;
	char	*one_fptr ;

/* read curent fields number */
 
	if ( strcmp ( kubun , "Des" ) == 0 ) {	
		crnt_fnum = d3defn ( ds_id , cmpno ) ;
	} else {
		crnt_fnum = d3axfn ( ds_id , cmpno ) ;
	}
	if ( crnt_fnum < 0 ) return ( crnt_fnum ) ;	/* error ret */
 
/* move pointer to "Des" , "Lab" */

	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;
	if ( strcmp ( kubun , "Des" ) == 0 ) {
		dp = dp[2].ptr.d ;
	} else {
		dp = dp[3].ptr.d ;
	}

/* allocate memory */

	n = d3gsiz ( crnt_fnum ) ;
	if ( n - crnt_fnum >= inum ) {
	} else {
		new_fnum = d3gsiz ( crnt_fnum + inum ) ;
		new_fptr = 
		( char ** ) d3allc ( ds_id->ds_memno,new_fnum*sizeof(char*) ) ;
		if ( new_fptr == 0 ) return ( DE_MEM ) ;
		if ( crnt_fnum > 0 ) { 		/* crnt fields copy to new */
			crnt_fptr = dp[cmpno+1].ptr.c ;
			for ( i=0 ; i <crnt_fnum ; i ++ ) 
				new_fptr[i] = crnt_fptr[i] ;
		}
		dp[cmpno+1].ptr.c = new_fptr ;
	}
	crnt_fptr = dp[cmpno+1].ptr.c ;

/* make insert eara */
	
	if ( crnt_fnum == 0 || ino == crnt_fnum ) {
	} else {
		for ( i=crnt_fnum-1 ; i>=ino ; i -- ) {
			crnt_fptr[i+inum] = crnt_fptr[i] ;
		}
	}

/* insert fileds */

	ifield_ptr = 0 ;
	for ( i=ino ; i<= ino + inum - 1 ; i++ ) {
		one_fptr = d3allc 
			( ds_id->ds_memno,strlen(&ifield[ifield_ptr])+1 );
		if ( one_fptr == 0 ) return ( DE_MEM ) ;
		strcpy ( one_fptr , &ifield[ifield_ptr] ) ;
		crnt_fptr[i] = one_fptr ; 	
		ifield_ptr = ifield_ptr + strlen ( &ifield[ifield_ptr] ) + 1;
	}

/* set type */ 					/* 880204*/

	if ( crnt_fnum == 0 ) dp[cmpno+1].type = DS_C ;  

/* field length up */

	dp[cmpno+1].length += inum ;
  	return ( 0 ) ;
}
