/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: ＧＳＣ描画終了割り込み処理		*
 *		ファイル名	: intgsc.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intgsc

#include "sg_cpu.h"

/*=======================================================================
 |
 |		ＧＳＣ描画終了割り込み処理
 |
 |	void	intgsc()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intgsc()
{
	/* Ｄ／ＰをＲＡＰにセット */
	switch (sg_mode) {
	case MODE_AI:		/* ＡＩ表示モード */
		*(struct DP_AI *)ADR_RAP_DP = dp_ai;
		break;
	case MODE_HUD:		/* ＨＵＤ表示モード */
		*(struct DP_HUD *)ADR_RAP_DP = dp_hud;
		break;
	case MODE_HDD:		/* ＨＤＤ表示モード */
		*(struct DP_HDD *)ADR_RAP_DP = dp_hdd;
		break;
	case MODE_MENU:		/* メニュー表示モード */
		break;
	case MODE_DATA:		/* 外光照度データ表示モード */
		*(struct DP_DATA *)ADR_RAP_DP = dp_data;
		break;
	}

	/* Ｐ／ＡビットをＲＡＰにセット */
	*(struct PA_BIT *)ADR_RAP_PA = pa_bit;

	/* ビデオコントロールレジスタセット */
	*REG_GSC_OUTPUT = video_control;
}
