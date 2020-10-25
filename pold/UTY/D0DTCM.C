/*	FUNC. DESCRIPTION

		外部表現ファイルの保守

		FUNC.NAME	: dtcm()
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.11.31	K.SIGEMI
*/

#include	<jcurses.h>
#include	<string.h>
#include	"la_ws/include/d2lerr.h"
#include	"functn.h"
#include	"d3data.h"
#include	"d3item.h"

#define	ENV_DIR	"Y_COMDIR"

#define 	PN 	6

/* -----------------------------*/
static	int	i;
#define ARRAY_CLR(x) for(i=0;i<sizeof(x)/sizeof(x[0]);i++) x[i]

/* ---------------------------- */
#define 	SCR_CLEAR	wclear(pad);
#define 	FK_CLEAR 	slk_clear()
#define 	PREFRESH 	prefresh(pad,0,0,0,0,l_num,80)
#define 	PNOUTREFRESH 	pnoutrefresh(pad,0,0,0,0,l_num,80)
static	chtype	f_key;
#define SCR_GETS(l,c,ln,bf,f)  d3fcmk(f); PNOUTREFRESH; KEY_IN(l,c,ln,bf)
#define KEY_IN(l,c,ln,bf) f_key=d3chin(l,c,ln,bf)

#define 	DSP_PN_DR 	d3pned(PN); d3dred()

#define 	DIR_CALL 	d0whre(); d3pned(PN); d3dred()
#define 	JOKEN_CALL(fl) 	d0cdrf(fl); d3pned(PN); d3dred()
#define		DSP_KEKA(f,t) d3keka(f,t);SCR_CLEAR;DSP_PN_DR

#define 	ER_MES(x) 	d3errw(x)

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/* -------------------------------- */
/* DESCRIPTION of TMP     FILE      */
/*                                  */
static char 	tmp_name[100];	 	/* tmp-file name */
		  /*  = "/usr/tmp/aaaaxxxxxx......"; */
static	FILE	*fp_tmp;
#define 	GET_TMP 	tmpnam(tmp_name)
#define TMP_OPEN(x) if(NULL==(fp_tmp=fopen(tmp_name,x))) { ER_MES(E_TF_A); }
#define 	TMP_CLOSE	if(NULL!=fclose(fp_tmp)) { ER_MES(E_TF_A);}

/* -------------------------------- */
/*                                  */
#define	READ(bf,fp) if(NULL!=fgets(bf,sizeof(bf)-1,fp)){ *(strchr(bf,'\n'))=NULL;
#define INVALID     }else{;
#define ENDRD       }

/* -------------------------------- */
/* SYSTEM COMMAND                   */
static	int	exit_inf;
#define 	CM_CAT3(a,b,c) 	strcat(cmd_b,a);strcat(cmd_b,b);strcat(cmd_b,c)
#define 	SYS_EX 		exit_inf = (system(cmd_b)>>8)
#define 	SYS_CM(x,y,z) 	strcpy(cmd_b,x);CM_CAT3(y,z," ");SYS_EX
#define SYS_CM7(x1,x2,x3,x4,x5,x6,x7) strcpy(cmd_b,x1);CM_CAT3(x2,x3,x4);CM_CAT3(x5,x6,x7);SYS_EX

/* -------------------------------- */
/* PROCESS DEFINE                   */
static	int	loopsw, loopsw1, loopswg;
#define 	LOOP 		1
#define 	LOOP_END 	0

static	int	field_no;		/* 入力フィールドＮｏ． */
static	int	field_max;	 	/* 最終入力フィールドＮｏ． */
#define 	FIELD_END  	500

#define 	SEND    1
#define 	RECEIVE 2
#define 	DELETE	3

#define 	YES 	300
#define 	NO 	400
#define 	JOKEN   500
#define 	DIR 	600

#define		STOP  	700    		/* 中止 */
#define		CONT  	800   		/* continue */
#define		END  	800   		/* 終了 */

/* -------------------------------- */
/* WORK AREA of dtcm                */
static	int	syori_status;
#define 	SINGLE 	1
#define 	MULTI	2
static	int	dataset_mode;
#define 	MANUAL 	1
#define 	JOKENF 	2
static	char	dataset_name[100];	/* データセット名 */
static	char	file_name[100];		/* 入／出力ファイル名 */
static	char	joken_bf[180];		/* 条件 file read buffer */

