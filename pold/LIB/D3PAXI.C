/***************************************************************
	Process  of "Lab" On memory 
	Process is Read , Insert , Delete , Update 
	FUNC.NAME	: d3paxi() 
	INPUT		: ds_id 	; dataset id  
			  wap		; "Lab","Lab"'s I/F area
	OUTPUT	 	: error code  
	REMARKS		:	88.01.16	S.Takahashi
****************************************************************/
#include	"d3paxi.h"
#define		DBG(x)		printf ( "x\n" )
extern	char	*d3allc() ;
extern	int	d3inda() ;

static	char	sccsid[]="@(#)d3paxi.c 1.2 88/06/12 14:54:44";

d3paxi ( ds_id , wap ) 
DSET	*ds_id ;
PD_CMPREC	*wap ; 
{
	
/* branch for process */

	if ( strcmp ( wap->cmp_rec_mode , "r" ) == 0 ) {
		return ( paxi10 ( ds_id , wap ) ) ;	/* read record */
	} else if ( strcmp ( wap->cmp_rec_mode , "rf" ) == 0 ) {
		return ( paxi20 ( ds_id , wap ) ) ;	/* read fields */
	} else if ( strcmp ( wap->cmp_rec_mode , "i" ) == 0 ) {
		return ( paxi30 ( ds_id , wap ) ) ;	/* insert record */
	} else if ( strcmp ( wap->cmp_rec_mode , "if" ) == 0 ) {
		return ( paxi40 ( ds_id , wap ) ) ;	/* insert fields */
	} else if ( strcmp ( wap->cmp_rec_mode , "d" ) == 0 ) {
		return ( paxi50 ( ds_id , wap ) ) ;	/* delete record */
	} else if ( strcmp ( wap->cmp_rec_mode , "df" ) == 0 ) {
		return ( paxi60 ( ds_id , wap ) ) ;	/* delete fields */
	} else if ( strcmp ( wap->cmp_rec_mode , "u" ) == 0 ) {
		return ( paxi70 ( ds_id , wap ) ) ;	/* update record */
	} else if ( strcmp ( wap->cmp_rec_mode , "uf" ) == 0 ) {
		return ( paxi80 ( ds_id , wap ) ) ;	/* update fields */
	} else {  
		return ( DE_PRM ) ;
	}
}
/*******************************************************************************
		Read record  
		FUNC.NAME	: paxi10 () 
		INPUT		: ds_id 	; dataset id  
				  wap		; "Lab","Lab"'s I/F area
		OUTPUT	 	: error code  
		REMARKS		:	87.12.24	S.Takahashi
*******************************************************************************/
static
paxi10 ( ds_id , wap )  
	DSET	*ds_id ;
	PD_CMPREC	*wap ;
{
	int	mem_cmpno ;
	int 	cmpname_len ;	
	struct	dsm	*dp ;	
	int	i;

/* parameter check */

	mem_cmpno = d3axin ( ds_id ) ;
	if ( mem_cmpno == 0 ) return ( DE_NONE_DAT ) ;
	if ( mem_cmpno < 0 ) return ( mem_cmpno ) ;
	if ( wap->cmp_rec_cmpno < 1 || wap->cmp_rec_cmpno > mem_cmpno ) 
		return ( DE_NONE_DAT ) ;

/* clear data */

	for ( i=0 ; i<wap->cmp_rec_len - PD_CMP_HEADSIZ ; i ++ ) 
		wap->cmp_rec_data[i] = NULL ;

/* read cmp name */

	dp = ds_id->ds_entry ;
	dp = dp->ptr.d ;
	dp = dp[3].ptr.d ;
	cmpname_len = strlen  ( dp[wap->cmp_rec_cmpno+1].seibun ) + 1; 
	if ( cmpname_len >
 	wap->cmp_rec_len - PD_CMP_HEADSIZ ) { 
		wap->cmp_rec_efno = 0 ;
		return ( DE_IF_MEMOVR ) ;
	} 
	strcpy ( wap->cmp_rec_data , dp[wap->cmp_rec_cmpno+1].seibun ) ;
 	wap->cmp_rec_num = 1 ;

/* read fields name */

	if ( wap->cmp_rec_sfno == 0 ) { 
		return ( 0 ) ; 	/* no request */
	} else { 
		wap->cmp_rec_sfno = 1 ;
		wap->cmp_rec_efno = d3axfn ( ds_id , wap->cmp_rec_cmpno ) ;
	}
	return ( d3rdda ( "Lab" , ds_id , 
				wap->cmp_rec_cmpno ,
				wap->cmp_rec_sfno , 
				&wap->cmp_rec_efno , 
				wap->cmp_rec_len-PD_CMP_HEADSIZ-cmpname_len, 
				&wap->cmp_rec_data[cmpname_len] , 
				&wap->cmp_rec_num )  ) ;  
}

