/************************************************************************
 *									*
 *		ＪＴＩ－８８１２　容器検査プログラム			*
 *									*
 *		ファイル名	: jti8812.c				*
 *		作成者		: s.aizawa				*
 *		作成日		: 88/12/31				*
 *									*
 ************************************************************************/

#include	<stdio.h>
#include	<stdlib.h>
#include	<string.h>
#include	<ctype.h>

/*	ＩＣカードデータファイル名	*/

#define	FN_DATA		"I:JTI8812.DAT"

/*	キーコード定義		*/

#define	KEY_STOP	0x03
#define	KEY_BS		0x08
#define	KEY_CR		0x0d
#define	KEY_RIGHT	0x1c
#define	KEY_LEFT	0x1d
#define	KEY_UP		0x1e
#define	KEY_DOWN	0x1f
#define	KEY_3ZERO	0xfe

/*	ＢＩＯＳエントリ	*/

#define	BIOS_CONST	(char)1
#define	BIOS_CONIN	(char)2
#define	BIOS_CONOUT	(char)3
#define	BIOS_TIMDAT	(char)25
#define	BIOS_RSIOX	(char)27

/*	ＨＣ－４５システムエリア	 */

#define	ATSHUTOFF	*(char *)0xef40
#define	IT3ZERO		*(char *)0xf0f2
#define	IFRCECNT	*(char *)0xf312

/*	データバッファ	*/

struct	kdata	{
	char	date[8];
	char	time[5];
	char	j_code[8];
	struct	{
		char	flag;
		char	tank_no[8];
		char	shubetu;
		char	aturyoku[8][11];
		char	ondo[4][11];
		char	hantei;
	} rdata[5];
} kbuf;

/*	固定部画面表示テーブル	*/

struct	scrdat	{
	char	pos_x;			/* 表示桁位置 */
	char	pos_y;			/* 表示行位置 */
	char	*data;			/* 表示データ */
};
typedef	struct scrdat SCRDAT;

/*	ガイドメッセージ 	*/

static	char	msg00[] = "ｼｮﾘ ｦ ｾﾝﾀｸ ｼﾃｸﾀﾞｻｲ";
static	char	msg01[] = "ｲﾝｻﾂ ｼﾏｽ｡ ｼﾞｭﾝﾋﾞ ﾊ ﾖﾛｼｲﾃﾞｽｶ  [Y]/N";
static	char	msg10[] = "ﾋﾂﾞｹ ｦ ﾆｭｳﾘｮｸ ｼﾃｸﾀﾞｻｲ";
static	char	msg11[] = "ｼﾞｺｸ ｦ ﾆｭｳﾘｮｸ ｼﾃｸﾀﾞｻｲ";
static	char	msg12[] = "ｼﾞｷﾞｮｳｼｮ ｺｰﾄﾞ ｦ ﾆｭｳﾘｮｸ ｼﾃｸﾀﾞｻｲ";
static	char	msg13[] = "ﾖﾛｼｲﾃﾞｽｶ  [Y]/N";
static	char	msg20[] = "ﾀﾝｸ No ｦ ﾆｭｳﾘｮｸ ｼﾃｸﾀﾞｻｲ  (E:ｼｭｳﾘｮｳ)";
static	char	msg21[] = "ｼｭﾍﾞﾂ ｦ ｼﾃｲ ｼﾃｸﾀﾞｻｲ  1:ﾀﾝｸ / 2:ﾊｲｶﾝ";
static	char	msg22[] = "ﾃﾞｰﾀ ｦ ｼﾞｭｼﾝ ｼﾏｽ｡ ﾖﾛｼｲﾃﾞｽｶ  [Y]/N";
static	char	msg23[] = "ｹｯｶ ｦ ｲﾝｻﾂ ｼﾏｽｶ (1 ﾏｲﾒ)  [Y]/N";
static	char	msg24[] = "ｲﾝｻﾂﾁｭｳ !!";
static	char	msg30[] = "ﾃﾞｰﾀ ｼﾞｭｼﾝﾁｭｳ  (A:ﾁｭｳｼ)";
static	char	msg31[] = "ｼﾞｭｼﾝ ｼｭｳﾘｮｳ｡ ｶｸﾆﾝ ﾊ ﾖﾛｼｲﾃﾞｽｶ  [Y]";
static	char	msg90[] = "ﾌﾟﾛｸﾞﾗﾑ ｦ ﾁｭｳｼ ｼﾏｽ｡ ﾖﾛｼｲﾃﾞｽｶ  [Y]/N";

/*	エラーメッセージ	*/

