/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: ＭＢＩボードＢＩＴ終了処理		*
 *		ファイル名	: intmbi_b.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_b

#include "sg_cpu.h"

/*=======================================================================
 |
 |		ＭＢＩボードＢＩＴ終了割り込み処理
 |
 |	void	intmbi_bit()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intmbi_bit(void)
{
	word	brcs;
	word	isw0;

	isw0 = *REG_MBI_ISW0;

	/* ＩＳＷ０ゼロクリア */
	*REG_MBI_ISW0 = 0x0000;

	if ((isw0 & 0xe000) == 0x2000) {
		/* ＢＲＣＳチェック */
		brcs = *REG_MBI_BRCS;
		if (brcs == 0xfe01 || brcs == 0x6f90 || brcs == 0x6e91) {
			/* 正常終了 */
			/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマー停止 */
			tm_mbibit = TM_STOP;

			/* ＭＢＩボード動作停止 */
			*REG_MBI_CFCW = 0x0000;

			/* ＲＴモード設定 */
			*REG_MBI_CFCW = 0x4007;

			/* ＲＴオペレーションステータス設定 */
			*REG_MBI_OPCW = 0x0080;

			/* ＲＴアドレス設定（４） */
			*REG_MBI_RTID = 0x003b;

			/* 受信バッファのアドレッシング */
			*(REG_MBI_RQMT+1) = 0x0100;
			*(REG_MBI_RQMT+3) = 0x0140;
			*(REG_MBI_RQMT+5) = 0x0180;
			*(REG_MBI_RQMT+7) = 0x01c0;
			*(REG_MBI_RQMT+9) = 0x0200;
			*(REG_MBI_RQMT+11) = 0x0240;
			*(REG_MBI_RQMT+13) = 0x0280;
			*(REG_MBI_RQMT+15) = 0x02c0;

			/* 送信バッファのアドレッシング */
			*(REG_MBI_TMTA+1) = 0x0300;
			*(REG_MBI_TMTA+30) = 0x0340;

			/* １５５３Ｂ送信バッファセット */
			set_mbi_sendbuf();

			/* ＢＩＴＬＥＤ消灯及び割り込みマスクの解除 */
			*REG_MBI_IMSK = 0x00f7;
			*REG_VIC_VICR5 = 0x7d;
			*REG_VIC_VICR6 = 0x7e;
			*REG_WDT = 0x9f;
		} else {
			/* 異常終了 */
			if (++counter_mbibit < 3)
				mbibit();/* ＭＢＩボードＢＩＴチェック再実行 */
			else
				halt_cpu("MBI BIT ERROR", 1);
		}
	} else if ((isw0 & 0xe01f) == 0x4003) {
		/* Ｒｅｍｏｔｅ ＢＩＴ */
		rbit_request = 1;
	}
}
