/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include	"baslib.h"
#include	"bcbios.h"

#define		LSCCPOSX	(*(char *)0xf260)
#define		LSCCPOSY	(*(char *)0xf261)
#define		LWDPOSY		(*(char *)0xf259)
#define		LWDSIZEY	(*(char *)0xf25a)

extern	void	b_cursor_on(), b_cursor_off();
extern	void	b_cputc(char);

#pragma	module	cls@
void
b_cls(x)
int	x;
{
	switch(x) {
	case 0:
		b_cputc(0x0c);
		break;
	case 1:
		b_work[0] = 0x00;
		regs.c.c = 0x02;
		regs.i.hl = (int)b_work;
		b_bios(&regs,GRAPHICS);
	}
}

#pragma	module	locate@
void
b_locate(x,y,sw)
int	x, y, sw;
{
	b_cputc(0x1b);
	b_cputc('=');
	b_cputc(0x1f + (char)y);
	b_cputc(0x1f + (char)x);

	switch(sw) {
	case 0:
		b_cursor_off();
		break;
	case 1:
		b_cursor_on();
	}
}

#pragma	module	csrlin@
int
b_csrlin()
{
	return((int)LSCCPOSY);
}

#pragma	module	mscrn@
void
b_mscreen(a,b)
int	a,b;
{
	if (a == 0 || a == 1)
	{
		b_cputc(0x1b);
		b_cputc(0xda);
		b_cputc((char)a);
	}

	if (b == 0 || b == 1)
	{
		b_cputc(0x1b);
		b_cputc(0xd1);
		b_cputc((char)b);
	}
}

#pragma	module	screen@
int
b_screen(x,y,op)
int	x,y,op;
{
	regs.c.b = (char)x;
	regs.c.c = (char)y;
	b_bios(&regs,RDVRAM);

	if (op == 1)
		return((int)regs.c.h);

	return((int)regs.c.l);
}

#pragma	module	window@
void
b_window(x,y)
int	x,y;
{
	if (x == -1)
		x = LWDPOSY;

	if (y == -1)
		y = LWDSIZEY;

	b_cputc(0x1b);
	b_cputc(0xd8);
	b_cputc((char)x);
	b_cputc((char)y);
}

#pragma	module	copy@
void
b_copy()
{
	b_bios(&regs,SCRNDUMP);
}

#pragma	module	color@
void
b_color(x)
int	x;
{
	b_cputc(0x1b);
	b_cputc(0xd9);
	b_cputc((char)x);
}

#pragma	module	pset@
void
b_pset(step,x,y,clr)
int	step, x, y, clr;
{
	struct	{
		int	dx;
		int	dy;
		char	dc;
	} dat;

	switch(step) {
	case 0:
		dat.dx = x;
		dat.dy = y;
		break;
	case 1:
		dat.dx = x + lp.x;
		dat.dy = y + lp.y;
	}

	lp.x = dat.dx;
	lp.y = dat.dy;

	if (clr == 0)
		dat.dc = 0;
	else
		dat.dc = 0x01;

	regs.c.c = 0x03;
	regs.i.hl = (int)&dat;
	b_bios(&regs,GRAPHICS);
}

#pragma	module	preset@
void
b_preset(step,x,y,clr)
int	step, x, y, clr;
{
	struct	{
		int	dx;
		int	dy;
		char	dc;
	} dat;

	switch(step) {
	case 0:
		dat.dx = x;
		dat.dy = y;
		break;
	case 1:
		dat.dx = x + lp.x;
		dat.dy = y + lp.y;
	}

	lp.x = dat.dx;
	lp.y = dat.dy;

	if (clr == 0)
		dat.dc = 0;
	else
		dat.dc = 0x01;

	regs.c.c = 0x03;
	regs.i.hl = (int)&dat;
	b_bios(&regs,GRAPHICS);
}

