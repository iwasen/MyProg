/****************************************************************
	Make Dummy Ladataset On Memmory
	FUNC.NAME	: d3ldmy() 
	INPUT		: pass_name	; ladataset name 
			: mgno		; memory group  
	OUTPUT 		: mptr		; memory pointer 
			: return code	; error code
	REMARKS		:	88.01.13	S.Takahashi
****************************************************************/
#include	"d3ldmy.h"
extern	char	*d3allc() ;

static	char	sccsid[]="@(#)d3ldmy.c 1.3 88/06/12 14:53:35";

d3ldmy ( pass_name , mgno , mptr )
char	*pass_name ;
int	mgno ; 
char	**mptr ;
{
	struct	dsm	*hdp,*dp ;

/* make header */

	if ((hdp = (struct dsm *)d3allc(mgno, sizeof(struct dsm))) == 0)
		return(DE_MEM); 
	*mptr = ( char * ) hdp ;		/* set memory pointer */
	hdp->seibun = d3allc ( mgno , 1 ) ;
	*hdp->seibun = NULL ;			/* seibun <- null */
	hdp->type = DS_STR ;			/* type <- structure */
	hdp->length = 3 ;			/* length <- 3 */
	if ((dp = (struct dsm *)d3allc(mgno, (sizeof(struct dsm))*6)) == 0)
		return(DE_MEM);
	hdp->ptr.d = dp ;			/* pointer <- directory */

/* make directory ( Des,Lab,Dat ) */

	dp->seibun = 0 ;			
	dp->type = DS_DIR ;
	dp->length = 3 ;
	dp->ptr.s = d3allc ( mgno , strlen(pass_name )+1 );
	strcpy ( dp->ptr.s , pass_name ); 

	dp++ ;
	dp->seibun = 0  ;
	dp->type = 10 ;
	dp->length = 0;
	*(int *)&dp->ptr = 3 ;
						/* Des */
	dp ++ ;
	if ((dp->seibun = d3allc(mgno, 4)) == 0)
		return(DE_MEM);
	strcpy ( dp->seibun , "Des" );
	dp->type = DS_N;
	dp->length = 0 ;
	*(int *)&dp->ptr = 0 ;
						/* Lab */
	dp ++ ;
	if ((dp->seibun = d3allc(mgno, 4)) == 0)
		return(DE_MEM);
	strcpy(dp->seibun, "Lab");
	dp->type = DS_N;
	dp->length = 0 ;
	*(int *)&dp->ptr = 0 ;
 						/* Dat */
	dp ++ ;
	if ((dp->seibun = d3allc(mgno, 4)) == 0)
		return(DE_MEM);
	strcpy(dp->seibun, "Dat");
	dp->type = DS_N;
	dp->length = 0 ;
	*(int *)&dp->ptr = 0 ;

	return ( 0 ) ;
}
