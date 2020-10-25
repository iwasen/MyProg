/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンド入力画面－２処理		*
 *		ファイル名	: prog5.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	最大入力データ長	*/
#define	MAX_NUM_LEN	5	/* 数値項目 */
#define	MAX_NAM_LEN	24	/* 名称項目 */
#define	MAX_GUD_LEN	48	/* 説明項目 */

/*	選択項目入力バッファ	*/
typedef	struct	{
	int	sel_no;		/* 現在選択中の項目番号 */
	int	x[20];		/* 選択項目の表示桁位置 */
	int	len[20];	/* 選択項目の長さ */
} SEL_BUF;

/*	数値項目入力バッファ	*/
typedef	struct	{
	int	min;		/* 最小値 */
	int	max;		/* 最大値 */
	int	x;		/* 数値項目の入力桁位置 */
	int	sel;		/* 選択項目フラグ */
	int	size;		/* 入力フィールド長 */
	char	buf[MAX_NUM_LEN + 1];	/* 入力バッファ */
} NUM_BUF;

/*	名称項目入力バッファ	*/
typedef	struct	{
	int	x;		/* 名称項目の入力桁位置 */
	int	flag;		/* ０：拡張子１　１：拡張子２ */
	int	sel;		/* 選択項目フラグ */
	int	size;		/* 入力フィールド長 */
	char	buf[MAX_NAM_LEN + 1];	/* 入力バッファ１ */
} NAM_BUF;

/*	説明項目入力バッファ	*/
typedef	struct	{
	int	x;		/* 名称項目の入力桁位置 */
	int	size;		/* 入力フィールド長 */
	char	buf[MAX_GUD_LEN + 1];	/* 入力バッファ */
} GUD_BUF;

/*	パラメータ入力バッファ	*/
typedef	union	{
	SEL_BUF	*sel;		/* 選択項目 */
	NUM_BUF	*num;		/* 数値項目 */
	NAM_BUF	*nam;		/* 名称項目 */
	GUD_BUF	*gud;		/* 説明項目 */
} PRM_BUF;

/*	内部関数	*/
static	PRM_BUF	*alloc_prm(void);
static	SEL_BUF	*alloc_sel(SEL_TBL *, char *);
static	NUM_BUF	*alloc_num(NUM_TBL *, char *);
static	NAM_BUF	*alloc_nam(NAM_TBL *, char *);
static	GUD_BUF	*alloc_gud(GUD_TBL *, char *);
static	void	free_prm(PRM_BUF *);
static	int	inp_prm(PRM_BUF *, PRT_TBL *, int);
static	int	inp_sel(SEL_BUF *, SEL_TBL *, int);
static	int	inp_num(NUM_BUF *, NUM_TBL *, int);
static	int	inp_nam(NAM_BUF *, NAM_TBL *, int);
static	int	inp_gud(GUD_BUF *, GUD_TBL *, int);
static	void	dsp_prm(int, PRM_BUF *);
static	void	dsp_sel(SEL_BUF *, SEL_TBL *, int);
static	void	dsp_num(NUM_BUF *, NUM_TBL *, int);
static	void	dsp_nam(NAM_BUF *, NAM_TBL *, int);
static	void	dsp_gud(GUD_BUF *, GUD_TBL *, int);
static	void	edit_prm(PRM_BUF *);

/*	スタティック変数	*/
static	int	color;		/* 色コード */

/*=======================================================================
 |
 |		コマンド入力画面－２処理
 |
 |	void	prog5()
 |
 =======================================================================*/
