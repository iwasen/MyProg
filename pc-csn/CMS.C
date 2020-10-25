/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンド選択ファイル処理		*
 *		ファイル名	: cms.c					*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	内部関数	*/
static	CMS_TBL	*read_cms(FILE *);
static	CMD_TBL	*read_cmd(FILE *, int);
static	PRM_TBL	*read_prm(FILE *, int);
static	PRT_TBL	*read_prt(FILE *, int);
static	SEL_TBL	*read_sel(FILE *);
static	NUM_TBL	*read_num(FILE *);
static	NAM_TBL	*read_nam(FILE *);
static	GUD_TBL	*read_gud(FILE *);
static	char	*getstr(FILE *);
static  int	getnum(FILE *);
static	CMD_TBL	*search_cmd(CMS_TBL *, char *, char *);
static	PRM_TBL	*search_prm(CMD_TBL *, char *);

/*=======================================================================
 |
 |		コマンド選択ファイル読み込み
 |
 |	void	read_cmdfile()
 |
 =======================================================================*/
void	read_cmdfile()
{
	FILE	*fp;		/* ファイルポインタ */
	int	i;		/* ループカウンタ */

	/* オンラインコマンド選択ファイル読み込み */
	for (i = 0; i < 5; i++) {
		if (csg.fsg.cms_file[i][0] != '\0') {
			/* コマンド選択ファイルオープン */
			if ((fp = fopen(csg.fsg.cms_file[i], "rb")) != NULL) {

				/* コマンド選択ファイル読み込み */
				cmstbl[i] = read_cms(fp);

				/* コマンド選択ファイルクローズ */
				fclose(fp);
			}
		}
	}

	/* オフラインコマンド選択ファイル読み込み */
	if ((fp = fopen(csg.fsg.loc_file, "rb")) != NULL) {

		/* コマンド選択ファイル読み込み */
		loctbl = read_cms(fp);

		/* コマンド選択ファイルクローズ */
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		コマンドタイトル読み込み
 |
 |	CMS_TBL	*read_cms(fp)
 |
 |		char	*file_name;	コマンドファイル名
 |
 |		返値			コマンド選択テーブルポインタ
 |
 =======================================================================*/
static	CMS_TBL	*read_cms(FILE *fp)
{
	CMS_TBL	*ptr;		/* コマンド選択テーブルポインタ */

	ptr = talloc(sizeof(CMS_TBL));	/* メモリ確保 */

	ptr->title = getstr(fp);	/* タイトル読み込み */
	ptr->guide = getstr(fp);	/* 説明読み込み */
	ptr->n = getnum(fp);		/* コマンド個数読み込み */
	ptr->ptr = read_cmd(fp, ptr->n); /* コマンドデータ読み込み */

	return(ptr);
}

/*=======================================================================
 |
 |		コマンドデータ読み込み
 |
 |	CMD_TBL	*read_cmd(fp, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		int	n;		コマンド個数
 |
 |		返値			コマンドテーブルポインタ
 |
 =======================================================================*/
static	CMD_TBL	*read_cmd(FILE *fp, int n)
{
	CMD_TBL	*ptr, *p;	/* コマンドテーブルポインタ */

	if (n == 0)
		return(NULL);

	ptr = talloc(sizeof(CMD_TBL) * n);	/* メモリ確保 */

	for (p = ptr; n--; p++) {
		p->name = getstr(fp);	/* コマンド名読み込み */
		p->guide = getstr(fp);	/* 説明読み込み */
		p->n = getnum(fp);	/* パラメータ種類個数読み込み */
		p->ptr = read_prm(fp, p->n); /* パラメータ種類読み込み */
	}

	return(ptr);
}

/*=======================================================================
 |
 |		パラメータ種類データ読み込み
 |
 |	PRM_TBL *read_prm(fp, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		int	n;		パラメータ種類個数
 |
 |		返値			パラメータ種類テーブルポインタ
 |
 =======================================================================*/
static	PRM_TBL	*read_prm(FILE *fp, int n)
{
	PRM_TBL	*ptr, *p;	/* パラメータ種類テーブルポインタ */

	if (n == 0)
		return(NULL);

	ptr = talloc(sizeof(PRM_TBL) * n);	/* メモリ確保 */

	for (p = ptr; n--; p++) {
		p->name = getstr(fp);		/* パラメータ名読み込み */
		p->guide = getstr(fp);		/* 説明読み込み */
		p->n = getnum(fp);		/* パラメータ数読み込み */
		p->ptr = read_prt(fp, p->n); /* パラメータデータ読み込み */
	}

	return(ptr);
}

/*=======================================================================
 |
 |		パラメータデータ読み込み
 |
 |	PRT_TBL	*read_prt(fp, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		int	n;		パラメータ個数
 |
 |		返値			パラメータテーブル
 |
 =======================================================================*/
static	PRT_TBL	*read_prt(FILE *fp, int n)
{
	PRT_TBL	*ptr, *p;	/* パラメータテーブルポインタ */

	if (n == 0)
		return(NULL);

	ptr = talloc(sizeof(PRT_TBL) * n);	/* メモリ確保 */

	for (p = ptr; n--; p++) {
		p->type = getnum(fp);		/* パラメータ種類読み込み */
		p->guide = getstr(fp);		/* 説明読み込み */
		switch (p->type) {
		case KWD_SEL:	/* 説明項目 */
			p->ptr.sel = read_sel(fp);
			break;
		case KWD_NUM:	/* 数値項目 */
			p->ptr.num = read_num(fp);
			break;
		case KWD_NAM:	/* 名称項目 */
			p->ptr.nam = read_nam(fp);
			break;
		case KWD_MSG:	/* 説明項目 */
			p->ptr.gud = read_gud(fp);
			break;
		}
	}

	return(ptr);
}

/*=======================================================================
 |
 |		選択項目データ読み込み
 |
 |	SEL_TBL	*read_sel(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		返値			選択項目テーブルポインタ
 |
 =======================================================================*/
static	SEL_TBL	*read_sel(FILE *fp)
{
	SEL_TBL	*ptr;		/* 選択項目テーブルポインタ */
	int	i;		/* ループカウンタ */
	int	n;		/* 選択項目数 */

	n = getnum(fp);		/* 選択項目数読み込み */
	ptr = talloc(sizeof(SEL_TBL)+sizeof(ptr->ptr) * n); /* メモリ確保 */
	ptr->n = n;

	for (i = 0; i < n; i++) {
		ptr->ptr[i][0] = getstr(fp); /* 選択項目（表示）読み込み */
		ptr->ptr[i][1] = getstr(fp); /* 選択項目（入力）読み込み */
	}

	return(ptr);
}

/*=======================================================================
 |
 |		数値項目データ読み込み
 |
 |	NUM_TBL	*read_num(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		返値			数値項目テーブルポインタ
 |
 =======================================================================*/
static	NUM_TBL	*read_num(FILE *fp)
{
	NUM_TBL	*ptr;		/* 数値項目テーブルポインタ */

	ptr = talloc(sizeof(NUM_TBL));	/* メモリ確保 */

	ptr->min_flg = getnum(fp);	/* 最小値指定フラグ読み込み */
	ptr->min = getnum(fp);		/* 最小値読み込み */
	ptr->min_syc = getnum(fp);	/* 最小値システム定数読み込み */
	ptr->max_flg = getnum(fp);	/* 最大値指定フラグ読み込み */
	ptr->max = getnum(fp);		/* 最大値読み込み */
	ptr->max_syc = getnum(fp);	/* 最大値システム定数読み込み */
	ptr->prmid = getstr(fp);	/* パラメータＩＤ読み込み */
	ptr->sel = getstr(fp);		/* 選択項目読み込み */

	return(ptr);
}

/*=======================================================================
 |
 |		名称項目データ読み込み
 |
 |	NAM_TBL	*read_nam(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		返値			名称項目テーブルポインタ
 |
 =======================================================================*/
static	NAM_TBL	*read_nam(FILE *fp)
{
	NAM_TBL	*ptr;		/* 名称項目テーブルポインタ */

	ptr = talloc(sizeof(NAM_TBL));	/* メモリ確保 */

	ptr->length = getnum(fp);	/* 最大文字数読み込み */
	ptr->sel = getstr(fp);		/* 選択項目読み込み */
	ptr->ext1 = getstr(fp);		/* 拡張子１読み込み */
	ptr->ext2 = getstr(fp);		/* 拡張子２読み込み */

	return(ptr);
}

/*=======================================================================
 |
 |		説明項目データ読み込み
 |
 |	GUD_TBL	*read_gud(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		返値			名称項目テーブルポインタ
 |
 =======================================================================*/
static	GUD_TBL	*read_gud(FILE *fp)
{
	GUD_TBL	*ptr;		/* 説明項目テーブルポインタ */

	ptr = talloc(sizeof(GUD_TBL));	/* メモリ確保 */

	ptr->length = getnum(fp);	/* 最大文字数読み込み */

	return(ptr);
}

/*=======================================================================
 |
 |		文字列読み込み
 |
 |	char	*getstr(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		返値			読み込んだ文字列のポインタ
 |
 =======================================================================*/
static	char	*getstr(FILE *fp)
{
	char	*ptr;		/* 文字列ポインタ */
	int	len;		/* 文字列長 */

	len = getnum(fp);		/* 長さ読み込み */

	if (len != -1) {
		ptr = talloc(len + 1);		/* メモリ確保 */

		fread(ptr, 1, len, fp);		/* 文字列読み込み */
		ptr[len] = '\0';
	} else
		ptr = NULL;

	return(ptr);
}

/*=======================================================================
 |
 |		数値読み込み
 |
 |	int	getnum(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 |		返値			読み込んだ数値
 |
 =======================================================================*/
static	int	getnum(FILE *fp)
{
	int	n;		/* 数値データ */

	/* 数値データ読み込み */
	fread(&n, sizeof(int), 1, fp);

	return(n);
}

/*=======================================================================
 |
 |		コマンド選択テーブル検索
 |
 |	CMS_TBL	*search_cms(bufp)
 |
 |		char	*bufp;		コマンドバッファ
 |
 |		返値			コマンド選択テーブルポインタ
 |
 =======================================================================*/
CMS_TBL	*search_cms(char *bufp)
{
	char	cmd_name[4];	/* コマンド名 */
	char	prm_name[4];	/* パラメータ名 */
	int	i;		/* ループカウンタ */

	/* コマンド名取り出し */
	for (i = 0; i < 3 && *bufp != '\0'; i++, bufp++)
		cmd_name[i] = *bufp;
	cmd_name[i] = '\0';

	/* パラメータ名取り出し */
	while (*bufp == ' ')
		bufp++;
	for (i = 0; i < 3 && *bufp != '\0'; i++, bufp++)
		prm_name[i] = *bufp;
	prm_name[i] = '\0';

	/* コマンド名検索 */
	for (i = 0; i < 5; i++) {
		cmd_current = search_cmd(cmstbl[i], cmd_name, prm_name);
		if (cmd_current != NULL)
			return(cmstbl[i]);
	}
	return(NULL);
}

/*=======================================================================
 |
 |		コマンドテーブル検索
 |
 |	CMD_TBL	*search_cmd(cmstp, cmd_name, prm_name)
 |
 |		CMS_TBL	*cmsp;		コマンド選択テーブルポインタ
 |		char	*cmd_name;	コマンド名
 |		char	*prm_name;	パラメータ名
 |
 |		返値			コマンドテーブルポインタ
 |
 =======================================================================*/
static	CMD_TBL	*search_cmd(CMS_TBL *cmstp, char *cmd_name, char *prm_name)
{
	int	i;		/* ループカウンタ */
	CMD_TBL	*cmdtp;		/* コマンドテーブルポインタ */

	if (cmstp == NULL)
		return(NULL);

	/* パラメータ名検索 */
	for (i = 0, cmdtp = cmstp->ptr; i < cmstp->n; i++, cmdtp++) {
		if (strcmp(cmd_name, cmdtp->name) == 0) {
			prm_current = search_prm(cmdtp, prm_name);
			if (prm_current != NULL)
				return(cmdtp);
		}
	}

	return(NULL);
}

/*=======================================================================
 |
 |		パラメータテーブル検索
 |
 |	PRM_TBL	*search_prm(cmdtp, prm_name)
 |
 |		CMD_TBL	*cmdp;		コマンドテーブルポインタ
 |		char	*prm_name;	パラメータ名
 |
 |		返値			パラメータ種類テーブルポインタ
 |
 =======================================================================*/
static	PRM_TBL	*search_prm(CMD_TBL *cmdtp, char *prm_name)
{
	int	i;		/* ループカウンタ */
	PRM_TBL	*prmtp;		/* パラメータ種類テーブルポインタ */

	/* パラメータ名検索 */
	for (i = 0, prmtp = cmdtp->ptr; i < cmdtp->n; i++, prmtp++) {
		if (prmtp->name == NULL)
			return(prmtp);
		if (strcmp(prm_name, prmtp->name) == 0)
			return(prmtp);
	}

	return(NULL);
}
