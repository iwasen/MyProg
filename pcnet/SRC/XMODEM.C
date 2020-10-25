/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: ＸＭＯＤＥＭ送受信処理		*
 *		ファイル名	: xmodem.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <string.h>

#define	ST_END		0
#define	ST_ERR		-1
#define	ST_ABORT	-2
#define	ST_1		1
#define	ST_2		2
#define	ST_3		3
#define	SOH		(CHAR)0x01
#define	EOT		(CHAR)0x04
#define	ACK		(CHAR)0x06
#define	NAK		(CHAR)0x15
#define	CAN		(CHAR)0x18
#define	TIMEOUT		0x101
#define	ABORT		0x102

typedef	struct	{
	USHORT	chNo;
	FILE	*fp;
	VOID	(*func)(USHORT, USHORT);
	USHORT	blkno;
	CHAR	rcv_buf[132];
	SHORT	timer;
	SHORT	retry_counter;
	SHORT	rcv_counter;
	LONG	save_fp;
	BOOL	crcMode;
} XMODEM;

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	SHORT	s_start(XMODEM *);
static	SHORT	s_st1(XMODEM *, SHORT);
static	SHORT	s_st2(XMODEM *, SHORT);
static	SHORT	s_st3(XMODEM *, SHORT);
static	SHORT	send_dat(XMODEM *);
static	SHORT	r_start(XMODEM *);
static	SHORT	r_st1(XMODEM *, SHORT);
static	SHORT	r_st2(XMODEM *, SHORT);
static	SHORT	rcv_dat(XMODEM *);
static	SHORT	event(XMODEM *);
static	VOID	SendChar(XMODEM *, CHAR);
static	VOID	SendBlock(XMODEM *, CHAR *, USHORT);
static	VOID	SetTimer(XMODEM *, SHORT);
static	USHORT	calcrc(CHAR *, SHORT);

/*--------------------------------------*/
/*		内部変数		*/
/*--------------------------------------*/
static	BOOL	xFlag[N_CHANNEL];

/*=======================================================================
 |
 |		ファイル送信処理
 |
 |	SHORT	SXmodem(chNo, fp, func)
 |
 |		USHORT	chNo;		チャネル番号
 |		FILE	*fp;		ファイルポインタ
 |		VOID	(*func)();	ブロック番号表示処理関数
 |
 |		返値			　０：正常終了
 |					－１：送信失敗
 |					－２：中止
 |
 =======================================================================*/
SHORT	SXmodem(USHORT chNo, FILE *fp, VOID (*func)())
{
	SHORT	st, ev;
	DCBINFO		svDCBInfo, DCBInfo;
	XMODEM	xmodem;

	if (chNo == CH0)
		return(-1);

	xFlag[chNo] = TRUE;

	DosDevIOCtl(&DCBInfo, 0L, ASYNC_GETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);
	memcpy(&svDCBInfo, &DCBInfo, sizeof(DCBINFO));

	DCBInfo.fbFlowReplace = 0;
	DCBInfo.fbTimeout = MODE_READ_TIMEOUT;
	DosDevIOCtl(0L, &DCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);

	ClearRcvQue(chNo);		/* 受信バッファクリア */

	xmodem.chNo = chNo;
	xmodem.fp = fp;
	xmodem.func = func;

	st = s_start(&xmodem);		/* 送信開始処理 */
	while (st > 0) {
		ev = event(&xmodem);	/* イベント待ち */
		switch (st) {
		case ST_1:
			st = s_st1(&xmodem, ev);
			break;
		case ST_2:
			st = s_st2(&xmodem, ev);
			break;
		case ST_3:
			st = s_st3(&xmodem, ev);
			break;
		}
	}

	DosDevIOCtl(0L, &svDCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);

	xFlag[chNo] = FALSE;

	return(st);
}

/*=======================================================================
 |
 |		ファイル送信開始処理
 |
 |	SHORT	s_start(xp)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |
 |		返値			ＳＴ＿１
 |
 =======================================================================*/
static	SHORT	s_start(XMODEM *xp)
{
	SetTimer(xp, 60);		/* ＮＡＫ待ちタイマーセット */
	xp->blkno = 0;			/* ブロック番号クリア */

	return(ST_1);
}

