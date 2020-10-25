/*	FILE DESCRIPTION

		FILE NAME	: d3init.c
		ROUTINE		: 会話型初期化処理
		REVISION	:
		REMARKS		:	88.05.16	S.Aizawa
*/

#include	<stdio.h>
#include	<jcurses.h>
#include	"/la_ws/include/d2lerr.h"
#include	"functn.h"
#include	"d3data.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3init()
		INPUT		: none
		OUTPUT		: none
*/

d3init()
{
	char	*env_p;
	char	*getenv();

	env_p = getenv ( "Y_PDHOME" );	/* default Y_PDHOME dir get */
	if (env_p == NULL) {
		init10("Y_PDHOME");
		exit(1);
	}
	strcpy(home_pold, env_p);	/* save	home pold directory */

	env_p = getenv ( "Y_LADB" );	/* default current dir get */
	if (env_p == NULL) {
		init10("Y_LADB");
		exit(1);
	}
	strcpy(home_ladb, env_p);	/* save	home ladb name */

	env_p = getenv ( "Y_DSGRP" );	/* default group name get*/
	if (env_p != NULL)
		strcpy(home_grp, env_p);	/* save home group name */

	getcwd(home_dir, 128);		/* get current working dir */

	/*if (d3drrd() != 0) {*/		/* read current file */
		strcpy(cur_ladb, home_ladb);	/* set current ladb */
		strcpy(cur_grp, home_grp);	/* set current group */
		/*d3drwt();*/			/* write current file */
	/*}*/

	lbzzfs();			/* initialize function key 1 */
	lbzzsc();			/* initialize function key 2 */
	slk_init(1);			/* initialize soft label */
	initscr();			/* initialize screen */

	d3pdmk(1);			/* make common pad */

	w1 = subpad(pad,1,50,l_num-2,30);	/* subpad for pad */

	d3sgnl();			/* set signel process */
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: init10()
		INPUT		: env_name = 環境変数名
		OUTPUT		: none
*/

static
init10(env_name)
char	*env_name;
{
	char	msg[80];

	d3msgh(E_ENV_NSET, env_name);	/* エラー通知 */

	d3erms(E_ENV_NSET, msg);	/* エラーメッセージ取り出し */
	fprintf(stderr, msg, env_name);	/* エラーメッセージ表示 */
	fprintf(stderr, "\n\n");
}
