/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	int	sscanf(char *, char *, ...);
extern	char	b_getc(FB *);
extern	void	b_cputc(char);
extern	void	b_cputs(char *);
char	b_gets(FB *, char *);


#pragma	module	input@

static char b_gets(fp, s)
FB	*fp;
char	*s;
{
	char	sflag;
	char	*sp, c;

	sp = s;
	sflag = 0;

	while (1) {
		switch (c = b_getc(fp)) {
		case EOF:
			b_error(E_IE);
		case '"':
			if (sflag)
				sflag = 0;
			else
				sflag = 1;
			break;
		case 0x08:
		case 0x1d:
			if (sp != s)
				sp--;
			break;
		case 0x1c:
			sp++;
			break;
		case ',':
			if (sflag) {
				*sp++ = c;
				break;
			}
			/* no break */
		case 0x0d:
			*sp = 0;
			*(sp+1) = 0;
			return((char)(sp - s));
		default:
			*sp++ = c;
		}
	}
}

void b_input(fp, p)
FB	*fp;
char	*p;
{
	union	{
		char	**pp;
		int	**ip;
		long	**lp;
		float	**fp;
		double	**dp;
		STRTBL	**sp;
	} ap;
	char	i, len, c[128];
	STRTBL	*s;
	char	*cp,*pp;
	int	n;

start:
	pp = p;
	ap.pp = &p+1;
	if (fp == NULL) {
		if (*pp == 'p') {
			s = *ap.sp++;
			for (cp = s->s_str, i = s->s_len; i; --i)
				b_cputc(*cp++);
			pp++;
			if (*pp++ == ';') {
				b_cputc('?');
				b_cputc(' ');
			}
		} else {			
			b_cputc('?');
			b_cputc(' ');
		}

		if (cursor_flag == 0) {
			b_cputc(0x1b);
			b_cputc('3');
		}
	}

	while (*pp) {
		len = b_gets(fp,b_work);
		switch (*pp++) {
		case 'c':
		case 'i':
			**ap.ip = 0;
			n = sscanf(b_work,"%d%s",*ap.ip++,c);
			goto ncheck;
		case 'l':
			**ap.lp = 0;
			n = sscanf(b_work,"%ld%s",*ap.lp++,c);
			goto ncheck;
		case 'f':
			**ap.fp = 0;
			n = sscanf(b_work,"%g%s",*ap.fp++,c);
			goto ncheck;
		case 'd':
			**ap.dp = 0;
			n = sscanf(b_work,"%lg%s",*ap.dp++,c);
		ncheck:
			if (fp == NULL && n == 2 && c[0] != 0) {
				b_cputs("?Redo from start\x0d\x0a");
				goto start;
			}
			break;
		case 's':
			s = *ap.sp++;
			s->s_len = len;
			for (i = 0; i < len; i++)
				s->s_str[i] = b_work[i];
		}
	}

	if (fp == NULL && cursor_flag == 0) {
		b_cputc(0x1b);
		b_cputc('2');
	}
}

#pragma	module	linein@

void b_lineinp(fp, p, s)
FB	*fp;
STRTBL	*p, *s;
{
	char	i, c;

	if (p != NULL) {
		for (i = 0;i != p->s_len; i++)
			b_cputc(p->s_str[i]);
	}		

	if (fp == NULL && cursor_flag == 0) {
		b_cputc(0x1b);
		b_cputc('3');
	}

	for (i = 0; i != 255; ) {
		switch (c = b_getc(fp)) {
		case EOF:
			b_error(E_IE);
			return;
		case 0x08:
		case 0x1d:
			if (i != 0)
				i--;
			break;
		case 0x1c:
			i++;
			break;
		case 0x0d:
			goto lineinp10;
		default:
			s->s_str[i++] = c;
		}
	}
lineinp10:
	s->s_len = i;

	if (fp == NULL && cursor_flag == 0) {
		b_cputc(0x1b);
		b_cputc('2');
	}
}

#pragma	module	sinput@

STRTBL *b_sinput(len, fp)
int	len;
FB	*fp;
{
	STRTBL	*sp;
	char	*s, c;

	if (fp == NULL && cursor_flag == 0) {
		b_cputc(0x1b);
		b_cputc('3');
	}

	sp = b_getarea((char)len);
	for (s = sp->s_str; len; len--) {
		if (fp == 0) {
			b_bios(&regs, CONIN);
			c = regs.c.a;
		} else
			c = b_getc(fp);
		*s++ = c;
	}

	if (fp == NULL && cursor_flag == 0) {
		b_cputc(0x1b);
		b_cputc('2');
	}

	return(sp);
}
