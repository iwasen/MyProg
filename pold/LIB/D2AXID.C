/******************************************************************************	
		Ž²ƒ‰ƒxƒ‹‚Ì•\Ž¦
		FUNC.NAME	: d2axid() 
		INPUT		: dsp		; ladataset table pointer
				: edt_fp 	; edit file pointer
		OUTPUT		: error code
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#define		DBLQ	0x22  
static	char	buffer_s[1024] ;
#define		OUT(x,y)	sprintf(buffer_s,x,y);d2dspl(edt_fp,buffer_s)
#define		OUT3(x,y1,y2,y3)	sprintf(buffer_s,x,y1,y2,y3);d2dspl(edt_fp,buffer_s)

static char	sccsid[]="@(#)d2axid.c 1.3 88/06/12 14:38:08";

d2axid ( dsp , edt_fp ) 
	DSET	*dsp ;		/* (i) */
	FILE	*edt_fp ;	/* (i) */
{
	PD_CMPREC	buf ;
	PD_CMPREC	*wap=&buf ;
	int	cmp , fld ;
	int	cmp_num , fld_num ;
	int	err ;
	char	mark[128] ;

/* get comp number on Des */

	cmp_num = d3axin ( dsp ) ;
	if ( cmp_num < 0 ) return ( d3cver ( cmp_num , "ef" ) ) ;

/* display all comp name & field name */

	OUT ( "%s","\n" ) ;
	for ( cmp=1 ; cmp<=cmp_num ; cmp++ ) {
					/* read 1 comp name */
		wap->cmp_rec_len = PD_CMP_HEADSIZ + 256 ; 
		strcpy ( wap->cmp_rec_mode , "r" ) ;
		wap->cmp_rec_cmpno = cmp ;
		wap->cmp_rec_sfno = 0 ;
		err = d3paxi ( dsp , wap ) ;
		if ( err != 0 ) return ( d3cver ( err , "ef" ) ) ;
					/* display 1 comp name */
		axid10 ( wap->cmp_rec_data , mark ) ;
		OUT ( "%s\t", mark ) ;
					/* read field number on comp */
		fld_num = d3axfn ( dsp , cmp ) ;
		if ( fld_num < 0 ) return ( d3cver ( err , "ef" ) ) ;
		for ( fld=1 ; fld<=fld_num ; fld++ ) {
					/* read 1 field name */
			wap->cmp_rec_len = PD_CMP_HEADSIZ + 256 ; 
			strcpy ( wap->cmp_rec_mode , "rf" ) ;
			wap->cmp_rec_cmpno = cmp ;
			wap->cmp_rec_sfno = fld ;
			wap->cmp_rec_efno = fld ;
			err = d3paxi ( dsp , wap ) ;
			if ( err != 0 ) return ( d3cver ( err , "ef" ) ) ;
					/* display 1 field name */
			OUT ( "%s","\t" ) ;
			OUT3 ( "%c%s%c",DBLQ,wap->cmp_rec_data,DBLQ ) ;
		}
		OUT ( "%s","\n" ) ;
	}
	return ( 0 ) ;
}
/******************************************************************************	
		Make "#A2" From "Axi2"
		FUNC.NAME	: axid10() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
axid10 ( axin , mark ) 
	char	*axin ;
	char	*mark ;
{
	strcpy ( mark , PD_LAB_MARK ) ;
	strcat ( mark , &axin[3] ) ;
}
