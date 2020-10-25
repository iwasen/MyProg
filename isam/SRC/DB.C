/************************************************************************
 *									*
 *		ＩＳＡＭライブラリ					*
 *									*
 *		名称		: データベース操作			*
 *		ファイル名	: db.c					*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "isam.h"

/*	ローカル関数定義	*/
static	void	skip_deleted(DB *, int);
static	void	skip(DB *, long);
static	void	check_bof(DB *, long);
static	void	check_eof(DB *, long);

static	DB	*_db_top_ptr = 0;	/* データベース先頭ポインタ */

/*=======================================================================
 |
 |		データベースオープン
 |
 |	DB	*db_open(file_name)
 |
 |		char	*file_name;	データファイル名
 |
 |		返値			ＤＢポインタ
 |
 =======================================================================*/
DB	*db_open(char *file_name)
{
	DB	*dbp;

	/* データベース管理情報エリア確保 */
	if ((dbp = malloc(sizeof(DB))) == NULL) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dbp, 0, sizeof(DB));

	/* データファイル（ＤＢＦ）オープン */
	if ((dbp->dp = dbf_open(file_name)) == NULL) {
		db_close(dbp);
		return(NULL);
	}

	check_bof(dbp, 1L);	/* ＢＯＦチェック*/

	/* チェイン処理 */
	dbp->chain = _db_top_ptr;
	_db_top_ptr = dbp;

	return(dbp);
}

/*=======================================================================
 |
 |		データベース作成
 |
 |	DB	*db_create(dbf_file_name, dip, n_field)
 |
 |		char	*dbf_file_name;	データファイル名
 |		DBF_I	*dip;		データファイル情報
 |		int	n_field;	フィールド数
 |
 |		返値			ＤＢポインタ
 |
 =======================================================================*/
DB	*db_create(char *dbf_file_name, DBF_I *dip, int n_field)
{
	DBF	*dp;

	/* データファイル作成 */
	if ((dp = dbf_create(dbf_file_name, dip, n_field)) == NULL)
		return(NULL);

	dbf_close(dp);	/* データファイルクローズ */

	return(db_open(dbf_file_name));
}

/*=======================================================================
 |
 |		構造ファイルよりデータベース作成
 |
 |	DB	*db_create_from(dbf_file_name, str_file_name)
 |
 |		char	*dbf_file_name;	データファイル名
 |		char	*str_file_name;	構造ファイル名
 |
 |		返値			ＤＢポインタ
 |
 =======================================================================*/
DB	*db_create_from(char *dbf_file_name, char *str_file_name)
{
	DBF	*dp;
	FIELD	*flp_name, *flp_type, *flp_len, *flp_dec;
	DBF_I	*dip;
	register int	i, j;
	int	n_field;

	/* 構造ファイルオープン */
	if ((dp = dbf_open(str_file_name)) == NULL) {
		return(NULL);
	}

	/* "FIELD_NAME" フィールド取り出し */
	if ((flp_name = get_field(dp, "FIELD_NAME")) == 0) {
		db_err(IE_STRFILE);
		dbf_close(dp);
		return(NULL);
	}

	/* "FIELD_TYPE" フィールド取り出し */
	if ((flp_type = get_field(dp, "FIELD_TYPE")) == 0) {
		db_err(IE_STRFILE);
		dbf_close(dp);
		return(NULL);
	}

	/* "FIELD_LEN" フィールド取り出し */
	if ((flp_len = get_field(dp, "FIELD_LEN")) == 0) {
		db_err(IE_STRFILE);
		dbf_close(dp);
		return(NULL);
	}

	/* "FIELD_DEC" フィールド取り出し */
	if ((flp_dec = get_field(dp, "FIELD_DEC")) == 0) {
		db_err(IE_STRFILE);
		dbf_close(dp);
		return(NULL);
	}

	/* フィールド情報エリア確保 */
	n_field = (int)dp->dhp->n_rec;
	if ((dip = calloc(n_field, sizeof(DBF_I))) == NULL) {
		db_err(IE_MEMORY);
		dbf_close(dp);
		return(NULL);
	}

	/* フィールド情報セット */
	for (i = 0; i < n_field; i++) {
		dbf_read(dp, (long)(i + 1));
		for (j = 0; j < 10 && flp_name->fad[j] != ' '; j++)
			dip[i].name[j] = flp_name->fad[j];
		dip[i].name[j] = '\0';
		dip[i].type = flp_type->fad[0];
		dip[i].l_field = natoi(flp_len->fad, flp_len->l_field);
		dip[i].l_dec = natoi(flp_dec->fad, flp_dec->l_dec);
	}

	dbf_close(dp);	/* 構造ファイルクローズ */

	/* データファイル作成 */
	dp = dbf_create(dbf_file_name, dip, n_field);

	free(dip);	/* フィールド情報エリア解放 */

	if (dp == NULL)
		return(NULL);

	dbf_close(dp);	/* データファイルクローズ */

	return(db_open(dbf_file_name));
}

