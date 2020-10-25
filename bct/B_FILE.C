/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#define	BDOS_A	(*(char (*)())5)
#define	BDOS_HL	(*(int (*)())5)

extern	char	b_dskio();
extern	void	b_setfcb(STRTBL *, char *);

#pragma	module	kill@

void b_kill(fnp)
STRTBL	*fnp;
{
	char	fcb[36];

	b_setfcb(fnp, fcb);

	if (b_dskio(fcb,19) == 0xff)		/* file delete		*/
		b_error(E_NE);
}


#pragma	module	set@

void b_set(fnp,p)
STRTBL	*fnp;
int	p;
{
	char	fcb[36];

	b_setfcb(fnp, fcb);

	if (p)
		fcb[9] |= 0x80;

	if (b_dskio(fcb,30) == 0xff)		/* set attribute	*/
		b_error(E_NE);
}


#pragma	module	attr@

STRTBL *b_attr(fnp)
STRTBL	*fnp;
{
	char	fcb[36];
	STRTBL	*sp;

	b_setfcb(fnp, fcb);

	if (b_dskio(fcb,15) == 0xff)	/* file open		*/
		b_error(E_NE);

	b_dskio(fcb,16);		/* file close		*/

	if (fcb[9] & 0x80) {
		sp = b_getarea(1);
		sp->s_str[0] = 'P';
	} else
		sp = b_getarea(0);

	return(sp);
}


#pragma	module	name@

void b_name(fnp1, fnp2)
STRTBL	*fnp1, *fnp2;
{
	char	i, fcb1[36], fcb2[36];

	b_setfcb(fnp1, fcb1);
	b_setfcb(fnp2, fcb2);

	for (i = 0; i < 12; i++)
		fcb1[i + 16] = fcb2[i];	/* copy new file name	*/

	if (b_dskio(fcb1,23) == 0xff)	/* rename		*/
		b_error(E_NE);
}


#pragma	module	dskf@

int b_dskf(sp)
STRTBL	*sp;
{
	struct dpb {
		int	spt;
		char	bsh;
		char	blm;
		char	exm;
		int	dsm;
	} *dpbp;
	char	dev, current_dev, *aloc, a, i;
	int	free_block, free_size, bln;

	if (sp->s_len != 2 || sp->s_str[1] != ':')
		b_error(E_FD);
	dev = sp->s_str[0];
	if (dev >= 'A' && dev <= 'Z')
		dev -= 'A';
	else if (dev >= 'a' && dev <= 'z')
		dev -= 'a';
	else
		b_error(E_FD);

	current_dev = BDOS_A(0,0,25);	/* get current drive No.	*/
	if (BDOS_A(0,(int)dev,14) == 0xff) /* select disk drive		*/
		b_error(E_DU);
	dpbp = (struct dpb *)BDOS_HL(0,0,31); /* get disk parameter addr*/
	aloc = (char *)BDOS_HL(0,0,27);	/* get allcation address	*/

	bln = dpbp->dsm + 1;

	free_block = 0;
	while (bln != 0) {
		a = *aloc++;
		for (i = 0; i < 8 && bln != 0; i++, bln--) {
			if ((a & 0x80) == 0)
				free_block++;
			a <<= 1;
		}
	}

	free_size = free_block << (dpbp->bsh - 3);

	BDOS_A(0,(int)current_dev,14);	/* select disk drive		*/

	return(free_size);
}
