/*	FILE DESCRIPTION

		FILE NAME	: pamenu.c
		ROUTINE		: main menu editing
		REVITION	:
		REMARKS		:	87.10.20	N.MATSUO
		REMARKS		:	88.04.21	M.MOTOKI
*/

#include	"pdmenu.h"

#define		MENU_NUM	11		/* menu item number */

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static 	char	pa[] = "＊＊＊  ＬＡデータ管理メニュー  ＊＊＊";

static	char	*pb[MENU_NUM] = {	/* item for main menu */
	"   １  ＬＡデータの格納             ",
	"   ２  説明部・ラベル部の保守       ",
	"   ３  表データセットの保守         ",
	"   ４  ユーザ索引の保守             ",
	"   ５  外部表現ファイルの保守       ",
	"   ６  条件検索                     ",
	"   ７  ＬＡデータセットの削除       ",
	"   ８  バックアップファイルの保守   ",
	"   ９  ＬＡデータセットの保存・復元 ",
	" １０  システム索引の保守           ",
	" １１  グループ索引の保守           "
};


static 	char	*pc[8] = {	/* function key for main menu */
	"shell", NULL, NULL, NULL,
	NULL, NULL, "LADB変更", NULL
};

static	char	pd[] = "処理を選択してください";


/*	FUNC. DESCRIPTION

		FUNC.NAME	: main()
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.10.20		N.MATSUO
*/

main(argc, argv)
int	argc;
char	*argv[];
{
	int	stat, i_no, com_no, loop_sw;
	char	pno[55];
	chtype	fno;

	com_no = menu10(argc, argv);		/* get command No. */

	d3init();				/*	initialize	*/

	loop_sw = 1;
	while (loop_sw) {
		wclear(pad);

		i_no = MENU_NUM;

		if (com_no == 0) {
			d3pned(0);		/* display title */
			d3mnmk(0, pa, pb, i_no, pc, pd, pno, &fno);
		} else {
			fno = '0';
			pno[0] = com_no;
			loop_sw = 0;
		}

		switch (fno){
		case '0':		/* push < CR > */
			switch (pno[0]) {
			case	1:	/* ＬＡデータセット 登録・格納 */
				stat = d0dsmk();
				break;
			case	2:	/* 説明部・ラベル部 保守 */
				stat = d0slmt();
				break;
			case	3:	/* 表データ 保守 */
				stat = d0kdmt();
				break;
			case	4:	/* ユーザ索引 保守 */
				stat = d0usmt();
				break;
			case	5:	/* ＬＡデータセット 表現形式の変換 */
				stat = d0dtcm();
				break;
			case	6:	/* 条件検索 */
				if (argc < 2 && com_no == 0)
					stat = d0cdrf(NULL);
				else
					stat = d0cdrf(argv[1]);
				break;
			case	7:	/* ＬＡデータセット 削除 */
				stat = d0dsdl();
				break;
			case	8:	/* バックアップファイルの保守 */
				stat = d0back();
				break;
			case	9:	/* ＬＡデータベース 保存・復元 */
				stat = d0dbcp();
				break;
			case	10:	/* システム索引の生成・保守 */
				stat = d0sind();
				break;
			case	11:	/* ＬＡデータベースの生成・保守 */
				stat = d0ladb();
				break;
			}
			break;
		case '1':		/* shell */
			menu20();
			break;
		case '7':		/* push < F7 > */
			stat = d0whre();	/* CHANGE LADB */
			break;
		case 'A':		/* f9  （強制終了） */
		case 'E':		/* f10 （正常終了） */
			loop_sw = 0;
			break;
		}
	}
	d3term();	/* terminate process */
	exit(OK);
}

/*	FUNC. DESCRIPTION

		FUNC.NAME	: menu10
		INPUT		: argc, argv
		OUTPUT		: none
		REMARKS		:	87.10.20		N.MATSUO
*/

menu10(argc, argv)
int	argc;
char	*argv[];
{
	static	char	command[11][8] = {
		"pd_dsmk" , "pd_slmt" , "pd_kdmt" , "pd_usmt" ,
		"pd_dtcm" , "pd_cdrf" , "pd_dsdl" , "pd_back" ,
		"pd_dbcp" , "pd_sidx" , "pd_ladb"
	};
	int	i;
	char	*cp;

	if ((cp = strrchr(argv[0], '/')) != NULL)
		cp++;
	else
		cp = argv[0];

	for (i = 0; i < 11; i++) {
		if (strcmp(command[i], cp) == 0)
	 		return(i+1);
	}
	return(0);
}

/*	FUNC. DESCRIPTION

			ＳＨＥＬＬ起動

		FUNC.NAME	: menu20
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.10.20		N.MATSUO
*/

menu20()
{
	/* 画面消去＆カーシズ終了 */
	slk_clear();
	clear();
	refresh();
	endwin();

	/* 全シグナル無視 */
	signal(SIGHUP, SIG_IGN);
	signal(SIGINT, SIG_IGN);
	signal(SIGQUIT, SIG_IGN);
	signal(SIGILL, SIG_IGN);
	signal(SIGTRAP, SIG_IGN);
	signal(SIGIOT, SIG_IGN);
	signal(SIGEMT, SIG_IGN);
	signal(SIGFPE, SIG_IGN);
	signal(SIGBUS, SIG_IGN);
	signal(SIGSEGV, SIG_IGN);
	signal(SIGSYS, SIG_IGN);

	/* ＳＨＥＬＬ起動 */
	d3exec("sh", 0);

	/* シグナル処理設定 */
	d3sgnl();
}
