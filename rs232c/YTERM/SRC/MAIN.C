/************************************************************************
 *									*
 *		通信プログラム　Ｙ－ＴＥＲＭ				*
 *									*
 *		名称		: メインプログラム			*
 *		ファイル名	: main.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <time.h>
#ifdef	__TURBOC__
#include <graphics.h>
#else
#include <graph.h>
#endif
#include <dos.h>
#include <string.h>
#include <signal.h>
#include <conio.h>
#include <process.h>
#include "rs232c.h"
#include "yterm.h"

/*	内部関数	*/
static	void	set_screen(void);
static	void	initialize(void);
static	int	initialize2(void);
static	void	terminate(void);
static	void	ms_dos(void);
static	void	user_func(char *);
static	void	scr_save(void);
static	void	scr_restore(void);
static	void	back_scroll(void);
static	void	interrupt far	int_stop(void);
#ifdef	__TURBOC__
static	int	hard_err(unsigned, unsigned, unsigned far *);
#else
static	void	far hard_err(unsigned, unsigned, unsigned far *);
#endif

/*	内部変数	*/
static	struct key_buf	func_save;
static	int	save_text[80*22];
static	int	save_attr[80*22];
static	char	save_gaiji[11][34];
static	void	(far interrupt *stop_vect)();

/*=======================================================================
 |
 |		メイン処理
 |
 |	int	main()
 |
 =======================================================================*/
main(int argc, char *argv[])
{
	int	loop_sw;
	int	ch;

	/* オプションチェック */
	while (--argc) {
		argv++;
		if (strcmp(*argv, "/M") == 0 || strcmp(*argv, "/m") == 0)
			opt_m = 1;
		else {
			printf("未定義オプション '%s'\n", *argv);
			exit(1);
		}
	}

	/* ＲＳ－２３２Ｃオープン */
	rs1_open(&default_prm);
	rs1_cmd(0x00, 0xff);

	/* 初期化処理 */
	initialize();

	switch (initialize2()) {
	case 0:		/* 環境設定ファイル無し */
		setuzoku();
		break;
	case 2:		/* センター情報ファイル有り */
		settei();
		break;
	}

	set_screen();

	loop_sw = 1;
	while (loop_sw) {
		if (rs1_rxc() != 0) {	/* データ受信チェック */
			/* 受信データ有り */
			ch = rs1_get();		/* 受信データ取り出し */

			/* 接続中なら画面に表示 */
			if (conxxxt)
				putch2(ch);

			/* テキストファイル送信待ち処理 */
			if (snd_fp != NULL) {
				if (cprm.prompt != 0) {
					if (ch == cprm.prompt)
						send_wait = 0;	/* 送信可 */
				}
			}
		} else if (ch = inkey()) {	/* キー入力チェック */
			if (conxxxt) {
				/* 接続中の処理 */
				switch (ch) {
				case KEY_F2:	/* ユーザファンクション１ */
					user_func(modem.f2cmd);
					set_screen();
					break;
				case KEY_F3:	/* ユーザファンクション２ */
					user_func(cprm.f3cmd);
					set_screen();
					break;
				case KEY_F5:	/* ﾊﾟﾗﾒｰﾀ */
					parameter();
					set_screen();
					break;
				case KEY_F6:	/* ﾌｧｲﾙ */
					file();
					set_screen();
					break;
				case KEY_F8:	/* MS-DOS */
					ms_dos();
					set_screen();
					break;
				case KEY_F9:	/* 中止 */
					snd_text_end();
					set_screen();
					break;
				case KEY_F10:	/* 切断 */
					setudan();
					set_screen();
					break;
				case KEY_UP:
				case KEY_DOWN:
					back_scroll();
					set_screen();
					break;
				default:
					if (snd_fp == NULL) {
						/* エコーバック */
						if (cprm.echo)
							putch2(ch);

						rs1_putc(ch);	/* 送信 */

						/* ＬＦ付加処理 */
						if (ch == KEY_CR &&
							cprm.retkey != 0) {
							if (cprm.echo)
								putch2(KEY_LF);
							rs1_putc(KEY_LF);
						}
					}
					break;
				}
			} else {
				/* 未接続中の処理 */
				switch (ch) {
				case KEY_F1:	/* 接続 */
					setuzoku();
					set_screen();
					break;
				case KEY_F2:	/* ユーザファンクション１ */
					user_func(modem.f2cmd);
					set_screen();
					break;
				case KEY_F5:	/* 登録 */
					touroku();
					set_screen();
					break;
				case KEY_F6:	/* 設定 */
					settei();
					set_screen();
					break;
				case KEY_F8:	/* MS-DOS */
					ms_dos();
					set_screen();
					break;
				case KEY_F10:	/* 終了 */
					loop_sw = 0;
					break;
				case KEY_UP:
				case KEY_DOWN:
					back_scroll();
					set_screen();
					break;
				default:
					/*putch2(ch);*/
					break;
				}
			}
		} else if (snd_fp != NULL && send_wait == 0) {
			/* テキストファイル送信処理 */
			ch = fgetc(snd_fp);	/* ファイルから１文字読みだし*/
			if (ch == EOF) {	/* ＥＯＦチェック */
				beep();
				snd_text_end();	/* テキストファイル送信終了 */
				set_screen();
			} else {
				/* ＬＦ－＞ＣＲ */
				if (ch == '\n') {
					ch = KEY_CR;
					if (cprm.prompt != 0)
						send_wait = 1;
				}

				/* エコーバック */
				if (cprm.echo)
					putch2(ch);

				rs1_putc(ch);	/* 送信 */

				/* ＬＦ付加処理 */
				if (ch == KEY_CR && cprm.retkey != 0) {
					if (cprm.echo)
						putch2(KEY_LF);
					rs1_putc(KEY_LF);
				}
			}
		}
	}

	rs1_close();	/* ＲＳ－２３２Ｃクローズ */
	terminate();	/* 終了処理 */
	return(0);
}

