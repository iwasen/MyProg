/**********************************************************
 *
 *	FUNCTION.DESCRIPTION	maintenace of system-index
 *	FILE		d0sind.c
 *	ROUTINE		d0sind()
 *	COMMENT		we are in curses mode !
 *			we consider "pad" and "w1" already exist
 *	REMARKS		1988/04/20	M.MOTOKI
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
#include "la_ws/include/d3nmty.h"
#include "la_ws/include/d3libc.h"

#define END_LADB	0
#define END_SIND	1
#define ERR_BUF_LEN	65+1
#define ERR_MES_LEN	50+1
#define	D_MAX_DATA	2048
#define CELL_SIZE	sizeof(D_CELL)*D_MAX_DATA
#define	PD_ERROR(err)	d3errw(err)
#define	WCLRTOEOL(y,x)	{wmove(pad,y,x); wclrtoeol(pad);}

#define MES_OK_C "ＬＡデータセットの再登録を実行します。実行を押してください  "
#define MES_OK_D "システム索引の削除を実行します。実行を押してください  "
#define MES_OK_I "システム索引の初期作成を実行します。実行を押してください  "
#define MES_SELECT	"システム索引を選択してください  "

#define	MAIN_TITLE	"＜　システム索引の保守  ＞"
#define	SUB_TITLE	"システム索引一覧"
#define	REF_TITLE	"＜  システム索引の参照  ＞"
#define	UPD_TITLE	"＜  システム索引の作成・更新  ＞"

#define REF_M	UT_READ|UT_NSLC|UT_NUPD|UT_NADC|UT_NDTC|UT_NADL|UT_NDTL|UT_NCOL
#define UPD_M	UT_NSLC|UT_NROW
#define ENTER	IT_NUM|IT_MUL|IT_ALL
#define ENTER1	IT_NUM|IT_MUL
#define	DISPL	IT_NUM|IT_DSP|IT_NSL

extern	DSET	*d3lbgn();
extern	chtype	d3item();
extern	char	*getenv(), *d3allc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	FILE	*debug;
static	char	*pf_label_1[] = {
		NULL, "索引登録", NULL, " 参  照 ",
		NULL, NULL, "LADB変更", "切り換え"
	};
static	char	*pf_label_2[] = {
		" 削  除 ", NULL, "初期作成", "索引格納",
		" 全指定 ", " 全解除 ", NULL, "切り換え"
	};
static	char	*pf_label_3[] = {
		NULL, NULL, NULL, NULL, " 実  行 ", NULL, NULL, NULL
	};
static	char	**label_p;
static	int	pf_no;
static	DSET	*g_dsp, *dsp;
static	D_CELL	*cell_p;
static	PD_KEYREC	*key_rec_p;
static	int	item_n, item_mem;

d0sind()
{
	char	temp_ladb[128];
	int	loop, err;
	int	width, c_pos;
	chtype	pf_key;

	g_dsp = dsp = NULL;
	key_rec_p = NULL;

	if ((item_mem=d3mgno()) < 0) {
		PD_ERROR(E_M);
		return(0);
	}

	if ((cell_p = (D_CELL *) d3allc(item_mem, CELL_SIZE)) == NULL) {
		PD_ERROR(E_M);
		return(0);
	}

	if (sind03() != 0) {			/* open CURRENT LADB */
		sind04(END_SIND);		/* termnate */
		return(0);
	}

	label_p = pf_label_1;	/* display SOFT LABEL KEY */
	pf_no   = 1;
	width=64; c_pos=0;
	loop=1;				/* loop on */
 
	while (loop) {
		sind02(MES_SELECT);		/* SETUP GAMEN */

		pf_key=d3item(item_n, cell_p, width,
					pf_no == 1 ? ENTER1 : ENTER, &c_pos);
					/* SELECT CELL & SOFT LABEL! */
		switch(pf_key){

		case KEY_F(1):
			if (pf_no == 1) ;
			else {	/* DELETE SYSTEM INDEX */
				err=sind40(item_n, cell_p, c_pos, width);
				err=sind06();
			}
			break;

		case KEY_F(2):
			if (pf_no == 1) {	/* 索引の登録 : 表操作 */
				err=sind10(item_n, cell_p, c_pos,
							UPD_TITLE, UPD_M);
				err=sind06();
			}
			break;

		case KEY_F(3):
			if (pf_no == 1) ;
			else {		/* システム索引の初期生成 */
				err=sind20(item_n, cell_p, c_pos, width);
				err=sind06();
			}
			break;

		case KEY_F(4):
			if (pf_no == 1) {/* システム索引の参照 : 表操作 */
				err=sind10(item_n, cell_p, c_pos,
							REF_TITLE, REF_M);
			} else {	/* システム索引の索引の再格納 */
				err=sind30(item_n, cell_p, c_pos, width);
				err=sind06();
			}
			break;

		case KEY_F(7):
			if (pf_no == 1) {	/* LADBの変更 */
				strcpy(temp_ladb, cur_ladb);/*save cur dir*/
				d0whre();
				d3dred();
				if (strcmp(temp_ladb, cur_ladb) != 0) {
					sind04(END_LADB); /* close LADB */
					if (sind03() != 0) {
						sind04(END_SIND);
						return(0);
					}		/* open NEW LADB */
				}
			}
			break;

		case KEY_F(8):		/* 切り換え */
			if (pf_no == 1) {
				label_p = pf_label_2;	/* SOFT LABEL KEY */
				pf_no   = 2;
			 } else {
				label_p = pf_label_1;	/* SOFT LABEL KEY */
				pf_no   = 1;
			}
			break;

		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			loop=0;
			break;
		}
	}

	sind04(END_SIND);			/* close LADB */

	return(0);
}

