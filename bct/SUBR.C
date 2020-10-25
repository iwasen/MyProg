/********************************************************
*							*
*	ＢＡＳＩＣ　ＴＯ　Ｃ　トランスレータ　		*
*							*
*		共通サブルーチン			*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.03.03	*
*			file name     : subr.c		*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<ctype.h>
#include	<setjmp.h>
#include	"trans.h"
#include	"keyword.h"
#include	"ext.h"

#ifdef	TMP_MAX
#pragma	nonrec
#endif

extern	jmp_buf	jmpbuf;			/* long jump buffer		*/
extern	void	exit(int);
extern	int	strcmp(char *, char *);


/*	変数名サーチ処理	*/

VAR *srchvar()
{
	int	i;
	VAR	*vp;

	for (i = 0, vp = vartbl; i != vnum; i++, vp++) {
		if (strcmp(vnamebuf, vp->vnamep) == 0)
			return(vp);
	}
	return(NULL);
}


/*	文字列定数サーチ処理	*/

int srchsc()
{
	int	i;
	char	**p;

	for (i = 0, p = sctbl; i != cnum; i++, p++) {
		if (strcmp(wordbuf, *p) == 0)
			return(i);
	}
	return(-1);
}


/*	ラベルサーチ処理	*/

char srchlb()
{
	int	i, label;

	label = value.d;
	for (i = 0; i < lnum; i++) {
		if (lbtbl[i] == label)
			return((char)1);
	}
	return(0);
}


/*	共通バッファ格納処理	*/

char cbufset(c)
char	c;
{
	if (cbufp == CBUFSIZ)
		error(E_CBUFOV);
	cbuf[cbufp++] = c;
	return(c);
}


/*	Ｃファイル１文字出力	*/

void cputc(c)
char	c;
{
	if (fputc((int)c, cfp) == -1)
		error(E_CFILE);
}


/*	Ｃファイル文字列出力	*/

void cputs(s)
char	*s;
{
	if (fputs(s, cfp) == -1)
		error(E_CFILE);
}


/*	式チェック	*/

int expr_check()
{
	switch (kcode) {
	case K_NOT:
	case K_NCONST:
	case K_SCONST:
	case K_VAR:
	case K_AVAR:
	case K_FNX:
	case K_USR:
	case K_VARPTR:
	case K_ERL:
	case K_ERR:
	case '-':
	case '(':
		break;
	default:
		if (vtype == 0)
			return(0);
	}
	return(1);
}


/*	ステートメント読み飛ばし	*/

void nextcom()
{
	while (1) {
		switch (kcode) {
		case EOF:
		case K_LNO:
		case ':':
		case '\'':
		case K_ELSE:
			return;
		}
		getcode();
	}
}


/*	型名出力	*/

void type_put(type)
char	type;
{
	switch (type) {
	case V_CHR:
		cputc((char)'c');
		break;
	case V_INT:
		cputc((char)'i');
		break;
	case V_LNG:
		cputc((char)'l');
		break;
	case V_SNG:
		cputc((char)'f');
		break;
	case V_DBL:
		cputc((char)'d');
		break;
	case V_STR:
		cputc((char)'s');
		break;
	}
}


/*	ｓｙｎｔａｘ チェック	*/

void kc_check(kc)
int	kc;
{
	if (kc != kcode)
		syntax_err();
}

void syn_check(kc)
int	kc;
{
	getcode();
	kc_check(kc);
}


void expect(kc)
int	kc;
{
	kc_check(kc);
	getcode();
}


/*	エラー処理	*/

void error(errst)
int	errst;
{
	printf("error %d in %u\n",errst,line_no);
	if (errst >= 100)
		exit(1);
	err_count++;
	nextcom();
	longjmp(jmpbuf,1);
}

void syntax_err()
{
	error(E_SN);
}

void type_err()
{
	error(E_TM);
}
