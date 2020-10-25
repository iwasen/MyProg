/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: ＣＣＭ手順通信処理			*
 *		ファイル名	: ccm.c					*
 *									*
 ************************************************************************/

#include	<stdio.h>
#include	<stdlib.h>
#include	<setjmp.h>
#include	"bumper.h"

/*	コード		*/
#define	SOH	0x01	
#define	STX	0x02	
#define	ETX	0x03	
#define	EOT	0x04	
#define	ENQ	0x05	
#define	ACK	0x06	
#define	NAK	0x15	
#define	ETB	0x17	

static	void	chag(int, char *);
static	void	s_tid(int);
static	void	r_tid(int);
static	void	s_header(int, int, int, int, int);
static	void	r_ack(void);
static	void	s_data(char *, int);
static	void	r_data(char *, int);
static	void	r_eot(void);
static	void	send(char *, int);
static	void	receive(char *, int);
static	void	err(int);

static	jmp_buf	jmpbuf;			/* ロングジャンプバッファ */

/*=======================================================================
 |
 |		ＣＣＭ手順処理	書き込み処理
 |
 |	int	wccm(t_id, s_id, data, adr, byte)
 |
 |		int	t_id;		ターゲットＩＤ
 |		int	s_id;		ソースＩＤ
 |		void	*data;		データ
 |		int	adr;		ターゲットメモリアドレス
 |		int	byte;		バイト数
 |
 =======================================================================*/
int	wccm(int t_id, int s_id, void *data, int adr, int byte)
{
	int	flag;
return(0);
	rs1_open(7, 0x4e, 0x27);	/* ＲＳ－２３２Ｃオープン(9600bps) */

	if ((flag = setjmp(jmpbuf)) == 0) {
		s_tid(t_id);		/* ターゲットＩＤ送信 */
		r_tid(t_id);		/* ターゲットＩＤ受信 */

		s_header(t_id, s_id, adr, byte, '8');	/* ヘッダ送信 */
		r_ack();		/* ＡＣＫ受信 */

		s_data(data, byte);	/* データ送信 */

		rs1_put(EOT);		/* ＥＯＴ送信 */
	}

	timset(10);
	while (tm_ovf == 0)
		;

	rs1_close();				/* ＲＳ－２３２Ｃクローズ */

	return(flag);
}

/*=======================================================================
 |
 |		ＣＣＭ手順処理	読み込み処理
 |
 |	int	rccm(t_id, s_id, data, adr, byte)
 |
 |		int	t_id;		ターゲットＩＤ
 |		int	s_id;		ソースＩＤ
 |		void	*data;		データバッファ
 |		int	adr;		ターゲットメモリアドレス
 |		int	byte;		バイト数
 |
 =======================================================================*/
int	rccm(int t_id, int s_id, void *data, int adr, int byte)
{
	int	flag;
return(0);
	rs1_open(7, 0x4e, 0x27);	/* ＲＳ－２３２Ｃオープン(9600bps) */

	if ((flag = setjmp(jmpbuf)) == 0) {
		s_tid(t_id);		/* ターゲットＩＤ送信 */
		r_tid(t_id);		/* ターゲットＩＤ受信 */

		s_header(t_id, s_id, adr, byte, '0');	/* ヘッダ送信 */
		r_ack();		/* ＡＣＫ受信 */

		r_data(data, byte);	/* データ送信 */

		r_eot();		/* ＥＯＴ受信 */

		rs1_put(EOT);		/* ＥＯＴ送信 */
	}

	timset(10);
	while (tm_ovf == 0)
		;

	rs1_close();				/* ＲＳ－２３２Ｃクローズ */

	return(flag);
}

static	void	s_tid(int t_id)
{
	char	buf[3];

	buf[0] = 'N';
	buf[1] = t_id+0x20;
	buf[2] = ENQ;

	send(buf, 3);
}

static	void	r_tid(int t_id)
{
	char	buf[3];

	receive(buf, 3);
	if (buf[0] != 'N' || buf[1] != t_id+0x20 || buf[2] != ACK)
		err(1);
}

