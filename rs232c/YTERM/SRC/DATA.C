/************************************************************************
 *									*
 *		通信プログラム　Ｙ－ＴＥＲＭ				*
 *									*
 *		名称		: 外部変数				*
 *		ファイル名	: data.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include "rs232c.h"
#include "yterm.h"

MODEM	modem;			/* 環境設定情報 */
CENTER	cprm;			/* カレントセンター情報 */
CENTER	center[10];		/* センター情報 */
int	csr1_x;			/* カーソル桁位置 */
int	csr1_y;			/* カーソル行位置 */
int	csr2_x = 0;		/* カーソル桁位置 */
int	csr2_y = 1;		/* カーソル行位置 */
char	*func0[] = {		/* ファンクションキー全消去 */
	NULL, NULL, NULL, NULL, NULL,
	NULL, NULL, NULL, NULL, NULL
};
long	c_time;			/* 通信開始時刻 */
int	conxxxt;		/* 接続フラグ */
RSPRM	default_prm = {		/* デフォルト通信パラメータ */
	ASYNC,
	DUPLEX_FULL,
	BLT_300,
	PARITY_NONE,
	CHR_8,
	STP_1,
	FLOW_NONE,
	DTR_OFF,
	0,
	8196,
	NULL
};
FILE	*rcv_fp;		/* 受信ファイルポインタ */
FILE	*snd_fp;		/* 送信ファイルポインタ */
int	send_wait;		/* ファイル送信待ちフラグ */
int	vbufc;			/* ＶＲＡＭバッファカウンタ */
int	vbufn;			/* ＶＲＡＭバッファ格納行数 */
static	WINDOW	scr = {		/* 全画面用ウィンドウデータ */
	0, 0, 80, 25, C_WHITE, NULL, NULL
};
WINDOW	*scrp = &scr;		/* 全画面用ウィンドウポインタ */
int	opt_m;			/* ／Ｍオプションフラグ */
WINDOW	*wp_deverr;		/* デバイスエラー表示ウィンドウ */
