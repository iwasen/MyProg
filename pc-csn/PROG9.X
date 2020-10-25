/************************************************************************
 *									*
 *		V‚u‚r‚dƒVƒXƒeƒ€@‚o‚b|‚b‚m‚rƒvƒƒOƒ‰ƒ€		*
 *									*
 *		–¼Ì		: ‚c‚r‚oƒƒbƒZ[ƒWóM‰æ–Êi‚P‚U•\¦j	*
 *		ƒtƒ@ƒCƒ‹–¼	: prog9.c				*
 *		ì¬“ú		: ??/??/??				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

#define	ACT_UIB	0
#define	ACT_AUB	1
#define	ACT_BUS	2
#define	SBY_BUS	3
#define	ERR_BUS	4
#define	FRE_BUS	5

struct	dspmsg	{
	struct	{
		int	mce;	/* ‚l‚b‚d */
		int	vpe[2];	/* ‚u‚o‚d */
		int	uib;	/* ‚t‚h‚a */
		int	aub;	/* ‚`‚t‚a */
	} kei[2];
	int	iie[2];		/* ‚h‚h‚d */
};
typedef	struct	dspmsg	DSPMSG;

static	DSPMSG	dspmsg[16] = {
	{
		{
			{1, {1, 1}, 1, 1},
			{2, {3, 2}, 3, 2}
		},
		{1, 1}
	},
	{
		{
			{1, {1, 1}, 1, 1},
			{2, {2, 2}, 3, 2}
		},
		{1, 1}
	},
	{
		{
			{1, {1, 1}, 1, 1},
			{2, {2, 2}, 3, 2}
		},
		{1, 1}
	},
	{
		{
			{1, {1, 1}, 1, 1},
			{2, {2, 2}, 3, 2}
		},
		{1, 1}
	}
};

static	void	dsp_16(void);
static	void	output(void);
extern	void	dsp_eqm(int, int, int);
extern	void	dsp_bus(int, int, int, int);
extern	void	dsp_line(int, int, int, int, int);

/*=======================================================================
 |
 |		‚c‚r‚oƒƒbƒZ[ƒWóM‰æ–Êi‚P‚U•\¦jˆ—
 |
 |	void	prog9()
 |
 =======================================================================*/
void	prog9()
{
	static	LINE	line[] = {
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 13, 79, 13},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	static	SCRDAT	scr[] = {	/* ‰æ–Ê•\¦ƒf[ƒ^ */
		{ 2,  3, "U-NO   0   1   2   3   4   5   6   7   8   9  10  11  12  13  14  15"},
		{75,  4, "UIB"},
		{ 2,  5, "‚OŒn"},
		{73,  5, "MCE"},
		{73,  6, "VPE"},
		{75,  7, "AUB"},
		{73,  8, "IIE"},
		{75,  9, "AUB"},
		{73, 10, "VPE"},
		{ 2, 11, "‚PŒn"},
		{73, 11, "MCE"},
		{75, 12, "UIB"},
		{ 2, 14, "•\¦‚ÌITEMAŒrüAF‚Ìà–¾"},
		{ 8, 15, "FŒ»İ‰^—p’†(ACT)‚ÌŒn(—Î)"},
		{44, 15, "F‚`‚b‚sŒnƒ†ƒjƒbƒgŠÔƒoƒX(UIB)(—Î)"},
		{ 8, 16, "F‘Ò‹@’†(SBY)‚ÌŒn    (”’)"},
		{44, 16, "F‚`‚b‚sŒn‰¹ºƒoƒX(AUB)      (—Î)"},
		{ 8, 17, "FáŠQ‚Ì—L‚éŒn       (Ô)"},
		{44, 17, "F‚r‚a‚xŒn‚t‚h‚aor‚`‚t‚a     (”’)"},
		{ 8, 18, "F–¢À‘•             (Â)"},
		{44, 18, "FáŠQ‚Ì—L‚é‚t‚h‚aor‚`‚t‚a   (Ô)"},
		{44, 19, "F–¢À‘•‚t‚h‚aor‚`‚t‚a       (Â)"},
		{0, 0, 0}
	};
	int	ch;

	/* ‰æ–ÊÁ‹ */
	t_cls();
	g_cls();

	/* Œrü•\¦ */
	dspline(line, C_WAKU);

	/* ‰æ–Ê•\¦ */
	xprintf(2, 1, C_ONLINE, msg20, ssg.nsg.sys_name, msg900);
	dspscr(scr, C_GUIDE);
	dspstr(6, 15, "¡", C_GREEN);
	dspstr(6, 16, " ", C_WHITE);
	dspstr(6, 17, "š", C_RED);
	dspstr(6, 18, "ëÑ", C_BLUE);
	dsp_line(37, 15, 43, 15, ACT_UIB);
	dsp_line(37, 16, 43, 16, ACT_AUB);
	dsp_line(37, 17, 43, 17, SBY_BUS);
	dsp_line(37, 18, 43, 18, ERR_BUS);
	dsp_line(37, 19, 43, 19, FRE_BUS);
	dspstr(2, 22, msg910, C_ONLINE);
	dspstr(31, 22, msg920, C_GUIDE);
	dsp_msgenv();

	dsp_16();

	/* ƒtƒ@ƒ“ƒNƒVƒ‡ƒ“ƒL[İ’è */
	setfunc(FUNC3);

	while (prog == 9) {
		ch = getchr();
		switch (ch) {
		case KEY_CR:
		case KEY_ESC:
			prog = 7;
			break;
		case KEY_F3:
			output();
			break;
		}
	}
}

