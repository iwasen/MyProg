/************************************************************************
 *									*
 *		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
 *									*
 *		名称		: パワーオン処理			*
 *		ファイル名	: init.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=init

#include "sg_cpu.h"

/*	ＶＩＣ０６８コントロールレジスタ設定値 */
static	BYTE_REG_DATA	VIC068[] = {
        {REG_VIC_EGIVBR, 0x50, 100},
        {REG_VIC_ICGSIVBR, 0x58, 100},
        {REG_VIC_ICMSIVBR, 0x5C, 100},
        {REG_VIC_LIVBR,  0x40, 100},
        {REG_VIC_LICR1,  0xb2, 100},
        {REG_VIC_LICR2,  0xb1, 100},
        {REG_VIC_VIVR1,  0x48, 100},
        {REG_VIC_VIVR2,  0x49, 100},
        {REG_VIC_VIVR3,  0x4a, 100},
        {REG_VIC_VIVR4,  0x4b, 100},
        {REG_VIC_VIVR5,  0x4c, 100},
        {REG_VIC_VIVR6,  0x4d, 100},
        {REG_VIC_VIVR7,  0x4e, 100},
        {REG_VIC_SS0CR0, 0xc0, 100},
        {REG_VIC_SS1CR0, 0x00, 100},
        {REG_VIC_LBTR,   0x00, 100},
        {REG_VIC_TTR,    0x6a, 100},
        {REG_VIC_RCR,    0x00, 100},
        {REG_VIC_BESR,   0x80, 100},
        {REG_VIC_ARCR,   0x60, 100}
};

/*	ＤＵＡＲＴコントロールレジスタ設定値 */
static	BYTE_REG_DATA	DUART[] = {
	{REG_DUART_CRA,  0x0a, 100},
	{REG_DUART_CRA,  0x10, 100},
	{REG_DUART_CRA,  0x20, 100},
	{REG_DUART_CRA,  0x30, 100},
	{REG_DUART_CRA,  0x40, 100},
	{REG_DUART_CRA,  0x50, 100},
	{REG_DUART_MR1A, 0x07, 100},
	{REG_DUART_MR2A, 0x0f, 100},
	{REG_DUART_CSRA, 0xbb, 100},
	{REG_DUART_CRA,  0x05, 100},
	{REG_DUART_CRB,  0x0a, 100},
	{REG_DUART_CRB,  0x10, 100},
	{REG_DUART_CRB,  0x20, 100},
	{REG_DUART_CRB,  0x30, 100},
	{REG_DUART_CRB,  0x40, 100},
	{REG_DUART_CRB,  0x50, 100},
	{REG_DUART_MR1B, 0x17, 100},
	{REG_DUART_MR2B, 0x07, 100},
	{REG_DUART_CSRB, 0xbb, 100},
	{REG_DUART_CRB,  0x05, 100},
	{REG_DUART_IMR,  0x22, 100}
};

/*=======================================================================
 |
 |		パワーオン処理
 |
 |	void	power_on()
 |
 =======================================================================*/
void	power_on(void)
{
	register word	*p;

	/* ＬＥＤ点灯 */
	*REG_WDT = 0x9d;

	/* ＳＲＡＭゼロクリア */
	for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
		*p = 0;

	/* ＶＩＣ０６８コントロールレジスタ設定 */
	write_byte_register(VIC068,
			sizeof(VIC068) / sizeof(BYTE_REG_DATA));

	/* ＤＵＡＲＴコントロールレジスタ設定 */
	set_DUART();

	/* デバッグモード初期値設定 */
	debug_flag = debug_mode;

	/* ＥＥＰＲＯＭサムチェック */
	rom_check();

	/* ＳＲＡＭチェック */
	ram_check();

	/* ＲＡＰゼロクリア */
	for (p = ADR_RAP; p < ADR_RAP_END; p++)
		*p = 0;

	/* ＭＢＩボードＢＩＴチェック開始 */
	mbibit();

	/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマーセット */
	tmc_mbibit = 0;			/* タイマーカウンタクリア */
	tmf_mbibit = TIMER_START;	/* タイマー起動 */

	/* ビデオモードセット */
	video_mode = 0x0a;

	/* パワーオンシーケンス番号初期設定 */
	seq_power_on = 1;

	/* 割り込みマスク解除 */
	mskclr();
	int_enable();

	/* デバッグモードメッセージ表示 */
	msgout("\x0d\x0a*DEBUG MODE ? ");
	if (debug_flag) {
		msgout("Y\x0d\x0a");
		msgout(">");
	}

	/* ＧＳＣレジスタセット */
	*REG_GSC_OUTPUT = 0x0001;
	*REG_GSC_OPRATE = 0x2000;

	/* 入力データ処理 */
	inpdata();
}

