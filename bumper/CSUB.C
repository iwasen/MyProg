/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: 共通サブルーチン			*
 *		ファイル名	: csub.c				*
 *									*
 ************************************************************************/
#include "bumper.h"
#include <ctype.h>
#include <jctype.h>
#include <jstring.h>

static	char	key_buf[160], *key_bufp;
static	int	key_buf_len;

/*=======================================================================
 |
 |		文字列表示
 |
 |	void	dspstr(x, y, str, color)
 |
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |		char	*str;		表示データ
 |		int	color;		表示色
 |
 =======================================================================*/
void	dspstr(int x, int y, char *str, int color)
{
	t_loc(x, y);
	t_color(t_color_code(color));
	cputs(str);
}

/*=======================================================================
 |
 |		文字列表示（長さ指定）
 |
 |	void	dspnstr(x, y, str, len, color)
 |
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |		char	*str;		表示データ
 |		int	len;		長さ
 |		int	color;		表示色
 |
 =======================================================================*/
void	dspnstr(int x, int y, char *str, int len, int color)
{
	t_loc(x, y);

	t_color(t_color_code(color));
	while (len--)
		putch(*str++);
}

/*=======================================================================
 |
 |		位置指定書式付き表示
 |
 |	void	xprintf(x, y, color, p1, p2, .....)
 |
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |
 =======================================================================*/
void	xprintf(int x, int y, int color, char *fmt, char *p)
{
	char	s[82];

	vsprintf(s, fmt, (char *)&p);
	dspstr(x, y, s, color);
}

/*=======================================================================
 |
 |		固定部画面表示
 |
 |	void	dspscr(scrdat, color)
 |
 |		SCRDAT	*scrdat;	固定部画面データ
 |		int	color;		色種別
 |
 =======================================================================*/
void	dspscr(SCRDAT *scrdat, int color)
{
	t_color(t_color_code(color));

	while (scrdat->data) {
		t_loc(scrdat->pos_x, scrdat->pos_y);
		cputs(scrdat->data);
		scrdat++;
	}
}

/*=======================================================================
 |
 |		スペース表示
 |
 |	void	dspspc(x, y, len)
 |
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |		int	len;		長さ
 |
 =======================================================================*/
void	dspspc(int x, int y, int len)
{
	t_loc(x, y);
	while (len--)
		putch(' ');
}

/*=======================================================================
 |
 |		カーソル位置より行末までクリア
 |
 |	void	clrtoeol()
 |
 =======================================================================*/
void	clrtoeol()
{
	cputs("\x1b[0K");
}

/*=======================================================================
 |
 |		罫線表示
 |
 |	void	dspline(line, color)
 |
 |		LINE	*line;		ラインデータ
 |		int	color;		色種別
 |
 =======================================================================*/
void	dspline(LINE *line, int color)
{
	while (line->flag != -1) {
		if (line->flag != 3) {
			g_line(line->pos_x1*8, line->pos_y1*16+8,
				line->pos_x2*8, line->pos_y2*16+8,
				color, line->flag, 0);
		} else {
			g_line(line->pos_x1*8, line->pos_y1*16+7,
				line->pos_x2*8, line->pos_y2*16+7,
				color, 0, 0);
			g_line(line->pos_x1*8, line->pos_y1*16+10,
				line->pos_x2*8, line->pos_y2*16+10,
				color, 0, 0);
		}
		line++;
	}
}

/*=======================================================================
 |
 |		テキスト画面色コード取り出し
 |
 |	int	t_color_code(color)
 |
 |		int	color;		色種別
 |
 =======================================================================*/
int	t_color_code(int color)
{
	static	int	t_color_data[8] = {
		0x01, 0x21, 0x41, 0x61,
		0x81, 0xa1, 0xc1, 0xe1
	};

	return(t_color_data[color]);
}

/*=======================================================================
 |
 |		入力文字数カウント
 |
 |	int	strcount(p)
 |
 |		char	*p;		入力文字列
 |
 =======================================================================*/
int	strcount(char *p)
{
	int	i;

	for (i = strlen(p); i > 0; i--) {
		if (p[i-1] != ' ')
			break;
	}
	return(i);
}

/*=======================================================================
 |
 |		入力フィールド表示
 |
 |	void	dsp_field(x, y, n, color)
 |		int	x;		桁位置
 |		int	y;		行位置
 |		int	n;		桁数
 |		int	color;		色種別
 |
 =======================================================================*/
void	dsp_field(int x, int y, int n, int color)
{
	while (n--) {
		g_line(x*8-4, y*16, x*8+9, y*16+16, color, 1, 0);
		x += 2;
	}
}

/*=======================================================================
 |
 |		１文字入力
 |
 |	int	inkey()
 |
 =======================================================================*/
