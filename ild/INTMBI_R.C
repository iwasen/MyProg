/************************************************************************
 *									*
 *		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
 *									*
 *		名称		: １５５３Ｂデータ受信割り込み処理	*
 *		ファイル名	: intmbi_rx.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_rx

#include "sg_cpu.h"
extern	void	shortout(short);
/*=======================================================================
 |
 |		１５５３Ｂデータ受信割り込み処理
 |
 |	void	intmbi_rx()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intmbi_rx(void)
{
	short	rqspp, rqtpp;
	short	word_count, sub_address, read_counter, i;
	word	command_word, rqtp, *rqmt, *rbufp, *wbufp;

	*REG_MBI_ISW2 = 0x0000;		/* INT ACK */

	read_counter = 0;		/* 読み込みカウンタクリア */

	/* キューポインタ取り出し */
	rqspp = (*REG_MBI_RQSP & 0x00ff) << 1;
	rqtp = *REG_MBI_RQTP;
	rqtpp = (rqtp & 0x00ff) << 1;

	for (;;) {
		/* キューポインタチェック */
		if (rqspp == rqtpp)
			break;

		/* 読み込みカウンタチェック */
		if (++read_counter > 8)
			break;

		/* 受信データ読み込みチェック */
		rqmt = rqspp + REG_MBI_HEAD;
		command_word = *rqmt;
		sub_address = (command_word >> 5) & 0x1f;
		if (sub_address == 1)
			sub_adrs_count = 1;
		if (sub_address >= sub_adrs_count) {
			word_count = command_word & 0x1f;
			if (word_count == 0)
				word_count = 0x20;
			rbufp = ((*(rqmt + 1) & 0x0fff) << 1) + REG_MBI_HEAD;
			wbufp = mbi_rcvbuf + (sub_address - 1) * 32;
			for (i = 0; i < word_count; i++)
				*wbufp++ = *rbufp++;	/* データ読み込み */
			if (sub_address == 16) {
				if (ibuf_flag == 0) {
					COPY_WORD(input_data, mbi_rcvbuf, 512);
					ibuf_flag = 1;
					count_1553B++;
					rcv_1553B_flag = 1;
				}
			}
			sub_adrs_count = sub_address + 1;
		}

		/* ＲＱＳＰＰ更新 */
		rqspp += 4;
		if (rqspp >= 0x80)
			rqspp = 0x60;
	}
	*REG_MBI_RQSP = rqtp;
}
