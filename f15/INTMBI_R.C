/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: １５５３Ｂデータ受信割り込み処理	*
 *		ファイル名	: intmbi_rx.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_rx

#include "sg_cpu.h"

/*	内部関数	*/
static	void	read_mbi(void);
/*static*/	void	filtering(void);

/*=======================================================================
 |
 |		１５５３Ｂデータ受信割り込み処理
 |
 |	void	intmbi_rx()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intmbi_rx()
{
	/* 受信データの読み込み */
	read_mbi();

	switch (sg_mode) {
	case MODE_AI:		/* ＡＩ表示モード */
		/* フイルタリング */
		filtering();

		/* ＡＩ表示用Ｄ／Ｐセット */
		dp_ai_pitch();		/* ピッチ */
		dp_ai_roll();		/* ロール */
		dp_ai_mhdg();		/* 磁方位 */
		dp_ai_tas();		/* 真大気速度 */
		dp_ai_alt();		/* 気圧高度 */
		dp_ai_rollscale();	/* ロールスケール */
		break;
	case MODE_HUD:		/* ＨＵＤ表示モード */
		/* フイルタリング */
		filtering();

		/* ＨＵＤ表示用Ｄ／Ｐセット */
		dp_hud_pitch();		/* ピッチ */
		dp_hud_roll();		/* ロール */
		dp_hud_ias();		/* 指示大気速度 */
		dp_hud_mhdg();		/* 磁方位 */
		dp_hud_alt();		/* 気圧高度 */
		dp_hud_vvc();		/* ベロシティーベクタ */
		break;
	case MODE_HDD:		/* ＨＤＤ表示モード */
		/* フイルタリング */
		filtering();

		/* ＨＤＤ表示用Ｄ／Ｐセット */
		dp_hdd_tas();		/* 真大気速度 */
		dp_hdd_alt();		/* 気圧高度 */
		break;
	case MODE_MENU:		/* メニュー表示モード */
		/* Ｄ／Ｐは無し */
		break;
	case MODE_DATA:		/* 外光照度データ表示モード */
		/* 外光照度データ表示用Ｄ／Ｐセット */
		dp_gaikou();
		break;
	}
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		１５５３Ｂ受信データ読み込み処理
 |
 |	void	read_mbi()
 |
 =======================================================================*/
static	void	read_mbi()
{
	short	read_counter;
	short	rqspp, rqtpp;
	short	word_count, i;
	word	*rbufp, *wbufp;

	read_counter = 0;	/* 読み込みカウンタクリア */

	for (;;) {
		/* キューポインタチェック */
		rqspp = *REG_MBI_RQSP & 0x0fff;
		rqtpp = *REG_MBI_RQTP & 0x0fff;
		if (rqspp == rqtpp)
			break;

		/* 読み込みカウンタチェック */
		if (++read_counter >= 8)
			break;

		/* ブロック間タイマーリセット */
		tmc_1553B = 0;			/* タイマーカウンタクリア */
		tmf_1553B = TIMER_START;	/* タイマー起動 */
		tmo_1553B = 0;			/* タイムアウトフラグリセット*/

		/* 受信データ読み込み */
		command_word = *(REG_MBI_RQMT + rqspp / 2);
		word_count = command_word & 0x1f;
		rbufp = REG_MBI_BMWB + rqspp * 32;
		if (word_count == 8) {		/* データ種類チェック */
			/* 飛行データ */
			wbufp = (word *)&orgdata;
			r1553B_data = 1;
		} else {
			/* 外光照度データ */
			wbufp = (word *)&lumdata;
			r1553B_data = 2;
		}
		for (i = 0; i < word_count; i++) {
			*wbufp++ = *rbufp;	/* データ読み込み */
			rbufp = (word *)((char *)rbufp + 1);
		}
	}
}

/*=======================================================================
 |
 |		フィルタリング処理
 |
 |	void	filtering()
 |
 =======================================================================*/
/*static*/	void	filtering()
{
	/* 機体ピッチ */
	tmpdata.IPTCHA += (((short)(orgdata.IPTCHA & 0xfff0) - tmpdata.IPTCHA) * (long)DELTA_t) / (T_IPTCHA + DELTA_t);
	fltdata.IPTCHA = tmpdata.IPTCHA >> 4;

	/* 機体ロール */
	tmpdata.IROLLA += (((short)(orgdata.IROLLA & 0xfff0) - tmpdata.IROLLA) * (long)DELTA_t) / (T_IROLLA + DELTA_t);
	fltdata.IROLLA = tmpdata.IROLLA >> 4;

	/* Ｘ方向ベロシティベクタ */
	tmpdata.OHVVCX += (((short)(orgdata.OHVVCX & 0xffc0) - tmpdata.OHVVCX) * (long)DELTA_t) / (T_OHVVCX + DELTA_t);
	fltdata.OHVVCX = tmpdata.OHVVCX >> 6;

	/* Ｙ方向ベロシティベクタ */
	tmpdata.OHVVCY += (((short)(orgdata.OHVVCY & 0xffc0) - tmpdata.OHVVCY) * (long)DELTA_t) / (T_OHVVCY + DELTA_t);
	fltdata.OHVVCY = tmpdata.OHVVCY >> 6;

	/* 機体磁方位 */
	fltdata.IMAGHD = (ushort)orgdata.IMAGHD >> 4;

	/* 気圧高度 */
	if (orgdata.IHBALT != 0x8000)
		fltdata.IHBALT = orgdata.IHBALT + 31512; /* 31512 = 39390ft */

	/* 真大気速度 */
	if ((orgdata.IASPDT & BIT0) == 0)
		fltdata.IASPDT = orgdata.IASPDT >> 1;

	/* 指示大気速度 */
	if ((orgdata.IASPDI & BIT0) == 0)
		fltdata.IASPDI = orgdata.IASPDI >> 1;
}
