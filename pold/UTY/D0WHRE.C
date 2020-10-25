/**********************************************************
 *	FUNC. DESCRIPTION
 *
 *	ＬＡデータベースの変更 (サブルーチン)
 *
 *	FUNC.NAME	: d0whre()
 *	INPUT		: none
 *	OUTPUT		: none
 *	COMMENT		: We are in curses-mode.
 *			: Pad & w1 are already created !
 *	REMARKS		:	87.10.31	K.SIGEMI
 *	REMARKS		:	88.04.25	M.MOTOKI
 **********************************************************/

#include	<jcurses.h>
#include	<sys/types.h>
#include	<sys/stat.h>
#include	<errno.h>
#include	<string.h> 
#include	"la_ws/include/d3nmty.h" 
#include	"la_ws/include/d3libc.h" 
#include	"la_ws/include/d2lerr.h" 
#include	"d3item.h" 
#include	"d3data.h"

#define	PNOUTREFRESH	pnoutrefresh(pad,0,0,0,0,l_num,80)
#define PREFRESH 	prefresh(pad,0,0,0,0,l_num,80)
#define	WCLRTOEOL(y,x)	{wmove(pad,y,x); wclrtoeol(pad);}
#define ER_MES(x) 	d3errw(x)
#define	ITEM_ENTER	(IT_NUM|IT_CEN)
#define	PRINT(err)	sprintf(msgbuf,"実行エラー : エラーコード=%-2d",err)
#define	PD_ERRCD(err, st)	{d3errw(err); return(st);}
#define	PD_ERRMS(msg, st)	{d3errm(msg); return(st);}
#define	PD_ERRNO(err, st)	{PRINT(err); d3errm(msgbuf); return(st);}
#define	RET_ERROR	1
#define MES_OK_F "ＬＡデータベースの検索を実行します。実行を押してください  "

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	pname[] = "＜  ＬＡデータベースの変更  ＞";
static	char          title[]		/* タイトル名 */
	= "ＬＡデータベース一覧" ;       
static	char		*func[8] = { 	/* description of func-key */
	  NULL,"LADB検索",NULL,"参  照",NULL,"一覧表示",NULL,NULL
}; 
static	char	*pf_label_3[] = {
		NULL, NULL, NULL, NULL," 実  行 ", NULL, NULL, NULL
	};
static	char		imes[] 		/* 入力促進メッセージ */
	= "ＬＡデータベース名を指定してください  "  ;         
static	char		kmes[] 	 	/*  検索中メッセージ */
	= "ＬＡデータベースを検索中     "  ;         
static		char	ladb_list[128];
static	int	mgno;			/* メモリ番号 */
static	D_CELL	*cell_p;		/* 項目テーブルポインタ */
static	int	cell_n;			/* 項目数 */
static	int	c_pos;			/* 選択項目位置 */
static	char	msgbuf[30];
static	struct	stat	stat_buf;

d0whre()
{
	int	i, err = 0, loop = 1;
	char	*p;
	chtype	ch;

	mgno = d3mgno();		/* メモリ番号取り出し */

	strcpy(ladb_list, home_pold);  
	strcat(ladb_list,"/");
	strcat(ladb_list, "ladb_list");

	if ( d3fchk(ladb_list) != 0 ) {	/* ファイル "ladb_list" がないなら */
		wmove(pad,1,0); wclrtobot(pad);	/* 画面消去 */
 		mvwaddstr(pad, 1, 0, pname);
		d3dred() ;
		mvwprintw(pad, 1, 40, "< $Y_LADB=%s >", home_ladb);
		mvwaddstr(pad, 3, 18, title);

		if ((err=whre01()) != 0) /* ＬＡデータベースを検索する */
			loop = 0;
	}

	if ((err = d3fitm(ladb_list, mgno, &cell_p, &cell_n)) != 0) {
		ER_MES(err);
		loop = 0;
	}
	c_pos = 0;
	for (i = 0; i < cell_n; i++) {
		if (strcmp(cur_ladb, cell_p[i].ptr) == 0) {
			c_pos = i;
			break;
		}
	}

	while (loop) {			/* 画面インタフェイス処理 */
		wmove(pad,1,0); wclrtobot(pad);	/* 画面消去 */
 		mvwaddstr(pad, 1, 0, pname);
		d3dred() ;
		mvwprintw(pad, 1, 40, "< $Y_LADB=%s >", home_ladb);

		mvwaddstr(pad, 3, 18, title);
		mvwaddstr(pad, l_num-3, 2, imes);
		d3fcmk(func);

		ch = d3item(cell_n, cell_p, 68, ITEM_ENTER, &c_pos);
		switch (ch) {
		case '\n':		/* ＬＡデータベース変更 !! */
			if (cell_n != 0) {
				/* カレントファイルへ書き出し */ 
				err = whre03();
				if (err == 0) loop = 0;
			}
			break;
		case KEY_F(2):		/* LADB検索 */
			whre01();	/* ＬＡデータベース再検索 */
			if ((err = d3fitm(ladb_list, mgno,
						&cell_p, &cell_n)) != 0) {
				ER_MES(err);
				loop = 0;
			}
			c_pos = 0;
			break;
		case KEY_F(4):		/* 参照 */
			whre10();
			break;
		case KEY_F(6):		/* 一覧表示 */
			whre20();
			break;
		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			loop = 0;
			break;
		}
	}

	wmove(pad,1,0); wclrtobot(pad);	/* 画面消去 */

	d3free(mgno);			/* メモリ解放 */

	return ( err );
} 

