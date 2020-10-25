/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンド直接入力画面処理		*
 *		ファイル名	: prog6.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	内部関数	*/
static	void	prog6a(void);
static	void	prog6b(void);
int	exec_cmd(char *);
static	void	dsp_comment(char *, int);
static	char	*find_comment(CMS_TBL *, char *, char *);

/*=======================================================================
 |
 |		コマンド作成中チェック
 |
 |	void	prog6()
 |
 =======================================================================*/
void	prog6()
{
	if (!mkcmd)
		prog6a();	/* コマンド直接入力画面処理 */
	else
		prog6b();	/* コマンドファイル登録画面処理 */
}

/*=======================================================================
 |
 |		コマンド直接入力画面処理
 |
 |	void	prog6a()
 |
 =======================================================================*/
void	prog6a()
{
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	int	ch;		/* キー入力文字 */
	int	color;		/* 色コード */
	char	buf[76*2+1];	/* コマンド入力バッファ */
	char	id[2];		/* コマンドＩＤ */

	if (old_prog != 7) {
		/* 画面消去 */
		t_cls();
		g_cls();
	}

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	if (online) {
		color = C_ONLINE;
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg600);
	} else {
		color = C_OFFLINE;
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg601);
	}
	dspstr(2, 22, msg610, color);
	dspstr(28, 22, msg620, C_GUIDE);

	dsp_msgenv();		/* メッセージ出力環境表示 */

	restore_scr();		/* 受信画面リストア */

	out_msg();		/* メッセージ出力 */

	/* 改行 */
	csr_y = next_line(csr_y);
	if (csr_y == 17) {
		next_line(csr_y);
		csr_y--;
	}

	setfunc(FUNC4|FUNC5);	/* ファンクションキーセット */
	
	memset(buf, ' ', 76);	/* 入力バッファクリア */

	while (prog == 6) {
		/* コマンド入力 */
		ch = inputm(buf, 2, csr_y + 3, 76 * 2, 76, C_GUIDE);
		switch (ch) {
		case KEY_CR:
			prog = 7;
			buf[strcount(buf)] = '\0';
			exec_cmd(buf);		/* コマンド実行 */
			break;
		case KEY_ESC:
			prog = 7;
			break;
		case KEY_SFT_ESC:
			prog = old_prog;
			break;
		case KEY_HOME:
		case KEY_HELP:
			if (online)
				prog = 2;
			else {
				cms_current = loctbl;
				cmdbuf[0] = csg.nsg.offline_id;
				cmdbuf[1] = '\0';
				prog = 3;
			}
			break;
		case KEY_F4:
			id[0] = csg.nsg.offline_id;
			id[1] = '\0';
			set_keybuf(id);
			break;
		case KEY_F5:
			id[0] = csg.nsg.online_id;
			id[1] = '\0';
			set_keybuf(id);
			break;
		default:
			beep();
			break;
		}
	}

	dspspc(2, csr_y + 3, 76);
	dspspc(2, csr_y + 4, 76);

	save_scr();		/* 受信画面セーブ */
}

/*=======================================================================
 |
 |		コマンド実行処理
 |
 |	int	exec_cmd(buf)
 |
 |		char	*buf;		コマンド入力バッファ
 |
 |		返値			０：正常終了　１：異常終了
 |
 =======================================================================*/