void	prog5()
{
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1,  6, 79,  6},
		{ 0,  1, 22, 79, 22},
		{-1}
	};
	int	sn = 0;		/* 先頭パラメータ番号 */
	int	cn = 0;		/* カレントパラメータ番号 */
	int	n;		/* パラメータ数 */
	int	ch;		/* キー入力文字 */
	int	y;		/* 選択項目行位置 */
	int	i;		/* ループカウンタ */
	PRT_TBL	*ptr;		/* パラメータテーブルポインタ */
	PRM_BUF	*pbp;		/* パラメータ入力バッファポインタ */
	MOUSE	mouse[15];	/* マウスデータ */

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	color = online ? C_ONLINE : C_OFFLINE;
	if (mkcmd)
		xprintf(2, 1, color, msg30, csg.nsg.sys_name, cmf_name);
	else if (local)
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg501);
	else
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg500);
	dspstr(4, 5, prm_current->guide != NULL ? prm_current->guide
					: cmd_current->guide, C_GUIDE);

	/* ローカルコマンド編集 */
	if (cmdbuf[0] == csg.nsg.offline_id)
		edit_loc_cmd();

	/* パラメータ入力バッファ設定 */
	pbp = alloc_prm();

	/* パラメータ数取り出し */
	n = prm_current->n;

	/* パラメータ表示 */
	dsp_prm(sn, pbp);

	/* マウスデータセット */
	for (i = 0; i < n && i < 15; i++) {
		mouse[i].x = 2;
		mouse[i].y = i + 7;
		mouse[i].len = 24;
		mouse[i].s_code = KEY_MOUSE + i;
		mouse[i].l_code = KEY_CR;
		mouse[i].r_code = 0;
	}
	m_reg(mouse, i);

	/* ファンクションキー設定 */
	setfunc(0);

	while (prog == 5) {
		y = cn - sn + 7;	/* 選択中項目行位置 */

		/* パラメータテーブルポインタセット */
		ptr = &prm_current->ptr[cn];

		/* 選択中パラメータ反転 */
		attrib(2, y, 24, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);

		/* 現在のパラメータを表示 */
		edit_prm(pbp);
		if (dspstr2(4, 3, 72, cmdbuf, C_GUIDE) == 1) {
			clrtoeol();
			t_loc(4, 4);
			clrtoeol();
		} else
			clrtoeol();
		

		ch = inp_prm(&pbp[cn], ptr, y);	/* キー入力 */

		/* 選択中パラメータ反転解除 */
		attrib(2, y, 24, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_CR:	/* ＣＲキー */
			/* パラメータ編集 */
			edit_prm(pbp);

			/* コマンド終了マークセット */
			strcat(cmdbuf, "/");

			/* キーバッファにセット */
			set_keybuf(cmdbuf);

			/* コマンド直接入力画面 */
			prog = 6;
			break;
		case KEY_ESC:	/* ＥＳＣキー */
			/* メッセージ受信画面 */
			prog = 7;
			break;
		case KEY_SFT_ESC:	/* シフト＋ＥＳＣキー */
			/* コマンド入力画面－１ */
			prog = prm_current->name != NULL ? 4 : 3;
			break;
		case KEY_HOME:	/* ＨＯＭＥキー */
		case KEY_HELP:	/* ＨＥＬＰキー */
			/* コマンド直接入力画面 */
			prog = 6;
			break;
		case KEY_UP:	/* 上矢印キー */
			/* 上のパラメータを選択 */
			if (cn > 0) {
				if (sn == cn) {
					sn--;
					dsp_prm(sn, pbp);
				}
				cn--;
			}
			break;
		case KEY_DOWN:	/* 下矢印キー */
			/* 下のパラメータ種類を選択 */
			if (cn + 1 < n) {
				if (cn == sn + 14) {
					sn++;
					dsp_prm(sn, pbp);
				}
				cn++;
			}
			break;
		default:
			if ((ch & 0xff00) == KEY_MOUSE)
				cn = sn + (ch & 0xff);
			else
				beep();
			break;
		}
	}

	/* マウスデータ解除 */
	m_reg(NULL, 0);

	/* パラメータ入力バッファ解放 */
	free_prm(pbp);
}

/*=======================================================================
 |
 |		パラメータ入力バッファ設定処理
 |
 |	PRM_BUF	*alloc_prm()
 |
 |		返値		パラメータ入力バッファポインタ
 |
 =======================================================================*/