/********************************************
 *	ＬＡデータベース検索
*********************************************/
static
whre01( )
{
	int	err;
	char	*dmy="   ";
	chtype	pf_k;
   
	wmove(pad,2,0) ; wclrtobot(pad) ;

	d3fcmk(pf_label_3);
	WCLRTOEOL(l_num-3, 2);
	mvwaddstr(pad, l_num-3, 2, MES_OK_F);

	for (;;) {
		pf_k = d3chin(l_num-3,strlen(MES_OK_F)+2, 0, dmy);
		if (pf_k == KEY_F(5)) break;
		else if (pf_k == KEY_F(17) || pf_k == KEY_F(18)) return(0);
	}

	WCLRTOEOL(l_num-3, 2);
	mvwaddstr(pad, l_num -3, 2, kmes) ;  PREFRESH ;

	strcpy(cmd_b, "find / -type d -name ladb -print > ");
	strcat(cmd_b, ladb_list);
	strcat(cmd_b, " 2>/dev/null ");

	err = system(cmd_b) >> 8;
	       
	if (err != NULL) {
		unlink ( ladb_list );
		ER_MES(E_E);
		return(E_E);
	} 

	return(NULL) ;
} 

/********************************************
 *	カレントファイルへ書き出し
 ********************************************/
static
whre03( )
{
	char	*cpa; 
	int 	rtn_inf; 
					/* ディレクトリのチェック */
	rtn_inf = stat(cell_p[c_pos].ptr, &stat_buf);

	if (rtn_inf == -1 && errno == ENOENT)
		PD_ERRCD(E_NEXST_LADB, RET_ERROR)
	else if (rtn_inf != 0)
		PD_ERRCD(E_POLD_NEXE, RET_ERROR)

	strcpy(cur_ladb, cell_p[c_pos].ptr);

/*	####### カレントファイルを使用しないように変更 #######
	rtn_inf = d3drwt() ; /# write directory-name into current-file #/ 
	if (rtn_inf != NULL) {
		ER_MES(rtn_inf);
		return(rtn_inf);  
	}
*/
					/* return */
	return(NULL) ;
} 

/*************************************
 *	グループ索引の参照
 *************************************/
static	whre10()
{
	int	err, f_no;
	char	ladir[128];

	if ((f_no =d3slct(cell_n, cell_p, 0)) == -1)
		f_no = c_pos;
	strcpy(ladir, (cell_p + f_no)->ptr);

	err = stat(ladir, &stat_buf);	/* ディレクトリのチェック */
	if (err == 0) {			/* ＬＡデータベースが存在する */
		err = d3pgrp(ladir);
		if (err != 0) PD_ERRCD(err, RET_ERROR);	
	} else if (errno == ENOENT)		/* ＬＡデータベースがない */ 
		PD_ERRCD(E_NEXST_LADB, RET_ERROR)
	else
		PD_ERRNO(errno, RET_ERROR)	/* 実行エラー */

	return(0);
}

/*********************************************
 *	ＬＡデータベースの情報
 *********************************************/
static	whre20()
{
	int	f;

	if ((f = d3slct(cell_n, cell_p, 0)) == -1) /* 見つからないなら */
		f = c_pos;	/* カーソルの現在位置のデータベースの情報 */

	d3fdsp((cell_p + f)->ptr, NULL);

	return(0);
}
