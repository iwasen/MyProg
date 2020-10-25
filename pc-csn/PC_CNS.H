/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: 共通ヘッダファイル			*
 *		ファイル名	: pc_cns.h				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <memory.h>
#include <dos.h>
#include <signal.h>
#include <conio.h>

/*	特殊メッセージ番号	*/
#define	MN_KAIWA	'8'	/* 会話応答メッセージ (8xxx) */
#define	MN_ABORT	"9990"	/* アボート受信メッセージ */
#define	MN_NORMAL	"9990"	/* コマンド正常終了 */
#define	MN_ERROR	"9991"	/* コマンド異常終了 */

/*	型定義		*/
typedef	unsigned int	u_int;
typedef	unsigned char	u_char;

/*	パス名サイズ	*/
#define	SZ_PATH		64

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

/*	ファンクションキー設定コード	*/
#define	FUNC1		0x001
#define	FUNC2		0x002
#define	FUNC3		0x004
#define	FUNC4		0x008
#define	FUNC5		0x010
#define	FUNC6		0x020
#define	FUNC7		0x040
#define	FUNC8		0x080
#define	FUNC9		0x100
#define	FUNC10		0x200

/*	カラーコード	*/
#define	C_BLACK		0
#define	C_BLUE		1
#define	C_RED		2
#define	C_MAGENTA	3
#define	C_GREEN		4
#define	C_CYAN		5
#define	C_YELLOW	6
#define	C_WHITE		7

#define	C_WAKU		0x100	/* 枠 */
#define	C_OFFLINE	0x101	/* オフライン表示 */
#define	C_ONLINE	0x102	/* オンライン表示 */
#define	C_GUIDE		0x103	/* 説明 */
#define	C_CNSMSG	0x104	/* コンソールメッセージ */
#define	C_CRM		0x105	/* コマンド応答 */
#define	C_ANMA		0x106	/* 自立メッセージＡ */
#define	C_ANMB		0x107	/* 自立メッセージＢ */
#define	C_ERR		0x108	/* エラー表示 */

/*	メッセージ種別	*/
#define	MSG_ANM		1	/* 自立メッセージ */
#define	MSG_CRM		2	/* コマンド応答メッセージ */
#define	MSG_CNS		3	/* コンソールメッセージ */
#define	MSG_CNSR	4	/* コンソールメッセージ （赤表示）*/

/*	自立メッセージ緊急度	*/
#define	MSG_CLASS_DFLT	0	/* 省略 */
#define	MSG_CLASS_A1	1	/* 最緊急 */
#define	MSG_CLASS_A2	2	/* 緊急 */
#define	MSG_CLASS_B	3	/* 通常 */

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

/*	コマンド選択ファイルキーワード	*/
#define	KWD_TTL	1
#define	KWD_COM	2
#define	KWD_PRM	3
#define	KWD_SEL	4
#define	KWD_NUM	5
#define	KWD_NAM	6
#define	KWD_MSG	7

/*	ローカルメッセージ	*/
#define	LOC_MSG_START	1
#define	LOC_MSG_CONxxxT	10
#define	LOC_MSG_DISCON	11
#define	LOC_MSG_NOSSG	12
#define	LOC_MSG_NORESP	13
#define	LOC_MSG_CAB	14
#define	LOC_MSG_ERLINE	15
#define	LOC_MSG_ERRM	16
#define	LOC_MSG_RESTART	17
#define	LOC_MSG_1DISC	18
#define	LOC_MSG_NORMAL	100
#define	LOC_MSG_ERPRM	101
#define	LOC_MSG_EREXT	102
#define	LOC_MSG_ERLEN	103
#define	LOC_MSG_ERPATH	104
#define	LOC_MSG_ERFILE	105
#define	LOC_MSG_ERCON	106
#define	LOC_MSG_ERNOFILE	107
#define	LOC_MSG_ERCMF	110
#define	LOC_MSG_MKCMF	200
#define	LOC_MSG_CLOSE	201
#define	LOC_MSG_END	202