int	exec_cmd(char *buf)
{
	int	st;		/* コマンド実行結果 */

	if (*buf == csg.nsg.online_id) {
		/* オンラインコマンド */
		enq_msg(MSG_CRM, buf);		/* コマンド表示 */
		dsp_comment(buf, 0);		/* コメント表示 */
		if (online) 
			send_sd(buf+1);		/* コマンド送信 */
		st = 0;
	} else if (*buf == csg.nsg.offline_id) {
		/* ローカルコマンド */
		enq_msg(MSG_CNS, buf);		/* コマンド表示 */
		dsp_comment(buf, 1);		/* コメント表示 */
		st = exec_local(buf);		/* ローカルコマンド実行 */
	} else if (strncmp(buf, "/*", 2) == 0) {
		/* コメント */
		enq_msg(MSG_CNS, buf);		/* コメント表示 */
		st = 0;
	} else if (buf[0] >= 'A' && buf[0] <= 'Z'
					|| buf[0] >= 'a' && buf[0] <= 'z') {
		/* コマンドファイル */
		enq_msg(MSG_CNS, buf);		/* コマンドファイル名表示 */
		st = start_cmf(buf);		/* コマンドファイル実行 */
	} else {
		/* 空白行 */
		st = 0;
	}

	return(st);
}

/*=======================================================================
 |
 |		コメント表示処理
 |
 |	void	dsp_comment(bufp, flag)
 |
 |		char	*bufp;		コマンドバッファ
 |		int	flag;		０：オンライン　１：ローカル
 |
 =======================================================================*/
static	void	dsp_comment(char *bufp, int flag)
{
	char	cmd_name[4];	/* コマンド名 */
	char	prm_name[4];	/* パラメータ名 */
	char	comment[80];	/* コメント編集バッファ */
	int	i;		/* ループカウンタ */
	char	*cp;

	if (csg.opt.comment == 0)
		return;

	/* コマンド名取り出し */
	for (i = 0, bufp++; i < 3 && *bufp != '\0'; i++, bufp++)
		cmd_name[i] = *bufp;
	cmd_name[i] = '\0';

	/* パラメータ名取り出し */
	while (*bufp == ' ')
		bufp++;
	for (i = 0; i < 3 && *bufp != '\0'; i++, bufp++)
		prm_name[i] = *bufp;
	prm_name[i] = '\0';

	if (flag == 0) {
		/* オンラインコマンド検索 */
		for (i = 0; i < 5; i++) {
			cp = find_comment(cmstbl[i], cmd_name, prm_name);
			if (cp != NULL)
				break;
		}
	} else {
		/* ローカルコマンド検索 */
		cp = find_comment(loctbl, cmd_name, prm_name);
	}

	if (cp != NULL) {
		/* コメント表示 */
		sprintf(comment, "/* %s */", cp);
		enq_msg(flag ? MSG_CNS : MSG_CRM, comment);
	}
}

/*=======================================================================
 |
 |		コメント検索処理
 |
 |	char	*find_comment(cmstp, cmd_name, prm_name)
 |
 |		CMS_TBL	*cmstp;		コマンド選択テーブルポインタ
 |		char	*cmd_name;	コマンド名
 |		char	*prm_name;	パラメータ名
 |
 |		返値			コメント
 |
 =======================================================================*/
static	char	*find_comment(CMS_TBL *cmstp, char *cmd_name, char *prm_name)
{
	int	i;		/* ループカウンタ */
	CMD_TBL	*cmdtp;		/* コマンドテーブルポインタ */
	PRM_TBL	*prmtp;		/* パラメータ種類テーブルポインタ */

	if (cmstp == NULL)
		return(NULL);

	/* コマンド名検索 */
	for (i = 0, cmdtp = cmstp->ptr; i < cmstp->n; i++, cmdtp++) {
		if (strcmp(cmd_name, cmdtp->name) == 0)
			break;
	}

	if (i == cmstp->n)
		return(NULL);

	if (cmdtp->n == 0 || cmdtp->ptr->name == NULL)
		return(cmdtp->guide);

	/* パラメータ名検索 */
	for (i = 0, prmtp = cmdtp->ptr; i < cmdtp->n; i++, prmtp++) {
		if (strcmp(prm_name, prmtp->name) == 0)
			break;
	}

	if (i == cmdtp->n)
		return(NULL);

	return(prmtp->guide);
}

/*=======================================================================
 |
 |		コマンドファイル登録画面処理
 |
 |	void	prog6b()
 |
 =======================================================================*/
