/************************************************************************
 *									*
 *		グラフィックウィンドウライブラリ			*
 *									*
 *		名称		: ヘッダファイル			*
 *		ファイル名	: gwlib.h				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

/*	キーコード	*/
#define	KEY_HELP	0x01
#define	KEY_BS		0x08
#define	KEY_HOME	0x0b
#define	KEY_CLR		0x0c
#define	KEY_CR		0x0d
#define	KEY_ESC		0x1b
#define	KEY_RIGHT	0x1c
#define	KEY_LEFT	0x1d
#define	KEY_UP		0x1e
#define	KEY_DOWN	0x1f
#define	KEY_DEL		0x7f
#define	KEY_INS		0x11
#define	KEY_FUNC	0x0630
#define	KEY_F1		0x0630
#define	KEY_F2		0x0631
#define	KEY_F3		0x0632
#define	KEY_F4		0x0633
#define	KEY_F5		0x0634
#define	KEY_F6		0x0635
#define	KEY_F7		0x0636
#define	KEY_F8		0x0637
#define	KEY_F9		0x0638
#define	KEY_F10		0x0639
#define	MOUSE_R		0x0700
#define	MOUSE_L		0x0701

/*	グラフィックカラーコード	*/
#define	C_BLACK		0
#define	C_BLUE		1
#define	C_RED		2
#define	C_MAGENTA	3
#define	C_GREEN		4
#define	C_CYAN		5
#define	C_YELLOW	6
#define	C_WHITE		7

/*	セグメントアドレス	*/
#define	SEG_BVRAM	0xa800		/* グラフィックＶＲＡＭセグメント */
#define	SEG_RVRAM	0xb000		/* グラフィックＶＲＡＭセグメント */
#define	SEG_GVRAM	0xb800		/* グラフィックＶＲＡＭセグメント */

/*	ｉ／ｏポートアドレス	*/
#define	PA_INTM1	0x00		/* マスタ割り込みコントローラ */
#define	PA_INTM2	0x02		/* マスタ割り込みマスクレジスタ */

/*	型定義		*/
#ifndef	u_int
#define	u_int	unsigned int
#endif
#ifndef	u_char
#define	u_char	unsigned char
#endif

/*	ウィンドウ構造体	*/
struct	window	{
	int	sx;			/* 開始Ｘ座標 */
	int	sy;			/* 開始Ｙ座標 */
	int	dx;			/* １桁のドット数 */
	int	dy;			/* １行のドット数 */
	int	nx;			/* 桁数 */
	int	ny;			/* 行数 */
	int	xdot;			/* Ｘ方向ドット数 */
	int	ydot;			/* Ｙ方向ドット数 */
	int	shadow;			/* 影部分ドット数 */
	int	bcolor;			/* ウィンドウ背景色 */
	int	flag;			/* フラグ */
	char	*bram;			/* グラフィックＶＲＡＭセーブエリア */
	char	*rram;			/* グラフィックＶＲＡＭセーブエリア */
	char	*gram;			/* グラフィックＶＲＡＭセーブエリア */
	struct	window	*chain;		/* チェインポインタ */
};
typedef	struct	window	WINDOW;

/*	マウス情報	*/
struct	mouse	{
	int	x;			/* Ｘ座標 */
	int	y;			/* Ｙ座標 */
	int	len;			/* 長さ */
	int	code;			/* 識別コード */
};
typedef	struct	mouse	MOUSE;

/*	項目選択情報	*/
struct	select	{
	WINDOW	*wp;			/* ウィンドウポインタ */
	MOUSE	*mp;			/* マウス情報ポインタ */
	int	color;			/* 選択時カラー */
	struct	select	*next;		/* チェインポインタ */
};
typedef	struct	select	SELECT;
	
/*	ライン表示テーブル		*/
struct	line	{
	int	flag;			/* フラグ　=0:線　=1:枠  =-1:終了 */
	int	pos_x1;			/* 開始桁位置 */
	int	pos_y1;			/* 開始行位置 */
	int	pos_x2;			/* 終了桁位置 */
	int	pos_y2;			/* 終了行位置 */
};
typedef	struct	line	LINE;

/*	ファンクションキー設定データ	*/
struct	key_buf {
	char	fun[20][16];		/* ファンクションキー */
	char	cur[11][6];		/* 特殊キー */
};

/*	固定部画面表示テーブル	*/
struct	scrdat	{
	int	pos_x;			/* 表示桁位置 */
	int	pos_y;			/* 表示行位置 */
	char	*data;			/* 表示データ */
};
typedef	struct scrdat SCRDAT;

/*	データ入力パラメータ	*/
struct	input	{
	char	*bufp;		/* 入力バッファポインタ */
	int	x;		/* 桁位置 */
	int	y;		/* 行位置 */
	int	len1;		/* 整数部桁数（文字型の場合は文字数） */
	int	len2;		/* 小数部桁数（文字型の場合は－１とする） */
	int	flag;		/* 入力フラグ */
	int	cr;		/* ＣＲキー */
	int	up;		/* 上矢印キー */
	int	down;		/* 下矢印キー */
	int	left;		/* 左矢印キー */
	int	right;		/* 右矢印キー */
};
typedef	struct	input	INPUT;

/*	関数定義	*/

