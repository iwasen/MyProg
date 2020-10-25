/************************************************************
 * ----- %Z%%M% %I% %E% %U% -----
 *	FILE DESCRIPTION
 *		XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
 *	FILE	:	XXXXXXXX
 *		:	XXXXXXXX
 *	REMARKS	:	1988-04-26	X.XXXXXX
 *
 ************************************************************/
#include <stdio.h>
#include <jcurses.h>
#include <string.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <memory.h>
#include "d3item.h"
#include "d3data.h"
#include "functn.h"
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d2lerr.h"
#include "la_ws/include/d3nmty.h"
#define  REC_LEN	80

extern	DSET	*d3lbgn();
extern	char	*d3allc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3pgrp(ladir)
char	*ladir;
{
	static	char	*func[8] = { 	/* description of func-key */
		NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
	};
	int	c_pos, cl_n, mgno, err;
	chtype	ch;
	D_CELL	*cl_p;
	char	*name = "ＬＡデータベース内容", title[REC_LEN];

	mgno = d3mgno();
	if ((err = pgrp10(ladir, mgno, &cl_p, &cl_n, title)) != 0) {
		d3free(mgno);
		return(err);
	}

	wmove(pad,0,40); wclrtoeol(pad); waddstr(pad,"< ");
	waddstr(pad, ladir); waddstr(pad," >");

	wmove(pad, 3, 0); wclrtobot(pad);
	mvwprintw(pad, 3, 18, "%s", name);
	mvwprintw(pad, 4, 0, "%-s", title);

	wmove(pad, l_num-3, 2); wclrtoeol(pad);
	wprintw(pad, "参照後、リターンキーを押してください  " );

	d3fcmk(func);
	c_pos = 0;
	for (;;) {
		ch = d3item(cl_n, cl_p, 74, IT_NUM|IT_NSL, &c_pos);
		if (ch==0x0a || ch==KEY_F(17) || ch==KEY_F(18))
			break;
	}

	d3free(mgno);
	return(0);
}

/*********************************************/
/*      グループ索引の内容をメモリに展開する */
/*********************************************/
static	pgrp10(ladir, mgno, p, n, t)
char	*ladir, *t;
D_CELL	**p;
int	mgno, *n;
{
	char	buf[128], **keyword, *data;
	T_TEMPLETE	*templete;
	DSET	*dsp;
	int	err, nitems, i, j, cl_n;
	D_CELL	*cl_p, *c_p;

	templete = (T_TEMPLETE *)d3allc(mgno, sizeof(TEMPLETE)*5);
	if (templete == NULL)
		return(E_M);

	d3path(ladir, GROUP_INDEX, buf);
	dsp = d3lbgn(buf, "r", 0 , &err);
	if (dsp == NULL)
		return(d3cver(err, "gi"));

	if ((err = d3tkey(dsp, &nitems, &keyword)) != 0)
		return(d3cver(err, "gi"));

	cl_n = d3trow(dsp);
	*n = cl_n;

	if ((data = d3allc(mgno, REC_LEN*cl_n)) == NULL)
		return(E_M);
	if ((cl_p = (D_CELL *)d3allc(mgno, sizeof(D_CELL)*cl_n)) == NULL)
		return(E_M);
	*p = cl_p;
	memset(data, '\0', REC_LEN*cl_n);		/* clear D_CELL */
	memset(cl_p, '\0', sizeof(D_CELL)*cl_n);	/* clear D_CELL */
	memset(t, '\0', REC_LEN);			/* clear title */

	for (i=0; i<nitems && i<=4; i++)
		sprintf(t, "       %-12s  %-16s  %-12s  %-26s",
			keyword[0], keyword[1], keyword[2],
			nitems >= 4 ? keyword[3] : "  ");

	templete->tp_bufp = buf;
	templete->tp_cnum = nitems;
	for (i=0; i<nitems && i<=4; i++)
		templete->tp_templete[i].tp_col = i+1;
	d3mktp(dsp, templete);
	templete->tp_templete[0].tp_len = 12;
	templete->tp_templete[1].tp_len = 16;
	templete->tp_templete[2].tp_len = 12;
	templete->tp_templete[3].tp_len = 24;
	templete->tp_templete[1].tp_offset =  0;
	templete->tp_templete[1].tp_offset = 14;
	templete->tp_templete[2].tp_offset = 32;
	templete->tp_templete[3].tp_offset = 46;
	buf[0+12]='\0'; buf[14+16]='\0'; buf[32+12]='\0'; buf[46+24]='\0';

	c_p = cl_p;
	c_p->ptr = data;

	for (j=0; j<cl_n; j++, c_p++, c_p->ptr=(c_p-1)->ptr + REC_LEN) {
		d3trd(dsp, j+1, 1, templete);
		sprintf(c_p->ptr, "%-12s  %-16s  %-12s  %-24s",
			buf+templete->tp_templete[0].tp_offset,	
			buf+templete->tp_templete[1].tp_offset,	
			buf+templete->tp_templete[2].tp_offset,	
			nitems >= 4 ?
			buf+templete->tp_templete[3].tp_offset :
			"  ");
		c_p->ptr[12+2+16+2+12+2+24] = '\0';
	}

	d3lend(dsp, "q", 0);

	return(0);
}
