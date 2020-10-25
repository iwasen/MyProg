/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンド選択画面－１処理		*
 *		ファイル名	: prog2.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*=======================================================================
 |
 |		コマンド選択画面－１処理
 |
 |	void	prog2()
 |
 =======================================================================*/
void	prog2()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ１ */
		{ 2, 21, msg210},
		{ 2, 23, msg220},
		{ 0, 0, 0}
	};
	static	SCRDAT	scr2[] = {	/* 画面表示データ２ */
		{42,  4, "１．"},
		{42,  6, "２．"},
		{42,  8, "３．"},
		{42, 10, "４．"},
		{42, 12, "５．"},
		{42, 14, msg230},
		{42, 16, "９．"},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 18, 79, 18},
		{ 0,  1, 20, 79, 20},
		{ 0,  1, 22, 79, 22},
		{ 1,  3,  3, 39, 17},
		{ 1, 41,  3, 77, 17},
		{ 0, 41,  5, 77,  5},
		{ 0, 41,  7, 77,  7},
		{ 0, 41,  9, 77,  9},
		{ 0, 41, 11, 77, 11},
		{ 3, 41, 13, 77, 13},
		{ 0, 41, 15, 77, 15},
		{-1}
	};
	static	struct	{		/* コマンド選択データ */
		int	flag;		/* 有効／無効フラグ */
		int	num;		/* コマンド種類番号 */
		int	y;		/* 桁位置 */
	} sel[7] =  {
		{0, '1', 4},
		{0, '2', 6},
		{0, '3', 8},
		{0, '4', 10},
		{0, '5', 12},
		{1, '8', 14},
		{0, '9', 16}
	};
	static	MOUSE	mouse[] = {	/* マウスデータ */
		{42, 4, 34, '1', KEY_CR, 0},
		{42, 6, 34, '2', KEY_CR, 0},
		{42, 8, 34, '3', KEY_CR, 0},
		{42,10, 34, '4', KEY_CR, 0},
		{42,12, 34, '5', KEY_CR, 0},
		{42,14, 34, '8', KEY_CR, 0},
		{42,16, 34, '9', KEY_CR, 0}
	};
	static	int	cmd = 0;	/* コマンド選択番号 */
	int	i;			/* ループカウンタ */
	int	ch;			/* キー入力文字 */
	int	color;			/* 色コード */
	CMS_TBL	*ptr;			/* コマンド選択テーブルポインタ */

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	color = online ? C_ONLINE : C_OFFLINE;
	if (mkcmd)
		xprintf(2, 1, color, msg30, csg.nsg.sys_name, cmf_name);
	else
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg200);
	dspscr(scr1, color);
	dspscr(scr2, C_GUIDE);

	/* 絵画面表示 */
	dspgrf(mkcmd ? cfg_grf : ssg_grf);

	/* コマンド選択チェック */
	for (i = 0; i < 5; i++) {
		if ((ptr = cmstbl[i]) != NULL) {
			dspstr(46, 4 + i * 2, ptr->title, C_GUIDE);
			sel[i].flag = 1;
			mouse[i].len = 34;
		} else {
			sel[i].flag = 0;
			mouse[i].len = 0;
		}
	}
	if (loctbl != NULL) {
		sel[6].flag = 1;
		dspstr(46, 16, loctbl->title, C_GUIDE);
		mouse[6].len = 34;
	} else {
		sel[6].flag = 0;
		mouse[6].len = 0;
	}

	/* 入力フィールド表示 */
	dsp_field(38, 23, 1, C_GUIDE);

	/* オフライン色取り出し */
	color = t_color_code(C_ONLINE);

	/* マウスデータセット */
	m_reg(mouse, 7);

	/* ファンクションキー設定 */
	setfunc(0);

	while (prog == 2) {
		/* コマンド種類選択 */
		t_loc(38, 23);
		t_color(t_color_code(C_GUIDE));
		putch(sel[cmd].num);
		attrib(42, sel[cmd].y, 34, A_WHITE|A_REVERSE, color|A_REVERSE);

		/* コマンド説明表示 */
		if (cmd >= 0 && cmd <= 4)
			dspstr(2, 19, cmstbl[cmd]->guide, C_GUIDE);
		else if (cmd == 5)
			dspstr(2, 19, "", C_GUIDE);
		else if (cmd == 6)
			dspstr(2, 19, loctbl->guide, C_GUIDE);
		clrtoeol();

		/* コマンド選択番号入力 */
		t_loc(38, 23);
		ch = getchr();

		/* 選択中コマンド反転解除 */
		attrib(42, sel[cmd].y, 34, A_WHITE|A_REVERSE
						, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_CR:	/* ＣＲキー */
			if (cmd >= 0 && cmd <= 4) {
				/* オンラインコマンドの実行 */
				/* コマンドテーブルポインタセット */
				cms_current = cmstbl[cmd];

				/* ローカルフラグＯＦＦ */
				local = 0;

				/* ＩＤ，コマンド名セット */
				cmdbuf[0] = csg.nsg.online_id;
				cmdbuf[1] = '\0';

				/* コマンド選択画面－２ */
				prog = 3;
			} else if (cmd == 5) {
				/* コマンドファイルの実行 */
				if (n_cmf != 0 && !mkcmd) {
					prog = 9;
				}
			} else if (cmd == 6) {
				/* ローカルコマンドの実行 */
				/* コマンドテーブルポインタセット */
				cms_current = loctbl;

				/* ＩＤ，コマンド名セット */
				cmdbuf[0] = csg.nsg.offline_id;
				cmdbuf[1] = '\0';

				/* ローカルフラグＯＮ */
				local = 1;

				/* コマンド選択画面－２ */
				prog = 3;
			}
			break;
		case KEY_ESC:	/* ＥＳＣキー */
			if (!mkcmd) {
				/* メッセージ受信画面*/
				prog = 7;
			}
			break;
		case KEY_HOME:	/* ＨＯＭＥキー */
		case KEY_HELP:	/* ＨＥＬＰキー */
			/* コマンド直接入力画面 */
			prog = 6;
			break;
		case KEY_UP:	/* 上矢印キー */
			/* 上のコマンド種類を選択 */
			do {
				cmd = (cmd + 6) % 7;
			} while (sel[cmd].flag == 0);
			break;
		case KEY_DOWN:	/* 下矢印キー */
			/* 下のコマンド種類を選択 */
			do {
				cmd = (cmd + 1) % 7;
			} while (sel[cmd].flag == 0);
			break;
		default:
			/* コマンド種類番号チェック */
			for (i = 0; i < 7; i++) {
				if (ch == sel[i].num)
					break;
			}
			if (i < 7 && sel[i].flag != 0)
				cmd = i;
			else
				beep();
			break;
		}
	}

	/* マウスデータ解除 */
	m_reg(NULL, 0);
}
