/*	FILE DESCRIPTION

		FILE NAME	: d3slmt.c
		ROUTINE		: 説明部ラベル部の保守
		REVISION	:
		REMARKS		: created	88.06.15	s.aizawa
*/

#include	<jcurses.h>
#include	"la_ws/include/d2lerr.h"
#include	"d3data.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3slmt()
		ROUTINE		: 説明部ラベル部の保守
		INPUT		: flag  = 0 説明部の保守
				:       = 1 ラベル部の保守
				: lads  = ＬＡデータセット名
				: title = タイトルメッセージ
		OUTPUT		: none
*/
d3slmt(ladir, lads, flag, title)
char	*ladir;
char	*lads;
int	flag;
char	*title;
{
	int	err;
	char	flag_s[4];

	endwin();

	sprintf(flag_s, "%d", flag);
	err = (d3exec("d0_slhn", ladir, lads, flag_s, title, 0) >> 8) & 0xff;

	if (err != 0 && err != E_E)
		d3errw(err);

	return(err);
}