static	unsigned int 	msg_ctr;

static	char	pno[100];		/* content of input */
static	chtype	fno[10];		/* inputed function no */

static	char	*dir_name;		/* 外部表現データ格納ディレクトリ */
static	FILE	*pfp;			/* file pointer */

static	int	mgno;			/* メモリ番号 */
static	int	c_pos;			/* カレント位置 */
static	int	n_item;			/* 項目数 */
static	D_CELL	*ptr_item;		/* 項目テーブルポインタ */

static	char	*func_null[8] = {
	NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
};


/*---------------------------------------------*/
/* main part */
d0dtcm()
{
	int	rtn_inf = NULL;

	dtcm10();  			/* 初期処理 */

	loopsw = LOOP;
	while (loopsw == LOOP) {
		switch (dtcm20()) { 		/* 送格納選択画面処理 */
		case SEND :
			dtcm30();		/* 抽出処理 */
			break;
		case RECEIVE :
			dtcm40();		/* 格納処理 */
			break;
		case DELETE :
			dtcm50();		/* 削除処理 */
			break;
		case DIR :
			DIR_CALL;		/* ディレクトリ指定 */
			break;
		case END : 			/* 終了 */
			loopsw = LOOP_END;
			break;
		default :
			break;
		}
	}

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 初期処理 */
static
dtcm10( )
{

}

/*----------------------------------------------*/
/* 格納選択画面処理 */
static
dtcm20( )
{
					/* 画面[6-1] 処理 */
	int	rtn_inf = NULL;
	static char	title[] =  " ";
	static char	*item[4] = { 	/* komoku */
		" １  抽出  " ,
		" ２  格納  " ,
		" ３  削除  " ,
		NULL
	};
	static char	imes[] = "処理を選択してください  ";
	static char	*func1[8] = { 	/* description of func-key */
		NULL,  NULL,  NULL,  NULL,
		NULL,  NULL,  "LADB変更",  NULL
	};

	SCR_CLEAR;
	DSP_PN_DR;

	loopswg = LOOP;

	while (loopswg == LOOP) {
		*pno = NULL;
		d3mnmk(0, title, item, 3, func1, imes, pno, fno);
		switch (*fno) {
		case '0' :
			switch (*pno) {
			case 1 :
				rtn_inf = SEND;
				loopswg = LOOP_END;
				break;
			case 2 :
				rtn_inf = RECEIVE;
				loopswg = LOOP_END;
				break;
			case 3 :
				rtn_inf = DELETE;
				loopswg = LOOP_END;
				break;
			default :
				break;
			}
			break;
		case '7' :		/* LADB変更 */
			rtn_inf = DIR;
			loopswg = LOOP_END;
			break;
		case 'A':		/* 強制終了 */
		case 'E':		/* 正常終了 */
			rtn_inf = END;
			loopswg = LOOP_END;
			break;
		default :
			break;
		}
	}

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 抽出処理 */
static
dtcm30( )
{
	int	rtn_inf = NULL;
	long	f_offset;

	if (dtcm60() != 0)	/* 外部表現データ格納ディレクトリ名取り出し */
		return;

	dtcm31(); 			/* 初期処理 */

	loopsw1 = LOOP;
	while (loopsw1 == LOOP) {
		switch (syori_status) {	/* 処理ステータス判断 */
		case NULL :
			break;
		case SINGLE :
			TMP_CLOSE;
			break;
		case MULTI :
			f_offset = ftell(fp_tmp);
			READ(joken_bf, fp_tmp)
				fseek(fp_tmp, f_offset, 0);
			         		/* 出力ファイル名入力処理 */
				if (dtcm32() == STOP)
					continue;
			INVALID   		/* 結果一覧 */
				DSP_KEKA(fp_tmp, rst_tbl);
				TMP_CLOSE;
				syori_status = NULL;
			ENDRD
			break;
		}

					/* ＬＡデータセット名入力判断 */
		switch (syori_status) {
		case MULTI :
			break;
		default :  /* ＬＡデータセット名と出力ファイル名の入力処理 */
			if (dtcm33() == STOP) {
				loopsw1 = LOOP_END;
				continue;
			}

			if (dataset_mode == JOKENF) { /* 処理選択が条件の時 */
						/* 出力ファイル名入力処理 */
				if (dtcm32() == STOP)
					continue;
			}
			break;
		}

		dtcm34();		/* ＬＡデータセット抽出処理 */

			/* 処理件数が３０００件を越える時は、処理を中止する */
		if (msg_ctr >= RST_MAX)
			dtcm_STOP();
	}

	dtcm35(); 			/* 終了処理 */
	return(rtn_inf);
}

/*----------------------------------------------*/
/* 抽出処理 [初期処理] */
static
dtcm31( )
{
	ARRAY_CLR(rst_tbl) = -1;
	msg_ctr = 0;
	syori_status = NULL;
	dataset_mode = NULL;

	GET_TMP;    				/* 条件検索 file */

	return(NULL);
}

/*----------------------------------------------*/
/* 抽出処理 [ＬＡデータセット名の表示]		*/
static
dtcm32( )
{
					/* 画面 [6-2] 処理  */
	int	rtn_inf = NULL;

	READ(joken_bf, fp_tmp)
		strcpy(dataset_name, joken_bf);
	INVALID
		ER_MES(E_TF_A);
		rst_tbl[msg_ctr++] = E_TF_A;
		return(STOP);
	ENDRD

	wmove(pad, 5, 32);
	wclrtoeol(pad);
	waddstr(pad, dataset_name);
	prefresh(pad,0,0,0,0,l_num,80);

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 抽出処理 [ＬＡデータセット名，出力ファイルの入力処理] */
static
dtcm33( )
{
					/* 画面 [6-2] 処理  */
	int	rtn_inf = NULL;
	static char	*func62b[8] = { 	/* description of func-key */
		NULL, NULL, NULL, NULL,
		"条件検索", "file一覧", NULL, NULL
	};
	static	char	msg1[] = "＜  抽出  ＞";
	static	char	msg2[] = "ＬＡデータセット名  : ";
	char	s[128], s2[128];

	rtn_inf = NULL;

	ARRAY_CLR(rst_tbl) = -1;
	msg_ctr = 0;
	dataset_name[0] = NULL;
	dataset_mode = NULL;
	syori_status = NULL;
	field_no = 1;

	SCR_CLEAR;
	DSP_PN_DR;

	while (field_no != FIELD_END) {
		mvwaddstr(pad, 1, 0, msg1);
		mvwaddstr(pad, 5, 10, msg2);
		SCR_GETS(5, 32, 12, dataset_name, func62b);

		switch (f_key) {
		case 0x0a :
			if (dataset_name[0] == NULL) {
				break;
			}
			dataset_mode = MANUAL;
			syori_status = NULL;
			field_no = FIELD_END;
			break;
		case KEY_F(5) : 			/* 条件 検索 */
			dataset_name[0] = NULL;
			JOKEN_CALL(tmp_name);
			mvwaddstr(pad, 1, 0, msg1);
			mvwaddstr(pad, 5, 10, msg2);

			TMP_OPEN("r");

			READ(joken_bf, fp_tmp)
				syori_status = SINGLE;
			INVALID
				loopswg = LOOP;
				TMP_CLOSE;
				break;
			ENDRD
			field_no = FIELD_END;
			dataset_mode = JOKENF;
			READ(joken_bf, fp_tmp)
				syori_status = MULTI;
			INVALID
				;
			ENDRD
			rewind(fp_tmp);
			rtn_inf = JOKEN;
			break;
		case KEY_F(6):			/* file一覧 */
			sprintf(s2, "%s/%s", cur_ladb,
				dataset_name[0] ? dataset_name : "[!A-Z]*");
			d3fsel(s2, s, 0);
			if (s[0] != '\0')
				strcpy(dataset_name, s);
			SCR_CLEAR;
			DSP_PN_DR;
			mvwaddstr(pad, 1, 0, msg1);
			mvwaddstr(pad, 5, 10, msg2);
			PREFRESH;
			break;
		case KEY_F(17):			/* 強制終了 */
			rtn_inf = STOP;
			field_no = FIELD_END;
			break;
		default :
			break;
		}
	}

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 抽出処理 [ＬＡデータセット抽出処理] */
static
dtcm34( )
{
	int	rtn_inf = NULL;
						/* ＬＡデータセット抽出 */
	wmove(pad,l_num-2,0);
	d3fcmk(func_null);
	PREFRESH;
	sprintf(file_name, "%s/%s", dir_name, dataset_name);
	SYS_CM7("d1_dssd ",cur_ladb," ",dataset_name," ",file_name,
			" 2>/dev/null");
	if (exit_inf != NULL) {			/* エラー判断 */
		if (syori_status != MULTI) {
			ER_MES(exit_inf);
		} else
			d3msgh(exit_inf);
	}
	rst_tbl[msg_ctr++] = exit_inf;

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 抽出処理 [終了処理] */
static
dtcm35( )
{
	int	rtn_inf = NULL;

	unlink(tmp_name);
	return(rtn_inf);
}

/*-----------------------------------------------*/
/* 抽出処理 [中止 処理] */
static
dtcm_STOP( )
{
	int	rtn_inf = NULL;

	rst_tbl[msg_ctr] = E_E; 		/* set 中止 コード */
	if (syori_status == MULTI) {
		if (fseek(fp_tmp, 0, 2) != NULL) {
			ER_MES(E_TF_A);
		}
	}

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 格納処理 */
static
dtcm40()
{
	int	rtn_inf = NULL;

	if (dtcm60() != 0)	/* 外部表現データ格納ディレクトリ名取り出し */
		return;

	pfp = NULL;
	loopsw1 = LOOP;
	while (loopsw1 == LOOP) {
				/* ＬＡデータセット名，入力ファイル名の入力 */
		if (dtcm41() == STOP) {
			loopsw1 = LOOP_END;
			continue;
		}
		dtcm43(); 		/* ＬＡデータセット格納処理 */
	}

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 格納処理 [ＬＡデータセット名，入力ファイル名の入力処理] */
static
dtcm41( )
{
					/* 画面 [6-3] 処理  */
	int	rtn_inf;
	char	ls_com[80], s[128], s2[128];
	static char	*func63a[8] = { 	/* description of func-key */
		"実  行", NULL, NULL, "全格納",
		NULL, "file一覧", NULL, NULL
	};
	static char	*func63b[8] = { 	/* description of func-key */
		NULL, NULL, "はい", "いいえ",
		NULL, NULL, NULL, NULL
	};
	static	char	msg[] =
		"全ファイルを格納します。よろしいですか　（はい／いいえ） ";
	int	x, y;
	chtype	ch;

	rtn_inf = NULL;
 start:
	if (pfp != 0) {
		if (fgets(file_name, sizeof(file_name), pfp) == 0) {
			pclose(pfp);
			pfp = NULL;
		} else {
			*strchr(file_name, 0x0a) = '\0';
			wmove(pad, 5, 32);
			wclrtoeol(pad);
			waddstr(pad, file_name);
			prefresh(pad,0,0,0,0,l_num,80);
			return(rtn_inf);
		}
	}

	dataset_name[0] = NULL;
	file_name[0] = NULL;
	field_no = 1;
	field_max = 2;

	SCR_CLEAR;
	DSP_PN_DR;

	while (field_no != FIELD_END) {
		mvwaddstr(pad, 1, 0,
			"＜  格納  ＞");
		mvwaddstr(pad, 5, 10, "外部表現ファイル名  : ");
		/* mvwaddstr(pad, 5, 10, "入力ファイル名      : "); */
		mvwaddstr(pad, 7, 10, "ＬＡデータセット名  : ");

		if (field_no == 1) {
		 	/* ファイル名の入力 */
			SCR_GETS(5, 32, 14, file_name, func63a);
		}
		if (field_no == 2) {
			/* データセット名の入力 */
			SCR_GETS(7, 32, 12, dataset_name, func63a);
		}

		switch (f_key) {
		case 0x0a :
		case KEY_UP :
		case KEY_DOWN :
			if (field_no == field_max)
				field_no = 1;
			else
				field_no++;
			break;
		case KEY_F(1) : 			/* 実行 */
			if (file_name[0] != NULL)
				field_no = FIELD_END;
			break;
		case KEY_F(4):			/* 全登録 */
			wmove(pad, 21, 2);
			wclrtoeol(pad);
			waddstr(pad, msg);
			d3fcmk(func63b);
			getyx(pad, y, x);
			for (;;) {
				ch = d3chin(y, x, 0, 0); 
				if (ch == KEY_F(3)) {
					sprintf(ls_com, "ls %s", dir_name);
					pfp = popen(ls_com, "r");
					goto start;
				} else if (ch == KEY_F(4))
					break;
			}
			wmove(pad, 21, 2);
			wclrtoeol(pad);
			break;
		case KEY_F(6):			/* file一覧 */
			if (field_no == 1) {
				sprintf(s2, "%s/%s", dir_name, file_name);
				d3fsel(s2, s, 0);
				if (s[0] != '\0') {
					strcpy(file_name, s);
					field_no = 2;
				}
			} else {
				sprintf(s2, "%s/%s", cur_ladb,
					dataset_name[0] ?
						dataset_name : "[!A-Z]*");
				d3fsel(s2, s, 0);
				if (s[0] != '\0') {
					strcpy(dataset_name, s);
					field_no = 1;
				}
			}
			SCR_CLEAR;
			DSP_PN_DR;
			mvwaddstr(pad, 5, 32, file_name);
			mvwaddstr(pad, 7, 32, dataset_name);
			break;
		case KEY_F(17):			/* 強制終了 */
			rtn_inf = STOP;
			field_no = FIELD_END;
			break;
		default :
			break;
		}
	}

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 格納処理 [確認画面処理] */
static
dtcm42( )
{
	int	rtn_inf = NULL;
	static char	*func63b[8] = { 	/* description of func-key */
		NULL, NULL, "はい", "いいえ",
		NULL, NULL, NULL, NULL
	};

	loopswg = LOOP 	;

	while (loopswg == LOOP) {
		*pno = NULL;
		d3msgh(E_LADS_OVW);			/* メッセージ通知 */
		mvwaddstr(pad, 21, 2,
"指定ＬＡデータセットは,既に存在しています。上書きしますか（はい／いいえ）");

		SCR_GETS(21, 78, 0, pno, func63b);
		switch (f_key) {
		case 0x0a :
			break;
		case KEY_F(3) : 			/* はい */
			rtn_inf = YES;
			loopswg = LOOP_END;
			break;
		case KEY_F(4) : 			/* いいえ */
			rtn_inf = NO;
			loopswg = LOOP_END;
			break;
		case KEY_F(17):			/* 強制終了 */
			rtn_inf = NO;
			loopswg = LOOP_END;
			loopsw1 = LOOP_END;
			break;
		default :
			break;
		}
	}

	wmove(pad, 21, 2);
	wclrtoeol(pad);
	d3fcmk(func_null);

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 格納処理 [ＬＡデータセット格納処理] */
static
dtcm43( )
{
	int	rtn_inf = NULL;
	char	s[80];
						/* ＬＡデータセット格納 */
	if (dataset_name[0] == NULL)
		strcpy(dataset_name, "-");
	if (file_name[0] != '/') {
		sprintf(s, "%s/%s", dir_name, file_name);
		strcpy(file_name, s);
	}

	wmove(pad,l_num-2,0);
	d3fcmk(func_null);
	PREFRESH;
	SYS_CM7("d1_dsrc ",cur_ladb," ",dataset_name," "
			,file_name," 2>/dev/null");
	if (exit_inf == E_DS_U) {
		if (dtcm42() == YES)
			SYS_CM7("d1_dsrc ",cur_ladb," ",dataset_name," "
					,file_name," -w 2>/dev/null");
	} else if (exit_inf != 0) {
		ER_MES(exit_inf);
	}
	return(rtn_inf);
}

/*----------------------------------------------*/
/* 削除処理 */
static
dtcm50()
{
	static	char	title[] = "外部表現ファイル名一覧";
	static char *func[8] = {
		"削  除", NULL, NULL, NULL,
		"全指定", "全解除", NULL, NULL
	};
	static char imes1[] = "外部表現ファイルを選択してください ";
	static char imes2[] = "外部表現ファイルはありません ";
	chtype	ch;			/* input character */
	int	status;			/* return status */
	int	loop_sw;		/* loop switch */

	if (dtcm60() != 0)	/* 外部表現データ格納ディレクトリ名取り出し */
		return;

	SCR_CLEAR;
	DSP_PN_DR;

	mgno = -1;			/* reset memory group No. */

	dtcm51();			/* get dtcmup file name */

	c_pos = 0;			/* reset current position */
	loop_sw = 1;			/* loop switch on */
	while (loop_sw) {
		mvwaddstr(pad, 3, 22, title);
		wmove(pad, l_num-3, 2); wclrtoeol(pad);
		d3fcmk(func);	/* set function key */
		mvwaddstr(pad, l_num-3, 2, n_item ? imes1 : imes2);

		ch = d3item(n_item, ptr_item, 20, IT_MUL|IT_ALL, &c_pos);
		switch (ch) {
		case KEY_F(1):		/* 削除 */
			if (n_item != 0) {
				dtcm52();
				dtcm51();	/* get dtcmup file inf. */
			}
			break;
		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			loop_sw = 0;	/* reset loop switch */
			break;
		}
	}

	if (mgno >= 0)
		d3free(mgno);		/* free memory */

	return(0);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: dtcm51()
		ROUTINE		: 外部表現ファイル名取り出し
		INPUT		: none
		OUTPUT		: none
*/

static
dtcm51()
{
	static	char	msg[] = "外部表現ファイル検索中　";
	int	err;
	char	dir[128];

	wmove(pad,2,0);
	wclrtobot(pad);		/* erase window */
	mvwaddstr(pad, l_num-3, 2, msg);	/* display message */
	prefresh(pad,0,0,0,0,l_num,80);

	if (mgno >= 0)			/* use memory ? */
		d3free(mgno);		/* free memory */
	mgno = d3mgno();		/* get memory group No. */

	err = d3list(dir_name, mgno, &ptr_item, &n_item, dir);
	if (err != 0)
		d3errw(err);

	c_pos = 0;
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: dtcm52()
		ROUTINE		: 削除
		INPUT		: none
		OUTPUT		: none
*/

static
dtcm52()
{
	static	char	imes[] = "削除を実行します。実行を押してください  ";
	static	char	msg[] = "削除処理実行中　%s\n";
	static char *func[8] = {
		NULL, NULL, NULL, NULL,
		"実  行", NULL, NULL, NULL
	};
	int	i, err, sel_flag;
	chtype	ch;
	D_CELL	*ptr;
	char	name[256], buf[16];

	wmove(pad, l_num-3, 2);
	wclrtoeol(pad);		/* erase window */
	waddstr(pad, imes);
	d3fcmk(func);		/* set function key */
	for (;;) {
		ch = d3item(n_item, ptr_item, 20, 0, &c_pos);
		if (ch == KEY_F(5)) break;
		else if (ch == KEY_F(17) || ch == KEY_F(18)) return;
	}

	wmove(pad, l_num-3, 2);
	wclrtoeol(pad);		/* erase window */

	sel_flag = 0;
	for (i = 0, ptr = ptr_item; i < n_item; i++, ptr++) {
		if (ptr->flag & 0x01) {
			sel_flag = 1;
			break;
		}
	}
	if (sel_flag == 0)
		ptr_item[c_pos].flag |= 0x01;

	d3item(n_item, ptr_item, 20, IT_DSP|IT_NSL, &c_pos);

	for (i = 0, ptr = ptr_item; i < n_item; i++, ptr++) {
		if (ptr->flag & 0x01) {
			mvwprintw(pad, l_num-3, 2, msg, ptr->ptr);
			prefresh(pad,0,0,0,0,l_num,80);

			sprintf(name, "%s/%s" , dir_name, ptr->ptr);
			if (unlink(name) != 0)
				err = E_GH_A;
			else
				err = 0;
			ptr->result = err;
			if (err != 0)
				d3msgh(err);
		}
	}
	d3rslt(n_item, ptr_item);
}

/*----------------------------------------------*/
/* 外部表現データ格納ディレクトリ名取り出し     */
static
dtcm60()
{
	char	s[80];
	extern	char	*getenv();

	if ((dir_name = getenv(ENV_DIR)) == 0) {
		sprintf(s,"環境変数が設定されていません (%s)", ENV_DIR);
		mvwaddstr(w1, 0, 0, s);
		prefresh(w1,0,0,l_num-2,30,l_num-2,79);
		beep();
		d3wkey(pad);
		return(1);
	}
	return(0);
}

