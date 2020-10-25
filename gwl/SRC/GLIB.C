/************************************************************************
 *									*
 *		グラフィックウィンドウライブラリ			*
 *									*
 *		名称		: グラフィック処理			*
 *		ファイル名	: glib.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<dos.h>
#include	<jstring.h>
#include	<conio.h>
#include	"gwlib.h"

#define		high(x)		(int)(((x) >> 8) & 0xff)
			/* ワード型からハイ・バイトを得るマクロ */
#define		low(x)		(int)((x) & 0xff)
			/* ワード型からロー・バイトを得るマクロ */

static	u_char	g_scrmod;	/*  screen mode  */

static	u_char	glio_work[0x1300];	/* グラフＬＩＯワークエリア */

static	u_int	glio_seg;	/* ワークエリアのセグメント */
static	u_int	glio_off;	/* ワークエリアのオフセット */
static	u_char	glio_ah;	/*  */

static	u_char	iret = 0xcf;	/* ＩＲＥＴ コード */

static	u_int	g_lio(int);

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
int	g_init(void)
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

	g_scrmod = 0;			/*  initialize screen mode  */

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

	if ((err = high(g_lio(0xa1))) == 0) {
		if ((char)mod != 0xff)
			g_scrmod = mod;
	}

	outp(0x68,8);			/*  graph mode 400  */

	return (err);
}


/*=======================================================================
 |
 |		描画領域の指定
 |
 |	int	g_view(x1, y1, x2, y2, ca, cb)
 |
 |		int	x1,y1;		ビューポートの左上座標
 |		int	x2,y2;		ビューポートの右下座標
 |		int	ca;		領域色
 |		int	cb;		境界色
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_view(int x1, int y1, int x2, int y2, int ca, int cb)
{
	struct	param	{		/* パラメータリスト */
		u_int	x1;
		u_int	y1;
		u_int	x2;
		u_int	y2;
		u_char	ca;
		u_char	cb;
	};

	((struct param *)glio_work)->x1 = x1;
	((struct param *)glio_work)->y1 = y1;
	((struct param *)glio_work)->x2 = x2;
	((struct param *)glio_work)->y2 = y2;
	((struct param *)glio_work)->ca = ca;
	((struct param *)glio_work)->cb = cb;

	return (high(g_lio(0xa2)));
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
 |		パレット番号と表示色コードの対応
 |
 |	int	g_color2(pal, cc)
 |
 |		int	pal;		パレット番号
 |		int	cc;		表示色コード
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_color2(int pal, int cc)
{
	struct	param	{		/* パラメータリスト */
		u_char	pal;
		u_int	cc;
	};

	((struct param *)glio_work)->pal = pal;
	((struct param *)glio_work)->cc  = cc;

	return (high(g_lio(0xa4)));
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
int	g_cls(void)
{
	return (high(g_lio(0xa5)));
}


/*=======================================================================
 |
 |		点の描画
 |
 |	int	g_pset(x, y, pal)
 |
 |		int	x,y;		点の座標
 |		int	pal;		パレット番号
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_pset(int x, int y, int pal)
{
	struct	param	{		/* パラメータリスト */
		u_int	x;
		u_int	y;
		u_char	pal;
	};

	((struct param *)glio_work)->x   = x;
	((struct param *)glio_work)->y   = y;
	((struct param *)glio_work)->pal = pal;

	glio_ah = 1;

	return (high(g_lio(0xa6)));
}