#pragma	module	line@
void
b_line(step1, sx, sy, step2, ex, ey, clr, op, style)
int	step1, sx, sy, step2, ex, ey, clr, op, style;
{
	struct	{
		int	sx;
		int	sy;
		int	ex;
		int	ey;
		char	clr;
		char	op;
		int	style;
	} dat;

	switch(step1) {
	case 0:
		dat.sx = sx;
		dat.sy = sy;
		break;
	case 1:
		dat.sx = lp.x + sx;
		dat.sy = lp.y + sy;
	}
	lp.x = dat.sx;
	lp.y = dat.sy;

	switch(step2) {
	case 0:
		dat.ex = ex;
		dat.ey = ey;
		break;
	case 1:
		dat.ex = lp.x + ex;
		dat.ey = lp.y + ey;
	}
	lp.x = dat.ex;
	lp.y = dat.ey;

	if (clr == 0)
		dat.clr = 0;
	else
		dat.clr = 0x01;

	dat.op = (char)op;
	dat.style = style;

	regs.c.c = 0x05;
	regs.i.hl = (int)&dat;
	b_bios(&regs,GRAPHICS);
}

#pragma	module	circle@
void
b_circle(step, x, y, r, clr, sr, er, per)
int	step, x, y, r, clr;
float	sr, er, per;
{
	struct	{
		int	cx;
		int	cy;
		int	rx;
		int	ry;
		char	clr;
		char	f1;
		char	f2;
		int	ts;
		int	te;
	} dat;

	switch(step) {
	case 0:
		dat.cx = x;
		dat.cy = y;
		break;
	case 1:
		dat.cx = lp.x + x;
		dat.cy = lp.y + y;
	}

	lp.x = dat.cx;
	lp.y = dat.cy;

	if (per > 1)
	{
		dat.rx = r / per;
		dat.ry = r;
	}
	else
	{
		dat.rx = r;
		dat.ry = r * per;
	}

	if (clr == 0)
		dat.clr = 0;
	else
		dat.clr = 0x01;

	dat.f1 = 0;

	if (sr < 0)
	{
		sr *= -1;
		dat.f1 |= 0x01;
	}
	dat.ts = 32768l * sr / 3.14;

	if (er < 0)
	{
		er *= -1;
		dat.f1 |= 0x80;
	}
	dat.te = 32768l * er / 3.14;

	if (dat.ts == dat.te)
		dat.f2 = 0x01;
	else
		dat.f2 = 0;

	regs.c.c = 0x06;
	regs.i.hl = (int)&dat;
	b_bios(&regs,GRAPHICS);
}

#pragma	module	paint@
void
b_paint(step, x, y, f, a, b)
int	step, x, y, f, a, b;
{
	struct	dpk1 {
		char	cp;
		char	cb;
		char	*ws;
		int	wl;
	};
	struct dpk2 {
		char	l;
		char	*tf;
		char	*tb;
		char	cb;
		char	*ws;
		int	wl;
	};
	struct	{
		int	x;
		int	y;
		union {
			struct	dpk1	dp1;
			struct	dpk2	dp2;
		} dp;
	} dat;
	STRTBL	*tp;
	char	i, bt[64];

	switch(step) {
	case 0:
		dat.x = x;
		dat.y = y;
		break;
	case 1:
		dat.x = lp.x + x;
		dat.y = lp.y + y;
	}

	lp.x = dat.x;
	lp.y = dat.y;

	if (f == 0) {
		dat.dp.dp1.cp = a ? 1 : 0;
		dat.dp.dp1.cb = b ? 1 : 0;
		dat.dp.dp1.ws = b_work;
		dat.dp.dp1.wl = 256;

		regs.c.c = 0x07;
	} else {
		tp = (STRTBL *)a;
		for (i = 0; i < tp->s_len; i++)
			bt[i] = 0;
		dat.dp.dp2.l = tp->s_len;
		dat.dp.dp2.tf = tp->s_str;
		dat.dp.dp2.tb = bt;
		dat.dp.dp2.cb = b ? 1 : 0;
		dat.dp.dp2.ws = b_work;
		dat.dp.dp2.wl = 256;
		regs.c.c = 0x08;
	}
	regs.i.hl = (int)&dat;
	b_bios(&regs,GRAPHICS);
}

#pragma	module	point@
int
b_point(x,y)
int	x, y;
{
	struct	{
		int	x;
		int	y;
	} dat;

	dat.x = x;
	dat.y = y;

	regs.c.c = 0x04;
	regs.i.hl = (int)&dat;
	b_bios(&regs, GRAPHICS);

	if (regs.c.a == 0x02)
		return(-1);
	return((int)regs.c.c);
}