/*=======================================================================
 |
 |		データベースクローズ
 |
 |	void	db_close(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_close(DB *dbp)
{
	DB	**dbpp;
	register int	i;

	/* データファイルクローズ */
	if (dbp->dp != NULL)
		dbf_close(dbp->dp);

	/* インデックスファイルクローズ */
	for (i = 1; i <= dbp->n_idx; i++) {
		if (dbp->ip[i] != NULL)
			idx_close(dbp->ip[i]);
	}

	/* チェイン切り離し */
	for (dbpp = &_db_top_ptr; *dbpp != 0; dbpp = &(*dbpp)->chain) {
		if ((*dbpp) == dbp) {
			*dbpp = (*dbpp)->chain;
			break;
		}
	}

	free(dbp);	/* 管理情報エリア解放 */
}

/*=======================================================================
 |
 |		全データベースクローズ
 |
 |	void	db_close_all()
 |
 =======================================================================*/
void	db_close_all(void)
{
	while (_db_top_ptr != NULL)
		db_close(_db_top_ptr);
}

/*=======================================================================
 |
 |		ファイル強制掃き出し
 |
 |	void	db_flush(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_flush(DB *dbp)
{
	register int	i;

	dbf_flush(dbp->dp);	/* データファイルフラッシュ */

	/* インデックスファイルフラッシュ */
	for (i = 1; i <= dbp->n_idx; i++) {
		if (dbp->ip[i] != NULL)
			idx_flush(dbp->ip[i]);
	}
}

/*=======================================================================
 |
 |		全ファイル強制掃き出し
 |
 |	void	db_flush_all()
 |
 =======================================================================*/
void	db_flush_all(void)
{
	DB	*dbp;

	for (dbp = _db_top_ptr; dbp != NULL; dbp = dbp->chain)
		db_flush(dbp);
}

/*=======================================================================
 |
 |		インデックスファイルオープン
 |
 |	int	db_index(dbp, file_name)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		char	*file_name;	インデックスファイル名
 |
 |		返値			インデックスファイル番号
 |
 =======================================================================*/
int	db_index(DB *dbp, char *file_name)
{
	/* インデックスファイルオープン数チェック */
	if (dbp->n_idx >= MAX_IDX) {
		db_err(IE_IDXOVER);
		return(0);
	}

	/* インデックスファイルオープン */
	if ((dbp->ip[dbp->n_idx+1] = idx_open(file_name)) == NULL)
		return(0);


	dbp->n_idx++;		/* インデックスファイル数更新 */

	/* マスターインデックスファイル番号セット */
	if (dbp->master == 0) {
		dbp->master = dbp->n_idx;
		db_top(dbp);
	}

	return(dbp->n_idx);
}

/*=======================================================================
 |
 |		インデックスファイル作成
 |
 |	int	db_idx_create(dbp, file_name, key, uniq)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		char	*file_name;	インデックスファイル名
 |		char	*key;		キー
 |		int	uniq;		０：キーの重複を許す　１：許さない
 |
 |		返値			インデックスファイル番号
 |
 =======================================================================*/
