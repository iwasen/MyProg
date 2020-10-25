/************************************************************************
 *									*
 *		通信プログラム　Ｙ－ＴＥＲＭ				*
 *									*
 *		名称		: ヘッダファイル			*
 *		ファイル名	: term.h				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

/*	キーコード	*/
#define	KEY_FUNC	0x600
#define	KEY_HELP	0x01
#define	KEY_BELL	0x07
#define	KEY_BS		0x08
#define	KEY_TAB		0x09
#define	KEY_LF		0x0a
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

/*	セグメントアドレス	*/
#define	SEG_TVRAM	0xa000		/* テキストＶＲＡＭセグメント */
#define	SEG_ATTR	0xa200		/* アトリビュートセグメント */
#define	SEG_GVRAM	0xa800		/* グラフィックＶＲＡＭセグメント */

/*	アトリビュート		*/
#define	A_BLINK		0x02	/* ブリンク */
#define	A_REVERSE	0x04	/* リバース */
#define	A_ULINE		0x08	/* アンダーライン */

/*	文字色	*/
#define	C_BLACK		0x01	/* 黒 */
#define	C_BLUE		0x21	/* 青 */
#define	C_RED		0x41	/* 赤 */
#define	C_MAGENTA	0x61	/* 紫 */
#define	C_GREEN		0x81	/* 緑 */
#define	C_CYAN		0xa1	/* 水色 */
#define	C_YELLOW	0xc1	/* 黄色 */
#define	C_WHITE		0xe1	/* 白 */

/*	反転文字色	*/
#define	RC_BLACK	0x05	/* 黒 */
#define	RC_BLUE		0x25	/* 青 */
#define	RC_RED		0x45	/* 赤 */
#define	RC_MAGENTA	0x65	/* 紫 */
#define	RC_GREEN	0x85	/* 緑 */
#define	RC_CYAN		0xa5	/* 水色 */
#define	RC_YELLOW	0xc5	/* 黄色 */
#define	RC_WHITE	0xe5	/* 白 */

/* ＶＲＡＭバッファサイズ	*/
#define	MAX_VBUF	1228

/*	センター情報	*/
struct	center	{
	char	name[25];		/* センター名 */
	char	tel[15];		/* 電話番号 */
	int	speed;			/* 通信速度 */
	int	parity;			/* パリティー */
	int	chrlen;			/* キャラクタ長 */
	int	stplen;			/* ストップビット長 */
	int	xon;			/* ＸＯＮ／ＸＯＦＦ */
	int	echo;			/* ローカルエコーバック */
	int	retkey;			/* リターンキー */
	int	prompt;			/* ファイル送信プロンプト */
	char	f3dsp[7];		/* ｆ・３表示データ */
	char	f3cmd[16];		/* ｆ・３実行コマンド */
	char	mdcmd[27];		/* モデム接続時補助コマンド */
	char	redial[3];		/* リダイヤル間隔 */
	char	login[49];		/* オートログインコマンド */
	char	reserve[256-158];	/* リザーブ */
};
typedef	struct	center	CENTER;

/*	環境情報	*/
struct	modem	{
	int	dial;			/* ０：ダイヤル　１：トーン */
	int	modem;			/* ０：ＡＴコマンド　２：Ｖ２５bis */
	char	mdcmd[27];		/* 接続時補助コマンド */
	char	f2dsp[7];		/* ｆ・２表示データ */
	char	f2cmd[16];		/* ｆ・２実行コマンド */
	char	reserve[128-54];	/* リザーブ */
};
typedef	struct	modem	MODEM;
	
/*	ウインドゥ構造体	*/
struct	window	{
	int	sx;			/* 開始桁位置 */
	int	sy;			/* 開始行位置 */
	int	nx;			/* 桁数 */
	int	ny;			/* 行数 */
	int	color;			/* ウィンドウ色 */
	int	*tram;			/* テキストＶＲＡＭセーブエリア */
	int	*attr;			/* アトリビュートセーブエリア */
};
typedef	struct	window	WINDOW;

/*	固定部画面表示データ	*/
struct	scrdat	{
	int	x;			/* 表示桁位置 */
	int	y;			/* 表示行位置 */
	char	*data;			/* 表示データ */
};
typedef	struct scrdat SCRDAT;

/*	ファンクションキー設定データ	*/
struct	key_buf				/*  bdos format of func-key buffer  */ 
{
	char	fun[20][16];
	char	cur[11][6];
};

/*	ファイル名	*/
#define	FN_CNT		"YTERM.CNT"	/* センター情報ファイル */
#define	FN_ENV		"YTERM.ENV"	/* 環境設定ファイル */

/*	main.c	*/
extern	void	dsp_ctrl(int);
extern	void	rs1_putc(int);

/*	term.c	*/
extern	void	setuzoku(void);
extern	void	touroku(void);
extern	void	settei(void);
extern	void	setudan(void);
extern	void	parameter(void);
extern	void	tm_wait(int);

/*	file.c	*/
extern	void	file(void);
extern	void	rcv_text_end(void);
extern	void	snd_text_end(void);

/*	xmodem.c	*/
extern	int	x_send(FILE *, WINDOW *);
extern	int	x_receive(FILE *, WINDOW *);

/*	console.c	*/
extern	WINDOW	*w_open(int, int, int, int, char *, int);
extern	WINDOW	*w_alloc(int, int);
extern	void	w_display(WINDOW *, int, int, char *, int);
extern	void	w_close(WINDOW *);
extern	void	w_erase(WINDOW *);
extern	void	w_free(WINDOW *);
extern	void	w_dspstr(WINDOW *, int, int, int, char *);
extern	void	w_dspnstr(WINDOW *, int, int, int, char *, int);
extern	void	w_dspspc(WINDOW *, int, int, int);
extern	void	w_printf();
extern	int	inkey(void);
extern	int	getchr(void);
extern	int	w_inputs(WINDOW *, char *, int, int, int, int, int);
extern	void	beep(void);
extern	int	w_newline(WINDOW *, int);
extern	void	dsp_func(char **);
extern	void	w_loc(WINDOW *, int, int);
extern	int	w_select(WINDOW *, char **, int, int, int);
extern	void	w_color(WINDOW *, int, int, int, int);
extern	void	w_dspscr(WINDOW *, SCRDAT *);
extern	void	cputs1(char *, int);
extern	void	putch1(int, int);
extern	void	putch2(int);
extern	void	scroll_up(void);
extern	void	scroll_down(void);

/*	bios.c	*/
extern	void	t_loc(int, int);
extern	void	t_csron(void);
extern	void	t_csroff(void);
extern	void	t_getfont(int, char *);
extern	void	t_setfont(int, char *);
extern	void	t_fget(struct key_buf *);
extern	void	t_fput(struct key_buf *);

/*	data.c	*/
extern	MODEM	modem;
extern	CENTER	cprm;
extern	CENTER	center[10];
extern	int	csr1_x;
extern	int	csr1_y;
extern	int	csr2_x;
extern	int	csr2_y;
extern	char	*func0[];
extern	long	c_time;
extern	int	conxxxt;
extern	RSPRM	default_prm;
extern	FILE	*rcv_fp;
extern	FILE	*snd_fp;
extern	int	send_wait;
extern	int	vbufc;
extern	int	vbufn;
extern	WINDOW	*scrp;
extern	int	opt_m;
extern	WINDOW	*wp_deverr;
