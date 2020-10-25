/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: 共通サブルーチン			*
 *		ファイル名	: cmnsub.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=cmnsub
#pragma	SECTION CONST=cmnsub
#pragma	SECTION DATA=cmnsub

#include "sg_cpu.h"
#include <setjmp.h>
extern	jmp_buf	jmpbuf;

/*=======================================================================
 |
 |		ＥＥＰＲＯＭサムチェック
 |
 |	void	rom_check()
 |
 |
 =======================================================================*/
void	rom_check(void)
{
	int	i, error_flag;
	word	sum;
	word	*p;
	char	buf[5];

	if ((debug_flag & DBF_ROMCHECK) != 0)
		return;

	/* ＥＥＰＲＯＭサムチェック */
	error_flag = 0;
	for (i = 0; i < 3; i++) {
		sum = 0;
		for (p = ADR_EEPROM; p < &chksum; p++)
			sum += *p;

		check_sum = sum;	/* チェックサム保存 */

		if (sum != chksum) {
			error_flag = 1;
			break;
		}
	}

	if (error_flag == 0) {
		/* エラー無し */
		sg_bit_status &= ~BIT_SG_ROMC;
	} else {
		/* エラー有り */
		sg_bit_status |= BIT_SG_ROMC;

		/* ＲＯＭエラーメッセージ出力処理 */
		msgout("\x0d\x0a*EEPROM SUM CHECK ERROR [SUM=");
		shorttohex(sum, buf, 4);
		buf[4] = '\0';
		msgout(buf);
		msgout("]\x0d\x0a");

		if (sg_mode == SGM_PBIT)
			rom_error_flag = 1;
		else
			halt_cpu(NULL, 5);
	}
}

/*=======================================================================
 |
 |		ＳＲＡＭリード／ライトチェック
 |
 |	void	ram_check()
 |
 =======================================================================*/
void	ram_check(void)
{
	register int	i, error_flag;
	register short	w;
	register word	*p;
	char	buf[9];

	if ((debug_flag & DBF_RAMCHECK) != 0)
		return;

	/* ＳＲＡＭチェック */
	error_flag = 0;
	for (i = 0; i < 3; i++) {
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++) {
			w = *p;		/* 現在の値を保存 */

			int_disable();	/* 割り込み禁止 */

			*p = 0x5555;	/* 0x5555を書き込む */
			if (*p != 0x5555) {/* 0x5555であることをチェックする */
				error_flag = 1;
				*p = w;
				break;
			}

			*p = 0xaaaa;	/* 0xaaaaを書き込む */
			if (*p != 0xaaaa) {/* 0xaaaaであることをチェックする */
				error_flag = 1;
				*p = w;
				break;
			}

			*p = w;		/* 元に戻す */

			int_enable();	/* 割り込み許可 */
		}
		if (error_flag != 0)
			break;		/* 異常終了 */
	}

	if (error_flag == 0) {
		/* エラー無し */
		sg_bit_status &= ~BIT_SG_RAML;
	} else {
		/* エラー有り */
		sg_bit_status |= BIT_SG_RAML;

		/* ＲＡＭエラーメッセージ出力処理 */
		msgout("\x0d\x0a*SRAM R/W CHECK ERROR [ADDRESS=");
		longtohex((long)p, buf, 8);
		buf[8] = '\0';
		msgout(buf);
		msgout("]\x0d\x0a");

		halt_cpu(NULL, 20);
	}
}

/*=======================================================================
 |
 |		ＣＰＵチェック
 |
 |	void	cpu_check()
 |
 =======================================================================*/
void	cpu_check(void)
{
}

/*=======================================================================
 |
 |		プログラム停止
 |
 |	void	halt_cpu(msg, led)
 |
 |		char	*msg;		ＶＴに出力するメッセージ
 |		int	led;		ＬＥＤの点滅周期（１００ｍｓ単位）
 |
 =======================================================================*/
void	halt_cpu(char *msg, int led)
{
	long	i;

	int_disable();		/* 割り込み禁止 */

	sg_mode = SGM_HALT;

	endscr();
	if (msg != NULL)
		msgout(msg);	/* ＶＴにメッセージ出力 */

	longjmp(jmpbuf, 1);
	exit(1);
}

/*=======================================================================
 |
 |		ＭＢＩボードＢＩＴチェック開始処理
 |
 |	void	mbibit()
 |
 =======================================================================*/
