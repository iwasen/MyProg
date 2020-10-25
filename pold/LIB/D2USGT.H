/*
* ----- @(#)d2usgt.h 1.3 88/06/12 16:23:45 -----
		FILE.NAME	: d2usgt.h 
		REMARKS		:	88.01.29	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d3nmty.h"
#include	<string.h>
#include 	<setjmp.h>
static	jmp_buf pd_usgt_env ;
#define		ERR(x)		longjmp ( pd_usgt_env , x ) 
#define		DBLQ		0x22		/* mark " */
#define		DBG(x) 		printf ("x\n") 
#define		DBGN(x)		printf ("x=%d\n",x) 
#define		DBGC(x)		printf ("x=%s\n",x) 
#define		DBGX(x)		printf ("x=%x\n",x) 
