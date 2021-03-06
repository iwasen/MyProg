/************************************************************************
 *									*
 *		õg\¦u@r^f@botvO		*
 *									*
 *		¼Ì		: ¤Êwb_t@C			*
 *		t@C¼	: sg_cpu.h				*
 *									*
 ************************************************************************/

/*	^è`		*/
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

/*	rbgÊu	*/
#define	BIT0	0x01
#define	BIT1	0x02
#define	BIT2	0x04
#define	BIT3	0x08
#define	BIT4	0x10
#define	BIT5	0x20
#define	BIT6	0x40
#define	BIT7	0x80

/*	r^f[h	*/
#define	MODE_NOSYMBOL	0	/* V{\¦³µ */
#define	MODE_BIT	1	/* ahsd[h */
#define	MODE_TESTPTN	2	/* eXgp^[[h	*/
#define	MODE_SAKUTEKI0	3	/* õGTCg[h0 */
#define	MODE_SAKUTEKI1	4	/* õGTCg[hP */
#define	MODE_SAKUTEKI2	5	/* õGTCg[hQ */
#define	MODE_SAKUTEKI3	6	/* õGTCg[hR */
#define	MODE_MISSILE	7	/* ~TCxú[h */

/*	ahsXe[^X	*/
#define	BIT_END		0	/* I¹ */
#define	BIT_START	1	/* Jn */
#define	BIT_NORMAL	2	/* ³í */
#define	BIT_DUERR	3	/* c^tG[ */

/*	^C}[tO	*/
#define	TIMER_STOP	0	/* â~ */
#define	TIMER_START	1	/* ®ì */

/*	FR[h	*/
#define	COLOR_RED	0x0000
#define	COLOR_GREEN	0x0100
#define	COLOR_BLUE	0x0200
#define	COLOR_WHITE	0x0300
#define	COLOR_YELLOW	0x0500
#define	COLOR_MAGENTA	0x0700
#define	COLOR_CYAN	0x0b00
#define	COLOR_BLACK	0x0e00

/*	tHgAhX	*/
#define	FONT7_SP	0x4ff8
#define	FONT7_PLUS	0x501c
#define	FONT7_MINUS	0x502c
#define	FONT7__		0x5070
#define	FONT7_0		0x4ffc
#define	FONT7_1		0x5040
#define	FONT7_2		0x50c8
#define	FONT7_3		0x5138
#define	FONT7_4		0x5164
#define	FONT7_5		0x5174
#define	FONT7_6		0x5194
#define	FONT7_7		0x51c0
#define	FONT7_8		0x51d0
#define	FONT7_9		0x5208
#define	FONT7_A		0x5234
#define	FONT7_B		0x524c
#define	FONT7_C		0x5270
#define	FONT7_D		0x5290
#define	FONT7_E		0x52a4
#define	FONT7_F		0x52c0
#define	FONT7_G		0x52d0
#define	FONT7_H		0x52f4
#define	FONT7_I		0x5304
#define	FONT7_J		0x5314
#define	FONT7_K		0x532c
#define	FONT7_L		0x533c
#define	FONT7_M		0x5348
#define	FONT7_N		0x5358
#define	FONT7_O		0x5364
#define	FONT7_P		0x5384
#define	FONT7_Q		0x539c
#define	FONT7_R		0x53c4
#define	FONT7_S		0x53e0
#define	FONT7_T		0x540c
#define	FONT7_U		0x5418
#define	FONT7_V		0x5430
#define	FONT7_W		0x5440
#define	FONT7_X		0x5450
#define	FONT7_Y		0x5468
#define	FONT7_Z		0x5480

