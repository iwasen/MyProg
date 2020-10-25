/*	FILE DESCRIPTION

		FILE NAME	: d0back.c
		ROUTINE		: backup file maintenance
		REVISION	:
		REMARKS		:	88.04.20	S.Aizawa
*/

#include	"d0back.h"
#include	"la_ws/include/d3libc.h"

extern	DSET	*d3lbgn();
extern	char	*d3allc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	tmp_file[64];		/* temp.file name */
static	int	mgno;			/* memory group No. */
static	int	n_item;
static	D_CELL	*ptr_item;
static	int	c_pos;			/* current select position */
static	int	mode;			/* 0:名前一覧　1:情報一覧 */

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: main()
		ROUTINE		: backup file maintenance
		INPUT		: none
		OUTPUT		: none
*/

d0back()
{
	static	char	title1[] = "バックアップファイル名前一覧";
	static	char	title2[] = "バックアップファイル情報一覧";
	static	char	title21[] =
 "名前                ＬＡデータセット            バックアップファイル";
	static char *func1[8] = {
		"削  除", "復  元", NULL, "情報一覧",
		"全指定", "全解除", "LADB変更", NULL
	};
	static char *func2[8] = {
		"削  除", "復  元", NULL, "名前一覧",
		"全指定", "全解除", "LADB変更", NULL
	};
	static char imes1[] = "バックアップファイルを選択してください ";
	static char imes2[] = "バックアップファイルはありません ";
	chtype	ch;			/* input character */
	int	status;			/* return status */
	int	loop_sw;		/* loop switch */

	d3pned(11);			/* display program name */

	tmpnam(tmp_file);		/* make temp.file name */
	mgno = -1;			/* reset memory group No. */

	back10();			/* get backup file name */

	mode = 0;			/* reset mode */
	c_pos = 0;			/* reset current position */
	loop_sw = 1;			/* loop switch on */
	while (loop_sw) {
		d3pned(11);		/* display program name */
		if (mode == 0) {
			mvwaddstr(pad, 3, 18, title1);
			wmove(pad, 4, 0); wclrtoeol(pad);
			d3fcmk(func1);	/* set function key */
		} else {
			mvwaddstr(pad, 3, 18, title2);
			mvwaddstr(pad, 4, 5, title21);
			d3fcmk(func2);	/* set function key */
		}
		mvwaddstr(pad, l_num-3, 2, n_item ? imes1 : imes2);

		ch = d3item(n_item, ptr_item, mode ? 78 : 20,
			 			IT_MUL|IT_ALL|IT_CEN, &c_pos);
		switch (ch) {
		case KEY_F(1):		/* 削除 */
			if (n_item != 0) {
				back30();
				back10();	/* get backup file inf. */
			}
			break;
		case KEY_F(2):		/* 復元 */
			if (n_item != 0) {
				back20();
				back10();	/* get backup file inf. */
			}
			break;
		case KEY_F(4):		/* 名前一覧／情報一覧 */
			mode = mode ? 0 : 1;	/* change mode */
			break;
		case KEY_F(7):		/* LADB変更 */
			d0whre();
			d3dred();		/* make current file */
			back10();
			break;
		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			loop_sw = 0;	/* reset loop switch */
			break;
		}
	}

	back50();			/* 後処理 */
	return(0);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: back10()
		ROUTINE		: get backup file name
		INPUT		: none
		OUTPUT		: none
*/

static
back10()
{
	static	char	msg[] = "バックアップファイル検索中　";
	int	err;
	char	s[256];

	wmove(pad,2,0);
	wclrtobot(pad);		/* erase window */
	mvwaddstr(pad, l_num-3, 2, msg);	/* display message */
	prefresh(pad,0,0,0,0,l_num,80);

	sprintf(s, "d1_back -t %s>%s 2>/dev/null", cur_ladb, tmp_file);
	if ((err = system(s) >> 8) != 0) {
		d3errw(err);
		return;
	}

	if (mgno >= 0)			/* use memory ? */
		d3free(mgno);		/* free memory */
	mgno = d3mgno();		/* get memory group No. */

	err = d3fitm(tmp_file, mgno, &ptr_item, &n_item); /* make item table */
	if (err != 0)
		d3errw(err);

	if ((err = back40()) != 0)	/* get file inf. */
		d3errw(err);
	c_pos = 0;
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: back20()
		ROUTINE		: 復元
		INPUT		: none
		OUTPUT		: none
*/

static
back20()
{
	static	char	msg[] = "復元処理実行中　%s\n";
	int	i, err, sel_flag;
	D_CELL	*ptr;
	char	cmd[256], buf[16];

	wmove(pad, l_num-3, 0);
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

	d3item(n_item, ptr_item, mode ? 78 : 20, IT_DSP|IT_NSL|IT_CEN, &c_pos);

	for (i = 0, ptr = ptr_item; i < n_item; i++, ptr++) {
		if (ptr->flag & 0x01) {
			strncpy(buf, ptr->ptr, 14);
			buf[14] = '\0';
			mvwprintw(pad, l_num-3, 2, msg, buf);
			prefresh(pad,0,0,0,0,l_num,80);

			sprintf(cmd, "d1_back -r %s %.14s 2>/dev/null"
					, cur_ladb, ptr->ptr);
			err = system(cmd) >> 8;
			ptr->result = err;
			if (err != 0)
				d3msgh(err);
		}
	}
	d3rslt(n_item, ptr_item);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: back30()
		ROUTINE		: 削除
		INPUT		: none
		OUTPUT		: none
*/

static
back30()
{
	static	char	msg[] = "削除処理実行中　%s\n";
	int	i, err, sel_flag;
	D_CELL	*ptr;
	char	cmd[256], buf[16];

	wmove(pad, l_num-3, 0);
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

	d3item(n_item, ptr_item, mode ? 78 : 20, IT_DSP|IT_NSL|IT_CEN, &c_pos);

	for (i = 0, ptr = ptr_item; i < n_item; i++, ptr++) {
		if (ptr->flag & 0x01) {
			strncpy(buf, ptr->ptr, 14);
			buf[14] = '\0';
			mvwprintw(pad, l_num-3, 2, msg, buf);
			prefresh(pad,0,0,0,0,l_num,80);

			sprintf(cmd, "d1_back -d %s %.14s 2>/dev/null"
					, cur_ladb, ptr->ptr);
			err = system(cmd) >> 8;
			ptr->result = err;
			if (err != 0)
				d3msgh(err);
		}
	}
	d3rslt(n_item, ptr_item);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: back40()
		ROUTINE		: 情報一覧取り出し
		INPUT		: none
		OUTPUT		: none
*/

static
back40()
{
	int	i, j;
	D_CELL	*ptr;
	char	back_name[128], lads_name[128];
	char	*p;

	ptr = ptr_item;
	for (i = 0; i < n_item; i++) {
		/* make backup file name */
		sprintf(back_name, "%s/.B%s", cur_ladb, ptr->ptr);
		/* make la dataset name */
		sprintf(lads_name, "%s/%s", cur_ladb, ptr->ptr);

		if ((p = d3allc(mgno, 80)) == 0)	/* allocate memory */
			return(E_M);

		for (j = 0; j < 79; j++)
			p[j] = ' ';		/* clear buffer */
		p[79] = '\0';

		strncpy(p, ptr->ptr, strlen(ptr->ptr));	/* set file name */
		back41(lads_name, p + 15);	/* set lads inf. */
		back41(back_name, p + 45);	/* set backup file inf. */

		ptr->ptr = p;
		ptr++;
	}
	return(0);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: back41()
		ROUTINE		: ファイル情報取り出し
		INPUT		: name = file name
		OUTPUT		: p    = size & date
*/

static
back41(name, p)
char	*name;
char	*p;
{
	struct	stat	buf;
	char	s[10], *sp;
	struct	tm	*tp;

	if (stat(name, &buf) == 0) {
		sprintf(s, "%8ld", buf.st_size);
		strncpy(p, s, 8);

		tp = localtime(&buf.st_mtime);
		sprintf(s, "%02d/%02d/%02d",
				tp->tm_year, tp->tm_mon+1, tp->tm_mday);
		strncpy(p + 10, s, 8);
		sprintf(s, "%02d:%02d:%02d",
				tp->tm_hour, tp->tm_min, tp->tm_sec);
		strncpy(p + 19, s, 8);
	} else
		strncpy(p, "  ......  ........ ........", 27);
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: back50()
		ROUTINE		: 後処理
		INPUT		: none
		OUTPUT		: none
*/

static
back50()
{
	unlink(tmp_file);		/* erase temp.file */
	if (mgno >= 0)
		d3free(mgno);		/* free memory */
}

