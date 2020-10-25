/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: ＩＳＡＭ関係				*
 *		ファイル名	: isam.h				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#define	BLOCK_SIZE	512	/* インデックスファイルのブロックサイズ */
#define	MAX_IDX		10	/* 同時にオープンできるインデックスファイル数*/

/*	インデックス（ＮＤＸ）ファイルのヘッダ	*/
struct	idx_h	{
	LONG	root;		/* ルートのブロックＮｏ． */
	LONG	nBlock;		/* ブロック数 */
	LONG	dummy1;		/* */
	USHORT	lKey;		/* キーの長さ */
	USHORT	order;		/* 次数 */
	USHORT	type;		/* データ種別 */
	USHORT	lRec;		/* １レコードの長さ */
	USHORT	dummy2;		/* */
	CHAR	dummy3;		/* */
	CHAR	uniq;		/* ユニークフラグ */
	CHAR	index[100];	/* インデックス表現式 */
	CHAR	work[388];	/* ワークエリア */
};
typedef	struct	idx_h	IDX_H;

/*	インデックスブロック情報	*/
struct	idx_b	{
	CHAR	block[BLOCK_SIZE];	/* ブロック情報 */
	CHAR	work[104];	/* 追加用ワークエリア */
	LONG	bno;		/* ブロックＮｏ． */
	SHORT	cp;		/* カレントポインタ */
	struct	idx_b	*fwp;	/* チェインポインタ */
	struct	idx_b	*bwp;	/* チェインポインタ */
};
typedef	struct	idx_b	IDX_B;

/*	インデックスファイル管理情報	*/
struct	idx	{
	CHAR	fileName[80];	/* ファイル名 */
	int	fh;		/* ファイルハンドラ */
	IDX_H	*ihp;		/* ヘッダ情報ポインタ */
	IDX_B	*bp;		/* カレントブロック情報ポインタ */
	IDX_B	*top;		/* ブロック情報ポインタ */
	IDX_B	*bottom;	/* ブロック情報ポインタ */
	CHAR	*keyp;		/* キーポインタ */
	BOOL	wFlag;		/* ヘッダ書き込みフラグ */
};
typedef	struct	idx	IDX;

/*	データ（ＤＢＦ）ファイルのヘッダ	*/
struct	dbf_h	{
	CHAR	flag;		/* データベーステキストファイル保有フラグ */
	CHAR	date[3];	/* 最終更新日 */
	LONG	nRec;		/* レコード数 */
	USHORT	lHeader;	/* ヘッダ部の長さ */
	USHORT	lRec;		/* １レコードの長さ */
	CHAR	reserve[20];	/* システム予約領域 */
};
typedef	struct	dbf_h	DBF_H;

/*	フィールド情報	*/
struct	field	{
	CHAR	name[11];	/* フィールド名 */
	CHAR	type;		/* フィールド形式 */
	CHAR	far *fad;	/* フィールド情報内容のメモリ内アドレス */
	CHAR	lField;	/* フィールド長 */
	CHAR	lDec;		/* 小数位桁数 */
	CHAR	reserve1[5];	/* システム予約領域 */
	CHAR	flag;		/* SET FIELDS ON/OFF の設定フラグ */
	CHAR	reserve2[8];	/* システム予約領域 */
};
typedef	struct	field	FIELD;

/*	データファイル管理情報	*/
struct	dbf	{
	CHAR	fileName[80];	/* ファイル名 */
	int	fh;		/* ファイルハンドラ */
	DBF_H	*dhp;		/* ヘッダ情報へのポインタ */
	FIELD	*flp;		/* フィールド情報へのポインタ */
	USHORT	nField;		/* フィールド数 */
	CHAR	*rbp;		/* レコードバッファへのポインタ */
	LONG	rno;		/* カレントレコードＮｏ． */
	BOOL	update;		/* 更新フラグ */
	BOOL	wFlag;		/* ヘッダ書き込みフラグ */
};
typedef	struct	dbf	DBF;

/*	データベース管理情報	*/
struct	db	{
	DBF	*dp;		/* データファイル情報 */
	IDX	*ip[MAX_IDX+1];	/* インデックスファイル情報 */
	USHORT	master;		/* マスターインデックス */
	USHORT	nIdx;		/* インデックスファイル数 */
	LONG	rno;		/* カレントレコードＮｏ． */
	BOOL	bof;		/* ＢＯＦフラグ */
	BOOL	eof;		/* ＥＯＦフラグ */
	DOSFSRSEM	sem;	/* セマフォ */
	BOOL	updateFlag;	/* 更新フラグ */
	struct	db	*chain;	/* チェインポインタ */
};
typedef	struct	db	DB;

/*	データファイル作成情報	*/
struct	dbf_i	{
	CHAR	name[11];	/* フィールド名 */
	CHAR	type;		/* フィールド形式 */
	CHAR	lField;		/* フィールド長 */
	CHAR	lDec;		/* 小数位桁数 */
};
typedef	struct	dbf_i	DBF_I;

/*	データ定義	*/
extern	SHORT	_db_err_code;		/* エラーコード */
extern	SHORT	_db_set_deleted;	/* 削除レコード無視フラグ */

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
