/************************************************************************
 *									*
 *		ＲＳ－２３２Ｃライブラリ				*
 *									*
 *		名称		: ＲＳ－２３２Ｃ　ＣＨ１制御		*
 *		ファイル名	: rs1.c					*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<stddef.h>
#include	<stdlib.h>
#include	<string.h>
#include	<dos.h>
#include	<conio.h>
#include	"rs232c.h"

/*	ワークエリア	*/
u_char	rs1_cmdreg;			/* コマンドレジスタ */
static	int	open_flag;		/* オープンフラグ */
static	int	duplex;			/* 二重モード */
static	int	*rbufp;			/* 受信バッファポインタ */
static	u_int	rbufsize;		/* 受信バッファ長 */
static	int	alloc_flag;		/* 受信バッファアロケーションフラグ */
static	u_int	read_ptr;		/* 受信バッファ読み込みポインタ */
static	u_int	write_ptr;		/* 受信バッファ書き込みポインタ */
static	u_int	rcv_count;		/* 受信文字数 */
static	int	flow_ctrl;		/* フロー制御方式 */
static	u_int	flow_off;		/* 受信待ち合わせ開始バッファサイズ */
static	u_int	flow_on;		/* 受信待ち合わせ解除バッファサイズ */
static	int	flow_rflag;		/* 受信フロー制御フラグ */
static	int	flow_sflag;		/* 送信フロー制御フラグ */
static	char	*send_buf;		/* 送信バッファ先頭ポインタ */
static	char	*send_ptr;		/* 送信バッファカレントポインタ */
static	int	send_len;		/* 送信データ長 */
static	char	l_pad[10];		/* リーディングパッドデータ */
static	int	l_pad_size;		/* リーディングパッドデータ長 */
static	char	t_pad[10];		/* トレーリングパッドデータ */
static	int	t_pad_size;		/* トレーリングパッドデータ長 */
static	int	(*rs1_rcvfunc)(int);	/* ユーザ受信割り込み関数ポインタ */
static	void	(far interrupt *save_vect)();	/* 割り込みベクタ保存エリア */
static	u_int	blt_tbl1[] = {	/* ボーレートカウンタ（１０ＭＨｚ） */
	0x8000, 0x4000, 0x2000, 0x1000, 0x800, 0x400, 0x200, 0x100, 0x80
};
static	u_int	blt_tbl2[] = {	/* ボーレートカウンタ（８ＭＨｚ） */
	0x6800, 0x3400, 0x1a00, 0xd00, 0x680, 0x340, 0x1a0, 0xd0, 0x68
};

/*	内部関数	*/
static	int	rs1_prmcheck(RSPRM *, u_int *, u_int *);
static	void	interrupt far	rs1_int(void);
static	void	outp2(int, int);

#pragma	check_stack-		/* スタックチェック無し */

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃオープン
 |
 |	int	rs1_open(rsprm)
 |
 |		RSPRM	*rsprm;		ＲＳ－２３２Ｃオープンパラメータ
 |
 |		返値			０：正常
 |					１：オープン済み
 |					２：同期モードパラメータエラー
 |					３：二重モードパラメータエラー
 |					４：ボーレートパラメータエラー
 |					５：パリティーパラメータエラー
 |					６：キャラクタ長パラメータエラー
 |					７：ストップビット長パラメータエラー
 |					８：フロー制御パラメータエラー
 |					９：ＤＴＲ制御パラメータエラー
 |					10：受信バッファ確保失敗
 |
 =======================================================================*/
