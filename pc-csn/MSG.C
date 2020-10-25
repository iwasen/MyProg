/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: メッセージ処理			*
 *		ファイル名	: msg.c					*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"
#include <time.h>

/*	内部関数	*/
static	MSG_TBL	*read_msg1file(char *);
static	char	*loc_kihonbu(void);
static	void	read_msg(MSG_TBL *, int, int *, int *, KAHENBU **, char **);
static	char	*read_submsg(MSG_TBL *, int);

/*=======================================================================
 |
 |		メッセージ受信画面保存処理
 |
 |	void	save_scr()
 |
 =======================================================================*/
void	save_scr()
{
	char	*vp;		/* ＶＲＡＭポインタ */

	FP_OFF(vp) = 80 * 2 * 3;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(t_save_buf, vp, 80*2*18);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(a_save_buf, vp, 80*2*18);
}

/*=======================================================================
 |
 |		メッセージ受信画面復元処理
 |
 |	void	restore_scr()
 |
 =======================================================================*/
void	restore_scr()
{
	char	*vp;		/* ＶＲＡＭポインタ */

	FP_OFF(vp) = 80 * 2 * 3;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(vp, t_save_buf, 80*2*18);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(vp, a_save_buf, 80*2*18);
}

/*=======================================================================
 |
 |		メッセージ出力処理
 |
 |	void	out_msg()
 |
 =======================================================================*/
void	out_msg()
{
	int	color;		/* 表示色 */
	int	prn;		/* プリンター出力有り／無しフラグ */
	int	fil;		/* ファイル出力有り／無しフラグ */
	int	class;		/* 自立メッセージクラス */
	char	*fname;		/* メッセージファイル名 */
	MSG_QUE	*mqp;		/* メッセージキューポインタ */
	char	*msgp;		/* メッセージ文字列ポインタ */
	char	*nlp;		/* 改行コードポインタ */
	FILE	*fp;		/* ファイルポインタ */
	int	dummy;

	while ((mqp = deq_msg()) != NULL) {
		msgp = mqp->msg;
		class = MSG_CLASS_DFLT;
		/* メッセージ種別チェック */
		switch (mqp->type) {
		case MSG_ANM:	/* 自立メッセージ */
			msgp = cnv_msg(msgp+4, anm_msg, &class);
			color = ssg.anm.dsp ? (class == MSG_CLASS_A1 || class == MSG_CLASS_A2 ? C_ANMA : C_ANMB) : 0;
			prn = ssg.anm.prn;
			fil = ssg.anm.fil;
			fname = ssg.anm.fname;
			break;
		case MSG_CRM:	/* コマンド応答メッセージ */
			if (strncmp(msgp, "@MSG", 4) == 0)
				msgp = cnv_msg(msgp+4, crm_msg, &dummy);
			color = ssg.crm.dsp ? C_CRM : 0;
			prn = ssg.crm.prn;
			fil = ssg.crm.fil;
			fname = ssg.crm.fname;
			break;
		case MSG_CNS:	/* コンソールメッセージ */
			color = csg.lrm.dsp ? C_CNSMSG : 0;
			prn = csg.lrm.prn;
			fil = csg.lrm.fil;
			fname = csg.lrm.fname;
			break;
		case MSG_CNSR:	/* 赤色コンソールメッセージ */
			color = csg.lrm.dsp ? C_RED : 0;
			prn = csg.lrm.prn;
			fil = csg.lrm.fil;
			fname = csg.lrm.fname;
			break;
		}

		/* メッセージ出力 */
		for (;;) {
			/* 行の分割 */
			if ((nlp = strchr(msgp, '\n')) != NULL)
				*nlp = '\0';

			/* 画面表示 */
			if (color != 0)
				dsp_msg(msgp, color);

			/* プリンター出力 */
			if (prn)
				lprintf("%s\x0d\x0a", msgp);

			/* ファイル出力 */
			if (fil) {
				if ((fp = fopen(fname, "a")) != NULL) {
					fprintf(fp, "%s\n", fil == 1 ? msgp : mqp->msg);
					fclose(fp);
				}
			}

			if (nlp == NULL)
				break;

			msgp = nlp + 1;
		}

		free_msg(mqp);

		if (class == MSG_CLASS_A1)
			alxxx_mj();
		else if (class == MSG_CLASS_A2)
			alxxx_mn();
	}
}