#define	FONT8_SP	0x5490
#define	FONT8_PLUS	0x54bc
#define	FONT8_MINUS	0x54cc
#define	FONT8__		0x5524
#define	FONT8_0		0x5494
#define	FONT8_1		0x54f4
#define	FONT8_2		0x5598
#define	FONT8_3		0x561c
#define	FONT8_4		0x5654
#define	FONT8_5		0x5664
#define	FONT8_6		0x5688
#define	FONT8_7		0x56bc
#define	FONT8_8		0x56cc
#define	FONT8_9		0x570c
#define	FONT8_A		0x5748
#define	FONT8_B		0x5760
#define	FONT8_C		0x578c
#define	FONT8_D		0x57b4
#define	FONT8_E		0x57d0
#define	FONT8_F		0x57e8
#define	FONT8_G		0x57fc
#define	FONT8_H		0x582c
#define	FONT8_I		0x5840
#define	FONT8_J		0x5854
#define	FONT8_K		0x5870
#define	FONT8_L		0x5888
#define	FONT8_M		0x5898
#define	FONT8_N		0x58a8
#define	FONT8_O		0x58bc
#define	FONT8_P		0x58e4
#define	FONT8_Q		0x5900
#define	FONT8_R		0x592c
#define	FONT8_S		0x594c
#define	FONT8_T		0x5980
#define	FONT8_U		0x5990
#define	FONT8_V		0x59ac
#define	FONT8_W		0x59c0
#define	FONT8_X		0x59d8
#define	FONT8_Y		0x59f4
#define	FONT8_Z		0x5a0c

/*	AhX	*/
#define	ADR_RAP		(word *)0x140140	/* q`oæªAhX */
#define	ADR_RAP_PA	(word *)0x140140	/* o^`rbg */
#define	ADR_RAP_DP	(word *)0x1401C0	/* c^o */
#define	ADR_RAP_END	(word *)0x144000	/* q`oI¹AhX */
#define	ADR_SRAM	(word *)0xff000000	/* rq`læªAhX */
#define	ADR_SRAM_END	(word *)0xff07ffff	/* SRAMI¹           */
#define	ADR_EEPROM	(word *)0xff080000	/* ddoqnlæªAhX */
#define	ADR_PRGEND	(ADR_EEPROM+0)		/* vOÅIAhX */

/*	uhbOUWRg[WX^AhX	*/
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

/*	ct`qsRg[WX^AhX	*/
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

/*	lah{[hRg[WX^AhX	*/
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
#define	REG_MBI_BMWB	(word *)0x800200
#define	REG_MBI_BRCS	(word *)0x801ff6

/*	vcs§äWX^AhX	*/
#define	REG_WDT		(byte *)0xffc20003

/*	frbWX^AhX	*/
#define REG_GSC_OUTPUT  (word *)0xf0000
#define REG_GSC_INPUT   (word *)0xf0002
#define REG_GSC_OPRATE  (word *)0x140000
#define REG_GSC_ACK     (word *)0x143ffe

/*	qr|QRQbóMobt@TCY	*/
#define	RSA_SBUFSIZE	10
#define	RSB_SBUFSIZE	4096
#define	RSB_RBUFSIZE	80

/*	o^`rbg[h	*/
#define	N_PABIT		64

/*	}Nè`	*/
#define	ROUND(x,n)	((x + ((x < 0) ? -n : n) / 2) / n)
#define	ABS(x)		(x >= 0 ? x : -x)
#define	MAX(a,b)	(a >= b ? a : b)
#define	MIN(a,b)	(a >= b ? b : a)
#define	SWAP(a,b)	{short tmp; tmp=a; a=b; b=tmp;}
#define	CHECK(n,min,max)	(n < min || n > max)
#define	ARC_LENGTH(r)	(ushort)(((r) * 0x6487eL) >> 16)
#define	DOT_TO_MM(dot)	((dot) * 15)
#define	MM_TO_DP(mm)	((((long)(mm) * 1024) / 1925) & 0xfffe)
#define	DOT_TO_DP(dot)	MM_TO_DP(DOT_TO_MM(dot))
#define	COPY_WORD(dst, src, size)	{struct copy {short tmp[size];}; *(struct copy *)dst=*(struct copy *)src;}
#define	SET_WORD(dst, val, size)	{int i;for (i=0; i<size; i++) ((short *)dst)[i] = val;}
#define	SET_PABIT(bit)	pa[(bit) / 16] |= bit_tbl[(bit) % 16]

/*	oCgWX^Ýèf[^	*/
typedef	struct	{
	byte	*address;
	byte	data;
	short	wait_time;
} BYTE_REG_DATA;

