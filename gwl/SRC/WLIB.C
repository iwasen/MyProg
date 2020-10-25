/************************************************************************
 *									*
 *		グラフィックウィンドウライブラリ			*
 *									*
 *		名称		: ウィンドウ操作処理			*
 *		ファイル名	: wlib.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dos.h>
#include "gwlib.h"

static	WINDOW	*w_alloc(int, int, int, int, int, int);
static	void	w_save(WINDOW *);
static	void	w_erase(WINDOW *, int);
static	WINDOW	*w_pick(WINDOW *);
static	int	w_pick_sub(WINDOW *, WINDOW *, char *);
static	void	w_restore(WINDOW *);
static	void	w_free(WINDOW *);
static	void	w_push(WINDOW *);
static	WINDOW	*w_pop(void);
static	void	read_font(char *);
static	void	w_change(WINDOW	*, WINDOW *, WINDOW *, char *, int, int, int, int);
static	WINDOW	*w_check_sub(int, int);

static	WINDOW	*wtop;
static	struct key_buf	func_save; /* ファンクションキーデータセーブエリア */

/*=======================================================================
 |
 |		画面初期化
 |
 |	void	w_initscr(dx, dy, mouse_d, font_file)
 |
 |		int	dx;		１桁のドット数（８固定）
 |		int	dy;		１行のドット数
 |		int	mouse_d;	マウスドライバ種別
 |		char	*font_file;	ＡＮＫフォントファイル
 |
 =======================================================================*/
void	w_initscr(int dx, int dy, int mouse_d, char *font_file)
{
	static	char	retkey_font[] = {	/* リターンキーの記号 */
		0x02, 0x02,
		0x00, 0x00, 0x00, 0x1f, 0x00, 0x11, 0x00, 0x11,
		0x06, 0x11, 0x0e, 0x11, 0x1a, 0x31, 0x33, 0xe1,
		0x60, 0x01, 0xc0, 0x01, 0x60, 0x03, 0x33, 0xfe,
		0x1a, 0x00, 0x0e, 0x00, 0x06, 0x00, 0x00, 0x00
	};

	if (m_init() == 0) {		/* マウス初期化 */
		printf("マウスドライバが組み込まれていません.\n");
		exit(0);
	}
	t_fget(&func_save);	/* ファンクションキーラベル取り出し */
	t_fput(&key_data);	/* ファンクションキーラベル設定 */

	read_font(font_file);	/* ＡＮＫフォントファイル読み込み */

	mouse_drv = mouse_d;	/* マウスドライバ種別セット */

	g_init();		/* グラフィック初期化 */
	g_screen(3, 0, 0, 1);	/* スクリーンモード設定 */
	g_cls();		/* グラフィック画面クリア */
	t_cls();		/* テキスト画面クリア */
	t_csroff();		/* テキストカーソル消去 */
	printf("\x1b[>1h");	/* 最下位行ユーザ使用 */

	t_kcgmode(1);		/* ビットマップアクセス */

	/* マウス割り込みベクタ取り出し */
	m_intp = _dos_getvect(0x15);

	/* ベクタを書き換える */
	_dos_setvect(0x15, csr_dsp);

	/* ウィンドウ情報セット */
	scrp->sx = 0;
	scrp->sy = 0;
	scrp->nx = 640 / dx;
	scrp->ny = 400 / dy;
	scrp->dx = dx;
	scrp->dy = dy;
	scrp->xdot = 640;
	scrp->ydot = 400;

	/* マウスカーソル位置設定（画面中央） */
	m_gx = 320;
	m_gy = 200;
	m_loc(m_gx, m_gy);

	t_setfont(0x7621, retkey_font);
}

/*=======================================================================
 |
 |		画面終了処理
 |
 |	void	w_endscr()
 |
 =======================================================================*/
