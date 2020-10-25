/*	FUNC. DESCRIPTION

		表データセットデータのメンテナンス処理

		FUNC.NAME	: d0kdmt()
		INPUT		: none
		OUTPUT		: none
		REMARKS		: create	87.11.31	K.SIGEMI
				: update	88.05.19	S.AIZAWA
				: update	88.07.19	S.AIZAWA
*/
#include	<jcurses.h>
#include	<string.h>
#include	<sys/types.h>
#include	<sys/stat.h>
#include	<errno.h>
#include	<memory.h>
#include	"la_ws/include/d2lerr.h"
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/tbdefs.h"
#include	"functn.h"
#include	"d3data.h"
 
#define	PNOUTREFRESH	pnoutrefresh(pad, 0 , 0, 0, 0, l_num, 80)
#define WCLRTOEOL(y,x)	{wmove(pad, y, x); wclrtoeol(pad);}
#define	REF_TITLE	"＜  表データセットの参照  ＞"
#define	UPD_TITLE	"＜  表データセットの更新  ＞"
#define	CRE_TITLE	"＜  表データセットの作成  ＞"

#define REF_M	UT_READ|UT_NSLC|UT_NUPD|UT_NADC|UT_NDTC|UT_NADL|UT_NDTL|UT_NCOL
#define UPD_M	UT_NSLC
#define C_LEN	14

#define 	PN 	3

/* -----------------------------*/
static	int	i;
#define ARRAY_CLR(x) for(i=0;i<sizeof(x)/sizeof(x[0]);i++) x[i]

/* ---------------------------- */
#define 	SCR_CLEAR	wclear(pad);
static	chtype	f_key ;
#define SCR_GETS(l,c,ln,bf,f)  d3fcmk(f); PNOUTREFRESH; KEY_IN(l,c,ln,bf)
#define KEY_IN(l,c,ln,bf) f_key=d3chin(l,c,ln,bf)

#define 	DSP_PN_DR 	d3pned(PN); d3dred()

#define 	DIR_CALL 	d0whre(); d3pned(PN); d3dred()
#define 	JOKEN_CALL(fl) 	d0cdrf(fl); d3pned(PN); d3dred()
#define DSP_KEKA(f,t) DSP_PN_DR;d3keka(f,t);SCR_CLEAR;

#define 	ER_MES(x) 	d3errw(x)

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/* -------------------------------- */
/* DESCRIPTION of TMP     FILE      */
/*                                  */
static char 	tmp_name[128];	 	/* tmp-file name */
		  /*  = "/usr/tmp/aaaaxxxxxx......"; */
static	FILE	*fp_tmp		;
#define 	GET_TMP 	tmpnam(tmp_name)
#define TMP_OPEN(x) if(NULL==(fp_tmp=fopen(tmp_name,x))) { ER_MES(E_TF_A); }
#define 	TMP_CLOSE	if(NULL!=fclose(fp_tmp)) { ER_MES(E_TF_A);}

/* -------------------------------- */
/*                                  */
#define	READ(bf,fp) if(NULL!=fgets(bf,sizeof(bf)-1,fp)){ *(strchr(bf,'\n'))=NULL;
#define INVALID     }else{;
#define ENDRD       }

/* -------------------------------- */
/* PROCESS DEFINE                   */
#define 	LOOP 		1
#define 	LOOP_END 	0

#define 	YES 	300
#define 	NO 	400
#define 	DIR 	600

#define		STOP  	700    		/* 中止 */
#define		CONT  	800   		/* continue */
#define		END  	800   		/* 終了 */

/* -------------------------------- */
/* WORK AREA of kdmt                */
static	int	syori_status;
#define 	SINGLE 	1
#define 	MULTI	2
static	int	dataset_mode;
#define 	MANUAL 	1
#define 	JOKENF 	2
static	char	dataset_name[128];
static	char	tbl_file[128];		/* 表形式ファイル名 */
static	char	des_file[128];		/* 表定義ファイル名 */
static	char	joken_bf[128];		/* 条件 file read buffer */

static	unsigned int 	msg_ctr;

static	char	pno[100];		/* content of input */
static	chtype	fno[10];		/* inputed function no */