int	inkey()
{
	int	ch;
	union	REGS	reg;

	ch = 0;
	if (key_buf_len != 0) {
		ch = *key_bufp++;
		key_buf_len--;
	} else if (kbhit()) {
		ch = getch();
		if (ch == 0x06)		/* ファンクションキー ? */
			ch = KEY_FUNC | getch();
		else if (ch == KEY_ESC || ch == KEY_TAB) {
			/* シフトキーチェック */
			reg.h.ah = 2;
			int86(0x18, &reg, &reg);
			if (reg.h.al & 0x01)
				ch |= KEY_SHIFT;
		}
	}

	return(ch);
}

/*=======================================================================
 |
 |		１文字入力待
 |
 |	int	getchr()
 |
 =======================================================================*/
int	getchr()
{
	int	ch;

	while ((ch = inkey()) == 0)
		;

	return(ch);
}

/*=======================================================================
 |
 |		キーバッファセット
 |
 |	void	set_keybuf(p)
 |
 |		char	*p;		キーバッファにセットするデータ
 |
 =======================================================================*/
void	set_keybuf(char *p)
{
	key_buf_len = strlen(p);
	memcpy(key_buf, p, key_buf_len);
	key_bufp = key_buf;
}

/*=======================================================================
 |
 |		文字列入力
 |
 |	int	inputs(buf, x, y, len, flag, color)
 |
 |		char	*buf;		入力バッファ
 |		int	x;		入力桁位置
 |		int	y;		入力行位置
 |		int	len;		入力文字数
 |		int	flag;	bit0　0/1 = 漢字不可／可
 |				bit1  0/1 = 英数字／数字
 |				bit4  0/1 = バッファ初期化しない／する
 |				bit6  0/1 = 上下矢印キーでリターンしない／する
 |				bit7  0/1 = 左右矢印キーでリターンしない／する
 |				bit8  0/1 = ＮＵＬＬなし／付加
 |		int	color;		色種別
 |
 =======================================================================*/
int	inputs(char *buf, int x, int y, int len, int flag, int color)
{
	register int	i;		/* ループカウンタ */
	register int	c;		/* 入力文字 */
	int	c2;			/* 漢字の２バイト目 */
	int	count;			/* 入力文字数カウンタ */
	int	loop_sw;		/* ループ制御スイッチ */
	int	ins_mode;		/* 挿入モードフラグ */
	int	d_flag;

	ins_mode = 0;
	d_flag = 0;
	count = 0;

	t_color(t_color_code(color));

	/* バッファ初期化 */
	if (flag & 0x10) {
		memset(buf, ' ', len);
	}

	d_flag = 1;
	loop_sw = 1;
	while(loop_sw) {
		if (count == len && (flag & 0x200))
			count--;

 		if (d_flag) {
			t_loc(x, y);
			for (i = 0; i < len; i++) {
				putch(buf[i]);
				if (flag & 0x200)
					putch(' ');
			}
			d_flag = 0;
		}

		if (flag & 0x200)
			t_loc(x + count*2, y);	/* カーソル位置セット */
		else
			t_loc(x + count, y);	/* カーソル位置セット */

		t_csron();		/* カーソル表示 */
		c = getchr();		/* キー入力 */
		t_csroff();		/* カーソル消去 */

		if (iskanji(c))		/* 漢字コード？ */
			c2 = getchr();	/* 漢字コードの２バイト目取り出し */
		else
			c2 = 0;

		switch (c) {
		case KEY_CLR:
			memset(buf, ' ', len);
			d_flag = 1;
		case KEY_CR:
		case KEY_ESC:
		case KEY_SFT_ESC:
		case KEY_HOME:
		case KEY_HELP:
		case KEY_TAB:
		case KEY_SFT_TAB:
		case KEY_F1:
		case KEY_F2:
		case KEY_F3:
		case KEY_F4:
		case KEY_F5:
		case KEY_F6:
		case KEY_F7:
		case KEY_F8:
		case KEY_F9:
		case KEY_F10:
			loop_sw = 0;
			break;
		case KEY_UP:
		case KEY_DOWN:
			if (flag & 0x40)
				loop_sw = 0;
			break;
		case KEY_RIGHT:
			if (count+1 < len) {
				count++;
				/* 漢字コードだったら２バイト進める */
				if (nthctype(buf, count) == CT_KJ2)
					count++;
			} else if (flag & 0x80)
				loop_sw = 0;
			break;
		case KEY_LEFT:
			if (count != 0) {
				count--;
				/* 漢字コードだったら２バイト戻す */
				if (nthctype(buf, count) == CT_KJ2)
					count--;
			} else if (flag & 0x80)
				loop_sw = 0;
			break;
		case KEY_BS:
			if (count != 0) {
				count--;
				/* 漢字コードだったら２バイト戻す */
				if (nthctype(buf, count) == CT_KJ2) {
					count--;
				}
			} else
				break;
			/* no break */
		case KEY_DEL:
			if (count < len) {
				if (iskanji(buf[count])) {
				memcpy(&buf[count], &buf[count+1]
							, len - count - 1);
					buf[len-1] = ' ';
				}
				memcpy(&buf[count], &buf[count+1]
							, len - count - 1);
				buf[len-1] = ' ';
				d_flag = 1;
			}
			break;
		case KEY_INS:
			if (ins_mode == 0) {
				ins_mode = 1;
				/*csr_sl = 8;*/
			} else {
				ins_mode = 0;
				/*csr_sl = 0;*/
			}
			break;
		default:
			if ((c & 0xff00) == KEY_MOUSE) {
				loop_sw = 0;
				break;
			}
			/* 漢字入力不可で漢字が入力されたらエラー */
			if ((flag & 0x01) == 0 && c2 != 0) {
				beep();
				break;
			}

			/* 英数字入力の場合の文字チェック */
			if (count < (c2 ? len-1 : len)
				  && ((flag & 0x01) != 0 || !iscntrl(c & 0x7f))
				  && ((flag & 0x02) == 0 || isdigit(c))) {
				if (ins_mode) {
					if (buf[len-1] != ' ') {
						beep();
						break;
					}
					if (c2 != 0) {
						if (buf[len-2] != ' ') {
							beep();
							break;
						}
						for (i = len-1; i > count; i--)
							buf[i] = buf[i-1];
					}
					for (i = len-1; i > count; i--)
						buf[i] = buf[i-1];

					buf[count++] = (char)c;
					if (c2) {
						buf[count++] = (char)c2;
					}
					d_flag = 1;
				} else {
					putch(c);
					buf[count++] = (char)c;
					if (c2) {
						putch(c2);
						buf[count++] = (char)c2;
					}
				}
			} else
				beep();
			break;
		}
	}

	/*csr_sl = 0;*/

	/* ＮＵＬＬ付加 */
	if (flag & 0x100)
		buf[len] = '\0';

	return(c);
}

