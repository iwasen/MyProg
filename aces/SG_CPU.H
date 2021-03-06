/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: 共通ヘッダファイル			*
 *		ファイル名	: sg_cpu.h				*
 *									*
 ************************************************************************/

/*	型定義		*/
typedef	unsigned char	byte;
typedef	unsigned char	uchar;
typedef	unsigned short	word;
typedef	unsigned short	ushort;
typedef	unsigned long	ulong;

#include "dp.h"
#include "pa_bit.h"

#define	NULL	(void *)0
#define	FALSE	0
#define	TRUE	1
#define PI   3.14159265358979323846

/*	ビット位置	*/
#define	BIT0	0x01
#define	BIT1	0x02
#define	BIT2	0x04
#define	BIT3	0x08
#define	BIT4	0x10
#define	BIT5	0x20
#define	BIT6	0x40
#define	BIT7	0x80

/*	ＣＰＵ　ＩＤ */
#define	CPU_1	0
#define	CPU_2	1
#define	CPU_3	2

/*	ＳＧモード	*/
#define	SGM_HALT	0	/* cpu halt */
#define	SGM_PBIT	1	/* power ON BIT */
#define	SGM_IBIT	2	/* initiated BIT */
#define	SGM_RBIT	3	/* remote BIT */
#define	SGM_NORMAL	4	/* normal */
#define	SGM_DEBUG	5	/* デバッグモード画面 */

/*	表示モード	*/
#define	DSPM_NOSYMBOL	0	/* シンボル表示無し */
#define	DSPM_FCR	1	/* ＦＣＲ */
#define	DSPM_SMS	2	/* ＳＭＳ */
#define	DSPM_EW		3	/* ＥＷ */
#define	DSPM_HSI	4	/* ＨＳＩ */
#define	DSPM_ADI	5	/* ＡＤＩ */

/*	ＢＩＴステータス	*/
#define	BIT_OK		0	/* 正常 */
#define	BIT_NG		1	/* 異常 */

/*	Ｉ−ＢＩＴモード	*/
#define	IBIT_START	0	/* 開始 */
#define	IBIT_DUBIT	1	/* Ｄ／ＵのＢＩＴ終了待ち */
#define	IBIT_TPTN1	2	/* テストパターン１表示 */
#define	IBIT_TPTN2	3	/* テストパターン２表示 */
#define	IBIT_TPTN3	4	/* テストパターン３表示 */
#define	IBIT_TPTN4	5	/* テストパターン４表示 */
#define	IBIT_SWITCH	6	/* スイッチテスト */
#define	IBIT_RESULT	7	/* 結果表示 */

/*	Ｒ−ＢＩＴモード	*/
#define	RBIT_START	0	/* 開始 */
#define	RBIT_DUBIT	0x0001	/* Ｄ／ＵへＲ−ＢＩＴ通知済み */
#define	RBIT_SG_END	0x0002	/* Ｓ／ＧのＲ−ＢＩＴ終了 */
#define	RBIT_DU_END	0x0004	/* Ｄ／ＵのＲ−ＢＩＴ終了 */

/*	Ｓ／Ｇ　ＢＩＴ結果	*/
#define	BIT_SG_HLT3	0x0800		/* CPU3 HALT */
#define	BIT_SG_HLT2	0x0400		/* CPU2 HALT */
#define	BIT_SG_HLT1	0x0200		/* CPU1 HALT */
#define	BIT_SG_SW	0x0100		/* SWITCH CHECK */
#define	BIT_SG_ROMC	0x0010		/* ROM CHECK SUM */
#define	BIT_SG_RAML	0x0008		/* RAM READ/WRITE */
#define	BIT_SG_CPUT	0x0004		/* CPU TEST */
#define	BIT_SG_232P	0x0002		/* RS-232C PARITY CHECK */
#define	BIT_SG_232T	0x0001		/* RS-232C TIMEOUT */

/*	Ｄ／Ｕ　ＢＩＴ結果	*/
#define	BIT_DU_ROMC	0x0001		/* ROM CEHCK SUM */
#define	BIT_DU_RAML	0x0002		/* RAM READ/WRITE */
#define	BIT_DU_CPUT	0x0004		/* CPU TEST */
#define	BIT_DU_232P	0x0008		/* RS-232C PARITY CEHCK */
#define	BIT_DU_232T	0x0010		/* RS-232C LOOP BACK CEHCK */
#define	BIT_DU_TAXV	0x0020		/* TAXI VIOLATION */
#define	BIT_DU_TAXI	0x0040		/* TAXI INPUT */
#define	BIT_DU_BKLT	0x0080		/* BACKLIGHT MONITOR */
#define	BIT_DU_BLSN	0x0100		/* BLILIANT SENSOT MONITOR */
#define	BIT_DU_PWRS	0x0200		/* POWER MONITOR */

