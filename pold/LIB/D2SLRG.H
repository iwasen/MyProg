/* ----- @(#)d2slrg.h 1.2 88/05/10 15:16:24 ----- */

/*
		FILE.NAME	: d2slrg.h 
		REMARKS		:	87.12.21	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d3lmac.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf	pd_slrg_env ;
#define		ERR(x)		longjmp ( pd_slrg_env , x ) 
#define		DBG(x) 		printf ("x\n") ; 
#define		DBGN(x)		printf ("x=%d\n",x) ;
#define		DBGC(x)		printf ("x=%s\n",x) ;
#define		DBGX(x)		printf ("x=%x\n",x) ;