void	w_endscr(void)
{
	m_csroff();

	t_kcgmode(0);		/* コードアクセス */

	/* ベクタを元に戻す */
	_dos_setvect(0x15, m_intp);

	t_fput(&func_save);	/* ファンクションキーラベル設定 */
	g_screen(3, 0, 0, 1);	/* スクリーンモード設定 */
	g_cls();		/* グラフィック画面クリア */
	t_cls();		/* テキスト画面クリア */
	t_csron();		/* テキストカーソル表示 */
	printf("\x1b[>1l");	/* 最下位行システム使用 */
}

/*=======================================================================
 |
 |		ウィンドウオープン処理
 |
 |	WINDOW	*w_open(sx, sy, nx, ny, dx, dy)
 |
 |		int	sx;		開始Ｘ座標
 |		int	sy;		開始Ｙ座標
 |		int	nx;		桁数
 |		int	ny;		行数
 |		int	dx;		１桁のドット数（８固定）
 |		int	dy;		１行のドット数
 |
 |		返値			ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
WINDOW	*w_open(int sx, int sy, int nx, int ny, int dx, int dy)
{
	WINDOW	*wp;

	sx &= ~7;

	/* 位置補正 */
	if (sx < 0)
		sx = 0;
	if (sy < 0)
		sy = 0;
	if (sx + nx * dx > 640)
		sx = 640 - nx * dx;
	if (sy + ny * dy > 400)
		sy = 400 - ny * dy;

	/* ウィンドウエリア確保 */
	if ((wp = w_alloc(sx, sy, nx, ny, dx, dy)) == 0)
		return(0);

	/* チェイン登録処理 */
	w_push(wp);

	/* ウィンドウエリアセーブ */
	w_save(wp);

	return(wp);
}

/*=======================================================================
 |
 |		ウィンドウ作業エリア確保
 |
 |	WINDOW	*w_alloc(sx, sy, nx, ny, dx, dy)
 |
 |		int	sx;		開始桁位置
 |		int	sy;		開始行位置
 |		int	nx;		桁数
 |		int	ny;		行数
 |		int	dx;		１桁のドット数（８固定）
 |		int	dy;		１行のドット数
 |
 |		返値			ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
static	WINDOW	*w_alloc(int sx, int sy, int nx, int ny, int dx, int dy)
{
	WINDOW	*wp;
	int	size;

	/* ウィンドウ構造体エリア確保 */
	if ((wp = (WINDOW *)malloc(sizeof(WINDOW))) == 0)
		return(0);
	memset(wp, 0, sizeof(WINDOW));

	/* ウィンドウ情報セーブ */
	wp->sx = sx;
	wp->sy = sy;
	wp->nx = nx;
	wp->ny = ny;
	wp->dx = dx;
	wp->dy = dy;
	wp->xdot = nx * dx;
	wp->ydot = ny * dy;

	/* グラフィックＶＲＡＭセーブエリア確保 */
	size = dy * ny * nx;
	if ((wp->bram = malloc(size)) == 0) {
		w_free(wp);
		return(0);
	}
	if ((wp->rram = malloc(size)) == 0) {
		w_free(wp);
		return(0);
	}
	if ((wp->gram = malloc(size)) == 0) {
		w_free(wp);
		return(0);
	}

	return(wp);
}

/*=======================================================================
 |
 |		ウィンドウエリアセーブ処理
 |
 |	void	w_save(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
static	void	w_save(WINDOW *wp)
{
	register int	i;
	char	*bp, *rp, *gp;
	char	*vp;

	/* グラフィックＶＲＡＭセーブ */
	bp = wp->bram;
	rp = wp->rram;
	gp = wp->gram;
	FP_OFF(vp) = wp->sy * 80 + wp->sx / 8;
	for (i = wp->ydot; i != 0; i--) {
		FP_SEG(vp) = SEG_BVRAM;
		memcpy(bp, vp, wp->nx);
		bp += wp->nx;

		FP_SEG(vp) = SEG_RVRAM;
		memcpy(rp, vp, wp->nx);
		rp += wp->nx;

		FP_SEG(vp) = SEG_GVRAM;
		memcpy(gp, vp, wp->nx);
		gp += wp->nx;

		FP_OFF(vp) += 80;
	}
}

