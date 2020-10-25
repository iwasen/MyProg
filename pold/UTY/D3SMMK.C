/*	FUNC. DESCRIPTION
		
		1. read item-file ;
		2. display menu item ;  
		3. input ; 
		4. if (input==KEY_NPAGE)   foward scroll ;
		5. if (input==KEY_PPAGE)   backward scroll ;
		6. if (input==func-key)    return ; 
		7. if (input==select-item) return ; 
                 
		FUNC.NAME	: d3smmk( )
		REMARKS		:	87.10.31	K.SIGEMI
*/

#include	<jcurses.h>
#include	"la_ws/include/d2lerr.h" 
#include	<string.h> 
#include	"d3data.h"

extern int	d3cmmk();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3smmk(title, itemfile, func, imes, slectitem, fno)
 
/* INPUT PARAMETER */
char 		*title 		; 	/* title message */
char   		*itemfile 	; 	/* name of item-file */
char		*func[]		; 	/* item of function */
char 		*imes 		; 	/* input prompt message */
  
/* OUTPUT PARAMETER */
char 		*slectitem  	; 	/* selected item from item-file */
chtype 		*fno  		; 	/* inputed func-key-no */

{ 

/* -------------------------------- */
/* DEFINITION of MAKE MENU function */
/*                                  */
/* d3cmmk(mmode,title,item,itemn,func,imes,pno,fno)     */
int		mmode = 1 	;	/* 0: not input, 1: input */

/* -------------------------------- */
/* Local define */

#define 	BEEP  beep( )
int 		loopsw 		; 	/* loop switch */
#define 	LOOP  1 	
#define 	LOOPEND  0

int		max_ln 		; 	/* max line */
int		dsp_ln 		; 	/* display max line */
int		max_cl 		; 	/* max cxxxn */
#define 	OFFMX  500
long 		r_offset[OFFMX]	; 	/* offset table of tmp-file */
int 		i,j,i_ln, i_off	; 	/* index */
static int 	save_i_off = 0  ;  
char 		save_select_item[200] ; 
int 		i_no 		; 	/* item no */
int 		i_ct  		; 	/* item counter */
#define 	MXL  25
#define 	MXC  50
char		d_buf[MXL][MXC]	; 	/* read buffer */
char 		*cp, *cp1, *cp2 ;
int 		selectno 	; 	/* select number */
char 		cmp_buf[MXC]  	;	 

FILE 		*fp 		;
#define  	ENDF 		-1   

/* -------------------------------- */
/* DEFINITION of Messages           */ 
/*                                  */
int 		e_mes = 0 	; 	/* error message no */
static char  	LN_ER[] = "行数不足で項目が表示できません  ";
static char  	FL_ER[] = "該当データが有りません  "; 
   
/* --------------------------------- */

/* initialize */
 					/* initialize local data */
	max_ln = l_num 			;
	dsp_ln = max_ln - 9 		; 
	if (dsp_ln <= 0)  e_mes = 1     ; 
	max_cl = 80			;
	save_select_item[0] = NULL	; 
	for (i_off = 0; i_off < OFFMX; ) r_offset[i_off++] = ENDF;

					/* open item file */
	if ((fp = fopen(itemfile,"r")) == NULL)
	{
	  e_mes = 2             	; 
	}
	else
	{
					/* make offset table */ 
	for (i_off = 0; i_off < OFFMX; i_off++) 
	   {
	     r_offset[i_off] = ftell(fp)  ;
	     if (NULL == fgets(d_buf, MXC, fp))
	        {
		  r_offset[i_off] = ENDF  ;
		  i_off = OFFMX + 10  ;
		} 
	   }
	}      
	i_off = 0 			;
	if (save_i_off != 0)  i_off = save_i_off  ; 
 
	if (r_offset[0] == ENDF)         e_mes = 7  ;
  	
/* display menu & input */
  loopsw = LOOP  ; 
  while (loopsw == LOOP)
  {
 					/* initialize */
	i_ln = 1  			;
					/* initialize line buffer */
	for (j=0 ; j < MXL ; j++)
	    { for (i=0 ; i < MXC ; i++)  { d_buf[j][i] = NULL ; }
	    } 

					/* set read point */
	save_i_off = i_off 		; 
	if (fp == NULL || NULL != fseek(fp, r_offset[i_off], 0))
	   { 
	     e_mes = 2             	;
	   }

	/*-------------------------------   if error ocurres then  display    */
 					/*    error message, and wait input. */
	if (e_mes == 1) 
	   {
					/* display error */
	     d3cmmk(1, title, d_buf, 0, func, LN_ER, slectitem, fno)  ; 
					/* error return */
	     return(e_mes)  		; 
	   }

	if (e_mes == 2) 
	   {
					/* display error */
	     d3cmmk(1, title, d_buf, 0, func, FL_ER, slectitem, fno)  ; 
					/* error return */
	     return(e_mes)  		; 
	   }

	if (e_mes == 7) 
	   {
					/* display error */
	     d3cmmk(1, title, d_buf, 0, func, FL_ER, slectitem, fno)  ;
	     fclose(fp) 		;  
					/* error return */
	     return(e_mes)  		; 
	   }

	/*-------------------------------   read item file into data_buffer   */
  	for (i_ct = 0, i_ln = 1 ; i_ln <= dsp_ln ; i_ln++)
	  {
					/* edit item no */
	    cp = cp1 = &d_buf[i_ln - 1][0] 	;
	    i_no = i_off + i_ln  	;
	    *cp1 = ((i_no / 100) % 10) | '0'  ;  
 	    if (*cp1 == '0')  *cp1 = ' ' ;  cp1++  ; 
	    *cp1 = ((i_no / 10) % 10) | '0'  ;  
 	    if (*cp1 == '0')  *cp1 = ' ' ;  cp1++  ; 
	    *cp1++ = (i_no % 10) | '0'  ;
	    *cp1++ = ' ' ;  *cp1++ = ' ' ;  

					/* read item file */
	    if (NULL == fgets(cp1, MXC -4, fp)) 
	      {
		*cp = NULL   		; 
 	        i_ln = dsp_ln + 10     	; 
	      }
	    else
	      {
 		i_ct++  		;
	        for (i=0 ; i < MXC ; i++)
		  {  
		    if (d_buf[i_ln -1][i] == '\n')  d_buf[i_ln -1][i] = ' ' ;
		    if (d_buf[i_ln -1][i] == NULL)  d_buf[i_ln -1][i] = ' ' ;
		  }
		d_buf[i_ln -1][MXC -1] = NULL ;
	      } 
	  } 

	/*-------------------------------  display data_buffer, and wait */
					/*    input        */
	strcpy(slectitem, save_select_item) ; 
	d3cmmk(1, title, d_buf, i_ct, func, imes, slectitem, fno)  ;

	/*-------------------------------  check input func-key          */
	switch (*fno)
	{
	  case  '1'       :
	  case  '2'       :
	  case  '3'       :
	  case  '4'       :
	  case  '5'       :
	  case  '6'       :
	  case  '7'       :
	  case  '8'       :
		loopsw = LOOPEND        ;
		save_i_off = 0  	; 
		break  ;

	  case  KEY_NPAGE : 
		if ((i_off + dsp_ln) >=  OFFMX)        { BEEP ; break ; }
		if (r_offset[i_off + dsp_ln] == ENDF)  { BEEP ; break ; }
		i_off = i_off + dsp_ln ; save_select_item[0] = NULL ; 
		break  ;

	  case  KEY_PPAGE :
		if ((i_off - dsp_ln) <  0)             { BEEP ; break ; }
		i_off = i_off - dsp_ln ; save_select_item[0] = NULL ; 
		break  ;

	  case  '0'       :
					/* input string is select no */
		strcpy(save_select_item, slectitem) ; 
		if ((selectno = atoi(slectitem))    &&  
		    (selectno > 0)    		    &&  
		    (selectno <= OFFMX)  	    &&  
		    (r_offset[selectno -1] != ENDF) &&  
		    (NULL == fseek(fp, r_offset[selectno -1], 0)) &&  
		    (NULL != fgets(slectitem, MXC -1, fp)) )
		{
		  cp = strchr(slectitem, '\n')  ;  *cp = NULL ;
		}
					/* illegal no */
		else if (selectno = atoi(slectitem))  *slectitem = NULL ;
					/* input string is data */
/*
		else
		{
		  cp = cmp_buf  ;  cp1 = slectitem  ; 
		  cmp_buf[0] = NULL 	;
		  for (rewind(fp)  	;
		       ((*cp1 != NULL) & (strcmp(cp1, cp) != 0))  ; ) 
		  {
		    if (NULL == fgets(cp, MXC -1, fp)) 	*cp1 = NULL ; 
		    else  {cp2 = strchr(cp, '\n') ;  	*cp2 = NULL ; }
		  } 
		} 
 */
		if (*slectitem) 	loopsw = LOOPEND  ;
		else  			{BEEP ; } 

		break  ;

	  default         :
		break  ; 
	}
 
  }
/* end process */
					/* close item file */
	fclose(fp)   			; 
  
					/* return */ 
	return(NULL) ;
}

