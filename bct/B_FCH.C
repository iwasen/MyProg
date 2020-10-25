/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#define	PUT_KEYPTR	*(int *)0xf22d
#define	GET_KEYPTR	*(int *)0xf22f
#define	LSCCPOSX	*(char *)0xf260

extern	char	b_fgetc(FB *);

#pragma	module	eof@

int b_eof(fp)
FB	*fp;
{
	int	b_feof(FB *);
	int	b_rseof(FB *);
	int	b_breof(FB *);
	int	b_kbeof(void);

	if (fp->f_mode == 0)		/* not open ?		*/
		b_error(E_BN);

	switch (fp->f_device) {		/* check device		*/
	case 0x00:			/* file			*/
		return(b_feof(fp));
	case 0xfc:			/* RS232C		*/
	case 0xf9:			/* serial		*/
		return(b_rseof(fp));
	case 0xf8:			/* barcode		*/
		return(b_breof(fp));
	case 0xff:			/* keyboad		*/
		return(b_kbeof());
	default:
		b_error(E_BF);
	}
}

static int b_feof(fp)
FB	*fp;
{
	if (b_fgetc(fp) == EOF)
		return(-1);
	fp->f_remchr++;
	return(0);
}

static int b_rseof(fp)
FB	*fp;
{
	regs.c.b = 0x30;
	regs.i.hl = (int)fp->f_fcb;
	b_bios(&regs, RSIOX);		/* insts	*/
	if (regs.c.a == 0x00)
		return(-1);
	return(0);
}

static int b_breof(fp)
FB	*fp;
{
	regs.c.c = 0x03;
	b_bios(&regs, BARCODE);		/* status	*/
	if (regs.i.bc == 0)
		return(-1);
	return(0);
}

static int b_kbeof()
{
	b_bios(&regs, CONST);
	if (regs.c.a == 0)
		return(-1);
	return(0);
}


#pragma	module	loc@

int b_loc(fp)
FB	*fp;
{
	int	b_floc(FB *);
	int	b_rsloc(FB *);
	int	b_brloc(FB *);
	int	b_kbloc(void);

	if (fp->f_mode == 0)		/* not open ?		*/
		b_error(E_BN);

	switch (fp->f_device) {		/* check device		*/
	case 0x00:			/* file			*/
		return(b_floc(fp));
	case 0xfc:			/* RS232C		*/
	case 0xf9:			/* serial		*/
		return(b_rsloc(fp));
	case 0xf8:			/* barcode		*/
		return(b_brloc(fp));
	case 0xff:			/* keyboad		*/
		return(b_kbloc());
	default:
		b_error(E_BF);
	}
}

static int b_floc(fp)
FB	*fp;
{
	return(fp->f_lrecno);
}

static int b_rsloc(fp)
FB	*fp;
{
	regs.c.b = 0x30;
	regs.i.hl = (int)fp->f_fcb;
	b_bios(&regs, RSIOX);		/* insts	*/
	return(regs.i.bc);	
}

static int b_brloc(fp)
FB	*fp;
{
	regs.c.c = 0x03;
	b_bios(&regs, BARCODE);		/* status	*/
	return(regs.i.bc);
}

static int b_kbloc()
{
	b_bios(&regs, CONST);
	if (regs.c.a == 0)
		return(0);
	return(1);
}


#pragma	module	lof@

int b_lof(fp)
FB	*fp;
{
	int	b_flof(FB *);
	int	b_rslof(FB *);
	int	b_brlof(FB *);
	int	b_kblof(void);

	if (fp->f_mode == 0)		/* not open ?	*/
		b_error(E_BN);

	switch (fp->f_device) {		/* check device		*/
	case 0x00:			/* file			*/
		return(b_flof(fp));
	case 0xfc:			/* RS232C		*/
	case 0xf9:			/* serial		*/
		return(b_rslof(fp));
	case 0xf8:			/* barcode		*/
		return(b_brlof(fp));
	case 0xff:			/* keyboad		*/
		return(b_kblof());
	default:
		b_error(E_BF);
	}
}

static int b_flof(fp)
FB	*fp;
{
	return((int)fp->f_fcb[15] * 128 / fp->f_recsiz);
}

static int b_rslof(fp)
FB	*fp;
{
	regs.c.b = 0x30;
	regs.i.hl = (int)fp->f_fcb;
	b_bios(&regs, RSIOX);		/* insts		*/
	return(128 - regs.i.bc);
}

static int b_brlof(fp)
FB	*fp;
{
	regs.c.c = 0x03;
	b_bios(&regs, BARCODE);		/* status	*/
	return(regs.i.de);
}

static int b_kblof()
{
	b_bios(&regs, CONST);
	if (regs.c.a == 0)
		return(1);
	return(0);
}


#pragma	module	pos@

int b_pos(fp)
FB	*fp;
{
	int	b_fpos(FB *);
	int	b_rspos(FB *);
	int	b_scpos(void);
	int	b_lppos(void);
	extern	FB	f_buff[];

	if (fp == f_buff)		/* file No. = 0 ?	*/
		return(b_scpos());

	if (fp->f_mode == 0)		/* not open ?	*/
		b_error(E_BN);

	switch (fp->f_device) {		/* check device		*/
	case 0x00:			/* file			*/
		return(b_fpos(fp));
	case 0xfc:			/* RS232C		*/
	case 0xf9:			/* serial		*/
		return(b_rspos(fp));
	case 0xfe:			/* screen		*/
		return(b_scpos());
	case 0xfd:			/* printer		*/
		return(b_lppos());
	default:
		b_error(E_BF);
	}
}

static int b_fpos(fp)
FB	*fp;
{
	return((int)fp->f_remchr + 1);
}

static int b_rspos(fp)
FB	*fp;
{
	return((int)fp->f_remchr + 1);
}

static int b_scpos()
{
	return((int)LSCCPOSX);
}

static int b_lppos()
{
	return((int)lpos + 1);
}


#pragma	module	lpos@

int b_lpos(dummy)
int	dummy;
{
	return((int)lpos + 1);
}
