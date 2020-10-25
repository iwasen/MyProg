/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: コマンドファイル変換ユーティリティー	*
 *		ファイル名	: cmdcnv.c				*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	最大文字数	*/
#define	LEN_CMS		34
#define	LEN_CMSGUIDE	72
#define	LEN_CMDGUIDE	56
#define	LEN_PRMGUIDE	56
#define	LEN_PRTGUIDE	23

/*	共通エリア	*/
static	int	unget_flg;
static	char	line_buf[256];
static	int	line_no;

/*	内部関数定義	*/
static	CMS_TBL	*read_file(FILE *);
static	CMD_TBL	*read_cmd(FILE *, int *);
static	PRM_TBL	*read_prm(FILE *, int *);
static	PRT_TBL	*read_prt(FILE *, int *);
static	SEL_TBL	*read_sel(FILE *);
static	NUM_TBL	*read_num(FILE *);
static	NAM_TBL	*read_nam(FILE *);
static	GUD_TBL	*read_gud(FILE *);
static	int	check_key(FILE *);
static	char	*getstr(FILE *, int);
static	int	gettok(FILE *, char *);
static	int	fgetchr(FILE *);
static	void	*talloc(int);
static	void	*trealloc(void *, int);
static  void	syntax_err(void);
static  void	length_err(char *, int);
static	void	write_file(FILE *, CMS_TBL *);
static	void	write_cmd(FILE *, CMD_TBL *, int);
static	void	write_prm(FILE *, PRM_TBL *, int);
static	void	write_prt(FILE *, PRT_TBL *, int);
static	void	write_sel(FILE *, SEL_TBL *);
static	void	write_num(FILE *, NUM_TBL *);
static	void	write_nam(FILE *, NAM_TBL *);
static	void	write_gud(FILE *, GUD_TBL *);
static	void	outstr(FILE *, char *);
static	void	outnum(FILE *, int);

/*=======================================================================
 |
 |		メイン処理
 |
 |	int	main()
 |
 =======================================================================*/
main()
{
	char	read_file_name[64], write_file_name[64];
	char	*p;
	FILE	*fp;
	CMS_TBL	*ptr;
	int	ch;

	/* タイトル表示 */
	printf("＊＊＊　コマンドファイル フォーマット変換ユーティリティー　＊＊＊\n\n");

	/* コマンドファイル名入力 */
	printf("コマンドファイル名を入力して下さい [.TXT] ==>");
	scanf("%s", read_file_name);

	/* オンライン／ローカル選択 */
	printf("０：オンライン　１：ローカル ==>");
	for (;;) {
		ch = getch();
		if (ch == '0' || ch == '1') {
			printf("%c\n\n", ch);
			break;
		}
	}

	/* ファイル名編集 */
	strcpy(write_file_name, read_file_name);
	if ((p = strrchr(read_file_name, '.')) == NULL) {
		strcat(read_file_name, ".TXT");
		strcat(write_file_name, ch == '0' ? ".CMS" : ".LOC");
	} else
		strcpy(strrchr(write_file_name, '.'), ch == '0' ? ".CMS" : ".LOC");

	/* 入力ファイル名表示 */
	printf("入力ファイル %s\n",read_file_name);

	/* 入力ファイルオープン */
	if ((fp = fopen(read_file_name, "r")) == 0) {
		printf("ファイルがオープンできません %s\n", read_file_name);
		exit(1);
	}

	/* 入力ファイル読み込み */
	ptr = read_file(fp);
	fclose(fp);

	/* 出力ファイル名表示 */
	printf("出力ファイル %s\n",write_file_name);

	/* 出力ファイルオープン */
	if ((fp = fopen(write_file_name, "wb")) == 0) {
		printf("ファイルがオープンできません %s\n", write_file_name);
		exit(1);
	}

	/* 出力ファイル書き込み */
	write_file(fp, ptr);
	fclose(fp);

	printf("\n正常終了しました\n");

	return(0);
}

/*=======================================================================
 |
 |		入力ファイル（テキストファイル）読み込み
 |
 |	CMS_TBL	*read_file(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 =======================================================================*/