/*=======================================================================
 |
 |		点の消去
 |
 |	int	g_preset(x, y)
 |
 |		int	x,y;		点の座標
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_preset(int x, int y)
{
	struct	param	{		/* パラメータリスト */
		u_int	x;
		u_int	y;
		u_char	pal;
	};

	((struct param *)glio_work)->x   = x;
	((struct param *)glio_work)->y   = y;
	((struct param *)glio_work)->pal = 0xff;

	glio_ah = 2;

	return (high(g_lio(0xa6)));
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
 |		円または楕円の描画
 |
 |	int	g_circle(cx,cy,rx,ry,pal1,flag,sx,sy,ex,ey,lentile,ptile)
 |
 |		int	cx,cy;		中心点の座標
 |		int	rx;		Ｘ方向の半径
 |		int	ry;		Ｙ方向の半径
 |		int	pal1;		パレット番号１
 |		int	flag;		フラグ
 |		int	sx,sy;		開始点の座標
 |		int	ex,ey;		終了点の座標
 |		int	lentile;	パレット番号２またはタイルパターン長
 |		u_char	*ptile; 	タイルパターン
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_circle(int cx, int cy, int rx, int ry, int pal1, int flag, int sx
	, int sy, int ex, int ey, int lentile, u_char *ptile)
{
	struct	param	{		/* パラメータリスト */
		u_int	cx;
		u_int	cy;
		u_int	rx;
		u_int	ry;
		u_char	pal1;
		u_char	flag;
		u_int	sx;
		u_int	sy;
		u_int	ex;
		u_int	ey;
		u_char	lentile;
		u_int	ptile_off;
		u_int	ptile_seg;
	};
	void	far *fptr;

	((struct param *)glio_work)->cx = cx;
	((struct param *)glio_work)->cy = cy;
	((struct param *)glio_work)->rx = rx;
	((struct param *)glio_work)->ry = ry;

	((struct param *)glio_work)->pal1 = pal1;
	((struct param *)glio_work)->flag = flag;

	((struct param *)glio_work)->sx = sx;
	((struct param *)glio_work)->sy = sy;
	((struct param *)glio_work)->ex = ex;
	((struct param *)glio_work)->ey = ey;

	((struct param *)glio_work)->lentile = lentile;

	fptr = ptile;
	((struct param *)glio_work)->ptile_off = FP_OFF(fptr);
	((struct param *)glio_work)->ptile_seg = FP_SEG(fptr);

	return (high(g_lio(0xa8)));
}

/*=======================================================================
 |
 |		指定色による塗りつぶし
 |
 |	int	g_paint(x,y,pal1,pal2)
 |
 |		int	x,y;		開始点の座標
 |		int	pal1;		領域色（パレット番号）
 |		int	pal2;		境界色（パレット番号）
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_paint(int x, int y, int pal1, int pal2)
{
	struct	param	{		/* パラメータリスト */
		u_int	x;
		u_int	y;
		u_char	pal1;
		u_char	pal2;
		u_int	e_work;
		u_int	s_work;
	};

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	((struct param *)glio_work)->pal1 = pal1;
	((struct param *)glio_work)->pal2 = pal2;

	((struct param *)glio_work)->e_work = 0x12f8;
	((struct param *)glio_work)->s_work = 0x1200;

	return (high(g_lio(0xa9)));
}

/*=======================================================================
 |
 |		タイルパターンによる塗りつぶし
 |
 |	int	g_paint2(x, y, lentile, ptile, cb)
 |
 |		int	x,y;		開始点の座標
 |		int	lentile;	タイルパターン長
 |		u_char	*ptile; 	タイルパターン
 |		int	cb;		境界色（パレット番号）
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_paint2(int x, int y, int lentile, u_char *ptile, int cb)
{
	struct	param	{		/* パラメータリスト */
		u_int	x;
		u_int	y;
		u_char	dummy1;
		u_char	lentile;
		u_int	ptile_off;
		u_int	ptile_seg;
		u_char	cb;
		u_char	dummy2;
		u_int	dummy3;
		u_int	dummy4;
		u_int	e_work;
		u_int	s_work;
	};
	void	far *fptr;

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	((struct param *)glio_work)->lentile = lentile;

	fptr = ptile;
	((struct param *)glio_work)->ptile_off = FP_OFF(fptr);
	((struct param *)glio_work)->ptile_seg = FP_SEG(fptr);

	((struct param *)glio_work)->cb = cb;

	((struct param *)glio_work)->e_work = 0x12f8;
	((struct param *)glio_work)->s_work = 0x1200;

	return (high(g_lio(0xaa)));
}

