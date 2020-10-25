/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: パワーオン処理			*
 *		ファイル名	: cpu20_init.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=cpu20_init

#include "sg_cpu.h"

/*	バイトレジスタ設定データ	*/
struct	byte_reg_data	{
	byte	*address;
	byte	data;
	short	wait_time;
};

/*	ＶＩＣ０６８コントロールレジスタ設定値 */
static	struct	byte_reg_data VIC068[] = {
	{REG_VIC_LIVBR,  0x40, 0},
	{REG_VIC_LICR1,  0xaa, 0},
	{REG_VIC_LICR2,  0xa9, 0},
	{REG_VIC_VIVR1,  0x48, 0},
	{REG_VIC_VIVR2,  0x49, 0},
	{REG_VIC_VIVR3,  0x4a, 0},
	{REG_VIC_VIVR4,  0x4b, 0},
	{REG_VIC_VIVR5,  0x4c, 0},
	{REG_VIC_VIVR6,  0x4d, 0},
	{REG_VIC_VIVR7,  0x4e, 0},
	{REG_VIC_SS0CR0, 0xc0, 0},
	{REG_VIC_SS1CR0, 0x00, 0},
	{REG_VIC_LBTR,   0x00, 0},
	{REG_VIC_TTR,    0x6c, 0},
	{REG_VIC_RCR,    0x00, 0},
	{REG_VIC_BESR,   0x80, 0},
	{REG_VIC_ARCR,   0x60, 0}
};

/*	ＤＵＡＲＴコントロールレジスタ設定値 */
static	struct	byte_reg_data DUART[] = {
	{REG_DUART_CRA,  0x0a, 0},
	{REG_DUART_CRA,  0x10, 100},
	{REG_DUART_CRA,  0x20, 100},
	{REG_DUART_CRA,  0x30, 100},
	{REG_DUART_CRA,  0x40, 100},
	{REG_DUART_CRA,  0x50, 100},
	{REG_DUART_MR1A, 0x0f, 0},
	{REG_DUART_MR2A, 0x0f, 0},
	{REG_DUART_CSRA, 0xbb, 0},
	{REG_DUART_CRA,  0x06, 0},
	{REG_DUART_CRB,  0x0a, 0},
	{REG_DUART_CRB,  0x10, 100},
	{REG_DUART_CRB,  0x20, 100},
	{REG_DUART_CRB,  0x30, 100},
	{REG_DUART_CRB,  0x40, 100},
	{REG_DUART_CRB,  0x50, 100},
	{REG_DUART_MR1B, 0x0f, 0},
	{REG_DUART_MR2B, 0x0f, 0},
	{REG_DUART_CSRB, 0xbb, 0},
	{REG_DUART_CRB,  0x09, 0},
	{REG_DUART_IMR,  0x20, 0}
};

/*	内部関数	*/
static	void	write_byte_register(struct byte_reg_data *, short);
static	void	memory_error(void);

/*=======================================================================
 |
 |		パワーオン処理
 |
 |	void	power_on()
 |
 =======================================================================*/
void	power_on()
{
	register short	i;
	register word	sum, *p;

	/* ＷＤＴリセット */
	*REG_WDT = 0xfd;

	/* ＥＥＰＲＯＭサムチェック */
	for (i = 0; i < 3; i++) {
		sum = 0;
		for (p = ADR_EEPROM; p < ADR_PRGEND; p++)
			sum += *p;
		if (sum != *ADR_PRGEND)
			memory_error();
	}

	/* ＳＲＡＭチェック */
	for (i = 0; i < 3; i++) {
		/* 0x5555を書き込む */
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
			*p = 0x5555;

		/* 0x5555であることをチェックする */
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
			if (*p != 0x5555)
				memory_error();

		/* 0xaaaaを書き込む */
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
			*p = 0xaaaa;

		/* 0xaaaaであることをチェックする */
		for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
			if (*p != 0xaaaa)
				memory_error();
	}

	/* ＳＲＡＭゼロクリア */
	for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
		*p = 0;

	/* ＶＩＣ０６８コントロールレジスタ設定 */
	write_byte_register(VIC068,
			sizeof(VIC068) / sizeof(struct byte_reg_data));

	/* ＲＡＰゼロクリア */
	for (p = ADR_RAP; p < ADR_RAP_END; p++)
		*p = 0;

	/* ＤＵＡＲＴコントロールレジスタ設定 */
	set_DUART();

	/* ＭＢＩボードＢＩＴ終了割り込み待ちタイマーセット */
	tmc_mbibit = 0;			/* タイマーカウンタクリア */
	tmf_mbibit = TIMER_START;	/* タイマー起動 */

	/* ＯＲＫＩＤコール */
	ORKID();
}

/*=======================================================================
 |
 |		ＤＵＡＲＴコントロールレジスタの設定
 |
 |	void	set_DUART()
 |
 =======================================================================*/
void	set_DUART()
{
	write_byte_register(DUART,
			sizeof(DUART) / sizeof(struct byte_reg_data));
}

/*=======================================================================
 |
 |		バイトレジスタ書き込み処理
 |
 |	void	write_byte_register(data_tbl, size)
 |
 |		struct byte_reg_data	*data_tbl;	レジスタ設定テーブル
 |		short	size;		テーブルサイズ
 |
 =======================================================================*/
static	void	write_byte_register(struct byte_reg_data *data_tbl, short size)
{
	short	i;

	while (size--) {
		/* データ書き込み */
		*data_tbl->address = data_tbl->data;
		data_tbl++;		/* テーブルポインタ更新 */

		/* ウエイト */
		for (i = 0; i < data_tbl->wait_time; i++)
			;
	}
}

/*=======================================================================
 |
 |		メモリエラー処理
 |
 |	void	memory_error()
 |
 =======================================================================*/
static	void	memory_error()
{
	long	a;

	/* ５秒待つ */
	for (a = 0; a < 1000000L; a++)
		*REG_WDT = 0xfd;	/* ＷＤＴリセット */

	/* 無限ループ（ＷＤＴタイムアウトを待つ） */
	for (;;);
}
