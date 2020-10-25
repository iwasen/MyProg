/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include	"baslib.h"
#include	"bcbios.h"

#define		DSPTYPE		(*(char *)0xf2df)

#pragma	module	view@
void
b_view(sx,sy,ex,ey,cp,cb)
int	sx,sy,ex,ey,cp,cb;
{
	struct	{
		int	x1;
		int	y1;
		int	x2;
		int	y2;
		char	cp;
		char	cb;
	}	dat;

	lp.x = lp.y = 0;

	dat.x1 = sx;
	dat.y1 = sy;

	if (ex == -1 || ey == -1)
	{
		switch(DSPTYPE) {
		case 0:
			ex = 83;
			ey = 153;
			break;
		case 1:
			ex = 153;
			ey = 83;
		}
	}

	dat.x2 = ex;
	dat.y2 = ey;

	if (1 <= cp && cp <= 7)
		cp = 1;
	dat.cp = (char)cp;

	if (1 <= cb && cb <= 7)
		cb = 1;
	dat.cb = (char)cb;

	regs.c.c = 0x01;
	regs.i.hl = (int)&dat;
	b_bios(&regs,GRAPHICS);
}

#pragma	module	geta@
void
b_geta(sx,sy,step,ex,ey,save)
int	sx,sy,step,ex,ey;
char	*save;
{
	struct	{
		int	x1;
		int	y1;
		int	x2;
		int	y2;
		char	*ptr;
		int	size;
	}	dat;

	dat.x1 = sx;
	dat.y1 = sy;

	switch(step) {
	case 0:
		dat.x2 = ex;
		dat.y2 = ey;
		break;
	case 1:
		dat.x2 = sx + ex;
		dat.y2 = sy + ey;
	}

	lp.x = dat.x2;
	lp.y = dat.y2;

	dat.ptr = save;
	dat.size = 1700;

	regs.c.c = 0x09;
	regs.i.hl = (int)&dat;
	b_bios(&regs,GRAPHICS);
}

#pragma	module	puta@
void
b_puta(x,y,save,mode)
int	x,y,mode;
char	*save;
{
	struct	{
		int	x;
		int	y;
		char	*ptr;
		char	mode;
	}	dat;

	lp.x = dat.x = x;
	lp.y = dat.y = y;

	dat.ptr = save;
	dat.mode = (char)mode;

	regs.c.c = 0x0a;
	regs.i.hl = (int)&dat;
	b_bios(&regs,GRAPHICS);
}

#pragma	module	cvi@
int
b_cvi(data)
STRTBL	*data;
{
	if (data->s_len < 2)
		b_error(E_FC);

	return(*(int *)data->s_str);
}

#pragma	module	cvs@
float
b_cvs(data)
STRTBL	*data;
{
	if (data->s_len < 4)
		b_error(E_FC);

	return(*(float *)data->s_str);
}

#pragma	module	cvd@
double
b_cvd(data)
STRTBL	*data;
{
	if (data->s_len < 8)
		b_error(E_FC);

	return(*(double *)data->s_str);
}

#pragma	module	mki@
STRTBL
*b_mki(data)
int	data;
{
	STRTBL	*ptr;

	ptr = b_getarea(2);
	ptr->s_len = 2;
	*(int *)ptr->s_str = data;
	return(ptr);
}

#pragma	module	mks@
STRTBL
*b_mks(data)
float	data;
{
	STRTBL	*ptr;

	ptr = b_getarea(4);
	ptr->s_len = 4;
	*(float *)ptr->s_str = data;
	return(ptr);
}

#pragma	module	mkd@
STRTBL
*b_mkd(data)
double	data;
{
	STRTBL	*ptr;

	ptr = b_getarea(8);
	ptr->s_len = 8;
	*(double *)ptr->s_str = data;
	return(ptr);
}