/*=======================================================================
 |
 |		描画情報の格納
 |
 |	int	g_get(x1, y1, x2, y2, buff, len)
 |
 |		int	x1,y1;		指定領域の左上の座標
 |		int	x2,y2;		指定領域の右下の座標
 |		u_char	*buff;		グラフィックパターンを格納する領域
 |		int	len;		格納領域のサイズ
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_get(int x1, int y1, int x2, int y2, u_char *buff, int len)
{
	struct	param	{		/* パラメータリスト */
		u_int	x1;
		u_int	y1;
		u_int	x2;
		u_int	y2;
		u_int	buff_off;
		u_int	buff_seg;
		u_int	len;
	};
	void	far *fptr;

	((struct param *)glio_work)->x1 = x1;
	((struct param *)glio_work)->y1 = y1;
	((struct param *)glio_work)->x2 = x2;
	((struct param *)glio_work)->y2 = y2;

	fptr = buff;
	((struct param *)glio_work)->buff_off = FP_OFF(fptr);
	((struct param *)glio_work)->buff_seg = FP_SEG(fptr);
	((struct param *)glio_work)->len      = len;

	return (high(g_lio(0xab)));
}

/*=======================================================================
 |
 |		描画情報を格納域から領域へ戻す
 |
 |	int	g_put(x, y, buff, len, dm, csw, fc, bc)
 |
 |		int	x,y;		表示させる領域の左上座標
 |		u_char	*buf;		格納域のポインタ
 |		int	len;		グラフィックパターンのサイズ
 |		int	dm;		表示モード
 |		int	csw;		カラースイッチ
 |		int	fc;		白のパレット番号
 |		inc	bc;		黒のパレット番号
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_put(int x, int y, u_char *buff, int len, int dm, int csw, int fc
	, int bc)
{
	struct	param	{		/* パラメータリスト */
		u_int	x;
		u_int	y;
		u_int	buff_off;
		u_int	buff_seg;
		u_int	len;
		u_char	dm;
		u_char	csw;
		u_char	fc;
		u_char	bc;
	};
	void	far *fptr;

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	fptr = buff;
	((struct param *)glio_work)->buff_off = FP_OFF(fptr);
	((struct param *)glio_work)->buff_seg = FP_SEG(fptr);
	((struct param *)glio_work)->len      = len;

	((struct param *)glio_work)->dm  = dm;
	((struct param *)glio_work)->csw = csw;
	((struct param *)glio_work)->fc  = fc;
	((struct param *)glio_work)->bc  = bc;

	return (high(g_lio(0xac)));
}

/*=======================================================================
 |
 |		漢字フォントの表示
 |
 |	int	g_kanji(x, y, jap, dm, csw, fc, bc)
 |
 |		int	x,y;		表示させる領域の左上座標
 |		u_int	jap;		漢字コード
 |		int	dm;		表示モード
 |		int	csw;		カラースイッチ
 |		int	fc;		フォントのパレット番号
 |		int	bc;		フォント以外の部分のパレット番号
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_kanji(int x, int y, u_int jap, int dm, int csw, int fc, int bc)
{
	struct	param	{		/* パラメータリスト */
		u_int	x;
		u_int	y;
		u_int	jap;
		u_char	dm;
		u_char	csw;
		u_char	fc;
		u_char	bc;
	};

	if (0x8000 <= jap)
		jap = jmstojis(jap);

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	((struct param *)glio_work)->jap = jap;

	((struct param *)glio_work)->dm  = dm;
	((struct param *)glio_work)->csw = csw;
	((struct param *)glio_work)->fc  = fc;
	((struct param *)glio_work)->bc  = bc;

	return (high(g_lio(0xad)));
}

/*=======================================================================
 |
 |		グラフィック画面のスクロール
 |
 |	int	g_roll(updot, leftdot, cf)
 |
 |		int	updot;		上下方向ドット数
 |		int	leftdot;	左右方向ドット数
 |		int	cf;		クリアフラグ
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_roll(int updot, int leftdot, int cf)
{
	struct	param	{		/* パラメータリスト */
		u_int	updot;
		u_int	leftdot;
		u_char	cf;
	};

	((struct param *)glio_work)->updot   = updot;
	((struct param *)glio_work)->leftdot = leftdot;

	((struct param *)glio_work)->cf = cf;

	return (high(g_lio(0xae)));
}

/*=======================================================================
 |
 |		ドットのパレット番号を得る
 |
 |	int	g_point(x, y)
 |
 |		int	pal;		パレット番号
 |		int	x,y;		ドットの座標
 |
 |		返値			終了コード
 |
 =======================================================================*/
int	g_point(int x, int y)
{
	struct	param	{		/* パラメータリスト */
		u_int	x;
		u_int	y;
	};

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	return (low(g_lio(0xaf)));
}
