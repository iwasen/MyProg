/************************************************************************
 *									*
 *		ＩＳＡＭライブラリ					*
 *									*
 *		名称		: コマンド入力版サンプルプログラム	*
 *		ファイル名	: csample.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>
#include <conio.h>
#include "isam.h"

#define	ESC	0x1b

static	void	err_proc(int);

static	void	t_search(DB *);
static	void	t_store(DB *);
static	void	t_update(DB *);
static	void	t_delete(DB *);
static	void	t_list(DB *);
static	void	t_ichiran(DB *);
static	void	t_info(DB *);
static	void	t_info_dbf(DBF *);
static	void	t_info_idx(IDX *);
static	void	t_locate(DB *);
static	void	t_etc(DB *);
static	void	t_sort(DB *);
static	void	t_pack(DB *);
static	void	t_reindex(DB *);
static	void	t_chgidx(DB *);
static	void	t_count(DB *);
static	void	t_recall(DB *);
static	void	del_mode(void);
static	void	t_flush(DB *);
static	DB	*t_dbf_create(char *);
static	void	t_idx_create(DB *);
static	void	display(DB *);
static	void	display2(DB *);
static	int	jikkou(void);
static	void	err_proc(int);
static	void	title(void);
static	void	beep(void);
static	void	stop_handler(void);

static	DB	*sv_dbp;

/*=======================================================================
 |
 |		メイン処理
 |
 |	int	main(argc, argv)
 |
 |		int	argc;		パラメータ数
 |		char	*argv[];	コマンドラインパラメータ
 |
 =======================================================================*/
main(int argc, char *argv[])
{
	char	dbf_file_name[32];
	char	idx_file_name[32];
	DB	*dbp;
	int	loop_sw, ch;

	/* パラメータ数チェック */
	if (argc < 2) {
		printf("使い方：ＣＳＡＭＰＬＥ　データファイル名　［インデックスファイル名　．．．］\n");
		exit(1);
	}

	signal(SIGINT, stop_handler);	/* ＳＴＯＰキーで処理中止 */

	title();		/* タイトル表示 */

	db_err_handler(err_proc);	/* エラー処理ルーチン設定 */

	/* ｄｂｆファイルオープン */
	argc--;
	strcpy(dbf_file_name, *++argv);
	if (strchr(dbf_file_name, '.') == NULL)
		strcat(dbf_file_name, ".DBF");
	if ((dbp = db_open(dbf_file_name)) == 0) {
		for (;;) {
			printf("\nデータファイルがありません。作成しますか (Y/N) ==>");
			ch = getch();
			printf("%c\n", ch);
			if (ch == 'Y' || ch == 'y')
				break;
			if (ch == 'N' || ch == 'n')
				exit(0);
		}
		if ((dbp = t_dbf_create(dbf_file_name)) == 0) {
			exit(1);
		}
	}
	sv_dbp = dbp;

	/* インデックスファイルオープン */
	while (--argc) {
		strcpy(idx_file_name, *++argv);
		if (strchr(idx_file_name, '.') == NULL)
			strcat(idx_file_name, ".NDX");
		if (db_index(dbp, idx_file_name) == 0) {
			printf("file open error (%s)\n", idx_file_name);
			db_close(dbp);
			exit(1);
		}
	}

	display2(dbp);	/* カレントレコード表示 */

	loop_sw = 1;
	while (loop_sw) {
		printf("\n===== メニュー =====\n");
		printf("1.検索  2.追加  3.更新  4.削除  5.表示  6.位置  7.その他  8.終了 ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':		/* 検索 */
			t_search(dbp);
			break;
		case '2':		/* 追加 */
			t_store(dbp);
			break;
		case '3':		/* 更新 */
			t_update(dbp);
			break;
		case '4':		/* 削除 */
			t_delete(dbp);
			break;
		case '5':		/* 表示 */
			t_list(dbp);
			break;
		case '6':		/* 位置 */
			t_locate(dbp);
			break;
		case '7':		/* その他 */
			t_etc(dbp);
			break;
		case '8':		/* 終了 */
			loop_sw = 0;
			break;
		default:
			beep();
		}
	}

	db_close(dbp);

	return(0);
}

