/********************************************************
*							*
*	‚a‚`‚r‚h‚b@‚s‚n@‚b@ƒgƒ‰ƒ“ƒXƒŒ[ƒ^@		*
*							*
*		ƒf[ƒ^’è‹`				*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.02.26	*
*			file name     : data.c		*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<setjmp.h>
#include	"trans.h"

char	b_file[32];		/* basic file name		*/
char	c_file[32];		/* C file name			*/

FILE	*bfp;			/* file discripter (basic file)	*/
FILE	*cfp;			/* file discripter (C file)	*/

char	o_src = 0;		/* source line output option	*/
int	o_lnsize = 0;		/* source bunkatsu line suu	*/

char	linebuf[512];		/* source line buffer		*/
char	*lp = linebuf;		/* line buffer pointer		*/
char	wordbuf[128];		/* word buffer			*/
int	kcode;			/* key code			*/
char	kflag;			/* key word flag		*/
VAL	value;			/* constant value		*/
char	vtype;			/* variable, constant type	*/

int	vnum = 0;		/* variable number		*/
VAR	vartbl[MAX_VAR];	/* variable data save area	*/	
int	cnum = 0;		/* string constant number	*/
char	*sctbl[SCTBLSIZ];	/* string constant table	*/
char	cbuf[CBUFSIZ];		/* constant & variable name save area	*/
int	cbufp = 0;		/* cbuff pointer		*/

char	defvtype[26];		/* default variable type	*/

int	dimbuf[MAX_DIM];	/* dimension save area		*/
int	dnum = 0;		/* dimension number		*/

int	file = 3;		/* max. file No.		*/
int	rbufsiz = 128;		/* random file buffer size	*/
int	strsize = 255;		/* string variable size		*/

int	lbtbl[MAX_LABEL];	/* label No. table		*/
int	lnum = 0;		/* label number			*/

char	*fntbl[MAX_DEFFN];	/* user function table		*/
char	fnum = 0;

char	vnamebuf[32];		/* variable name buffer		*/

char	tab;			/* tab position			*/

char	*funcp;			/* function name pointer	*/

int	line_no;		/* current line number		*/

char	not_flag = 0;		/* not or complement flag	*/

jmp_buf	jmpbuf;			/* long jump buffer		*/

int	err_count = 0;		/* error counter		*/
char	erl_flag = 0;		/* error line number display flag */

char	fn_flag = 0;		/* DEF FN flag			*/
char	dt_flag = 0;		/* DATA flag			*/
int	fst_data;		/* first data line number	*/

char	usrtbl[10];		/* usr table			*/

char	on_err_flag = 0;	/* ON ERROR GOTO flag		*/
char	resume_flag = 0;	/* RESUME flag			*/
char	on_key_flag = 0;	/* ON KEY(n) GOSUB flag		*/
char	int_flag = 0;		/* interrupt flag		*/
