/* ----- %Z%%M% %I% %E% %U% ----- */
/*
 * File		: lbmm.h
 * Comment	: macro defines
 *			lbmmg1 correspods to getch()
 *			lbmmg2 correspods to mvwgetch()
 *			lbmmg3 correspods to mvgetch()
 *
 * No.   Date      Name        Description                       Report No
 * --- --------  -------- ------------------------------------  ---------
 *  1. 88/02/18  M.Hosho  new
 *
 */

#define lbmmg1()	lbmmgc(stdscr)
#define lbmmg2(win,y,x)	(wmove(win,y,x)==ERR?ERR:lbmmgc(win))
#define lbmmg3(y,x)	lbmmg2(stdscr,y,x)

extern	int	lbmmgc();			/* Get 1 char */
