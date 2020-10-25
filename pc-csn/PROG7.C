/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: メッセージ受信画面処理		*
 *		ファイル名	: prog7.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"
#include	<time.h>

/*	通信ステータスコード	*/
#define	ST_RD	0
#define	ST_RA	1
#define	ST_SR	2
#define	ST_CQ	3
#define	ST_AK	4
#define	ST_STSR	5

/*	内部関数	*/
static	void	check_key(void);
static	void	check_tim(void);
static	void	check_rcv(void);
static	void	rcv_sra(void);
static	void	rcv_raa(void);
static	void	rcv_cqa(void);
static	void	rcv_ab(void);
static	void	rcv_rd(void);
static	void	rcv_aka(void);
static	void	time_out(void);
static	void	next_prog(int);
static	void	kaiwa_outou(void);

/*	スタティック変数	*/
static	int	status;
static	int	next_prog_no;
static	long	rcv_time;
static	int	kaiwa_flag;

/*=======================================================================
 |
 |		メッセージ受信画面処理
 |
 |	void	prog7()
 |
 =======================================================================*/
void	prog7()
{
	static	LINE	line[] = {	/* 罫線データ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	int	color;		/* 色コード */

	/* コマンド作成中の場合はコマンド選択画面へ */
	if (mkcmd) {
		prog = 2;
		return;
	}

	if (old_prog != 6) {
		/* 画面消去 */
		t_cls();
		g_cls();
	}

	/* 罫線表示 */
	dspline(line, C_WAKU);

	/* 画面表示 */
	if (online) {
		color = C_ONLINE;
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg700);
	} else {
		color = C_OFFLINE;
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg701);
	}
	dspstr(2, 22, msg710, color);
	dspstr(26, 22, msg720, C_GUIDE);

	dsp_msgenv();		/* メッセージ出力環境表示 */

	restore_scr();		/* 受信画面リストア */

	setfunc(online ? 0 : FUNC1);	/* ファンクションキーセット */

	next_prog_no = 0;	/* プログラム番号クリア */
	stop_flag = 0;		/* ＳＴＯＰキー入力フラグクリア */

	if (online) {
		time(&rcv_time);	/* 現時刻読みだし */
		timreset();		/* タイマーリセット */
		send_sig(SIG_RA);	/* ＲＡ信号送信 */
		status = ST_RA;
	} else
		status = ST_RD;

	while (prog == 7) {
		if (status == ST_RD) {
			check_key();	/* キー入力チェック */
			check_tim();	/* 時間チェック */
		}
		check_rcv();		/* 受信チェック */
		out_msg();		/* メッセージ出力 */
	}

	save_scr();		/* 受信画面セーブ */

	/* コマンドファイル実行中だったら中止する */
	if (exec_cmf_flag) {
		fclose(fp_cmf);
		exec_cmf_flag = 0;
	}
}

/*=======================================================================
 |
 |		キー入力チェック
 |
 |	void	check_key()
 |
 =======================================================================*/
static	void	check_key()
{
	/* ＳＴＯＰキー入力チェック */
	if (stop_flag) {
		stop_flag = 0;		/* ＳＴＯＰキー入力フラグクリア */
		send_sig(SIG_STSR);	/* ＳＴＳＲ信号送信 */
		status = ST_STSR;
		return;
	}
		
	switch (inkey()) {
	case 0:
		break;
	case KEY_ESC:
		if (online)
			next_prog(2);	/* コマンド選択画面－１ */
		else {
			/* ローカルコマンドＩＤセット */
			cms_current = loctbl;
			cmdbuf[0] = csg.nsg.offline_id;
			cmdbuf[1] = '\0';

			next_prog(3);	/* コマンド選択画面－２ */
		}
		break;
	case KEY_HOME:	/* ＨＯＭＥキー */
	case KEY_HELP:	/* ＨＥＬＰキー */
		next_prog(6);	/* コマンド直接入力画面 */
		break;
	case KEY_F1:
		next_prog(1);	/* コマンドコネクション開設画面 */
		break;
	default:
		beep();
		break;
	}
}

/*=======================================================================
 |
 |		定時起動コマンドチェック＆無活動監視
 |
 |	void	check_tim()
 |
 =======================================================================*/
