/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: 共通ヘッダ				*
 *		ファイル名	: bumper.h				*
 *									*
 ************************************************************************/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <memory.h>
#include <dos.h>
#include <signal.h>
#include <conio.h>

/*	型定義		*/
typedef	unsigned int	u_int;
typedef	unsigned char	u_char;

/*	キーコード	*/
#define	KEY_SHIFT	0x100
#define	KEY_FUNC	0x600
#define	KEY_HELP	0x01
#define	KEY_BS		0x08
#define	KEY_TAB		0x09
#define	KEY_SFT_TAB	(KEY_SHIFT|KEY_TAB)
#define	KEY_HOME	0x0b
#define	KEY_CLR		0x0c
#define	KEY_CR		0x0d
#define	KEY_ESC		0x1b
#define	KEY_SFT_ESC	(KEY_SHIFT|KEY_ESC)
#define	KEY_RIGHT	0x1c
#define	KEY_LEFT	0x1d
#define	KEY_UP		0x1e
#define	KEY_DOWN	0x1f
#define	KEY_DEL		0x7f
#define	KEY_INS		0x11
#define	KEY_F1		(KEY_FUNC|'1')
#define	KEY_F2		(KEY_FUNC|'2')
#define	KEY_F3		(KEY_FUNC|'3')
#define	KEY_F4		(KEY_FUNC|'4')
#define	KEY_F5		(KEY_FUNC|'5')
#define	KEY_F6		(KEY_FUNC|'6')
#define	KEY_F7		(KEY_FUNC|'7')
#define	KEY_F8		(KEY_FUNC|'8')
#define	KEY_F9		(KEY_FUNC|'9')
#define	KEY_F10		(KEY_FUNC|'0')
#define	KEY_MOUSE	0x700

/*	カラーコード	*/
#define	C_BLACK		0
#define	C_BLUE		1
#define	C_RED		2
#define	C_MAGENTA	3
#define	C_GREEN		4
#define	C_CYAN		5
#define	C_YELLOW	6
#define	C_WHITE		7

/*	アトリビュート		*/
#define	A_BLINK		0x02	/* ブリンク */
#define	A_REVERSE	0x04	/* リバース */
#define	A_ULINE		0x08	/* アンダーライン */
#define	A_BLUE		0x20	/* 青 */
#define	A_RED		0x40	/* 赤 */
#define	A_MAGENTA	0x60	/* 紫 */
#define	A_GREEN		0x80	/* 緑 */
#define	A_CYAN		0xa0	/* 水色 */
#define	A_YELLOW	0xc0	/* 黄色 */
#define	A_WHITE		0xe0	/* 白 */

/*	セグメントアドレス	*/
#define	SEG_TVRAM	0xa000		/* テキストＶＲＡＭセグメント */
#define	SEG_ATTR	0xa200		/* アトリビュートセグメント */
#define	SEG_GVRAM	0xa800		/* グラフィックＶＲＡＭセグメント */

/*	グローバルレジスタ	*/
#define	GR_BPT		0001
#define	GR_TRT		2001
#define	GR_DLT		2201
#define	GR_CCT		2701
#define	GR_DCT		2821

/*	ドーリー順列テーブル	*/
struct	schedul	{
	int	dolly_no[100];		/* ドーリーＮｏ． */
	int	color_code[100];	/* カラーコード */
};
typedef	struct	schedul	SCHEDUL;

/*	バンパーテーブル	*/
struct	bumper	{
	int	dolly_code[500];	/* ドーリーコード */
	int	color_code[500];	/* カラーコード */
	int	tekisei_zaiko[500];	/* 適正在庫 */
};
typedef	struct	bumper	BUMPER;

/*	固定部画面表示データ	*/
struct	scrdat	{
	int	pos_x;			/* 表示桁位置 */
	int	pos_y;			/* 表示行位置 */
	char	*data;			/* 表示データ */
};
typedef	struct scrdat SCRDAT;

/*	罫線表示テーブル		*/
struct	line	{
	int	flag;			/* フラグ　=0:線　=1:枠  =-1:終了 */
	int	pos_x1;			/* 開始桁位置 */
	int	pos_y1;			/* 開始行位置 */
	int	pos_x2;			/* 終了桁位置 */
	int	pos_y2;			/* 終了行位置 */
};
typedef	struct	line	LINE;

/*	ファンクションキー設定データ	*/

struct	key_buf				/*  bdos format of func-key buffer  */ 
{
	char	fun[20][16];
	char	cur[11][6];
};

extern	struct key_buf key_data; /* キーコード */
extern	int	tm_ovf;
extern	SCHEDUL	schedule_tbl;		/* スケジュールテーブル */
extern	BUMPER	bumper_tbl;		/* バンパーテーブル */
extern	int	dolly_tbl[500];		/* ドーリーＮｏテーブル */
extern	char	colcnv_tbl[30][4];	/* 色コード変換テーブル */
extern	char	dolcnv_tbl[50][6];	/* ドーリー種コード変換テーブル */
extern	int	target_id;		/* ソースＩＤ（ＣＣＭ） */
extern	int	source_id;		/* ターゲットＩＤ（ＣＣＭ） */

/*	main.c	*/
extern	void	initialize(void);
extern	void	final(void);
/*	menu.c	*/
extern	void	menu(void);
/*	schedule.c	*/
extern	void	schedule(void);
/*	bumper.c	*/
extern	void	bumper(void);
/*	dolly.c	*/
extern	void	dolly(void);
/*	colcnv.c()	*/
extern	void	colcnv(void);
/*	dolcnv.c()	*/
extern	void	dolcnv(void);
/*	ccm.c	*/
int	wccm(int, int, void *, int, int);
int	rccm(int, int, void *, int, int);
/*	csub.c	*/
extern	void	dspstr(int, int, char *, int);
extern	void	dspnstr(int, int, char *, int, int);
extern	void	xprintf(int, int, int, char *, ...);
extern	void	dspscr(SCRDAT *, int);
extern	void	dspspc(int, int, int);
extern	void	clrtoeol(void);
extern	void	dspline(LINE *, int);
extern	int	t_color_code(int);
extern	void	dsp_field(int, int, int, int);
extern	int	strcount(char *);
extern	int	inkey(void);
extern	int	getchr(void);
extern	void	set_keybuf(char *);
extern	int	inputs(char *, int, int, int, int, int);
extern	void	attrib(int, int, int, int, int);
extern	void	dsp_func(char **);
extern	void	*talloc(int);
extern	void	*trealloc(void *, int);
extern	int	kakunin(char *);
extern	void	message(char *, int);
/*	bios.c	*/
extern	void	t_loc(int, int);
extern	void	t_color(int);
extern	void	t_cls(void);
extern	void	t_csron(void);
extern	void	t_csroff(void);
extern	void	beep(void);
extern	void	t_setfont(int, char *);
extern	void	t_fget(struct key_buf *);
extern	void	t_fput(struct key_buf *);
extern	int	g_init(void);
extern	int	g_screen(int, int, int, int);
extern	int	g_color(int, int, int, int);
extern	int	g_color2(int, int);
extern	int	g_cls(void);
extern	int	g_line(int, int, int, int, int, int, ...);
extern	void	timset(int);
extern	int	far _interrupt tm_int(void);
extern	void	rs1_open(int, int, int);
extern	int	rs1_rxc(void);
extern	int	rs1_get(void);
extern	void	rs1_put(int);
extern	void	rs1_close(void);
