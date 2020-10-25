/***************************************************************	
	表形式データセットの登録	
	FUNC.NAME	: d2outh() 
	INPUT		: dsp		; dataset table pointer 
			: edt_fp	; edit file pointer
	OUTPUT		: error code
	REMARKS		:	88.02.02	S.Takahashi
***************************************************************/
#include	"d2outh.h"
extern	char	*malloc() ;
extern	double	atof() ;

static	char	sccsid[]="@(#)d2outh.c 1.3 88/06/12 14:44:53";

d2outh ( dsp , edt_fp )
DSET	*dsp ;
FILE	*edt_fp ;
{
	T_TEMPLETE	*tmp ;
	int	key_num ;
	int	rtn ;
	int	max_len ;

	(char *) tmp = NULL ;

/* error handling */

	rtn = setjmp ( pd_outh_env ) ;
	if ( rtn != 0 ) {
		outh90 ( tmp ) ;
		return ( rtn ) ;
	}

/* initialize */

	outh10 ( dsp , &tmp , &key_num ) ;

/* check edit file */
	
	outh15 ( edt_fp , tmp , key_num , &max_len ) ;

/* create "Lab" and "Dat" */

	outh20 ( dsp , edt_fp , tmp , key_num , max_len ) ;

/* end prosess */
	
	outh90 ( tmp ) ;
	return ( 0 ) ;
}
/******************************************************************************	
		Initialize 	
		FUNC.NAME	: outh10() 
		INPUT		: dsp		; dataset table pointer 
				: tmp		; templete 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
outh10 ( dsp , tmp , key_num ) 
	DSET	*dsp ;		/* (i) dataset table pointer */
	T_TEMPLETE	**tmp ;	/* (o) template buffer */
	int	*key_num ;	/* (o) number of keyword */
{
	char	**keyword ;
	int	err ;
	int	i ;

/* get number of keyword */

	err = d3tkey ( dsp , key_num , &keyword ) ;
	if ( err != 0 ) ERR ( err ) ;

/* allocate tmplete buffer */

	*tmp = ( T_TEMPLETE *) malloc ( sizeof ( T_TEMPLETE ) +
				sizeof ( TEMPLETE ) * *key_num ) ;
	if ( *tmp == NULL ) ERR ( DE_MEM ) ;
	(*tmp)->tp_cnum = *key_num ;
	for ( i=0 ; i<*key_num ; i++ ) ((*tmp)->tp_templete[i]).tp_col = i+1 ;

/* make templete */

	err = d3mktp ( dsp , *tmp ) ;
	if ( err != 0 ) ERR ( err ) ;

}
/******************************************************************************	
	 	Check edit file format  	
		FUNC.NAME	: outh15() 
		INPUT	 	: edt_fp 	; edit file pointer
				: tmp		; templete 
				: key_num 	; 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
outh15 ( edt_fp , tmp , key_num , max_len ) 
	FILE	*edt_fp ;		/* (i) edit file pointer */
	T_TEMPLETE	*tmp ;		/* (i) */
	int	key_num ;		/* (i) */
	int	*max_len ;		/* max length of 1 line's data */
{
	char	word[1024] ;
	long	offset ;
	int	len ;
	int	err ;
	int	i ;

/* save current pointer */

	offset = ftell ( edt_fp ) ;

/* format check & make max length */

	*max_len = 0 ;
	for ( ;; ) {
		len = 0 ;			/* current line's length=0*/
						/* read 1 word from edit file*/
		err = d3gets ( edt_fp , word ) ;
		if ( err == DE_FILE_IO ) break ; /* eof ? */
		if ( err != 0 ) ERR ( err ) ;
						/* # mark check */
		if ( strncmp ( word ,PD_DAT_MARK , 2 ) != 0 ) ERR ( DE_VI_FMT );
						/* read word number of key_num*/
		for ( i=0 ; i<key_num ; i++ ) {
						/* read 1 word */
			err = d3gets ( edt_fp , word ) ;
			if ( err == DE_FILE_IO ) ERR ( DE_VI_FMT ) ; /* eof ? */
			if ( err != 0 ) ERR ( err ) ;
						/* data format check */ 
			switch ( (tmp->tp_templete[i]).tp_mode ) {
				case DS_L :
					if ( strcmp ( word , "T" ) != 0 &&
					strcmp ( word , "F" ) != 0 &&
					strcmp ( word , "NA" ) != 0 ) 
						ERR ( DE_VI_FMT ) ;
					len += sizeof ( int ) ;   
					break ;
				case DS_I :  
				case DS_R :
					len += sizeof ( int ) ;
					break ;
				case DS_C :
					len += strlen ( word ) + 1 ;
					break ;
				default :
					ERR ( DE_VI_FMT ) ;
			}
		}					
		if ( len > *max_len ) *max_len = len ;/* max length check */
	}

/* reset file pointer */

	fseek ( edt_fp , offset , 0 ) ;
}
/******************************************************************************	
	 	Create "Lab" and "Dat"   	
		FUNC.NAME	: outh20() 
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
static
outh20 ( dsp , edt_fp , tmp , key_num , max_len ) 
	DSET	*dsp ;		/* (i) */
	FILE	*edt_fp ;	/* (i) */
	T_TEMPLETE	*tmp ;	/* (i) */
	int	key_num ;	/* (i) */
	int	max_len ;	/* (i) */
{
	char	*dat ;
	int	ptr ;
	int	line ;
	int	i ;
	int	err ;
	char	word[1024] ;

/* allocate data buffer */

	tmp->tp_bufp = malloc ( max_len ) ;
	if ( tmp->tp_bufp == NULL ) ERR ( DE_MEM ) ; 
	dat = tmp->tp_bufp ;

/* save datat to templete & create */

	line = 0 ;			/* insert line = 0 */
	for ( ;; ) {
		ptr = 0 ;		/* templete data buffer offset = 0 */
					/* dummy read mark ( #D ) */
		err = d3gets ( edt_fp , word ) ;
		if ( err == DE_FILE_IO ) break ;
		if ( err != 0 ) ERR ( err ) ;
					/* read number of key_num */
		for ( i=0 ; i<key_num ; i++ ) {
					/* read data */
			err = d3gets ( edt_fp , word ) ;
			if ( err != 0 ) ERR ( DE_VI_FMT ) ;
					/* set offset to templete */
			(tmp->tp_templete[i]).tp_offset = ptr ;
					/* set data with type */
			outh21 ( word , &dat[ptr] , &ptr , 
				(tmp->tp_templete[i]).tp_mode ) ;
		}

/* insert 1 line */

		err = d3tin ( dsp , line , 1 , tmp ) ;
		if ( err != 0 ) ERR ( err ) ;
		line ++ ;		/* increment line */
	}
}
/******************************************************************************	
		Edit Data & Save template buffer 
		FUNC.NAME	: outh21() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
outh21 ( word , dat , ptr , type ) 
	char	*word ; 		/* save data ( ascii ) */
	char	*dat ; 			/* save buffer  */
	int	*ptr ;			/* save buffer pointer */
	int	type ;			/* save data type */
{
	switch ( type ) {

/* logical */
		case DS_L :
			if ( strcmp ( word , "T" ) == 0 ) {
				*(int *)dat = 1 ;
			} else if ( strcmp (word,"F") == 0 ) {
				*(int *)dat = 0 ;
			} else { 
				*(int *)dat = PD_NA ;
			}
			*ptr += sizeof ( int ) ;
			break ;

/* integer */

		case DS_I :
			if ( strcmp ( word , "NA" ) != 0 ) 
				*(int *)dat = atoi (word) ;
			else 	*(int *)dat = PD_NA ;
			*ptr += sizeof ( int ) ;
			break ;

/* real */

		case DS_R : 
			if ( strcmp ( word , "NA" ) != 0 ) 
				*(float *)dat = atof(word);
			else 	*(int *)dat = PD_NA ;
			*ptr += sizeof ( float ) ;
			break ;

/* caharcter */

		case DS_C :
			strncpy ( dat , word , 
				strlen ( word ) + 1 ) ;
			*ptr += strlen ( word ) + 1 ;
			break ;
	}
}
/******************************************************************************	
		End prosess	
		FUNC.NAME	: outh90() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
outh90 ( tmp )  
	T_TEMPLETE	*tmp ;			/* (o) template */
{
	if ( (char *) tmp != NULL ) {
		if ( tmp->tp_bufp != NULL ) free ( tmp->tp_bufp ) ;
		free ( (char *) tmp ) ;
	}
}
