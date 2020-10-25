/*	FILE DESCRIPTION

		FILE NAME	: d3data.c
		ROUTINE		: 共通データ
		REVISION	:
		REMARKS		:	88.05.10	S.Aizawa
*/

#include	<jcurses.h>
#include	"d3data.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

char	home_pold[128];			/* directory name from $Y_PDHOME */
char	home_ladb[128];			/* directory name from $Y_LADB */
char	home_grp[16];			/* group name from $Y_DSGRP */
char	home_dir[128];			/* cur working dir from getcwd() */
char	cur_ladb[128];			/* current ladb directory */
char	cur_grp[128];			/* current group */

short 	rst_tbl[RST_MAX+1];		/* result table */
char	cmd_b[512];			/* コマンド編集バッファ */

WINDOW	*pad;				/* common pad */
WINDOW	*w1;				/* error message pad */
int	l_num;				/* number of user line */
