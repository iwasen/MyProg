/**************************************************************
	End Prosess To Use of Ladataset
	FUNC.NAME	: d3lend() 
	INPUT		: dsp		; pointer of d3lbgn()
			: term		; prosess mode
					;  "wq" ( write & quit )
					;  "q"  ( quit )
			: sync 		; memory update mode 
					; 0 ( none update )
					; 1 ( compress label-bu )
					; 2 ( delete not usesd Tra )
	OUTPUT		:  return code	; error code
	REMARKS		:	88.01.07	S.Takahashi
***************************************************************/
#include	"d3lend.h"
#define		DBG(x)	printf ( "x\n" )  
#define		DBGN(x)	printf ( "x=%d\n",x )  
#define		DBGC(x)	printf ( "x=%s\n",x )  
extern	int	d3dsrd() ;
extern	struct	dsm	*d3gcmp();
extern	int	errno ;

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3lend ( dsp , term , sync ) 
DSET	*dsp ;
char	*term ;
int	sync ; 
{
	int	rtn ;

/* error handling */
	
	rtn = setjmp ( pd_lend_env ) ;
	if ( rtn != 0 ) {
		lend90 ( dsp ) ; return ( rtn ) ; 
	}	
/* parameter check ( "wq" or "q" ? )  */

	if ( strcmp ( term , "wq" ) != 0 && strcmp ( term , "q" ) != 0 ) 
		ERR ( DE_PRM ) ; 
	if ( sync < 0 || sync > 2 ) ERR ( DE_PRM ) ;

/* memory update */
	 
	if ( strcmp ( term , "wq" ) == 0 ) {
		switch ( sync ) {
			case 0 : break ;
			case 1 : rtn = d3clky ( dsp , 0 ) ;
				 if ( rtn != 0 ) ERR ( rtn ) ;
				 break ;
			case 2 : rtn = d3clky ( dsp , 0 ) ;
				 if ( rtn != 0 ) ERR ( rtn ) ;
				 lend30 ( dsp ) ; 
				 break ;
		}
	}

/* write to ladataset */

	lend10 ( dsp , term ) ;
	  
/* close ladataset */

	if ( dsp->ds_pmode != READ ) fclose ( dsp->ds_fp ) ;

/* end prosess */
 
	rtn = d3free ( dsp->ds_memno ) ; 	/* free dynamic memory */
	if ( rtn != 0 ) ERR ( rtn ) ;
	dsp->ds_pmode = 0 ;			/* clear to ds-table */
	return ( 0 ) ;
}
/******************************************************************************	
		Write to Ladataset  
		FUNC.NAME	: lend10() 
		INPUT		: dsp		; pointer of d3lbgn()
		OUTPUT		:  return code	; error code
******************************************************************************/
static
lend10 ( dsp , term ) 
	DSET	*dsp ;
	char	*term ; 
{
	char	tmp_name[128] ; 
	char	back_name[128] ;
	FILE	*tmp_fp ;
	int	rtn ;
	struct	stat	buff ;

/* write to ladataset ( when term is "wq" )  */

	if ( ( strcmp ( term , "wq" ) == 0 ) &&
	( dsp->ds_pmode == UPDATE || dsp->ds_pmode == CREATE ) ) {
		lend11 ( dsp , tmp_name , back_name ) ;
 		tmp_fp = fopen ( tmp_name , "w" ) ;/* open temporary file*/
		if ( tmp_fp == NULL ) ERR ( DE_FILE_IO ) ;
						/* change mode to ladaset */
		rtn = chmod ( tmp_name , 0x01a4 ) ;
		if ( rtn != 0 ) ERR ( DE_FILE_IO ) ;
						/* write !!! */
		rtn = d3dswt ( tmp_fp , dsp->ds_entry ) ;
		if ( rtn != 0 ) ERR ( rtn ) ;
						/* close temporary file */
		fclose ( tmp_fp ) ;

		if ((dsp->ds_option & DSO_NBAK) == 0) {	/* no back up ? */
			if (d3fchk(back_name) == 0) {
				if (unlink(back_name) != 0)
					ERR(DE_FILE_IO) ;
			}

			stat ( dsp->ds_name , &buff ) ;
			if ( buff.st_size != 0 ) {
				if (link(dsp->ds_name, back_name) != 0)
					ERR(DE_FILE_IO) ;
			}
		}
						/* unlink ladataset */
		rtn = unlink ( dsp->ds_name ) ; 
		if ( rtn != 0 ) ERR ( DE_FILE_IO ) ;
						/* link tmp file -> ladataset */
		rtn = link ( tmp_name , dsp->ds_name ) ;
		if ( rtn != 0 ) ERR ( DE_FILE_IO ) ;
						/* unlink temporary file */
		unlink ( tmp_name ) ;
	} 
						
/* unlink size0 ladataset ( when term is 'q' and CREATE mode ) */
		
	if ( strcmp ( term , "q" ) == 0 && dsp->ds_pmode == CREATE ) { 
		stat ( dsp->ds_name , &buff ) ;
		if ( buff.st_size == 0 ) {
			rtn = unlink ( dsp->ds_name ) ;/* unlink ladataset */
			if ( rtn != 0 ) ERR ( DE_FILE_IO ) ;
		}
	}
}
/******************************************************************************	
		Get temporary File Name on Users Directory  
		ex ) /users/xxxx/ladb/LD001 ->
				/users/xxxx/ladb/.NEWD001
		FUNC.NAME	: lend11() 
		INPUT		: dsp		; pointer of d3lbgn()
		OUTPUT	: work_tmp_name ; temporary file name on users directory
******************************************************************************/
static
lend11 ( dsp , tmp_name , back_name ) 
	DSET	*dsp ;
	char	*tmp_name ;
	char	*back_name ;
{
	int	i,ptr ;

/* "/xxxxx/yyyy/ladb" */

	ptr = 0 ;
	for ( i=0 ; i<strlen(dsp->ds_name) ; i++ ) {
		if ( dsp->ds_name[i] == '/' ) ptr = i+1 ;
	}
	if ( ptr == 0 ) {
	 	tmp_name[0] = 0x00 ;
	 	back_name[0] = 0x00 ;
	} else {
		strncpy ( tmp_name , dsp->ds_name , ptr ) ;
		tmp_name[ptr] = 0x00 ;
		strncpy ( back_name , dsp->ds_name , ptr ) ;
		back_name[ptr] = 0x00 ;
	}

/* "/xxxxx/yyyy/ladb"+".T" */

	strcat ( tmp_name , ".T" ) ;

/* "/xxxxx/yyyy/ladb"+".B" */

	strcat ( back_name , ".B" ) ;

/* "/xxxxx/yyyy/ladb"+".T"+"LD001" */

	strcat ( tmp_name , &dsp->ds_name[ptr] ) ;

/* "/xxxxx/yyyy/ladb"+".B"+"LD001" */

	strcat ( back_name , &dsp->ds_name[ptr] ) ;
}
/******************************************************************************	
		Clear Not Used Cols On Dat
		FUNC.NAME	: lend30() 
		INPUT		: dsp		; pointer of d3lbgn()
******************************************************************************/
static
lend30 ( dsp )  
	DSET	*dsp ;
{
	struct	dsm  	*labp , *axip , *datp , *dimp , *datap ;
	int	*dim ;
	int	*data ;
	int	i,j ;
	int	sbn_suu ;
	int	del_flg ;
	int	*i_key ;
	char	**c_key ;
	int	rtn ;
	int	tno ; 
	int	*del_tbl ;
	int	del_tbl_cnt  ;

/* move pointer to Lab */
		
	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == 0)
		return;
	if ((axip = d3gcmp(labp, "Axi2")) == 0)
		return;
	sbn_suu = axip->length ;
	axip = axip->ptr.d ;

