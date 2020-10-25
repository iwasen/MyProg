/************************************************************************
 *									*
 *		‚r^‚f@‚b‚o‚tƒvƒƒOƒ‰ƒ€				*
 *									*
 *		–¼Ì		: ‚`‚h•\¦—p‚c^‚oŒvZˆ—		*
 *		ƒtƒ@ƒCƒ‹–¼	: dp_ai.c				*
 *									*
 ************************************************************************/
#include "sg_cpu.h"

/*=======================================================================
 |
 |		‚`‚hƒsƒbƒ`@‚c^‚oŒvZ
 |
 |	void	dp_ai_pitch()
 |
 =======================================================================*/
void	dp_ai_pitch()
{
	short	tmp;

	/* ‚k‚x‚P‚O‚X‚a‚ÌŒvZ */
	dp_ai.LY109B = (ushort)(2314L - fltdata.IPTCHA * 289L / 128L) << 1;

	/* ‚o‚x‚P‚O‚X‚`‚ÌŒvZ */
	tmp = (short)(fltdata.IPTCHA * 289L / 128L + 72L);
	if (tmp < -606)		/* ‰ºŒÀ’l•â³ */
		tmp = -606;
	else if (tmp > 678)	/* ãŒÀ’l•â³ */
		tmp = 678;
	dp_ai.PY109A = tmp << 1;
}

/*=======================================================================
 |
 |		‚`‚hƒ[ƒ‹@‚c^‚oŒvZ
 |
 |	void	dp_ai_roll()
 |
 =======================================================================*/
void	dp_ai_roll()
{
	/* ‚q‚P‚O‚O‚ÌŒvZ */
	dp_ai.R100 = fltdata.IROLLA << 4;
}

/*=======================================================================
 |
 |		‚`‚h¥‹C•ûˆÊ@‚c^‚oŒvZ
 |
 |	void	dp_ai_mhdg()
 |
 =======================================================================*/
void	dp_ai_mhdg()
{
	static	word	*chrtbl[5][2] = {
		{&dp_ai.A1740, &dp_ai.A1741},
		{&dp_ai.A1730, &dp_ai.A1731},
		{&dp_ai.A1720, &dp_ai.A1721},
		{&dp_ai.A1710, &dp_ai.A1711},
		{&dp_ai.A1700, &dp_ai.A1701}
	};
	ushort	delta_x;
	short	deg, i, j, n;
	ulong	tmp;

	/* ‚o‚w‚P‚O‚V‚ÌŒvZ */
	tmp = (ulong)fltdata.IMAGHD << 16;
	delta_x = (ushort)((tmp % (20480L * 0x10000L / 180L)) >> 16);
	dp_ai.PX107 = (-(delta_x * 241 / 32 + 857)) << 1;

	/* ƒXƒP[ƒ‹–Ú·‚è‚Ìİ’è */
	deg = (short)(tmp / (20480L * 0x10000L / 180L)); /* “x”‚ÌŒvZ */
	if (--deg < 0)			/* |‚P‚OK‚©‚ç•\¦ */
		deg += 36;		/* •‰‚É‚È‚Á‚½‚Ì•â³ */
	for (i = 0; i < 5; i++) {
		if (deg >= 36)
			deg -= 36;	/* ‚R‚U‚OK‚ğ‰z‚¦‚½‚Ì•â³ */
		/* ”š‚ÌƒtƒHƒ“ƒgƒAƒhƒŒƒXƒZƒbƒg */
		for (j = 0, n = deg; j < 2; j++) {
			*chrtbl[i][j] = numfont5_tbl[n % 10];
			n /= 10;
		}
		deg++;			/* {‚P‚OK */
	}
}

/*=======================================================================
 |
 |		‚`‚h^‘å‹C‘¬“x@‚c^‚oŒvZ
 |
 |	void	dp_ai_tas()
 |
 =======================================================================*/