static	char	EM_ICCARD[] = "IC ｶｰﾄﾞ ｶﾞ ｱｸｾｽ ﾃﾞｷﾏｾﾝ";
static	char	EM_SEND[] = "ﾃﾞｰﾀ ｶﾞ ｿｳｼﾝ ﾃﾞｷﾏｾﾝ";
static	char	EM_DATA[] = "ｼﾞｭｼﾝ ｼﾀ ﾃﾞｰﾀ ｶﾞ ｲｼﾞｮｳﾃﾞｽ";
static	char	EM_PRINT[] = "ﾌﾟﾘﾝﾀｰ ｴﾗｰ  ([R]:ｻｲｼﾞｯｺｳ A:ﾁｭｳｼ)";

/*	内部関数定義	*/

void	select(void), initial(void), set_datetime(void);
char	inp_date(void), inp_time(void), inp_jcode(void);
void	set_data(void);
char	inp_tankno(char);
void	inp_shubetu(char);
char	receive(char);
void	fmc_data(char *, char *), hantei(char);
void	print(void);
void	endmsg(void);
char	inputs(char *, char, char, char, char), kakunin(char *);
void	dsp_scr(SCRDAT *), dsp_msg(char *), dsp_err(char *);
void	dsp_str(char, char, char *);
void	dsp_nstr(char, char, char *, char);
void	locate(char, char), csr_on(void), csr_off(void), beep(void);
char	input(void);
void	rs_open(void), rs_close(void), rs_clear(void);
char	rs_get(char *, int), rs_put(char *, int);
void	cputs(char *);
char	_bios();

/*	共通データ	*/

FILE	*ic_fp;			/* ＩＣカードデータファイルポインタ */
double	hantei_ti[5];		/* 判定値セーブエリア */
char	*sv_msg;		/* ガイドメッセージセーブエリア */
char	sv_x, sv_y;		/* カーソル位置セーブエリア */
char	dcount;			/* 取り込んだデータ数 */


/*=======================================================================
 *
 *		メイン処理
 *
 *	void	main()
 *
 =======================================================================*/
void	main()
{
	memset((char *)&kbuf, ' ', sizeof(kbuf)); /* バッファ初期化 */

	/* ＩＣカードデータファイルオープン */
	if ((ic_fp = fopen(FN_DATA, "r+")) == 0) {
		/* 新規作成 */
		if ((ic_fp = fopen(FN_DATA, "w+")) == 0) {
			printf(EM_ICCARD);
			exit(1);
		}
		dcount = 0;
	} else {
		/* ＬＳＩカードデータ読み込み */
		fread(&kbuf, sizeof(kbuf), 1, ic_fp);

		/* 既存のデータの判定値をセット */
		for (dcount = 0; dcount < 5; dcount++) {
			if (kbuf.rdata[dcount].flag != 'A')
				break;
			hantei(dcount);
		}
	}

	ATSHUTOFF = 0;		/* auto shut off 禁止 */
	IT3ZERO = KEY_3ZERO;	/* "000"キーのコード設定 */
	IFRCECNT = 0;		/* リスタートモードセット */

	select();		/* 処理選択 */

	fclose(ic_fp);		/* ＩＣカードデータファイルクローズ */

	endmsg();		/* 終了メッセージ表示 */

	ATSHUTOFF = 5;		/* auto shut off セット */
}

/*=======================================================================
 *
 *		処理選択処理
 *
 *	void	select()
 *
 =======================================================================*/
void	select()
{
	static	SCRDAT	scr[] = {
		{5, 1, "*** JTI-8812 ﾖｳｷ ｹﾝｻ ﾌﾟﾛｸﾞﾗﾑ ***"},
		{12, 3, "1 ﾃﾞｰﾀ ﾄﾘｺﾐ"},
		{12, 4, "2 ｶｸﾆﾝ ﾌﾟﾘﾝﾄ"},
		{12, 6, "9 ｼｭｳﾘｮｳ"},
		{0 ,0, 0}
	};
	char	c;

	dsp_scr(scr);		/* 画面表示 */

	for (;;) {
		dsp_msg(msg00);
		locate(40, 8);

		csr_on();		/* カーソル表示 */
		c = input();		/* １文字入力 */
		csr_off();		/* カーソル消去 */
		switch (c) {
		case KEY_CR:
		case '1':
			if (dcount == 0)
				initial();	/* 初期設定処理 */
			set_data();		/* データ設定／受信処理 */
			dsp_scr(scr);		/* 画面表示 */
			break;
		case '2':
			if (kakunin(msg01) != 0) {
				dsp_msg(msg24);
				print();
			}
			break;
		case '9':
			return;
		}
	}
}

/*=======================================================================
 *
 *		初期設定処理
 *
 *	void	initial()
 *
 =======================================================================*/