/* get pointer of dim and data */ 

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == 0)
		return;
	if ((dimp = d3gcmp(datp, "Dim")) == 0)
		return;
	if ((datap = d3gcmp(datp, "Data")) == 0)
		return;
	dim = dimp->ptr.i ;
	data = datap->ptr.i ;

/* delete flag table allocate */

	del_tbl = ( int * ) d3allc ( dsp->ds_memno , dim[1]*sizeof(int) ) ;
	if ( del_tbl == NULL ) ERR ( DE_MEM ) ;

/* delete flag table clear */

	for ( i=0 ; i<dim[1] ; i++ ) del_tbl[i] = OFF ;
	del_tbl_cnt = 0 ;

/* find delete Trano ( code Tra ) */

	for ( i=2 ; i <= sbn_suu+1 ; i++ ){
		if ( strncmp ( axip[i].seibun , "Tra" , 3 ) == 0 ) {
			tno = atoi ( axip[i].seibun+3 ) ;
			if ( axip[i].length == 1 ) {
				del_flg = OFF ;
				switch ( axip[i].type ) {
					case DS_L : 
					case DS_R : 
					case DS_I : i_key = axip[i].ptr.i ;
						    if ( i_key[0] == PD_NA ) 
							del_flg = ON ; break ;
					case DS_C : c_key = axip[i].ptr.c ;
						    if ( strlen(c_key[0])== 0 ) 
							del_flg = ON ; break ;
					default	  : break ;
				}
				if ( del_flg == ON ) {
					del_tbl[del_tbl_cnt] = tno ;
					del_tbl_cnt ++ ;
				}
			}
		}
	}

/* find delete Trano ( uncode Tra ) */

	for ( i=0 ; i<dim[1] ; i++ ) {	/* search all 'NA' cols */
		del_flg = ON ;
		for ( j=0 ; j<dim[0] ; j++ ) {
			if ( data[i*dim[0]+j] != PD_NA ) {
				del_flg = OFF ;
				break ;
			}
		}
		if ( del_flg == ON ) {	/* clear cols */
			del_tbl[del_tbl_cnt] = i+1 ;
			del_tbl_cnt ++ ;
		}
	}

/* clear tra */

	if ( del_tbl_cnt != 0 ) {
		for ( i=0 ; i<del_tbl_cnt ; i++ ) {
			rtn = d3tdlc ( dsp , del_tbl[i] ) ;
			if ( rtn != 0 ) ERR ( rtn ) ;
			for ( j=0 ; j<del_tbl_cnt ; j++ ) {
				if ( del_tbl[i] < del_tbl[j] ) del_tbl[j] -= 1;
			}
		}
	}
}
/******************************************************************************	
		End Prosess On Error 
		FUNC.NAME	: lend90() 
		INPUT		: dsp		; pointer of d3lbgn()
		OUTPUT		: none ( no handling error ) ; 
******************************************************************************/
static
lend90 ( dsp ) 
 	DSET	*dsp ;
{

/* end prosess */

	d3free ( dsp->ds_memno ) ;		/* free dynamic memory */
	fclose ( dsp->ds_fp ) ; 		/* close ladataset */
	dsp->ds_pmode = 0 ;			/* clear to ds-table */
}