int	db_idx_create(DB *dbp, char *file_name, char *key, int uniq)
{
	DBF	*dp;
	IDX	*ip;

	dp = dbp->dp;

	/* インデックスファイルオープン数チェック */
	if (dbp->n_idx >= MAX_IDX) {
		db_err(IE_IDXOVER);
		return(0);
	}

	/* インデックスファイル作成 */
	if ((ip = idx_create(file_name, key, uniq)) == NULL)
		return(0);

	idx_make(dp, ip);	/* インデックス構築 */

	dbp->ip[dbp->n_idx+1] = ip;	/* インデックスポインタセーブ */

	dbp->n_idx++;		/* インデックスファイル数更新 */

	/* マスターインデックスファイル番号セット */
	if (dbp->master == 0) {
		dbp->master = dbp->n_idx;
		db_top(dbp);
	}

	return(dbp->n_idx);
}

/*=======================================================================
 |
 |		マスターインデックス切り替え
 |
 |	void	db_chgidx(dbp, n)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		int	n;		インデックスファイル番号
 |
 =======================================================================*/
void	db_chgidx(DB *dbp, int n)
{
	dbp->master = n;	/* マスターインデックス番号セット */
	db_top(dbp);		/* 先頭レコード */
}

/*=======================================================================
 |
 |		インデックスファイルによる検索
 |
 |	void	db_search(dbp, key, len)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		char	*key;		検索する文字列
 |		int	len;		検索する文字列の長さ
 |
 =======================================================================*/
void	db_search(DB *dbp, char *key, int len)
{
	DBF	*dp;
	IDX	*ip;
	double	a;
	long	rno;

	/* ポインタセット */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	/* インデックスファイルがなければ何もしない */
	if (ip == NULL)
		return;

	if (ip->ihp->type != 0) {
		/* 文字列を数値に変換 */
		a = natof(key, len);
		key = (char *)&a;
		len = 8;
	}

	rno = idx_search(ip, key, len);		/* 検索 */

	if (_db_set_deleted && rno != 0L) {
		/* 削除レコードをスキップする */
		while (dbf_check_deleted(dp, rno)) {
			if ((rno = idx_next(ip)) == 0)
				break;
			if (idx_compare(ip, key, len) != 0) {
				rno = 0;
				break;
			}
		}
	}

	check_eof(dbp, rno);	/* ＥＯＦチェック */
}

/*=======================================================================
 |
 |		キーに一致するレコード数を求める
 |
 |	long	db_count(dbp, key, len)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		char	*key;		検索する文字列
 |		int	len;		検索する文字列の長さ
 |
 |		返値			キーに一致したレコード数
 |
 =======================================================================*/
long	db_count(DB *dbp, char *key, int len)
{
	DBF	*dp;
	IDX	*ip;
	double	a;
	long	count, rno;

	/* ポインタセット */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	/* インデックスファイルがなければ何もしない */
	if (ip == NULL)
		return(0L);

	if (ip->ihp->type != 0) {
		/* 文字列を数値に変換 */
		a = natof(key, len);
		key = (char *)&a;
		len = 8;
	}

	count = 0;
	if ((rno = idx_search(ip, key, len)) != 0) {	/* 検索 */
		for (;;) {
			if (_db_set_deleted == 0
					|| dbf_check_deleted(dp, rno) == 0)
				count++;
			if ((rno = idx_next(ip)) == 0)
				break;
			if (idx_compare(ip, key, len) != 0)
				break;
		}
	}

	check_eof(dbp, rno);	/* ＥＯＦチェック */

	return(count);
}

/*=======================================================================
 |
 |		レコード追加
 |
 |	void	db_store(dbp, rec)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		char	*rec;		追加するレコードデータへのポインタ
 |
 =======================================================================*/
