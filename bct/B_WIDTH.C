/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#define		LSCSIZEY	(*(char *)0xf258)

extern	int	strncmp(char *, char *, int);

#pragma	module	width@

void b_width(x,y)
int	x,y;
{
	char	i;

	if (y == -1)
		y = LSCSIZEY;

	b_work[0] = 0x1b;
	b_work[1] = 0xd0;
	b_work[2] = (char)y;

	for(i = 0 ; i < 3 ; i++) {
		regs.c.c = b_work[i];
		b_bios(&regs,CONOUT);
	}
}

#pragma	module	lwidth@

void b_lwidth(cn)
int	cn;
{
	lp_width = cn;
}

#pragma	module	dwidth

void b_dwidth(dev,cn)
STRTBL	*dev;
int	cn;
{
	char	i, fnl, c;
	char	fname[16];

	for (i = 0, fnl = dev->s_len; i < 14; i++, fnl--) {
		if (fnl == 0)
			break;
		c = dev->s_str[i];
		fname[i] = (c >= 'a' && c <= 'z' ? c - 0x20 : c);
	}
	fname[i] = 0;

	if (strncmp(fname,"LPT0:",5) == 0)
		lp_width = cn;
	else if (strncmp(fname,"COM0:",5) == 0)
		cm_width[0] = cn;
	else if (strncmp(fname,"COM3:",5) == 0)
		cm_width[3] = cn;
	else
		b_error(E_FC);
}

#pragma	module	fwidth@

void b_fwidth(fp,cn)
FB	*fp;
int	cn;
{
	if (fp->f_mode == 0)
		b_error(E_BN);
	fp->f_maxcn = cn;
}
