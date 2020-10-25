#pragma SECTION PROG=dummy
#include "sg_cpu.h"
ushort	bit_tbl[16] = {
	0x0001, 0x0002, 0x0004, 0x0008, 0x0010, 0x0020, 0x0040, 0x0080,
	0x0100, 0x0200, 0x0400, 0x0800, 0x1000, 0x2000, 0x4000, 0x8000
};
short	debug_flag = 1;
void	msgout(char *);
void	calc_dp()
{
}

#pragma INTERRUPT
void	int10ms()
{
}
#pragma INTERRUPT-

#pragma INTERRUPT
void	intmbi_tx()
{
}
#pragma INTERRUPT-

#pragma INTERRUPT
void	intgsc()
{
	word	ack;

/*	msgout("*");*/
	ack = *REG_GSC_ACK;		/* INT6 ACK */
}
#pragma INTERRUPT-

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

void	int_enable(void)
{

#pragma	ASM
	ANDI.W	#0F8FFH,SR
#pragma	END_ASM
}

void	int_disable(void)
{
#pragma	ASM
	ORI.W	#700H,SR
#pragma	END_ASM
}

void	rsb_send(char *buf, short length)
{
	/* 送信可割り込みマスク */
	rs_IMR &= ~BIT4;
	*REG_DUART_IMR = rs_IMR;

	while (length--) {
		rsb_sndbuf[rsb_sndwptr++] = *buf++;
		if (rsb_sndwptr == RSB_SBUFSIZE)
			rsb_sndwptr = 0;
	}

	/* 送信可割り込みマスク解除 */
	rs_IMR |= BIT4;
	*REG_DUART_IMR = rs_IMR;
}

void	rom_check()
{
}

void	ram_check()
{
}

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

void	write_byte_register(BYTE_REG_DATA *data_tbl, short size)
{
	short	i;

	while (size--) {
		/* データ書き込み */
		*data_tbl->address = data_tbl->data;
		data_tbl++;		/* テーブルポインタ更新 */

		/* ウエイト */
		for (i = 0; i < data_tbl->wait_time; i++)
			;
	}
}

void	wait100(void)
{
	short	i;

	for (i =0; i < 100; i++)
		;
}

void	inpdata()
{
	short	srb, c;
	char	buf[6];
	long	ll;

	for(;;) {
		srb = *REG_DUART_SRB;
		c = ((srb >> 4) & 0xf) + 0x30;
		if (c > 0x39)
			c += 7;
		buf[0] = c;

		c = (srb & 0xf) + 0x30;
		if (c > 0x39)
			c += 7;
		buf[1] = c;

		buf[2] = 0x0d;
		buf[3] = 0x0a;
		buf[4] = 0;
		/*msgout(buf);*/
		for (ll = 0; ll < 500000; ll++);
	}
}

void	msgout(char *msg)
{
	short	timer;

	/* メッセージ送信 */
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
void	shortout(short n)
{
	char	buf[8];
	short	c;

	c = ((n >> 12) & 0xf) + 0x30;
	if (c > 0x39)
		c += 7;
	buf[0] = c;

	c = ((n >> 8) & 0xf) + 0x30;
	if (c > 0x39)
		c += 7;
	buf[1] = c;

	c = ((n >> 4) & 0xf) + 0x30;
	if (c > 0x39)
		c += 7;
	buf[2] = c;

	c = (n & 0xf) + 0x30;
	if (c > 0x39)
		c += 7;
	buf[3] = c;

	buf[4] = 0;
	msgout(buf);
}
