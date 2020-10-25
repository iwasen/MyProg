/******************************************************************************	
		Ž²ƒ‰ƒxƒ‹‚Ì“o˜^
		FUNC.NAME	: d2axiw() 
		INPUT		: dsp		; ladataset table pointer
				: edt_fp	; edit file pointer	
		OUTPUT		: error code
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
extern	double	atof() ;
#define		EDT_ERR 	return(d3cver(DE_VI_FMT,"lf"))
#define		DBGC(x)		printf ( "x=%s\n",x)
#define		DBGN(x)		printf ( "x=%d\n",x)
#define		DBG(x)		printf ( "x\n")

static char	sccsid[]="@(#)d2axiw.c 1.3 88/06/12 14:38:23";

d2axiw ( dsp , edt_fp ) 
	DSET	*dsp ;		/* (i) */
	FILE	*edt_fp ;	/* (i) */
{
	PD_CMPREC	buf ;
	PD_CMPREC	*wap=&buf ;
	int	cmpno , fldno ;
	int	err ;
	long	offset ;
	char	word[1024] ;
	char	axin[128] ;
	int	dup_chk[3] ;
	int	i,no ;

/* initialize */

	cmpno = 0 ;
	fldno = 0 ;
	wap->cmp_rec_len = PD_CMP_HEADSIZ + 256 ; 
	wap->cmp_rec_num = 1 ;
	for ( i=0;i<3;i++ ) dup_chk[i]=0 ; 

	for ( ;; ) {

/* read "#Annn" */

		err = d3gets ( edt_fp , word ) ;
		if ( err == DE_FILE_IO ) break ;  
		if ( err != 0 ) EDT_ERR ;
		if ( strlen ( word ) > 255 ) EDT_ERR ;
		if ( strncmp ( word , PD_LAB_MARK , 2 ) != 0 ) EDT_ERR ;

/* axi no check */

		no = ( int ) word[2] & 0x0f ;
		if ( no != 1 && no != 2 && no != 3 ) EDT_ERR ; 
		else {
			if ( dup_chk[no-1] != 0 ) EDT_ERR ;
			dup_chk[no-1] = 1 ;
		}
			
		if ( word[3] == 'A' ) {

/* auto mode */

			err = axiw10 ( dsp , word , edt_fp , &cmpno ) ;
			if ( err != 0 ) return ( d3cver ( err , "lf" ) ) ;
		} else {

/* manual mode */

					/* write Axin */
			strcpy ( wap->cmp_rec_mode , "i" ) ;
			wap->cmp_rec_cmpno = cmpno ;
			wap->cmp_rec_sfno = 0;
			err = axiw20 ( word , axin ) ;
			if ( err != 0 ) EDT_ERR ; 
			strncpy ( wap->cmp_rec_data , axin , strlen(axin)+1 ) ;
			err =  d3paxi ( dsp  , wap ) ;
			if ( err != 0 ) return ( d3cver ( err , " " ) ) ; 
					/* comp no up */
			cmpno ++ ;
			fldno = 0 ;
					/* read label & write to Lab */ 
			for ( ;; ) {
				offset = ftell ( edt_fp ) ;
				err = d3gets ( edt_fp , word ) ;
				if ( err == DE_FILE_IO ) break ; 
				if ( err != 0 ) EDT_ERR ;
				if ( strlen ( word ) > 255 ) EDT_ERR ;
				if ( strncmp ( word , PD_LAB_MARK , 2 ) != 0 ) {
					strcpy ( wap->cmp_rec_mode,"if" ) ;
					wap->cmp_rec_cmpno = cmpno ;
					wap->cmp_rec_sfno = fldno ;
					strncpy ( wap->cmp_rec_data , word ,
						strlen(word)+1 ) ;
					err =  d3paxi ( dsp  , wap ) ;
					if ( err != 0 ) 
						return ( d3cver (err," ") );
					fldno ++ ;
				} else {
					fseek ( edt_fp , offset , 0 ) ;
					break ;
				}
			}
		}
	}
	return ( 0 ) ;
}
/******************************************************************************	
		Write Label by Auto Mode
			ex ) #A2A ondo%%%% 0,100,20 
		FUNC.NAME	: axiw10() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
axiw10 ( dsp , mark , edt_fp , cmpno ) 
	DSET	*dsp ;
	char	*mark ;	
	FILE	*edt_fp ;
	int	*cmpno ;
{

	PD_CMPREC	buf ;
	PD_CMPREC	*wap=&buf ;
	char	*ptr , *strchr() ;
	char	axin[10] ;
	char	word[1024] ;
	int	err ;
	int	pnum ;
	float	l,m,n,f ;
	char	ondo[256] ;
	int	fldno ;
	char	format[128] ;
	
/* read "ondo%%%" */

	err = d3gets ( edt_fp , ondo ) ;
	if ( strlen ( ondo ) > 255 ) EDT_ERR ;
	if ( err != 0 ) return ( DE_VI_FMT ) ;