static	PRM_BUF	*alloc_prm()
{
	PRM_BUF	*pbp, *prmp;	/* パラメータ入力バッファポインタ */
	PRT_TBL	*ptr;		/* パラメータテーブルポインタ */
	int	i;		/* ループカウンタ */
	int	separator;	/* コマンドセパレータ */
	char	prm_buf[80];	/* パラメータバッファ */
	char	*cp;		/* パラメータＲＥＡＤポインタ */
	char	*p;		/* パラメータＷＲＩＴＥポインタ */

	/* メモリ確保 */
	prmp = pbp = talloc(sizeof(PRM_BUF) * prm_current->n);

	/* コマンドバッファポインタセット */
	if (prm_current->name != NULL) {
		cp = &cmdbuf[8];
		separator = ',';
	} else {
		cp = &cmdbuf[4];
		separator = ' ';
	}

	for (i = 0, ptr = prm_current->ptr; i < prm_current->n; i++, ptr++) {
		/* コマンドバッファよりパラメータ取り出し */
		if (*cp == separator) {
			cp++;
			for (p = prm_buf; *cp != ',' && *cp != '/' && *cp != '\0'; p++)
				*p = *cp++;
			*p = '\0';
		} else
			prm_buf[0] = '\0';
		separator = ',';

		switch (ptr->type) {
		case KWD_SEL:	/* 選択項目 */
			pbp->sel = alloc_sel(ptr->ptr.sel, prm_buf);
			break;
		case KWD_NUM:	/* 数値項目 */
			pbp->num = alloc_num(ptr->ptr.num, prm_buf);
			break;
		case KWD_NAM:	/* 名称項目 */
			pbp->nam = alloc_nam(ptr->ptr.nam, prm_buf);
			break;
		case KWD_MSG:	/* 説明項目 */
			pbp->gud = alloc_gud(ptr->ptr.gud, prm_buf);
			break;
		}
		pbp++;
	}

	return(prmp);
}

/*=======================================================================
 |
 |		選択項目入力バッファ設定処理
 |
 |	SEL_BUF	*alloc_sel(seltp, cp)
 |
 |		SEL_TBL	*seltp;		選択項目テーブルポインタ
 |		char	*cp;		パラメータ
 |
 |		返値			選択項目入力バッファポインタ
 |
 =======================================================================*/
static	SEL_BUF	*alloc_sel(SEL_TBL *seltp, char *cp)
{
	SEL_BUF	*selbp;		/* 選択項目入力バッファポインタ */
	int	i;		/* ループカウンタ */
	int	x;		/* 選択項目表示桁位置 */

	/* メモリ確保 */
	selbp = talloc(sizeof(SEL_BUF));

	selbp->sel_no = 0;
	for (i = 0, x = 28; i < seltp->n; i++) {
		if (strcmp(cp, seltp->ptr[i][1]) == 0)
			selbp->sel_no = i;
		selbp->x[i] = x;
		selbp->len[i] = strlen(seltp->ptr[i][0]);
		x += selbp->len[i] + 1;
	}

	return(selbp);
}

/*=======================================================================
 |
 |		数値項目入力バッファ設定処理
 |
 |	NUM_BUF	*alloc_num(numtp, cp)
 |
 |		NUM_TBL	*numtp;		数値項目テーブルポインタ
 |		char	*cp;		パラメータ
 |
 |		返値			数値項目入力バッファポインタ
 |
 =======================================================================*/
static	NUM_BUF	*alloc_num(NUM_TBL *numtp, char *cp)
{
	NUM_BUF	*numbp;		/* 数値項目入力バッファポインタ */
	int	x;		/* 数値項目入力桁位置 */

	x = 28;
	numbp = talloc(sizeof(NUM_BUF));
	if (numtp->min_flg) {
		numbp->min = numtp->min;
		if (numtp->min_syc != 0 && ssg.syc[numtp->min_syc - 1].syc_flg)
			numbp->min = ssg.syc[numtp->min_syc - 1].syc_val;
	}
	if (numtp->max_flg) {
		numbp->max = numtp->max;
		if (numtp->max_syc != 0 && ssg.syc[numtp->max_syc - 1].syc_flg)
			numbp->max = ssg.syc[numtp->max_syc - 1].syc_val;
	}
	if (numtp->sel != NULL)
		x += strlen(numtp->sel) + 1;
	numbp->x = x;
	numbp->size = MAX_NUM_LEN;
	memset(numbp->buf, ' ', MAX_NUM_LEN);
	numbp->buf[MAX_NUM_LEN] = '\0';

	if (numtp->sel != NULL && strcmp(cp, numtp->sel) == 0)
		numbp->sel = 1;
	else {
		numbp->sel = 0;
		if (numtp->prmid != NULL && strncmp(cp, numtp->prmid, strlen(numtp->prmid)) == 0)
			cp += strlen(numtp->prmid);
		memcpy(numbp->buf, cp, strlen(cp));
	}

	return(numbp);
}

