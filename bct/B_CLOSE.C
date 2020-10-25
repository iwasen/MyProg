/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	void	b_fputc(FB *, char);
extern	char	b_dskio();

#pragma	module	close@

void b_close(fp)
FB	*fp;
{
	void	b_fclose(FB *);
	void	b_rsclose(FB *);
	void	b_brclose(FB *);

	FB	**fpp;

	fpp = &fp;

	while ((fp = *fpp++) != (FB *)-1) {
		if (fp->f_mode != 0) {
			switch (fp->f_device) {	/* check device	*/
			case 0x00:
				b_fclose(fp);
				break;
			case 0xfc:		/* RS232C		*/
			case 0xf9:		/* serial		*/
				b_rsclose(fp);
				break;
			case 0xf8:		/* barcode		*/
				b_brclose(fp);
				break;
			}
			fp->f_mode = 0;
		}
	}
}

static void b_fclose(fp)
FB	*fp;
{
	switch (fp->f_mode) {
	case 2:
	case 8:
		b_fputc(fp,EOF);
		b_dskio(fp->f_dmabuf,26);/* set DMA address	*/
		if (b_dskio(fp->f_fcb,21))/* sequencial write	*/
			b_error(E_DF);
		break;
	case 4:
		if (fp->f_flag == 0)
			break;
		*(int *)&fp->f_fcb[33] = fp->f_precno;
		b_dskio(fp->f_dmabuf,26);/* set DMA address	*/
		if (b_dskio(fp->f_fcb,34))/* random write	*/
			b_error(E_DW);
		break;
	}
	b_dskio(fp->f_fcb,16);	/* file close		*/
}
static void b_rsclose(fp)
FB	*fp;
{
	regs.c.b = 0x20;
	regs.i.hl = (int)fp->f_fcb;
	b_bios(&regs, RSIOX);		/* close		*/
}

static void b_brclose(fp)
FB	*fp;
{
	regs.c.c = 0x01;
	b_bios(&regs, BARCODE);
}