int	rs1_open(RSPRM *rsprm)
{
	u_char	far	*fptr;
	union	{
		u_int	i;
		u_char	c[2];
	} cnt;
	u_int	mode, cmd;
	int	err;

	/* オープン済みチェック */
	if (open_flag != 0)
		return(1);

	/* オープンパラメータチェック */
	if ((err = rs1_prmcheck(rsprm, &mode, &cmd)) != 0)
		return(err);

	/* 受信バッファポインタ、受信バッファ長セーブ */
	rbufp = (int *)rsprm->bufp;
	rbufsize = rsprm->buf_size / sizeof(int);

	/* 受信バッファ確保 */
	if (rbufp == NULL) {
		if ((rbufp = malloc(rsprm->buf_size)) == NULL)
			return(10);
		alloc_flag = 1;
	} else
		alloc_flag = 0;

	_disable();		/* 割り込み禁止 */

	/* ボーレート設定 */
	FP_SEG(fptr) = 0;
	FP_OFF(fptr) = 0x501;
	cnt.i = ((*fptr & 0x80) ? blt_tbl2 : blt_tbl1)[rsprm->blt];
	if (rsprm->sync_mode == ASYNC)
		cnt.i /= 16;
	outp2(IOP_TMC_CWW, 0xb6);
	outp2(IOP_TMC_C2L, cnt.c[0]);
	outp2(IOP_TMC_C2L, cnt.c[1]);

	/* ＲＳ－２３２Ｃ割り込みマスク */
	outp2(IOP_SYS_WRT, inp(IOP_SYS_RD) & 0xf8);

	/* ８２５１Ａハードウェアリセット */
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0x40);

	/* モードレジスタセット */
	outp2(IOP_RS1_MOD, mode);

	/* 同期キャラクタセット */
	if (rsprm->sync_mode == SYNC) {
		outp2(IOP_RS1_SYN, rsprm->syn_chr);
		if ((mode & 0x80) == 0)
			outp2(IOP_RS1_SYN, rsprm->syn_chr >> 8);
	}

	/* コマンドセット */
	rs1_cmdreg = cmd;
	outp2(IOP_RS1_MOD, rs1_cmdreg | CMD_ER | CMD_EH);

	/* ポインタ＆カウンタ初期化 */
	read_ptr = write_ptr = rcv_count = 0;

	/* 割り込みベクタ保存 */
	save_vect = _dos_getvect(0x0c);

	/* 割り込みベクタセット */
	_dos_setvect(0x0c, rs1_int);

	/* 割り込みマスク解除 */
	outp2(IOP_ICM_IMR, inp(IOP_ICM_IMR) & 0xef);
	outp2(IOP_SYS_SET, 0x01);

	_enable();		/* 割り込み許可 */

	open_flag = 1;		/* オープン中フラグセット */

	return(0);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃ再オープン
 |
 |	int	rs1_reopen(rsprm)
 |
 |		RSPRM	*rsprm;		ＲＳ－２３２Ｃオープンパラメータ
 |
 |		返値			０：正常
 |					１：未オープン
 |					２：同期モードパラメータエラー
 |					３：二重モードパラメータエラー
 |					４：ボーレートパラメータエラー
 |					５：パリティーパラメータエラー
 |					６：キャラクタ長パラメータエラー
 |					７：ストップビット長パラメータエラー
 |					８：フロー制御パラメータエラー
 |					９：ＤＴＲ制御パラメータエラー
 |
 =======================================================================*/