static	void	s_header(int t_id, int s_id, int adr, int byte, int flow)
{
	char	buf[17];
	char	lrc;
	int	i;

	buf[0] = SOH;		/* (1) */
	buf[1] = '0';		/* (2) */
	buf[2] = 0x30 + t_id;	/* (3) */
	buf[3] = flow;		/* (4) */
	buf[4] = '1';		/* (5) */

	chag(adr, buf + 5);	/* (6 - 9)*/
	chag(byte, buf + 9);	/* (10 - 13)*/
	
	buf[13] = '0';		/* (14) */
	buf[14] = 0x30 + s_id;	/* (15) */
	buf[15] = ETB;		/* (16) */

	lrc = 0;
	for (i = 1; i < 15; i++)	/* 水平冗長検査 */
		lrc ^= buf[i];
	buf[16] = lrc;		/* (17) */

	send(buf, 17);
}

static	void	r_ack()
{
	char	buf[1];

	receive(buf, 1);
	if (buf[0] != ACK)
		err(1);
}

static	void	s_data(char *data, int byte)
{
	int	count1, count2;
	int	i, j;
	char	lrc;
	char	sbuf[259], rbuf[1], *bufp;

	count1 = byte / 0x100;
	count2 = byte % 0x100;

	for (i = 0; i < count1; i++) {
		bufp = sbuf;
		*bufp++ = STX;
		for (j = 0, lrc = 0; j < 256; j++)
			lrc ^= (*bufp++ = *data++);
		*bufp++ = ETB;
		*bufp = lrc;
		send(sbuf, 1+256+2);
		receive(rbuf, 1);
		if (rbuf[0] != ACK)
			err(1);
	}

	bufp = sbuf;
	*bufp++ = STX;
	for (j = 0, lrc = 0; j < count2; j++)
		lrc ^= (*bufp++ = *data++);
	*bufp++ = ETX;
	*bufp = lrc;
	send(sbuf, 1+count2+2);
	receive(rbuf, 1);
	if (rbuf[0] != ACK)
		err(1);
}

static	void	r_data(char *data, int byte)
{
	int	count1, count2;
	int	i, j;
	char	lrc;
	char	rbuf[259], *bufp;

	count1 = byte / 0x100;
	count2 = byte % 0x100;

	for (i = 0; i < count1; i++) {
		receive(rbuf, 1+256+2);
		bufp = rbuf;
		if (*bufp++ != STX)
			err(1);
		for (j = 0, lrc = 0; j < 256; j++)
			lrc ^= (*data++ = *bufp++);
		if (*bufp++ != ETB)
			err(1);
		if (*bufp != lrc)
			err(1);
		rs1_put(ACK);
	}

	receive(rbuf, 1+count2+2);
	bufp = rbuf;
	if (*bufp++ != STX)
		err(1);
	for (j = 0, lrc = 0; j < count2; j++)
		lrc ^= (*data++ = *bufp++);
	if (*bufp++ != ETX)
		err(1);
	if (*bufp != lrc)
		err(1);
	rs1_put(ACK);
}

static	void	r_eot()
{
	char	buf[1];

	receive(buf, 1);
	if (buf[0] != EOT)
		err(1);
}

/*=======================================================================
 |
 |		アスキー変換処理(HEX)
 |
 |	void	chag(n, asc)
 |
 |		int	n;		バイナリデータ
 |		char	*asc;		アスキーデータ
 |
 =======================================================================*/
static	void	chag(int n, char *asc)
{
	int	bin, i;

	asc += 4;
	for (i = 0; i < 4; i++) {
		bin = n & 0x000f;
		if (bin < 10)
			*--asc = 0x30 + bin;
		else
			*--asc = 0x37 + bin;
		n  >>= 4;
	}
}

static	void	send(char *data, int byte)
{
	while (byte--)
		rs1_put(*data++);
}

static	void	receive(char *buf, int byte)
{
	timset(300);

	while (byte != 0) {
		if (tm_ovf)
			err(2);
		if (rs1_rxc() != 0) {
			*buf++ = rs1_get();
			byte--;
		}
	}
}

static	void	err(int err_code)
{
	longjmp(jmpbuf, err_code);
}
