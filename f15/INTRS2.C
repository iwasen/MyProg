/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: ＲＳ－２３２Ｃデータ受信処理		*
 *		ファイル名	: intrs2.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intrs2

#include "sg_cpu.h"

/*	内部関数	*/
static	void	mode_check(void);
static	void	set_PAbit(void);

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃ割り込み処理
 |
 |	void	intrs2()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intrs2()
{
	byte	ch, isr, srb;

	/* 割り込みステータスレジスタ読み込み */
	isr = *REG_DUART_ISR;

	if (isr & BIT1) {	/* ＣＨ－Ａ割り込み？ */
		/* ＣＨ－Ａ割り込み禁止 */
		*REG_DUART_IMR = 0x20;
	}

	if (isr & BIT5) {	/* ＣＨ－Ｂ割り込み？ */
		while ((srb = *REG_DUART_SRB) & BIT0) {
			if (srb & (BIT4 | BIT5 | BIT6)) {/* 受信エラー有り？ */
				/* バッファ内データ読み捨て */
				ch = *REG_DUART_RHRB;

				/* 受信カウンタクリア */
				rs_counter = 0;
			} else {
				/* 受信データ読み込み */
				ch = *REG_DUART_RHRB;

				switch (rs_counter) {
				case 0:
					if (ch == '#') {
						/* ブロック間タイマーセット */
						tmc_rsblock = 0;
						tmf_rsblock = TIMER_START;
						tmo_rsblock = 0;

						/* 文字間タイマーセット */
						tmc_rschar = 0;
						tmf_rschar = TIMER_START;

						/* 受信カウンタセット */
						rs_counter = 1;
					}
					break;
				case 1:
					if (ch == 'S') {
						/* 受信カウンタセット */
						rs_counter = 2;
					} else {
						/* 文字間タイマー停止 */
						tmf_rschar = TIMER_STOP;

						/* 受信カウンタクリア */
						rs_counter = 0;
					}
					break;
				case 2:
					/* 受信データセーブ */
					rs_receive_data = ch;

					/* 受信カウンタセット */
					rs_counter = 3;
					break;
				}
			}
		}

		if (rs_counter == 3) {
			/* 文字間タイマー停止 */
			tmf_rschar = TIMER_STOP;

			if (gsc_start_flag == 0) {	/* １回目？ */
				/* ＧＳＣボード起動 */
				*REG_GSC_OUTPUT = 0x0000;
				gsc_start_flag = 1;
			}

			/* モード変更チェック */
			mode_check();

			/* Ｐ／Ａビットセット */
			set_PAbit();

			/* 受信カウンタクリア */
			rs_counter = 0;
		}
	}
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		表示モード変更チェック
 |
 |	void	mode_check()
 |
 =======================================================================*/
static	void	mode_check()
{
	if (rs_receive_data & BIT7) {
		/* 通常モード */
		if (rs_receive_data & BIT2)	/* グラフィック表示？ */
			sg_mode = MODE_AI;
		else {
			if (rs_receive_data & BIT3)
				sg_mode = MODE_HUD;
			else
				sg_mode = MODE_HDD;
		}
	} else {
		/* メンテナンスモード */
		switch (rs_receive_data) {
		case 0x80:	/* 通常モード－＞外照モードメニュー表示 */
			if (sg_mode == MODE_AI || sg_mode == MODE_HUD
						|| sg_mode == MODE_HDD)
				sg_mode = MODE_MENU;
			break;
		case 0x82:	/* 外照データ表示－＞外照モードメニュー表示 */
			if (sg_mode == MODE_DATA)
				sg_mode = MODE_MENU;
			break;
		case 0x84:	/* 外照データメニュー表示－＞外照データ表示 */
			if (sg_mode == MODE_MENU)
				sg_mode = MODE_DATA;
			break;
		case 0xa0:	/* 外照データ記録終了 */
			if (sg_mode == MODE_MENU)
				memorizing_flag = 0;
			break;
		case 0xa1:	/* 外照データ記録開始 */
			if (sg_mode == MODE_MENU)
				memorizing_flag = 1;
			break;
		}
	}
}

/*=======================================================================
 |
 |		Ｐ／Ａビット設定処理
 |
 |	void	set_PAbit()
 |
 =======================================================================*/
static	void	set_PAbit()
{
	/* 全Ｐ／Ａビットクリア */
	pa_bit.PA00001 = 0x0000;
	pa_bit.PA00002 = 0x0000;
	pa_bit.PA00003 = 0x0000;
	pa_bit.PA00004 = 0x0000;
	pa_bit.PA00005 = 0x0000;
	pa_bit.PA00006 = 0x0000;

	/* モード別にＰ／Ａビットをセットする */
	switch (sg_mode) {
	case MODE_AI:		/* ＡＩ表示モード */
		pa_bit.PA00001 |=
			PA1_F103  | PA1_F104A | PA1_F104B | PA1_F104C |
			PA1_F104D | PA1_F104E | PA1_F104F | PA1_F105A |
			PA1_F105B | PA1_F105C | PA1_F106  | PA1_F107  |
			PA1_F108  | PA1_F109A;

		/* パイロットコメント */
		if (rs_receive_data & BIT5)	/* GOOD ? */
			pa_bit.PA00001 |= PA1_F112A;
		if (rs_receive_data & BIT6)	/* NO GOOD ? */
			pa_bit.PA00001 |= PA1_F112B;

		/* ＴＡＳ異常時シンボル */
		if (orgdata.IASPDT & BIT0)
			pa_bit.PA00002 |= PA2_F113C;

		/* ＡＬＴ異常時シンボル */
		if (orgdata.IHBALT == 0x8000)
			pa_bit.PA00002 |= PA2_F113D;

		/* １５５３Ｂ伝送エラー */
		if (tmo_1553B)
			pa_bit.PA00002 |= PA2_F113A | PA2_F113B | PA2_F113C |
					  PA2_F113D;
		break;
	case MODE_HUD:		/* ＨＵＤ表示モード */
		pa_bit.PA00003 |=
			PA3_F202 | PA3_F203 | PA3_F204 | PA3_F205 |
			PA3_F207 | PA3_F208 | PA3_F209 | PA3_F210;

		/* ベロシティーベクタ */
		if (tmo_1553B == 0)
			pa_bit.PA00003 |= PA3_F206;

		/* パイロットコメント */
		if (rs_receive_data & BIT5)	/* GOOD ? */
			pa_bit.PA00003 |= PA3_F112A;
		if (rs_receive_data & BIT6)	/* NO GOOD ? */
			pa_bit.PA00003 |= PA3_F112B;

		/* ＩＡＳ異常時シンボル */
		if (orgdata.IASPDI & BIT0)
			pa_bit.PA00003 |= PA3_F211B;

		/* ＡＬＴ異常時シンボル */
		if (orgdata.IHBALT == 0x8000)
			pa_bit.PA00003 |= PA3_F211D;

		/* １５５３Ｂ伝送エラー */
		if (tmo_1553B)
			pa_bit.PA00003 |= PA3_F211A | PA3_F211B | PA3_F211C |
					  PA3_F211D;
		break;
	case MODE_HDD:		/* ＨＤＤ表示モード */
		pa_bit.PA00004 |= PA4_F301 | PA4_F302 | PA4_F303;

		/* パイロットコメント */
		if (rs_receive_data & BIT5)	/* GOOD ? */
			pa_bit.PA00004 |= PA4_F112A;
		if (rs_receive_data & BIT6)	/* NO GOOD ? */
			pa_bit.PA00004 |= PA4_F112B;

		/* ＴＡＳ異常時シンボル */
		if (orgdata.IASPDT & BIT0)
			pa_bit.PA00004 |= PA4_F304A;

		/* ＡＬＴ異常時シンボル */
		if (orgdata.IHBALT == 0x8000)
			pa_bit.PA00004 |= PA4_F304B;

		/* １５５３Ｂ伝送エラー */
		if (tmo_1553B)
			pa_bit.PA00004 |= PA4_F304A | PA4_F304B;
		break;
	case MODE_MENU:		/* メニュー表示モード */
		/* 固定パターン */
		pa_bit.PA00005 |= PA5_F401;

		/* 記録中／記録終了メッセージ */
		if (memorizing_flag == 0)
			pa_bit.PA00005 |= PA5_F403;	/* 記録終了 */
		else
			pa_bit.PA00005 |= PA5_F402;	/* 記録中 */
		break;
	case MODE_DATA:		/* 外光照度データ表示モード */
		pa_bit.PA00006 |=
			PA6_F501 | PA6_F502 | PA6_F503 | PA6_F504 |
			PA6_F505 | PA6_F506 | PA6_F507 | PA6_F508 |
			PA6_F509;
		break;
	}

	/* ビデオ表示コントロールレジスタ設定 */
	if (rs_receive_data & BIT0)
		video_control = 0x900;	/* ビデオ表示モード */
	else if (rs_receive_data & BIT1)
		video_control = 0x500;	/* ビデオ＋グラフィック表示モード */
	else if (rs_receive_data & BIT2)
		video_control = 0x100;	/* グラフィック表示モード */

	if (rs_receive_data & BIT4)
		video_control |= 0x20;	/* 緑／黒 */
	else
		video_control |= 0x10;	/* 白／黒 */
}
