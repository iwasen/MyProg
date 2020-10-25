/************************************************************************
 *									*
 *		通信プログラム　Ｙ－ＴＥＲＭ				*
 *									*
 *		名称		: ファイル転送処理			*
 *		ファイル名	: file.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <string.h>
#include <sys\types.h>
#include <sys\stat.h>
#include "rs232c.h"
#include "yterm.h"

/*	内部関数	*/
static	void	rcv_text(void);
static	void	snd_text(void);
static	void	rcv_xmodem(void);
static	void	snd_xmodem(void);

/*	内部変数	*/
static	char	rfname[16];	/* 受信ファイル名 */

/*=======================================================================
 |
 |		ファイル転送処理
 |
 |	void	file()
 |
 =======================================================================*/
void	file(void)
{
	static	char	*item1[] = {
		"テキストファイル受信",
		"テキストファイル送信",
		"ＸＭＯＤＥＭ受信",
		"ＸＭＯＤＥＭ送信"
	};
	static	char	*item2[] = {
		"テキストファイル受信終了",
		"テキストファイル送信",
		"ＸＭＯＤＥＭ受信",
		"ＸＭＯＤＥＭ送信"
	};
	static	char	*func1[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " 中止 ", NULL
	};
	WINDOW	*wp;
	int 	sel;

	t_csroff();		/* カーソル消去 */
	dsp_func(func1);	/* ファンクションキー表示 */

	/* ウィンドウオープン */
	wp = w_open(24, 6, 30, 8, "ファイル転送", RC_WHITE);

	/* 処理選択 */
	sel = w_select(wp, rcv_fp == NULL ? item1 : item2, 4, 0
							, C_YELLOW|A_REVERSE);
	switch (sel) {
	case 0:		/* テキストファイル受信（終了） */
		if (rcv_fp == NULL)
			rcv_text();
		else
			rcv_text_end();
		break;
	case 1:		/* テキストファイル送信 */
		snd_text();
		break;
	case 2:		/* ＸＭＯＤＥＭ受信 */
		rcv_xmodem();
		break;
	case 3:		/* ＸＭＯＤＥＭ送信 */
		snd_xmodem();
		break;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		テキストファイル受信処理
 |
 |	void	rcv_text()
 |
 =======================================================================*/
static	void	rcv_text(void)
{
	WINDOW	*wp;
	int	mode, ch;

	memset(rfname, ' ', 15);	/* 受信ファイル名クリア */
	mode = 0;

	/* ウィンドウオープン */
	wp = w_open(20, 9, 40, 11, "テキストファイル受信", RC_WHITE);
	w_dspstr(wp, 3, 4, wp->color, "受信ファイル名 [                 ]");
retry:
	/* 受信ファイル名入力 */
	ch = w_inputs(wp, rfname, 20, 4, 15, 0x141, wp->color);
	if (ch == KEY_ESC || ch == KEY_F9) {
		w_close(wp);
		return;
	}

	if (access(rfname, 0) == 0) {
		/* 追加／上書き選択 */
		w_dspstr(wp, 3, 6, wp->color, "追加  上書き");
		for (;;) {
			if (mode == 0)
				w_color(wp, 3, 6, 4, C_CYAN);
			else
				w_color(wp, 9, 6, 6, C_CYAN);
			ch = getchr();
			if (ch == KEY_ESC || ch == KEY_F9) {
				w_close(wp);
				return;
			} else if (ch == KEY_CR)
				break;
			if (mode == 0)
				w_color(wp, 3, 6, 4, C_WHITE);
			else
				w_color(wp, 9, 6, 6, C_WHITE);
			if (ch == KEY_RIGHT || ch == KEY_LEFT)
				mode = (mode + 1) % 2;
		}
	} else
		mode = 1;

	/* 受信ファイルオープン */
	if ((rcv_fp = fopen(rfname, mode ? "wb" : "ab")) == NULL) {
		w_dspstr(wp, 3, 8, C_MAGENTA|A_REVERSE, "ファイルが開けません");
		tm_wait(150);
		w_dspspc(wp, 3, 6, 12);
		w_dspspc(wp, 3, 8, 20);
		goto 	retry;
	}

	/* 受信ファイル名表示 */
	w_printf(scrp, 1, 23, C_GREEN|A_REVERSE, " [%s]に受信中 ", rfname);

	w_close(wp);
}

/*=======================================================================
 |
 |		テキストファイル受信終了処理
 |
 |	void	rcv_text_end()
 |
 =======================================================================*/
void	rcv_text_end(void)
{
	if (rcv_fp != NULL) {
		fclose(rcv_fp);
		rcv_fp = NULL;
		w_dspspc(scrp, 1, 23, 40);
	}
}

/*=======================================================================
 |
 |		テキストファイル送信処理
 |
 |	void	snd_text()
 |
 =======================================================================*/
static	void	snd_text(void)
{
	WINDOW	*wp;
	char	sfname[16];
	int	ch;

	memset(sfname, ' ', 15);	/* 送信ファイル名クリア */

	/* ウィンドウオープン */
	wp = w_open(20, 9, 40, 9, "テキストファイル送信", RC_WHITE);
	w_dspstr(wp, 3, 4, wp->color, "送信ファイル名 [                 ]");
retry:
	/* 送信ファイル名入力 */
	ch = w_inputs(wp, sfname, 20, 4, 15, 0x141, wp->color);
	if (ch == KEY_ESC || ch == KEY_F9) {
		w_close(wp);
		return;
	}

	/* 送信ファイルオープン */
	if ((snd_fp = fopen(sfname, "r")) == NULL) {
		w_dspstr(wp, 3, 6, C_MAGENTA|A_REVERSE, "ファイルが開けません");
		tm_wait(150);
		w_dspspc(wp, 3, 6, 20);
		goto 	retry;
	}

	/* 送信ファイル名表示 */
	w_dspspc(scrp, 1, 23, 40);
	w_printf(scrp, 1, 23, C_YELLOW|A_REVERSE, " [%s]を送信中 ", sfname);

	w_close(wp);
	send_wait = 0;
}

/*=======================================================================
 |
 |		テキストファイル送信終了処理
 |
 |	void	snd_text_end()
 |
 =======================================================================*/
void	snd_text_end(void)
{
	if (snd_fp != NULL) {
		fclose(snd_fp);
		snd_fp = NULL;
		w_dspspc(scrp, 1, 23, 40);
		if (rcv_fp != NULL)
			w_printf(scrp, 1, 23, C_GREEN|A_REVERSE, " [%s]に受信中 ", rfname);
	}
}

/*=======================================================================
 |
 |		ＸＭＯＤＥＭ受信処理
 |
 |	void	rcv_xmodem()
 |
 =======================================================================*/
static	void	rcv_xmodem(void)
{
	WINDOW	*wp;
	char	fname[16];
	int	ch;
	FILE	*fp;

	memset(fname, ' ', 15);		/* 受信ファイル名クリア */

	/* ウィンドウオープン */
	wp = w_open(20, 9, 40, 11, "ＸＭＯＤＥＭ受信", RC_WHITE);
	w_dspstr(wp, 3, 4, wp->color, "受信ファイル名 [                 ]");
retry:
	/* 受信ファイル名入力 */
	ch = w_inputs(wp, fname, 20, 4, 15, 0x141, wp->color);
	if (ch == KEY_ESC || ch == KEY_F9) {
		w_close(wp);
		return;
	}

	/* 受信ファイルオープン */
	if ((fp = fopen(fname, "wb")) == NULL) {
		w_dspstr(wp, 3, 6, C_MAGENTA|A_REVERSE, "ファイルが開けません");
		tm_wait(150);
		w_dspspc(wp, 3, 6, 20);
		goto 	retry;
	}

	w_dspstr(wp, 3, 6, wp->color, "受信ブロックＮｏ．0");

	/* ＸＭＯＤＥＭ受信 */
	switch (x_receive(fp, wp)) {
	case 0:
		w_dspstr(wp, 3, 8, RC_GREEN, "正常終了");
		break;
	case -1:
		w_dspstr(wp, 3, 8, RC_MAGENTA, "エラーが発生しました");
		break;
	case -2:
		w_dspstr(wp, 3, 8, RC_MAGENTA, "中止しました");
		break;
	}
	beep();
	tm_wait(200);

	fclose(fp);

	w_close(wp);
}

/*=======================================================================
 |
 |		ＸＭＯＤＥＭ送信処理
 |
 |	void	snd_xmodem()
 |
 =======================================================================*/
static	void	snd_xmodem(void)
{
	WINDOW	*wp;
	char	fname[16];
	int	ch;
	FILE	*fp;
	struct	stat	stat;

	memset(fname, ' ', 15);		/* 送信ファイル名入力 */

	/* ウィンドウオープン */
	wp = w_open(20, 9, 40, 11, "ＸＭＯＤＥＭ送信", RC_WHITE);
	w_dspstr(wp, 3, 4, wp->color, "送信ファイル名 [                 ]");
retry:
	/* 送信ファイル名入力 */
	ch = w_inputs(wp, fname, 20, 4, 15, 0x141, wp->color);
	if (ch == KEY_ESC || ch == KEY_F9) {
		w_close(wp);
		return;
	}

	/* 送信ファイルオープン */
	if ((fp = fopen(fname, "rb")) == NULL) {
		w_dspstr(wp, 3, 6, C_MAGENTA|A_REVERSE, "ファイルが開けません");
		tm_wait(150);
		w_dspspc(wp, 3, 6, 20);
		goto 	retry;
	}

	/* 送信ブロック数表示 */
	fstat(fileno(fp), &stat);
	w_printf(wp, 3, 6, wp->color, "送信ブロックＮｏ．0      / %ld", (stat.st_size + 127) / 128);

	/* ＸＭＯＤＥＭ送信 */
	switch (x_send(fp, wp)) {
	case 0:
		w_dspstr(wp, 3, 8, RC_GREEN, "正常終了");
		break;
	case -1:
		w_dspstr(wp, 3, 8, RC_MAGENTA, "エラーが発生しました");
		break;
	case -2:
		w_dspstr(wp, 3, 8, RC_MAGENTA, "中止しました");
		break;
	}
	beep();
	tm_wait(200);

	fclose(fp);

	w_close(wp);
}
