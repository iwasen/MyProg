/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: ＲＡＭデータ				*
 *		ファイル名	: ramdata.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*	表示モード	*/
short	sg_mode;

/*	Ｈ００９データ	*/
struct	H009	orgdata;	/* 生データ */
struct	H009	tmpdata;	/* フィルタリング用テンポラリエリア */
struct	H009	fltdata;	/* フィルタリング後データ */

/*	外光照度データ	*/
struct	GAIKO	lumdata;

/*	Ｄ／Ｐテンポラリエリア	*/
struct	DP_AI	dp_ai;		/* ＡＩ表示 */
struct	DP_HUD	dp_hud;		/* ＨＵＤ表示 */
struct	DP_HDD	dp_hdd;		/* ＨＤＤ表示 */
struct	DP_DATA	dp_data;	/* 外光照度データ表示 */

/*	Ｐ／Ａビットテンポラリエリア	*/
struct	PA_BIT	pa_bit;

/*	１５５３Ｂコマンドワード	*/
word	command_word;

/*	１５５３Ｂ受信データ種別	*/
short	r1553B_data;		/* ０：未受信,１：飛行データ,２：外光データ */

/*	ＲＳ－２３２Ｃ受信データ	*/
word	rs_receive_data;

/*	ＲＳ－２３２Ｃ受信カウンタ	*/
short	rs_counter;

/*	ＧＳＣボード起動フラグ	*/
short	gsc_start_flag;

/*	ＭＢＩボード起動フラグ	*/
short	mbi_start_flag;

/*	ビデオコントロールレジスタ設定値	*/
word	video_control;

/*	タイマー動作フラグ（０：停止，１：動作中）	*/
short	tmf_mbibit;		/* ＭＢＩボードＢＩＴ終了待ちタイマー */
short	tmf_1553B;		/* １５５３Ｂデータブロック間タイマー */
short	tmf_rsblock;		/* ＲＳ－２３２Ｃブロック間タイマー */
short	tmf_rschar;		/* ＲＳ－２３２Ｃ文字間タイマー */

/*	タイマーカウンタ	*/
short	tmc_mbibit;		/* ＭＢＩボードＢＩＴ終了待ちタイマー */
short	tmc_1553B;		/* １５５３Ｂデータブロック間タイマー */
short	tmc_rsblock;		/* ＲＳ－２３２Ｃブロック間タイマー */
short	tmc_rschar;		/* ＲＳ－２３２Ｃ文字間タイマー */
short	tmc_debug;		/* デバッグ時ビデオターミナル出力タイマー */

/*	タイムアウトフラグ	*/
short	tmo_1553B;		/* １５５３Ｂデータブロック間タイマー */
short	tmo_rsblock;		/* ＲＳ－２３２Ｃブロック間タイマー */

/*	外光照度データ記録中／記録終了フラグ	*/
short	memorizing_flag;	/* ０：記録終了，１：記録中 */

/*	デバックモードフラグ	*/
short	debug_mode_flag;	/* ０：ビデオターミナル出力無し　１：有り */
