#include <graph.h>

typedef	unsigned short	ushort;
typedef	unsigned long	ulong;
typedef	unsigned short	word;
struct	{
	short	IMAGHD;
	short	IHBALT;
} fltdata;
struct	{
	word	DP240;
	word	DP241;
	word	DP242;
	word	DP243;
	word	A2400;
	word	A2401;
	word	A2402;
	word	A2410;
	word	A2411;
	word	A2412;
	word	A2420;
	word	A2421;
	word	A2422;
	word	A2430;
	word	A2431;
	word	A2432;
} dp_hud;
main()
{
	int	loop_sw;

	_setvideomode(_98RESS8COLOR);
	fltdata.IHBALT = 0;
	loop_sw = 1;
	while (loop_sw) {
		switch (getch()) {
		case '1':
			fltdata.IHBALT = ++fltdata.IHBALT;
			break;
		case '2':
			fltdata.IHBALT = --fltdata.IHBALT;
			break;
		case ' ':
			scanf("%d", &fltdata.IHBALT);
			break;
		case 0x1b:
			loop_sw = 0;
			break;
		}
		_clearscreen(_GCLEARSCREEN);
		dram();
		printf("%d%d%d%d\n", dp_hud.A2430, dp_hud.A2420, dp_hud.A2410, dp_hud.A2400);
		printf("%d%d%d%d\n", dp_hud.A2431, dp_hud.A2421, dp_hud.A2411, dp_hud.A2401);
		printf("%d%d%d%d\n", dp_hud.A2432, dp_hud.A2422, dp_hud.A2412, dp_hud.A2402);
		_rectangle(_GFILLINTERIOR, 100, 400-dp_hud.DP243/10, 116, 400-(dp_hud.DP243/10+16));
		_rectangle(_GFILLINTERIOR, 120, 400-dp_hud.DP242/10, 136, 400-(dp_hud.DP242/10+16));
		_rectangle(_GFILLINTERIOR, 140, 400-dp_hud.DP241/10, 156, 400-(dp_hud.DP241/10+16));
		_rectangle(_GFILLINTERIOR, 160, 400-dp_hud.DP240/10, 176, 400-(dp_hud.DP240/10+16));
	}
	_setvideomode(_98TEXT80);	/* ƒeƒLƒXƒgƒ‚[ƒhƒZƒbƒg */
}

dram()
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
		n1 += 9;
		n2 += 9;
		n3 += 9;
		for (i = 0; i < 3; i++) {
			*chrtbl[0][i] = n1++ % 10;
			*chrtbl[1][i] = n2++ % 10;
			*chrtbl[2][i] = n3++ % 10;
			*chrtbl[3][i] = 0;
/*
			*chrtbl[0][i] = numfont5_tbl[n1++ % 10];
			*chrtbl[1][i] = zsnumfont5_tbl[n2++ % 10];
			*chrtbl[2][i] = zsnumfont5_tbl[n3++ % 10];
			*chrtbl[3][i] = zsnumfont5_tbl[0];
*/
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
		n2 += 9;
		n3 += 9;
		n4 += 9;
		for (i = 0; i < 3; i++) {
			*chrtbl[0][i] = 0;
			*chrtbl[1][i] = n2++ % 10;
			*chrtbl[2][i] = n3++ % 10;
			*chrtbl[3][i] = n4++ % 10;
/*
			*chrtbl[0][i] = numfont5_tbl[0];
			*chrtbl[1][i] = zsnumfont5_tbl[n2++ % 10];
			*chrtbl[2][i] = zsnumfont5_tbl[n3++ % 10];
			*chrtbl[3][i] = zsnumfont5_tbl[n4++ % 10];
*/
		}
	}
}