/*=======================================================================
 |
 |		メッセージ表示処理
 |
 |	void	dsp_msg(msg, color)
 |
 |		char	*msg;		メッセージ
 |		int	color;		表示色
 |
 =======================================================================*/
 void	dsp_msg(char *msg, int color)
{
	char	*p;		/* メッセージ文字列ポインタ */
	int	n;		/* １行の表示文字数 */

	for (p = msg; *p != '\0'; p += n) {
		kbhit();
		n = min(strlen(p), 76);
		dspnstr(2, csr_y + 3, p, n , color);
		csr_y = next_line(csr_y);
	}
}

/*=======================================================================
 |
 |		改行処理
 |
 |	int	next_line(y)
 |
 |		int	y;		現在の行位置
 |
 |		返値			改行後の行位置
 |
 =======================================================================*/
int	next_line(int y)
{
	char	*vp;		/* ＶＲＡＭポインタ */

	if (y < 17)
		y++;
	else {
		/* １行スクロールアップ */
		FP_OFF(vp) = 80 * 2 * 3;
		FP_SEG(vp) = SEG_TVRAM;
		memcpy(vp, vp+160, 80*2*17);
		FP_SEG(vp) = SEG_ATTR;
		memcpy(vp, vp+160, 80*2*17);
		dspspc(2, 20, 76);
	}

	return(y);
}

/*=======================================================================
 |
 |		メッセージ登録処理
 |
 |	void	enq_msg(type, msg)
 |
 |		int	type;		メッセージ種別
 |		char	*msg;		メッセージ
 |
 =======================================================================*/
void	enq_msg(int type, char *msg)
{
	MSG_QUE	mq;		/* メッセージキュー */

	mq.type = type;		/* メッセージタイプセーブ */
	mq.msg = talloc(strlen(msg) + 1);
	strcpy(mq.msg, msg);		/* メッセージデータセーブ */

	en_queue(&msg_que, &mq, sizeof(mq));	/* キュー登録 */
}

/*=======================================================================
 |
 |		メッセージ取り出し処理
 |
 |	MSG_QUE	*deq_msg()
 |
 |		返値			メッセージキューポインタ
 |
 =======================================================================*/
MSG_QUE	*deq_msg()
{
	return(de_queue(&msg_que));
}

/*=======================================================================
 |
 |		メッセージ解放処理
 |
 |	void	free_msg(mqp)
 |
 |		MSG_QUE	*mqp;		メッセージキューポインタ
 |
 =======================================================================*/
void	free_msg(MSG_QUE *mqp)
{
	free(mqp->msg);
	free(mqp);
}

/*=======================================================================
 |
 |		全メッセージファイル読み込み処理
 |
 |	void	read_msgfile()
 |
 =======================================================================*/
void	read_msgfile()
{
	/* 自立メッセージファイル読み込み */
	anm_msg = read_msg1file(csg.fsg.amsg_file);

	/* コマンド応答メッセージファイル読み込み */
	crm_msg = read_msg1file(csg.fsg.cmsg_file);

	/* ローカルメッセージファイル読み込み */
	loc_msg = read_msg1file(csg.fsg.lmsg_file);
}

/*=======================================================================
 |
 |		１メッセージファイル読み込み処理
 |
 |	MSG_TBL	*read_msg1file(file_name)
 |
 |		char	*file_name;	メッセージファイル名
 |
 |		返値			メッセージテーブルポインタ
 |
 =======================================================================*/