/*=======================================================================
 |
 |		ウィンドウクリア処理
 |
 |	void	w_erase(wp, color)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |		int	color;		背景色
 |
 =======================================================================*/
static	void	w_erase(WINDOW *wp, int color)
{
	/* グラフィックＶＲＡＭクリア */
	g_line(wp->sx, wp->sy, wp->sx+wp->xdot-1, wp->sy+wp->ydot-1
								, color, 2, 0);
}

/*=======================================================================
 |
 |		ウィンドウクローズ処理
 |
 |	void	w_close(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
void	w_close(WINDOW *wp)
{
	WINDOW	*cp;

	/* ウィンドウ消去 */
	if (wp == wtop) {
		w_pop();
		w_restore(wp);
		w_free(wp);
	} else if ((cp = w_pick(wp)) != 0) {
		w_restore(wp);
		w_free(cp);
		w_free(wp);
	}
}

/*=======================================================================
 |
 |		標準型ウィンドウ作成処理
 |
 |	void	w_stdwin(wp, title, tcolor, gcolor, scolor)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |		char	*title;		タイトル
 |		int	tcolor;		タイトル文字色
 |		int	bcolor;		背景色
 |		int	scolor;		影色
 |
 =======================================================================*/
void	w_stdwin(WINDOW *wp, char *title, int tcolor, int bcolor, int scolor)
{
	int	sx, sy, dy, ex, ey, by;
	char	tile[6];

	/* ウィンドウ情報取り出し */
	sx = wp->sx;
	sy = wp->sy;
	dy = wp->dy;
	ex = sx + wp->xdot - 1;
	ey = sy + wp->ydot - 1;

	/* タイルパターンセット */
	if (bcolor & C_BLUE)
		tile[0] = 0xff;
	else
		tile[0] = 0x00;

	if (bcolor & C_RED)
		tile[1] = 0xff;
	else
		tile[1] = 0x00;

	if (bcolor & C_GREEN)
		tile[2] = 0xff;
	else
		tile[2] = 0x00;

	if (scolor & C_BLUE)
		tile[3] = 0xff;
	else
		tile[3] = 0x00;

	if (scolor & C_RED)
		tile[4] = 0xff;
	else
		tile[4] = 0x00;

	if (scolor & C_GREEN)
		tile[5] = 0xff;
	else
		tile[5] = 0x00;

	/* ウィンドウ表示枠 */
	g_line(sx, sy, ex-8, ey-8, bcolor, 2, 0);
	g_line(sx+8, ey-7, ex, ey, scolor, 2, 0);
	g_line(ex-7, sy+8, ex, ey, scolor, 2, 0);
	by = sy + (dy - 16) / 2 + 1;
	g_line(sx+3, by, ex-11, by+13, bcolor, 2, 2, 0, 6, tile);
	wp->shadow = 8;

	/* タイトル表示 */
	w_loc(wp, (wp->nx - strlen(title)) / 2 - 1, 0);
	set_color(tcolor, bcolor);
	w_putch(wp, ' ');
	w_cputs(wp, title);
	w_putch(wp, ' ');

	g_line(sx, sy, ex-8, ey-8, scolor, 1, 0);
	g_line(sx+3, sy+dy-1, ex-11, ey-11, scolor, 1, 0);

	wp->bcolor = bcolor;	/* 背景色セーブ */
}

/*=======================================================================
 |
 |		項目選択処理
 |
 |	int	w_select(wp, strp, nx, ny, sel, fcolor, scolor)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |		char	**strp;		項目表示データ
 |		int	nx;		横方向項目数
 |		int	ny;		縦方向項目数
 |		int	sel;		初期選択項目
 |		int	fcolor;		文字色
 |		int	scolor;		選択項目色
 |
 |		返値			選択項目番号
 |
 =======================================================================*/