/*	エコーモード	*/
#define	ECHO_END	0		/* エコー無し */
#define	ECHO_START	1		/* エコーモード開始 */
#define	ECHO_WAIT	2		/* Ｄ／Ｕからのエコー待ち */

/*	エコー結果	*/
#define	ECHO_OK		0		/* エコー正常 */
#define	ECHO_NG		1		/* エコー異常 */

/*	タイマー値	*/
#define	TM_STOP		0	/* 停止 */
#define	TM_MBIBIT	500	/* ＭＢＩボードＢＩＴ終了タイムアウト検出 */
#define	TM_DUSTART	3000	/* Ｄ／Ｕ立ち上がり検出 */
#define	TM_DUDATA	5	/* Ｄ／Ｕへのデータ送信周期 */
#define	TM_DUCHAR	2	/* Ｄ／Ｕからのデータの文字間タイマー */
#define	TM_UPDATE	100	/* データ更新周期監視タイマー */
#define	TM_DIGITAL	20	/* デジタル数値表示更新周期 */
#define	TM_DURBIT	1000	/* Ｄ／ＵからのＲ−ＢＩＴ結果待ちタイマー */
#define	TM_DUIBIT	1000	/* Ｄ／ＵからのＩ−ＢＩＴ結果待ちタイマー */
#define	TM_TPTN		18000	/* Ｉ−ＢＩＴのテストパターン表示間隔 */
#define	TM_SWITCH	25200	/* Ｉ−ＢＩＴのスイッチテスト */
#define	TM_RESULT	500	/* Ｉ−ＢＩＴ結果表示時間 */
#define	TM_GSCBACKUP	3	/* ＧＳＣ割り込み停止時のバックアップ処理 */
#define	TM_GSCCHECK	5	/* ＧＳＣ割り込み監視 */
#define	TM_SIMULATION	5	/* 入力データシミュレーションタイマー */

/*	色コード	*/
#define	COLOR_BLACK	0x0000
#define	COLOR_BLUE	0x0001
#define	COLOR_RED1	0x0002
#define	COLOR_MAGENTA	0x0003
#define	COLOR_BROWN	0x0004
#define	COLOR_ORANGE	0x0005
#define	COLOR_AMBER	0x0006
#define	COLOR_CYAN	0x0007
#define	COLOR_GREEN1	0x0008
#define	COLOR_GREEN2	0x0009
#define	COLOR_GREEN3	0x000a
#define	COLOR_GREEN4	0x000b
#define	COLOR_YELLOW	0x000c
#define	COLOR_RED2	0x000d
#define	COLOR_WHITE2	0x000e
#define	COLOR_WHITE1	0x000f
/*
#define	COLOR_BLACK	0x0E00
#define	COLOR_BLUE	0x0000
#define	COLOR_RED1	0x0100
#define	COLOR_MAGENTA	0x0300
#define	COLOR_BROWN	0x0400
#define	COLOR_ORANGE	0x0500
#define	COLOR_AMBER	0x0600
#define	COLOR_CYAN	0x0700
#define	COLOR_GREEN1	0x0800
#define	COLOR_GREEN2	0x0900
#define	COLOR_GREEN3	0x0a00
#define	COLOR_GREEN4	0x0b00
#define	COLOR_YELLOW	0x0c00
#define	COLOR_RED2	0x0200
#define	COLOR_WHITE2	0x0f00
#define	COLOR_WHITE1	0x0d00
*/
/*	フォントアドレス	*/
#define FONT6_SP	0x4604
#define FONT6_0 	0x4FFC
#define FONT6_01	0x5024
#define FONT6_02	0x5030
#define FONT6_03	0x503C
#define FONT6_04	0x504C
#define FONT6_07	0x4664
#define FONT6_1 	0x5058
#define FONT6_12	0x4680
#define FONT6_13	0x46A8
#define FONT6_14	0x46B0
#define FONT6_15	0x46C4
#define FONT6_16	0x46D4
#define FONT6_19	0x46FC
#define FONT6_2 	0x5068
#define FONT6_20	0x4730
#define FONT6_21	0x4740
#define FONT6_22	0x4750
#define FONT6_23	0x4770
#define FONT6_3 	0x508C
#define FONT6_4 	0x5200
#define FONT6_5 	0x526C
#define FONT6_6 	0x5290
#define FONT6_7 	0x52C4
#define FONT6_8 	0x52D4
#define FONT6_9 	0x5314
#define FONT6_A 	0x48B0
#define FONT6_B 	0x48C8
#define FONT6_C 	0x48F4
#define FONT6_D 	0x491C
#define FONT6_E 	0x4938
#define FONT6_F 	0x4950
#define FONT6_G 	0x4960
#define FONT6_H 	0x4990
#define FONT6_I 	0x49A0
#define FONT6_J 	0x49B4
#define FONT6_K 	0x49D0
#define FONT6_L 	0x49E0
#define FONT6_M 	0x49EC
#define FONT6_N 	0x49F8
#define FONT6_O 	0x4A04
#define FONT6_P 	0x4A2C
#define FONT6_Q 	0x4A48
#define FONT6_R 	0x4A74
#define FONT6_S 	0x4A90
#define FONT6_T 	0x4AC4
#define FONT6_U 	0x4AD4
#define FONT6_V 	0x4AF0
#define FONT6_W 	0x4B00
#define FONT6_X 	0x4B10
#define FONT6_Y 	0x4B28
#define FONT6_Z 	0x4B40
#define FONT6_30	0x50C8
#define FONT6_31	0x50E0
#define FONT6_32	0x5100
#define FONT6_33	0x511C
#define FONT6_34	0x5140
#define FONT6_40	0x5210
#define FONT6_41	0x5238
#define FONT6_42	0x5248
#define FONT6_43	0x5258