/*=======================================================================
 |
 |		ファンクションキー，カーソル表示
 |
 |	void	set_screen()
 |
 =======================================================================*/
static	void	set_screen(void)
{
	static	char	*func1[] = {
		" 接続 ", NULL, NULL, NULL, " 登録 ",
		" 設定 ", NULL, "MS-DOS", NULL, " 終了 "
	};
	static	char	*func2[] = {
		NULL, NULL, NULL, NULL, "ﾊﾟﾗﾒｰﾀ",
		" ﾌｧｲﾙ ", NULL, "MS-DOS", NULL, " 切断 "
	};
	static	char	*func3[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " 中止 ", NULL
	};

	if (conxxxt) {
		/* ファンクションキー表示 */
		func2[1] = modem.f2dsp;
		func2[2] = cprm.f3dsp;
		dsp_func(snd_fp == NULL ? func2 : func3);

		/* カーソル表示 */
		t_loc(csr2_x, csr2_y);
		t_csron();
	} else {
		/* ファンクションキー表示 */
		func1[1] = modem.f2dsp;
		dsp_func(func1);

		/* カーソル消去 */
		t_csroff();
	}
}

/*=======================================================================
 |
 |		初期化処理１
 |
 |	void	initialize()
 |
 =======================================================================*/
static	void	initialize(void)
{
	static	char	gaiji_7621[] = {
		0x02, 0x02,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xab, 0xff, 0x55, 0x00,
		0xab, 0x7f, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40
	};
	static	char	gaiji_7622[] = {
		0x02, 0x02,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
		0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00
	};
	static	char	gaiji_7623[] = {
		0x02, 0x02,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xff, 0xea, 0x00, 0x55,
		0xff, 0x6a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75
	};
	static	char	gaiji_7624[] = {
		0x02, 0x02,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0xff, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75
	};
	static	char	gaiji_7625[] = {
		0x02, 0x02,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40
	};
	static	char	gaiji_7626[] = {
		0x02, 0x02,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0xff, 0x7a, 0x00, 0x75,
		0xff, 0xfa, 0xff, 0xf5, 0xff, 0xfa, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55
	};
	static	char	gaiji_7627[] = {
		0x02, 0x02,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x7f, 0x55, 0x00,
		0xab, 0xff, 0x55, 0x7f, 0xaa, 0xbf, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55
	};
	static	char	gaiji_7628[] = {
		0x02, 0x02,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x7f,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40
	};
	static	char	gaiji_7629[] = {
		0x02, 0x02,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75
	};
	static	char	gaiji_762a[] = {
		0x02, 0x02,
		0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
		0x00, 0x00, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0xff, 0xff, 0xff, 0xff, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55
	};
	static	char	gaiji_762b[] = {
		0x02, 0x02,
		0xff, 0xff, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00
	};
	static	char	*gaiji[] = {
		gaiji_7621, gaiji_7622, gaiji_7623, gaiji_7624,
		gaiji_7625, gaiji_7626, gaiji_7627, gaiji_7628,
		gaiji_7629, gaiji_762a, gaiji_762b
	};
	int	i;

	/* ビデオモード設定 */
#ifdef	__TURBOC__
{
	int	gdriver = DETECT, gmode, err;

	registerfarbgidriver(PC98_driver_far);
	initgraph(&gdriver, &gmode, "");
	if ((err = graphresult()) != grOk) {
		rs1_close();
		printf("graphics error : %s\n", grapherrormsg(err));
		exit(1);
	}
}
#else
	_setvideomode(_98RESS8COLOR);
#endif
	signal(SIGINT, SIG_IGN);	/* ＳＴＯＰキーを無視する */
	stop_vect = _dos_getvect(0x06);	/* ＳＴＯＰキー割り込みベクタ保存 */
	_dos_setvect(0x06, int_stop);	/* ＳＴＯＰキー割り込み設定 */

	t_fget(&func_save);	/* ファンクションキーラベル取り出し */
	puts("\x1b[>1h");	/* 最下位行ユーザ使用 */
	t_csroff();		/* カーソル消去 */

	/* 現在の外字保存 */
	for (i = 0; i < 11; i++)
		t_getfont(0x7621+i, save_gaiji[i]);

	/* 外字セット */
	for (i = 0; i < 11; i++)
		t_setfont(0x7621+i, gaiji[i]);

	if (opt_m == 0) {
		/* パレット設定 */
#ifdef	__TURBOC__
		setrgbpalette(0, 0, 0, 6);
		setrgbpalette(1, 8, 8, 8);
		setrgbpalette(2, 11, 15, 15);

		/* 上下の灰色の部分 */
		setfillstyle(SOLID_FILL, 1);
		bar(0, 0, 639, 15);
		bar(0, 368, 639, 383);
#else
		_remappalette(0, 0x060000L);
		_remappalette(1, 0x080808L);
		_remappalette(2, 0x0f0f0bL);

		/* 上下の灰色の部分 */
		_setcolor(1);
		_rectangle(_GFILLINTERIOR, 0, 0, 639, 15);
		_rectangle(_GFILLINTERIOR, 0, 368, 639, 383);
#endif
	}

#ifdef	__TURBOC__
	/* ライン表示 */
	setcolor(WHITE);
	line(0, 0, 639, 0);
	line(0, 15, 639, 15);
	setcolor(opt_m ? WHITE : 2);
	line(0, 368, 639, 368);
	line(0, 384, 639, 384);

	/* 裏バンクアクティブ（受信データバッファとして使用するため） */
	setactivepage(1);
#else
	/* ライン表示 */
	_setcolor(7);
	_moveto(0, 0);		_lineto(639, 0);
	_moveto(0, 15);		_lineto(639, 15);
	_setcolor(opt_m ? 7 : 2);
	_moveto(0, 368);	_lineto(639, 368);
	_moveto(0, 384);	_lineto(639, 384);

	/* 裏バンクアクティブ（受信データバッファとして使用するため） */
	_setactivepage(1);
#endif
	w_dspstr(scrp, 1, 0, C_GREEN, "◆");
	w_dspstr(scrp, 3, 0, C_YELLOW, "Ｙ－ＴＥＲＭ");
	w_dspstr(scrp, 15, 0, C_GREEN, "◆");

	w_dspstr(scrp, 45, 0, C_WHITE, "DTR● DSR● RTS● CTS● CD● CI●");
	w_dspstr(scrp, 42, 23, C_WHITE, "現在時刻");
	w_dspstr(scrp, 51, 23, C_GREEN, " 0:00:00");
	w_dspstr(scrp, 62, 23, C_WHITE, "通信時間");
	w_dspstr(scrp, 71, 23, C_YELLOW, " 0:00:00");

	dsp_ctrl(1);	/* コントロールライン，時刻表示 */

	tm_open();	/* タイマーオープン */
}