/*	信号種別	*/
#define	SIG_CN		0x01
#define	SIG_CNA		0x02
#define	SIG_SR		0x03
#define	SIG_STSR	0x04
#define	SIG_SRA		0x05
#define	SIG_RA		0x06
#define	SIG_RAA		0x07
#define	SIG_CQ		0x08
#define	SIG_CQA		0x09
#define	SIG_AB		0x0a
#define	SIG_SD		0x0b
#define	SIG_RD		0x0c
#define	SIG_AK		0x0d
#define	SIG_AKA		0x0e
#define	TIMEOUT		-1

/*	コンソールＳＧファイル	*/
struct	csg	{
	struct	c_nsg	{	/* システム名 */
		char	sys_name[9];	/* 全体のシステム名 */
		char	grf_file[SZ_PATH];	/* 絵画面ファイル名 */
		char	online_id;	/* コマンドＩＤ（オンライン）*/
		char	offline_id;	/* コマンドＩＤ（オフライン）*/
	} nsg;

	struct	c_fsg	{	/* ファイル名 */
		char	amsg_file[13];	/* 自立メッセージファイル名 */
		char	cms_file[5][13];	/* コマンド選択ファイル名 */
		char	cmsg_file[13];	/* コマンド応答メッセージファイル名 */
		char	loc_file[13];	/* ローカルコマンド選択ファイル名 */
		char	lmsg_file[13];	/* ﾛｰｶﾙｺﾏﾝﾄﾞ応答メッセージファイル名 */
	} fsg;

	struct	c_col	{	/* 画面の色指定 */
		int	c_waku;		/* 枠 */
		int	c_offline;	/* オフライン表示 */
		int	c_online;	/* オンライン表示 */
		int	c_guide;	/* 説明 */
		int	c_cnsmsg;	/* コンソールメッセージ */
		int	c_crm;		/* コマンド応答 */
		int	c_msga;		/* 自立メッセージＡ */
		int	c_msgb;		/* 自立メッセージＢ */
		int	c_err;		/* エラー表示 */
	} col;

	struct	{	/* ﾛｰｶﾙコマンド応答メッセージの出力機器環境の設定 */
		int	dsp;		/* 画面表示有り／無し */
		int	prn;		/* プリンタ出力有り／無し */
		int	fil;		/* ファイル出力有り／無し */
		char	fname[SZ_PATH];	/* ファイル出力有り時のファイル名 */
	} lrm;

	struct	c_opt	{	/* オプション */
		int	rs232c;		/* ＲＳ２３２Ｃ回線速度 */
		int	printer;	/* プリンター機種 */
		int	cmd_to;		/* コマンド入力タイムアウト時間 */
		int	cmd_err;	/* コマンドファイル実行エラー処置 */
		int	comment;	/* コメントの自動挿入 */
	} opt;
};
typedef	struct	csg	CSG;

/*	システムＳＧファイル	*/
struct	ssg	{
	struct	{	/* 個別システム名 */
		char	sys_name[7];	/* 個別システム名 */
		char	grf_file[SZ_PATH];	/* 絵画面ファイル名 */
		int	conxxxtion;	/* コネクション番号 */
		int	kanshi;		/* 無活動監視周期 */
	} nsg;

	struct	{	/* システム定数 */
		int	syc_flg;	/* システム定数指定フラグ */
		int	syc_val;	/* システム定数 */
	} syc[10];

	struct	{	/* 自立メッセージ出力機器環境の設定 */
		int	dsp;		/* 画面表示有り／無し */
		int	prn;		/* プリンタ出力有り／無し */
		int	fil;		/* ファイル出力有り／無し */
		char	fname[SZ_PATH];	/* ファイル出力有り時のファイル名 */
	} anm;

	struct	{	/* コマンド応答メッセージの出力機器環境の設定 */
		int	dsp;		/* 画面表示有り／無し */
		int	prn;		/* プリンタ出力有り／無し */
		int	fil;		/* ファイル出力有り／無し */
		char	fname[SZ_PATH];	/* ファイル出力有り時のファイル名 */
	} crm;

