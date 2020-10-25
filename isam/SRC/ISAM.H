/************************************************************************
 *									*
 *		ＩＳＡＭライブラリ					*
 *									*
 *		名称		: 共通ヘッダファイル			*
 *		ファイル名	: isam.h				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#define	BLOCK_SIZE	512	/* インデックスファイルのブロックサイズ */
#define	MAX_IDX		10	/* 同時にオープンできるインデックスファイル数*/

/*	型定義		*/
#ifndef	u_int
#define	u_int	unsigned int
#endif
#ifndef	u_char
#define	u_char	unsigned char
#endif
#ifndef	u_long
#define	u_long	unsigned long
#endif

/*	インデックス（ＮＤＸ）ファイルのヘッダ	*/
struct	idx_h	{
	long	root;		/* ルートのブロックＮｏ． */
	long	n_block;	/* ブロック数 */
	long	dummy1;		/* */
	int	l_key;		/* キーの長さ */
	int	order;		/* 次数 */
	int	type;		/* データ種別 */
	int	l_rec;		/* １レコードの長さ */
	int	dummy2;		/* */
	char	dummy3;		/* */
	char	uniq;		/* ユニークフラグ */
	char	index[100];	/* インデックス表現式 */
	char	work[388];	/* ワークエリア */
};
typedef	struct	idx_h	IDX_H;

/*	インデックスブロック情報	*/
struct	idx_b	{
	char	block[BLOCK_SIZE];	/* ブロック情報 */
	char	work[104];	/* 追加用ワークエリア */
	long	bno;		/* ブロックＮｏ． */
	int	cp;		/* カレントポインタ */
	struct	idx_b	*fwp;	/* チェインポインタ */
	struct	idx_b	*bwp;	/* チェインポインタ */
};
typedef	struct	idx_b	IDX_B;

/*	インデックスファイル管理情報	*/
struct	idx	{
	char	file_name[80];	/* ファイル名 */
	int	fh;		/* ファイルハンドル */
	IDX_H	*ihp;		/* ヘッダ情報ポインタ */
	IDX_B	*bp;		/* カレントブロック情報ポインタ */
	IDX_B	*top;		/* ブロック情報ポインタ */
	IDX_B	*bottom;	/* ブロック情報ポインタ */
	char	*keyp;		/* キーポインタ */
	int	w_flag;		/* ヘッダ書き込みフラグ */
};
typedef	struct	idx	IDX;

/*	データ（ＤＢＦ）ファイルのヘッダ	*/
struct	dbf_h	{
	char	flag;		/* データベーステキストファイル保有フラグ */
	char	date[3];	/* 最終更新日 */
	long	n_rec;		/* レコード数 */
	int	l_header;	/* ヘッダ部の長さ */
	int	l_rec;		/* １レコードの長さ */
	char	reserve[20];	/* システム予約領域 */
};
typedef	struct	dbf_h	DBF_H;

/*	フィールド情報	*/
struct	field	{
	char	name[11];	/* フィールド名 */
	char	type;		/* フィールド形式 */
	char	far *fad;	/* フィールド情報内容のメモリ内アドレス */
	u_char	l_field;	/* フィールド長 */
	u_char	l_dec;		/* 小数位桁数 */
	char	reserve1[5];	/* システム予約領域 */
	char	flag;		/* SET FIELDS ON/OFF の設定フラグ */
	char	reserve2[8];	/* システム予約領域 */
};
typedef	struct	field	FIELD;

/*	データファイル管理情報	*/
struct	dbf	{
	char	file_name[80];	/* ファイル名 */
	int	fh;		/* ファイルハンドル */
	DBF_H	*dhp;		/* ヘッダ情報へのポインタ */
	FIELD	*flp;		/* フィールド情報へのポインタ */
	int	n_field;	/* フィールド数 */
	char	*rbp;		/* レコードバッファへのポインタ */
	long	rno;		/* カレントレコードＮｏ． */
	int	update;		/* 更新フラグ */
	int	w_flag;		/* ヘッダ書き込みフラグ */
};
typedef	struct	dbf	DBF;

/*	データベース管理情報	*/
struct	db	{
	DBF	*dp;		/* データファイル情報 */
	IDX	*ip[MAX_IDX+1];	/* インデックスファイル情報 */
	int	master;		/* マスターインデックス */
	int	n_idx;		/* インデックスファイル数 */
	long	rno;		/* カレントレコードＮｏ． */
	int	bof;		/* ＢＯＦフラグ */
	int	eof;		/* ＥＯＦフラグ */
	struct	db	*chain;	/* チェインポインタ */
};
typedef	struct	db	DB;

