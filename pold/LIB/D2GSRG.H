/* ----- @(#)d2gsrg.h 1.4 88/06/12 15:48:14 ----- */

/*
		FILE.NAME	: d2gsrg.h 
		REMARKS		:	87.12.21	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d3nmty.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf	pd_gsrg_env ;
#define		ERR(x)		longjmp ( pd_gsrg_env , x ) 
#define		DBG(x) 		printf ("x\n") 
#define		DBGN(x)		printf ("x=%d\n",x) 
#define		DBGC(x)		printf ("x=%s\n",x) 
#define		DBGX(x)		printf ("x=%x\n",x) 
