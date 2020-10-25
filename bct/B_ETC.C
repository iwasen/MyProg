/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#define	ATSHUTOFF	*(char *)0xf020
#define	ATOSTIME	*(int *)0xf021
#define	ELOFTIME	*(int *)0xf023

extern	int	b_bdos(int, int, int);

#pragma	module	call@

void b_call(addr)
int	addr;
{
	int	**p;
	char	pn;

	p = (int **)&addr+1;
	for (pn = 0; p[pn] && pn < 20; pn++)
		;
	if (pn <= 3)
		(*(void (*)())addr)(p[0],p[1],p[2]);
	else
		(*(void (*)())addr)(p[0],p[1],&p[2]);
}


#pragma	module	reset@

void b_reset()
{
	b_bdos(0,0,13);		/* disk reset	*/
}


#pragma	module	inkey@

STRTBL *b_inkey()
{
	STRTBL	*sp;

	sp = b_getarea(1);

	b_bios(&regs, CONST);
	if (regs.c.a != 0) {
		b_bios(&regs, CONIN);
		sp->s_str[0] = regs.c.a;
	} else
		sp->s_len = 0;

	return(sp);
}


#pragma	module	clear@

void b_clear(dummy, memory, stack)
int	dummy, memory, stack;
{
	extern	char	d_end;

	if ((char *)memory < &d_end)
		b_error(E_OM);
}


#pragma	module	power@

void b_power(time)
int	time;
{
	if (time == -1) {
		regs.c.c = 0;
		b_bios(&regs, POWEROFF);
	} else {
		ATSHUTOFF = time;
		ATOSTIME = time * 60;
	}
}


#pragma	module	backl@

void b_backlight(time)
int	time;
{
	switch (time) {
	case -1:
		regs.c.c = 0;
		goto back10;
	case -2:
		regs.c.c = 1;
	back10:
		b_bios(&regs, BACKLIGHT);
		break;
	default:
		ELOFTIME = time * 60;
	}
}
