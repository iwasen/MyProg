/************************************************************************
 *									*
 *		�r�^�f�@�b�o�t�v���O����				*
 *									*
 *		����		: �g�t�c�\���p�c�^�o�v�Z����		*
 *		�t�@�C����	: dp_hud.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*=======================================================================
 |
 |		�g�t�c�s�b�`�@�c�^�o�v�Z
 |
 |	void	dp_hud_pitch()
 |
 =======================================================================*/
void	dp_hud_pitch()
{
	/* �k�x�Q�O�V�̌v�Z */
	dp_hud.LY207 = (7678 - fltdata.IPTCHA * 15 / 2) << 1;

	/* �k�x�Q�O�X�̌v�Z */
	dp_hud.LY209 = (7678 + fltdata.IPTCHA * 15 / 2) << 1;
}

/*=======================================================================
 |
 |		�g�t�c���[���@�c�^�o�v�Z
 |
 |	void	dp_hud_roll()
 |
 =======================================================================*/
void	dp_hud_roll()
{
	/* �q�Q�O�O�̌v�Z */
	dp_hud.R200 = fltdata.IROLLA << 4;
}

/*=======================================================================
 |
 |		�g�t�c�w����C���x�@�c�^�o�v�Z
 |
 |	void	dp_hud_ias()
 |
 =======================================================================*/