int	w_select(WINDOW *wp, char **strp, int nx, int ny, int sel, int fcolor
	, int scolor)
{
	register int	x, y;
	int	tx, ty, len, i, loop_sw, ch;
	MOUSE	*mp, *wmp;

	/* ワークエリア確保 */
	if ((mp = malloc(sizeof(MOUSE) * (nx*ny+1))) == 0)
		return(-1);

	w_popup(wp);

	/* 文字色設定 */
	set_color(fcolor, wp->bcolor);

	m_csroff();		/* マウスカーソルＯＦＦ */

	/* マウス選択項目データ作成 */
	len = (wp->nx - 3) / nx;
	wmp = mp;
	i = 0;
	for (x = 0; x < nx; x++) {
		for (y = 0; y < ny; y++) {
			tx = x * len + 1;
			ty = y + 1;
			w_dspstr(wp, tx+1, ty, *strp++);
			wmp->x = tx;
			wmp->y = ty;
			wmp->len = len;
			wmp->code = 0;
			wmp++;
			if (i++ == sel)
				m_loc(wp->sx + (x * len + len * 3 / 4) * wp->dx
				,wp->sy + (y + 1) * wp->dy + wp->dy / 2);
		}
	}
	wmp->len = 0;

	/* マウス選択項目登録 */
	m_reg(wp, mp, scolor);

	/* 入力処理 */
	loop_sw = 1;
	while(loop_sw) {
		ch = w_input(wp, 0);
		if (m_sel != -1) {
			x = m_sel / ny;
			y = m_sel % ny;
		}
		if (ch >= KEY_F1 && ch <= KEY_F10) {
			sel = ch;
			break;
		}
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
		case KEY_CR:
			sel = m_sel;
			loop_sw = 0;
			break;
		case KEY_UP:
			if (m_sel == -1) {
				x = 0;
				y = 0;
			} else if (y == 0)
				y = ny - 1;
			else
				y--;
		csr_set:
			m_gx = wp->sx + (x * len + len * 3 / 4) * wp->dx;
			m_gy = wp->sy + (y + 1) * wp->dy + wp->dy / 2;
			m_loc(m_gx, m_gy);
			break;
		case KEY_DOWN:
			if (m_sel == -1) {
				x = 0;
				y = 0;
			} else if (y == ny - 1)
				y = 0;
			else
				y++;
			goto csr_set;
		case KEY_RIGHT:
			if (m_sel == -1) {
				x = 0;
				y = 0;
			} else if (x == nx - 1)
				x = 0;
			else
				x++;
			goto csr_set;
		case KEY_LEFT:
			if (m_sel == -1) {
				x = 0;
				y = 0;
			} else if (x == 0)
				x = nx - 1;
			else
				x--;
			goto csr_set;
		case KEY_ESC:
			sel = -1;
			loop_sw = 0;
			break;
		}
	}

	/* マウス選択項目解除 */
	m_rel(mp);

	/* ワークエリア解放 */
	free(mp);

	return(sel);
}

