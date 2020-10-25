/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: ＲＳ－２３２Ｃ送受信割り込み処理	*
 *		ファイル名	: intrs2.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intrs2
#pragma	SECTION	CONST=intrs2
#pragma	SECTION	DATA=intrs2

#include "sg_cpu.h"

/*	内部関数	*/
	void	receive_CHA(void);
static	void	receive_pbit_result(void);
static	void	receive_ibit_result(long);
static	void	receive_rbit_result(void);
static	void	bezel_sw_status(void);
static	void	debug_menu(long);
static	void	send_CHA(void);
	void	receive_CHB(void);
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
void	receive_CHA(int	ch)
{
	byte	sr;
	int	i;
	long	bezel_sw, bezel_sw_on;

//	sr = *REG_DUART_SRA;	/* ステータスレジスタ読み込み */
	sr = ch >> 8;
//	ch = *REG_DUART_RHRA;	/* 受信データ読み込み */

	/* 受信エラーチェック */
	if (sr & (BIT4 | BIT5 | BIT6)) {
		/* ＲＳ２３２Ｃパリティーエラー */
		sg_bit_status |= BIT_SG_232P;

		/* 受信カウンタクリア */
		rsa_rcvcounter = 0;
	} else {
		/* ＲＳ２３２Ｃパリティー正常 */
		sg_bit_status &= ~BIT_SG_232P;

		du_rcv_data[rsa_rcvcounter++] = ch;
		if (echo_mode == ECHO_WAIT) {
			if (rsa_rcvcounter == 6) {
				/* 文字間タイマー停止 */
				tm_duchar = TM_STOP;

				/* 受信カウンタクリア */
				rsa_rcvcounter = 0;

				/* データ受信フラグセット */
				du_rcvflag = 1;

				for (i = 0; i < 6; i++) {
					if (du_send_data[i] != du_rcv_data[i])
						break;
				}
				if (i == 6)
					echo_status = ECHO_OK;
				else
					echo_status = ECHO_NG;

				echo_mode = ECHO_END;
			} else {
				/* 文字間タイマーセット */
				tm_duchar = TM_DUCHAR;
			}
		} else {
			if (rsa_rcvcounter == 10) {
				/* 文字間タイマー停止 */
				tm_duchar = TM_STOP;

				/* 受信カウンタクリア */
				rsa_rcvcounter = 0;

				/* データ受信フラグセット */
				du_rcvflag = 1;

				/* Ｄ／ＵＢＩＴステータスセット */
				du_bit_status = (ushort)du_rcv_data[4] << 8 |
						(ushort)du_rcv_data[5];

				/* ベゼルスイッチステータスセット */
				bezel_sw_status();

				/* 押されたスイッチの検出 */
				bezel_sw = (long)du_rcv_data[1] |
					(long)du_rcv_data[0] << 8 |
					(long)(du_rcv_data[3] & 0x0f) << 16 |
					(long)du_rcv_data[2] << 20;
				bezel_sw_on = (bezel_sw ^ bezel_sw_old) &
								bezel_sw;
				bezel_sw_old = bezel_sw;

				switch (sg_mode) {
				case SGM_PBIT:
					receive_pbit_result();
					break;
				case SGM_IBIT:
					receive_ibit_result(bezel_sw_on);
					break;
				case SGM_RBIT:
					receive_rbit_result();
					break;
				case SGM_NORMAL:
					break;
				case SGM_DEBUG:
					debug_menu(bezel_sw_on);
					break;
				}
			} else {
				/* 文字間タイマーセット */
				tm_duchar = TM_DUCHAR;
			}
		}
	}
}

/*=======================================================================
 |
 |		POWER ON BIT 結果受信処理
 |
 |	void	receive_pbit_result()
 |
 =======================================================================*/
static	void	receive_pbit_result(void)
{
	if (du_rcv_data[3] & 0x20) {
		debug_mode = DBM_DBGMENU;
		sg_mode = SGM_DEBUG;
	} else {
		if (rom_error_flag)
			halt_cpu(NULL, 5);

		if (du_rcv_data[3] & 0x10)
			rate_dsp_flag = 1;

		sg_mode = SGM_NORMAL;
	}

	tm_dustart = TM_STOP;		/* Ｄ／Ｕ立ち上がり検出タイマー停止 */
}

/*=======================================================================
 |
 |		INITIATED BIT 結果受信処理
 |
 |	void	receive_ibit_result()
 |
 =======================================================================*/
static	void	receive_ibit_result(long bezel_sw_on)
{
	switch (ibit_mode) {
	case IBIT_DUBIT:
		tm_dudata = TM_DUDATA;
		if (du_bit_status & (BIT_DU_ROMC | BIT_DU_RAML | BIT_DU_CPUT |
				BIT_DU_232T | BIT_DU_BKLT | BIT_DU_BLSN |
				BIT_DU_PWRS)) {
			ibit_status(BIT_NG);
		} else {
			ibit_status(BIT_OK);
		}
		break;
	case IBIT_TPTN1:
	case IBIT_TPTN2:
	case IBIT_TPTN3:
	case IBIT_TPTN4:
		if (bezel_sw_on != 0)
			ibit_status(BIT_OK);
		break;
	case IBIT_SWITCH:
		/* 押されたスイッチを保存 */
		ibit_switch |= bezel_sw_on;
		if (ibit_switch == 0x0fffffff) {
			sg_bit_status &= ~BIT_SG_SW;
			ibit_status(BIT_OK);
		}
		break;
	}
}

