/************************************************************
 *	FILE DESCRIPTION
 *			data set delete
 *	FILE	:	d3unln.c
 *		:	d3unln
 *	REMARKS	:	1988-04-26	M.MOTOKI
 *
 ************************************************************/
#include	<stdio.h>
#include	<fcntl.h>
#include	"la_ws/include/d3libc.h"	
#include	<sys/types.h>
#include	<string.h>
#include	<sys/stat.h>
#include	<errno.h>

#define		ERROR(err)	{fclose(fp); return(err);}

static	char	sccsid[]="@(#)d3unln.c 1.2 88/06/12 15:00:34";

d3unln(ladir, ladata, mode)
char	*ladir, *ladata;	/* path name of data set */
int	mode;
{
	struct flock	arg;
	FILE	*fp;
	int	fid, err;
	char	path_name[128], back_name[128];

	d3path(ladir, ladata, path_name);
	if ((err = d3fchk(path_name)) != 0)
		return(DE_FILE_NONE);

	if ((fp=fopen(path_name,"a")) == NULL) return(DE_FILE_IO);

	fid = fileno(fp);	

	arg.l_type = F_WRLCK;
	arg.l_whence = 0;
	arg.l_start = 0l;
	arg.l_len = 0l;

	if (fcntl(fid,F_SETLK,&arg) == -1) {
		if (errno==EAGAIN || errno==EACCES)	/* LOCK ERROR */
			ERROR(DE_LOCK)
		else
			ERROR(DE_FILE_IO)
	}
		
	if (mode==0) {
		strcpy(back_name,ladir);
		strcat(back_name,"/.B");
		strcat(back_name,ladata);
		if (d3fchk(back_name) == 0) {
			if (unlink(back_name) != 0) ERROR(DE_FILE_IO)
		}

		if(link(path_name, back_name) != 0) ERROR(DE_FILE_IO)
	}
	if (unlink(path_name) != 0) ERROR(DE_FILE_IO)

	fclose(fp);
	return(0);
}