/*	データファイル作成情報	*/
struct	dbf_i	{
	char	name[11];	/* フィールド名 */
	char	type;		/* フィールド形式 */
	u_char	l_field;	/* フィールド長 */
	u_char	l_dec;		/* 小数位桁数 */
};
typedef	struct	dbf_i	DBF_I;

/*	関数定義	*/
extern	DB	*db_open(char *);
extern	DB	*db_create(char *, DBF_I *, int);
extern	DB	*db_create_from(char *, char *);
extern	void	db_close(DB *);
extern	void	db_close_all(void);
extern	void	db_flush(DB *);
extern	void	db_flush_all(void);
extern	int	db_index(DB *, char *);
extern	int	db_idx_create(DB *, char *, char *, int);
extern	void	db_chgidx(DB *, int);
extern	void	db_search(DB *, char *, int);
extern	long	db_count(DB *, char *, int);
extern	void	db_store(DB *, char *);
extern	void	db_update(DB *, char *);
extern	void	db_delete(DB *);
extern	void	db_delete2(DB *);
extern	void	db_recall(DB *);
extern	void	db_top(DB *);
extern	void	db_bottom(DB *);
extern	void	db_set(DB *, long);
extern	void	db_skip(DB *, long);
extern	void	db_reindex(DB *);
extern	void	db_pack(DB *);
extern	void	db_read(DB *, char *);
extern	void	db_copy(DB *, DB *);
extern	int	db_check_deleted(DB *);

extern	DBF	*dbf_open(char *);
extern	DBF	*dbf_create(char *, DBF_I *, int);
extern	void	dbf_read(DBF *, long);
extern	void	dbf_write(DBF *, long);
extern	void	dbf_close(DBF *);
extern	void	dbf_flush(DBF *);
extern	void	dbf_delete(DBF *, long);
extern	void	dbf_recall(DBF *, long);
extern	void	dbf_pack(DBF *);
extern	int	dbf_check_deleted(DBF *, long);
extern	void	dbf_copy(DBF *, DBF *);

extern	IDX	*idx_open(char *);
extern	IDX	*idx_create(char *, char *, int);
extern	void	idx_setinfo(IDX *, int, int);
extern	IDX_B	*idx_read(IDX *, long);
extern	IDX_B	*idx_getblk(IDX *, long);
extern	void	idx_write(IDX *, IDX_B *);
extern	void	idx_close(IDX *);
extern	void	idx_flush(IDX *);
extern	long	idx_search(IDX *, void *, int);
extern	int	idx_compare(IDX *, void *, int);
extern	void	idx_store(IDX *, char *, long);
extern	void	idx_delete(IDX *);
extern	long	idx_next(IDX *);
extern	long	idx_back(IDX *);
extern	long	idx_top(IDX *);
extern	long	idx_bottom(IDX *);
extern	long	idx_count(IDX *, void *, int);

extern	void	dbf_sort(DBF *, char *, char *);
extern	void	idx_make(DBF *, IDX *);

extern	long	db_reccount(DB *);
extern	int	db_bof(DB *);
extern	char	*db_dbf(DB *);
extern	int	db_eof(DB *);
extern	char	*db_field(DB *, int);
extern	char	*db_ndx(DB *, int);
extern	long	db_recno(DB *);
extern	int	db_recsize(DB *);
extern	void	set_deleted(int);

extern	void	db_err_handler(void (*)());
extern	void	db_err(int);
extern	double	natof(char *, int);
extern	int	natoi(char *, int);
extern	int	get_key(DBF *, IDX_H *, char *);
extern	FIELD	*get_field(DBF *, char *);

/*	データ定義	*/
extern	int	_db_err_code;		/* エラーコード */
extern	int	_db_set_deleted;	/* 削除レコード無視フラグ */

/*	エラーコード	*/
#define	IE_MEMORY	1	/* メモリ不足 */
#define	IE_FOPEN	2	/* ファイルオープンエラー */
#define	IE_NOFILE	3	/* オープンするファイルがない */
#define	IE_IDXOVER	4	/* インデックスファイルが多すぎる */
#define	IE_TMPFILE	5	/* テンポラリファイルが作れない */
#define	IE_NOKEY	6	/* 指定されたキーが存在しない */
#define	IE_DBLKEY	7	/* キーが重複している*/
#define	IE_RECNO	8	/* レコード番号が不正 */
#define	IE_EOF		9	/* ＢＯＦまたはＥＯＦ */
#define	IE_STRFILE	10	/* 構造ファイルが正しくない */
#define	IE_READ		11	/* ファイルｒｅａｄエラー */
#define	IE_WRITE	12	/* ファイルｗｒｉｔｅエラー */
#define	IE_IDXKEY	13	/* インデックスのキー表現式が不正 */
