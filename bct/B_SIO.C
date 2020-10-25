/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	char	b_dskio();
extern	void	b_lputc(char);

#pragma	module	getc@
char b_getc(fp)
FB	*fp;
{
	char	b_cgetc(void);
	char	b_fgetc(FB *);
	char	b_rsgetc(FB *);
	char	b_brgetc(FB *);
	char	b_kbgetc(void);

	if (fp == NULL)
		return(b_cgetc());	/* console input	*/

	if (fp->f_mode == 0)		/* not open ?		*/
		b_error(E_BN);

	switch (fp->f_device) {		/* check device		*/
	case 0x00:			/* file			*/
		return(b_fgetc(fp));
	case 0xf8:			/* barcode		*/
		return(b_brgetc(fp));
	case 0xfc:			/* RS232C		*/
	case 0xf9:			/* serial		*/
		return(b_rsgetc(fp));
	case 0xff:			/* keyboad		*/
		return(b_kbgetc());
	default:
		b_error(E_BF);
	}
}

char b_fgetc(fp)
FB	*fp;
{
	char	b_fgetc1(FB *);
	char	c;

	switch (fp->f_mode) {
	case INPUT:
		break;
	default:
		b_error(E_BF);
	}

	if ((c = b_fgetc1(fp)) == 0x0d) {
		switch (b_fgetc1(fp)) {
		case EOF:
		case 0x0a:
			break;
		default:
			fp->f_remchr++;
		}
	}
	return(c);
}

static char b_fgetc1(fp)
FB	*fp;
{
	char	c;

	if (fp->f_remchr == 0) {
		b_dskio(fp->f_dmabuf,26);	/* set DMA address	*/
		if (b_dskio(fp->f_fcb,20))	/* sequencial read	*/
			return(EOF);
		fp->f_lrecno++;
		fp->f_remchr = 128;
	}

	c = fp->f_dmabuf[128 - fp->f_remchr];
	if (c != EOF)
		fp->f_remchr--;
	return(c);
}

static char b_rsgetc(fp)
FB	*fp;
{
	switch (fp->f_mode) {
	case INPUT:
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	regs.c.b = 0x50;
	regs.i.hl = (int)fp->f_fcb;
	b_bios(&regs, RSIOX);		/* get		*/
	if (fp->f_fcb[0] & 0x04)	/* receive buffer over flow ? */
		b_error(E_CO);
	return(regs.c.a);
}

static char b_brgetc(fp)
FB	*fp;
{
	while (1) {
		regs.c.c = 0x02;
		b_bios(&regs, BARCODE);
		if ((regs.c.f & 0x40) == 0)
			break;
	}
	return(regs.c.a);
}

static char b_kbgetc()
{
	b_bios(&regs, CONIN);
	return(regs.c.a);
}


#pragma	module	putc@
char b_putc(fp, c)
FB	*fp;
char	c;
{
	void	b_cputc(char);
	void	b_lputc(char);
	void	b_fputc(FB *, char);
	void	b_rsputc(FB *, char);
	void	b_lpputc(char);

	if (fp == NULL) {
		b_cputc(c);		/* console output	*/
		return;
	} else if (fp == (FB *)1) {
		b_lpputc(c);		/* list output		*/
		return;
	}

	if (fp->f_mode == 0)		/* not open ?		*/
		b_error(E_BN);

	switch (fp->f_device) {		/* check device		*/
	case 0x00:			/* file			*/
		b_fputc(fp,c);
		break;
	case 0xfc:			/* RS232C		*/
	case 0xf9:			/* serial		*/
		b_rsputc(fp,c);
		break;
	case 0xfd:			/* printer		*/
		b_lpputc(c);
		break;
	case 0xfe:			/* screen		*/
		b_cputc(c);
		break;
	default:
		b_error(E_BF);
	}

	if (c >= 0x20)
		fp->f_remchr++;
	else if (c == 0x0d)
		fp->f_remchr = 0;
}

void b_fputc(fp, c)
FB	*fp;
char	c;
{
	switch (fp->f_mode) {
	case OUTPUT:
	case APPEND:
		break;
	default:
		b_error(E_BF);
	}

	if (fp->f_iptr == 128) {
		b_dskio(fp->f_dmabuf,26);	/* set DMA address	*/
		if (b_dskio(fp->f_fcb,21))	/* sequencial write	*/
			b_error(E_DF);
		fp->f_iptr = 0;
	}

	fp->f_dmabuf[fp->f_iptr++] = c;
}

static void b_rsputc(fp, c)
FB	*fp;
char	c;
{
	switch (fp->f_mode) {
	case OUTPUT:
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	while (1) {
		regs.c.b = 0x40;
		regs.i.hl = (int)fp->f_fcb;
		b_bios(&regs, RSIOX);
		if (regs.c.a == 0xff)
			break;
	}

	regs.c.b = 0x60;
	regs.c.c = c;
	regs.i.hl = (int)fp->f_fcb;
	b_bios(&regs, RSIOX);
}

static void b_lpputc(c)
char	c;
{
	b_lputc(c);
	if (c == 0x0d)
		lpos = 0;
	else if (c >= 0x20 && ++lpos == lp_width && lp_width != 255) {
		b_lputc(0x0d);
		b_lputc(0x0a);
		lpos = 0;
	}
}
