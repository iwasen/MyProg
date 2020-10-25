/************************************************************************
 *									*
 *		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
 *									*
 *		名称		: ＲＳ－２３２Ｃ送受信割り込み処理	*
 *		ファイル名	: intrs2.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intrs2

#include "sg_cpu.h"

/*	内部関数	*/
static	void	receive_CHA(void);
static	void	send_CHA(void);
static	void	receive_CHB(void);
static	void	send_CHB(void);

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃ割り込み処理
 |
 |	void	intrs2()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intrs2(void)
{
	register byte	isr;

	for (;;) {
		/* 割り込みステータスレジスタ読み込み */
		isr = *REG_DUART_ISR;

		if (isr & rs_IMR & BIT1)	/* ＣＨ－Ａ受信割り込み？ */
			receive_CHA();
		else if (isr & rs_IMR & BIT0)	/* ＣＨ－Ａ送信可割り込み？ */
			send_CHA();
		else if (isr & rs_IMR & BIT5)	/* ＣＨ－Ｂ受信割り込み？ */
			receive_CHB();
		else if (isr & rs_IMR & BIT4)	/* ＣＨ－Ｂ送信可割り込み？ */
			send_CHB();
		else
			break;
	}
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		ＣＨ－Ａ受信割り込み処理
 |
 |	void	receive_CHA()
 |
 =======================================================================*/
static	void	receive_CHA(void)
{
	register byte	sra, ch;

	while ((sra = *REG_DUART_SRA) & BIT0) {
		if (sra & (BIT4 | BIT5 | BIT6)) {	/* 受信エラー有り？ */
			/* バッファ内データ読み捨て */
			ch = *REG_DUART_RHRA;

			/* 受信カウンタクリア */
			rsa_rcvcounter = 0;
			continue;
		}
		/* 受信データ読み込み */
		ch = *REG_DUART_RHRA;

		switch (rsa_rcvcounter) {
		case 0:		/* 第１バイト */
			switch (ch) {
			case '#':	/* ＢＩＴ結果 */
			case '*':	/* 更新速度表示 */
				rsa_rcvchar = ch;

				/* 文字間タイマーセット */
				tmc_rschar = 0;
				tmf_rschar = TIMER_START;

				/* 受信カウンタセット */
				rsa_rcvcounter = 1;
				break;
			}
			break;
		case 1:		/* 第２バイト */
			switch (rsa_rcvchar) {
			case '#':	/* ＢＩＴ結果 */
				if (bit_status == BIT_START && ch == 0x00) {
					bit_status = BIT_NORMAL;

					/* ＢＩＴ結果受信監視タイマー停止 */
					tmf_dubit = TIMER_STOP;
				}
				break;
			case '*':	/* 更新速度表示 */
				rate_dsp_flag = ch & 0x01;
				break;
			}

			/* 文字間タイマー停止 */
			tmf_rschar = TIMER_STOP;

			/* 受信カウンタクリア */
			rsa_rcvcounter = 0;
			break;
		}
	}
}

/*=======================================================================
 |
 |		ＣＨ－Ａ送信可割り込み処理
 |
 |	void	send_CHA()
 |
 =======================================================================*/
static	void	send_CHA(void)
{
	if (rsa_sndwptr == rsa_sndrptr) {
		rs_IMR &= ~BIT0;
		*REG_DUART_IMR = rs_IMR;
	} else {
		*REG_DUART_THRA = rsa_sndbuf[rsa_sndrptr++];
		if (rsa_sndrptr == RSA_SBUFSIZE)
			rsa_sndrptr = 0;
	}
}

/*=======================================================================
 |
 |		ＣＨ－Ｂ受信割り込み処理
 |
 |	void	receive_CHB()
 |
 =======================================================================*/
static	void	receive_CHB(void)
{
	register byte	srb, ch;
	char	echo[1];

	while ((srb = *REG_DUART_SRB) & BIT0) {
		if (srb & (BIT4 | BIT5 | BIT6)) {	/* 受信エラー有り？ */
			/* バッファ内データ読み捨て */
			ch = *REG_DUART_RHRB;
			continue;
		}
		/* 受信データ読み込み */
		ch = *REG_DUART_RHRB;
		echo[0] = ch;
		rsb_send(echo, 1);
		if (ch == 0x0d) {
			rsb_rcvbuf[rsb_rcvcounter] = '\0';
			if (debug_flag)
				debug_command();
			else {
				if (rsb_rcvbuf[0] == 'Y' &&
							rsb_rcvbuf[1] == 0) {
					debug_flag = 1;
					rsb_send("\x0d\x0a>", 3);
				}
			}
			rsb_rcvcounter = 0;
		} else if (ch == 0x08) {
			if (rsb_rcvcounter != 0)
				rsb_rcvcounter--;
		} else if (rsb_rcvcounter < RSB_RBUFSIZE - 1) {
			if (ch >= 0x20)
				rsb_rcvbuf[rsb_rcvcounter++] = ch;
		}
	}
}

/*=======================================================================
 |
 |		ＣＨ－Ｂ送信可割り込み処理
 |
 |	void	send_CHB()
 |
 =======================================================================*/
static	void	send_CHB(void)
{
	if (rsb_sndwptr == rsb_sndrptr) {
		rs_IMR &= ~BIT4;
		*REG_DUART_IMR = rs_IMR;
	} else {
		*REG_DUART_THRB = rsb_sndbuf[rsb_sndrptr++];
		if (rsb_sndrptr == RSB_SBUFSIZE)
			rsb_sndrptr = 0;
	}
}
