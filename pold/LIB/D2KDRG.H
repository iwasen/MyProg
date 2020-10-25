/* ----- @(#)d2kdrg.h 1.2 88/05/10 15:13:19 ----- */

/*
		FILE.NAME	: d2kdrg.h 
		REMARKS		:	87.12.21	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d3lmac.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf	pd_kdrg_env ;
#define		ERR(x)		longjmp ( pd_kdrg_env , x ) 
#define		DBG(x) 		printf ("x\n") ; sleep(3);
#define		DBGN(x)		printf ("x=%d\n",x) ; sleep(3);
#define		DBGC(x)		printf ("x=%s\n",x) ; sleep(3);
#define		DBGX(x)		printf ("x=%x\n",x) ; sleep(3);
