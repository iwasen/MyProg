/************************************************************************
 *									*
 *		�������g�\�����u�@�r�^�f�@�b�o�t�v���O����		*
 *									*
 *		����		: ���ʃT�u���[�`��			*
 *		�t�@�C����	: cmnsub.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=cmnsub

#include "sg_cpu.h"

/*=======================================================================
 |
 |		�d�d�o�q�n�l�T���`�F�b�N
 |
 |	void	rom_check()
 |
 |
 =======================================================================*/
void	rom_check(void)
{
	int	i;
	word	sum;
	word	*p;

	/* �d�d�o�q�n�l�T���`�F�b�N */
	for (i = 0; i < 3; i++) {
		sum = 0;
		for (p = ADR_EEPROM; p < &chksum; p++)
			sum += *p;

		check_sum = sum;	/* �`�F�b�N�T���ۑ� */

		if (debug_flag == 0) {
			if (sum != chksum)
				rom_error(sum);
		}
	}
}

/*=======================================================================
 |
 |		�d�d�o�q�n�l�T���`�F�b�N�G���[����
 |
 |	void	rom_error(sum)
 |
 |		short	sum;		�T���l
 |
 =======================================================================*/
static	void	rom_error(short sum)
{
	char	buf[5];

	msgout("\x0d\x0a*EEPROM SUM CHECK ERROR [SUM=");
	shorttohex(sum, buf, 4);
	buf[4] = '\0';
	msgout(buf);
	msgout("]\x0d\x0a");
	memory_error(5);
}

/*=======================================================================
 |
 |		�r�q�`�l���[�h�^���C�g�`�F�b�N
 |
 |	void	ram_check()
 |
 =======================================================================*/
void	ram_check(void)
{
	int	i;
	short	w;
	word	*p;

	/* �r�q�`�l�`�F�b�N */
	for (i = 0; i < 3; i++) {
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++) {
			w = *p;		/* ���݂̒l��ۑ� */

			*p = 0x5555;	/* 0x5555���������� */
			if (*p != 0x5555)/* 0x5555�ł��邱�Ƃ��`�F�b�N���� */
				ram_error(p);

			*p = 0xaaaa;	/* 0xaaaa���������� */
			if (*p != 0xaaaa)/* 0xaaaa�ł��邱�Ƃ��`�F�b�N���� */
				ram_error(p);

			*p = w;		/* ���ɖ߂� */
		}
	}
}

/*=======================================================================
 |
 |		�r�q�`�l���[�h�^���C�g�`�F�b�N�G���[����
 |
 |	void	ram_error(address)
 |
 |		word	*address;	�G���[�A�h���X
 |
 =======================================================================*/
static	void	ram_error(word *address)
{
	char	buf[9];

	msgout("\x0d\x0a*SRAM R/W CHECK ERROR [ADDRESS=");
	longtohex((long)address, buf, 8);
	buf[8] = '\0';
	msgout(buf);
	msgout("]\x0d\x0a");
	memory_error(20);
}

/*=======================================================================
 |
 |		�������G���[����
 |
 |	void	memory_error(long counter)
 |
 =======================================================================*/
void	memory_error(long counter)
{
	long	i;

	int_disable();		/* ���荞�݋֎~ */

	for (;;) {
		*REG_WDT = 0x9f;	/* �k�d�c���� */
		for (i = 0; i < counter * 25000L; i++)
			;
		*REG_WDT = 0x9d;	/* �k�d�c�_�� */
		for (i = 0; i < counter * 25000L; i++)
			;
	}
}

/*=======================================================================
 |
 |		�l�a�h�{�[�h�a�h�s�`�F�b�N�J�n����
 |
 |	void	mbibit()
 |
 =======================================================================*/
void	mbibit(void)
{
	*REG_MBI_CFCW = 0x0000;		/* set inactive */
	wait100();
	*REG_MBI_ISW0 = 0x0000;		/* set ISW0 INT inactive */
	wait100();
	*REG_MBI_IMSK = 0x0071;		/* set ISW0 INT & mask clear */
	wait100();
	*REG_MBI_CFCW = 0x2000;		/* BIT start */
}

/*=======================================================================
 |
 |		���荞�݃}�X�N�N���A����
 |
 |	void	mskclr()
 |
 =======================================================================*/
void	mskclr(void)
{
	*REG_VIC_LICR1 = 0x32;		/* RS232C RX INT mask clear */
	wait100();
	*REG_VIC_LICR2 = 0x31;		/* 10ms TIMER INT mask clear */
	wait100();
	*REG_VIC_VICR7 = 0x07;		/* MBI BIT INT mask clear */
	wait100();
	*REG_VIC_VICR4 = 0x04;		/* GSC DRAWING END INT mask clear */
}

