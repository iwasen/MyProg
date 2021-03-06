/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �g�c�c�\���p�c�^�o�v�Z����		*
 *		�t�@�C����	: dp_hdd.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*=======================================================================
 |
 |		�g�c�c�^��C���x�@�c�^�o�v�Z
 |
 |	void	dp_hdd_tas()
 |
 =======================================================================*/
void	dp_hdd_tas()
{
	static	word	*chrtbl[3][3] = {
		{&dp_hdd.A3202, &dp_hdd.A3201, &dp_hdd.A3200},
		{&dp_hdd.A3212, &dp_hdd.A3211, &dp_hdd.A3210},
		{&dp_hdd.A3222, &dp_hdd.A3221, &dp_hdd.A3220}
	};
	word	tas, delta_y, n, n1, n2, n3, k;
	short	i;

	/* �}�C�i�X�̏ꍇ�͂O�Ƃ��� */
	tas = fltdata.IASPDT;
	if (tas & 0x4000)
		tas = 0;

	/* ���x���o�� */
	delta_y = tas & 0x0f;

	/* �P���ڂ̐������o�� */
	n = tas >> 4;
	n1 = n % 10;

	/* �Q���ڂ̐������o�� */
	n2 = (n / 10) % 10;

	/* �R���ڂ̐������o�� */
	n3 = (n / 100) % 10;

	/* �c�o�R�Q�O�`�Q�̌v�Z */
	if (delta_y > 4 && n1 == 9) {
		k = 2 * (delta_y - 4);
		if (k > 16)		/* 16 == 1.0kt */
			k = 16;
		dp_hdd.DP321 = (k * 31 / 4 - 886) << 1;
		if (n2 == 9)
			dp_hdd.DP322 = dp_hdd.DP321;
		else
			dp_hdd.DP322 = -886 << 1;
	} else
		dp_hdd.DP322 = dp_hdd.DP321 = -886 << 1;
	dp_hdd.DP320 = (delta_y * 31 / 4 - 886) << 1;

	/* �`�R�Q�O�O�`�`�R�Q�Q�Q�̐ݒ� */
	n1 += 8;
	n2 += 8;
	n3 += 8;
	for (i = 0; i < 3; i++) {
		/* �P������ */
		n1 = (n1 + 1) % 10;
		*chrtbl[0][i] = numfont5_tbl[n1];

		/* �P�O�������i�[���T�v���X�j */
		n2 = (n2 + 1) % 10;
		*chrtbl[1][i] = (n2 == 0 && n < 10) ?
				FONT_T5 : numfont5_tbl[n2];

		/* �P�O�O�������i�[���T�v���X�j */
		n3 = (n3 + 1) % 10;
		*chrtbl[2][i] = (n3 == 0 && n < 100) ?
				FONT_T5 : numfont5_tbl[n3];
	}
}

/*=======================================================================
 |
 |		�g�c�c�C�����x�@�c�^�o�v�Z
 |
 |	void	dp_hdd_alt()
 |
 =======================================================================*/
void	dp_hdd_alt()
{
	static	word	*chrtbl[4][3] = {
		{&dp_hdd.A3302, &dp_hdd.A3301, &dp_hdd.A3300},
		{&dp_hdd.A3312, &dp_hdd.A3311, &dp_hdd.A3310},
		{&dp_hdd.A3322, &dp_hdd.A3321, &dp_hdd.A3320},
		{&dp_hdd.A3332, &dp_hdd.A3331, &dp_hdd.A3330}
	};
	word	alt, delta_y, n, n1, n2, n3, n4, k;
	short	i;

	alt = fltdata.IHBALT;

	/* ���x���o�� */
	delta_y = alt & 0x07;

	/* �P���ڂ̐������o�� */
	n = alt >> 3;
	n1 = n % 10;

	/* �Q���ڂ̐������o�� */
	n2 = (n / 10) % 10;

	/* �R���ڂ̐������o�� */
	n3 = (n / 100) % 10;

	/* �S���ڂ̐������o�� */
	n4 = n / 1000;

	/* �c�o�R�R�O�`�R�̌v�Z */
	if (n < 100) {
		/* �P�O�O�O�������� */
		if (delta_y > 2 && n1 == 9) {
			k = 2 * (delta_y - 2);
			if (k > 8)		/* 8 == 10ft */
				k = 8;
			dp_hdd.DP331 = (k * 61 / 4 - 886) << 1;
			if (n2 == 9)
				dp_hdd.DP332 = dp_hdd.DP331;
			else
				dp_hdd.DP332 = -886 << 1;
			dp_hdd.DP333 = -886 << 1;
		} else
			dp_hdd.DP333 = dp_hdd.DP332 = dp_hdd.DP331 = -886 << 1;
		dp_hdd.DP330 = (delta_y * 61 / 4 - 886) << 1;

		/* �`�R�R�O�O�`�`�R�R�R�Q�̐ݒ� */
		n1 += 8;
		n2 += 8;
		n3 += 8;
		for (i = 0; i < 3; i++) {
			/* �P�O������ */
			n1 = (n1 + 1) % 10;
			*chrtbl[0][i] = numfont5_tbl[n1];

			/* �P�O�O�������i�[���T�v���X�j */
			n2 = (n2 + 1) % 10;
			*chrtbl[1][i] = (n2 == 0 && n < 10) ?
					FONT_T5 : numfont5_tbl[n2];

			/* �P�O�O�O�������i�[���T�v���X�j */
			n3 = (n3 + 1) % 10;
			*chrtbl[2][i] = (n3 == 0) ?
					FONT_T5 : numfont5_tbl[n3];

			/* �P�O�O�O�O�������i�X�y�[�X�j */
			*chrtbl[3][i] = FONT_T5;
		}
	} else {
		/* �P�O�O�O�����ȏ� */
		delta_y += n1 << 3;
		if (delta_y > 24 && n2 == 9) {
			k = 2 * (delta_y - 24);
			if (k > 80)		/* 80 == 100ft */
				k = 80;
			dp_hdd.DP332 = (k * 6 / 4 - 886) << 1;
			if (n3 == 9)
				dp_hdd.DP333 = dp_hdd.DP332;
			else
				dp_hdd.DP333 = -886 << 1;
		} else
			dp_hdd.DP333 = dp_hdd.DP332 = -886 << 1;
		dp_hdd.DP331 = dp_hdd.DP330 = (delta_y * 6 / 4 - 886) << 1;

		/* �`�R�R�O�O�`�`�R�R�R�Q�̐ݒ� */
		n2 += 8;
		n3 += 8;
		n4 += 8;
		for (i = 0; i < 3; i++) {
			/* �P�O�������i�[���j */
			*chrtbl[0][i] = numfont5_tbl[0];

			/* �P�O�O������ */
			n2 = (n2 + 1) % 10;
			*chrtbl[1][i] = numfont5_tbl[n2];

			/* �P�O�O�O������ */
			n3 = (n3 + 1) % 10;
			*chrtbl[2][i] = numfont5_tbl[n3];

			/* �P�O�O�O�O�������i�[���T�v���X�j */
			n4 = (n4 + 1) % 10;
			*chrtbl[3][i] = (n4 == 0 && n < 1000) ?
					FONT_T5 : numfont5_tbl[n4];
		}
	}
}
