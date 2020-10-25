/************************************************************************
 *									*
 *		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
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
void	int10ms(void)
{
	char	sendbuf[2];

	/* ＭＢＩボードＢＩＴ終了割り込みタイムアウト検出 */
	if (tmf_mbibit == TIMER_START) {
		if (++tmc_mbibit == 500) {
			/* ＭＢＩボードＢＩＴチェック再実行 */
			mbibit();

			/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマーセット */
			tmc_mbibit = 0;		/* タイマーカウンタクリア */
			tmf_mbibit = TIMER_START;	/* タイマー起動 */
		}
	}

	/* データ更新周期の監視 */
	if (tmf_1553B == TIMER_START) {
		if (++tmc_1553B == 100) {
			/* １５５３Ｂデータ更新レート保存 */
			rate_1553B = count_1553B;

			/* 表示更新レート保存 */
			rate_gscint = count_gscint;

			/* １５５３Ｂデータ受信カウンタクリア */
			count_1553B = 0;

			/* 表示更新カウンタクリア */
			count_gscint = 0;

			if (rate_1553B == 0 && (rcv_1553B_flag || debug_flag))
				ibuf_flag = 1;

			rate_calc = count_calc;
			count_calc = 0;

			tmc_1553B = 0;		/* タイマーカウンタリセット */
		}
	}

	/* Ｄ／Ｕへ表示モード切り換えデータ送信 */
	if (tmf_dspmode == TIMER_START) {
		if (++tmc_dspmode == 5) {
			/* 表示モード切り換えデータ送信 */
			sendbuf[0] = 'S';
			sendbuf[1] = video_mode;
			rsa_send(sendbuf, 2);

			tmc_dspmode = 0;	/* タイマーカウンタリセット */
		}
	}

	/* Ｄ／ＵからのＢＩＴ結果受信監視 */
	if (tmf_dubit == TIMER_START) {
		if (++tmc_dubit == 500) {
			/* Ｄ／Ｕ ＢＩＴエラーフラグセット */
			bit_status = BIT_DUERR;

			/* Ｄ／Ｕ ＢＩＴ結果受信監視タイマー停止 */
			tmf_dubit = TIMER_STOP;
		}
	}

	/* 点滅させるシンボルの表示タイミングのカウント */
	if (++tmc_blink == 50) {	/* ２Ｈｚ */
		/* ブリンクフラグセット */
		blink_flag = !blink_flag;

		/* ブリンクカウンタリセット */
		tmc_blink = 0;
	}

	/* デジタル表示の更新タイミングのカウント */
	if (++tmc_digital >= digital_update_rate) {	/* ５Ｈｚ */
		/* デジタル表示更新フラグセット */
		digital_update_flag = 1;

		/* デジタル表示更新カウンタリセット */
		tmc_digital = 0;
	}

	/* パワーオンシーケンス */
	if (tmf_poweron == TIMER_START) {
		if (++tmc_poweron == 100) {
			power_on_seq();
		}
	}

	/* デバッグ用入力データシミュレーション処理 */
	if (debug_flag) {
		if (++tmc_simulation == 3) {
			debug_simulation();
			tmc_simulation = 0;
		}
	}
}
