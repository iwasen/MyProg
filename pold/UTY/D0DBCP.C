/*

		FUNC.NAME	: d0dbcp.c
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.11.10	S.Takahashi
*/
#include	"d0dbcp.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	int	selectpr ;			/* selected prosess */
static	int	hozon_kind;			/* 保存種別 */
static	int	device;				/* 媒体種別 */
static	char	baitai[64] ;			/* selected MTorFD */
static	int	proc_no;			/* 処理Ｎｏ． */

/*****************************************************************************/
/*		main prosessing						     */
/*****************************************************************************/
d0dbcp()
{
	int	rtn ;

/* error ( temporary file acsess error )  handling */

	setjmp ( env );

/* hozon fukugen shori */

	for ( ;; ) {

		if (dbcp20 () != 0)	/* 処理選択 */
			continue;

		switch (selectpr) {
		case 	HOZON :		/* 保存処理 */
			if (dbcp25() != 0)	/* 媒体選択 */
				continue;

			dbcp30 () ;
			switch ( selectpr ) {

/* hozon shori 1 ( all , all dataset , all user make index , group sakuin ) */

			case 	ALL :
			case ALL_DSET :
			case ALL_USR :
			case ONE_GRP :
				dbcp40 () ;
				break ;

/* hozon shori 2 ( select group ) */

			case ONE_KOBETSU_GRP :
				dbcp50 ( ) ;
				break ;

/* hozon shori 3 ( select dataset ) */

			case ONE_KOBETSU_DSET :
				dbcp60 ( ) ;
				break ;

/* hozon shori 4 ( select user make index ) */

			case ONE_KOBETSU_USR :
				dbcp70 ( ) ;
				break ;
			case STOP :
				break ;
			}
			break ;

		case FUKUGEN :		/* 復元処理 */
			if (dbcp25() != 0)	/* 媒体選択 */
				continue;
			dbcp80 ( ) ;
			break ;

		case ICHIRAN :		/* 一覧処理 */
			if (dbcp25() != 0)	/* 媒体選択 */
				continue;
			dbcp85();
			break;

		case DIR :		/* LADB変更 */
			DIR_CALL ;
			break ;

		case END :		/* 終了 */
			return(0);
		}
		if ( selectpr == STOP )
			break ;
	}
}

/*****************************************************************************/
/*		hozon or fukugen menu					     */
/*****************************************************************************/
static
dbcp20 ()
{
	static	char	title[]  = { "" } ;
	static	char	*item[5] = {
		" １  保存  " ,
		" ２  復元  " ,
	 	" ３  一覧  " ,
		NULL
	};
	static	char	*func[8] = {
		NULL, NULL, NULL, NULL,
		NULL, NULL, "LADB変更", NULL
	};
	static	char	imes[] = { "処理を選択してください" };
	char	pno[100] ;
	chtype	fno[10] ;

/* menu selecting */

	SCR_CLEAR ;
	DSP_PN_DR ;
	*pno = NULL ;
	d3mnmk ( 0 , title , item , 3 , func , imes , pno , fno ) ;
	switch ( *fno ) {
	case '0' :			/* press CR ? */
		switch ( *pno ) {
		case 1 :
			selectpr = HOZON;
			break ;
		case 2 :
			selectpr = FUKUGEN;
			break ;
		case 3 :
			selectpr = ICHIRAN;
			break;
		}
		proc_no = selectpr;
		break ;
	case '7' :
		selectpr = DIR ;
		break ;
	case 'A':		/* 強制終了 */
	case 'E':		/* 正常終了 */
		selectpr = END ;
		break ;
	}

	return(0);
}
/*****************************************************************************/
/*		媒体選択						     */
/*****************************************************************************/
static
dbcp25 ()
{
	static	char	title[]  = { "" } ;
	static	char	*item[] = {
		" １  ＣＭＴ  " ,
	 	" ２  ＦＤ    " ,
		NULL
	};
	static	char	*func[8] = {
		NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, NULL
	};
	static	char	imes[] = { "媒体を選択してください" };
	char	pno[100] ;
	chtype	fno[10] ;
	char	*dev_env, *dev_name, s[80];
	extern	char	*getenv();

/* menu selecting */

	dbcp95();		/* 画面固定部表示 */
	*pno = NULL ;
	d3mnmk ( 0 , title , item , 2 , func , imes , pno , fno ) ;
	switch ( *fno ) {
	case '0' :			/* press CR ? */
		switch ( *pno ) {
		case 1 :
			device = MT;
			break ;
		case 2 :
			device = FD;
			break ;
		}
		dev_env = (device == MT) ? ENV_MT : ENV_FD;
		if ((dev_name = getenv(dev_env)) == 0) {
			d3msgh(E_ENV_NSET, dev_env);	/* エラー通知 */
			sprintf(s,"環境変数が設定されていません (%s)",dev_env);
			d3errm(s);
			return(1);
		}
		strcpy(baitai, " -f ");
		strcat(baitai, dev_name);
		if (selectpr == HOZON) {
			if (device == FD)
				strcat(baitai, " -s 980k");
			else if (device == MT)
				strcat(baitai, " -s 110M");
		}
		break ;
	case 'A':		/* 強制終了 */
		return(1);
	}

	return(0);
}

