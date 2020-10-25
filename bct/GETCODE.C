/********************************************************
*							*
*	ＢＡＳＩＣ　ＴＯ　Ｃ　トランスレータ　		*
*							*
*		キーコード取り出し			*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.02.25	*
*			file name     : getcode.c	*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<ctype.h>
#include	"trans.h"
#include	"keyword.h"
#include	"ext.h"

#ifdef	TMP_MAX
#pragma	nonrec
#endif

#define	R_DECIMAL	0
#define	R_OCTAL		1
#define	R_HEXA		2

extern	char	p2_print[];
int	strcmp(char *, char *);
int	keycheck();
void	getvtype();


void nextline()
{
	*lp = NULL;
}

void getcode()
{
	char	*wp;
	int	i_value;
	char	c, edflag, radix;

	kflag = 0;				/* clear key word flag	*/
	radix = R_DECIMAL;
	vtype = NULL;
	wp = wordbuf;
	*wp = 0;

	while (isspace(*lp) != 0)		/* space ?		*/
		lp++;				/* yes skip		*/

	switch (kcode = *lp) {
	case 0x0a:				/* NL ?			*/
	case NULL:
		if(fgets(linebuf,512,bfp) == NULL) { /* read 1 line	*/
			kcode = EOF;
			return;			/* yes return		*/
		}
		lp = linebuf;
		kcode = K_LNO;			/* keycode = line number */
		break;
	case '=':
		switch (*(++lp)) {
		case '<':
			lp++;
			kcode = K_LE;
			break;
		case '>':
			lp++;
			kcode = K_GE;
		}
		return;
	case '<':
		switch (*(++lp)) {
		case '=':
			lp++;
			kcode = K_LE;
			break;
		case '>':
			lp++;
			kcode = K_NE;
		}
		return;
	case '>':
		switch (*(++lp)) {
		case '=':
			lp++;
			kcode = K_GE;
			break;
		case '<':
			lp++;
			kcode = K_NE;
		}
		return;
	case '"':
		lp++;
		kcode = K_SCONST;
		break;
	case '&':
		radix = R_OCTAL;
		switch(*(++lp)) {
		case 'H':
		case 'h':
			radix = R_HEXA;
		case 'O':
		case 'o':
			lp++;
		}
	case '.':
		kcode = K_NCONST;
		break;
	case '?':
		lp++;
		kcode = K_PRINT;
		funcp = p2_print;
		kflag = 0x20;
		return;
	default:
		if (isdigit(*lp))		/* digit ?		*/
			kcode = K_NCONST;	/* yes			*/
		else if (isalpha(*lp))		/* alphabet ?		*/
			kcode = K_VAR;		/* yes			*/
		else {
			lp++;
			return;
		}
	}	

	edflag = 0;
	while (1) {
		c = *lp;
		switch (kcode) {
		case K_LNO:			/* line number ?	*/
			if (isdigit(c) == 0) {	/* digit ?		*/
				sscanf(wordbuf,"%d",&value);
				return;		/* yes			*/
			}
			break;
		case K_NCONST:			/* constant number	*/
			switch (radix) {
			case R_DECIMAL:		/* decimal		*/
				if (isdigit(c)) {
					edflag = 0;
					break;
				}
				switch (c) {
				case '+':
				case '-':
					if (edflag == 0)
						goto def1;
				case '.':
					edflag = 0;
					break;
				case 'D':			
					c = 'E';
				case 'E':
					edflag = 1;
					break;
				default:
				def1:
					switch (c) {
					case '%':
						vtype = V_INT;
						goto lb_nconst;
					case '@':
						vtype = V_LNG;
						goto lb_nconst;
					case '!':
						vtype = V_SNG;
						goto lb_nconst;
					case '#':
						vtype = V_DBL;
lb_nconst:
						lp++;
						break;
					}
					sscanf(wordbuf,"%lf",&value);
					return;
				}
				break;
			case R_OCTAL:		/* octal		*/
				if (c < '0' || c > '7') {
					sscanf(wordbuf,"%o",&i_value);
					goto lb_hex;
				}
				break;
			case R_HEXA:		/* hex			*/
				if (isxdigit(c) == 0) {
					sscanf(wordbuf,"%x",&i_value);
lb_hex:
					value.d = i_value;
					vtype = V_INT;
					return;
				}
				break;
			}
			break;
		case K_SCONST:			/* string constant	*/
			switch (c) {
			case '"':
			case 0x0a:
				lp++;
				return;
			case '\\':
				*wp++ = '\\';
			}
			vtype = V_STR;
			break;
		case K_VAR:
			if (c == '.')
				c = '_';
			else if (isalnum(c) == 0) {
				switch (c) {
				case '%':	
				case '@':
				case '!':
				case '#':
					if (keycheck() == 0) {
						*wp++ = c;
						*wp = 0;
						lp++;
						getvtype();
					}
					break;
				case '$':
					*wp++ = c;
					*wp = 0;
					lp++;
				default:
					if (keycheck() == 0)
						getvtype();
				}
				return;
			}
			c = toupper(c);
		}
		*wp++ = c;
		*wp = 0;
		lp++;
	}
}

/*	キーコードチェック	*/

static int keycheck()
{
	int	i1, i2, i3, st;

	i1 = -1;
	i2 = keyn;

	while (1) {
		i3 = (i1 + i2) >> 1;
		st = strcmp(keytbl[i3].keydata,wordbuf);
		if (st == 0) {
			kcode = keytbl[i3].keycode;
			funcp = keytbl[i3].funcname;
			kflag = keytbl[i3].keyflag;
			vtype = kflag & 0x07;
			if (kcode == K_USR)
				value.i = wordbuf[3] & 0x0f;
			return(1);
		}
		if (st < 0)
			i1 = i3;
		else
			i2 = i3;
		if ((i1 > i2 ? i1 - i2 : i2 - i1) < 2)
			return(0);
	}
}


/*	変数タイプ取り出し処理	*/

static void getvtype()
{
	char	*p1, *p2, *p;

	for (p1 = wordbuf, p2 = vnamebuf; isalnum(*p1) || *p1 == '_'; p1++, p2++)
		*p2 = tolower(*p1);		/* make var.name	*/
	*p2++ = '_';

	switch (*p1) {			/* check var.type		*/
	case '%':
		vtype = V_INT;
		break;
	case '@':
		vtype = V_LNG;
		break;
	case '!':
		vtype = V_SNG;
		break;
	case '#':
		vtype = V_DBL;
		break;
	case '$':
		vtype = V_STR;
		break;
	default:
		vtype = defvtype[wordbuf[0] - 'A'];
	}

	switch (vtype) {
	case V_CHR:
		*p2 = 'c';
		break;
	case V_INT:
		*p2 = 'i';
		break;
	case V_LNG:
		*p2 = 'l';
		break;
	case V_SNG:
		*p2 = 'f';
		break;
	case V_DBL:
		*p2 = 'd';
		break;
	case V_STR:
		*p2 = 's';
		break;
	}
	p2++;

	p = lp;
	while (isspace(*p) != 0)		/* space ?		*/
		p++;				/* yes skip		*/

	if (vnamebuf[0] == 'f' && vnamebuf[1] == 'n')
		kcode = K_FNX;
	else if (*p == '(') {
		kcode = K_AVAR;
		*p2++ = 'a';
	}

	*p2 = NULL;			/* set stoper			*/
}
