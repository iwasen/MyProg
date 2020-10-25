// cl du.c graphics.lib
#include <stdio.h>
#include <graph.h>
#include <dos.h>
#include <signal.h>
#include "\gwl\gwlib.h"
#include "\rs232c\rs232c.h"

#define D_SIZE	300		/* ＤＵ一辺の長さ	*/
#define	C_X	420		/* ＤＵ中心　Ｘ座標	*/
#define	C_Y	200		/* 　　　　　Ｙ座標	*/

#define	B_SIZE	(D_SIZE/10/2)	/* ベゼルスイッチの一辺の長さの１／２	*/
#define	R_SIZE	(D_SIZE/18/2)	/* ロッカスイッチの縦辺の長さの１／２	*/
#define	D_B_KAN (D_SIZE/20)	/* ベゼルスイッチとＤＵの間隔		*/
#define	R_KAN 	(D_SIZE/50)	/* ロッカスイッチの間隔			*/
#define	B_KAN   (D_SIZE/7)	/* ベゼルスイッチの間隔			*/
				/* ＤＵ中心からベゼルスイッチ中心の距離 */
#define	B_POS	(D_SIZE/2 + D_B_KAN + B_SIZE/2)

static	void	m_int(union REGS *);

					/* ベゼル，ロッカスイッチ情報  */
struct	bezel_xy {
	int x1;				/* 左上の座標　(x1,y1) */
	int y1;	
	int x2;				/* 右下の座標　(x2,y2) */
	int y2;
	int byte;			/* Ｄ／Ｕ－－＞Ｓ／Ｇ　*/ 
	int bit;			/*       フォーマット  */ 
};
typedef	struct bezel_xy POS_XY;

