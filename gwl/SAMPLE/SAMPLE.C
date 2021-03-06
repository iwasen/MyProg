/************************************************************************
 *									*
 *		グラフィックウィンドウライブラリ			*
 *			サンプルプログラム				*
 *									*
 ************************************************************************/

/*--------------------------------------------------------------*/
/*	標準インクルードファイル 				*/
/*--------------------------------------------------------------*/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dos.h>

/*--------------------------------------------------------------*/
/*	グラフィックウィンドウライブラリ用インクルードファイル	*/
/*	必ずインクルードすること				*/
/*--------------------------------------------------------------*/
#include "gwlib.h"

/*--------------------------------------------------------------*/
/*	モードの定義		 				*/
/*--------------------------------------------------------------*/
#define	M_MAKE	0
#define	M_DEL	1
#define	M_PICK	2
#define	M_MOVE	3
#define	M_END	6

/*--------------------------------------------------------------*/
/*	内部関数の定義		 				*/
/*--------------------------------------------------------------*/
static	void	menu(void);
static	void	title(void);
static	void	make(int);
static	int	set_color_c(void);
static	int	set_color_w(void);

/*--------------------------------------------------------------*/
/*	ウィンドウ内に表示するデータ 				*/
/*--------------------------------------------------------------*/
static	char	*mstr[] = {
	"1 MSDOS.SYS",
	"2 IO.SYS",
	"3 COMMAND.COM",
	"4 CONFIG.SYS",
	"5 AUTOEXEC.BAT",
	"6 FORMAT.EXE",
	"7 ASSIGN.EXE",
	"8 DISKCOPY.EXE",
};

/*--------------------------------------------------------------*/
/*	スタティック変数の定義	 				*/
/*--------------------------------------------------------------*/
static	int	mode;			/* 現在のモード */
static	int	color_w = C_WHITE;	/* 文字色 */
static	int	color_c = C_BLACK;	/* 背景色 */

/*=======================================================================
 *
 *		メイン処理
 *
 *	void	main()
 *
 =======================================================================*/
main(int argc, char *argv[])
{
	void	menu(), make();
	WINDOW	*wp;
	int	loop_sw, mouse;
	int	n = 0;

	/* パラメータチェック */
	switch (argc) {
	case 1:
		mouse = 0;
		break;
	case 2:
		if (strcmp(argv[1], "1") == 0)
			mouse = 1;
		else {
			printf("パラメータが違います．\n");
			exit(1);
		}
		break;
	default:
		printf("パラメータの数が違います．\n");
		exit(1);
	}

	w_initscr(8, 16, mouse, "ANK.FNT");	/* 初期化処理 */
/*                �E   �E    �E        �E
                  �E   �E    �E        �W�C�C�C ＡＮＫフォントファイル名
                  �E   �E    �W�C�C�C�C�C�C�C�C�C�C�C�C マウスドライバ種別
                  �E   �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C １行のドット数
                  �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C １桁のドット数
*/
	title();		/* タイトル表示 */

	loop_sw = 1;
	while (loop_sw) {
		switch (w_input(scrp, 0)) {	/* １文字入力 */
/*                               �E    �E
                                 �E    �W�C�C�C�C 文字カーソル表示なし
                                 �W�C�C�C�C�C�C�C�C�C 全画面ポインタ
*/
		case MOUSE_L:
			switch (mode) {
			case M_MAKE:
				make(++n);
				break;
			case M_DEL:
				if ((wp = w_check()) != 0)
					w_close(wp);
				break;
			case M_PICK:
				if ((wp = w_check()) != 0)
					w_popup(wp);
				break;
			case M_MOVE:
				if ((wp = w_check()) != 0)
					w_xmove(wp);
				break;
			}
			break;
		case MOUSE_R:
			menu();
			if (mode == M_END)
				loop_sw = 0;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		}
	}

	w_endscr();		/* 終了処理 */

	return(0);
}
/*=======================================================================
 *
 *		タイトル表示
 *
 *	void	title()
 *
 =======================================================================*/