void	initial()
{
	static	SCRDAT	scr[] = {
		{5, 1, "*** JTI-8812 ﾖｳｷ ｹﾝｻ ﾌﾟﾛｸﾞﾗﾑ ***"},
		{8, 3, "ﾋﾂﾞｹ(YY/MM/DD)"},
		{8, 4, "ｼﾞｺｸ(HH:MM)"},
		{8, 5, "ｼﾞｷﾞｮｳｼｮ ｺｰﾄﾞ  ________"},
		{0 ,0, 0}
	};
	char	select;

	dsp_scr(scr);		/* 画面表示 */

	set_datetime();		/* 日付／時刻セット */
	dsp_nstr(23, 3, kbuf.date, 8);	/* 現日付表示 */
	dsp_nstr(23, 4, kbuf.time, 5);	/* 現時刻表示 */
	memset(kbuf.j_code, ' ', 80);	/* 事業所コードクリア */

	select = 0;
	for (;;) {
		switch (select) {
		case 0:
			switch (inp_date()) {	/* 日付入力 */
			case KEY_CR:
			case KEY_DOWN:
				select = 1;
				break;
			}
			break;
		case 1:
			switch (inp_time()) {	/* 時間入力 */
			case KEY_CR:
			case KEY_DOWN:
				select = 2;
				break;
			case KEY_UP:
				select = 0;
				break;
			}
			break;
		case 2:
			switch (inp_jcode()) {	/* 事業所コード入力 */
			case KEY_CR:
				if (kakunin(msg13) != 0)
					return;
				else
					select = 0;
				break;
			case KEY_UP:
				select = 1;
				break;
			}
			break;
		}
	}
}

/*=======================================================================
 *
 *		日付／時刻セット処理
 *
 *	void	set_datetime()
 *
 =======================================================================*/
void	set_datetime()
{
	char	buf[12];

	bios(BIOS_TIMDAT, 0, (int)buf);	/* 日付／時刻読み出し */

	/* 日付セット */
	kbuf.date[0] = (buf[0] >> 4) | '0';
	kbuf.date[1] = (buf[0] & 0x0f) | '0';
	kbuf.date[2] = '/';
	kbuf.date[3] = (buf[1] >> 4) | '0';
	kbuf.date[4] = (buf[1] & 0x0f) | '0';
	kbuf.date[5] = '/';
	kbuf.date[6] = (buf[2] >>4) | '0';
	kbuf.date[7] = (buf[2] & 0x0f) | '0';

	/* 時刻セット */
	kbuf.time[0] = (buf[3] >> 4) | '0';
	kbuf.time[1] = (buf[3] & 0x0f) | '0';
	kbuf.time[2] = ':';
	kbuf.time[3] = (buf[4] >> 4) | '0';
	kbuf.time[4] = (buf[4] & 0x0f) | '0';
}

/*=======================================================================
 *
 *		日付入力処理
 *
 *	char	inp_date()
 *
 =======================================================================*/
char	inp_date()
{
	dsp_msg(msg10);
	return(inputs(kbuf.date, 23, 3, 8, 0x05));
}

/*=======================================================================
 *
 *		時刻入力処理
 *
 *	char	inp_time()
 *
 =======================================================================*/
char	inp_time()
{
	dsp_msg(msg11);
	return(inputs(kbuf.time, 23, 4, 5, 0x05));
}

/*=======================================================================
 *
 *		事業所コード入力処理
 *
 *	char	inp_jcode()
 *
 =======================================================================*/
char	inp_jcode()
{
	char	c;

	dsp_msg(msg12);		/* ガイドメッセージ表示 */

	for (;;) {
		/* 事業所コード入力 */
		c = inputs(kbuf.j_code, 23, 5, 8, 0x04);
		if (kbuf.j_code[0] == ' ' && c == KEY_CR)
			beep();
		else
			break;
	}
	return(c);
}

/*=======================================================================
 *
 *		データ設定処理
 *
 *	void	set_data()
 *
 =======================================================================*/