static	char	sub_title1[] = "＜　更新　＞";
static	char	sub_title2[] = "＜　抽出　＞";
static	char	sub_title3[] = "＜　格納　＞";
/*---------------------------------------------*/
/* main part */
d0kdmt()
{
	static char	title[] =  " ";
	static char	*item[4] = { 	/* komoku */
		" １  更新  " ,
		" ２  抽出  " ,
		" ３  格納  " ,
		NULL
	};
	static char	imes[] = "処理を選択してください  ";
	static char	*func[8] = { 	/* description of func-key */
		NULL,  NULL,  NULL,  NULL,
		NULL,  NULL,  "LADB変更",  NULL
	};
	int	rtn_inf = NULL;
	int	loopsw;

	kdmt01();				/* 初期処理 */

	loopsw = LOOP;
	while (loopsw == LOOP) {
		SCR_CLEAR;
		DSP_PN_DR;
		*pno = NULL;
		d3mnmk(0, title, item, 3, func, imes, pno, fno);
		switch (*fno) {
		case '0' :
			switch (*pno) {
			case 1 :
				kdmt10();	/* 更新処理 */
				break;
			case 2 :
				kdmt20();	/* 抽出処理 */
				break;
			case 3 :
				kdmt30();	/* 格納処理 */
				break;
			default :
				break;
			}
			break;
		case '7' :		/* LADB変更 */
			d0whre();
			break;
		case 'A':		/* 強制終了 */
		case 'E':		/* 正常終了 */
			loopsw = LOOP_END;
			break;
		default :
			break;
		}
	}

	kdmt90( ); 			/* 終了 処理 */

	return(rtn_inf);
}

/*----------------------------------------------*/
/* 初期処理 */
static
kdmt01()
{

	ARRAY_CLR(rst_tbl) = -1;
	msg_ctr = 0;
	syori_status = NULL;

	GET_TMP;    				/* 条件検索 file */

	return(NULL);
}

/*----------------------------------------------*/
/*	更新処理	*/
static
kdmt10()
{
	int	rtn_inf = NULL, err, loopsw;
	long 	f_offset;

	loopsw = LOOP;
	while (loopsw == LOOP) {
		switch (syori_status) {		/* 処理ステータス 判断 */
		case NULL:
			break;
		case SINGLE:
			TMP_CLOSE;
			break;
		case MULTI:
			f_offset = ftell(fp_tmp);
			READ(joken_bf, fp_tmp)
				fseek(fp_tmp, f_offset, 0);
			INVALID
				DSP_KEKA(fp_tmp, rst_tbl);
				TMP_CLOSE;
				ARRAY_CLR(rst_tbl) = -1;
				msg_ctr = 0;
				syori_status = NULL;
			ENDRD
			break;
		}

		if (syori_status != MULTI) {
			if (kdmt13() == END) {
				loopsw = LOOP_END;
				continue;
			}
		}

		if (kdmt12() != YES)	/* 画面 [3-2] 処理 */
			continue;

		err = kdmt14();		/* 表操作 */
		rst_tbl[msg_ctr++] = err;

		/* 処理件数が３０００件を越える時は、処理を中止する */
		if (msg_ctr >= RST_MAX)
			kdmt80();
	}

	return(rtn_inf);
}
/*-----------------------------------------------*/
/* 実行確認処理  */
static
kdmt12()
{
	int	rtn_inf = NULL;
	static char	*func3[8] = { 	/* description of func-key */
		NULL, NULL, "はい", "いいえ",
		NULL, NULL, NULL, NULL
	};
	int	loopsw;

	if (syori_status == SINGLE || syori_status == MULTI) {
		READ(joken_bf, fp_tmp)
			;
		INVALID
			ER_MES(E_TF_A);
			rst_tbl[msg_ctr++] = E_TF_A;
			return(NO);
		ENDRD
	}
	if (syori_status != MULTI)
		return(YES);

	loopsw = LOOP;

	SCR_CLEAR;
	DSP_PN_DR;
	mvwaddstr(pad, 1, 0, sub_title1);

	while (loopsw == LOOP) {
		*pno = NULL;
		mvwaddstr(pad, 5, 10, "ＬＡデータセット名 : ");
		waddstr(pad, joken_bf);
		mvwaddstr(pad, 21, 3, "実行しますか（はい／いいえ）");

		SCR_GETS(21, 31, 5, pno, func3);
		switch (f_key) {
		case KEY_F(3): 			/* はい */
			rtn_inf = YES;
			loopsw = LOOP_END;
			break;
		case KEY_F(4): 			/* いいえ */
			rtn_inf = NO;
			loopsw = LOOP_END;
			rst_tbl[msg_ctr++] = -1;
			break;
		case KEY_F(17):			/* 強制終了 */
			rtn_inf = STOP;
			loopsw = LOOP_END;
			kdmt80(); 			/* 中止 処理 */
			break;
		default:
			break;
		}
	}

	return(rtn_inf);
}

