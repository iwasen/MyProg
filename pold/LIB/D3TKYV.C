/***************************************************************	
	Read Key Value all
	FUNC.NAME	: d3tkyv() 
	INPUT		: dsp 
			: col			; colum no
	OUTPUT		: keyrec 		
			: return code		; error code
	REMARKS		:	88.01.18	S.Takahashi
******************************************************************************/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

/* local defines */
#define		ARI	1  
#define		NASI	0

extern		char	*malloc() ;
extern	struct	dsm	*d3gcmp(), *d3gtra();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3tkyv ( dsp , col , keyrec )
DSET	*dsp ;
int	col ;
PD_KEYREC	**keyrec ;
{
	struct	dsm	*dp, *trap;
	int	type , length , clength ;
	int	**i_srt_tbl , i , ptr , tkyv97() , tkyv98() , tkyv99() ;
	int	last , *dat , total_len , dup_cnt , err ;
	char	**c_srt_tbl , **buf ;

/* clear not used key value */

	err = d3clky(dsp, col) ; 
	if ( err != 0 ) return ( err ) ; 

/* colum check */

	err = tkyv10 ( col , dsp , &type , &length , &clength , &trap ) ;
	if ( err != 0 ) return ( err ) ; 


/* allocate sort table */

	if ( type == DS_C ) {
		c_srt_tbl = ( char ** ) malloc ( length*sizeof(char *) ) ;
		if ( c_srt_tbl == NULL ) return ( DE_MEM ) ;  
	} else {
		i_srt_tbl = ( int ** ) malloc ( length*sizeof(int *) ) ;
		if ( i_srt_tbl == NULL ) return ( DE_MEM ) ;  
	}

/* allocate keyrec */
	
	if ( type == DS_C ) 
		*keyrec = ( PD_KEYREC * ) malloc ( 4*(3+length)+clength ) ;
	else 	*keyrec = ( PD_KEYREC * ) malloc ( 4*(3+length) ) ;
	if ( *keyrec == NULL ) {
		err = DE_MEM;
		goto ret;
	}

/* copy data to sort table */

	if ( trap != 0 ) {		 	/* copy from Tran */
		if ( type == DS_C ) {
			buf = trap->ptr.c ;
			for ( i=0 ; i<length ; i++ ) c_srt_tbl[i] = buf[i] ;
		} else {
			dat = trap->ptr.i ;
			for ( i=0 ; i<length ; i++ ) i_srt_tbl[i] = &dat[i] ;
		}
	} else {				/* copy from Dat */
		if ((dp = d3gcmp(dsp->ds_entry, "Dat")) == 0) {
			err = DE_DS_FORMAT;
			goto ret;
		}
		if ((dp = d3gcmp(dp, "Data")) == 0) {
			err = DE_DS_FORMAT;
			goto ret;
		}
		dat = dp->ptr.i ;
		for ( i=0;i<length;i++ )i_srt_tbl[i]=&dat[(col-1)*length+i] ; 
	}
	
/* sort */

	switch ( type ) {
		case DS_C :
			qsort ( (char *)c_srt_tbl , length ,
						sizeof ( *c_srt_tbl ),tkyv97 );
			break ;
		case DS_I :
		case DS_L :
			qsort ( (char *)i_srt_tbl , length , 
						sizeof ( *i_srt_tbl ),tkyv98 );
			break ;
		case DS_R :
			qsort ( (char *)i_srt_tbl , length , 
						sizeof ( *i_srt_tbl ),tkyv99 );
			break ;
	}

/* copy sorted data to keyrec */

	if ( trap != 0 ) {
		if ( type == DS_C ) {		/* copy pointer & string */
			total_len = 0 ;
			for ( i=0 ; i<length ; i++ ) {
				strncpy((char *)(*keyrec)+(4*(3+length))+total_len,
					c_srt_tbl[i],strlen(c_srt_tbl[i])+1 ); 
				(*keyrec)->key_type.c[i] = 
					(char *)(*keyrec)+(4*(3+length))+total_len;
				total_len += strlen ( c_srt_tbl[i] ) + 1 ;
			}
		} else {			/* copy data */
			for ( i=0 ; i<length ; i++ ) 
				(*keyrec)->key_type.i[i] = *i_srt_tbl[i] ;
		}
	} else {			/* copy with duplicate check */
		ptr = dup_cnt = 0 ;
		for ( i=0 ; i<length ; i++ ) {
			if ( i!=0 ) {
				if ( last == *i_srt_tbl[i] ) {	/* duplicate?*/
					dup_cnt ++ ;		/* ----- yes */
				} else { 			/* ----- no  */
					(*keyrec)->key_type.i[ptr]=*i_srt_tbl[i] ;
					ptr ++ ;
				}
			} else {
				(*keyrec)->key_type.i[ptr] = *i_srt_tbl[i] ;
				ptr ++ ;
			}
			last = *i_srt_tbl[i] ;
		}
	}

/* set keyrec othor data */

	(*keyrec)->col_no = col ;
	(*keyrec)->col_type = type ;
	if ( trap != 0 ) {
		(*keyrec)->key_length = length ;
	} else {
		(*keyrec)->key_length = length - dup_cnt ;
	}
	err = 0;
/* free sort tble */
ret:
	if ( type == DS_C ) 
		free ( (char *) c_srt_tbl ) ;
	else 	free ( (char *) i_srt_tbl ) ; 

	if (err != 0 && *keyrec != 0)
		free(*keyrec);

	return ( err ) ;
}
/******************************************************************************	
		Check col
		FUNC.NAME	: tkyv10() 
		INPUT		: col
				: dsp
				: type			; col type
				: length		; col length 
				: clength		; char length
				: return code		; error code
		REMARKS		:	88.01.18	S.Takahashi
******************************************************************************/
static
tkyv10 ( col , dsp , type , length , clength , trap )
	int	col ;
	DSET	*dsp ;
	int	*type ;
	int	*length ;
	int	*clength ;
	struct	dsm	**trap;
{
	struct	dsm	*datp, *dimp, *labp, *axip;
	int	line_length , col_length , *dim , j ;
	char	**cdata ;

/* col haini check */

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == 0)
		return(DE_DS_FORMAT);
	if ((dimp = d3gcmp(datp, "Dim")) == 0)
		return(DE_DS_FORMAT);
	dim = dimp->ptr.i;
	line_length = dim[0] ;			/* get Dim(line) */
	col_length = dim[1] ;			/* get Dim(col) */
	if ( col < 1 || col > col_length ) return ( DE_PRM ) ;