void	set_data()
{
	static	SCRDAT	scr[] = {
		{1, 1, "ｼﾞｷﾞｮｳｼｮ ｺｰﾄﾞ"},
		{7, 2, "ﾀﾝｸ No  ｼｭﾍﾞﾂ    ﾊﾝﾃｲﾁ    ｹｯｶ"},
		{0, 0, 0}
	};
	char	i, cl, st, buf[42];

	cl = 0;
	for (;;) {
		dsp_scr(scr);				/* 画面表示 */
		dsp_nstr(15, 1, kbuf.j_code, 8);	/* 事業所コード表示 */
		dsp_nstr(27, 1, kbuf.date, 8);		/* 日付表示 */
		dsp_nstr(36, 1, kbuf.time, 5);		/* 時刻表示 */
		for (i = 0; i < dcount; i++) {
			fp_sprintf(buf, "%d %.8s  %s  %7.2f  %s",
				i+1, kbuf.rdata[i].tank_no,
				kbuf.rdata[i].shubetu == '1'
						? "ﾀﾝｸ " : "ﾊｲｶﾝ",
				hantei_ti[i],
				kbuf.rdata[i].hantei == 'N'
						? "ｲｼﾞｮｳﾅｼ" : "ｲｼﾞｮｳｱﾘ");
			dsp_str(4, i+3, buf);
		}
		if (cl != 0) {
			sprintf(buf, "%d %.8s  %s",
				i+1, kbuf.rdata[i].tank_no,
				kbuf.rdata[i].shubetu == '1'
						? "ﾀﾝｸ " : "ﾊｲｶﾝ");
			dsp_str(4, i+3, buf);
			i++;
		}
		for ( ; i < 5; i++) {
			sprintf(buf, "%d ________  ____", i+1);
			dsp_str(4, i+3, buf);
		}
retry:
		if (dcount == 5)
			break;

		if (inp_tankno(dcount) == 0)
			break;

		inp_shubetu(dcount);

		if (kakunin(msg22) != 0) {
			/* ＲＳ－２３２Ｃオープン */
			rs_open();

			/* データ受信 */
			st = receive(dcount);

			/* ＲＳ－２３２Ｃクローズ */
			rs_close();

			if (st == 0) {
				kbuf.rdata[dcount].flag = 'A';
				rewind(ic_fp);
				fwrite(&kbuf, sizeof(kbuf), 1, ic_fp);
				fflush(ic_fp);
				hantei(dcount);
				dcount++;
				cl = 0;
			} else
				cl = 1;
		} else
			goto retry;
	}

	/* 印刷 */
	for (i = 0; i < 3; i++) {
		msg23[17] = i + '1';
		if (kakunin(msg23) == 0)
			break;
		dsp_msg(msg24);
		print();
	}
}

/*=======================================================================
 *
 *		タンクＮｏ入力処理
 *
 *	char	inp_tankno(count)
 *
 *		char	count;		データＮｏ
 *
 *		char	返値		０：’Ｅ’入力　１：タンクＮｏ入力
 *
 =======================================================================*/
char	inp_tankno(count)
char	count;
{
	dsp_msg(msg20);		/* ガイドメッセージ表示 */

	for (;;) {
		if (inputs(kbuf.rdata[count].tank_no, 6, count+3, 8, 0x02) == 'E')
			return(0);

		if (kbuf.rdata[count].tank_no[0] == ' ')
			beep();
		else
			return(1);
	}
}

/*=======================================================================
 *
 *		種別入力処理
 *
 *	void	inp_shubetu(count)
 *
 *		char	count;		データＮｏ
 *
 =======================================================================*/
void	inp_shubetu(count)
char	count;
{
	char	c;

	dsp_msg(msg21);		/* ガイドメッセージ表示 */

	for (;;) {
		locate(16, count+3);		/* カーソル位置セット */
		csr_on();			/* カーソル表示 */
		c = input();			/* １文字入力 */
		csr_off();
		switch(c) {
		case KEY_CR:
			if (kbuf.rdata[count].shubetu != ' ')
				return;
			beep();
			break;
		case '1':		/* タンク */
			dsp_str(16, count+3, "ﾀﾝｸ ");
			kbuf.rdata[count].shubetu = '1';
			return;
		case '2':		/* 配管 */
			dsp_str(16, count+3, "ﾊｲｶﾝ");
			kbuf.rdata[count].shubetu = '2';
			return;
		default:
			beep();
		}
	}
}

/*=======================================================================
 *
 *		データ受信処理
 *
 *	char	receive(count)
 *
 *		char	count;		データＮｏ
 *
 *		char	返値		０：正常　１：異常
 *
 =======================================================================*/
