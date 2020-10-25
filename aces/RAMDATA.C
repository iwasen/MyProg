/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: ＲＡＭデータ				*
 *		ファイル名	: ramdata.c				*
 *									*
 ************************************************************************/
#pragma	SECTION DATA=ramdata

#include "sg_cpu.h"

short	cpu;				/* ＣＰＵ番号 */
char	*adr_gsc;			/* ＧＳＣ先頭アドレス */
short	display_mode;			/* 表示モード */
short	old_display_mode;		/* 前回表示モード */
short	rom_error_flag;			/* ＲＯＭチェックサムエラーフラグ */
short	debug_mode;			/* デバッグモード */

/*	ＲＳ２３２Ｃ　ＣＨ－Ａ関連	*/
char	rsa_sndbuf[RSA_SBUFSIZE];	/* 送信バッファ */
short	rsa_sndwptr;			/* 送信バッファ書き込みポインタ */
short	rsa_sndrptr;			/* 送信バッファ読み込みポインタ */
short	rsa_rcvcounter;			/* 受信カウンタ */
short	du_rcvflag;			/* データ受信フラグ */
uchar	du_rcv_data[10];			/* 受信バッファ */

/*	ＲＳ２３２Ｃ　ＣＨ－Ｂ関連	*/
char	rsb_sndbuf[RSB_SBUFSIZE];	/* 送信バッファ */
short	rsb_sndwptr;			/* 送信バッファ書き込みポインタ */
short	rsb_sndrptr;			/* 送信バッファ読み込みポインタ */
char	rsb_rcvbuf[RSB_RBUFSIZE];	/* 受信バッファ */
short	rsb_rcvcounter;			/* 受信カウンタ */

/*	１５５３Ｂ関連	*/
short	sub_adrs_count;			/* 受信待ちサブアドレス */
INPUT_DATA	input_data;		/* 入力データバッファ */
FILTER_DATA	filter_data;		/* フィルタ処理済みデータ */
FILTER_DATA	tmp_data;
short	calc_flag;			/* 計算開始フラグ */
short	count_nodata;			/* 未受信カウンタ */
short	bus_fail;			/* ５秒以上未受信 */
short	counter_mbibit;			/* ＭＢＩ ＢＩＴリトライカウンタ */

/*	タイマーカウンタ	*/
short	tm_mbibit;		/* ＭＢＩボードＢＩＴ終了待ちタイマー */
short	tm_dustart;		/* Ｄ／Ｕ立ち上がり検出タイマー */
short	tm_update;		/* データ更新周期監視タイマー */
short	tm_dudata;		/* Ｄ／Ｕへデータ送信,データ受信監視タイマー */
short	tm_duchar;		/* Ｄ／Ｕの文字間タイムアウト検出タイマー */
short	tm_digital;		/* デジタル表示更新タイマー */
short	tm_ibit;		/* Ｉ－ＢＩＴ実行タイマー */
short	tm_gscbackup;		/* ＧＳＣ割り込み停止時のバックアップ処理 */
short	tm_simulation;		/* 入力データシミュレーションタイマー */

/*	ＢＩＴ関連	*/
short	rbit_mode;		/* Ｒ－ＢＩＴモード */
long	ibit_switch;		/* スイッチテスト用フラグ */
short	ibit_request;		/* Ｉ－ＢＩＴ開始要求フラグ（デバッグ用） */
short	rbit_request;		/* Ｒ－ＢＩＴ開始要求フラグ */
short	mbi_board_test;		/* ＭＢＩボードチェック結果 */

DP	dp;			/* Ｄ／Ｐ */
DP	dp_tmp;			/* Ｄ／Ｐ保存用テンポラリエリア */
ushort	pa[N_PABIT];		/* Ｐ／Ａ */
ushort	pa_tmp[N_PABIT];	/* Ｐ／Ａ保存用テンポラリエリア */
short	count_1553B;		/* １５５３Ｂ受信カウンタ（周期監視用） */
short	count_gscint;		/* ＧＳＣ描画終了割り込みカウンタ */
short	rate_1553B;		/* １５５３Ｂ受信周期 */
short	rate_gscint;		/* ＧＳＣ描画終了割り込み周期 */
short	rate_dsp_flag;		/* 更新レート表示フラグ */
short	digital_update_flag;	/* デジタル表示更新フラグ */
short	digital_update_rate;	/* デジタル表示更新周期 */
long	bezel_sw_old;		/* ベゼルスイッチ前回データ */
short	str_count;		/* 文字表示用カウンタ */
short	echo_mode;		/* エコーモード */
short	echo_status;		/* エコー結果 */
uchar	du_send_data[6];	/* Ｄ／Ｕへの送信データ */

ushort	check_sum;		/* チェックサム保存エリア */
uchar	rs_IMR;			/* ＤＵＡＲＴ割り込みマスクデータ */

short	test_data_no;		/* テストデータ番号 */
short	test_data_change;	/* テストデータ変更フラグ */
SIM_WORK sim_work[44*32];	/* 入力データシミュレーション用ワークエリア */

short	tm_test;
short	test_flag;
short	test_count;
short	grid_flag;
