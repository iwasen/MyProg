/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �q�r�|�Q�R�Q�b�f�[�^��M����		*
 *		�t�@�C����	: intrs2.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intrs2

#include "sg_cpu.h"

/*	�����֐�	*/
static	void	mode_check(void);
static	void	set_PAbit(void);

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b���荞�ݏ���
 |
 |	void	intrs2()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intrs2()
{
	byte	ch, isr, srb;

	/* ���荞�݃X�e�[�^�X���W�X�^�ǂݍ��� */
	isr = *REG_DUART_ISR;

	if (isr & BIT1) {	/* �b�g�|�`���荞�݁H */
		/* �b�g�|�`���荞�݋֎~ */
		*REG_DUART_IMR = 0x20;
	}

	if (isr & BIT5) {	/* �b�g�|�a���荞�݁H */
		while ((srb = *REG_DUART_SRB) & BIT0) {
			if (srb & (BIT4 | BIT5 | BIT6)) {/* ��M�G���[�L��H */
				/* �o�b�t�@���f�[�^�ǂݎ̂� */
				ch = *REG_DUART_RHRB;

				/* ��M�J�E���^�N���A */
				rs_counter = 0;
			} else {
				/* ��M�f�[�^�ǂݍ��� */
				ch = *REG_DUART_RHRB;

				switch (rs_counter) {
				case 0:
					if (ch == '#') {
						/* �u���b�N�ԃ^�C�}�[�Z�b�g */
						tmc_rsblock = 0;
						tmf_rsblock = TIMER_START;
						tmo_rsblock = 0;

						/* �����ԃ^�C�}�[�Z�b�g */
						tmc_rschar = 0;
						tmf_rschar = TIMER_START;

						/* ��M�J�E���^�Z�b�g */
						rs_counter = 1;
					}
					break;
				case 1:
					if (ch == 'S') {
						/* ��M�J�E���^�Z�b�g */
						rs_counter = 2;
					} else {
						/* �����ԃ^�C�}�[��~ */
						tmf_rschar = TIMER_STOP;

						/* ��M�J�E���^�N���A */
						rs_counter = 0;
					}
					break;
				case 2:
					/* ��M�f�[�^�Z�[�u */
					rs_receive_data = ch;

					/* ��M�J�E���^�Z�b�g */
					rs_counter = 3;
					break;
				}
			}
		}

		if (rs_counter == 3) {
			/* �����ԃ^�C�}�[��~ */
			tmf_rschar = TIMER_STOP;

			if (gsc_start_flag == 0) {	/* �P��ځH */
				/* �f�r�b�{�[�h�N�� */
				*REG_GSC_OUTPUT = 0x0000;
				gsc_start_flag = 1;
			}

			/* ���[�h�ύX�`�F�b�N */
			mode_check();

			/* �o�^�`�r�b�g�Z�b�g */
			set_PAbit();

			/* ��M�J�E���^�N���A */
			rs_counter = 0;
		}
	}
}
#pragma	INTERRUPT-

/*=======================================================================
 |
 |		�\�����[�h�ύX�`�F�b�N
 |
 |	void	mode_check()
 |
 =======================================================================*/
static	void	mode_check()
{
	if (rs_receive_data & BIT7) {
		/* �ʏ탂�[�h */
		if (rs_receive_data & BIT2)	/* �O���t�B�b�N�\���H */
			sg_mode = MODE_AI;
		else {
			if (rs_receive_data & BIT3)
				sg_mode = MODE_HUD;
			else
				sg_mode = MODE_HDD;
		}
	} else {
		/* �����e�i���X���[�h */
		switch (rs_receive_data) {
		case 0x80:	/* �ʏ탂�[�h�|���O�ƃ��[�h���j���[�\�� */
			if (sg_mode == MODE_AI || sg_mode == MODE_HUD
						|| sg_mode == MODE_HDD)
				sg_mode = MODE_MENU;
			break;
		case 0x82:	/* �O�ƃf�[�^�\���|���O�ƃ��[�h���j���[�\�� */
			if (sg_mode == MODE_DATA)
				sg_mode = MODE_MENU;
			break;
		case 0x84:	/* �O�ƃf�[�^���j���[�\���|���O�ƃf�[�^�\�� */
			if (sg_mode == MODE_MENU)
				sg_mode = MODE_DATA;
			break;
		case 0xa0:	/* �O�ƃf�[�^�L�^�I�� */
			if (sg_mode == MODE_MENU)
				memorizing_flag = 0;
			break;
		case 0xa1:	/* �O�ƃf�[�^�L�^�J�n */
			if (sg_mode == MODE_MENU)
				memorizing_flag = 1;
			break;
		}
	}
}

/*=======================================================================
 |
 |		�o�^�`�r�b�g�ݒ菈��
 |
 |	void	set_PAbit()
 |
 =======================================================================*/
static	void	set_PAbit()
{
	/* �S�o�^�`�r�b�g�N���A */
	pa_bit.PA00001 = 0x0000;
	pa_bit.PA00002 = 0x0000;
	pa_bit.PA00003 = 0x0000;
	pa_bit.PA00004 = 0x0000;
	pa_bit.PA00005 = 0x0000;
	pa_bit.PA00006 = 0x0000;

	/* ���[�h�ʂɂo�^�`�r�b�g���Z�b�g���� */
	switch (sg_mode) {
	case MODE_AI:		/* �`�h�\�����[�h */
		pa_bit.PA00001 |=
			PA1_F103  | PA1_F104A | PA1_F104B | PA1_F104C |
			PA1_F104D | PA1_F104E | PA1_F104F | PA1_F105A |
			PA1_F105B | PA1_F105C | PA1_F106  | PA1_F107  |
			PA1_F108  | PA1_F109A;

		/* �p�C���b�g�R�����g */
		if (rs_receive_data & BIT5)	/* GOOD ? */
			pa_bit.PA00001 |= PA1_F112A;
		if (rs_receive_data & BIT6)	/* NO GOOD ? */
			pa_bit.PA00001 |= PA1_F112B;

		/* �s�`�r�ُ펞�V���{�� */
		if (orgdata.IASPDT & BIT0)
			pa_bit.PA00002 |= PA2_F113C;

		/* �`�k�s�ُ펞�V���{�� */
		if (orgdata.IHBALT == 0x8000)
			pa_bit.PA00002 |= PA2_F113D;

		/* �P�T�T�R�a�`���G���[ */
		if (tmo_1553B)
			pa_bit.PA00002 |= PA2_F113A | PA2_F113B | PA2_F113C |
					  PA2_F113D;
		break;
	case MODE_HUD:		/* �g�t�c�\�����[�h */
		pa_bit.PA00003 |=
			PA3_F202 | PA3_F203 | PA3_F204 | PA3_F205 |
			PA3_F207 | PA3_F208 | PA3_F209 | PA3_F210;

		/* �x���V�e�B�[�x�N�^ */
		if (tmo_1553B == 0)
			pa_bit.PA00003 |= PA3_F206;

		/* �p�C���b�g�R�����g */
		if (rs_receive_data & BIT5)	/* GOOD ? */
			pa_bit.PA00003 |= PA3_F112A;
		if (rs_receive_data & BIT6)	/* NO GOOD ? */
			pa_bit.PA00003 |= PA3_F112B;

		/* �h�`�r�ُ펞�V���{�� */
		if (orgdata.IASPDI & BIT0)
			pa_bit.PA00003 |= PA3_F211B;

		/* �`�k�s�ُ펞�V���{�� */
		if (orgdata.IHBALT == 0x8000)
			pa_bit.PA00003 |= PA3_F211D;

		/* �P�T�T�R�a�`���G���[ */
		if (tmo_1553B)
			pa_bit.PA00003 |= PA3_F211A | PA3_F211B | PA3_F211C |
					  PA3_F211D;
		break;
	case MODE_HDD:		/* �g�c�c�\�����[�h */
		pa_bit.PA00004 |= PA4_F301 | PA4_F302 | PA4_F303;

		/* �p�C���b�g�R�����g */
		if (rs_receive_data & BIT5)	/* GOOD ? */
			pa_bit.PA00004 |= PA4_F112A;
		if (rs_receive_data & BIT6)	/* NO GOOD ? */
			pa_bit.PA00004 |= PA4_F112B;

		/* �s�`�r�ُ펞�V���{�� */
		if (orgdata.IASPDT & BIT0)
			pa_bit.PA00004 |= PA4_F304A;

		/* �`�k�s�ُ펞�V���{�� */
		if (orgdata.IHBALT == 0x8000)
			pa_bit.PA00004 |= PA4_F304B;

		/* �P�T�T�R�a�`���G���[ */
		if (tmo_1553B)
			pa_bit.PA00004 |= PA4_F304A | PA4_F304B;
		break;
	case MODE_MENU:		/* ���j���[�\�����[�h */
		/* �Œ�p�^�[�� */
		pa_bit.PA00005 |= PA5_F401;

		/* �L�^���^�L�^�I�����b�Z�[�W */
		if (memorizing_flag == 0)
			pa_bit.PA00005 |= PA5_F403;	/* �L�^�I�� */
		else
			pa_bit.PA00005 |= PA5_F402;	/* �L�^�� */
		break;
	case MODE_DATA:		/* �O���Ɠx�f�[�^�\�����[�h */
		pa_bit.PA00006 |=
			PA6_F501 | PA6_F502 | PA6_F503 | PA6_F504 |
			PA6_F505 | PA6_F506 | PA6_F507 | PA6_F508 |
			PA6_F509;
		break;
	}

	/* �r�f�I�\���R���g���[�����W�X�^�ݒ� */
	if (rs_receive_data & BIT0)
		video_control = 0x900;	/* �r�f�I�\�����[�h */
	else if (rs_receive_data & BIT1)
		video_control = 0x500;	/* �r�f�I�{�O���t�B�b�N�\�����[�h */
	else if (rs_receive_data & BIT2)
		video_control = 0x100;	/* �O���t�B�b�N�\�����[�h */

	if (rs_receive_data & BIT4)
		video_control |= 0x20;	/* �΁^�� */
	else
		video_control |= 0x10;	/* ���^�� */
}