/*=======================================================================
 |
 |		���荞�݃}�X�N�Z�b�g����
 |
 |	void	mskset()
 |
 =======================================================================*/
void	mskset(void)
{
	*REG_VIC_LICR1 = 0xaa;		/* RS232C RX INT mask set */
	wait100();
	*REG_VIC_LICR2 = 0xa9;		/* 10ms TIMER INT mask set */
	wait100();
	*REG_VIC_VICR7 = 0x87;		/* MBI BIT INT mask set */
	wait100();
	*REG_VIC_VICR4 = 0x84;		/* GSC DRAWING END INT mask set */
}

/*=======================================================================
 |
 |		�P�O�O�ʂ��E�G�C�g
 |
 |	void	wait100()
 |
 =======================================================================*/
void	wait100(void)
{
	int	i;

	for (i =0; i < 100; i++)
		;
}

/*=======================================================================
 |
 |		���荞�݋�����
 |
 |	void	int_enable()
 |
 =======================================================================*/
void	int_enable(void)
{
#pragma	ASM
	ANDI.W	#0F8FFH,SR
#pragma	END_ASM
}

/*=======================================================================
 |
 |		���荞�݋֎~����
 |
 |	void	int_disable()
 |
 =======================================================================*/
void	int_disable(void)
{
#pragma	ASM
	ORI.W	#700H,SR
#pragma	END_ASM
}

/*=======================================================================
 |
 |		�o�C�g���W�X�^�������ݏ���
 |
 |	void	write_byte_register(data_tbl, size)
 |
 |		BYTE_REG_DATA	*data_tbl;	���W�X�^�ݒ�e�[�u��
 |		short	size;			�e�[�u���T�C�Y
 |
 =======================================================================*/
void	write_byte_register(BYTE_REG_DATA *data_tbl, short size)
{
	int	i;

	while (size--) {
		/* �f�[�^�������� */
		*data_tbl->address = data_tbl->data;
		data_tbl++;		/* �e�[�u���|�C���^�X�V */

		/* �E�G�C�g */
		for (i = 0; i < data_tbl->wait_time; i++)
			;
	}
}

/*=======================================================================
 |
 |		�������v�Z
 |
 |	long	sqrt(x)
 |
 =======================================================================*/
short	sqrt(long x)
{
	long	y, z;

	y = 0;
	z = 0x8000;
	while (z) {
		if ((y + z) * (y + z) <= x)
			y += z;
		z >>= 1;
	}
	return((short)y);
}

/*=======================================================================
 |
 |		�r�h�m�֐�
 |
 |	short	sin(x)
 |
 =======================================================================*/
short	sin(short x)
{
	short	s;

	if (x < 0) {
		s = -1;
		x = -x;
	} else
		s = 1;

	while (x >= 3600)
		x -= 3600;

	if (x >= 1800) {
		s = -s;
		x -= 1800;
	}

	return(sin_tbl[x] * s);
}

/*=======================================================================
 |
 |		�b�n�r�֐�
 |
 |	short	cos(x)
 |
 =======================================================================*/
short	cos(short x)
{
	return(sin(x + 900));
}

/*=======================================================================
 |
 |		�`�q�b�s�`�m�֐�
 |
 |	short	atan(y, x)
 |
 =======================================================================*/
ushort	atan(short y, short x)
{
	if (x == 0 && y == 0)
		return(0);

	if (x >= 0) {
		if (y >= 0) {
			if (x > y)
				return(atan_tbl[(short)(y*1024L/x)]);
			else
				return(0x4000 - atan_tbl[(short)(x*1024L/y)]);
		} else {
			y = -y;
			if (x > y)
				return(-atan_tbl[(short)(y*1024L/x)]);
			else
				return(0xc000 + atan_tbl[(short)(x*1024L/y)]);
		}
	} else {
		x = -x;
		if (y >= 0) {
			if (x > y)
				return(0x8000 - atan_tbl[(short)(y*1024L/x)]);
			else
				return(0x4000 + atan_tbl[(short)(x*1024L/y)]);
		} else {
			y = -y;
			if (x > y)
				return(0x8000 + atan_tbl[(short)(y*1024L/x)]);
			else
				return(0xc000 - atan_tbl[(short)(x*1024L/y)]);
		}
	}
}

/*=======================================================================
 |
 |		���l�t�H���g�ݒ�i�E�l�߁j
 |
 |	void	tonumR(n, ptr, len, font_tbl, font_sp)
 |
 =======================================================================*/
void	tonumR(ushort n, ushort *ptr, short len,
				ushort *font_tbl, ushort font_sp)
{
	register short	i, tmp;

	ptr += len;
	for (i = 0; i < len; i++) {
		tmp = n % 10;
		*(--ptr) = (n != 0 || i == 0) ? font_tbl[tmp] : font_sp;
		n /= 10;
	}
}