/*=======================================================================
 |
 |		名称項目入力バッファ設定処理
 |
 |	NAM_BUF	*alloc_nam(namtp, cp)
 |
 |		NAM_TBL	*namtp;		名称項目テーブルポインタ
 |		char	*cp;		パラメータ
 |
 |		返値			名称項目入力バッファポインタ
 |
 =======================================================================*/
static	NAM_BUF	*alloc_nam(NAM_TBL *namtp, char *cp)
{
	NAM_BUF	*nambp;		/* 名称項目入力バッファポインタ */
	int	x;		/* 名称項目入力桁位置 */
	char	*p;

	x = 28;
	nambp = talloc(sizeof(NAM_BUF));
	if (namtp->sel != NULL)
		x += strlen(namtp->sel) + 1;
	nambp->x = x;
	if (namtp->ext1 != NULL)
		nambp->size = 8;
	else
		nambp->size = namtp->length ? namtp->length : MAX_NAM_LEN;
	memset(nambp->buf, ' ', nambp->size);
	nambp->buf[nambp->size] = '\0';

	if (namtp->sel != NULL && strcmp(cp, namtp->sel) == 0)
		nambp->sel = 1;
	else {
		nambp->sel = 0;
		p = strchr(cp, '.');
		if (namtp->ext1 != NULL && p != NULL
					&& strcmp(p+1, namtp->ext1) == 0) {
			memcpy(nambp->buf, cp, p - cp);
			nambp->flag = 0;
		} else if (namtp->ext2 != NULL && p != NULL
					&& strcmp(p+1, namtp->ext2) == 0) {
			memcpy(nambp->buf, cp, p - cp);
			nambp->flag = 1;
		} else {
			memcpy(nambp->buf, cp, strlen(cp));
			nambp->flag = 0;
		}
	}

	return(nambp);
}

/*=======================================================================
 |
 |		説明項目入力バッファ設定処理
 |
 |	GUD_BUF	*alloc_gud(gudtp, cp)
 |
 |		GUD_TBL	*gudtp;		説明項目テーブルポインタ
 |		char	*cp;		パラメータ
 |
 |		返値			説明項目入力バッファポインタ
 |
 =======================================================================*/
static	GUD_BUF	*alloc_gud(GUD_TBL *gudtp, char *cp)
{
	GUD_BUF	*gudbp;		/* 説明項目入力バッファポインタ */

	gudbp = talloc(sizeof(GUD_BUF));
	gudbp->x = 28;
	gudbp->size = gudtp->length ? gudtp->length : MAX_GUD_LEN;
	memset(gudbp->buf, ' ', gudbp->size);
	gudbp->buf[gudbp->size] = '\0';
	memcpy(gudbp->buf, cp, min(strlen(cp), gudbp->size));

	return(gudbp);
}

/*=======================================================================
 |
 |		パラメータ入力バッファ解放
 |
 |	void	free_prm(pbp)
 |
 |		PRM_BUF	*pbp;		パラメータ入力バッファ
 |
 =======================================================================*/
static	void	free_prm(PRM_BUF *pbp)
{
	int	i;		/* ループカウンタ */

	for (i = 0; i < prm_current->n; i++)
		free(&pbp[i]);
	free(pbp);
}

/*=======================================================================
 |
 |		パラメータ入力処理
 |
 |	int	inp_prm(pbp, ptr, y)
 |
 |		PRM_BUF	*pbp;		パラメータ入力バッファ
 |		PRM_TBL	*ptr;		パラメータテーブル
 |		int	y;		入力行位置
 |
 |		返値			キー入力文字
 |
 =======================================================================*/