/*=======================================================================
 |
 |		検索処理
 |
 |	void	t_search(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_search(DB *dbp)
{
	IDX	*ip;
	char	buf[40];

	printf("\n===== 検索 =====\n");

	ip = dbp->ip[dbp->master];

	if (ip == NULL) {
		printf("インデックスファイルがありません\n");
		return;
	}

	/* 検索文字列入力 */
	printf("%s ==>", ip->ihp->index);
	scanf("%s", buf);

	db_search(dbp, buf, strlen(buf));	/* 検索 */

	display2(dbp);		/* カレントレコード表示 */
}

/*=======================================================================
 |
 |		追加処理
 |
 |	void	t_store(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_store(DB *dbp)
{
	DBF	*dp;
	register int	i;
	FIELD	*flp;
	char	ibuf[256], *ibp, buf[100], buf2[100], fmt[20];

	printf("\n===== 追加 =====\n");

	dp = dbp->dp;

	/* レコードバッファクリア */
	memset(ibuf, ' ', dp->dhp->l_rec - 1);

	/* フィールド情報入力 */
	ibp = ibuf;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		printf("%-10s(%d) ==>", flp->name, flp->l_field);
		scanf("%s", buf);

		if (flp->type != 'N')
			memcpy(ibp, buf, strlen(buf));
		else {
			sprintf(fmt, "%%%d.%dlf", flp->l_field, flp->l_dec);
			sprintf(buf2, fmt, atof(buf));
			memcpy(ibp, buf2, flp->l_field);
		}

		ibp += flp->l_field;
	}

	if (i == dp->n_field) {
		if (jikkou()) {
			db_store(dbp, ibuf);	/* レコード追加 */
			display2(dbp);		/* カレントレコード表示 */
		}
	}
}

/*=======================================================================
 |
 |		更新処理
 |
 |	void	t_update(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_update(DB *dbp)
{
	DBF	*dp;
	register int	i;
	FIELD	*flp;
	char	ibuf[256], *ibp, buf[100], buf2[100], fmt[20];

	printf("\n===== 更新 =====\n");

	dp = dbp->dp;

	/* レコードバッファクリア */
	memset(ibuf, ' ', dp->dhp->l_rec - 1);

	/* フィールド情報入力 */
	ibp = ibuf;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		printf("%-10s(%d) ==>", flp->name, flp->l_field);
		scanf("%s", buf);

		if (flp->type != 'N')
			memcpy(ibp, buf, strlen(buf));
		else {
			sprintf(fmt, "%%%d.%dlf", flp->l_field, flp->l_dec);
			sprintf(buf2, fmt, atof(buf));
			memcpy(ibp, buf2, flp->l_field);
		}
		
		ibp += flp->l_field;
	}

	if (i == dp->n_field) {
		if (jikkou()) {
			db_update(dbp, ibuf);	/* レコード更新 */
			display2(dbp);		/* カレントレコード表示 */
		}
	}
}

/*=======================================================================
 |
 |		削除処理
 |
 |	void	t_delete(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_delete(DB *dbp)
{
	printf("\n===== 削除 =====\n");

	if (jikkou()) {
		db_delete(dbp);		/* レコード削除 */
		display2(dbp);		/* カレントレコード表示 */
	}
}

/*=======================================================================
 |
 |		表示処理
 |
 |	void	t_list(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_list(DB *dbp)
{
	int	ch;
	int	loop_sw;

	loop_sw = 1;
	while (loop_sw) {
		printf("\n===== 表示 =====\n");
		printf("1.一覧  2.情報 ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':		/* 一覧 */
			t_ichiran(dbp);
			break;
		case '2':		/* 情報 */
			t_info(dbp);
			break;
		case ESC:		/* 戻る */
			loop_sw = 0;
			break;
		default:
			beep();
		}
	}
}

/*=======================================================================
 |
 |		一覧表示処理
 |
 |	void	t_ichiran(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_ichiran(DB *dbp)
{
	int	ch, loop_sw;

	printf("\n===== 一覧 =====\n");

	loop_sw = 1;
	while (loop_sw) {
		printf("1.先頭レコードから表示  2.カレントレコードから表示 ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':	/* 先頭レコードから表示 */
			db_top(dbp);
			loop_sw = 0;
			break;
		case '2':	/* カレントレコードから表示 */
			loop_sw = 0;
			break;
		case ESC:	/* 戻る */
			return;
		default:
			beep();
		}
	}

	for (;;) {
		display(dbp);		/* １レコード表示 */

		if (db_eof(dbp))
			break;

		db_skip(dbp, 1L);	/* 次レコード */

		if (kbhit()) {
			if (ch = getch()) {
				if (ch == ESC)
					break;
				else if (ch == ' ') {
					ch = getch();
					if (ch == ESC)
						break;
				}
			}
		}
	}
}

