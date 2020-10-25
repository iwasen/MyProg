/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンドファイル選択画面処理		*
 *		ファイル名	: prog9.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

static	void	dsp_cmf(int);

/*=======================================================================
 |
 |		コマンドファイル選択画面処理
 |
 |	void	prog9()
 |
 =======================================================================*/
void	prog9()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ */
		{ 2, 23, msg910},
		{45, 23, msg920},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1,  6, 79,  6},
		{ 0,  1,  8, 79,  8},
		{ 0,  1, 22, 79, 22},
		{ 0, 19,  8, 19, 22},
		{-1}
	};
	int	sn = 0, cn = 0;
	int	ch, color, y, i;
	CMF_TBL	*ptr;
	MOUSE	mouse[13];

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	if (online) {
		color = C_ONLINE;
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg900);
	} else {
		color = C_OFFLINE;
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg900);
	}
	dspscr(scr1, color);

	/* 現在までに選択されたコマンドの表示 */
	cmdbuf[0] = '\0';
	dspstr(4, 5, "コマンドファイルの実行", C_GUIDE);

	/* コマンド表示 */
	dsp_cmf(sn);

	/* マウスデータセット */
	for (i = 0; i < n_cmf && i < 13; i++) {
		mouse[i].x = 2;
		mouse[i].y = i + 9;
		mouse[i].len = 76;
		mouse[i].s_code = KEY_MOUSE + i;
		mouse[i].l_code = KEY_CR;
		mouse[i].r_code = 0;
	}
	m_reg(mouse, i);

	/* ファンクションキー設定 */
	setfunc(0);

	while (prog == 9) {
		y = cn - sn + 9;	/* 選択中項目行位置 */

		/* コマンドテーブルポインタセット */
		ptr = &cmf_tbl[cn];

		/* 現在選択中のコマンド表示 */
		dspstr(4, 7, ptr->name, C_GUIDE);
		clrtoeol();
		dspstr(21, 7, ptr->guide, C_GUIDE);

		/* 選択中コマンド反転 */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);

		ch = getchr();	/* キー入力 */

		/* 選択中コマンド反転解除 */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_CR:	/* ＣＲキー */
			/* キーバッファにセット */
			set_keybuf(ptr->name);

			/* コマンド直接入力画面 */
			prog = 6;
			break;
		case KEY_ESC:	/* ＥＳＣキー */
			/* メッセージ受信画面 */
			prog = 7;
			break;
		case KEY_SFT_ESC:	/* シフト＋ＥＳＣキー */
			/* コマンド選択画面－１ */
			prog = 2;
			break;
		case KEY_HOME:	/* ＨＯＭＥキー */
		case KEY_HELP:	/* ＨＥＬＰキー */
			/* コマンド直接入力画面 */
			prog = 6;
			break;
		case KEY_UP:	/* 上矢印キー */
			/* 上のコマンドを選択 */
			if (cn > 0) {
				if (sn == cn) {
					sn--;
					dsp_cmf(sn);
				}
				cn--;
			}
			break;
		case KEY_DOWN:	/* 下矢印キー */
			/* 下のコマンドを選択 */
			if (cn + 1 < n_cmf) {
				if (cn == sn + 12) {
					sn++;
					dsp_cmf(sn);
				}
				cn++;
			}
			break;
		default:
			if ((ch & 0xff00) == KEY_MOUSE)
				cn = sn + (ch & 0xff);
			else
				beep();
			break;
		}
	}

	/* マウスデータ解除 */
	m_reg(NULL, 0);
}

/*=======================================================================
 |
 |		コマンドファイル表示処理
 |
 |	void	dsp_cmf(n)
 |
 |		int	n;		先頭のコマンドＮｏ．
 |
 =======================================================================*/
static	void	dsp_cmf(int n)
{
	int	i, y;
	CMF_TBL	*ptr;

	for (i = 0, ptr = &cmf_tbl[n]; i < 13; i++, ptr++) {
		y = i + 9;
		if (n++ < n_cmf) {
			/* コマンド名表示 */
			dspstr(4, y, ptr->name, C_GUIDE);
			clrtoeol();

			/* 説明表示 */
			dspstr(21, y, ptr->guide, C_GUIDE);
		}
	}
}
