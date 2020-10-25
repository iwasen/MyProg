/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: ＢＩＯＳコール処理			*
 *		ファイル名	: bios.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

#define		high(x)		(int)(((x) >> 8) & 0xff)
			/* ワード型からハイ・バイトを得るマクロ */
#define		low(x)		(int)((x) & 0xff)
			/* ワード型からロー・バイトを得るマクロ */
#define		btow(h,l)	(((u_int)(h) << 8) | (l))
			/* ハイ・バイトとロー・バイトからワード型を得るマクロ */
mouse_drv = 0;

static	u_char	glio_work[0x1200];	/* グラフＬＩＯワークエリア */

static	u_int	glio_seg;	/* ワークエリアのセグメント */
static	u_int	glio_off;	/* ワークエリアのオフセット */
static	u_char	glio_ah;	/*  */

static	u_char	iret = 0xcf;	/* ＩＲＥＴ コード */

static	u_int	g_lio(int);
static	void	m_int(union REGS *);

/*=======================================================================
 |
 |		カーソル位置設定
 |
 |	void	t_loc(x, y)
 |
 |		int	x;		Ｘ座標（０－７９）
 |		int	y;		Ｙ座標（０－２４）
 |
 =======================================================================*/
void	t_loc(int x, int y)
{
	union REGS	reg;

	reg.h.cl = 0x10;
	reg.h.ah = 3;
	reg.h.dh = y;
	reg.h.dl = x;
	int86(0xdc, &reg, &reg);
}

/*=======================================================================
 |
 |		文字色の設定
 |
 |	void	t_color(c)
 |
 |		int	c;		テキストカラーコード
 |
 =======================================================================*/
void	t_color(int c)
{
	union REGS	reg;


	reg.h.cl = 0x10;
	reg.h.ah = 2;
	reg.h.dl = c;
	int86(0xdc,&reg,&reg);
}

/*=======================================================================
 |
 |		テキスト画面クリア
 |
 |	void	t_cls()
 |
 =======================================================================*/
void	t_cls()
{
	union REGS	reg;

	reg.h.cl = 0x10;
	reg.h.ah = 10;
	reg.h.dl = 2;
	int86(0xdc, &reg, &reg);
}

/*=======================================================================
 |
 |		テキストカーソル表示
 |
 |	void	t_csron()
 |
 =======================================================================*/
void	t_csron()
{
	union REGS	reg;

	reg.h.ah = 0x11;
	int86(0x18, &reg, &reg);
}

/*=======================================================================
 |
 |		テキストカーソル消去
 |
 |	void	t_csroff()
 |
 =======================================================================*/
void	t_csroff()
{
	union REGS	reg;

	reg.h.ah = 0x12;
	int86(0x18, &reg, &reg);
}

/*=======================================================================
 |
 |		フォントデータ読み込み
 |
 |	void	t_getfont(code, font)
 |
 |		int	code;		外字コード
 |		char	*font;		フォントデータ
 |
 =======================================================================*/
void	t_getfont(int code, char *font)
{
	union REGS	reg;
	void	far *fptr;

	reg.h.ah = 0x14;
	reg.x.dx = code;
	fptr = font;
	reg.x.cx = FP_OFF(fptr);
	reg.x.bx = FP_SEG(fptr);

	int86(0x18, &reg, &reg);
}

/*=======================================================================
 |
 |		外字フォントデータセット
 |
 |	void	t_setfont(code, font)
 |
 |		int	code;		外字コード
 |		char	*font;		フォントデータ
 |
 =======================================================================*/
void	t_setfont(int code, char *font)
{
	union REGS	reg;
	void	far *fptr;

	reg.h.ah = 0x1a;
	reg.x.dx = code;
	fptr = font;
	reg.x.cx = FP_OFF(fptr);
	reg.x.bx = FP_SEG(fptr);

	int86(0x18, &reg, &reg);
}

/*=======================================================================
 |
 |		ファンクションキーの内容の取得
 |
 |	void	t_fget(keystr)
 |
 |		struct key_buf *keystr;		取り出された内容
 |
 =======================================================================*/
void	t_fget(struct key_buf *keystr)
{
	union REGS	reg;
	struct SREGS	sreg;
	void	far *fptr;

	reg.x.ax = 0;
	reg.x.cx = 0x0c;
	fptr = keystr;
	reg.x.dx = FP_OFF(fptr);
	sreg.ds = FP_SEG(fptr);

	int86x(0xdc, &reg, &reg, &sreg);
}

