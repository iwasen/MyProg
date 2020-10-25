/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: ＭＢＩボードＢＩＴ終了処理		*
 *		ファイル名	: intmbi_bit.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_bit

#include "sg_cpu.h"

/*=======================================================================
 |
 |		ＭＢＩボードＢＩＴ終了割り込み処理
 |
 |	void	intmbi_bit()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intmbi_bit()
{
	word	brcs;

	/* ＩＳＷ０ゼロクリア */
	*REG_MBI_ISW0 = 0x0000;

	/* ＢＲＣＳチェック */
	brcs = *REG_MBI_BRCS & 0x00ff;
	if (brcs == 0x01 || brcs == 0x90 || brcs == 0x91) { /* 結果チェック */
		/* 正常終了 */
		/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマー停止 */
		tmf_mbibit = TIMER_STOP;

		/* ＭＢＩボード動作停止 */
		*REG_MBI_CFCW = 0x0000;

		/* ＲＴモード設定 */
		*REG_MBI_CFCW = 0x4007;

		/* ＲＴオペレーションステータス設定 */
		*REG_MBI_OPCW = 0x0000;

		/* ＲＴアドレス設定 */
		*REG_MBI_RTID = 0x003e;

		/* 受信バッファのアドレッシング */
		*((word *)((byte *)REG_MBI_RQMT+1)) = 0x0080;
		*((word *)((byte *)REG_MBI_RQMT+3)) = 0x00c0;
		*((word *)((byte *)REG_MBI_RQMT+5)) = 0x0100;
		*((word *)((byte *)REG_MBI_RQMT+7)) = 0x0140;
		*((word *)((byte *)REG_MBI_RQMT+9)) = 0x0180;
		*((word *)((byte *)REG_MBI_RQMT+11)) = 0x01c0;
		*((word *)((byte *)REG_MBI_RQMT+13)) = 0x0200;
		*((word *)((byte *)REG_MBI_RQMT+15)) = 0x0240;

		/* ＢＩＴＬＥＤ消灯及び受信割り込みマスクの解除 */
		*REG_MBI_IMSK = 0x00f5;

		/* １５５３Ｂブロック間タイマーセット */
		tmc_1553B = 0;			/* タイマーカウンタクリア */
		tmf_1553B = TIMER_START;	/* タイマー起動 */

		/* ＭＢＩボード起動フラグセット */
		mbi_start_flag = 1;
	} else {
		/* 異常終了 */
		/* ＭＢＩボードのソフトウェアリセット */
		*REG_MBI_CFCW = 0x2000;

		/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマーセット */
		tmc_mbibit = 0;			/* タイマーカウンタクリア */
		tmf_mbibit = TIMER_START;	/* タイマー起動 */
	}
}
