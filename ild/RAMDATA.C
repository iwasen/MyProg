/************************************************************************
 *									*
 *		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
 *									*
 *		名称		: ＲＡＭデータ				*
 *		ファイル名	: ramdata.c				*
 *									*
 ************************************************************************/
#pragma	SECTION DATA=ramdata

#include "sg_cpu.h"

short	debug_flag;

/*	ＲＳ２３２Ｃ　ＣＨ－Ａ関連	*/
char	rsa_sndbuf[RSA_SBUFSIZE];	/* 送信バッファ */
short	rsa_sndwptr;			/* 送信バッファ書き込みポインタ */
short	rsa_sndrptr;			/* 送信バッファ読み込みポインタ */
short	rsa_rcvcounter;			/* 受信カウンタ */
byte	rsa_rcvchar;			/* 受信データ */

/*	ＲＳ２３２Ｃ　ＣＨ－Ｂ関連	*/
char	rsb_sndbuf[RSB_SBUFSIZE];	/* 送信バッファ */
short	rsb_sndwptr;			/* 送信バッファ書き込みポインタ */
short	rsb_sndrptr;			/* 送信バッファ読み込みポインタ */
char	rsb_rcvbuf[RSB_RBUFSIZE];	/* 受信バッファ */
short	rsb_rcvcounter;			/* 受信カウンタ */

/*	１５５３Ｂ関連	*/
short	sub_adrs_count;			/* 受信待ちサブアドレス */
short	mbi_rcvbuf[512];		/* １５５３Ｂ受信データバッファ */
short	input_data[512];		/* 入力データバッファ */
short	ibuf_flag;			/* 受信完了フラグ */

/*	タイマー動作フラグ（０：停止，１：動作中）	*/
short	tmf_mbibit;		/* ＭＢＩボードＢＩＴ終了待ちタイマー */
short	tmf_1553B;		/* 入力データ更新周期監視タイマー */
short	tmf_dspmode;		/* Ｄ／Ｕへデータ送信タイマー */
short	tmf_dubit;		/* Ｄ／ＵからのＢＩＴ結果受信待ちタイマー */
short	tmf_poweron;		/* パワーオンテストシーケンスタイマー */
short	tmf_rschar;		/* ＲＳ－２３２Ｃ文字間タイマー */

/*	タイマーカウンタ	*/
short	tmc_mbibit;		/* ＭＢＩボードＢＩＴ終了待ちタイマー */
short	tmc_1553B;		/* 入力データ更新周期監視タイマー */
short	tmc_dspmode;		/* Ｄ／Ｕへデータ送信タイマー */
short	tmc_dubit;		/* Ｄ／ＵからのＢＩＴ結果受信待ちタイマー */
short	tmc_poweron;		/* パワーオンテストシーケンスタイマー */
short	tmc_rschar;		/* ＲＳ－２３２Ｃ文字間タイマー */
short	tmc_blink;		/* ブリンクタイマー */
short	tmc_digital;		/* デジタル数値更新タイマー */
short	tmc_simulation;

short	sg_mode;
short	old_sg_mode;

short	dot_video;		/* ビデオの横方向のドット数 */
short	data_error_flag;	/* 入力データエラーフラグ */
DP	dp;			/* Ｄ／Ｐ */
DP	dp_tmp;			/* Ｄ／Ｐ保存用テンポラリエリア */
ushort	pa[N_PABIT];		/* Ｐ／Ａ */
ushort	pa_tmp[N_PABIT];	/* Ｐ／Ａ保存用テンポラリエリア */
short	message_data[4];	/* 前回のメッセージ保存エリア */
short	message_no[4];		/* メッセージ表示テーブル */
short	blink_flag;		/* ブリンクフラグ */
short	count_1553B;		/* １５５３Ｂ受信カウンタ（周期監視用） */
short	count_gscint;		/* ＧＳＣ描画終了割り込みカウンタ */
short	rate_1553B;		/* １５５３Ｂ受信周期 */
short	rate_gscint;		/* ＧＳＣ描画終了割り込み周期 */
short	rate_dsp_flag;		/* 更新レート表示フラグ */
short	digital_update_flag;	/* デジタル数値表示更新フラグ */
short	digital_update_rate;
short	rcv_1553B_flag;

short	seq_power_on;		/* パワーオンテストシーケンス */
ushort	check_sum;		/* チェックサム保存エリア */
byte	video_mode;		/* ビデオ表示モード */
short	bit_status;		/* ＢＩＴチェックステータス */
short	bit_data;		/* 前回ＢＩＴ開始データ保存エリア */
uchar	rs_IMR;			/* ＤＵＡＲＴ割り込みマスクデータ */

short	test_data_no;		/* テストデータ番号 */
short	test_data_change;	/* テストデータ変更 */
SIM_WORK	sim_work[512];	/* 入力データシミュレーション用ワークエリア */

short	count_calc;		/* for debug */
short	rate_calc;