/*****************************************************************************/
/*		hozon kind menu						     */
/*****************************************************************************/
static
dbcp30 ()
{
	char	title[20] ;
	static	char	titlewk[] = { "へ保存" } ;
	static	char	mt[] = { "ＣＭＴ" } ;
	static	char	fd[] = { "ＦＤ" } ;
	static	char	*item[8] = {
 		"  １  全体                  " ,
		"  ２  全ＬＡデータセット    " ,
 		"  ３  全ユーザ索引          " ,
 		"  ４  グループ索引のみ      " ,
 		"  ５  個別グループ          " ,
 		"  ６  個別ＬＡデータセット  " ,
 		"  ７  個別ユーザ索引        " ,
		NULL
	};
	static	char	*func[8] = {
		NULL, NULL, NULL, NULL, 
		NULL, NULL, NULL, NULL
	};
	static	char	imes[] = { "保存対象を選択してください" } ;
	char	pno[100] ;
	chtype	fno[10] ;

/* menu selecting */

	dbcp95();		/* 画面固定部表示 */
 	*pno = NULL ;
	if ( device == MT ) {
		strcpy ( title , mt ) ; strcat ( title , titlewk ) ;
	} else {
		strcpy ( title , fd ) ; strcat ( title , titlewk ) ;
	}
	d3mnmk ( 0 , title , item , 7 , func , imes , pno , fno ) ;
	switch ( *fno ) {
	case '0' :			/* press CR ? */
		switch ( *pno ) {
		case 1 :
			selectpr = ALL ; break ;
		case 2 :
			selectpr = ALL_DSET ; break ;
		case 3 :
			selectpr = ALL_USR ;break ;
		case 4 :
			selectpr = ONE_GRP ;break ;
		case 5 :
			selectpr = ONE_KOBETSU_GRP ; break ;
		case 6 :
			selectpr = ONE_KOBETSU_DSET ; break ;
		case 7 :
			selectpr = ONE_KOBETSU_USR ; break ;
		}
		break ;
	case 'A':		/* 強制終了 */
		selectpr = END ;
		break ;
	}
}
/*****************************************************************************/
/*		hozon shori 1 ( all , all dataset , all user index , group ) */
/*****************************************************************************/
static
dbcp40 ()
{
	int	filecode;

/* file kind code make */

	switch ( selectpr ) {
		case ALL :	filecode = 0 ; break ;
		case ALL_DSET :	filecode = 1 ; break ;
		case ALL_USR :	filecode = 2 ; break ;
		case ONE_GRP : 	filecode = 3 ; break ;
	}

/* command */

	dbcp90(filecode, "");		/* 保存処理実行 */
}
/*****************************************************************************/
/*		hozon shori 2 ( select group ) 				     */
/*****************************************************************************/
static
dbcp50 ( )
{
	char	selectitem[100], s[128];

/* menu selecting */

	dbcp95();		/* 画面固定部表示 */
	sprintf(s, "%s/IND.*", cur_ladb);
	d3fslc(s, selectitem, 0, "グループ");
	if (*selectitem != 0) {
		dbcp90(4, selectitem);		/* 保存処理実行 */
	}

}

