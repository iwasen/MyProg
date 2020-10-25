/************************************************************************
 *									*
 *		ƒOƒ‰ƒtƒBƒbƒNƒEƒBƒ“ƒhƒEƒ‰ƒCƒuƒ‰ƒŠ			*
 *			ƒTƒ“ƒvƒ‹ƒvƒƒOƒ‰ƒ€				*
 *									*
 ************************************************************************/

/*--------------------------------------------------------------*/
/*	•W€ƒCƒ“ƒNƒ‹[ƒhƒtƒ@ƒCƒ‹ 				*/
/*--------------------------------------------------------------*/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dos.h>

/*--------------------------------------------------------------*/
/*	ƒOƒ‰ƒtƒBƒbƒNƒEƒBƒ“ƒhƒEƒ‰ƒCƒuƒ‰ƒŠ—pƒCƒ“ƒNƒ‹[ƒhƒtƒ@ƒCƒ‹	*/
/*	•K‚¸ƒCƒ“ƒNƒ‹[ƒh‚·‚é‚±‚Æ				*/
/*--------------------------------------------------------------*/
#include "gwlib.h"

/*--------------------------------------------------------------*/
/*	ƒ‚[ƒh‚Ì’è‹`		 				*/
/*--------------------------------------------------------------*/
#define	M_MAKE	0
#define	M_DEL	1
#define	M_PICK	2
#define	M_MOVE	3
#define	M_END	6

/*--------------------------------------------------------------*/
/*	“à•”ŠÖ”‚Ì’è‹`		 				*/
/*--------------------------------------------------------------*/
static	void	menu(void);
static	void	title(void);
static	void	make(int);
static	int	set_color_c(void);
static	int	set_color_w(void);

/*--------------------------------------------------------------*/
/*	ƒEƒBƒ“ƒhƒE“à‚É•\Ž¦‚·‚éƒf[ƒ^ 				*/
/*--------------------------------------------------------------*/
static	char	*mstr[] = {
	"1 MSDOS.SYS",
	"2 IO.SYS",
	"3 COMMAND.COM",
	"4 CONFIG.SYS",
	"5 AUTOEXEC.BAT",
	"6 FORMAT.EXE",
	"7 ASSIGN.EXE",
	"8 DISKCOPY.EXE",
};

/*--------------------------------------------------------------*/
/*	ƒXƒ^ƒeƒBƒbƒN•Ï”‚Ì’è‹`	 				*/
/*--------------------------------------------------------------*/
static	int	mode;			/* Œ»Ý‚Ìƒ‚[ƒh */
static	int	color_w = C_WHITE;	/* •¶ŽšF */
static	int	color_c = C_BLACK;	/* ”wŒiF */

/*=======================================================================
 *
 *		ƒƒCƒ“ˆ—
 *
 *	void	main()
 *
 =======================================================================*/
