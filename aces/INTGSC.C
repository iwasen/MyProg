/************************************************************************
 *									*
 *		�`�b�d�r�@�r�^�f�@�b�o�t�v���O����			*
 *									*
 *		����		: �f�r�b�`��I�����荞�ݏ���		*
 *		�t�@�C����	: intgsc.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intgsc

#include "sg_cpu.h"

/*=======================================================================
 |
 |		�f�r�b�`��I�����荞�ݏ���
 |
 |	void	intgsc()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intgsc()
{
//	word	ack;

//	ack = *GSC_ACK;		/* INT6 ACK */

	/* �c�^�o���q�`�o�ɃZ�b�g */
	*(DP *)GSC_RAP_DP = dp_tmp;

	/* �o�^�`�r�b�g���q�`�o�ɃZ�b�g */
	COPY_WORD(GSC_RAP_PA, pa_tmp, N_PABIT);

	calc_flag = 1;		/* �v�Z�J�n�t���O�Z�b�g */

	count_gscint++;		/* �\�������J�E���^�X�V */

	gsc_board_test = BIT_OK;	/* �f�r�b�{�[�h���� */

	tm_gscbackup = TM_GSCCHECK;	/* �f�r�b���荞�݊Ď��^�C�}�[�Z�b�g */
}