/*-----------------------------------------------*/
/* ＬＡデータセット名入力処理  */
static
kdmt13( )
{
	int	rtn_inf = NULL;
	static char	*func1[8] = { 	/* description of func-key */
		NULL, NULL, NULL, NULL,
		"条件検索", "file一覧", NULL, NULL
	};
	char	s[128], s2[128];
	int	loopsw;

	ARRAY_CLR(rst_tbl) = -1;
	msg_ctr = 0;
	dataset_name[0] = NULL;

	loopsw = LOOP;

	SCR_CLEAR;
	DSP_PN_DR;

	while (loopsw == LOOP) {
		mvwaddstr(pad, 1, 0, sub_title1);
		mvwaddstr(pad, 5, 10, "ＬＡデータセット名 : ");

		SCR_GETS(5, 32, 12, dataset_name, func1);
		switch (f_key) {
		case 0x0a:
			if (dataset_name[0] == NULL) {
				beep();
				break;
			}
			dataset_mode = MANUAL;
			syori_status = NULL;
			loopsw = LOOP_END;
			break;
		case KEY_F(5): 			/* 条件 検索 */
			dataset_name[0] = NULL;
			JOKEN_CALL(tmp_name);
			dataset_mode = JOKENF;
			TMP_OPEN("r");

			loopsw = LOOP_END;

			READ(joken_bf, fp_tmp)
				syori_status = SINGLE;
			INVALID
				loopsw = LOOP;
				TMP_CLOSE;
				break;
			ENDRD

			READ(joken_bf, fp_tmp)
				syori_status = MULTI;
			INVALID
				;
			ENDRD
			rewind(fp_tmp);
			break;
		case KEY_F(6):			/* file一覧 */
			sprintf(s2, "%s/%s", cur_ladb,
				dataset_name[0] ? dataset_name : "[!A-Z]*");
			d3fsel(s2, s, 0);
			if (s[0] != '\0')
				strcpy(dataset_name, s);
			SCR_CLEAR;
			DSP_PN_DR;
			break;
		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			dataset_name[0] = NULL;
			rtn_inf = END;
			loopsw = LOOP_END;
			break;
		default:
			break;
		}
	}

	return(rtn_inf);
}

/*-----------------------------------------------*/
/*	表操作		*/
static
kdmt14( )
{
	char	main_title[80], path_name[128], column_name[C_LEN];
	struct	stat	stat_buf;
	int	err, mode;
	char	*title, *name;
	chtype	rtn;

	mode = UPD_M;
	name = dataset_mode == MANUAL ? dataset_name : joken_bf;

	if (d3xdat(name) == 0) {
		d3errw(E_DSNAME);
		return(E_DSNAME);
	}

	strcpy(column_name, "ファイル名");

	d3path(cur_ladb, name, path_name);
	err=stat(path_name, &stat_buf);
	if (err == -1 && errno == ENOENT) {	/* THERE IS NO FILE */

		/***********************************************************/
		mvwaddstr(pad, l_num-3, 2,
			"第１列のラベル名を入力してください");
		for (;;) {
			PNOUTREFRESH ;
			memset(column_name, '\0', C_LEN);
			rtn = d3chin(l_num-3, 38, C_LEN, column_name);
			if (rtn == 0x0a && column_name[0] != '\0')
				break;
			else if (rtn ==  KEY_F(17) || rtn == KEY_F(18)) {
				WCLRTOEOL(l_num-3, 2); WCLRTOEOL(l_num-2, 2);
				return(0);
			}
		}
		/***********************************************************/

		title = CRE_TITLE;
		mode |= UT_UIDX;
		if ((mode & UT_READ) == UT_READ) {/* if read_mode then error */
			return(E_DS_N);
		}
	} else if (err == -1 || stat_buf.st_size == 0) {
		return(E_DS_A);
	} else {
		if ((mode & UT_READ) == UT_READ)
			title = REF_TITLE;
		else
			title = UPD_TITLE;
	}

	sprintf(main_title, "     %-40s             < %s >",
		title, name);

	return(d3tbhn(cur_ladb, name, mode, column_name, 1, C_LEN, main_title));
}

