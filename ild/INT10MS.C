/************************************************************************
 *									*
 *		�������g�\�����u�@�r�^�f�@�b�o�t�v���O����		*
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
void	int10ms(void)
{
	char	sendbuf[2];

	/* �l�a�h�{�[�h�a�h�s�I�����荞�݃^�C���A�E�g���o */
	if (tmf_mbibit == TIMER_START) {
		if (++tmc_mbibit == 500) {
			/* �l�a�h�{�[�h�a�h�s�`�F�b�N�Ď��s */
			mbibit();

			/* �l�a�h�{�[�h�a�h�s�I�����荞�ݑ҂��^�C�}�[�Z�b�g */
			tmc_mbibit = 0;		/* �^�C�}�[�J�E���^�N���A */
			tmf_mbibit = TIMER_START;	/* �^�C�}�[�N�� */
		}
	}

	/* �f�[�^�X�V�����̊Ď� */
	if (tmf_1553B == TIMER_START) {
		if (++tmc_1553B == 100) {
			/* �P�T�T�R�a�f�[�^�X�V���[�g�ۑ� */
			rate_1553B = count_1553B;

			/* �\���X�V���[�g�ۑ� */
			rate_gscint = count_gscint;

			/* �P�T�T�R�a�f�[�^��M�J�E���^�N���A */
			count_1553B = 0;

			/* �\���X�V�J�E���^�N���A */
			count_gscint = 0;

			if (rate_1553B == 0 && (rcv_1553B_flag || debug_flag))
				ibuf_flag = 1;

			rate_calc = count_calc;
			count_calc = 0;

			tmc_1553B = 0;		/* �^�C�}�[�J�E���^���Z�b�g */
		}
	}

	/* �c�^�t�֕\�����[�h�؂芷���f�[�^���M */
	if (tmf_dspmode == TIMER_START) {
		if (++tmc_dspmode == 5) {
			/* �\�����[�h�؂芷���f�[�^���M */
			sendbuf[0] = 'S';
			sendbuf[1] = video_mode;
			rsa_send(sendbuf, 2);

			tmc_dspmode = 0;	/* �^�C�}�[�J�E���^���Z�b�g */
		}
	}

	/* �c�^�t����̂a�h�s���ʎ�M�Ď� */
	if (tmf_dubit == TIMER_START) {
		if (++tmc_dubit == 500) {
			/* �c�^�t �a�h�s�G���[�t���O�Z�b�g */
			bit_status = BIT_DUERR;

			/* �c�^�t �a�h�s���ʎ�M�Ď��^�C�}�[��~ */
			tmf_dubit = TIMER_STOP;
		}
	}

	/* �_�ł�����V���{���̕\���^�C�~���O�̃J�E���g */
	if (++tmc_blink == 50) {	/* �Q�g�� */
		/* �u�����N�t���O�Z�b�g */
		blink_flag = !blink_flag;

		/* �u�����N�J�E���^���Z�b�g */
		tmc_blink = 0;
	}

	/* �f�W�^���\���̍X�V�^�C�~���O�̃J�E���g */
	if (++tmc_digital >= digital_update_rate) {	/* �T�g�� */
		/* �f�W�^���\���X�V�t���O�Z�b�g */
		digital_update_flag = 1;

		/* �f�W�^���\���X�V�J�E���^���Z�b�g */
		tmc_digital = 0;
	}

	/* �p���[�I���V�[�P���X */
	if (tmf_poweron == TIMER_START) {
		if (++tmc_poweron == 100) {
			power_on_seq();
		}
	}

	/* �f�o�b�O�p���̓f�[�^�V�~�����[�V�������� */
	if (debug_flag) {
		if (++tmc_simulation == 3) {
			debug_simulation();
			tmc_simulation = 0;
		}
	}
}