char	receive(count)
char	count;
{
	static	SCRDAT	scr[] = {
		{3, 1, "ﾃﾞｰﾀ ｼﾞｭｼﾝ  ﾀﾝｸ No          ｼｭﾍﾞﾂ"},
		{2, 2, "ｱﾂﾘｮｸ   ･･･････････   ･･･････････"},
		{10, 3, "･･･････････   ･･･････････"},
		{10, 4, "･･･････････   ･･･････････"},
		{10, 5, "･･･････････   ･･･････････"},
		{2, 6, "ｵﾝﾄﾞ    ･･･････････   ･･･････････"},
		{10, 7, "･･･････････   ･･･････････"},
		{0, 0, 0}
	};
	static	char	start[] = "SSSSSSSSSSSSSSSSSSSSSSSSS";
	static	char	end[] = "*****\x1a\x1a\x1a";
	char	i;
	char	buf[14];

	dsp_scr(scr);				/* 画面表示 */

	/* 番号表示 */
	locate(1, 1);
	bios(BIOS_CONOUT, count + '1');

	/* タンクＮｏ表示 */
	dsp_nstr(22, 1, kbuf.rdata[count].tank_no, 8);

	/* 種別表示 */
	dsp_str(37, 1, kbuf.rdata[count].shubetu == '1' ? "ﾀﾝｸ" : "ﾊｲｶﾝ");

	dsp_msg(msg30);			/* ガイドメッセージ表示 */

	rs_clear();			/* ＲＳ－２３２Ｃ受信バッファクリア */

	/* スタートコマンド送信 */
	if (rs_put(start, 25) != 0) {
		dsp_err(EM_SEND);
		return(1);
	}

	/* 圧力データ受信 */
	for (i = 0; i < 8; i++) {
		if (rs_get(kbuf.rdata[count].aturyoku[i], 11) != 0)
			return(1);
		fmc_data(kbuf.rdata[count].aturyoku[i], buf);
		dsp_str((i%2)*14+9, i/2+2, buf);
	}

	/* 温度データ受信 */
	for (i = 0; i < 4; i++) {
		if (rs_get(kbuf.rdata[count].ondo[i], 11) != 0)
			return(1);
		fmc_data(kbuf.rdata[count].ondo[i], buf);
		dsp_str((i%2)*14+9, i/2+6, buf);
	}

	/* エンドマーク受信 */
	if (rs_get(buf, 5) != 0)
		return(1);

	/* エンドマークチェック */
	if (strncmp(buf, end, 5) != 0) {
		dsp_err(EM_DATA);
		return(1);
	}

	for (;;) {
		if (kakunin(msg31) != 0)
			break;
	}

	return(0);
}

/*=======================================================================
 *
 *		圧力／温度データ表示形式変換処理
 *
 *	void	fmc_data(x, y, data)
 *
 *		char	*rdata;		受信データ
 *		char	*ddata;		表示データ
 *
 =======================================================================*/
void	fmc_data(rdata, ddata)
char	*rdata, *ddata;
{
	/* データコピー */
	memcpy(ddata+1, rdata, 11);
	ddata[12] = '\0';

	if (rdata[0] & 0x80) {		/* 符号判定 */
		/* プラス */
		ddata[1] &= 0x7f;	/* 符号ビットクリア */
		ddata[0] = '-';
	} else {
		/* マイナス */
		ddata[0] = ' ';
	}
}

/*=======================================================================
 *
 *		判定処理
 *
 *	void	hantei(count)
 *
 *		char	count;		データＮｏ
 *
 *		double	返値		判定値
 *
 =======================================================================*/
void	hantei(count)
char	count;
{
	double	atu15, atu30, hanteiti;
	char	buf[13];

	/* １５分と３０分のデータを浮動小数データに変換 */
	fmc_data(kbuf.rdata[count].aturyoku[4], buf);	/* １５分のデータ */
	atu15 = atof(buf);
	fmc_data(kbuf.rdata[count].aturyoku[7], buf);	/* ３０分のデータ */
	atu30 = atof(buf);

	hanteiti = atu15 - atu30;		/* 判定値計算 */

	/* 判定 */
	if (kbuf.rdata[count].shubetu == '1') {
		/* タンク */
		if (hanteiti <= 6)
			kbuf.rdata[count].hantei = 'N';
		else
			kbuf.rdata[count].hantei = 'E';
	} else {
		/* 配管 */
		if (hanteiti <= 15)
			kbuf.rdata[count].hantei = 'N';
		else
			kbuf.rdata[count].hantei = 'E';
	}

	hantei_ti[count] = hanteiti;	/* 判定値セーブ */
}

/*=======================================================================
 *
 *		印刷処理
 *
 *	void	print()
 *
 =======================================================================*/