#define FONT7_SP	0x53A8
#define FONT7_0 	0x53AC
#define FONT7_01	0x53D4
#define FONT7_02	0x53E0
#define FONT7_03	0x53F0
#define FONT7_04	0x5400
#define FONT7_07	0x540C
#define FONT7_1 	0x5418
#define FONT7_12	0x542C
#define FONT7_13	0x5454
#define FONT7_14	0x545C
#define FONT7_15	0x5470
#define FONT7_16	0x5480
#define FONT7_19	0x54A8
#define FONT7_2 	0x54B8
#define FONT7_20	0x54E0
#define FONT7_21	0x54F0
#define FONT7_22	0x5500
#define FONT7_23	0x5520
#define FONT7_3 	0x553C
#define FONT7_30	0x5574
#define FONT7_31	0x558C
#define FONT7_32	0x55B0
#define FONT7_33	0x55C8
#define FONT7_34	0x55E8
#define FONT7_4 	0x56AC
#define FONT7_40	0x56BC
#define FONT7_41	0x56E4
#define FONT7_42	0x56F4
#define FONT7_43	0x570C
#define FONT7_5 	0x5724
#define FONT7_6 	0x5750
#define FONT7_7 	0x5784
#define FONT7_8 	0x5794
#define FONT7_9 	0x57D4
#define FONT7_A 	0x580C
#define FONT7_B 	0x5824
#define FONT7_C 	0x5850
#define FONT7_D 	0x5878
#define FONT7_E 	0x5894
#define FONT7_F 	0x58AC
#define FONT7_G 	0x58BC
#define FONT7_H 	0x58EC
#define FONT7_I 	0x58FC
#define FONT7_J 	0x5914
#define FONT7_K 	0x5930
#define FONT7_L 	0x5940
#define FONT7_M 	0x594C
#define FONT7_N 	0x5958
#define FONT7_O 	0x5968
#define FONT7_P 	0x5990
#define FONT7_Q 	0x59AC
#define FONT7_R 	0x59DC
#define FONT7_S 	0x5A00
#define FONT7_T 	0x5A34
#define FONT7_U 	0x5A44
#define FONT7_V 	0x5A60
#define FONT7_W 	0x5A74
#define FONT7_X 	0x5A88
#define FONT7_Y 	0x5AA0
#define FONT7_Z 	0x5AB8

#define FONT8_SP	0x5AD0
#define FONT8_0 	0x5AD4
#define FONT8_01	0x5AFC
#define FONT8_02	0x5B08
#define FONT8_03	0x5B14
#define FONT8_04	0x5B24
#define FONT8_07	0x5B30
#define FONT8_1 	0x5B4C
#define FONT8_12	0x5B60
#define FONT8_13	0x5B88
#define FONT8_14	0x5B90
#define FONT8_15	0x5BA4
#define FONT8_16	0x5BB4
#define FONT8_19	0x5BE0
#define FONT8_2 	0x5BF0
#define FONT8_20	0x5C14
#define FONT8_21	0x5C28
#define FONT8_22	0x5C38
#define FONT8_23	0x5C58
#define FONT8_3 	0x5C74
#define FONT8_30	0x5CAC
#define FONT8_31	0x5CC4
#define FONT8_32	0x5CE8
#define FONT8_33	0x5D08
#define FONT8_34	0x5D2C
#define FONT8_4 	0x5DF0
#define FONT8_40	0x5E00
#define FONT8_41	0x5E28
#define FONT8_42	0x5E38
#define FONT8_43	0x5E48
#define FONT8_5 	0x5E60
#define FONT8_6 	0x5E84
#define FONT8_7 	0x5EB8
#define FONT8_8 	0x5EC8
#define FONT8_9 	0x5F08
#define FONT8_A 	0x5F44
#define FONT8_B 	0x5F5C
#define FONT8_C 	0x5F88
#define FONT8_D 	0x5FB0
#define FONT8_E 	0x5FCC
#define FONT8_F 	0x5FE4
#define FONT8_G 	0x5FF8
#define FONT8_H 	0x6028
#define FONT8_I 	0x603C
#define FONT8_J 	0x6050
#define FONT8_K 	0x606C
#define FONT8_L 	0x6084
#define FONT8_M 	0x6094
#define FONT8_N 	0x60A4
#define FONT8_O 	0x60B8
#define FONT8_P 	0x60E0
#define FONT8_Q 	0x60FC
#define FONT8_R 	0x6128
#define FONT8_S 	0x6148
#define FONT8_T 	0x617C
#define FONT8_U 	0x618C
#define FONT8_V 	0x61A8
#define FONT8_W 	0x61BC
#define FONT8_X 	0x61D4
#define FONT8_Y 	0x61F0
#define FONT8_Z 	0x6208