static	MSG_TBL	*read_msg1file(char *file_name)
{
	FILE	*fp;		/* ファイルポインタ */
	MSG_TBL	*ptr;		/* メッセージテーブルポインタ */

	/* ファイル名が設定されていない場合はリターン */
	if (file_name[0] == '\0')
		return(NULL);

	/* メッセージファイルオープン */
	if ((fp = fopen(file_name, "rb")) == NULL)
		return(NULL);

	/* メッセージテーブルエリア確保 */
	ptr = talloc(sizeof(MSG_TBL));

	/* メッセージ数読み込み */
	fread(&ptr->n_msg, sizeof(int), 1, fp);
	ptr->fp = fp;

	/* メッセージインデックス読み込み */
	ptr->msg = talloc(ptr->n_msg * sizeof(struct msg_no));
	fread(ptr->msg, sizeof(struct msg_no), ptr->n_msg, fp);

	return(ptr);
}

/*=======================================================================
 |
 |		メッセージ日本語変換処理
 |
 |	char	*cnv_msg(msg, mtp, class)
 |
 |		char	*msgp;		変換前メッセージ
 |		MSG_TBL	*mtp;		メッセージ変換テーブル
 |		int	*class;		自立メッセージクラス（ＡｏｒＢ）
 |
 |		返値			変換後のメッセージへのポインタ
 |
 =======================================================================*/
char	*cnv_msg(char *msgp, MSG_TBL *mtp, int *class)
{
	int	msg_no;		/* メッセージ番号 */
	int	n_kahenbu;	/* 受信データ上の可変部個数 */
	int	n_kahenbu2;	/* メッセージファイル上の可変部個数 */
	int	l_kahenbu;	/* 可変部の長さ */
	int	i;		/* ループカウンタ */
	KAHENBU	*kahenbu;	/* 可変部データ */
	KAHENBU	*kp;		/* 可変部データポインタ */
	char	prmbuf[256];	/* 可変部編集バッファ */
	char	*prm;		/* 可変部編集バッファポインタ */
	char	*msg;		/* ﾒｯｾｰｼﾞﾌｧｲﾙから読み込んだメッセージデータ */
	char	*kdp[100];	/* 可変部データポインタ */
	char	*p;		/* 可変部取り出し用ポインタ */
	static	char	msgbuf[512];	/* 変換後のメッセージバッファ */

	/* メッセージ番号，可変部個数取り出し */
	sscanf(msgp, "%4d%2d", &msg_no, &n_kahenbu);

	/* 可変部取り出し */
	for (i = 1, p = msgp + 6; i <= n_kahenbu; i++) {
		sscanf(p, "%2d", &l_kahenbu);
		kdp[i] = talloc(l_kahenbu + 1);
		p += 2;
		memcpy(kdp[i], p, l_kahenbu);
		kdp[i][l_kahenbu] = '\0';
		p += l_kahenbu;
	}

	/* 基本部取り出し */
	kdp[0] = talloc(45);
	sprintf(kdp[0], "[VSE#%.2s MODULE#%.2s %.1s] %.23s", p+3, p+1, p, p+5);

	/* メッセージファイル読み込み */
	read_msg(mtp, msg_no, class, &n_kahenbu2, &kahenbu, &msg);

	/* 可変部データ編集 */
	for (i = 0, prm = prmbuf, kp = kahenbu; i < n_kahenbu2; i++, kp++) {
		switch (kp->type) {
		case 0:		/* 数値型 */
			sscanf(kdp[kp->kahenbu_no], "%x", ((int *)prm)++);
			break;
		case 1:		/* 文字型 */
			*((char **)prm)++ = kdp[kp->kahenbu_no];
			break;
		case 2:		/* サブメッセージ */
			msg_no = atoi(kdp[kp->kahenbu_no]);
			free(kdp[kp->kahenbu_no]);
			kdp[kp->kahenbu_no] = read_submsg(mtp, msg_no);
			*((char **)prm)++ = kdp[kp->kahenbu_no];
			break;
		}
	}

	/* メッセージ編集 */
	vsprintf(msgbuf, msg, prmbuf);

	/* バッファ解放 */
	free(kahenbu);
	free(msg);
	for (i = 0; i <= n_kahenbu; i++)
		free(kdp[i]);

	return(msgbuf);
}

