/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: ローカルコマンド処理			*
 *		ファイル名	: local.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	ローカルコマンド	*/
#define	LOC_CSG		0x10
#define	LOC_CSG_NSG	0x11
#define	LOC_CSG_FSG	0x12
#define	LOC_CSG_COL	0x13
#define	LOC_CSG_LRM	0x14
#define	LOC_CSG_OPT	0x15
#define	LOC_SSG		0x20
#define	LOC_SSG_CON	0x21
#define	LOC_SSG_NSG	0x22
#define	LOC_SSG_SYC	0x23
#define	LOC_SSG_ANM	0x24
#define	LOC_SSG_CRM	0x25
#define	LOC_SSG_CMF	0x26
#define	LOC_SSG_AEC	0x27
#define	LOC_DSP		0x30
#define	LOC_DSP_CSG	0x31
#define	LOC_DSP_SSG	0x32
#define	LOC_CSC		0x40
#define	LOC_CSC_CCQ	0x41
#define	LOC_CSC_DOS	0x42
#define	LOC_CSC_END	0x43
#define	LOC_UTL		0x50
#define	LOC_UTL_CPY	0x51
#define	LOC_UTL_CFG	0x52
#define	LOC_UTL_CFD	0x53
#define	LOC_UTL_MTR	0x54

/*	パラメータテーブル	*/
static	char	*csg_col[] = {	/* 表示色 */
	"",		/* 0 黒 */
	"B",		/* 1 青 */
	"R",		/* 2 赤 */
	"P",		/* 3 紫 */
	"G",		/* 4 緑 */
	"LB",		/* 5 水色 */
	"Y",		/* 6 黄 */
	"W",		/* 7 白 */
	NULL
};
static	char	*csg_lrm1[] = {	/* 画面表示 */
	"",		/* 0 無し */
	"DSP",		/* 1 画面表示有り */
	NULL
};
static	char	*csg_lrm2[] = {	/* プリンタ出力 */
	"",		/* 0 無し */
	"PRN",		/* 1 有り */
	NULL
};
static	char	*csg_lrm3[] = {	/* ファイル出力 */
	"",		/* 0 出力無し */
	"FIL",		/* 1 日本語 */
	NULL
};
static	char	*csg_opt1[] = {	/* RS-232C回線速度 */
	"9600",		/* 0 9600 */
	"4800",		/* 1 4800 */
	"2400",		/* 2 2400 */
	"1200",		/* 3 1200 */
	NULL
};
static	char	*csg_opt2[] = {	/* プリンターの機種指定 */
	"201",		/* 0 ＮＥＣ　ＰＣ－ＰＲ２０１ */
	"602",		/* 1 ＮＥＣ　ＰＣ－ＰＲ６０２ */
	"NM",		/* 2 ＮＥＣ　ＮＭシリーズ */
	"xxxxx",	/* 3 ＥＰＳＯＮ */
	NULL
};
static	char	*csg_opt3[] = {	/* ｺﾏﾝﾄﾞﾌｧｲﾙ実行ｴﾗｰ */
	"",		/* 0 無視 */
	"STP",		/* 1 停止 */
	NULL
};
static	char	*csg_opt4[] = { /* コメントの自動挿入 */
	"",		/* 0 挿入無し */
	"INS",		/* 1 自動挿入 */
	NULL
};
static	char	*ssg_anm1[] = {	/* 画面表示 */
	"",		/* 0 無し */
	"DSP",		/* 1 画面表示有り */
	NULL
};
static	char	*ssg_anm2[] = {	/* プリンタ出力 */
	"",		/* 0 無し */
	"PRN",		/* 1 有り */
	NULL
};
static	char	*ssg_anm3[] = {	/* ファイル出力 */
	"",		/* 0 出力無し */
	"FIL",		/* 1 日本語 */
	"FIC",		/* 2 番号 */
	NULL
};
static	char	*ssg_crm1[] = {	/* 画面表示 */
	"",		/* 0 無し */
	"DSP",		/* 1 画面表示有り */
	NULL
};
static	char	*ssg_crm2[] = {	/* プリンタ出力 */
	"",		/* 0 無し */
	"PRN",		/* 1 有り */
	NULL
};
static	char	*ssg_crm3[] = {	/* ファイル出力 */
	"",		/* 0 出力無し */
	"FIL",		/* 1 日本語 */
	"FIC",		/* 2 番号 */
	NULL
};
static	char	*utl_cfd[] = {	/* コマンドファイル処理 */
	"DSP",		/* 0 表示 */
	"UPD",		/* 1 修正 */
	"DEL",		/* 2 削除 */
	NULL
};
static	char	*utl_mtr1[] = {	/* メッセージ種類 */
	"ANM",		/* 0 自立メッセージ */
	"CRM",		/* 1 コマンド応答メッセージ */
	NULL
};
static	char	*utl_mtr2[] = {	/* 画面表示 */
	"",		/* 0 無し */
	"DSP",		/* 1 画面表示有り */
	NULL
};
static	char	*utl_mtr3[] = {	/* プリンタ出力 */
	"",		/* 0 無し */
	"PRN",		/* 1 有り */
	NULL
};
static	char	*utl_mtr4[] = {	/* ファイル出力 */
	"",		/* 0 出力無し */
	"FIL",		/* 1 日本語 */
	NULL
};

/*	スタティック変数	*/
static	char	*cmbp;		/* コマンドポインタ */
static	char	cmd_name[4];	/* コマンド（分類）名 */
static	char	prm_name[4];	/* パラメータ（小分類）名 */

/*	内部関数	*/
static	int	exec_csg_nsg(void);
static	int	exec_csg_fsg(void);
static	int	exec_csg_col(void);
static	int	exec_csg_lrm(void);
static	int	exec_csg_opt(void);
static	int	exec_ssg_con(void);
static	int	exec_ssg_nsg(void);
static	int	exec_ssg_syc(void);
static	int	exec_ssg_anm(void);
static	int	exec_ssg_crm(void);
static	int	exec_ssg_cmf(void);
static	int	exec_ssg_aec(void);
static	int	exec_dsp_csg(void);
static	void	exec_dsp_csg_nsg(PRT_TBL *);
static	void	exec_dsp_csg_fsg(PRT_TBL *);
static	void	exec_dsp_csg_col(PRT_TBL *);
static	void	exec_dsp_csg_lrm(PRT_TBL *);
static	void	exec_dsp_csg_opt(PRT_TBL *);
static	int	exec_dsp_ssg(void);
static	void	exec_dsp_ssg_nsg(PRT_TBL *);
static	void	exec_dsp_ssg_syc(PRT_TBL *);
static	void	exec_dsp_ssg_anm(PRT_TBL *);
static	void	exec_dsp_ssg_crm(PRT_TBL *);
static	void	exec_dsp_ssg_cmf(PRT_TBL *);
static	void	exec_dsp_ssg_aec(PRT_TBL *);
static	void	dspsel(PRT_TBL *, char *);
static	void	dspnum(PRT_TBL *, int);
static	void	dspnam(PRT_TBL *, char *);
static	int	exec_csc_ccq(void);
static	int	exec_csc_dos(void);
static	int	exec_csc_end(void);
static	int	exec_utl_cpy(void);
static	int	exec_utl_cfg(void);
static	int	exec_utl_cfd(void);
static	int	exec_utl_mtr(void);
static	int	getsel(char *, char **);
static	char	*getprm(void);
static	void	setsel(char *);
static	void	setnum(int);
static	void	setnam(char *);
static	int	chk_loc_cmd(void);