static	void	title(void)
{

	static	char	tile[12] = {
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa,
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa
	};

	g_line(0, 0, 639, 399, 0, 2, 2, 0, 12, tile);

	set_color(C_BLUE, C_WHITE);	/* 色設定 */
/*                  �E        �E
                    �E        �W�C�C�C 背景色（白）
                    �W�C�C�C�C�C�C�C�C�C�C�C�C 文字色（青）
*/
	/* 文字列表示 */
	w_dspstr(scrp, 18, 0, "*** GRAPHIC WINDOW LIBRARY DEMONSTRATION ***");
/*                �E     �E  �E     �E
                  �E     �E  �E     �W�C�C�C 表示文字列
                  �E     �E  �W�C�C�C�C�C�C�C�C�C 行位置
                  �E     �W�C�C�C�C�C�C�C�C�C�C�C�C 桁位置
                  �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C 全画面ポインタ
*/
}

/*=======================================================================
 *
 *		メニュー処理
 *
 *	void	menu()
 *
 =======================================================================*/
static	void	menu(void)
{
	static	char	title[] = "メニュー";
	static	char	*item[] = {
		"作    成",		/* 0 */
		"消    去",		/* 1 */
		"引き上げ",		/* 2 */
		"移    動",		/* 3 */
		"文 字 色",		/* 4 */
		"背 景 色",		/* 5 */
		"終    了"		/* 6 */
	};
	WINDOW	*wp;
	static	int	sel = 0;
	int	csel;
	int	loop_sw;
	int	sx, sy;

	sx = m_gx - 13 * 8 / 2;
	sy = m_gy - 10 * 18 / 2;

	/* ウィンドウオープン */
	if ((wp = w_open(sx, sy, 13, 9, 8, 18)) == 0) {
/*                        �E   �E   �E  �E  �E   �E
                          �E   �E   �E  �E  �E   �W�C�C�C １行のドット数
                          �E   �E   �E  �E  �W�C�C�C�C�C�C�C １桁のドット数
                          �E   �E   �E  �W�C�C�C�C�C�C�C�C�C�C 行数
                          �E   �E   �W�C�C�C�C�C�C�C�C�C�C�C�C�C 桁数
                          �E   �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C ウィンドウ左上のＹ座標
                          �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C ウィンドウ左上のＸ座標
*/
		mode = M_DEL;
		return;
	}

	/* ウィンドウ表示 */
	w_stdwin(wp, title, C_BLACK, C_YELLOW, C_BLACK);
/*                �E    �E      �E        �E         �E
                  �E    �E      �E        �E         �W�C�C�C 影の色（黒）
                  �E    �E      �E        �W�C�C�C�C�C�C�C�C�C�C�C�C�C 背景色（黄）
                  �E    �E      �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C 文字色（黒）
                  �E    �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C タイトル
                  �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C ウィンドウポインタ
*/
	loop_sw = 1;
	while (loop_sw) {
		/* 処理選択 */
		csel = w_select(wp, item, 1, 7, sel, C_BLACK, C_BLUE);
/*                              �E    �E    �E  �E   �E     �E        �E
                                �E    �E    �E  �E   �E     �E        �W�C�C�C 選択時の色
                                �E    �E    �E  �E   �E     �W�C�C�C 文字色
                                �E    �E    �E  �E   �W�C�C�C 最初に選択されている項目
                                �E    �E    �E  �W�C�C�C�C�C�C�C 縦方向の項目数
                                �E    �E    �W�C�C�C�C�C�C�C�C�C�C 横方向の項目数
                                �E    �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C 項目文字列
                                �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C ウィンドウポインタ
*/
		switch (csel) {       
		case 0:		/* 作成 */
		case 1:		/* 消去 */
		case 2:		/* 引き上げ */
		case 3:		/* 移動 */
		case 6:		/* 終了 */
			mode = csel;
			loop_sw = 0;
			break;
		case 4:		/* 文字色 */
			if (set_color_c() != -1)
				loop_sw = 0;
			break;
		case 5:		/* 背景色 */
			if (set_color_w() != -1)
				loop_sw = 0;
			break;
		case -1:
			loop_sw = 0;
			break;
		}

		if (csel != -1)
			sel = csel;
	}

	w_close(wp);	/* ウィンドウクローズ */
/*               �E
                 �W�C�C�C ウィンドウポインタ
*/
}

