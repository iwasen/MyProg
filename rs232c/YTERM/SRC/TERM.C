/************************************************************************
 *									*
 *		通信プログラム　Ｙ－ＴＥＲＭ				*
 *									*
 *		名称		: 各処理プログラム			*
 *		ファイル名	: term.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <ctype.h>
#include <time.h>
#include <string.h>
#include "rs232c.h"
#include "yterm.h"

/*	内部関数 */
static	void	dsp_dial(WINDOW *, int, int);
static	void	dsp_modem(WINDOW *, int, int);
static	int	inp_param(WINDOW *);
static	WINDOW	*dsp_param(int, int, char *);
static	void	dsp_speed(WINDOW *, int, int);
static	void	dsp_parity(WINDOW *, int, int);
static	void	dsp_chrlen(WINDOW *, int, int);
static	void	dsp_stplen(WINDOW *, int, int);
static	void	dsp_xon(WINDOW *, int, int);
static	void	dsp_echo(WINDOW *, int, int);
static	void	dsp_retkey(WINDOW *, int, int);
static	void	dsp_prompt(WINDOW *, int);
static	WINDOW	*dsp_center(int, int, char *, int);
static	int	sel_center(WINDOW *, int);
static	int	auto_call(void);
static	int	rcv_ack(WINDOW *);
static	void	auto_call_err(WINDOW *, int, int);
static	void	auto_login(void);

struct	select	{
	int	x;
	int	len;
};

/*=======================================================================
 |
 |		接続処理
 |
 |	void	setuzoku()
 |
 =======================================================================*/
void	setuzoku(void)
{
	static	int	speed[] = {
		BLT_300, BLT_1200, BLT_2400, BLT_4800, BLT_9600
	};
	static	int	parity[] = {
		PARITY_NONE, PARITY_ODD, PARITY_EVEN
	};
	static	int	chrlen[] = {
		CHR_8, CHR_7
	};
	static	int	stplen[] = {
		STP_1, STP_1_5, STP_2
	};
	static	int	xon[] = {
		FLOW_SOFT, FLOW_NONE
	};
	RSPRM	rsprm;
	WINDOW	*wp, *wp2;
	int	sel;

	/* センター名表示 */
	wp = dsp_center(22, 4, "接続先センター選択", 1);

	/* 接続先センター選択 */
	sel = sel_center(wp, 1);

	if (sel == 10) {
		/* マニュアル設定 */
		wp2 = dsp_param(10, 3, "通信パラメータ設定");
		if (inp_param(wp2) == 0)
			sel = -1;
		w_close(wp2);
	}

	if (sel != -1) {
		/* センター情報取り出し */
		if (sel != 10)
			cprm = center[sel];

		/* ＲＳ－２３２Ｃ再オープン */
		rsprm.sync_mode = ASYNC;
		rsprm.duplex = DUPLEX_FULL;
		rsprm.blt = speed[cprm.speed];
		rsprm.parity = parity[cprm.parity];
		rsprm.chr_len = chrlen[cprm.chrlen];
		rsprm.stp_len = stplen[cprm.chrlen];
		rsprm.flow_ctrl = xon[cprm.xon];
		rsprm.dtr = DTR_ON;
		rsprm.buf_size = 8192;
		rsprm.bufp = NULL;
		rs1_reopen(&rsprm);

		/* センター名表示 */
		w_dspstr(scrp, 19, 0, C_CYAN, cprm.name);
	}

	w_close(wp);

	if (sel != -1 && cprm.tel[0] != '\0') {
		/* 回線接続処理 */
		if (auto_call() != 0) {
			setudan();
			sel = -1;
		}
	}

	if (sel != -1) {
		conxxxt = 1;		/* 接続中フラグセット */
		time(&c_time);		/* 接続時刻取り出し */
		auto_login();		/* オートログイン */
	}
}

/*=======================================================================
 |
 |		センター情報登録処理
 |
 |	void	touroku()
 |
 =======================================================================*/
