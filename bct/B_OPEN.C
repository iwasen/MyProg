/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	int	strncmp(char *, char *, int);
extern	char	b_fgetc(FB *);
extern	char	b_dskio();

#pragma	module	open1@

void b_open1(fnp, mode, fp, recsiz)
STRTBL	*fnp;
int	mode;
FB	*fp;
int	recsiz;
{
	void	b_fopen(char *, int, FB *, int);
	void	b_rsopen(char *, int, FB *, int);
	void	b_bropen(char *, int, FB *);
	void	b_kbopen(int, FB *);
	void	b_scopen(int, FB *);
	void	b_lpopen(int, FB *);

	char	i, fnl, c;
	char	fname[16], *p;

	if (fp->f_mode != 0)
		b_error(E_AO);

	for (p = (char *)fp, i = 0xba; i; i--)
		*p++ = 0;			/* clear file buffer	*/

	for (i = 0, fnl = fnp->s_len; i < 14; i++, fnl--) {
		if (fnl == 0)
			break;
		c = fnp->s_str[i];
		fname[i] = (c >= 'a' && c <= 'z' ? c - 0x20 : c);
	}
	fname[i] = 0;

	if (strncmp(fname,"COM0:",5) == 0)
		b_rsopen(fname,mode,fp,0);
	else if (strncmp(fname,"COM3:",5) == 0)
		b_rsopen(fname,mode,fp,3);
	else if (strncmp(fname,"BRCD:",5) == 0)
		b_bropen(fname,mode,fp);
	else if (strncmp(fname,"KYBD:",5) == 0)
		b_kbopen(mode,fp);
	else if (strncmp(fname,"SCRN:",5) == 0)
		b_scopen(mode,fp);
	else if (strncmp(fname,"LPT0:",5) == 0)
		b_lpopen(mode,fp);
	else
		b_fopen(fname,mode,fp,recsiz);

	fp->f_mode = mode;
}

static void b_fopen(fname, mode, fp, recsiz)
char	*fname;
int	mode;
FB	*fp;
int	recsiz;
{
	char	i, c;
	char	*p;

	p = fname;
	if (*p != 0 && *(p+1) == ':') {
		fp->f_fcb[0] = *p - 'A' + 1;	/* set drive No.	*/
		p += 2;
	}
	for (i = 1; i < 12; i++)
		fp->f_fcb[i] = ' ';
	for (i = 1; i < 12; i++) {
		if ((c = *p++) == 0)
			break;
		else if (c == '.') {
			for (i = 9; i < 12; i++) {
				if ((c = *p++) == 0)
					break;
				fp->f_fcb[i] = c;	/* set extention */
			}
			break;
		}
		fp->f_fcb[i] = c;		/* set file name	*/
	}

	fp->f_recsiz = recsiz;			/* set record length	*/

	if (b_dskio(fp->f_fcb,15) == 0xff) {	/* file open		*/
		if (mode == INPUT)
			b_error(E_NE);
		if (b_dskio(fp->f_fcb,22) == 0xff) /* file make	*/
			b_error(E_DF);
	} else {
		if (mode == APPEND) {		/* append mode ?	*/
			fp->f_mode = INPUT;
			while (b_fgetc(fp) != EOF)
				;
			--fp->f_fcb[32];
			fp->f_iptr = 128 - fp->f_remchr;
		}
	}
	fp->f_precno = -1;
}

