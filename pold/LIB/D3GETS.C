/***************************************************************	
	Get 1 Word From Ascii File
	FUNC.NAME	: d3gets() 
	INPUT		: fp 		; ascii file fp 
	OUTPUT		: word		; get word 
			: error code	
	REMARKS		:	88.02.01	S.Takahashi
***************************************************************/
#include	"d3gets.h"
#define		INT 	0   		/* string  mode = init */
#define		STR 	1 		/*  	"	= "abc" */ 
#define		NUM_CHR	2 		/* 	"	= 123 , abc */
#define		CMT 	3		/* 	"	= comment */
#define		SET_CHAR	word[ptr]=crt ; ptr ++ ; if ( ptr >=1024 ) return ( DE_VI_FMT )   
#define		SET_NULL	word[ptr]=0 ; 

static	char	sccsid[]="@(#)d3gets.c 1.2 88/06/12 14:52:09";

d3gets ( fp , word )
	FILE	*fp ;
	char	*word ;
{
	int	ptr , mode ;
	char	crt , next ;
	long	offset ;

/* initialize */

	mode = INT ;
	ptr = 0 ;
	
/* get 1 word */

	for ( ;; ) {
		crt = fgetc ( fp ) ;		/* read 1 byte */
		if ( crt == -1 ) {		/* eof ? */
			switch ( mode ) {
				case INT : 
					return ( DE_FILE_IO ) ; 
				case STR :
				case CMT :
					return ( DE_VI_FMT ) ;
				case NUM_CHR :	
					SET_NULL ;
				 	return ( 0 ) ;
			}
		}
		switch ( crt ) {
			case '\n' : 			/* CR ? */
			case '\t' :			/* tab ? */	
			case ' '  : 			/* space ? */
			case ','  :			/* , ? */
				switch ( mode ) {
					case INT : 
						break ;
					case STR : 
						SET_CHAR ;
						break ;
					case NUM_CHR : 
						SET_NULL
						return ( 0 ) ;
					case CMT : 
						break ;
				}
				break ;
			case '"'  : 			/* duble quataion ? */
				switch ( mode ) {
					case INT : 
						mode = STR ; 	
						break ;
					case STR :
						SET_NULL ;
						return ( 0 ) ;
					case NUM_CHR : 
						return ( DE_VI_FMT ) ;
					case CMT : 
						break ;
				}
				break ;	
			case '/'  :			/* slash ? */
				switch ( mode ) {
					case INT : 
						next = fgetc ( fp ) ;
						if ( next==-1 )
							return (DE_VI_FMT);
						if ( next=='*' ) { 
							mode=CMT ;
							break ;
						} else {
							SET_CHAR ;
							crt = next ;
							SET_CHAR ;
							mode=NUM_CHR ; 
						}
					case STR : 
						SET_CHAR ;
						break ;
					case NUM_CHR : 
						SET_CHAR ;
						break ;
					case CMT : 
						break ;
				}
				break ;
			case '*'  :			/* astarisk ? */
				switch ( mode ) {
					case INT :
						mode = NUM_CHR ;
					case STR : 
						SET_CHAR ; 
						break ;
					case NUM_CHR : 
						SET_CHAR ;
						break ;
					case CMT :	
						offset = ftell ( fp ) ;
						next = fgetc ( fp ) ;
						if ( next==-1 )
							return (DE_VI_FMT);
						if ( next=='/' )  mode=INT ;
						else fseek ( fp,offset,0 ) ;
						break ;
				}
				break ;
			default : 			/* other data */
				switch ( mode ) {
					case INT : 
						mode = NUM_CHR ;
						SET_CHAR ;
						break ;
					case STR : 
						SET_CHAR ;
						break ;
					case NUM_CHR : 
						SET_CHAR ;
						break ;
					case CMT : 
						break ;
				}
		}
	}
}