#define	FONT6_MINUS	FONT6_04
#define	FONT7_MINUS	FONT7_04
#define	FONT8_MINUS	FONT8_04

/*	ＶＭＥアドレススペース	*/
/*#define	VME_CPU1	0xf7000000*/	/* ＣＰＵ１ */
/*#define	VME_CPU2	0xf8000000*/	/* ＣＰＵ２ */
/*#define	VME_CPU3	0xf9000000*/	/* ＣＰＵ３ */
#define	VME_CPU1	0xff000000	/* ＣＰＵ１ */
#define	VME_CPU2	0xff000000	/* ＣＰＵ２ */
#define	VME_CPU3	0xff000000	/* ＣＰＵ３ */
#define	VME_GSC1	0x0f0000	/* ＧＳＣ１ */
#define	VME_GSC2	0x1f0000	/* ＧＳＣ２ */
#define	VME_GSC3	0x2f0000	/* ＧＳＣ３ */

/*	ＣＰＵアドレス	*/
#define	CPU_LOCAL	0xff000000
#define	CPU1_ADR(p)	((byte *)p - (CPU_LOCAL - VME_CPU1))
#define	CPU2_ADR(p)	((byte *)p - (CPU_LOCAL - VME_CPU2))
#define	CPU3_ADR(p)	((byte *)p - (CPU_LOCAL - VME_CPU3))
#define	CPU1_VME(x)	*(&x - (CPU_LOCAL - VME_CPU1) / sizeof(x))
#define	CPU2_VME(x)	*(&x - (CPU_LOCAL - VME_CPU2) / sizeof(x))
#define	CPU3_VME(x)	*(&x - (CPU_LOCAL - VME_CPU3) / sizeof(x))

/*	ＧＳＣアドレス	*/
#define GSC_OUTPUT	(word *)(adr_gsc+0x00000) /* 出力レジスタ */
//#define GSC_INPUT	(word *)(adr_gsc+0x00002) /* 入力レジスタ */
extern	short	*GSC_INPUT;
#define GSC_OPRATE	(word *)(adr_gsc+0x50000)
#define	GSC_RAP		(word *)(adr_gsc+0x50140) /* ＲＡＰ先頭アドレス */
#define	GSC_RAP_PA	(word *)(adr_gsc+0x50140) /* Ｐ／Ａビット */
#define	GSC_RAP_DP	(word *)(adr_gsc+0x501c0) /* Ｄ／Ｐ */
#define GSC_ACK		(word *)(adr_gsc+0x53ffe)
#define	GSC_RAP_END	(word *)(adr_gsc+0x54000) /* ＲＡＰ終了アドレス */

/*	ローカルメモリアドレス	*/
#define	ADR_SRAM	(word *)0xff000000	/* ＳＲＡＭ先頭アドレス */
#define	ADR_SRAM_END	(word *)0xff07ffff	/* ＳＲＡＭ終了アドレス */
#define	ADR_EEPROM	(word *)0xff080000	/* ＥＥＰＲＯＭ先頭アドレス */
#define	ADR_PRGEND	(ADR_EEPROM+0)		/* プログラム最終アドレス */

