/************************************************************************
 *									*
 *		通信プログラム　Ｙ－ＴＥＲＭ				*
 *									*
 *		名称		: ＸＭＯＤＥＭ送受信処理		*
 *		ファイル名	: xmodem.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include "rs232c.h"
#include "yterm.h"

#define	ST_END		0
#define	ST_ERR		-1
#define	ST_ABORT	-2
#define	ST_1		1
#define	ST_2		2
#define	ST_3		3
#define	SOH		0x01
#define	EOT		0x04
#define	ACK		0x06
#define	NAK		0x15
#define	CAN		0x18
#define	TIMEOUT		0x101
#define	ABORT		0x102

/*	内部関数	*/
static	int s_start(void);
static	int s_st1(int);
static	int s_st2(int);
static	int s_st3(int);
static	int send_dat(void);
static	int r_start(void);
static	int r_st1(int);
static	int r_st2(int);
static	int rcv_dat(void);
static	int event(void);

/*	内部変数	*/
static	u_int	blkno;
static	FILE	*sfp;
static	WINDOW	*swp;
static	u_char	rcv_buf[132];
static	int	retry_counter;
static	int	rcv_counter;
static	long	save_fp;

/*=======================================================================
 |
 |		ファイル送信処理
 |
 |	int	x_send(fp, wp)
 |
 |		FILE	*fp;		ファイルポインタ
 |		WINDOW	*wp;		ウィンドウポインタ
 |
 |		返値			　０：正常終了
 |					－１：送信失敗
 |					－２：中止
 |
 =======================================================================*/
int	x_send(FILE *fp, WINDOW *wp)
{
	int	st, ev;

	sfp = fp;		/* 送信ファイルポインタ保存 */
	swp = wp;		/* ウィンドウポインタ保存 */
	rs1_flow(FLOW_NONE);	/* フロー制御無し */
	rs1_clear();		/* 受信バッファクリア */

	st = s_start();		/* 送信開始処理 */
	while (st > 0) {
		ev = event();	/* イベント待ち */
		switch (st) {
		case ST_1:
			st = s_st1(ev);
			break;
		case ST_2:
			st = s_st2(ev);
			break;
		case ST_3:
			st = s_st3(ev);
			break;
		}
	}
	tm_stop(0);		/* タイマー停止 */

	if (cprm.xon)
		rs1_flow(FLOW_SOFT);	/* フロー制御設定 */

	return(st);
}

/*=======================================================================
 |
 |		ファイル送信開始処理
 |
 |	int	s_start()
 |
 |		返値			ＳＴ＿１
 |
 =======================================================================*/
static	int	s_start(void)
{
	tm_set(0, 6000);		/* ＮＡＫ待ちタイマーセット */
	blkno = 0;			/* ブロック番号クリア */

	return(ST_1);
}