static void b_rsopen(fname, mode, fp, lineno)
char	*fname;
int	mode;
FB	*fp;
int	lineno;
{
	struct	rsprm {
		char	*rs_buf;
		int	rs_bufsz;
		char	rs_bitrate;
		char	rs_chrlen;
		char	rs_parity;
		char	rs_stopbit;
		char	rs_spcprm;
	} *rsp;
	char	bitrate;
	char	chrlen;
	char	parity;
	char	stopbit;
	char	spcprm;
	char	*p, c, cntline;

	switch (mode) {
	case INPUT:
	case OUTPUT:
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	if (lineno == 0)
		fp->f_device = 0xfc;
	else if (lineno == 3)
		fp->f_device = 0xf9;

	p = fname + 5;

	bitrate = 0x0d;
	chrlen = 0x03;
	parity = 0x00;
	stopbit = 0x03;
	spcprm = 0x73;
	cntline = 'F';

	if (*p != 0) {
		if (*p++ != '(')
			b_error(E_FD);
	} else
		goto rs_open10;

	if (*p != ')') {
		if ((c = *p++) == '0')
			bitrate = 0x81;
		else if (c == '1')
			bitrate = 0x80;
		else if (c >= '2' && c <= 9)
			bitrate = (c & 0x0f);
		else if (c >= 'A' && c <= 'G')
			bitrate = (c & 0x0f) + 9;
		else if (c != ' ')
			b_error(E_FD);
	}

	if (*p != ')') {
		switch (*p++) {
		case ' ':
			break;
		case '7':
			chrlen = 2;
			break;
		case '8':
			chrlen = 3;
			break;
		default:
			b_error(E_FD);
		}
	}

	if (*p != ')') {
		switch (*p++) {
		case ' ':
			break;
		case 'N':
			parity = 0x00;
			break;
		case 'E':
			parity = 0x03;
			break;
		case 'O':
			parity = 0x01;
			break;
		default:
			b_error(E_FD);
		}
	}

	if (*p != ')') {
		switch (*p++) {
		case ' ':
			break;
		case '1':
			stopbit = 0x01;
			break;
		case '3':
			stopbit = 0x03;
			break;
		default:
			b_error(E_FD);
		}
	}

	if (*p != ')') {
		if ((c = *p++) != ' ')
			cntline = c;
	}

	if (*p != ')') {
		switch (*p++) {
		case ' ':
		case 'N':
			break;
		case 'X':
			spcprm &= ~0x10;
			break;
		case 'D':
			spcprm &= ~0x20;
			break;
		case 'R':
			spcprm &= ~0x40;
			break;
		default:
			b_error(E_FD);
		}
	}

	if (*p != ')') {
		switch (*p++) {
		case ' ':
			break;
		case 'S':
			spcprm &= ~0x04;
			break;
		case 'N':
			spcprm |= 0x04;
			break;
		default:
			b_error(E_FD);
		}
	}

	if (*p != ')')
		b_error(E_FD);
rs_open10:
	rsp = (struct rsprm *)fp->f_fcb;
	rsp->rs_buf = fp->f_dmabuf;
	rsp->rs_bufsz = 128;
	rsp->rs_bitrate = bitrate;
	rsp->rs_chrlen = chrlen;
	rsp->rs_parity = parity;
	rsp->rs_stopbit = stopbit;
	rsp->rs_spcprm = spcprm;
	fp->f_flag = cntline;

	regs.c.b = lineno + 0x10;
	regs.i.hl = (int)rsp;
	b_bios(&regs, RSIOX);
}


static void b_bropen(fname, mode, fp)
char	*fname;
int	mode;
FB	*fp;
{
	char	code, param, *p;

	switch (mode) {
	case INPUT:
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	fp->f_device = 0xf8;

	p = fname + 5;

	code = 1;
	param = 0x00;

	if (*p != 0) {
		if (*p++ != '(')
			b_error(E_FD);
	} else
		goto br_open10;

	if (*p != ')') {
		if (*p < '1' || *p > '4')
			b_error(E_FD);
		code = (*p++ & 0x0f);
	}

	while (*p != ')') {
		switch (*p++) {
		case 'B':
			param |= 0x80;
			break;
		case 'L':
			param |= 0x20;
			break;
		case 'P':
			param |= 0x40;
			break;
		case 'C':
			param |= 0x01;
			break;
		case 'F':
			param |= 0x02;
			break;
		case 'Z':
			param |= 0x04;
			break;
		default:
			b_error(E_FD);
		}
	}
br_open10:
	regs.c.c = 0x00;
	regs.c.a = code;
	regs.c.d = param;
	regs.c.e = 0x0d;
	b_bios(&regs, BARCODE);
}

static void b_kbopen(mode,fp)
int	mode;
FB	*fp;
{
	switch (mode) {
	case INPUT:
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	fp->f_device = 0xff;
}

static void b_scopen(mode,fp)
int	mode;
FB	*fp;
{
	switch (mode) {
	case OUTPUT:
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	fp->f_device = 0xfe;
}

static void b_lpopen(mode,fp)
int	mode;
FB	*fp;
{
	switch (mode) {
	case OUTPUT:
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	fp->f_device = 0xfd;
}


#pragma	module	open2@

void b_open2(modep, fp, fnp, recsiz)
STRTBL	*modep;
FB	*fp;
STRTBL	*fnp;
int	recsiz;
{
	int	mode;

	if (modep->s_len == 0) {
		b_error(E_BF);
	}
	
	switch (modep->s_str[0]) {
	case 'I':
	case 'i':
		mode = INPUT;
		break;
	case 'O':
	case 'o':
		mode = OUTPUT;
		break;
	case 'R':
	case 'r':
		mode = RANDOM;
		break;
	case 'A':
	case 'a':
		mode = APPEND;
		break;
	default:
		b_error(E_BF);
	}
	b_open1(fnp, mode, fp, recsiz);
}
