/************************************************************************
 *									*
 *		ＲＳ－２３２Ｃライブラリ				*
 *									*
 *		名称		: ヘッダファイル			*
 *		ファイル名	: rs232c.h				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

/*	チャネル番号	*/
#define	RSCH1		0		/* チャネル１ */
#define	RSCH2		1		/* チャネル２ */
#define	RSCH3		2		/* チャネル３ */

/*	同期モード	*/
#define	SYNC		0		/* 同期モード */
#define	ASYNC		1		/* 調歩同期モード */

/*	全／半二重モード	*/
#define	DUPLEX_FULL	0		/* 全二重 */
#define	DUPLEX_HALF	1		/* 半二重 */

/*	ボーレート	*/
#define	BLT_75		0
#define	BLT_150		1
#define	BLT_300		2
#define	BLT_600		3
#define	BLT_1200	4
#define	BLT_2400	5
#define	BLT_4800	6
#define	BLT_9600	7
#define	BLT_19200	8

/*	パリティー	*/
#define	PARITY_NONE	0x00		/* パリティー無し */
#define	PARITY_EVEN	0x30		/* 偶数パリティー */
#define	PARITY_ODD	0x10		/* 奇数パリティー */

/*	キャラクタ長	*/
#define	CHR_5		0x00		/* ５ｂｉｔ */
#define	CHR_6		0x04		/* ６ｂｉｔ */
#define	CHR_7		0x08		/* ７ｂｉｔ */
#define	CHR_8		0x0c		/* ８ｂｉｔ */

/*	ストップビット長	*/
#define	STP_1		0x40		/* １ビット */
#define	STP_1_5		0x80		/* １．５ビット */
#define	STP_2		0xc0		/* ２ビット */

/*	フロー制御	*/
#define	FLOW_NONE	0		/* フロー制御無し */
#define	FLOW_SOFT	1		/* ＸＯＮ／ＸＯＦＦ制御 */
#define	FLOW_HARD	2		/* ＲＴＳ／ＣＴＳ制御 */

/*	ＤＴＲ制御	*/
#define	DTR_OFF		0
#define	DTR_ON		1

/*	割り込みコントローラＩ／Ｏポートアドレス	*/
#define	IOP_ICM_EOI	0x00		/* マスタ割り込みコントローラ */
#define	IOP_ICM_IMR	0x02		/* マスタ割り込みマスクレジスタ */
#define	IOP_ICS_EOI	0x08		/* スレーブ割り込みコントローラ */
#define	IOP_ICS_IMR	0x0a		/* スレーブ割り込みマスクレジスタ */

/*	ＳＩＯ－ＣＨ１ Ｉ／Ｏポートアドレス	*/
#define	IOP_RS1_DAT	0x30		/* データＲ／Ｗレジスタ */
#define	IOP_RS1_STS	0x32		/* ステータスレジスタ */
#define	IOP_RS1_MOD	0x32		/* モード／コマンドレジスタ */
#define	IOP_RS1_SYN	0x32		/* 同期キャラクタレジスタ */

/*	ＳＩＯ－ＣＨ２ Ｉ／Ｏポートアドレス	*/
#define	IOP_RS2_IMR	0xb0		/* 割り込みマスクレジスタ */
#define	IOP_RS2_SGN	0xb0		/* リードシグナル／割り込みレベル */
#define	IOP_RS2_DAT	0xb1		/* データＲ／Ｗレジスタ */
#define	IOP_RS2_STS	0xb3		/* ステータスレジスタ */
#define	IOP_RS2_MOD	0xb3		/* モード／コマンドレジスタ */
#define	IOP_RS2_SYN	0xb3		/* 同期キャラクタレジスタ */