	struct	{	/* コマンドファイル読みだしパス名 */
		char	cmd_path[SZ_PATH]; /* コマンドファイル読みだしパス */
	} cmf;

	struct	{	/* 定時起動コマンド */
		char	command[80];	/* コマンド */
	} aec[5];
};
typedef	struct	ssg	SSG;

/*	選択項目テーブル	*/
struct	sel_tbl	{
	int	n;		/* 選択項目数 */
	char	*ptr[1][2];	/* 選択項目 */
};
typedef	struct	sel_tbl	SEL_TBL;

/*	数値項目テーブル	*/
struct	num_tbl	{
	int	min_flg;	/* 最小値指定フラグ */
	u_int	min;		/* 最小値 */
	int	min_syc;	/* システム定数 */
	int	max_flg;	/* 最大値指定フラグ */
	u_int	max;		/* 最大値 */
	int	max_syc;	/* システム定数 */
	char	*prmid;		/* パラメータ識別子 */
	char	*sel;		/* 選択項目 */
};
typedef	struct	num_tbl	NUM_TBL;

/*	名称項目テーブル	*/
struct	nam_tbl	{
	int	length;		/* 最大文字数 */
	char	*sel;		/* 選択項目 */
	char	*ext1;		/* 拡張子 */
	char	*ext2;		/* 拡張子 */
};
typedef	struct	nam_tbl	NAM_TBL;

/*	説明項目テーブル	*/
struct	gud_tbl	{
	int	length;		/* 最大文字数 */
};
typedef	struct	gud_tbl	GUD_TBL;

/*	パラメータテーブル	*/
struct	prt_tbl {
	int	type;		/* パラメータ種類 */
	char	*guide;		/* パラメータ説明 */
	union	{
		SEL_TBL	*sel;
		NUM_TBL	*num;
		NAM_TBL	*nam;
		GUD_TBL	*gud;
	} ptr;
};	
typedef	struct	prt_tbl	PRT_TBL;

/*	パラメータ種類テーブル	*/
struct	prm_tbl	{
	char	*name;		/* パラメータ名 */
	char	*guide;		/* パラメータ説明 */
	int	n;		/* パラメータ数 */
	PRT_TBL	*ptr;		/* パラメータテーブルポインタ */
};
typedef	struct	prm_tbl	PRM_TBL;

/*	コマンドテーブル	*/
struct	cmd_tbl	{
	char	*name;		/* コマンド名 */
	char	*guide;		/* コマンド説明 */
	int	n;		/* パラメータの種類の個数 */
	PRM_TBL	*ptr;		/* パラメータ種類テーブルポインタ */
};
typedef	struct	cmd_tbl	CMD_TBL;

/*	コマンド選択テーブル	*/
struct	cms_tbl	{
	char	*title;		/* タイトル文 */
	char	*guide;		/* 説明文 */
	int	n;		/* コマンド個数 */
	CMD_TBL	*ptr;		/* コマンドテーブルポインタ */
};
typedef	struct	cms_tbl	CMS_TBL;

/*	メッセージテーブル	*/
struct	msg_tbl	{
	int	n_msg;		/* メッセージ個数 */
	FILE	*fp;		/* メッセージファイルポインタ */
	struct	msg_no	{
		int	msg_no;		/* メッセージ番号 */
		long	offset;		/* ファイルオフセット */
	} *msg;
};
typedef	struct	msg_tbl	MSG_TBL;

/*	メッセージ可変部情報	*/
struct	kahenbu	{
	int	kahenbu_no;	/* 可変部Ｎｏ． */
	int	type;		/* ０：数値型　１：文字型 */
};
typedef	struct	kahenbu	KAHENBU;

/*	キュー		*/
struct	queue	{
	void	*data;		/* データポインタ */
	struct	queue	*chain;	/* チェインポインタ */
};
typedef	struct	queue	QUEUE;

/*	メッセージキュー	*/
struct	msg_que	{
	int	type;		/* メッセージタイプ */
	char	*msg;		/* メッセージデータ */
};
typedef	struct	msg_que MSG_QUE;

