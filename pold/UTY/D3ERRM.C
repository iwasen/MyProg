/************************************************************
 *	FILE DESCRIPTION
 *		エラーメッセージを出力・キー入力待ち
 *	FILE	:	d3errm.c
 *		:	d3errm.c
 *	REMARKS	:	1988-05-06	M.MOTOKI
 *
 ************************************************************/
#include <jcurses.h>
#include "la_ws/include/d2lerr.h"
#include "la_ws/include/d3libc.h"
#include "functn.h"

#define	MREFRESH	prefresh(w1, 0, 0, l_num-2, 30, l_num-2, 79)

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/****************************************************
 *	エラーメッセージの表示とキー入力待ち
 ****************************************************/
d3errm(err_mes)
char	*err_mes;
{
	if (isendwin()) doupdate();
	werase(w1);
	mvwaddstr(w1, 0, 0, err_mes); MREFRESH ;

	beep();
	d3wkey(pad);
	werase(w1); MREFRESH ;

	return(0);
}

/****************************************************
 *	エラーコードのメッセージの表示とキー入力待ち
 ****************************************************/
d3errw(err)
int	err;
{
	char	err_mes[50];

	d3msgh(err);		/* メッセージ通知 */

	if (isendwin()) doupdate();
	werase(w1);
	d3erms(err, err_mes);
	mvwaddstr(w1, 0,0,err_mes); MREFRESH;

	beep();
	d3wkey(pad);
	werase(w1); MREFRESH;

	return(0);
}
