/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include	"baslib.h"
#include	"bcbios.h"

#pragma	module	inp@
int
b_inp(port)
int	port;
{
char	_asm_c(char *);

	_asm_c("\n\tMOV\tC,L\n");	/* LD	C,L	*/
	_asm_c("\n\tDB\t0EDH\n");	/* IN	L,(C)	*/
	_asm_c("\n\tDB\t068H\n");
	_asm_c("\n\tMVI\tH,0\n");	/* LD	H,0	*/
}

#pragma	module	out@
void
b_out(port,data)
int	port, data;
{
char	_asm_c(char *);

	_asm_c("\n\tMOV\tC,L\n");	/* LD	C,L	*/
	_asm_c("\n\tDB\t0EDH\n");	/* OUT	(C),E	*/
	_asm_c("\n\tDB\t059H\n");
}

#pragma	module	key@
void
b_key(x,ptr)
int	x;
STRTBL	*ptr;
{
	if (x == 255)
	{
		regs.c.c = 0x00;
	}
	else
	{
		regs.c.c = 0x01;
		regs.c.b = (char)x;
		regs.c.a = *(ptr->s_str);
	}
	b_bios(&regs,PUTPFK);
}

#pragma	module	touch@
void
b_touch(sx,sy,ex,ey,ptr,atr,data)
int	sx,sy,ex,ey,atr;
STRTBL	*ptr,*data;
{
	char	i;
	char	*p, *q;

	p = q = b_work;
	for(i = 0 ; i < 9*14 ; i++)
		*q++ = 0x20;

	if (ex == -1)
		ex = sx;
	if (ey == -1)
		ey = sy;

	*p++ = (char)sx;
	*p++ = (char)sy;
	*p++ = (char)(ex - sx + 1);
	*p++ = (char)(ey - sy + 1);
	*p++ = *(ptr->s_str);
	*p++ = (char)atr;
/* 87.10.14
	if (0xf6 <= *(ptr->s_str) && *(ptr->s_str) <= 0xfe)
		*p++ = 0xff;
	else
*/
		*p++ = 0x00;

	i = data->s_len;
	q = data->s_str;

	while(i--)
		*p++ = *q++;

	regs.c.c = 0x01;
	regs.i.de = (int)b_work;
	b_bios(&regs,TOUCH);
}

#pragma	module	kanji@
void	b_kanji(int, int, int, int, int, ...);
void
b_kanji(step,x,y,mode,kcode)
int	step,x,y,mode,kcode;
{
	struct	{
		int	px;
		int	py;
		int	pcode;
		char	pmode;
	}	dat;
	int	*kp;

	switch(step) {
	case 0:
		dat.px = x;
		dat.py = y;
		break;
	case 1:
		dat.px = lp.x + x;
		dat.py = lp.y + y;
	}
	lp.x = dat.px;
	lp.y = dat.py;

	dat.pmode = (char)mode;

	kp = &kcode;
	while(*kp != -1)
	{
		dat.pcode = *kp++;
		regs.c.c = 0x0b;
		regs.i.hl = (int)&dat;
		b_bios(&regs,GRAPHICS);
		dat.px += 16;
	}
}

#pragma	module	lkanji@
void	b_lkanji(int, ...);
void
b_lkanji(kcode)
int	kcode;
{
	struct	{
		char	x;
		char	y;
		char	n;
		int	pcode[9];
	}	dat;
	int	*kp;

	dat.x = dat.y = (char)0;

	kp = &kcode;
	dat.n = 0;
	while(*kp != -1)
	{
		dat.pcode[dat.n++] = *kp++;
		if (dat.n >= 9)
			break;
	}
	regs.c.b = 0x02;
	regs.i.hl = (int)&dat;
	b_bios(&regs,KANJI);
}

#pragma	module	gaiji@
void
b_gaiji(ptr)
STRTBL	*ptr;
{
	struct	{
		char	dr;
		char	file[8];
		char	ext[3];
	}	dat;
	char	i, j;
	char	*p;

	i = ptr->s_len;
	p = ptr->s_str;

	if ((i > 2) && (*(p+1) == ':'))
	{
		dat.dr = *p & 0x0f;
		i -= 2;
		p += 2;
	}
	else
	{
		dat.dr = 0x00;
	}

	for(j = 0 ; j < 8 ; j++)
		dat.file[j] = 0x20;
	for(j = 0 ; j < 3 ; j++)
		dat.ext[j] = 0x20;

	for(j = 0 ; j < i && j < 8 ; j++)
	{
		if (*p == '.')
			break;
		dat.file[j] = *p++;
	}
	if (*p == '.')
	{
		p++;
		i -= (j + 1);
		if (i < 0)
			i = 0;
	}
	else
	{
		i -= j;
	}
	if (i > 3)
		i = 3;
	j = 0;
	while(i--)
		dat.ext[j++] = *p++;

	regs.c.b = 0x00;
	regs.i.hl = (int)&dat;
	b_bios(&regs,KANJI);
}

#pragma	module	kinput@
STRTBL
*b_kinput(x)
int	x;
{
	STRTBL	*dat;

	dat = b_getarea(40);

	regs.c.b = (char)x;
	if (x == 0)
		regs.c.c = 0xff;
	regs.i.hl = (int)(dat->s_str);

	b_bios(&regs,KEYIN);

	if (regs.c.a == 0)
		dat->s_len = 0;
	else
		dat->s_len = regs.c.a - (char)1;

	return(dat);
}
