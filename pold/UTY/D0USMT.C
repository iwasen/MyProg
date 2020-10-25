/**********************************************************
 *
 *	FUNCTION.DESCRIPTION	maintenace of user-index
 *	FILE		d0usmt.c
 *	ROUTINE		d0usmt()
 *	COMMENT		we are in curses mode !
 *			we consider "pad" and "w1" already exist
 *	REMARKS		1988/05/17	S.AIZAWA
 *
 **********************************************************/

#include <jcurses.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include "la_ws/include/d2lerr.h"
#include "la_ws/include/tbdefs.h"
#include "functn.h"
#include "d3item.h"
#include "d3data.h"
#include "la_ws/include/d3libc.h"

#define	PD_ERROR(err)	d3errw(err)
#define	WCLRTOEOL(y,x)	{wmove(pad,y,x); wclrtoeol(pad);}

#define MES_CONFIRM_D	"削除を実行します。実行を押してください  "
#define MES_SELECTION	"ユーザ索引を選択してください  "
#define MES_NONE_DAT	"ユーザ索引はありません  "

#define	MAIN_TITLE	"＜　ユーザ索引の保守  ＞"
#define	SUB_TITLE	"ユーザ索引一覧"
#define	REF_TITLE	"＜  ユーザ索引の参照  ＞"
#define	UPD_TITLE	"＜  ユーザ索引の更新  ＞"
#define	CRE_TITLE	"＜  ユーザ索引の作成  ＞"

#define REF_M	UT_READ|UT_NSLC|UT_NUPD|UT_NADC|UT_NDTC|UT_NADL|UT_NDTL|UT_NCOL
#define UPD_M	UT_NSLC
#define CRE_M	UT_NSLC
#define ENTER	IT_MUL|IT_ALL
#define	DISPL	IT_DSP|IT_NSL

extern	DSET	*d3lbgn();
extern	chtype	d3item();
extern	char	*d3allc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	FILE	*debug;
static	char	*pf_label_1[] = {
		"削  除","更  新","新規作成","参  照",
		"全指定","全解除","LADB変更",NULL
	};
static	char	*pf_label_2[] = {
		NULL,NULL,"新規作成",NULL,
		NULL,NULL,"LADB変更",NULL
	};
static	char	*pf_label_3[] = {
		NULL,NULL,NULL,NULL,
		NULL,NULL,"LADB変更",NULL
	};
static	char	*pf_label_4[] = {
		NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
	};
static	char	*pf_label_5[] = {
		NULL, NULL, NULL, NULL,"実  行", NULL, NULL, NULL
	};
static	char	**label_p;
static	DSET	*dsp;
static	D_CELL	*cell_p;
static	PD_KEYREC	*key_rec_p;
static	int	item_n, open_flag;

d0usmt()
{
	char	c_cur_ladb[128];
	int	loop, err;
	int	width, c_pos, pf_no;
	chtype	pf_key;

	dsp = NULL;
	key_rec_p = NULL;

	width=20; c_pos=0;
	open_flag = 0;
	loop=1;				/* loop on */
 
	while (loop) {
		if (open_flag == 0) {
			err = usmt03();		/* open "IND.USR" */
			if (dsp != NULL) {
				if (item_n != 0) {
					pf_no = 0;
					label_p = pf_label_1;
				} else {
					pf_no = 1;
					label_p = pf_label_2;
				}
			} else {
				pf_no = 2;
				label_p = pf_label_3;
			}
			usmt02();		/* SETUP GAMEN */
			if (err != 0) {
				prefresh(pad,0,0,0,0,l_num,80);
				PD_ERROR(err);
			}
			c_pos = 0;
		} else
			usmt02();		/* SETUP GAMEN */

		mvwaddstr(pad, l_num-3, 2,
				pf_no == 0 ? MES_SELECTION : MES_NONE_DAT);

		pf_key=d3item(item_n, cell_p, width, ENTER, &c_pos);
					/* SELECT CELL & SOFT LABEL! */

		switch(pf_key){
		case	KEY_F(1):	/* 削除 */
			if (pf_no == 0) {
				usmt40(item_n, cell_p, c_pos, width);
				usmt04(); /* close "IND.USR" */
			}
			break;
		case	KEY_F(2):	/* 更新 */
			if (pf_no == 0)
				usmt10(item_n, cell_p, c_pos, UPD_M);
			break;
		case	KEY_F(3):	/* 新規作成 */
			if (pf_no != 2) {
				d3item(item_n, cell_p, width, DISPL, &c_pos);
				usmt30();
				usmt04(); /* close "IND.USR" */
			}
			break;
		case	KEY_F(4):	/* 参照 */
			if (pf_no == 0)
				usmt10(item_n, cell_p, c_pos, REF_M);
			break;
		case	KEY_F(7):	/* LADB変更 */
			strcpy(c_cur_ladb, cur_ladb);	/* save current ladb */
			d0whre();
			d3dred();
			if (strcmp(c_cur_ladb, cur_ladb) != 0) {
				usmt04(); /* close "IND.USR" */
			}
			break;
		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			loop=0;
			break;
		}
	}

	usmt04();			/* close "IND.USR" */

	return(0);
}

/***************************************
 *	usmt02	SETUP GAMEN
 ***************************************/
static	usmt02()
{
	werase(pad);		/* GAMEN clear ! */

	mvwaddstr(pad, 0, 2, MAIN_TITLE); /* display TITLE */
	mvwaddstr(pad, 3, 26, SUB_TITLE); /* display ITEM NAME */
	d3dred();		/* display LADB */
	d3fcmk(label_p);	/* display SOFT LABEL KEY */
 
	return(0);
}

