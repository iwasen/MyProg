/* ----- @(#)d3lbgn.h 1.2 88/05/10 15:29:55 ----- */

/*
		FILE.NAME	: d3lbgn.h 
		REMARKS		:	88.01.05	S.Takahashi
*/
#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	<sys/fcntl.h>
#include	<sys/types.h>
#include	<sys/stat.h>
/*****************************************************************************/
/*	local defines						             */
/*****************************************************************************/
/*......... prosess mode */
#define		UPDATE	1
#define		CREATE	2
#define		READ 	3
/*......... dataset table max */
#define		DSET_TBL_MAX 10
/*......... other */
#define		RTNCHK	if ( rtn < 0 ) return ( rtn ) 
