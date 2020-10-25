/* ----- @(#)d3lend.h 1.2 88/05/10 15:31:16 ----- */

/*
		FILE.NAME	: d3lend.h 
		REMARKS		:	88.01.07	S.Takahashi
*/
#include	<stdio.h>
#include	<setjmp.h>
#include	"la_ws/include/d3libc.h"
#include	<sys/fcntl.h>
#include	<sys/types.h>
#include	<sys/stat.h>
/******************************************************************************/
/*	local defines							      */
/******************************************************************************/
/*......... prosess mode */
#define		UPDATE	1
#define		CREATE	2
#define		READ 	3
/*......... long jump */
jmp_buf	pd_lend_env ;
#define		ERR(x)	longjmp ( pd_lend_env , x ) 
/*......... other */
#define		NOT_USE	0
#define 	USE	1
#define		OFF	0
#define		ON	1