static	void	dsp_16()
{
	int	i, x, type;

	x = 8;
	for (i = 0; i < 16; i++) {
		/* ‚l‚b‚d•\¦ */
		dsp_eqm(dspmsg[i].kei[0].mce, x, 5);
		dsp_eqm(dspmsg[i].kei[1].mce, x, 11);

		/* ‚u‚o‚d•\¦ */
		dsp_eqm(dspmsg[i].kei[0].vpe[0], x, 6);
		dsp_eqm(dspmsg[i].kei[0].vpe[1], x+2, 6);
		dsp_eqm(dspmsg[i].kei[1].vpe[0], x, 10);
		dsp_eqm(dspmsg[i].kei[1].vpe[1], x+2, 10);

		/* ‚h‚h‚d•\¦ */
		dsp_eqm(dspmsg[i].iie[0], x, 8);
		dsp_eqm(dspmsg[i].iie[1], x+2, 8);

		/* ‚t‚h‚a•\¦ */
		dsp_bus(dspmsg[i].kei[0].uib, 0, x, 4);
		dsp_bus(dspmsg[i].kei[1].uib, 0, x, 12);

		/* ‚`‚t‚a•\¦ */
		dsp_bus(dspmsg[i].kei[0].aub, 1, x, 7);
		dsp_bus(dspmsg[i].kei[1].aub, 1, x, 9);

		if (dspmsg[i].kei[0].uib == 0)
			type = FRE_BUS;
		else if (dspmsg[i].kei[0].uib == 1)
			type = ACT_BUS;
		else
			type = SBY_BUS;
		dsp_line(x+1, 4, x+1, 8, type);
		dsp_line(x+1, 5, x+3, 5, type);
		dsp_line(x+3, 5, x+3, 8, type);

		if (dspmsg[i].kei[1].uib == 0)
			type = FRE_BUS;
		else if (dspmsg[i].kei[1].uib == 1)
			type = ACT_BUS;
		else
			type = SBY_BUS;
		dsp_line(x+1, 12, x+1, 8, type);
		dsp_line(x+1, 11, x+3, 11, type);
		dsp_line(x+3, 11, x+3, 8, type);

		x += 4;
	}
}

static	void	output()
{
	if (ssg.crm.prn)
		hcopy(2, 3, 76, 10);
}