/*=======================================================================
 |
 |		ウィンドウ移動処理
 |
 |	void	w_xmove(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
void	w_xmove(WINDOW *wp)
{
	int	plsw, prsw;
	int	w_gx, w_gy;
	int	dx, dy;

	dx = m_gx - wp->sx;
	dy = m_gy - wp->sy;

	m_area(dx, dy, 640 - wp->xdot + dx, 400 - wp->ydot + dy);

	w_gx = m_gx;
	w_gy = m_gy;
	m_csroff();
	w_frame(wp->sx, wp->sy, wp->xdot, wp->ydot, C_MAGENTA);
	w_frame(wp->sx, wp->sy, wp->xdot, wp->ydot, C_WHITE);
	m_csron();
	for (;;) {
		m_pos(&m_gx, &m_gy, &plsw, &prsw);
		if (plsw == 0)
			break;
		if (m_gx != w_gx || m_gy != w_gy) {
			m_csroff();
			w_frame(w_gx-dx, w_gy-dy, wp->xdot, wp->ydot, C_WHITE);
			w_frame(m_gx-dx, m_gy-dy, wp->xdot, wp->ydot, C_WHITE);
			m_csron();
			w_gx = m_gx;
			w_gy = m_gy;
		}
	}
	m_csroff();
	w_frame(w_gx-dx, w_gy-dy, wp->xdot, wp->ydot, C_WHITE);
	w_frame(wp->sx, wp->sy, wp->xdot, wp->ydot, C_MAGENTA);
	m_area(0, 0, 639, 399);

	if (wp == csr_wp) {
		csr_sx += (w_gx - dx - wp->sx) & ~7;
		csr_sy += w_gy - dy - wp->sy;
	}

	w_move(wp, w_gx - dx, w_gy - dy);

	m_csron();
}

/*=======================================================================
 |
 |		ウィンドウ移動処理
 |
 |	void	w_move(wp, x, y)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |		int	x;		移動先Ｘ座標
 |		int	y;		移動先Ｙ座標
 |
 =======================================================================*/
void	w_move(WINDOW *wp, int x, int y)
{
	WINDOW	*cp;

	if ((cp = w_pick(wp)) != 0) {
		w_restore(wp);
		wp->sx = cp->sx = x & ~7;
		wp->sy = cp->sy = y;
		w_save(wp);
		w_restore(cp);
		w_push(wp);
		w_free(cp);
	}
}

/*=======================================================================
 |
 |		ウィンドウ枠表示処理
 |
 |	void	w_frame(sx, sy, dx, dy, color)
 |
 |		int	sx;		開始桁位置
 |		int	sy;		開始行位置
 |		int	dx;		桁数
 |		int	dy;		行数
 |		int	color;		枠色
 |
 =======================================================================*/
void	w_frame(int sx, int sy, int dx, int dy, int color)
{
	char	far	*vp;
	register int	i, j;
	static	int	col_tbl[3][2] = {
		{C_BLUE,  SEG_BVRAM},
		{C_RED,   SEG_RVRAM},
		{C_GREEN, SEG_GVRAM}
	};

	for (j = 0; j < 3; j++) {
		if (color & col_tbl[j][0]) {
			FP_SEG(vp) = col_tbl[j][1];
			for (i = 0; i < dx / 8; i++) {
				FP_OFF(vp) = sy * 80 + sx / 8 + i;
				*vp ^= 0xff;
				FP_OFF(vp) = (sy + dy) * 80 + sx / 8 + i;
				*vp ^= 0xff;
			}
			for (i = 0; i < dy; i++) {
				FP_OFF(vp) = (sy + i) * 80 + sx / 8;
				*vp ^= 0x80;
				FP_OFF(vp) = (sy + i) * 80 + (sx + dx) / 8 - 1;
				*vp ^= 0x01;
			}
		}
	}
}

/*=======================================================================
 |
 |		ウィンドウポップアップ処理
 |
 |	void	w_popup(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
void	w_popup(WINDOW *wp)
{
	WINDOW	*cp;

	if (wp != wtop) {
		if ((cp = w_pick(wp)) != 0) {
			w_restore(cp);
			w_free(cp);
			w_push(wp);
		}
	}
}

/*=======================================================================
 |
 |		ウィンドウピックアップ処理
 |
 |	WINDOW	*w_pick(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 |		返値			ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
static	WINDOW	*w_pick(WINDOW *wp)
{
	WINDOW	*cp;
	char	*mp;
	int	size, st;

	/* ワークエリア確保 */
	size = wp->ydot * wp->nx;
	if ((mp = malloc(size)) == 0)
		return(0);
	memset(mp, 0, size);

	/* 現ウィンドウセーブ */
	if ((cp = w_alloc(wp->sx, wp->sy, wp->nx, wp->ny, wp->dx, wp->dy)) == 0) {
		free(mp);
		return(0);
	}
	cp->shadow = wp->shadow;
	w_save(cp);

	/* ピックアップ処理 */
	st = w_pick_sub(wp, cp, mp);

	/* ワークエリア解放 */
	free(mp);

	/* ワークウィンドウ解放 */
	if (st == 0) {
		w_free(cp);
		return(0);
	}

	return(cp);
}

