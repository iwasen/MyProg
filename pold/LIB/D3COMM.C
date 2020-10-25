/***************************************************************	
	Write Des's Comm
	FUNC.NAME	: d3comm() 
	INPUT		: dsp		; ladataset table pointer
			: comment	
	OUTPUT		: error code
	REMARKS		:	88.02.02	S.Takahashi
***************************************************************/
#include	"stdio.h"
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="@(#)d3comm.c 1.3 88/06/12 14:48:27";

d3comm ( dsp , comment ) 
DSET	*dsp ;		/* (i) */
char	*comment ;	/* (i) */
{
	PD_CMPREC	buf ;
	PD_CMPREC	*wap=&buf ;
	char	*cmt = "Comm" ;

/* "Comm" = comment */
	
	wap->cmp_rec_len = 256 ; 
	strncpy ( wap->cmp_rec_mode , "i" , 2 ) ;
	wap->cmp_rec_cmpno = 0 ;
	wap->cmp_rec_sfno = 1;
	wap->cmp_rec_num = 2;
	strncpy ( wap->cmp_rec_data , cmt , strlen(cmt)+1 ) ;
	strncpy ( &wap->cmp_rec_data[strlen(cmt)+1] , comment , 
						strlen ( comment ) + 1 ) ;
	return ( d3pdes ( dsp  , wap ) ) ;
}