void	print()
{
	FILE	*fp;
	char	loop_sw, count, i;
	char	buf[13];

	for (;;) {
		if ((fp = fopen("LST:", "w")) == 0) {
			dsp_err(EM_PRINT);
			loop_sw = 1;
			while (loop_sw) {
				switch (input()) {
				case 0x0d:
				case 'R':
					loop_sw = 0;
					break;
				case 'A':
					return;
				default:
					beep();
				}
			}
		} else
			break;
	}

	fprintf(fp, "\n");
	fprintf(fp, "**********************\n");
	fprintf(fp, "*  JTI-8812 ｹﾝｻ ｹｯｶ  *\n");
	fprintf(fp, "**********************\n\n");

	fprintf(fp, "ﾋﾂﾞｹ : %.8s    ｼﾞｺｸ : %.5s\n", kbuf.date, kbuf.time);
	fprintf(fp, "ｼﾞｷﾞｮｳｼｮ ｺｰﾄﾞ : %.8s\n\n", kbuf.j_code);

	for (count = 0; count < 5; count++) {
		if (kbuf.rdata[count].flag != 'A')
			continue;
		fprintf(fp, "ﾀﾝｸ No : %.8s\n", kbuf.rdata[count].tank_no);
		fprintf(fp, "ｱﾂﾘｮｸ  : ");
		for (i = 0; i < 4; i++) {
			fmc_data(kbuf.rdata[count].aturyoku[i], buf);
			fprintf(fp, "%s ", buf);
		}
		fprintf(fp, "\n");
		fprintf(fp, "         ");
		for (i = 4; i < 8; i++) {
			fmc_data(kbuf.rdata[count].aturyoku[i], buf);
			fprintf(fp, "%s ", buf);
		}
		fprintf(fp, "\n");
		fprintf(fp, "ｵﾝﾄﾞ   : ");
		for (i = 0; i < 4; i++) {
			fmc_data(kbuf.rdata[count].ondo[i], buf);
			fprintf(fp, "%s ", buf);
		}
		fprintf(fp, "\n\n");
		fp_fprintf(fp, "ﾊﾝﾃｲﾁ  : %7.2f       ﾊﾝﾃｲ : %s %s\n",
		    hantei_ti[count],
		    kbuf.rdata[count].shubetu == '1' ? "ﾀﾝｸ" : "ﾊｲｶﾝ",
		    kbuf.rdata[count].hantei == 'N' ? "ｲｼﾞｮｳﾅｼ" : "ｲｼﾞｮｳｱﾘ");
		fprintf(fp, "\n\n");
	}
	fprintf(fp, "\n\nｿｸﾃｲｼｬ :\n\n\nﾀﾁｱｲｼｬ :\n\n\n\n");
	fclose(fp);
}

/*=======================================================================
 *
 *		終了メッセージ表示処理
 *
 *	void	endmsg()
 *
 =======================================================================*/
void	endmsg()
{
	static	SCRDAT	scr[] = {
		{11, 3, "< ﾌﾟﾛｸﾞﾗﾑ ｼｭｳﾘｮｳ >"},
		{0, 0, 0}
	};
	int	i;

	dsp_scr(scr);		/* 終了メッセージ表示 */

	/* 時間待ちダミーループ */
	for (i = 0; i < 30000; i++);
		;
}


/*=======================================================================
 *
 *		文字列入力
 *
 *	char	inputs(buf, x, y, len, flag)
 *
 *		char	*buf;		入力バッファ
 *		char	x;		入力桁位置
 *		char	y;		入力行位置
 *		char	len;		入力文字数
 *		char	flag;		bit0　0/1 = 通常／日付時刻
 *					bit1  0/1 = 通常／'E'で終了
 *					bit2  0/1 = UP,DOWNキー無効／有効
 *
 =======================================================================*/
char	inputs(buf, x, y, len, flag)
char	*buf;
char	x, y, len;
char	flag;
{
	char	c;		/* 入力文字 */
	char	count;		/* 入力文字数カウンタ */
	char	loop_sw;	/* ループ制御スイッチ */
	char	zero;

	csr_on();		/* カーソル表示 */

	zero = 0;
	count = 0;
	loop_sw = 1;
	while(loop_sw) {
		locate(x+count, y);
		if (zero != 0) {
			c = '0';
			zero--;
		} else
			c = input();	/* キー入力 */

		switch (c) {
		case KEY_CR:		/* ＣＲキー */
			loop_sw = 0;
			break;
		case KEY_UP:		/* cursor up */
		case KEY_DOWN:		/* cursor down */
			if (flag & 0x04)
				loop_sw = 0;
			else
				beep();
			break;
		case KEY_BS:		/* back space */
		case KEY_LEFT:		/* cursor left */
			if (count != 0) {
				count--;
				if ((flag & 0x01) != 0 && (count % 3) == 2)
					count--;
			}
			break;
		case KEY_RIGHT:		/* cursor right */
			if (count < len-1) {
				count++;
				if ((flag & 0x01) != 0 && (count % 3) == 2)
					count++;
			}
			break;
		case 'E':
			if ((flag & 0x02) != 0 && count == 0)
				loop_sw = 0;
			else
				beep();
			break;
		case KEY_3ZERO:
			zero = 3;
			break;
		default:
			if (count < len && (isdigit(c) || c == ' ')) {
				bios(BIOS_CONOUT, c);
				buf[count] = c;
				if (count < len-1) {
					count++;
					if ((flag & 0x01) != 0
							&& (count % 3) == 2)
						count++;
				}
			} else
				beep();
			break;
		}
	}

	csr_off();		/* カーソル消去 */

	return(c);
}

/*=======================================================================
 *
 *		固定部画面表示
 *
 *	void	dsp_scr(scrdat)
 *
 *		SCRDAT	*scrdat;	固定部画面データ
 *
 =======================================================================*/