static	CMS_TBL	*read_file(FILE *fp)
{
	CMS_TBL	*ptr;

	ptr = NULL;
	if (check_key(fp) == KWD_TTL) {
		ptr = talloc(sizeof(CMS_TBL));	/* メモリ確保 */
		ptr->title = getstr(fp, 1);	/* タイトル読み込み */
		if (strlen(ptr->title) > LEN_CMS)
			length_err(ptr->title, LEN_CMS);
		ptr->guide = getstr(fp, 1);	/* 説明読み込み */
		if (strlen(ptr->guide) > LEN_CMSGUIDE)
			length_err(ptr->guide, LEN_CMSGUIDE);
		ptr->ptr = read_cmd(fp, &ptr->n); /* コマンドデータ読み込み */
	}
	return(ptr);
}

/*=======================================================================
 |
 |		コマンドデータ読み込み
 |
 |	CMD_TBL	*read_cmd(fp, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		int	*n;		コマンド個数
 |
 =======================================================================*/
static	CMD_TBL	*read_cmd(FILE *fp, int *n)
{
	CMD_TBL	*ptr, *p;

	ptr = NULL;
	*n = 0;
	while (check_key(fp) == KWD_COM) {
		if (ptr == NULL)
			ptr = talloc(sizeof(CMD_TBL));	/* メモリ確保 */
		else
			ptr = trealloc(ptr, sizeof(CMD_TBL) * (*n + 1));

		p = &ptr[*n];
		p->name = getstr(fp, 1);	/* コマンド名読み込み */
		p->guide = getstr(fp, 1);	/* 説明読み込み */
		if (strlen(p->guide) > LEN_CMDGUIDE)
			length_err(p->guide, LEN_CMDGUIDE);
		p->ptr = read_prm(fp, &p->n);	/* パラメータ種類読み込み */

		(*n)++;
	}
	unget_flg = 1;
	return(ptr);
}

/*=======================================================================
 |
 |		パラメータ種類読み込み
 |
 |	PRM_TBL	*read_prm(fp, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		int	*n;		パラメータ種類個数
 |
 =======================================================================*/
static	PRM_TBL	*read_prm(FILE *fp, int *n)
{
	PRM_TBL	*ptr, *p;
	int	key;

	ptr = NULL;
	*n = 0;

	key = check_key(fp);
	unget_flg = 1;
	if (key == KWD_SEL || key == KWD_NUM
				|| key == KWD_NAM || key == KWD_MSG) {
		ptr = talloc(sizeof(PRM_TBL));	/* メモリ確保 */
		ptr->name = NULL;		/* パラメータ名読み込み */
		ptr->guide = NULL;		/* 説明読み込み */
		ptr->ptr = read_prt(fp, &ptr->n);/* パラメータ読み込み */
		(*n)++;
	} else {
	    while (check_key(fp) == KWD_PRM) {
		if (ptr == NULL)
			ptr = talloc(sizeof(PRM_TBL));	/* メモリ確保 */
		else
			ptr = trealloc(ptr, sizeof(PRM_TBL) * (*n + 1));

		p = &ptr[*n];
		p->name = getstr(fp, 1);	/* パラメータ名読み込み */
		p->guide = getstr(fp, 1);	/* 説明読み込み */
		if (strlen(p->guide) > LEN_PRMGUIDE)
			length_err(p->guide, LEN_PRMGUIDE);
		p->ptr = read_prt(fp, &p->n);	/* パラメータ読み込み */
		(*n)++;
	    }
	}
	unget_flg = 1;
	return(ptr);
}

/*=======================================================================
 |
 |		各パラメータ読み込み
 |
 |	PRT_TBL	*read_prt(fp, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		int	*n;		パラメータ個数
 |
 =======================================================================*/