/*	ＶＩＣ０６８コントロールレジスタアドレス	*/
#define REG_VIC_VICR1   (byte *)0xff400007
#define REG_VIC_VICR2   (byte *)0xff40000b
#define REG_VIC_VICR3   (byte *)0xff40000f
#define REG_VIC_VICR4   (byte *)0xff400013
#define REG_VIC_VICR5   (byte *)0xff400017
#define REG_VIC_VICR6   (byte *)0xff40001b
#define REG_VIC_VICR7   (byte *)0xff40001f
#define REG_VIC_LICR1   (byte *)0xff400027
#define REG_VIC_LICR2   (byte *)0xff40002b
#define REG_VIC_ICGSIVBR (byte *)0xff40004f
#define REG_VIC_ICMSIVBR (byte *)0xff400053
#define REG_VIC_LIVBR   (byte *)0xff400057
#define REG_VIC_EGIVBR  (byte *)0xff40005b
#define REG_VIC_VISR    (byte *)0xff400083
#define REG_VIC_VIVR1   (byte *)0xff400087
#define REG_VIC_VIVR2   (byte *)0xff40008b
#define REG_VIC_VIVR3   (byte *)0xff40008f
#define REG_VIC_VIVR4   (byte *)0xff400093
#define REG_VIC_VIVR5   (byte *)0xff400097
#define REG_VIC_VIVR6   (byte *)0xff40009b
#define REG_VIC_VIVR7   (byte *)0xff40009f
#define REG_VIC_TTR     (byte *)0xff4000a3
#define REG_VIC_LBTR    (byte *)0xff4000a7
#define REG_VIC_BESR    (byte *)0xff4000bb
#define REG_VIC_ARCR    (byte *)0xff4000b3
#define REG_VIC_SS0CR0  (byte *)0xff4000c3
#define REG_VIC_SS1CR0  (byte *)0xff4000cb
#define REG_VIC_RCR     (byte *)0xff4000d3
#define	REG_VIC_RCR	(byte *)0xff4000d3

/*	ＤＵＡＲＴコントロールレジスタアドレス	*/
#define	REG_DUART_MR1A	(byte *)0xffc10003
#define	REG_DUART_MR2A	(byte *)0xffc10003
#define	REG_DUART_SRA	(byte *)0xffc10007
#define	REG_DUART_CSRA	(byte *)0xffc10007
#define	REG_DUART_CRA	(byte *)0xffc1000b
#define	REG_DUART_THRA	(byte *)0xffc1000f
#define	REG_DUART_RHRA	(byte *)0xffc1000f
#define	REG_DUART_ISR	(byte *)0xffc10017
#define	REG_DUART_IMR	(byte *)0xffc10017
#define	REG_DUART_MR1B	(byte *)0xffc10023
#define	REG_DUART_MR2B	(byte *)0xffc10023
#define	REG_DUART_SRB	(byte *)0xffc10027
#define	REG_DUART_CSRB	(byte *)0xffc10027
#define	REG_DUART_CRB	(byte *)0xffc1002b
#define	REG_DUART_THRB	(byte *)0xffc1002f
#define	REG_DUART_RHRB	(byte *)0xffc1002f

/*	ＭＢＩボードコントロールレジスタアドレス	*/
#define	REG_MBI_HEAD	0x800000
#define	REG_MBI_ISW0	(word *)0x800000
#define	REG_MBI_ISW1	(word *)0x800002
#define	REG_MBI_ISW2	(word *)0x800004
#define	REG_MBI_ISW3	(word *)0x800006
#define	REG_MBI_IMSK	(word *)0x800010
#define	REG_MBI_CFCW	(word *)0x800014
#define	REG_MBI_OPCW	(word *)0x800018
#define	REG_MBI_RQTP	(word *)0x80001a
#define	REG_MBI_RQSP	(word *)0x80001c
#define	REG_MBI_RTID	(word *)0x800030
#define	REG_MBI_RQMT	(word *)0x800060
#define	REG_MBI_TMTA	(word *)0x800080
#define	REG_MBI_TMTB	(word *)0x8000c0
#define	REG_MBI_BMWB	(word *)0x800200
#define	REG_MBI_BRCS	(word *)0x801ff6

/*	ステータスレジスタアドレス	*/
#define	REG_STATUS	(byte *)0xffc20002

/*	ＷＤＴ制御レジスタアドレス	*/
#define	REG_WDT		(byte *)0xffc20003

/*	ＲＳ−２３２Ｃ送受信バッファサイズ	*/
#define	RSA_SBUFSIZE	10
#define	RSB_SBUFSIZE	4096
#define	RSB_RBUFSIZE	80

/*	Ｐ／Ａビットワード数	*/
#define	N_PABIT		64

/*	デバッグフラグ	*/
#define	DBF_ROMCHECK	0x01
#define	DBF_RAMCHECK	0x02
#define	DBF_DUSTART	0x04
#define	DBF_1553B	0x08
#define	DBF_DEBUGMODE	0x10
#define	DBF_IBIT	0x20

/*	デバッグモード	*/
#define	DBM_NORMAL	0
#define	DBM_DSPUPD	1
#define	DBM_VT		2
#define	DBM_DBGMENU	3
#define	DBM_TPTN1	4
#define	DBM_TPTN2	5
#define	DBM_TPTN3	6
#define	DBM_TPTN4	7
#define	DBM_SIMTBL	8

