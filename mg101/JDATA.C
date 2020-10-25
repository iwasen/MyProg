/********************************************************
*							*
*	HAND HELD COMPUTER COMMUNICATION ADAPTER	*
*							*
*		COMMON DATA DEFINE	('87/04/25)	*
*							*
********************************************************/

#include	"adaputer.h"

char	wr_sa[8];		/* SIO A chanel port data	*/
char	wr_sb[8];		/* SIO B chanel port data	*/

char	rr_sa;			/* SIO A chanel status		*/
char	rr_sb;			/* SIO B chanel status		*/

int	st;			/* status			*/
int	timer0;			/* timer 0			*/
int	timer1;			/* timer 1			*/
int	timer2;			/* timer 2			*/

char	rxchr;			/* receive character		*/

/*
char	rxsts;			/* receive status		*/
*/

char	rbuff1[RBUFSIZ1];	/* receive buffer (DCE)		*/
char	rbuff2[RBUFSIZ2];	/* receive buffer (DTE)		*/

int	rrptr1;			/* receive buffer read pointer (DCE)	*/
int	rrptr2;			/* receive buffer read pointer (DTE)	*/
int	rwptr1;			/* receive buffer write pointer (DCE)	*/
int	rwptr2;			/* receive buffer write pointer (DTE)	*/

int	rdlen1;			/* receive data length (DCE)	*/
int	rdlen2;			/* receive data length (DTE)	*/

char	cmdbuf[260];		/* command buffer		*/
char	cmdflg;			/* command executing flag	*/

char	sdata[9][300];		/* send		data		*/
char	rdata[300];		/* receive	data		*/

char	*sdptr1;		/* send data pointer (DCE)	*/
char	*sdptr2;		/* send data pointer (DTE)	*/

char	*sdtp;			/* send data pointer save area	*/
char	crcf;			/* CRC send flag save area	*/

/*
char	sdflg1;			/* data send flag (DCE)		*/
char	sdflg2;			/* data send flag (DTE)		*/
*/

int	sdcount1;		/* data send counter (DCE)	*/
int	sdcount2;		/* data send counter (DTE)	*/

char	crcflg;			/* CRC send flag		*/

/*
char	etxcnt;			/* ETX,CRC receive counter	*/
*/

char	dipsw;			/* dip switch data		*/

int	blklng;			/* block length			*/
int	sdr_lng;		/* send	retry	length		*/

CRCDATA	crcsdt;			/* FOR	SEND	CRC VALUE FIELD	*/
CRCDATA	crcrdt;			/* FOR	RECEIVE	CRC VALUE FIELD	*/

char	sd_flag1;		/* SEND (DCE)	TIMING	FLAG	*/
char	sd_flag2;		/* SEND	(DTE)	TIMING	FLAG	*/

char	income_flag;		/* AUTO INCOMING CALL	FLAG	*/

char	blkcnt;			/* send block count to DCE	*/
char	blklen;			/* send 1-record length		*/
char	blkflg;			/* send block flag (ETB/ETX)	*/

int 	sd_blkcnt;		/* send block-no		*/
int 	rd_blkcnt;		/* receive block-no		*/

char	send_wait;		/* send wait for XON		*/

char	save_cmd;
char	eotflg;
char	r_blkcnt;

int	reqflg;

char	x_controll;		/* x_controll flag		*/
