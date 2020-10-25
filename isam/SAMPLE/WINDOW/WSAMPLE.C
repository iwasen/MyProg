/************************************************************************
 *									*
 *		ＩＳＡＭライブラリ					*
 *									*
 *		名称		: ウィンドウ版サンプルプログラム	*
 *		ファイル名	: wsample.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>
#include "isam.h"
#include "gwlib.h"

static	WINDOW	*dwp;
static	void	err_proc(int);

static	void	t_search(DB *);
static	void	inp_field(WINDOW *, int);
static	void	t_store(DB *);
static	void	t_update(DB *);
static	void	t_delete(DB *);
static	void	t_list(DB *);
static	void	t_ichiran(DB *);
static	void	t_info(DB *);
static	void	t_info_dbf(DBF *);
static	void	t_info_idx(IDX *);
static	void	t_locate(DB *);
static	void	t_etc(DB *);
static	void	t_sort(DB *);
static	void	t_pack(DB *);
static	void	t_reindex(DB *);
static	void	t_chgidx(DB *);
static	void	t_count(DB *);
static	void	t_recall(DB *);
static	void	del_mode(void);
static	void	t_flush(DB *);
static	DB	*t_dbf_create(char *);
static	int	t_idx_create(DB *, char *);
static	int	sel_key(DBF *, char *);
static	void	display(WINDOW *, DB *, int);
static	void	display2(DB *);
static	void	w_cls(WINDOW *);
static	int	jikkou(WINDOW *);
static	void	err_proc(int);
static	void	title(void);
static	void	stop_handler(void);

static	DB	*sv_dbp;

/*=======================================================================
 |
 |		メイン処理
 |
 |	int	main(argc, argv)
 |
 |		int	argc;		パラメータ数
 |		char	*argv[];	コマンドラインパラメータ
 |
 =======================================================================*/
main(int argc, char *argv[])
{
	char	dbf_file_name[32];
	char	idx_file_name[32];
	DB	*dbp;
	DBF	*dp;
	FIELD	*flp;
	int	loop_sw;
	int	max_length, i;
	int	csel;
	WINDOW	*wp;
	static	int	sel;
	static	char	*item[] = {
		"検  索",
		"追  加",
		"更  新",
		"削  除",
		"表  示",
		"位  置",
		"その他",
		"終  了"
	};

	/* パラメータ数チェック */
	if (argc < 2) {
		printf("使い方：ＷＳＡＭＰＬＥ　データファイル名　［インデックスファイル名　．．．］\n");
		exit(1);
	}

	w_initscr(8, 18, 1, "ANK.FNT");		/* 初期化 */

	signal(SIGINT, stop_handler);	/* ＳＴＯＰキーで処理中止 */

	title();		/* タイトル表示 */

	/* ｄｂｆファイルオープン */
	argc--;
	strcpy(dbf_file_name, *++argv);
	if (strchr(dbf_file_name, '.') == NULL)
		strcat(dbf_file_name, ".DBF");
	if ((dbp = db_open(dbf_file_name)) == NULL) {
		if ((dbp = t_dbf_create(dbf_file_name)) == NULL) {
			w_endscr();
			exit(1);
		}
	}
	sv_dbp = dbp;

	/* インデックスファイルオープン */
	while (--argc) {
		strcpy(idx_file_name, *++argv);
		if (strchr(idx_file_name, '.') == NULL)
			strcat(idx_file_name, ".NDX");
		if (db_index(dbp, idx_file_name) == 0) {
			if (t_idx_create(dbp, idx_file_name) == 0) {
				db_close(dbp);
				w_endscr();
				exit(1);
			}
		}
	}

	db_err_handler(err_proc);	/* エラー処理ルーチン設定 */

	/* メニューウィンドウオープン */
	wp = w_open(100, 70, 11, 10, 8, 18);
	w_stdwin(wp, "MENU", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* カレントレコードウィンドウオープン */
	dp = dbp->dp;
	max_length = 7;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		if (flp->l_field > max_length)
			max_length = flp->l_field;
	}
	dwp = w_open(200, 100, max_length+16, dp->n_field + 2, 8, 18);
	w_stdwin(dwp, "カレントレコード", C_BLACK, C_WHITE, C_BLACK);
	dwp->flag = 0x01;

	display2(dbp);			/* カレントレコード表示 */

	loop_sw = 1;
	while (loop_sw) {
		csel = w_select(wp, item, 1, 8, sel, C_BLACK, C_BLUE);
		switch (csel) {
		case 0:		/* 検索 */
			t_search(dbp);
			break;
		case 1:		/* 追加 */
			t_store(dbp);
			break;
		case 2:		/* 更新 */
			t_update(dbp);
			break;
		case 3:		/* 削除 */
			t_delete(dbp);
			break;
		case 4:		/* 表示 */
			t_list(dbp);
			break;
		case 5:		/* 位置 */
			t_locate(dbp);
			break;
		case 6:		/* その他 */
			t_etc(dbp);
			break;
		case 7:		/* 終了 */
			loop_sw = 0;
			break;
		}

		sel = csel;

		w_popup(wp);
		if (w_check() == wp)
			sel = -1;
	}

	db_close(dbp);

	w_endscr();		/* 終了処理 */

	return(0);
}

