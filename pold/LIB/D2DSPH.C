/******************************************************************************	
		表形式データセットの表示
		FUNC.NAME	: d2dsph() 
		INPUT		: dsp  
				: kubun		; "all" display all 
						; "hed" header only 	
		OUTPUT		: error code
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
#include	"d2dsph.h"
extern	char	*malloc() ;
static	char	print_s[1024];
#define	PRT(x,y)	sprintf(print_s,x,y);d2dspl(stdout,print_s); 
#define	PRT3(x,y1,y2,y3)	sprintf(print_s,x,y1,y2,y3);d2dspl(stdout,print_s); 

static char	sccsid[]="@(#)d2dsph.c 1.2 88/06/12 14:40:10";

d2dsph ( dsp , kubun )
	DSET	*dsp ;
	char	*kubun ;
{
	T_TEMPLETE	*tmp ;
	char	**key_ptr ;
	int	key_num ;
	int	rtn ;
	
	( char * ) tmp = NULL ;

/* error handling */

	rtn = setjmp ( pd_dsph_env ) ;
	if ( rtn != 0 ) {
		dsph90 ( tmp ) ;
		return ( rtn ) ;
	}
	
/* output header  */

	d2head ( 1 , stdout ) ;

/* request is output data ? */

	if ( strcmp ( kubun ,"hed" ) == 0 ) return ( 0 ) ;

/* get keyword pointer */	

	dsph10 ( dsp , &key_ptr , &key_num ) ;	 
	if ( key_num == 0 ) return ( 0 ) ; 

/* make template */

	dsph20 ( dsp , &tmp , key_num ) ;

/* output header */

	dsph25 ( key_ptr , key_num ) ;

/* output keyword all (#L) */
	
	PRT ( "%s","\n" ) ;
	dsph30 ( key_ptr , key_num , tmp ) ;

/* output data (#D) */
	
	PRT ( "%s","\n" ) ;
	dsph40 ( dsp , tmp ) ;	

/* end prosess */

	dsph90 ( tmp ) ;
	return ( 0 ) ;
}
/******************************************************************************	
		Get Keyword Pointer 
		FUNC.NAME	: dsph10() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
dsph10 ( dsp , key_ptr , key_num ) 
	DSET	*dsp ;		/* (i) pointer of ladataset table */
	char	**key_ptr[] ;	/* (o) pointer of keyword */
	int	*key_num ;	/* (o) number of keyword */

