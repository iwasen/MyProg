/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: デバッグコマンド処理			*
 *		ファイル名	: debug1.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=debug1
#pragma	SECTION CONST=debug1
#pragma	SECTION DATA=debug1

#include "sg_cpu.h"

/*	内部関数	*/
static	int	cmd_dump(void);
static	int	cmd_window(void);
static	int	cmd_byte_write(void);
static	int	cmd_word_write(void);
static	int	cmd_test_data(void);
static	int	cmd_manual_data(void);
static	int	cmd_dsp_status(void);
static	int	cmd_go(void);
static	int	cmd_target(void);
static	int	cmd_dsp_update(void);
static	int	cmd_mode_change(void);
static	int	cmd_dsp_val(void);
static	int	cmd_ibit(void);
static	int	cmd_rbit(void);
static	int	cmd_help(void);
static	char	*hextoshort(char *, short *);
static	char	*hextolong(char *, long *);
static	char	*dectoshort(char *, short *);
extern	void	sendline(char *, ...);

/*=======================================================================
 |
 |		デバッグコマンド実行処理
 |
 |	void	debug_command()
 |
 =======================================================================*/
void	debug_command(void)
{
	static	char	command_error[] = "COMMAND ERROR\x0d\x0a";
	int	err;

	rsb_send("\x0d\x0a", 2);

	switch (rsb_rcvbuf[0]) {
	case 'A':
		err = cmd_window();
		break;
	case 'B':
		err = cmd_byte_write();
		break;
	case 'C':
		err = cmd_mode_change();
		break;
	case 'D':
		err = cmd_dump();
		break;
	case 'F':
		err = cmd_target();
		break;
	case 'G':
		err = cmd_go();
		break;
	case 'I':
		err = cmd_ibit();
		break;
	case 'M':
		err = cmd_manual_data();
		break;
	case 'N':
		ibit_status(BIT_OK);
		err = 0;
		break;
	case 'R':
		err = cmd_rbit();
		break;
	case 'S':
		err = cmd_dsp_status();
		break;
	case 'T':
		err = cmd_test_data();
		break;
	case 'U':
		err = cmd_dsp_update();
		break;
	case 'V':
		err = cmd_dsp_val();
		break;
	case 'W':
		err = cmd_word_write();
		break;
	case 'Z':
		SET_WORD(mbi_rcvbuf, 0, 44*32);
		err = 0;
		break;
	case 'H':
	case '?':
		err = cmd_help();
		break;
	case '\0':
		err = 0;
		break;
	default:
		err = 1;
		break;

	}

	if (err != 0)
		rsb_send(command_error, sizeof(command_error) - 1);

	rsb_send(">", 1);
}

