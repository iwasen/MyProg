/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: １５５３Ｂデータ受信割り込み処理	*
 *		ファイル名	: intmbi_r.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_r

#include "sg_cpu.h"

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
	short	word_count, sub_address;
	word	command_word, rqtp, *rqmt, *rbufp;

	*REG_MBI_ISW2 = 0x0000;		/* INT ACK */

	/* キューポインタ取り出し */
	rqspp = (*REG_MBI_RQSP & 0x00ff) << 1;
	rqtp = *REG_MBI_RQTP;
	rqtpp = (rqtp & 0x00ff) << 1;

	for (;;) {
		/* キューポインタチェック */
		if (rqspp == rqtpp)
			break;

		rqmt = rqspp + REG_MBI_HEAD;
		command_word = *rqmt;
		sub_address = (command_word >> 5) & 0x1f;

		/* サブアドレスチェック */
		if (sub_address >= 1 && sub_address <= 29) {
			if (sub_address == sub_adrs_count + 1) {
				if (sub_address == 29) {
					count_1553B++;
					sub_adrs_count = 0;
				} else
					sub_adrs_count++;
			} else
				sub_adrs_count = 0;
		}

		word_count = command_word & 0x1f;
		if (word_count == 0)
			word_count = 0x20;
		rbufp = ((*(rqmt + 1) & 0x0fff) << 1) + REG_MBI_HEAD;

		if (sub_address == 1) {
			mbi_rcvbuf[0][0] = *rbufp++;
			mbi_rcvbuf[0][1] = *rbufp++;
			if ((mbi_rcvbuf[0][1] & 0x8000) == 0) {
				COPY_WORD(&mbi_rcvbuf[0][2], rbufp, 30);
			} else {
				COPY_WORD(&mbi_rcvbuf[29][2], rbufp, 30);
			}
		} else if (sub_address <= 14) {
			if ((mbi_rcvbuf[0][1] & 0x8000) == 0) {
				COPY_WORD2(mbi_rcvbuf[sub_address - 1],
						rbufp, word_count);
			} else {
				COPY_WORD2(mbi_rcvbuf[sub_address+28],
						rbufp, word_count);
			}
		} else if (sub_address == 15) {
			if ((mbi_rcvbuf[0][1] & 0x8000) == 0) {
				COPY_WORD(mbi_rcvbuf[14], rbufp, 10);
			} else {
				COPY_WORD(mbi_rcvbuf[43], rbufp, 10);
			}
			COPY_WORD(&mbi_rcvbuf[14][10], rbufp+10, 22);
		} else if (sub_address <= 29) {
			COPY_WORD2(mbi_rcvbuf[sub_address - 1],
						rbufp, word_count);
		} else if (sub_address == 30) {
			/* WRAP AROUND TEST */
			COPY_WORD2(REG_MBI_BMWB + 0x340, rbufp, word_count);
		}

		/* ＲＱＳＰＰ更新 */
		rqspp += 4;
		if (rqspp >= 0x80)
			rqspp = 0x60;
	}
	*REG_MBI_RQSP = rqtp;
}