/*******************************************************************************
		Read Fields 
		FUNC.NAME	: paxi20 () 
		INPUT		: ds_id 	; dataset id  
				  wap		; "Lab","Lab"'s I/F area
		OUTPUT	 	: error code  
		REMARKS		:	87.12.24	S.Takahashi
*******************************************************************************/
static
paxi20 ( ds_id , wap )  
	DSET	*ds_id ;
	PD_CMPREC	*wap ;
{
	int	mem_cmpno ;
	int	i ;

/* parameter check */

	mem_cmpno = d3axin ( ds_id ) ;
	if ( mem_cmpno == 0 ) return ( DE_NONE_DAT ) ;
	if ( mem_cmpno < 0 ) return ( mem_cmpno ) ;
	if ( wap->cmp_rec_cmpno < 1 || wap->cmp_rec_cmpno > mem_cmpno ) 
		return ( DE_NONE_DAT ) ;

/* clear data */

	for ( i=0 ; i<wap->cmp_rec_len - PD_CMP_HEADSIZ ; i ++ ) 
		wap->cmp_rec_data[i] = NULL ;

/* read fields name */
	
	wap->cmp_rec_num = 0 ; 
	return ( d3rdda ( "Lab" , ds_id , 
				wap->cmp_rec_cmpno ,
				wap->cmp_rec_sfno , 
				&wap->cmp_rec_efno , 
				wap->cmp_rec_len - PD_CMP_HEADSIZ , 
				&wap->cmp_rec_data[0] , 
				&wap->cmp_rec_num )  ) ;  
}
/*******************************************************************************
		Insert Reocrd 
		FUNC.NAME	: paxi30 () 
		INPUT		: ds_id 	; dataset id  
				  wap		; "Lab","Lab"'s I/F area
		OUTPUT	 	: error code  
		REMARKS		:	87.12.24	S.Takahashi
*******************************************************************************/
static
paxi30 ( ds_id , wap ) 
	DSET	*ds_id ;
	PD_CMPREC	*wap ;
{
	
	struct	dsm	*dp , *strp , *crnt_strp , *new_strp ;
	int	axi_tra_suu ;
	int	axi_suu ;
	int	new_cmpno ;
	int	i , n ;
	char	*seibun_ptr ;
	int	cmpname_len ;

/* cmpno check */

	axi_suu = d3axin ( ds_id ) ;
	if ( axi_suu < 0 ) return ( axi_suu ) ;
	if ( wap->cmp_rec_cmpno < 0 || 
		wap->cmp_rec_cmpno > axi_suu ) return ( DE_NONE_DAT ) ;

/* move memory pointer to "Lab" field */
 	
	dp = ds_id -> ds_entry ;
	dp = dp -> ptr.d ;
	axi_tra_suu = dp[3].length ;

/* insert cmp */

	if ( axi_tra_suu == 0 ) {
		dp[3].type = DS_STR ;
		dp[3].length = 0 ;
		strp = ( struct dsm * ) d3allc 	
			( ds_id->ds_memno , ( sizeof ( struct dsm ) ) * 3 ) ;
		dp[3].ptr.d = strp ; 
		strp -> seibun = 0 ;
		strp -> type = DS_DIR ;
		strp -> length = 0 ;
		strp -> ptr.s = d3allc ( ds_id->ds_memno,strlen ("Lab") + 1 ) ;
		strncpy ( strp->ptr.s , "Lab" , 4 ) ;
		strp ++ ;
		strp -> seibun = 0 ;
		strp -> type = 10 ;		/* ? */
		strp -> length = 0 ;
		*(int *)&strp->ptr = 0 ;
		strp ++ ;
		seibun_ptr = d3allc 
			( ds_id->ds_memno , strlen(wap->cmp_rec_data)+1 ) ;
		strcpy ( seibun_ptr , wap->cmp_rec_data ) ;
		strp -> seibun = seibun_ptr ;
		strp -> type = DS_C ;
		strp -> length = 0 ;
		*(int *)&strp->ptr = 0 ;
	} else { 
		n = d3gsiz ( axi_tra_suu ) ;
		if ( n - axi_tra_suu >= 1 ) {
		} else {
			new_cmpno = d3gsiz ( axi_tra_suu + 1 ) + 2 ;
			new_strp = ( struct dsm * ) d3allc 
			( ds_id->ds_memno,(sizeof(struct dsm))*(new_cmpno) );
			crnt_strp = dp[3].ptr.d ;	
			dp[3].ptr.d = new_strp ;
			for ( i=1 ; i<=axi_tra_suu+2 ; i++ ) {
				new_strp->seibun = crnt_strp->seibun ; 
				new_strp->type = crnt_strp->type ; 
				new_strp->length = crnt_strp->length ; 
				new_strp->ptr = crnt_strp->ptr ; 
				new_strp++ ; crnt_strp++ ;
			}
	 	}
	}

/* cmp namber count up */

	dp[3].length = dp[3].length + 1 ;
	dp = dp[3].ptr.d ;			/* move ptr */
	dp[0].length += 1 ;
	dp[1].length += 1 ;

/* make insert area */

	if ( axi_tra_suu == 0 || wap->cmp_rec_cmpno == axi_tra_suu ) {
	} else {
		for ( i=2+axi_tra_suu-1 ; i>=wap->cmp_rec_cmpno+2 ; i-- ) {
			dp[i+1].seibun = dp[i].seibun ;
			dp[i+1].type = dp[i].type ;
			dp[i+1].length = dp[i].length ;
			dp[i+1].ptr = dp[i].ptr ;
		}
	}

/* insert cmp */

	if ( axi_tra_suu != 0 ) {
		seibun_ptr = d3allc 
			( ds_id->ds_memno,strlen ( wap->cmp_rec_data ) + 1 );
		if ( seibun_ptr == NULL ) return ( DE_MEM ) ;
		strncpy ( seibun_ptr , wap->cmp_rec_data , 
				strlen ( wap->cmp_rec_data ) + 1 ) ;  
		dp[wap->cmp_rec_cmpno+2].seibun = seibun_ptr ;
		dp[wap->cmp_rec_cmpno+2].type = DS_C ;
		dp[wap->cmp_rec_cmpno+2].length = 0 ;
		*(int *)&dp[wap->cmp_rec_cmpno+2].ptr = 0 ;
	}

/* insert field */
	
	if ( wap->cmp_rec_sfno == 0 ) return ( 0 ) ;	/* no request ? */
	cmpname_len = strlen ( wap->cmp_rec_data ) + 1 ;
	return ( d3inda ( "Lab" , ds_id , 
				wap->cmp_rec_cmpno + 1 ,
				0 ,
				wap->cmp_rec_num -1 ,
				&wap->cmp_rec_data[cmpname_len] ) )  ;

}
/*******************************************************************************
		Insert Fields	
		FUNC.NAME	: paxi40 () 
		INPUT		: ds_id 	; dataset id  
				  wap		; "Lab","Lab"'s I/F area
		OUTPUT	 	: error code  
		REMARKS		:	87.12.24	S.Takahashi
*******************************************************************************/
static
paxi40 ( ds_id , wap ) 
	DSET	*ds_id ;
	PD_CMPREC	*wap ;
{
	int	crnt_cmpno ;
	int	crnt_fno ;

/* parameter check */

	crnt_cmpno = d3axin ( ds_id ) ;		/* cmp no hani check */
	if ( crnt_cmpno == 0 ) return ( DE_NONE_DAT ) ;
	if ( crnt_cmpno < 0 ) return ( crnt_cmpno ) ;
	if ( wap->cmp_rec_cmpno < 1 || wap->cmp_rec_cmpno > crnt_cmpno ) 
		return ( DE_NONE_DAT ) ;
						/* field no hani check */
	crnt_fno = d3axfn ( ds_id , wap->cmp_rec_cmpno ) ;
	if ( wap->cmp_rec_sfno < 0 ||
		wap->cmp_rec_sfno > crnt_fno ) return ( DE_NONE_DAT ) ;

/* insert fields name */
	
	return ( d3inda ( "Lab" , ds_id , 
				wap->cmp_rec_cmpno ,
				wap->cmp_rec_sfno ,
				wap->cmp_rec_num  ,
				wap->cmp_rec_data ) )  ;

}
static
paxi50 () { return ( DE_PRM ) ; }		/* NO SUPORTED FUNCTION */
static
paxi60 () { return ( DE_PRM ) ; }		/* NO SUPORTED FUNCTION */
static
paxi70 () { return ( DE_PRM ) ; }		/* NO SUPORTED FUNCTION */
static
paxi80 () { return ( DE_PRM ) ; }		/* NO SUPORTED FUNCTION */