static	void	check_tim()
{
	long	ltime;			/* 現時刻 */
	struct	tm	*ltm;		/* 時間情報ポインタ */
	char	ctime[11];		/* 現時刻 */
	static	char	stime[11];	/* 前回時刻 */
	int	i;			/* ループカウンタ */
	char	*sp;			/* セパレータポインタ */
	char	buf[82];		/* 定時起動コマンドバッファ */

	if (online == 0)
		return;

	/* 現在時間取り出し */
	time(&ltime);

	/* 無活動監視 */
	if (ssg.nsg.kanshi != 0) {
		if (ltime > rcv_time + ssg.nsg.kanshi * 60) {
			send_sig(SIG_AK);
			status = ST_AK;
			rcv_time += ssg.nsg.kanshi * 60;
		}
	}

	/* 時間編集 */
	ltm = localtime(&ltime);
	sprintf(ctime, "%02u%02u%02u%02u%02u",
			ltm->tm_year, ltm->tm_mon+1, ltm->tm_mday,
			ltm->tm_hour, ltm->tm_min);

	if (strcmp(ctime, stime) == 0)
		return;

	/* 定時起動コマンドチェック */
	for (i = 0; i < 5; i++) {
		strcpy(buf, ssg.aec[i].command);
		if ((sp = strchr(buf, ';')) != NULL) {
			*sp = '\0';
			if (strcmp(ctime+(10 - (sp - buf)), buf) == 0) {
				sp++;
				/* リターンキーセット */
				strcat(sp, "\x0d");

				/* キーバッファにセット */
				set_keybuf(sp);

				/* コマンド直接入力画面 */
				next_prog(6);
				break;
			}
		}
	}

	strcpy(stime, ctime);	/* 現時刻セーブ */
}

/*=======================================================================
 |
 |		データ受信チェック
 |
 |	void	check_rcv()
 |
 =======================================================================*/
static	void	check_rcv()
{
	if (online == 0)
		return;

	switch (receive()) {
	case SIG_SRA:		/* ＳＲＡ信号受信 */
		rcv_sra();
		break;
	case SIG_RAA:		/* ＲＡＡ信号受信 */
		rcv_raa();
		break;
	case SIG_CQA:		/* ＣＱＡ信号受信 */
		rcv_cqa();
		break;
	case SIG_AB:		/* ＡＢ信号受信 */
		rcv_ab();
		break;
	case SIG_RD:		/* ＲＤ信号受信 */
		rcv_rd();
		break;
	case SIG_AKA:		/* ＡＫＡ信号受信 */
		rcv_aka();
		break;
	case TIMEOUT:		/* タイムアウト */
		time_out();
		break;
	}
}

/*=======================================================================
 |
 |		ＳＲＡ信号受信処理
 |
 |	void	rcv_sra()
 |
 =======================================================================*/
static	void	rcv_sra()
{
	if (status == ST_SR) {
		if (kaiwa_flag)
			kaiwa_outou();	/* 会話応答 */
		else if (exec_cmf_flag) {
			exec_cmf();		/* コマンドファイル実行 */
			send_sig(SIG_RA);	/* ＲＡ信号送信 */
			status = ST_RA;
		} else
			prog = next_prog_no;
	} else if (status == ST_STSR) {
		send_sd("STP/");	/* ＳＴＰコマンド送信 */
		send_sig(SIG_RA);	/* ＲＡ信号送信 */
		status = ST_RA;
	}
}

/*=======================================================================
 |
 |		ＲＡＡ信号受信処理
 |
 |	void	rcv_raa()
 |
 =======================================================================*/
static	void	rcv_raa()
{
	if (status == ST_RA) {
		status = ST_RD;
		timreset();		/* タイマーリセット */
		if (disconxxxt_request) {
			/* コネクション切断要求処理 */
			disconxxxt_request = 0;
			send_sig(SIG_CQ);	/* ＣＱ信号送信 */
			status = ST_CQ;
		}
	}
}

/*=======================================================================
 |
 |		ＣＱＡ信号受信処理
 |
 |	void	rcv_cqa()
 |
 =======================================================================*/
static	void	rcv_cqa()
{
	if (status == ST_CQ) {
		timreset();	/* タイマーリセット */
		online = 0;	/* オンラインフラグクリア */
		/* コネクション切断メッセージ表示 */
		dsp_loc_msg(LOC_MSG_DISCON, ssg.nsg.conxxxtion);
		prog = 1;
	}
}

/*=======================================================================
 |
 |		ＡＢ信号受信処理
 |
 |	void	rcv_ab()
 |
 =======================================================================*/
static	void	rcv_ab()
{
	/* アボート信号受信メッセージ表示 */
	ab_msg[15] = rcv_buf[4];
	enq_msg(MSG_ANM, ab_msg);

	online = 0;	/* オンラインフラグクリア */

	/* コネクション切断メッセージ表示 */
	dsp_loc_msg(LOC_MSG_DISCON, ssg.nsg.conxxxtion);

	out_msg();	/* メッセージ出力 */
	alxxx_mj();	/* アラーム */
	prog = 8;	/* コネクション切断画面 */
}

