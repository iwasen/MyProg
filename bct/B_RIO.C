/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	char	b_dskio();

#pragma	module	rfio@

static void b_blkget(fp, blkno)
FB	*fp;
int	blkno;
{
	char	rst, i;
	void	b_blkput();

	if (fp->f_flag != 0 && fp->f_precno != blkno)
		b_blkput(fp, fp->f_precno);

	*(int *)&fp->f_fcb[33] = blkno;	/* set random record No.*/
	b_dskio(fp->f_dmabuf,26);	/* set DMA address	*/
	if (rst = b_dskio(fp->f_fcb,33)) {	/* random read		*/
		if (rst != 1 && rst != 4)
			b_error(E_DR);
		for (i = 0; i != 128; i++)
			fp->f_dmabuf[i] = 0;
	}
	fp->f_precno = blkno;
}

static void b_blkput(fp, blkno)
FB	*fp;
int	blkno;
{
	char	rst;

	*(int *)&fp->f_fcb[33] = blkno;	/* set random record No.*/
	b_dskio(fp->f_dmabuf,26);	/* set DMA address	*/
	if (rst = b_dskio(fp->f_fcb,34)) {	/* random write		*/
		if (rst == 2)		/* disk full ?		*/
			b_error(E_DF);
		else
			b_error(E_DW);
	}
	fp->f_precno = blkno;
	fp->f_flag = 0;
}

void b_get(fp, recno)
FB	*fp;
int	recno;
{
	int	recsiz, precno;
	long	l;
	char	offset, *rbp;

	switch (fp->f_mode) {
	case 0:
		b_error(E_BN);
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	if (recno == 0)
		recno = fp->f_lrecno + 1;

	recsiz = fp->f_recsiz;
	l = (long)(recno - 1) * recsiz;
	precno = l / 128;
	offset = l % 128;

	if (fp->f_precno != precno)
		b_blkget(fp, precno);

	for (rbp = fp->f_rbuf; recsiz; recsiz--) {
		*rbp++ = fp->f_dmabuf[offset++];
		if (offset == 128) {
			b_blkget(fp, ++precno);
			offset = 0;
		}
	}

	fp->f_lrecno = recno;
}

void b_put(fp, recno)
FB	*fp;
int	recno;
{
	int	recsiz, precno;
	long	l;
	char	offset, *rbp;

	switch (fp->f_mode) {
	case 0:
		b_error(E_BN);
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	if (recno == 0)
		recno = fp->f_lrecno + 1;

	recsiz = fp->f_recsiz;
	l = (long)(recno - 1) * recsiz;
	precno = l / 128;
	offset = l % 128;

	if (fp->f_precno != precno)
		b_blkget(fp, precno);

	for (rbp = fp->f_rbuf; recsiz; recsiz--) {
		fp->f_dmabuf[offset++] = *rbp++;
		fp->f_flag = 1;
		if (offset == 128) {
			b_blkget(fp, ++precno);
			offset = 0;
		}
	}

	fp->f_lrecno = recno;
}