static	PRT_TBL	*read_prt(FILE *fp, int *n)
{
	PRT_TBL	*ptr, *p;
	int	loop_sw, key;

	ptr = NULL;
	*n = 0;
	loop_sw = 1;
	while ((key = check_key(fp)) == KWD_SEL || key == KWD_NUM
				|| key == KWD_NAM || key == KWD_MSG) {
		if (ptr == NULL)
			ptr = talloc(sizeof(PRT_TBL));	/* メモリ確保 */
		else
			ptr = trealloc(ptr, sizeof(PRT_TBL) * (*n + 1));

		p = &ptr[*n];
		p->type = key;			/* パラメータ種類セット */
		p->guide = getstr(fp, 1);	/* 説明読み込み */
		if (strlen(p->guide) > LEN_PRTGUIDE)
			length_err(p->guide, LEN_PRTGUIDE);
		switch (key) {
		case KWD_SEL:	/* 選択項目 */
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

		(*n)++;
	}

	unget_flg = 1;

	return(ptr);
}

/*=======================================================================
 |
 |		選択項目読み込み
 |
 |	SEL_TBL	*read_sel(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 =======================================================================*/
static	SEL_TBL	*read_sel(FILE *fp)
{
	SEL_TBL	*ptr;
	char	*p, *sp;

	ptr = talloc(sizeof(SEL_TBL));	/* メモリ確保 */
	ptr->n = 0;
	while (p = getstr(fp, 0)) {	/* 選択項目読み込み */
		ptr = trealloc(ptr, sizeof(SEL_TBL)+ptr->n*sizeof(ptr->ptr));
		ptr->ptr[ptr->n][0] = p;
		if (p[strlen(p)-1] == '*')
			ptr->ptr[ptr->n][1] = &p[strlen(p)];
		else if ((sp = strchr(p, '(')) != NULL) {
			sp++;
			p = talloc(strlen(sp) + 1);
			strcpy(p, sp);
			if ((sp = strchr(p, ')')) != NULL)
				*sp = '\0';
			ptr->ptr[ptr->n][1] = p;
		} else
			ptr->ptr[ptr->n][1] = p;
		ptr->n++;
	}

	return(ptr);
}

/*=======================================================================
 |
 |		数値項目読み込み
 |
 |	NUM_TBL	*read_num(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 =======================================================================*/
static	NUM_TBL	*read_num(FILE *fp)
{
	NUM_TBL	*ptr;
	char	*p;

	ptr = talloc(sizeof(NUM_TBL));		/* メモリ確保 */
	if ((p = getstr(fp, 0)) != NULL) {	/* 最小値読み込み */
		ptr->min_flg = 1;		/* 最小値指定有り */
		ptr->min = atoi(p);		/* 最小値セット */
		if ((p = strstr(p, "SYC")) != NULL)
			ptr->min_syc = atoi(p + 3);	/* システム定数有り */
		else
			ptr->min_syc = 0;		/* システム定数無し */
	} else
		ptr->min_flg = 0;		/* 最小値指定無し */

	if ((p = getstr(fp, 0)) != NULL) {	/* 最大値読み込み */
		ptr->max_flg = 1;		/* 最大値指定有り */
		ptr->max = atoi(p);		/* 最大値セット */
		if ((p = strstr(p, "SYC")) != NULL)
			ptr->max_syc = atoi(p + 3);	/* システム定数有り */
		else
			ptr->max_syc = 0;		/* システム定数無し */
	} else
		ptr->max_flg = 0;		/* 最大値指定無し */

	ptr->prmid = getstr(fp, 0);	/* パラメータＩＤ読み込み */
	ptr->sel = getstr(fp, 0);	/* 選択項目読み込み */

	return(ptr);
}

/*=======================================================================
 |
 |		名称項目読み込み
 |
 |	NAM_TBL	*read_nam(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 =======================================================================*/
static	NAM_TBL	*read_nam(FILE *fp)
{
	NAM_TBL	*ptr;
	char	*p;

	ptr = talloc(sizeof(NAM_TBL));	/* メモリ確保 */

	p = getstr(fp, 0);	/* 最大文字数読み込み */
	ptr->length = (p != NULL) ? atoi(p) : 0;
	ptr->sel = getstr(fp, 0);	/* 選択項目読み込み */
	ptr->ext1 = getstr(fp, 0);	/* 拡張子１読み込み */
	ptr->ext2 = getstr(fp, 0);	/* 拡張子２読み込み */

	return(ptr);
}

/*=======================================================================
 |
 |		説明項目読み込み
 |
 |	GUD_TBL	*read_gud(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 =======================================================================*/
static	GUD_TBL	*read_gud(FILE *fp)
{
	GUD_TBL	*ptr;
	char	*p;

	ptr = talloc(sizeof(GUD_TBL));	/* メモリ確保 */

	p = getstr(fp, 0);		/* 最大文字数読み込み */
	ptr->length = (p != NULL) ? atoi(p) : 0;

	return(ptr);
}

/*=======================================================================
 |
 |		キーワードチェック
 |
 |	int	check_key(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 =======================================================================*/
static	int	check_key(FILE *fp)
{
	char	buf[80];
	int	st;

	switch (gettok(fp, buf)) {
	case EOF:
		st = EOF;
		break;
	case 1:		/* キーワード */
		if (strcmp(buf, "@TTL") == 0)
			st = KWD_TTL;
		else if (strcmp(buf, "@COM") == 0)
			st = KWD_COM;
		else if (strcmp(buf, "@PRM") == 0)
			st = KWD_PRM;
		else if (strcmp(buf, "@SEL") == 0)
			st = KWD_SEL;
		else if (strcmp(buf, "@NUM") == 0)
			st = KWD_NUM;
		else if (strcmp(buf, "@NAM") == 0)
			st = KWD_NAM;
		else if (strcmp(buf, "@MSG") == 0)
			st = KWD_MSG;
		else	
			syntax_err();
		break;
	default:
		syntax_err();
	}

	return(st);
}

/*=======================================================================
 |
 |		文字列読み込み
 |
 |	char	*getstr(fp, flag)
 |
 |		FILE	*fp;		ファイルポインタ
 |		int	flag;		０：省略可　１：省略不可
 |
 =======================================================================*/
static	char	*getstr(FILE *fp, int flag)
{
	char	buf[80];
	char	*p;

	switch (gettok(fp, buf)) {
	case 0:		/* 省略 */
	case EOF:
		if (flag != 0)
			syntax_err();
		p = NULL;
		break;
	case 1:		/* キーワード */
		if (flag != 0)
			syntax_err();
		unget_flg = 1;
		p = NULL;
		break;
	case 2:		/* その他の文字 */
		p = talloc(strlen(buf)+1);
		strcpy(p, buf);
		break;
	}
	return(p);
}

/*=======================================================================
 |
 |		トークン取り出し
 |
 |	int	gettok(fp, bufp)
 |
 |		FILE	*fp;		ファイルポインタ
 |		char	*bufp;		トークン格納エリア
 |
 =======================================================================*/
static	int	gettok(FILE *fp, char *bufp)
{
	int	mode, ch, loop_sw;
	char	*p;
	static	char	sv_buf[80];
	static	int	sv_mode;

	if (unget_flg) {
		unget_flg = 0;
		strcpy(bufp, sv_buf);
		return(sv_mode);
	}

	mode = 0;
	p = bufp;
	loop_sw = 1;
	while (loop_sw) {
		ch = fgetchr(fp);
		switch (ch) {
		case EOF:
			mode = EOF;
			loop_sw = 0;
			break;
		case '@':
			if (mode == 0)
				mode = 1;
			*p++ = ch;
			break;
		case ' ':
		case '\t':
			if (mode == 1)
				loop_sw = 0;
			else if (mode == 2)
				*p++ = ch;
			break;
		case ',':
			loop_sw = 0;
			break;
		case '\n':
			if (mode != 0)
				loop_sw = 0;
			break;
		default:
			if (mode == 0)
				mode = 2;
			*p++ = ch;
			break;
		}
	}

	for ( ;p != bufp; p--) {
		if (*(p - 1) != ' ' && *(p - 1) != '\t')
			break;
	}
	*p = '\0';

	strcpy(sv_buf, bufp);
	sv_mode = mode;

	return(mode);
}

/*=======================================================================
 |
 |		１文字読み込み（コメント読みとばし）
 |
 |	int	fgetchr(fp)
 |
 |		FILE	*fp;		ファイルポインタ
 |
 =======================================================================*/
static	int	fgetchr(FILE *fp)
{
	int	ch, mode;
	static	char	*p = line_buf;

	mode = 0;
	for (;;) {
		if ((ch = *p) == '\0') {
			if (fgets(line_buf, sizeof(line_buf), fp) == NULL)
				return(EOF);
			p = line_buf;
			line_no++;
			continue;
		}
		p++;
			
		if (mode == 0 && ch == '/')
			mode = 1;
		else if (mode == 1 && ch == '*')
			mode = 2;
		else if (mode == 2 && ch == '*')
			mode = 3;
		else if (mode == 3 && ch == '/')
			mode = 0;
		else if (mode == 0)
			break;
		else if (mode == 1) {
			p--;
			ch = '/';
			break;
		} else if (mode == 3)
			mode = 2;
	}

	return(ch);
}

/*=======================================================================
 |
 |		メモリ確保（エラーチェック有り）
 |
 |	void	*talloc(size)
 |
 |		int	size;		確保するメモリサイズ
 |
 =======================================================================*/
static	void	*talloc(int size)
{
	void	*p;

	if ((p = malloc(size)) == NULL) {
		printf("メモリの確保に失敗しました\n");
		exit(1);
	}
	return(p);
}

/*=======================================================================
 |
 |		メモリサイズ変更（エラーチェック有り）
 |
 |	void	*trealloc(p, size)
 |
 |		void	*p;		変更前のメモリポインタ
 |		int	size;		変更するメモリサイズ
 |
 =======================================================================*/
static	void	*trealloc(void *p, int size)
{
	if ((p = realloc(p, size)) == NULL) {
		printf("メモリの確保に失敗しました\n");
		exit(1);
	}
	return(p);
}

/*=======================================================================
 |
 |		シンタックスエラー処理
 |
 |	void	syntax_err()
 |
 =======================================================================*/
static	void	syntax_err()
{
	printf("\n記述に誤りがあります : (行番号 %d)\n%s\n", line_no, line_buf);
	printf("\n異常終了しました\n");
	exit(1);
}

/*=======================================================================
 |
 |		長さオーバエラー処理
 |
 |	void	length_err()
 |
 =======================================================================*/
static	void	length_err(char *p, int len)
{
	printf("\n文字列が長すぎます : (行番号 %d)\n", line_no);
	printf("【%s】 (%d文字以下にして下さい)\n", p, len);
	printf("\n異常終了しました\n");
	exit(1);
}

/*=======================================================================
 |
 |		ファイル書き込み
 |
 |	void	write_file(fp, ptr)
 |
 |		FILE	*fp;		ファイルポインタ
 |		CMS_TBL	*ptr;		タイトルデータ	
 |
 =======================================================================*/
static	void	write_file(FILE *fp, CMS_TBL *ptr)
{
	if (ptr != NULL) {
		outstr(fp, ptr->title);		/* タイトル書き込み */
		outstr(fp, ptr->guide);		/* 説明書き込み */
		outnum(fp, ptr->n);		/* コマンド個数書き込み */
		write_cmd(fp, ptr->ptr, ptr->n); /* コマンドデータ書き込み */
	}
}

/*=======================================================================
 |
 |		コマンドデータ書き込み
 |
 |	void	write_cmd(fp, ptr, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		CMD_TBL	*ptr;		コマンドデータ
 |		int	n;		コマンド個数
 |
 =======================================================================*/
static	void	write_cmd(FILE *fp, CMD_TBL *ptr, int n)
{
	while (n--) {
		outstr(fp, ptr->name);	/* コマンド名書き込み */
		outstr(fp, ptr->guide);	/* 説明書き込み */
		outnum(fp, ptr->n);	/* パラメータ種類個数書き込み */
		write_prm(fp, ptr->ptr, ptr->n); /* パラメータ種類書き込み */
		ptr++;
	}
}

/*=======================================================================
 |
 |		パラメータ種類データ書き込み
 |
 |	void	write_prm(fp, ptr, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		PRM_TBL	*ptr;		パラメータ種類データ
 |		int	n;		パラメータ種類個数
 |
 =======================================================================*/
static	void	write_prm(FILE *fp, PRM_TBL *ptr, int n)
{
	while (n--) {
		outstr(fp, ptr->name);		/* パラメータ名書き込み */
		outstr(fp, ptr->guide);		/* 説明書き込み */
		outnum(fp, ptr->n);		/* パラメータ数書き込み */
		write_prt(fp, ptr->ptr, ptr->n); /* パラメータデータ書き込み */
		ptr++;
	}
}

/*=======================================================================
 |
 |		パラメータデータ書き込み
 |
 |	void	write_prt(fp, ptr, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		PRT_TBL	*ptr;		パラメータデータ
 |		int	n;		パラメータ個数
 |
 =======================================================================*/
static	void	write_prt(FILE *fp, PRT_TBL *ptr, int n)
{
	while (n--) {
		outnum(fp, ptr->type);		/* パラメータ種類書き込み */
		outstr(fp, ptr->guide);		/* 説明書き込み */
		switch (ptr->type) {
		case KWD_SEL:	/* 説明項目 */
			write_sel(fp, ptr->ptr.sel);
			break;
		case KWD_NUM:	/* 数値項目 */
			write_num(fp, ptr->ptr.num);
			break;
		case KWD_NAM:	/* 名称項目 */
			write_nam(fp, ptr->ptr.nam);
			break;
		case KWD_MSG:	/* 説明項目 */
			write_gud(fp, ptr->ptr.gud);
			break;
		}
		ptr++;
	}
}

/*=======================================================================
 |
 |		選択項目データ書き込み
 |
 |	void	write_sel(fp, ptr)
 |
 |		FILE	*fp;		ファイルポインタ
 |		SEL_TBL	*ptr;		選択項目データ
 |
 =======================================================================*/
static	void	write_sel(FILE *fp, SEL_TBL *ptr)
{
	int	i;

	outnum(fp, ptr->n);		/* 選択項目数書き込み */
	for (i = 0; i < ptr->n; i++) {
		outstr(fp, ptr->ptr[i][0]); /* 選択項目（表示）書き込み */
		outstr(fp, ptr->ptr[i][1]); /* 選択項目（入力）書き込み */
	}
}

/*=======================================================================
 |
 |		数値項目データ書き込み
 |
 |	void	write_num(fp, ptr)
 |
 |		FILE	*fp;		ファイルポインタ
 |		NUM_TBL	*ptr;		数値項目データ
 |
 =======================================================================*/
static	void	write_num(FILE *fp, NUM_TBL *ptr)
{
	outnum(fp, ptr->min_flg);	/* 最小値指定フラグ書き込み */
	outnum(fp, ptr->min);		/* 最小値書き込み */
	outnum(fp, ptr->min_syc);	/* 最小値システム定数書き込み */
	outnum(fp, ptr->max_flg);	/* 最大値指定フラグ書き込み */
	outnum(fp, ptr->max);		/* 最大値書き込み */
	outnum(fp, ptr->max_syc);	/* 最大値システム定数書き込み */
	outstr(fp, ptr->prmid);		/* パラメータＩＤ書き込み */
	outstr(fp, ptr->sel);		/* 選択項目書き込み */
}

/*=======================================================================
 |
 |		名称項目データ書き込み
 |
 |	void	write_nam(fp, ptr)
 |
 |		FILE	*fp;		ファイルポインタ
 |		NAM_TBL	*ptr;		名称項目データ
 |
 =======================================================================*/
static	void	write_nam(FILE *fp, NAM_TBL *ptr)
{
	outnum(fp, ptr->length);	/* 最大文字数書き込み */
	outstr(fp, ptr->sel);		/* 選択項目書き込み */
	outstr(fp, ptr->ext1);		/* 拡張子１書き込み */
	outstr(fp, ptr->ext2);		/* 拡張子２書き込み */
}

/*=======================================================================
 |
 |		説明項目データ書き込み
 |
 |	void	write_gud(fp, ptr)
 |
 |		FILE	*fp;		ファイルポインタ
 |		GUD_TBL	*ptr;		説明項目データ
 |
 =======================================================================*/
static	void	write_gud(FILE *fp, GUD_TBL *ptr)
{
	outnum(fp, ptr->length);	/* 最大文字数書き込み */
}

/*=======================================================================
 |
 |		文字列書き込み
 |
 |	void	outstr(fp, p)
 |
 |		FILE	*fp;		ファイルポインタ
 |		char	*p;		文字列
 |
 =======================================================================*/
static	void	outstr(FILE *fp, char *p)
{
	int	len;

	if (p != NULL) {
		len = strlen(p);		/* 長さ取り出し */
		outnum(fp, len);		/* 長さ書き込み */
		fwrite(p, 1, strlen(p), fp);	/* 文字列書き込み */
	} else 
		outnum(fp, -1);
}

/*=======================================================================
 |
 |		数値書き込み
 |
 |	void	outnum(fp, n)
 |
 |		FILE	*fp;		ファイルポインタ
 |		int	n;		数値
 |
 =======================================================================*/
static	void	outnum(FILE *fp, int n)
{
	fwrite(&n, sizeof(int), 1, fp);
}