static	int	inp_prm(PRM_BUF *pbp, PRT_TBL *ptr, int y)
{
	int	ch;		/* キー入力文字 */

	switch (ptr->type) {
	case KWD_SEL:	/* 選択項目 */
		ch = inp_sel(pbp->sel, ptr->ptr.sel, y);
		break;
	case KWD_NUM:	/* 数値項目 */
		ch = inp_num(pbp->num, ptr->ptr.num, y);
		break;
	case KWD_NAM:	/* 名称項目 */
		ch = inp_nam(pbp->nam, ptr->ptr.nam, y);
		break;
	case KWD_MSG:	/* 説明項目 */
		ch = inp_gud(pbp->gud, ptr->ptr.gud, y);
		break;
	}
	return(ch);
}

/*=======================================================================
 |
 |		選択項目入力処理
 |
 |	int	inp_sel(selbp, seltp, y)
 |
 |		SEL_BUF	*selbp;		選択項目入力バッファ
 |		SEL_TBL	*seltp;		選択項目テーブル
 |		int	y;		入力行位置
 |
 |		返値			キー入力文字
 |
 =======================================================================*/
static	int	inp_sel(SEL_BUF *selbp, SEL_TBL *seltp, int y)
{
	int	ch;		/* キー入力文字 */
	int	loop_sw;	/* ループ制御スイッチ */
	int	sel_no;		/* 選択項目番号 */

	sel_no = selbp->sel_no;

	dspstr(2, 23, msg510, color);
	clrtoeol();
	attrib(selbp->x[sel_no], y, selbp->len[sel_no]
		, A_WHITE|A_ULINE|A_REVERSE, t_color_code(color)|A_REVERSE);
	loop_sw = 1;
	while (loop_sw) {
		ch = getchr();
		switch (ch) {
		case KEY_RIGHT:
		case KEY_TAB:
			attrib(selbp->x[sel_no], y, selbp->len[sel_no]
				, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));
			if (sel_no < seltp->n-1)
				sel_no++;
			else
				sel_no = 0;
			attrib(selbp->x[sel_no], y, selbp->len[sel_no]
			   , A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);
			break;
		case KEY_LEFT:
		case KEY_SFT_TAB:
			attrib(selbp->x[sel_no], y, selbp->len[sel_no]
				, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));
			if (sel_no != 0)
				sel_no--;
			else
				sel_no = seltp->n - 1;
			attrib(selbp->x[sel_no], y, selbp->len[sel_no]
			   , A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);
			break;
		default:
			loop_sw = 0;
			break;
		}
	}
	attrib(selbp->x[sel_no], y, selbp->len[sel_no]
		, A_WHITE|A_ULINE|A_REVERSE, t_color_code(color)|A_ULINE);

	selbp->sel_no = sel_no;

	return(ch);
}

/*=======================================================================
 |
 |		数値項目入力処理
 |
 |	int	inp_num(numbp, numtp, y)
 |
 |		NUM_BUF	*numbp;		数値項目入力バッファ
 |		NUM_TBL	*numtp;		数値項目テーブル
 |		int	y;		入力行位置
 |
 |		返値			キー入力文字
 |
 =======================================================================*/
static	int	inp_num(NUM_BUF *numbp, NUM_TBL *numtp, int y)
{
	int	ch;		/* キー入力文字 */
	int	loop_sw;	/* ループ制御スイッチ */
	u_int	n;		/* 入力数値 */

	dspstr(2, 23, msg520, color);
	clrtoeol();
	loop_sw = 1;
	while (loop_sw) {
		if (numbp->sel == 0) {
			attrib(28, y, strlen(numtp->sel)
					, A_WHITE|A_ULINE|A_REVERSE
					, t_color_code(C_GUIDE));
			ch = inputs(numbp->buf, numbp->x, y
					, numbp->size, 0x4a, C_GUIDE);
		} else {
			attrib(28, y, strlen(numtp->sel)
					, A_WHITE|A_ULINE|A_REVERSE
					, t_color_code(color)|A_REVERSE);
			ch = getchr();
		}
		switch (ch) {
		case KEY_CR:
		case KEY_UP:
		case KEY_DOWN:
			if (numbp->sel == 0 && strcount(numbp->buf) != 0) {
				n = atoi(numbp->buf);
				if (numtp->min_flg && n < numbp->min) {
					beep();
					break;
				}
				if (numtp->max_flg && n > numbp->max) {
					beep();
					break;
				}
			}
			loop_sw = 0;
			break;
		case KEY_TAB:
		case KEY_SFT_TAB:
			if (numtp->sel != NULL) {
				if (numbp->sel == 0) {
					numbp->sel = 1;
					memset(numbp->buf, ' ', numbp->size);
					dspstr(numbp->x, y, numbp->buf
								, C_GUIDE);
				} else
					numbp->sel = 0;
			}
			break;
		default:
			loop_sw = 0;
			break;
		}
	}
	if (numbp->sel) {
		attrib(28, y, strlen(numtp->sel), A_WHITE|A_ULINE|A_REVERSE
						, t_color_code(color)|A_ULINE);
	}

	return(ch);
}