/*----------------------------------------------*/
/*	抽出処理	*/
static
kdmt20()
{
	int	rtn_inf = NULL, err, loopsw;
	long 	f_offset;

	dataset_name[0] = NULL;
	tbl_file[0] = NULL;

	loopsw = LOOP;
	while (loopsw == LOOP) {
		switch (syori_status) {		/* 処理ステータス 判断 */
		case NULL:
			break;
		case MULTI:
			f_offset = ftell(fp_tmp);
			READ(dataset_name, fp_tmp)
				fseek(fp_tmp, f_offset, 0);
			INVALID
				DSP_KEKA(fp_tmp, rst_tbl);
				TMP_CLOSE;
				ARRAY_CLR(rst_tbl) = -1;
				msg_ctr = 0;
				syori_status = NULL;
			ENDRD
			break;
		}

		if (kdmt23() == END) {
			if (syori_status != MULTI)
				loopsw = LOOP_END;
			continue;
		}

		err = kdmt24();		/* 抽出処理 */
		rst_tbl[msg_ctr] = err;

		if (err == 0 || err == -1) {
			kdmt22();	/* ＬＡデータセット名読み込み */
			dataset_name[0] = NULL;
			tbl_file[0] = NULL;
		}

		/* 処理件数が３０００件を越える時は、処理を中止する */
		if (msg_ctr >= RST_MAX)
			kdmt80();
	}

	return(rtn_inf);
}
/*-----------------------------------------------*/
/* ＬＡデータセット名読み込み（条件検索） */
static
kdmt22()
{
	if (syori_status == MULTI) {
		READ(dataset_name, fp_tmp)
			;
		INVALID
			ER_MES(E_TF_A);
			rst_tbl[msg_ctr] = E_TF_A;
		ENDRD
	}
	msg_ctr++;
}

/*-----------------------------------------------*/
/* ＬＡデータセット名入力処理  */
static
kdmt23( )
{
	int	rtn_inf = NULL;
	static char	*func1[8] = { 	/* description of func-key */
		"実  行", NULL, NULL, NULL,
		"条件検索", "file一覧", NULL, NULL
	};
	static char	*func2[8] = { 	/* description of func-key */
		"実  行", NULL, NULL, NULL,
		NULL, "file一覧", NULL, NULL
	};
	int	icont, loopsw;
	char	s[128], s2[128];

	icont = (syori_status != MULTI) ? 0 : 1;

	loopsw = LOOP;

	while (loopsw == LOOP) {
		SCR_CLEAR;
		DSP_PN_DR;

		mvwaddstr(pad, 1, 0, sub_title2);
		mvwaddstr(pad, 5, 10, "ＬＡデータセット名 : ");
		mvwaddstr(pad, 7, 10, "表形式ファイル名   : ");

		mvwaddstr(pad, 5, 32, dataset_name);
		mvwaddstr(pad, 7, 32, tbl_file);

		if (icont == 0) {
			SCR_GETS(5, 32, 12, dataset_name, func1);
		} else {
			SCR_GETS(7, 32, 48, tbl_file, func2);
			d3cven(tbl_file);	/* 環境変数展開 */
		}
		switch (f_key) {
		case KEY_F(1):			/* 実行 */
			if (dataset_name[0] == NULL || tbl_file[0] == NULL) {
				beep();
				break;
			}
			loopsw = LOOP_END;
			break;
		case KEY_F(5): 			/* 条件 検索 */
			if (icont != 0)
				break;
			dataset_name[0] = NULL;
			JOKEN_CALL(tmp_name);
			TMP_OPEN("r");

			READ(joken_bf, fp_tmp)
				strcpy(dataset_name, joken_bf);
			INVALID
				TMP_CLOSE;
				break;
			ENDRD

			READ(joken_bf, fp_tmp)
				syori_status = MULTI;
				icont = 1;
			INVALID
				TMP_CLOSE;
				break;
			ENDRD
			rewind(fp_tmp);
			break;
		case KEY_F(6):			/* file一覧 */
			if (icont == 0) {
				sprintf(s2, "%s/%s", cur_ladb,
				 dataset_name[0] ? dataset_name : "[!A-Z]*");
				d3fsel(s2, s, 0);
				if (s[0] != '\0')
					strcpy(dataset_name, s);
			} else {
				d3fsel(tbl_file, s, 1);
				if (s[0] != '\0')
					strcpy(tbl_file, s);
			}
			break;
		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			if (syori_status == MULTI)
				kdmt80();	/* 中止処理 */
			rtn_inf = END;
			loopsw = LOOP_END;
			break;
		case '\n':
		case KEY_UP:
		case KEY_DOWN:
			if (syori_status != MULTI) {
				if (icont == 0)
					icont = 1;
				else
					icont = 0;
			}
			break;
		default:
			break;
		}
	}

	return(rtn_inf);
}

