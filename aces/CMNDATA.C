/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: 各ＣＰＵ共通データ			*
 *		ファイル名	: cmndata.c				*
 *									*
 ************************************************************************/
#pragma	SECTION DATA=cmndata

#include "sg_cpu.h"

short	sg_mode;		/* Ｓ／Ｇモード */
short	mbi_rcvbuf[44][32];	/* １５５３Ｂ受信データバッファ */
short	ibit_mode;		/* Ｉ－ＢＩＴモード */
ushort	bezel_sw1;		/* ベゼルスイッチデータ１ */
ushort	bezel_sw2;		/* ベゼルスイッチデータ２ */
ushort	gsc_board_test;		/* GSC BOARD TEST 結果 */
ushort	sg_bit_status;		/* Ｓ／Ｇ　ＢＩＴステータス */
ushort	du_bit_status;		/* Ｄ／Ｕ　ＢＩＴステータス */
