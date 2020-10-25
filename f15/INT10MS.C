/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: １０ｍｓ定周期割り込み処理		*
 *		ファイル名	: int10ms.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=int10ms

#include "sg_cpu.h"

/*=======================================================================
 |
 |		１０ｍｓ定周期割り込み処理
 |
 |	void	int10ms()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	int10ms()
{
	/* ＷＤＴトリガリセット */
	*REG_WDT = 0xff;

	/* ＭＢＩボードＢＩＴ終了割り込みタイムアウト検出 */
	if (tmf_mbibit == TIMER_START) {
		if (tmc_mbibit == 500) {
			/* ＭＢＩボードＢＩＴチェック再実行 */
			mbibit();

			/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマーセット */
			tmc_mbibit = 0;		/* タイマーカウンタクリア */
			tmf_mbibit = TIMER_START;	/* タイマー起動 */
		} else {
			/* タイマーカウンタ更新 */
			++tmc_mbibit;
		}
	}

	/* １５５３Ｂバス伝送ブロック間タイムアウト検出 */
	if (tmf_1553B == TIMER_START) {
		if (tmc_1553B == 500) {
			/* タイムアウトフラグセット */
			tmo_1553B = 1;

			/* タイマー停止 */
			tmf_1553B = TIMER_STOP;

			/* ＭＢＩボードＢＩＴチェック再実行 */
			mbibit();

			/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマーセット */
			tmc_mbibit = 0;		/* タイマーカウンタクリア */
			tmf_mbibit = TIMER_START;	/* タイマー起動 */
		} else {
			/* タイマーカウンタ更新 */
			++tmc_1553B;
		}
	}

	/* ＲＳ－２３２Ｃ伝送ブロック間タイムアウト検出 */
	if (tmf_rsblock == TIMER_START) {
		if (tmc_rsblock == 500) {
			/* タイムアウトフラグセット */
			tmo_rsblock = 1;

			/* ＤＵＡＲＴコントロールレジスタ再設定 */
			set_DUART();

			/* ブロック間タイマーセット */
			tmc_rsblock = 0;
			tmf_rsblock = TIMER_START;
		} else {
			/* タイマーカウンタ更新 */
			++tmc_rsblock;
		}
	}

	/* ＲＳ－２３２Ｃ伝送文字間タイムアウト検出 */
	if (tmf_rschar == TIMER_START) {
		if (tmc_rschar == 4) {
			/* タイマー停止 */
			tmf_rschar = TIMER_STOP;

			/* ＲＳ－２３２Ｃ受信カウンタクリア */
			rs_counter = 0;
		} else {
			/* タイマーカウンタ更新 */
			++tmc_rschar;
		}
	}

	/* デバッグ時ビデオターミナル伝送出力 */
	if (debug_mode_flag) {
		if (tmc_debug == 200) {		/* 200 = ２秒 */
			debug();
			tmc_debug = 0;
		} else
			++tmc_debug;
	}
}
