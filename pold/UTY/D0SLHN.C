/*	FILE DESCRIPTION

		FILE NAME	: d0slhn.c
		ROUTINE		: 説明部ラベル部の保守
		REVISION	:
		REMARKS		: created	88.06.15	s.aizawa
*/

#include	"la_ws/include/epsub.h"
#include	"la_ws/include/d2lerr.h"
#include	"d3data.h"

extern	int	d_ch1[], d_pg03[], d_pg04[];

char	*ep_lads;
char	*ep_ladir;
char	*ep_sidx;
int	epbach=F;
int	dl_book[] = {
	undef,
	undef,
	1,
	(int)d_ch1
};
int	dl_rinf;
int	dl_write;

static	char	sccsid[]="%Z%%M% %I% %E% %U%";


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: main()
		ROUTINE		: メイン処理
		INPUT		: argc    = パラメータ数
				: argv[0] = コマンド名
				: argv[1] = ＬＡデータベースディレクトリ名
				: argv[2] = ＬＡデータセット名
				: argv[3] = 0 説明部の保守
				:         = 1 ラベル部の保守
				: argv[4] = タイトルメッセージ
		OUTPUT		: none
*/

main(argc, argv)
int	argc;
char	*argv[];
{
	int	err = 0;
	int	flag;
	char	*file;
	char	lads_path[128];
	char	title_buf[82];
	char	idx_name[16];

	if (argc != 5)
		exit(E_P);

	ep_ladir = argv[1];
	ep_lads = argv[2];

	err = d2ichk(ep_ladir, ep_lads, idx_name);
	if (err != 0)
		exit(err);
	ep_sidx = idx_name;
		

	lbzzfs();			/* initialize function key 1 */
	lbzzsc();			/* initialize function key 2 */
	initscr();			/* initialize screen */

	d3sgnl();			/* set signel process */

	sprintf(title_buf, "%-40s< %s >", argv[4], ep_lads);
	d_ch1[3] = (int)title_buf;

	flag = atoi(argv[3]);
	if (flag == 0)
		d_ch1[12] = (int)d_pg03;
	else
		d_ch1[12] = (int)d_pg04;

	dl_rinf = dl_write = 0;
	epbook(dl_book);

	if (dl_rinf == 0) {
		if (dl_write != 0) {
			err = d2idup(ep_ladir, idx_name, argv[2]);
		} else
			err = E_E;
	} else if (dl_rinf == 1) 
		err = E_LABEL;
	else {
		if (d3xgrp(ep_lads))
			file = "gi";
		else if (d3xind(ep_lads))
			file = "if";
		else if (d3xusr(ep_lads))
			file = "uf";
		else if (d3xdat(ep_lads))
			file = "ds";
		else
			file = "";

		err=d3cver(dl_rinf, file);
	}

	clear();		/* clear screen */
	slk_clear();		/* clear soft label key */
	refresh();
	endwin();		/* curses mode end */
	resetterm();		/* reset terminal */

	exit(err);
}