/*=======================================================================
 |
 |		初期化処理２
 |
 |	int	initialize2()
 |
 =======================================================================*/
static	int	initialize2(void)
{
	FILE	*fp;
	char	*vbufp;
	int	st = 0;

	/* センター情報ファイル読み込み */
	if ((fp = fopen(FN_CNT, "rb")) != NULL) {
		fread(center, sizeof(center), 1, fp);
		fclose(fp);
	} else
		st = 1;

	/* 環境設定ファイル読み込み */
	if ((fp = fopen(FN_ENV, "rb")) != NULL) {
		fread(&modem, sizeof(modem), 1, fp);
		fclose(fp);
	} else
		st = 2;

	/* 受信データバッファ１行目クリア */
	FP_SEG(vbufp) = SEG_GVRAM;
	FP_OFF(vbufp) = 0;
	memset(vbufp, 0, 80);

	/* デバイスエラー表示ウィンドウ確保 */
	wp_deverr = w_alloc(44, 7);

	/* デバイスエラーハンドラセット */
#ifdef	__TURBOC__
	harderr(hard_err);
#else
	_harderr(hard_err);
#endif

	return(st);
}

/*=======================================================================
 |
 |		終了処理
 |
 |	void	terminate()
 |
 =======================================================================*/
static	void	terminate(void)
{
	int	i;

	/* 外字復帰 */
	for (i = 0; i < 11; i++)
		t_setfont(0x7621+i, save_gaiji[i]);

	tm_close();		/* タイマー使用終了 */

#ifdef	__TURBOC__
	setactivepage(0);	/* 表バンクアクティブ */
	setrgbpalette(0, 0, 0, 0);	/* パレットセット */
	closegraph();
	clrscr();		/* テキスト画面クリア */
#else
	_setactivepage(0);	/* 表バンクアクティブ */
	_remappalette(0, 0x000000L);	/* パレットセット */
	_setvideomode(_98TEXT80);	/* テキストモードセット */
#endif
	puts("\x1b[>1l");	/* 最下位行システム使用 */
	t_fput(&func_save);	/* ファンクションキーラベル設定 */
	t_csron();		/* カーソル表示 */
	_dos_setvect(0x06, stop_vect);	/* ＳＴＯＰキー割り込みベクタ復帰 */
}

