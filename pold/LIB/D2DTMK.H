/* ----- @(#)d2dtmk.h 1.2 88/05/10 15:06:40 ----- */

/*
		FILE.NAME	: d2dtmk.h 
		REMARKS		:	87.12.21	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf	pd_dtmk_env ;
#define		ERR(x)		longjmp ( pd_dtmk_env , x ) 
#define		PD_LENTRY	4
#define		PD_CPYSIZ	((PD_LENTRY-1)*sizeof(int))
#define		PD_ENTCPY(s1,s2) 	memcpy ( (int*)s1+1 , (int*)s2+1 , PD_CPYSIZ )
#define		NAMA_DATA	1
#define		KAISEKI_DATA	2
#define		DBG(x) 		printf ("x\n") ; sleep(3);
#define		DBGN(x)		printf ("x=%d\n",x) ; sleep(3);
#define		DBGC(x)		printf ("x=%s\n",x) ; sleep(3);
#define		DBGX(x)		printf ("x=%x\n",x) ; sleep(3);
