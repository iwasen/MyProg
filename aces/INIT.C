/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: パワーオン処理			*
 *		ファイル名	: init.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=init

#include "sg_cpu.h"

/*	内部関数	*/
static	void	check_pid(void);
static	void	set_VIC068(void);

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

	check_pid();		/* プロセッサＩＤチェック */

//	*REG_WDT = 0x9d;	/* ＬＥＤ点灯 */

	/* ＳＲＡＭゼロクリア */
//	for (p = ADR_SRAM; p < ADR_SRAM_END; p++)
//		*p = 0;

	/* デバッグモードチェック */
	if (debug_flag & DBF_DEBUGMODE)
		debug_mode = DBM_VT;

	set_VIC068();		/* ＶＩＣ０６８コントロールレジスタ設定 */
	set_DUART();		/* ＤＵＡＲＴコントロールレジスタ設定 */

	sg_mode = SGM_PBIT;	/* POWER ON BIT 開始 */

	rom_check();		/* ＥＥＰＲＯＭサムチェック */
	ram_check();		/* ＳＲＡＭチェック */
	cpu_check();		/* ＣＰＵチェック */

	/* ＲＡＰゼロクリア */
	for (p = GSC_RAP; p < GSC_RAP_END; p++)
		*p = 0;

	if (cpu == CPU_1)
		mbibit();	/* ＭＢＩボードＢＩＴチェック開始 */

	/* 割り込みマスク解除 */
	mskclr();
	int_enable();

	/* ＧＳＣレジスタセット */
//	*REG_GSC_OUTPUT = 0x0001;
//	*REG_GSC_OPRATE = 0x2000;

	sg_main();		/* メイン処理 */
}

/*=======================================================================
 |
 |		プロセッサＩＤチェック
 |
 |	void	check_pid()
 |
 =======================================================================*/
static	void	check_pid(void)
{
/*@@@@@@@@@@@@*/
extern	word	test_gsc[];
cpu = CPU_1;
adr_gsc = (char *)test_gsc;
return;
/*@@@@@@@@@@@@*/
	switch (*REG_STATUS & 0x0f) {
	case 0x0f:
		cpu = CPU_1;			/* ＣＰＵ番号セット */
		adr_gsc = (char *)VME_GSC1;	/* ＧＳＣアドレスセット */
		break;
	case 0x0e:
		cpu = CPU_2;			/* ＣＰＵ番号セット */
		adr_gsc = (char *)VME_GSC2;	/* ＧＳＣアドレスセット */
		break;
	case 0x0d:
		cpu = CPU_3;			/* ＣＰＵ番号セット */
		adr_gsc = (char *)VME_GSC3;	/* ＧＳＣアドレスセット */
		break;
	default:
		halt_cpu("ILLEGAL PROCESSOR ID", 100);
		break;
	}
}

/*=======================================================================
 |
 |		ＶＩＣ０６８コントロールレジスタ設定
 |
 |	void	set_VIC068()
 |
 =======================================================================*/
static	void	set_VIC068(void)
{
	/*	ＶＩＣ０６８コントロールレジスタ設定値 (MASTER CPU) */
	static	BYTE_REG_DATA	CPU1_VIC068[] = {
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

	/*	ＶＩＣ０６８コントロールレジスタ設定値 (SLAVE CPU) */
	static	BYTE_REG_DATA	CPU23_VIC068[] = {
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
	        {REG_VIC_BESR,   0x00, 100},
	        {REG_VIC_ARCR,   0x60, 100}
	};

	switch (cpu) {
	case CPU_1:
		write_byte_register(CPU1_VIC068,
			sizeof(CPU1_VIC068) / sizeof(BYTE_REG_DATA));
		break;
	default:
		write_byte_register(CPU23_VIC068,
			sizeof(CPU23_VIC068) / sizeof(BYTE_REG_DATA));
		break;
	}
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
		{REG_DUART_CSRB, 0xbb, 100}
	};

	/* DUART 初期化 */
	write_byte_register(DUART,
			sizeof(DUART) / sizeof(BYTE_REG_DATA));

	/* デバッグモードチェック */
	if (debug_mode == DBM_VT) {
		/* ＣＨ－Ｂ（ＶＴ）受信可 */
//		*REG_DUART_CRB = 0x05;
		rs_IMR = 0x22;
//		*REG_DUART_IMR = rs_IMR;
	} else {
		/* ＣＨ－Ｂ（ＶＴ）受信不可 */
//		*REG_DUART_CRB = 0x02;
		rs_IMR = 0x02;
//		*REG_DUART_IMR = rs_IMR;
	}
}
