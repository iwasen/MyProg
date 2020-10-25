/************************************************************************
 *									*
 *		グラフィックウィンドウライブラリ			*
 *									*
 *		名称		: 汎用入出力処理			*
 *		ファイル名	: clib.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dos.h>
#include <ctype.h>
#include <jctype.h>
#include <jstring.h>
#include <conio.h>
#include "gwlib.h"

static	void	nedit(char *, char *, int, int);
static	void	d_kanji(int, int, int);
static	void	d_ank(int, int, int);
static	char	**sv_func;	/* ファンクションキーデータセーブポインタ */
static	SELECT	*s_top;
static	int	csr_sl = 0, csr_el = 16, csr_kanji = 0;
static	int	csr_blink;

/*=======================================================================
 |
 |		複数フィールドデータ入力
 |
 |	int	w_inputx(wp, idata, n, mp, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		INPUT	idata[];	入力フィールド情報
 |		int	n;		初期入力フィールド
 |		MOUSE	*mp;		マウス選択情報
 |		int	color;		マウス選択時カラー
 |
 =======================================================================*/
int	w_inputx(WINDOW *wp, INPUT idata[], int n, MOUSE *mp, int color)
{
	INPUT	*ip;
	int	loop_sw, ch;

	m_reg(wp, mp, color);	/* マウス選択登録 */

	loop_sw = 1;
	while (loop_sw) {
		ip = &idata[n];
		if (ip->len2 == -1) {
			/* 文字列入力 */
			ch = w_inputs(wp, ip->bufp, ip->x, ip->y
					, ip->len1, ip->flag);
		} else {
			/* 数値入力 */
			ch = w_inputn(wp, ip->bufp, ip->x, ip->y
					, ip->len1, ip->len2, ip->flag);
		}
		switch (ch) {
		case KEY_UP:
			if (ip->up != -1)
				n = ip->up;
			else
				beep();
			break;
		case KEY_DOWN:
			if (ip->down != -1)
				n = ip->down;
			else
				beep();
			break;
		case KEY_LEFT:
			if (ip->left != -1)
				n = ip->left;
			else
				beep();
			break;
		case KEY_RIGHT:
			if (ip->right != -1)
				n = ip->right;
			else
				beep();
			break;
		case KEY_CR:
			if (ip->cr != -1)
				n = ip->cr;
			else
				loop_sw = 0;
			break;
		case MOUSE_L:
			if (m_sel != -1)
				n = m_sel;
			break;
		case MOUSE_R:
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
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	m_rel(mp);	/* マウス選択解除 */

	return(ch);
}

/*=======================================================================
 |
 |		文字列入力
 |
 |	int	w_inputs(wp, buf, x, y, len, flag)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		char	*buf;		入力バッファ
 |		int	x;		入力桁位置
 |		int	y;		入力行位置
 |		int	len;		入力文字数
 |		int	flag;		bit0　0/1 = 漢字不可／可
 |					bit1  0/1 = 英数字／数字
 |					bit4  0/1 = バッファ初期化しない／する
 |					bit7  0/1 = 左右キーフィールド間／内
 |					bit8  0/1 = ＮＵＬＬなし／付加
 |
 =======================================================================*/
int	w_inputs(WINDOW *wp, char *buf, int x, int y, int len, int flag)
{
	register int	i;		/* ループカウンタ */
	register int	c;		/* 入力文字 */
	int	c2;			/* 漢字の２バイト目 */
	int	delc2;			/* 漢字の２バイト目消去フラグ */
	int	count;			/* 入力文字数カウンタ */
	int	loop_sw;		/* ループ制御スイッチ */
	int	ins_mode;		/* 挿入モードフラグ */
	WINDOW	*kwp;

	/* バッファ初期化 */
	if (flag & 0x10) {
		memset(buf, ' ', len);
		w_dspnstr(wp, x, y, buf, len);
	}

	/* 漢字入力のための処理 */
	if ((flag & 0x01) && g_kcolor != -1) {
		kwp = w_open(0, 24*16, 80, 1, 8, 16);
		w_line(kwp, 0, 0, 639, 15, g_kcolor, 2, 0);
	}

	ins_mode = 0;
	delc2 = 0;
	count = 0;
	loop_sw = 1;
	while(loop_sw) {
		w_loc(wp, x + count, y);	/* カーソル位置セット */

		if (iskanji(buf[count]))	/* カーソルサイズ設定 */
			csr_kanji = 1;
		else
			csr_kanji = 0;

		c = w_input(wp, 1);	/* キー入力 */

		if (iskanji(c))		/* 漢字コード？ */
			c2 = getch();	/* 漢字コードの２バイト目取り出し */
		else
			c2 = 0;

		switch (c) {
		case KEY_CLR:
			memset(buf, ' ', len);
			w_loc(wp, x, y);
			for (i = 0; i < len; i++)
				w_putch(wp, ' ');
			/* no break */
		case KEY_HOME:
			count = 0;
			break;
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
		case KEY_CR:
		case KEY_UP:
		case KEY_DOWN:
		case KEY_ESC:
		case MOUSE_L:
		case MOUSE_R:
			loop_sw = 0;
			break;
		case KEY_RIGHT:
			if (count+1 < len) {
				count++;
				/* 漢字コードだったら２バイト進める */
				if (nthctype(buf, count) == CT_KJ2)
					count++;
			} else {
				if ((flag & 0x80) == 0)
					loop_sw = 0;
			}
			break;
		case KEY_LEFT:
			if (count != 0) {
				count--;
				/* 漢字コードだったら２バイト戻す */
				if (nthctype(buf, count) == CT_KJ2)
					count--;
			} else {
				if ((flag & 0x80) == 0)
					loop_sw = 0;
			}
			break;
		case KEY_BS:
			if (count != 0) {
				count--;
				w_putch(wp, '\b');
				/* 漢字コードだったら２バイト戻す */
				if (nthctype(buf, count) == CT_KJ2) {
					count--;
					w_putch(wp, '\b');
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
				for (i = count; i < len; i++)
					w_putch(wp, buf[i]);
			}
			break;
		case KEY_INS:
			if (ins_mode == 0) {
				ins_mode = 1;
				csr_sl = 8;
			} else {
				ins_mode = 0;
				csr_sl = 0;
			}
			break;
		default:
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
				}else{
					if (c2 == 0){
						if (iskanji(buf[count]))
							delc2 = 1;
					}else{
						if (! iskanji(buf[count]) &&
							 iskanji(buf[count+1]))
							delc2 = 1;
					}
				}
				w_putch(wp, c);
				buf[count++] = (char)c;
				if (c2) {
					w_putch(wp, c2);
					buf[count++] = (char)c2;
				}
				if (delc2) {
					w_putch(wp, ' ');
					buf[count] = ' ';
					delc2 = 0;
				}
				if (ins_mode) {
					for (i = count; i < len; i++)
						w_putch(wp, buf[i]);
				}
			} else
				beep();
			break;
		}
	}

	csr_sl = 0;
	csr_kanji = 0;

	/* 漢字入力ウィンドウクローズ */
	if ((flag & 0x01) && g_kcolor != -1) {
		w_close(kwp);
	}

	/* ＮＵＬＬ付加 */
	if (flag & 0x100)
		buf[len] = '\0';

	return(c);
}

/*=======================================================================
 |
 |		数値入力
 |
 |	int	w_inputn(wp buf, x, y, len1, len2, flag)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		char	*buf;		入力バッファ
 |		int	x;		入力桁位置
 |		int	y;		入力行位置
 |		int	len1;		フィールド長
 |		int	len2;		小数部桁数
 |		int	flag		bit7  0/1 = 左右キーフィールド間／内
 |					bit4  0/1 = バッファ初期化しない／する
 |					bit8  0/1 = ＮＵＬＬなし／付加
 |
 =======================================================================*/
int	w_inputn(WINDOW *wp, char *buf, int x, int y, int len1, int len2
	, int flag)
{
	register int	i;		/* ループカウンタ */
	register int	c;		/* 入力文字 */
	int	count;			/* 入力文字数カウンタ */
	int	loop_sw;		/* ループ制御スイッチ */
	char	ibuf[40];		/* 入力バッファ */

	/* バッファ初期化 */
	if (flag & 0x10) {
		memset(buf, ' ', len1);
		w_dspnstr(wp, x, y, buf, len1);
	}

	count = 0;

	memset(ibuf, ' ', len1);
	loop_sw = 1;
	while(loop_sw) {
		w_loc(wp, x + count, y);	/* カーソル位置セット */

		c = w_input(wp, 1);		/* キー入力 */
		switch (c) {
		case KEY_BS:
			if (count != 0) {
				w_cputs(wp, "\b \b");
				ibuf[--count] = ' ';
			}
			break;
		case KEY_CLR:
			memset(buf, ' ', len1);
			w_loc(wp, x, y);
			for (i = 0; i < len1; i++)
				w_putch(wp, ' ');
			/* no break */
		case KEY_HOME:
			count = 0;
			break;
		case KEY_LEFT:
			if (count != 0) {
				count--;
			} else {
				if ((flag & 0x80) == 0)
					loop_sw = 0;
			}
			break;
		case KEY_RIGHT:
			if (count+1 < len1) {
				count++;
			} else {
				if ((flag & 0x80) == 0)
					loop_sw = 0;
			}
			break;
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
		case KEY_CR:
		case KEY_UP:
		case KEY_DOWN:
		case KEY_ESC:
		case MOUSE_R:
		case MOUSE_L:
			if (count != 0)
				nedit(ibuf, buf, len1, len2);
			loop_sw = 0;
			break;
		default:
			if (isdigit(c) == 0) {
				beep();
				break;
			}
			/* no break */
		case '+':
		case '-':
		case '.':
			if (count < len1) {
				w_putch(wp, c);
				ibuf[count++] = (char)c;
			} else
				beep();
			break;
		}
	}

	w_dspnstr(wp, x, y, buf, len1);

	/* ＮＵＬＬ付加 */
	if (flag & 0x100)
		buf[len1] = '\0';

	return(c);
}

/*=======================================================================
 |
 |		入力数値編集
 |
 |	int	nedit(ibuf, obuf, len1, len2)
 |
 |		char	*ibuf;		入力バッファ
 |		char	*obuf;		出力バッファ
 |		int	len1;		フィールド長
 |		int	len2;		小数部桁数
 |
 =======================================================================*/
static	void	nedit(char *ibuf, char *obuf, int len1, int len2)
{
	char	fmt[40], buf1[40], buf2[40];

	strncpy(buf1, ibuf, len1);
	buf1[len1] = '\0';

	sprintf(fmt, "%%%d.%dlf", len1, len2);
	sprintf(buf2, fmt, atof(buf1));

	strncpy(obuf, buf2, len1);
}

/*========================================================================
 |
 |		キー入力待処理
 |
 |	int	waitkey(wp)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |
 |		返値			入力キーコード
 |
 =======================================================================*/
int	waitkey(WINDOW *wp)
{
	w_loc(wp, 0, 0);
	return(w_input(wp, 0));		/* キー入力待 */
}

/*========================================================================
 |
 |		キー入力処理
 |
 |	int	inkey()
 |
 |		返値			入力キーコード
 |					（０：キー入力なし）
 |
 =======================================================================*/
int	inkey(void)
{
	register int	ch;
	static	int	plsw, prsw;
	int	wplsw, wprsw;
	int	sv_csr_flag;
	WINDOW	*wp;

	ch = 0;

	if (kbhit() != 0) {		/* キー入力チェック */
		ch = getch();		/* １文字読み込み */
		if (ch == 0x06)		/* ファンクションキー ? */
			ch = 0x0600 | getch();
	} else {
		/* マウスカーソル位置取り出し */
		m_pos(&m_gx, &m_gy, &wplsw, &wprsw);
		if (plsw != wplsw) {
			if (wplsw != 0) {
				ch = MOUSE_L;	/* 左ボタンが押された */
				if ((wp = w_check()) != NULL) {
					if (wp->flag & 0x01) {
						if (m_gy < wp->sy + wp->dy) {
							sv_csr_flag = csr_flag;
							if (sv_csr_flag)
								csr_off();
							w_xmove(wp);
							if (sv_csr_flag)
								csr_on();
							ch = 0;
						}
					}
				}
			}
			plsw = wplsw;
		} else if (prsw != wprsw) {
			if (wprsw != 0)
				ch = MOUSE_R;	/* 右ボタンが押された */
			prsw = wprsw;
		}
	}
	csr_dsp1();

	return(ch);
}

/*========================================================================
 |
 |		キー入力処理
 |
 |	int	w_input(wp, cursor)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	cursor;		カーソル表示フラグ
 |
 |		返値			入力キーコード
 |
 =======================================================================*/
int	w_input(WINDOW *wp, int cursor)
{
	int	ch;
	int	n, m_tx, m_ty;
	WINDOW	*twp, *cwp;
	MOUSE	*mp;
	SELECT	*m_sp, *sp;

	/* 文字カーソル表示 */
	if (cursor)
		csr_on();

	/* マウスカーソル表示 */
	m_csron();

	m_sel = -1;
	m_sp = 0;

	t_kcgmode(0);		/* コードアクセス */

	for (;;) {
		/* 選択位置サーチ */
		for (sp = s_top; sp != 0; sp = sp->next) {
			twp = sp->wp;
			if (twp != wp && twp != scrp)
				continue;
			m_tx = (m_gx - twp->sx) / twp->dx;
			m_ty = (m_gy - twp->sy) / twp->dy;
			for (mp = sp->mp, n = 0; mp->len != 0; mp++, n++) {
				if (mp->x <= m_tx && mp->x+mp->len > m_tx
							&& mp->y == m_ty) {
					cwp = w_check();
					if (cwp == twp || cwp == NULL)
						goto match;
					else
						break;
				}
			}
		}
		n = -1;
match:
		if (sp != m_sp || n != m_sel) {
			if (m_sel != -1) {
				/* 反転を戻す */
				mp = &m_sp->mp[m_sel];
				m_csroff();
				w_reverse(m_sp->wp, mp->x, mp->y, mp->len
								, m_sp->color);
				m_csron();
			}
			if (n != -1) {
				/* 選択位置を反転させる */
				mp = &sp->mp[n];
				m_csroff();
				w_reverse(sp->wp, mp->x, mp->y, mp->len
								, sp->color);
				m_csron();
			}
			m_sel = n;
			m_sp = sp;
		}

		if ((ch = inkey()) != 0) {	/* キー入力有り ? */
			break;
		}
	}

	t_kcgmode(1);		/* ビットマップアクセス */

	/* マウスカーソル消去 */
	m_csroff();

	if (m_sel != -1) {
		/* 反転を戻す */
		mp = &m_sp->mp[m_sel];
		w_reverse(m_sp->wp, mp->x, mp->y, mp->len, m_sp->color);
		if ((mp->code != 0) && (ch == MOUSE_L || ch == MOUSE_R))
			ch = mp->code;
	}

	/* 文字カーソル消去 */
	if (cursor)
		csr_off();

	return(ch);
}

/*========================================================================
 |
 |		マウス選択登録処理
 |
 |	void	m_reg(wp, mp, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		MOUSE	*mp;		マウス情報ポインタ
 |		int	color;		反転色
 |
 =======================================================================*/
void	m_reg(WINDOW *wp, MOUSE *mp, int color)
{
	SELECT	*sp;

	if (mp == 0)
		return;

	if ((sp = malloc(sizeof(SELECT))) == 0)
		return;

	sp->wp = wp;
	sp->mp = mp;
	sp->color = wp->bcolor ^ color;
	sp->next = s_top;

	s_top = sp;
}

/*========================================================================
 |
 |		マウス選択解除処理
 |
 |	void	m_rel(mp)
 |
 |		MOUSE	*mp;		マウス情報ポインタ
 |
 =======================================================================*/
void	m_rel(MOUSE *mp)
{
	SELECT	**spp, *sp;

	if (mp == 0)
		return;

	for (spp = &s_top; *spp != 0; spp = &(*spp)->next) {
		if ((*spp)->mp == mp) {
			sp = *spp;
			*spp = (*spp)->next;
			free(sp);
			break;
		}
	}
}

/*=======================================================================
 |
 |		文字列表示
 |
 |	void	w_dspstr(wp, x, y, str)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |		char	*str;		表示データ
 |
 =======================================================================*/
void	w_dspstr(WINDOW *wp, int x, int y, char *str)
{
	w_loc(wp, x, y);	/* カーソル位置セット */
	w_cputs(wp, str);	/* 文字列表示 */
}

/*=======================================================================
 |
 |		文字列表示（長さ指定）
 |
 |	void	w_dspnstr(wp, x, y, str, len)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |		char	*str;		表示データ
 |		int	len;		長さ
 |
 =======================================================================*/
void	w_dspnstr(WINDOW *wp, int x, int y, char *str, int len)
{
	w_loc(wp, x, y);	/* カーソル位置セット */

	while (len--)
		w_putch(wp, *str++);	/* １文字表示 */
}

/*=======================================================================
 |
 |		位置指定書式付き表示
 |
 |	void	w_xprintf(wp, x, y, fmt, p1, p2, .....)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |
 =======================================================================*/
void	w_xprintf(WINDOW *wp, int x, int y, char *fmt, char *p)
{
	char	s[82];

	vsprintf(s, fmt, (char *)&p);
	w_dspstr(wp, x, y, s);
}

/*=======================================================================
 |
 |		固定部画面表示
 |
 |	void	w_dspscr(wp, scrdat)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		SCRDAT	*scrdat;	固定部画面データ
 |
 =======================================================================*/
void	w_dspscr(WINDOW *wp, SCRDAT *scrdat)
{
	while (scrdat->data) {
		w_dspstr(wp, scrdat->pos_x, scrdat->pos_y, scrdat->data);
		scrdat++;
	}
}

/*=======================================================================
 |
 |		反転表示
 |
 |	void	w_reverse(wp, x, y, len, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	x;		桁位置
 |		int	y;		行位置
 |		int	len;		長さ
 |		int	color		反転する色
 |
 =======================================================================*/
void	w_reverse(WINDOW *wp, int x, int y, int len, int color)
{
	register int	i, j;
	char	far *vp;

	for (i = 0; i < wp->dy; i++) {
		/* 反転位置計算 */
		FP_OFF(vp) = (wp->sy + y * wp->dy + i) * 80 + wp->sx / 8 + x;

		/* 指定色を反転 */
		for (j = 0; j < len; j++) {
			if (color & C_BLUE) {
				FP_SEG(vp) = SEG_BVRAM;
				*vp ^= 0xff;
			}
			if (color & C_RED) {
				FP_SEG(vp) = SEG_RVRAM;
				*vp ^= 0xff;
			}
			if (color & C_GREEN) {
				FP_SEG(vp) = SEG_GVRAM;
				*vp ^= 0xff;
			}
			vp++;
		}
	}
}

/*=======================================================================
 |
 |		ライン表示
 |
 |	void	w_dspline(wp, line, color)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		LINE	*line;		ラインデータ
 |		int	color;		色
 |
 =======================================================================*/
void	w_dspline(WINDOW *wp, LINE *line, int color)
{
	while (line->flag != -1) {
		g_line(wp->sx + line->pos_x1 * wp->dx,
			wp->sy + line->pos_y1 * wp->dy,
			wp->sx + line->pos_x2 * wp->dx,
			wp->sy + line->pos_y2 * wp->dy,
			color, line->flag, 0);
		line++;
	}
}

/*=======================================================================
 |
 |		カーソル位置セット
 |
 |	void	w_loc(wp, x, y)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |
 =======================================================================*/
void	w_loc(WINDOW *wp, int x, int y)
{
	csr_wp = wp;
	csr_sx = wp->sx + x * wp->dx;
	csr_sy = wp->sy + y * wp->dy + (wp->dy - 16) / 2;
}

/*=======================================================================
 |
 |		カーソル表示
 |
 |	void	csr_on()
 |
 =======================================================================*/
void	csr_on(void)
{
	if (csr_flag == 0) {
		csr_flag = 1;

		_disable();
		csr_dsp1();
		_enable();
	}
}

/*=======================================================================
 |
 |		カーソル消去
 |
 |	void	csr_off()
 |
 =======================================================================*/
void	csr_off(void)
{
	if (csr_flag != 0) {
		csr_flag = 0;

		_disable();
		csr_dsp1();
		_enable();
	}
}

/*=======================================================================
 |
 |		ＢＥＥＰ
 |
 |	void	beep()
 |
 =======================================================================*/
void	beep(void)
{
	putch(0x07);
}

/*=======================================================================
 |
 |		中断処理
 |
 |	void	w_abort(err_msg)
 |
 |		char	*err_msg;	エラーメッセージ
 |
 =======================================================================*/
void	w_abort(char *err_msg)
{
	w_endscr();
	puts(err_msg);
	exit(1);
}

/*=======================================================================
 |
 |		１文字表示
 |
 |	void	w_putch(wp, c)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		int	c;		表示文字
 |
 =======================================================================*/
void	w_putch(WINDOW *wp, int c)
{
	static	unsigned char	sc;
	int	px, py;
	union	{
		int	i;
		char	c[2];
	} kanji;

	/* カーソル位置取り出し */
	px = csr_sx;
	py = csr_sy;

	if (sc) {
		/* 漢字コードの２バイト目 */
		csr_sx += wp->dx * 2;	/* カーソル位置更新 */
		kanji.c[0] = c;
		kanji.c[1] = sc;
		d_kanji(px, py, jmstojis(kanji.i));	/* 漢字表示 */
		sc = 0;
	} else {
		if (iskanji(c)) {
			/* 漢字コード */
			sc = c;		/* 漢字コード１バイト目セーブ */
		} else {
			/* ＡＮＫコード */
			if (c == '\b')		/* バックスペース */
				csr_sx -= wp->dx;	/* カーソルを戻す */
			else {
				csr_sx += wp->dx;	/* カーソル位置更新 */
				d_ank(px, py, c); /* ＡＮＫ表示 */
			}
		}
	}
}

/*=======================================================================
 |
 |		漢字表示
 |
 |	void	d_kanji(x, y, code)
 |
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |		int	code;		漢字コード（ＪＩＳコード）
 |
 =======================================================================*/
static
void	d_kanji(int x, int y, int code)
{
	struct	font	{
		char	y;
		char	x;
		union	{
			char	b[32];
			int	w[16];
		} f;
	} font;
	register int	i;
	void	*vp;
	unsigned char	b;
	unsigned int	w;

	t_getfont(code, (char *)&font);	/* フォントパターン読み込み */
	FP_OFF(vp) = y * 80 + x / 8;	/* 表示アドレス計算 */
	if (font.x == 1) {
		/* 半角文字 */
		for (i = 0; i < font.y*8; i++) {
			b = font.f.b[i];

			FP_SEG(vp) = SEG_BVRAM;
			*(char *)vp = ((g_fcolor & C_BLUE) ? b : 0)
					| ((g_bcolor & C_BLUE) ? ~b : 0);

			FP_SEG(vp) = SEG_RVRAM;
			*(char *)vp = ((g_fcolor & C_RED) ? b : 0)
					| ((g_bcolor & C_RED) ? ~b : 0);

			FP_SEG(vp) = SEG_GVRAM;
			*(char *)vp = ((g_fcolor & C_GREEN) ? b : 0)
					| ((g_bcolor & C_GREEN) ? ~b : 0);

			FP_OFF(vp) += 80;
		}
	} else {
		/* 全角文字 */
		for (i = 0; i < font.y*8; i++) {
			w = font.f.w[i];

			FP_SEG(vp) = SEG_BVRAM;
			*(int *)vp = ((g_fcolor & C_BLUE) ? w : 0)
					| ((g_bcolor & C_BLUE) ? ~w : 0);

			FP_SEG(vp) = SEG_RVRAM;
			*(int *)vp = ((g_fcolor & C_RED) ? w : 0)
					| ((g_bcolor & C_RED) ? ~w : 0);

			FP_SEG(vp) = SEG_GVRAM;
			*(int *)vp = ((g_fcolor & C_GREEN) ? w : 0)
					| ((g_bcolor & C_GREEN) ? ~w : 0);

			FP_OFF(vp) += 80;
		}
	}
}

/*=======================================================================
 |
 |		ＡＮＫ表示
 |
 |	void	d_ank(x, y, code)
 |
 |		int	x;		表示桁位置
 |		int	y;		表示行位置
 |		int	code;		ＡＮＫコード
 |
 =======================================================================*/
static
void	d_ank(int x, int y, int code)
{
	register int	i;
	char	*vp, b;

	FP_OFF(vp) = y * 80 + x / 8;	/* 表示アドレス計算 */
	for (i = 0; i < 16; i++) {
		b = ank_font[code][i];	/* フォントパターン取り出し */

		FP_SEG(vp) = SEG_BVRAM;
		*vp = ((g_fcolor & C_BLUE) ? b : 0)
				| ((g_bcolor & C_BLUE) ? ~b : 0);

		FP_SEG(vp) = SEG_RVRAM;
		*vp = ((g_fcolor & C_RED) ? b : 0)
				| ((g_bcolor & C_RED) ? ~b : 0);

		FP_SEG(vp) = SEG_GVRAM;
		*vp = ((g_fcolor & C_GREEN) ? b : 0)
				| ((g_bcolor & C_GREEN) ? ~b : 0);

		FP_OFF(vp) += 80;
	}
}

/*=======================================================================
 |
 |		文字列表示
 |
 |	void	w_cputs(wp, s)
 |
 |		WINDOW	*wp;		ウィンドウポインタ
 |		char	*s;		表示文字列
 |
 =======================================================================*/
void	w_cputs(WINDOW *wp, char *s)
{
	while (*s)
		w_putch(wp, *s++);
}

/*=======================================================================
 |
 |		文字色設定
 |
 |	void	set_color(fc, bc)
 |
 |		int	fc;		フォアグランドカラー
 |		int	bc;		バックグランドカラー
 |
 =======================================================================*/
void	set_color(int fc, int bc)
{
	if (fc != -1)
		g_fcolor = fc;

	if (bc != -1)
		g_bcolor = bc;
}

/*=======================================================================
 |
 |		ファンクションキー表示
 |
 |	void	dspfunc(func)
 |
 |		char	*func[];	ファンクションキー表示データ
 |
 =======================================================================*/
void	dspfunc(char *func[])
{
	static	char	func_key[10][3] = {
		{0x06,'0'}, {0x06,'1'}, {0x06,'2'}, {0x06,'3'}, {0x06,'4'},
		{0x06,'5'}, {0x06,'6'}, {0x06,'7'}, {0x06,'8'}, {0x06,'9'}
	};
	static	int	pos[] = {4, 11, 18, 25, 32, 42, 49, 56, 63, 70};
	static	char	spc[] = "      ";
	static	MOUSE	m_func[11];
	register int	i, j;
	int	sv_fcolor, sv_bcolor;

	m_rel(m_func);
	sv_func = func;
	sv_fcolor = g_fcolor;
	sv_bcolor = g_bcolor;
	set_color(C_BLACK, C_WHITE);
	for (i = j = 0; i < 10; i++) {
		if (*func) {
			w_dspnstr(scrp, pos[i], func_line, *func, 6);
			if (*func[0] == 0xfe)
				strcpy(key_data.fun[i], *func+6);
			else
				strcpy(key_data.fun[i], func_key[i]);
			m_func[j].x = pos[i];
			m_func[j].y = func_line;
			m_func[j].len = 6;
			m_func[j].code = KEY_FUNC + i;
			j++;
		} else {
			w_dspnstr(scrp, pos[i], func_line, spc, 6);
			key_data.fun[i][0] = '\0';
		}
		func++;
	}
	m_func[j].len = 0;
	m_reg(scrp, m_func, g_fkcolor ^ C_WHITE);

	t_fput(&key_data);

	g_fcolor = sv_fcolor;
	g_bcolor = sv_bcolor;
}

/*=======================================================================
 |
 |		マウス割り込み処理（文字カーソル表示）
 |
 |	interrupt far	csr_dsp()
 |
 =======================================================================*/
void	interrupt far csr_dsp(void)
{
	static	int	counter;

	/* カーソル点滅カウンタ更新 */
	if (++counter == 50) {
		counter = 0;
		csr_blink = 1;
	}
/*	_chain_intr(m_intp);*/
	(*m_intp)();
}

/*=======================================================================
 |
 |		文字カーソル点滅処理
 |
 |	void	csr_dsp1()
 |
 =======================================================================*/
void	csr_dsp1(void)
{
	static	int	dsp_flag;
	static	int	move_flag;
	static	int	w_csr_sx, w_csr_sy;
	register int	i, chg_flag;
	static	char	*vp;
	static	union	REGS	reg;

	/* カーソル表示チェック */
	chg_flag = 0;
	if (move_flag) {
		if (csr_flag)
			chg_flag = 1;
		move_flag = 0;
	} else {
		if (csr_flag) {
			if (csr_blink)
				chg_flag = 1;
			if (dsp_flag && (csr_sx != w_csr_sx
						|| csr_sy != w_csr_sy)) {
				chg_flag = 1;
				move_flag = 1;
			}
		} else {
			if (dsp_flag)
				chg_flag = 1;
		}
	}
	csr_blink = 0;
		
	if (chg_flag) {
		/* カーソル位置セーブ */
		if (dsp_flag == 0) {
			w_csr_sx = csr_sx;
			w_csr_sy = csr_sy;
		}

		/* カーソル表示アドレス計算 */
		FP_OFF(vp) = (w_csr_sy + csr_sl) * 80 + w_csr_sx / 8;

		/* マウスカーソルＯＦＦ */
		if (m_csr_flag) {
			reg.x.ax = 2;
			int86(0x33, &reg, &reg);
		}

		/* カーソル表示 */
		if (csr_kanji == 0) {
			for (i = csr_sl; i < csr_el; i++) {
				FP_SEG(vp) = SEG_BVRAM;
				*vp ^= 0xff;
				FP_SEG(vp) = SEG_RVRAM;
				*vp ^= 0xff;
				FP_SEG(vp) = SEG_GVRAM;
				*vp ^= 0xff;
				FP_OFF(vp) += 80;
			}
		} else {
			for (i = csr_sl; i < csr_el; i++) {
				FP_SEG(vp) = SEG_BVRAM;
				*(int *)vp ^= 0xffff;
				FP_SEG(vp) = SEG_RVRAM;
				*(int *)vp ^= 0xffff;
				FP_SEG(vp) = SEG_GVRAM;
				*(int *)vp ^= 0xffff;
				FP_OFF(vp) += 80;
			}
		}

		/* マウスカーソルＯＮ */
		if (m_csr_flag) {
			reg.x.ax = 1;
			int86(0x33, &reg, &reg);
		}

		/* カーソル点滅フラグ反転 */
		dsp_flag = !dsp_flag;
	}
}
