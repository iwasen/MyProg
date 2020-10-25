/************************************************
*	JBSC	PROTOCOL			*
*		1987/11/04			*
*			FAST	VERSION		*
************************************************/

#include	"adaputer.h"
#include	"adcode.h"
#include	"jbsc.h"

	/*	MAIN	BSC	OF	JCA	*/

void	jbsc(int ev)
{
		(*label[st][ev-1])();
}

s0_p1() { }
s0_p2() { }
s0_p3() { }
s0_p4() { }
s0_p5() { }
s0_p6() { }
s0_p7() { }
s0_p8() { }
s0_p9() { }
s0_p10() { }
s0_p11() { }

s0_p12()
{
	timst1(-1);
}

s0_p13()
{
	sndrsp(F2*0x1000+ST0*0x100+R_NOTOPEN*0x10+0);
}

s0_p14()
{
	sndrsp(F2*0x1000+ST0*0x100+R_NOTOPEN*0x10+0);
}

s0_p15()
{
int	errcd;

	if ((errcd = md_opn()) == 0)
	{
		sndrsp(R_NORMAL);
		st = 1;
		request_flag = NO_REQUEST;
		enq_flag = rvi_send_flag = OFF;
		rc_toc = 0;
		timst1(TMWAIT3);
	}
	else			/*	modem open error	*/
	{
		sndrsp(errcd);
	}
}

s0_p16()
{
	sndrsp(R_NORMAL);
}

s1_p1()
{
	switch(request_flag)
	{
		case NO_REQUEST:
			enq_flag = ON;
			rc_toc = 0;
			timst1(TMWAIT2);
			return;

		case SEND_REQUEST:
			sndmdl(nak);
			break;

		case RECEIVE_REQUEST:
			sndmdl(ack0);
			ack01_flag = C_ACK1;
			st = 2;
			rc_ttd = 0;
	}
	enq_flag = OFF;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s1_p2() { }
s1_p3() { }
s1_p4() { }
s1_p5() { }
s1_p6() { }
s1_p7() { }
s1_p8() { }
s1_p9() { }
s1_p10() { }

s1_p11()
{
	sx_p11();
}

s1_p12()
{
	if (rc_toc < RNA_TOC)
	{
		if (enq_flag == ON)
		{
			enq_flag = OFF;
			sndmdl(wack);
			rc_toc = 0;
		}
		else
		{
			rc_toc++;
		}
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_TOCERR);
	}
}

s1_p13()
{
	sndmdl(enq);
	rc_toc = rc_ack = rc_nak = rc_wack = 0;
	ack01_flag = C_ACK0;
	st = 4;
	request_flag = SEND_REQUEST;
	timst1(TMWAIT3);
}

s1_p14()
{
	request_flag = RECEIVE_REQUEST;
	if (enq_flag == ON)
		s1_p1();
}

s1_p15()
{
	sndrsp(F2*0x1000+ST1*0x100+R_EXTOPEN*0x10+0);
}

s1_p16()
{
	sx_p16();
}

s2_p1()
{
	sndrty();		
	rc_toc = 0;
	timst1(TMWAIT3);
}

s2_p2()
{
	ptr = 0;		/* buffer pointer initialize	*/
	crcrdt.i = 0;		/* crc	  value	  initialize	*/
	st = 3;		
	rc_toc = 0;
	timst1(TMWAIT3);
}

s2_p3() { }
s2_p4() { }
s2_p5() { }
s2_p6() { }

s2_p7()
{
	if (rc_ttd < RNB_TTD)
	{
		sndmdl(nak);
		rc_ttd++;
		rc_toc = 0;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_RTYERR);
	}
}

s2_p8() { }
s2_p9() { }

s2_p10()
{
	enq_flag = rvi_send_flag = OFF;

	switch(request_flag)
	{
		case SEND_REQUEST:
			s1_p13();
			return;
			
		case RECEIVE_REQUEST:
/* (87.04.30) */
			request_flag = NO_REQUEST;
			sndrsp(R_NORMAL);
	}
	st = 1;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s2_p11()
{
	sx_p11();
}

s2_p12()
{
	if (rc_toc < RNA_TOC)
	{
		rc_toc++;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_TOCERR);		/*  'E' error	*/
	}
}

