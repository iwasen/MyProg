#include <stdio.h>
#include <dos.h>
#include <conio.h>
#include <setjmp.h>
#include "\rs232c\rs232c.h"
#include "sg_cpu.h"

static	void	interrupt far	tm_intr(void);
static	void	(interrupt far *tm_vect)();
short	test_gsc[0x4000];
extern	int	receive_CHA(int);
jmp_buf	jmpbuf;
short	test_mbi_error;
short	test_gsc_input;
short	*GSC_INPUT = &test_gsc_input;
ushort	sbuf[32];
int	time_count;
int	calc_count;
int	time_test;

main()
{
	int	err, channel;
	RSPRM	rsprm;

	rsprm.sync_mode = ASYNC;
	rsprm.duplex = DUPLEX_FULL;
	rsprm.blt = BLT_9600;
	rsprm.parity = PARITY_ODD;
	rsprm.chr_len = CHR_8;
	rsprm.stp_len = STP_1;
	rsprm.flow_ctrl = FLOW_NONE;
	rsprm.dtr = DTR_ON;
	rsprm.buf_size = 1024;
	rsprm.bufp = NULL;

	if ((err = rs1_open(&rsprm)) != 0) {
		printf("err %d\n", err);
		exit(1);
	}
	rs1_rcvint(receive_CHA);

	initscr();

	_disable();
	outp(IOP_TMC_CWW, 0x36);
	outp(IOP_TMC_C0L, 0x00);
	outp(IOP_TMC_C0L, 0x4e);
	outp(IOP_ICM_IMR, inp(IOP_ICM_IMR) & 0xfe);

	tm_vect = _dos_getvect(0x08);
	_dos_setvect(0x08, tm_intr);
	_enable();

	printf(">");
	if (setjmp(jmpbuf) == 0)
		power_on();

	_disable();
	outp(IOP_ICM_IMR, inp(IOP_ICM_IMR) | 0x01);
	_dos_setvect(0x08, tm_vect);
	_enable();
	rs1_close();

	endscr();
}

static	void	interrupt far	tm_intr(void)
{
	static	int	counter1;
	static	int	counter2;

	int10ms();
	if (++counter1 == 3) {
		intgsc();
		counter1 = 0;
	}

	if (++counter2 == 5) {
		intmbi_tx();
		counter2 = 0;
	}

	if (time_count != 0)
		time_count--;

	outp(IOP_ICM_EOI, 0x20);
}

int	kbcheck(void)
{
	static	int	mode;
	int	c;

	c = toupper(getch());
	if (c == 0x1b)
		return(1);

	if (mode == 0) {
		if (c == 0x09) {
			mode = 1;
			printf("\nSPECIAL COMMAND MODE\n#");
		} else
			receive_CHB(c);
	} else {
		putch(c);
		switch (c) {
		case 0x09:
			mode = 0;
			printf("\nDEBUG COMMAND MODE\n>");
			return(0);
		case 'E':
			printf("\xxxHO START\n");
			echo_mode = ECHO_START;
			break;
		case 'H':
			printf("\nƒRƒ}ƒ“ƒhˆê——\n\n");
			printf("E : ‚d‚b‚g‚nƒ‚[ƒh\n");
			printf("H : ƒwƒ‹ƒv•\¦\n");
			printf("I : ‚h|‚a‚h‚sŠJn^I—¹\n");
			printf("R : ‚q|‚a‚h‚sŠJn\n");
			printf("T : ÀsŠÔ‘ª’è\n");
			break;
		case 'T':
			calc_count = 0;
			time_count = 100;
			time_test = 1;
			return(0);
		default:
			test(c);
			break;
		}
		printf("\n#");
	}
	return(0);
}
