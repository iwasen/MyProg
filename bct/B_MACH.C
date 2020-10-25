/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	char	b_dskio();

#pragma	module	bload@

void b_bload(fnp, addr, r)
STRTBL	*fnp;
char	*addr;
int	r;
{
	FB	*fp;
	struct {
		char	flag;
		char	*address;
		int	len;
	} header;
	char	*p, i;
	void	(*prog)();
	char	fgetc(FB *);
	extern	FB	f_buff[];

	fp = f_buff;
	b_open1(fnp, 1, fp, 0);
	b_dskio(fp->f_dmabuf,26);	/* set DMA address	*/

	for (i = 0, p = (char *)&header; i < 5; i++)
		*p++ = fgetc(fp);
	if (header.flag != 0xfd)
		b_error(E_FC);
	if (addr == 0)
		addr = header.address;
	prog = (void (*)())addr;

	while (header.len--)
		*addr++ = fgetc(fp);
	b_close(fp, -1);

	if (r)
		(*prog)();
}

static char fgetc(fp)
FB	*fp;
{
	char	c;

	if (fp->f_remchr == 0) {
		if (b_dskio(fp->f_fcb,20))	/* sequencial read	*/
			return(EOF);
		fp->f_remchr = 128;
	}

	c = fp->f_dmabuf[128 - fp->f_remchr];
	fp->f_remchr--;
	return(c);
}


#pragma	module	bsave@

void b_bsave(fnp, addr, len)
STRTBL	*fnp;
char	*addr;
int	len;
{
	FB	*fp;
	struct {
		char	flag;
		char	*address;
		int	len;
	} header;
	char	i, *p;
	void	b_fputc(FB *, char);
	extern	FB	f_buff[];

	fp = f_buff;
	header.flag = 0xfd;
	header.address = addr;
	header.len = len;

	b_open1(fnp, 2, fp, 0);
	b_dskio(fp->f_dmabuf,26);	/* set DMA address	*/

	for (i = 0, p = (char *)&header; i < 5; i++)
		b_fputc(fp, *p++);

	while (len--)
		b_fputc(fp, *addr++);

	b_close(fp, -1);
}
