/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include	"baslib.h"
#include	"bcbios.h"

#pragma	module	sdate@
void
b_sdate(data)
STRTBL	*data;
{
	char	*ptr;
	char	x,y;
	char	i,j;

	for(i = 0 ; i < 7 ; i++)
		b_work[i] = 0xff;

	ptr = data->s_str;

	for(i = data->s_len , j = 0 ; i > 0 ; i -= 3 , j++)
	{
		x = *ptr++;
		y = *ptr++;
		ptr++;
		b_work[j] = ((x << 4) | (y & 0x0f));
	}

	regs.c.c = 0xff;
	regs.i.de = (int)b_work;
	b_bios(&regs,TIMDAT);
}

#pragma	module	stime@
void
b_stime(data)
STRTBL	*data;
{
	char	*ptr;
	char	x,y;
	char	i,j;

	for(i = 0 ; i < 7 ; i++)
		b_work[i] = 0xff;

	ptr = data->s_str;

	for(i = data->s_len , j = 3 ; i > 0 ; i -= 3 , j++)
	{
		x = *ptr++;
		y = *ptr++;
		ptr++;
		b_work[j] = ((x << 4) | (y & 0x0f));
	}

	regs.c.c = 0xff;
	regs.i.de = (int)b_work;
	b_bios(&regs,TIMDAT);
}

#pragma	module	sday@
void
b_sday(x)
int	x;
{
	char	i;

	for(i = 0 ; i < 7 ; i++)
		b_work[i] = 0xff;

	b_work[6] = (char)x;

	regs.c.c = 0xff;
	regs.i.de = (int)b_work;
	b_bios(&regs,TIMDAT);
}

#pragma	module	gdate@
STRTBL
*b_gdate()
{
	static	STRTBL	dat;
	static	char	timedata[9];

	char	i;
	char	*ptr;
	char	c;

	regs.c.c = 0x00;
	regs.i.de = (int)b_work;
	b_bios(&regs,TIMDAT);

	ptr = timedata;
	for(i = 0 ; i < 3 ; i++)
	{
		c = b_work[i];
		*ptr++ = (0x30 | (c >> 4));
		*ptr++ = (0x30 | (c & 0x0f));
		*ptr++ = '/';
	}

	dat.s_len = 8;
	dat.s_str = timedata;
	return(&dat);
}

#pragma	module	gtime@
STRTBL
*b_gtime()
{
	static	STRTBL	dat;
	static	char	timedata[9];

	char	i;
	char	*ptr;
	char	c;

	regs.c.c = 0x00;
	regs.i.de = (int)b_work;
	b_bios(&regs,TIMDAT);

	ptr = timedata;
	for(i = 3 ; i < 6 ; i++)
	{
		c = b_work[i];
		*ptr++ = (0x30 | (c >> 4));
		*ptr++ = (0x30 | (c & 0x0f));
		*ptr++ = ':';
	}

	dat.s_len = 8;
	dat.s_str = timedata;
	return(&dat);
}

#pragma	module	gday@
int
b_gday()
{
	regs.c.c = 0x00;
	regs.i.de = (int)b_work;
	b_bios(&regs,TIMDAT);

	return((int)b_work[6]);
}

#pragma	module	len@
int
b_len(ptr)
STRTBL	*ptr;
{
	return((int)ptr->s_len);
}

#pragma	module	left@
STRTBL
*b_left(ptr, i)
STRTBL	*ptr;
int	i;
{
	STRTBL	*dat;

	dat = b_getarea(0);

	if (ptr->s_len > i)
		dat->s_len = i;
	else
		dat->s_len = ptr->s_len;

	dat->s_str = ptr->s_str;

	return(dat);
}

#pragma	module	right@
STRTBL
*b_right(ptr, i)
STRTBL	*ptr;
int	i;
{
	STRTBL	*dat;

	dat = b_getarea(0);

	dat->s_len = ptr->s_len;
	dat->s_str = ptr->s_str;

	if (dat->s_len <= i)
		return(dat);

	dat->s_str += (dat->s_len - i);
	dat->s_len = i;

	return(dat);
}

#pragma	module	mid@
STRTBL
*b_mid(ptr, x, y)
STRTBL	*ptr;
int	x, y;
{
	STRTBL	*dat;

	if (x == 0)
	{
		b_error(E_FC);
		return;
	}

	dat = b_getarea(0);

	dat->s_len = ptr->s_len;
	dat->s_str = ptr->s_str;

	if (x > dat->s_len)
	{
		dat->s_len = 0;
		return(dat);
	}

	dat->s_len -= (x - 1);
	dat->s_str += (x - 1);

	if (dat->s_len >= y)
		dat->s_len = y;

	return(dat);
}

#pragma	module	midc@
void
b_midc(dat1,x,y,dat2)
STRTBL	*dat1, *dat2;
int	x, y;
{
	char	*p, *q;

	if ((x > dat1->s_len) || (x == 0))
	{
		b_error(E_FC);
		return;
	}

	switch(y) {
	case 0:
		return;
	case -1:
		y = dat2->s_len;
	}

	if (y > dat2->s_len)
		y = dat2->s_len;

	if (y > (dat1->s_len - x + 1))
		y = dat1->s_len - x + 1;

	p = dat1->s_str;
	p += (x - 1);
	q = dat2->s_str;

	while(y--)
		*p++ = *q++;
}

#pragma	module	space@
STRTBL
*b_space(x)
int	x;
{
	STRTBL	*dat;
	char	*p;

	if (!(0 <= x && x <= 255))
	{
		b_error(E_FC);
		return;
	}

	dat = b_getarea((char)x);
	p = dat->s_str;

	while(x--)
		*p++ = 0x20;

	return(dat);
}

#pragma	module	string@
STRTBL
*b_string(n,m,c)
int	n, m, c;
{
	STRTBL	*dat, *sp;
	char	*p;

	if (!(0 <= n && n <= 255))
	{
		b_error(E_FC);
		return;
	}

	dat = b_getarea((char)n);

	if (m != 0)
	{
		sp = (STRTBL *)c;
		c  = *(sp->s_str);
	}

	p = dat->s_str;
	while(n--)
		*p++ = (char)c;

	return(dat);
}

#pragma	module	str@
STRTBL
*b_str(d)
double	d;
{
	extern	int	sprintf(char *, char *, ...);
	STRTBL	*dat;

	dat = b_getarea(30);

	dat->s_len = (char)sprintf(dat->s_str,"% .16lg",d);

	return(dat);
}

#pragma	module	instr@
int
b_instr(s,dat1,dat2)
int	s;
STRTBL	*dat1,*dat2;
{
	extern	int	strncmp(char *, char *, int);
	extern	void	b_error(int);
	char	*ptr1,*ptr2;
	char	i;
	int	len;

	if (s == 0)
	{
		b_error(E_FC);
		return(0);
	}

	if ((s > dat1->s_len) || (dat1->s_len == 0) || (dat2->s_len == 0) || ((dat1->s_len - s + 1) < dat2->s_len))
		return(0);

	ptr1 = dat1->s_str + s - 1;
	ptr2 = dat2->s_str;
	len  = dat2->s_len;

	i = dat1->s_len - dat2->s_len - s + 2;
	while(i--)
	{
		if (strncmp(ptr1,ptr2,len) == 0)
			return(s);
		ptr1++;
		s++;
	}
	return(0);
}
