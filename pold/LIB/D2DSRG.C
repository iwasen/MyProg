/*
	title:				
	command	name:	d2dsrg
	file name:	d2dsrg.c
	parameter:	oladir,path_idata,oladata,w
								*/
#include	"d2dsrg.h"
extern	DSET	*d3lbgn();

static char	sccsid[]="@(#)d2dsrg.c 1.6 88/06/22 12:09:50";
static	DSET	*idsp;	/* pointer to input data set */
static	DSET	*odsp;	/* pointer to output data set */

/**********************************************************
*	meta main
**********************************************************/
d2dsrg(oladir,oladata,path_idata,w)
char	*oladir;	/* directory of output data set */
char	*path_idata;	/* path name of input data set */
char	*oladata;	/* output data set */
int	*w;		/* 1...overwrite / 0...forbid */
{
	int	err;
	char	index_odata[128];	/* index file of output data set */

	idsp = odsp = 0;

	err = dsrg10(oladir,path_idata,oladata,w,index_odata);

	if(odsp != 0){
		if(err == 0)
			err = d3lend(odsp,"wq",0);
		else
			d3lend(odsp,"q",0);
	}
	if(idsp != 0)
		d3lend(idsp,"q",0);

/* update index file */

	if(err == 0)
		 err = d2idup(oladir,index_odata,oladata);
	return(err);
}

/**********************************************************
*	main routine
**********************************************************/
static
dsrg10(oladir,path_idata,oladata,w,index_odata)
char	*path_idata;	/* path_name of input data set */
char	*oladir;	/* directory of output data set */
char	*oladata;	/* output data set */
int	*w;		/* 1...overwrite / 0...forbid */
char	*index_odata;	/* index file of output data set */
{
	char	path_odata[128];	/* path name of output data set */
	char	iladata[128];		/* input ladata set */

	char	*mode;
	int	err;

/* making pathname or directory or dataset */

	dsrg20(path_idata,path_odata,oladir,iladata,oladata);

/* check parameter */

	err = d3xdat(oladata);
	if(err == 0)
		return(d3cver(DE_DSNAME,""));

/* check existence of input data set */

	err = d3fchk(path_idata);
	if(err != 0)
		return(d3cver(DE_FILE_NONE,"df"));

/* check index file */

	err = d2ichk(oladir,oladata,index_odata);
	if(err != 0)
		return(err);

/* start using input data set */

	mode = "r";
	idsp = d3lbgn(path_idata,mode,0,&err);
	if(err != 0)
		return(d3cver(err,"df"));

/* start using outptput data set */

	err = d3fchk(path_odata);
	if(err != 0)			/* output data set none */
		mode = "c";
	else{				/* output data exists */
		if(*w == 0)			/* forbidden overwrite */
			return(d3cver(DE_FILE_EXIST,"ds"));
		else				/* overwrite */
			mode = "c+";
	}

	odsp = d3lbgn(path_odata,mode,0,&err);
	if(err != 0)
		return(d3cver(err,"ds"));

/* copy iladata oladata */

	dsrg50();

/* normal return */

	return(0);

}

/**********************************************************
*	making path or dir or data set		dsrg20
**********************************************************/
static
dsrg20(path_idata,path_odata,oladir,iladata,oladata)
char	*path_idata;		/* path name of input data */
char	*path_odata;		/* path name of output data */
char	*oladir;		/* directory of output ladata set */
char	*iladata;		/* input data set */
char	*oladata;		/* output data set */
{
	char	*ptr,*strrchr();
	
	d3path(oladir,oladata,path_odata);
	ptr = strrchr(path_idata,'/');

	if(ptr == NULL)
		strcpy(iladata,path_idata);
	else
		strcpy(iladata,++ptr);

}
/**********************************************************
*	copy iladata oladata	dsrg50
**********************************************************/
static
dsrg50()
{
	struct	dsm	*iptr;		/* pointer to input data set */
	struct	dsm	*optr;		/* pointer to output data set */
	struct	dsm	*ptr;		/* mediate pointer */

	ptr = idsp -> ds_entry;
	iptr = ptr -> ptr.d;

	ptr = odsp -> ds_entry;
	optr = ptr -> ptr.d;

	optr[1].type = iptr[1].type;	/* copy id */
	optr[2] = iptr[2];		/* copy Des */
	optr[3] = iptr[3];		/* copy Lab */
	optr[4] = iptr[4];		/* copy Dat */
}
