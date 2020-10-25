/************************************************************
 *	FILE DESCRIPTION
 *		�G���[���b�Z�[�W���o�́E�L�[���͑҂�
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
 *	�G���[���b�Z�[�W�̕\���ƃL�[���͑҂�
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
 *	�G���[�R�[�h�̃��b�Z�[�W�̕\���ƃL�[���͑҂�
 ****************************************************/
d3errw(err)
int	err;
{
	char	err_mes[50];

	d3msgh(err);		/* ���b�Z�[�W�ʒm */

	if (isendwin()) doupdate();
	werase(w1);
	d3erms(err, err_mes);
	mvwaddstr(w1, 0,0,err_mes); MREFRESH;

	beep();
	d3wkey(pad);
	werase(w1); MREFRESH;

	return(0);
}