/*=======================================================================
 |
 |		名称項目入力処理
 |
 |	int	inp_nam(nambp, namtp, y)
 |
 |		NAM_BUF	*nambp;		名称項目入力バッファ
 |		NAM_TBL	*namtp;		名称項目テーブル
 |		int	y;		入力行位置
 |
 |		返値			キー入力文字
 |
 =======================================================================*/
static	int	inp_nam(NAM_BUF *nambp, NAM_TBL *namtp, int y)
{
	int	ch;		/* キー入力文字 */
	int	loop_sw;	/* ループ制御スイッチ */

	dspstr(2, 23, msg530, color);
	clrtoeol();
	loop_sw = 1;
	while (loop_sw) {
		if (nambp->sel == 0) {
			attrib(28, y, strlen(namtp->sel)
					, A_WHITE|A_ULINE|A_REVERSE
					, t_color_code(C_GUIDE));
			if (nambp->flag == 0) {
				ch = inputs(nambp->buf, nambp->x, y
						, nambp->size, 0x41, C_GUIDE);
			} else {
				ch = inputs(nambp->buf, nambp->x + 13, y
						, nambp->size, 0x41, C_GUIDE);
			}
		} else {
			attrib(28, y, strlen(namtp->sel)
					, A_WHITE|A_ULINE|A_REVERSE
					, t_color_code(color)|A_REVERSE);
			ch = getchr();
		}
		switch (ch) {
		case KEY_TAB:
			if (nambp->sel) {
				nambp->sel = 0;
				nambp->flag = 0;
			} else if (nambp->flag == 0) {
				memset(nambp->buf, ' ', nambp->size);
				dspstr(nambp->x, y, nambp->buf, C_GUIDE);
				if (namtp->ext2 != NULL)
					nambp->flag = 1;
				else if (namtp->sel != NULL)
					nambp->sel = 1;
			} else {
				memset(nambp->buf, ' ', nambp->size);
				dspstr(nambp->x + 13, y, nambp->buf, C_GUIDE);
				if (namtp->sel != NULL)
					nambp->sel = 1;
				else
					nambp->flag = 0;
			}
			break;
		case KEY_SFT_TAB:
			if (nambp->sel) {
				nambp->sel = 0;
				if (namtp->ext2 != NULL)
					nambp->flag = 1;
				else
					nambp->flag = 0;
			} else if (nambp->flag == 0) {
				memset(nambp->buf, ' ', nambp->size);
				dspstr(nambp->x, y, nambp->buf, C_GUIDE);
				if (namtp->sel != NULL)
					nambp->sel = 1;
				else if (namtp->ext2 != NULL)
					nambp->flag = 1;
			} else {
				memset(nambp->buf, ' ', nambp->size);
				dspstr(nambp->x + 13, y, nambp->buf, C_GUIDE);
				nambp->flag = 0;
			}
			break;
		default:
			loop_sw = 0;
			break;
		}
	}
	if (nambp->sel) {
		attrib(28, y, strlen(namtp->sel), A_WHITE|A_ULINE|A_REVERSE
						, t_color_code(color)|A_ULINE);
	}

	return(ch);
}

/*=======================================================================
 |
 |		説明項目入力処理
 |
 |	int	inp_gud(gudbp, gudtp, y)
 |
 |		GUD_BUF	*gudbp;		説明項目入力バッファ
 |		GUD_TBL	*gudtp;		説明項目テーブル
 |		int	y;		入力行位置
 |
 |		返値			キー入力文字
 |
 =======================================================================*/
