/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンドファイル処理			*
 *		ファイル名	: cmf.c					*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	内部関数	*/
static	int	cmf_sort(CMF_TBL *, CMF_TBL *);

/*=======================================================================
 |
 |		コマンドファイル検索処理
 |
 |	void	find_cmf()
 |
 =======================================================================*/
void	find_cmf()
{
	struct	find_t	find;	/* ファイル検索構造体 */
	char	*cmf_path;	/* コマンドファイルパス名 */
	int	st;		/* ファイル検索結果 */
	int	count;		/* コマンドファイル数 */
	int	len;		/* コマンドファイルの説明文の長さ */
	FILE	*fp;		/* ファイルポインタ */
	CMF_TBL	*cmfp;		/* コマンドファイルテーブルポインタ */

	cmf_path = make_cmfpath("*.CMF");	/* パス名セット */

	/* コマンドファイル検索 */
	count = 0;
	st = _dos_findfirst(cmf_path, 0, &find);
	for (count = 0; st == 0; count++)
		st = _dos_findnext(&find);

	/* 現在のコマンドファイルテーブルを解放 */
	if (cmf_tbl != NULL)
		free(cmf_tbl);

	/* 新たにコマンドファイルテーブルを確保 */
	cmf_tbl = talloc(sizeof(CMF_TBL) * count);

	/* コマンドファイルテーブル作成 */
	st = _dos_findfirst(cmf_path, 0, &find);
	for (count = 0, cmfp = cmf_tbl; st == 0; ) {
		/* コマンドファイルオープン */
		if ((fp = fopen(make_cmfpath(find.name), "r")) != NULL) {
			/* 拡張子を取り除く */
			*strchr(find.name, '.') = '\0';

			/* コマンドファイル名セーブ */
			strcpy(cmfp->name, find.name);

			/* 説明文読み込み */
			fgets(cmfp->guide, 80, fp);
			len = strlen(cmfp->guide);
			if (cmfp->guide[len-1] == '\n')
				cmfp->guide[len-1] = '\0';

			/* コマンドファイルクローズ */
			fclose(fp);

			count++;	/* コマンドファイル数更新 */
			cmfp++;	/* コマンドファイルテーブルポインタ更新 */
		}
		st = _dos_findnext(&find);
	}

	n_cmf = count;		/* コマンドファイル数セーブ */

	/* コマンドテーブルをコマンドファイル名でソート */
	qsort(cmf_tbl, n_cmf, sizeof(CMF_TBL), cmf_sort);
}

/*=======================================================================
 |
 |		コマンドファイルソート処理
 |
 |	int	cmf_sort(p1, p2)
 |
 |		CMF_TBL	*p1;		コマンドファイルテーブルポインタ１
 |		CMF_TBL	*p2;		コマンドファイルテーブルポインタ２
 |
 |		返値			コマンドファイル名の比較結果
 |
 =======================================================================*/
static	int	cmf_sort(CMF_TBL *p1, CMF_TBL *p2)
{
	/* コマンドファイル名の比較 */
	return(strcmp(p1->name, p2->name));
}

/*=======================================================================
 |
 |		コマンドファイル読み込み処理
 |
 |	void	read_cmf()
 |
 =======================================================================*/
void	read_cmf()
{
	FILE	*fp;		/* ファイルポインタ */
	char	buf[256];	/* コマンドファイル読み込みバッファ */

	/* コマンドファイルオープン */
	if ((fp = fopen(cmf_name, "r")) == NULL)
		return;

	/* 機能概要読み込み */
	fgets(cmf_guide, sizeof(cmf_guide), fp);
	cmf_guide[strlen(cmf_guide) - 1] = '\0';

	/* コマンドファイル読み込み */
	while (fgets(buf, sizeof(buf), fp) != NULL) {
		buf[strlen(buf) - 1] = '\0';
		enq_cmf(buf);
	}

	fclose(fp);
}

/*=======================================================================
 |
 |		コマンドファイル書き込み処理
 |
 |	void	write_cmf()
 |
 =======================================================================*/
