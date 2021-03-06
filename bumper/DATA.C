/************************************************************************
 *									*
 *		日産自動車　バンパー塗色支援システム			*
 *									*
 *		名称		: データ定義				*
 *		ファイル名	: data.c				*
 *									*
 ************************************************************************/
#include "bumper.h"

struct key_buf key_data = {	/* キーコード */
	{
		{0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0},
		{0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}
	},
	{
		{0}, {0}, {KEY_INS}, {KEY_DEL},
		{KEY_UP}, {KEY_LEFT}, {KEY_RIGHT}, {KEY_DOWN},
		{KEY_HOME}, {KEY_HELP}, {KEY_HOME}
	}
};

SCHEDUL	schedule_tbl;		/* スケジュールテーブル */
BUMPER	bumper_tbl;		/* バンパーテーブル */
int	dolly_tbl[500];		/* ドーリーＮｏテーブル */
char	colcnv_tbl[30][4];	/* 色コード変換テーブル */
char	dolcnv_tbl[50][6];	/* ドーリー種コード変換テーブル */
int	target_id;		/* ソースＩＤ（ＣＣＭ） */
int	source_id;		/* ターゲットＩＤ（ＣＣＭ） */
