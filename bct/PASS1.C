/********************************************************
*							*
*	‚a‚`‚r‚h‚b@‚s‚n@‚b@ƒgƒ‰ƒ“ƒXƒŒ[ƒ^@		*
*							*
*		‚o‚`‚r‚r‚Pˆ—				*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.02.27	*
*			file name     : pass1.c		*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<setjmp.h>
#include	"trans.h"
#include	"keyword.h"
#include	"ext.h"

#ifdef	TMP_MAX
#pragma	nonrec
#endif

extern	jmp_buf	jmpbuf;			/* long jump buffer		*/
extern	int	strcmp(char *, char *);

void	p1_init();
VAR	*p1_var();
void	p1_dim();
void	p1_def();
void	p1_deftype(char);
void	p1_sconst();
void	p1_open();
void	p1_label();
void	p1_on();
void	p1_rem();
void	p1_resume();
void	p1_then();
void	p1_data();
char	*savevar();
int	*savedim(char,int *);
char	*savesc();


/*	‚‚‚“‚“‚P ƒƒCƒ“ˆ—	*/

void pass1()
{
	printf("--- pass 1 ---\n");

	p1_init();				/* pass1 initial process */

	getcode();
	setjmp(jmpbuf);
	while (kcode != EOF) {			/* read key code	*/
		switch (kcode) {
		case EOF:
			return;
		case K_LNO:
			line_no = value.i;
			break;
		case K_VAR:
		case K_AVAR:
			p1_var()->use_flag = 1;
			break;
		case K_CALL:
			getcode();
			break;
		case K_DIM:
			p1_dim();
			continue;
		case K_DEF:
			p1_def();
			continue;
		case K_DEFINT:
			p1_deftype((char)V_INT);
			continue;
		case K_DEFSNG:
			p1_deftype((char)V_SNG);
			continue;
		case K_DEFDBL:
			p1_deftype((char)V_DBL);
			continue;
		case K_DEFSTR:
			p1_deftype((char)V_STR);
			continue;
		case K_SCONST:
			p1_sconst();
			break;
		case K_GOTO:
		case K_GOSUB:
			p1_label();
			continue;
		case K_THEN:
		case K_ELSE:
		case K_RETURN:
			p1_then();
			continue;
		case K_ON:
			p1_on();
			break;
		case K_OPTION:
			getcode();
			break;
		case K_REM:
			p1_rem();
			continue;
		case K_RESUME:
			p1_resume();
			continue;
		case K_DATA:
			p1_data();
			break;
		case K_ERL:
			erl_flag = 1;
			break;
		case '\'':
			nextline();
			break;
		}
		getcode();
	}
}


/*	‚‚‚“‚“‚P ‰Šú‰»ˆ—		*/

static void p1_init()
{
	char	i;

	for (i = 0; i != 26; i++)
		defvtype[i] = V_SNG;		/* set default variable type */

	for (i = 0; i != 10; i++)
		usrtbl[i] = 0;			/* clear usr table	*/

	nextline();
}


/*	•Ï”“o˜^ˆ—	*/

static VAR	*p1_var()
{
	static	int	dim10[1] = {10};
	VAR	*vp;

	if ((vp = srchvar()) == NULL) {		/* search variable name	*/
		if (vnum == MAX_VAR)		/* variable number over ? */
			error(E_VAROV);		/* yes			*/

		vp = &vartbl[vnum++];
		vp->vartype = vtype;		/* save type		*/
		vp->vnamep = savevar();		/* save variable name	*/
		vp->strsiz = strsize;		/* save string size	*/
		if (kcode == K_AVAR) {
			vp->dim = 1;
			vp->dimbufp = savedim((char)1,dim10);
		} else {
			vp->dim = 0;
			vp->dimbufp = 0;
		}
	}
	return(vp);
}


/*	”z—ñ“o˜^ˆ—	*/

static void p1_dim()
{
	int	dim[100];
	char	dimn;
	VAR	*vp;

	do {
		syn_check(K_AVAR);		/* variable ?		*/

		if ((vp = srchvar()) == NULL) {
			if (vnum == MAX_VAR)	/* variable number over ? */
				error(E_VAROV);	/* yes			*/
			vp = &vartbl[vnum++];
			vp->vartype = vtype;	/* save var.type	*/
			vp->vnamep = savevar();	/* save var.name	*/
			vp->strsiz = strsize;
		} else if (vp->use_flag != 0)
			error(E_DD);

		syn_check('(');			/* '(' ?		*/

		dimn = 0;
		while (1) {
			syn_check(K_NCONST);	/* number ?		*/

			dim[dimn++] = value.d;
			getcode();		/* get code		*/
 			if (kcode == ')')	/* ')' ?		*/
				break;		/* yes			*/
			kc_check(',');		/* ',' ?		*/
		}
		vp->dim = dimn;			/* save dimension number */
		vp->dimbufp = savedim(dimn,dim);
		getcode();
	} while(kcode == ',');
}


/*	‚c‚d‚e•¶ˆ—	*/

static void p1_def()
{
	getcode();
	switch (kcode) {
	case K_FNX:
		fn_flag = 1;
		break;
	case K_USR:
		usrtbl[value.i] = 1;
		break;
	}
}


