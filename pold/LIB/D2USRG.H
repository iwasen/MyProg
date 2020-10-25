/*
*----- @(#)d2usrg.h 1.3 88/06/12 16:24:06 ----- 
		FILE.NAME	: d2usrg.h 
		REMARKS		:	87.12.21	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d3nmty.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf	pd_usrg_env ;
#define		ERR(x)		longjmp ( pd_usrg_env , x ) 
#define		DBG(x) 		printf ("x\n") 
#define		DBGN(x)		printf ("x=%d\n",x) 
#define		DBGC(x)		printf ("x=%s\n",x) 
#define		DBGX(x)		printf ("x=%x\n",x) 
