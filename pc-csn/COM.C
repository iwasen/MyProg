/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: 通信処理				*
 *		ファイル名	: com.c					*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	内部関数	*/
static	void	rs_puts(char *);

/*	スタティック変数	*/
static	int	_sig_ack;		/* 応答信号（テスト用） */

/*=======================================================================
 |
 |		コネクション開設処理
 |
 |	int	conxxxt(flag)
 |
 |		int	flag;		０：コネクション開設後ＳＲ送信無し
 |					１：　　　　　〃　　　　　　　有り
 |
 |		返値			０：開設成功　１：開設失敗
 |
 =======================================================================*/
int	conxxxt(flag)
{
	char	sig_cn[16];
	int	st, loop_sw;

	rs_open();		/* ＲＳ－２３２Ｃオープン */

	/* ＣＮ信号送信 */
	sprintf(sig_cn, "%02d%s%02X\x0d", ssg.nsg.conxxxtion, password, SIG_CN);	rs_puts(sig_cn);

	_sig_ack = SIG_CNA;
	timset(1000);		/* タイマーセット（１０秒） */

	loop_sw = 1;
	while (loop_sw) {
		switch (receive()) {
		case SIG_CNA:		/* ＣＮＡ受信 */
			online = 1;	/* オンラインフラグセット */
			if (flag == 0) {
				st = 0;
				loop_sw = 0;
			} else
				send_sig(SIG_SR);
				timset(1000);	/* タイマーセット（１０秒） */
			break;
		case SIG_SRA:
			if (flag && online) {
				st = 0;
				loop_sw = 0;
			}
			break;
		case SIG_AB:		/* ＡＢ受信 */
			/* アボート信号受信メッセージ出力 */
			ab_msg[15] = rcv_buf[4];
			enq_msg(MSG_ANM, ab_msg);
			st = 1;
			loop_sw = 0;
			break;
		case TIMEOUT:		/* 無応答 */
			/* システム無応答メッセージ出力 */
			dsp_loc_msg(LOC_MSG_NORESP);
			st = 1;
			loop_sw = 0;
			break;
		}
	}

	return(st);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃオープン
 |
 |	void	rs_open()
 |
 =======================================================================*/
void	rs_open()
{
	static	int	speed[4] = {7, 6, 5, 4};

	/* ＲＳ－２３２Ｃオープン */
	/* データ長　　　　：　８ｂｉｔ */
	/* パリティ　　　　：　ＥＶＥＮ */
	/* ストップビット　：　１ｂｉｔ */
	rs1_open(speed[csg.opt.rs232c], 0x7e, 0x37);
}

/*=======================================================================
 |
 |		コネクション切断処理
 |
 |	void	disconxxxt()
 |
 =======================================================================*/
void	disconxxxt()
{
	disconxxxt_request = 1;		/* コネクション切断要求フラグセット */
}

/*=======================================================================
 |
 |		信号送信処理
 |
 |	void	send_sig(sig_type)
 |
 |		int	sig_type;	信号種別
 |
 =======================================================================*/
void	send_sig(int sig_type)
{
	char	sig[6];

	if (opt_t == 0) {
		sprintf(sig, "%02d%02X\x0d", ssg.nsg.conxxxtion, sig_type);
		rs_puts(sig);
	} else {
		if (sig_type == SIG_SR)
			_sig_ack = sig_type + 2;
		else
			_sig_ack = sig_type + 1;
	}

	timset(1000);		/* タイマーセット（１０秒） */
}

/*=======================================================================
 |
 |		データ送信処理
 |
 |	void	send_sd(data)
 |
 |		char	*data;		送信データ
 |
 =======================================================================*/
void	send_sd(char *data)
{
	char	sig[6];

	if (opt_t == 0) {
		sprintf(sig, "%02d%02X1", ssg.nsg.conxxxtion, SIG_SD);
		rs_puts(sig);
		rs_puts(data);
		rs1_put(0x0d);
	}
}

/*=======================================================================
 |
 |		文字列送信処理
 |
 |	void	rs_puts(s)
 |
 |		char	*s;		送信文字列
 |
 =======================================================================*/
static	void	rs_puts(char *s)
{
	if (opt_t)
		return;

	while (*s)
		rs1_put(*s++);
}

/*=======================================================================
 |
 |		受信処理
 |
 |	int	receive()
 |
 |		返値			受信信号種別
 |
 =======================================================================*/
int	receive()
{
	static	int	rcv_count = 0;
	static	char	*rcv_ptr = rcv_buf;
	char	con[3];
	int	c, sig;

	if (tm_ovf) {
		tm_ovf = 0;
		return(TIMEOUT);
	}

	if (opt_t) {
		sig = _sig_ack;
		_sig_ack = 0;
		return(sig);
	}

	sprintf(con, "%02d", ssg.nsg.conxxxtion);

	sig = 0;
	if (rs1_rxc() != 0) {
		c = rs1_get();
		if (c == 0x0d) {
			*rcv_ptr = '\0';
			if (strncmp(rcv_buf, con, 2) == 0) {
				sscanf(rcv_buf+2, "%2x", &sig);
			}
			rcv_count = 0;
			rcv_ptr = rcv_buf;
		} else {
			if (++rcv_count > 256) {
				rcv_count = 0;
				rcv_ptr = rcv_buf;
			} else {
				*rcv_ptr++ = c;
				rcv_count++;
			}
		}
	}
	return(sig);
}