/*	コマンドファイル情報	*/
struct	cmf_tbl	{
	char	name[9];	/* コマンドファイル名 */
	char	guide[80];	/* 機能概要 */
};
typedef	struct	cmf_tbl	CMF_TBL;

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

/*	マウス情報	*/
struct	mouse	{
	int	x;			/* Ｘ座標 */
	int	y;			/* Ｙ座標 */
	int	len;			/* 長さ */
	int	s_code;			/* 識別コード */
	int	l_code;
	int	r_code;
};
typedef	struct	mouse	MOUSE;

/*	ファンクションキー設定データ	*/

struct	key_buf				/*  bdos format of func-key buffer  */ 
{
	char	fun[20][16];
	char	cur[11][6];
};

/*	外部関数定義	*/
extern	void	prog1(void);
extern	void	prog2(void);
extern	void	prog3(void);
extern	void	prog4(void);
extern	void	prog5(void);
extern	void	prog6(void);
extern	void	prog6c(void);
extern	void	prog7(void);
extern	void	prog8(void);
extern	void	prog9(void);

/*	main.c	*/
extern	void	initialize(void);
extern	void	final(void);

/*	console.c	*/
extern	void	dspstr(int, int, char *, int);
extern	int	dspstr2(int, int, int, char *, int);
extern	void	dspnstr(int, int, char *, int, int);
extern	void	xprintf(int, int, int, char *, ...);
extern	void	dspscr(SCRDAT *, int);
extern	void	dspspc(int, int, int);
extern	void	clrtoeol(void);
extern	void	dspline(LINE *, int);
extern	int	t_color_code(int);
extern	int	g_color_code(int);
extern	void	dsp_field(int, int, int, int);
extern	int	inkey(void);
extern	int	getchr(void);
extern	void	set_keybuf(char *);
extern	int	inputs(char *, int, int, int, int, int);
extern	int	inputm(char *, int, int, int, int, int);
extern	void	m_reg(MOUSE *, int);
extern	void	attrib(int, int, int, int, int);
extern	void	dspgrf(char *);
extern	void	setfunc(int);
extern	void	dsp_msgenv(void);
extern	void	grfputs(int, int, char *, int, char *);
extern	void	hcopy(int, int, int, int);

/*	sub.c	*/
extern	void	exec_prog(int);
extern	void	beep(void);
extern	void	alxxx_mj(void);
extern	void	alxxx_mn(void);
extern	int	strcount(char *);
extern	void	*talloc(int);
extern	void	en_queue(QUEUE **, void *,int);
extern	void	*de_queue(QUEUE **);

/*	cmf.c	*/
extern	void	find_cmf(void);
extern	void	read_cmf(void);
extern	void	write_cmf(void);
extern	void	enq_cmf(char *);
extern	char	*deq_cmf(void);
extern	char	*make_cmfpath(char *);
extern	int	start_cmf(char *);
extern	void	exec_cmf(void);

/*	cms.c	*/
extern	void	read_cmdfile(void);
extern	CMS_TBL	*search_cms(char *);

/*	msg.c	*/
extern	void	restore_scr(void);
extern	void	save_scr(void);
extern	void	out_msg(void);
extern	void	dsp_msg(char *, int);
extern	int	 next_line(int);
extern	void	enq_msg(int, char *);
extern	MSG_QUE	*deq_msg(void);
extern	void	free_msg(MSG_QUE *);
extern	void	read_msgfile(void);
extern	char 	*cnv_msg(char *, MSG_TBL *, int *);
extern	void	dsp_loc_msg(int, ...);