/*=======================================================================
 |
 |		検索処理
 |
 |	void	t_search(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_search(DB *dbp)
{
	IDX	*ip;
	IDX_H	*ihp;
	WINDOW	*wp;
	int	i, kl, cn, ch, loop_sw;
	char	buf[40];

	ip = dbp->ip[dbp->master];

	if (ip == NULL)
		return;

	ihp = ip->ihp;

	kl = strlen(ihp->index);
	cn = kl + ihp->l_key + 5;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, cn, 3, 8, 18);
	w_stdwin(wp, "検索", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* 表現式表示 */
	w_xprintf(wp, 1, 1, "%s", ihp->index);
	inp_field(wp, ihp->l_key);

	memset(buf, ' ', ihp->l_key);
	loop_sw = 1;
	while (loop_sw) {
		w_popup(wp);

		/* 検索文字列入力 */
		if (ihp->type == 0)
			ch = w_inputs(wp, buf, kl+2, 1, ihp->l_key, 0x81);
		else
			ch = w_inputn(wp, buf, kl+2, 1, ihp->l_key, 0, 0x00);
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
			if (w_check() != wp) {
				loop_sw = 0;
				break;
			}
		case KEY_CR:
			for (i = ihp->l_key - 1; i >= 0; i--) {
				if (buf[i] != ' ')
					break;
			}

			if (i >= 0) {
				db_search(dbp, buf, i + 1);	/* 検索 */
				display2(dbp);
			} else
				loop_sw = 0;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	w_close(wp);
}

static	void	inp_field(WINDOW *wp, int len)
{
	w_putch(wp, '[');
	while(len--)
		w_putch(wp, ' ');
	w_putch(wp, ']');
}