void	dp_ai_tas()
{
	static	word	*chrtbl[5][3] = {
		{&dp_ai.A1600, &dp_ai.A1601, &dp_ai.A1602},
		{&dp_ai.A1610, &dp_ai.A1611, &dp_ai.A1612},
		{&dp_ai.A1620, &dp_ai.A1621, &dp_ai.A1622},
		{&dp_ai.A1630, &dp_ai.A1631, &dp_ai.A1632},
		{&dp_ai.A1640, &dp_ai.A1641, &dp_ai.A1642}
	};
	short	delta_y, n, knot, i, j;

	/* ‚o‚x‚P‚O‚U‚ÌŒvZ */
	n = fltdata.IASPDT / 400;	/* ‚S‚O‚O‚T‚O‚‹‚” */
	delta_y = fltdata.IASPDT - (400 * n);	/* ƒ¢‚x‚ÌŒvZ */
	if (n == 0) {
		/* ‚T‚O‚‹‚”–¢– */
		n++;
		delta_y -= 400;
	}
	dp_ai.PY106 = (-((short)(delta_y * 137L / 64L) + 785)) << 1;

	/* ƒXƒP[ƒ‹–Ú·‚è‚Ìİ’è */
	knot = (n - 1) * 50;		/* |‚T‚O‚‹‚”‚©‚ç‚Ì•\¦ */
	for (i = 0; i < 5; i++) {
		/* ”š‚ÌƒtƒHƒ“ƒgƒAƒhƒŒƒXƒZƒbƒg */
		for (j = 0, n = knot; j < 3; j++) {
			*chrtbl[i][j] = numfont5_tbl[n % 10];
			n /= 10;
		}
		knot += 50;		/* {‚T‚O‚‹‚” */
	}
}

/*=======================================================================
 |
 |		‚`‚h‹Cˆ³‚“x@‚c^‚oŒvZ
 |
 |	void	dp_ai_alt()
 |
 =======================================================================*/
void	dp_ai_alt()
{
	static	word	*chrtbl[5][4] = {
		{&dp_ai.A1800, &dp_ai.A1801, &dp_ai.A1802, &dp_ai.A1803},
		{&dp_ai.A1810, &dp_ai.A1811, &dp_ai.A1812, &dp_ai.A1813},
		{&dp_ai.A1820, &dp_ai.A1821, &dp_ai.A1822, &dp_ai.A1823},
		{&dp_ai.A1830, &dp_ai.A1831, &dp_ai.A1832, &dp_ai.A1833},
		{&dp_ai.A1840, &dp_ai.A1841, &dp_ai.A1842, &dp_ai.A1843}
	};
	short	delta_y, n, feet, i, j;

	/* ‚o‚x‚P‚O‚W‚ÌŒvZ */
	if (fltdata.IHBALT >= 800) {		/* ‚W‚O‚O‚P‚O‚O‚O‚†‚” */
		/* ‚P‚O‚O‚O‚†‚”ˆÈã‚Ì */
		n = fltdata.IHBALT / 80;	/* ‚W‚O‚P‚O‚O‚†‚” */
		delta_y = fltdata.IHBALT - (80 * n); /* ƒ¢‚x‚ÌŒvZ */
		dp_ai.PY108 = (-(delta_y * 171 / 16 + 785)) << 1;
		feet = n * 10;
	} else {
		/* ‚P‚O‚O‚O‚†‚”–¢–‚Ì */
		n = fltdata.IHBALT / 8;		/* ‚W‚P‚O‚†‚” */
		delta_y = fltdata.IHBALT - (8 * n); /* ƒ¢‚x‚ÌŒvZ */
		if (n == 0) {
			/* ‚P‚O‚†‚”–¢– */
			n++;
			delta_y -= 8;
		}
		dp_ai.PY108 = (-(delta_y * 107 + 785)) << 1;
		feet = n;
	}

	/* ƒXƒP[ƒ‹–Ú·‚è‚Ìİ’è */
	if (feet > 100)
		feet -= 10;		/* |‚P‚O‚O‚†‚”‚©‚ç•\¦ */
	else
		feet -= 1;		/* |‚P‚O‚†‚”‚©‚ç•\¦ */
	for (i = 0; i < 5; i++) {
		/* ”š‚ÌƒtƒHƒ“ƒgƒAƒhƒŒƒXƒZƒbƒg */
		for (j = 0, n = feet; j < 4; j++) {
			*chrtbl[i][j] = numfont5_tbl[n % 10];
			n /= 10;
		}

		if (feet >= 100)
			feet += 10;	/* {‚P‚O‚O‚†‚” */
		else
			feet += 1;	/* {‚P‚O‚†‚” */
	}
}

/*=======================================================================
 |
 |		‚`‚hƒ[ƒ‹ƒXƒP[ƒ‹@‚c^‚oŒvZ
 |
 |	void	dp_ai_rollscale()
 |
 =======================================================================*/
void	dp_ai_rollscale()
{
	dp_ai.R104B = 0;
	dp_ai.R104C = (word)(30L * 32768L / 180L) << 1;
	dp_ai.R104D = (word)(60L * 32768L / 180L) << 1;
	dp_ai.R104E = (word)(300L * 32768L / 180L) << 1;
	dp_ai.R104F = (word)(330L * 32768L / 180L) << 1;
}
