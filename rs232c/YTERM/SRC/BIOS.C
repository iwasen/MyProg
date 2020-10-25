/************************************************************************
 *									*
 *		通信プログラム　Ｙ－ＴＥＲＭ				*
 *									*
 *		名称		: ＢＩＯＳ呼び出し処理			*
 *		ファイル名	: bios.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <dos.h>

typedef	unsigned char	u_char;
typedef	unsigned int	u_int;

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
 |		テキストカーソル表示
 |
 |	void	t_csron()
 |
 =======================================================================*/
void	t_csron(void)
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
void	t_csroff(void)
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
