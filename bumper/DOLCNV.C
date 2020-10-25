/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: ドーリー種コード変換テーブル保守処理	*
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
 |		ドーリー種コード変換テーブル保守画面処理
 |
 |	void	dolcnv()
 |
 =======================================================================*/
void	dolcnv()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ */
		{22,  1, "バ ン パ ー 塗 色 支 援 シ ス テ ム"},
		{23,  3, "ドーリー種コード変換テーブル保守"},
		{ 6,  7, "ｺｰﾄﾞ 車種/型  ｺｰﾄﾞ 車種/型  ｺｰﾄﾞ 車種/型  ｺｰﾄﾞ 車種/型  ｺｰﾄﾞ 車種/型"},
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
		g_line(5*8, i*16, 75*8, i*16, C_WHITE, 0, 0);
	for (i = 5; i <=75; i += 14)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 0);
	for (i = 10; i <=75; i += 14)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);

	/* ファンクションキー表示 */
	dsp_func(func1);

	dsp_tbl();

	no = 0;
	loop_sw = 1;
	while (loop_sw) {
		dspstr(2, 22, "車種／車型を入力して下さい。", C_WHITE);
		clrtoeol();
		ch = inputs(dolcnv_tbl[no], 12+no/10*14, 8+no%10, 5, 0xc0, C_WHITE);
		switch (ch) {
		case KEY_CR:
			if (no < 49)
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
			if (no + 10 < 50)
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

	for (i = 0; i < 50; i++) {
		xprintf(7+i/10*14, 8+i%10, C_WHITE, "%2d   %-5.5s", i+1, dolcnv_tbl[i]);
	}
}

static	void	f_save()
{
	FILE	*fp;

	if (kakunin("ﾄﾞｰﾘｰ種コード変換テーブルをファイルに保存します。よろしいですか？ (Y/N) ")) {
		if ((fp = fopen("DOLCNV.TBL", "wb")) != NULL) {
			fwrite(dolcnv_tbl, 6, 50, fp);
			fclose(fp);
			message("保存しました。", C_GREEN);
		}
	}
}

static	void	f_load()
{
	FILE	*fp;

	if (kakunin("ﾄﾞｰﾘｰ種ｺｰﾄﾞ変換テーブルをファイルから読み込みます。よろしいですか？ (Y/N) ")) {
		if ((fp = fopen("DOLCNV.TBL", "rb")) != NULL) {
			fread(dolcnv_tbl, 6, 50, fp);
			fclose(fp);
			dsp_tbl();
			message("読み込みました。", C_GREEN);
		}
	}
}

static	void	f_init()
{
	int	i;

	if (kakunin("ドーリー種コード変換テーブルを初期化します。よろしいですか？ (Y/N) ")) {
		for (i = 0; i < 50; i++)
			strcpy(dolcnv_tbl[i], "     ");
		dsp_tbl();
		message("初期化しました。", C_GREEN);
	}
}

static	void	f_write()
{
	if (kakunin("ﾄﾞｰﾘｰ種ｺｰﾄﾞ変換ﾃｰﾌﾞﾙをＰＬＣレジスタに書き込みます。よろしいですか？ (Y/N) ")) {
		if (wccm(target_id, source_id, dolcnv_tbl, GR_DCT, sizeof(dolcnv_tbl)) == 0)
			message("書き込みました。", C_GREEN);
		else
			message("書き込みに失敗しました。", C_RED);
	}
}

static	void	f_read()
{
	if (kakunin("ﾄﾞｰﾘｰ種ｺｰﾄﾞ変換ﾃｰﾌﾞﾙをＰＬＣﾚｼﾞｽﾀから読み込みます。よろしいですか？ (Y/N) ")) {
		if (rccm(target_id, source_id, dolcnv_tbl, GR_DCT, sizeof(dolcnv_tbl)) == 0) {
			dsp_tbl();
			message("読み込みました。", C_GREEN);
		} else
			message("読み込みに失敗しました。", C_RED);
	}
}
