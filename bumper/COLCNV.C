/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: カラーコード変換テーブル保守処理	*
 *		ファイル名	: colcnv.c				*
 *									*
 ************************************************************************/

#include "bumper.h"

static	void	dsp_tbl(void);
static	void	f_save(void);
static	void	f_load(void);
static	void	f_init(void);
static	void	f_write(void);
static	void	f_read(void);

/*=======================================================================
 |
 |		カラーコード変換テーブル保守処理
 |
 |	void	colcnv()
 |
 =======================================================================*/
void	colcnv()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ */
		{22,  1, "バ ン パ ー 塗 色 支 援 シ ス テ ム"},
		{25,  3, "カラーコード変換テーブル保守"},
		{26,  7, "ｺｰﾄﾞ ｶﾗｰ  ｺｰﾄﾞ ｶﾗｰ  ｺｰﾄﾞ ｶﾗｰ"},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 23},
		{ 3,  1,  2, 79,  2},
		{ 1,  1,  4, 79,  4},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	static	char	*func1[] = {
		" 保存 ", " 呼出 ", "初期化", " 書込 ", " 読込 ",
		NULL, NULL, NULL, NULL, " 終了 "
	};
	int	i, loop_sw, no, ch;

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_CYAN);

	/* 画面表示 */
	dspscr(scr1, C_WHITE);

	for (i = 7; i <= 18; i++)
		g_line(25*8, i*16, 55*8, i*16, C_WHITE, 0, 0);
	for (i =25; i <=55; i += 10)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 0);
	for (i = 30; i <=55; i += 10)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);

	/* ファンクションキー表示 */
	dsp_func(func1);

	dsp_tbl();

	no = 0;
	loop_sw = 1;
	while (loop_sw) {
		dspstr(2, 22, "カラー名を入力して下さい。", C_WHITE);
		clrtoeol();
		ch = inputs(colcnv_tbl[no], 31+no/10*10, 8+no%10, 3, 0xc0, C_WHITE);
		switch (ch) {
		case KEY_CR:
			if (no < 29)
				no++;
			break;
		case KEY_UP:
			if (no % 10 != 0)
				no--;
			break;
		case KEY_DOWN:
			if (no % 10 != 9)
				no++;
			break;
		case KEY_LEFT:
			if (no - 10 >= 0)
				no -= 10;
			break;
		case KEY_RIGHT:
			if (no + 10 < 30)
				no += 10;
			break;
		case KEY_F1:	/* 保存 */
			f_save();
			break;
		case KEY_F2:	/* 呼出 */
			f_load();
			break;
		case KEY_F3:	/* 初期化 */
			f_init();
			break;
		case KEY_F4:	/* 書込 */
			f_write();
			break;
		case KEY_F5:	/* 読込 */
			f_read();
			break;
		case KEY_F10:
			loop_sw = 0;
			break;
		}
	}
}

static	void	dsp_tbl()
{
	int	i;

	for (i = 0; i < 30; i++) {
		xprintf(27+i/10*10, 8+i%10, C_WHITE, "%2d  %-3.3s", i+1, colcnv_tbl[i]);
	}
}

static	void	f_save()
{
	FILE	*fp;

	if (kakunin("ｶﾗｰｺｰﾄﾞ変換テーブルをファイルに保存します。よろしいですか？ (Y/N) ")) {
		if ((fp = fopen("COLCNV.TBL", "wb")) != NULL) {
			fwrite(colcnv_tbl, 4, 30, fp);
			fclose(fp);
			message("保存しました。", C_GREEN);
		}
	}
}

static	void	f_load()
{
	FILE	*fp;

	if (kakunin("ｶﾗｰｺｰﾄﾞ変換テーブルをファイルから読み込みます。よろしいですか？ (Y/N) ")) {
		if ((fp = fopen("COLCNV.TBL", "rb")) != NULL) {
			fread(colcnv_tbl, 4, 30, fp);
			fclose(fp);
			dsp_tbl();
			message("読み込みました。", C_GREEN);
		}
	}
}

static	void	f_init()
{
	int	i;

	if (kakunin("ｶﾗｰｺｰﾄﾞ変換テーブルを初期化します。よろしいですか？ (Y/N) ")) {
		for (i = 0; i < 30; i++)
			strcpy(colcnv_tbl[i], "   ");
		dsp_tbl();
		message("初期化しました。", C_GREEN);
	}
}

static	void	f_write()
{
	if (kakunin("ｶﾗｰｺｰﾄﾞ変換テーブルをＰＬＣレジスタに書き込みます。よろしいですか？ (Y/N) ")) {
		if (wccm(target_id, source_id, colcnv_tbl, GR_CCT, sizeof(colcnv_tbl)) == 0)
			message("書き込みました。", C_GREEN);
		else
			message("書き込みに失敗しました。", C_RED);
	}
}

static	void	f_read()
{
	if (kakunin("ｶﾗｰｺｰﾄﾞ変換ﾃｰﾌﾞﾙをＰＬＣレジスタから読み込みます。よろしいですか？ (Y/N) ")) {
		if (rccm(target_id, source_id, colcnv_tbl, GR_CCT, sizeof(colcnv_tbl)) == 0) {
			dsp_tbl();
			message("読み込みました。", C_GREEN);
		} else
			message("読み込みに失敗しました。", C_RED);
	}
}