/*-----------------------------------------------*/
/*	抽出処理	*/
static
kdmt24( )
{
	int	err;
	char	cmd[256];

	if (d3xdat(dataset_name) == 0) {
		d3errw(E_DSNAME);
		return(E_DSNAME);
	}

	sprintf(cmd, "d1_tbgt %s %s>%s 2>/dev/null",
				cur_ladb, dataset_name, tbl_file);
	err = system(cmd) >> 8;
	if (err == 0)
		chmod(tbl_file, 0666);
	else {
		unlink(tbl_file);
		d3errw(err);
	}

	return(err);
}

/*----------------------------------------------*/
/*	格納処理	*/
static
kdmt30()
{
	int	rtn_inf = NULL, err, loopsw;
	long 	f_offset;

	dataset_name[0] = NULL;
	tbl_file[0] = NULL;
	des_file[0] = NULL;

	loopsw = LOOP;
	while (loopsw == LOOP) {
		switch (syori_status) {		/* 処理ステータス 判断 */
		case NULL:
			break;
		case MULTI:
			f_offset = ftell(fp_tmp);
			READ(dataset_name, fp_tmp)
				fseek(fp_tmp, f_offset, 0);
			INVALID
				DSP_KEKA(fp_tmp, rst_tbl);
				TMP_CLOSE;
				ARRAY_CLR(rst_tbl) = -1;
				msg_ctr = 0;
				syori_status = NULL;
			ENDRD
			break;
		}

		if (kdmt33() == END) {
			if (syori_status != MULTI)
				loopsw = LOOP_END;
			continue;
		}

		err = kdmt34();		/* 格納処理 */
		rst_tbl[msg_ctr] = err;

		if (err == 0 || err == -1) {
			kdmt32();	/* ＬＡデータセット名読み込み */
			dataset_name[0] = NULL;
			tbl_file[0] = NULL;
			des_file[0] = NULL;
		}

		/* 処理件数が３０００件を越える時は、処理を中止する */
		if (msg_ctr >= RST_MAX)
			kdmt80();
	}

	return(rtn_inf);
}
/*-----------------------------------------------*/
/* ＬＡデータセット名読み込み（条件検索） */
static
kdmt32()
{
	if (syori_status == MULTI) {
		READ(dataset_name, fp_tmp)
			;
		INVALID
			ER_MES(E_TF_A);
			rst_tbl[msg_ctr] = E_TF_A;
		ENDRD
	}
	msg_ctr++;
}

