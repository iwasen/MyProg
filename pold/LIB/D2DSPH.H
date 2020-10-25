/* ----- @(#)d2dsph.h 1.2 88/05/10 15:02:33 ----- */

/*
		FILE.NAME	: d3dsph.h 
		REMARKS		:	87.12.21	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf	pd_dsph_env ;
#define		ERR(x)		longjmp ( pd_dsph_env , x ) 
#define		DBLQ		0x22  
/***char	pd_dsph_buf[2] = { DBLQ_MARK , 0x00 } ; *****/
/***#define		DBLQ		pd_dsph_buf */	
#define		DBG(x) 		printf ("x\n") 
#define		DBGN(x)		printf ("x=%d\n",x) 
#define		DBGC(x)		printf ("x=%s\n",x) 
#define		DBGX(x)		printf ("x=%x\n",x) 