/*=======================================================================
 |
 |		可変位置ウィンドウ表示処理
 |
 |	short	cmd_window()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_window(void)
{
	char	*bp;
	MFD_WINDOW	*wp;
	int	i;
	short	n, size, x, y;
	uchar	data[4];

	bp = &rsb_rcvbuf[1];
	if ((bp = dectoshort(bp, &n)) == NULL)
		return(1);
	if (n < 0 || n > 91)
		return(1);

	if ((bp = dectoshort(bp, &size)) == NULL)
		return(1);
	if (size < 0 || size > 2)
		return(1);

	while (*bp == ' ')
		bp++;

	for (i = 0; i < 4; i++) {
		if (*bp != '\0')
			data[i] = *bp++;
		else
			break;
	}
	for ( ; i < 4; i++)
		data[i] = 0x20;

	for (i = 0; i < 91; i++) {
		if (i == 0)
			wp = (MFD_WINDOW *)&mbi_rcvbuf[0][2];
		else if (i == 38)
			wp = (MFD_WINDOW *)&mbi_rcvbuf[29][2];
		else if (i == 76)
			wp = (MFD_WINDOW *)&mbi_rcvbuf[14][10];
		if (i % 20 == 0) {
			x = 0xc180 + i * 0x140;
			y = 0x3800;
		}
		if (i < n) {
			wp->x = x | size;
			wp->y = y | 0x0f;
			wp->cha = (data[0] << 8) | data[1];
			wp->chb = (data[2] << 8) | data[3];
		} else {
			wp->x = 0;
			wp->y = 0;
			wp->cha = 0;
			wp->chb = 0;
		}
		wp++;
		y -= 0x620;
	}

	return(0);
}

/*=======================================================================
 |
 |		ダンプコマンド実行処理
 |
 |	short	cmd_dump()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_dump(void)
{
	char	*bp, *p;
	long	address;
	short	nbyte, n, offset;
	int	i;
	char	buf[60];

	bp = &rsb_rcvbuf[1];
	if ((bp = hextolong(bp, &address)) == NULL)
		return(1);

	if ((bp = hextoshort(bp, &nbyte)) == NULL)
		nbyte = 0x10;

	rsb_send(" ADDRESS   0  1  2  3  4  5  6  7   8  9  A  B  C  D  E  F\x0d\x0a", 60);
	for (n = 0; n < nbyte; n++) {
		p = (char *)address;
		offset = (short)(address & 0xf);
		if (offset == 0 && n != 0)
			rsb_send(buf, 60);
		if (offset == 0 || n == 0) {
			for (i = 0; i < 58; i++)
				buf[i] = ' ';
			buf[58] = 0x0d;
			buf[59] = 0x0a;
			longtohex(address & ~0xfL, buf, 8);
		}
		shorttohex(*p, &buf[10 + offset*3 + (offset >= 8 ? 1 : 0)], 2);
		address++;
	}

	if (nbyte != 0)
		rsb_send(buf, 60);

	return(0);
}

/*=======================================================================
 |
 |		バイトデータ書き込みコマンド実行処理
 |
 |	int	cmd_byte_write()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_byte_write(void)
{
	char	*bp, *p;
	long	address;
	short	data;

	bp = &rsb_rcvbuf[1];
	if ((bp = hextolong(bp, &address)) == NULL)
		return(1);

	if ((bp = hextoshort(bp, &data)) == NULL)
		return(1);

	p = (char *)address;
	*p = (char)data;

	return(0);
}

/*=======================================================================
 |
 |		ワードデータ書き込みコマンド実行処理
 |
 |	int	cmd_word_write()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_word_write(void)
{
	char	*bp;
	short	*p;
	long	address;
	short	data;

	bp = &rsb_rcvbuf[1];
	if ((bp = hextolong(bp, &address)) == NULL)
		return(1);

	if ((bp = hextoshort(bp, &data)) == NULL)
		return(1);

	p = (short *)address;
	*p = data;

	return(0);
}

/*=======================================================================
 |
 |		テストデータ番号設定コマンド実行処理
 |
 |	int	cmd_test_data()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_test_data(void)
{
	char	*bp;
	short	data;

	bp = &rsb_rcvbuf[1];

	if ((bp = dectoshort(bp, &data)) == NULL)
		return(1);

	if (data >= 0 && data <= 5)
		set_simulation(CPU_1, data);
	else if (data >= 10 && data <= 15)
		set_simulation(CPU_2, data-10);
	else if (data >= 20 && data <= 25)
		set_simulation(CPU_3, data-20);

	return(0);
}

/*=======================================================================
 |
 |		マニュアルデータ入力コマンド実行処理
 |
 |	int	cmd_manual_data()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_manual_data(void)
{
	char	*bp;
	short	blk, no, data;
	char	buf[6];

	bp = &rsb_rcvbuf[1];

	if ((bp = dectoshort(bp, &blk)) == NULL)
		return(1);
	if (blk < 1 || blk > 44)
		return(1);

	if ((bp = dectoshort(bp, &no)) == NULL)
		return(1);
	if (no < 1 || no > 32)
		return(1);

	if ((bp = hextoshort(bp, &data)) == NULL) {
		shorttohex(mbi_rcvbuf[blk-1][no-1], buf, 4);
		buf[4] = 0x0d;
		buf[5] = 0x0a;
		rsb_send(buf, 6);
	} else
		mbi_rcvbuf[blk-1][no-1] = data;

	return(0);
}

/*=======================================================================
 |
 |		ステータス表示処理
 |
 |	int	cmd_dsp_status()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_dsp_status(void)
{
//	ushort	*sbuf;

/*@@@@@@@@@@@@*/
	extern	ushort	sbuf[];