/*=======================================================================
 |
 |		ファンクションキーの内容の設定
 |
 |	void	t_fput(keystr)
 |
 |		struct key_buf *keystr;		設定する内容
 |
 =======================================================================*/
void	t_fput(struct key_buf *keystr)
{
	union REGS	reg;
	struct SREGS	sreg;
	void	far *fptr;

	reg.x.ax = 0;
	reg.x.cx = 0x0d;
	fptr = keystr;
	reg.x.dx = FP_OFF(fptr);
	sreg.ds = FP_SEG(fptr);

	int86x(0xdc, &reg, &reg, &sreg);
}

/*=======================================================================
 |
 |		グラフＬＩＯルーチンの呼び出し
 |
 |	int	g_lio(vect)
 |
 |		int	vect;		グラフＬＩＯの内部割込コード
 |
 |		返値	ret;		グラフＬＩＯの終了条件
 |
 =======================================================================*/
static	u_int	g_lio(int vect)
{
	union	REGS	reg;
	struct	SREGS	seg;

	reg.h.ah = glio_ah;
	reg.x.bx = glio_off;
	seg.es = seg.ds = glio_seg;

	int86x(vect, &reg, &reg, &seg);

	return ((u_int)reg.x.ax);
}

/*=======================================================================
 |
 |		グラフＬＩＯの初期化
 |
 |	int	g_init()
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_init()
{
	register int	i;
	u_int	doff, dseg;
	void	far *fptr;
	unsigned far *romp, far *vectp;

	fptr = glio_work;
	doff = FP_OFF(fptr);
	dseg = FP_SEG(fptr);

	glio_seg = dseg + (doff >> 4);
	glio_off = doff & 15;

	FP_SEG(romp) = 0xf990;
	FP_OFF(romp) = 6;
	FP_SEG(vectp) = 0;
	FP_OFF(vectp) = 0xa0 * 4;

	/* 割込ベクターＡ０Ｈ～ＡＦＨの設定 */
	for (i = 16; i != 0; --i) {
		*vectp++ = *romp;
		*vectp++ = 0xf990;
		romp += 2;
	}

	/* 割込ベクターＣ５Ｈの設定 */
	_dos_setvect(0xc5, (void (interrupt far *)())&iret);

	return (high(g_lio(0xa0)));
}

/*=======================================================================
 |
 |		グラフィック画面モード設定
 |
 |	int	g_screen(mod, sw, ac, disp)
 |
 |		int	mod;		画面モード
 |		int	sw;		画面スイッチ
 |		int	ac;		アクティブ画面
 |		int	disp;		ディスプレイ画面
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_screen(int mod, int sw, int ac, int disp)
{
	struct	param	{		/* パラメータリスト */
		u_char	mod;
		u_char	sw;
		u_char	ac;
		u_char	disp;
	};
	int	err;

	((struct param *)glio_work)->mod  = mod;
	((struct param *)glio_work)->sw   = sw;
	((struct param *)glio_work)->ac   = ac;
	((struct param *)glio_work)->disp = disp;

	err = high(g_lio(0xa1));

	outp(0x68,8);			/*  graph mode 400  */

	return (err);
}

/*=======================================================================
 |
 |		背景色等の指定
 |
 |	int	g_color(cbg, cbd, cfg, pmod)
 |
 |		int	cbg;		バックグランドカラー
 |		int	cbd;		ボーダーカラー
 |		int	cfg;		フォアグランドカラー
 |		int	pmod;		パレットモード
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_color(int cbg, int cbd, int cfg, int pmod)
{
	struct	param	{		/* パラメータリスト */
		u_char	dummy;
		u_char	cbg;
		u_char	cbd;
		u_char	cfg;
		u_char	pmod;
	};

	((struct param *)glio_work)->cbg  = cbg;
	((struct param *)glio_work)->cbd  = cbd;
	((struct param *)glio_work)->cfg  = cfg;
	((struct param *)glio_work)->pmod = pmod;

	return (high(g_lio(0xa3)));
}