void	mbibit(void)
{
//	*REG_MBI_CFCW = 0x0000;		/* set inactive */
	wait100();
//	*REG_MBI_ISW0 = 0x0000;		/* set ISW0 INT inactive */
	wait100();
//	*REG_MBI_IMSK = 0x0071;		/* set ISW0 INT & mask clear */
	wait100();
//	*REG_MBI_CFCW = 0x2000;		/* BIT start */

	/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマーセット */
	tm_mbibit = TM_MBIBIT;		/* タイマー起動 */
}

/*=======================================================================
 |
 |		割り込みマスククリア処理
 |
 |	void	mskclr()
 |
 =======================================================================*/
void	mskclr(void)
{
//	*REG_VIC_LICR1 = 0x32;		/* RS232C RX INT mask clear */
	wait100();
//	*REG_VIC_LICR2 = 0x31;		/* 10ms TIMER INT mask clear */
	wait100();
//	*REG_VIC_VICR4 = 0x04;		/* GSC DRAWING END INT mask clear */
	if (cpu == CPU_1) {
		wait100();
//		*REG_VIC_VICR7 = 0x07;	/* MBI BIT INT mask clear */
	}
}

/*=======================================================================
 |
 |		割り込みマスクセット処理
 |
 |	void	mskset()
 |
 =======================================================================*/
void	mskset(void)
{
//	*REG_VIC_LICR1 = 0xaa;		/* RS232C RX INT mask set */
	wait100();
//	*REG_VIC_LICR2 = 0xa9;		/* 10ms TIMER INT mask set */
	wait100();
//	*REG_VIC_VICR4 = 0x84;		/* GSC DRAWING END INT mask set */
	if (cpu == CPU_1) {
		wait100();
//		*REG_VIC_VICR7 = 0x87;	/* MBI BIT INT mask set */
	}
}

/*=======================================================================
 |
 |		１００μｓウエイト
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
 |		割り込み許可処理
 |
 |	void	int_enable()
 |
 =======================================================================*/
void	int_enable(void)
{
#pragma	ASM
//	ANDI.W	#0F8FFH,SR
#pragma	END_ASM
}

/*=======================================================================
 |
 |		割り込み禁止処理
 |
 |	void	int_disable()
 |
 =======================================================================*/
void	int_disable(void)
{
#pragma	ASM
//	ORI.W	#700H,SR
#pragma	END_ASM
}

/*=======================================================================
 |
 |		バイトレジスタ書き込み処理
 |
 |	void	write_byte_register(data_tbl, size)
 |
 |		BYTE_REG_DATA	*data_tbl;	レジスタ設定テーブル
 |		short	size;			テーブルサイズ
 |
 =======================================================================*/
void	write_byte_register(BYTE_REG_DATA *data_tbl, short size)
{
	int	i;

	while (size--) {
		/* データ書き込み */
//		*data_tbl->address = data_tbl->data;
		data_tbl++;		/* テーブルポインタ更新 */

		/* ウエイト */
		for (i = 0; i < data_tbl->wait_time; i++)
			;
	}
}

/*=======================================================================
 |
 |		数値フォント設定（右詰め）
 |
 |	void	tonumR(n, ptr, len, font_tbl, font_sp)
 |
 =======================================================================*/
void	tonumR(short n, short *ptr, short len,
				short *font_tbl, short font_sp)
{
	register short	i, tmp;

	for (i = 0; i < len; i++) {
		tmp = n % 10;
		*ptr++ = (n != 0 || i == 0) ? font_tbl[tmp] : font_sp;
		n /= 10;
	}
}

/*=======================================================================
 |
 |		半径をＤ／Ｐに変換
 |
 |	short	radius(r)
 |
 =======================================================================*/
short	radius(long r)
{
	register short	ibuffer, g;

	if (r == 0)
		return(0);

	g = 1;
	if (r < 0x10000) {
		while (r < 0x10000) {
			g++;
			r <<= 1;
		}
	} else  {
		if (r >= 0x20000) {
			while (r >= 0x20000) {
				g--;
				r >>= 1;
			}
		}
	}

	if (g < -8 || g > 7)
		return(0);

	ibuffer = (short)((r - 0x10000 + 0x80) >> 8);
	if (ibuffer == 256)
		ibuffer = 255;

	return((short)(0x8000 | (g << 12) | (ibuffer << 4)));
}

/*=======================================================================
 |
 |		平方根計算
 |
 |	long	_sqrt(x)
 |
 =======================================================================*/