static	POS_XY bezel[] = {
	{C_X-B_KAN*2-B_SIZE, C_Y-B_POS-B_SIZE,		/* T501 */
	 C_X-B_KAN*2+B_SIZE, C_Y-B_POS+B_SIZE,
	 1, 0x01},
	{C_X-B_KAN-B_SIZE, C_Y-B_POS-B_SIZE,		/* T502 */
	 C_X-B_KAN+B_SIZE, C_Y-B_POS+B_SIZE,
	 1, 0x02},
	{C_X-B_SIZE, C_Y-B_POS-B_SIZE,			/* T503 */
	 C_X+B_SIZE, C_Y-B_POS+B_SIZE,
	 1, 0x04},
	{C_X+B_KAN-B_SIZE, C_Y-B_POS-B_SIZE,		/* T504 */
	 C_X+B_KAN+B_SIZE, C_Y-B_POS+B_SIZE,
	 1, 0x08},
	{C_X+B_KAN*2-B_SIZE, C_Y-B_POS-B_SIZE,		/* T505 */
	 C_X+B_KAN*2+B_SIZE, C_Y-B_POS+B_SIZE,
	 1, 0x10},
	{C_X+B_POS-B_SIZE, C_Y-B_KAN*2-B_SIZE,		/* T506 */
	 C_X+B_POS+B_SIZE, C_Y-B_KAN*2+B_SIZE,
	 1, 0x20},
	{C_X+B_POS-B_SIZE, C_Y-B_KAN-B_SIZE,		/* T507 */
	 C_X+B_POS+B_SIZE, C_Y-B_KAN+B_SIZE,
	 1, 0x40},
	{C_X+B_POS-B_SIZE, C_Y-B_SIZE,			/* T508 */
	 C_X+B_POS+B_SIZE, C_Y+B_SIZE,
	 1, 0x80},
	{C_X+B_POS-B_SIZE, C_Y+B_KAN-B_SIZE,		/* T509 */
	 C_X+B_POS+B_SIZE, C_Y+B_KAN+B_SIZE,
	 0, 0x01},
	{C_X+B_POS-B_SIZE, C_Y+B_KAN*2-B_SIZE,		/* T510 */
	 C_X+B_POS+B_SIZE, C_Y+B_KAN*2+B_SIZE,
	 0, 0x02},
	{C_X+B_KAN*2-B_SIZE, C_Y+B_POS-B_SIZE,		/* T511 */
	 C_X+B_KAN*2+B_SIZE, C_Y+B_POS+B_SIZE,
	 0, 0x04},
	{C_X+B_KAN-B_SIZE, C_Y+B_POS-B_SIZE,		/* T512 */
	 C_X+B_KAN+B_SIZE, C_Y+B_POS+B_SIZE,
	 0, 0x08},
	{C_X-B_SIZE, C_Y+B_POS-B_SIZE,			/* T513 */
	 C_X+B_SIZE, C_Y+B_POS+B_SIZE,
	 0, 0x10},
	{C_X-B_KAN-B_SIZE, C_Y+B_POS-B_SIZE,		/* T514 */
	 C_X-B_KAN+B_SIZE, C_Y+B_POS+B_SIZE,
	 0, 0x20},
	{C_X-B_KAN*2-B_SIZE, C_Y+B_POS-B_SIZE,		/* T515 */
	 C_X-B_KAN*2+B_SIZE, C_Y+B_POS+B_SIZE,
	 0, 0x40},
	{C_X-B_POS-B_SIZE, C_Y+B_KAN*2-B_SIZE,		/* T516 */
	 C_X-B_POS+B_SIZE, C_Y+B_KAN*2+B_SIZE,
	 0, 0x80},
	{C_X-B_POS-B_SIZE, C_Y+B_KAN-B_SIZE,		/* T517 */
	 C_X-B_POS+B_SIZE, C_Y+B_KAN+B_SIZE,
	 3, 0x01},
	{C_X-B_POS-B_SIZE, C_Y-B_SIZE,			/* T518 */
	 C_X-B_POS+B_SIZE, C_Y+B_SIZE,
	 3, 0x02},
	{C_X-B_POS-B_SIZE, C_Y-B_KAN-B_SIZE,		/* T519 */
	 C_X-B_POS+B_SIZE, C_Y-B_KAN+B_SIZE,
	 3, 0x04},
	{C_X-B_POS-B_SIZE, C_Y-B_KAN*2-B_SIZE,		/* T520 */
	 C_X-B_POS+B_SIZE, C_Y-B_KAN*2+B_SIZE,
	 3, 0x08},
	{C_X-B_POS-B_SIZE, C_Y-B_KAN*3-R_SIZE*3-R_KAN,	/* T521 */
	 C_X-B_POS+B_SIZE, C_Y-B_KAN*3-R_SIZE*1-R_KAN,
	 2, 0x80},
	{C_X-B_POS-B_SIZE, C_Y-B_KAN*3-R_SIZE,		/* T522 */
	 C_X-B_POS+B_SIZE, C_Y-B_KAN*3+R_SIZE,
	 2, 0x40},
	{C_X-B_POS-B_SIZE, C_Y+B_KAN*3-R_SIZE,		/* T523 */
	 C_X-B_POS+B_SIZE, C_Y+B_KAN*3+R_SIZE,
	 2, 0x20},
	{C_X-B_POS-B_SIZE, C_Y+B_KAN*3+R_SIZE*1+R_KAN,	/* T524 */
	 C_X-B_POS+B_SIZE, C_Y+B_KAN*3+R_SIZE*3+R_KAN,
	 2, 0x10},
	{C_X+B_POS-B_SIZE, C_Y-B_KAN*3-R_SIZE*3-R_KAN,	/* T525 */
	 C_X+B_POS+B_SIZE, C_Y-B_KAN*3-R_SIZE*1-R_KAN,
	 2, 0x08},
	{C_X+B_POS-B_SIZE, C_Y-B_KAN*3-R_SIZE,		/* T526 */
	 C_X+B_POS+B_SIZE, C_Y-B_KAN*3+R_SIZE,
	 2, 0x04},
	{C_X+B_POS-B_SIZE, C_Y+B_KAN*3-R_SIZE,		/* T527 */
	 C_X+B_POS+B_SIZE, C_Y+B_KAN*3+R_SIZE,
	 2, 0x02},
	{C_X+B_POS-B_SIZE, C_Y+B_KAN*3+R_SIZE*1+R_KAN,	/* T528 */
	 C_X+B_POS+B_SIZE, C_Y+B_KAN*3+R_SIZE*3+R_KAN,
	 2, 0x01},
	{-1}
};

