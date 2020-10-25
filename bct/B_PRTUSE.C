/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"

#define	ON	1
#define OFF	0

typedef	struct	{	char type;
			int  uppercnt;
			int  lowercnt;
			char yenflg;
			char astflg;
			char sumflg;	/* .ketazoroe shitei.	*/
			char expflg;	/* .shisuu shitei.	*/
			char signflg;
	} 	FORM;

typedef	struct	{	char sign;
			int  sumcnt;
	}	DATA;

extern  char	*strchr(char *, char);
extern	int	sprintf(char *, char *, ...);
extern	void	b_putc(FB *, char);
extern	void	b_puts(FB *, char *);

char *getform(char *, FORM *, FB *);
void getdata(DATA *, FORM *);
char strchk(char *);
void inschar(char *, char);
void delchar(char *);
char round(char *, int);
char *strend(char *);
char signproc(char *, char, char);
void formout(DATA *, FORM *);

#pragma	module	prtuse@

static void b_puts(fp, s)
FB	*fp;
char	*s;
{
	while (*s)
		b_putc(fp, *s++);
}


static char *
getform(char *s, FORM *f, FB *fp)
{
	char pointflg = OFF;
	char formcnt = 0;

	while(strchr("#.+*\\,!&@",*s) == NULL){
		if(*s == '_')	s++;
		if(*s == NULL)	return(s);
		b_putc(fp, *s++);
	}
 
	f->type = 'N';
	f->uppercnt = f->lowercnt = 0;
	f->yenflg = f->astflg = f->sumflg = f->expflg = f->signflg = OFF;

	do{
		switch(*s){
			case'#':if(pointflg)
					f->lowercnt++;
				else	
					f->uppercnt++;
				goto f;
			case'.':pointflg = ON;
				goto f;
			case'+':if(formcnt){
					f->signflg = 3;
					return(s+1);
				}
				else{
					f->signflg = 1;
					f->uppercnt++;
					goto f;
				}
			case'-':f->signflg = 2;
				return(s+1);
			case'\\':f->yenflg = ON;
				f->uppercnt++;
				goto f;
			case'*':f->astflg = ON;
				f->uppercnt++;
				goto f;
			case',':f->sumflg = ON;
				f->uppercnt++;
				goto f;
			case'^':if(*(s+1) == '^' && *(s+2) == '^' &&
				   *(s+3) == '^'){
					f->expflg = ON;
					s += 3;
					goto f;
				}
				else
					return(s);
			f:	f->type = 'F';
				break;

			case'!':f->uppercnt = 1;
				goto s;
			case'&':if(f->uppercnt = strchk(s+1)){
					s += (int)f->uppercnt;
					goto s;
				}
				else{
					b_putc(fp, *s);
					return(s+1);
				}
			case'@':f->uppercnt = 0;
			s:	f->type = 'S';
				return(s+1);

			default:return(s);
		}
		formcnt++;
	}while(*s++);
	return(s);
}

static char
strchk(p)
char *p;
{
	char i = 0;

	while(*p == ' ')
	{
		i++;
		p++;
	}
	if(*p == '&')
		return(i + 2);
	else
		return(NULL);
}

static void
getdata(DATA *d, FORM *f)
{
	int i, j;

/*	sign check	*/
	if(b_work[0] == '-')
	{
		d->sign = '-';
		delchar(b_work);
	}
	else
		d->sign = '+';

	for (i = 0; b_work[i] != '.' && b_work[i]; i++)
		;
	d->sumcnt = i;
	delchar(&(b_work[i]));

	if(f->expflg){
		for(i = 0; b_work[0] == '0'; i++)
			delchar(b_work);

		if(b_work[i]){
			d->sumcnt -= (i + f->uppercnt - 1);
			inschar(b_work, '0');
		}
		else{
			while(i--)
				inschar(b_work, '0');
			d->sumcnt = 0;
		}

		round(b_work, f->uppercnt + f->lowercnt);
	}
	else{
		if(round(b_work, d->sumcnt + f->lowercnt))
			d->sumcnt++;
	}
}

static void
inschar(char *p, char c)
{
	char d;

	do{
		d = c;
		c = *p;
		*p++ = d;
	} while (d);
}

static void
delchar(char *p)
{
	while(*p){
		*p = *(p + 1);
		p++;
	}
}

