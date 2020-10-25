/******************************************************************************	
		Check Of File Status 
		FUNC.NAME	: d3fchk() 
		INPUT		: path_name
		OUTPUT		: 0 . exist  -1 . dose not exist 
		REMARKS		:	87.12.22	S.Takahashi
******************************************************************************/
#include	<sys/types.h>
#include	<sys/stat.h>

static	char	sccsid[]="@(#)d3fchk.c 1.2 88/06/12 14:50:47";

d3fchk(pass_name)
char	*pass_name;
{
	struct	stat	buf;
	if ( pass_name[strlen(pass_name)-1] == '/' )return(-1);
	return(stat(pass_name,&buf));
}