static	void	du(void);
static	void	dsp_lcd(void);
static	void	m_int(union REGS *);
static	void	m_csron(void);
static	void	mousechk(void);
static	int	kbchk(void);
static	void	p_input(void);
static	void	input_bit(int);
static	void	input_debag(void);
static	void	rs1chk(void);
static	void	timechk(void);
static	int	char_to_int(char *, int);

static	char	a[256];
static	char	sbuf[10];
static	char	rbuf[6];
static	int	rcnt;
static	int	p_bit1, p_bit2;
static	int	r_bit1, r_bit2;
static	int	i_bit1, i_bit2;
static	int	c_bit1, c_bit2;
static	int	tflag1;
static	int	tflag2;
static	int	first;

/*=======================================================================
 |
 |		疑似ＤＵメイン
 |
 |	static	void	main(void)
 |
 =======================================================================*/
main(void)
{

	RSPRM	rsprm;

	_setvideomode(_98RESS8COLOR);
	_setwritemode(_GPSET);

	if (m_init() == 0) {
		printf("マウスドライバを組み込んで下さい");
		getch();
	} else {
		rsprm.sync_mode = ASYNC;
		rsprm.duplex = DUPLEX_FULL;
		rsprm.blt = BLT_9600;
		rsprm.parity = PARITY_ODD;
		rsprm.chr_len = CHR_8;	
		rsprm.stp_len = STP_1;
		rsprm.flow_ctrl = FLOW_NONE;
		rsprm.dtr = DTR_ON;
		rsprm.buf_size = 1024;
		rsprm.bufp = NULL;

		rs1_open(&rsprm);
		tm_open();
		signal(SIGINT, SIG_IGN);

		du();
		rs1_close();
		tm_close();
	}
	_setvideomode(_98TEXT80);
}

/*=======================================================================
 |
 |		疑似ＤＵ入力チェック
 |
 |	static	void	du(void)
 |
 =======================================================================*/
static	void	du(void)
{
	dsp_lcd();
	printf("\n>");
	m_csron();

	for (;;) {
		mousechk();
		rs1chk();
		timechk();
		if (kbchk())
			break;
	}
}


/*=======================================================================
 |
 |		マウスチェック
 |
 |	static	void	mousechk(void)
 |
 =======================================================================*/
static	void	mousechk(void)
{
	POS_XY	*beze;	
	int	px, py;
	int	plsw, prsw;
	static	int	sw;
	static	POS_XY	*ptr;

	m_pos(&px, &py, &plsw, &prsw);
	if (plsw != 0 && sw == 0) {		/* 左ボタン：ＯＮ */
		for (beze = bezel; beze->x1 != -1; beze++) {
			if ((px >= beze->x1 && px <= beze->x2) && 
			    (py >= beze->y1 && py <= beze->y2)) {
				m_csroff();
				_rectangle(_GFILLINTERIOR,   /* 塗りつぶし */
					beze->x1, beze->y1,
					beze->x2, beze->y2);
				m_csron();
				sbuf[beze->byte] |= beze->bit;
				ptr = beze;
				sw = 1;
				break;
			}
		}
	} else if (plsw == 0 && sw == 1) {
		beze = ptr;		
		sbuf[beze->byte] &= ~beze->bit;
		m_csroff();
		_setcolor(0);			/* 塗りつぶし（黒） */
		_rectangle(_GFILLINTERIOR,
			beze->x1, beze->y1,
			beze->x2, beze->y2);
		_setcolor(7);			/* 枠表示（白） */
		_rectangle(_GBORDER,
			beze->x1, beze->y1,
			beze->x2, beze->y2);
		m_csron();
		sw = 0;
	}
}