/*	マクロ定義	*/
#define	ABS(x)		((x) >= 0 ? (x) : -(x))
#define	COPY_WORD(dst, src, size)	{struct copy {short tmp[size];}; *(struct copy *)(dst)=*(struct copy *)(src);}
#define	COPY_WORD2(dst, src, size)	{int _i; short *_dp=(short *)(dst),*_sp=(short *)(src); for (_i=size; _i != 0; _i--) *_dp++=*_sp++;}
#define	SET_WORD(dst, val, size)	{int _i;short *_dp=(short *)(dst);for (_i=size; _i != 0; _i--) *_dp++ = val;}
#define	SET_PABIT(bit)	pa[(bit) / 16] |= bit_tbl[(bit) % 16]
#define	FILTER(in,out,tmp)	out=(tmp=(in>-16384 && in<16384)?(short)(((long)(in)+(long)(tmp)+1)>>1):(short)(((long)((ushort)in)+(long)((ushort)tmp)+1)>>1))&0xfffe
#define	MM_TO_DP(mm)	((((long)(mm) * 1024) / 1925 + 1) & 0xfffe)
#define	POSITION(data,fs)	(short)((((data)*(long)((double)(fs)/154*0x2000L)>>16)+1)&0xfffe)
#define	LENGTH(data,fs)	(short)((((ushort)(data)*(ulong)((double)(fs)/154*0x2000L)>>16)+1)&0xfffe)
#define	ANGLE(data)		(short)((data) & 0xfff0)
#define	RADIUS(data,fs)	(short)((((ushort)(data)*(ulong)((double)(fs)/154*0x2000L)>>16)+1)&0xfffe)
//#define	RADIUS(data,fs)	radius((long)(data)*(long)((double)(fs)*PI/154*0x800L+0.5)>>12)
#define	COLOR(data,sft)	color_tbl[((data)>>sft)&0xf]
#define	CHECK_COLOR(data,sft)	(data & (0x000f << sft))
#define	LIMIT(data,min,max)	((data)<(min)?(min):((data)>(max)?(max):(data)))

/*	可変位置ウィンドウ	*/
typedef	struct	{
	short	x;
	short	y;
	ushort	cha;
	ushort	chb;
} MFD_WINDOW;

/*	固定位置シンボル	*/
typedef	struct	{
	ushort	fcr_fixed_symbol1;	/* 01 OMFCRFX1 */
	ushort	fcr_fixed_symbol2;	/* 02 OMFCRFX2 */
	ushort	fcr_fixed_symbol3;	/* 03 OMFCRFX3 */
	ushort	sms_fixed_symbol1;	/* 04 OMSMSFX1 */
	ushort	sms_fixed_symbol2;	/* 05 OMSMSFX2 */
	ushort	sms_fixed_symbol3;	/* 06 OMSMSFX3 */
	ushort	ew_fixed_symbol;	/* 07 OMEWFX1 */
	short	ew_dot_symbol;		/* 08 OMEWDOT */
	ushort	fcr_fixed_symbol4;	/* 09 OMFCRFX4 */
} FIXED_SYMBOL;

/*	ＦＣＲターゲット	*/
typedef	struct	{
	short	x;
	short	y;
} FCR_TARGET;

/*	可変位置シンボル１	*/
typedef	struct	{
	short	art_horizon_pitch;	/* 01 OMHZLPT */
	short	art_horizon_roll;	/* 02 OMHZLRL */
	short	art_horizon_color;	/* 03 OMHZLCL */
	short	ant_scale_az;		/* 04 OMANTAZ */
	short	ant_scale_el;		/* 05 OMANTEL */
	short	ase_circle_x;		/* 06 OMASECX */
	short	ase_circle_y;		/* 07 OMASECY */
	short	ase_circle_radius;	/* 08 OMASECR */
	short	steering_dot_x;		/* 09 OMSTRDTX */
	short	steering_dot_y;		/* 10 OMSTRDTY */
	short	asm_circle_x;		/* 11 OMASMCX */
	short	asm_circle_y;		/* 12 OMASMCY */
	short	r_max_x;		/* 13 OMRMAXX */
	short	r_max_y;		/* 14 OMRMAXY */
	short	r_max2_x;		/* 15 OMRMAX2X */
	short	r_max2_y;		/* 16 OMRMAX2Y */
	short	r_min_x;		/* 17 OMRMINX */
	short	r_min_y;		/* 18 OMRMINY */
	short	acq_symbol_x;		/* 19 OMACQX */
	short	acq_symbol_y;		/* 20 OMACQY */
	short	ac_to_tgt_line_x;	/* 21 OMACTGTX */
	short	ac_to_tgt_line_y;	/* 22 OMACTGTY */
	short	ac_to_tgt_line_length;	/* 23 OMACTGTL */
	short	ac_to_tgt_line_angle;	/* 24 OMACRGTR */
} MOVING_SYMBOL1;

