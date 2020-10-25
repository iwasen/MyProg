/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: 共通ヘッダファイル			*
 *		ファイル名	: sg_cpu.h				*
 *									*
 ************************************************************************/

typedef	unsigned char	byte;
typedef	unsigned short	word;
typedef	unsigned short	ushort;
typedef	unsigned long	ulong;

#define	BIT0	0x01
#define	BIT1	0x02
#define	BIT2	0x04
#define	BIT3	0x08
#define	BIT4	0x10
#define	BIT5	0x20
#define	BIT6	0x40
#define	BIT7	0x80

/*	表示モード	*/
#define	MODE_AI		1	/* ＡＩ表示中 */
#define	MODE_HUD	2	/* ＨＵＤ表示中 */
#define	MODE_HDD	3	/* ＨＤＤ表示中 */
#define	MODE_MENU	4	/* メンテナンスモード・メニュー表示中 */
#define	MODE_DATA	5	/* メンテナンスモード・外光照度データ表示中 */

/*	タイマーフラグ	*/
#define	TIMER_STOP	0	/* 停止 */
#define	TIMER_START	1	/* 動作中 */

/*	メモリアドレス	*/
#define	ADR_RAP		(word *)0x140140	/* ＲＡＰ先頭アドレス */
#define	ADR_RAP_PA	(word *)0x140140	/* Ｐ／Ａビット */
#define	ADR_RAP_DP	(word *)0x1401C0	/* Ｄ／Ｐ */
#define	ADR_RAP_END	(word *)0x144000	/* ＲＡＰ終了アドレス */
#define	ADR_SRAM	(word *)0xff000000	/* ＳＲＡＭ先頭アドレス */
#define	ADR_SRAM_END	(word *)0xff07ffff	/* SRAM終了ｱﾄﾞﾚｽ */
#define	ADR_EEPROM	(word *)0xff080000	/* ＥＥＰＲＯＭ先頭アドレス */
#define	ADR_PRGEND	(ADR_EEPROM+0)		/* プログラム最終アドレス */

/*	ＶＩＣ０６８コントロールレジスタアドレス	*/
#define	REG_VIC_VICR3	(byte *)0xff40000f
#define	REG_VIC_VICR7	(byte *)0xff40001f
#define	REG_VIC_LICR1	(byte *)0xff400027
#define	REG_VIC_LICR2	(byte *)0xff40002b
#define	REG_VIC_LIVBR	(byte *)0xff400057
#define	REG_VIC_VIVR1	(byte *)0xff400087
#define	REG_VIC_VIVR2	(byte *)0xff40008b
#define	REG_VIC_VIVR3	(byte *)0xff40008f
#define	REG_VIC_VIVR4	(byte *)0xff400093
#define	REG_VIC_VIVR5	(byte *)0xff400097
#define	REG_VIC_VIVR6	(byte *)0xff40009b
#define	REG_VIC_VIVR7	(byte *)0xff40009f
#define	REG_VIC_TTR	(byte *)0xff4000a3
#define	REG_VIC_LBTR	(byte *)0xff4000a7
#define	REG_VIC_BESR	(byte *)0xff4000bb
#define	REG_VIC_ARCR	(byte *)0xff4000b3
#define	REG_VIC_SS0CR0	(byte *)0xff4000c3
#define	REG_VIC_SS1CR0	(byte *)0xff4000c7
#define	REG_VIC_RCR	(byte *)0xff4000d3

/*	ＤＵＡＲＴコントロールレジスタアドレス	*/
#define	REG_DUART_MR1A	(byte *)0xffc10003
#define	REG_DUART_MR2A	(byte *)0xffc10003
#define	REG_DUART_SRA	(byte *)0xffc10007
#define	REG_DUART_CSRA	(byte *)0xffc10007
#define	REG_DUART_CRA	(byte *)0xffc1000b
#define	REG_DUART_THRA	(byte *)0xffc1000f
#define	REG_DUART_ISR	(byte *)0xffc10017
#define	REG_DUART_IMR	(byte *)0xffc10017
#define	REG_DUART_MR1B	(byte *)0xffc10023
#define	REG_DUART_MR2B	(byte *)0xffc10023
#define	REG_DUART_SRB	(byte *)0xffc10027
#define	REG_DUART_CSRB	(byte *)0xffc10027
#define	REG_DUART_CRB	(byte *)0xffc1002b
#define	REG_DUART_RHRB	(byte *)0xffc1002e

