/************************************************************************
 *									*
 *		‚r^‚f@‚b‚o‚tƒvƒƒOƒ‰ƒ€				*
 *									*
 *		–¼Ì		: ‚g‚c‚c•\¦—p‚c^‚oŒvZˆ—		*
 *		ƒtƒ@ƒCƒ‹–¼	: dp_hdd.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*=======================================================================
 |
 |		‚g‚c‚c^‘å‹C‘¬“x@‚c^‚oŒvZ
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

	/* ƒ}ƒCƒiƒX‚Ìê‡‚Í‚O‚Æ‚·‚é */
	tas = fltdata.IASPDT;
	if (tas & 0x4000)
		tas = 0;

	/* ƒ¢‚xæ‚èo‚µ */
	delta_y = tas & 0x0f;

	/* ‚PŒ…–Ú‚Ì”šæ‚èo‚µ */
	n = tas >> 4;
	n1 = n % 10;

	/* ‚QŒ…–Ú‚Ì”šæ‚èo‚µ */
	n2 = (n / 10) % 10;

	/* ‚RŒ…–Ú‚Ì”šæ‚èo‚µ */
	n3 = (n / 100) % 10;

	/* ‚c‚o‚R‚Q‚O`‚Q‚ÌŒvZ */
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

	/* ‚`‚R‚Q‚O‚O`‚`‚R‚Q‚Q‚Q‚Ìİ’è */
	n1 += 8;
	n2 += 8;
	n3 += 8;
	for (i = 0; i < 3; i++) {
		/* ‚P‚‹‚”Œ… */
		n1 = (n1 + 1) % 10;
		*chrtbl[0][i] = numfont5_tbl[n1];

		/* ‚P‚O‚‹‚”Œ…iƒ[ƒƒTƒvƒŒƒXj */
		n2 = (n2 + 1) % 10;
		*chrtbl[1][i] = (n2 == 0 && n < 10) ?
				FONT_T5 : numfont5_tbl[n2];

		/* ‚P‚O‚O‚‹‚”Œ…iƒ[ƒƒTƒvƒŒƒXj */
		n3 = (n3 + 1) % 10;
		*chrtbl[2][i] = (n3 == 0 && n < 100) ?
				FONT_T5 : numfont5_tbl[n3];
	}
}

/*=======================================================================
 |
 |		‚g‚c‚c‹Cˆ³‚“x@‚c^‚oŒvZ
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

	/* ƒ¢‚xæ‚èo‚µ */
	delta_y = alt & 0x07;

	/* ‚PŒ…–Ú‚Ì”šæ‚èo‚µ */
	n = alt >> 3;
	n1 = n % 10;

	/* ‚QŒ…–Ú‚Ì”šæ‚èo‚µ */
	n2 = (n / 10) % 10;

	/* ‚RŒ…–Ú‚Ì”šæ‚èo‚µ */
	n3 = (n / 100) % 10;

	/* ‚SŒ…–Ú‚Ì”šæ‚èo‚µ */
	n4 = n / 1000;

	/* ‚c‚o‚R‚R‚O`‚R‚ÌŒvZ */
	if (n < 100) {
		/* ‚P‚O‚O‚O‚†‚”–¢– */
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

		/* ‚`‚R‚R‚O‚O`‚`‚R‚R‚R‚Q‚Ìİ’è */
		n1 += 8;
		n2 += 8;
		n3 += 8;
		for (i = 0; i < 3; i++) {
			/* ‚P‚O‚†‚”Œ… */
			n1 = (n1 + 1) % 10;
			*chrtbl[0][i] = numfont5_tbl[n1];

			/* ‚P‚O‚O‚†‚”Œ…iƒ[ƒƒTƒvƒŒƒXj */
			n2 = (n2 + 1) % 10;
			*chrtbl[1][i] = (n2 == 0 && n < 10) ?
					FONT_T5 : numfont5_tbl[n2];

			/* ‚P‚O‚O‚O‚†‚”Œ…iƒ[ƒƒTƒvƒŒƒXj */
			n3 = (n3 + 1) % 10;
			*chrtbl[2][i] = (n3 == 0) ?
					FONT_T5 : numfont5_tbl[n3];

			/* ‚P‚O‚O‚O‚O‚†‚”Œ…iƒXƒy[ƒXj */
			*chrtbl[3][i] = FONT_T5;
		}
	} else {
		/* ‚P‚O‚O‚O‚†‚”ˆÈã */
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

		/* ‚`‚R‚R‚O‚O`‚`‚R‚R‚R‚Q‚Ìİ’è */
		n2 += 8;
		n3 += 8;
		n4 += 8;
		for (i = 0; i < 3; i++) {
			/* ‚P‚O‚†‚”Œ…iƒ[ƒj */
			*chrtbl[0][i] = numfont5_tbl[0];

			/* ‚P‚O‚O‚†‚”Œ… */
			n2 = (n2 + 1) % 10;
			*chrtbl[1][i] = numfont5_tbl[n2];

			/* ‚P‚O‚O‚O‚†‚”Œ… */
			n3 = (n3 + 1) % 10;
			*chrtbl[2][i] = numfont5_tbl[n3];

			/* ‚P‚O‚O‚O‚O‚†‚”Œ…iƒ[ƒƒTƒvƒŒƒXj */
			n4 = (n4 + 1) % 10;
			*chrtbl[3][i] = (n4 == 0 && n < 1000) ?
					FONT_T5 : numfont5_tbl[n4];
		}
	}
}