/*=======================================================================
 |
 |		キーボードチェック
 |
 |	static	void	kbchk(void)
 |
 =======================================================================*/
static	int	kbchk(void)
{
	static	int	i;
	int	c, st;

	st = 0;
	if (kbhit()) {
		c = getch();
		if (c == KEY_ESC) {
			i = 0;
			st = 1;
		} else if (c == KEY_CR) {
			a[i] = '\0';	
			p_input();
			rs1_clear();
			i = 0;
		} else {
			putch(c);
			a[i++] = c;
		}
	}
	return(st);
}

/*=======================================================================
 |
 |		キー入力チェック
 |
 |	static	void	p_input(void)
 |
 =======================================================================*/
static	void	p_input(void)
{
	if ((strcmp(a, "p") == 0) || (strcmp(a, "P") == 0)) {
		printf("\nPOWER ON BIT = ");
		input_bit(1);
	} else if ((strcmp(a, "r") == 0) || (strcmp(a, "R") == 0)) {
		printf("\nR BIT = ");
		input_bit(2);
	} else if ((strcmp(a, "i") == 0) || (strcmp(a, "I") == 0)) {
		printf("\nI BIT = ");
		input_bit(3);
	} else if ((strcmp(a, "c") == 0) || (strcmp(a, "C") == 0)) {
		printf("\nC BIT = ");
		input_bit(4);
	} else if ((strcmp(a, "d") == 0) || (strcmp(a, "D") == 0)) {
		printf("\nDEBAG : ");
		input_debag();
	}
	printf("\n>");
}

/*=======================================================================
 |
 |		入力ＢＩＴ　セット
 |
 |	static	void	input_bit(int)
 |
 =======================================================================*/
static	void	input_bit(int bit_st)
{
	int	bit1, bit2;

	bit1 = bit2 = 0;

	scanf("%2x%2x", &bit1, &bit2);
	rewind(stdin);

	switch	(bit_st) {
		case 1:
			p_bit1 = bit1;
			p_bit2 = bit2;
			break;
		case 2:
			r_bit1 = bit1;
			r_bit2 = bit2;
			break;
		case 3:
			i_bit1 = bit1;
			i_bit2 = bit2;
			break;
		case 4:
			c_bit1 = bit1;
			c_bit2 = bit2;
			break;
	}

	printf("\n>");
	printf("p1:%2X, p2:%2X\n", p_bit1, p_bit2);
	printf(" r1:%2X, r2:%2X\n", r_bit1, r_bit2);
	printf(" i1:%2X, i2:%2X\n", i_bit1, i_bit2);
	printf(" c1:%2X, c2:%2X\n", c_bit1, c_bit2);
}

/*=======================================================================
 |
 |		デバッグモード切り替え
 |
 |	static	void	input_debag(void)
 |
 =======================================================================*/
static	void	input_debag(void)
{
	int	bit1;
	char	c;

	scanf("%1d", &bit1);
	rewind(stdin);
	
	c = sbuf[3];
	switch	(bit1) {
		case 1:
			if (c & 0x10)
				sbuf[3] &= 0xef;
			else
				sbuf[3] |= 0x10;
			break;
		case 2:
			if (c & 0x20)
				sbuf[3] &= 0xdf;
			else
				sbuf[3] |= 0x20;
			break;
	}

	c = sbuf[3];
	c &= 0x10;
	c >>= 4;
	printf("\n>");
	printf("ﾓｰﾄﾞ1:%2X\n", c);

	c = sbuf[3];
	c &= 0x20;
	c >>= 5;
	printf(" ﾓｰﾄﾞ2:%2X\n", c);
}

