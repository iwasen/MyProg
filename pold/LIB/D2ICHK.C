/**********************************************************
	title:				
	file name:	d2ichk.c
	function name:	d2ichk()

	input:		ladir		; directory
			ladata		; data set

	output:		index_name	; name of index file
			err		; error code

HISTORY.......................................................................
1.	modify	M.MOTOKI       88-07-25	システム索引の存在チェックを止めた
**********************************************************/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"	
#include	"la_ws/include/d3nmty.h"	

extern	DSET	*d3lbgn() ;

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/**********************************************************
*	main routine 		d2ichk()
**********************************************************/
d2ichk(ladir,ladata,index_name)
char	*ladir;		/* directory */
char	*ladata;	/* data set */
char	*index_name;	/* name of index file */
{
	char	path_grp[128];		/* path name of group index */
	char	path_index[128];	/* path name of index name */

	DSET	*dsp;
	int	err;

/* for using group index file */

	d3path(ladir,GROUP_INDEX,path_grp);
	dsp = d3lbgn(path_grp,"r",0,&err);
	if(err != 0)
		return(d3cver(err,"gi"));

/* get index file name */

	err = d3fidx(dsp,ladata,index_name);
	if(err != 0){
		d3lend(dsp,"q",0);
		return(d3cver(err,"gi"));
	}

		/* index file exists ? */
		/*************** DELETED ****************************
				********* 88-07-25 M.MOTOKI *********
			d3path(ladir,index_name,path_index);
			err = d3fchk(path_index);
			if(err != 0){
				d3lend(dsp,"q",0);
				return(d3cver(DE_FILE_NONE,"if"));
			}
		****************************************************/

/* the end of using group index file */

	err = d3lend(dsp,"q",0);
	if(err != 0)
		return(d3cver(err,"gi"));

/* normal end */

	return(0);
}
