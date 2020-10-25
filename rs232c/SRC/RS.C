/************************************************************************
 *									*
 *		ＲＳ－２３２Ｃライブラリ				*
 *									*
 *		名称		: ＲＳ－２３２Ｃ　全チャネル		*
 *		ファイル名	: rs.c					*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<stddef.h>
#include	<stdlib.h>
#include	<dos.h>
#include	<conio.h>
#include	"rs232c.h"

#pragma	check_stack-		/* スタックチェック無し */

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃオープン
 |
 |	int	rsx_open(channel, rsprm)
 |
 |		int	channel;	チャネル番号（１～３）
 |		RSPRM	*rsprm;		ＲＳ－２３２Ｃオープンパラメータ
 |
 |		返値			０：正常オープン
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
int	rsx_open(int channel, RSPRM *rsprm)
{
	static	int	(*rs_func[3])(RSPRM *) = {
		rs1_open, rs2_open, rs3_open
	};

	return (*rs_func[channel])(rsprm);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃ再オープン
 |
 |	int	rsx_reopen(channel, rsprm)
 |
 |		int	channel;	チャネル番号（１～３）
 |		RSPRM	*rsprm;		ＲＳ－２３２Ｃオープンパラメータ
 |
 |		返値			０：正常オープン
 |					１：未オープン
 |					２：同期モードパラメータエラー
 |					３：二重モードパラメータエラー
 |					４：ボーレートパラメータエラー
 |					５：パリティーパラメータエラー
 |					６：キャラクタ長パラメータエラー
 |					７：ストップビット長パラメータエラー
 |					８：フロー制御パラメータエラー
 |
 =======================================================================*/
int	rsx_reopen(int channel, RSPRM *rsprm)
{
	static	int	(*rs_func[3])(RSPRM *) = {
		rs1_reopen, rs2_reopen, rs3_reopen
	};

	return (*rs_func[channel])(rsprm);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃクローズ
 |
 |	void	rsx_close(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 =======================================================================*/
void	rsx_close(int channel)
{
	static	void	(*rs_func[3])(void) = {
		rs1_close, rs2_close, rs3_close
	};

	(*rs_func[channel])();
}

/*=======================================================================
 |
 |		受信バッファチェック
 |
 |	int	rsx_rxc(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 |		返値			受信文字数
 |
 =======================================================================*/
int	rsx_rxc(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_rxc, rs2_rxc, rs3_rxc
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		１文字受信
 |
 |	int	rsx_get(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 |		返値			受信文字
 |
 =======================================================================*/
int	rsx_get(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_get, rs2_get, rs3_get
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		受信バッファクリア
 |
 |	void	rsx_clear(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 =======================================================================*/
void	rsx_clear(int channel)
{
	static	void	(*rs_func[3])(void) = {
		rs1_clear, rs2_clear, rs3_clear
	};

	(*rs_func[channel])();
}

/*=======================================================================
 |
 |		１文字送信
 |
 |	void	rsx_put(channel, c)
 |
 |		int	channel;	チャネル番号（１～３）
 |		int	c;		送信文字
 |
 =======================================================================*/
void	rsx_put(int channel, int c)
{
	static	void	(*rs_func[3])(int) = {
		rs1_put, rs2_put, rs3_put
	};

	(*rs_func[channel])(c);
}

/*=======================================================================
 |
 |		ブロック送信
 |
 |	void	rsx_send(channel, s, len)
 |
 |		int	channel;	チャネル番号（１～３）
 |		char	*s;		送信文字列
 |		int	len;		文字列長
 |
 =======================================================================*/
void	rsx_send(int channel, char *s, int len)
{
	static	void	(*rs_func[3])(char *, int) = {
		rs1_send, rs2_send, rs3_send
	};

	(*rs_func[channel])(s, len);
}

/*=======================================================================
 |
 |		ブロック送信要求
 |
 |	int	rsx_sendreq(channel, s, len)
 |
 |		int	channel;	チャネル番号（１～３）
 |		char	*s;		送信文字列
 |		int	len;		文字列長
 |
 |		返値			０：正常　１：送信バッファ確保失敗
 |
 =======================================================================*/
int	rsx_sendreq(int channel, char *s, int len)
{
	static	int	(*rs_func[3])(char *, int) = {
		rs1_sendreq, rs2_sendreq, rs3_sendreq
	};

	return (*rs_func[channel])(s, len);
}

/*=======================================================================
 |
 |		データ送信完了待ち
 |
 |	void	rsx_swait(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 =======================================================================*/
void	rsx_swait(int channel)
{
	static	void	(*rs_func[3])(void) = {
		rs1_swait, rs2_swait, rs3_swait
	};

	(*rs_func[channel])();
}

/*=======================================================================
 |
 |		フロー制御設定
 |
 |	void	rsx_flow(channel, ctrl)
 |
 |		int	channel;	チャネル番号（１～３）
 |		int	ctrl;		フロー制御方式
 |
 =======================================================================*/
void	rsx_flow(int channel, int ctrl)
{
	static	void	(*rs_func[3])(int) = {
		rs1_flow, rs2_flow, rs3_flow
	};

	(*rs_func[channel])(ctrl);
}

/*=======================================================================
 |
 |		ＰＡＤ文字設定
 |
 |	void	rsx_pad(channel, lpad, lpadsize, tpad, tpadsize)
 |
 |		int	channel;	チャネル番号（１～３）
 |		char	*lpad;		リーディングパッドデータ
 |		int	lpad_size;	リーディングパッドデータ長
 |		char	*tpad;		トレーリングパッドデータ
 |		int	tpadsize;	トレーリングパッドデータ長
 |
 =======================================================================*/
void	rsx_pad(int channel, char *lpad, int lpadsize, char *tpad, int tpadsize)
{
	static	void	(*rs_func[3])(char *, int, char *, int) = {
		rs1_pad, rs2_pad, rs3_pad
	};

	(*rs_func[channel])(lpad, lpadsize, tpad, tpadsize);
}

/*=======================================================================
 |
 |		受信割り込み処理設定
 |
 |	void	rsx_rcvint(channel, func)
 |
 |		int	channel;	チャネル番号（１～３）
 |		int	(*func)();	受信割り込み処理
 |
 =======================================================================*/
void	rsx_rcvint(int channel, int (*func)(int))
{
	static	void	(*rs_func[3])(int (*func)(int)) = {
		rs1_rcvint, rs2_rcvint, rs3_rcvint
	};

	(*rs_func[channel])(func);
}

/*=======================================================================
 |
 |		エンターハントモードセット
 |
 |	void	rsx_ehm(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 =======================================================================*/
void	rsx_ehm(int channel)
{
	static	void	(*rs_func[3])(void) = {
		rs1_ehm, rs2_ehm, rs3_ehm
	};

	(*rs_func[channel])();
}

/*=======================================================================
 |
 |		ＤＳＲチェック
 |
 |	int	rsx_dsr(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 |		返値		０：ＤＳＲオフ　１：ＤＳＲオン
 |
 =======================================================================*/
int	rsx_dsr(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_dsr, rs2_dsr, rs3_dsr
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		キャリアチェック
 |
 |	int	rsx_cd(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 |		返値		０：キャリアオフ　１：キャリアオン
 |
 =======================================================================*/
int	rsx_cd(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_cd, rs2_cd, rs3_cd
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		ＣＴＳチェック
 |
 |	int	rsx_cts(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 |		返値		０：ＣＴＳオフ　１：ＣＴＳオン
 |
 =======================================================================*/
int	rsx_cts(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_cts, rs2_cts, rs3_cts
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		ＣＩチェック
 |
 |	int	rsx_ci(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 |		返値		０：ＣＩオフ　１：ＣＩオン
 |
 =======================================================================*/
int	rsx_ci(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_ci, rs2_ci, rs3_ci
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		ＤＴＲ制御
 |
 |	void	rsx_dtr(channel, sw);
 |
 |		int	channel;	チャネル番号（１～３）
 |		int	sw;		０：ＤＴＲオフ　≠０：ＤＴＲオン
 |
 =======================================================================*/
void	rsx_dtr(int channel, int sw)
{
	static	void	(*rs_func[3])(int) = {
		rs1_dtr, rs2_dtr, rs3_dtr
	};

	(*rs_func[channel])(sw);
}

/*=======================================================================
 |
 |		ＲＴＳ制御
 |
 |	void	rsx_rts(channel, sw);
 |
 |		int	channel;	チャネル番号（１～３）
 |		int	sw;		０：ＲＴＳオフ　≠０：ＲＴＳオン
 |
 =======================================================================*/
void	rsx_rts(int channel, int sw)
{
	static	void	(*rs_func[3])(int) = {
		rs1_rts, rs2_rts, rs3_rts
	};

	(*rs_func[channel])(sw);
}

/*=======================================================================
 |
 |		ブレーク信号送出
 |
 |	void	rsx_break(channel, sw);
 |
 |		int	channel;	チャネル番号（１～３）
 |		int	sw;		  ０：ブレーク信号オフ
 |					≠０：ブレーク信号オン
 |
 =======================================================================*/
void	rsx_break(int channel, int sw)
{
	static	void	(*rs_func[3])(int) = {
		rs1_break, rs2_break, rs3_break
	};

	(*rs_func[channel])(sw);
}

/*=======================================================================
 |
 |		コマンド出力
 |
 |	void	rsx_cmd(channel, cmd, mask)
 |
 |		int	channel;	チャネル番号（１～３）
 |		int	cmd;		コマンド
 |		int	mask;		マスクデータ
 |
 =======================================================================*/
void	rsx_cmd(int channel, int cmd, int mask)
{
	static	void	(*rs_func[3])(int, int) = {
		rs1_cmd, rs2_cmd, rs3_cmd
	};

	(*rs_func[channel])(cmd, mask);
}

/*=======================================================================
 |
 |		ステータス読み込み
 |
 |	int	rsx_sts(channel)
 |
 |		int	channel;	チャネル番号（１～３）
 |
 |		返値			ステータス
 |
 =======================================================================*/
int	rsx_sts(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_sts, rs2_sts, rs3_sts
	};

	return (*rs_func[channel])();
}