/*=======================================================================
 |
 |		REMOTE BIT 結果受信処理
 |
 |	void	receive_rbit_result()
 |
 =======================================================================*/
static	void	receive_rbit_result(void)
{
	if ((rbit_mode & (RBIT_DUBIT | RBIT_DU_END)) == RBIT_DUBIT) {
		tm_dudata = TM_DUDATA;
		rbit_mode |= RBIT_DU_END;
	}

	if ((rbit_mode & (RBIT_SG_END | RBIT_DU_END)) ==
					(RBIT_SG_END | RBIT_DU_END))
		sg_mode = SGM_NORMAL;
}

/*=======================================================================
 |
 |		ベゼルスイッチデータチェック
 |
 |	void	bezel_sw_status()
 |
 =======================================================================*/
static	void	bezel_sw_status(void)
{
	ushort	sw;
	ushort	tmp;
	int	i;

	sw = 0;
	tmp = du_rcv_data[1];
	for (i = 0; i < 8; i++) {
		sw <<= 1;
		if (tmp & 0x01)
			sw |= 0x0001;
		tmp >>= 1;
	}

	tmp = du_rcv_data[0];
	for (i = 0; i < 8; i++) {
		sw <<= 1;
		if (tmp & 0x01)
			sw |= 0x0001;
		tmp >>= 1;
	}
	bezel_sw1 = sw;

	sw = 0;
	tmp = du_rcv_data[3];
	for (i = 0; i < 4; i++) {
		sw <<= 1;
		if (tmp & 0x01)
			sw |= 0x1000;
		tmp >>= 1;
	}
	sw |= (ushort)du_rcv_data[2] << 4;
	bezel_sw2 = sw;
}

/*=======================================================================
 |
 |		デバッグモード画面処理
 |
 |	void	debug_menu()
 |
 =======================================================================*/
static	void	debug_menu(long bezel_sw)
{
	switch (debug_mode) {
	case DBM_DBGMENU:
		switch (bezel_sw) {
		case 0x00001:	/* SW1 */
			debug_mode = DBM_TPTN1;
			break;
		case 0x00002:	/* SW2 */
			debug_mode = DBM_TPTN2;
			break;
		case 0x00004:	/* SW3 */
			debug_mode = DBM_TPTN3;
			break;
		case 0x00008:	/* SW4 */
			debug_mode = DBM_TPTN4;
			break;
		case 0x00400:	/* SW11 */
			debug_mode = DBM_SIMTBL;
			set_simulation(cpu, DSPM_ADI);
			break;
		case 0x00800:	/* SW12 */
			debug_mode = DBM_SIMTBL;
			set_simulation(cpu, DSPM_HSI);
			break;
		case 0x01000:	/* SW13 */
			debug_mode = DBM_SIMTBL;
			set_simulation(cpu, DSPM_EW);
			break;
		case 0x02000:	/* SW14 */
			debug_mode = DBM_SIMTBL;
			set_simulation(cpu, DSPM_SMS);
			break;
		case 0x04000:	/* SW15 */
			debug_mode = DBM_SIMTBL;
			set_simulation(cpu, DSPM_FCR);
			break;
		case 0x10000:	/* SW17 */
			debug_mode = DBM_VT;
			sg_mode = SGM_NORMAL;

			/* ＲＳ－２３２ＣチャネルＢ受信有効 */
			*REG_DUART_CRB = 0x05;
			rs_IMR |= 0x20;
			*REG_DUART_IMR = rs_IMR;
			break;
		}
		break;
	case DBM_TPTN1:
	case DBM_TPTN2:
	case DBM_TPTN3:
	case DBM_TPTN4:
		if (bezel_sw != 0)
			debug_mode = DBM_DBGMENU;
		break;
	case DBM_SIMTBL:
		if (bezel_sw != 0) {
			debug_mode = DBM_DBGMENU;
			set_simulation(cpu, DSPM_NOSYMBOL);
		}
		break;
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
void	receive_CHB(int ch)
{
	byte	sr;
	char	echo[1];

//	sr = *REG_DUART_SRB;	/* ステータスレジスタ読み込み */
//	ch = *REG_DUART_RHRB;	/* 受信データ読み込み */
sr = 0;
	if ((sr & (BIT4 | BIT5 | BIT6)) == 0) {
		if (ch >= 'a' && ch <= 'z')
			ch -= 0x20;
		echo[0] = ch;
		rsb_send(echo, 1);
		if (ch == 0x0d) {
			rsb_rcvbuf[rsb_rcvcounter] = '\0';
			debug_command();
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
