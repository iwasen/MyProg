/************************************************************************
 *									*
 *		�������g�\�����u�@�r�^�f�@�b�o�t�v���O����		*
 *									*
 *		����		: �P�T�T�R�a�f�[�^��M���荞�ݏ���	*
 *		�t�@�C����	: intmbi_rx.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_rx

#include "sg_cpu.h"
extern	void	shortout(short);
/*=======================================================================
 |
 |		�P�T�T�R�a�f�[�^��M���荞�ݏ���
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

	read_counter = 0;		/* �ǂݍ��݃J�E���^�N���A */

	/* �L���[�|�C���^���o�� */
	rqspp = (*REG_MBI_RQSP & 0x00ff) << 1;
	rqtp = *REG_MBI_RQTP;
	rqtpp = (rqtp & 0x00ff) << 1;

	for (;;) {
		/* �L���[�|�C���^�`�F�b�N */
		if (rqspp == rqtpp)
			break;

		/* �ǂݍ��݃J�E���^�`�F�b�N */
		if (++read_counter > 8)
			break;

		/* ��M�f�[�^�ǂݍ��݃`�F�b�N */
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
				*wbufp++ = *rbufp++;	/* �f�[�^�ǂݍ��� */
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

		/* �q�p�r�o�o�X�V */
		rqspp += 4;
		if (rqspp >= 0x80)
			rqspp = 0x60;
	}
	*REG_MBI_RQSP = rqtp;
}