void	db_store(DB *dbp, char *rec)
{
	DBF	*dp;
	IDX	*ip;
	register int	i;
	DBF_H	*dhp;
	IDX_H	*ihp;
	char	kbuf[100];

	/* ポインタセット */
	dp = dbp->dp;
	dhp = dp->dhp;

	dp->rbp[0] = ' ';	/* 削除レコードフラグクリア */
	memcpy(dp->rbp+1, rec, dhp->l_rec-1);	/* データコピー */

	/* データファイルに追加 */
	dbp->rno = ++dhp->n_rec;
	dbf_write(dp, dbp->rno);
	dp->w_flag = 1;

	/* インデックスファイル更新 */
	for (i = 1; i <= dbp->n_idx; i++) {
		ip = dbp->ip[i];
		ihp = ip->ihp;

		if (get_key(dp, ihp, kbuf) != 0)
			idx_store(ip, kbuf, dhp->n_rec);
	}

	/* インデックスファイル内のポインタをセット */
	db_set(dbp, dbp->rno);
}

/*=======================================================================
 |
 |		レコード更新
 |
 |	void	db_update(dbp, rec)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		char	*rec;		更新するレコードデータへのポインタ
 |
 =======================================================================*/
void	db_update(DB *dbp, char *rec)
{
	DBF	*dp;
	IDX	*ip;
	register int	i;
	DBF_H	*dhp;
	IDX_H	*ihp;
	long	rno;
	char	kbuf1[100], kbuf2[100];

	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	/* ポインタセット */
	dp = dbp->dp;
	dhp = dp->dhp;

	dbf_read(dp, dbp->rno);		/* レコード読み込み */

	/* インデックスファイル更新 */
	for (i = 1; i <= dbp->n_idx; i++) {
		ip = dbp->ip[i];
		ihp = ip->ihp;

		if (get_key(dp, ihp, kbuf1) != 0) {
			rno = idx_search(ip, kbuf1, ihp->l_key);
			while (rno != dbp->rno && rno != 0)
				rno = idx_next(ip);
			if (rno != 0) {
				memcpy(dp->rbp+1, rec, dhp->l_rec-1);
				get_key(dp, ihp, kbuf2);
				if (memcmp(kbuf1, kbuf2, ihp->l_key) != 0) {
					idx_delete(ip);
					idx_store(ip, kbuf2, rno);
				}
				dp->rno = 0;
				dbf_read(dp, dbp->rno);	/* レコード読み込み */
			}
		}
	}

	/* データファイル更新 */
	memcpy(dp->rbp+1, rec, dhp->l_rec-1);
	dbf_write(dp, dbp->rno);

	/* インデックスファイル内のポインタをセット */
	db_set(dbp, dbp->rno);
}

/*=======================================================================
 |
 |		レコード削除
 |
 |	void	db_delete(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_delete(DB *dbp)
{
	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	dbf_delete(dbp->dp, dbp->rno);	/* 削除マークセット */

	skip_deleted(dbp, 1);		/* 削除レコードスキップ */
}

/*=======================================================================
 |
 |		レコード復元
 |
 |	void	db_recall(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_recall(DB *dbp)
{
	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	dbf_recall(dbp->dp, dbp->rno);	/* 削除マーク取り消し */
}

/*=======================================================================
 |
 |		レコードの物理的削除
 |
 |	void	db_delete2(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_delete2(DB *dbp)
{
	DBF	*dp;
	DBF_H	*dhp;
	IDX	*ip;
	int	i;
	char	*rbufp;
	long	rno;
	char	kbuf[100];

	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	dp = dbp->dp;
	dhp = dp->dhp;

	/* レコード保存エリア確保 */
	if ((rbufp = malloc(dp->dhp->l_rec - 1)) == 0) {
		db_err(IE_MEMORY);
		return;
	}

	dbf_read(dp, dhp->n_rec);	/* 最終レコード読み込み */

	/* 物理的最終レコードをインデックスファイルより削除 */
	for (i = 1; i <= dbp->n_idx; i++) {
		ip = dbp->ip[i];

		if (get_key(dp, ip->ihp, kbuf) != 0) {
			rno = idx_search(ip, kbuf, ip->ihp->l_key);
			while (rno != dhp->n_rec && rno != 0)
				rno = idx_next(ip);
			if (rno != 0)
				idx_delete(ip);		/* キーの削除 */
		}
	}

	/* 最終レコード以外の場合、削除するレコードに最終レコードを上書き */
	if (dbp->rno != dhp->n_rec) {
		memcpy(rbufp, dp->rbp + 1, dhp->l_rec - 1);
		db_update(dbp, rbufp);
	}

	free(rbufp);

	dhp->n_rec--;	/* レコード数－１ */
	dp->update = 1;		/* ファイル更新フラグセット */

	if (dhp->n_rec == 0)	/* ０件になった場合はＢＯＦ、ＥＯＦをセット */
		dbp->eof = dbp->bof = 1;

	/* ファイルサイズを調整する */
	chsize(dp->fh, dhp->l_header + dhp->l_rec * dhp->n_rec);
}

