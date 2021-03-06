/************************************************************************
 *									*
 *		グラフィックウィンドウライブラリ			*
 *									*
 *		名称		: マウス処理				*
 *		ファイル名	: mlib.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<dos.h>
#include	"gwlib.h"

static	void	m_int(union REGS *);

/*=======================================================================
 |
 |		マウスドライバー呼び出し
 |
 |	void	m_int(reg)
 |
 |		union REGS *reg;	レジスタ
 |
 =======================================================================*/
static
void	m_int(union REGS *reg)
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
int	m_init(void)
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
void	m_csron(void)
{
	union	REGS	reg;

	if (m_csr_flag == 0) {
		reg.x.ax = 1;
		m_int(&reg);

		m_csr_flag = 1;
	}
}

/*=======================================================================
 |
 |		マウスカーソル表示ＯＦＦ
 |
 |	void	m_csroff()
 |
 =======================================================================*/
void	m_csroff(void)
{
	union	REGS	reg;

	if (m_csr_flag != 0) {
		reg.x.ax = 2;
		m_int(&reg);

		m_csr_flag = 0;
	}
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
 |		左ボタンのオン情報を得る
 |
 |	void	m_lon(psw, pn, px, py)
 |
 |		int	*psw;		左ボタンの状態
 |		int	*pn;        	左ボタンがオンになった回数
 |		int	*px,*py;	最後に左ボタンがオンになった時の座標
 |
 =======================================================================*/
void	m_lon(int *psw, int *pn, int *px, int *py)
{
	union	REGS	reg;

	reg.x.ax = 5;
	m_int(&reg);

	*psw = reg.x.ax;
	*pn = reg.x.bx;
	*px = reg.x.cx;
	*py = reg.x.dx;
}

/*=======================================================================
 |
 |		左ボタンのオフ情報を得る
 |
 |	void	m_loff(psw, pn, px, py)
 |
 |		int	*psw;		左ボタンの状態
 |		int	*pn;        	左ボタンがオフになった回数
 |		int	*px,*py;	最後に左ボタンがオフになった時の座標
 |
 =======================================================================*/
void	m_loff(int *psw, int *pn, int *px, int *py)
{
	union	REGS	reg;

	reg.x.ax = 6;
	m_int(&reg);

	*psw = reg.x.ax;
	*pn = reg.x.bx;
	*px = reg.x.cx;
	*py = reg.x.dx;
}

/*=======================================================================
 |
 |		右ボタンのオン情報を得る
 |
 |	void	m_ron(psw, pn, px, py)
 |
 |		int	*psw;		左ボタンの状態
 |		int	*pn;        	左ボタンがオンになった回数
 |		int	*px,*py;	最後に左ボタンがオンになった時の座標
 |
 =======================================================================*/
void	m_ron(int *psw, int *pn, int *px, int *py)
{
	union	REGS	reg;

	reg.x.ax = 7;
	m_int(&reg);

	*psw = reg.x.ax;
	*pn = reg.x.bx;
	*px = reg.x.cx;
	*py = reg.x.dx;
}

/*=======================================================================
 |
 |		右ボタンのオフ情報を得る
 |
 |	void	m_roff(psw, pn, px, py)
 |
 |		int	*psw;		左ボタンの状態
 |		int	*pn;        	左ボタンがオフになった回数
 |		int	*px,*py;	最後に左ボタンがオフになった時の座標
 |
 =======================================================================*/
void	m_roff(int *psw, int *pn, int *px, int *py)
{
	union	REGS	reg;

	reg.x.ax = 8;
	m_int(&reg);

	*psw = reg.x.ax;
	*pn = reg.x.bx;
	*px = reg.x.cx;
	*py = reg.x.dx;
}

/*=======================================================================
 |
 |		マウスカーソルの形状の設定
 |
 |	void	m_csr(x, y, pat)
 |
 |		int	x,y;		中心点の位置
 |		char	*pat;		マウスカーソルの形状データ
 |
 =======================================================================*/
void	m_csr(int x, int y, char *pat)
{
	union	REGS	reg;
	struct	SREGS	segs;
	char	far	*fptr;

	fptr = pat;

	reg.x.ax = 9;
	reg.x.bx = x;
	reg.x.cx = y;
	reg.x.dx = FP_OFF(fptr);
	segs.es = FP_SEG(fptr);

	int86x(0x33, &reg, &reg, &segs);
}

/*=======================================================================
 |
 |		マウスの移動距離を得る
 |
 |	void	m_mov(px, py)
 |
 |		int	*px;		水平方向の移動距離（ミッキー）
 |		int	*py;        	垂直方向の移動距離（ミッキー）
 |
 =======================================================================*/
void	m_mov(int *px, int *py)
{
	union	REGS	reg;

	reg.x.ax = 11;
	m_int(&reg);

	*px = reg.x.cx;
	*py = reg.x.dx;
}

/*=======================================================================
 |
 |		ミッキー／ドット比の設定
 |
 |	void	m_step(x, y)
 |
 |		int	x;		水平方向のミッキー／ドット比
 |		int	y;          	垂直方向のミッキー／ドット比
 |
 =======================================================================*/
void	m_step(int x, int y)
{
	union	REGS	reg;

	reg.x.cx = x;
	reg.x.dx = y;

	reg.x.ax = 15;
	m_int(&reg);
}

/*=======================================================================
 |
 |		マウスカーソルの移動領域の設定
 |
 |	void	m_area(x1,y1,x2,y2)
 |
 |		int	x1,y1;		移動領域の左上の座標
 |		int	y1,y2;      	移動領域の右下の座標
 |
 =======================================================================*/
void	m_area(int x1, int y1, int x2, int y2)
{
	union	REGS	reg;

	reg.x.ax = mouse_drv ? 7 : 16;
	reg.x.cx = x1;
	reg.x.dx = x2;
	m_int(&reg);

	reg.x.ax = mouse_drv ? 8 : 17;
	reg.x.cx = y1;
	reg.x.dx = y2;
	m_int(&reg);
}

/*=======================================================================
 |
 |		マウスカーソルの表示画面の設定
 |
 |	void	m_color(n)
 |
 |		int	n;			マウスカーソルの表示画面
 |
 =======================================================================*/
void	m_color(int n)
{
	union	REGS	reg;

	reg.x.ax = mouse_drv ? 255 : 18;
	reg.x.bx = n;
	m_int(&reg);
}