/*=======================================================================
 |
 |		ＲＤ信号受信処理
 |
 |	void	rcv_rd()
 |
 =======================================================================*/
static	void	rcv_rd()
{
	switch (rcv_buf[4]) {
	case '2':	/* コマンド応答メッセージ */
		/* 会話応答チェック */
		if (rcv_buf[5] == MN_KAIWA) {
			send_sig(SIG_SR);
			status = ST_SR;
			kaiwa_flag = 1;
		}

		/* コマンドファイル実行中チェック */
		if (exec_cmf_flag) {
			if (strncmp(rcv_buf+5, MN_NORMAL, 4) == 0
				    || strncmp(rcv_buf+5, MN_ERROR, 4) == 0
				    && csg.opt.cmd_err == 0) {
				send_sig(SIG_SR);
				status = ST_SR;
			} else if (strncmp(rcv_buf+5, MN_ERROR, 4) == 0
						&& csg.opt.cmd_err != 0) {
				fclose(fp_cmf);
				exec_cmf_flag = 0;
			}
		}

		memcpy(rcv_buf+1, "@MSG", 4);
		enq_msg(MSG_CRM, rcv_buf+1);
		break;
	case '3':	/* 自立メッセージ */
	case '4':	/* 再開メッセージ */
		memcpy(rcv_buf+1, "@MSG", 4);
		enq_msg(MSG_ANM, rcv_buf+1);
		break;
	case '5':	/* 修正メッセージ */
		cmdbuf[0] = csg.nsg.online_id;
		strcpy(cmdbuf+1, rcv_buf+5);
		if (search_cms(cmdbuf+1) != 0)
			next_prog(5);
		else {
			/* コマンド終了マークセット */
			strcat(cmdbuf, "/");

			/* キーバッファにセット */
			set_keybuf(cmdbuf);

			next_prog(6);
		}
		break;
	}
}

/*=======================================================================
 |
 |		ＡＫＡ信号受信処理
 |
 |	void	rcv_aka()
 |
 =======================================================================*/
static	void	rcv_aka()
{
	if (status == ST_AK) {
		status = ST_RD;
		timreset();	/* タイマーリセット */
	}
}

/*=======================================================================
 |
 |		タイムアウト処理
 |
 |	void	time_out()
 |
 =======================================================================*/
static	void	time_out()
{
	/* システム無応答メッセージ表示 */
	dsp_loc_msg(LOC_MSG_NORESP);

	online = 0;	/* オンラインフラグクリア */

	/* コネクション切断メッセージ表示 */
	dsp_loc_msg(LOC_MSG_DISCON, ssg.nsg.conxxxtion);

	out_msg();	/* メッセージ出力 */
	alxxx_mj();	/* アラーム */
	prog = 8;	/* コネクション切断画面 */
}

/*=======================================================================
 |
 |		プログラム番号セット
 |
 |	void	next_prog(prog_no)
 |
 |		int	prog_no;	プログラム番号
 |
 =======================================================================*/
static	void	next_prog(int prog_no)
{
	if (online) {
		send_sig(SIG_SR);	/* ＳＲ信号送信 */
		status = ST_SR;
		next_prog_no = prog_no;
	} else
		prog = prog_no;
}

/*=======================================================================
 |
 |		会話応答処理
 |
 |	void	kaiwa_outou()
 |
 =======================================================================*/
static	void	kaiwa_outou()
{
	static	char	outou[] = "\\(Y/N)";	/* 会話応答メッセージ */
	int	yn;		/* Ｙｅｓ／Ｎｏ フラグ */
	int	loop_sw;	/* ループ制御変数 */

	/* 会話応答メッセージ表示 */
	outou[0] = csg.nsg.online_id;
	dsp_msg(outou, C_CRM);

	yn = 0;		/* Ｙｅｓ／Ｎｏフラグ初期化 */
	t_csron();	/* カーソル表示 */

	loop_sw = 1;
	while (loop_sw) {
		t_loc(yn == 0 ? 4 : 6, csr_y + 2);
		switch (getchr()) {
		case KEY_CR:
			loop_sw = 0;
			break;
		case KEY_LEFT:
		case 'Y':
		case 'y':
			yn = 0;
			break;
		case KEY_RIGHT:
		case 'N':
		case 'n':
			yn = 1;
			break;
		case ' ':
			yn ^= 1;
			break;
		}
	}

	t_csroff();	/* カーソル消去 */

	send_sd(yn == 0 ? "Y" : "N");	/* Ｙ or Ｎ 送信 */

	send_sig(SIG_RA);	/* ＲＡ信号送信 */
	status = ST_RA;

	kaiwa_flag = 0;
}