/*=======================================================================
 |
 |		描画領域の塗りつぶし
 |
 |	int	g_cls()
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_cls()
{
	return (high(g_lio(0xa5)));
}

/*=======================================================================
 |
 |		直線または矩形の描画
 |
 |	int	g_line(x1, y1, x2, y2, pal1, dc, lsw, lstyle, lentile, ptile)
 |
 |		int	x1,y1;		座標１
 |		int	x2,y2;		座標２
 |		int	pal1;		パレット番号１
 |		int	dc;		描画コード
 |		int	lsw;		スイッチ
 |		int	lstyle; 	ラインスタイルまたはパレット番号２
 |		int	lentile;	タイルパターン長
 |		u_char	*ptile; 	タイルパターン
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_line(int x1, int y1, int x2, int y2, int pal1, int dc, int lsw
		, int lstyle, int lentile, u_char *ptile)
{
	struct	param	{		/* パラメータリスト */
		u_int	x1;
		u_int	y1;
		u_int	x2;
		u_int	y2;
		u_char	pal1;
		u_char	dc;
		u_char	lsw;
		u_int	lstyle;
		u_char	lentile;
		u_int	ptile_off;
		u_int	ptile_seg;
	};

	void	far *fptr;

	((struct param *)glio_work)->x1 = x1;
	((struct param *)glio_work)->y1 = y1;
	((struct param *)glio_work)->x2 = x2;
	((struct param *)glio_work)->y2 = y2;

	((struct param *)glio_work)->pal1 = pal1;
	((struct param *)glio_work)->dc   = dc;
	((struct param *)glio_work)->lsw  = lsw;
	((struct param *)glio_work)->lstyle = lstyle;
	((struct param *)glio_work)->lentile = lentile;

	fptr = ptile;
	((struct param *)glio_work)->ptile_off = FP_OFF(fptr);
	((struct param *)glio_work)->ptile_seg = FP_SEG(fptr);

	return (high(g_lio(0xa7)));
}

/*=======================================================================
 |
 |		マウスドライバー呼び出し
 |
 |	void	m_int(reg)
 |
 |		union REGS *reg;	レジスタ
 |
 =======================================================================*/
static	void	m_int(union REGS *reg)
{
	int86(0x33, reg, reg);
}

/*=======================================================================
 |
 |		マウスドライバー初期化
 |
 |	int	m_init()
 |
 |		int	c;		表示文字
 |
 |		返値			０：ドライバなし　１：あり
 |
 =======================================================================*/
int	m_init()
{
	union	REGS	reg;

	reg.x.ax = 0;
	m_int(&reg);
	
	return (reg.x.ax);
}

/*=======================================================================
 |
 |		マウスカーソル表示ＯＮ
 |
 |	void	m_csron()
 |
 =======================================================================*/
void	m_csron()
{
	union	REGS	reg;

	reg.x.ax = 1;
	m_int(&reg);
}

/*=======================================================================
 |
 |		マウスカーソル表示ＯＦＦ
 |
 |	void	m_csroff()
 |
 =======================================================================*/
void	m_csroff()
{
	union	REGS	reg;

	reg.x.ax = 2;
	m_int(&reg);
}

/*=======================================================================
 |
 |		マウスカーソルの位置を得る
 |
 |	void	m_pos(px, py, plsw, prsw)
 |
 |		int	*px;		マウスカーソルのＸ座標
 |		int	*py;		マウスカーソルのＹ座標
 |		int	*plsw;      	左ボタンの状態
 |		int	*prsw;      	右ボタンの状態
 |
 =======================================================================*/
void	m_pos(int *px, int *py, int *plsw, int *prsw)
{
	union	REGS	reg;

	reg.x.ax = 3;
	m_int(&reg);

	*px = reg.x.cx;
	*py = reg.x.dx;
	*plsw = reg.x.ax;
	*prsw = reg.x.bx;
	if (mouse_drv) {
		*plsw = (*prsw & 0x01) ? -1 : 0;
		*prsw = (*prsw & 0x02) ? -1 : 0;
	}
}

/*=======================================================================
 |
 |		マウスカーソルの位置を設定する
 |
 |	void	m_loc(x, y)
 |
 |		int	x;		マウスカーソルのＸ座標
 |		int	y;		マウスカーソルのＹ座標
 |
 =======================================================================*/
void	m_loc(int x, int y)
{
	union	REGS	reg;

	reg.x.ax = 4;
	reg.x.cx = x;
	reg.x.dx = y;
	m_int(&reg);
}