/*=======================================================================
 |
 |		追加処理
 |
 |	void	t_store(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_store(DB *dbp)
{
	DBF	*dp;
	WINDOW	*wp;
	register int	i;
	DBF_H	*dhp;
	FIELD	*flp;
	int	max_length, loop_sw, ch;
	char	ibuf[256], *ibp;

	dp = dbp->dp;

	dhp = dp->dhp;

	/* ウィンドウオープン */
	max_length = 0;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		if (flp->l_field > max_length)
			max_length = flp->l_field;
	}
	wp = w_open(m_gx, m_gy, max_length+16, dp->n_field + 3, 8, 18);
	w_stdwin(wp, "追加", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/*フィールド名表示 */
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		w_xprintf(wp, 1, i+1, "%-10s ", flp->name);
		inp_field(wp, flp->l_field);
	}

	/* レコードバッファクリア */
	memset(ibuf, ' ', dhp->l_rec - 1);

	/* フィールドデータ入力 */
	ibp = ibuf;
	loop_sw = 1;
	for (i = 0, flp = dp->flp; loop_sw && i < dp->n_field; ) {
		if (flp->type == 'C')
			ch = w_inputs(wp, ibp, 13, i+1, flp->l_field, 0x01);
		else
			ch = w_inputn(wp, ibp, 13, i+1, flp->l_field, flp->l_dec, 0x00);
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
			if (w_check() != wp) {
				loop_sw = 0;
				break;
			}
		case KEY_CR:
			ibp += flp->l_field;
			i++;
			flp++;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	if (loop_sw) {
		if (jikkou(wp)) {
			db_store(dbp, ibuf);	/* 追加処理 */
			display2(dbp);
		}
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		更新処理
 |
 |	void	t_update(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_update(DB *dbp)
{
	DBF	*dp;
	WINDOW	*wp;
	register int	i;
	DBF_H	*dhp;
	FIELD	*flp;
	int	max_length, loop_sw, ch;
	char	ibuf[256], *ibp, fmt[20];

	dp = dbp->dp;

	dhp = dp->dhp;

	/* ウィンドウオープン */
	max_length = 0;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		if (flp->l_field > max_length)
			max_length = flp->l_field;
	}
	wp = w_open(m_gx, m_gy, max_length+16, dp->n_field + 3, 8, 18);
	w_stdwin(wp, "更新", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* フィールド名表示 */
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		sprintf(fmt, "%%-10s [%%.%ds]", flp->l_field);
		w_xprintf(wp, 1, i+1, fmt, flp->name, flp->fad);
	}

	memcpy(ibuf, dp->rbp+1, dhp->l_rec - 1);

	/* フィールド情報入力 */
	ibp = ibuf;
	loop_sw = 1;
	for (i = 0, flp = dp->flp; loop_sw && i < dp->n_field; ) {
		if (flp->type == 'C')
			ch = w_inputs(wp, ibp, 13, i+1, flp->l_field, 0x01);
		else
			ch = w_inputn(wp, ibp, 13, i+1, flp->l_field, flp->l_dec, 0x00);
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
			if (w_check() != wp) {
				loop_sw = 0;
				break;
			}
		case KEY_CR:
			ibp += flp->l_field;
			i++;
			flp++;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	if (loop_sw) {
		if (jikkou(wp)) {
			db_update(dbp, ibuf);	/* 更新処理 */
			display2(dbp);
		}
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		削除処理
 |
 |	void	t_delete(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_delete(DB *dbp)
{
	WINDOW	*wp;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 11, 3, 8, 18);
	w_stdwin(wp, "削除", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		db_delete(dbp);		/* 削除処理 */
		display2(dbp);
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		表示処理
 |
 |	void	t_list(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_list(DB *dbp)
{
	static	int	sel;
	static	char	*item[] = {
		"一  覧",
		"情  報"
	};
	int	csel;
	WINDOW	*wp;
	int	loop_sw;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 11, 4, 8, 18);
	w_stdwin(wp, "表示", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	loop_sw = 1;
	while (loop_sw) {
		csel = w_select(wp, item, 1, 2, sel, C_BLACK, C_BLUE);
		switch (csel) {
		case 0:		/* 一覧 */
			t_ichiran(dbp);
			break;
		case 1:		/* 情報 */
			t_info(dbp);
			break;
		default:
			loop_sw = 0;
		}

		if (csel != -1)
			sel = csel;

		w_popup(wp);
		if (w_check() == wp)
			sel = -1;
		else
			loop_sw = 0;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		一覧表示処理
 |
 |	void	t_ichiran(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_ichiran(DB *dbp)
{
	static	char	*item[] = {
		"先頭レコードから表示",
		"ｶﾚﾝﾄレコードから表示"
	};
	DBF	*dp;
	WINDOW	*wp;
	int	cn, ln, ch, csel;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 25, 4, 8, 18);
	w_stdwin(wp, "一覧", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;
	csel = w_select(wp, item, 1, 2, 0, C_BLACK, C_BLUE);
	w_close(wp);
	if (csel == 0)
		db_top(dbp);
	else if (csel == -1)
		return;

	dp = dbp->dp;

	cn = max(dp->dhp->l_rec+dp->n_field, 5) + 3;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, cn, 15, 8, 18);
	w_stdwin(wp, "一覧", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	ln = 0;
	for (;;) {
		ln++;
		if (ln <= 13)
			display(wp, dbp, ln);	/* １レコード表示 */
		else {
			w_scroll_up(wp);	/* ロールアップ */
			display(wp, dbp, 13);	/* １レコード表示 */
		}

		if (db_eof(dbp)) {
			waitkey(wp);
			break;
		}

		db_skip(dbp, 1L);		/* 次レコード */

		if (ch = inkey()) {
			if (ch == KEY_ESC || ch == MOUSE_R)
				break;
			else if (ch == ' ' || ch == MOUSE_L) {
				ch = waitkey(wp);
				w_popup(wp);
				if (ch == KEY_ESC || ch == MOUSE_R)
					break;
			}
		}
	}

	w_close(wp);

	display2(dbp);
}

/*=======================================================================
 |
 |		情報表示処理
 |
 |	void	t_info(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_info(DB *dbp)
{
	DBF	*dp;
	IDX	*ip;
	static	int	sel;
	char	*item[20];
	int	csel;
	WINDOW	*wp;
	int	loop_sw, max_fnl, cn, n, len;

	dp = dbp->dp;

	/* ファイル名取り出し */
	n = 1;
	item[0] = dp->file_name;
	max_fnl = strlen(dp->file_name);
	for (n = 1; n <= dbp->n_idx; n++) {
		ip = dbp->ip[n];
		if ((len = strlen(ip->file_name)) > max_fnl)
			max_fnl= len;
		item[n] = ip->file_name;
	}

	if (n == 1)
		t_info_dbf(dp);		/* ｄｂｆファイル情報表示 */
	else {
		/* ウィンドウオープン */
		cn = max(max_fnl, 4) + 4;
		wp = w_open(m_gx, m_gy, cn, n+2, 8, 18);
		w_stdwin(wp, "情報", C_BLACK, C_WHITE, C_BLACK);
		wp->flag = 0x01;

		/* ファイル選択 */
		loop_sw = 1;
		while (loop_sw) {
			csel = w_select(wp, item, 1, n, sel, C_BLACK, C_BLUE);
			switch (csel) {
			case 0:		/* ＤＢＦファイル */
				t_info_dbf(dp);
				break;
			default:	/* ＮＤＸファイル */
				t_info_idx(dbp->ip[csel]);
				break;
			case -1:
				loop_sw = 0;
				break;
			}

			if (csel != -1)
				sel = csel;

			w_popup(wp);
			if (w_check() == wp)
				sel = -1;
			else
				loop_sw = 0;
		}
		w_close(wp);
	}
}

/*=======================================================================
 |
 |		ｄｂｆファイル情報表示処理
 |
 |	void	t_info_dbf(dp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |
 =======================================================================*/
static	void	t_info_dbf(DBF *dp)
{
	DBF_H	*dhp;
	FIELD	*flp;
	WINDOW	*wp;
	register int	i;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 40, dp->n_field+6, 8, 18);
	w_stdwin(wp, dp->file_name, C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* ｄｂｆファイル情報表示 */
	dhp = dp->dhp;
	w_xprintf(wp, 1, 1, "最終更新日 : %2d/%02d/%02d\n", dhp->date[0], dhp->date[1], dhp->date[2]);
	w_xprintf(wp, 1, 2, "レコード数 : %ld\n", dhp->n_rec);
	w_xprintf(wp, 1, 3, "レコード長 : %d\n\n", dhp->l_rec);
	w_xprintf(wp, 1, 4, "フィールド名　形式　桁数　小数位桁数\n");
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		w_xprintf(wp, 1, i+5, " %-11s   %c     %2d     %2d\n",
				flp->name,
				flp->type,
				flp->l_field,
				flp->l_dec);
	}

	waitkey(wp);

	w_close(wp);
}

/*=======================================================================
 |
 |		インデックスファイル情報表示処理
 |
 |	void	t_info_idx(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
static	void	t_info_idx(IDX *ip)
{
	IDX_H	*ihp;
	WINDOW	*wp;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 40, 9, 8, 18);
	w_stdwin(wp, ip->file_name, C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* インデックスファイル情報表示 */
	ihp = ip->ihp;
	w_xprintf(wp, 1, 1, "ルートＮｏ : %ld\n", ihp->root);
	w_xprintf(wp, 1, 2, "ブロック数 : %ld\n", ihp->n_block);
	w_xprintf(wp, 1, 3, "キー長     : %d\n", ihp->l_key);
	w_xprintf(wp, 1, 4, "次数       : %d\n", ihp->order);
	w_xprintf(wp, 1, 5, "データ種別 : %d\n", ihp->type);
	w_xprintf(wp, 1, 6, "レコード長 : %d\n", ihp->l_rec);
	w_xprintf(wp, 1, 7, "表現式     : %s\n", ihp->index);

	waitkey(wp);

	w_close(wp);
}

/*=======================================================================
 |
 |		位置処理
 |
 |	void	t_locate(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_locate(DB *dbp)
{
	static	int	sel;
	static	char	*item[] = {
		"先  頭",
		"最  後",
		"指定ﾚｺｰﾄﾞ",
		"前後移動"
	};
	int	csel;
	WINDOW	*wp, *wp2;
	int	loop_sw, loop_sw2, ch;
	char	buf[6];
	static	char	rnbuf[6] = "    1";

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 13, 6, 8, 18);
	w_stdwin(wp, "位置", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	loop_sw = 1;
	while (loop_sw) {
		csel = w_select(wp, item, 1, 4, sel, C_BLACK, C_BLUE);
		switch (csel) {
		case 0:		/* 先頭 */
			db_top(dbp);
			display2(dbp);
			break;
		case 1:		/* 最後 */
			db_bottom(dbp);
			display2(dbp);
			break;
		case 2:		/* 指定ﾚｺｰﾄﾞ */
			wp2 = w_open(m_gx, m_gy, 23, 3, 8, 18);
			w_stdwin(wp2, "指定ﾚｺｰﾄﾞ", C_BLACK, C_WHITE, C_BLACK);
			wp2->flag = 0x01;
			w_dspstr(wp2, 1, 1, "レコード番号 ");
			inp_field(wp2, 5);
			memset(buf, ' ', 5);
			loop_sw2 = 1;
			while (loop_sw2) {
				w_popup(wp2);
				ch = w_inputn(wp2, buf, 15, 1, 5, 0, 0x180);
				switch (ch) {
				case MOUSE_R:
				case MOUSE_L:
					if (w_check() != wp2) {
						loop_sw2 = 0;
						break;
					}
				case KEY_CR:
					db_set(dbp, atol(buf));
					display2(dbp);
					break;
				case KEY_ESC:
					loop_sw2 = 0;
					break;
				default:
					beep();
				}
			}
			w_close(wp2);
			break;
		case 3:		/* 前後移動 */
			wp2 = w_open(m_gx, m_gy, 25, 3, 8, 18);
			w_stdwin(wp2, "前後移動", C_BLACK, C_WHITE, C_BLACK);
			wp2->flag = 0x01;
			w_dspstr(wp2, 1, 1, "移動レコード数 ");
			inp_field(wp2, 5);
			w_dspstr(wp2, 17, 1, rnbuf);
			loop_sw2 = 1;
			while (loop_sw2) {
				w_popup(wp2);
				ch = w_inputn(wp2, rnbuf, 17, 1, 5, 0, 0x180);
				switch (ch) {
				case MOUSE_R:
				case MOUSE_L:
					if (w_check() != wp2) {
						loop_sw2 = 0;
						break;
					}
				case KEY_CR:
					db_skip(dbp, atol(rnbuf));
					display2(dbp);
					break;
				case KEY_ESC:
					loop_sw2 = 0;
					break;
				default:
					beep();
				}
			}
			w_close(wp2);
			break;
		default:
			loop_sw = 0;
		}

		if (csel != -1)
			sel = csel;

		w_popup(wp);
		if (w_check() == wp)
			sel = -1;
		else
			loop_sw = 0;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		その他処理
 |
 |	void	t_etc(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_etc(DB *dbp)
{
	static	int	sel;
	static	char	*item[] = {
		"ソート",
		"パック",
		"ｲﾝﾃﾞｯｸｽ再構築",
		"ｲﾝﾃﾞｯｸｽ切替",
		"カウント",
		"復元",
		"削除モード",
		"フラッシュ",
		"ｲﾝﾃﾞｯｸｽ作成"
	};
	int	csel;
	WINDOW	*wp;
	int	loop_sw;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 18, 11, 8, 18);
	w_stdwin(wp, "その他", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	loop_sw = 1;
	while (loop_sw) {
		csel = w_select(wp, item, 1, 9, sel, C_BLACK, C_BLUE);
		switch (csel) {
		case 0:		/* ソート */
			t_sort(dbp);
			break;
		case 1:		/* パック */
			t_pack(dbp);
			break;
		case 2:		/* インデックス再構築 */
			t_reindex(dbp);
			break;
		case 3:		/* インデックス切替 */
			t_chgidx(dbp);
			break;
		case 4:		/* カウント */
			t_count(dbp);
			break;
		case 5:		/* 復元 */
			t_recall(dbp);
			break;
		case 6:		/* 削除モード */
			del_mode();
			break;
		case 7:		/* フラッシュ */
			t_flush(dbp);
			break;
		case 8:		/* ｲﾝﾃﾞｯｸｽ作成 */
			t_idx_create(dbp, NULL);
			break;
		default:
			loop_sw = 0;
		}

		if (csel != -1)
			sel = csel;

		w_popup(wp);
		if (w_check() == wp)
			sel = -1;
		else
			loop_sw = 0;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		ソート処理
 |
 |	void	t_sort(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_sort(DB *dbp)
{
	DBF	*dp;
	WINDOW	*wp;
	char	file_name[10], key[11];
	int	ch;

	dp = dbp->dp;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 30, 6, 8, 18);
	w_stdwin(wp, "ソート", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	w_dspstr(wp, 1, 1, "出力ファイル名 [        ]");
	w_dspstr(wp, 1, 2, "キー           [          ]");

	/* 出力ファイル名入力 */
	ch = w_inputs(wp, file_name, 17, 1, 8, 0x111);
	switch (ch) {
	case MOUSE_R:
	case MOUSE_L:
		if (w_check() != wp)
			break;
	case KEY_CR:
		/* キー選択 */
		if (sel_key(dp, key) != -1) {
			w_dspstr(wp, 17, 2, key);
			if (jikkou(wp)) {
				set_color(C_RED, -1);
				w_dspstr(wp, 1, 3, "実行中");
				dbf_sort(dp, file_name, key);	/* ソート */
			}
		}
		break;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		パック処理
 |
 |	void	t_pack(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_pack(DB *dbp)
{
	WINDOW	*wp;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 11, 4, 8, 18);
	w_stdwin(wp, "パック", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		set_color(C_RED, -1);
		w_dspstr(wp, 1, 1, "実行中");
		db_pack(dbp);		/* パック処理 */
		display2(dbp);
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		インデックス再構築処理
 |
 |	void	t_reindex(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_reindex(DB *dbp)
{
	WINDOW	*wp;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 18, 4, 8, 18);
	w_stdwin(wp, "ｲﾝﾃﾞｯｸｽ再構築", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		set_color(C_RED, -1);
		w_dspstr(wp, 1, 1, "実行中");
		db_reindex(dbp);	/* インデックス再構築処理 */
		display2(dbp);
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		インデックス切り替え処理
 |
 |	void	t_chgidx(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_chgidx(DB *dbp)
{
	IDX	*ip;
	static	int	sel;
	char	*item[20];
	int	csel;
	WINDOW	*wp;
	int	max_fnl, cn, n, len;

	if (dbp->n_idx == 0)
		return;

	/* インデックスファイル名取り出し */
	item[0] = "なし";
	max_fnl = 0;
	for (n = 1; n <= dbp->n_idx; n++) {
		ip = dbp->ip[n];
		if ((len = strlen(ip->file_name)) > max_fnl)
			max_fnl= len;
		item[n] = ip->file_name;
	}

	/* ウィンドウオープン */
	cn = max(max_fnl, 11) + 4;
	wp = w_open(m_gx, m_gy, cn, n+2, 8, 18);
	w_stdwin(wp, "ｲﾝﾃﾞｯｸｽ切替", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* インデックスファイル選択 */
	csel = w_select(wp, item, 1, n, sel, C_BLACK, C_BLUE);
	if (csel != -1) {
		db_chgidx(dbp, csel);	/* インデックス切り替え */
		display2(dbp);
		sel = csel;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		カウント処理
 |
 |	void	t_count(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_count(DB *dbp)
{
	IDX	*ip;
	IDX_H	*ihp;
	WINDOW	*wp;
	int	i, kl, cn, ch, loop_sw;
	char	buf[40];
	long	n;

	ip = dbp->ip[dbp->master];

	if (ip == NULL)
		return;

	ihp = ip->ihp;

	kl = strlen(ihp->index);
	cn = kl + ihp->l_key + 2;
	cn = max(cn, 8) + 3;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, cn, 4, 8, 18);
	w_stdwin(wp, "カウント", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;
	
	/* 表現式表示 */
	w_xprintf(wp, 1, 1, "%s", ihp->index);
	inp_field(wp, ihp->l_key);

	memset(buf, ' ', ihp->l_key);
	loop_sw = 1;
	while (loop_sw) {
		w_popup(wp);
		if (ihp->type == 0)
			ch = w_inputs(wp, buf, kl+2, 1, ihp->l_key, 0x81);
		else
			ch = w_inputn(wp, buf, kl+2, 1, ihp->l_key, 0, 0x00);
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
			if (w_check() != wp) {
				loop_sw = 0;
				break;
			}
		case KEY_CR:
			for (i = ihp->l_key - 1; i >= 0; i--) {
				if (buf[i] != ' ')
					break;
			}

			if (i >= 0) {
				n = db_count(dbp, buf, i + 1);	/* カウント */
				w_xprintf(wp, 1, 2, "%5ld 件", n);
				display2(dbp);
			} else
				loop_sw = 0;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		復元処理
 |
 |	void	t_recall(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_recall(DB *dbp)
{
	WINDOW	*wp;

	wp = w_open(m_gx, m_gy, 11, 3, 8, 18);
	w_stdwin(wp, "復元", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		db_recall(dbp);		/* 復元処理 */
		display2(dbp);
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		削除モード処理
 |
 |	void	del_mode(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	del_mode()
{
	static	char	*item[] = {
		"削除レコード有効",
		"削除レコード無視"
	};
	int	csel;
	WINDOW	*wp;

	wp = w_open(m_gx, m_gy, 21, 4, 8, 18);
	w_stdwin(wp, "削除モード", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	csel = w_select(wp, item, 1, 2, _db_set_deleted, C_BLACK, C_BLUE);
	if (csel != -1)
		set_deleted(csel);	/* 削除モードセット */

	w_close(wp);
}

/*=======================================================================
 |
 |		フラッシュ処理
 |
 |	void	t_flush(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_flush(DB *dbp)
{
	WINDOW	*wp;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 17, 3, 8, 18);
	w_stdwin(wp, "フラッシュ", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		db_flush(dbp);	/* フラッシュ処理 */
		display2(dbp);
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		ｄｂｆファイル作成処理
 |
 |	DB	*t_dbf_create(file_name)
 |
 |		char	*file_name;	ｄｂｆファイル名
 |
 =======================================================================*/
static	DB	*t_dbf_create(char *file_name)
{
	DB	*dbp;
	WINDOW	*wp, *wp2;
	char	buf[4];
	int	y, i, j, csel;
	DBF_I	dbfi[20];
	static	char	*item[] = {
		"文字型",
		"数値型",
		"日付型",
		"論理型"
	};

	/* ウィンドウオープン */
	wp = w_open(180, 100, 37, 13, 8, 18);
	w_stdwin(wp, "ﾃﾞｰﾀﾍﾞｰｽ作成", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* データファイル名表示 */
	w_xprintf(wp, 1, 1, "ﾃﾞｰﾀ ﾌｧｲﾙ名 %s", file_name);

	w_dspstr(wp, 1, 2, "フィールド名   形式     長さ  小数");
	for (i = 0; ; i++) {
		if (i < 9) {
			y = i + 3;
		} else {
			y = 11;
			w_scroll_up(wp);
		}
		w_dspstr(wp, 1, y, "[          ] [      ]  [   ]  [  ]");

		/* ファイル名入力 */
		w_inputs(wp, dbfi[i].name, 2, y, 10, 0x91);
		for (j = 10; j > 0; j--) {
			if (dbfi[i].name[j-1] != ' ')
				break;
		}
		if (j == 0)
			break;
		dbfi[i].name[j] = '\0';

		/* フィールド形式選択 */
		wp2 = w_open(m_gx, m_gy, 13, 6, 8, 18);
		w_stdwin(wp2, "形式", C_BLACK, C_WHITE, C_BLACK);
		csel = w_select(wp2, item, 1, 4, 0, C_BLACK, C_BLUE);
		w_close(wp2);
		if (csel == -1)
			break;
		w_dspstr(wp, 15, y, item[csel]);
		switch (csel) {
		case 0:
			dbfi[i].type = 'C';
			break;
		case 1:
			dbfi[i].type = 'N';
			break;
		case 2:
			dbfi[i].type = 'D';
			break;
		case 3:
			dbfi[i].type = 'L';
			break;
		}

		/* フィールド長入力 */
		for (;;) {
			if (dbfi[i].type != 'D') {
				w_inputn(wp, buf, 25, y, 3, 0, 0x90);
				dbfi[i].l_field = natoi(buf, 3);
			} else {
				w_dspstr(wp, 25, y, "  8");
				dbfi[i].l_field = 8;
			}
			if (dbfi[i].l_field > 0 && dbfi[i].l_field < 128)
				break;
		}

		/* 小数部桁数入力 */
		if (dbfi[i].type == 'N') {
			w_inputn(wp, buf, 32, y, 2, 0, 0x90);
			dbfi[i].l_dec = natoi(buf, 2);
		} else
			dbfi[i].l_dec = 0;
	}

	if (i != 0)
		dbp = db_create(file_name, dbfi, i);	/* データベース作成 */
	else
		dbp = NULL;

	w_close(wp);

	return(dbp);
}

/*=======================================================================
 |
 |		インデックスファイル作成処理
 |
 |	int	t_idx_create(dbp, fname)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		char	*fname;		インデックスファイル名
 |
 =======================================================================*/
static	int	t_idx_create(DB *dbp, char *fname)
{
	DBF	*dp;
	WINDOW	*wp, *wp2;
	char	file_name[14], buf[11], key[100];
	int	ch, uniq, csel, idx;
	static	char	*item1[] = {
		"終了",
		"連結"
	};
	static	char	*item2[] = {
		"許す",
		"許さない"
	};

	idx = 0;
	dp = dbp->dp;

	/* ウィンドウオープン */
	wp = w_open(m_gx, m_gy, 40, 7, 8, 18);
	w_stdwin(wp, "ｲﾝﾃﾞｯｸｽ作成", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	w_dspstr(wp, 1, 1, "ｲﾝﾃﾞｯｸｽ ﾌｧｲﾙ名 [        ]");
	w_dspstr(wp, 1, 2, "キー           [                    ]");
	w_dspstr(wp, 1, 3, "キーの重複     [        ]");

	/* インデックスファイル名入力 */
	if (fname == NULL) {
		ch = w_inputs(wp, file_name, 17, 1, 8, 0x111);
	} else {
		strcpy(file_name, fname);
		w_dspstr(wp, 17, 1, file_name);
		ch = KEY_CR;
	}
	if (strchr(file_name, '.') == NULL)
		strcat(file_name, ".NDX");
	switch (ch) {
	case MOUSE_R:
	case MOUSE_L:
		if (w_check() != wp)
			break;
	case KEY_CR:
		key[0] = '\0';
	select_key:
		/* キー選択 */
		if (sel_key(dp, buf) == -1)
			break;
		strcat(key, buf);
		w_dspstr(wp, 17, 2, key);
		wp2 = w_open(m_gx, m_gy, 17, 4, 8, 18);
		w_stdwin(wp2, "キーの連結", C_BLACK, C_WHITE, C_BLACK);
		csel = w_select(wp2, item1, 1, 2, 0, C_BLACK, C_BLUE);
		w_close(wp2);
		if (csel == 1) {
			strcat(key, "+");
			goto select_key;
		} else if (csel == -1)
			break;

		/* キーの重複選択 */
		wp2 = w_open(m_gx, m_gy, 17, 4, 8, 18);
		w_stdwin(wp2, "キーの重複", C_BLACK, C_WHITE, C_BLACK);
		csel = w_select(wp2, item2, 1, 2, 0, C_BLACK, C_BLUE);
		w_close(wp2);
		if (csel == -1)
			break;
		w_dspstr(wp, 17, 3, item2[csel]);
		uniq = csel;

		if (jikkou(wp)) {
			/* インデックスファイル作成 */
			idx = db_idx_create(dbp, file_name, key, uniq);
			if (fname == NULL)
				display2(dbp);
		}
		break;
	}

	w_close(wp);

	return(idx);
}

/*=======================================================================
 |
 |		キー選択処理
 |
 |	void	sel_key(dp, key)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		char	*key;		キー入力バッファ
 |
 =======================================================================*/
static	int	sel_key(DBF *dp, char *key)
{
	WINDOW	*wp;
	register int 	i;
	int	csel;
	char	*item[30];

	for (i = 0; i < dp->n_field; i++)
		item[i] = dp->flp[i].name;

	wp = w_open(m_gx, m_gy, 13, dp->n_field+2, 8, 18);
	w_stdwin(wp, "キー一覧", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;
	csel = w_select(wp, item, 1, dp->n_field, 0, C_BLACK, C_BLUE);
	if (csel != -1)
		strcpy(key, item[csel]);

	w_close(wp);

	return(csel);
}

/*=======================================================================
 |
 |		レコード表示（一覧形式）
 |
 |	void	display(wp, dbp, y)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		DB	*dbp;		ＤＢポインタ
 |		int	y;		表示行位置
 |
 =======================================================================*/
static	void	display(WINDOW *wp, DB *dbp, int y)
{
	char	fmt[10];
	DBF	*dp;
	FIELD	*flp;
	int	i, x;

	dp = dbp->dp;
	flp = dp->flp;
	if (db_bof(dbp)) {
		set_color(C_BLACK, -1);
		sprintf(fmt, "%%-%ds", dp->dhp->l_rec);
		w_xprintf(wp, 1, y, fmt, "[BOF]");
	} else if (db_eof(dbp))  {
		set_color(C_BLACK, -1);
		sprintf(fmt, "%%-%ds", dp->dhp->l_rec);
		w_xprintf(wp, 1, y, fmt, "[EOF]");
	} else {
		dbf_read(dp, dbp->rno);
		set_color(*dp->rbp == '*' ? C_BLUE : C_BLACK, -1);
		w_dspnstr(wp, 1, y, dp->rbp, 1);
		x = 2;
		for (i = 0; i < dp->n_field; i++) {
			w_dspnstr(wp, x, y, flp->fad, flp->l_field);
			x += flp->l_field;
			w_dspstr(wp, x, y, " ");
			x++;
			flp++;
		}
		/*w_dspnstr(wp, 1, y, dp->rbp, dp->dhp->l_rec);*/
		set_color(C_BLACK, -1);
	}
}

/*=======================================================================
 |
 |		レコード表示
 |
 |	void	display2(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	display2(DB *dbp)
{
	DBF	*dp;
	register int	i;
	FIELD	*flp;
	char	fmt[20];

	w_popup(dwp);

	dp = dbp->dp;

	if (db_bof(dbp)) {
		set_color(C_RED, -1);
		w_cls(dwp);
		w_dspstr(dwp, 1, 1, "[BOF]");
	} else if (db_eof(dbp)) {
		set_color(C_RED, -1);
		w_cls(dwp);
		w_dspstr(dwp, 1, 1, "[EOF]");
	} else {
		dbf_read(dp, dbp->rno);
		set_color(*dp->rbp == '*' ? C_BLUE : C_BLACK, -1);

		for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
			sprintf(fmt, "%%-10s [%%.%ds]", flp->l_field);
			w_xprintf(dwp, 1, i+1, fmt, flp->name, flp->fad);
		}
	}
	set_color(C_BLACK, -1);
}

static	void	w_cls(WINDOW *wp)
{
	int	x, y;

	for (y = 1; y < wp->ny - 1; y++) {
		w_loc(wp, 1, y);
		for (x = 1; x < wp->nx - 2; x++)
			w_putch(wp, ' ');
	}
}

/*=======================================================================
 |
 |		実行確認
 |
 |	int	jikkou(wp)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |
 =======================================================================*/
static	int	jikkou(WINDOW *wp)
{
	static	MOUSE	mouse[2];
	int	x, y, ch;

	set_color(C_BLUE, -1);

	x = wp->nx - 8;
	y = wp->ny - 2;
	w_dspstr(wp, x, y, "実行");
	w_line(wp, x*wp->dx-1, y*wp->dy-1, (x+4)*wp->dx, (y+1)*wp->dy
							, C_BLUE, 1, 0);
	mouse[0].x = x;
	mouse[0].y = y;
	mouse[0].len = 4;
	mouse[0].code = KEY_CR;
	m_reg(wp, mouse, C_BLUE);
	ch = w_input(wp, 0);
	m_rel(mouse);

	return(ch == KEY_CR ? 1 : 0);
}

/*=======================================================================
 |
 |		エラー表示処理
 |
 |	void	err_proc(err_code)
 |
 |		int	err_code;	エラーコード
 |
 =======================================================================*/
static	void	err_proc(int err_code)
{
	WINDOW	*wp;
	char	*msg;

	wp = w_open(m_gx, m_gy, 33, 3, 8, 18);
	w_stdwin(wp, "エラーメッセージ", C_RED, C_WHITE, C_BLACK);
	wp->flag = 0x01;
	switch (err_code) {
	case IE_MEMORY:
		msg = "メモリ不足";
		break;
	case IE_FOPEN:
		msg = "ファイルオープンエラー ";
		break;
	case IE_NOFILE:
		msg = "オープンするファイルがない";
		break;
	case IE_IDXOVER:
		msg = "インデックスファイルが多すぎる";
		break;
	case IE_TMPFILE:
		msg = "テンポラリファイルが作れない";
		break;
	case IE_NOKEY:
		msg = "指定されたキーが存在しない";
		break;
	case IE_DBLKEY:
		msg = "キーが重複している";
		break;
	case IE_RECNO:
		msg = "レコード番号が不正";
		break;
	case IE_EOF:
		msg = "ＢＯＦまたはＥＯＦ";
		break;
	case IE_STRFILE:
		msg = "構造ファイルが正しくない";
		break;
	case IE_READ:
		msg = "ファイルｒｅａｄエラー";
		break;
	case IE_WRITE:
		msg = "ファイルｗｒｉｔｅエラー";
		break;
	case IE_IDXKEY:
		msg = "インデックスのキー表現式が不正";
		break;
	default:
		msg = "??????????";
		break;
	}
	w_dspstr(wp, 1, 1, msg);
	waitkey(wp);
	w_close(wp);
}

/*=======================================================================
 |
 |		タイトル表示処理
 |
 |	void	title()
 |
 =======================================================================*/
static	void	title()
{

	static	char	tile[12] = {
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa,
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa
	};

	g_line(0, 0, 639, 399, 0, 2, 2, 0, 12, tile);
	set_color(C_BLUE, C_WHITE);
	w_dspstr(scrp, 22, 0, "*** ISAM LIBRARY SAMPLE PROGRAM ***");
}

/*=======================================================================
 |
 |		ＳＴＯＰキー処理
 |
 |	void	stop_handler()
 |
 =======================================================================*/
static	void	stop_handler(void)
{
	db_close(sv_dbp);
	w_endscr();		/* 終了処理 */
	printf("強制終了しました.\n");
	exit(1);
}
