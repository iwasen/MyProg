/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#define	ALRMTP	*(char *)0xf028
#define	ALRMMSG	(char *)0xf32f

struct timdsp	{
	char	date[3];
	char	time[3];
	char	day;
	char	type;
	char	*addr;
	char	status;
};

#pragma	module	salxxx@

void b_salxxx(date,time,str,w)
STRTBL	*date, *time, *str;
int	w;
{
	void	pack(STRTBL *, char *, char);
	struct	timdsp	dat;
	char	i, *p;

	if (date == 0 || time == 0 || str == 0) {
		ALRMTP = 0;
	} else {
		pack(date,dat.date,'/');
		pack(time,dat.time,':');
		dat.day = 0xff;
		dat.type = w ? 2 : 1;
		p = b_work;
		*p++ = str->s_len;
		for (i = 0; i != str->s_len; i++)
			*p++ = str->s_str[i];
		dat.addr = b_work;
		regs.c.c = 0x82;
		regs.i.de = (int)&dat;
		b_bios(&regs, TIMDAT);
	}
}

static void pack(sp, cp, dl)
STRTBL	*sp;
char	*cp, dl;
{
	char	pack1(char);
	char	*p1, c, i;

	if (sp->s_len != 8)
		b_error(E_FC);
	for (i = 2, p1 = sp->s_str; ; i--) {
		c = pack1(*p1++);
		*cp++ = (c << 4) | pack1(*p1++);
		if (i == 0)
			break;
		if (*p1++ != dl)
			b_error(E_FC);
	}
}

static char pack1(c)
char	c;
{
	char	s;

	if (c == '*')
		s = 0x0f;
	else if (c >= '0' && c <= '9')
		s = c & 0x0f;
	else
		b_error(E_FC);
	return(s);
}


#pragma	module	galxxx@

STRTBL *b_galxxx(param)
int	param;
{
	void	unpack(char *, STRTBL *, char);
	struct	timdsp	dat;
	STRTBL	*sp;
	char	c, len, i, *p;

	regs.c.c = 0x84;
	regs.i.de = (int)&dat;
	b_bios(&regs, TIMDAT);

	switch (param) {
	case 0:
		if (dat.type == 0)
			c = 'N';
		else if (dat.status == 0)
			c = 'B';
		else
			c = 'P';
		sp = b_getarea(1);
		sp->s_str[0] = c;
		break;
	case 1:
		sp = b_getarea(8);
		unpack(dat.date,sp,'/');
		break;
	case 2:
		sp = b_getarea(8);
		unpack(dat.time,sp,':');
		break;
	case 3:
		p = ALRMMSG;
		len = *p++;
		sp = b_getarea(len);
		for (i = 0; i != len; i++)
			sp->s_str[i] = *p++;
		break;
	default:
		b_error(E_FC);
	}
	return(sp);
}

static void unpack(cp, sp, dl)
char	*cp;
STRTBL	*sp;
char	dl;
{
	char	unpack1(char);
	char	*p1, c, i;

	for (i = 2, p1 = sp->s_str; ; i--) {
		c = *cp++;
		*p1++ = unpack1(c >> 4);
		*p1++ = unpack1(c);
		if (i == 0)
			break;
		*p1++ = dl;
	}
}

static char unpack1(c)
char	c;
{
	char	s;

	c &= 0x0f;
	if (c == 0x0f)
		s = '*';
	else
		s = c | '0';
	return(s);
}