/*@@@@@@@@@@@@*/
//	sbuf = (ushort *)(REG_MBI_HEAD + 0x300 * 2);
	sendline("BEZEL SWITCH1 : %x %x", sbuf[0], sbuf[1]);
	sendline("BEZEL SWITCH2 : %x %x", sbuf[2], sbuf[3]);
	sendline("BEZEL SWITCH3 : %x %x", sbuf[4], sbuf[5]);
	sendline("BIT RESULT    : %x %x", sbuf[6], sbuf[7]);
	sendline("SG BIT STATUS : %x", sbuf[8]);
	sendline("DU BIT STATUS : %x %x %x", sbuf[9], sbuf[10], sbuf[11]);
	
	return(0);
}

/*=======================================================================
 |
 |		ＦＣＲモード　ターゲットシンボル表示
 |
 |	int	cmd_target()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_target(void)
{
	char	*bp;
	FCR_TARGET	*tp;
	int	i, x, y;
	short	n, lock;

	bp = &rsb_rcvbuf[1];
	if ((bp = dectoshort(bp, &n)) == NULL)
		return(1);
	if (n < 0 || n > 64)
		return(1);

	if ((bp = dectoshort(bp, &lock)) == NULL)
		lock = -1;
	else if (lock != 0 && lock != 1)
		return(1);

	tp = (FCR_TARGET *)&mbi_rcvbuf[21][0];
	for (i = 0; i < 64; i++) {
		if (i % 16 == 0) {
			x = 0xd400;
			y = 0x2800 - i * 0x80;
		}
		if (i < n) {
			if (lock == -1)
				tp->x = x | (i & 1);
			else
				tp->x = x | lock;
			tp->y = y | 0x0f;
		} else {
			tp->x = 0;
			tp->y = 0;
		}
		tp++;
		x += 0x600;
	}
	old_display_mode = DSPM_NOSYMBOL;

	return(0);
}

/*=======================================================================
 |
 |		ジャンプコマンド実行処理
 |
 |	int	cmd_go()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_go(void)
{
	char	*bp;
	long	address;

	bp = &rsb_rcvbuf[1];
	if ((bp = hextolong(bp, &address)) == NULL)
		return(1);

	(*(void (*)())address)();

	return(0);
}

/*=======================================================================
 |
 |		更新速度表示コマンド実行処理
 |
 |	int	cmd_dsp_update()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_dsp_update(void)
{
	rate_dsp_flag ^= 0x01;
	return(0);
}

/*=======================================================================
 |
 |		モード変更（デバッグモード－通常モード）
 |
 |	int	cmd_mode_change()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_mode_change(void)
{
	if (sg_mode != SGM_DEBUG) {
		sg_mode = SGM_DEBUG;
		debug_mode = DBM_DBGMENU;
	} else {
		sg_mode = SGM_NORMAL;
		debug_mode = DBM_NORMAL;
	}

	return(0);
}

/*=======================================================================
 |
 |		変数内容の表示処理
 |
 |	int	cmd_dsp_val()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_dsp_val(void)
{
	sendline("cpu=%d", cpu);
	sendline("sg_mode=%d", sg_mode);
	sendline("display_mode=%d", display_mode);
	sendline("debug_mode=%d", debug_mode);
	sendline("bus_fail=%d", bus_fail);
	sendline("rate_1553B=%d", rate_1553B);
	sendline("rate_gscint=%d", rate_gscint);
	sendline("ibit_mode=%d", ibit_mode);
	sendline("rbit_mode=%d", rbit_mode);
	sendline("echo_mode=%d", echo_mode);
	sendline("echo_status=%d", echo_status);

	return(0);
}

/*=======================================================================
 |
 |		Ｉ－ＢＩＴ実行開始／終了
 |
 |	int	cmd_ibit()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_ibit(void)
{
	if (ibit_request) {
		ibit_request = 0;
		sendline("I-BIT OFF");
	} else {
		ibit_request = 1;
		sendline("I-BIT ON");
	}

	return(0);
}

/*=======================================================================
 |
 |		Ｒ－ＢＩＴ実行開始／終了
 |
 |	int	cmd_rbit()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_rbit(void)
{
	rbit_request = 1;
	sendline("R-BIT START");

	return(0);
}

/*=======================================================================
 |
 |		コマンドヘルプ
 |
 |	int	cmd_help()
 |
 |		int	返値		０：正常　１：エラー
 |
 =======================================================================*/
