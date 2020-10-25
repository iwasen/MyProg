/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	int	b_bdos(int, int, int);


#pragma	module	dskio@
char b_dskio(de,bc)
int	de;
int	bc;
{
	union {
		int	rst_i;
		char	rst_c[2];
	} rst;

	rst.rst_i = b_bdos(0,de,bc);
	switch (rst.rst_c[1]) {
	case 1:
		b_error(E_IO);
		break;
	case 2:
		b_error(E_DU);
		break;
	case 3:
	case 4:
		b_error(E_WP);
		break;
	}
	return(rst.rst_c[0]);
}


#pragma	module	cgetc@
char b_cgetc()
{
	char	c;

	b_bios(&regs, CONIN);
	c = regs.c.a;
	regs.c.c = c;
	b_bios(&regs, CONOUT);
	if (c == 0x0d) {
		regs.c.c = 0x0a;
		b_bios(&regs, CONOUT);
	}
	return(c);
}


#pragma	module	cputc@
void b_cputc(c)
char	c;
{
	regs.c.c = c;
	b_bios(&regs, CONOUT);
}

#pragma	module	lputc@
void b_lputc(c)
char	c;
{
	extern	char	f_hc10;

	for (;;) {
		b_bios(&regs, LISTST);
		if (regs.c.a != 0)
			break;
		if (f_hc10 != 0) {
			regs.c.c = 5;
			b_bios(&regs, INFORM);
			if (*(char *)regs.i.hl != 0)
				b_error(E_DU);
		}
	}

	regs.c.c = c;
	b_bios(&regs, LIST);
}


#pragma	module	setfcb@
void b_setfcb(fnp, fcb)
STRTBL	*fnp;
char	*fcb;
{
	char	i, c, fnl, *p, fname[16];

	for (i = 0; i < 36; i++)
		fcb[i] = 0;

	for (i = 0, fnl = fnp->s_len; i < 14; i++, fnl--) {
		if (fnl == 0)
			break;
		c = fnp->s_str[i];
		fname[i] = (c >= 'a' && c <= 'z' ? c - 0x20 : c);
	}
	fname[i] = 0;

	p = fname;
	if (*p != 0 && *(p+1) == ':') {
		fcb[0] = *p - 'A' + 1;		/* set drive No.	*/
		p += 2;
	}
	for (i = 1; i < 12; i++)
		fcb[i] = ' ';
	for (i = 1; i < 12; i++) {
		if ((c = *p++) == 0)
			break;
		else if (c == '.') {
			for (i = 9; i < 12; i++) {
				if ((c = *p++) == 0)
					break;
				fcb[i] = c;	/* set extention */
			}
			break;
		}
		fcb[i] = c;			/* set file name	*/
	}
}