main(int argc, char *argv[])
{
	void	menu(), make();
	WINDOW	*wp;
	int	loop_sw, mouse;
	int	n = 0;

	/* ƒpƒ‰ƒ[ƒ^ƒ`ƒFƒbƒN */
	switch (argc) {
	case 1:
		mouse = 0;
		break;
	case 2:
		if (strcmp(argv[1], "1") == 0)
			mouse = 1;
		else {
			printf("ƒpƒ‰ƒ[ƒ^‚ªˆá‚¢‚Ü‚·D\n");
			exit(1);
		}
		break;
	default:
		printf("ƒpƒ‰ƒ[ƒ^‚Ì”‚ªˆá‚¢‚Ü‚·D\n");
		exit(1);
	}

	w_initscr(8, 16, mouse, "ANK.FNT");	/* ‰Šú‰»ˆ— */
/*                †E   †E    †E        †E
                  †E   †E    †E        †W†C†C†C ‚`‚m‚jƒtƒHƒ“ƒgƒtƒ@ƒCƒ‹–¼
                  †E   †E    †W†C†C†C†C†C†C†C†C†C†C†C†C ƒ}ƒEƒXƒhƒ‰ƒCƒoŽí•Ê
                  †E   †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C ‚Ps‚Ìƒhƒbƒg”
                  †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C ‚PŒ…‚Ìƒhƒbƒg”
*/
	title();		/* ƒ^ƒCƒgƒ‹•\Ž¦ */

	loop_sw = 1;
	while (loop_sw) {
		switch (w_input(scrp, 0)) {	/* ‚P•¶Žš“ü—Í */
/*                               †E    †E
                                 †E    †W†C†C†C†C •¶ŽšƒJ[ƒ\ƒ‹•\Ž¦‚È‚µ
                                 †W†C†C†C†C†C†C†C†C†C ‘S‰æ–Êƒ|ƒCƒ“ƒ^
*/
		case MOUSE_L:
			switch (mode) {
			case M_MAKE:
				make(++n);
				break;
			case M_DEL:
				if ((wp = w_check()) != 0)
					w_close(wp);
				break;
			case M_PICK:
				if ((wp = w_check()) != 0)
					w_popup(wp);
				break;
			case M_MOVE:
				if ((wp = w_check()) != 0)
					w_xmove(wp);
				break;
			}
			break;
		case MOUSE_R:
			menu();
			if (mode == M_END)
				loop_sw = 0;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		}
	}

	w_endscr();		/* I—¹ˆ— */

	return(0);
}
/*=======================================================================
 *
 *		ƒ^ƒCƒgƒ‹•\Ž¦
 *
 *	void	title()
 *
 =======================================================================*/
static	void	title(void)
{

	static	char	tile[12] = {
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa,
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa
	};

	g_line(0, 0, 639, 399, 0, 2, 2, 0, 12, tile);

	set_color(C_BLUE, C_WHITE);	/* FÝ’è */
/*                  †E        †E
                    †E        †W†C†C†C ”wŒiFi”’j
                    †W†C†C†C†C†C†C†C†C†C†C†C†C •¶ŽšFiÂj
*/
	/* •¶Žš—ñ•\Ž¦ */
	w_dspstr(scrp, 18, 0, "*** GRAPHIC WINDOW LIBRARY DEMONSTRATION ***");
/*                †E     †E  †E     †E
                  †E     †E  †E     †W†C†C†C •\Ž¦•¶Žš—ñ
                  †E     †E  †W†C†C†C†C†C†C†C†C†C sˆÊ’u
                  †E     †W†C†C†C†C†C†C†C†C†C†C†C†C Œ…ˆÊ’u
                  †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C ‘S‰æ–Êƒ|ƒCƒ“ƒ^
*/
}

/*=======================================================================
 *
 *		ƒƒjƒ…[ˆ—
 *
 *	void	menu()
 *
 =======================================================================*/
