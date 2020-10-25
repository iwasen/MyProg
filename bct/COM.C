/********************************************************
*							*
*	‚a‚`‚r‚h‚b@‚s‚n@‚b@ƒgƒ‰ƒ“ƒXƒŒ[ƒ^@		*
*							*
*		‚o‚`‚r‚r‚Q ƒRƒ}ƒ“ƒh“WŠJ‹¤’Êˆ—		*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.03.05	*
*			file name     : com.c		*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<string.h>
#include	<setjmp.h>
#include	"trans.h"
#include	"keyword.h"
#include	"ext.h"

#ifdef	TMP_MAX
#pragma	nonrec
#endif


void	indent(), indentx();
void	com(), com1();
extern	char	varout(char);
extern	void	p2_if_end();
extern	void	p2_lno();
extern	void	p2_let();
extern	void	p2_rem();

extern	jmp_buf	jmpbuf;			/* long jump buffer		*/

char	for_nest = 0, while_nest = 0;
char	then_flag = 0;
static	char	lno_flag = 0;
static	int	ln_count = 0;

/*	ƒRƒ}ƒ“ƒh“WŠJƒƒCƒ“ˆ—		*/

ot_main()
{
	char	*comp;
	void	(*func)();

	fseek(bfp, 0L, 0);

	cputs("main()\n{\n");
	tab = 1;

	indent();
	cputs("init();\n");

	getcode();
	setjmp(jmpbuf);
	while (kcode != EOF) {
		switch (kcode) {
		case K_LNO:
			p2_lno();
			continue;
		case ':':
			getcode();
			continue;
		case '\'':
		case K_DATA:
			nextline();
			getcode();
			continue;
		case K_DATE:
			com1("b_sdate:=s");
			break;
		case K_DAY:
			com1("b_sday:=i");
			break;
		case K_MID:
			com1("b_midc:(v,i[,i{-1}])=s");
			break;
		case K_SCREEN:
			com1("b_mscreen:[i{-1},i{-1}]");
			break;
		case K_TIME:
			com1("b_stime:=s");
			break;
		case K_REM:
			p2_rem();
			continue;
		case K_VAR:
		case K_AVAR:
			p2_let();
			break;
		default:
			if (kflag & 0x10) {
				comp = funcp;
				com1(comp);
			} else if (kflag & 0x20) {
				func = (void (*)())funcp;
				getcode();
				(*func)();
			} else if (kflag & 0x40) {
				nextcom();
				continue;
			} else {
				syntax_err();
				break;
			}
		}
		cputc((char)'\n');
	}
	p2_if_end();

	if (setjmp(jmpbuf) == 0) {
		if (for_nest)
			error(E_FN);
		if (while_nest)
			error(E_WE);
	}
	cputs("}\n");
}


/*	ƒRƒ}ƒ“ƒh“WŠJ‹¤’Êˆ—	*/

void com1(s)
char	*s;
{
	void	com(char *);

	getcode();
	com(s);
}

