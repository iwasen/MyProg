/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: スケジューリングモニタ処理		*
 *		ファイル名	: schedule.c				*
 *		作成者		: s.aizawa				*
 *		作成日		: ??/??/??				*
 *									*
 ************************************************************************/

#include "bumper.h"

static	void	dsp_tbl(int);
static	void	dsp_data(int);

/*=======================================================================
 |
 |		スケジューリングモニタ処理
 |
 |	void	schedule()
 |
 =======================================================================*/
void	schedule()
{
	static	SCRDAT	scr1[] = {	/* 画面表示データ */
		{22,  1, "バ ン パ ー 塗 色 支 援 シ ス テ ム"},
		{28,  3, "スケジューリングモニタ"},
		{ 6,  7, "順列  ﾄﾞｰﾘｰNo. ｶﾗｰｺｰﾄﾞ 順列  ﾄﾞｰﾘｰNo. ｶﾗｰｺｰﾄﾞ 順列  ﾄﾞｰﾘｰNo. ｶﾗｰｺｰﾄﾞ"},
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
		NULL, NULL, NULL, NULL, NULL,
		" 前頁 ", " 次頁 ", NULL, NULL, " 終了 "
	};
	int	i, loop_sw, no, pno, page;

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_CYAN);

	/* 画面表示 */
	dspscr(scr1, C_WHITE);

	for (i = 7; i <= 18; i++)
		g_line(5*8, i*16, 74*8, i*16, C_WHITE, 0, 0);
	for (i = 5; i <=74; i += 23)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 0);
	for (i = 11; i <=74; i += 23)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);
	for (i = 20; i <=74; i += 23)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);

	/* ファンクションキー表示 */
	dsp_func(func1);

	no = 0;
	tm_ovf = 1;

	loop_sw = 1;
	while (loop_sw) {
		pno = no % 30;
		page = no / 30;

		if (tm_ovf) {		
			if (rccm(target_id, source_id, &schedule_tbl, GR_TRT, sizeof(schedule_tbl)) == 0)
				dspstr(2, 22, "実行中", C_GREEN);
			else
				dspstr(2, 22, "読み込みエラー", C_RED);
			clrtoeol();
			dsp_tbl(page);
			timset(500);
		}

		switch (inkey()) {
		case KEY_F6:	/* 前頁 */
			if (page > 0)
				page--;
			else
				page = 3;
			no = page * 30;
			dsp_tbl(page);
			break;
		case KEY_F7:	/* 次頁 */
			if (page < 3)
				page++;
			else
				page = 0;
			no = page * 30;
			dsp_tbl(page);
			break;
		case KEY_F10:
			loop_sw = 0;
			break;
		}
	}
}

static	void	dsp_tbl(int page)
{
	int	i, no;

	for (i = 0; i < 30; i++) {
		no = page * 30 + i;
		if (no < 100)
			dsp_data(no);
		else {
			t_loc(6+i/10*22, 8+i%10);
			clrtoeol();
		}
	}
}

static	void	dsp_data(int no)
{
	int	i;

	i = no % 30;
	xprintf(6+i/10*23, 8+i%10, C_WHITE, "%3d     %3d      %2d"
			, no+1
			, schedule_tbl.dolly_no[no]
			, schedule_tbl.color_code[no]);
}
