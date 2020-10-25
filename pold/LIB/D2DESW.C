/******************************************************************************	
		à–¾•”‚Ì“o˜^
		FUNC.NAME	: d2desw() 
		INPUT		: dsp		; ladataset table pointer
				: edt_fp	; edit file pointer	
		OUTPUT		: error code
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
#include	"stdio.h"
#include	"la_ws/include/d3libc.h"
#define		EDT_ERR 	return(d3cver(DE_VI_FMT,"ef"))
#define		DBGC(x)		printf("x=%s\n",x)

static char	sccsid[]="@(#)d2desw.c 1.3 88/06/12 14:39:41";

d2desw ( dsp , edt_fp ) 
	DSET	*dsp ;		/* (i) */
	FILE	*edt_fp ;	/* (i) */
{
	PD_CMPREC	buf ;
	PD_CMPREC	*wap=&buf ;
	int	cmpno , fldno ;
	int	err ;
	long	offset ;
	char	word[1024] ;

	cmpno = 0 ;
	fldno = 0 ;
	wap->cmp_rec_len = PD_CMP_HEADSIZ + 256 ; 
	wap->cmp_rec_num = 1 ; 

	for ( ;; ) {
					/* read "#K" */
		err = d3gets ( edt_fp , word ) ;
		if ( err == DE_FILE_IO ) break ;  
		if ( err != 0 ) EDT_ERR ;
		if ( strlen ( word ) > 255 ) EDT_ERR ;
		if ( strcmp ( word , PD_KEY_MARK ) != 0 ) EDT_ERR ;
					/* read keyword */
		err = d3gets ( edt_fp , word ) ;
		if ( err != 0 ) return ( d3cver ( err , "ef" ) ) ;
		if ( strlen ( word ) > 255 ) EDT_ERR ;
					/* write keyword */
		strcpy ( wap->cmp_rec_mode , "i" ) ;
		wap->cmp_rec_cmpno = cmpno ;
		wap->cmp_rec_sfno = 0;
		strncpy ( wap->cmp_rec_data , word , strlen(word)+1 ) ;
		err =  d3pdes ( dsp  , wap ) ;
		if ( err != 0 ) return ( d3cver ( err , " " ) ) ; 
					/* comp no up */
		if ( strlen ( word ) > 255 ) EDT_ERR ;
		cmpno ++ ;
		fldno = 0 ;
					/* read key-value & write */
		for ( ;; ) {
			offset = ftell ( edt_fp ) ;
			err = d3gets ( edt_fp , word ) ;
			if ( strlen ( word ) > 255 ) EDT_ERR ;
			if ( err == DE_FILE_IO ) break ; 
			if ( err != 0 ) EDT_ERR ;
			if ( strcmp ( word , PD_KEY_MARK ) != 0 ) {
				strcpy ( wap->cmp_rec_mode , "if" ) ;
				wap->cmp_rec_cmpno = cmpno ;
				wap->cmp_rec_sfno = fldno ;
				strncpy(wap->cmp_rec_data,word,strlen(word)+1);
				err =  d3pdes ( dsp  , wap ) ;
				if ( err != 0 ) return ( d3cver (err," ") );
				fldno ++ ;
			} else {
				fseek ( edt_fp , offset , 0 ) ;
				break ;
			}
		}
	}
	return ( 0 ) ;
}
