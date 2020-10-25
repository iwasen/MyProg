/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンドコネクション開設画面処理	*
 *		ファイル名	: prog1.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	内部関数	*/
static	int	sel_command(int);
static	int	inp_conxxxtion(int);
static	int	inp_password(char *);

/*=======================================================================
 |
 |		コマンドコネクション開設画面処理
 |
 |	void	prog1()
 |
 =======================================================================*/
void	prog1()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ１ */
		{ 2, 19, msg110},
		{ 2, 21, msg120},
		{ 2, 23, msg130},
		{ 0, 0, 0}
	};
	static	SCRDAT	scr2[] = {	/* 画面表示データ２ */
		{42,  4, msg140},
		{42,  8, msg150},
		{42, 16, msg160},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 18, 79, 18},
		{ 0,  1, 20, 79, 20},
		{ 0,  1, 22, 79, 22},
		{ 1,  3,  3, 39, 17},
		{ 1, 41,  3, 77,  5},
		{ 1, 41,  7, 77,  9},
		{ 1, 41, 15, 77, 17},
		{-1}
	};
	static	int	cmd = 0;	/* コマンド種類番号 */

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	xprintf(2, 1, C_OFFLINE, msg10, csg.nsg.sys_name, msg100);
	dspscr(scr1, C_OFFLINE);
	dspscr(scr2, C_GUIDE);
	dspgrf(csg_grf);

	/* 入力フィールド表示 */
	dsp_field(38, 19,  1, C_GUIDE);
	dsp_field(38, 21,  2, C_GUIDE);
	dsp_field(38, 23, 10, C_GUIDE);

	online = 0;		/* オンラインフラグクリア */
	while (prog == 1) {
		cmd = sel_command(cmd);	/* コマンド種類選択 */
		switch (cmd) {
		case 0:		/* ＶＳＥシステムの保守 */
		case 1:		/* 遠隔ＶＳＥシステムの保守 */
			/* コネクション番号入力 */
			if (inp_conxxxtion(cmd) == -1)
				break;

			/* パスワード入力 */
			if (inp_password(password) == -1)
				break;

			/* コネクション処理 */
			dsp_loc_msg(LOC_MSG_CONxxxT, ssg.nsg.conxxxtion);
			if (conxxxt(1) == 0) {
				find_cmf();
				prog = 2;
			}
			break;
		case 2:		/* ローカルコマンドの選択 */
			/* コマンドテーブルポインタセット */
			cms_current = loctbl;

			/* ＩＤ，コマンド名セット */
			cmdbuf[0] = csg.nsg.offline_id;
			cmdbuf[1] = '\0';

			/* ローカルフラグＯＮ */
			local = 1;

			/* コマンド選択画面－２ */
			prog = 3;
			break;
		}
	}
}

/*=======================================================================
 |
 |		コマンド選択処理
 |
 |	int	sel_command(cmd)
 |
 |		int	cmd;	最初に選択されているコマンド番号
 |
 |		返値		コマンド選択番号
 |
 =======================================================================*/
static	int	sel_command(int cmd)
{
	static	struct	{
		int	num;	/* コマンド番号 */
		int	y;	/* 行位置 */
	} sel[3] =  {
		{'1', 4},
		{'2', 8},
		{'9', 16}
	};
	static	MOUSE	mouse[] = {	/* マウスデータ */
		{42, 4, 34, '1', KEY_CR, 0},
		{42, 8, 34, '2', KEY_CR, 0},
		{42,16, 34, '9', KEY_CR, 0}
	};
	int	ch;		/* キー入力文字 */
	int	i;		/* ループカウンタ */
	int	color;		/* 色コード */

	/* 文字色セット */
	t_color(t_color_code(C_GUIDE));

	/* オフライン色取り出し */
	color = t_color_code(C_OFFLINE);

	/* マウスデータセット */
	m_reg(mouse, 3);

	/* ファンクションキー設定 */
	setfunc(0);

	for (;;) {
		/* コマンド番号表示 */
		t_loc(38, 19);
		putch(sel[cmd].num);

		/* 選択中コマンド反転 */
		attrib(42, sel[cmd].y, 34, A_WHITE|A_REVERSE, color|A_REVERSE);

		/* コマンド番号入力 */
		t_loc(38, 19);
		t_csron();
		ch = getchr();
		t_csroff();

		/* ＣＲキーだったらリターン */
		if (ch == KEY_CR)
			break;

		/* 選択中コマンド反転解除 */
		attrib(42, sel[cmd].y, 34, A_WHITE|A_REVERSE
						, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_UP:	/* 上矢印キー */
			cmd = (cmd + 2) % 3;
			break;
		case KEY_DOWN:	/* 下矢印キー */
			cmd = (cmd + 1) % 3;
			break;
		default:
			/* 入力文字チェック */
			for (i = 0; i < 3; i++) {
				if (ch == sel[i].num)
					break;
			}
			if (i < 3)
				cmd = i;
			else
				beep();
			break;
		}
	}

	/* マウスデータ解除 */
	m_reg(NULL, 0);

	return(cmd);
}

/*=======================================================================
 |
 |		コネクション番号入力処理
 |
 |	int	inp_conxxxtion(cmd)
 |
 |		int	cmd;	コマンド番号
 |
 |		返値		コネクション番号
 |				（ＥＳＣキーが押された場合、－１）
 |
 =======================================================================*/
static	int	inp_conxxxtion(int cmd)
{
	char	buf[3];		/* コネクション番号入力バッファ */
	int	loop_sw;	/* ループ制御スイッチ */
	int	ch;		/* キー入力文字 */
	int	con;		/* コネクション番号 */

	/* ファンクションキー設定 */
	setfunc(0);

	loop_sw = 1;
	while (loop_sw) {
		/* コネクション番号入力 */
		ch = inputs(buf, 38, 21, 2, 0x312, C_GUIDE);
		switch (ch) {
		case KEY_CR:	/* ＣＲキー */
			/* 番号チェック */
			if (strcmp(buf, "  ") != 0) {
			    con = atoi(buf);
			    if ((cmd == 0 && (con >= 0 && con <= 1))
				|| (cmd == 1 && (con >= 2 && con <= 63))) {
				/* システムＳＧファイル検索 */
				if (find_ssg(con) == 0) {
					/* システムＳＧファイル読み込み */
					read_ssg();
					loop_sw = 0;
					break;
				}
			    }
			}

			/* エラーメッセージ表示 */
			beep();
			dspstr(62, 21, err10, C_ERR);
			attrib(62, 21, 14, A_REVERSE, A_REVERSE);
			break;
		case KEY_ESC:	/* ＥＳＣキー */
			con = -1;
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	/* エラーメッセージ消去 */
	dspspc(62, 21, 14);

	return(con);
}

/*=======================================================================
 |
 |		パスワード入力処理
 |
 |	int	inp_password(pwd)
 |
 |		char	*pwd;	パスワード
 |
 |		返値		０：リターンキー　－１：ＥＳＣキー	
 |
 =======================================================================*/
static	int	inp_password(char *pwd)
{
	int	loop_sw;	/* ループ制御スイッチ */
	int	ch;		/* キー入力文字 */
	int	st;		/* リターンステータス */

	loop_sw = 1;
	while (loop_sw) {
		/* パスワード入力 */
		ch = inputs(pwd, 38, 23, 10, 0x314, C_GUIDE);
		switch (ch) {
		case KEY_CR:	/* ＣＲキー */
			st = 0;
			loop_sw = 0;
			break;
		case KEY_ESC:	/* ＥＳＣキー */
			st = -1;
			loop_sw = 0;
			break;
		}
	}
	return(st);
}