/***************************************
 *	sind02	SETUP GAMEN
 ***************************************/
static	sind02(mes)
char	*mes;
{
	werase(pad);		/* GAMEN clear ! */

	d3pned(9);
	/*mvwaddstr(pad, 0, 2, MAIN_TITLE);*/ /* display TITLE */
	mvwaddstr(pad, 3, 26, SUB_TITLE); /* display ITEM NAME */
	d3dred();		/* display LADB */
	d3fcmk(label_p);	/* display SOFT LABEL KEY */
	mvwaddstr(pad, l_num-3, 2, mes);
 
	return(0);
}

/***************************************
 *	sind03	NEW LADB
 ***************************************/
static	sind03()
{
	int	err, l_flag=0, i;
	char	path_name[128], ind_buf[128], *p, *comm;

	d3path(cur_ladb, GROUP_INDEX, path_name);
	g_dsp=d3lbgn(path_name, "r", l_flag, &err);
	if (g_dsp==NULL) {
		PD_ERROR(d3cver(err, "gi"));
		return(-1);
	}

	err=d3tkyv(g_dsp, 3, &key_rec_p);	/* SYSTEM-INDEX NAME */
	if (err != 0) {
		PD_ERROR(d3cver(err, "gi"));
		return(-1);
	}
	item_n=key_rec_p->key_length;

	for (i=0; i<item_n; i++) {	/* SETUP D_CELL */
		(cell_p+i)->flag   = 0;
		(cell_p+i)->result = 0;

		p = d3allc(item_mem, 60);
		d3path(cur_ladb, key_rec_p->key_type.c[i], ind_buf);
		if (d3fchk(ind_buf) != 0)
			comm = "初期作成されていません";
		else
			comm = "";
		sprintf(p, "%-12s  %-45s", key_rec_p->key_type.c[i], comm);
		(cell_p+i)->ptr    = p;
	}
	return(0);
}

/***************************************
 *	sind04	close LADB
 ***************************************/
static	sind04(end)
int	end;
{
	if (key_rec_p != NULL) {
		free((char *) key_rec_p);
		key_rec_p = NULL;
	}
	if (g_dsp != NULL) {
		d3lend(g_dsp, "q", 0);
		g_dsp = NULL;
	}
	if (end == END_SIND)
		d3free(item_mem);

	return(0);
}