s2_p13()
{
	request_flag = SEND_REQUEST;
}

s2_p14()
{
	request_flag = RECEIVE_REQUEST;
}

s2_p15()
{
	sndrsp(F2*0x1000+ST2*0x100+R_EXTOPEN*0x10+0);
}

s2_p16()
{
	sx_p16();
}

s3_p1()
{
	sndmdl(nak);
	st = 2;
	rc_toc = rc_ttd = 0;
	timst1(TMWAIT3);
}

s3_p2()
{
	get();
}

s3_p3()
{
	get();
}

s3_p4()
{
	if ((crcrdt.i == 0) && (ptr == blklng)) {	/* CRC & LENGTH	*/
		switch(request_flag)
		{
		case NO_REQUEST:
			enq_flag = ON;
			st = 7;
			rc_toc = 0;
			timst1(TMWAIT2);
			return;

		case SEND_REQUEST:
			if (rvi_send_flag == OFF)
			{
				sndmdl(rvi);
				rvi_send_flag = ON;
				ack_reverse();
			}
			else
			{
				ack_send_reverse();
			}
			break;

		case RECEIVE_REQUEST:
			ack_send_reverse();
			sndrdt(rdata,blklng);
			if (rd_blkcnt < 15)
				rd_blkcnt++;
/* (87.04.30)	*********
			request_flag = NO_REQUEST;
************************/
		}
	}
	else
	{
		sndmdl(nak);
	}
	st = 2;
	enq_flag = OFF;
	rc_ttd = rc_toc = 0;
	timst1(TMWAIT3);
}

s3_p5()
{
	get();
}

s3_p6()
{
	get();
}

s3_p7()
{
	get();
}

s3_p8()
{
	get();
}

s3_p9()
{
	get();
}

s3_p10()
{
	get();
}

s3_p11()
{
	sx_p11();
}

s3_p12()
{
	if (rc_toc < RNA_TOC)
	{
		rc_toc++;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_TOCERR);		/*   'E' error	*/
	}
}

s3_p13()
{
	request_flag = SEND_REQUEST;
}

s3_p14()
{
	request_flag = RECEIVE_REQUEST;
}

s3_p15()
{
	sndrsp(F2*0x1000+ST3*0x100+R_EXTOPEN*0x10+0);
}

s3_p16()
{
	sx_p16();
}

s4_p1() { }
s4_p2() { }
s4_p3() { }
s4_p4() { }

s4_p5()
{
	switch(rxchr) {
	case C_ACK0:
		sndmdc(&(sdata[sd_blkcnt][0]),blklng);	/*  SEND TO MODEM */
		ack01_flag = C_ACK1;
		st = 5;
		rc_toc = rc_ack = rc_nak = rc_wack = 0;
		timst1(TMWAIT3);
		break;

	case C_ACK1:
		if (rc_ack < RNA_ACK)
		{
			sndmdl(enq);
			rc_ack++;
			rc_toc = 0;
			timst1(TMWAIT3);
		}
		else
		{
			sndmdl(deot);
			prgend(R_RTYERR);	/*   'E' error	*/
		}
	}
}

s4_p6()
{
	if (rc_nak < RNA_NAK)
	{
		sndmdl(enq);
		rc_nak++;
		rc_toc = 0;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_RTYERR);		/*   'E' error	*/
	}
}

s4_p7() { }

s4_p8()
{
	sndmdl(eot);
	sndrsp(F2*0x1000+ST4*0x100+R_RVIREC*0x10+(sd_blkcnt+1));
	request_flag = NO_REQUEST;
	enq_flag = OFF;
	st = 1;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s4_p9()
{
	if (rc_wack < RNA_WACK)
	{
		sndmdl(enq);
		rc_wack++;
		rc_toc = 0;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_RTYERR);		/*   'E' error	*/
	}
}