/*	ＭＢＩボードコントロールレジスタアドレス	*/
#define	REG_MBI_ISW0	(word *)0x800000
#define	REG_MBI_IMSK	(word *)0x800008
#define	REG_MBI_CFCW	(word *)0x80000a
#define	REG_MBI_OPCW	(word *)0x80000c
#define	REG_MBI_RQTP	(word *)0x80000d
#define	REG_MBI_RQSP	(word *)0x80000e
#define	REG_MBI_RTID	(word *)0x800018
#define	REG_MBI_RQMT	(word *)0x800030
#define	REG_MBI_BMWB	(word *)0x800100
#define	REG_MBI_BRCS	(word *)0x800ffb

/*	ＷＤＴ制御レジスタアドレス	*/
#define	REG_WDT		(byte *)0xffc2003

/*	ＧＳＣレジスタアドレス	*/
#define	REG_GSC_OUTPUT	(word *)0xf0000

/*	Ｐ／Ａビット	*/
#define	PA1_F103	0x0001
#define	PA1_F104A	0x0002
#define	PA1_F104B	0x0004
#define	PA1_F104C	0x0008
#define	PA1_F104D	0x0010
#define	PA1_F104E	0x0020
#define	PA1_F104F	0x0040
#define	PA1_F105A	0x0080
#define	PA1_F105B	0x0100
#define	PA1_F105C	0x0200
#define	PA1_F106	0x0400
#define	PA1_F107	0x0800
#define	PA1_F108	0x1000
#define	PA1_F109A	0x2000
#define	PA1_F112A	0x4000
#define	PA1_F112B	0x8000
#define	PA2_F113A	0x0001
#define	PA2_F113B	0x0002
#define	PA2_F113C	0x0004
#define	PA2_F113D	0x0008
#define	PA3_F202	0x0001
#define	PA3_F203	0x0002
#define	PA3_F204	0x0004
#define	PA3_F205	0x0008
#define	PA3_F206	0x0010
#define	PA3_F207	0x0020
#define	PA3_F208	0x0040
#define	PA3_F209	0x0080
#define	PA3_F210	0x0100
#define	PA3_F112A	0x0200
#define	PA3_F112B	0x0400
#define	PA3_F211A	0x0800
#define	PA3_F211B	0x1000
#define	PA3_F211C	0x2000
#define	PA3_F211D	0x4000
#define	PA4_F301	0x0001
#define	PA4_F302	0x0002
#define	PA4_F303	0x0004
#define	PA4_F112A	0x0008
#define	PA4_F112B	0x0010
#define	PA4_F304A	0x0020
#define	PA4_F304B	0x0040
#define	PA5_F401	0x0040
#define	PA5_F402	0x0080
#define	PA5_F403	0x0100
#define	PA6_F501	0x0040
#define	PA6_F502	0x0080
#define	PA6_F503	0x0100
#define	PA6_F504	0x0200
#define	PA6_F505	0x0400
#define	PA6_F506	0x0800
#define	PA6_F507	0x1000
#define	PA6_F508	0x2000
#define	PA6_F509	0x4000

/*	フォント４アドレス	*/
#define	FONT_T4		0x4604
#define	FONT_T40	0x4608
#define	FONT_T41	0x4670
#define	FONT_T42	0x470c
#define	FONT_T43	0x478c
#define	FONT_T44	0x47c4
#define	FONT_T45	0x47d0
#define	FONT_T46	0x47f4
#define	FONT_T47	0x4828
#define	FONT_T48	0x4838
#define	FONT_T49	0x4878