void	write_cmf()
{
	char	*cmd;		/* コマンドデータポインタ */
	FILE	*fp;		/* ファイルポインタ */

	if (cmf_que != NULL) {
		/* コマンドファイオープン */
		if ((fp = fopen(cmf_name, "w")) != NULL) {
			/* 説明文書き込み */
			fprintf(fp, "%s\n", cmf_guide);

			/* コマンドデータ書き込み */
			while ((cmd = deq_cmf()) != NULL) {
				fprintf(fp, "%s\n", cmd);
				free(cmd);
			}

			fclose(fp);	/* コマンドファイルクローズ */

			/* コマンドファイル作成メッセージ出力 */
			dsp_loc_msg(LOC_MSG_MKCMF, cmf_name);
		}
	}
}

/*=======================================================================
 |
 |		コマンドキュー登録処理
 |
 |	void	enq_cmf(cmd)
 |
 |		char	*cmd;		コマンド
 |
 =======================================================================*/
void	enq_cmf(char *cmd)
{
	en_queue(&cmf_que, cmd, strlen(cmd) + 1);
}

/*=======================================================================
 |
 |		コマンドキュー取り出し処理
 |
 |	char	*deq_cmf()
 |
 |		返値			コマンドデータ
 |
 =======================================================================*/
char	*deq_cmf()
{
	return(de_queue(&cmf_que));
}

/*=======================================================================
 |
 |		コマンドファイルパス名編集処理
 |
 |	char	*make_cmfpath(cmf)
 |
 |		char	*cmf;		コマンドファイル名
 |
 |		返値			コマンドファイルパス名
 |
 =======================================================================*/
char	*make_cmfpath(char *cmf)
{
	static	char	cmf_path[SZ_PATH];	/* コマンドファイルパス名 */
	char	*p;

	/* コマンドファイルパス名のセット */
	strcpy(cmf_path, ssg.cmf.cmd_path);
	if ((p = strchr(cmf_path, ':')) != NULL)
		p++;
	else
		p = cmf_path;

	if (*p != '\0' && p[strlen(p)-1] != '\\')
		strcat(cmf_path, "\\");

	strcat(cmf_path, cmf);		/* コマンドファイル名のセット */

	return(cmf_path);
}

/*=======================================================================
 |
 |		コマンドファイル実行開始処理
 |
 |	int	start_cmf(cmf_name)
 |
 |		char	*cmf_name;	コマンドファイル名
 |
 |		返値			０：正常終了　１：異常終了
 |
 =======================================================================*/
int	start_cmf(char *cmf_name)
{
	char	cmf[13];	/* コマンドファイル名 */
	char	buf[256];	/* コマンドファイル読み込みバッファ */

	/* すでにコマンドファイル実行中だったら中止 */
	if (exec_cmf_flag) {
		fclose(fp_cmf);
		exec_cmf_flag = 0;
	}

	/* コマンドファイル名編集 */
	sprintf(cmf, "%s.CMF", cmf_name);

	/* コマンドファイルオープン */
	if ((fp_cmf = fopen(make_cmfpath(cmf), "r")) == NULL) {
		dsp_loc_msg(LOC_MSG_ERCMF, cmf);
		return(1);
	}
	exec_cmf_flag = 1;	/* コマンドファイル実行中フラグＯＮ */

	/* 機能概要読み飛ばし */
	fgets(buf, sizeof(buf), fp_cmf);

	/* コマンドファイル実行 */
	exec_cmf();

	return(0);
}

/*=======================================================================
 |
 |		コマンドファイル実行処理
 |
 |	void	exec_cmf()
 |
 =======================================================================*/
void	exec_cmf()
{
	char	buf[256];
	int	len, c;

	for (;;) {
		/* コマンドファイル読み込み */
		if (fgets(buf, sizeof(buf), fp_cmf) == NULL) {
			fclose(fp_cmf);
			exec_cmf_flag = 0;
			break;
		}
		len = strlen(buf);
		if (buf[len-1] == '\n')
			buf[len-1] = '\0';

		/* コマンドファイル実行 */
		if (exec_cmd(buf) != 0) {
			if (csg.opt.cmd_err) {
				fclose(fp_cmf);
				exec_cmf_flag = 0;
				break;
			}
		}

		/* ＥＯＦなら終了 */
		if ((c = fgetc(fp_cmf)) == EOF) {
			fclose(fp_cmf);
			exec_cmf_flag = 0;
			break;
		}
		ungetc(c, fp_cmf);

		/* オンラインコマンドだったらメッセージ受信のためぬける */
		if (buf[0] == csg.nsg.online_id)
			break;
	}
}
