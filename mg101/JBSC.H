/*	THIS	HEADER	IS	FOR	JBSC.C		*/

#define		C_NULL		0x00

		/*	RESPONSE  TO  HC-10	*/

#define		R_NORMAL	0	/*     	NORMAL			*/
#define		R_NOTOPEN	1	/*	NOT OPEN ERROR		*/
#define		R_EXTOPEN	2	/*	ALREADY OPENED ERROR	*/
#define		R_TOCERR	3	/*	TIME OVER COUNT ERROR	*/
#define		R_RTYERR	4	/*	RETRY OVER ERROR	*/
#define		R_SEND		5	/*	SEND FINISH ERROR	*/
#define		R_REC		6	/*	RECEIVE FINISH ERROR	*/
#define		R_ACKERR	7	/*	ALTERNATIVE ACK ERROR	*/
#define		R_RVIREC	8	/*	WHEN SEND , RECEIVE RVI	*/
#define		R_EOTREC	9	/*	WHEN SEND , RECEIVE EOT	*/

		/*	REQUEST		FLAG	*/

#define		NO_REQUEST		0
#define		SEND_REQUEST		1
#define		RECEIVE_REQUEST		2
#define		DISCONxxxT_REQUEST	3

		/*	RETRY	COUNTER		*/

#define		RNA_TOC		7
#define		RNB_TTD		15
#define		RNA_ACK		7
#define		RNA_NAK		7
#define		RNA_WACK	7
#define		RNB_WACK	15

#define		ON		1	/*	FLAG	ON		*/
#define		OFF		0	/*	FLAG	OFF		*/
#define		TMWAIT3		3	/*	TIMER 	WAIT	3 sec	*/
#define		TMWAIT2		2	/*	TIMER	WAIT	2 sec	*/

#define		F2		2	/*	MODEM	STATUS		*/

#define		ST0		0
#define		ST1		1
#define		ST2		2
#define		ST3		3
#define		ST4		4
#define		ST5		5
#define		ST6		6
#define		ST7		7
#define		ST8		8

		/*	EXTERN		*/

extern	int	st;			/*	STATUS		*/
extern	int	blklng;			/* BLOCK      LENGTH	*/
/*
extern	char	rxsts;			/* CRC BIT 6  CHECK	*/
*/
extern	char	rxchr;			/* RECEIVE    CHARACTER	*/
extern	CRCDATA crcrdt;			/* CRC	VALUE	FIELD	*/

extern	int	crc16(char, int);	/* CRC	CHECK GENERATOR */
extern	int	md_opn(void);		/* MODEM      OPEN	*/
extern	void	md_cls(void);		/* MODEM      CLOSE	*/
extern	void	sndrsp(int);		/* RESPONSE   TO HC-10	*/
extern	void	sndrdt(char *, int);	/* SEND  DATA TO HC-10	*/
extern	void	sndmdm(char *, int);	/* SEND CNTRL TO MODEM	*/
extern	void	sndmdc(char *, int);	/* SEND  DATA TO MODEM	*/
extern	void	sndrty(void);		/* RETRY SEND TO MODEM	*/
extern	void	timst1(int);		/* TIMER SET		*/

extern	char	sdata[9][300];		/* SEND		DATA	*/
extern	char	rdata[];		/* RECEIVE	DATA	*/

extern	char	blkcnt, sd_flag1, sd_flag2;
extern	int	sd_blkcnt, rd_blkcnt;

		/* THESE SOURCES ARE LIMITED	*/

static	char	enq[]	= {1,C_ENQ};
static	char	eot[]	= {1,C_EOT};
static	char	ack0[]	= {2,C_DLE,C_ACK0};
static	char	ack1[]	= {2,C_DLE,C_ACK1};
static	char	wack[]	= {2,C_DLE,C_WACK};
static	char	nak[]	= {1,C_NAK};
static	char	rvi[]	= {2,C_DLE,C_RVI};
static	char	ttd[]	= {2,C_STX,C_TTD};
static	char	deot[]	= {2,C_DLE,C_EOT};

static	int	s0_p1() ,s0_p2() ,s0_p3() ,s0_p4() ;
static	int	s0_p5() ,s0_p6() ,s0_p7() ,s0_p8() ;
static	int	s0_p9() ,s0_p10(),s0_p11(),s0_p12();
static	int	s0_p13(),s0_p14(),s0_p15(),s0_p16();

static	int	s1_p1() ,s1_p2() ,s1_p3() ,s1_p4() ;
static	int	s1_p5() ,s1_p6() ,s1_p7() ,s1_p8() ;
static	int	s1_p9() ,s1_p10(),s1_p11(),s1_p12();
static	int	s1_p13(),s1_p14(),s1_p15(),s1_p16();

static	int	s2_p1() ,s2_p2() ,s2_p3() ,s2_p4() ;
static	int	s2_p5() ,s2_p6() ,s2_p7() ,s2_p8() ;
static	int	s2_p9() ,s2_p10(),s2_p11(),s2_p12();
static	int	s2_p13(),s2_p14(),s2_p15(),s2_p16();

static	int	s3_p1() ,s3_p2() ,s3_p3() ,s3_p4() ;
static	int	s3_p5() ,s3_p6() ,s3_p7() ,s3_p8() ;
static	int	s3_p9() ,s3_p10(),s3_p11(),s3_p12();
static	int	s3_p13(),s3_p14(),s3_p15(),s3_p16();