void	dsp_scr(scrdat)
SCRDAT	*scrdat;
{
	cputs("\x1b*");		/* 画面消去 */

	/* 画面表示 */
	while (scrdat->data) {
		dsp_str(scrdat->pos_x, scrdat->pos_y, scrdat->data);
		scrdat++;
	}
}

/*=======================================================================
 *
 *		ガイドメッセージ表示
 *
 *	void	dsp_msg(msg)
 *
 *		char	*msg;		入力ガイドメッセージ
 *
 =======================================================================*/
void	dsp_msg(msg)
char	*msg;
{
	char	buf[46];

	sprintf(buf, "\x1b0 %-39s\x1b1", msg);
	dsp_str(1, 8, buf);
	sv_msg = msg;
}

/*=======================================================================
 *
 *		エラーメッセージ表示
 *
 *	void	dsp_err(msg)
 *
 *		char	*msg;		入力ガイドメッセージ
 *
 =======================================================================*/
void	dsp_err(msg)
char	*msg;
{
	beep();
	dsp_msg(msg);		/* エラーメッセージ表示 */
	input();		/* １文字入力待 */
}

/*=======================================================================
 *
 *		確認処理
 *
 *	char	kakunin(msg)
 *
 *		char	*msg;		ガイドメッセージ
 *
 *		返値			１：ＹｏｒＣＲ　０：Ｎ
 *
 =======================================================================*/
char	kakunin(msg)
char	*msg;
{
	char	c;

	dsp_msg(msg);
	locate(40, 8);
	for (;;) {
		csr_on();		/* カーソル表示 */
		c = input();		/* １文字入力 */
		csr_off();		/* カーソル消去 */
		switch (c) {
		case KEY_CR:
		case 'Y':
			return((char)1);
		case 'N':
			return((char)0);
		}
	}
}

/*=======================================================================
 *
 *		文字列表示
 *
 *	void	dsp_str(x, y, str)
 *
 *		char	x;		表示桁位置
 *		char	y;		表示行位置
 *		char	*str;		表示データ
 *
 =======================================================================*/
void	dsp_str(x, y, str)
char	x, y;
char	*str;
{
	locate(x, y);
	while (*str)
		bios(BIOS_CONOUT, *str++);
}

/*=======================================================================
 *
 *		文字列表示（長さ指定）
 *
 *	void	dsp_nstr(x, y, str, len)
 *
 *		char	x;		表示桁位置
 *		char	y;		表示行位置
 *		char	*str;		表示データ
 *		char	len;		長さ
 *
 =======================================================================*/
void	dsp_nstr(x, y, str, len)
char	x, y;
char	*str;
char	len;
{
	locate(x, y);

	while (len--)
		bios(BIOS_CONOUT, *str++);
}

/*=======================================================================
 *
 *		カーソル位置セット
 *
 *	void	locate(x, y)
 *
 *		char	x;		桁位置
 *		char	y;		行位置
 *
 =======================================================================*/
void	locate(x, y)
char	x, y;
{
	char	buf[6];

	sprintf(buf, "\x1b=%c%c", y+0x1f, x+0x1f);
	cputs(buf);

	sv_x = x;
	sv_y = y;
}

/*=======================================================================
 *
 *		カーソル表示
 *
 *	void	csr_on()
 *
 =======================================================================*/
void	csr_on()
{
	cputs("\x1b3");
}

/*=======================================================================
 *
 *		カーソル消去
 *
 *	void	csr_off()
 *
 =======================================================================*/
void	csr_off()
{
	cputs("\x1b2");
}

/*=======================================================================
 *
 *		キーボード１文字入力処理
 *
 *	char	input()
 *
 =======================================================================*/
char	input()
{
	char	c, x, y, buf[46];

	for (;;) {
		c = bios(BIOS_CONIN);		/* キー入力 */
		if (c == KEY_STOP) {
			x = sv_x;
			y = sv_y;
			sprintf(buf, "\x1b0 %-39s\x1b1", msg90);
			dsp_str(1, 8, buf);
			for (;;) {
				locate(40, 8);
				c = bios(BIOS_CONIN);
				if (c == KEY_CR || c == 'Y') {
					ATSHUTOFF = 5;
					exit(0);
				} else if (c == 'N') {
					dsp_msg(sv_msg);
					locate(x, y);
					break;
				}
			}
		} else
			break;
	}
	return(c);
}
	
/*=======================================================================
 *
 *		ＲＳ－２３２Ｃオープン処理
 *
 *	void	rs_open()
 *
 =======================================================================*/