/*=======================================================================
 |
 |		ＮＡＫ（送信要求）待ち処理
 |
 |	SHORT	s_st1(xp, ev)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |		SHORT	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	SHORT	s_st1(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_1;
	switch (ev) {
	case NAK:
		xp->crcMode = FALSE;
		goto label_1;
	case 'C':
		xp->crcMode = TRUE;
	label_1:
		xp->blkno++;		/* ブロック番号更新 */
		if (send_dat(xp) != EOF) {	/* データブロック送信 */
			SetTimer(xp, 60);	/* ＡＣＫ待ちタイマーセット */
			st = ST_2;
		} else {
			/* 送信終了 */
			SendChar(xp, EOT);	/* ＥＯＴ送信 */
			st = ST_3;
		}
		break;
	case CAN:
		st = ST_ERR;
		break;
	case TIMEOUT:
		st = ST_ERR;
		break;
	case ABORT:
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		データブロック送信後ＡＣＫ待ち処理
 |
 |	SHORT	s_st2(xp, ev)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |		SHORT	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	SHORT	s_st2(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_2;
	switch(ev) {
	case ACK:
		xp->blkno++;		/* ブロック番号更新 */
		if (send_dat(xp) != EOF) {	/* データブロック送信 */
			SetTimer(xp, 60);	/* ＡＣＫ待ちタイマーセット */
			st = ST_2;
		} else {
			/* 送信終了 */
			SendChar(xp, EOT);	/* ＥＯＴ送信 */
			st = ST_3;
		}
		break;
	case NAK:
		/* ファイル位置を戻す */
		fseek(xp->fp, xp->save_fp, SEEK_SET);
		send_dat(xp);			/* データブロック送信 */
		SetTimer(xp, 60);		/* ＡＣＫ待ちタイマーセット */
		st = ST_2;
		break;
	case CAN:
		st = ST_ERR;
		break;
	case TIMEOUT:
		st = ST_ERR;
		break;
	case ABORT:
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		ＥＯＴ送信後ＡＣＫ待ち処理
 |
 |	SHORT	s_st3(xp, ev)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |		SHORT	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	SHORT	s_st3(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_3;
	switch (ev) {
	case ACK:
		st = ST_END;
		break;
	case NAK:
		SendChar(xp, EOT);
		SetTimer(xp, 60);		/* ＡＣＫ待ちタイマーセット */
		break;
	case CAN:
		st = ST_ERR;
		break;
	case TIMEOUT:
		st = ST_ERR;
		break;
	case ABORT:
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		データブロック送信処理
 |
 |	SHORT	send_dat(xp)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |
 |		返値			０：送信した
 |					ＥＯＦ：送信終了
 |
 =======================================================================*/
static	SHORT	send_dat(XMODEM *xp)
{
	SHORT	c, i;
	CHAR	sum;
	USHORT	crc;
	CHAR	sendBuf[132];

	/* 現ファイルポインタセーブ */
	xp->save_fp = ftell(xp->fp);

	/* ファイル終了チェック */
	if ((c = fgetc(xp->fp)) == EOF)
		return(EOF);

	sendBuf[0] = SOH;
	sendBuf[1] = (CHAR)xp->blkno;	/* ブロック番号送信 */
	sendBuf[2] = (CHAR)~xp->blkno;	/* 反転したブロック番号送信 */
	sendBuf[3] = (CHAR)c;		/* データの１バイト目送信 */
	for (i = 4; i < 131; i++) {
		c = fgetc(xp->fp);		/* データ読み込み */
		if (c == EOF)
			c = 0x1a;
		sendBuf[i] = (CHAR)c;	/* データ送信 */
	}

	if (xp->crcMode) {
		crc = calcrc(&sendBuf[3], 128);
		sendBuf[i++] = (CHAR)(crc >> 8);
		sendBuf[i] = (CHAR)crc;
		SendBlock(xp, sendBuf, 133);
	} else {
		sum = 0;
		for (i = 3; i < 131; i++)
			sum += sendBuf[i];
		sendBuf[i] = sum;
		SendBlock(xp, sendBuf, 132);
	}

	if (xp->func != NULL)
		(*xp->func)(xp->chNo, xp->blkno);

	return(0);
}

/*=======================================================================
 |
 |		ファイル受信処理
 |
 |	SHORT	RXmodem(chNo, fp, func)
 |
 |		USHORT	chNo;		チャネル番号
 |		FILE	*fp;		ファイルポインタ
 |		VOID	(*func)();	ブロック番号表示処理関数
 |
 |		返値			　０：正常終了
 |					－１：受信失敗
 |					－２：中止
 |
 =======================================================================*/
SHORT	RXmodem(USHORT chNo, FILE *fp, VOID (*func)())
{
	SHORT	st, ev;
	DCBINFO		svDCBInfo, DCBInfo;
	XMODEM	xmodem;

	if (chNo == CH0)
		return(-1);

	xFlag[chNo] = TRUE;

	DosDevIOCtl(&DCBInfo, 0L, ASYNC_GETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);
	memcpy(&svDCBInfo, &DCBInfo, sizeof(DCBINFO));

	DCBInfo.fbFlowReplace = 0;
	DCBInfo.fbTimeout = MODE_READ_TIMEOUT;
	DosDevIOCtl(0L, &DCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);

	ClearRcvQue(chNo);		/* 受信バッファクリア */

	xmodem.chNo = chNo;
	xmodem.fp  = fp;
	xmodem.func = func;

	st = r_start(&xmodem);		/* 受信開始処理 */
	while (st > 0) {
		ev = event(&xmodem);	/* イベント待ち */
		switch (st) {
		case ST_1:
			st = r_st1(&xmodem, ev);
			break;
		case ST_2:
			st = r_st2(&xmodem, ev);
			break;
		}
	}

	DosDevIOCtl(0L, &svDCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);

	xFlag[chNo] = FALSE;

	return(st);
}

/*=======================================================================
 |
 |		ファイル受信開始処理
 |
 |	SHORT	r_start(xp)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |
 |		返値			ＳＴ＿１
 |
 =======================================================================*/
static	SHORT	r_start(XMODEM *xp)
{
	SendChar(xp, NAK);		/* ＮＡＫ（送信要求）送信 */
	SetTimer(xp, 10);		/* ＳＯＨ待ちタイマーセット */
	xp->retry_counter = 0;		/* リトライカウンタクリア */
	xp->blkno = 0;			/* ブロック番号クリア */
	return(ST_1);
}

/*=======================================================================
 |
 |		ＳＯＨ受信待ち処理
 |
 |	SHORT	r_st1(xp, ev)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |		SHORT	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	SHORT	r_st1(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_1;
	switch (ev) {
	case SOH:
		xp->rcv_buf[0] = SOH;	/* ＳＯＨセーブ */
		xp->rcv_counter = 1;	/* 受信カウンタセット */
		SetTimer(xp, 1);	/* 文字間タイマーセット */
		st = ST_2;
		break;
	case EOT:
		SendChar(xp, ACK);	/* ＡＣＫ送信 */
		st = ST_END;
		break;
	case TIMEOUT:
		if (++xp->retry_counter <= 10) {	/* リトライチェック */
			SendChar(xp, NAK);	/* ＮＡＫ送信 */
			SetTimer(xp, 10);	/* ＳＯＨ待ちタイマーセット */
		} else {
			/* リトライオーバ */
			SendChar(xp, CAN);	/* ＣＡＮ送信 */
			st = ST_ERR;
		}
		break;
	case ABORT:
		SendChar(xp, CAN);	/* ＣＡＮ送信 */
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		データブロック受信待ち処理
 |
 |	SHORT	r_st2(xp, ev)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |		SHORT	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	SHORT	r_st2(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_2;
	switch (ev) {
	case TIMEOUT:
		if (++xp->retry_counter <= 10) {	/* リトライチェック */
			SendChar(xp, NAK);	/* ＮＡＫ送信 */
			SetTimer(xp, 10);	/* ＳＯＨ待ちタイマーセット */
			st = ST_1;
		} else {
			SendChar(xp, CAN);	/* ＣＡＮ送信 */
			st = ST_ERR;
		}
		break;
	case ABORT:
		SendChar(xp, CAN);		/* ＣＡＮ送信 */
		st = ST_ABORT;
		break;
	default:
		if (ev <= 0xff) {
			/* 受信データセーブ*/
			xp->rcv_buf[xp->rcv_counter++] = (CHAR)ev;
			if (xp->rcv_counter == 132) {
				/* １ブロック受信終了 */
				if (rcv_dat(xp) == 0) {
					/* 受信データは正しい */
					SendChar(xp, ACK);
					SetTimer(xp, 10);
					xp->retry_counter = 0;
					st = ST_1;
				} else {
					/* 受信データにエラー有り */
					if (++xp->retry_counter <= 10) {
						SendChar(xp, NAK);
						SetTimer(xp, 10);
						st = ST_1;
					} else {
						SendChar(xp, CAN);
						st = ST_ERR;
					}
				}
			} else
				SetTimer(xp, 1);	/* 文字間ﾀｲﾏｰｾｯﾄ */
		}
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		データブロック受信処理
 |
 |	SHORT	rcv_dat(xp)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |
 |		返値			０：正常ブロック
 |					１：異常ブロック
 |
 =======================================================================*/
static	SHORT	rcv_dat(XMODEM *xp)
{
	CHAR	sum;
	SHORT	i;

	/* ブロック番号チェック */
	if (xp->rcv_buf[1] != (CHAR)~xp->rcv_buf[2])
		return(1);

	/* チェックサムチェック */
	sum = 0;
	for (i = 0; i < 128; i++)
		sum += xp->rcv_buf[3+i];
	if (sum != xp->rcv_buf[131])
		return(1);

	/* ブロック番号シーケンスチェック */
	if (xp->rcv_buf[1] == (CHAR)xp->blkno)
		;
	else if (xp->rcv_buf[1] == (CHAR)(xp->blkno + 1)) {
		fwrite(xp->rcv_buf + 3, 128, 1, xp->fp);
		xp->blkno++;
	} else
		return(1);

	if (xp->func != NULL)
		(*xp->func)(xp->chNo, xp->blkno);

	return(0);
}

/*=======================================================================
 |
 |		イベント待ち処理
 |
 |	SHORT	event(xp)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |
 |		返値			イベント
 |
 =======================================================================*/
static	SHORT	event(XMODEM *xp)
{
	CHAR	rcvChar;
	USHORT	numBytes;
	SHORT	ev;
	SHORT	timer;

	timer = xp->timer;
	for (;;) {
		if (xFlag[xp->chNo] == FALSE) {
			ev = ABORT;
			break;
		}

		DosRead(portHndl[xp->chNo], &rcvChar, 1, &numBytes);
		if (numBytes != 0) {
			ev = rcvChar;
			break;
		} else if (--timer == 0) {
			ev = TIMEOUT;
			break;
		}
	}

	return(ev);
}

/*=======================================================================
 |
 |		１文字送信処理
 |
 |	VOID	SendChar(xp, ch)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |		CHAR	ch;		送信文字
 |
 =======================================================================*/
static	VOID	SendChar(XMODEM *xp, CHAR ch)
{
	USHORT	numBytes;

	DosWrite(portHndl[xp->chNo], &ch, 1, &numBytes);
}

/*=======================================================================
 |
 |		１ブロック送信処理
 |
 |	VOID	SendBlock(xp, block, len)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |		CHAR	*block;		ブロックバッファ
 |		USHORT	len;		ブロック長
 |
 =======================================================================*/
static	VOID	SendBlock(XMODEM *xp, CHAR *block, USHORT len)
{
	USHORT	numBytes;

	DosWrite(portHndl[xp->chNo], block, len, &numBytes);
}

/*=======================================================================
 |
 |		タイマーセット処理
 |
 |	VOID	SetTimer(xp, tm)
 |
 |		XMODEM	*xp;		ＸＭＯＤＥＭパラメータ
 |		SHORT	tm;		タイマー値
 |
 =======================================================================*/
static	VOID	SetTimer(XMODEM *xp, SHORT tm)
{
	xp->timer = tm;
}

/*=======================================================================
 |
 |		ＸＭＯＤＥＭ中止処理
 |
 |	VOID	AbortXmodem(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
VOID	AbortXmodem(USHORT chNo)
{
	xFlag[chNo] = FALSE;
}

/*=======================================================================
 |
 |		ＣＲＣ計算
 |
 |	USHORT	calcrc(ptr, count)
 |
 |		CHAR	*ptr;		データバッファ
 |		USHORT	crc;		データ数
 |
 |		返値	ＣＲＣ
 |
 =======================================================================*/
static	USHORT	calcrc(CHAR *ptr, SHORT count)
{
	USHORT	crc, i;

	crc = 0;
	while (--count >= 0) {
		crc = crc ^ (USHORT)*ptr++ << 8;
		for (i = 0; i < 8; i++)
			if (crc & 0x8000)
				crc = crc << 1 ^ 0x1021;
			else
				crc = crc << 1;
	}
	return(crc);
}