/*=======================================================================
 |
 |		レコードポインタを先頭レコードにセット
 |
 |	void	db_top(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_top(DB *dbp)
{
	IDX	*ip;

	ip = dbp->ip[dbp->master];

	check_bof(dbp, (ip == NULL) ? 1L : idx_top(ip));

	skip_deleted(dbp, 1);	/* 削除レコードスキップ */
}

/*=======================================================================
 |
 |		レコードポインタを最終レコードにセット
 |
 |	void	db_bottom(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_bottom(DB *dbp)
{
	IDX	*ip;

	ip = dbp->ip[dbp->master];

	check_eof(dbp, (ip == NULL) ? dbp->dp->dhp->n_rec : idx_bottom(ip));

	skip_deleted(dbp, -1);	/* 削除レコードスキップ */
}

/*=======================================================================
 |
 |		レコードポインタを指定されたレコードにセット
 |
 |	void	db_set(dbp, recno)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		long	recno;		レコード番号
 |
 =======================================================================*/
void	db_set(DB *dbp, long recno)
{
	DBF	*dp;
	IDX	*ip;
	long	rno;
	char	kbuf[100];
	
	/* ポインタセット */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	if (ip != NULL) {
		/* インデックスファイルがある場合 */
		dbf_read(dp, recno);	/* レコード読み込み */

		/* インデックスファイルの指定されたレコード番号を探す */
		if (get_key(dp, ip->ihp, kbuf) != 0) {
			rno = idx_search(ip, kbuf, ip->ihp->l_key);
			while (rno != recno && rno != 0)
				rno = idx_next(ip);
		}
	}

	check_eof(dbp, recno);	/* ＥＯＦチェック */
}

/*=======================================================================
 |
 |		レコードポインタの前後移動
 |
 |	void	db_skip(dbp, n)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		long	n;		移動レコード数
 |
 =======================================================================*/
void	db_skip(DB *dbp, long n)
{
	if (_db_set_deleted == 0)
		skip(dbp, n);	/* レコードポインタ移動 */
	else {
		if (n < 0) {
			while (n++) {
				skip(dbp, -1L);
				skip_deleted(dbp, -1);/* 削除レコードスキップ*/
			}
		} else if (n > 0) {
			while (n--) {
				skip(dbp, 1L);
				skip_deleted(dbp, 1);/* 削除レコードスキップ */
			}
		}
	}
}

/*=======================================================================
 |
 |		インデックスファイル再構築
 |
 |	void	db_reindex(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_reindex(DB *dbp)
{
	DBF	*dp;
	register int	i;

	dp = dbp->dp;

	for (i = 1; i <= dbp->n_idx; i++) {
		idx_make(dp, dbp->ip[i]);	/* インデックス構築 */
	}

	db_top(dbp);
}

/*=======================================================================
 |
 |		削除レコードをファイル上から削除
 |
 |	void	db_pack(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
void	db_pack(DB *dbp)
{
	dbf_pack(dbp->dp);	/* 削除レコードの削除 */
	db_reindex(dbp);	/* インデックスファイル再構築 */
}

/*=======================================================================
 |
 |		カレントレコード読み込み
 |
 |	void	db_read(dbp, p)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		char	*p;		レコード読み込みバッファポインタ
 |
 =======================================================================*/
void	db_read(DB *dbp, char *p)
{
	DBF	*dp;

	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	dp = dbp->dp;
	dbf_read(dp, dbp->rno);		/* 読み込み */
	memcpy(p, dp->rbp + 1, dp->dhp->l_rec - 1);	/* バッファにコピー */
}