/*	可変位置シンボル２	*/
typedef	struct	{
	short	xy_cursor_x;		/* 01 OMXYCURX */
	short	xy_cursor_y;		/* 02 OMXYCURY */
	short	ac_alt_line_x;		/* 03 OMACALTX */
	short	ac_alt_line_y;		/* 04 OMACALTY */
	short	ant_position_x;		/* 05 OMANTPX */
	short	ant_position_y;		/* 06 OMANTPY */
	short	reacq_symbol_x;		/* 07 OMRACQX */
	short	reacq_symbol_y;		/* 08 OMRACQY */
	short	mi_cue_eccm;		/* 09 OMMIC */
	short	aoj_strobe_eccm;	/* 10 OMAOJ */
} MOVING_SYMBOL2;

/*	ＨＳＩシンボル	*/
typedef	struct	{
	short	mode_submode;		/* 01 OMHSISUB */
	short	symbol_status;		/* 02 OMHSIST */
	short	color_code1;		/* 03 OMHSICC1 */
	short	color_code2;		/* 04 OMHSICC2 */
	short	color_code3;		/* 05 OMHSICC3 */
	short	color_code4;		/* 06 OMHSICC4 */
	short	color_code5;		/* 07 OMHSICC5 */
	short	color_code6;		/* 08 OMHSICC6 */
	short	color_code7;		/* 09 OMHSICC7 */
	short	stpt_number;		/* 10 OMHSTPTN */
	short	stpt_bearing;		/* 11 OMHSTPTB */
	short	present_mag_heading;	/* 12 OMHPMHDG */
	short	ground_track;		/* 13 OMHGTRK */
	short	comamnd_course;		/* 14 OMHCCRS */
	short	designated_point_brg;	/* 15 OMHDPB */
	short	glide_slope;		/* 16 OMHGS */
	short	tacan_bearing_pointer;	/* 17 OMHTBP */
	short	localizer;		/* 18 OMHLOC */
	short	deviation_bar;		/* 19 OMHDEVB */
	short	heading_pointer;	/* 20 OMHHP */
	short	trim_1;			/* 21 OMHTRM1 */
	short	trim_2;			/* 22 OMHTRM2 */
} HSI_SYMBOL;

/*	ＡＤＩシンボル	*/
typedef	struct	{
	short	symbol_status;		/* 01 OMADIST */
	short	color_code1;		/* 02 OMADICC1 */
	short	color_code2;		/* 03 OMADICC2 */
	short	color_code3;		/* 04 OMADICC3 */
	short	color_code4;		/* 05 OMADICC4 */
	short	color_code5;		/* 06 OMADICC5 */
	short	color_code6;		/* 07 OMADICC6 */
	short	color_code7;		/* 08 OMADICC7 */
	short	color_code8;		/* 09 OMADICC8 */
	short	present_mag_heading;	/* 10 OMAPMHDG */
	short	comamnd_course;		/* 11 OMACCRS */
	short	roll_angle;		/* 12 OMAROLL */
	short	pitch_angle;		/* 13 OMAPITCH */
	short	flight_path_marker_az;	/* 14 OMAFPMA */
	short	flight_path_marker_el;	/* 15 OMAFPME */
	short	present_altitude;	/* 16 OMAPALT */
	short	glide_slope;		/* 17 OMAGS */
	short	vertical_velocity;	/* 18 OMAVV */
	short	locator;		/* 19 OMALOC */
	short	aoa;			/* 20 OMAAOA */
	short	present_speed;		/* 21 OMAPSPD */
	short	trim_1;			/* 22 OMATRM1 */
	short	trim_2;			/* 23 OMATRM2 */
} ADI_SYMBOL;

/*	入力データ	*/
typedef	struct	{
	short	fighter_code;
	short	display_mode;
	MFD_WINDOW	mfd_window1[76];
	MFD_WINDOW	mfd_window2[15];
	FIXED_SYMBOL	fixed_symbol;
	FCR_TARGET	fcr_target[64];
	MOVING_SYMBOL1	moving_symbol1;
	MOVING_SYMBOL2	moving_symbol2;
	HSI_SYMBOL	hsi_symbol;
	ADI_SYMBOL	adi_symbol;
} INPUT_DATA;

/*	フィルタ処理データ	*/
typedef	struct	{
	FCR_TARGET	fcr_target[64];
	MOVING_SYMBOL1	moving_symbol1;
	MOVING_SYMBOL2	moving_symbol2;
	short	ew_dot_symbol;
	HSI_SYMBOL	hsi_symbol;
	short	hsi_roll_trim;
	ADI_SYMBOL	adi_symbol;
	short	adi_roll_trim;
} FILTER_DATA;

/*	フィルタリングテーブル	*/
typedef	struct	{
	short	mask;
	short	shift;
} FILTERTBL;

/*	バイトレジスタ設定データ	*/
typedef	struct	{
	byte	*address;
	byte	data;
	short	wait_time;
} BYTE_REG_DATA;

/*	入力データシミュレーションデータ	*/
typedef	struct	{
	short	cur_data;
	short	counter;
} SIM_WORK;