/*=======================================================================
 |
 |		コントロールライン，時刻表示
 |
 |	void	dsp_ctrl(flag)
 |
 |		int	flag;		０：変化があったもののみ表示
 |					１：全てを表示
 |
 =======================================================================*/
void	dsp_ctrl(int flag)
{
	static	int	dtr, dsr, rts, cts, cd, ci;
	int	w_dtr, w_dsr, w_rts, w_cts, w_cd, w_ci;
	long	n_time;
	static	long	s_time;
	struct	tm	*ltm;
	int	itime;
	int	far *vp;
	long	far *vlp;
	char	buf[9], *cp;
	long	on, off;

	if (opt_m == 0) {
		FP_SEG(vlp) = SEG_ATTR;
		on = ((long)C_GREEN << 16) | C_GREEN;
		off = ((long)C_RED << 16) | C_RED;
	} else {
		FP_SEG(vlp) = SEG_TVRAM;
		on = 0x7c817c01;
		off = 0x7b817b01;
	}

	/* ＤＴＲ表示 */
	if ((w_dtr = (rs1_cmdreg & CMD_DTR)) != dtr || flag) {
		FP_OFF(vlp) = 48 * 2;
		*vlp = w_dtr ? on : off;
		dtr = w_dtr;
	}

	/* ＤＳＲ表示 */
	if ((w_dsr = rs1_dsr()) != dsr || flag) {
		FP_OFF(vlp) = 54 * 2;
		*vlp = w_dsr ? on : off;
		dsr = w_dsr;
	}

	/* ＲＴＳ表示 */
	if ((w_rts = (rs1_cmdreg & CMD_DTR)) != rts || flag) {
		FP_OFF(vlp) = 60 * 2;
		*vlp = w_rts ? on : off;
		rts = w_rts;
	}

	/* ＣＴＳ表示 */
	if ((w_cts = rs1_cts()) != cts || flag) {
		FP_OFF(vlp) = 66 * 2;
		*vlp = w_cts ? on : off;
		cts = w_cts;
	}

	/* ＣＤ表示 */
	if ((w_cd = rs1_cd()) != cd || flag) {
		FP_OFF(vlp) = 71 * 2;
		*vlp = w_cd ? on : off;
		cd = w_cd;
	}

	/* ＣＩ表示 */
	if ((w_ci = rs1_ci()) != ci || flag) {
		FP_OFF(vlp) = 76 * 2;
		*vlp = w_ci ? on : off;
		ci = w_ci;
	}

	time(&n_time);

	if (n_time != s_time) {
		/* 現時刻表示 */
		ltm = localtime(&n_time);
		sprintf(buf, "%2u:%02u:%02u"
				, ltm->tm_hour, ltm->tm_min, ltm->tm_sec);
		FP_SEG(vp) = SEG_TVRAM;
		FP_OFF(vp) = (51 + 23 * 80) * 2;
		for (cp = buf; *cp != '\0'; cp++)
			*vp++ = *cp;

		if (conxxxt) {
			/* 通信時間表示 */
			itime = n_time - c_time;
			sprintf(buf, "%2u:%02u:%02u"
				, itime / 3600, (itime / 60) % 60, itime % 60);
			FP_SEG(vp) = SEG_TVRAM;
			FP_OFF(vp) = (71 + 23 * 80) * 2;
			for (cp = buf; *cp != '\0'; cp++)
				*vp++ = *cp;
		}
		s_time = n_time;
	}
}