short	_sqrt(long x)
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
 |		ＳＩＮ関数
 |
 |	short	_sin(x)
 |
 =======================================================================*/
short	_sin(short x)
{
	short	s;

	if (x < 0) {
		s = -1;
		x = -x;
		if (x < 0)
			x = 0;
	} else
		s = 1;

	if (x >= 0x4000)
		x = 0x8000 - x;

	return(sin_tbl[x >> 3] * s);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃ　ＣＨ－Ａデータ送信処理
 |
 |	void	rsa_send(buf, length)
 |
 |		char	*buf;		送信データ
 |		short	length;		送信データ長
 |
 =======================================================================*/
void	rsa_send(char *buf, short length)
{
	if (rs1_cts())
		rs1_sendreq(buf, length);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃ　ＣＨ－Ｂデータ送信処理
 |
 |	void	rsb_send(buf, length)
 |
 |		char	*buf;		送信データ
 |		short	length;		送信データ長
 |
 =======================================================================*/
void	rsb_send(char *buf, short length)
{
	while (length--) {
		putch(*buf++);
	}
}

/*=======================================================================
 |
 |		ＶＴにメッセージ送信（非割り込み処理）
 |
 |	void	msgout(msg)
 |
 |		char	*msg;		送信メッセージ
 |
 =======================================================================*/
void	msgout(char *msg)
{
	cputs(msg);
}

/*=======================================================================
 |
 |		shortから１６進文字列に変換
 |
 |	void	shorttohex(n, bp, length)
 |
 |		short	n;		short値
 |		char	*bp;		１６進文字格納バッファ
 |		short	length;		桁数
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
 |		longから１６進文字列に変換
 |
 |	void	longtohex(n, bp, length)
 |
 |		long	n;		long値
 |		char	*bp;		１６進文字格納バッファ
 |		short	length;		桁数
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

/*=======================================================================
 |
 |		可変位置ウィンドウによる文字列表示
 |
 |	void	dspstr(x, y, color, size, str)
 |
 |
 =======================================================================*/
void	dspstr(int x, int y, int color, int size, char *str)
{
	int	n;

	n = 0;
	while (*str) {
		if (n == 4) {
			n = 0;
			str_count++;
			switch (size) {
			case 0:
				x += 902;
				break;
			case 1:
				x += 1353;
				break;
			case 2:
				x += 1804;
				break;
			}
		}
		if (n == 0) {
			dp.mfd_window1[str_count].W_PX = MM_TO_DP(x);
			dp.mfd_window1[str_count].W_PY = MM_TO_DP(y);
			dp.mfd_window1[str_count].W_C = color;
			SET_PABIT(PA_MW00 + str_count);
		}
		switch (size) {
		case 0:
			dp.mfd_window1[str_count].W_A[n++] =
						font6_code[*str++];
			break;
		case 1:
			dp.mfd_window1[str_count].W_A[n++] =
						font7_code[*str++];
			break;
		case 2:
			dp.mfd_window1[str_count].W_A[n++] =
						font8_code[*str++];
			break;
		}
	}

	while (n < 4) {
		switch (size) {
		case 0:
			dp.mfd_window1[str_count].W_A[n++] = FONT6_SP;
			break;
		case 1:
			dp.mfd_window1[str_count].W_A[n++] = FONT7_SP;
			break;
		case 2:
			dp.mfd_window1[str_count].W_A[n++] = FONT8_SP;
			break;
		}
	}

	str_count++;
}

void	sendline(char *format, int data)
{
	int	*datap;
	int	mode, i, n;
	char	tmp[10];

	datap = &data;
	mode = 0;
	while (*format) {
		if (mode == 0) {
			if (*format == '%')
				mode = 1;
			else
				rsb_send(format, 1);
		} else {
			switch (*format) {
			case 'd':
				n = *datap++;
				i = 0;
				if (n < 0) {
					rsb_send("-", 1);
					n = -n;
				}
				do {
					tmp[i++] = n % 10 + '0';
					n /= 10;
				} while (n != 0);
				while (i--)
					rsb_send(&tmp[i], 1);
				break;
			case 'x':
				n = *datap++;
				for (i = 0; i < 4; i++) {
					tmp[i] = hex[n & 0xf];
					n >>= 4;
				}
				while (i--)
					rsb_send(&tmp[i], 1);
				break;
			}
			mode = 0;
		}
		format++;
	}
	rsb_send("\x0d\x0a", 2);
}