/*	cmnsub.c	*/
extern	void	rom_check(void);
extern	void	ram_check(void);
extern	void	cpu_check(void);
extern	void	halt_cpu(char *, int);
extern	void	mbibit(void);
extern	void	mskclr(void);
extern	void	mskset(void);
extern	void	wait100(void);
extern	void	int_enable(void);
extern	void	int_disable(void);
extern	void	write_byte_register(BYTE_REG_DATA *, short);
extern	void	tonumR(short, short *, short, short *, short);
extern	short	radius(long);
extern	short	_sqrt(long);
extern	short	_sin(short);
extern	void	rsa_send(char *, short);
extern	void	rsb_send(char *, short);
extern	void	msgout(char *);
extern	void	shorttohex(short, char *, short);
extern	void	longtohex(long, char *, short);
extern	void	dspstr(int, int, int, int, char *);

/*	debug1.c	*/
extern	void	debug_command(void);

/*	debug2.c	*/
extern	void	set_simulation(short, short);
extern	void	debug_simulation(void);

/*	init.c		*/
extern	void	power_on(void);
extern	void	set_DUART(void);

/*	sg_main.c	*/
extern	void	sg_main(void);
extern	void	copy_pa_dp(void);
extern	void	ibit_status(int);

/*	dp_calc.c	*/
extern	void	dp_calc(void);
extern	void	dp_update_rate(void);

/*	dp_fcr.c	*/
extern	void	dp_fcr(void);

/*	dp_sms.c	*/
extern	void	dp_sms(void);

/*	dp_ew.c		*/
extern	void	dp_ew(void);

/*	dp_hsi.c	*/
extern	void	dp_hsi(void);

/*	dp_adi.c	*/
extern	void	dp_adi(void);

/*	romdata.c	*/
extern	short	debug_flag;
extern	char	hex[];
extern	ushort	bit_tbl[];
extern	short	font6_tbl[];
extern	short	font7_tbl[];
extern	short	font8_tbl[];
extern	short	font6_code[];
extern	short	font7_code[];
extern	short	font8_code[];
extern	short	color_tbl[16];
extern	short	sin_tbl[];

/*	ramdata.c	*/
extern	short	cpu;
extern	char	*adr_gsc;
extern	short	display_mode;
extern	short	old_display_mode;
extern	short	rom_error_flag;
extern	short	debug_mode;
extern	char	rsa_sndbuf[RSA_SBUFSIZE];
extern	short	rsa_sndwptr;
extern	short	rsa_sndrptr;
extern	short	rsa_rcvcounter;
extern	short	du_rcvflag;
extern	uchar	du_rcv_data[10];
extern	char	rsb_sndbuf[RSB_SBUFSIZE];
extern	short	rsb_sndwptr;
extern	short	rsb_sndrptr;
extern	char	rsb_rcvbuf[RSB_RBUFSIZE];
extern	short	rsb_rcvcounter;
extern	short	sub_adrs_count;
extern	INPUT_DATA	input_data;
extern	FILTER_DATA	filter_data;
extern	FILTER_DATA	tmp_data;
extern	short	calc_flag;
extern	short	count_nodata;
extern	short	bus_fail;
extern	short	counter_mbibit;
extern	short	tm_mbibit;
extern	short	tm_dustart;
extern	short	tm_update;
extern	short	tm_dudata;
extern	short	tm_duchar;
extern	short	tm_digital;
extern	short	tm_ibit;
extern	short	tm_gscbackup;
extern	short	tm_simulation;
extern	short	rbit_mode;
extern	long	ibit_switch;
extern	short	ibit_request;
extern	short	rbit_request;
extern	short	mbi_board_test;
extern	DP	dp;
extern	DP	dp_tmp;
extern	ushort	pa[N_PABIT];
extern	ushort	pa_tmp[N_PABIT];
extern	short	count_1553B;
extern	short	count_gscint;
extern	short	rate_1553B;
extern	short	rate_gscint;
extern	short	rate_dsp_flag;
extern	short	digital_update_flag;
extern	short	digital_update_rate;
extern	long	bezel_sw_old;
extern	short	str_count;
extern	short	echo_mode;
extern	short	echo_status;
extern	uchar	du_send_data[6];
extern	ushort	check_sum;
extern	uchar	rs_IMR;
extern	SIM_WORK	sim_work[];
extern	short	test_data_no;
extern	short	test_data_change;
extern	short	grid_flag;

/*	cmndata.c	*/
extern	short	sg_mode;
extern	short	mbi_rcvbuf[44][32];
extern	short	ibit_mode;
extern	ushort	bezel_sw1;
extern	ushort	bezel_sw2;
extern	ushort	gsc_board_test;
extern	ushort	sg_bit_status;
extern	ushort	du_bit_status;

/*	chksum.c	*/
extern	word	chksum;