void	rs_open()
{
	static	char	rsbuf[256];	/* ＲＳ２３２Ｃ受信バッファ */
	static	struct	{		/* ＲＳ－２３２Ｃオープンパラメータ */
		char	*rbufp;		/* 受信バッファの先頭アドレス */
		int	rbufsiz;	/* 受信バッファの大きさ */
		char	bit_rate;	/* ビットレート */
		char	chr_leng;	/* キャラクタ長 */
		char	parity;		/* パリティチェック */
		char	stop_bit;	/* ストップビット */
		char	spc_prm;	/* スペシャルパラメータ */
	} rs_param = {
		rsbuf,			/* 受信バッファ */
		sizeof(rsbuf),		/* 受信バッファサイズ */
		0x06,			/* 300 bps */
		0x03,			/* 8 bit/character */
		0x00,			/* non parity */
		0x01,			/* 1 stop bit */
		0x17			/* DTR ON, RTS ON */
	};
	char	ret_inf[9];

	memcpy(ret_inf, &rs_param, 9);
	_bios(BIOS_RSIOX, 0x1000, 0, ret_inf); /* ＲＳ－２３２Ｃオープン */
}

/*=======================================================================
 *
 *		データ送信（ＲＳ－２３２Ｃ）
 *
 *	char	rs_put(buf, len)
 *
 *		char	*buf;		送信データバッファ
 *		int	len;		送信データ長
 *
 *		char	返値		０：正常　１：異常
 *
 =======================================================================*/
char	rs_put(buf, len)
char	*buf;
int	len;
{
	char	ret_inf[9];

	while (len--) {
		/* １文字送信 */
		_bios(BIOS_RSIOX, 0x6000 | (int)*buf++, 0, ret_inf);
	}
	return(0);
}

/*=======================================================================
 *
 *		データ受信（ＲＳ－２３２Ｃ）
 *
 *	char	rs_get(buf, len)
 *
 *		char	*buf;		受信データバッファ
 *		int	len;		受信データ長
 *
 *		char	返値		０：正常　１：異常
 *
 =======================================================================*/
char	rs_get(buf, len)
char	*buf;
int	len;
{
	char	ret_inf[9];

	while (len) {
		/* キーボード入力チェック */
		if (_bios(BIOS_CONST) != 0) {
			/* 'A'が入力されたら中止 */
			if (bios(BIOS_CONIN) == 'A')
				return(1);
		}

		/* ＲＳ－２３２Ｃ受信チェック */
		if (_bios(BIOS_RSIOX, 0x3000, 0, ret_inf) == 0xff) {
			/* １文字受信 */
			*buf++ = _bios(BIOS_RSIOX, 0x5000, 0, ret_inf);
			len--;
		}
	}
	return(0);
}

/*=======================================================================
 *
 *		ＲＳ－２３２Ｃ受信バッファクリア
 *
 *	void	rs_clear()
 *
 =======================================================================*/
void	rs_clear()
{
	char	ret_inf[9];

	/* 受信文字があったらすてる */
	while (_bios(BIOS_RSIOX, 0x3000, 0, ret_inf) == 0xff) {
		/* １文字受信 */
		_bios(BIOS_RSIOX, 0x5000, 0, ret_inf);
	}
}

/*=======================================================================
 *
 *		ＲＳ－２３２Ｃクローズ処理
 *
 *	void	rs_close()
 *
 =======================================================================*/
void	rs_close()
{
	_bios(BIOS_RSIOX, 0x2000);
}

/*=======================================================================
 *
 *		ブザーを鳴らす
 *
 *	void	beep()
 *
 =======================================================================*/
void	beep()
{
	bios(BIOS_CONOUT, 0x07);
}

/*=======================================================================
 *
 *		文字列出力
 *
 *	void	cputs(str)
 *
 *		char	*str;		出力データ
 *
 =======================================================================*/
void	cputs(str)
char	*str;
{
	while (*str)
		bios(BIOS_CONOUT, *str++);
}

/*=======================================================================
 *
 *		ＢＩＯＳコール
 *
 *	char	_bios(a, bc, de, hl)
 *
 =======================================================================*/
char	_bios(a, bc, de, hl)
char	a;
int	bc, de, hl;
{
	void	_asm_c(char *);

	_asm_c("\nPUSH B\n");
	_asm_c("\nPUSH D\n");
	_asm_c("\nMVI D,0\n");
	_asm_c("\nMOV E,A\n");
	_asm_c("\nLHLD 1\n");
	_asm_c("\nDAD D\n");
	_asm_c("\nDAD D\n");
	_asm_c("\nDAD D\n");
	_asm_c("\nPOP B\n");
	_asm_c("\nPOP D\n");
	_asm_c("\nPUSH H\n");
	_asm_c("\nPUSH D\n");
	_asm_c("\nCALL LAUTO6\n");
	_asm_c("\nPOP D\n");
	_asm_c("\nRET\n");
	_asm_c("\nEXTRN LAUTO6\n");
}