/*	フォント５アドレス	*/
#define	FONT_T5		0x4b54
#define	FONT_T50	0x4b58
#define	FONT_T51	0x4ba8
#define	FONT_T52	0x4c30
#define	FONT_T53	0x4ca0
#define	FONT_T54	0x4ccc
#define	FONT_T55	0x4cdc
#define	FONT_T56	0x4cfc
#define	FONT_T57	0x4d28
#define	FONT_T58	0x4d38
#define	FONT_T59	0x4d70

/*	フィルタリング・時定数（逆数）		*/
#define	DELTA_t		60	/* 計算周期 (1/60 = 16.667ms) */
#define	T_IPTCHA	10	/* ピッチ (1/10 = 100ms) */
#define	T_IROLLA	10	/* ロール (1/10 = 100ms) */
#define	T_OHVVCX	10	/* Ｘ方向ベロシティー (1/10 = 100ms) */
#define	T_OHVVCY	10	/* Ｙ方向ベロシティー (1/10 = 100ms) */

/*	Ｈ００９飛行データ	*/
struct	H009	{
	short	IASPDT;		/* 真大気速度 */
	short	IHBALT;		/* 気圧高度 */
	short	IASPDI;		/* 指示大気速度 */
	short	IPTCHA;		/* 機体ピッチ */
	short	IROLLA;		/* 機体ロール */
	short	IMAGHD;		/* 機体磁方位 */
	short	OHVVCX;		/* Ｘ方向ベロシティーベクタ */
	short	OHVVCY;		/* Ｙ方向ベロシティーベクタ */
};

/*	外光照度データ	*/
struct	GAIKO	{
	ushort	display_no;	/* 表示Ｎｏ */
	ushort	data[15][2];	/* 外光照度データ */
};

/*	ＡＩ表示用Ｄ／Ｐデータ	*/
struct	DP_AI	{
	ushort	R104B;
	ushort	R104C;
	ushort	R104D;
	ushort	R104E;
	ushort	R104F;
	ushort	A1600;
	ushort	A1601;
	ushort	A1602;
	ushort	A1610;
	ushort	A1611;
	ushort	A1612;
	ushort	A1620;
	ushort	A1621;
	ushort	A1622;
	ushort	A1630;
	ushort	A1631;
	ushort	A1632;
	ushort	A1640;
	ushort	A1641;
	ushort	A1642;
	short	PY106;
	ushort	A1700;
	ushort	A1701;
	ushort	A1710;
	ushort	A1711;
	ushort	A1720;
	ushort	A1721;
	ushort	A1730;
	ushort	A1731;
	ushort	A1740;
	ushort	A1741;
	short	PX107;
	ushort	A1800;
	ushort	A1801;
	ushort	A1802;
	ushort	A1803;
	ushort	A1810;
	ushort	A1811;
	ushort	A1812;
	ushort	A1813;
	ushort	A1820;
	ushort	A1821;
	ushort	A1822;
	ushort	A1823;
	ushort	A1830;
	ushort	A1831;
	ushort	A1832;
	ushort	A1833;
	ushort	A1840;
	ushort	A1841;
	ushort	A1842;
	ushort	A1843;
	short	PY108;
	short	PY109A;
	ushort	LY109B;
	ushort	R100;
};

/*	ＨＵＤ表示用Ｄ／Ｐデータ	*/
struct	DP_HUD	{
	short	DP222;
	short	DP221;
	short	DP220;
	ushort	A2220;
	ushort	A2221;
	ushort	A2222;
	ushort	A2210;
	ushort	A2211;
	ushort	A2212;
	ushort	A2200;
	ushort	A2201;
	ushort	A2202;
	short	DP232;
	short	DP231;
	short	DP230;
	ushort	A2320;
	ushort	A2321;
	ushort	A2322;
	ushort	A2310;
	ushort	A2311;
	ushort	A2312;
	ushort	A2300;
	ushort	A2301;
	ushort	A2302;
	short	DP243;
	short	DP242;
	short	DP241;
	short	DP240;
	ushort	A2430;
	ushort	A2431;
	ushort	A2432;
	ushort	A2420;
	ushort	A2421;
	ushort	A2422;
	ushort	A2410;
	ushort	A2411;
	ushort	A2412;
	ushort	A2400;
	ushort	A2401;
	ushort	A2402;
	short	PX206;
	short	PY206;
	ushort	R200;
	ushort	LY207;
	ushort	LY209;
};