/*=======================================================================
 |
 |		ＮＡＫ（送信要求）待ち処理
 |
 |	int	s_st1(ev)
 |
 |		int	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	int	s_st1(int ev)
{
	int	st;

	st = ST_1;
	switch (ev) {
	case NAK:
		blkno++;	/* ブロック番号更新 */
		if (send_dat() != EOF) {	/* データブロック送信 */
			tm_set(0, 6000);	/* ＡＣＫ待ちタイマーセット */
			st = ST_2;
		} else {
			/* 送信終了 */
			rs1_putc(EOT);		/* ＥＯＴ送信 */
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
 |	int	s_st2(ev)
 |
 |		int	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	int	s_st2(int ev)
{
	int	st;

	st = ST_2;
	switch(ev) {
	case ACK:
		blkno++;	/* ブロック番号更新 */
		if (send_dat() != EOF) {	/* データブロック送信 */
			tm_set(0, 6000);	/* ＡＣＫ待ちタイマーセット */
			st = ST_2;
		} else {
			/* 送信終了 */
			rs1_putc(EOT);		/* ＥＯＴ送信 */
			st = ST_3;
		}
		break;
	case NAK:
		fseek(sfp, save_fp, SEEK_SET);	/* ファイル位置を戻す */
		send_dat();			/* データブロック送信 */
		tm_set(0, 6000);		/* ＡＣＫ待ちタイマーセット */
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
 |	int	s_st3(ev)
 |
 |		int	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	int	s_st3(int ev)
{
	int	st;

	st = ST_3;
	switch (ev) {
	case ACK:
		st = ST_END;
		break;
	case NAK:
		rs1_putc(EOT);
		tm_set(0, 6000);	/* ＡＣＫ待ちタイマーセット */
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
 |	int	send_dat()
 |
 |		返値			０：送信した
 |					ＥＯＦ：送信終了
 |
 =======================================================================*/
static	int	send_dat(void)
{
	int	c, i;
	u_char	sum;

	/* 現ファイルポインタセーブ */
	save_fp = ftell(sfp);

	/* ファイル終了チェック */
	if ((c = fgetc(sfp)) == EOF)
		return(EOF);

	w_printf(swp, 21, 6, swp->color, "%u", blkno);	/* ブロック番号表示 */
	rs1_putc(SOH);		/* ＳＯＨ送信 */
	rs1_putc(blkno);		/* ブロック番号送信 */
	rs1_putc(~blkno);	/* 反転したブロック番号送信 */
	rs1_putc(c);		/* データの１バイト目送信 */
	sum = c;		/* チェックサムクリア */
	for (i = 0; i < 127; i++) {
		c = fgetc(sfp);	/* データ読み込み */
		if (c == EOF)
			c = 0x1a;
		rs1_putc(c);	/* データ送信 */
		sum += c;	/* チェックサム計算 */
	}
	rs1_putc(sum);		/* チェックサム送信 */

	return(0);
}

/*=======================================================================
 |
 |		ファイル受信処理
 |
 |	int	x_receive(fp, wp)
 |
 |		FILE	*fp;		ファイルポインタ
 |		WINDOW	*wp;		ウィンドウポインタ
 |
 |		返値			　０：正常終了
 |					－１：受信失敗
 |					－２：中止
 |
 =======================================================================*/
int	x_receive(FILE *fp, WINDOW *wp)
{
	int	st, ev;

	sfp = fp;		/* 送信ファイルポインタ保存 */
	swp = wp;		/* ウィンドウポインタ保存 */
	rs1_flow(FLOW_NONE);	/* フロー制御無し */
	rs1_clear();		/* 受信バッファクリア */

	st = r_start();		/* 受信開始処理 */
	while (st > 0) {
		ev = event();	/* イベント待ち */
		switch (st) {
		case ST_1:
			st = r_st1(ev);
			break;
		case ST_2:
			st = r_st2(ev);
			break;
		}
	}
	tm_stop(0);		/* タイマー停止 */

	if (cprm.xon)
		rs1_flow(FLOW_SOFT);	/* フロー制御設定 */

	return(st);
}

/*=======================================================================
 |
 |		ファイル受信開始処理
 |
 |	int	r_start()
 |
 |		返値			ＳＴ＿１
 |
 =======================================================================*/
static	int	r_start(void)
{
	rs1_putc(NAK);			/* ＮＡＫ（送信要求）送信 */
	tm_set(0, 1000);		/* ＳＯＨ待ちタイマーセット */
	retry_counter = 0;		/* リトライカウンタクリア */
	blkno = 0;			/* ブロック番号クリア */
	return(ST_1);
}

/*=======================================================================
 |
 |		ＳＯＨ受信待ち処理
 |
 |	int	r_st1(ev)
 |
 |		int	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	int	r_st1(int ev)
{
	int	st;

	st = ST_1;
	switch (ev) {
	case SOH:
		rcv_buf[0] = ev;	/* ＳＯＨセーブ */
		rcv_counter = 1;	/* 受信カウンタセット */
		tm_set(0, 100);		/* 文字間タイマーセット */
		st = ST_2;
		break;
	case EOT:
		rs1_putc(ACK);		/* ＡＣＫ送信 */
		st = ST_END;
		break;
	case TIMEOUT:
		if (++retry_counter <= 10) {	/* リトライチェック */
			rs1_putc(NAK);	/* ＮＡＫ送信 */
			tm_set(0, 1000);	/* ＳＯＨ待ちタイマーセット */
		} else {
			/* リトライオーバ */
			rs1_putc(CAN);	/* ＣＡＮ送信 */
			st = ST_ERR;
		}
		break;
	case ABORT:
		rs1_putc(CAN);	/* ＣＡＮ送信 */
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		データブロック受信待ち処理
 |
 |	int	r_st2(ev)
 |
 |		int	ev;		イベント
 |
 |		返値			ステータス（ＳＴ＿ＸＸＸ）
 |
 =======================================================================*/
static	int	r_st2(int ev)
{
	int	st;

	st = ST_2;
	switch (ev) {
	case TIMEOUT:
		if (++retry_counter <= 10) {	/* リトライチェック */
			rs1_putc(NAK);		/* ＮＡＫ送信 */
			tm_set(0, 1000);	/* ＳＯＨ待ちタイマーセット */
			st = ST_1;
		} else {
			rs1_putc(CAN);		/* ＣＡＮ送信 */
			st = ST_ERR;
		}
		break;
	case ABORT:
		rs1_putc(CAN);			/* ＣＡＮ送信 */
		st = ST_ABORT;
		break;
	default:
		if (ev <= 0xff) {
			rcv_buf[rcv_counter++] = ev;	/* 受信データセーブ */
			if (rcv_counter == 132) {
				/* １ブロック受信終了 */
				if (rcv_dat() == 0) {
					/* 受信データは正しい */
					rs1_putc(ACK);
					tm_set(0, 1000);
					retry_counter = 0;
					st = ST_1;
				} else {
					/* 受信データにエラー有り */
					if (++retry_counter <= 10) {
						rs1_putc(NAK);
						tm_set(0, 1000);
						st = ST_1;
					} else {
						rs1_putc(CAN);
						st = ST_ERR;
					}
				}
			} else
				tm_set(0, 100);	/* 文字間ﾀｲﾏｰｾｯﾄ */
		}
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		データブロック受信処理
 |
 |	int	rcv_dat()
 |
 |		返値			０：正常ブロック
 |					１：異常ブロック
 |
 =======================================================================*/
static	int	rcv_dat(void)
{
	u_char	sum;
	int	i;

	/* ブロック番号チェック */
	if (rcv_buf[1] != (u_char)~rcv_buf[2])
		return(1);

	/* チェックサムチェック */
	sum = 0;
	for (i = 0; i < 128; i++)
		sum += rcv_buf[3+i];
	if (sum != rcv_buf[131])
		return(1);

	/* ブロック番号シーケンスチェック */
	if (rcv_buf[1] == (u_char)blkno)
		;
	else if (rcv_buf[1] == (u_char)(blkno + 1)) {
		fwrite(rcv_buf + 3, 128, 1, sfp);
		blkno++;
	} else
		return(1);

	/* 受信ブロック番号表示 */
	w_printf(swp, 21, 6, swp->color, "%u", blkno);

	return(0);
}

/*=======================================================================
 |
 |		イベント待ち処理
 |
 |	int	event()
 |
 |		返値			イベント
 |
 =======================================================================*/
static	int	event(void)
{
	int	ev, ch;

	for (;;) {
		if (tm_check(0)) {
			ev = TIMEOUT;
			break;
		} else if (rs1_rxc()) {
			ev = (u_char)rs1_get();
			break;
		} else if (ch = inkey()) {
			if (ch == KEY_ESC || ch == KEY_F9) {
				ev = ABORT;
				break;
			}
		}
	}

	return(ev);
}
