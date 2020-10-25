#include <stdio.h>
#include <conio.h>
#include "sg_cpu.h"

byte	REG_VIC_VICR3[1];
byte	REG_VIC_VICR7[1];
byte	REG_VIC_LICR1[1];
byte	REG_VIC_LICR2[1];
byte	REG_VIC_LIVBR[1];
byte	REG_VIC_VIVR1[1];
byte	REG_VIC_VIVR2[1];
byte	REG_VIC_VIVR3[1];
byte	REG_VIC_VIVR4[1];
byte	REG_VIC_VIVR5[1];
byte	REG_VIC_VIVR6[1];
byte	REG_VIC_VIVR7[1];
byte	REG_VIC_TTR[1];
byte	REG_VIC_LBTR[1];
byte	REG_VIC_BESR[1];
byte	REG_VIC_ARCR[1];
byte	REG_VIC_SS0CR0[1];
byte	REG_VIC_SS1CR0[1];
byte	REG_VIC_RCR[1];

/*	ＤＵＡＲＴコントロールレジスタアドレス	*/
byte	REG_DUART_MR1A[1];
byte	REG_DUART_MR2A[1];
byte	REG_DUART_SRA[1];
byte	REG_DUART_CSRA[1];
byte	REG_DUART_CRA[1];
byte	REG_DUART_THRA[1];
byte	REG_DUART_ISR[1];
byte	REG_DUART_IMR[1];
byte	REG_DUART_MR1B[1];
byte	REG_DUART_MR2B[1];
byte	REG_DUART_SRB[1];
byte	REG_DUART_CSRB[1];
byte	REG_DUART_CRB[1];
byte	REG_DUART_RHRB[1];

/*	ＭＢＩボードコントロールレジスタアドレス	*/
word	REG_MBI_ISW0[1];
word	REG_MBI_IMSK[1];
word	REG_MBI_CFCW[1];
word	REG_MBI_OPCW[1];
word	REG_MBI_RQTP[1];
word	REG_MBI_RQSP[1];
word	REG_MBI_RTID[1];
word	REG_MBI_RQMT[16];
word	REG_MBI_BMWB[3584];
word	REG_MBI_BRCS[1];

/*	ＷＤＴ制御レジスタアドレス	*/
byte	REG_WDT	[1];

/*	ＧＳＣレジスタアドレス	*/
word	REG_GSC_OUTPUT[1];

static	void	intr(void);
static	void	set_intrs2(void);
static	void	set_intmbi_rx(void);
static	void	set_intmbi_bit(void);
static	void	dump(void);

main()
{
	int	ch;

	power_on();
	for (;;) {
		printf("1.interrupt  2.dump  9.exit ==>");
		switch (ch = getch()) {
		case '1':
			putch(ch);
			intr();
			break;
		case '2':
			putch(ch);
			dump();
			break;
		case '9':
			exit(0);
		}
	}
}

static	void	intr()
{
	int	ch;

	for (;;) {
		printf("\n\n1.intrs2  2.int10ms  3.intgsc  4.intmbi_rx  5.intmbi_bit ==>");
		switch (ch = getch()) {
		case '1':
			putch(ch);
			set_intrs2();
			intrs2();
			break;
		case '2':
			putch(ch);
			int10ms();
			break;
		case '3':
			putch(ch);
			intgsc();
			break;
		case '4':
			putch(ch);
			set_intmbi_rx();
			intmbi_rx();
			break;
		case '5':
			putch(ch);
			set_intmbi_bit();
			intmbi_bit();
			break;
		case 0x1b:
			return;
		}
	}
}

static	void	set_intrs2()
{
}

static	void	set_intmbi_rx()
{
}

static	void	set_intmbi_bit()
{
}

static	void	dump()
{
}

void	mbibit()
{
}

void	ORKID()
{
}
