/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンド選択画面－２処理		*
 *		ファイル名	: prog3.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	内部関数	*/
static	void	dspcmd(int);

/*=======================================================================
 |
 |		コマンド選択画面－２処理
 |
 |	void	prog3()
 |
 =======================================================================*/
void	prog3()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ */
		{ 2, 23, msg310},
		{45, 23, msg320},
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
	int	sn = 0;		/* 先頭コマンド番号 */
	int	cn = 0;		/* カレントコマンド番号 */
	int	n;		/* コマンド数 */
	int	ch;		/* キー入力文字 */
	int	color;		/* 色コード */
	int	y;		/* 選択項目行位置 */
	int	i;		/* ループカウンタ */
	CMD_TBL	*ptr;		/* コマンドテーブルポインタ */
	MOUSE	mouse[13];	/* マウスデータ */

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	color = online ? C_ONLINE : C_OFFLINE;
	if (mkcmd)
		xprintf(2, 1, color, msg30, csg.nsg.sys_name, cmf_name);
	else if (local)
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg301);
	else
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg300);
	dspscr(scr1, color);

	/* 現在までに選択されたコマンドの表示 */
	cmdbuf[1] = '\0';
	dspstr(4, 3, cmdbuf, C_GUIDE);
	dspstr(4, 5, cms_current->guide, C_GUIDE);

	/* コマンド数取り出し */
	n = cms_current->n;

	/* コマンド表示 */
	dspcmd(sn);

	/* マウスデータセット */
	for (i = 0; i < n && i < 13; i++) {
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

	while (prog == 3) {
		y = cn - sn + 9;	/* 選択中項目行位置 */

		/* コマンドテーブルポインタセット */
		ptr = &cms_current->ptr[cn];

		/* 現在選択中のコマンド表示 */
		dspstr(8, 7, ptr->name, C_GUIDE);
		dspstr(21, 7, ptr->guide, C_GUIDE);
		clrtoeol();

		/* 選択中コマンド反転 */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);

		ch = getchr();	/* キー入力 */

		/* 選択中コマンド反転解除 */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_CR:	/* ＣＲキー */
			/* コマンド名をバッファにセット */
			strcpy(&cmdbuf[1], ptr->name);

			if (ptr->n != 0) {
				/* パラメータ有り */
				/* コマンドテーブルポインタセット */
				cmd_current = ptr;

				if (ptr->ptr->name != NULL) {
					/* コマンド入力画面－１ */
					prog = 4;
				} else {
					prm_current = ptr->ptr;
					/* コマンド入力画面－２ */
					prog = 5;
				}
			} else {
				/* パラメータ無し */
				/* コマンド終了マークセット */
				strcat(cmdbuf, "/");

				/* キーバッファにセット */
				set_keybuf(cmdbuf);

				/* コマンド直接入力画面 */
				prog = 6;
			}
			break;
		case KEY_ESC:	/* ＥＳＣキー */
			/* メッセージ受信画面 */
			prog = 7;
			break;
		case KEY_SFT_ESC:	/* シフト＋ＥＳＣキー */
			if (online) {
				/* コマンド選択画面－１ */
				prog = 2;
			} else {
				/* コマンドコネクション開設画面 */
				prog = 1;
			}
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
					dspcmd(sn);
				}
				cn--;
			}
			break;
		case KEY_DOWN:	/* 下矢印キー */
			/* 下のコマンドを選択 */
			if (cn + 1 < n) {
				if (cn == sn + 12) {
					sn++;
					dspcmd(sn);
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
 |		コマンド表示処理
 |
 |	void	dspcmd(n)
 |
 |		int	n;		先頭のコマンドＮｏ．
 |
 =======================================================================*/
static	void	dspcmd(int n)
{
	int	i;		/* ループカウンタ */
	int	y;		/* 表示行位置 */
	CMD_TBL	*ptr;		/* コマンド選択テーブルポインタ */

	for (i = 0, ptr = &cms_current->ptr[n]; i < 13; i++, ptr++) {
		y = i + 9;
		if (n++ < cms_current->n) {
			/* コマンド名表示 */
			dspstr(8, y, ptr->name, C_GUIDE);

			/* 説明表示 */
			dspstr(21, y, ptr->guide, C_GUIDE);
			clrtoeol();
		}
	}
}