static	int	cmd_help(void)
{
	sendline("### DEBUG COMMAND ###");
	sendline("A : DISPLAY MFDS-WINDOW");
	sendline("B : WRITE BYTE DATA");
	sendline("C : CHANGE NORMAL/DEBUG MODE");
	sendline("D : MEMORY DUMP");
	sendline("F : DISPLAY FCR TARGET SYMBOL");
	sendline("H : HELP MESSAGE");
	sendline("I : INITIATED BIT ON/OFF");
	sendline("L : DISPLAY GRID");
	sendline("M : MANUAL DATA SET");
	sendline("N : NEXT INITIATED BIT MODE");
	sendline("R : START REMOTE BIT");
	sendline("S : DISPLAY STATUS WORD");
	sendline("T : DEBUG DATA SIMULATION");
	sendline("U : DISPLAY UPDATE RATE");
	sendline("V : DISPLAY VARIABLE");
	sendline("W : WRITE WORD DATA");

	return(0);
}

/*=======================================================================
 |
 |		１６進文字からshortに変換
 |
 |	short	hextoshort(bp, n)
 |
 |		char	*bp;		１６進文字データ
 |		short	*n;		short値
 |
 |		char	*返値		変換後のbp
 |
 =======================================================================*/
static	char	*hextoshort(char *bp, short *n)
{
	int	c;

	while (*bp == ' ')
		bp++;

	*n = 0;
	for (;;) {
		c = *bp;
		*n <<= 4;
		if (c >= '0' && c <= '9')
			*n += c - '0';
		else if (c >= 'A' && c <= 'F')
			*n += c - 'A' + 10;
		else if (c >= 'a' && c <= 'f')
			*n += c - 'a' + 10;
		else
			return(NULL);
		bp++;
		if (*bp == ' ' || *bp == '\0')
			break;
	}
	return(bp);
}

/*=======================================================================
 |
 |		１６進文字からlongに変換
 |
 |	short	hextolong(bp, n)
 |
 |		char	*bp;		１６進文字データ
 |		long	*n;		long値
 |
 |		char	*返値		変換後のbp
 |
 =======================================================================*/
static	char	*hextolong(char *bp, long *n)
{
	int	c;

	while (*bp == ' ')
		bp++;

	*n = 0;
	for (;;) {
		c = *bp;
		if (c == ' ' || c == '\0')
			break;
		*n <<= 4;
		if (c >= '0' && c <= '9')
			*n += c - '0';
		else if (c >= 'A' && c <= 'F')
			*n += c - 'A' + 10;
		else if (c >= 'a' && c <= 'f')
			*n += c - 'a' + 10;
		else
			return(NULL);
		bp++;
	}
	return(bp);
}

/*=======================================================================
 |
 |		１０進文字からshortに変換
 |
 |	char	*dectoshort(bp, n)
 |
 |		char	*bp;		１０進文字データ
 |		short	*n;		short値
 |
 |		char	*返値		変換後のbp
 |
 =======================================================================*/
static	char	*dectoshort(char *bp, short *n)
{
	int	c, flag;

	while (*bp == ' ')
		bp++;

	if (*bp == '$')
		return(hextoshort(bp+1, n));

	if (*bp == '-') {
		flag = -1;
		bp++;
	} else
		flag = 1;

	*n = 0;
	for (;;) {
		c = *bp;
		*n *= 10;
		if (c >= '0' && c <= '9')
			*n += c - '0';
		else
			return(NULL);
		bp++;
		if (*bp == ' ' || *bp == '\0')
			break;
	}
	*n *= flag;

	return(bp);
}
