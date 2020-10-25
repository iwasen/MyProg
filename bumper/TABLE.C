/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: 各種テーブル保守			*
 *		ファイル名	: table.c				*
 *		作成者		: s.aizawa				*
 *		作成日		: ??/??/??				*
 *									*
 ************************************************************************/

#include "bumper.h"

/*=======================================================================
 |
 |		各種テーブル保守
 |
 |	void	table()
 |
 =======================================================================*/
void	table()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ */
		{22,  1, "バ ン パ ー 塗 色 支 援 シ ス テ ム"},
		{31,  3, "各種テーブル保守"},
		{20,  8, "１．バンパーテーブル保守"},
		{20, 11, "２．ドーリーテーブル保守"},
		{20, 14, "３．カラーコード変換テーブル保守"},
		{20, 17, "４．ドーリー種コード変換テーブル保守"},
		{ 4, 22, "処理を選択して下さい"},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 23},
		{ 3,  1,  2, 79,  2},
		{ 0,  1,  4, 79,  4},
		{ 0,  1, 21, 79, 21},
		{ 1, 18,  7, 62,  9},
		{ 1, 18, 10, 62, 12},
		{ 1, 18, 13, 62, 15},
		{ 1, 18, 16, 62, 18},
		{-1}
	};
	static	char	*func1[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, NULL, " 終了 "
	};
	int	loop_sw, ch, sel = 1;

menu:
	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_CYAN);

	/* 画面表示 */
	dspscr(scr1, C_WHITE);

	/* 入力フィールド表示 */
	dsp_field(30, 22, 1, C_CYAN);

	/* ファンクションキー表示 */
	dsp_func(func1);

	loop_sw = 1;
	while (loop_sw) {
		/* コマンド種類選択 */
		t_loc(30, 22);
		t_color(t_color_code(C_WHITE));
		putch(sel + '0');

		attrib(20, 5+sel*3, 40, A_WHITE|A_REVERSE, A_YELLOW|A_REVERSE);

		/* コマンド選択番号入力 */
		t_loc(38, 23);
		ch = getchr();

		/* 選択中コマンド反転解除 */
		attrib(20, 5+sel*3, 40, A_WHITE|A_REVERSE, A_WHITE);

		switch (ch) {
		case KEY_CR:	/* ＣＲキー */
			switch (sel) {
			case 1:		/* バンパーテーブル保守 */
				bumper();
				break;
			case 2:		/* ドーリーテーブル保守 */
				dolly();
				break;
			case 3:		/* カラーコード変換テーブル保守 */
				colcnv();
				break;
			case 4:		/* ドーリー種コード変換テーブル保守 */
				dolcnv();
				break;
			}
			goto menu;
			break;
		case KEY_ESC:	/* ＥＳＣキー */
			break;
		case KEY_UP:	/* 上矢印キー */
			if (sel > 1)
				sel--;
			else
				sel = 4;
			break;
		case KEY_DOWN:	/* 下矢印キー */
			if (sel < 4)
				sel++;
			else
				sel = 1;
			break;
		case KEY_F10:
			loop_sw = 0;
			break;
		default:
			if (ch >= '1' && ch <= '4')
				sel = ch - '0';
			break;
		}
	}
}