/*	clib.c	*/
extern	int	w_inputx(WINDOW *, INPUT *, int, MOUSE *, int);
extern	int	w_inputs(WINDOW *, char *, int, int, int, int);
extern	int	w_inputn(WINDOW *, char *, int, int, int, int, int);
extern	int	waitkey(WINDOW *);
extern	int	inkey(void);
extern	int	w_input(WINDOW *, int);
extern	void	m_reg(WINDOW *, MOUSE *, int);
extern	void	m_rel(MOUSE *);
extern	void	w_dspstr(WINDOW *, int, int, char *);
extern	void	w_dspnstr(WINDOW *, int, int, char *, int);
/*extern	void	w_xprintf(WINDOW *, int, int, char *, ...);*/
extern	void	w_xprintf();
extern	void	w_dspscr(WINDOW *, SCRDAT *);
extern	void	w_reverse(WINDOW *, int, int, int, int);
extern	void	w_dspline(WINDOW *, LINE *, int);
extern	void	w_loc(WINDOW *, int, int);
extern	void	csr_on(void);
extern	void	csr_off(void);
extern	void	beep(void);
extern	void	w_abort(char *);
extern	void	w_putch(WINDOW *, int);
extern	void	w_cputs(WINDOW *, char *);
extern	void	set_color(int, int);
extern	void	dspfunc(char **);
extern	void	far interrupt csr_dsp(void);
extern	void	csr_dsp1(void);

/*	wlib.c	*/
extern	int	w_select(WINDOW *, char **, int, int, int, int, int);
extern	void	w_stdwin(WINDOW *, char *, int, int, int);
extern	void	w_xmove(WINDOW *);
extern	void	w_move(WINDOW *, int, int);
extern	void	w_frame(int, int, int, int, int);
extern	void	w_initscr(int, int, int, char *);
extern	void	w_endscr(void);
extern	WINDOW	*w_open(int, int, int, int, int, int);
extern	void	w_close(WINDOW *);
extern	void	w_popup(WINDOW *);
extern	WINDOW	*w_check(void);
/*extern	int	w_line(WINDOW *, int, int, int, int, int, int, ...);*/
extern	int	w_line();
extern	void	w_scroll_up(WINDOW *);
extern	void	w_scroll_down(WINDOW *);

/*	tlib.c	*/
extern	void	t_loc(int, int);
extern	void	t_cls(void);
extern	void	t_fget(struct key_buf *);
extern	void	t_fput(struct key_buf *);
extern	void	t_csron(void);
extern	void	t_csroff(void);
extern	void	t_setfont(int, char *);
extern	void	t_getfont(int, char *);
extern	void	t_kcgmode(int);

/*	glib.c	*/
extern	int	g_init(void);
extern	int	g_screen(int ,int, int, int);
extern	int	g_view(int, int, int, int, int, int);
extern	int	g_color(int, int, int, int);
extern	int	g_color2(int, int);
extern	int	g_cls(void);
extern	int	g_pset(int, int, int);
extern	int	g_preset(int, int);
/*extern	int	g_line(int, int, int, int, int, int, ...);*/
extern	int	g_line();
/*extern	int	g_circle(int, int, int, int, int, int, ...);*/
extern	int	g_circle();
extern	int	g_paint(int, int, int, int);
extern	int	g_paint2(int, int, int, unsigned char *, int);
extern	int	g_get(int, int, int, int, unsigned char *, int);
extern	int	g_put(int, int, unsigned char *, int, int, int, int, int);
extern	int	g_kanji(int, int, unsigned int, int, int, int, int);
extern	int	g_roll(int, int, int);
extern	int	g_point(int, int);

/*	mlib.c	*/
extern	int	m_init(void);
extern	void	m_csron(void);
extern	void	m_csroff(void);
extern	void	m_pos(int *, int *, int *, int *);
extern	void	m_loc(int, int);
extern	void	m_lon(int *, int *, int *, int *);
extern	void	m_loff(int *, int *, int *, int *);
extern	void	m_ron(int *, int *, int *, int *);
extern	void	m_roff(int *, int *, int *, int *);
extern	void	m_csr(int, int, char *);
extern	void	m_mov(int *, int *);
extern	void	m_step(int, int);
extern	void	m_area(int, int, int, int);
extern	void	m_color(int);

/*	データ定義	*/
extern	WINDOW	*scrp;		/* 全画面ポインタ */
extern	WINDOW	*csr_wp;	/* カーソルの存在するウィンドウポインタ */
extern	int	func_line;	/* ファンクションキー表示行 */
extern	int	csr_sx;		/* カーソルＸ座標 */
extern	int	csr_sy;		/* カーソルＹ座標 */
extern	int	csr_flag;	/* カーソル表示フラグ */
extern	int	g_fcolor;	/* 文字色 */
extern	int	g_bcolor;	/* 背景色 */
extern	int	g_kcolor;	/* 漢字入力行の色 */
extern	int	g_fkcolor;	/* ファンクションキー選択時の色 */
extern	int	m_gx;		/* マウスＸ座標（グラフィック座標） */
extern	int	m_gy;		/* マウスＹ座標（グラフィック座標） */
extern	int	m_sel;		/* マウス選択項目番号 */
extern	int	m_sel_color;	/* 選択項目の反転色 */
extern	int	m_csr_flag;	/* マウスカーソル表示フラグ */
extern	char	ank_font[][16];	/* ＡＮＫフォントバッファ */
extern	void	(far interrupt *m_intp)();/* マウス割り込みセーブエリア */
extern	int	mouse_drv;	/* マウスドライバ種別 */
extern	struct key_buf key_data; /* キーコード */

/*	ＭＳ－Ｃの関数をＴＵＲＢＯ－Ｃの関数に置き換える */
#ifdef	__TURBOC__
#define	_dos_getvect(x)		getvect(x)
#define	_dos_setvect(x,y)	setvect(x,y)
#define	_enable()		enable()
#define	_disable()		disable()
#endif
