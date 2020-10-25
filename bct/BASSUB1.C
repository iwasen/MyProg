/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include	"baslib.h"
#include	"bcbios.h"

#pragma	module	run@
void
b_run(dat)
STRTBL	*dat;
{
	extern	void	execl(char *, ...);
	char	*ptr, *w_ptr;
	char	len;

	len = dat->s_len;
	ptr = dat->s_str;
	w_ptr = b_work;

	while(len--)
		*w_ptr++ = *ptr++;
	*w_ptr = 0x00;

	execl(b_work,0);
}

#pragma	module	beep@
void
b_beep(x)
int	x;
{
	char	s;

	s = (x + 5) / 10;

	regs.c.b = 34;		/*	880 Hz		*/
	regs.c.c = s;		/*	100 msec	*/
	b_bios(&regs,BEEP);
}

#pragma	module	sound@
void
b_sound(x,y)
int	x,y;
{
	char	a,b;
	long	cl;

	a = (y + 5) / 10;

	regs.c.b = 0xff;	/*	Hz request	*/
	regs.c.c = a;		/*	100 msec	*/

	/*	13D + 3307E = 1840000 / x + 3072	*/

	cl  = 1840000l / x + 3072l;
	b   = cl / 3307l;
	cl %= 3307l;
	a   = cl / 13l;

	regs.c.d = a;
	regs.c.e = b;
	b_bios(&regs,BEEP);
}

#pragma	module	peek@
int
b_peek(x)
int	x;
{
	return((int)(*(char *)x));
}

#pragma	module	poke@
void
b_poke(x,y)
int	x,y;
{
	(*(char *)x) = (char)y;
}

#pragma	module	chr@
STRTBL
*b_chr(x)
int	x;
{
	STRTBL	*dat;

	dat = b_getarea(1);

	*(dat->s_str) = (char)x;

	return(dat);
}

#pragma	module	asc@
int
b_asc(ptr)
STRTBL	*ptr;
{
	return((int)(*(ptr->s_str)));
}

#pragma	module	val@
double
b_val(data)
STRTBL	*data;
{
	extern	int	sscanf(char *, char *, ...);
	double	d;
	char	len;
	char	*p, *dmy_p;
	char	flag;
	int	i;

	len = data->s_len;
	p = data->s_str;

	dmy_p = b_work;
	while(len--)
		*dmy_p++ = *p++;
	*dmy_p++ = 0x00;
	*dmy_p = 0x00;

	p = b_work;

	switch(*p) {
	case '&':
		p++;
		switch(*p) {
		case 'H':
		case 'h':
			flag = 16;
			p++;
			break;
		case 'O':
		case 'o':
			p++;
			/* no break */
		default:
			flag = 8;
		}
		break;
	default:
		flag = 0;
		dmy_p = p;
		while(*dmy_p)
		{
			if ((*dmy_p == 'D') || (*dmy_p == 'd'))
				*dmy_p = 'E';
			dmy_p++;
		}
	}

	switch(flag) {
	case 0:
		if (sscanf(p,"%lg",&d) != 1)
			d = 0;
		break;
	case 8:
		if (sscanf(p,"%o",&i) != 1)
			i = 0;
		d = i;
		break;
	case 16:
		if (sscanf(p,"%x",&i) != 1)
			i = 0;
		d = i;
	}
	return(d);
}

#pragma	module	hex@
STRTBL
*b_hex(x)
int	x;
{
	extern	int	sprintf(char *, char *, ...);
	STRTBL	*dat;

	dat = b_getarea(10);
	dat->s_len = (char)sprintf(dat->s_str,"%X",x);
	return(dat);
}

#pragma	module	oct@
STRTBL
*b_oct(x)
int	x;
{
	extern	int	sprintf(char *, char *, ...);
	STRTBL	*dat;

	dat = b_getarea(10);
	dat->s_len = (char)sprintf(dat->s_str,"%o",x);
	return(dat);
}

#pragma	module	cswap@
void
b_cswap(a,b)
char	*a, *b;
{
	char	wrk;

	wrk = *a;
	*a = *b;
	*b = wrk;
}

#pragma	module	iswap@
void
b_iswap(a,b)
int	*a, *b;
{
	int	wrk;

	wrk = *a;
	*a = *b;
	*b = wrk;
}

#pragma	module	lswap@
void
b_lswap(a,b)
long	*a, *b;
{
	long	wrk;

	wrk = *a;
	*a = *b;
	*b = wrk;
}

#pragma	module	fswap@
void
b_fswap(a,b)
float	*a, *b;
{
	float	wrk;

	wrk = *a;
	*a = *b;
	*b = wrk;
}

#pragma	module	dswap@
void
b_dswap(a, b)
double	*a, *b;
{
	double	wrk;

	wrk = *a;
	*a = *b;
	*b = wrk;
}

#pragma	module	sswap@
void
b_sswap(a,b)
STRTBL	*a, *b;
{
	STRTBL	wrk;

	wrk = *a;
	*a = *b;
	*b = wrk;
}

#pragma	module	abs@
double
b_abs(x)
double	x;
{
	if (x < 0)
		return(-x);
	else
		return(x);
}

#pragma	module	sgn@
int
b_sgn(x)
float	x;
{
	if (x == 0)
		return(0);
	else if (x > 0)
		return(1);
	else
		return(-1);
}

#pragma	module	int@
double
b_int(x)
double	x;
{
	long	yl;

	yl = (long)x;
	if (yl < 0)
		yl -= 1;
	return((double)yl);
}

#pragma	module	fix@
double
b_fix(x)
double	x;
{
	long	yl;

	yl = (long)x;
	return((double)yl);
}

#pragma	module	cint@
int
b_cint(x)
double	x;
{
	if (x > 0)
		x += 0.5;
	else
		x -= 0.5;

	if ((-32768 <= x) && (x <= 32767))
		return((int)x);

	b_error(E_OV);
}

#pragma	module	csng@
float
b_csng(x)
double	x;
{
	return((float)x);
}

#pragma	module	cdbl@
double
b_cdbl(x)
double	x;
{
	return(x);
}

#pragma	module	rnd@
float
b_rnd(x)
int	x;
{
	static	float	back;
	extern	void	srand(unsigned int);
	extern	int	rand(void);

	if (x == 0)
		return(back);

	if (x < 0)
		srand(-x);

	back = (float)rand();

	back /= 32768l;

	return(back);
}

#pragma	module	randm@
void
b_randomize(x)
int	x;
{
	extern	void	srand(unsigned int);

	srand(x);
}