static	void	menu(void)
{
	static	char	title[] = "ƒƒjƒ…[";
	static	char	*item[] = {
		"ì    ¬",		/* 0 */
		"Á    ‹Ž",		/* 1 */
		"ˆø‚«ã‚°",		/* 2 */
		"ˆÚ    “®",		/* 3 */
		"•¶ Žš F",		/* 4 */
		"”w Œi F",		/* 5 */
		"I    —¹"		/* 6 */
	};
	WINDOW	*wp;
	static	int	sel = 0;
	int	csel;
	int	loop_sw;
	int	sx, sy;

	sx = m_gx - 13 * 8 / 2;
	sy = m_gy - 10 * 18 / 2;

	/* ƒEƒBƒ“ƒhƒEƒI[ƒvƒ“ */
	if ((wp = w_open(sx, sy, 13, 9, 8, 18)) == 0) {
/*                        †E   †E   †E  †E  †E   †E
                          †E   †E   †E  †E  †E   †W†C†C†C ‚Ps‚Ìƒhƒbƒg”
                          †E   †E   †E  †E  †W†C†C†C†C†C†C†C ‚PŒ…‚Ìƒhƒbƒg”
                          †E   †E   †E  †W†C†C†C†C†C†C†C†C†C†C s”
                          †E   †E   †W†C†C†C†C†C†C†C†C†C†C†C†C†C Œ…”
                          †E   †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C ƒEƒBƒ“ƒhƒE¶ã‚Ì‚xÀ•W
                          †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C ƒEƒBƒ“ƒhƒE¶ã‚Ì‚wÀ•W
*/
		mode = M_DEL;
		return;
	}

	/* ƒEƒBƒ“ƒhƒE•\Ž¦ */
	w_stdwin(wp, title, C_BLACK, C_YELLOW, C_BLACK);
/*                †E    †E      †E        †E         †E
                  †E    †E      †E        †E         †W†C†C†C ‰e‚ÌFi•j
                  †E    †E      †E        †W†C†C†C†C†C†C†C†C†C†C†C†C†C ”wŒiFi‰©j
                  †E    †E      †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C •¶ŽšFi•j
                  †E    †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C ƒ^ƒCƒgƒ‹
                  †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C ƒEƒBƒ“ƒhƒEƒ|ƒCƒ“ƒ^
*/
	loop_sw = 1;
	while (loop_sw) {
		/* ˆ—‘I‘ð */
		csel = w_select(wp, item, 1, 7, sel, C_BLACK, C_BLUE);
/*                              †E    †E    †E  †E   †E     †E        †E
                                †E    †E    †E  †E   †E     †E        †W†C†C†C ‘I‘ðŽž‚ÌF
                                †E    †E    †E  †E   †E     †W†C†C†C •¶ŽšF
                                †E    †E    †E  †E   †W†C†C†C Å‰‚É‘I‘ð‚³‚ê‚Ä‚¢‚é€–Ú
                                †E    †E    †E  †W†C†C†C†C†C†C†C c•ûŒü‚Ì€–Ú”
                                †E    †E    †W†C†C†C†C†C†C†C†C†C†C ‰¡•ûŒü‚Ì€–Ú”
                                †E    †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C €–Ú•¶Žš—ñ
                                †W†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C†C ƒEƒBƒ“ƒhƒEƒ|ƒCƒ“ƒ^
*/
		switch (csel) {       
		case 0:		/* ì¬ */
		case 1:		/* Á‹Ž */
		case 2:		/* ˆø‚«ã‚° */
		case 3:		/* ˆÚ“® */
		case 6:		/* I—¹ */
			mode = csel;
			loop_sw = 0;
			break;
		case 4:		/* •¶ŽšF */
			if (set_color_c() != -1)
				loop_sw = 0;
			break;
		case 5:		/* ”wŒiF */
			if (set_color_w() != -1)
				loop_sw = 0;
			break;
		case -1:
			loop_sw = 0;
			break;
		}

		if (csel != -1)
			sel = csel;
	}

	w_close(wp);	/* ƒEƒBƒ“ƒhƒEƒNƒ[ƒY */
/*               †E
                 †W†C†C†C ƒEƒBƒ“ƒhƒEƒ|ƒCƒ“ƒ^
*/
}

/*=======================================================================
 *
 *		ƒEƒBƒ“ƒhƒEì¬
 *
 *	void	make()
 *
 =======================================================================*/
static	void	make(int n)
{
	int	i;
	WINDOW	*wp;
	char	title[20];

	/* ƒEƒBƒ“ƒhƒEƒI[ƒvƒ“ */
	if ((wp = w_open(m_gx, m_gy, 20, 10, 8, 18)) == 0)
		return;

	/* ƒEƒBƒ“ƒhƒE•\Ž¦ */
	sprintf(title, "WINDOW%d", n);
	w_stdwin(wp, title, C_BLACK, color_w, C_BLACK);

	/* ƒEƒBƒ“ƒhƒE“à‚É•¶Žš•\Ž¦ */
	set_color(color_c, color_w);
	for (i = 0; i < 8; i++)
		w_dspstr(wp, 2, i+1, mstr[i]);
}

