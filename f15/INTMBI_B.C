/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �l�a�h�{�[�h�a�h�s�I������		*
 *		�t�@�C����	: intmbi_bit.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_bit

#include "sg_cpu.h"

/*=======================================================================
 |
 |		�l�a�h�{�[�h�a�h�s�I�����荞�ݏ���
 |
 |	void	intmbi_bit()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intmbi_bit()
{
	word	brcs;

	/* �h�r�v�O�[���N���A */
	*REG_MBI_ISW0 = 0x0000;

	/* �a�q�b�r�`�F�b�N */
	brcs = *REG_MBI_BRCS & 0x00ff;
	if (brcs == 0x01 || brcs == 0x90 || brcs == 0x91) { /* ���ʃ`�F�b�N */
		/* ����I�� */
		/* �l�a�h�{�[�h�a�h�s�I�����荞�ݑ҂��^�C�}�[��~ */
		tmf_mbibit = TIMER_STOP;

		/* �l�a�h�{�[�h�����~ */
		*REG_MBI_CFCW = 0x0000;

		/* �q�s���[�h�ݒ� */
		*REG_MBI_CFCW = 0x4007;

		/* �q�s�I�y���[�V�����X�e�[�^�X�ݒ� */
		*REG_MBI_OPCW = 0x0000;

		/* �q�s�A�h���X�ݒ� */
		*REG_MBI_RTID = 0x003e;

		/* ��M�o�b�t�@�̃A�h���b�V���O */
		*((word *)((byte *)REG_MBI_RQMT+1)) = 0x0080;
		*((word *)((byte *)REG_MBI_RQMT+3)) = 0x00c0;
		*((word *)((byte *)REG_MBI_RQMT+5)) = 0x0100;
		*((word *)((byte *)REG_MBI_RQMT+7)) = 0x0140;
		*((word *)((byte *)REG_MBI_RQMT+9)) = 0x0180;
		*((word *)((byte *)REG_MBI_RQMT+11)) = 0x01c0;
		*((word *)((byte *)REG_MBI_RQMT+13)) = 0x0200;
		*((word *)((byte *)REG_MBI_RQMT+15)) = 0x0240;

		/* �a�h�s�k�d�c�����y�ю�M���荞�݃}�X�N�̉��� */
		*REG_MBI_IMSK = 0x00f5;

		/* �P�T�T�R�a�u���b�N�ԃ^�C�}�[�Z�b�g */
		tmc_1553B = 0;			/* �^�C�}�[�J�E���^�N���A */
		tmf_1553B = TIMER_START;	/* �^�C�}�[�N�� */

		/* �l�a�h�{�[�h�N���t���O�Z�b�g */
		mbi_start_flag = 1;
	} else {
		/* �ُ�I�� */
		/* �l�a�h�{�[�h�̃\�t�g�E�F�A���Z�b�g */
		*REG_MBI_CFCW = 0x2000;

		/* �l�a�h�{�[�h�a�h�s�I�����荞�ݑ҂��^�C�}�[�Z�b�g */
		tmc_mbibit = 0;			/* �^�C�}�[�J�E���^�N���A */
		tmf_mbibit = TIMER_START;	/* �^�C�}�[�N�� */
	}
}
