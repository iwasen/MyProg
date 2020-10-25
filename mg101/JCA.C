/********************************************************
*							*
*	HAND HELD COMPUTER COMMUNICATION ADAPTER	*
*							*
*		JCA PROTOCOL CONTROL	('87/07/22)	*
*							*
********************************************************/

#include	"adaputer.h"
#include	"adcode.h"
#include	"adresp.h"
#include	"adioport.h"

#define	CR		(char)0x0d
#define	LF		(char)0x0a

extern	void	init(), topen(), sndrsp(), jbsc(), tsend();
extern	void	trace(int),trept();
extern	int	crc16(char, int);
extern	CRCDATA	crcsdt, crcrdt;
extern	char	jtoe();
extern	char	rbuff1[], rbuff2[], rxchr, cmdbuf[], sdata[9][300], cmdflg;
extern	char	income_flag; 
extern	int	rrptr1, rrptr2, rdlen1, rdlen2, timer1, blklng, st;
extern	char	wr_sa[], wr_sb[];
extern	char	blkcnt, blklen, blkflg;
extern	int	sd_blkcnt, rd_blkcnt;
extern	char	sd_flag1, sd_flag2, send_wait;
extern	char	save_cmd, eotflg;
extern	int	reqflg;
extern	char	r_blkcnt;

extern	char	x_controll;

/*	main program	*/

jmain()
{
	int	event();
	void	jca();
	int	ev;

	init();				/* initialize		*/

	topen();			/* DTE open		*/

	ei();				/* interrupt enable	*/

	while (1) {
		ev = event();
		if (ev == 0)
		{
			jca();
		}
		else
		{
			trace(ev);
			jbsc(ev);
		}
	}
}

event()
{
static	char	xoff_code[1] = { C_XOFF };
static	char	dleflg, stxflg, etxflg, backup_rxchr;
int	i;
void	rsp_r();

	if (reqflg != 0) {
		i = reqflg;
		reqflg = 0;
		return(i);
	}

	while (1) {
		if ((send_wait != 0) && (sd_flag2 == 0)) {
			send_wait = 0;
			return(4);
		}

		if (stxflg == 2) {
			stxflg = 0;
			rxchr = backup_rxchr;
			goto event10;
		}

		if (timer1 == 0) {
			dleflg = etxflg = 0;
			return(12);
		}

		if (rdlen1 != 0) {
			di();
			rxchr = rbuff1[rrptr1++];
			--rdlen1;
			if (rrptr1 == RBUFSIZ1)
				rrptr1 = 0;
			ei();

			if (stxflg == 1) {
				if (rxchr == C_ENQ)
				{
					stxflg = 0;
					return(7);
				}
				else
				{
					backup_rxchr = rxchr;
					rxchr = C_STX;
					stxflg = 2;
					return(2);
				}
			}
event10:
			switch(etxflg) {
			case 1:
				crcrdt.i = crc16(rxchr , crcrdt.i);
				etxflg++;
				continue;
			case 2:
				crcrdt.i = crc16(rxchr , crcrdt.i);
				etxflg = 0;
				if (sd_flag2 != 0) {
					send_wait = 1;
					continue;
				}
				return(4);
			}

			if (dleflg != 0) {
				dleflg = 0;

				switch (rxchr) {
				case C_ACK0:
				case C_ACK1:
					return(5);
				case C_RVI:
					return(8);
				case C_WACK:
					return(9);
				case C_EOT:
					return(11);
				}
			}

			switch (rxchr) {
			case C_ENQ:
				return(1);
			case C_STX:
				stxflg = 1;
				break;
			case C_ETX:
				etxflg = 1;
				crcrdt.i = crc16(rxchr , crcrdt.i);
				break;
			case C_NAK:
				return(6);
			case C_EOT:
/********************************
				if ((sd_flag2 != 0) || (send_wait != 0)) {
					di();
					rdlen1++;
					if (rrptr1 == 0)
						rrptr1 = RBUFSIZ1;
					else 
						rrptr1--;
					ei();
					continue;
				}
********************************/
				eotflg = 1;
				return(10);
			case C_DLE:
				dleflg = 1;
				break;
			case C_SYN:
				break;
			case C_TPAD:
				di();
				outp(P_SAC,3);
				wr_sa[3] |= 0x10;
				outp(P_SAC,wr_sa[3]);
				ei();
				break;
			default:
				return(3);
			}
		}

		if (rdlen2 != 0) {
			di();
			rxchr = rbuff2[rrptr2++];
			--rdlen2;
			if (rrptr2 == RBUFSIZ2)
				rrptr2 = 0;
			ei();

			if (x_controll == ON)
			{
				if (rdlen2 < (RBUFSIZ2*1/4))
				{
					x_controll = OFF;
					tsend(xoff_code,1);
				}
			}
			return(0);
		}

/****************
		if ((save_cmd == 'R') && (eotflg == 0)) {
			cmdflg = 1;
			return(14);		/* receive cmmand	*/
		}
****************/

		if ((sd_flag1 == 0) && (r_blkcnt > sd_blkcnt)) {
			sd_flag1 = 1;		/* data sending		*/
			cmdflg = 1;
			return(13);		/* send command		*/
		}
		if ((sd_flag1 == 0) && (blkcnt == sd_blkcnt))
			blkcnt = r_blkcnt = sd_blkcnt = 0;

		if ((st == 0) && (income_flag == 1))
		{
			if (((inp(P_PBD)) & 0x01) == 0)
			{
				do {
					outp(P_PCD,0x00);	/* CIR RESET */
					outp(P_PCD,0x01);	/* CIR   SET */
				} while (((inp(P_PBD)) & 0x01) == 0);
				rsp_r();
			}
		}
	} /* WHILE(1) END */
}