int	rs1_reopen(RSPRM *rsprm)
{
	u_char	far	*fptr;
	union	{
		u_int	i;
		u_char	c[2];
	} cnt;
	u_int	mode, cmd;
	int	err;
	int	sync1_flag, sync1, sync2_flag, sync2;

	/* オープン済みチェック */
	if (open_flag == 0)
		return(1);

	/* オープンパラメータチェック */
	if ((err = rs1_prmcheck(rsprm, &mode, &cmd)) != 0)
		return(err);

	_disable();		/* 割り込み禁止 */

	/* ボーレート設定 */
	FP_SEG(fptr) = 0;
	FP_OFF(fptr) = 0x501;
	cnt.i = ((*fptr & 0x80) ? blt_tbl2 : blt_tbl1)[rsprm->blt];
	if (rsprm->sync_mode == ASYNC)
		cnt.i /= 16;
	outp2(IOP_TMC_CWW, 0xb6);
	outp2(IOP_TMC_C2L, cnt.c[0]);
	outp2(IOP_TMC_C2L, cnt.c[1]);

	/* ＲＳ－２３２Ｃ割り込みマスク */
	outp2(IOP_SYS_WRT, inp(IOP_SYS_RD) & 0xf8);

	/* 同期キャラクタチェック */
	sync1_flag = sync2_flag = 0;
	if (rsprm->sync_mode == SYNC) {
		sync1_flag = 1;
		sync1 = rsprm->syn_chr;
		if ((mode & 0x80) == 0) {
			sync2_flag = 1;
			sync2 = rsprm->syn_chr >> 8;
		}
	}

	/* ８２５１Ａハードウェアリセット */
	outp2(IOP_RS1_MOD, 0x40);

	/* モードレジスタセット */
	outp2(IOP_RS1_MOD, mode);

	/* 同期キャラクタセット */
	if (sync1_flag) {
		(void)outp(IOP_RS1_SYN, sync1);
		if (sync2_flag)
			(void)outp(IOP_RS1_SYN, sync2);
	}

	/* コマンドセット */
	rs1_cmdreg = cmd;
	(void)outp(IOP_RS1_MOD, rs1_cmdreg | CMD_ER | CMD_EH);

	/* ポインタ＆カウンタ初期化 */
	read_ptr = write_ptr = rcv_count = 0;

	/* 割り込みマスク解除 */
	outp2(IOP_SYS_SET, 0x01);

	_enable();		/* 割り込み許可 */

	return(0);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃオープンパラメータチェック
 |
 |	int	rs1_prmcheck(rsprm, mode, cmd)
 |
 |		RSPRM	*rsprm;		ＲＳ－２３２Ｃオープンパラメータ
 |		u_int	*mode;		８２５１Ａモード
 |		u_int	*cmd;		８２５１Ａコマンド
 |
 |		返値			０：正常パラメータ
 |					２：同期モードパラメータエラー
 |					３：二重モードパラメータエラー
 |					４：ボーレートパラメータエラー
 |					５：パリティーパラメータエラー
 |					６：キャラクタ長パラメータエラー
 |					７：ストップビット長パラメータエラー
 |					８：フロー制御パラメータエラー
 |					９：ＤＴＲ制御パラメータエラー
 |
 =======================================================================*/
static	int	rs1_prmcheck(RSPRM *rsprm, u_int *mode, u_int *cmd)
{
	/* モード、コマンド初期化 */
	*mode = 0;
	*cmd = 0;

	/* 同期モードパラメータチェック */
	switch (rsprm->sync_mode) {
	case SYNC:		/* 同期モード */
		break;
	case ASYNC:		/* 非同期モード */
		*mode |= 0x02;
		break;
	default:		/* パラメータエラー */
		return(2);
	}

	/* 二重モードパラメータチェック */
	switch (duplex = rsprm->duplex) {
	case DUPLEX_FULL:	/* 全二重 */
		*cmd |= CMD_RXE | CMD_TXE | CMD_RTS;
		break;
	case DUPLEX_HALF:	/* 半二重 */
		*cmd |= CMD_RXE;
		break;
	default:
		return(3);	/* パラメータエラー */
	}

	/* ボーレートチェック */
	if (rsprm->blt < BLT_75 || rsprm->blt > BLT_19200)
		return(4);

	/* パリティーチェック */
	switch (rsprm->parity) {
	case PARITY_NONE:	/* パリティー無し */
	case PARITY_EVEN:	/* 偶数パリティー */
	case PARITY_ODD:	/* 奇数パリティー */
		*mode |= rsprm->parity;
		break;
	default:		/* パラメータエラー */
		return(5);
	}

	/* キャラクタ長チェック */
	switch (rsprm->chr_len) {
	case CHR_5:		/* ５ビット */
	case CHR_6:		/* ６ビット */
	case CHR_7:		/* ７ビット */
	case CHR_8:		/* ８ビット */
		*mode |= rsprm->chr_len;
		break;
	default:		/* パラメータエラー */
		return(6);
	}

	if (rsprm->sync_mode == ASYNC) {
		/* ストップビット長チェック */
		switch (rsprm->stp_len) {
		case STP_1:	/* １ビット */
		case STP_1_5:	/* １．５ビット */
		case STP_2:	/* ２ビット */
			*mode |= rsprm->stp_len;
			break;
		default:	/* パラメータエラー */
			return(7);
		}
	} else {
		/* 同期キャラクタチェック */
		if ((rsprm->syn_chr & 0xff00) == 0)
			*mode |= 0x80;
	}

	/* フロー制御チェック */
	switch (rsprm->flow_ctrl) {
	case FLOW_NONE:		/* フロー制御無し */
	case FLOW_SOFT:		/* ＸＯＮ／ＸＯＦＦ制御 */
	case FLOW_HARD:		/* ＲＴＳ／ＣＴＳ制御 */
		flow_ctrl = rsprm->flow_ctrl;
		flow_on = rsprm->buf_size / 8;
		flow_off = rsprm->buf_size / 8 * 3;
		flow_rflag = 0;
		flow_sflag = 0;
		break;
	default:		/* パラメータエラー */
		return(8);
	}

	/* ＤＴＲ制御チェック */
	switch (rsprm->dtr) {
	case DTR_OFF:
		break;
	case DTR_ON:
		*cmd |= CMD_DTR;
		break;
	default:		/* パラメータエラー */
		return(9);
	}

	return(0);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃクローズ
 |
 |	void	rs1_close()
 |
 =======================================================================*/
void	rs1_close(void)
{
	/* 未オープンなら何もしない */
	if (open_flag == 0)
		return;

	_disable();		/* 割り込み禁止 */

	/* ハードウェアリセット */
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0x40);

	/* 割り込みマスク */
	outp2(IOP_ICM_IMR, inp(IOP_ICM_IMR) | 0x10);
	outp2(IOP_SYS_WRT, inp(IOP_SYS_RD) & 0xf8);

	/* 割り込みベクタ復帰 */
	_dos_setvect(0x0c, save_vect);

	_enable();		/* 割り込み許可 */

	/* 受信バッファを確保した場合は解放する */
	if (alloc_flag != 0)
		free(rbufp);

	rs1_cmdreg = 0;
	open_flag = 0;		/* オープン中フラグリセット */
}

/*=======================================================================
 |
 |		受信バッファチェック
 |
 |	int	rs1_rxc()
 |
 |		返値		受信文字数
 |
 =======================================================================*/
int	rs1_rxc(void)
{
	return(rcv_count);
}

/*=======================================================================
 |
 |		１文字受信
 |
 |	int	rs1_get()
 |
 |		返値		受信文字
 |
 =======================================================================*/
int	rs1_get(void)
{
	int	c;

	/* 受信待 */
	while (rcv_count == 0)
		;

	_disable();		/* 割り込み禁止 */

	/* １文字取り出し */
	c = rbufp[read_ptr++];
	if (read_ptr == rbufsize)
		read_ptr = 0;
	rcv_count--;

	/* フロー制御 */
	switch (flow_ctrl) {
	case FLOW_SOFT:		/* ＸＯＮ／ＸＯＦＦ制御 */
		if (flow_rflag != 0 && rcv_count < flow_on) {
			outp2(IOP_RS1_DAT, XON);	/* ＸＯＮ送信 */
			flow_rflag = 0;
		}
		break;
	case FLOW_HARD:		/* ＲＴＳ／ＣＴＳ制御 */
		if (flow_rflag != 0 && rcv_count < flow_on) {
			rs1_cmd(CMD_RTS, CMD_RTS);	/* ＲＴＳオン */
			flow_rflag = 0;
		}
		break;
	}

	_enable();		/* 割り込み許可 */

	return(c);
}

/*=======================================================================
 |
 |		受信バッファクリア
 |
 |	void	rs1_clear()
 |
 =======================================================================*/
void	rs1_clear(void)
{
	_disable();		/* 割り込み禁止 */

	/* ポインタ＆カウンタ初期化 */
	read_ptr = write_ptr = rcv_count = 0;

	_enable();		/* 割り込み許可 */
}

/*=======================================================================
 |
 |		１文字送信
 |
 |	void	rs1_put(c)
 |
 |		int	c;		送信文字
 |
 =======================================================================*/
void	rs1_put(int c)
{
	/* フロー制御による送信待ち合わせ */
	while (flow_sflag != 0)
		;

	/* 送信可待 */
	while ((inp(IOP_RS1_STS) & STS_TXRDY) == 0)
		;

	/* １文字送信 */
	outp2(IOP_RS1_DAT, c);
}

/*=======================================================================
 |
 |		ブロック送信
 |
 |	void	rs1_send(s, len)
 |
 |		char	*s;		送信文字列
 |		int	len;		文字列長
 |
 =======================================================================*/
void	rs1_send(char *s, int len)
{
	int	i;

	if (len == 0)
		return;

	if (duplex == DUPLEX_HALF) {
		/* 送信可、受信禁止、ＲＴＳオン */
		rs1_cmd(CMD_TXE|CMD_RTS, CMD_TXE|CMD_RXE|CMD_RTS);
	}

	/* リーディングＰＡＤ送信 */
	for (i = 0; i < l_pad_size; i++)
		rs1_put(l_pad[i]);

	/* データ送信 */
	while (len--)
		rs1_put(*s++);

	/* トレーリングＰＡＤ送信 */
	for (i = 0; i < t_pad_size; i++)
		rs1_put(t_pad[i]);

	if (duplex == DUPLEX_HALF) {
		rs1_cmd(0, CMD_TXE);	/* 送信禁止 */

		/* 送信終了を待つ */
		while ((inp(IOP_RS1_STS) & STS_TXE) == 0)
			;
		/* 受信可、ＲＴＳオフ */
		rs1_cmd(CMD_RXE, CMD_RXE|CMD_RTS);
	}
}

/*=======================================================================
 |
 |		ブロック送信要求
 |
 |	int	rs1_sendreq(s, len)
 |
 |		char	*s;		送信文字列
 |		int	len;		文字列長
 |
 |		返値			０：正常　１：送信バッファ確保失敗
 |
 =======================================================================*/
int	rs1_sendreq(char *s, int len)
{
	int	data_size;

	if (len == 0)
		return(0);

	/* 現在データ送信中なら送信終了まで待つ */
	while (send_len != 0)
		;

	/* データサイズ計算 */
	data_size = l_pad_size + len + t_pad_size;

	/* 送信データバッファ確保 */
	if ((send_buf = malloc(data_size)) == NULL)
		return(1);

	/* 送信データバッファセット */
	memcpy(send_buf, l_pad, l_pad_size);
	memcpy(send_buf+l_pad_size, s, len);
	memcpy(send_buf+l_pad_size+len, t_pad, t_pad_size);

	/* 送信データ長、送信データバッファポインタセット */
	send_len = data_size;
	send_ptr = send_buf;

	if (duplex == DUPLEX_HALF){
		/* 送信可、受信禁止、ＲＴＳオン */
		rs1_cmd(CMD_TXE|CMD_RXE|CMD_RTS, CMD_TXE|CMD_RTS);
	}

	outp2(IOP_SYS_SET, 0x05);	/* 送信可割り込み許可 */

	return(0);
}

/*=======================================================================
 |
 |		データ送信完了待ち
 |
 |	void	rs1_swait()
 |
 =======================================================================*/
void	rs1_swait(void)
{
	/* 現在データ送信中なら送信終了まで待つ */
	while (send_len != 0)
		;

	/* 送信終了を待つ */
	while ((inp(IOP_RS1_STS) & STS_TXE) == 0)
		;
}

/*=======================================================================
 |
 |		フロー制御設定
 |
 |	void	rs1_flow(ctrl)
 |
 |		int	ctrl;		フロー制御方式
 |
 =======================================================================*/
void	rs1_flow(int ctrl)
{
	flow_ctrl = ctrl;
}

/*=======================================================================
 |
 |		ＰＡＤ文字設定
 |
 |	void	rs1_pad(lpad, lpadsize, tpad, tpadsize)
 |
 |		char	*lpad;		リーディングパッドデータ
 |		int	lpad_size;	リーディングパッドデータ長
 |		char	*tpad;		トレーリングパッドデータ
 |		int	tpadsize;	トレーリングパッドデータ長
 |
 =======================================================================*/
void	rs1_pad(char *lpad, int lpadsize, char *tpad, int tpadsize)
{
	memcpy(l_pad, lpad, lpadsize);
	l_pad_size = lpadsize;
	memcpy(t_pad, tpad, tpadsize);
	t_pad_size = tpadsize;
}

/*=======================================================================
 |
 |		受信割り込み処理設定
 |
 |	void	rs1_rcvint(func)
 |
 |		int	(*func)();	受信割り込み処理
 |
 =======================================================================*/
void	rs1_rcvint(int (*func)(int))
{
	rs1_rcvfunc = func;
}

/*=======================================================================
 |
 |		エンターハントモードセット
 |
 |	void	rs1_ehm()
 |
 =======================================================================*/
void	rs1_ehm(void)
{
	outp2(IOP_RS1_MOD, rs1_cmdreg | 0x80);
}

/*=======================================================================
 |
 |		ＤＳＲチェック
 |
 |	int	rs1_dsr()
 |
 |		返値		０：ＤＳＲオフ　１：ＤＳＲオン
 |
 =======================================================================*/
int	rs1_dsr(void)
{
	return(inp(IOP_RS1_STS) & STS_DSR ? 1 : 0);
}

/*=======================================================================
 |
 |		キャリアチェック
 |
 |	int	rs1_cd()
 |
 |		返値		０：キャリアオフ　１：キャリアオン
 |
 =======================================================================*/
int	rs1_cd(void)
{
	return(inp(IOP_SYS_SGN) & SGN_CD ? 0 : 1);
}

/*=======================================================================
 |
 |		ＣＴＳチェック
 |
 |	int	rs1_cts()
 |
 |		返値		０：ＣＴＳオフ　１：ＣＴＳオン
 |
 =======================================================================*/
int	rs1_cts(void)
{
	return(inp(IOP_SYS_SGN) & SGN_CS ? 0 : 1);
}

/*=======================================================================
 |
 |		ＣＩチェック
 |
 |	int	rs1_ci()
 |
 |		返値		０：ＣＩオフ　１：ＣＩオン
 |
 =======================================================================*/
int	rs1_ci(void)
{
	return(inp(IOP_SYS_SGN) & SGN_CI ? 0 : 1);
}

/*=======================================================================
 |
 |		ＤＴＲ制御
 |
 |	void	rs1_dtr(sw);
 |
 |		int	sw;		０：ＤＴＲオフ　≠０：ＤＴＲオン
 |
 =======================================================================*/
void	rs1_dtr(int sw)
{
	_disable();

	if (sw == 0)
		rs1_cmdreg &= ~CMD_DTR;
	else
		rs1_cmdreg |= CMD_DTR;

	outp2(IOP_RS1_MOD, rs1_cmdreg);

	_enable();
}

/*=======================================================================
 |
 |		ＲＴＳ制御
 |
 |	void	rs1_rts(sw);
 |
 |		int	sw;		０：ＲＴＳオフ　≠０：ＲＴＳオン
 |
 =======================================================================*/
void	rs1_rts(int sw)
{
	_disable();

	if (sw == 0)
		rs1_cmdreg &= ~CMD_RTS;
	else
		rs1_cmdreg |= CMD_RTS;

	outp2(IOP_RS1_MOD, rs1_cmdreg);

	_enable();
}

/*=======================================================================
 |
 |		ブレーク信号送出
 |
 |	void	rs1_break(sw);
 |
 |		int	sw;		  ０：ブレーク信号オフ
 |					≠０：ブレーク信号オン
 |
 =======================================================================*/
void	rs1_break(int sw)
{
	_disable();

	if (sw == 0)
		rs1_cmdreg &= ~CMD_SBRK;
	else
		rs1_cmdreg |= CMD_SBRK;

	outp2(IOP_RS1_MOD, rs1_cmdreg);

	_enable();
}

/*=======================================================================
 |
 |		コマンド出力
 |
 |	void	rs1_cmd(cmd, mask)
 |
 |		int	cmd;		コマンド
 |		int	mask;		マスクデータ
 |
 =======================================================================*/
void	rs1_cmd(int cmd, int mask)
{
	_disable();
	rs1_cmdreg = (cmd & mask) | (rs1_cmdreg & ~mask);
	outp2(IOP_RS1_MOD, rs1_cmdreg);
	_enable();
}

/*=======================================================================
 |
 |		ステータス読み込み
 |
 |	int	rs1_sts()
 |
 |		返値			ステータス
 |
 =======================================================================*/
int	rs1_sts(void)
{
	return(inp(IOP_RS1_STS));
}

/*=======================================================================
 |
 |		割り込み処理
 |
 |	void	interrupt far	rs1_int()
 |
 =======================================================================*/
static	void	interrupt far	rs1_int(void)
{
	int	c, sts, sys_flag;

	sts = inp(IOP_RS1_STS);		/* ステータス読み込み */

	/* 受信割り込み処理 */
	if (sts & STS_RXRDY) {
		/* 受信文字読み込み */
		c = inp(IOP_RS1_DAT);

		/* ステータス読み込み */
		if (sts & 0x38) {
			/* エラーリセット */
			outp2(IOP_RS1_MOD, rs1_cmdreg | 0x10);
			c |= (sts & 0x38) << 8;
		}

		/* ユーザ受信割り込み関数呼び出し */
		if (rs1_rcvfunc != NULL)
			c = (*rs1_rcvfunc)(c);

		if (c == -1)
			;
		else if (flow_ctrl == FLOW_SOFT && c == XOFF) {
			flow_sflag = 1;
			if (send_len != 0) {
				/* 送信禁止 */
				outp2(IOP_RS1_MOD, rs1_cmdreg &= ~CMD_TXE);
			}
		} else if (flow_ctrl == FLOW_SOFT && c == XON) {
			flow_sflag = 0;
			if (send_len != 0) {
				/* 送信可 */
				outp2(IOP_RS1_MOD, rs1_cmdreg |= CMD_TXE);
			}
		} else if (rcv_count < rbufsize) {
			/* 受信文字をバッファにセット */
			rbufp[write_ptr++] = c;

			/* 書き込みポインタの更新 */
			if (write_ptr == rbufsize)
				write_ptr = 0;

			/* 受信文字数更新 */
			rcv_count++;

			/* フロー制御 */
			switch (flow_ctrl) {
			case FLOW_SOFT:
				if (flow_rflag == 0 && rcv_count > flow_off) {
					/* ＸＯＦＦ送信 */
					if (sts & STS_TXRDY) {
						outp2(IOP_RS1_DAT, XOFF);
						flow_rflag = 1;
					}
				}
				break;
			case FLOW_HARD:
				if (flow_rflag == 0 && rcv_count > flow_off) {
					/* ＲＴＳオフ */
					outp2(IOP_RS1_MOD, rs1_cmdreg &= ~CMD_RTS);
					flow_rflag = 1;
				}
				break;
			}
		}
	}

	/* ＥＭＰＴＹ割り込み処理 */
	if ((inp(IOP_SYS_RD) & IMR_TXEE) && (sts & STS_TXE)) {
		outp2(IOP_SYS_SET, 0x02);	/* ＥＭＰＴＹ割り込み禁止 */
		if (duplex == DUPLEX_HALF) {
			/* 受信可、ＲＴＳオフ */
			rs1_cmdreg = rs1_cmdreg & ~(CMD_RXE|CMD_RTS) | CMD_RXE;
			outp2(IOP_RS1_MOD, rs1_cmdreg);
		}
	}

	/* 送信可割り込み処理 */
	if (send_len != 0 && (sts & STS_TXRDY)) {
		outp2(IOP_RS1_DAT, *send_ptr++);		/* 次の１文字送信 */
		if (--send_len == 0) {
			if (duplex == DUPLEX_HALF) {
				/* 送信禁止 */
				outp2(IOP_RS1_MOD, rs1_cmdreg &= ~CMD_TXE);
			}
			outp2(IOP_SYS_SET, 0x04); /* 送信可割り込み禁止 */
			outp2(IOP_SYS_SET, 0x03); /* ＥＭＰＴＹ割り込み許可 */
			free(send_buf);		/* 送信バッファ解放 */
		}
	}

	/* 割り込みマスク */
	sys_flag = inp(IOP_SYS_RD);
	outp2(IOP_SYS_WRT, sys_flag & 0xf8);

	/* 割り込み終了通知 */
	outp2(IOP_ICM_EOI, 0x20);

	/* 割り込みマスク解除 */
	outp2(0x35, sys_flag);
}

/*=======================================================================
 |
 |		ポート書き込み
 |
 |	void	outp2(int port, int databyte)
 |
 |		int	port;		ポート番号
 |		int	databyte;	出力する値
 |
 =======================================================================*/
static	void	outp2(int port, int databyte)
{
	(void)outp(port, databyte);
}
