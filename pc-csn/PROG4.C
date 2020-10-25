/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンド入力画面－１処理		*
 *		ファイル名	: prog4.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

static	void	dspprm(int);

/*=======================================================================
 |
 |		コマンド入力画面－１処理
 |
 |	void	prog4()
 |
 =======================================================================*/
void	prog4()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ */
		{ 2, 23, msg410},
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
	int	sn = 0;		/* 先頭パラメータ番号 */
	int	cn = 0;		/* カレントパラメータ番号 */
	int	n;		/* パラメータ種類数 */
	int	ch;		/* キー入力文字 */
	int	color;		/* 色コード */
	int	y;		/* 選択項目行位置 */
	int	i;		/* ループカウンタ */
	PRM_TBL	*ptr;		/* パラメータ種類テーブルポインタ */
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
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg401);
	else
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg400);
	dspscr(scr1, color);

	/* 現在までに選択されたコマンドの表示 */
	cmdbuf[4] = '\0';
	dspstr(4, 3, cmdbuf, C_GUIDE);
	dspstr(4, 5, cmd_current->guide, C_GUIDE);

	/* パラメータ種類数取り出し */
	n = cmd_current->n;

	/* パラメータ表示 */
	dspprm(sn);

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

	while (prog == 4) {
		y = cn - sn + 9;	/* 選択中項目行位置 */

		/* パラメータ種類テーブルポインタセット */
		ptr = &cmd_current->ptr[cn];

		/* 現在選択中のコマンド表示 */
		dspstr(8, 7, ptr->name, C_GUIDE);
		dspstr(21, 7, ptr->guide, C_GUIDE);
		clrtoeol();

		/* 選択中パラメータ種類反転 */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);

		ch = getchr();	/* キー入力 */

		/* 選択中パラメータ種類反転解除 */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_CR:	/* ＣＲキー */
			/* パラメータ種類名をバッファにセット */
			cmdbuf[4] = ' ';
			strcpy(&cmdbuf[5], ptr->name);

			if (ptr->n != 0) {
				/* パラメータ有り */
				/* パラメータ種類テーブルポインタセット */
				prm_current = ptr;
				prog = 5;
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
			/* コマンド選択画面－２ */
			prog = 3;
			break;
		case KEY_HOME:	/* ＨＯＭＥキー */
		case KEY_HELP:	/* ＨＥＬＰキー */
			/* コマンド直接入力画面 */
			prog = 6;
			break;
		case KEY_UP:	/* 上矢印キー */
			/* 上のパラメータ種類を選択 */
			if (cn > 0) {
				if (sn == cn) {
					sn--;
					dspprm(sn);
				}
				cn--;
			}
			break;
		case KEY_DOWN:	/* 下矢印キー */
			/* 下のパラメータ種類を選択 */
			if (cn + 1 < n) {
				if (cn == sn + 12) {
					sn++;
					dspprm(sn);
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
 |		パラメータ種類表示処理
 |
 |	void	dspprm(n)
 |
 |		int	n;		先頭のパラメータ種類Ｎｏ．
 |
 =======================================================================*/
static	void	dspprm(int n)
{
	int	i;		/* ループカウンタ */
	int	y;		/* 表示行位置 */
	PRM_TBL	*ptr;		/* パラメータ種類テーブルポインタ */

	for (i = 0, ptr = &cmd_current->ptr[n]; i < 13; i++, ptr++) {
		y = i + 9;
		if (n++ < cmd_current->n) {
			/* パラメータ名表示 */
			dspstr(8, y, ptr->name, C_GUIDE);

			/* 説明表示 */
			dspstr(21, y, ptr->guide, C_GUIDE);
			clrtoeol();
		}
	}
}