/*=======================================================================
 |
 |		タイマーセット処理
 |
 |	void	timset(tm)
 |
 |		int	tm;		タイマー値（１０ｍｓ単位）
 |
 =======================================================================*/
void	timset(int tm)
{
	extern	interrupt far	tm_int();
	union	REGS	reg;
	struct	SREGS	seg;
	void	far	*fptr;

	tm_ovf = 0;		/* タイマーオーバーフローフラグクリア */

	/* タイマーセット */
	reg.h.ah = 0x02;
	reg.x.cx = tm;
	fptr = tm_int;
	reg.x.bx = FP_OFF(fptr);
	seg.es =FP_SEG(fptr);
	int86x(0x1c, &reg, &reg, &seg);
}

/*=======================================================================
 |
 |		タイマー割り込み処理
 |
 |	void	tm_int()
 |
 =======================================================================*/
interrupt far	tm_int()
{
	tm_ovf = 1;
}

/*=======================================================================
 |
 |		タイマーリセット処理
 |
 |	void	timreset()
 |
 =======================================================================*/
void	timreset()
{
	timset(1);
	while (tm_ovf == 0);
		;
	tm_ovf = 0;
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃオープン
 |
 |	void	rs1_open(speed, mode, cmd)
 |
 |		int	speed;		通信速度
 |		int	mode;		通信モード
 |		int	cmd;		初期コマンド
 |
 =======================================================================*/
void	rs1_open(int speed, int mode, int cmd)
{
	static	char	rsbuf[8196];		/* 受信バッファ */
	union	REGS	reg;
	struct	SREGS	seg;
	char	far	*fptr;

	reg.h.ah = 0x01;	/* コマンド識別コード */
	reg.h.al = speed;	/* スピード設定 */

	reg.h.bh = 0;		/* 送信時タイムアウト時間（デフォルト） */
	reg.h.bl = 0;		/* 受信時タイムアウト時間（デフォルト） */

	reg.h.ch = mode;	/* モード設定 */
	reg.h.cl = cmd;		/* コマンド設定 */

	fptr = rsbuf;
	reg.x.di = FP_OFF(fptr);
	seg.es = FP_SEG(fptr);
	reg.x.dx = sizeof(rsbuf) - 20;	/* 受信バッファサイズ */

	int86x(0x19, &reg, &reg, &seg);	/* ＢＩＯＳコール */
}

/*=======================================================================
 |
 |		受信バッファチェック
 |
 |	int	rs1_rxc()
 |
 |		返値		受信文字数
 |
 =======================================================================*/
int	rs1_rxc()
{
	union	REGS	reg;

	reg.h.ah = 0x02;		/* コマンド識別コード */
	int86(0x19, &reg, &reg);	/* ＢＩＯＳコール */

	return(reg.x.cx);
}

/*=======================================================================
 |
 |		１文字受信
 |
 |	int	rs1_get()
 |
 |		返値		受信文字
 |
 =======================================================================*/
int	rs1_get()
{
	union	REGS	reg;

	reg.h.ah = 0x04;		/* コマンド識別コード */
	int86(0x19, &reg, &reg);	/* ＢＩＯＳコール */

	if (reg.h.cl & 0x38)		/* エラーチェック */
		return(0x100);

	return(reg.h.ch);
}

/*=======================================================================
 |
 |		１文字送信
 |
 |	void	rs1_put(c)
 |
 |		int	c;		送信文字
 |
 =======================================================================*/
void	rs1_put(int c)
{
	union	REGS	reg;

	reg.h.ah = 0x03;		/* コマンド識別コード */
	reg.h.al = c;
	int86(0x19, &reg, &reg);	/* ＢＩＯＳコール */
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃクローズ
 |
 |	void	rs1_close()
 |
 =======================================================================*/
void	rs1_close()
{
	union	REGS	reg;

	reg.h.ah = 0x05;		/* コマンド識別コード */
	reg.h.al = 0x40;
	int86(0x19, &reg, &reg);	/* ＢＩＯＳコール */
}

/*=======================================================================
 |
 |		１文字プリンター出力
 |
 |	void	lputc(c)
 |
 =======================================================================*/
void	lputc(int c)
{
	union REGS	reg;

	reg.h.ah = 0x11;
	reg.h.al = c;
	int86(0x1a, &reg, &reg);
}
