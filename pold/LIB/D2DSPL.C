/******************************************************************************	
		‚Ps•\¦
			(‚Ps‚Ì‚l‚`‚w‚ğ’´‚¦‚½‚ç©“®“I‚É‚b‚q‚ğo—Í‚·‚é)
		FUNC.NAME	: d2dspl() 
		INPUT		: data		; display data 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
#include	<stdio.h>
#define	CHECK_LEN	256	
static	int	pd_dsp_len ; 
static char	sccsid[]="@(#)d2dspl.c 1.2 88/06/12 14:40:25";

d2dspl ( std , data ) 
	FILE	*std ;		/* (i) stdout or file fp */
	char	*data ;		/* (i) out put data */
{
	int	i;
	int	ovr ;

	ovr = 0 ;

/* display 1 line limit check */

	for ( i=0 ; i<strlen(data) ; i++ ) {
		if ( pd_dsp_len >= CHECK_LEN ) { 	/* over limit ? */
			ovr = 1 ;			/* --- yes */
			pd_dsp_len = 0 ;
		}
		if ( data[i] == '\t' ) {		/* tab count up */
			pd_dsp_len += 8-(pd_dsp_len%80)%8 ;	 
		} else if ( data[i] == '\n' ) { 	/* CR clear count */
			pd_dsp_len = 0 ;
		} else 
			pd_dsp_len += 1 ;		/* normal data count 1*/
	}

/* if over limit then display with CR */

	if ( ovr == 1 ) fprintf ( std , "%s" , "\n" ) ;
	fprintf ( std , "%s" , data ) ; 
		
}