/* get type,length,character data length... */

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == 0)
		return(DE_DS_FORMAT);
	if ((axip = d3gcmp(labp, "Axi2")) == 0)
		return(DE_DS_FORMAT);

	if ((*trap = d3gtra(axip, col)) != 0) {
		*type = (*trap)->type ;	/* data type */
		*length = (*trap)->length ;/* data length */	
		if ( *length == 0 ) return ( DE_NONE_DAT ) ;
		if ( *type == DS_C ) {	/* get total length*/
			*clength = 0 ;
			cdata =  (*trap)->ptr.c ;
			for ( j=0 ; j<*length ; j++ ) 
				*clength += strlen(cdata[j])+1;
		}
	} else {
		*type = DS_I ;				/* type is integer fix*/
		*length = line_length ;			/* length = Dim(line) */
		if ( *length == 0 ) return ( DE_NONE_DAT ) ;
	}
	return ( 0 ) ;
}
/******************************************************************************	
		Sort compare ( char type )
		FUNC.NAME	: tkyv97() 
		REMARKS		:	88.01.18	S.Takahashi
******************************************************************************/
static
tkyv97 ( node1 , node2 ) 
	char	*node1 , *node2 ;
{
	struct	node	{
		char	*string ;
		int	len ;
	};

	return ( strcmp (
			(( struct node*)node1 )->string ,
			(( struct node*)node2 )->string)) ;
}
/******************************************************************************	
		Sort compare ( int type )
		FUNC.NAME	: tkyv98() 
		REMARKS		:	88.01.18	S.Takahashi
******************************************************************************/
static
tkyv98 ( node1 , node2 ) 
	int	*node1 , *node2 ;
{
	struct	node	{
		int	*dat ;
		int	len ;
	};
	if ( *((struct node*)node1)->dat == *((struct node*)node2)->dat )  
		return ( 0 ) ;
	if ( *((struct node*)node1)->dat < *((struct node*)node2)->dat ) 
		return ( -1 ) ;
	else 	return ( 1 ) ;
}
/******************************************************************************	
		Sort compare ( float type )
		FUNC.NAME	: tkyv99() 
		REMARKS		:	88.01.18	S.Takahashi
******************************************************************************/
static
tkyv99 ( node1 , node2 ) 
	float	*node1 , *node2 ;
{
	struct	node	{
		float	*dat ;
		int	len ;
	};
	if ( *((struct node*)node1)->dat == *((struct node*)node2)->dat )  
		return ( 0 ) ;
	if ( *((struct node*)node1)->dat < *((struct node*)node2)->dat ) 
		return ( -1 ) ;
	else 	return ( 1 ) ;
}
