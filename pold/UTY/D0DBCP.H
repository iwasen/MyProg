/* ----- %Z%%M% %I% %E% %U% ----- */
/*****************************************************************************/
/* 		header file						     */
/*		source file ; d0dbcp.c					     */
/*****************************************************************************/
#include	<jcurses.h>
#include	<string.h>
#include 	<setjmp.h>
#include 	"la_ws/include/d2lerr.h"
#include	"functn.h"
#include	"d3data.h"

static	jmp_buf 	env ;

extern int 	d3kged(), d3fcmk() ;
extern chtype 	d3chin() ;

#define 	PN 	10

static	int		i ;

#define	ARRAY_CLR(x) for(i=0;i<sizeof(x)/sizeof(x[0]);i++) x[i]
/* ---------------------------- */
#define 	SCR_CLEAR	wclear(pad);
#define 	PREFRESH 	prefresh(pad,0,0,0,0,l_num,80)
#define 	PNOUTREFRESH 	pnoutrefresh(pad,0,0,0,0,l_num,80)
#define 	SET_CURSOR(l,c)	wmove(pad,l,c); PREFRESH
static	chtype 		f_key  ;
#define SCR_GETS(l,c,ln,bf,f)  d3fcmk(f); PNOUTREFRESH; KEY_IN(l,c,ln,bf)
#define KEY_IN(l,c,ln,bf)      if((f_key=d3chin(l,c,ln,bf)) !=0x0a)
#define 	SET_MES(l,c,m)	wmove(pad,l,c); d3kged(pad,m)

#define 	DSP_PN_DR 	d3pned(PN) ; d3dred()

#define 	DIR_CALL 	d0whre(); d3pned(PN); d3dred()
#define 	JOKEN_CALL(fl) 	d0cdrf(fl) ; d3pned(PN); d3dred() 

#define DSP_KEKA(f,t) d3keka(f,t);SCR_CLEAR;DSP_PN_DR

#define 	ER_MES_WAIT(x) 	d3errw(x)

/* ---------------------------------*/
/* DESCRIPTION of TMP FILE	    */
/*				    */
static	char	tmp_name[100] ;
#define	GET_TMP	tmpnam ( tmp_name )

/* --------------------------------*/
/* SYSTEM COMMAND	           */
/*				   */
static	int	exit_inf = NULL ;
#define	SYS_CM(a,b,c,d,e,f) strcpy(cmd_b,a);strcat(cmd_b,b);strcat(cmd_b,c);strcat(cmd_b,d);strcat(cmd_b,e);strcat(cmd_b,f);exit_inf=system(cmd_b)>>8

/* --------------------------------*/
/* FUNCTION'S PROSESS KEY          */
/*				   */
#define 	YES 	300
#define 	NO 	400
#define 	DIR 	600
#define		STOP  	700    		/* stop */
#define		CONT  	800   		/* continue */
#define		END  	900   		/* syuryo */
#define		JOKEN	910
/**********************************/
/*	select item code 	  */
/**********************************/

#define		HOZON		100
#define		FUKUGEN		200
#define		ICHIRAN		300

#define		ALL		1100
#define		ALL_DSET	1200
#define		ALL_USR 	1300
#define		ONE_GRP		1400
#define		ONE_KOBETSU_GRP	1500
#define		ONE_KOBETSU_DSET 1600
#define		ONE_KOBETSU_USR	1700

#define		MT		0
#define		FD		1

#define		ENV_MT		"Y_BACKUP_CMT"
#define		ENV_FD		"Y_BACKUP_FD"

#define 	INIT		0
#define		SINGLE		1
#define		MULT		2

#define		MSG_TBL_MAX	3000		/* max of joken kensaku kensuu*/

/*************************** eof **********************************************/