static char
round(char *s, int i)
{
	char carry;

	carry =(s[i] > '4')? 1: 0;
	s[i] = '\0';

	while (--i >= 0)
	{
		if(carry)
			if(s[i] == '9')
			{
				s[i] = '0';
				carry = 1;
			}
			else
			{
				s[i] += 1;
				carry = 0;
			}
		else 
			break;
	}

	if(carry)	inschar(s, '1');

	return((char)carry);
}

static void
formout(DATA *d, FORM *f)
{
	int  i, j;
	char n, spc, *s;

	spc = (f->astflg)? '*': ' ';

	if(f->lowercnt){
		s = strend(b_work);
		inschar(s - f->lowercnt, '.');
	}

	s = b_work;
	if(f->expflg){
		sprintf(strend(s), "E%+03d",d->sumcnt);

		if(signproc(s, d->sign, f->signflg))
			inschar(s, '%');
		else
			if(b_work[0] == '0' && f->uppercnt > 1)
				b_work[0] = ' ';
	}
	else{
		if(f->sumflg){
			for(n = 0, i = d->sumcnt - 3; i > 0; n++, i -= 3)
				inschar(&(b_work[i]), ',');
			d->sumcnt += n;
		}

		if(f->yenflg){
			inschar(b_work, '\\');
			d->sumcnt++;
		}

		if(b_work[0] == '0')
			inschar(b_work, '0');

		if(signproc(b_work, d->sign, f->signflg))
			d->sumcnt++;

		if(b_work[0] == '0')
			delchar(b_work);

		if(d->sumcnt > f->uppercnt)
			inschar(b_work, '%');
		else{
			for( i = f->uppercnt, j = d->sumcnt; i > j; j++)
				inschar(b_work, spc);
		}
	}

}

	
static char
signproc(char *s, char sign, char signflg)
{
	char carry = 0;

	switch(signflg){
		case 0: if(sign == '+')	break;
		case 1: if(*s == '0')
				*s = sign;
			else{
				inschar(s, sign);
				carry = ON;
			}
			break;

		case 2: if(sign == '+')	break;
		case 3: inschar(strend(s), sign);
			break;
 		}

	return(carry);
}

static char *
strend(char *s)
{
	while(*s)	s++;
	return(s);
}

void b_prtuse(fp, p)
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

	char	*cp, *formptr, crlf;
	int	i;
	STRTBL	*s;
	DATA	d;
	FORM	f;

	crlf = 1;
	ap.pp = &p+1;

	formptr = (*ap.sp)->s_str;
	formptr[(*ap.sp)->s_len] = '\0';
	ap.sp++;
	p++;

	while(*p){
		crlf = 1;
		switch(*p++){
			case 'c':
			case 'i':
				sprintf(b_work,"%d",*ap.ip++);
				goto lb_print;
			case 'l':
				sprintf(b_work,"%ld",*ap.lp++);
				goto lb_print;
			case 'f':
				sprintf(b_work,"%.6g",*ap.fp++);
				goto lb_print;
			case 'd':
				sprintf(b_work,"%.16lg",*ap.dp++);
lb_print:
				cp = strend(b_work);
				if(strchr(b_work, '.') == NULL)
					inschar(cp++, '.');

				for(i = 0; i < 20; i++)
					inschar(cp, '0');
				formptr = getform(formptr, &f, fp);
				getdata(&d, &f);
				formout(&d, &f);
				b_puts(fp,b_work);
				break;
			case 's':
				s = *ap.sp++;
				formptr = getform(formptr, &f, fp);
				if(f.uppercnt)
					i = f.uppercnt;
				else
					i = s->s_len;
				for (cp = s->s_str; i; --i)
					b_putc(fp, *cp++);
				break;
			case 'b':
				b_putc(fp,0x0d);
				b_putc(fp,0x0a);
				i = (*ap.ip++) - 1;
				goto spc;
			case 't':
				i = *ap.ip++;
			spc:
				if (i < 0)
					i = 0;
				while (i--)
					b_putc(fp,' ');
				break;
			case ',':
				crlf = 0;
				break;
			case ';':
				crlf = 0;
				break;
		}
	}

	b_puts(fp, formptr);

	if (crlf) {
		b_putc(fp,0x0d);
		b_putc(fp,0x0a);
	}
}