static	int	w_pick_sub(WINDOW *wp, WINDOW *cp, char *mp)
{
	int	sx1, sy1, ex1, ey1, sx2, sy2, ex2, ey2, sd1, sd2;
	int	sx, sy, ex, ey;
	WINDOW	*tp;

	tp = w_pop();
	if (tp == NULL)
		return(0);

	if (tp != wp) {
		if (w_pick_sub(wp, cp, mp) == 0) {
			w_push(tp);
			return(0);
		}

		sx1 = wp->sx;
		sy1 = wp->sy;
		ex1 = sx1 + wp->xdot;
		ey1 = sy1 + wp->ydot;
		sd1 = wp->shadow;

		sx2 = tp->sx;
		sy2 = tp->sy;
		ex2 = sx2 + tp->xdot;
		ey2 = sy2 + tp->ydot;
		sd2 = tp->shadow;

		sx = max(sx1, sx2);
		sy = max(sy1, sy2);
		ex = min(ex1 - sd1, ex2 - sd2);
		ey = min(ey1 - sd1, ey2 - sd2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1, ex2 - sd2);
		sy = max(sy1, sy2 + sd2);
		ex = min(ex1 - sd1, ex2);
		ey = min(ey1 - sd1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1, sx2 + sd2);
		sy = max(sy1, ey2 - sd2);
		ex = min(ex1 - sd1, ex2);
		ey = min(ey1 - sd1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(ex1 - sd1, sx2);
		sy = max(sy1 + sd1, sy2);
		ex = min(ex1, ex2 - sd2);
		ey = min(ey1, ey2 - sd2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(ex1 - sd1, ex2 - sd2);
		sy = max(sy1 + sd1, sy2 + sd2);
		ex = min(ex1, ex2);
		ey = min(ey1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(ex1 - sd1, sx2 + sd2);
		sy = max(sy1 + sd1, ey2 - sd2);
		ex = min(ex1, ex2);
		ey = min(ey1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1 + sd1, sx2);
		sy = max(ey1 - sd1, sy2);
		ex = min(ex1, ex2 - sd2);
		ey = min(ey1, ey2 - sd2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1 + sd1, ex2 - sd2);
		sy = max(ey1 - sd1, sy2 + sd2);
		ex = min(ex1, ex2);
		ey = min(ey1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1 + sd1, sx2 + sd2);
		sy = max(ey1 - sd1, ey2 - sd2);
		ex = min(ex1, ex2);
		ey = min(ey1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		w_push(tp);
	}
	return(1);
}

static	void	w_change(WINDOW *wp, WINDOW *cp, WINDOW *tp, char *mp, int sx
		, int sy, int ex, int ey)
{
	int	x, y;
	register int	p1, p2;
	char	c;

	sx /= 8;
	ex /= 8;
	for (y = sy; y < ey; y++) {
		for (x = sx; x < ex; x++) {
			p1 = (y - wp->sy) * wp->nx + (x - wp->sx / 8);
			if (mp[p1] == 0) {
				p2 = (y - tp->sy) * tp->nx + (x - tp->sx / 8);
				c = tp->bram[p2];
				tp->bram[p2] = wp->bram[p1];
				wp->bram[p1] = cp->bram[p1];
				cp->bram[p1] = c;
				c = tp->rram[p2];
				tp->rram[p2] = wp->rram[p1];
				wp->rram[p1] = cp->rram[p1];
				cp->rram[p1] = c;
				c = tp->gram[p2];
				tp->gram[p2] = wp->gram[p1];
				wp->gram[p1] = cp->gram[p1];
				cp->gram[p1] = c;
				mp[p1] = 1;
			}
		}
	}
}

/*=======================================================================
 |
 |		ウィンドウリストア処理
 |
 |	void	w_restore(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
static	void	w_restore(WINDOW *wp)
{
	register int	i;
	int	len, sdx, sdy;
	char	*bp, *rp, *gp;
	char	*vp;

	/* グラフィックＶＲＡＭリストア */
	bp = wp->bram;
	rp = wp->rram;
	gp = wp->gram;
	sdy = wp->shadow;
	sdx = sdy / 8;
	len = wp->nx - sdx;
	FP_OFF(vp) = wp->sy * 80 + wp->sx / 8;
	for (i = wp->ydot; i != 0; i--) {
		if (i == wp->ydot - sdy)
			len += sdx;
		else if (i == sdy) {
			len -= sdx;
			FP_OFF(vp) += sdx;
			bp += sdx;
			rp += sdx;
			gp += sdx;
		}

		FP_SEG(vp) = SEG_BVRAM;
		memcpy(vp, bp, len);
		bp += wp->nx;

		FP_SEG(vp) = SEG_RVRAM;
		memcpy(vp, rp, len);
		rp += wp->nx;

		FP_SEG(vp) = SEG_GVRAM;
		memcpy(vp, gp, len);
		gp += wp->nx;

		FP_OFF(vp) += 80;
	}
}

/*=======================================================================
 |
 |		ウィンドウ作業エリア解放処理
 |
 |	void	w_free(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
static	void	w_free(WINDOW *wp)
{
	if (wp != 0) {
		/* ＶＲＡＭセーブエリア解放 */
		if (wp->bram != 0)
			free(wp->bram);
		if (wp->rram != 0)
			free(wp->rram);
		if (wp->gram != 0)
			free(wp->gram);

		/* ウィンドウ構造体解放 */
		free(wp);
	}
}

/*=======================================================================
 |
 |		ウィンドウチェイン登録処理
 |
 |	void	w_push(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
static	void	w_push(WINDOW *wp)
{
	wp->chain = wtop;
	wtop = wp;
}

/*=======================================================================
 |
 |		ウィンドウチェイン取り外し処理
 |
 |	WINDOW	*w_pop()
 |
 |		返値			ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
static	WINDOW	*w_pop(void)
{
	WINDOW	*wp;

	if (wtop == NULL)
		return(NULL);

	wp = wtop;
	wtop = wp->chain;
	return(wp);
}

/*=======================================================================
 |
 |		マウスカーソルがどのウィンドウ内にあるかチェック
 |
 |	WINDOW	*w_check()
 |
 |		返値			ウィンドウ構造体へのポインタ
 |					（０：どのウィンドウ内にもない）
 |
 =======================================================================*/
WINDOW	*w_check(void)
{
	return(w_check_sub(m_gx, m_gy));
}

static	WINDOW	*w_check_sub(int x, int y)
{
	WINDOW	*wp;

	for (wp = wtop; wp != 0; wp = wp->chain) {
		if (x >= wp->sx && x < wp->sx + wp->xdot
				&& y >= wp->sy && y < wp->sy + wp->ydot)
			return(wp);
	}
	return(0);
}


/*=======================================================================
 |
 |		g_lineのウィンドウ対応
 |
 |	int	w_line(wp,x1,y1,x2,y2,pal1,dc,lsw,lstyle,lentile,ptile)
 |
 =======================================================================*/
int	w_line(WINDOW *wp, int x1, int y1, int x2, int y2, int pal1, int dc
	, int lsw, int lstyle, int lentile, char *ptile)
{
	return(g_line(x1+wp->sx,y1+wp->sy,x2+wp->sx,y2+wp->sy,pal1,dc,lsw,lstyle,lentile,ptile));
}

/*=======================================================================
 |
 |		スクロールアップ
 |
 |	void	w_scroll_up(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
void	w_scroll_up(WINDOW *wp)
{
	char	*src, *dst;
	int	byte, i;

	/* ＶＲＡＭアドレスセット */
	FP_OFF(dst) = (wp->sy+wp->dy) * 80 + (wp->sx+wp->dx) / 8;
	FP_OFF(src) = FP_OFF(dst) + wp->dy * 80;

	byte = wp->nx - 3;

	/* １行スクロール */
	for (i = wp->ydot - wp->dy*3; i > 0; i--) {
		FP_SEG(src) = FP_SEG(dst) = SEG_BVRAM;
		memcpy(dst, src, byte);
		FP_SEG(src) = FP_SEG(dst) = SEG_RVRAM;
		memcpy(dst, src, byte);
		FP_SEG(src) = FP_SEG(dst) = SEG_GVRAM;
		memcpy(dst, src, byte);

		FP_OFF(src) += 80;
		FP_OFF(dst) += 80;
	}

	/* 最下位行クリア */
	for (i = wp->dy; i > 0; i--) {
		FP_SEG(dst) = SEG_BVRAM;
		memset(dst, (wp->bcolor & C_BLUE) ? 0xff : 0, byte);
		FP_SEG(dst) = SEG_RVRAM;
		memset(dst, (wp->bcolor & C_RED) ? 0xff : 0, byte);
		FP_SEG(dst) = SEG_GVRAM;
		memset(dst, (wp->bcolor & C_GREEN) ? 0xff : 0, byte);

		FP_OFF(dst) += 80;
	}
}