/*	bios.c	*/
extern	void	t_loc(int,int);
extern	void	t_color(int);
extern	void	t_cls(void);
extern	void	t_csron(void);
extern	void	t_csroff(void);
extern	void	t_getfont(int,char *);
extern	void	t_setfont(int,char *);
extern	void	t_fget(struct key_buf *);
extern	void	t_fput(struct key_buf *);
extern	int	g_init(void);
extern	int	g_screen(int,int,int,int);
extern	int	g_color(int,int,int,int);
extern	int	g_cls(void);
extern	int	g_line(int, int, int, int, int, int, int, ...);
extern	int	m_init(void);
extern	void	m_csron(void);
extern	void	m_csroff(void);
extern	void	m_pos(int *,int *,int *,int *);
extern	void	m_loc(int,int);
extern	void	timset(int);
extern	int	far _interrupt tm_int(void);
extern	void	timreset(void);
extern	void	rs1_open(int,int,int);
extern	int	rs1_rxc(void);
extern	int	rs1_get(void);
extern	void	rs1_put(int);
extern	void	rs1_close(void);
extern	void	lputc(int);

/*	sg.c	*/
extern	int	find_csg(void);
extern	void	read_csg(void);
extern	void	write_csg(void);
extern	int	find_ssg(int);
extern	void	read_ssg(void);
extern	void	write_ssg(void);

/*	com.c	*/
extern	void	rs_open(void);
extern	int	conxxxt(int);
extern	void	disconxxxt(void);
extern	void	send_sig(int);
extern	void	send_sd(char *);
extern	int	receive(void);

/*	local.c	*/
extern	int	exec_local(char *);
extern	void	edit_loc_cmd(void);

/*	data.c	*/
extern	char	csg_file[13];
extern	CSG	csg_default;
extern	CSG	csg;
extern	char	ssg_file[13];
extern	SSG	ssg;
extern	char	csg_grf[21216];
extern	char	ssg_grf[21216];
extern	char	cfg_grf[21216];
extern	CMS_TBL	*cmstbl[5];
extern	CMS_TBL	*loctbl;
extern	CMS_TBL	*cms_current;
extern	CMD_TBL	*cmd_current;
extern	PRM_TBL	*prm_current;
extern	MSG_TBL	*anm_msg;
extern	MSG_TBL	*crm_msg;
extern	MSG_TBL	*loc_msg;
extern	int	online;
extern	int	local;
extern	int	mkcmd;
extern	int	prog;
extern	int	old_prog;
extern	char	cmdbuf[160];
extern	int	csr_y;
extern	QUEUE	*msg_que;
extern	char	t_save_buf[80*2*18];
extern	char	a_save_buf[80*2*18];
extern	int	mouse;
extern	int	loc_seq;
extern	char	cmf_name[13];
extern	char	cmf_guide[80];
extern	CMF_TBL	*cmf_tbl;
extern	int	n_cmf;
extern	QUEUE	*cmf_que;
extern	int	opt_t;
extern	char	password[11];
extern	int	stop_flag;
extern	int	disconxxxt_request;
extern	char	rcv_buf[1024+1];
extern	int	tm_ovf;
extern	int	exec_cmf_flag;
extern	FILE	*fp_cmf;
extern	char	ab_msg[];
extern	struct key_buf key_data;

/*	guide.c	*/
extern	char	msg10[];
extern	char	msg20[];
extern	char	msg30[];
extern	char	msg40[];
extern	char	msg100[];
extern	char	msg110[];
extern	char	msg120[];
extern	char	msg130[];
extern	char	msg140[];
extern	char	msg150[];
extern	char	msg160[];
extern	char	msg200[];
extern	char	msg210[];
extern	char	msg220[];
extern	char	msg230[];
extern	char	msg300[];
extern	char	msg301[];
extern	char	msg310[];
extern	char	msg320[];
extern	char	msg400[];
extern	char	msg401[];
extern	char	msg410[];
extern	char	msg500[];
extern	char	msg501[];
extern	char	msg510[];
extern	char	msg520[];
extern	char	msg530[];
extern	char	msg540[];
extern	char	msg600[];
extern	char	msg601[];
extern	char	msg610[];
extern	char	msg620[];
extern	char	msg650[];
extern	char	msg660[];
extern	char	msg670[];
extern	char	msg680[];
extern	char	msg700[];
extern	char	msg701[];
extern	char	msg710[];
extern	char	msg720[];
extern	char	msg800[];
extern	char	msg810[];
extern	char	msg820[];
extern	char	msg900[];
extern	char	msg910[];
extern	char	msg920[];
extern	char	err10[];