/*	‚c‚d‚e‚w‚w‚wˆ—	*/

static void p1_deftype(type)
char	type;
{
	char	c;

	do {
		syn_check(K_VAR);		/* variable ?		*/

		c = wordbuf[0];			/* save var.name	*/
		defvtype[c - 'A'] = type;	/* set default ver.type	*/
		getcode();
		if (kcode == '-') {		/* '-' ?		*/
			syn_check(K_VAR);	/* variable ?		*/
			for ( c++; c <= wordbuf[0]; c++)
				defvtype[c - 'A'] = type; /* set var.type */
			getcode();
		}
	} while(kcode == ',');
}


/*	•¶š—ñ’è”ˆ—		*/

static void p1_sconst()
{
	if (srchsc() == -1) {			/* search string constant */ 
		if (cnum == SCTBLSIZ)
			error(E_SCTBLOV);
		sctbl[cnum++] = savesc();	/* save string constant	*/
	}
}


/*	”ò‚Ñæƒ‰ƒxƒ‹“o˜^ˆ—	*/

static void p1_label()
{
	void	label_sub();

	getcode();
	if (kcode == K_NCONST)
		label_sub();
}

static void label_sub()
{
	while (1) {
		kc_check(K_NCONST);		/* number ?		*/

		if (lnum == MAX_LABEL)		/* label over		*/
			error(E_LABELOV);	/* yes			*/

		if (value.d >= 65536)
			syntax_err();
		if (srchlb() == 0)
			lbtbl[lnum++] = value.d;
		getcode();
		if (kcode != ',')
			break;
		getcode();
	}
}


/*	h‚s‚g‚d‚mh•h‚d‚k‚r‚dh•h‚q‚d‚s‚t‚q‚mhˆ—	*/

static void p1_then()
{
	getcode();

	if (kcode == K_NCONST)
		label_sub();
}


/*	‚c‚`‚s‚`•¶ˆ—	*/

static void p1_data()
{
	dt_flag = 1;
	nextline();
}


/*	‚n‚m@ˆ—	*/

static void p1_on()
{
	getcode();
	switch (kcode) {
	case K_ERROR:
		on_err_flag = 1;
		break;
	case K_KEY:
		on_key_flag = 1;
	case K_COM:
	case K_BRCD:
		int_flag = 1;
		break;
	}
}


/*	‚q‚d‚r‚t‚l‚d•¶ˆ—	*/

static void p1_resume()
{
	getcode();
	switch (kcode) {
	case K_NEXT:
		if (resume_flag < 2)
			resume_flag = 2;
		break;
	case K_NCONST:
		label_sub();
		break;
	default:
		if (resume_flag < 1)
			resume_flag = 1;
	}
}


/*	‚q‚d‚l•¶ˆ—	*/

static void p1_rem()
{
	VAR	*vp;

	getcode();
	if (strcmp(wordbuf,"OPTION") == 0) {
		do {
			getcode();
			if (strcmp(wordbuf,"STRSIZE") == 0) {
				syn_check('=');
				syn_check(K_NCONST);
				strsize = value.d;
			} else if (strcmp(wordbuf,"FILE") == 0) {
				syn_check('=');
				syn_check(K_NCONST);
				file = value.d;
			} else if (strcmp(wordbuf,"RFBSIZE") == 0) {
				syn_check('=');
				syn_check(K_NCONST);
				rbufsiz = value.d;
			} else if (strcmp(wordbuf,"DEFCHR") == 0) {
				p1_deftype((char)V_CHR);
				return;
			} else if (strcmp(wordbuf,"DEFLNG") == 0) {
				p1_deftype((char)V_LNG);
				return;
			} else {
				if (kcode == K_VAR && vtype == V_STR) {
					vp = p1_var();
					syn_check('=');
					syn_check(K_NCONST);
					vp->strsiz = value.d;
				} else if (kcode == K_AVAR && vtype == V_STR) {
					vp = p1_var();
					syn_check('(');
					syn_check(')');
					syn_check('=');
					syn_check(K_NCONST);
					vp->strsiz = value.d;
				} else
					syntax_err();
			}
			getcode();
		} while (kcode == ',');
	}
	while (kcode != K_LNO && kcode != EOF)
		getcode();
}


/*	•Ï”–¼ƒZ[ƒuˆ—	*/

static char *savevar()
{
	char	i;
	char	*p;

	p = &cbuf[cbufp];
	for (i = 0; cbufset(vnamebuf[i++]); )
		;
	return(p);
}


/*	”z—ñ”ƒZ[ƒuˆ—	*/

static int *savedim(n, dn)
char	n;
int	dn[];
{
	char	i;
	int	*p;

	p = &dimbuf[dnum];
	for (i = 0; n--; i++) {
		if (dnum == MAX_DIM)
			error(E_DIMOV);

		dimbuf[dnum++] = dn[i];
	}
	return(p);
}


/*	•¶š—ñ’è”ƒZ[ƒuˆ—	*/

static char *savesc()
{
	char	i;
	char	*p;

	p = &cbuf[cbufp];
	for (i = 0; cbufset(wordbuf[i++]); )
		;
	return(p);
}