/*=======================================================================
 |
 |		ローカルコマンド実行処理
 |
 |	int	exec_local(buf)
 |
 |		char	*buf;		コマンドバッファ
 |
 =======================================================================*/
int	exec_local(char *buf)
{
	int	msg_no;		/* メッセージＮｏ. */

	cmbp = buf;
	switch (chk_loc_cmd()) {
	case LOC_CSG_NSG:	/* コンソールＳＧファイルを作る */
		msg_no = exec_csg_nsg();
		break;
	case LOC_CSG_FSG:	/* コマンド、メッセージファイルとの結合 */
		msg_no = exec_csg_fsg();
		break;
	case LOC_CSG_COL:	/* 画面の色指定 */
		msg_no = exec_csg_col();
		break;
	case LOC_CSG_LRM:	/* ﾛｰｶﾙｺﾏﾝﾄﾞ応答ﾒｯｾｰｼﾞの出力機器環境の設定 */
		msg_no = exec_csg_lrm();
		break;
	case LOC_CSG_OPT:	/* オプション設定 */
		msg_no = exec_csg_opt();
		break;
	case LOC_SSG_CON:	/* ｺﾈｸｼｮﾝ番号によりｼｽﾃﾑＳＧﾌｧｲﾙを選択する */
		msg_no = exec_ssg_con();
		break;
	case LOC_SSG_NSG:	/* システムＳＧファイルを作成､変更する */
		msg_no = exec_ssg_nsg();
		break;
	case LOC_SSG_SYC:	/* システム定数 */
		msg_no = exec_ssg_syc();
		break;
	case LOC_SSG_ANM:	/* 自立メッセージの出力機器環境の設定 */
		msg_no = exec_ssg_anm();
		break;
	case LOC_SSG_CRM:	/* ｺﾏﾝﾄﾞ応答メッセージの出力機器環境の設定 */
		msg_no = exec_ssg_crm();
		break;
	case LOC_SSG_CMF:	/* コマンドファイル読みだしパス名 */
		msg_no = exec_ssg_cmf();
		break;
	case LOC_SSG_AEC:	/* 定時起動コマンドの設定 */
		msg_no = exec_ssg_aec();
		break;
	case LOC_DSP_CSG:	/* ＣＳＧ関係の表示を行う */
		msg_no = exec_dsp_csg();
		break;
	case LOC_DSP_SSG:	/* ＳＳＧ関係の表示を行う */
		msg_no = exec_dsp_ssg();
		break;
	case LOC_CSC_CCQ:	/* コマンドコネクションを切断する */
		msg_no = exec_csc_ccq();
		break;
	case LOC_CSC_DOS:	/* ＤＯＳに制御を渡す */
		msg_no = exec_csc_dos();
		break;
	case LOC_CSC_END:	/* システムを終了する */
		msg_no = exec_csc_end();
		break;
	case LOC_UTL_CPY:	/* ファイル単位のコピーを行う */
		msg_no = exec_utl_cpy();
		break;
	case LOC_UTL_CFG:	/* コマンドファイルを作成する */
		msg_no = exec_utl_cfg();
		break;
	case LOC_UTL_CFD:	/* コマンドファイルを表示／修正／削除する */
		msg_no = exec_utl_cfd();
		break;
	case LOC_UTL_MTR:	/* ﾒｯｾｰｼﾞﾌｧｲﾙを日本語変換し出力する */
		msg_no = exec_utl_mtr();
		break;
	default:
		msg_no = LOC_MSG_ERPRM;
		break;
	}

	/* 終了メッセージ表示 */
	dsp_loc_msg(msg_no, cmd_name, prm_name);

	return(msg_no == LOC_MSG_NORMAL ? 0 : 1);
}

/*=======================================================================
 |
 |		ＣＳＧ　ＮＳＧコマンド実行処理
 |
 |	int	exec_csg_nsg()
 |
 =======================================================================*/