/***************************************
 *	usmt03	NEW IND.USR
 ***************************************/
static	usmt03()
{
	int	err, l_flag=0, i;
	char	path_name[128];

	open_flag = 1;
	item_n = 0;

	d3path(cur_ladb, "IND.USR", path_name);
	dsp=d3lbgn(path_name, "r", l_flag, &err);
	if (dsp==NULL) {
		return(d3cver(err, "if"));
	}

	err=d3tkyv(dsp, 1, &key_rec_p);	/* SYSTEM-INDEX NAME */
	if (err != 0) {
		if (err != DE_NONE_DAT)
			return(d3cver(err, "if"));
	} else
		item_n=key_rec_p->key_length;

	if ((cell_p = (D_CELL *)d3allc(dsp->ds_memno,
				sizeof(D_CELL) * item_n)) == NULL) {
		item_n = 0;
		return(E_M);
	}

	for (i=0; i<item_n; i++) {	/* SETUP D_CELL */
		(cell_p+i)->flag   = 0;
		(cell_p+i)->result = 0;
		(cell_p+i)->ptr    = key_rec_p->key_type.c[i];
	}
	return(0);
}

/***************************************
 *	usmt04	close "IND.USR"
 ***************************************/
static	usmt04()
{
	open_flag = 0;

	if (key_rec_p != NULL) {
		free((char *) key_rec_p);
		key_rec_p = NULL;
	}
	if (dsp != NULL) {
		d3lend(dsp, "q", 0);
		dsp = NULL;
	}

	return(0);
}

/***************************************
 *	usmt10	表操作
 ***************************************/
static	usmt10(item_n, cell_p, c_pos, mode)
D_CELL	*cell_p;
int	item_n, c_pos, mode;
{
	int	f_no;

	if ((f_no=d3slct(item_n, cell_p, 0)) == -1)
		f_no=c_pos;

	return(usmt11((cell_p+f_no)->ptr, mode));
}

static	usmt11(name, mode)
char	*name;
int	mode;
{
	char	main_title[80], path_name[128];
	struct	stat	stat_buf;
	int	err;
	char	*title;

	d3path(cur_ladb, name, path_name);
	err=stat(path_name, &stat_buf);
	if (err == -1 && errno == ENOENT) {	/* THERE IS NO FILE */
		title = CRE_TITLE;
		mode |= UT_UIDX;
		if ((mode & UT_READ) == UT_READ) {/* if read_mode then error */
			PD_ERROR(E_UF_N);
			return(-1);
		}
	} else if (err == -1 || stat_buf.st_size == 0) {
		PD_ERROR(E_UF_A); return(-1);
	} else {
		if ((mode & UT_READ) == UT_READ)
			title = REF_TITLE;
		else
			title = UPD_TITLE;
	}

	sprintf(main_title, "     %-40s             < %s >",
		title, name);

	d3tbhn(cur_ladb, name, mode, "ファイル名", 1, 12, main_title);

	return(0);
}

/***************************************
 *	usmt30	新規作成
 ***************************************/
static	usmt30()
{
	char	s[14];
	int	rst, err;

	wmove(pad, l_num-3,2);
	wclrtoeol(pad);
	waddstr(pad, "ユーザ索引名を入力してください");
	d3fcmk(pf_label_4);
	pnoutrefresh(pad,0,0,0,0,l_num,80); /* display screen */

	s[0] = '\0';
	for (;;) {
		rst = d3chin(l_num-3, 34, 12, s);
		if (rst == 0x0a) {
			if (d3xusr(s) != 0) {
				break;
			} else
				beep();
		} else if (rst == KEY_F(17))
			return(0);
	}
	err = usmt11(s, CRE_M);
	return(err);
}

/***************************************
 *	usmt40	DELETE USER INDEX
 ***************************************/
static	usmt40(item_n, cell_p, c_pos, wid)
D_CELL	*cell_p;
int	item_n, c_pos, wid;
{
	static	char	msg[] = "削除処理中  %s\n";
	int	c_p, f_no, err;
	chtype	pf_k;

	c_p = c_pos;
	d3fcmk(pf_label_5);
	WCLRTOEOL(l_num-3, 2);
	waddstr(pad, MES_CONFIRM_D);

	for (;;) {
		pf_k = d3item(item_n, cell_p, wid, 0, &c_p);
		if (pf_k == KEY_F(5)) break;
		else if (pf_k == KEY_F(17) || pf_k == KEY_F(18)) return(0);
	}

	if (d3slct(item_n, cell_p, 0) == -1)
		(cell_p + c_p) -> flag |= 0x01;

	WCLRTOEOL(l_num-3, 2);
	d3item(item_n, cell_p, wid, DISPL, &c_pos);

	for (f_no=-1; ;) {
		if ((f_no=d3slct(item_n, cell_p, f_no+1)) == -1)
			break;

		mvwprintw(pad, l_num-3, 2, msg, (cell_p+f_no)->ptr);
		prefresh(pad,0,0,0,0,l_num,80); /* display screen */

		sprintf(cmd_b, "d1_dsdl %s -nc %s 2>/dev/null",
					cur_ladb, (cell_p+f_no)->ptr);
		err = system(cmd_b) >> 8;
		(cell_p+f_no)->result=(short) err;
		if (err != 0)
			d3msgh(err);		/* エラーコード通知 */
	}

	WCLRTOEOL(l_num-3, 2);

	d3rslt(item_n, cell_p);		/* display result */

	return(0);
}