/*=======================================================================
 |
 |		ＣＯＭＭＡＮＤ．ＣＯＭ起動処理
 |
 |	void	ms_dos()
 |
 =======================================================================*/
static	void	ms_dos(void)
{
	scr_save();		/* 現画面保存 */
	terminate();		/* 終了処理 */
	printf("'EXIT' でＹ－ＴＥＲＭに戻ります.\n");
	spawnlp(0, "COMMAND", "COMMAND", 0L);	/* COMMAND.COM 実行 */
	initialize();		/* 初期化処理 */
	scr_restore();		/* 現画面復帰 */
}

/*=======================================================================
 |
 |		ユーザファンクションコマンド実行処理
 |
 |	void	user_func(cmd)
 |
 |		char	*cmd;		実行コマンド
 |
 =======================================================================*/
static	void	user_func(char *cmd)
{
	scr_save();		/* 現画面保存 */
	terminate();		/* 終了処理 */
	system(cmd);		/* コマンド実行 */
	initialize();		/* 初期化処理 */
	scr_restore();		/* 現画面復帰 */
}

/*=======================================================================
 |
 |		現画面保存処理
 |
 |	void	scr_save()
 |
 =======================================================================*/
static	void	scr_save(void)
{
	int	far *vp;

	/* ＶＲＡＭの内容を保存エリアに転送 */
	FP_OFF(vp) = 160;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(save_text, vp, 160 * 22);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(save_attr, vp, 160 * 22);
}

/*=======================================================================
 |
 |		現画面復帰処理
 |
 |	void	scr_restore()
 |
 =======================================================================*/
static	void	scr_restore(void)
{
	int	far *vp;

	/* ＶＲＡＭに保存エリアの内容を転送 */
	FP_OFF(vp) = 160;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(vp, save_text, 160 * 22);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(vp, save_attr, 160 * 22);
}

/*=======================================================================
 |
 |		バックスクロール処理
 |
 |	void	back_scroll()
 |
 =======================================================================*/