/*=======================================================================
 |
 |		ＤＵＡＲＴコントロールレジスタの設定
 |
 |	void	set_DUART()
 |
 =======================================================================*/
void	set_DUART(void)
{
	write_byte_register(DUART,
			sizeof(DUART) / sizeof(BYTE_REG_DATA));

	/* set DUART int mask data */
	rs_IMR = 0x22;
}

/*=======================================================================
 |
 |		パワーオンシーケンス処理
 |
 |	void	power_on_seq()
 |
 =======================================================================*/
void	power_on_seq(void)
{
	/* パワーオンシーケンスタイマーリセット */
	tmc_poweron = 0;

	switch (seq_power_on++) {
	case 1:
		/* パワーオンシーケンスタイマー起動 */
		tmf_poweron = TIMER_START;

		/* テストパターン２（全面白）表示 */
		SET_PABIT(PA_T001);
		break;
	case 2:
		/* パワーオンシーケンスタイマー起動 */
		tmf_poweron = TIMER_START;

		/* テストパターン１（全面黒）表示 */
		SET_WORD(pa, 0, N_PABIT);
		SET_PABIT(PA_T000);
		break;
	case 3:
		/* パワーオンシーケンスタイマー起動 */
		tmf_poweron = TIMER_START;

		/* テストパターン３（８色）表示 */
		SET_WORD(pa, 0, N_PABIT);
		SET_PABIT(PA_T002);
		break;
	case 4:
		/* パワーオンシーケンスタイマー起動 */
		tmf_poweron = TIMER_START;

		/* シンボル表示 */
		input_data[0] = 300;
		input_data[1] = 15000;
		input_data[7] = 1840;
		input_data[10] = 1;
		input_data[11] = 0x9990;
		input_data[12] = 2;
		input_data[13] = 995;
		input_data[14] = 9995;
		input_data[21] = 1;
		sg_mode = MODE_SAKUTEKI1;
		calc_dp(0);		/* Ｄ／Ｐ計算 */
		break;
	case 5:
		/* パワーオンシーケンスタイマー停止 */
		tmf_poweron = TIMER_STOP;

		/* Ｐ／Ａビットクリア */
		SET_WORD(pa, 0, N_PABIT);

		/* 入力データクリア */
		SET_WORD(input_data, 0, 512);

		/* Ｓ／Ｇモードリセット */
		sg_mode = MODE_NOSYMBOL;

		/* ビデオモードセット */
		video_mode = 0x02;

		if (debug_flag == 0) {
			/* デバッグモード解除表示 */
			msgout("N\x0d\x0a");

			/* ＲＳ－２３２ＣチャネルＢ受信停止 */
			*REG_DUART_CRB = 0x02;
			rs_IMR &= ~0x20;
			*REG_DUART_IMR = rs_IMR;
		}

		/* パワーオンシーケンス終了 */
		seq_power_on = 0;
		break;
	}

	/* Ｄ／Ｐ，Ｐ／Ａをテンポラリエリアにコピー */
	*REG_VIC_VICR4 = 0x84;	/* ＧＳＣ描画終了割り込み禁止 */
	dp_tmp = dp;			/* Ｄ／Ｐコピー */
	COPY_WORD(pa_tmp, pa, N_PABIT);	/* Ｐ／Ａコピー */
	*REG_VIC_VICR4 = 0x04;	/* ＧＳＣ描画終了割り込み禁止解除 */
}