/*=======================================================================
 *
 *		•¶ŽšFÝ’è
 *
 *	int	set_color_c()
 *
 =======================================================================*/
static	int	set_color_c(void)
{
	static	char	title[] = "•¶ŽšF";
	static	char	*item[] = {
		"”’",
		"‰©",
		"ƒVƒAƒ“",
		"—Î",
		"ƒ}ƒ[ƒ“ƒ^",
		"Ô",
		"Â",
		"•"
	};
	static	int	sel = 0;
	int	csel;
	WINDOW	*wp;

	/* •¶ŽšF‘I‘ðƒEƒBƒ“ƒhƒEƒI[ƒvƒ“ */
	if ((wp = w_open(m_gx, m_gy, 13, 10, 8, 18)) == 0)
		return(-1);

	/* ƒEƒBƒ“ƒhƒE•\Ž¦ */
	w_stdwin(wp, title, C_WHITE, C_MAGENTA, C_BLACK);

	/* •¶ŽšF‘I‘ð */
	csel = w_select(wp, item, 1, 8, sel, C_WHITE, C_CYAN);
	switch (csel) {
	case 0:		/* ”’ */
		color_c = C_WHITE;
		break;
	case 1:		/* ‰© */
		color_c = C_YELLOW;
		break;
	case 2:		/* ƒVƒAƒ“ */
		color_c = C_CYAN;
		break;
	case 3:		/* —Î */
		color_c = C_GREEN;
		break;
	case 4:		/* ƒ}ƒ[ƒ“ƒ^ */
		color_c = C_MAGENTA;
		break;
	case 5:		/* Ô */
		color_c = C_RED;
		break;
	case 6:		/* Â */
		color_c = C_BLUE;
		break;
	case 7:		/* • */
		color_c = C_BLACK;
		break;
	}

	if (csel != -1)
		sel = csel;

	w_close(wp);	/* ƒEƒBƒ“ƒhƒEƒNƒ[ƒY */

	return(csel);
}

/*=======================================================================
 *
 *		”wŒiFÝ’è
 *
 *	int	set_color_w()
 *
 =======================================================================*/
static	int	set_color_w(void)
{
	static	char	title[] = "”wŒiF";
	static	char	*item[] = {
		"”’",
		"‰©",
		"ƒVƒAƒ“",
		"—Î",
		"ƒ}ƒ[ƒ“ƒ^",
		"Ô",
		"Â",
		"•"
	};
	static	int	sel = 0;
	int	csel;
	WINDOW	*wp;

	/* ”wŒiF‘I‘ðƒEƒBƒ“ƒhƒEƒI[ƒvƒ“ */
	if ((wp = w_open(m_gx, m_gy, 13, 10, 8, 18)) == 0)
		return(-1);

	/* ƒEƒBƒ“ƒhƒE•\Ž¦ */
	w_stdwin(wp, title, C_BLACK, C_CYAN, C_BLACK);

	/* ”wŒiF‘I‘ð */
	csel = w_select(wp, item, 1, 8, sel, C_BLACK, C_MAGENTA);
	switch (csel) {
	case 0:		/* ”’ */
		color_w = C_WHITE;
		break;
	case 1:		/* ‰© */
		color_w = C_YELLOW;
		break;
	case 2:		/* ƒVƒAƒ“ */
		color_w = C_CYAN;
		break;
	case 3:		/* —Î */
		color_w = C_GREEN;
		break;
	case 4:		/* ƒ}ƒ[ƒ“ƒ^ */
		color_w = C_MAGENTA;
		break;
	case 5:		/* Ô */
		color_w = C_RED;
		break;
	case 6:		/* Â */
		color_w = C_BLUE;
		break;
	case 7:		/* • */
		color_w = C_BLACK;
		break;
	}

	if (csel != -1)
		sel = csel;

	w_close(wp);	/* ƒEƒBƒ“ƒhƒEƒNƒ[ƒY */

	return(csel);
}
