/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
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
	/* �c�^�o���q�`�o�ɃZ�b�g */
	switch (sg_mode) {
	case MODE_AI:		/* �`�h�\�����[�h */
		*(struct DP_AI *)ADR_RAP_DP = dp_ai;
		break;
	case MODE_HUD:		/* �g�t�c�\�����[�h */
		*(struct DP_HUD *)ADR_RAP_DP = dp_hud;
		break;
	case MODE_HDD:		/* �g�c�c�\�����[�h */
		*(struct DP_HDD *)ADR_RAP_DP = dp_hdd;
		break;
	case MODE_MENU:		/* ���j���[�\�����[�h */
		break;
	case MODE_DATA:		/* �O���Ɠx�f�[�^�\�����[�h */
		*(struct DP_DATA *)ADR_RAP_DP = dp_data;
		break;
	}

	/* �o�^�`�r�b�g���q�`�o�ɃZ�b�g */
	*(struct PA_BIT *)ADR_RAP_PA = pa_bit;

	/* �r�f�I�R���g���[�����W�X�^�Z�b�g */
	*REG_GSC_OUTPUT = video_control;
}
