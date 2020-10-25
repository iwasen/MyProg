/***************************************************************	
	Seek Edit File 
	FUNC.NAME	: d3sked() 
	INPUT		: fp 		; ascii file fp 
	OUTPUT		: mark		; seek mark ( ex "#D","#L" ... )
			: error code	
	REMARKS		:	88.02.01	S.Takahashi
***************************************************************/
#include	<stdio.h>

static	char	sccsid[]="@(#)d3sked.c 1.2 88/06/12 14:56:01";

d3sked ( fp , mark )
FILE	*fp ;
char	*mark ;
{
	char	word[1024] ;
	long	offset ;
	int	err ;

	for ( ;; ) {
		offset = ftell ( fp ) ; 	/* save current pointer */
		err = d3gets ( fp , word ) ;	/* get 1 word */
		if ( err != 0 ) return ( err ) ;
		if ( strcmp ( word , mark ) == 0 ) {	/* find mark ? */
			fseek ( fp , offset , 0 ) ;	/* reset pointer */
			return ( 0 ) ;
		}
	}
}