/*=======================================================================
 |
 |		アトリビュート設定
 |
 |	void	attrib(x, y, len, mask, attr)
 |
 |		int	x;		桁位置
 |		int	y;		行位置
 |		int	len;		長さ
 |		int	mask;		マスクデータ
 |		int	attr;		アトリビュート
 |
 =======================================================================*/
void	attrib(int x, int y, int len, int mask, int attr)
{
	char	far	*p;

	FP_SEG(p) = SEG_ATTR;
	FP_OFF(p) = (y * 80 + x) * 2;
	while (len--) {
		*p = (*p & ~mask) | (attr & mask);
		p += 2;
	}
}

/*=======================================================================
 *
 *		ファンクションキー表示
 *
 *	void	dspfunc(func)
 *
 *		char	*func[];	ファンクションキー表示データ
 *
 =======================================================================*/
void	dsp_func(func)
char	*func[];
{
	static	char	func_key[10][3] = {
		{0x06,'1'}, {0x06,'2'}, {0x06,'3'}, {0x06,'4'}, {0x06,'5'},
		{0x06,'6'}, {0x06,'7'}, {0x06,'8'}, {0x06,'9'}, {0x06,'0'}
	};
	static	int	pos[] = {4, 11, 18, 25, 32, 42, 49, 56, 63, 70};
	static	char	spc[] = "      ";
	register int	i, j;

	t_color(A_WHITE|A_REVERSE|0x01);
	for (i = 0; i < 10; i++) {
		t_loc(pos[i], 24);
		if (*func) {
			for (j = 0; j < 6; j++)
				putch((*func)[j]);
			if (*func[0] == 0xfe)
				strcpy(key_data.fun[i], *func+6);
			else
				strcpy(key_data.fun[i], func_key[i]);
		} else {
			cputs(spc);
			key_data.fun[i][0] = '\0';
		}
		func++;
	}

	t_fput(&key_data);
}

/*=======================================================================
 |
 |		メモリ確保（エラーチェック有り）
 |
 |	void	*talloc(size)
 |
 |		int	size;		確保するメモリサイズ
 |
 =======================================================================*/
void	*talloc(int size)
{
	void	*p;

	if ((p = malloc(size)) == NULL) {
		final();
		printf("memory allocation error\n");
		exit(1);
	}

	return(p);
}

/*=======================================================================
 |
 |		メモリサイズ変更（エラーチェック有り）
 |
 |	void	*trealloc(p, size)
 |
 |		void	*p;		変更前のメモリポインタ
 |		int	size;		変更するメモリサイズ
 |
 =======================================================================*/
void	*trealloc(void *p, int size)
{
	if ((p = realloc(p, size)) == NULL) {
		final();
		printf("メモリの確保に失敗しました\n");
		exit(1);
	}
	return(p);
}

int	kakunin(char *msg)
{
	int	loop_sw, yn;

	dspstr(2, 22, msg, C_WHITE);
	clrtoeol();
	t_csron();

	loop_sw = 1;
	while (loop_sw) {	
		switch (getchr()) {
		case 'Y':
		case 'y':
			putch('Y');
			yn = 1;
			loop_sw = 0;
			break;
		case 'N':
		case 'n':
			putch('N');
			yn = 0;
			loop_sw = 0;
			break;
		}
	}

	t_csroff();

	return(yn);
}

void	message(char *msg, int color)
{
	dspstr(2, 22, msg, color);
	clrtoeol();

	timset(150);
	while (tm_ovf == 0);
		;
}