/*	ＨＤＤ表示用Ｄ／Ｐデータ	*/
struct	DP_HDD	{
	short	DP322;
	short	DP321;
	short	DP320;
	ushort	A3220;
	ushort	A3221;
	ushort	A3222;
	ushort	A3210;
	ushort	A3211;
	ushort	A3212;
	ushort	A3200;
	ushort	A3201;
	ushort	A3202;
	short	DP333;
	short	DP332;
	short	DP331;
	short	DP330;
	ushort	A3330;
	ushort	A3331;
	ushort	A3332;
	ushort	A3320;
	ushort	A3321;
	ushort	A3322;
	ushort	A3310;
	ushort	A3311;
	ushort	A3312;
	ushort	A3300;
	ushort	A3301;
	ushort	A3302;
};

/*	外光照度データ表示用Ｄ／Ｐデータ	*/
struct	DP_DATA	{
	ushort	data[15][18];
};

/*	Ｐ／Ａビット	*/
struct	PA_BIT	{
	ushort	PA00001;
	ushort	PA00002;
	ushort	PA00003;
	ushort	PA00004;
	ushort	PA00005;
	ushort	PA00006;
};

/*	外部関数	*/
extern	void	power_on(void);
extern	void	set_DUART(void);
extern	void	intmbi_bit(void);
extern	void	intmbi_rx(void);
extern	void	dp_ai_pitch(void);
extern	void	dp_ai_roll(void);
extern	void	dp_ai_mhdg(void);
extern	void	dp_ai_tas(void);
extern	void	dp_ai_alt(void);
extern	void	dp_ai_rollscale(void);
extern	void	dp_hud_pitch(void);
extern	void	dp_hud_roll(void);
extern	void	dp_hud_ias(void);
extern	void	dp_hud_mhdg(void);
extern	void	dp_hud_alt(void);
extern	void	dp_hud_vvc(void);
extern	void	dp_hdd_tas(void);
extern	void	dp_hdd_alt(void);
extern	void	dp_gaikou(void);
extern	void	intmbi_tx(void);
extern	void	intrs2(void);
extern	void	int10ms(void);
extern	void	intgsc(void);
extern	void	debug(void);

/*	ＯＳ（ＯＲＫＩＤ）内ルーチン	*/
extern	void	ORKID(void);
extern	void	mbibit(void);

/*	ＲＡＭデータ (ramdata.c)	*/
extern	short	sg_mode;
extern	struct	H009	orgdata;
extern	struct	H009	tmpdata;
extern	struct	H009	fltdata;
extern	struct	GAIKO	lumdata;
extern	struct	DP_AI	dp_ai;
extern	struct	DP_HUD	dp_hud;
extern	struct	DP_HDD	dp_hdd;
extern	struct	DP_DATA	dp_data;
extern	struct	PA_BIT	pa_bit;
extern	word	command_word;
extern	short	r1553B_data;
extern	word	rs_receive_data;
extern	short	rs_counter;
extern	short	gsc_start_flag;
extern	short	mbi_start_flag;
extern	word	video_control;
extern	short	tmf_mbibit;
extern	short	tmf_1553B;
extern	short	tmf_rsblock;
extern	short	tmf_rschar;
extern	short	tmc_mbibit;
extern	short	tmc_1553B;
extern	short	tmc_rsblock;
extern	short	tmc_rschar;
extern	short	tmc_debug;
extern	short	tmo_1553B;
extern	short	tmo_rsblock;
extern	short	memorizing_flag;
extern	short	debug_mode_flag;

/*	ＲＯＭデータ (romdata.c)	*/
extern	word	numfont4_tbl[];
extern	word	numfont5_tbl[];