/* seach & mark */

	ptr = strchr ( ondo , '%' ); 
	if ( ptr == NULL ) return ( DE_VI_FMT ) ;

/* get number of '%' */

	pnum = 0 ;
	for ( ;; ) { 
		if ( *ptr == '%' ) pnum ++ ;
		if ( *ptr == 0 ) break ;
		ptr ++ ; 
	}

/* read l,m,n */

	err = d3gets ( edt_fp , word ) ;
	if ( err != 0 ) return ( DE_VI_FMT ) ;
	l = atof ( word ) ; 
	err = d3gets ( edt_fp , word ) ;
	if ( err != 0 ) return ( DE_VI_FMT ) ;
	m = atof ( word ) ; 
	err = d3gets ( edt_fp , word ) ;
	if ( err != 0 ) return ( DE_VI_FMT ) ;
	n = atof ( word ) ; 

/* check l,m,n */
	
	if ( l>m || m-l<n ) return ( DE_VI_FMT ) ;

/* write "Axin" */
	
	wap->cmp_rec_len = PD_CMP_HEADSIZ + 256 ; 
	wap->cmp_rec_num = 1 ; 
	err = axiw20 ( mark , axin ) ;
	if ( err != 0 ) return ( DE_FILE_IO ) ;
	strcpy ( wap->cmp_rec_mode , "i" ) ;
	wap->cmp_rec_cmpno = *cmpno ;
	wap->cmp_rec_sfno = 0;
	strncpy ( wap->cmp_rec_data , axin , strlen(axin)+1 ) ;
	err =  d3paxi ( dsp  , wap ) ;
	if ( err != 0 ) return ( err ) ; 
	*cmpno += 1 ;

/* make label & write */

	fldno = 0 ;
	ptr = strchr ( ondo , '%' ) ;
	*ptr = 0 ;
				/* read label & write to Lab */ 
	strcpy ( wap->cmp_rec_mode,"if" ) ;
	sprintf ( format , "%s%s%d%s",ondo,"%0",pnum,"g" ) ;
	wap->cmp_rec_cmpno = *cmpno ;
	for ( f=l ; f<=m ; f=f+n ) {
		wap->cmp_rec_sfno = fldno ;
		sprintf ( wap->cmp_rec_data , format , f ) ;
		err =  d3paxi ( dsp  , wap ) ;
		if ( err != 0 ) 
			return ( d3cver (err," ") );
		fldno ++ ;
	}
	return ( 0 ) ;
}
/******************************************************************************	
		make "Axin" From "#An"
		FUNC.NAME	: axiw90() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
axiw20 ( mark , axin )   
	char	*mark ;
	char	*axin ;
{
	strcpy ( axin , "Axi" ) ;
	if ( strlen ( mark ) == 3 ) {
		strcpy ( &axin[3] , &mark[2] ) ;
	} else if ( strlen ( mark ) == 4 ) {
		strncpy ( &axin[3] , &mark[2] , 1 ) ;
		axin[4]=0;
	} else 
		return ( -1 ) ;
	return ( 0 ) ; 
}
