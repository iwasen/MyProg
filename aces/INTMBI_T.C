/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: １５５３Ｂデータ伝送終了処理		*
 *		ファイル名	: intmbi_t.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_t

#include "sg_cpu.h"

void	set_mbi_sendbuf(void);

/*=======================================================================
 |
 |		１５５３Ｂデータ伝送終了割り込み処理
 |
 |	void	intmbi_bit()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intmbi_tx(void)
{
	word	isw1;

//	isw1 = *REG_MBI_ISW1;
//	*REG_MBI_ISW1 = 0x0000;		/* INT ACK */
	set_mbi_sendbuf();
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		１５５３Ｂ送信バッファセット
 |
 |	void	set_mbi_sendbuf()
 |
 =======================================================================*/
void	set_mbi_sendbuf(void)
{
	short	sg1_mode, sg2_mode, sg3_mode;
	ushort	sg1_bit_status, sg2_bit_status, sg3_bit_status;
	ushort	du1_bit_status, du2_bit_status, du3_bit_status;
	ushort	*sbufp;
	ushort	tmp;

	/* 送信バッファポインタセット */
//	sbufp = (uchar *)(REG_MBI_BMWB + 0x300);
/*@@@@@@@@@@@@*/
{
	extern	ushort	sbuf[];
	sbufp = sbuf;
}
/*@@@@@@@@@@@@*/

	/* ベゼルスイッチデータセット */
	*sbufp++ = bezel_sw1;
	*sbufp++ = bezel_sw2;
	*sbufp++ = CPU2_VME(bezel_sw1);
	*sbufp++ = CPU2_VME(bezel_sw2);
	*sbufp++ = CPU3_VME(bezel_sw1);
	*sbufp++ = CPU3_VME(bezel_sw2);

	/* Ｓ／Ｇモード取り出し */
	sg1_mode = sg_mode;
	sg2_mode = CPU2_VME(sg_mode);
	sg3_mode = CPU3_VME(sg_mode);

	/* Ｓ／Ｇ　ＢＩＴステータス取り出し */
	sg1_bit_status = sg_bit_status;
	sg2_bit_status = CPU2_VME(sg_bit_status);
	sg3_bit_status = CPU3_VME(sg_bit_status);

	/* Ｄ／Ｕ　ＢＩＴステータス取り出し */
	du1_bit_status = du_bit_status;
	du2_bit_status = CPU2_VME(du_bit_status);
	du3_bit_status = CPU3_VME(du_bit_status);

	tmp = 0;

	/* BIT STATUS セット */
	if (sg1_mode == SGM_PBIT ||
			sg1_mode == SGM_IBIT ||
			sg1_mode == SGM_RBIT ||
			sg2_mode == SGM_PBIT ||
			sg2_mode == SGM_IBIT ||
			sg2_mode == SGM_RBIT ||
			sg3_mode == SGM_PBIT || 
			sg3_mode == SGM_IBIT ||
			sg3_mode == SGM_RBIT)
		tmp |= 0x8000;

	/* BIT RESULT-S/G セット */
	if (sg1_bit_status != 0 || sg2_bit_status != 0 || sg3_bit_status != 0)
		tmp |= 0x1000;

	/* BIT RESULT-DU1 セット */
	if (du1_bit_status != 0)
		tmp |= 0x0800;

	/* POWER ON BIT RESULT-DU2 セット */
	if (du2_bit_status != 0)
		tmp |= 0x0400;

	/* POWER ON BIT RESULT-DU3 セット */
	if (du3_bit_status != 0)
		tmp |= 0x0200;

	*sbufp++ = tmp;		/* バッファにセット */

	tmp = 0;

	/* S/G-MIL-STD-1553B BOARD TEST セット */
	if (mbi_board_test != BIT_OK)
		tmp |= 0x0008;

	/* S/G-GSC#1 BOARD TEST セット */
	if (gsc_board_test != BIT_OK)
		tmp |= 0x0004;

	/* S/G-GSC#2 BOARD TEST セット */
	if (CPU2_VME(gsc_board_test) != BIT_OK)
		tmp |= 0x0002;

	/* S/G-GSC#3 BOARD TEST セット */
	if (CPU3_VME(gsc_board_test) != BIT_OK)
		tmp |= 0x0001;

	*sbufp++ = tmp;		/* バッファにセット */

	/* S/G BIT RESULT セット */
	*sbufp++ = ((sg1_bit_status & 0x1f) << 11) |
			(sg2_bit_status & 0x1f) << 6 |
			(sg3_bit_status & 0x1f) << 1;

	*sbufp++ = du1_bit_status;	/* D/U1 BIT RESULT セット */
	*sbufp++ = du2_bit_status;	/* D/U2 BIT RESULT セット */
	*sbufp   = du3_bit_status;	/* D/U3 BIT RESULT セット */
}
