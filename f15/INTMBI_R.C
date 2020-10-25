/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �P�T�T�R�a�f�[�^��M���荞�ݏ���	*
 *		�t�@�C����	: intmbi_rx.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_rx

#include "sg_cpu.h"

/*	�����֐�	*/
static	void	read_mbi(void);
/*static*/	void	filtering(void);

/*=======================================================================
 |
 |		�P�T�T�R�a�f�[�^��M���荞�ݏ���
 |
 |	void	intmbi_rx()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intmbi_rx()
{
	/* ��M�f�[�^�̓ǂݍ��� */
	read_mbi();

	switch (sg_mode) {
	case MODE_AI:		/* �`�h�\�����[�h */
		/* �t�C���^�����O */
		filtering();

		/* �`�h�\���p�c�^�o�Z�b�g */
		dp_ai_pitch();		/* �s�b�` */
		dp_ai_roll();		/* ���[�� */
		dp_ai_mhdg();		/* ������ */
		dp_ai_tas();		/* �^��C���x */
		dp_ai_alt();		/* �C�����x */
		dp_ai_rollscale();	/* ���[���X�P�[�� */
		break;
	case MODE_HUD:		/* �g�t�c�\�����[�h */
		/* �t�C���^�����O */
		filtering();

		/* �g�t�c�\���p�c�^�o�Z�b�g */
		dp_hud_pitch();		/* �s�b�` */
		dp_hud_roll();		/* ���[�� */
		dp_hud_ias();		/* �w����C���x */
		dp_hud_mhdg();		/* ������ */
		dp_hud_alt();		/* �C�����x */
		dp_hud_vvc();		/* �x���V�e�B�[�x�N�^ */
		break;
	case MODE_HDD:		/* �g�c�c�\�����[�h */
		/* �t�C���^�����O */
		filtering();

		/* �g�c�c�\���p�c�^�o�Z�b�g */
		dp_hdd_tas();		/* �^��C���x */
		dp_hdd_alt();		/* �C�����x */
		break;
	case MODE_MENU:		/* ���j���[�\�����[�h */
		/* �c�^�o�͖��� */
		break;
	case MODE_DATA:		/* �O���Ɠx�f�[�^�\�����[�h */
		/* �O���Ɠx�f�[�^�\���p�c�^�o�Z�b�g */
		dp_gaikou();
		break;
	}
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		�P�T�T�R�a��M�f�[�^�ǂݍ��ݏ���
 |
 |	void	read_mbi()
 |
 =======================================================================*/
static	void	read_mbi()
{
	short	read_counter;
	short	rqspp, rqtpp;
	short	word_count, i;
	word	*rbufp, *wbufp;

	read_counter = 0;	/* �ǂݍ��݃J�E���^�N���A */

	for (;;) {
		/* �L���[�|�C���^�`�F�b�N */
		rqspp = *REG_MBI_RQSP & 0x0fff;
		rqtpp = *REG_MBI_RQTP & 0x0fff;
		if (rqspp == rqtpp)
			break;

		/* �ǂݍ��݃J�E���^�`�F�b�N */
		if (++read_counter >= 8)
			break;

		/* �u���b�N�ԃ^�C�}�[���Z�b�g */
		tmc_1553B = 0;			/* �^�C�}�[�J�E���^�N���A */
		tmf_1553B = TIMER_START;	/* �^�C�}�[�N�� */
		tmo_1553B = 0;			/* �^�C���A�E�g�t���O���Z�b�g*/

		/* ��M�f�[�^�ǂݍ��� */
		command_word = *(REG_MBI_RQMT + rqspp / 2);
		word_count = command_word & 0x1f;
		rbufp = REG_MBI_BMWB + rqspp * 32;
		if (word_count == 8) {		/* �f�[�^��ރ`�F�b�N */
			/* ��s�f�[�^ */
			wbufp = (word *)&orgdata;
			r1553B_data = 1;
		} else {
			/* �O���Ɠx�f�[�^ */
			wbufp = (word *)&lumdata;
			r1553B_data = 2;
		}
		for (i = 0; i < word_count; i++) {
			*wbufp++ = *rbufp;	/* �f�[�^�ǂݍ��� */
			rbufp = (word *)((char *)rbufp + 1);
		}
	}
}

/*=======================================================================
 |
 |		�t�B���^�����O����
 |
 |	void	filtering()
 |
 =======================================================================*/
/*static*/	void	filtering()
{
	/* �@�̃s�b�` */
	tmpdata.IPTCHA += (((short)(orgdata.IPTCHA & 0xfff0) - tmpdata.IPTCHA) * (long)DELTA_t) / (T_IPTCHA + DELTA_t);
	fltdata.IPTCHA = tmpdata.IPTCHA >> 4;

	/* �@�̃��[�� */
	tmpdata.IROLLA += (((short)(orgdata.IROLLA & 0xfff0) - tmpdata.IROLLA) * (long)DELTA_t) / (T_IROLLA + DELTA_t);
	fltdata.IROLLA = tmpdata.IROLLA >> 4;

	/* �w�����x���V�e�B�x�N�^ */
	tmpdata.OHVVCX += (((short)(orgdata.OHVVCX & 0xffc0) - tmpdata.OHVVCX) * (long)DELTA_t) / (T_OHVVCX + DELTA_t);
	fltdata.OHVVCX = tmpdata.OHVVCX >> 6;

	/* �x�����x���V�e�B�x�N�^ */
	tmpdata.OHVVCY += (((short)(orgdata.OHVVCY & 0xffc0) - tmpdata.OHVVCY) * (long)DELTA_t) / (T_OHVVCY + DELTA_t);
	fltdata.OHVVCY = tmpdata.OHVVCY >> 6;

	/* �@�̎����� */
	fltdata.IMAGHD = (ushort)orgdata.IMAGHD >> 4;

	/* �C�����x */
	if (orgdata.IHBALT != 0x8000)
		fltdata.IHBALT = orgdata.IHBALT + 31512; /* 31512 = 39390ft */

	/* �^��C���x */
	if ((orgdata.IASPDT & BIT0) == 0)
		fltdata.IASPDT = orgdata.IASPDT >> 1;

	/* �w����C���x */
	if ((orgdata.IASPDI & BIT0) == 0)
		fltdata.IASPDI = orgdata.IASPDI >> 1;
}