/*=======================================================================
 |
 |		データファイルをコピーする
 |
 |	void	db_copy(dbp1, dbp2)
 |
 |		DB	*dbp1;		コピー元ＤＢポインタ
 |		DB	*dbp2;		コピー先ＤＢポインタ
 |
 =======================================================================*/
void	db_copy(DB *dbp1, DB *dbp2)
{
	dbf_copy(dbp1->dp, dbp2->dp);	/* データファイルコピー */
	db_reindex(dbp2);		/* インデックスファイル再構築 */
}

/*=======================================================================
 |
 |		削除レコードチェック
 |
 |	int	db_check_deleted(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 |		返値			０：通常レコード　１：削除レコード
 |					-1：エラー
 |
 =======================================================================*/
int	db_check_deleted(DB *dbp)
{
	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return(-1);
	}

	return(dbf_check_deleted(dbp->dp, dbp->rno));
}

/*=======================================================================
 |
 |		削除レコードのスキップ
 |
 |	void	skip_deleted(dbp, n)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		int	n;		１：後ろへスキップ　－１：前へスキップ
 |
 =======================================================================*/
static	void	skip_deleted(DB *dbp, int n)
{
	DBF	*dp;

	if (_db_set_deleted != 0 && n != 0) {
		dp = dbp->dp;
		while (!(dbp->bof || dbp->eof) && dbf_check_deleted(dp, dbp->rno)) {
			skip(dbp, (long)n);
		}
	}
}

/*=======================================================================
 |
 |		レコードの前後移動
 |
 |	void	skip(dbp, n)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		long	n;		移動レコード数
 |
 =======================================================================*/
static	void	skip(DB *dbp, long n)
{
	DBF	*dp;
	IDX	*ip;
	long	rno;

	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	if (ip == NULL) {
		/* インデックスファイルがない場合 */
		rno = dbp->rno + n;
		if (rno <= 0)
			check_bof(dbp, 0L);
		else if (rno > dp->dhp->n_rec)
			check_eof(dbp, 0L);
		else
			check_eof(dbp, rno);
	} else {
		/* インデックスファイルがある場合 */
		if (n > 0) {
			if (dbp->bof) {
				rno = idx_top(ip);
				n--;
			}
			while (n-- != 0) {
				rno = idx_next(ip);
				if (rno == 0)
					break;
			}
			check_eof(dbp, rno);
		} else if (n < 0) {
			if (dbp->eof) {
				rno = idx_bottom(ip);
				n++;
			}
			while (n++ != 0) {
				rno = idx_back(ip);
				if (rno == 0)
					break;
			}
			check_bof(dbp, rno);
		}
	}
}

/*=======================================================================
 |
 |		ＢＯＦチェック
 |
 |	void	check_bof(dbp, rno)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		long	rno;		レコード番号
 |
 =======================================================================*/
static	void	check_bof(DB *dbp, long rno)
{
	if (dbp->dp->dhp->n_rec == 0) {
		/* レコードが１件もない場合 */
		dbp->rno = 1;
		dbp->bof = 1;
		dbp->eof = 1;
	} else {
		if (rno == 0) {
			dbp->rno = 1;
			dbp->bof = 1;
			dbp->eof = 0;
		} else {
			dbp->rno = rno;
			dbp->bof = 0;
			dbp->eof = 0;
		}
	}
}

/*=======================================================================
 |
 |		ＥＯＦチェック
 |
 |	void	check_eof(dbp, rno)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		long	rno;		レコード番号
 |
 =======================================================================*/
static	void	check_eof(DB *dbp, long rno)
{
	if (dbp->dp->dhp->n_rec == 0) {
		/* レコードが１件もない場合 */
		dbp->rno = 1;
		dbp->bof = 1;
		dbp->eof = 1;
	} else {
		if (rno == 0) {
			dbp->rno = dbp->dp->dhp->n_rec + 1;
			dbp->bof = 0;
			dbp->eof = 1;
		} else {
			dbp->rno = rno;
			dbp->bof = 0;
			dbp->eof = 0;
		}
	}
}