/*=======================================================================
 |
 |		ローカルメッセージ表示処理
 |
 |	void	dsp_loc_msg(msg_no, p)
 |
 |		int	msg_no;		メッセージ番号
 |		char	*p;		可変部データ
 |
 =======================================================================*/
void	dsp_loc_msg(int msg_no, char *p)
{
	int	class;		/* 自立メッセージクラス（ダミー） */
	int	n_kahenbu;	/* 可変部個数 */
	int	i;		/* ループカウンタ */
	int	msg_type;	/* メッセージ種別 */
	KAHENBU	*kahenbu;	/* 可変部データ */
	KAHENBU	*kp;		/* 可変部データポインタ */
	char	prmbuf[256];	/* 可変部編集バッファ */
	char	*prm;		/* 可変部編集バッファポインタ */
	char	**pp;		/* パラメータポインタ */
	char	*msg;		/* ﾒｯｾｰｼﾞﾌｧｲﾙから読み込んだメッセージデータ */
	char	msgbuf[256];	/* 変換後のメッセージバッファ */

	/* メッセージファイル読み込み */
	read_msg(loc_msg, msg_no, &class, &n_kahenbu, &kahenbu, &msg);

	/* 可変部，基本部編集 */
	pp = &p;
	for (i = 0, prm = prmbuf, kp = kahenbu; i < n_kahenbu; i++, kp++) {
		if (kp->kahenbu_no == 0) {
			/* 基本部 */
			*((char **)prm)++ = loc_kihonbu();
		} else {
			/* 可変部 */
			if (kp->type == 0) {
				/* 数値型 */
				*((int *)prm)++ = *((int *)pp)++;
			} else {
				/* 文字型 */
				*((char **)prm)++ = *((char **)pp)++;
			}
		}
	}

	vsprintf(msgbuf, msg, prmbuf);	/* メッセージ編集 */

	/* バッファ解放 */
	free(kahenbu);
	free(msg);

	/* メッセージ種別セット */
	switch (msg_no) {
	case LOC_MSG_DISCON:
	case LOC_MSG_RESTART:
		msg_type = MSG_CNSR;
		break;
	default:
		msg_type = MSG_CNS;
		break;
	}

	enq_msg(msg_type, msgbuf);	/* メッセージ登録 */
}

/*=======================================================================
 |
 |		ローカルメッセージ基本部編集
 |
 |	char	*loc_kihonbu()
 |
 |		返値			ローカルメッセージ基本部
 |
 =======================================================================*/
static	char	*loc_kihonbu()
{
	static	char	kihonbu[30];	/* 基本部編集バッファ */
	static	char	week[7][3] = {	/* 曜日データ */
		"日", "月", "火", "水", "木", "金", "土"
	};
	long	ltime;		/* 現時刻取り出しエリア */
	struct	tm	*ltm;	/* 時刻構造体ポインタ */

	/* 現時刻取り出し */
	time(&ltime);
	ltm = localtime(&ltime);

	/* 基本部編集 */
	sprintf(kihonbu, "LOC%04u %04u-%02u-%02u(%s) %02u:%02u"
			, ++loc_seq
			, 1900 + ltm->tm_year, ltm->tm_mon+1, ltm->tm_mday
			, week[ltm->tm_wday]
			, ltm->tm_hour, ltm->tm_min);

	return(kihonbu);
}