/*=======================================================================
 |
 |		情報表示処理
 |
 |	void	t_info(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_info(DB *dbp)
{
	DBF	*dp;
	IDX	*ip;
	int	ch, n;

	printf("\n===== 情報 =====\n");

	dp = dbp->dp;

	/* ファイル名表示 */
	n = 1;
	printf("0.%s\n", dp->file_name);
	for (n = 1; n <= dbp->n_idx; n++) {
		ip = dbp->ip[n];
		printf("%d.%s\n", n, ip->file_name);
	}

	if (n == 1)
		t_info_dbf(dp);		/* ｄｂｆファイル情報表示 */
	else {
		for (;;) {
			printf("==>");
			ch = getch();
			printf("%c\n", ch);
			if (ch == ESC)
				break;
			else if (ch == '0') {
				t_info_dbf(dp);	/* ＤＢＦファイル */
				break;
			} else if (ch >= '1' && ch < n+'0') {
				t_info_idx(dbp->ip[ch-'0']);/* ＮＤＸファイル */
				break;
			} else
				beep();
		}
	}
}

/*=======================================================================
 |
 |		ｄｂｆファイル情報表示処理
 |
 |	void	t_info_dbf(dp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |
 =======================================================================*/
static	void	t_info_dbf(DBF *dp)
{
	DBF_H	*dhp;
	FIELD	*flp;
	register int	i;

	dhp = dp->dhp;
	printf("*** %s *** \n", dp->file_name);
	printf("最終更新日 : %2d/%02d/%02d\n", dhp->date[0], dhp->date[1], dhp->date[2]);
	printf("レコード数 : %ld\n", dhp->n_rec);
	printf("レコード長 : %d\n\n", dhp->l_rec);
	printf("フィールド名　形式　桁数　小数位桁数\n");
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		printf(" %-11s   %c     %2d     %2d\n",
				flp->name,
				flp->type,
				flp->l_field,
				flp->l_dec);
	}
}

/*=======================================================================
 |
 |		インデックスファイル情報表示処理
 |
 |	void	t_info_idx(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
static	void	t_info_idx(IDX *ip)
{
	IDX_H	*ihp;

	ihp = ip->ihp;
	printf("*** %s *** \n", ip->file_name);
	printf("ルートＮｏ : %ld\n", ihp->root);
	printf("ブロック数 : %ld\n", ihp->n_block);
	printf("キー長     : %d\n", ihp->l_key);
	printf("次数       : %d\n", ihp->order);
	printf("データ種別 : %d\n", ihp->type);
	printf("レコード長 : %d\n", ihp->l_rec);
	printf("表現式     : %s\n", ihp->index);
}

/*=======================================================================
 |
 |		位置処理
 |
 |	void	t_locate(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_locate(DB *dbp)
{
	int	loop_sw, ch;
	char	buf[6];

	loop_sw = 1;
	while (loop_sw) {
		printf("\n===== 位置 =====\n");
		printf("1.先頭  2.最後  3.指定ﾚｺｰﾄﾞ  4.前後移動 ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':		/* 先頭 */
			db_top(dbp);
			display2(dbp);
			break;
		case '2':		/* 最後 */
			db_bottom(dbp);
			display2(dbp);
			break;
		case '3':		/* 指定ﾚｺｰﾄﾞ */
			printf("レコード番号 ==>");
			scanf("%s", buf);
			db_set(dbp, atol(buf));
			display2(dbp);
			break;
		case '4':		/* 前後移動 */
			printf("移動レコード数 ==>");
			scanf("%s", buf);
			db_skip(dbp, atol(buf));
			display2(dbp);
			break;
		case ESC:		/* 戻る */
			loop_sw = 0;
			break;
		default:
			break;
		}
	}
}