void	dp_hud_ias()
{
	static	word	*chrtbl[3][3] = {
		{&dp_hud.A2200, &dp_hud.A2201, &dp_hud.A2202},
		{&dp_hud.A2210, &dp_hud.A2211, &dp_hud.A2212},
		{&dp_hud.A2220, &dp_hud.A2221, &dp_hud.A2222}
	};
	word	ias, delta_y, n, n1, n2, n3, k;
	short	i;

	/* �}�C�i�X�̏ꍇ�͂O�Ƃ��� */
	ias = fltdata.IASPDI;
	if (ias & 0x4000)
		ias = 0;

	/* ���x���o�� */
	delta_y = ias & 0x0f;

	/* �P���ڂ̐������o�� */
	n = ias >> 4;
	n1 = n % 10;

	/* �Q���ڂ̐������o�� */
	n2 = (n / 10) % 10;

	/* �R���ڂ̐������o�� */
	n3 = (n / 100) % 10;

	/* �c�o�Q�Q�O�`�Q�̌v�Z */
	if (delta_y > 4 && n1 == 9) {
		k = 2 * (delta_y - 4);
		if (k > 16)		/* 16 == 1.0kt */
			k = 16;
		dp_hud.DP221 = (k * 31 / 4 + 1000) << 1;
		if (n2 == 9)
			dp_hud.DP222 = dp_hud.DP221;
		else
			dp_hud.DP222 = 1000 << 1;
	} else
		dp_hud.DP222 = dp_hud.DP221 = 1000 << 1;
	dp_hud.DP220 = (delta_y * 31 / 4 + 1000) << 1;

	/* �`�Q�Q�O�O�`�`�Q�Q�Q�Q�̐ݒ� */
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
 |		�g�t�c�����ʁ@�c�^�o�v�Z
 |
 |	void	dp_hud_mhdg()
 |
 =======================================================================*/
void	dp_hud_mhdg()
{
	static	word	*chrtbl[3][3] = {
		{&dp_hud.A2300, &dp_hud.A2301, &dp_hud.A2302},
		{&dp_hud.A2310, &dp_hud.A2311, &dp_hud.A2312},
		{&dp_hud.A2320, &dp_hud.A2321, &dp_hud.A2322}
	};
	ushort	delta_y, n, n1, n2, n3, k;
	short	i;
	ulong	tmp;

	/* ���x���o�� */
	tmp = (ulong)fltdata.IMAGHD << 16;
	delta_y = (ushort)(tmp % (2048L * 0x10000L / 180L) >> 16);

	/* �P���ڂ̐������o�� */
	n = (ushort)(tmp / (2048L * 0x10000L / 180L));
	n1 = n % 10;

	/* �Q���ڂ̐������o�� */
	n2 = (n / 10) % 10;

	/* �R���ڂ̐������o�� */
	n3 = n / 100;

	/* �c�o�Q�R�O�`�Q�̌v�Z */
	if (delta_y > 3 && (n1 == 9 || n == 359)) {
		k = 2 * (delta_y - 3);
		if (k > 11)		/* 11 == 1.0deg */
			k = 11;
		dp_hud.DP231 = (k * 43 / 4 + 1000) << 1;
		if (n2 == 9 || n == 359)
			dp_hud.DP232 = dp_hud.DP231;
		else
			dp_hud.DP232 = 1000 << 1;
	} else
		dp_hud.DP232 = dp_hud.DP231 = 1000 << 1;
	dp_hud.DP230 = (delta_y * 43 / 4 + 1000) << 1;

	/* �`�Q�R�O�O�`�`�Q�R�Q�Q�̐ݒ� */
	n1 += 9;
	n2 += 9;
	n3 += 9;
	for (i = 0; i < 3; i++) {
		*chrtbl[0][i] = numfont5_tbl[n1 % 10];
		*chrtbl[1][i] = numfont5_tbl[n2 % 10];
		*chrtbl[2][i] = numfont5_tbl[n3 % 10];
		if (n++ == 360)
			n1 = n2 = n3 = 0;
		else {
			n1++;
			n2++;
			n3++;
		}
	}
}

/*=======================================================================
 |
 |		�g�t�c�C�����x�@�c�^�o�v�Z
 |
 |	void	dp_hud_alt()
 |
 =======================================================================*/
void	dp_hud_alt()
{
	static	word	*chrtbl[4][3] = {
		{&dp_hud.A2400, &dp_hud.A2401, &dp_hud.A2402},
		{&dp_hud.A2410, &dp_hud.A2411, &dp_hud.A2412},
		{&dp_hud.A2420, &dp_hud.A2421, &dp_hud.A2422},
		{&dp_hud.A2430, &dp_hud.A2431, &dp_hud.A2432}
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

	/* �c�o�Q�S�O�`�R�̌v�Z */
	if (n < 100) {
		/* �P�O�O�O�������� */
		if (delta_y > 2 && n1 == 9) {
			k = 2 * (delta_y - 2);
			if (k > 8)		/* 8 == 10ft */
				k = 8;
			dp_hud.DP241 = (k * 61 / 4 + 1000) << 1;
			if (n2 == 9)
				dp_hud.DP242 = dp_hud.DP241;
			else
				dp_hud.DP242 = 1000 << 1;
			dp_hud.DP243 = 1000 << 1;
		} else
			dp_hud.DP243 = dp_hud.DP242 = dp_hud.DP241 = 1000 << 1;
		dp_hud.DP240 = (delta_y * 61 / 4 + 1000) << 1;

		/* �`�Q�S�O�O�`�`�Q�S�R�Q�̐ݒ� */
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
			dp_hud.DP242 = (k * 6 / 4 + 1000) << 1;
			if (n3 == 9)
				dp_hud.DP243 = dp_hud.DP242;
			else
				dp_hud.DP243 = 1000 << 1;
		} else
			dp_hud.DP243 = dp_hud.DP242 = 1000 << 1;
		dp_hud.DP241 = dp_hud.DP240 = (delta_y * 6 / 4 + 1000) << 1;

		/* �`�Q�S�O�O�`�`�Q�S�R�Q�̐ݒ� */
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

/*=======================================================================
 |
 |		�g�t�c�x���V�e�B�[�x�N�^�@�c�^�o�v�Z
 |
 |	void	dp_hud_vvc()
 |
 =======================================================================*/
void	dp_hud_vvc()
{
	/* �o�w�Q�O�U�̌v�Z */
	dp_hud.PX206 = (fltdata.OHVVCX * 5 / 2) << 1;

	/* �o�x�Q�O�U�̌v�Z */
	dp_hud.PY206 = (fltdata.OHVVCY * 5 / 2) << 1;
}