/*=======================================================================
 |
 |		���l�t�H���g�ݒ�i���l�߁j
 |
 |	void	tonumL(n, ptr, len, font_tbl, font_sp)
 |
 =======================================================================*/
void	tonumL(ushort n, ushort *ptr, short len,
				ushort *font_tbl, ushort font_sp)
{
	static	short	nn[5] = {1, 10, 100, 1000, 10000};
	register short	tmp, c, f;

	c = len;
	f = 0;
	while (len--) {
		tmp = n / nn[len];
		if (f != 0 || tmp != 0 || len == 0) {
			*ptr++ = font_tbl[tmp];
			c--;
			f = 1;
		}
		n -= tmp * nn[len];
	}
	while (c) {
		*ptr++ = font_sp;
		c--;
	}
}

/*=======================================================================
 |
 |		�P�U�i�t�H���g�ݒ�
 |
 |	void	tohex(n, ptr, len, font_tbl)
 |
 =======================================================================*/
void	tohex(ushort n, ushort *ptr, short len, ushort *font_tbl)
{
	register short	i;

	ptr += len;
	for (i = 0; i < len; i++) {
		*(--ptr) = font_tbl[n & 0xf];
		n >>= 4;
	}
}

/*=======================================================================
 |
 |		���a���c�^�o�ɕϊ�
 |
 |	short	radius(r)
 |
 =======================================================================*/
short	radius(short r)
{
	register short	ibuffer, g;
	register long	buffer;

	buffer = r * 6845L / 1024;
	g = 1;
	if (buffer < 0x10000) {
		while (buffer < 0x10000) {
			g++;
			buffer *= 2;
		}
	} else  {
		if (buffer >= 0x20000) {
			while (buffer >= 0x20000) {
				g--;
				buffer /= 2;
			}
		}
	}

	ibuffer = (short)((buffer - 0x10000 + 0x80) >> 8);
	if (ibuffer == 256)
		ibuffer = 255;

	return((short)(0x8000 | (g << 12) | (ibuffer << 4)));
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�@�b�g�|�`�f�[�^���M����
 |
 |	void	rsa_send(buf, length)
 |
 |		char	*buf;		���M�f�[�^
 |		short	length;		���M�f�[�^��
 |
 =======================================================================*/
void	rsa_send(char *buf, short length)
{
	/* ���M���荞�݃}�X�N */
	rs_IMR &= ~BIT0;
	*REG_DUART_IMR = rs_IMR;

	while (length--) {
		rsa_sndbuf[rsa_sndwptr++] = *buf++;
		if (rsa_sndwptr == RSA_SBUFSIZE)
			rsa_sndwptr = 0;
	}

	/* ���M���荞�݃}�X�N���� */
	rs_IMR |= BIT0;
	*REG_DUART_IMR = rs_IMR;
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�@�b�g�|�a�f�[�^���M����
 |
 |	void	rsb_send(buf, length)
 |
 |		char	*buf;		���M�f�[�^
 |		short	length;		���M�f�[�^��
 |
 =======================================================================*/
void	rsb_send(char *buf, short length)
{
	/* ���M���荞�݃}�X�N */
	rs_IMR &= ~BIT4;
	*REG_DUART_IMR = rs_IMR;

	while (length--) {
		rsb_sndbuf[rsb_sndwptr++] = *buf++;
		if (rsb_sndwptr == RSB_SBUFSIZE)
			rsb_sndwptr = 0;
	}

	/* ���M���荞�݃}�X�N���� */
	rs_IMR |= BIT4;
	*REG_DUART_IMR = rs_IMR;
}

void	msgout(char *msg)
{
	short	timer;

	/* ���b�Z�[�W���M */
	while (*msg) {
		for (timer = 0; timer < 10000; timer++) {
			if (*REG_DUART_SRB & BIT2) {
				*REG_DUART_THRB = *msg;
				break;
			}
		}
		msg++;
	}
}

/*=======================================================================
 |
 |		short����P�U�i������ɕϊ�
 |
 |	void	shorttohex(n, bp, length)
 |
 |		short	n;		short�l
 |		char	*bp;		�P�U�i�����i�[�o�b�t�@
 |		short	length;		����
 |
 =======================================================================*/
void	shorttohex(short n, char *bp, short length)
{
	bp += length;
	while (length--) {
		*(--bp) = hex[n & 0xf];
		n >>= 4;
	}
}

/*=======================================================================
 |
 |		long����P�U�i������ɕϊ�
 |
 |	void	longtohex(n, bp, length)
 |
 |		long	n;		long�l
 |		char	*bp;		�P�U�i�����i�[�o�b�t�@
 |		short	length;		����
 |
 =======================================================================*/
void	longtohex(long n, char *bp, short length)
{
	bp += length;
	while (length--) {
		*(--bp) = hex[n & 0xf];
		n >>= 4;
	}
}