{
	int	err ;

/* get keyword pointer */

	err = d3tkey ( dsp , key_num , key_ptr ) ;
	if ( err == DE_NONE_DAT ) { *key_num = 0 ; return ( 0 ) ; }
	if ( err != 0 ) ERR ( err ) ;
}	
/******************************************************************************	
		Make Templete 
		FUNC.NAME	: dsph20() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
dsph20 ( dsp , tmp , key_num )
	DSET		*dsp ;		/* (i) pointer of ladataset table */
	T_TEMPLETE	**tmp ;		/* (o) templete */
	int		key_num ;	/* (i) number of key */
{
	int	i ;
	int	err ;

/* allocate tmplete buffer */

	*tmp = ( T_TEMPLETE *) malloc ( sizeof ( T_TEMPLETE ) +
				sizeof ( TEMPLETE ) * key_num ) ;
	if ( *tmp == NULL ) ERR ( DE_MEM ) ;
	(*tmp)->tp_cnum = key_num ;
	for ( i=0 ; i<key_num ; i++ ) ((*tmp)->tp_templete[i]).tp_col = i+1 ;

/* make templete */

	err = d3mktp ( dsp , *tmp ) ;
	if ( err != 0 ) ERR ( err ) ;

/* allocate data buffer */

	(*tmp)->tp_bufp = malloc ( (*tmp)->tp_rlen ) ;
	if ( (*tmp)->tp_bufp == NULL ) ERR ( DE_MEM ) ;
}
/******************************************************************************	
		Output Header
		FUNC.NAME	: dsph25() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
dsph25 ( key_ptr , key_num )  
	char	**key_ptr ;		/* (i) pointer of keyword */
	int	key_num ;		/* (i) number of keyword */
{
	int	i ;

	PRT ( "%s","/* " ) ;
	for ( i=0 ; i<key_num ; i++ ) { 
		PRT3 ( "%c%s%c",DBLQ,key_ptr[i],DBLQ ) ;
		if ( i != key_num-1 ) { 
			PRT ( "%s", "," ) ;
		}
	}
	PRT ( "%s"," */\n" ) ;
}
/******************************************************************************	
		Output Keyword all to STDOUT
		FUNC.NAME	: dsph30() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
dsph30 ( key_ptr , key_num , tmp )  
	char	**key_ptr ;		/* (i) pointer of keyword */
	int	key_num ;		/* (i) number of keyword */
	T_TEMPLETE	*tmp ;		/* (i) templete */
{
	int	i ;

	for ( i=0 ; i<key_num ; i++ ) { 
		PRT ( "%s\t", PD_DATKEY_MARK );
		PRT3 ( "%c%s%c",DBLQ,key_ptr[i],DBLQ );
		switch ( (tmp->tp_templete[i]).tp_mode ) {
			case DS_L : PRT ( "%s"," L" ) ; break ;
			case DS_I : PRT ( "%s"," I" ) ; break ;
			case DS_R : PRT ( "%s"," R" ) ; break ;
			case DS_D : PRT ( "%s"," D" ) ; break ;
			case DS_C : PRT ( "%s"," C" ) ; break ;
			default	  : PRT ( "%s"," antother type" ) ; break ;
		}
		PRT ( "%s", "\n" ) ; 
	}
}
/******************************************************************************	
		Output Data to STDOUT
		FUNC.NAME	: dsph40() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
dsph40 ( dsp , tmp )
	DSET	*dsp ;			/* (i) pointer of ladataste table */
	T_TEMPLETE	*tmp ;		/* (i) template */
{
	char	*dat ;
	int	lines ;
	int	i , j ;
	int	err ;
	int	ptr ;
	int	type ;
	char	edit[1024] ;
	int	field_len ;

/* get Dim ( line ) */

	lines = d3trow ( dsp ) ;
 	if ( lines < 0 ) ERR ( lines ) ;

/* read 1 line data & output to STDOUT */

	for ( i=1 ; i<=lines ; i++ ) {
		err = d3trd ( dsp , i , 1 , tmp ) ;	/* read 1 line */
		if ( err != 0 ) ERR ( err ) ;
		dat = tmp->tp_bufp ;
		PRT ( "%s",PD_DAT_MARK ) ;
		PRT ( "%s","\t" ) ;
		for ( j=0 ; j<tmp->tp_cnum ; j++ ) {	/* out put */
			ptr=(tmp->tp_templete[j]).tp_offset ; 
			type =  (tmp->tp_templete[j]).tp_mode ; 
			dsph41 ( &dat[ptr] , type , edit ) ;
			switch ( type ) {
		  		case DS_L : field_len = 7 ; break ;
				case DS_I : 
				case DS_R : field_len = 15 ; break ;
				case DS_C : 
					field_len = tmp->tp_templete[j].tp_len;
					break ;
			}
			if ( j==tmp->tp_cnum-1 ) { 
				PRT ( "%s",edit ) ;	
			} else {
			        dsph99 ( field_len , edit ); 
			}
		}
		PRT ( "%s","\n" ) ;
	}
}	
/******************************************************************************	
		edit data 
		FUNC.NAME	: dsph41() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
dsph41 ( dat , type , edit ) 
	char	*dat ;		/* (i) nama data */
	int	type ;		/* (i) nama data type */
	char	*edit ;		/* (o) edited data */
{
	switch ( type ) {
		case DS_L : 
		    	switch ( *(int *)dat ) {
				case PD_NA : strcpy ( edit , "NA" ) ; break;
				case  1 : strcpy ( edit , "T" ) ; break ;
				case  0 : strcpy ( edit , "F" ) ; break ;
				default : strcpy ( edit , "E" ) ; break ;
			}
			break ;
		case DS_I : 
			switch ( *(int *)dat ) {
				case PD_NA : strcpy ( edit,"NA" ) ; break ;
				default : sprintf ( edit,"%d",*(int *)dat ) ;
					  break ;
			}
			break ;
		case DS_R : 
			switch ( *(int *)dat ) {
				case PD_NA : strcpy ( edit,"NA" ) ; break ;
				default : sprintf ( edit,"%g",*(float *)dat ) ;
					  break ;
			}
			break ;
		case DS_C :
			sprintf ( edit , "%c%s%c" , DBLQ,dat,DBLQ ) ;
			break ;
	}
}
/******************************************************************************	
		End prosess	
		FUNC.NAME	: dsph90() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
dsph90 ( tmp )  
	T_TEMPLETE	*tmp ;			/* (o) template */
{
	if ( (char *) tmp != NULL && tmp->tp_bufp != NULL )
		 free ( tmp->tp_bufp ) ;
	if ( (char *) tmp != NULL ) free ( (char *) tmp ) ;
}
/******************************************************************************	
		Display Data With Tab
		FUNC.NAME	: dsph99() 
		REMARKS		:	88.01.23	S.Takahashi
******************************************************************************/
static
dsph99 ( maxlen , buf ) 
	int	maxlen ;		/* (i) field max length */
	char	*buf ;			/* (i) dipslay data */
{
	int	i , tab_num , add_tab ;
	
	tab_num = (maxlen)/8+1 ;
	add_tab = tab_num - ( strlen(buf)/8 ) ;
	PRT ( "%s",buf ) ;
	for ( i=0 ; i<add_tab ; i++ ) { 
		PRT ( "%s","\t" ) ;
	}
}
/*******************************
prt ( buf ) 
	char	*buf ;

{
	int	i;

	for ( i=0 ; i<strlen(buf) ; i++ ) {
		if ( len >= 79 ) { 
			PRT ( "\n" ) ;
			len = 0 ;
		}
		PRT ( "%c",buf[i] ) ;
		if ( strncmp ( &buf[i] , "\t" ,2 ) == 0 ) len += 8-(len%8) ;	
		else if ( strncmp ( &buf[i] , "\n" ,2 ) == 0 ) len = 0 ;
		else len += 1 ;
	}
}				*******************************************/