/*=======================================================================
 |
 |		ＲＳ２３２Ｃチェック
 |
 |	static	void	rs1chk(void)
 |
 =======================================================================*/
static	void	rs1chk(void)
{
	char	c;
	int	wk1, wk2;

	if (rs1_rxc()) {
		rbuf[rcnt++] = rs1_get();		/* 受信 */
		if (rcnt == 6) {
			tflag2 = 0;
			tm_stop(1);
			rcnt = 0;
			if (first == 0) {		/* POWER ON BIT */
				sbuf[4] = p_bit1;
				sbuf[5] = p_bit2;
				rs1_sendreq(sbuf, 10);
				printf("\n>送信終了（Ｐ　ＢＩＴ）\n>");
				first = 1;
				return;
			}
			c = rbuf[4];
			if (c & 0x80) {			/* Echo BIT */
				rs1_sendreq(rbuf, 6);
				printf("\n>送信終了（Ｅ　ＢＩＴ）\n>");
			}
			c = rbuf[5];
			if (c & 0x02) {			/* R BIT */
				sbuf[4] = r_bit1;
				sbuf[5] = r_bit2;
				printf("\n>送信待ち（Ｒ　ＢＩＴ）\n>");
				tm_set(0, 300);		/* タイマーセット */
				tflag1 = 1;
			}
			c = rbuf[5];
			if (c & 0x01) {			/* I BIT */
				sbuf[4] = i_bit1;
				sbuf[5] = i_bit2;
				printf("\n>送信待ち（Ｉ　ＢＩＴ）\n>");
				tm_set(0, 300);		/* タイマーセット */
				tflag1 = 1;
			} else {
				sbuf[4] = c_bit1;
				sbuf[5] = c_bit2;
				rs1_sendreq(sbuf, 10);	/*  */
//				printf("\n>送信終了（Ｃ　ＢＩＴ）\n>");
			}
		} else {
			tm_set(1, 2);			/* タイマーセット */
			tflag2 = 1;
		}
	}
}

/*=======================================================================
 |
 |		タイマーチェック
 |
 |	static	void	timechk(void)
 |
 =======================================================================*/
static	void	timechk(void)
{
	if (tflag1 == 1) {
		if (tm_check(0)) {		/* タイムアウト */
			rs1_sendreq(sbuf, 10);
			printf("\n>送信終了\n>");
			tflag1 = 0;
		}
	}
	if (tflag2 == 1) {
		if (tm_check(1)) {		/* タイムアウト */
			rcnt = 0;		
			rs1_clear();
//			printf("\n>ＲＢＵＦ　リセット\n>");
			tflag2 = 0;
		}
	}
}

/*=======================================================================
 |
 |		ＤＵ画面イメージ表示
 |
 |	static	void	dsp_lcd(void)
 |
 =======================================================================*/
static	void	dsp_lcd(void)
{
	POS_XY	*beze;	

	_rectangle(_GBORDER,			/* DU */
		C_X-D_SIZE/2, C_Y+D_SIZE/2,
	 	C_X+D_SIZE/2, C_Y-D_SIZE/2);

	beze = bezel;
	while (beze->x1 != -1) {
		_rectangle(_GBORDER,		/* bezel switch */
			beze->x1, beze->y1,
			beze->x2, beze->y2);
		beze++;
	}
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

//	if (m_csr_flag == 0) {
		reg.x.ax = 1;
		m_int(&reg);

//		m_csr_flag = 1;
//	}
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

//	if (m_csr_flag != 0) {
		reg.x.ax = 2;
		m_int(&reg);

//		m_csr_flag = 0;
//	}
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
//	if (mouse_drv) {
		*plsw = (*prsw & 0x01) ? -1 : 0;
		*prsw = (*prsw & 0x02) ? -1 : 0;
//	}
}