rsp_r()
{
static	char	rp[] = "R\x0d";

	tsend(rp , 2);
}

jca()
{
static	int	cmdlen, sdlen, sdcnt;
static	char	*sdp, crflg, sdrflg;

	int	i, cl;
	char	*cmp;
	void	mem_rw();

	switch (sdrflg) {		/* 'W' command flag check	*/
	case 1:				/* wait block-count		*/
		blkcnt = rxchr;		/* block count save		*/
		sdrflg++;
		sd_blkcnt = r_blkcnt = 0;
		return;
	case 2:				/* wait block flag (ETX)	*/
		blkflg = rxchr;		/* block flag save		*/
		sdrflg++;
		return;
	case 3:				/* wait data-len		*/
		if (rxchr == 0)
			sdlen = sdcnt = 256;
		else
			sdlen = sdcnt = rxchr;
		sdp = &(sdata[r_blkcnt][0]);
		*sdp++ = C_STX;
		crcsdt.i = 0;
		sdrflg++;
		return;
	case 4:				/* wait data			*/
		crcsdt.i = crc16((*sdp++ = jtoe(rxchr)) , crcsdt.i);
		if (--sdcnt == 0) {
			for (i = sdlen; i < blklng; i++)
				crcsdt.i = crc16((*sdp++ = 0x40) , crcsdt.i);
			if (blkflg == ETB_BLK)
				crcsdt.i = crc16((*sdp++ = C_ETB), crcsdt.i);
			else
				crcsdt.i = crc16((*sdp++ = C_ETX), crcsdt.i);
			*sdp++ = crcsdt.c[1];
			*sdp   = crcsdt.c[0];

			if (++r_blkcnt == blkcnt)	/* recv end ?	*/
				sdrflg = 0;
			else
				sdrflg = 2;		/* next block	*/
			if (sd_flag1 == 0) {
				cmdflg = 1;
				sd_flag1 = 1;		/* data sending	*/
				reqflg = 13;		/* send command	*/
			}
		}
		return;
	}

	if (rxchr == 'W' && cmdlen == 0) {
		sdrflg = 1;
		return;
	}

	if (rxchr == LF && crflg != 0) {
		cmdbuf[--cmdlen] = 0;
		cl = cmdlen;
		cmdlen = 0;
		crflg = 0;
		cmdflg = 1;
		save_cmd = cmdbuf[0];			/* command save	*/

		switch (cmdbuf[0]) {
		case 'O':
			if (cl != 1) {
				sndrsp(R_PRMERR);
				return;
			}
			income_flag = 1;
			sndrsp(R_NORMAL);
			return;
		case 'Q':
			if (cl != 1) {
				sndrsp(R_PRMERR);
				return;
			}
			income_flag = 0;
			sndrsp(R_NORMAL);
			return;
		case 'B':
			if (cl != 2) {
				sndrsp(R_PRMERR);
				return;
			}
			switch (cmdbuf[1]) {
			case '1':
				blklng = 128;
				sndrsp(0);
				return;
			case '2':
				blklng = 256;
				sndrsp(0);
				return;
			}
			sndrsp(R_PRMERR);
			return;
		case 'C':
			reqflg = 15;
			return;
		case 'D':
			if (cl != 1) {
				sndrsp(R_PRMERR);
				return;
			}
			reqflg = 16;
			return;
		case 'M':
			mem_rw();
			return;
		case 'R':
			if (cl != 1) {
				sndrsp(R_PRMERR);
				return;
			}
			eotflg = 0;
			rd_blkcnt = 0;
			reqflg = 14;
			return;
/**	('87/04/06)
		case 'S':
			sdp = sdata;
			cmp = &cmdbuf[1];
			*sdp++ = C_STX;
			crcsdt.i = 0;
			for (i = 0; --cl != 0; i++)
				crcsdt.i=crc16((*sdp++=jtoe(*cmp++)),crcsdt.i);
			for ( ; i < blklng; i++)
				crcsdt.i=crc16((*sdp++ = 0x40),crcsdt.i);
			crcsdt.i=crc16((*sdp++ = C_ETX),crcsdt.i);
			*sdp++ = crcsdt.c[1];
			*sdp   = crcsdt.c[0];
			reqflg = 13;
			return;
**/
		case 'T':
			if (cl != 1) {
				sndrsp(R_PRMERR);
				return;
			}
			sndrsp(R_NORMAL);
			trept();
			return;
		}
		sndrsp(R_CMDERR);
		return;
	}

	cmdbuf[cmdlen++] = rxchr;

	if (rxchr == CR)
		crflg = 1;
	else
		crflg = 0;
}