static	int	inp_gud(GUD_BUF *gudbp, GUD_TBL *gudtp, int y)
{
	int	ch;		/* キー入力文字 */

	dspstr(2, 23, msg540, color);
	clrtoeol();
	ch = inputs(gudbp->buf, gudbp->x, y, gudbp->size, 0x41, C_GUIDE);

	return(ch);
}

/*=======================================================================
 |
 |		パラメータ表示処理
 |
 |	void	dsp_prm(n, pbp)
 |
 |		int	n;		先頭のパラメータＮｏ．
 |		PRM_BUF	*pbp;		パラメータ入力バッファ
 |
 =======================================================================*/
static	void	dsp_prm(int n, PRM_BUF *pbp)
{
	int	i;		/* ループカウンタ */
	int	y;		/* 表示行位置 */
	PRT_TBL	*ptr;		/* パラメータテーブルポインタ */

	for (i = 0, ptr = &prm_current->ptr[n], pbp += n; i < 15; i++, ptr++, pbp++) {
		y = i + 7;
		if (n++ < prm_current->n) {
			dspstr(3, y, ptr->guide, C_GUIDE);
			clrtoeol();
			t_loc(26, y);
			t_color(t_color_code(C_GUIDE));
			switch (ptr->type) {
			case KWD_SEL:	/* 選択項目 */
				dsp_sel(pbp->sel, ptr->ptr.sel, y);
				break;
			case KWD_NUM:	/* 数値項目 */
				dsp_num(pbp->num, ptr->ptr.num, y);
				break;
			case KWD_NAM:	/* 名称項目 */
				dsp_nam(pbp->nam, ptr->ptr.nam, y);
				break;
			case KWD_MSG:	/* 説明項目 */
				dsp_gud(pbp->gud, ptr->ptr.gud, y);
				break;
			}
		}
	}
}

/*=======================================================================
 |
 |		選択項目表示処理
 |
 |	void	dsp_sel(selbp, seltp, y)
 |
 |		SEL_BUF	*selbp;		選択項目入力バッファ
 |		SEL_TBL	*seltp;		選択項目テーブル
 |		int	y;		表示行位置
 |
 =======================================================================*/
static	void	dsp_sel(SEL_BUF *selbp, SEL_TBL *seltp, int y)
{
	int	 i;		/* ループカウンタ */

	cputs("[ ");
	for (i = 0; i < seltp->n; i++) {
		cputs(seltp->ptr[i][0]);
		t_color(t_color_code(color));
		putch(' ');
		t_color(t_color_code(C_GUIDE));
	}
	cputs("]");
	attrib(selbp->x[selbp->sel_no], y, selbp->len[selbp->sel_no]
			, A_WHITE|A_ULINE, t_color_code(color)|A_ULINE);
}

/*=======================================================================
 |
 |		数値項目表示処理
 |
 |	void	dsp_num(numbp, numtp, y)
 |
 |		NUM_BUF	*numbp;		数値項目入力バッファ
 |		NUM_TBL	*numtp;		数値項目テーブル
 |		int	y;		表示行位置
 |
 =======================================================================*/
static	void	dsp_num(NUM_BUF *numbp, NUM_TBL *numtp, int y)
{
	cputs("[ ");
	if (numtp->sel != NULL) {
		cputs(numtp->sel);
		putch(' ');
		if (numbp->sel)
			attrib(28, y, strlen(numtp->sel), A_WHITE|A_ULINE
						, t_color_code(color)|A_ULINE);
	}
	cputs(numbp->buf);
	cputs(" ]");
	if (numtp->min_flg || numtp->max_flg) {
		cputs("( ");
		if (numtp->min_flg)
			cprintf("%u", numbp->min);
		cputs("～");
		if (numtp->max_flg)
			cprintf("%u", numbp->max);
		cputs(" )");
	}
}

/*=======================================================================
 |
 |		名称項目表示処理
 |
 |	void	dsp_nam(nambp, namtp, y)
 |
 |		NAM_BUF	*nambp;		名称項目入力バッファ
 |		NAM_TBL	*namtp;		名称項目テーブル
 |		int	y;		表示行位置
 |
 =======================================================================*/