static	int	s4_p1() ,s4_p2() ,s4_p3() ,s4_p4() ;
static	int	s4_p5() ,s4_p6() ,s4_p7() ,s4_p8() ;
static	int	s4_p9() ,s4_p10(),s4_p11(),s4_p12();
static	int	s4_p13(),s4_p14(),s4_p15(),s4_p16();

static	int	s5_p1() ,s5_p2() ,s5_p3() ,s5_p4() ;
static	int	s5_p5() ,s5_p6() ,s5_p7() ,s5_p8() ;
static	int	s5_p9() ,s5_p10(),s5_p11(),s5_p12();
static	int	s5_p13(),s5_p14(),s5_p15(),s5_p16();

static	int	s6_p1() ,s6_p2() ,s6_p3() ,s6_p4() ; 
static	int	s6_p5() ,s6_p6() ,s6_p7() ,s6_p8() ;
static	int	s6_p9() ,s6_p10(),s6_p11(),s6_p12();
static	int	s6_p13(),s6_p14(),s6_p15(),s6_p16();

static	int	s7_p1() ,s7_p2() ,s7_p3() ,s7_p4() ;
static	int	s7_p5() ,s7_p6() ,s7_p7() ,s7_p8() ;
static	int	s7_p9() ,s7_p10(),s7_p11(),s7_p12();
static	int	s7_p13(),s7_p14(),s7_p15(),s7_p16();

static	int	s8_p1() ,s8_p2() ,s8_p3() ,s8_p4() ;
static	int	s8_p5() ,s8_p6() ,s8_p7() ,s8_p8() ;
static	int	s8_p9() ,s8_p10(),s8_p11(),s8_p12();
static	int	s8_p13(),s8_p14(),s8_p15(),s8_p16();

static	int (*label[][])() = {

		{ s0_p1,s0_p2 ,s0_p3 ,s0_p4 ,s0_p5 ,s0_p6 ,s0_p7 ,s0_p8 ,
		  s0_p9,s0_p10,s0_p11,s0_p12,s0_p13,s0_p14,s0_p15,s0_p16 } ,

		{ s1_p1,s1_p2 ,s1_p3 ,s1_p4 ,s1_p5 ,s1_p6 ,s1_p7 ,s1_p8 ,
		  s1_p9,s1_p10,s1_p11,s1_p12,s1_p13,s1_p14,s1_p15,s1_p16 } ,

		{ s2_p1,s2_p2 ,s2_p3 ,s2_p4 ,s2_p5 ,s2_p6 ,s2_p7 ,s2_p8 ,
		  s2_p9,s2_p10,s2_p11,s2_p12,s2_p13,s2_p14,s2_p15,s2_p16 } ,

		{ s3_p1,s3_p2 ,s3_p3 ,s3_p4 ,s3_p5 ,s3_p6 ,s3_p7 ,s3_p8 ,
		  s3_p9,s3_p10,s3_p11,s3_p12,s3_p13,s3_p14,s3_p15,s3_p16 } ,

		{ s4_p1,s4_p2 ,s4_p3 ,s4_p4 ,s4_p5 ,s4_p6 ,s4_p7 ,s4_p8 ,
		  s4_p9,s4_p10,s4_p11,s4_p12,s4_p13,s4_p14,s4_p15,s4_p16 } ,

		{ s5_p1,s5_p2 ,s5_p3 ,s5_p4 ,s5_p5 ,s5_p6 ,s5_p7 ,s5_p8 ,
		  s5_p9,s5_p10,s5_p11,s5_p12,s5_p13,s5_p14,s5_p15,s5_p16 } ,

		{ s6_p1,s6_p2 ,s6_p3 ,s6_p4 ,s6_p5 ,s6_p6 ,s6_p7 ,s6_p8 ,
		  s6_p9,s6_p10,s6_p11,s6_p12,s6_p13,s6_p14,s6_p15,s6_p16 } ,

		{ s7_p1,s7_p2 ,s7_p3 ,s7_p4 ,s7_p5 ,s7_p6 ,s7_p7 ,s7_p8 ,
		  s7_p9,s7_p10,s7_p11,s7_p12,s7_p13,s7_p14,s7_p15,s7_p16 } ,

		{ s8_p1,s8_p2 ,s8_p3 ,s8_p4 ,s8_p5 ,s8_p6 ,s8_p7 ,s8_p8 ,
		  s8_p9,s8_p10,s8_p11,s8_p12,s8_p13,s8_p14,s8_p15,s8_p16 } ,

				} ;

static	int	ptr;		 	/*	RDATA[PTR]		*/
static	char	ack01_flag;		/*	ALTERNAITVE ACK FLAG	*/
static	char	request_flag;		/* NON SEND RECEIVE DISCONxxxT	*/
static	char	enq_flag;		/* ENQ HAD COME FLAG		*/
static	char	rvi_send_flag;

static	int	rc_toc;			/*	TIME	OVER	COUNT	*/
static	int	rc_ttd;			/*	TTD  	SEND	COUNT	*/
static	int	rc_ack;			/*	ACK	SEND	COUNT	*/
static	int	rc_nak;			/*	NAK	SEND	COUNT	*/
static	int	rc_wack;		/*	WACK	SEND	COUNT	*/

static	void	sndmdl(char *);		/*	SEND	CONTROL	CODE	*/
static	void	ack_reverse(void);	/*	ACK0	ACK1	REVERSE	*/
static	void	ack_send_reverse(void);	/*	ACK0/1	SEND	REVERSE	*/
static	void	get(void);		/*	GET	AND	SET	*/
static	void	sx_p11(void);
static	void	sx_p16(void);
static	void	sy_p16(void);
static	void	prgend(int);
