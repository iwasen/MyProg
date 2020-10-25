/*	FILE DESCRIPTION

		FILE NAME	: d0tbhn.c
		ROUTINE		: 表操作
		REVISION	:
		REMARKS		: created	88.06.17	s.aizawa
*/

#include	<jcurses.h>
#include	"la_ws/include/d2lerr.h"
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/tbdefs.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: main()
		ROUTINE		: メイン処理
		INPUT		: argc    = パラメータ数
				: argv[0] = コマンド名
				: argv[1] = ＬＡデータベースディレクトリ名
				: argv[2] = ＬＡデータセット名
				: argv[3] = モード
				: argv[4] = ラベル名
				: argv[5] = 最少文字数
				: argv[6] = 最大文字数
				: argv[7] = タイトル
		OUTPUT		: none
*/

main(argc, argv)
int	argc;
char	*argv[];
{
	TY_PRIM	primary;
	char	env_buf[128];
	char	*file;
	int	err;

	if (argc != 8)
		exit(E_P);

	sprintf(env_buf, "Y_LADB=%s", argv[1]);
	if ((err = putenv(env_buf)) != 0) {
		exit(E_M);
	}

	lbzzfs();			/* initialize function key 1 */
	lbzzsc();			/* initialize function key 2 */
	initscr();			/* initialize screen */

	d3sgnl();			/* set signel process */

	primary.label	= argv[4];
	primary.minlen	= atoi(argv[5]);
	primary.maxlen	= atoi(argv[6]);
	primary.mtitl	= argv[7];
	primary.stitl1	= NULL;
	primary.stitl2	= NULL;
	primary.stitl3	= NULL;
	primary.count	= 0;
	primary.maxcol 	= 0;

	err = tbmnpl(argv[2], &primary, atoi(argv[3]));

	if (d3xgrp(argv[2]))
		file = "gi";
	else if (d3xind(argv[2]))
		file = "if";
	else if (d3xusr(argv[2]))
		file = "uf";
	else if (d3xdat(argv[2]))
		file = "ds";
	else
		file = "";

	err = d3cver(d3tbcv(err), file);

	clear();		/* clear screen */
	slk_clear();		/* clear soft label key */
	refresh();
	endwin();		/* curses mode end */
	resetterm();		/* reset terminal */

	exit(err);
}