/*=======================================================================
 |
 |		その他処理
 |
 |	void	t_etc(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_etc(DB *dbp)
{
	int	ch;
	int	loop_sw;

	loop_sw = 1;
	while (loop_sw) {
		printf("\n===== その他 =====\n");
		printf("1.ソート  2.パック      3.ｲﾝﾃﾞｯｸｽ再構築  4.ｲﾝﾃﾞｯｸｽ切替  5.カウント\n");
		printf("6.復元    7.削除モード  8.フラッシュ     9.ｲﾝﾃﾞｯｸｽ作成  ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':		/* ソート */
			t_sort(dbp);
			break;
		case '2':		/* パック */
			t_pack(dbp);
			break;
		case '3':		/* インデックス再構築 */
			t_reindex(dbp);
			break;
		case '4':		/* インデックス切替 */
			t_chgidx(dbp);
			break;
		case '5':		/* カウント */
			t_count(dbp);
			break;
		case '6':		/* 復元 */
			t_recall(dbp);
			break;
		case '7':		/* 削除モード */
			del_mode();
			break;
		case '8':		/* フラッシュ */
			t_flush(dbp);
			break;
		case '9':		/* ｲﾝﾃﾞｯｸｽ作成 */
			t_idx_create(dbp);
			break;
		case ESC:		/* 戻る */
			loop_sw = 0;
			break;
		default:
			beep();
		}
	}
}

/*=======================================================================
 |
 |		ソート処理
 |
 |	void	t_sort(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_sort(DB *dbp)
{
	DBF	*dp;
	char	file_name[10], key[100];

	printf("\n===== ソート =====\n");

	dp = dbp->dp;

	/* 出力ファイル名入力 */
	printf("出力ファイル名 ==>");
	scanf("%s", file_name);

	/* キー入力 */
	printf("キー ==>");
	scanf("%s", key);

	if (jikkou()) {
		printf("実行中\n");
		dbf_sort(dp, file_name, key);	/* ソート */
	}
}

/*=======================================================================
 |
 |		パック処理
 |
 |	void	t_pack(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_pack(DB *dbp)
{
	printf("\n===== パック =====\n");

	if (jikkou()) {
		printf("実行中\n");
		db_pack(dbp);		/* パック処理 */
		display2(dbp);		/* カレントレコード表示 */
	}
}

/*=======================================================================
 |
 |		インデックス再構築処理
 |
 |	void	t_reindex(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_reindex(DB *dbp)
{
	printf("\n===== ｲﾝﾃﾞｯｸｽ再構築 =====\n");

	if (jikkou()) {
		printf("実行中\n");
		db_reindex(dbp);	/* インデックス再構築 */
		display2(dbp);		/* カレントレコード表示 */
	}
}

/*=======================================================================
 |
 |		インデックス切り替え処理
 |
 |	void	t_chgidx(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_chgidx(DB *dbp)
{
	IDX	*ip;
	int	ch;
	int	n;

	if (dbp->n_idx == 0)
		return;

	/* インデックスファイル名表示 */
	printf("0.なし\n");
	for (n = 1; n <= dbp->n_idx; n++) {
		ip = dbp->ip[n];
		printf("%d.%s\n", n, ip->file_name);
	}

	for (;;) {
		printf("==>");
		ch = getch();
		printf("%c\n", ch);
		if (ch == ESC)
			break;
		else if (ch >= '0' && ch < n+'0') {
			db_chgidx(dbp, ch - '0'); /* インデックス切り替え */
			display2(dbp);		/* カレントレコード表示 */
			break;
		} else
			beep();
	}
}

/*=======================================================================
 |
 |		カウント処理
 |
 |	void	t_count(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_count(DB *dbp)
{
	IDX	*ip;
	char	buf[40];
	long	n;

	printf("\n===== カウント =====\n");

	ip = dbp->ip[dbp->master];

	if (ip == NULL)
		return;

	/* キーデータ入力 */
	printf("%s ==>", ip->ihp->index);
	scanf("%s", buf);

	n = db_count(dbp, buf, strlen(buf));	/* カウント処理 */
	printf("%5ld 件\n", n);			/* 件数表示 */

	display2(dbp);				/* カレントレコード表示 */
}

/*=======================================================================
 |
 |		復元処理
 |
 |	void	t_recall(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_recall(DB *dbp)
{
	printf("\n===== 復元 =====\n");

	if (jikkou()) {
		db_recall(dbp);		/* 復元処理 */
		display2(dbp);		/* カレントレコード表示 */
	}
}