/*=======================================================================
 |
 |		メッセージ読み込み処理
 |
 |	void	read_msg(mtp, msg_no, class, n_kahenbu, kahenbu, msg)
 |
 |		MSG_TBL	*mtp;		メッセージテーブルポインタ
 |		int	msg_no;		メッセージ番号
 |		int	*class;		自立メッセージクラス（ＡｏｒＢ）
 |		int	*n_kahenbu;	可変部個数
 |		KAHENBU	**kahenbu;	可変部情報
 |		char	**msg;		メッセージ
 |
 =======================================================================*/
static	void	read_msg(MSG_TBL *mtp, int msg_no, int *class, int *n_kahenbu
					, KAHENBU **kahenbu, char **msg)
{
	int	i;		/* ループカウンタ */
	int	len;		/* メッセージ長 */
	char	buf[80];

	*class = MSG_CLASS_DFLT;	/* メッセージクラスにデフォルトセット*/
	*n_kahenbu = 0;			/* 可変部個数クリア */
	*kahenbu = NULL;
	*msg = NULL;

	if (mtp == NULL) {
		strcpy(buf, "メッセージファイルがありません");
		*msg = talloc(strlen(buf) + 1);
		strcpy(*msg, buf);
		return;
	}

	/* メッセージ番号検索 */
	for (i = 0; i < mtp->n_msg; i++) {
		if (mtp->msg[i].msg_no == msg_no)
			break;
	}

	if (i == mtp->n_msg) {
		/* メッセージが無い */
		sprintf(buf, "未登録メッセージ(%4d)", msg_no);
		*msg = talloc(strlen(buf) + 1);
		strcpy(*msg, buf);
		return;
	}

	/* 読み込み位置セット */
	fseek(mtp->fp, mtp->msg[i].offset, 0);

	/* 自立メッセージクラス読み込み */
	fread(class, sizeof(int), 1, mtp->fp);

	/* 可変部個数読み込み */
	fread(n_kahenbu, sizeof(int), 1, mtp->fp);

	/* 可変部データ読み込み */
	*kahenbu = talloc(sizeof(KAHENBU) * (*n_kahenbu));
	fread(*kahenbu, sizeof(KAHENBU), *n_kahenbu, mtp->fp);

	/* メッセージ長読み込み */
	fread(&len, sizeof(int), 1, mtp->fp);

	/* メッセージデータ読み込み */
	*msg = talloc(len + 1);
	fread(*msg, 1, len, mtp->fp);
	(*msg)[len] = '\0';
}

/*=======================================================================
 |
 |		サブメッセージ読み込み処理
 |
 |	char	*read_submsg(mtp, msg_no)
 |
 |		MSG_TBL	*mtp;		メッセージテーブルポインタ
 |		int	msg_no;		メッセージ番号
 |
 |		返値			サブメッセージ
 |
 =======================================================================*/
static	char	*read_submsg(MSG_TBL *mtp, int msg_no)
{
	int	i;		/* ループカウンタ */
	int	len;		/* メッセージ長 */
	int	n_kahenbu;	/* 可変部個数 */
	char	*msg;		/* メッセージポインタ */

	/* メッセージ番号検索 */
	for (i = 0; i < mtp->n_msg; i++) {
		if (mtp->msg[i].msg_no == msg_no)
			break;
	}

	if (i == mtp->n_msg) {
		/* メッセージが無い場合 */
		msg = talloc(1);
		len = 0;
	} else {
		/* メッセージが有った場合 */
		fseek(mtp->fp, mtp->msg[i].offset + sizeof(int), 0);
		fread(&n_kahenbu, sizeof(int), 1, mtp->fp);
		fseek(mtp->fp, (long)(sizeof(KAHENBU) * n_kahenbu), 1);
		fread(&len, sizeof(int), 1, mtp->fp);
		msg = talloc(len + 1);
		fread(msg, 1, len, mtp->fp);
	}
	msg[len] = '\0';

	return(msg);
}