/***************************************
 *	sind06	システム索引情報の再表示
 *		g_dsp	:
 *		item_n	:
 *		key_rec_p:
 ***************************************/
static	sind06()
{
	int	err, i;
	char	path_name[128], ind_buf[128], *comm;

	for (i=0; i<item_n; i++) {	/* SETUP D_CELL */
		(cell_p+i)->flag   = 0;
		(cell_p+i)->result = 0;

		d3path(cur_ladb, key_rec_p->key_type.c[i], ind_buf);
		if (d3fchk(ind_buf) != 0)
			comm = "初期作成されていません";
		else
			comm = "";
		sprintf((cell_p+i)->ptr, "%-12s  %-45s",
				key_rec_p->key_type.c[i], comm);
					/* D_CELL エリアを再利用する */
	}

	return(0);
}

/***************************************
 *	sind10	表操作
 ***************************************/
static	sind10(item_n, cell_p, c_pos, title, mode)
D_CELL	*cell_p;
int	item_n, c_pos, mode;
char	*title;
{
	char	main_title[80], path_name[128], index[15];
	struct	stat	stat_buf;
	int	err, f_no, ty;

	if ((f_no=d3slct(item_n, cell_p, 0)) == -1)
		f_no=c_pos;

	sind90((cell_p + f_no)->ptr, index);	/* get index name */
	d3path(cur_ladb, index, path_name);
	err=stat(path_name, &stat_buf);
	if (err==-1 && errno==ENOENT) {	/* THERE IS NO FILE */
		if ((mode & UT_READ)== UT_READ) {/* if read_mode then error */
			PD_ERROR(E_IF_N);
			return(0);
		} else {		/* if update_mode then create table */
			if ((err=d2mksi(path_name)) != 0)
				PD_ERROR(err);
		}
	} else if (err==0 && stat_buf.st_size==0) {
		PD_ERROR(E_IF_A);
		return(0);
	}

	sprintf(main_title, "     %-40s             < %s >", title, index);

	d3tbhn(cur_ladb, index, mode, "", 1, 12, main_title);

	return(0);
}

/***************************************
 *	sind20	システム索引の初期作成
 ***************************************/
static	sind20(item_n, cell_p, c_pos, wid)
D_CELL	*cell_p;
int	item_n, c_pos, wid;
{
	struct	stat	stat_buf;
	static	char	msg[] = "初期作成中  %s\n";
	int	f_no, err, c_p;
	char	com_buf[128];
	char	path_name[128], index[15];
	chtype	pf_k;

	if (d3slct(item_n, cell_p, 0) == -1)
		(cell_p + c_pos) -> flag |= 0x01;

	WCLRTOEOL(l_num-3, 2);
	d3item(item_n, cell_p, wid, DISPL, &c_pos);	/* 反転解除 */

	for (f_no=-1; ;) {
		if ((f_no=d3slct(item_n, cell_p, f_no+1)) == -1)
			break;

		sind90((cell_p + f_no)->ptr, index);	/* get index name */
		mvwprintw(pad, l_num-3, 2, msg, index);
		prefresh(pad,0,0,0,0,l_num,80); /* display screen */

		d3path(cur_ladb, index, path_name);
		err=stat(path_name, &stat_buf);
		if (err==-1 && errno==ENOENT) {	/* THERE IS NO FILE */
			err=d2mksi(path_name);
			if (err != 0)
				PD_ERROR(err);
		}

		(cell_p+f_no)->result=(short) err;
		if (err != 0)
			d3msgh(err);		/* エラーコード通知 */
	}

	WCLRTOEOL(l_num-3, 2);

	d3rslt(item_n, cell_p);		/* display result */

	for (f_no = 0; f_no < item_n; f_no++)
		(cell_p + f_no)->flag = 0;

	return(0);
}

/***************************************
 *	sind30	RE-CREATION SYSTEM INDEX
 ***************************************/
