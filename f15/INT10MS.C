/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �P�O������������荞�ݏ���		*
 *		�t�@�C����	: int10ms.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=int10ms

#include "sg_cpu.h"

/*=======================================================================
 |
 |		�P�O������������荞�ݏ���
 |
 |	void	int10ms()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	int10ms()
{
	/* �v�c�s�g���K���Z�b�g */
	*REG_WDT = 0xff;

	/* �l�a�h�{�[�h�a�h�s�I�����荞�݃^�C���A�E�g���o */
	if (tmf_mbibit == TIMER_START) {
		if (tmc_mbibit == 500) {
			/* �l�a�h�{�[�h�a�h�s�`�F�b�N�Ď��s */
			mbibit();

			/* �l�a�h�{�[�h�a�h�s�I�����荞�ݑ҂��^�C�}�[�Z�b�g */
			tmc_mbibit = 0;		/* �^�C�}�[�J�E���^�N���A */
			tmf_mbibit = TIMER_START;	/* �^�C�}�[�N�� */
		} else {
			/* �^�C�}�[�J�E���^�X�V */
			++tmc_mbibit;
		}
	}

	/* �P�T�T�R�a�o�X�`���u���b�N�ԃ^�C���A�E�g���o */
	if (tmf_1553B == TIMER_START) {
		if (tmc_1553B == 500) {
			/* �^�C���A�E�g�t���O�Z�b�g */
			tmo_1553B = 1;

			/* �^�C�}�[��~ */
			tmf_1553B = TIMER_STOP;

			/* �l�a�h�{�[�h�a�h�s�`�F�b�N�Ď��s */
			mbibit();

			/* �l�a�h�{�[�h�a�h�s�I�����荞�ݑ҂��^�C�}�[�Z�b�g */
			tmc_mbibit = 0;		/* �^�C�}�[�J�E���^�N���A */
			tmf_mbibit = TIMER_START;	/* �^�C�}�[�N�� */
		} else {
			/* �^�C�}�[�J�E���^�X�V */
			++tmc_1553B;
		}
	}

	/* �q�r�|�Q�R�Q�b�`���u���b�N�ԃ^�C���A�E�g���o */
	if (tmf_rsblock == TIMER_START) {
		if (tmc_rsblock == 500) {
			/* �^�C���A�E�g�t���O�Z�b�g */
			tmo_rsblock = 1;

			/* �c�t�`�q�s�R���g���[�����W�X�^�Đݒ� */
			set_DUART();

			/* �u���b�N�ԃ^�C�}�[�Z�b�g */
			tmc_rsblock = 0;
			tmf_rsblock = TIMER_START;
		} else {
			/* �^�C�}�[�J�E���^�X�V */
			++tmc_rsblock;
		}
	}

	/* �q�r�|�Q�R�Q�b�`�������ԃ^�C���A�E�g���o */
	if (tmf_rschar == TIMER_START) {
		if (tmc_rschar == 4) {
			/* �^�C�}�[��~ */
			tmf_rschar = TIMER_STOP;

			/* �q�r�|�Q�R�Q�b��M�J�E���^�N���A */
			rs_counter = 0;
		} else {
			/* �^�C�}�[�J�E���^�X�V */
			++tmc_rschar;
		}
	}

	/* �f�o�b�O���r�f�I�^�[�~�i���`���o�� */
	if (debug_mode_flag) {
		if (tmc_debug == 200) {		/* 200 = �Q�b */
			debug();
			tmc_debug = 0;
		} else
			++tmc_debug;
	}
}