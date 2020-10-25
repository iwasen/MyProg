/***************************************************************	
	‚`‚w‚h‚Q‚Ì“o˜^
	FUNC.NAME	: d2outh() 
	INPUT		: dsp		; dataset table pointer
			: edt_fp 	; edit file pointer 
	OUTPUT		: error code
	REMARKS		:	88.02.02	S.Takahashi
***************************************************************/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d2out2.c 1.3 88/06/12 14:44:40";

d2out2 ( dsp , edt_fp ) 
DSET	*dsp ;		/* (i) */
FILE	*edt_fp ;		/* (i) */
{
	int 	err ;
	char	word[1024] ;
	int	attr ;
	char	dat[1024] ;
	int	col ;

	col = 0 ;
	for ( ;; ) {

/* read "#L" */

		err = d3gets ( edt_fp , word ) ;
		if ( err == DE_FILE_IO || 
		strcmp ( word , PD_DAT_MARK ) == 0 ) break ;
		if ( err != 0 ) return ( DE_VI_FMT ) ;
		if ( strcmp ( word , PD_DATKEY_MARK ) != 0 ) 
			return ( DE_VI_FMT ) ;

/* read keyword */

		err = d3gets ( edt_fp , word ) ;
		if ( err != 0 ) return ( DE_VI_FMT ) ;
		strcpy ( dat , word ) ;

/* read attribute */

		err = d3gets ( edt_fp , word ) ;
		if ( err != 0 ) return ( DE_VI_FMT ) ;
		switch ( word[0] ) {
			case	'L' : attr = DS_L ; break ;
			case	'R' : attr = DS_R ; break ;
			case	'I' : attr = DS_I ; break ;
			case	'C' : attr = DS_C ; break ;
			default	    : return  ( DE_VI_FMT ) ;
		}

/* insert 1 line */

		err = d3tinc ( dsp , col , attr , dat ) ;
		if ( err != 0 ) return ( err ) ;
		col ++ ;
	}
	if ( col == 0 ) return ( DE_VI_FMT ) ;
	return ( 0 ) ;
}
