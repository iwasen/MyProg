/************************************************************************
 *									*
 *		�`�b�d�r�@�r�^�f�@�b�o�t�v���O����			*
 *									*
 *		����		: �l�a�h�{�[�h�a�h�s�I������		*
 *		�t�@�C����	: intmbi_b.c				*
 *									*
 ************************************************************************/

#pragma	SECTION	PROG=intmbi_b

#include "sg_cpu.h"

/*=======================================================================
 |
 |		�l�a�h�{�[�h�a�h�s�I�����荞�ݏ���
 |
 |	void	intmbi_bit()
 |
 =======================================================================*/
#pragma	INTERRUPT
void	intmbi_bit(void)
{
	word	brcs;
	word	isw0;

	isw0 = *REG_MBI_ISW0;

	/* �h�r�v�O�[���N���A */
	*REG_MBI_ISW0 = 0x0000;

	if ((isw0 & 0xe000) == 0x2000) {
		/* �a�q�b�r�`�F�b�N */
		brcs = *REG_MBI_BRCS;
		if (brcs == 0xfe01 || brcs == 0x6f90 || brcs == 0x6e91) {
			/* ����I�� */
			/* �l�a�h�{�[�h�a�h�s�I�����荞�ݑ҂��^�C�}�[��~ */
			tm_mbibit = TM_STOP;

			/* �l�a�h�{�[�h�����~ */
			*REG_MBI_CFCW = 0x0000;

			/* �q�s���[�h�ݒ� */
			*REG_MBI_CFCW = 0x4007;

			/* �q�s�I�y���[�V�����X�e�[�^�X�ݒ� */
			*REG_MBI_OPCW = 0x0080;

			/* �q�s�A�h���X�ݒ�i�S�j */
			*REG_MBI_RTID = 0x003b;

			/* ��M�o�b�t�@�̃A�h���b�V���O */
			*(REG_MBI_RQMT+1) = 0x0100;
			*(REG_MBI_RQMT+3) = 0x0140;
			*(REG_MBI_RQMT+5) = 0x0180;
			*(REG_MBI_RQMT+7) = 0x01c0;
			*(REG_MBI_RQMT+9) = 0x0200;
			*(REG_MBI_RQMT+11) = 0x0240;
			*(REG_MBI_RQMT+13) = 0x0280;
			*(REG_MBI_RQMT+15) = 0x02c0;

			/* ���M�o�b�t�@�̃A�h���b�V���O */
			*(REG_MBI_TMTA+1) = 0x0300;
			*(REG_MBI_TMTA+30) = 0x0340;

			/* �P�T�T�R�a���M�o�b�t�@�Z�b�g */
			set_mbi_sendbuf();

			/* �a�h�s�k�d�c�����y�ъ��荞�݃}�X�N�̉��� */
			*REG_MBI_IMSK = 0x00f7;
			*REG_VIC_VICR5 = 0x7d;
			*REG_VIC_VICR6 = 0x7e;
			*REG_WDT = 0x9f;
		} else {
			/* �ُ�I�� */
			if (++counter_mbibit < 3)
				mbibit();/* �l�a�h�{�[�h�a�h�s�`�F�b�N�Ď��s */
			else
				halt_cpu("MBI BIT ERROR", 1);
		}
	} else if ((isw0 & 0xe01f) == 0x4003) {
		/* �q���������� �a�h�s */
		rbit_request = 1;
	}
}