/*=======================================================================
 *
 *		ウィンドウ作成
 *
 *	void	make()
 *
 =======================================================================*/
static	void	make(int n)
{
	int	i;
	WINDOW	*wp;
	char	title[20];

	/* ウィンドウオープン */
	if ((wp = w_open(m_gx, m_gy, 20, 10, 8, 18)) == 0)
		return;

	/* ウィンドウ表示 */
	sprintf(title, "WINDOW%d", n);
	w_stdwin(wp, title, C_BLACK, color_w, C_BLACK);

	/* ウィンドウ内に文字表示 */
	set_color(color_c, color_w);
	for (i = 0; i < 8; i++)
		w_dspstr(wp, 2, i+1, mstr[i]);
}

/*=======================================================================
 *
 *		文字色設定
 *
 *	int	set_color_c()
 *
 =======================================================================*/
static	int	set_color_c(void)
{
	static	char	title[] = "文字色";
	static	char	*item[] = {
		"白",
		"黄",
		"シアン",
		"緑",
		"マゼンタ",
		"赤",
		"青",
		"黒"
	};
	static	int	sel = 0;
	int	csel;
	WINDOW	*wp;

	/* 文字色選択ウィンドウオープン */
	if ((wp = w_open(m_gx, m_gy, 13, 10, 8, 18)) == 0)
		return(-1);

	/* ウィンドウ表示 */
	w_stdwin(wp, title, C_WHITE, C_MAGENTA, C_BLACK);

	/* 文字色選択 */
	csel = w_select(wp, item, 1, 8, sel, C_WHITE, C_CYAN);
	switch (csel) {
	case 0:		/* 白 */
		color_c = C_WHITE;
		break;
	case 1:		/* 黄 */
		color_c = C_YELLOW;
		break;
	case 2:		/* シアン */
		color_c = C_CYAN;
		break;
	case 3:		/* 緑 */
		color_c = C_GREEN;
		break;
	case 4:		/* マゼンタ */
		color_c = C_MAGENTA;
		break;
	case 5:		/* 赤 */
		color_c = C_RED;
		break;
	case 6:		/* 青 */
		color_c = C_BLUE;
		break;
	case 7:		/* 黒 */
		color_c = C_BLACK;
		break;
	}

	if (csel != -1)
		sel = csel;

	w_close(wp);	/* ウィンドウクローズ */

	return(csel);
}

/*=======================================================================
 *
 *		背景色設定
 *
 *	int	set_color_w()
 *
 =======================================================================*/
static	int	set_color_w(void)
{
	static	char	title[] = "背景色";
	static	char	*item[] = {
		"白",
		"黄",
		"シアン",
		"緑",
		"マゼンタ",
		"赤",
		"青",
		"黒"
	};
	static	int	sel = 0;
	int	csel;
	WINDOW	*wp;

	/* 背景色選択ウィンドウオープン */
	if ((wp = w_open(m_gx, m_gy, 13, 10, 8, 18)) == 0)
		return(-1);

	/* ウィンドウ表示 */
	w_stdwin(wp, title, C_BLACK, C_CYAN, C_BLACK);

	/* 背景色選択 */
	csel = w_select(wp, item, 1, 8, sel, C_BLACK, C_MAGENTA);
	switch (csel) {
	case 0:		/* 白 */
		color_w = C_WHITE;
		break;
	case 1:		/* 黄 */
		color_w = C_YELLOW;
		break;
	case 2:		/* シアン */
		color_w = C_CYAN;
		break;
	case 3:		/* 緑 */
		color_w = C_GREEN;
		break;
	case 4:		/* マゼンタ */
		color_w = C_MAGENTA;
		break;
	case 5:		/* 赤 */
		color_w = C_RED;
		break;
	case 6:		/* 青 */
		color_w = C_BLUE;
		break;
	case 7:		/* 黒 */
		color_w = C_BLACK;
		break;
	}

	if (csel != -1)
		sel = csel;

	w_close(wp);	/* ウィンドウクローズ */

	return(csel);
}