void	prog6b()
{
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	int	ch;		/* キー入力文字 */
	int	color;		/* 色コード */
	int	y;		/* コマンド表示行位置 */
	int	mode;		/* コマンド入力／ＹＮ入力モード */
	int	y_or_n;		/* Ｙｅｓ ｏｒ Ｎｏ */
	char	buf[76*2+1];	/* コマンド入力バッファ */
	char	id[2];		/* コマンドＩＤ */
	QUEUE	*qp;		/* キューポインタ */

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	color = online ? C_ONLINE : C_OFFLINE;
	xprintf(2, 1, color, msg30, csg.nsg.sys_name, cmf_name);
	dspstr(2, 22, msg650, C_GUIDE);
	dspstr(2, 23, msg660, C_GUIDE);

	/* 入力フィールド表示 */
	dsp_field(37, 23,  1, C_GUIDE);

	y = 0;

	/* いままで登録したコマンドの表示 */
	for (qp = cmf_que; qp != NULL; qp = qp->chain) {
		dspstr(2, y + 3, qp->data, C_GUIDE);
		y = next_line(y);
	}

	setfunc(FUNC4|FUNC5);	/* ファンクションキーセット */

	memset(buf, ' ', 76);	/* 入力バッファクリア */

	mode = 0;		/* コマンド直接入力モード */
	y_or_n = ' ';		/* Ｙｅｓ／Ｎｏ入力フラグクリア */

	while (prog == 6) {
		if (mode == 0) {
			/* コマンド直接入力 */
			ch = inputm(buf, 2, y + 3, 76 * 2, 76, C_GUIDE);
		} else {
			/* Ｙｅｓ　ｏｒ　Ｎｏ入力 */
			t_loc(37, 23);
			t_csron();
			ch = getchr();
			t_csroff();
		}
		switch (ch) {
		case KEY_CR:
			if (mode == 0) {
				buf[strcount(buf)] = '\0';
				enq_cmf(buf);
				y = next_line(y);
				memset(buf, ' ', 76);
			} else {
				if (y_or_n == 'Y')
					prog = 2;
				else if (y_or_n == 'N') {
					write_cmf();
					prog = 0;
				} else
					beep();
			}
			break;
		case KEY_HOME:
		case KEY_HELP:
			mode = !mode;
			break;
		case KEY_F4:
			id[0] = csg.nsg.offline_id;
			id[1] = '\0';
			set_keybuf(id);
			break;
		case KEY_F5:
			id[0] = csg.nsg.online_id;
			id[1] = '\0';
			set_keybuf(id);
			break;
		case 'Y':
		case 'y':
			y_or_n = 'Y';
			dspstr(37, 23, "Y", C_GUIDE);
			break;
		case 'N':
		case 'n':
			y_or_n = 'N';
			dspstr(37, 23, "N", C_GUIDE);
			break;
		default:
			beep();
			break;
		}
	}

	/* 画面消去 */
	t_cls();
	g_cls();
}

/*=======================================================================
 |
 |		コマンドファイル内容表示画面処理
 |
 |	void	prog6c()
 |
 =======================================================================*/
void	prog6c()
{
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 22, 79, 22},
		{-1}
	};
	int	color;		/* 色コード */
	int	y;		/* コマンド表示行位置 */
	char	*cmd;		/* コマンドポインタ */

	/* 画面消去 */
	t_cls();
	g_cls();

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	color = online ? C_ONLINE : C_OFFLINE;
	xprintf(2, 1, color, msg670, csg.nsg.sys_name, cmf_name);
	dspstr(2, 23, msg680, C_GUIDE);

	/* いままで登録したコマンドの表示 */
	y = 0;
	while ((cmd = deq_cmf()) != NULL) {
		dspstr(2, y + 3, cmd, C_GUIDE);
		y = next_line(y);
		free(cmd);
	}

	/* ＥＳＣ入力待ち */
	while (getchr() != KEY_ESC)
		;

	/* 画面消去 */
	t_cls();
	g_cls();
}