/*=======================================================================
 |
 |		削除モード処理
 |
 |	void	del_mode(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	del_mode()
{
	int	ch;

	printf("\n===== 削除モード =====\n");

	printf("1.削除レコード有効  2.削除レコード無視 ==>");
	ch = getch();
	printf("%c\n", ch);
	if (ch == '1')
		set_deleted(0);		/* 削除レコード有効 */
	else if (ch == '2')
		set_deleted(1);		/* 削除レコード無効 */
}

/*=======================================================================
 |
 |		フラッシュ処理
 |
 |	void	t_flush(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_flush(DB *dbp)
{
	printf("\n===== フラッシュ =====\n");

	if (jikkou()) {
		db_flush(dbp);		/* フラッシュ処理 */
		display2(dbp);		/* カレントレコード表示 */
	}
}

/*=======================================================================
 |
 |		ｄｂｆファイル作成処理
 |
 |	DB	*t_dbf_create(file_name)
 |
 |		char	*file_name;	ｄｂｆファイル名
 |
 =======================================================================*/
static	DB	*t_dbf_create(char *file_name)
{
	DB	*dbp;
	char	buf[10];
	int	i, ch, loop_sw;
	DBF_I	dbfi[20];

	printf("\n===== ﾃﾞｰﾀﾍﾞｰｽ作成 =====\n");

	/* データファイル名入力 */
	printf("ﾃﾞｰﾀ ﾌｧｲﾙ名 %s\n", file_name);

	for (i = 0; ; i++) {
		/* フィールド名入力 */
		printf("フィールド名（終了:CTRL-Z） ==>");
		dbfi[i].name[0] = '\0';
		scanf("%s", dbfi[i].name);
		if (strlen(dbfi[i].name) == 0)
			break;

		/* フィールド形式入力 */
		printf("形式 (C,N,D,L) ==>");
		loop_sw = 1;
		while (loop_sw) {
			ch = getch();
			switch (ch) {
			case 'C':
			case 'c':
				dbfi[i].type = 'C';
				printf("C\n");
				loop_sw = 0;
				break;
			case 'N':
			case 'n':
				dbfi[i].type = 'N';
				printf("N\n");
				loop_sw = 0;
				break;
			case 'D':
			case 'd':
				dbfi[i].type = 'D';
				printf("D\n");
				loop_sw = 0;
				break;
			case 'L':
			case 'l':
				dbfi[i].type = 'L';
				printf("L\n");
				loop_sw = 0;
				break;
			default:
				beep();
			}
		}

		/* フィールド長入力 */
		printf("長さ ==>");
		scanf("%s", buf);
		dbfi[i].l_field = atoi(buf);

		/* 小数部桁数入力 */
		if (dbfi[i].type == 'N') {
			printf("小数 ==>");
			scanf("%s", buf);
			dbfi[i].l_dec = atoi(buf);
		} else
			dbfi[i].l_dec = 0;
	}

	if (i != 0)
		dbp = db_create(file_name, dbfi, i);	/* データベース作成 */
	else
		dbp = NULL;

	return(dbp);
}

/*=======================================================================
 |
 |		インデックスファイル作成処理
 |
 |	void	t_idx_create(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	t_idx_create(DB *dbp)
{
	char	file_name[14], key[100];
	int	ch, uniq;

	printf("\n===== ｲﾝﾃﾞｯｸｽ作成 =====\n");

	/* インデックスファイル名入力 */
	printf("ｲﾝﾃﾞｯｸｽ ﾌｧｲﾙ名 ==>");
	scanf("%s", file_name);
	strcat(file_name, ".NDX");

	/* キー入力 */
	printf("キー ==>");
	scanf("%s", key);

	/* キーの重複入力 */
	printf("キーの重複 （０：有り　１：無し） ==>");
	for (;;) {
		ch = getch();
		printf("%c\n", ch);
		if (ch == '0') {
			uniq = 0;
			break;
		} else if (ch == '1') {
			uniq = 1;
			break;
		} else
			beep();
	}
	if (jikkou()) {
		/* インデックスファイル作成 */
		db_idx_create(dbp, file_name, key, uniq);

		display2(dbp);	/* カレントレコード表示 */
	}
}