/*-----------------------------------------------*/
/* ＬＡデータセット名入力処理  */
static
kdmt33( )
{
	int	rtn_inf = NULL;
	static char	*func1[8] = { 	/* description of func-key */
		"実  行", NULL, NULL, NULL,
		"条件検索", "file一覧", NULL, NULL
	};
	static char	*func2[8] = { 	/* description of func-key */
		"実  行", NULL, NULL, "エディタ",
		NULL, "file一覧", NULL, NULL
	};
	int	icont, loopsw;
	char	s[128], s2[128];

	icont = (syori_status != MULTI) ? 0 : 1;

	loopsw = LOOP;

	while (loopsw == LOOP) {
		SCR_CLEAR;
		DSP_PN_DR;

		mvwaddstr(pad, 1, 0, sub_title3);
		mvwaddstr(pad, 5, 10, "ＬＡデータセット名 : ");
		mvwaddstr(pad, 7, 10, "表形式ファイル名   : ");
		mvwaddstr(pad, 9, 10, "表定義ファイル名   : ");

		mvwaddstr(pad, 5, 32, dataset_name);
		mvwaddstr(pad, 7, 32, tbl_file);
		mvwaddstr(pad, 9, 32, des_file);

		switch (icont) {
		case 0:
			SCR_GETS(5, 32, 12, dataset_name, func1);
			break;
		case 1:
			SCR_GETS(7, 32, 48, tbl_file, func2);
			d3cven(tbl_file);	/* 環境変数展開 */
			break;
		case 2:
			SCR_GETS(9, 32, 48, des_file, func2);
			d3cven(des_file);	/* 環境変数展開 */
			break;
		}
		switch (f_key) {
		case KEY_F(1):			/* 実行 */
			if (dataset_name[0] == NULL
					|| tbl_file[0] == NULL
					|| des_file[0] == NULL) {
				beep();
				break;
			}
			loopsw = LOOP_END;
			break;
		case KEY_F(4):			/* エディタ */
			switch (icont) {
			case 1:
				if (tbl_file[0] != '\0') {
					clear();
					slk_clear();
					refresh();
					endwin();
					d3exec("jvi", tbl_file, 0);
				}
				break;
			case 2:
				if (des_file[0] != '\0') {
					clear();
					slk_clear();
					refresh();
					endwin();
					d3exec("jvi", des_file, 0);
				}
				break;
			}
			break;
		case KEY_F(5): 			/* 条件 検索 */
			if (icont != 0)
				break;
			dataset_name[0] = NULL;
			JOKEN_CALL(tmp_name);
			TMP_OPEN("r");

			READ(joken_bf, fp_tmp)
				strcpy(dataset_name, joken_bf);
			INVALID
				TMP_CLOSE;
				break;
			ENDRD

			READ(joken_bf, fp_tmp)
				syori_status = MULTI;
				icont = 1;
			INVALID
				TMP_CLOSE;
				break;
			ENDRD
			rewind(fp_tmp);
			break;
		case KEY_F(6):			/* file一覧 */
			switch (icont) {
			case 0:
				sprintf(s2, "%s/%s", cur_ladb,
				 dataset_name[0] ? dataset_name : "[!A-Z]*");
				d3fsel(s2, s, 0);
				if (s[0] != '\0')
					strcpy(dataset_name, s);
				break;
			case 1:
				d3fsel(tbl_file, s, 1);
				if (s[0] != '\0')
					strcpy(tbl_file, s);
				break;
			case 2:
				d3fsel(des_file, s, 1);
				if (s[0] != '\0')
					strcpy(des_file, s);
				break;
			}
			break;
		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			if (syori_status == MULTI)
				kdmt80();	/* 中止処理 */
			rtn_inf = END;
			loopsw = LOOP_END;
			break;
		case '\n':
		case KEY_DOWN:
			if (icont == 2) {
				if (syori_status != MULTI)
					icont = 0;
				else
					icont = 1;
			} else
				icont++;
			break;
		case KEY_UP:
			if (syori_status != MULTI) {
				if (icont == 0)
					icont = 2;
				else
					icont--;
			} else {
				if (icont == 1)
					icont = 2;
				else
					icont--;
			}
			break;
		}
	}

	return(rtn_inf);
}

/*-----------------------------------------------*/
/*	格納処理	*/
static
kdmt34( )
{
	static	char	msg[] =
 "指定されたＬＡデータセットは既に存在します。上書きしますか（はい／いいえ）";
	static char	*func[8] = { 	/* description of func-key */
		NULL, NULL, "はい", "いいえ",
		NULL, NULL, NULL, NULL
	};
	int	err;
	chtype	ch;
	char	cmd[256];

	if (d3xdat(dataset_name) == 0) {
		d3errw(E_DSNAME);
		return(E_DSNAME);
	}

	sprintf(cmd, "d1_tbrg %s %s %s %s 2>/dev/null",
				cur_ladb, dataset_name, tbl_file, des_file);
	err = system(cmd) >> 8;
	if (err == E_DS_U) {
		for (;;) {
			d3fcmk(func);
			mvwaddstr(pad, 21, 2, msg);
			ch = d3chin(21, 79, 0, 0);
			if (ch == KEY_F(3))
				break;
			else if (ch == KEY_F(4))
				return(-1);
		}
		sprintf(cmd, "d1_tbrg %s %s %s %s -w 2>/dev/null",
				cur_ladb, dataset_name, tbl_file, des_file);
		err = system(cmd) >> 8;
		if (err != 0)
			d3errw(err);
	} else if (err != 0)
		d3errw(err);

	return(err);
}

/*-----------------------------------------------*/
/* 中止 処理 */
static
kdmt80( )
{
	int	rtn_inf = NULL;

	rst_tbl[msg_ctr++] = E_E; 		/* set 中止 コード */
	if (syori_status == MULTI) {
		if (fseek(fp_tmp, 0, 2) != NULL) {
			rtn_inf = E_TF_A;
			ER_MES(rtn_inf);
		}
	}

	return(rtn_inf);
}

/*---------------------------------------------*/
/* 終了 処理 */
static
kdmt90( )
{
	int	rtn_inf = NULL;

	unlink(tmp_name);
	return(rtn_inf);
}