static	void	dsp_nam(NAM_BUF *nambp, NAM_TBL *namtp, int y)
{
	cputs("[ ");
	if (namtp->sel != NULL) {
		cputs(namtp->sel);
		putch(' ');
		if (nambp->sel)
			attrib(28, y, strlen(namtp->sel), A_WHITE|A_ULINE
						, t_color_code(color)|A_ULINE);
	}
	if (namtp->ext1 != NULL) {
		cprintf("%s.%s", nambp->flag ? "        " : nambp->buf
								, namtp->ext1);
		if (namtp->ext2 != NULL)
			cprintf(" %s.%s", nambp->flag ? nambp->buf : "        "
								, namtp->ext2);
	} else
		cputs(nambp->buf);
	cputs(" ]");
}

/*=======================================================================
 |
 |		説明項目表示処理
 |
 |	void	dsp_gud(gudbp, gudtp, y)
 |
 |		GUD_BUF	*gudbp;		説明項目入力バッファ
 |		GUD_TBL	*gudtp;		説明項目テーブル
 |		int	y;		表示行位置
 |
 =======================================================================*/
static	void	dsp_gud(GUD_BUF *gudbp, GUD_TBL *gudtp, int y)
{
	cprintf("[ %s ]", gudbp->buf);
}

/*=======================================================================
 |
 |		パラメータ編集処理
 |
 |	void	edit_prm(pbp)
 |
 |		PRM_BUF	*pbp;		パラメータ入力バッファ
 |
 =======================================================================*/
static	void	edit_prm(PRM_BUF *pbp)
{
	PRT_TBL *ptr;
	PRM_BUF	prm;
	SEL_TBL	*seltp;
	NUM_TBL	*numtp;
	NAM_TBL	*namtp;
	GUD_TBL	*gudtp;
	int	i, len, len2, separator;
	char	*cp;

	if (prm_current->name != NULL) {
		cp = &cmdbuf[8];
		separator = ',';
	} else {
		cp = &cmdbuf[4];
		separator = ' ';
	}
	ptr = prm_current->ptr;
	for (i = 0; i < prm_current->n; i++) {
		*cp++ = separator;
		separator = ',';
		switch (ptr->type) {
		case KWD_SEL:	/* 選択項目 */
			seltp = ptr->ptr.sel;
			prm.sel = pbp->sel;
			len = strlen(seltp->ptr[prm.sel->sel_no][1]);
			memcpy(cp, seltp->ptr[prm.sel->sel_no][1], len);
			cp += len;
			break;
		case KWD_NUM:	/* 数値項目 */
			numtp = ptr->ptr.num;
			prm.num = pbp->num;
			if (prm.num->sel) {
				len = strlen(numtp->sel);
				memcpy(cp, numtp->sel, len);
			} else {
				len = strcount(prm.num->buf);
				if (len != 0 && numtp->prmid != NULL) {
					len2 = strlen(numtp->prmid);
					memcpy(cp, numtp->prmid, len2);
					cp += len2;
				}
				memcpy(cp, prm.num->buf, len);
			}
			cp += len;
			break;
		case KWD_NAM:	/* 名称項目 */
			namtp = ptr->ptr.nam;
			prm.nam = pbp->nam;
			if (prm.nam->sel) {
				len = strlen(namtp->sel);
				memcpy(cp, namtp->sel, len);
				cp += len;
			} else {
				len = strcount(prm.nam->buf);
				memcpy(cp, prm.nam->buf, len);
				cp += len;
				if (namtp->ext1 != NULL && len != 0) {
					*cp++ = '.';
					if (prm.nam->flag == 0) {
						len = strlen(namtp->ext1);
						memcpy(cp, namtp->ext1, len);
					} else {
						len = strlen(namtp->ext2);
						memcpy(cp, namtp->ext2, len);
					}
					cp += len;
				}
			}
			break;
		case KWD_MSG:	/* 説明項目 */
			gudtp = ptr->ptr.gud;
			prm.gud = pbp->gud;
			len = strcount(prm.gud->buf);
			memcpy(cp, prm.gud->buf, len);
			cp += len;
			break;
		}
		pbp++;
		ptr++;
	}
	*cp = '\0';
}
