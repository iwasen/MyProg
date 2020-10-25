/************************************************************************
 *									*
 *		‚r^‚f@‚b‚o‚tƒvƒƒOƒ‰ƒ€				*
 *									*
 *		–¼Ì		: ‚g‚t‚c•\¦—p‚c^‚oŒvZˆ—		*
 *		ƒtƒ@ƒCƒ‹–¼	: dp_hud.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*=======================================================================
 |
 |		‚g‚t‚cƒsƒbƒ`@‚c^‚oŒvZ
 |
 |	void	dp_hud_pitch()
 |
 =======================================================================*/
void	dp_hud_pitch()
{
	/* ‚k‚x‚Q‚O‚V‚ÌŒvZ */
	dp_hud.LY207 = (7678 - fltdata.IPTCHA * 15 / 2) << 1;

	/* ‚k‚x‚Q‚O‚X‚ÌŒvZ */
	dp_hud.LY209 = (7678 + fltdata.IPTCHA * 15 / 2) << 1;
}

/*=======================================================================
 |
 |		‚g‚t‚cƒ[ƒ‹@‚c^‚oŒvZ
 |
 |	void	dp_hud_roll()
 |
 =======================================================================*/
void	dp_hud_roll()
{
	/* ‚q‚Q‚O‚O‚ÌŒvZ */
	dp_hud.R200 = fltdata.IROLLA << 4;
}

/*=======================================================================
 |
 |		‚g‚t‚cw¦‘å‹C‘¬“x@‚c^‚oŒvZ
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

	/* ƒ}ƒCƒiƒX‚Ìê‡‚Í‚O‚Æ‚·‚é */
	ias = fltdata.IASPDI;
	if (ias & 0x4000)
		ias = 0;

	/* ƒ¢‚xæ‚èo‚µ */
	delta_y = ias & 0x0f;

	/* ‚PŒ…–Ú‚Ì”šæ‚èo‚µ */
	n = ias >> 4;
	n1 = n % 10;

	/* ‚QŒ…–Ú‚Ì”šæ‚èo‚µ */
	n2 = (n / 10) % 10;

	/* ‚RŒ…–Ú‚Ì”šæ‚èo‚µ */
	n3 = (n / 100) % 10;

	/* ‚c‚o‚Q‚Q‚O`‚Q‚ÌŒvZ */
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

	/* ‚`‚Q‚Q‚O‚O`‚`‚Q‚Q‚Q‚Q‚Ìİ’è */
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
 |		‚g‚t‚c¥•ûˆÊ@‚c^‚oŒvZ
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

	/* ƒ¢‚xæ‚èo‚µ */
	tmp = (ulong)fltdata.IMAGHD << 16;
	delta_y = (ushort)(tmp % (2048L * 0x10000L / 180L) >> 16);

	/* ‚PŒ…–Ú‚Ì”šæ‚èo‚µ */
	n = (ushort)(tmp / (2048L * 0x10000L / 180L));
	n1 = n % 10;

	/* ‚QŒ…–Ú‚Ì”šæ‚èo‚µ */
	n2 = (n / 10) % 10;

	/* ‚RŒ…–Ú‚Ì”šæ‚èo‚µ */
	n3 = n / 100;

	/* ‚c‚o‚Q‚R‚O`‚Q‚ÌŒvZ */
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

	/* ‚`‚Q‚R‚O‚O`‚`‚Q‚R‚Q‚Q‚Ìİ’è */
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
 |		‚g‚t‚c‹Cˆ³‚“x@‚c^‚oŒvZ
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

	/* ‚c‚o‚Q‚S‚O`‚R‚ÌŒvZ */
	if (n < 100) {
		/* ‚P‚O‚O‚O‚†‚”–¢– */
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

		/* ‚`‚Q‚S‚O‚O`‚`‚Q‚S‚R‚Q‚Ìİ’è */
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
			dp_hud.DP242 = (k * 6 / 4 + 1000) << 1;
			if (n3 == 9)
				dp_hud.DP243 = dp_hud.DP242;
			else
				dp_hud.DP243 = 1000 << 1;
		} else
			dp_hud.DP243 = dp_hud.DP242 = 1000 << 1;
		dp_hud.DP241 = dp_hud.DP240 = (delta_y * 6 / 4 + 1000) << 1;

		/* ‚`‚Q‚S‚O‚O`‚`‚Q‚S‚R‚Q‚Ìİ’è */
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

/*=======================================================================
 |
 |		‚g‚t‚cƒxƒƒVƒeƒB[ƒxƒNƒ^@‚c^‚oŒvZ
 |
 |	void	dp_hud_vvc()
 |
 =======================================================================*/
void	dp_hud_vvc()
{
	/* ‚o‚w‚Q‚O‚U‚ÌŒvZ */
	dp_hud.PX206 = (fltdata.OHVVCX * 5 / 2) << 1;

	/* ‚o‚x‚Q‚O‚U‚ÌŒvZ */
	dp_hud.PY206 = (fltdata.OHVVCY * 5 / 2) << 1;
}