s4_p10() { }

s4_p11()
{
	sx_p11();
}

s4_p12()
{
	if (rc_toc < RNA_TOC)
	{
		sndmdl(enq);
		rc_toc++;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_TOCERR);		/*   'E' error	*/
	}
}

s4_p13()
{
	request_flag = SEND_REQUEST;
}

s4_p14()
{
	request_flag = RECEIVE_REQUEST;
}

s4_p15()
{
	sndrsp(F2*0x1000+ST4*0x100+R_EXTOPEN*0x10+0);
}

s4_p16()
{
	sx_p16();
}

s5_p1() { }
s5_p2() { }
s5_p3() { }
s5_p4() { }

s5_p5()
{
	if (rxchr == ack01_flag)
	{
		if (request_flag == SEND_REQUEST)
		{
			request_flag = NO_REQUEST;
			ack_reverse();
			sd_flag1 = OFF;

			if (++sd_blkcnt == blkcnt)
			{
				sndrsp(R_NORMAL);
/********************************
   				sndmdl(eot);
				st = 8;
				enq_flag = OFF;
				rc_toc = 0;
				timst1(TMWAIT3);
				return;
********************************/
			}
		}
		st = 8;
		rc_toc = 0;
		timst1(TMWAIT2);
		return;
	}
	else		/*	alternative ack error	*/
	{
		sndmdl(enq);
		st = 6;
	}
	rc_toc = 0;
	timst1(TMWAIT3);
}

s5_p6()
{
	if (rc_nak < RNA_NAK)
	{
		sndmdc(&(sdata[sd_blkcnt][0]),blklng); 	/* SDATA[] RETRY */
		rc_nak++;
		rc_toc = 0;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_RTYERR);		/*   'E' error	*/
	}
}

s5_p7() { }