static	void	back_scroll(void)
{
	int	loop_sw, bufc, bufn, i;
	char	*vbufp;

	/* １画面分表示されていなかったら何もしない */
	if (vbufn < 22)
		return;

	if (opt_m == 0) {
#ifdef	__TURBOC__
		setrgbpalette(0, 0, 6, 0);	/* 背景色変更 */
#else
		_remappalette(0, 0x000600L);	/* 背景色変更 */
#endif
	}

	t_csroff();		/* カーソル消去 */
	scr_save();		/* 現画面保存 */
	dsp_func(func0);	/* ファンクションキー表示 */

	bufc = vbufc;		/* バッファカウンタセット */
	bufn = vbufn;		/* バッファ内行数セット */

	loop_sw = 1;
	while (loop_sw) {
		switch (getchr()) {
		case KEY_DOWN:
			if (bufn < vbufn) {
				/* 上スクロール */
				bufn++;
				bufc = ++bufc % MAX_VBUF;
				scroll_up();
				FP_SEG(vbufp) = SEG_GVRAM + bufc * 5;
				FP_OFF(vbufp) = 0;
				w_dspnstr(scrp, 0, 22, C_WHITE, vbufp, 80);
			}
			break;
		case KEY_UP:
			if (bufn - 22 >= 0) {
				/* 下スクロール */
				bufn--;
				bufc = (bufc + MAX_VBUF - 1) % MAX_VBUF;
				scroll_down();
				FP_SEG(vbufp) = SEG_GVRAM +
				((bufc + MAX_VBUF - 21) % MAX_VBUF) * 5;
				FP_OFF(vbufp) = 0;
				w_dspnstr(scrp, 0, 1, C_WHITE, vbufp, 80);
			}
			break;
		case KEY_HOME:
			w_dspspc(scrp, 0, 1, 80*22);
			bufc = (vbufc == vbufn) ? 0 : (vbufc + 1);
			FP_OFF(vbufp) = 0;
			for (i = 0 ; ; i++) {
				FP_SEG(vbufp) = SEG_GVRAM + bufc * 5;
				w_dspnstr(scrp, 0, i+1, C_WHITE, vbufp, 80);
				if (i == 21)
					break;
				bufc = (bufc + 1) % MAX_VBUF;
			}
			bufn = 21;
			break;
		default:
			loop_sw = 0;
			break;
		}
	}

	scr_restore();			/* 現画面復帰 */

	if (opt_m == 0) {
#ifdef	__TURBOC__
		setrgbpalette(0, 0, 0, 6);	/* 背景色変更 */
#else
		_remappalette(0, 0x060000L);	/* 背景色を元に戻す */
#endif
	}
}

/*=======================================================================
 |
 |		ＳＴＯＰキー割り込み処理
 |
 |	void	int_stop()
 |
 =======================================================================*/
static	void	interrupt far	int_stop(void)
{
}

/*=======================================================================
 |
 |		１文字送信（送信タイムアウトチェック付き）
 |
 |	void	rs1_putc(c)
 |
 |		int	c;		送信文字
 |
 =======================================================================*/
void	rs1_putc(int c)
{
	if ((rs1_sts() & STS_TXRDY) == 0) {
		tm_set(1, 300);		/* ３秒 */
		while ((rs1_sts() & STS_TXRDY) == 0) {
			if (tm_check(1))
				return;
		}
		tm_stop(1);
	}
	rs1_put(c);
}

#ifdef	__TURBOC__
int	hard_err(unsigned deverror, unsigned errcode, unsigned far *devhdr)
#else
void far hard_err(unsigned deverror, unsigned errcode, unsigned far *devhdr)
#endif
{
	int	mode, ch;
	char	*msg;

	if (deverror & 0x8000) {
#ifdef	__TURBOC__
		hardresume(3);
#else	
		_hardresume(3);
#endif
	}

	switch (errcode) {
	case 0:
		msg = "フロッピーが書き込み禁止になっています";
		break;
	case 2:
		msg = "ドライブの準備ができていません";
		break;
	default:
		msg = "ファイルアクセスでエラーが発生しました";
		break;
	}

	w_display(wp_deverr, 34, 15, "ファイルアクセスエラー", RC_WHITE);
	w_dspstr(wp_deverr, 3, 3, RC_WHITE, msg);
	w_dspstr(wp_deverr, 5, 5, RC_WHITE, "再試行  中止");

	mode = 0;
	for (;;) {
		if (mode == 0)
			w_color(wp_deverr, 5, 5, 6, C_CYAN);
		else
			w_color(wp_deverr, 13, 5, 4, C_CYAN);
		ch = getch();
		if (ch == KEY_CR)
			break;
		if (mode == 0)
			w_color(wp_deverr, 5, 5, 6, C_WHITE);
		else
			w_color(wp_deverr, 13, 5, 4, C_WHITE);
		if (ch == KEY_RIGHT || ch == KEY_LEFT)
			mode = (mode + 1) % 2;
	}

	w_erase(wp_deverr);

#ifdef	__TURBOC__
	hardresume(mode ? 3 : 1);
	return(2);
#else
	_hardresume(mode ? 3 : 1);
#endif
}