/*	ＳＩＯ－ＣＨ３ Ｉ／Ｏポートアドレス	*/
#define	IOP_RS3_IMR	0xb2		/* 割り込みマスクレジスタ */
#define	IOP_RS3_SGN	0xb2		/* リードシグナル／割り込みレベル */
#define	IOP_RS3_DAT	0xb9		/* データＲ／Ｗレジスタ */
#define	IOP_RS3_STS	0xbb		/* ステータスレジスタ */
#define	IOP_RS3_MOD	0xbb		/* モード／コマンドレジスタ */
#define	IOP_RS3_SYN	0xbb		/* 同期キャラクタレジスタ */

/*	システムポート	*/
#define	IOP_SYS_SGN	0x33		/* リードシグナル */
#define	IOP_SYS_RD	0x35		/* 読み込み */
#define	IOP_SYS_WRT	0x35		/* 書き込み */
#define	IOP_SYS_SET	0x37		/* ビットセット */

/*	タイマコントローラＩ／Ｏポートアドレス	*/
#define	IOP_TMC_CWW	0x77		/* コントロールワードライト */
#define	IOP_TMC_C2L	0x75		/* カウント＃２ロード */
#define	IOP_TMC_C0L	0x71		/* カウンタ＃０ロード */

/*	ＳＩＯコマンド	*/
#define	CMD_TXE		0x01
#define	CMD_DTR		0x02
#define	CMD_RXE		0x04
#define	CMD_SBRK	0x08
#define	CMD_ER		0x10
#define	CMD_RTS		0x20
#define	CMD_IR		0x40
#define	CMD_EH		0x80

/*	ＳＩＯステータス	*/
#define	STS_TXRDY	0x01
#define	STS_RXRDY	0x02
#define	STS_TXE		0x04
#define	STS_PE		0x08
#define	STS_OE		0x10
#define	STS_FE		0x20
#define	STS_SYNDET	0x40
#define	STS_DSR		0x80

/*	ＲＳ－２３２Ｃ信号線	*/
#define	SGN_CD		0x20
#define	SGN_CS		0x40
#define	SGN_CI		0x80

/*	ＲＳ－２３２Ｃ割り込みマスク	*/
#define	IMR_RXRE	0x01
#define	IMR_TXEE	0x02
#define	IMR_TXRE	0x04

/*	ＸＯＮ／ＸＯＦＦコード	*/
#define	XON		0x11
#define	XOFF		0x13

/*	型定義		*/
#ifndef	u_int
#define	u_int	unsigned int
#endif
#ifndef	u_char
#define	u_char	unsigned char
#endif

/*	ＲＳ－２３２Ｃオープンパラメータ	*/
struct	rsprm	{
	int	sync_mode;	/* 同期モード */
	int	duplex;		/* 二重モード */
	int	blt;		/* ボーレート */
	int	parity;		/* パリティー */
	int	chr_len;	/* キャラクタ長 */
	int	stp_len;	/* ストップビット長 */
	int	flow_ctrl;	/* フロー制御 */
	int	dtr;		/* ＤＴＲ制御 */
	u_int	syn_chr;	/* 同期キャラクタ */
	u_int	buf_size;	/* 受信バッファサイズ */
	char	*bufp;		/* 受信バッファポインタ */
};
typedef	struct	rsprm	RSPRM;

/*	関数プロトタイプ	*/
/*	rsx.c	*/
extern	int	rsx_open(int, RSPRM *);
extern	int	rsx_reopen(int, RSPRM *);
extern	void	rsx_close(int);
extern	int	rsx_rxc(int);
extern	int	rsx_get(int);
extern	void	rsx_clear(int);
extern	void	rsx_put(int, int);
extern	void	rsx_send(int, char *, int);
extern	int	rsx_sendreq(int, char *, int);
extern	void	rsx_swait(int);
extern	void	rsx_flow(int, int);
extern	void	rsx_pad(int, char *, int, char *, int);
extern	void	rsx_rcvint(int, int (*)(int));
extern	void	rsx_ehm(int);
extern	int	rsx_dsr(int);
extern	int	rsx_cd(int);
extern	int	rsx_cts(int);
extern	int	rsx_ci(int);
extern	void	rsx_dtr(int, int);
extern	void	rsx_rts(int, int);
extern	void	rsx_break(int, int);
extern	void	rsx_cmd(int, int ,int);
extern	int	rsx_sts(int);