void	touroku(void)
{
	WINDOW	*wp, *wp2;
	int	sel;
	FILE	*fp;

	/* センター名表示 */
	wp = dsp_center(22, 4, "センター登録", 0);

	for (;;) {
		sel = sel_center(wp, 0);	/*  センター選択 */
		if (sel != -1) {
			cprm = center[sel];
			wp2 = dsp_param(10, 3, "通信パラメータ設定");
			if (inp_param(wp2)) {	/* 通信パラメータ入力 */
				center[sel] = cprm;
				/* ファイルに保存 */
				if ((fp = fopen(FN_CNT, "wb")) != NULL) {
					fwrite(center, sizeof(center), 1, fp);
					fclose(fp);
				}
				w_close(wp2);
				w_dspstr(wp, 6, sel+3, wp->color
						, "                        ");
				w_dspstr(wp, 6, sel+3, wp->color, cprm.name);
			} else
				w_close(wp2);
		} else
			break;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		環境設定処理
 |
 |	void	settei()
 |
 =======================================================================*/
void	settei(void)
{
	static	char	*func1[] = {
		" 実行 ", NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " 中止 ", NULL
	};
	static	SCRDAT	scr[] = {
		{3, 3, "電話回線            [ ダイヤル  トーン ]"},
		{3, 4, "モデムコマンド種類  [ ＡＴコマンド  Ｖ２５ｂｉｓ ]"},
		{3, 5, "接続時補助コマンド  [                            ]"},
		{3, 6, "ｆ・２設定          [        ] [                 ]"},
		{0, 0, 0}
	};
	WINDOW	*wp;
	int	item, loop_sw, ch;
	FILE	*fp;

	dsp_func(func1);	/* ファンクションキー表示 */

	/* 環境設定ウィンドウ表示 */
	wp = w_open(10, 6, 58, 8, "環境設定", RC_WHITE);
	w_dspscr(wp, scr);

	/* 現設定値表示 */
	dsp_dial(wp, modem.dial, C_CYAN);
	dsp_modem(wp, modem.modem, C_CYAN);
	w_dspstr(wp, 25, 5, wp->color, modem.mdcmd);
	w_dspstr(wp, 25, 6, wp->color, modem.f2dsp);
	w_dspstr(wp, 36, 6, wp->color, modem.f2cmd);

	item = 0;
	loop_sw = 1;
	while (loop_sw) {
		w_color(wp, 2, item+3, 20, C_YELLOW);
		switch (item) {
		case 0:		/* ダイヤル種別設定 */
			ch = getchr();
			dsp_dial(wp, modem.dial, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				modem.dial = (modem.dial + 1) % 2;
				break;
			}
			dsp_dial(wp, modem.dial, C_CYAN);
			break;
		case 1:		/* モデム種類設定 */
			ch = getchr();
			dsp_modem(wp, modem.modem, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				modem.modem = (modem.modem + 1) % 2;
				break;
			}
			dsp_modem(wp, modem.modem, C_CYAN);
			break;
		case 2:		/* 接続時補助コマンド設定 */
			ch = w_inputs(wp, modem.mdcmd, 25, 5, 26, 0x140, wp->color);
			break;
		case 3:		/* ｆ・２設定 */ 
			ch = w_inputs(wp, modem.f2dsp, 25, 6, 6, 0x141, wp->color);
			if (ch == KEY_TAB)
				ch = w_inputs(wp, modem.f2cmd, 36, 6, 15, 0x141, wp->color);
			break;
		}
		w_color(wp, 2, item+3, 20, C_WHITE);

		switch (ch) {
		case KEY_F1:	/* 実行 */
			if ((fp = fopen(FN_ENV, "wb")) != NULL) {
				fwrite(&modem, sizeof(modem), 1, fp);
				fclose(fp);
			}
			loop_sw = 0;
			break;
		case KEY_F9:	/* 中止 */
		case KEY_ESC:
			loop_sw = 0;
			break;
		case KEY_UP:
			item = (item + 3) % 4;
			break;
		case KEY_CR:
		case KEY_DOWN:
			item = (item + 1) % 4;
			break;
		}
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		ダイヤル種別表示
 |
 |	void	dsp_dial(wp, dial, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	dial;		ダイヤル種別
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_dial(WINDOW *wp, int dial, int color)
{
	static	struct	select	sel[2] = {
		{25, 8}, {35, 6}
	};

	w_color(wp, sel[dial].x, 3, sel[dial].len, color);
}

/*=======================================================================
 |
 |		モデム種類表示
 |
 |	void	dsp_modem(wp, modem, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	modem;		モデム種類
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_modem(WINDOW *wp, int modem, int color)
{
	static	struct	select	sel[2] = {
		{25, 12}, {39, 12}
	};

	w_color(wp, sel[modem].x, 4, sel[modem].len, color);
}

/*=======================================================================
 |
 |		通信パラメータ表示処理
 |
 |	WINDOW	dsp_param(x, y, title)
 |
 |		int	x;		ウィンドウ表示桁位置
 |		int	y;		ウィンドウ表示行位置
 |		char	*title;		タイトル
 |
 |		返値			ウィンドウポインタ
 |
 =======================================================================*/
static	WINDOW	*dsp_param(int x, int y, char *title)
{
	static	SCRDAT	scr[] = {
		{3, 3, "センター名            [                          ]"},
		{3, 4, "電話番号              [                ]"},
		{3, 5, "通信速度              [ 300  1200  2400  4800  9600 ]"},
		{3, 6, "パリティー            [ 無し  奇数  偶数 ]"},
		{3, 7, "キャラクタ長          [ ８  ７ ]"},
		{3, 8, "ストップビット長      [ １  １.５  ２ ]"},
		{3, 9, "ＸＯＮ／ＸＯＦＦ      [ 有効  無効 ]"},
		{3,10, "ローカルエコーバック  [ 無し  有り ]"},
		{3,11, "リターンキー          [ ＣＲ  ＣＲ＋ＬＦ ]"},
		{3,12, "ファイル送信ﾌﾟﾛﾝﾌﾟﾄ   [    ]"},
		{3,13, "ｆ・３設定            [        ] [                 ]"},
		{3,14, "接続時補助コマンド    [                            ]"},
		{3,15, "リダイヤル間隔        [    ]"},
		{3,16, "オートログイン"},
		{3,17, "[                                                  ]"},
		{0, 0, 0}
	};
	WINDOW	*wp;

	/* ウィンドウオープン */
	wp = w_open(x, y, 58, 19, title, RC_WHITE);
	w_dspscr(wp, scr);

	/* パラメータ設定値表示 */
	w_dspstr(wp, 27, 3, wp->color, cprm.name);
	w_dspstr(wp, 27, 4, wp->color, cprm.tel);
	dsp_speed(wp, cprm.speed, C_CYAN);
	dsp_parity(wp, cprm.parity, C_CYAN);
	dsp_chrlen(wp, cprm.chrlen, C_CYAN);
	dsp_stplen(wp, cprm.stplen, C_CYAN);
	dsp_xon(wp, cprm.xon, C_CYAN);
	dsp_echo(wp, cprm.echo, C_CYAN);
	dsp_retkey(wp, cprm.retkey, C_CYAN);
	dsp_prompt(wp, cprm.prompt);
	w_dspstr(wp, 27, 13, wp->color, cprm.f3dsp);
	w_dspstr(wp, 38, 13, wp->color, cprm.f3cmd);
	w_dspstr(wp, 27, 14, wp->color, cprm.mdcmd);
	w_dspstr(wp, 27, 15, wp->color, cprm.redial);
	w_dspstr(wp, 5, 17, wp->color, cprm.login);

	return(wp);
}

/*=======================================================================
 |
 |		通信速度表示
 |
 |	void	dsp_speed(wp, speed, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	speed;		通信速度
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_speed(WINDOW *wp, int speed, int color)
{
	static	struct	select	sel[5] = {
		{27, 3}, {32, 4}, {38, 4}, {44, 4}, {50, 4}
	};

	w_color(wp, sel[speed].x, 5, sel[speed].len, color);
}

/*=======================================================================
 |
 |		パリティー表示
 |
 |	void	dsp_parity(wp, parity, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	parity;		パリティー
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_parity(WINDOW *wp, int parity, int color)
{
	static	struct	select	sel[3] = {
		{27, 4}, {33, 4}, {39, 4}
	};

	w_color(wp, sel[parity].x, 6, sel[parity].len, color);
}

/*=======================================================================
 |
 |		キャラクタ長表示
 |
 |	void	dsp_chrlen(wp, chrlen, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	chrlen;		キャラクタ長
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_chrlen(WINDOW *wp, int chrlen, int color)
{
	static	struct	select	sel[2] = {
		{27, 2}, {31, 2}
	};

	w_color(wp, sel[chrlen].x, 7, sel[chrlen].len, color);
}

/*=======================================================================
 |
 |		ストップビット長表示
 |
 |	void	dsp_stplen(wp, stplen, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	stplen;		ストップビット長
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_stplen(WINDOW *wp, int stplen, int color)
{
	static	struct	select	sel[3] = {
		{27, 2}, {31, 5}, {38, 2}
	};

	w_color(wp, sel[stplen].x, 8, sel[stplen].len, color);
}

/*=======================================================================
 |
 |		ＸＯＮ／ＸＯＦＦ有無表示
 |
 |	void	dsp_xon(wp, xon, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	xon;		ＸＯＮ／ＸＯＦＦ有無
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_xon(WINDOW *wp, int xon, int color)
{
	static	struct	select	sel[2] = {
		{27, 4}, {33, 4}
	};

	w_color(wp, sel[xon].x, 9, sel[xon].len, color);
}

/*=======================================================================
 |
 |		エコーバック有無表示
 |
 |	void	dsp_echo(wp, echo, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	echo;		エコーバック有無
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_echo(WINDOW *wp, int echo, int color)
{
	static	struct	select	sel[2] = {
		{27, 4}, {33, 4}
	};

	w_color(wp, sel[echo].x, 10, sel[echo].len, color);
}

/*=======================================================================
 |
 |		リターンキー機能表示
 |
 |	void	dsp_speed(wp, retkey, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	retkey;		リターンキー機能
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_retkey(WINDOW *wp, int retkey, int color)
{
	static	struct	select	sel[2] = {
		{27, 4}, {33, 10}
	};

	w_color(wp, sel[retkey].x, 11, sel[retkey].len, color);
}

/*=======================================================================
 |
 |		テキストファイル送信プロンプト表示
 |
 |	void	dsp_speed(wp, prompt, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	prompt;		プロンプト
 |		int	color;		表示色
 |
 =======================================================================*/
static	void	dsp_prompt(WINDOW *wp, int prompt)
{
	if (prompt == 0)
		w_dspspc(wp, 27, 12, 2);
	else if (prompt < 0x20)
		w_printf(wp, 27, 12, wp->color, "^%c", prompt + 0x40);
	else
		w_printf(wp, 27, 12, wp->color, "%c ", prompt);
}

/*=======================================================================
 |
 |		通信パラメータ入力処理
 |
 |	int	inp_param(wp)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |
 |		返値			０：中止　１：実行
 |
 =======================================================================*/
static	int	inp_param(WINDOW *wp)
{
	int	item, loop_sw, ch, jikkou;

	item = 0;
	jikkou = 0;
	loop_sw = 1;
	while (loop_sw) {
		w_color(wp, 2, item+3, 22, C_YELLOW);
		switch (item) {
		case 0:		/* センター名入力 */
			ch = w_inputs(wp, cprm.name, 27, 3, 24, 0x141, wp->color);
			break;
		case 1:		/* 電話番号入力 */
			ch = w_inputs(wp, cprm.tel, 27, 4, 14, 0x140, wp->color);
			break;
		case 2:		/* 通信速度設定 */
			ch = getchr();
			dsp_speed(wp, cprm.speed, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
				cprm.speed = (cprm.speed + 4) % 5;
				break;
			case KEY_RIGHT:
				cprm.speed = (cprm.speed + 1) % 5;
				break;
			}
			dsp_speed(wp, cprm.speed, C_CYAN);
			break;
		case 3:		/* パリティー設定 */
			ch = getchr();
			dsp_parity(wp, cprm.parity, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
				cprm.parity = (cprm.parity + 2) % 3;
				break;
			case KEY_RIGHT:
				cprm.parity = (cprm.parity + 1) % 3;
				break;
			}
			dsp_parity(wp, cprm.parity, C_CYAN);
			break;
		case 4:		/* キャラクタ長設定 */
			ch = getchr();
			dsp_chrlen(wp, cprm.chrlen, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				cprm.chrlen = (cprm.chrlen + 1) % 2;
				break;
			}
			dsp_chrlen(wp, cprm.chrlen, C_CYAN);
			break;
		case 5:		/* ストップビット長設定 */
			ch = getchr();
			dsp_stplen(wp, cprm.stplen, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
				cprm.stplen = (cprm.stplen + 2) % 3;
				break;
			case KEY_RIGHT:
				cprm.stplen = (cprm.stplen + 1) % 3;
				break;
			}
			dsp_stplen(wp, cprm.stplen, C_CYAN);
			break;
		case 6:		/* ＸＯＮ／ＸＯＦＦ有無設定 */
			ch = getchr();
			dsp_xon(wp, cprm.xon, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				cprm.xon = (cprm.xon + 1) % 2;
				break;
			}
			dsp_xon(wp, cprm.xon, C_CYAN);
			break;
		case 7:		/* エコーバック有無設定 */
			ch = getchr();
			dsp_echo(wp, cprm.echo, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				cprm.echo = (cprm.echo + 1) % 2;
				break;
			}
			dsp_echo(wp, cprm.echo, C_CYAN);
			break;
		case 8:		/* リターンキー設定 */
			ch = getchr();
			dsp_retkey(wp, cprm.retkey, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				cprm.retkey = (cprm.retkey + 1) % 2;
				break;
			}
			dsp_retkey(wp, cprm.retkey, C_CYAN);
			break;
		case 9:		/* ファイル送信待ちプロンプト設定 */
			w_loc(wp, 27, 12);
			t_loc(csr1_x, csr1_y);
			t_csron();
			ch = getchr();
			t_csroff();
			if (isalnum(ch) || ch == KEY_LF)
				cprm.prompt = ch;
			else if (ch == KEY_DEL)
				cprm.prompt = 0;
			dsp_prompt(wp, cprm.prompt);
			break;
		case 10:	/* ｆ・３設定 */
			ch = w_inputs(wp, cprm.f3dsp, 27, 13, 6, 0x141, wp->color);
			if (ch == KEY_TAB)
				ch = w_inputs(wp, cprm.f3cmd, 38, 13, 15, 0x141, wp->color);
			break;
		case 11:	/* 接続時補助コマンド設定 */
			ch = w_inputs(wp, cprm.mdcmd, 27, 14, 26, 0x140, wp->color);
			break;
		case 12:	/* リダイヤル間隔 */
			ch = w_inputs(wp, cprm.redial, 27, 15, 2, 0x142, wp->color);
			break;
		case 13:	/* オートログインコマンド設定 */
			ch = w_inputs(wp, cprm.login, 5, 17, 48, 0x140, wp->color);
			break;
		}
		w_color(wp, 2, item+3, 22, C_WHITE);

		switch (ch) {
		case KEY_F1:	/* 実行 */
			jikkou  = 1;
			loop_sw = 0;
			break;
		case KEY_F9:	/* 中止 */
		case KEY_ESC:
			loop_sw = 0;
			break;
		case KEY_UP:
			item = (item + 13) % 14;
			break;
		case KEY_CR:
		case KEY_DOWN:
			item = (item + 1) % 14;
			break;
		}
	}
	return(jikkou);
}

/*=======================================================================
 |
 |		センター名表示処理
 |
 |	WINDOW	*dsp_center(x, y, title, manual)
 |
 |		int	x;		ウィンドウ表示桁位置
 |		int	y;		ウィンドウ表示行位置
 |		char	*title;		タイトル
 |		int	manual;		０：マニュアル設定無し　１：有り
 |
 |		返値			ウィンドウポインタ
 |
 =======================================================================*/
static	WINDOW	*dsp_center(int x, int y, char *title, int manual)
{
	static	SCRDAT	scr[] = {
		{3, 3, "1:"},
		{3, 4, "2:"},
		{3, 5, "3:"},
		{3, 6, "4:"},
		{3, 7, "5:"},
		{3, 8, "6:"},
		{3, 9, "7:"},
		{3,10, "8:"},
		{3,11, "9:"},
		{3,12, "0:"},
		{0, 0, 0}
	};
	WINDOW	*wp;
	int	i;

	/* ウィンドウオープン */
	wp = w_open(x, y, 34, manual ? 15 : 14, title, RC_WHITE);

	w_dspscr(wp, scr);
	if (manual)
		w_dspstr(wp, 3, 13, wp->color, "M: マニュアル設定");

	/* センター名表示 */
	for (i = 0; i < 10; i++)
		w_dspstr(wp, 6, i+3, wp->color, center[i].name);

	return(wp);
}

/*=======================================================================
 |
 |		センター選択処理
 |
 |	int	sel_center(wp, manual)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	manual;		０：マニュアル設定無し　１：有り
 |
 |		返値			ウィンドウポインタ
 |
 =======================================================================*/
static	int	sel_center(WINDOW *wp, int manual)
{
	static	char	*func1[] = {
		" 実行 ", NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " 中止 ", NULL
	};
	static	int	sel;
	int	loop_sw, ch;

	if (manual == 0 && sel == 10)
		sel = 0;

	dsp_func(func1);
	loop_sw = 1;
	while (loop_sw) {
		w_color(wp, 2, sel+3, wp->nx-4, C_YELLOW);
		ch = getchr();
		w_color(wp, 2, sel+3, wp->nx-4, C_WHITE);
		switch (ch) {
		case KEY_CR:
		case KEY_F1:	/* 実行 */
			loop_sw = 0;
			break;
		case KEY_ESC:
		case KEY_F9:	/* 中止 */
			return(-1);
		case KEY_UP:
			if (manual)
				sel = (sel + 10) % 11;
			else
				sel = (sel + 9) % 10;
			break;
		case KEY_DOWN:
			if (manual)
				sel = (sel + 1) % 11;
			else
				sel = (sel + 1) % 10;
			break;
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			sel = ch - '1';
			break;
		case '0':
			sel = 9;
			break;
		case 'M':
		case 'm':
			if (manual)
				sel = 10;
			break;
		}
	}

	return(sel);
}

/*=======================================================================
 |
 |		電話回線接続処理
 |
 |	int	auto_call()
 |
 |		返値			０：接続成功　１：接続失敗（中止）
 |
 =======================================================================*/
static	int	auto_call(void)
{
	static	char	*func1[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " 中止 ", NULL
	};
	WINDOW	*wp;
	char	command[128], *cp, *dialp;
	int	y, err, ch, resp, interval;

	dsp_func(func1);	/* ファンクションキー表示 */

	/* ウィンドウオープン */
	wp = w_open(14, 5, 50, 15, "電話回線接続中", RC_WHITE);

	y = 2;
	interval = atoi(cprm.redial);

	/* １秒後にＣＴＳがオフだったらモデムの電源がＯＦＦとみなす */
	tm_wait(100);
	if (rs1_cts() == 0) {
		auto_call_err(wp, y, 1);
		return(1);
	}

	/* モデムコマンド編集 */
	if (modem.modem == 0) {
		/* ＡＴコマンド */
		sprintf(command, "ATZ|%s|%s|ATD%c%s"
				, modem.mdcmd, cprm.mdcmd
				, modem.dial ? 'T' : 'P', cprm.tel);
	} else {
		/* Ｖ２５ｂｉｓ */
		sprintf(command, "SET|%s|%s|CRN%s"
				, modem.mdcmd, cprm.mdcmd, cprm.tel);
	}

	cp = command;
redial:
	for (;;) {
		while (*cp == '|')
			cp++;

		dialp = cp;

		/* コマンド送信 */
		y = w_newline(wp, y);
		w_loc(wp, 3, y);
		while (*cp != '|' && *cp != '\0') {
			rs1_putc(*cp);
			putch1(*cp++, wp->color);
			tm_wait(10);
		}
		rs1_clear();
		rs1_putc(KEY_CR);

		if (*cp == '\0')
			break;

		/* 応答受信 */
		y = w_newline(wp, y);
		w_loc(wp, 3, y);
		if ((err = rcv_ack(wp)) != 0) {
			auto_call_err(wp, y, err);
			return(1);
		}

		tm_wait(50);
	}

	/* ＣＤがオンになるのを待つ */
	y = w_newline(wp, y);
	w_loc(wp, 3, y);
	resp = 0;
	tm_set(0, 6000);
	for (;;) {
		if (tm_check(0)) {
			/* タイムアウト */
			if (interval == 0) {
				auto_call_err(wp, y, 4);
				return(1);
			}
			cp = dialp;
			goto redial;
		} else if (rs1_cd()) {
			/* 接続成功 */
			rs1_clear();
			beep();
			break;
		} else if (rs1_rxc()) {
			/* 応答受信 */
			ch = rs1_get();
			if (isalnum(ch)) {
				putch1(ch, wp->color);
				resp = 1;
			} else if (ch == KEY_CR && resp != 0) {
				if (interval != 0)
					tm_set(0, interval*100);
				else
					tm_set(0, 200);
			}
		} else if (ch = inkey()) {
			/* 中止処理 */
			if (ch == KEY_ESC || ch == KEY_F9) {
				auto_call_err(wp, y, 2);
				return(1);
			}
		}
	}

	tm_stop(0);

	w_close(wp);

	return(0);
}

/*=======================================================================
 |
 |		モデム応答受信処理
 |
 |	int	rcv_ack(wp)
 |
 |		返値			０：肯定応答　≠０：否定応答（中止）
 |
 =======================================================================*/
static	int	rcv_ack(WINDOW *wp)
{
	char	buf[128];
	int	err, ch, count;

	count = 0;
	tm_set(0, 500);
	for (;;) {
		if (tm_check(0)) {
			/* 応答無し */
			err = 3;
			break;
		} else if (rs1_rxc()) {
			/* 応答受信 */
			ch = rs1_get();
			if (isalnum(ch)) {
				putch1(ch, wp->color);
				buf[count++] = ch;
			}
			if (ch == 0x0d && count != 0) {
				/* 応答データチェック */
				if (modem.modem == 0) {
					if (strncmp(buf, "OK", 2) == 0)
						err = 0;
					else
						err = 5;
				} else {
					if (strncmp(buf, "VAL", 3) == 0)
						err = 0;
					else
						err = 5;
				}
				break;
			}
		} else if (ch = inkey()) {
			/* 中止処理 */
			if (ch == KEY_ESC || ch == KEY_F9) {
				err = 2;
				break;
			}
		}
	}
	tm_stop(0);
	return(err);
}

/*=======================================================================
 |
 |		電話回線接続エラーメッセージ表示処理
 |
 |	void	auto_call_err(wp, y, err)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |
 =======================================================================*/
static	void	auto_call_err(WINDOW *wp, int y, int err)
{
	char	*msg;

	switch (err) {
	case 1:
		msg = "モデムの電源が入っていません";
		break;
	case 2:
		msg = "中止しました";
		break;
	case 3:
		msg = "モデムの応答がありません";
		break;
	case 4:
		msg = "接続できません";
		break;
	case 5:
		msg = "モデムの応答が不正です";
		break;
	default:
		msg = "";
	}
	y = w_newline(wp, y);
	w_dspstr(wp, 3, y, C_MAGENTA|A_REVERSE, msg);
	tm_wait(200);
	w_close(wp);
}

/*=======================================================================
 |
 |		時間待ち処理
 |
 |	void	tm_wait(tm)
 |
 |		int	tm;		待ち時間（１０ｍｓ単位）
 |
 =======================================================================*/
void	tm_wait(int tm)
{
	tm_set(1, tm);
	while (tm_check(1) == 0)
		dsp_ctrl(0);
}

/*=======================================================================
 |
 |		電話回線切断処理
 |
 |	void	setudan()
 |
 =======================================================================*/
void	setudan(void)
{
	rcv_text_end();
	rs1_cmd(0x00, 0xff);
	w_dspspc(scrp, 19, 0, 24);
	conxxxt = 0;
}

/*=======================================================================
 |
 |		通信パラメータ表示処理
 |
 |	void	parameter()
 |
 =======================================================================*/
void	parameter(void)
{
	WINDOW	*wp;

	t_csroff();
	wp = dsp_param(10, 3, "通信パラメータ設定値");
	getchr();
	w_close(wp);
}

/*=======================================================================
 |
 |		オートログイン処理
 |
 |	void	auto_login()
 |
 =======================================================================*/
static	void	auto_login(void)
{
	static	char	*func1[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " 中止 ", NULL
	};
	char	*cp;
	int	r_flag, ch;

	if (cprm.login[0] == '\0')
		return;

	dsp_func(func1);

	w_dspstr(scrp, 1, 23, C_YELLOW|A_REVERSE, " オートログイン中 ");

	r_flag = 0;
	for (cp = cprm.login; *cp != '\0'; ) {
		ch = inkey();
		if (ch == KEY_ESC || ch == KEY_F9)
			break;
		if (rs1_rxc()) {
			ch = rs1_get();
			putch2(ch);
			if (r_flag) {
				if (ch == *cp) {
					r_flag = 0;
					cp++;
				}
			}
		} else if (r_flag == 0) {
			switch (ch = *cp++) {
			case '_':
				tm_wait(100);
				break;
			case '%':
				r_flag = 1;
				break;
			case '|':
				ch = KEY_CR;
			default:
				rs1_putc(ch);
				if (cprm.echo) {
					putch2(ch);
				}
				tm_wait(10);
				break;
			}
		}
	}
	w_dspspc(scrp, 1, 23, 18);
}