/*****************************************************************************/
/*		hozon shori 3 ( select dataset ) 			     */
/*****************************************************************************/
static
dbcp60 ( )
{
	char	jokenfile[50] ;
	char	selectname[1024*10] ;

/* initialize */

	dbcp61 ( jokenfile ) ;

/* select data set hozon shori */

	dbcp62 ( selectname , jokenfile ) ;

	if ( selectpr == STOP )
		return ;

	dbcp63 ( selectname );
}
/*****************************************************************************/
/*		initialize						     */
/*****************************************************************************/
static
dbcp61 ( jokenfile )
char	*jokenfile ;
{

/* get temporary file name */

	GET_TMP ;
	strcpy ( jokenfile , tmp_name ) ;
}
/*****************************************************************************/
/*		input ladataset name					     */
/*****************************************************************************/
static
dbcp62 ( selectname , jokenfile )
char	*selectname ;
char	*jokenfile ;
{

	static	char	*func[8] = {
		NULL, NULL, NULL, NULL, 
		"条件検索", "file一覧", NULL, NULL
	};
	FILE	*fp ;
 	char	buf[50], s[128], s2[128];
	int	len;
	char	*bp;

/* display input screen */

	*selectname = NULL ;
	dbcp95();		/* 画面固定部表示 */
retry:
	mvwaddstr ( pad , 5,10,"保存ＬＡデータセット名  :") ;

/* input */

	SCR_GETS ( 5 , 36 , 12 , selectname , func ) ;
	switch ( f_key ) {
	case 0x0a :
		if ( *selectname == NULL ) goto retry ;
		selectpr = CONT ;
		break ;
	case KEY_F(5) :
		JOKEN_CALL ( jokenfile ) ;
		dbcp95();		/* 画面固定部表示 */
		fp = fopen ( jokenfile , "r" ) ;
		if ( fp == NULL ) {
			ER_MES_WAIT ( E_TF_A ) ;
			longjmp ( env , 1 ) ;
		}
		for (bp = selectname, i = 0; fgets(buf, 50, fp) != 0; i++) {
			len = strlen(buf);
			buf[len - 1] = ' ';
			strcpy(bp, buf);
			bp += len;
		}
	 	fclose ( fp ) ;
		unlink ( jokenfile ) ;
		if (i == 0) {
			goto retry ;
		}
		selectpr = CONT ;
		break ;
	case KEY_F(6):			/* file一覧 */
		sprintf(s2, "%s/%s", cur_ladb,
				selectname[0] ? selectname : "[!A-Z]*");
		d3fsel(s2, s, 0);
		if (s[0] != '\0')
			strcpy(selectname, s);
		dbcp95();		/* 画面固定部表示 */
		goto retry ;
	case KEY_F(17):			/* 強制終了 */
		selectpr = STOP ;
		break ;
	default :
		goto retry ;
	}
}
/*****************************************************************************/
/*		hozon shori 					    	     */
/*****************************************************************************/
static
dbcp63 ( selectname )
char	*selectname ;
{

/* system command */

	dbcp90(5, selectname);		/* 保存処理実行 */
}

/*****************************************************************************/
/*		hozon shori 4 ( select user make index )		     */
/*****************************************************************************/
static
dbcp70 ( )
{
	char	selectitem[100], s[128];

	dbcp95();		/* 画面固定部表示 */
	sprintf(s, "%s/USR.*", cur_ladb);
	d3fslc(s, selectitem, 0, "ユーザ索引");
	if (*selectitem != 0) {
		dbcp90(6, selectitem);		/* 保存処理実行 */
	}
}
	
/*****************************************************************************/
/*		fukugen shori						     */
/*****************************************************************************/
static
dbcp80 ( )
{
	dbcp95();		/* 画面固定部表示 */

	dbcp90(-1, "");		/* 復元処理実行 */
}

/*****************************************************************************/
/*		一覧表示処理						     */
/*****************************************************************************/
static
dbcp85()
{
	dbcp95();		/* 画面固定部表示 */

	dbcp90(-2, "");
}

/*****************************************************************************/
/*	 	保存／復元実行処理					     */
/*****************************************************************************/
static
dbcp90(kind, item)
int	kind;
char	*item;
{
	static	char	*func[8] = {
		NULL, NULL, NULL, NULL,
		"実  行", NULL, NULL, NULL
	};
	int	y, x;
	char	cmd[1024*10];

	wmove(pad, 21, 2);
	wclrtoeol(pad);

	if (device == MT)
		waddstr(pad, "ＣＭＴ");
	else
		waddstr(pad, "ＦＤ");

 	waddstr(pad, "をセットして、実行キーを押してください  ");
	d3fcmk(func);
	prefresh(pad,0,0,0,0,l_num,80);

	getyx(pad, y, x);
	for (;;) {
		switch (d3chin(y, x, 0, 0)) {
		case KEY_F(5):		/* 実行 */
			break;
		case KEY_F(17):		/* 強制終了 */
			return(1);
		default:
			continue;
		}
		break;
	}

	if (kind == -1) {
		sprintf(cmd, "d1_dbrs %s %s", cur_ladb, baitai);
	} else if (kind == -2) {
		sprintf(cmd, "bru -tv %s", baitai);
	} else {
		sprintf(cmd, "d1_dbsv %s %s -k %d %s",
				cur_ladb, baitai, kind, item);
	}

	wclear(pad);
	prefresh(pad,0,0,0,0,l_num,80);
	slk_clear();
	endwin();

	system(cmd);

	printf("確認後、リターンキーを押してください  ");
	fflush(stdout);
	gets(cmd);

	return(0);
}

/*****************************************************************************/
/*	 	画面固定部表示						     */
/*****************************************************************************/
static
dbcp95()
{
	char	*sub_title;

	SCR_CLEAR ;
	DSP_PN_DR ;

	switch (proc_no) {
	case HOZON:
		sub_title = "＜  保存  ＞";
		break ;
	case FUKUGEN:
		sub_title = "＜  復元  ＞";
		break ;
	case ICHIRAN:
		sub_title = "＜  一覧  ＞";
		break;
	default:
		sub_title = "";
	}
	mvwaddstr(pad, 1, 0, sub_title);
}
/***************************** eof *******************************************/
