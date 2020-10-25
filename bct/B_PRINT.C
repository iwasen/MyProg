/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"

extern	int	sprintf(char *, char *, ...);
extern	void	b_putc(FB *, char);

#pragma	module	print@

void b_print(fp, p)
FB	*fp;
char	*p;
{
	union	{
		char	**pp;
		int	*ip;
		long	*lp;
		double	*fp;
		double	*dp;
		STRTBL	**sp;
	} ap;
	int	i;
	STRTBL	*s;
	char	*cp, flag;

	flag = 1;
	ap.pp = &p+1;
	while (*p) {
		flag = 1;
		switch (*p++) {
		case 'c':
		case 'i':
			sprintf(b_work,"% d ",*ap.ip++);
			goto lb_print;
		case 'l':
			sprintf(b_work,"% ld ",*ap.lp++);
			goto lb_print;
		case 'f':
			sprintf(b_work,"% .6g ",*ap.fp++);
			goto lb_print;
		case 'd':
			sprintf(b_work,"% .16lg ",*ap.dp++);
lb_print:
			for (cp = b_work; *cp; cp++)
				b_putc(fp,*cp);
			break;
		case 's':
			s = *ap.sp++;
			for (cp = s->s_str, i = s->s_len; i; --i)
				b_putc(fp,*cp++);
			break;
		case 't':
			b_putc(fp,0x0d);
			i = (*ap.ip++) - 1;
			if (i < 0)
				i = 0;
			while (i--)
				b_putc(fp,0x1c);
			break;
		case 'b':
			i = *ap.ip++;
			if (i < 0)
				i = 0;
			while (i--)
				b_putc(fp,' ');
			break;
		case 'p':
			ap.sp++;
			break;
		case ',':
			b_putc(fp,0x0d);
			b_putc(fp,0x0a);
			flag = 0;
			break;
		case ';':
			flag = 0;
			break;
		}
	}
	if (flag) {
		b_putc(fp,0x0d);
		b_putc(fp,0x0a);
	}
}


#pragma	module	write@

void b_write(fp,p)
FB	*fp;
char	*p;
{
	union	{
		char	**pp;
		int	*ip;
		long	*lp;
		double	*fp;
		double	*dp;
		STRTBL	**sp;
	} ap;
	int	i;
	STRTBL	*s;
	char	*cp;

	ap.pp = &p+1;
	while (1) {
		switch (*p++) {
		case 'c':
		case 'i':
			sprintf(b_work,"%d",*ap.ip++);
			goto lb_write;
		case 'l':
			sprintf(b_work,"%ld",*ap.lp++);
			goto lb_write;
		case 'f':
			sprintf(b_work,"%.6g",*ap.fp++);
			goto lb_write;
		case 'd':
			sprintf(b_work,"%.16lg",*ap.dp++);
lb_write:
			for (cp = b_work; *cp; cp++)
				b_putc(fp,*cp);
			break;
		case 's':
			b_putc(fp,'"');
			s = *ap.sp++;
			for (cp = s->s_str, i = s->s_len; i; --i)
				b_putc(fp,*cp++);
			b_putc(fp,'"');
			break;
		}
		if (*p == 0)
			break;
		b_putc(fp,',');
	}
	b_putc(fp,0x0d);
	b_putc(fp,0x0a);
}
