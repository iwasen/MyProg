/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: メイン処理				*
 *		ファイル名	: main.c				*
 *									*
 ************************************************************************/

#include "bumper.h"

/*	内部関数定義	*/
static	void	read_err(void);

static	struct key_buf	func_save; /* ファンクションキーデータセーブエリア */

/*=======================================================================
 |
 |		メイン処理
 |
 |	int	main()
 |
 =======================================================================*/
int	main()
{
	initialize();		/* 初期化処理 */

	menu();			/* メニュー画面 */

	final();		/* 終了処理 */

	return(0);
}

/*=======================================================================
 |
 |		初期化処理
 |
 |	void	initialize()
 |
 =======================================================================*/
static	void	xxx()
{
	final();
	exit(1);
}
void	initialize()
{
	signal(SIGINT, xxx);	/* ＳＴＯＰキーを無視する */

	/*set_err_handler();*/	/* エラーハンドラセット */

	t_fget(&func_save);	/* ファンクションキーラベル取り出し */
	t_fput(&key_data);	/* ファンクションキーラベル設定 */

	cputs("\x1b[>1h");	/* 最下位行ユーザ使用 */
	t_csroff();		/* カーソル消去 */

	g_init();		/* グラフィック初期化 */
	g_screen(3, 0, 0, 1);	/* スクリーンモード設定 */
	g_color(-1, -1, -1, 1);
	g_color2(0, 0x236);

	target_id = 1;
	source_id = 1;

	t_cls();
	dspstr(24, 11, "ＰＬＣレジスタ読み込み中！！ ", C_WHITE);

	putch('*');
	if (rccm(target_id, source_id, &bumper_tbl, GR_BPT, sizeof(bumper_tbl)) != 0)
		read_err();
	putch('*');
	if (rccm(target_id, source_id, &schedule_tbl, GR_TRT, sizeof(schedule_tbl)) != 0)
		read_err();
	putch('*');
	if (rccm(target_id, source_id, dolly_tbl, GR_DLT, sizeof(dolly_tbl)) != 0)
		read_err();
	putch('*');
	if (rccm(target_id, source_id, colcnv_tbl, GR_CCT, sizeof(colcnv_tbl)) != 0)
		read_err();
	putch('*');
	if (rccm(target_id, source_id, dolcnv_tbl, GR_DCT, sizeof(dolcnv_tbl)) != 0)
		read_err();
/*
	{
		FILE	*fp;

		if ((fp = fopen("BUMPER.TBL", "rb")) != NULL) {
			fread(&bumper_tbl, sizeof(bumper_tbl), 1, fp);
			fclose(fp);
		}
		if ((fp = fopen("DOLLY.TBL", "rb")) != NULL) {
			fread(dolly_tbl, 2, 500, fp);
			fclose(fp);
		}
		if ((fp = fopen("COLCNV.TBL", "rb")) != NULL) {
			fread(colcnv_tbl, 4, 30, fp);
			fclose(fp);
		}
		if ((fp = fopen("DOLCNV.TBL", "rb")) != NULL) {
			fread(dolcnv_tbl, 6, 50, fp);
			fclose(fp);
		}
	}
*/
}

void	read_err()
{
	dspstr(28, 15, "ＰＬＣレジスタ読み込み失敗", C_RED);
	getch();
	final();
	exit(1);
}

/*=======================================================================
 |
 |		終了処理
 |
 |	void	final()
 |
 =======================================================================*/
void	final()
{
	t_color(0xe1);		/* 文字色を白に設定 */

	cputs("\x1b[>1l");	/* 最下位行システム使用 */
	t_fput(&func_save);	/* ファンクションキーラベル設定 */

	t_csron();		/* カーソル表示 */

	g_color2(0, 0);
	g_cls();		/* グラフィック画面クリア */
	t_cls();		/* テキスト画面クリア */

	rs1_close();		/* ＲＳ－２３２Ｃクローズ */
}