/*=======================================================================
 |
 |		レコード表示（一覧形式）
 |
 |	void	display(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	display(DB *dbp)
{
	char	fmt[10];
	DBF	*dp;
	FIELD	*flp;
	int	i;

	dp = dbp->dp;
	flp = dp->flp;

	if (db_bof(dbp)) {
		printf("[BOF]\n");
	} else if (db_eof(dbp))  {
		printf("[EOF]\n");
	} else {
		dbf_read(dp, dbp->rno);
		printf("%c", *dp->rbp);
		for (i = 0; i < dp->n_field; i++) {
			sprintf(fmt, "%%.%ds", flp->l_field);
			printf(fmt, flp->fad);
			printf(" ");
			flp++;
		}
		printf("\n");
	}
}

/*=======================================================================
 |
 |		レコード表示
 |
 |	void	display2(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
static	void	display2(DB *dbp)
{
	DBF	*dp;
	register int	i;
	FIELD	*flp;
	char	fmt[20];

	printf("##### カレントレコード #####\n");

	dp = dbp->dp;

	if (db_bof(dbp)) {
		printf("[BOF]\n");
	} else if (db_eof(dbp)) {
		printf("[EOF]\n");
	} else {
		dbf_read(dp, dbp->rno);
		if (*dp->rbp == '*')
			printf("*削除済*\n");
		for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
			sprintf(fmt, "%%-10s [%%.%ds]\n", flp->l_field);
			printf(fmt, flp->name, flp->fad);
		}
	}
}

/*=======================================================================
 |
 |		実行確認
 |
 |	int	jikkou()
 |
 =======================================================================*/
static	int	jikkou()
{
	int	ch;

	printf("実行しますか (Y/N) ");
	for (;;) {
		ch = getch();
		if (ch == 'Y' || ch == 'y') {
			printf("%c\n", ch);
			return(1);
		} else if (ch == 'N' || ch == 'n') {
			printf("%c\n", ch);
			return(0);
		}
	}
}

/*=======================================================================
 |
 |		エラー表示処理
 |
 |	void	err_proc(err_code)
 |
 |		int	err_code;	エラーコード
 |
 =======================================================================*/
static	void	err_proc(int err_code)
{
	char	*msg;

	printf("エラーメッセージ ： ");
	switch (err_code) {
	case IE_MEMORY:
		msg = "メモリ不足";
		break;
	case IE_FOPEN:
		msg = "ファイルオープンエラー ";
		break;
	case IE_NOFILE:
		msg = "オープンするファイルがない";
		break;
	case IE_IDXOVER:
		msg = "インデックスファイルが多すぎる";
		break;
	case IE_TMPFILE:
		msg = "テンポラリファイルが作れない";
		break;
	case IE_NOKEY:
		msg = "指定されたキーが存在しない";
		break;
	case IE_DBLKEY:
		msg = "キーが重複している";
		break;
	case IE_RECNO:
		msg = "レコード番号が不正";
		break;
	case IE_EOF:
		msg = "ＢＯＦまたはＥＯＦ";
		break;
	case IE_STRFILE:
		msg = "構造ファイルが正しくない";
		break;
	case IE_READ:
		msg = "ファイルｒｅａｄエラー";
		break;
	case IE_WRITE:
		msg = "ファイルｗｒｉｔｅエラー";
		break;
	case IE_IDXKEY:
		msg = "インデックスのキー表現式が不正";
		break;
	default:
		msg = "??????????";
		break;
	}
	printf("%s\n", msg);
}

/*=======================================================================
 |
 |		タイトル表示処理
 |
 |	void	title()
 |
 =======================================================================*/
static	void	title()
{

	printf("*** ISAM LIBRARY SAMPLE PROGRAM ***\n");
}

/*=======================================================================
 |
 |		ＢＥＥＰ処理
 |
 |	void	beep()
 |
 =======================================================================*/
static	void	beep()
{
	putch(0x07);
}

/*=======================================================================
 |
 |		ＳＴＯＰキー処理
 |
 |	void	stop_handler()
 |
 =======================================================================*/
static	void	stop_handler(void)
{
	db_close(sv_dbp);
	printf("強制終了しました.\n");
	exit(1);
}
