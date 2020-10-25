/*********************************************************
	title:delete LA-DATASET			
	command	name:	d2dsdl
	file name:	d2dsdl.c
	parameter:	ladir,ladata
*********************************************************/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"	

static char	sccsid[]="@(#)d2dsdl.c 1.4 88/06/12 14:39:52";

/**********************************************************
*	main routine
**********************************************************/
d2dsdl(ladir,ladata)
char	*ladir, *ladata;
{
	int	type, err, nind = 0;
	char	index_name[128], file_tp[3];

	if (d3xdat(ladata) == 0 && d3xusr(ladata) == 0)
		return(d3cver(DE_DSNAME,""));
	if (d3xdat(ladata) == 1)
		strcpy(file_tp, "ds");
	else
		strcpy(file_tp, "uf");

	err = d2ichk(ladir,ladata,index_name);
	if(err != 0){
		if(err == 0x0a)
			nind = 1;
		else
			return(err);
	}

	if ((err=d3unln(ladir, ladata, 0)) != 0)
		return(d3cver(err, file_tp));

	if(nind == 1) return(0);

	err = d2idup(ladir,index_name,ladata);
	if(err != 0)
		return(err);

	return(0);
}