s5_p8()
{
	sndmdl(eot);
	sndrsp(F2*0x1000+ST5*0x100+R_RVIREC*0x10+(sd_blkcnt+1));
	request_flag = NO_REQUEST;
	st = 1;
	enq_flag = OFF;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s5_p9()
{
	sndmdl(enq);
	st = 6;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s5_p10()
{
	if (request_flag == SEND_REQUEST)
	{
		sndrsp(F2*0x1000+ST5*0x100+R_EOTREC*0x10+(sd_blkcnt+1));
		request_flag = NO_REQUEST;
	}
	st = 1;
	enq_flag = OFF;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s5_p11()
{
	sx_p11();
}

s5_p12()
{
	sndmdl(enq);
	st = 6;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s5_p13()
{ 
	request_flag = SEND_REQUEST;
}

s5_p14()
{
	request_flag = RECEIVE_REQUEST;
}

s5_p15()
{
	sndrsp(F2*0x1000+ST5*0x100+R_EXTOPEN*0x10+0);
}

s5_p16()
{
	sy_p16();
}

s6_p1() { }
s6_p2() { }
s6_p3() { }
s6_p4() { }

s6_p5()
{
	if (rxchr == ack01_flag)
	{
		if (request_flag == SEND_REQUEST)
		{
			request_flag = NO_REQUEST;
			ack_reverse();
			sd_flag1 = OFF;

			if (++sd_blkcnt == blkcnt)
			{
				sndrsp(R_NORMAL);
/********************************
				st = 8;
				enq_flag = OFF;
				rc_toc = 0;
				timst1(TMWAIT3);
				return;
********************************/
			}
		}
		rc_toc = 0;
		timst1(TMWAIT2);
		st = 8;
		return;
	}
	else		/*	alternative ack error	*/
	{
		if (rc_ack < RNA_ACK)
		{
			sndmdc(&(sdata[sd_blkcnt][0]),blklng);	/* RETRY */
			st = 5;
			rc_ack++;
		}
		else
		{
		       sndmdl(eot);
		       sndrsp(F2*0x1000+ST6*0x100+R_ACKERR*0x10+(sd_blkcnt+1));
		       st = 1;
		       enq_flag = OFF;
		}	
	}
	rc_toc = 0;
	timst1(TMWAIT3);
}

s6_p6()
{
	if (rc_nak < RNA_NAK)
	{
		sndmdc(&(sdata[sd_blkcnt][0]),blklng); /*  SEND RETRY	*/
		rc_nak++;
		rc_toc = 0;
		st = 5;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_RTYERR);		/*   'E' error	*/
	}
}

s6_p7() { }

s6_p8()
{
	sndmdl(eot);
	sndrsp(F2*0x1000+ST6*0x100+R_RVIREC*0x10+(sd_blkcnt+1));
	request_flag = NO_REQUEST;
	st = 1;
	enq_flag = OFF;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s6_p9()
{
	if (rc_wack < RNB_WACK)
	{
		sndmdl(enq);
		rc_wack++;
		rc_toc = 0;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_RTYERR);		/*  'E' error	*/
	}
}

s6_p10()
{
	if (request_flag == SEND_REQUEST)
	{
		sndrsp(F2*0x1000+ST6*0x100+R_EOTREC*0x10+(sd_blkcnt+1));
		request_flag = NO_REQUEST;
	}
	st = 1;
	enq_flag = OFF;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s6_p11()
{
	sx_p11();
}

s6_p12()
{
	if (rc_toc < RNA_TOC)
	{
		sndmdl(enq);
		rc_toc++;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_TOCERR);		/*   'E' error	*/
	}
}

s6_p13()
{
	request_flag = SEND_REQUEST;
}

s6_p14()
{
	request_flag = RECEIVE_REQUEST;
}

s6_p15()
{
	sndrsp(F2*0x1000+ST6*0x100+R_EXTOPEN*0x10+0);
}

s6_p16()
{
	sy_p16();
}

s7_p1()
{
	switch(request_flag) {
	case NO_REQUEST:
		enq_flag = ON;
		timst1(TMWAIT2);
		return;

	case SEND_REQUEST:
		if (rvi_send_flag == OFF)
		{
			sndmdl(rvi);
			rvi_send_flag = ON;
			ack_reverse();
		}
		else
		{
			ack_send_reverse();
		}
		break;

	case RECEIVE_REQUEST:
		ack_send_reverse();
		sndrdt(rdata,blklng);
		if (rd_blkcnt < 15)
			rd_blkcnt++;
/* (87.04.30) ***
		request_flag = NO_REQUEST;
****************/
	}
	st = 2;
	enq_flag = OFF;
	rc_ttd = rc_toc = 0;
	timst1(TMWAIT3);
}

s7_p2() { }
s7_p3() { }
s7_p4() { }
s7_p5() { } 
s7_p6() { }
s7_p7() { }
s7_p8() { }
s7_p9() { }

s7_p10()
{
int	y;

	if (request_flag == RECEIVE_REQUEST)
	{
		y = rd_blkcnt + 1;
		if (y > 15)
			y = 15;
		sndrsp(F2*0x1000+ST7*0x100+R_EOTREC*0x10+y);
		request_flag = NO_REQUEST;
	}
	st = 1;
	enq_flag = OFF;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s7_p11()
{
	sx_p11();
}

s7_p12()
{
	if (rc_toc < RNA_TOC)
	{
		if (enq_flag == ON)
		{
			enq_flag = OFF;
			sndmdl(wack);
			rc_toc = 0;
		}
		else
		{
			rc_toc++;
		}
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_TOCERR);		/*   'E' error	*/
	}
}

s7_p13()
{
	request_flag = SEND_REQUEST;
	if (enq_flag == ON)
		s7_p1();
}	

s7_p14()
{
	request_flag = RECEIVE_REQUEST;
	if (enq_flag == ON)
		s7_p1();
}

s7_p15()
{
	sndrsp(F2*0x1000+ST7*0x100+R_EXTOPEN*0x10+0);
}

s7_p16()
{
	sx_p16();
}

s8_p1() { }
s8_p2() { }
s8_p3() { }
s8_p4() { }
s8_p5() { }

s8_p6()
{
	switch(request_flag)
	{
		case NO_REQUEST:
/************************
			rc_toc = 0;
************************/
			timst1(TMWAIT2);
			return;

		case SEND_REQUEST:
			sndmdc(&(sdata[sd_blkcnt][0]),blklng);
			st = 5;
			rc_ack = rc_nak = rc_wack = 0;
			break;

		case RECEIVE_REQUEST:
		case DISCONxxxT_REQUEST:
			sndmdl(eot);
			st = 1;
			enq_flag = OFF;
	}
	rc_toc = 0;
	timst1(TMWAIT3);
}

s8_p7() { }
s8_p8() { }
s8_p9() { }

s8_p10()
{
	if (request_flag == SEND_REQUEST)
	{
		sndrsp(F2*0x1000+ST8*0x100+R_EOTREC*0x10+(sd_blkcnt+1));
		request_flag = NO_REQUEST;
	}
	st = 1;
	enq_flag = OFF;
	rc_toc = 0;
	timst1(TMWAIT3);
}

s8_p11()
{
	sx_p11();
}

s8_p12()
{
	if (rc_toc < RNA_TOC)
	{
		sndmdl(ttd);
		rc_toc++;
		timst1(TMWAIT3);
	}
	else
	{
		sndmdl(deot);
		prgend(R_TOCERR);		/*   'E' error	*/
	}
}

s8_p13()
{
	request_flag = SEND_REQUEST;
	if (rc_toc == 0)
		s8_p6();
}

s8_p14()
{
	request_flag = RECEIVE_REQUEST;
	if (rc_toc == 0)
		s8_p6();
}

s8_p15()
{
	sndrsp(F2*0x1000+ST8*0x100+R_EXTOPEN*0x10+0);
}

s8_p16()
{
	sy_p16();
}

/****************  SUB ROUTINE  *****************/

void	sndmdl(char *data)
{
int	length;

	length = *data++;
	sndmdm(data,length);
}

void	ack_send_reverse()
{
	if (ack01_flag == C_ACK0)
	{
		sndmdl(ack0);
		ack01_flag = C_ACK1;
	}	
	else
	{
		sndmdl(ack1);
		ack01_flag = C_ACK0;
	}
}

void	ack_reverse()
{
	if (ack01_flag == C_ACK0)
	{
		ack01_flag = C_ACK1;
	}	
	else
	{
		ack01_flag = C_ACK0;
	}
}

void	get()		/* set rdata[ptr++]  and  crc16	*/
{
	rdata[ptr++] = rxchr;
	crcrdt.i = crc16(rxchr , crcrdt.i);
	rc_toc = 0;
	timst1(TMWAIT3);
}

void	sx_p11()
{
	switch(request_flag)
	{
		case NO_REQUEST:		/* NO		REQUEST	*/
			md_cls();
			st = 0;
			break;

		case SEND_REQUEST:		/* SEND		REQUEST	*/
			prgend(R_SEND);
			break;

		case RECEIVE_REQUEST:		/* RECEIVE 	REQUEST	*/
			prgend(R_REC);
			break;

		case DISCONxxxT_REQUEST:	/* DISCONxxxT	REQUEST	*/
			prgend(R_NORMAL);
	}
}

void	sx_p16()
{
	sndmdl(deot);
	prgend(R_NORMAL);
}

void	sy_p16()
{
	request_flag = DISCONxxxT_REQUEST;
}

void	prgend(int x)
{
int	y;

	if ((x == 0) || (request_flag == DISCONxxxT_REQUEST))
	{
		sndrsp(R_NORMAL);
	}
	else
	{
		switch(request_flag) {
		case SEND_REQUEST:
			y = sd_blkcnt + 1;
			break;

		case RECEIVE_REQUEST:
			y = rd_blkcnt + 1;
			break;

		default:
			y = 0;
		}

		if (y > 15)
			y = 15;

		sndrsp(F2*0x1000+st*0x100+x*0x10+y);
	}
	request_flag = NO_REQUEST;
	md_cls();
	st = 0;
}