void com(s)
char	*s;
{
	char	cast, p;
	char	c;
	char	cflag, xflag, sflag, kflag1, kflag2;

	cflag = xflag = sflag = kflag1 = kflag2 = 0;
	indentx();
	while (*s != NULL && *s != ':')
		cputc((char)*s++);
	if (*s == ':')
		s++;
	cputc((char)'(');

	while (c = *s++) {
		switch (c) {
		case 'c':
			cast = V_CHR;
			break;
		case 'i':
			cast = V_INT;
			break;
		case 'l':
			cast = V_LNG;
			break;
		case 'f':
			cast = V_SNG;
			break;
		case 'd':
			cast = V_DBL;
			break;
		case 's':
			cast = V_STR;
			break;
		case 'S':
			if (kcode == K_STEP) {
				cputc((char)'1');
				getcode();
			} else if (kcode == '-')
				cputc((char)'1');
			else
				cputc((char)'0');
			goto next;
		case 'B':
			if (wordbuf[0] == 'B') {
				if (wordbuf[1] == 'F')
					cputc((char)'2');
				else
					cputc((char)'1');
				getcode();
			} else
				cputc((char)'0');
			goto next;
		case 'M':
			switch (kcode) {
			case K_PSET:
				p = '0';
				break;
			case K_PRESET:
				p = '1';
				break;
			case K_OR:
				p = '2';
				break;
			case K_AND:
				p = '3';
				break;
			case K_XOR:
				p = '4';
				break;
			default:
				if (sflag == 0)
					syntax_err();
				cputc((char)'0');
				goto next;
			}
			cputc(p);
			getcode();
			goto next;
		case 'P':
		case 'R':
		case 'W':
			if (wordbuf[0] == c) {
				cputc((char)'1');
				getcode();
			} else
				cputc((char)'0');
			goto next;
		case 'O':
			switch (kcode) {
			case K_ON:
				cputc((char)'1');
				break;
			case K_OFF:
				cputc((char)'0');
				break;
			case K_STOP:
				cputc((char)'2');
				break;
			default:
				syntax_err();
			}
			getcode();
			goto next;
		case 'v':
			varout((char)0);
			goto next;
		case 'x':
			xflag = 1;
			continue;
		case '#':
			cast = V_FILE;
			break;
		case 'A':
			kc_check(K_VAR);
			cputs(vnamebuf);
			cputc((char)'a');
			getcode();
			goto next;
		case 'T':
			if (vtype == V_STR) {
				cputc((char)'1');
				cast = V_STR;
			} else {
				cputc((char)'0');
				cast = V_INT;
			}
			cputc((char)',');
			break;
		case '[':
			sflag = 1;
			continue;
		case ']':
			sflag = 0;
			continue;
		default:
			if (kcode == '(')
				kflag1 = 1;
			else if (kcode == ')')
				kflag1 = 0;
			if (c == '(')
				kflag2 = 1;
			else if (c == ')')
				kflag2 = 0;

			if (kcode == c && kflag1 == kflag2)
				getcode();
			else if (sflag == 0)
				syntax_err();
			if (cflag) {
				cputc((char)',');
				cflag = 0;
			}
			continue;
		}
		if (xflag) {
			while (1) {
				expression(cast);
				cputc((char)',');
				if (kcode != ',')
					break;
				getcode();
			}
			cputs("-1");
			break;
		}
		if (sflag) {
			if (expr_check() == 0 || kflag1 != kflag2) {
				if (*s == '{') {
					while (*(++s) != '}')
						cputc((char)*s);
					s++;
				} else {
					cputc((char)'0');
				}
				goto next;
			}
			if (*s == '{')
				while (*s++ != '}') {}
		}
		expression(cast);
next:
		cflag = 1;
	}
	cputs(");");
}


/*	ƒ‰ƒCƒ“”Ô†ˆ—		*/

static void p2_lno()
{
	static	int	fn_no = 0;
	int	i;

	p2_if_end();

	if (o_lnsize != 0 && ln_count > o_lnsize && tab == 1) {
		fn_no++;
		fprintf(cfp,"\tjmpf(%u);\n}\n\nstatic f%u()\n{\n",fn_no,fn_no);
		ln_count = 1;
	}

	if (o_src)
		fprintf(cfp,"/*\n%s*/\n",linebuf);

	line_no = value.i;
	for (i = 0; i != lnum; i++) {
		if (lbtbl[i] == line_no) {
			fprintf(cfp,"label(%u);\n",line_no);
			break;
		}
	}
	lno_flag = 1;
	getcode();
}


void indent()
{
	char	i;

	for (i = tab; i; i--)
		cputc((char)'\t');
	ln_count++;
}


void indentx()
{
	if (lno_flag && erl_flag) {
		fprintf(cfp,"lno = %u;\n",line_no);
		lno_flag  = 0;
	}

	if (int_flag)
		cputs("b_intrpt();\n");

	if (on_err_flag) {
		switch (resume_flag) {
		case 1:
			cputs("b_setjmp();\n");
			break;
		case 2:
			cputs("if (b_setjmp() == 0)\n");
			break;
		}
	}
	indent();
}