static	int	exec_csg_nsg()
{
	char	*p;			/* パラメータポインタ */
	char	sys_name[9];		/* システム名 */
	char	grf_file[SZ_PATH];	/* 絵画面ファイル名 */
	char	online_id;		/* オンラインＩＤ */
	char	offline_id;		/* オフラインＩＤ */

	/* オンラインチェック */
	if (online)
		return(LOC_MSG_ERCON);

	/* 全体のシステム名取り出し */
	p = getprm();
	if (strlen(p) > 8)
		return(LOC_MSG_ERLEN);
	strcpy(sys_name, p);

	/* 絵画面ファイル名取り出し */
	p = getprm();
	if (strlen(p) > SZ_PATH)
		return(LOC_MSG_ERLEN);
	strcpy(grf_file, p);

	/* コマンドＩＤ（オンライン）取り出し */
	p = getprm();
	if (strlen(p) > 1)
		return(LOC_MSG_ERLEN);
	if (*p != '\\' && *p != '$' && *p != '#' && *p != '%')
		return(LOC_MSG_ERPRM);
	online_id =  *p;

	/* コマンドＩＤ（オフライン）取り出し */
	p = getprm();
	if (strlen(p) > 1)
		return(LOC_MSG_ERLEN);
	if (*p != '\\' && *p != '$' && *p != '#' && *p != '%')
		return(LOC_MSG_ERPRM);
	offline_id =  *p;

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* コンソールＳＧデータセット */
	strcpy(csg.nsg.sys_name, sys_name);
	strcpy(csg.nsg.grf_file, grf_file);
	csg.nsg.online_id = online_id;
	csg.nsg.offline_id = offline_id;

	/* コンソールＳＧファイル名設定 */
	sprintf(csg_file, "%s.CSG", sys_name);

	/* コンソールＳＧファイルに書き込み */
	write_csg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＣＳＧ　ＦＳＧコマンド実行処理
 |
 |	int	exec_csg_fsg()
 |
 =======================================================================*/
static	int	exec_csg_fsg()
{
	char	*p;			/* パラメータポインタ */
	int	i;			/* ループカウンタ */
	char	amsg_file[13];		/* 自立メッセージファイル名 */
	char	cms_file[5][13];	/* コマンド選択ファイル名 */
	char	cmsg_file[13];		/* コマンド応答メッセージファイル名 */
	char	loc_file[13];		/* ローカルコマンド選択ファイル名 */
	char	lmsg_file[13];		/* ﾛｰｶﾙｺﾏﾝﾄﾞ応答メッセージファイル名 */

	/* オンラインチェック */
	if (online)
		return(LOC_MSG_ERCON);

	/* 自立メッセージファイル名取り出し */
	p = getprm();
	if (strlen(p) > 12)
		return(LOC_MSG_ERLEN);
	strcpy(amsg_file, p);

	/* コマンド選択ファイル名取り出し */
	for (i = 0; i < 5; i++) {
		p = getprm();
		if (strlen(p) > 12)
			return(LOC_MSG_ERLEN);
		strcpy(cms_file[i], p);
	}

	/* コマンド応答メッセージファイル名取り出し */
	p = getprm();
	if (strlen(p) > 12)
		return(LOC_MSG_ERLEN);
	strcpy(cmsg_file, p);

	/* ローカルコマンド選択ファイル名取り出し */
	p = getprm();
	if (strlen(p) > 12)
		return(LOC_MSG_ERLEN);
	strcpy(loc_file, p);

	/* ﾛｰｶﾙｺﾏﾝﾄﾞ応答メッセージファイル名取り出し */
	p = getprm();
	if (strlen(p) > 12)
		return(LOC_MSG_ERLEN);
	strcpy(lmsg_file, p);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* コンソールＳＧデータセット */
	strcpy(csg.fsg.amsg_file, amsg_file);
	for (i = 0; i < 5; i++)
		strcpy(csg.fsg.cms_file[i], cms_file[i]);
	strcpy(csg.fsg.cmsg_file, cmsg_file);
	strcpy(csg.fsg.loc_file, loc_file);
	strcpy(csg.fsg.lmsg_file, lmsg_file);

	/* コンソールＳＧファイルに書き込み */
	write_csg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＣＳＧ　ＣＯＬコマンド実行処理
 |
 |	int	exec_csg_col()
 |
 =======================================================================*/
static	int	exec_csg_col()
{
	char	*p;		/* パラメータポインタ */
	int	i;		/* ループカウンタ */
	int	col[9];		/* 表示色 */

	/* 画面の色取り出し */
	for (i = 0; i < 9; i++) {
		p = getprm();
		if ((col[i] = getsel(p, csg_col)) == -1)
			return(LOC_MSG_ERPRM);
	}

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* コンソールＳＧデータセット */
	memcpy(&csg.col, col, sizeof(csg.col));

	/* コンソールＳＧファイルに書き込み */
	write_csg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＣＳＧ　ＬＲＭコマンド実行処理
 |
 |	int	exec_csg_lrm()
 |
 =======================================================================*/
static	int	exec_csg_lrm()
{
	char	*p;		/* パラメータポインタ */
	int	dsp;		/* 画面表示有り／無し */
	int	prn;		/* プリンタ出力有り／無し */
	int	fil;		/* ファイル出力有り／無し */
	char	fname[SZ_PATH];	/* ファイル出力有り時のファイル名 */

	/* 画面表示有り／無し取り出し */
	p = getprm();
	if ((dsp = getsel(p, csg_lrm1)) == -1)
		return(LOC_MSG_ERPRM);

	/* プリンタ出力有り／無し取り出し */
	p = getprm();
	if ((prn = getsel(p, csg_lrm2)) == -1)
		return(LOC_MSG_ERPRM);

	/* ファイル出力有り／無し取り出し */
	p = getprm();
	if ((fil = getsel(p, csg_lrm3)) == -1)
		return(LOC_MSG_ERPRM);

	/* ファイル出力有り時のファイル名取り出し */
	p = getprm();
	if (strlen(p) > SZ_PATH)
		return(LOC_MSG_ERLEN);
	strcpy(fname, p);

	/* ファイル名入力チェック */
	if (fil != 0) {
		if (*p == '\0')
			return(LOC_MSG_ERFILE);
	}

	/* 拡張子チェック */
	if (*p != '\0') {
		if ((p = strchr(p, '.')) == NULL || strcmp(p, ".MSL") != 0)
			return(LOC_MSG_EREXT);
	}

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* コンソールＳＧデータセット */
	csg.lrm.dsp = dsp;
	csg.lrm.prn = prn;
	csg.lrm.fil = fil;
	strcpy(csg.lrm.fname, fname);

	/* コンソールＳＧファイルに書き込み */
	write_csg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＣＳＧ　ＯＰＴコマンド実行処理
 |
 |	int	exec_csg_opt()
 |
 =======================================================================*/
static	int	exec_csg_opt()
{
	char	*p;		/* パラメータポインタ */
	int	rs232c;		/* ＲＳ２３２Ｃ回線速度 */
	int	printer;	/* プリンター機種 */
	int	cmd_to;		/* コマンド入力タイムアウト時間 */
	int	cmd_err;	/* コマンドファイル実行エラー処置 */
	int	comment;	/* コメントの自動挿入 */

	/* ＲＳ２３２Ｃ回線速度取り出し */
	p = getprm();
	if ((rs232c = getsel(p, csg_opt1)) == -1)
		return(LOC_MSG_ERPRM);

	/* プリンター機種取り出し */
	p = getprm();
	if ((printer = getsel(p, csg_opt2)) == -1)
		return(LOC_MSG_ERPRM);

	/* コマンド入力タイムアウト時間取り出し */
	p = getprm();
	cmd_to = atoi(p);

	/* コマンドファイル実行エラー処置取り出し */
	p = getprm();
	if ((cmd_err = getsel(p, csg_opt3)) == -1)
		return(LOC_MSG_ERPRM);

	/* コメントの自動挿入取り出し */
	p = getprm();
	if ((comment = getsel(p, csg_opt4)) == -1)
		return(LOC_MSG_ERPRM);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* コンソールＳＧデータセット */
	csg.opt.rs232c = rs232c;
	csg.opt.printer = printer;
	csg.opt.cmd_to = cmd_to;
	csg.opt.cmd_err = cmd_err;
	csg.opt.comment = comment;

	/* コンソールＳＧファイルに書き込み */
	write_csg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＳＳＧ　ＣＯＮコマンド実行処理
 |
 |	int	exec_ssg_con()
 |
 =======================================================================*/
static	int	exec_ssg_con()
{
	char	*p;		/* パラメータポインタ */
	int	conxxxtion;	/* コネクション番号 */

	/* オンラインチェック */
	if (online)
		return(LOC_MSG_ERCON);

	/* コネクション番号取り出し */
	p = getprm();
	if (*p == '\0' || (conxxxtion = atoi(p)) > 63)
		return(LOC_MSG_ERPRM);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* システムＳＧファイル設定 */
	if (find_ssg(conxxxtion) == 0)
		read_ssg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＳＳＧ　ＮＳＧコマンド実行処理
 |
 |	int	exec_ssg_nsg()
 |
 =======================================================================*/
static	int	exec_ssg_nsg()
{
	char	*p;			/* パラメータポインタ */
	char	sys_name[7];		/* 個別システム名 */
	char	grf_file[SZ_PATH];	/* 絵画面ファイル名 */
	int	conxxxtion;		/* コネクション番号 */
	int	kanshi;			/* 無活動監視周期 */

	/* オンラインチェック */
	if (online)
		return(LOC_MSG_ERCON);

	/* 個別システム名取り出し */
	p = getprm();
	if (strlen(p) > 6)
		return(LOC_MSG_ERLEN);
	strcpy(sys_name, p);

	/* 絵画面ファイル名取り出し */
	p = getprm();
	if (strlen(p) > SZ_PATH)
		return(LOC_MSG_ERLEN);
	strcpy(grf_file, p);

	/* コネクション番号取り出し */
	p = getprm();
	if ((conxxxtion = atoi(p)) > 63)
		return(LOC_MSG_ERPRM);

	/* 無活動監視周期取り出し */
	p = getprm();
	kanshi = atoi(p);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* システムＳＧデータセット */
	strcpy(ssg.nsg.sys_name, sys_name);
	strcpy(ssg.nsg.grf_file, grf_file);
	ssg.nsg.conxxxtion = conxxxtion;
	ssg.nsg.kanshi = kanshi;

	/* システムＳＧファイル名設定 */
	sprintf(ssg_file, "%s%02d.SSG", sys_name, conxxxtion);

	/* システムＳＧファイルに書き込み */
	write_ssg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＳＳＧ　ＳＹＣコマンド実行処理
 |
 |	int	exec_ssg_syc()
 |
 =======================================================================*/
static	int	exec_ssg_syc()
{
	char	*p;			/* パラメータポインタ */
	int	i;			/* ループカウンタ */
	struct	{
		int	syc_flg;	/* システム定数指定フラグ */
		int	syc_val;	/* システム定数 */
	} syc[10];

	/* システム定数取り出し */
	for (i = 0; i < 10; i++) {
		p = getprm();
		if (*p != '\0') {
			syc[i].syc_flg = 1;
			syc[i].syc_val = atoi(p);
		} else
			syc[i].syc_flg = 0;
	}

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* システムＳＧデータセット */
	memcpy(ssg.syc, syc, sizeof(syc));

	/* システムＳＧファイルに書き込み */
	write_ssg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＳＳＧ　ＡＮＭコマンド実行処理
 |
 |	int	exec_ssg_anm()
 |
 =======================================================================*/
static	int	exec_ssg_anm()
{
	char	*p;		/* パラメータポインタ */
	int	dsp;		/* 画面表示有り／無し */
	int	prn;		/* プリンタ出力有り／無し */
	int	fil;		/* ファイル出力有り／無し */
	char	fname[SZ_PATH];	/* ファイル出力有り時のファイル名 */

	/* 画面表示有り／無し取り出し */
	p = getprm();
	if ((dsp = getsel(p, ssg_anm1)) == -1)
		return(LOC_MSG_ERPRM);

	/* プリンタ出力有り／無し取り出し */
	p = getprm();
	if ((prn = getsel(p, ssg_anm2)) == -1)
		return(LOC_MSG_ERPRM);

	/* ファイル出力有り／無し取り出し */
	p = getprm();
	if ((fil = getsel(p, ssg_anm3)) == -1)
		return(LOC_MSG_ERPRM);

	/* ファイル出力有り時のファイル名取り出し */
	p = getprm();
	if (strlen(p) > SZ_PATH)
		return(LOC_MSG_ERPRM);
	strcpy(fname, p);

	/* ファイル名入力チェック */
	if (fil != 0) {
		if (*p == '\0')
			return(LOC_MSG_ERFILE);
	}

	/* 拡張子チェック */
	if (*p != '\0') {
		if ((p = strchr(p, '.')) == NULL)
			return(LOC_MSG_EREXT);
		if (fil == 1) {
			if (strcmp(p, ".MSL") != 0)
				return(LOC_MSG_EREXT);
		} else if (fil == 2) {
			if (strcmp(p, ".MSC") != 0)
				return(LOC_MSG_EREXT);
		}
	}

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* システムＳＧデータセット */
	ssg.anm.dsp = dsp;
	ssg.anm.prn = prn;
	ssg.anm.fil = fil;
	strcpy(ssg.anm.fname, fname);

	/* システムＳＧファイルに書き込み */
	write_ssg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＳＳＧ　ＣＲＭコマンド実行処理
 |
 |	int	exec_ssg_crm()
 |
 =======================================================================*/
static	int	exec_ssg_crm()
{
	char	*p;		/* パラメータポインタ */
	int	dsp;		/* 画面表示有り／無し */
	int	prn;		/* プリンタ出力有り／無し */
	int	fil;		/* ファイル出力有り／無し */
	char	fname[SZ_PATH];	/* ファイル出力有り時のファイル名 */

	/* 画面表示有り／無し取り出し */
	p = getprm();
	if ((dsp = getsel(p, ssg_crm1)) == -1)
		return(LOC_MSG_ERPRM);

	/* プリンタ出力有り／無し取り出し */
	p = getprm();
	if ((prn = getsel(p, ssg_crm2)) == -1)
		return(LOC_MSG_ERPRM);

	/* ファイル出力有り／無し取り出し */
	p = getprm();
	if ((fil = getsel(p, ssg_crm3)) == -1)
		return(LOC_MSG_ERPRM);

	/* ファイル出力有り時のファイル名取り出し */
	p = getprm();
	if (strlen(p) > SZ_PATH)
		return(LOC_MSG_ERLEN);
	strcpy(fname, p);

	/* ファイル名入力チェック */
	if (fil != 0) {
		if (*p == '\0')
			return(LOC_MSG_ERFILE);
	}

	/* 拡張子チェック */
	if (*p != '\0') {
		if ((p = strchr(p, '.')) == NULL)
			return(LOC_MSG_EREXT);
		if (fil == 1) {
			if (strcmp(p, ".MSL") != 0)
				return(LOC_MSG_EREXT);
		} else if (fil == 2) {
			if (strcmp(p, ".MSC") != 0)
				return(LOC_MSG_EREXT);
		}
	}

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* システムＳＧデータセット */
	ssg.crm.dsp = dsp;
	ssg.crm.prn = prn;
	ssg.crm.fil = fil;
	strcpy(ssg.crm.fname, fname);

	/* システムＳＧファイルに書き込み */
	write_ssg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＳＳＧ　ＣＭＦコマンド実行処理
 |
 |	int	exec_ssg_cmf()
 |
 =======================================================================*/
static	int	exec_ssg_cmf()
{
	char	*p;			/* パラメータポインタ */
	char	cmd_path[SZ_PATH];	/* コマンドファイル読みだしパス */

	/* コマンドファイル読みだしパス取り出し */
	p = getprm();
	if (strlen(p) > SZ_PATH)
		return(LOC_MSG_ERLEN);
	strcpy(cmd_path, p);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* システムＳＧデータセット */
	strcpy(ssg.cmf.cmd_path, cmd_path);

	/* システムＳＧファイルに書き込み */
	write_ssg();

	/* コマンドファイル検索 */
	find_cmf();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＳＳＧ　ＡＥＣコマンド実行処理
 |
 |	int	exec_ssg_aec()
 |
 =======================================================================*/
static	int	exec_ssg_aec()
{
	char	*p;		/* パラメータポインタ */
	int	i;		/* ループカウンタ */
	char	aec[5][80];	/* 定時起動コマンド */

	for (i = 0; i < 5; i++) {
		p = getprm();
		strcpy(aec[i],p);
	}

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* システムＳＧデータセット */
	for (i = 0; i < 5; i++)
		strcpy(ssg.aec[i].command, aec[i]);

	/* システムＳＧファイルに書き込み */
	write_ssg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＣＳＧコマンド実行処理
 |
 |	int	exec_dsp_csg()
 |
 =======================================================================*/
static	int	exec_dsp_csg()
{
	int	i;		/* ループカウンタ */
	CMD_TBL	*cmdp;		/* コマンドテーブルポインタ */
	PRM_TBL	*prmp;		/* パラメータ種類テーブルポインタ */

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* コマンドテーブル検索 */
	for (cmdp = loctbl->ptr, i = 0; i < loctbl->n; i++, cmdp++) {
		if (strcmp(cmdp->name, "CSG") == 0)
			break;
	}
	if (i == loctbl->n)
		return(LOC_MSG_NORMAL);

	/* 現設定値表示 */
	for (prmp = cmdp->ptr, i = 0; i < cmdp->n; i++, prmp++) {
		if (strcmp(prmp->name, "NSG") == 0)
			exec_dsp_csg_nsg(prmp->ptr);
		else if (strcmp(prmp->name, "FSG") == 0)
			exec_dsp_csg_fsg(prmp->ptr);
		else if (strcmp(prmp->name, "COL") == 0)
			exec_dsp_csg_col(prmp->ptr);
		else if (strcmp(prmp->name, "LRM") == 0)
			exec_dsp_csg_lrm(prmp->ptr);
		else if (strcmp(prmp->name, "OPT") == 0)
			exec_dsp_csg_opt(prmp->ptr);
	}

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＣＳＧ，ＮＳＧコマンド実行処理
 |
 |	void	exec_dsp_csg_nsg(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_csg_nsg(PRT_TBL *ptr)
{
	char	id[2];		/* コマンドＩＤ */

	enq_msg(MSG_CNS, "--- NSG ----------");
	dspnam(ptr++, csg.nsg.sys_name);
	dspnam(ptr++, csg.nsg.grf_file);
	id[0] = csg.nsg.online_id;
	id[1] = '\0';
	dspnam(ptr++, id);
	id[0] = csg.nsg.offline_id;
	dspnam(ptr, id);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＣＳＧ，ＦＳＧコマンド実行処理
 |
 |	void	exec_dsp_csg_fsg(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_csg_fsg(PRT_TBL *ptr)
{
	int	i;		/* ループカウンタ */

	enq_msg(MSG_CNS, "--- FSG ----------");
	dspnam(ptr++, csg.fsg.amsg_file);
	for (i= 0; i < 5; i++)
		dspnam(ptr++, csg.fsg.cms_file[i]);
	dspnam(ptr++, csg.fsg.cmsg_file);
	dspnam(ptr++, csg.fsg.loc_file);
	dspnam(ptr++, csg.fsg.lmsg_file);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＣＳＧ，ＣＯＬコマンド実行処理
 |
 |	void	exec_dsp_csg_col(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_csg_col(PRT_TBL *ptr)
{
	enq_msg(MSG_CNS, "--- COL ----------");
	dspsel(ptr++, csg_col[csg.col.c_waku]);
	dspsel(ptr++, csg_col[csg.col.c_offline]);
	dspsel(ptr++, csg_col[csg.col.c_online]);
	dspsel(ptr++, csg_col[csg.col.c_guide]);
	dspsel(ptr++, csg_col[csg.col.c_cnsmsg]);
	dspsel(ptr++, csg_col[csg.col.c_crm]);
	dspsel(ptr++, csg_col[csg.col.c_msga]);
	dspsel(ptr++, csg_col[csg.col.c_msgb]);
	dspsel(ptr, csg_col[csg.col.c_err]);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＣＳＧ，ＬＲＭコマンド実行処理
 |
 |	void	exec_dsp_csg_lrm(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_csg_lrm(PRT_TBL *ptr)
{
	enq_msg(MSG_CNS, "--- LRM ----------");
	dspsel(ptr++, csg_lrm1[csg.lrm.dsp]);
	dspsel(ptr++, csg_lrm2[csg.lrm.prn]);
	dspsel(ptr++, csg_lrm3[csg.lrm.fil]);
	dspnam(ptr, csg.lrm.fname);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＣＳＧ，ＯＰＴコマンド実行処理
 |
 |	void	exec_dsp_csg_opt(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_csg_opt(PRT_TBL *ptr)
{
	enq_msg(MSG_CNS, "--- OPT ----------");
	dspsel(ptr++, csg_opt1[csg.opt.rs232c]);
	dspsel(ptr++, csg_opt2[csg.opt.printer]);
	dspnum(ptr++, csg.opt.cmd_to);
	dspsel(ptr++, csg_opt3[csg.opt.cmd_err]);
	dspsel(ptr, csg_opt4[csg.opt.comment]);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＳＳＧコマンド実行処理
 |
 |	int	exec_dsp_ssg(ptr)
 |
 =======================================================================*/
static	int	exec_dsp_ssg()
{
	int	i;
	CMD_TBL	*cmdp;
	PRM_TBL	*prmp;

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* コマンドテーブル検索 */
	for (cmdp = loctbl->ptr, i = 0; i < loctbl->n; i++, cmdp++) {
		if (strcmp(cmdp->name, "SSG") == 0)
			break;
	}
	if (i == loctbl->n)
		return(LOC_MSG_NORMAL);

	/* 現設定値表示 */
	for (prmp = cmdp->ptr, i = 0; i < cmdp->n; i++, prmp++) {
		if (strcmp(prmp->name, "NSG") == 0)
			exec_dsp_ssg_nsg(prmp->ptr);
		else if (strcmp(prmp->name, "SYC") == 0)
			exec_dsp_ssg_syc(prmp->ptr);
		else if (strcmp(prmp->name, "ANM") == 0)
			exec_dsp_ssg_anm(prmp->ptr);
		else if (strcmp(prmp->name, "CRM") == 0)
			exec_dsp_ssg_crm(prmp->ptr);
		else if (strcmp(prmp->name, "CMF") == 0)
			exec_dsp_ssg_cmf(prmp->ptr);
		else if (strcmp(prmp->name, "AEC") == 0)
			exec_dsp_ssg_aec(prmp->ptr);
	}

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＳＳＧ，ＮＳＧコマンド実行処理
 |
 |	void	exec_dsp_ssg_nsg(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_ssg_nsg(PRT_TBL *ptr)
{
	enq_msg(MSG_CNS, "--- NSG ----------");
	dspnam(ptr++, ssg.nsg.sys_name);
	dspnam(ptr++, ssg.nsg.grf_file);
	dspnum(ptr++, ssg.nsg.conxxxtion);
	if (ssg.nsg.kanshi != 0)
		dspnum(ptr, ssg.nsg.kanshi);
	else
		dspnam(ptr, "");
}

/*=======================================================================
 |
 |		ＤＳＰ　ＳＳＧ，ＳＹＣコマンド実行処理
 |
 |	void	exec_dsp_ssg_syc(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_ssg_syc(PRT_TBL *ptr)
{
	int	i;		/* ループカウンタ */

	enq_msg(MSG_CNS, "--- SYC ----------");
	for (i = 0; i < 10; i++) {
		if (ssg.syc[i].syc_flg)
			dspnum(ptr++, ssg.syc[i].syc_val);
		else
			dspnam(ptr++, "");
	}
}

/*=======================================================================
 |
 |		ＤＳＰ　ＳＳＧ，ＡＮＭコマンド実行処理
 |
 |	void	exec_dsp_ssg_anm(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_ssg_anm(PRT_TBL *ptr)
{
	enq_msg(MSG_CNS, "--- ANM ----------");
	dspsel(ptr++, ssg_anm1[ssg.anm.dsp]);
	dspsel(ptr++, ssg_anm2[ssg.anm.prn]);
	dspsel(ptr++, ssg_anm3[ssg.anm.fil]);
	dspnam(ptr++, ssg.anm.fname);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＳＳＧ，ＣＲＭコマンド実行処理
 |
 |	void	exec_dsp_ssg_crm(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_ssg_crm(PRT_TBL *ptr)
{
	enq_msg(MSG_CNS, "--- CRM ----------");
	dspsel(ptr++, ssg_crm1[ssg.crm.dsp]);
	dspsel(ptr++, ssg_crm2[ssg.crm.prn]);
	dspsel(ptr++, ssg_crm3[ssg.crm.fil]);
	dspnam(ptr, ssg.crm.fname);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＳＳＧ，ＣＭＦコマンド実行処理
 |
 |	void	exec_dsp_ssg_cmf(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_ssg_cmf(PRT_TBL *ptr)
{
	enq_msg(MSG_CNS, "--- CMF ----------");
	dspnam(ptr, ssg.cmf.cmd_path);
}

/*=======================================================================
 |
 |		ＤＳＰ　ＳＳＧ，ＡＥＣコマンド実行処理
 |
 |	void	exec_dsp_ssg_aec(ptr)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |
 =======================================================================*/
static	void	exec_dsp_ssg_aec(PRT_TBL *ptr)
{
	int	i;		/* ループカウンタ */

	enq_msg(MSG_CNS, "--- AEC ----------");
	for (i = 0; i < 5; i++)
		dspnam(ptr++, ssg.aec[i].command);
}

/*=======================================================================
 |
 |		ＤＳＰコマンド選択項目表示処理
 |
 |	void	dspsel(ptr, sel)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |		char	*sel;		選択項目
 |
 =======================================================================*/
static	void	dspsel(PRT_TBL *ptr, char *sel)
{
	int	i;		/* ループカウンタ */
	SEL_TBL	*seltp;		/* 選択項目テーブルポインタ */

	seltp = ptr->ptr.sel;
	for (i = 0; i < seltp->n; i++) {
		if (strcmp(sel, seltp->ptr[i][1]) == 0)
			break;
	}

	dspnam(ptr, i != seltp->n ? seltp->ptr[i][0] : "");
}

/*=======================================================================
 |
 |		ＤＳＰコマンド数値項目表示処理
 |
 |	void	dspnum(ptr, num)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |		int	num;		数値項目
 |
 =======================================================================*/
static	void	dspnum(PRT_TBL *ptr, int num)
{
	char	buf[80];	/* 数値編集バッファ */

	sprintf(buf, "%u", num);
	dspnam(ptr, buf);
}

/*=======================================================================
 |
 |		ＤＳＰコマンド名称項目表示処理
 |
 |	void	dspnam(ptr, nam)
 |
 |		PRT_TBL	*ptr;		パラメータテーブルポインタ
 |		char	*nam;		名称項目
 |
 =======================================================================*/
static	void	dspnam(PRT_TBL *ptr, char *nam)
{
	char	buf[80];	/* 編集バッファ */
	char	*bp;		/* 編集バッファポインタ */
	int	len;		/* 説明文の長さ */

	bp = buf;		/* バッファポインタセット */

	*bp++ = ' ';
	*bp++ = ' ';

	/* 説明文セット */
	strcpy(bp, ptr->guide);
	len = strlen(bp);
	bp += len;

	/* スペースセット */
	while (len++ < 25)
		*bp++ = ' ';

	*bp++ = '[';
	*bp++ = ' ';

	/* データセット */
	strcpy(bp, nam);
	bp += strlen(bp);

	*bp++ = ' ';
	*bp++ = ']';
	*bp = '\0';

	enq_msg(MSG_CNS, buf);	/* メッセージ表示 */
}

/*=======================================================================
 |
 |		ＣＳＣ　ＤＯＳコマンド実行処理
 |
 |	int	exec_csc_dos()
 |
 =======================================================================*/
static	int	exec_csc_dos()
{
	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	save_scr();		/* 受信画面セーブ */

	final();		/* 終了処理 */
	spawnlp(0, "COMMAND", "COMMAND", 0L);	/* COMMAND.COM 実行 */
	initialize();		/* 初期化処理 */

	restore_scr();		/* 受信画面リストア */

	if (online)
		rs_open();	/* ＲＳ－２３２Ｃオープン */

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＣＳＣ　ＣＣＱコマンド実行処理
 |
 |	int	exec_csc_ccq()
 |
 =======================================================================*/
static	int	exec_csc_ccq()
{
	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	if (online)
		disconxxxt();		/* コネクション切断処理 */

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＣＳＣ　ＥＮＤコマンド実行処理
 |
 |	int	exec_csc_end()
 |
 =======================================================================*/
static	int	exec_csc_end()
{
	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	prog = 0;

	/* システム終了メッセージ出力 */
	dsp_loc_msg(LOC_MSG_END, csg.nsg.sys_name);
	out_msg();

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＵＴＬ　ＣＰＹコマンド実行処理
 |
 |	int	exec_utl_cpy()
 |
 =======================================================================*/
static	int	exec_utl_cpy()
{
	char	*p;			/* パラメータポインタ */
	char	src_file[128];		/* コピー元ファイル名 */
	char	dst_file[128];		/* コピー先ファイル名 */
	char	cmd[128];		/* コピーコマンド編集バッファ */

	/* コピー対象ファイル名取り出し */
	p = getprm();
	if (*p == '\0')
		return(LOC_MSG_ERFILE);
	strcpy(src_file, p);

	/* コピー先パス名ｏｒファイル名取り出し */
	p = getprm();
	strcpy(dst_file, p);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* COPY コマンド編集 */
	sprintf(cmd, "COPY %s %s", src_file, dst_file);

	save_scr();		/* 受信画面セーブ */
	final();		/* 終了処理 */
	system(cmd);		/* COPY コマンド実行 */
	printf("\nどれかキーを押して下さい。");
	getch();
	initialize();		/* 初期化処理 */
	restore_scr();		/* 受信画面リストア */
	if (online)
		rs_open();	/* ＲＳ－２３２Ｃオープン */

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＵＴＬ　ＣＦＧコマンド実行処理
 |
 |	int	exec_utl_cfg()
 |
 =======================================================================*/
static	int	exec_utl_cfg()
{
	char	*p;		/* パラメータポインタ */

	/* コマンドファイル名取り出し */
	p = getprm();
	if (*p == '\0')
		return(LOC_MSG_ERFILE);
	strcpy(cmf_name, p);

	/* 機能概要取り出し */
	p = getprm();
	strcpy(cmf_guide, p);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	/* コマンドファイル作成中絵画面作成 */
	memset(cfg_grf, 0, sizeof(cfg_grf));
	grfputs(4, 3, "コマンドﾌｧｲﾙ", C_BLUE, cfg_grf);
	grfputs(4, 6, cmf_name, C_YELLOW, cfg_grf);
	grfputs(20, 10, "作成中", C_BLUE, cfg_grf);

	save_scr();	/* 現在の受信画面セーブ */
	mkcmd = 1;	/* コマンドファイル作成中フラグセット */
	exec_prog(2);	/* 各処理起動 */
	mkcmd = 0;	/* コマンド作成中フラグリセット */
	local = 1;	/* ローカルフラグセット */
	find_cmf();	/* コマンドファイル検索 */
	restore_scr();	/* 受信画面リストア */

	prog = 7;

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＵＴＬ　ＣＦＤコマンド実行処理
 |
 |	int	exec_utl_cfd()
 |
 =======================================================================*/
static	int	exec_utl_cfd()
{
	char	*p;		/* パラメータポインタ */
	int	ps;		/* 処理選択 */

	/* 処理選択取り出し */
	p = getprm();
	if ((ps = getsel(p, utl_cfd)) == -1)
		return(LOC_MSG_ERPRM);

	/* コマンドファイル名取り出し */
	p = getprm();
	if (*p == '\0')
		return(LOC_MSG_ERFILE);
	strcpy(cmf_name, p);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);

	if (access(cmf_name, 0) != 0)
		return(LOC_MSG_ERNOFILE);

	switch (ps) {
	case 0:		/* 表示 */
		read_cmf();	/* コマンドファイル読み込み */
		save_scr();	/* 現在の受信画面セーブ */
		prog6c();	/* コマンドファイル内容表示画面処理 */
		restore_scr();	/* 受信画面リストア */
		break;
	case 1:		/* 修正 */
		/* コマンドファイル修正中絵画面作成 */
		memset(cfg_grf, 0, sizeof(cfg_grf));
		grfputs(4, 3, "コマンドﾌｧｲﾙ", C_BLUE, cfg_grf);
		grfputs(4, 6, cmf_name, C_YELLOW, cfg_grf);
		grfputs(20, 10, "修正中", C_BLUE, cfg_grf);

		read_cmf();	/* コマンドファイル読み込み */
		save_scr();	/* 現在の受信画面セーブ */
		mkcmd = 2;	/* コマンドファイル作成中フラグセット */
		exec_prog(6);	/* 各処理起動 */
		mkcmd = 0;	/* コマンド作成中フラグリセット */
		local = 1;	/* ローカルフラグセット */
		find_cmf();	/* コマンドファイル検索 */
		restore_scr();	/* 受信画面リストア */
		prog = 7;
		break;
	case 2:		/* 削除 */
		remove(cmf_name);	/* コマンドファイル削除 */
		find_cmf();		/* コマンドファイル検索 */
		break;
	}

	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		ＵＴＬ　ＭＴＲコマンド実行処理
 |
 |	int	exec_utl_mtr()
 |
 =======================================================================*/
static	int	exec_utl_mtr()
{
	char	*p;			/* パラメータポインタ */
	char	ifname[SZ_PATH];	/* 変換前ファイル名 */
	int	kind;			/* メッセージ種類 */
	int	dsp;			/* 画面表示有り／無し */
	int	prn;			/* プリンタ出力有り／無し */
	int	fil;			/* ファイル出力有り／無し */
	char	ofname[SZ_PATH];	/* 変換後ファイル名 */
	FILE	*ifp, *ofp;		/* ファイルポインタ */
	char	buf[512];		/* ファイル読み込みバッファ */
	char	*msg;			/* 変換後メッセージポインタ */
	int	class;			/* 自立メッセージクラス */

	/* メッセージ種類取り出し */
	p = getprm();
	if ((kind = getsel(p, utl_mtr1)) == -1)
		return(LOC_MSG_ERPRM);

	/* 変換前ファイル名取り出し */
	p = getprm();
	if (strlen(p) > SZ_PATH)
		return(LOC_MSG_ERPRM);
	strcpy(ifname, p);

	/* ファイル名入力チェック */
	if (*p == '\0')
		return(LOC_MSG_ERFILE);

	/* 画面表示有り／無し取り出し */
	p = getprm();
	if ((dsp = getsel(p, utl_mtr2)) == -1)
		return(LOC_MSG_ERPRM);

	/* プリンタ出力有り／無し取り出し */
	p = getprm();
	if ((prn = getsel(p, utl_mtr3)) == -1)
		return(LOC_MSG_ERPRM);

	/* ファイル出力有り／無し取り出し */
	p = getprm();
	if ((fil = getsel(p, utl_mtr4)) == -1)
		return(LOC_MSG_ERPRM);

	/* 変換後ファイル名取り出し */
	p = getprm();
	if (strlen(p) > SZ_PATH)
		return(LOC_MSG_ERLEN);
	strcpy(ofname, p);

	/* ファイル名入力チェック */
	if (fil != 0) {
		if (*p == '\0')
			return(LOC_MSG_ERFILE);
	}

	/* 拡張子チェック */
	if (*p != '\0') {
		if ((p = strchr(p, '.')) == NULL || strcmp(p, ".MSL") != 0)
			return(LOC_MSG_EREXT);
	}

	/* 変換前ファイルオープン */
	if ((ifp = fopen(ifname, "r")) == NULL)
		return(LOC_MSG_ERNOFILE);

	/* 変換後ファイルオープン */
	if (fil != 0) {
		if ((ofp = fopen(ofname, "w")) == NULL)
			fil = 0;
	}

	out_msg();

	/* メッセージ出力 */
	while (fgets(buf, sizeof(buf), ifp) != NULL) {
		buf[strlen(buf) - 1] = '\0';
		msg = buf;
		if (strncmp(msg, "@MSG", 4) == 0)
			msg = cnv_msg(msg + 4, kind == 0 ? anm_msg : crm_msg, &class);
		if (dsp)
			dsp_msg(msg, C_CNSMSG);
		if (prn)
			lprintf("%s\x0d\x0a", msg);
		if (fil)
			fprintf(ofp, "%s\n", msg);
	}

	/* ファイルクローズ */
	fclose(ifp);
	if (fil != 0)
		fclose(ofp);

	/* パラメータ終了チェック */
	if (*cmbp != '/')
		return(LOC_MSG_ERPRM);


	return(LOC_MSG_NORMAL);
}

/*=======================================================================
 |
 |		選択項目取り出し処理
 |
 |	int	getsel(p, prm)
 |
 |		char	*p;		選択項目
 |		char	**prm;		選択項目テーブル
 |
 =======================================================================*/
static	int	getsel(char *p, char **prm)
{
	int	i;		/* ループカウンタ */

	/* 選択項目検索 */
	for (i = 0; prm[i] != NULL; i++) {
		if (strcmp(p, prm[i]) == 0)
			return(i);
	}
	return(-1);
}

/*=======================================================================
 |
 |		パラメータ取り出し処理
 |
 |	char	*getprm()
 |
 =======================================================================*/
static	char	*getprm()
{
	static	char	prmbuf[80];	/* パラメータ取り出しバッファ */
	int	i;		/* ループカウンタ */
	int	c;

	if (*cmbp == ',') {
		for (i = 0; i < sizeof(prmbuf); i++) {
			c = *++cmbp;
			if (c == ',' || c == '/' || c == '\0')
				break;
			prmbuf[i] = c;
		}
		prmbuf[i] = '\0';
	} else
		prmbuf[0] = '\0';

	return(prmbuf);
}

/*=======================================================================
 |
 |		ローカルコマンド編集処理
 |
 |	void	edit_loc_cmd()
 |
 =======================================================================*/
void	edit_loc_cmd()
{
	int	i;		/* ループカウンタ */

	cmbp = cmdbuf;
	switch (chk_loc_cmd()) {
	case LOC_CSG_NSG:
		setnam(csg.nsg.sys_name);
		setnam(csg.nsg.grf_file);
		*cmbp++ = ',';
		*cmbp++ = csg.nsg.online_id;
		*cmbp++ = ',';
		*cmbp++ = csg.nsg.offline_id;
		break;
	case LOC_CSG_FSG:
		setnam(csg.fsg.amsg_file);
		for (i= 0; i < 5; i++)
			setnam(csg.fsg.cms_file[i]);
		setnam(csg.fsg.cmsg_file);
		setnam(csg.fsg.loc_file);
		setnam(csg.fsg.lmsg_file);
		break;
	case LOC_CSG_COL:
		setsel(csg_col[csg.col.c_waku]);
		setsel(csg_col[csg.col.c_offline]);
		setsel(csg_col[csg.col.c_online]);
		setsel(csg_col[csg.col.c_guide]);
		setsel(csg_col[csg.col.c_cnsmsg]);
		setsel(csg_col[csg.col.c_crm]);
		setsel(csg_col[csg.col.c_msga]);
		setsel(csg_col[csg.col.c_msgb]);
		setsel(csg_col[csg.col.c_err]);
		break;
	case LOC_CSG_LRM:
		setsel(csg_lrm1[csg.lrm.dsp]);
		setsel(csg_lrm2[csg.lrm.prn]);
		setsel(csg_lrm3[csg.lrm.fil]);
		setnam(csg.lrm.fname);
		break;
	case LOC_CSG_OPT:
		setsel(csg_opt1[csg.opt.rs232c]);
		setsel(csg_opt2[csg.opt.printer]);
		setnum(csg.opt.cmd_to);
		setsel(csg_opt3[csg.opt.cmd_err]);
		setsel(csg_opt4[csg.opt.comment]);
		break;
	case LOC_SSG_NSG:
		setnam(ssg.nsg.sys_name);
		setnam(ssg.nsg.grf_file);
		setnum(ssg.nsg.conxxxtion);
		if (ssg.nsg.kanshi != 0)
			setnum(ssg.nsg.kanshi);
		else
			*cmbp++ = ',';
		break;
	case LOC_SSG_SYC:
		for (i = 0; i < 10; i++) {
			if (ssg.syc[i].syc_flg)
				setnum(ssg.syc[i].syc_val);
			else
				*cmbp++ = ',';
		}
		break;
	case LOC_SSG_ANM:
		setsel(ssg_anm1[ssg.anm.dsp]);
		setsel(ssg_anm2[ssg.anm.prn]);
		setsel(ssg_anm3[ssg.anm.fil]);
		setnam(ssg.anm.fname);
		break;
	case LOC_SSG_CRM:
		setsel(ssg_crm1[ssg.crm.dsp]);
		setsel(ssg_crm2[ssg.crm.prn]);
		setsel(ssg_crm3[ssg.crm.fil]);
		setnam(ssg.crm.fname);
		break;
	case LOC_SSG_CMF:
		setnam(ssg.cmf.cmd_path);
		break;
	case LOC_SSG_AEC:
		for (i = 0; i < 5; i++)
			setnam(ssg.aec[i].command);
		break;
	}
	*cmbp = '\0';
}

/*=======================================================================
 |
 |		選択項目セット処理
 |
 |	void	setsel(data)
 |
 |		char	*data;		選択項目
 |
 =======================================================================*/
static	void	setsel(char *data)
{
	*cmbp++ = ',';
	strcpy(cmbp, data);

	cmbp += strlen(cmbp);
}

/*=======================================================================
 |
 |		数値項目セット処理
 |
 |	void	setnum(data)
 |
 |		int	data;		数値項目
 |
 =======================================================================*/
static	void	setnum(int data)
{
	*cmbp++ = ',';
	sprintf(cmbp, "%u", data);

	cmbp += strlen(cmbp);
}

/*=======================================================================
 |
 |		名称項目セット処理
 |
 |	void	setnam(data)
 |
 |		char	*data;		名称項目
 |
 =======================================================================*/
static	void	setnam(char *data)
{
	*cmbp++ = ',';
	strcpy(cmbp, data);

	cmbp += strlen(cmbp);
}

/*=======================================================================
 |
 |		ローカルコマンドチェック処理
 |
 |	int	chk_loc_cmd()
 |
 |		int	data;		数値項目
 |
 =======================================================================*/
static	int	chk_loc_cmd()
{
	struct	prm	{
		char	prm[4];
		int	code;
	};
	static	struct	prm	csg_prm[] = {
		{"NSG", LOC_CSG_NSG},
		{"FSG", LOC_CSG_FSG},
		{"COL", LOC_CSG_COL},
		{"LRM", LOC_CSG_LRM},
		{"OPT", LOC_CSG_OPT},
		{"", 0}
	};
	static	struct	prm	ssg_prm[] = {
		{"CON", LOC_SSG_CON},
		{"NSG", LOC_SSG_NSG},
		{"SYC", LOC_SSG_SYC},
		{"ANM", LOC_SSG_ANM},
		{"CRM", LOC_SSG_CRM},
		{"CMF", LOC_SSG_CMF},
		{"AEC", LOC_SSG_AEC},
		{"", 0}
	};
	static	struct	prm	dsp_prm[] = {
		{"CSG",	LOC_DSP_CSG},
		{"SSG",	LOC_DSP_SSG},
		{"", 0}
	};
	static	struct	prm	csc_prm[] = {
		{"CCQ", LOC_CSC_CCQ},
		{"DOS", LOC_CSC_DOS},
		{"END", LOC_CSC_END},
		{"", 0}
	};
	static	struct	prm	utl_prm[] = {
		{"CPY", LOC_UTL_CPY},
		{"CFG", LOC_UTL_CFG},
		{"CFD", LOC_UTL_CFD},
		{"MTR", LOC_UTL_MTR},
		{"", 0}
	};
	static	struct	{
		char	cmd[4];
		int	code;
		struct	prm	*prm;
	} cmd[] = {
		{"CSG",	LOC_CSG, csg_prm},
		{"SSG", LOC_SSG, ssg_prm},
		{"DSP", LOC_DSP, dsp_prm},
		{"CSC", LOC_CSC, csc_prm},
		{"UTL", LOC_UTL, utl_prm},
		{"", 0, NULL}
	};
	int	i;		/* ループカウンタ */
	int	code;		/* コマンドコード */
	struct	prm	*prm;

	/* コマンド名取り出し */
	for (i = 0, cmbp++; i < 3 && *cmbp != '\0'; i++, cmbp++)
		cmd_name[i] = *cmbp;
	cmd_name[i] = '\0';

	/* パラメータ名取り出し */
	while (*cmbp == ' ')
		cmbp++;
	for (i = 0; i < 3 && *cmbp != '\0'; i++, cmbp++)
		prm_name[i] = *cmbp;
	prm_name[i] = '\0';

	/* コマンド名チェック */
	for (i = 0; cmd[i].code; i++) {
		if (strcmp(cmd_name, cmd[i].cmd) == 0)
			break;
	}

	/* パラメータ名チェック */
	if (cmd[i].code) {
		prm = cmd[i].prm;
		for (i = 0; prm[i].code; i++) {
			if (strcmp(prm_name, prm[i].prm) == 0)
				break;
		}
		code = prm[i].code;
	} else
		code = 0;

	return(code);
}
