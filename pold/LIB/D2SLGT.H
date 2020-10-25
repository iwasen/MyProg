/* ----- @(#)d2slgt.h 1.2 88/05/10 15:15:43 ----- */

/*
		FILE.NAME	: d2slgt.h 
		REMARKS		:	88.01.29	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf	pd_slgt_env ;
#define		ERR(x)		longjmp ( pd_slgt_env , x ) 
#define		DBG(x) 		printf ("x\n") ; 
#define		DBGN(x)		printf ("x=%d\n",x) ; 
#define		DBGC(x)		printf ("x=%s\n",x) ;
#define		DBGX(x)		printf ("x=%x\n",x) ;
