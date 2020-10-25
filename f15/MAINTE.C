/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: メンテナンスモード処理		*
 *		ファイル名	: maintenance.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*=======================================================================
 |
 |		外光照度データ表示用Ｄ／Ｐセット
 |
 |	void	dp_gaikou()
 |
 =======================================================================*/
void	dp_gaikou()
{
	ushort	no, data, renge, n;
	short	i, j;

	/* １５５３Ｂ受信データチェック */
	if (r1553B_data != 2) {
		/* ベゼルＳＷはメンテナンスモードだが１５５３Ｂからまだ
		   外光照度データを受信していないので、全てのＤ／Ｐに
		   スペースをセット */
		for (i = 0; i < 15; i++) {
			for (j = 0; j < 18; j++)
				dp_data.data[i][j] = FONT_T4;
		}
		return;
	}

	/* 表示Ｎｏ取り出し */
	no = lumdata.display_no;

	for (i = 0; i < 15; i++) {
		/* 表示Ｎｏセット */
		for (j = 0, n = no; j < 4; j++) {
			dp_data.data[i][3-j] = (j != 0 && n == 0) ?
					FONT_T4 : numfont4_tbl[n % 10];
			n /= 10;
		}

		/* ブランクセット */
		dp_data.data[i][4] = FONT_T4;
		dp_data.data[i][5] = FONT_T4;

		/* ＬＥＦＴデータセット */
		data = lumdata.data[i][0] & 0x3fff;
		renge = lumdata.data[i][0] & 0xc000;
		if (renge == 0x4000)		/* レンジ１？ */
			n = (ushort)(data * 100L / 4092L);
		else if (renge == 0x8000)	/* レンジ２？ */
			n = (ushort)(data * 1000L / 4092L);
		else if (renge == 0xc000)	/* レンジ３？ */
			n = (ushort)(data * 10000L / 4092L);
		else
			n = 0;
		for (j = 0; j < 5; j++) {
			dp_data.data[i][10-j] = (j != 0 && n == 0) ?
					FONT_T4 : numfont4_tbl[n % 10];
			n /= 10;
		}

		/* ブランクセット */
		dp_data.data[i][11] = FONT_T4;
		dp_data.data[i][12] = FONT_T4;

		/* ＲＩＧＨＴデータセット */
		data = lumdata.data[i][1] & 0x3fff;
		renge = lumdata.data[i][1] & 0xc000;
		if (renge == 0x4000)		/* レンジ１？ */
			n = (ushort)(data * 100L / 4092L);
		else if (renge == 0x8000)	/* レンジ２？ */
			n = (ushort)(data * 1000L / 4092L);
		else if (renge == 0xc000)	/* レンジ３？ */
			n = (ushort)(data * 10000L / 4092L);
		else
			n = 0;
		for (j = 0; j < 5; j++) {
			dp_data.data[i][17-j] = (j != 0 && n == 0) ?
					FONT_T4 : numfont4_tbl[n % 10];
			n /= 10;
		}

		no++;		/* 表示Ｎｏ更新 */
	}
}
