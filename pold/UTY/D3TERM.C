/*	FILE DESCRIPTION

		FILE NAME	: d3term.c
		ROUTINE		: âÔòbå^èIóπèàóù
		REVISION	:
		REMARKS		:	88.05.16	S.Aizawa
*/

#include	<jcurses.h>

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3term()
		INPUT		: none
		OUTPUT		: none
*/

d3term()
{
	clear();		/* clear screen */
	slk_clear();		/* clear soft label key */
	refresh();
	endwin();		/* curses mode end */
	resetterm();		/* reset terminal */
}
