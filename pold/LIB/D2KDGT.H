/* ----- @(#)d2kdgt.h 1.2 88/05/10 15:12:22 ----- */

/*
		FILE.NAME	: d2kdgt.h 
		REMARKS		:	88.01.29	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf	pd_kdgt_env ;
#define		ERR(x)		longjmp ( pd_kdgt_env , x ) 
#define		DBLQ		0x22		/* mark " */
#define		DBG(x) 		printf ("x\n") ; sleep(3)
#define		DBGN(x)		printf ("x=%d\n",x) ; sleep(3) 
#define		DBGC(x)		printf ("x=%s\n",x) ; sleep(3)
#define		DBGX(x)		printf ("x=%x\n",x) ; sleep(3) 