/*	üÍf[^V~[Vf[^	*/
typedef	struct	{
	short	cur_data;
	short	counter;
} SIM_WORK;

/*	init.c		*/
extern	void	set_DUART(void);
extern	void	power_on_seq(void);

/*	inpdata.c	*/
extern	int	data_check(int);

/*	calc_dp.c	*/
extern	void	calc_dp(int);

/*	cmnsub.c	*/
extern	void	rom_check(void);
extern	void	ram_check(void);
extern	void	memory_error(long);
extern	void	mbibit(void);
extern	void	mskclr(void);
extern	void	mskset(void);
extern	void	wait100(void);
extern	void	int_enable(void);
extern	void	int_disable(void);
extern	void	write_byte_register(BYTE_REG_DATA *, short);
extern	short	sqrt(long);
extern	short	sin(short);
extern	short	cos(short);
extern	ushort	atan(short, short);
extern	void	tonumR(ushort, ushort *, short, ushort *, ushort);
extern	void	tonumL(ushort, ushort *, short, ushort *, ushort);
extern	void	tohex(ushort, ushort *, short, ushort *);
extern	short	radius(short);
extern	void	rsa_send(char *, short);
extern	void	rsb_send(char *, short);
extern	void	msgout(char *);
extern	void	shorttohex(short, char *, short);
extern	void	longtohex(long, char *, short);

/*	error.c		*/
extern	void	abort(char *);

/*	debug1.c	*/
extern	void	debug_command(void);

/*	debug2.c	*/
extern	void	debug_simulation(void);

/*	romdata.c	*/
extern	short	debug_mode;
extern	char	hex[];
extern	ushort	bit_tbl[];
extern	ushort	font8_tbl[];
extern	short	sin_tbl[];
extern	short	atan_tbl[];

/*	chksum.c	*/
extern	word	chksum;

/*	ramdata.c	*/
extern	short	debug_flag;
extern	char	rsa_sndbuf[RSA_SBUFSIZE];
extern	short	rsa_sndwptr;
extern	short	rsa_sndrptr;
extern	short	rsa_rcvcounter;
extern	byte	rsa_rcvchar;

extern	char	rsb_sndbuf[RSB_SBUFSIZE];
extern	short	rsb_sndwptr;
extern	short	rsb_sndrptr;
extern	char	rsb_rcvbuf[RSB_RBUFSIZE];
extern	short	rsb_rcvcounter;

extern	short	sub_adrs_count;
extern	short	mbi_rcvbuf[512];
extern	short	input_data[512];
extern	short	ibuf_flag;

extern	short	tmf_mbibit;
extern	short	tmf_1553B;
extern	short	tmf_dspmode;
extern	short	tmf_dubit;
extern	short	tmf_poweron;
extern	short	tmf_rschar;

extern	short	tmc_mbibit;
extern	short	tmc_1553B;
extern	short	tmc_dspmode;
extern	short	tmc_dubit;
extern	short	tmc_poweron;
extern	short	tmc_rschar;
extern	short	tmc_blink;
extern	short	tmc_digital;
extern	short	tmc_simulation;

extern	short	sg_mode;
extern	short	old_sg_mode;

extern	short	dot_video;
extern	short	data_error_flag;
extern	DP	dp;
extern	DP	dp_tmp;
extern	ushort	pa[N_PABIT];
extern	ushort	pa_tmp[N_PABIT];
extern	short	message_data[4];
extern	short	message_no[4];
extern	short	blink_flag;
extern	short	count_1553B;
extern	short	count_gscint;
extern	short	rate_1553B;
extern	short	rate_gscint;
extern	short	rate_dsp_flag;
extern	short	digital_update_flag;
extern	short	digital_update_rate;
extern	short	rcv_1553B_flag;

extern	short	seq_power_on;
extern	ushort	check_sum;
extern	byte	video_mode;
extern	short	bit_status;
extern	short	bit_data;
extern	uchar	rs_IMR;

extern	short	test_data_no;
extern	short	test_data_change;
extern	SIM_WORK	sim_work[512];

extern	short	count_calc;
extern	short	rate_calc;
