/********************************************************
*							*
*	hand held computer communication adapter	*
*							*
*		interrupt routine	('87/04/25)	*
*							*
********************************************************/

#include	"adaputer.h"
#include	"adioport.h"
#include	"adcode.h"

extern	void	tsend(char *, int);

extern	char	*sdptr1, *sdptr2, wr_sa[], wr_sb[], rr_sa, rr_sb;
extern	char	rbuff1[], rbuff2[];
extern	int	rwptr1, rwptr2;
extern	int	rdlen1, rdlen2;
extern	int	timer0, timer1, timer2;
extern	int	sdcount1, sdcount2;
extern	char	sd_flag1, sd_flag2;

extern	char	x_controll;

/*
extern	int	st;
extern	char	etxcnt, rxsts, crcflg;
*/

/*	SIO B chanel transmitter buffer empty interrupt	*/

i_sib0()
{
	if (sdcount2 != 0) {
		outp(P_SBD,*sdptr2++);	/* data send	*/
		sdcount2--;
		return;
	}
	outp(P_SBC,5);
	wr_sb[5] &= (char)~0x08;
	outp(P_SBC,wr_sb[5]);		/* transmitter interrupt disable */

	outp(P_SBC,0x28);		/* cancel transmitter interrupt	*/

	sd_flag2 = 0;			/* data sending flag reset	*/
}

/*	SIO B chanel external status interrupt	*/

i_sib1()
{
	outp(P_SBC,0x10);		/* reset external status interrupt */

	rr_sb = inp(P_SBC);			/* read RR0		*/
	if (rr_sb & 0x80)			/* detect break ?	*/
		(*(void(*)())(0))();
}

/*	SIO B chanel receive character available interrupt	*/

i_sib2()
{
static	char	xon_code[1] = { C_XON };
	char	c;

	c = inp(P_SBD);
	if (c == C_XON) {
		outp(P_SBC,5);
		wr_sb[5] &= (char)~0x08;
		outp(P_SBC,wr_sb[5]);	/* transmitter interrupt disable */
		return;
	}
	else if (c == C_XOFF) {
		outp(P_SBC,5);
		wr_sb[5] |= (char)0x08;
		outp(P_SBC,wr_sb[5]);	/* transmitter interrupt enable */
		return;
	}
	rbuff2[rwptr2++] = c;
	rdlen2++;

	if (rwptr2 == RBUFSIZ2)
		rwptr2 = 0;

	if (x_controll == OFF)
	{
		if (rdlen2 > (RBUFSIZ2*3/4))
		{
			x_controll = ON;
			tsend(xon_code,1);
		}
	}
}

/*	SIO B chanel special receive condition interrupt */

i_sib3()
{
	outp(P_SBC,0x30);		/* error reset		*/
}

/*	SIO A chanel transmitter buffer empty interrupt	*/

i_sia0()
{
static	char	trp_cnt;
/********
int	i;
********/

	if (sdcount1 != 0) {
/****************
		switch (crcflg) {
		case 1:
			crcflg = 2;
			break;
		case 2:
			crcflg = 3;
			break;
		case 3:
			crcflg = 4;
			outp(P_SAC,0x80); /* reset CRC generater */
			break;
		}
****************/
		outp(P_SAD,*sdptr1++);	/* send data		*/
		sdcount1--;
		return;
/****************
	} else {
		if (crcflg != 0) {
			outp(P_SAC,0xc0); /* reset Tx under run */
			outp(P_SAC,0x28);
			crcflg = 0;
			return;
		}
****************/
	}
	else
	{
		if (trp_cnt == 0)
			trp_cnt = 5;	/* traning pad set	*/
	}

	if (--trp_cnt != 0)
	{
		outp(P_SAD, (char)C_TPAD);
		return;
	}

	outp(P_SAC,5);
	wr_sa[5] &= (char)~0x08;
	outp(P_SAC,wr_sa[5]);		/* disable transmitter		*/

/******** ('87/11/16)
	ei();
	for (i = 0; i != 0x400; i++) ;
	di();
********/

	outp(P_SAC,0x28);		/* cancel transmitter interrupt	*/

	outp(P_SAC,5);
	wr_sa[5] &= ~0x02;		/* RTS off			*/
	outp(P_SAC,wr_sa[5]);

/******** ('87/04/06)
	sd_flag1 = 0;			/* data sending flag reset	*/
********/
}

/*	SIO A chanel external status interrupt	*/

i_sia1()
{
/********
	static	char	sts;
********/
	outp(P_SAC,0x10);		/* reset external status interrupt */

	rr_sa = inp(P_SAC);		/* read RR0		*/

/********
	if ((rr_sa & 0x40) != 0 && (sts & 0x40) == 0) {
		if (crcflg != 0) {
			outp(P_SAD,0xff); /* send traling pad	*/
			crcflg = 0;	/* reset CRC send flag	*/
		}
	}
	sts = rr_sa;
********/
}

/*	SIO A chanel receive character available interrupt	*/

i_sia2()
{
/********
	static char	stxcnt;
********/
	char	c;

	c = rbuff1[rwptr1++] = inp(P_SAD);
/********
	if (etxcnt == 0) {
		switch (c) {
		case C_STX:			/* read receive data */
			if (st == 2)
				stxcnt = 1;
			break;
		case C_ETX:
			outp(P_SAC,3);
			wr_sa[3] &= ~0x12;
			outp(P_SAC,wr_sa[3]);	

			etxcnt = 1;
			break;
		case C_TPAD:
			if (etxcnt != 0)
				break;

			outp(P_SAC,3);
			wr_sa[3] |= 0x12;
			outp(P_SAC,wr_sa[3]);
			break;
		}
	}
	if (stxcnt != 0) {
		if (stxcnt++ == 2) {
			outp(P_SAC,0x40);
			stxcnt = 0;
		}
	}

	if (etxcnt != 0) {
		if (etxcnt++ == 5) {
			outp(P_SAC,1);
			rxsts = inp(P_SAC);
			etxcnt = 0;
		}
	}
********/
	if (rwptr1 == RBUFSIZ1)
		rwptr1 = 0;

	rdlen1++;
}

/*	SIO A chanel special receive condition interrupt */

i_sia3()
{
	outp(P_SAC,0x30);		/* error reset		*/
}

/*	timer interrupt		*/

i_ctc3()
{
	if (timer0 != 0)
		timer0--;

	if ((timer1 != 0) && (timer1 != -1))
		timer1--;

	if ((timer2 != 0) && (timer2 != -1))
		timer2--;
}