static	sind30(item_n, cell_p, c_pos, wid)
D_CELL	*cell_p;
int	item_n, c_pos, wid;
{
	static	char	msg[] = "再登録処理中  %s\n";
	int	f_no, err, c_p;
	char	com_buf[128];
	char	index[15];
	chtype	pf_k;

	c_p = c_pos;
	d3fcmk(pf_label_3);
	WCLRTOEOL(l_num-3, 2);
	mvwaddstr(pad, l_num-3, 2, MES_OK_C);

	for (;;) {
		pf_k = d3item(item_n, cell_p, wid, IT_NUM, &c_p);
		if (pf_k == KEY_F(5)) break;
		else if (pf_k == KEY_F(17) || pf_k == KEY_F(18)) return(0);
	}

	if (d3slct(item_n, cell_p, 0) == -1)
		(cell_p + c_pos) -> flag |= 0x01;

	WCLRTOEOL(l_num-3, 2);
	d3item(item_n, cell_p, wid, DISPL, &c_pos);	/* 反転解除 */

	for (f_no=-1; ;) {
		if ((f_no=d3slct(item_n, cell_p, f_no+1)) == -1)
			break;

		sind90((cell_p + f_no)->ptr, index);	/* get index name */
		mvwprintw(pad, l_num-3, 2, msg, index);
		prefresh(pad,0,0,0,0,l_num,80); /* display screen */

		sprintf(com_buf, "d1_idmk %s %s >/dev/null 2>&1",
			cur_ladb, index);

		/*if (!isendwin()) endwin();*/ /* shell mode <== curses mode */

		err=system(com_buf) >> 8;

		(cell_p+f_no)->result=(short) err;
		if (err != 0)
			d3msgh(err);		/* エラーコード通知 */
	}

	WCLRTOEOL(l_num-3, 2);

	d3rslt(item_n, cell_p);		/* display result */

	for (f_no = 0; f_no < item_n; f_no++)
		(cell_p + f_no)->flag = 0;

	return(0);
}

/***************************************
 *	sind40	DELETE SYSTEM INDEX
 ***************************************/
static	sind40(item_n, cell_p, c_pos, wid)
D_CELL	*cell_p;
int	item_n, c_pos, wid;
{
	static	char	msg[] = "削除処理中  %s\n";
	int	c_p, f_no, err;
	chtype	pf_k;
	char	index[15];

	c_p = c_pos;
	d3fcmk(pf_label_3);
	WCLRTOEOL(l_num-3, 2);
	mvwaddstr(pad, l_num-3, 2, MES_OK_D);

	for (;;) {
		pf_k = d3item(item_n, cell_p, wid, IT_NUM, &c_p);
		if (pf_k == KEY_F(5)) break;
		else if (pf_k == KEY_F(17) || pf_k == KEY_F(18)) return(0);
	}

	if (d3slct(item_n, cell_p, 0) == -1)
		(cell_p + c_pos) -> flag |= 0x01;

	WCLRTOEOL(l_num-3, 2);
	d3item(item_n, cell_p, wid, DISPL, &c_pos);	/* 反転解除 */

	for (f_no=-1; ;) {
		if ((f_no=d3slct(item_n, cell_p, f_no+1)) == -1)
			break;

		sind90((cell_p + f_no)->ptr, index);	/* get index name */
		mvwprintw(pad, l_num-3, 2, msg, index);
		prefresh(pad,0,0,0,0,l_num,80); /* display screen */

		err=d3unln(cur_ladb, index, 0);
		if (err != 0) err=d3cver(err, "if");

		(cell_p+f_no)->result=(short) err;
		if (err != 0)
			d3msgh(err);		/* エラーコード通知 */
	}

	WCLRTOEOL(l_num-3, 2);

	d3rslt(item_n, cell_p);		/* display result */
	for (f_no = 0; f_no < item_n; f_no++)
		(cell_p + f_no)->flag = 0;

	return(0);
}

/***************************************
 *	sind90	GET INDEX NAME
 ***************************************/
static	sind90(buf, name)
char	*buf, *name;
{
	char	*b, *n;

	for (b=buf, n=name; *b != ' '; *n++ = *b++);
	*n = '\0';
	return(0);
}