/*	rs1.c	*/
extern	int	rs1_open(RSPRM *);
extern	int	rs1_reopen(RSPRM *);
extern	void	rs1_close(void);
extern	int	rs1_rxc(void);
extern	int	rs1_get(void);
extern	void	rs1_clear(void);
extern	void	rs1_put(int);
extern	void	rs1_send(char *, int);
extern	int	rs1_sendreq(char *, int);
extern	void	rs1_swait(void);
extern	void	rs1_flow(int);
extern	void	rs1_pad(char *, int, char *, int);
extern	void	rs1_rcvint(int (*)(int));
extern	void	rs1_ehm(void);
extern	int	rs1_dsr(void);
extern	int	rs1_cd(void);
extern	int	rs1_cts(void);
extern	int	rs1_ci(void);
extern	void	rs1_dtr(int);
extern	void	rs1_rts(int);
extern	void	rs1_break(int);
extern	void	rs1_cmd(int ,int);
extern	int	rs1_sts(void);

/*	rs2.c	*/
extern	int	rs2_open(RSPRM *);
extern	int	rs2_reopen(RSPRM *);
extern	void	rs2_close(void);
extern	int	rs2_rxc(void);
extern	int	rs2_get(void);
extern	void	rs2_clear(void);
extern	void	rs2_put(int);
extern	void	rs2_send(char *, int);
extern	int	rs2_sendreq(char *, int);
extern	void	rs2_swait(void);
extern	void	rs2_flow(int);
extern	void	rs2_pad(char *, int, char *, int);
extern	void	rs2_rcvint(int (*)(int));
extern	void	rs2_ehm(void);
extern	int	rs2_dsr(void);
extern	int	rs2_cd(void);
extern	int	rs2_cts(void);
extern	int	rs2_ci(void);
extern	void	rs2_dtr(int);
extern	void	rs2_rts(int);
extern	void	rs2_break(int);
extern	void	rs2_cmd(int ,int);
extern	int	rs2_sts(void);

/*	rs3.c	*/
extern	int	rs3_open(RSPRM *);
extern	int	rs3_reopen(RSPRM *);
extern	void	rs3_close(void);
extern	int	rs3_rxc(void);
extern	int	rs3_get(void);
extern	void	rs3_clear(void);
extern	void	rs3_put(int);
extern	void	rs3_send(char *, int);
extern	int	rs3_sendreq(char *, int);
extern	void	rs3_swait(void);
extern	void	rs3_flow(int);
extern	void	rs3_pad(char *, int, char *, int);
extern	void	rs3_rcvint(int (*)(int));
extern	void	rs3_ehm(void);
extern	int	rs3_dsr(void);
extern	int	rs3_cd(void);
extern	int	rs3_cts(void);
extern	int	rs3_ci(void);
extern	void	rs3_dtr(int);
extern	void	rs3_rts(int);
extern	void	rs3_break(int);
extern	void	rs3_cmd(int ,int);
extern	int	rs3_sts(void);

/*	timer.c	*/
extern	int	tm_open(void);
extern	int	tm_set(int, int);
extern	int	tm_check(int);
extern	void	tm_stop(int);
extern	void	tm_close(void);
extern	long	tm_counter;

/*	コマンドレジスタ	*/
extern	u_char	rs1_cmdreg;		/* ＣＨ１ */
extern	u_char	rs2_cmdreg;		/* ＣＨ２ */
extern	u_char	rs3_cmdreg;		/* ＣＨ３ */

/*	ＭＳ－Ｃの関数をＴＵＲＢＯ－Ｃの関数に置き換える */
#ifdef	__TURBOC__
#define	_dos_getvect(x)		getvect(x)
#define	_dos_setvect(x,y)	setvect(x,y)
#ifndef	_enable
#define	_enable()		enable()
#endif
#ifndef	_disable
#define	_disable()		disable()
#endif
#endif
