/* ----- @(#)d3name.c 1.2 88/05/10 15:32:33 ----- */
/******************************************************************************	
		Check Ladataset Name  
		FUNC.NAME	: d3name() 
		INPUT		: ladata	; user index file name 
		OUTPUT		: 0		; ok
				  1 		; dose not ok
		REMARKS		:	88.02.02	S.Takahashi
******************************************************************************/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
d3name ( ladata ) 
	char	*ladata ;
{
	if ( strncmp ( ladata , GROUP_INDEX , strlen ( GROUP_INDEX ) ) == 0 ||
	strncmp ( ladata , USER_INDEX , strlen ( USER_INDEX ) ) ==  0 ||
	strncmp ( ladata , INDEX , strlen ( INDEX ) ) ==  0 ) 
		return ( DE_DSNAME ) ;
	else 	return ( 0 ) ; 
}
