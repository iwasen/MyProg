/************************************************************************
 *									*
 *		Ｓ／Ｇ　ＣＰＵプログラム				*
 *									*
 *		名称		: デバッグ時ビデオターミナル伝送出力	*
 *		ファイル名	: debug.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*	内部関数	*/
static	void	bintohex(ushort, char *, short);

/*=======================================================================
 |
 |		デバッグ時ビデオターミナル伝送出力処理
 |
 |	void	debug()
 |
 =======================================================================*/
void	debug()
{
	char	buf[19];
	ushort	bin;
	short	i;

	/* ヘッダセット */
	buf[0] = '*';

	/* Ｓ／Ｇモードセット */
	bin = sg_mode;
	if (gsc_start_flag)	/* ＧＳＣボード起動済み？ */
		bin |= BIT7;
	if (mbi_start_flag)	/* ＭＢＩボード起動済み？ */
		bin |= BIT6;
	bintohex(bin, &buf[1], 2);

	/* Ｓ／Ｇエラーステータスセット */
	bin = 0;
	bintohex(bin, &buf[3], 2);

	/* ＭＢＩ　ＢＩＴ結果セット */
	bin = *REG_MBI_BRCS;
	bintohex(bin, &buf[5], 4);

	/* ＤＵＡＲＴ　ＣＨ－Ｂステータスセット */
	buf[9] = (char)(tmo_rsblock ? 'N' : 'Y');

	/* ベゼルＳＷデータセット */
	bintohex(rs_receive_data, &buf[10], 2);

	/* ＭＢＩステータスセット */
	buf[12] = (char)(tmo_1553B ? 'N' : 'Y');

	/* １５５３Ｂコマンドワードセット */
	bintohex(command_word, &buf[13], 4);

	/* ターミネータセット	*/
	buf[17] = 0x0d;		/* ＣＲ */
	buf[18] = 0x0a;		/* ＬＦ */

	/* データ送信 */
	for (i = 0; i < 19; i++) {
		if (*REG_DUART_SRA & BIT2)	/* TXRDY ? */
			*REG_DUART_THRA = buf[i];	/* １文字送信 */
	}
}

/*=======================================================================
 |
 |		バイナリ－＞１６進文字変換処理
 |
 |	void	bintohex(bin, bufp, length)
 |
 |		ushort	bin;		バイナリデータ
 |		char	*bufp;		１６進文字格納バッファ
 |		short	length;		桁数
 |
 =======================================================================*/
static	void	bintohex(ushort bin, char *bufp, short length)
{
	static	char	hex[] = "0123456789ABCDEF";

	bufp += length - 1;
	while (length--) {
		*bufp-- = hex[bin & 0x0f];
		bin >>= 4;
	}
}