/*=======================================================================
 |
 |		スクロールダウン
 |
 |	void	w_scroll_down(wp)
 |
 |		WINDOW	*wp;		ウィンドウ構造体へのポインタ
 |
 =======================================================================*/
void	w_scroll_down(WINDOW *wp)
{
	char	*src, *dst;
	int	byte, i;

	/* ＶＲＡＭアドレスセット */
	FP_OFF(dst) = (wp->sy+wp->dy*(wp->ny-1)) * 80 + (wp->sx+wp->dx) / 8;
	FP_OFF(src) = FP_OFF(dst) - wp->dy * 80;

	byte = wp->nx - 3;

	/* １行スクロール */
	for (i = wp->ydot - wp->dy*3; i > 0; i--) {
		FP_SEG(src) = FP_SEG(dst) = SEG_BVRAM;
		memcpy(dst, src, byte);
		FP_SEG(src) = FP_SEG(dst) = SEG_RVRAM;
		memcpy(dst, src, byte);
		FP_SEG(src) = FP_SEG(dst) = SEG_GVRAM;
		memcpy(dst, src, byte);

		FP_OFF(src) -= 80;
		FP_OFF(dst) -= 80;
	}

	/* 最上位行クリア */
	for (i = wp->dy; i > 0; i--) {
		FP_SEG(dst) = SEG_BVRAM;
		memset(dst, (wp->bcolor & C_BLUE) ? 0xff : 0, byte);
		FP_SEG(dst) = SEG_RVRAM;
		memset(dst, (wp->bcolor & C_RED) ? 0xff : 0, byte);
		FP_SEG(dst) = SEG_GVRAM;
		memset(dst, (wp->bcolor & C_GREEN) ? 0xff : 0, byte);

		FP_OFF(dst) -= 80;
	}
}

/*=======================================================================
 |
 |		ＡＮＫフォントファイル読み込み
 |
 |	void	read_font(font_file)
 |
 |		char	*font_file;	フォントファイル名
 |
 =======================================================================*/
static	void	read_font(char *font_file)
{
	FILE	*fp;
	register int	i;
	char	fbuf[34];

	if (font_file) {
		/* フォントファイル指定あり */
		if ((fp = fopen(font_file, "rb")) == 0)
			return;
		fread(ank_font, 16, 256, fp);
		fclose(fp);
	} else {
		/* フォントファイル指定なし */
		for (i = 0; i < 256; i++) {
			t_getfont(i+0x8000, fbuf);
			memcpy(ank_font[i], fbuf+2, 16);
		}
	}
}
