/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: データベース操作			*
 *		ファイル名	: isam.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <memory.h>
#include <string.h>
#include <time.h>
#include <io.h>
#include <fcntl.h>
#include <sys\types.h>
#include <sys\stat.h>

#define	MBLOCK	8192
#define	SBLOCK	(USHORT)(MBLOCK*3)

typedef	struct	{
	LONG	nRec;
	LONG	offset;
} SORTBLK;

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	VOID	SkipDeleted(DB *, SHORT);
static	VOID	Skip(DB *, LONG);
static	VOID	CheckBof(DB *, LONG);
static	VOID	CheckEof(DB *, LONG);
static	VOID	memcpyr(CHAR *, CHAR *, SHORT);
static	USHORT	Marge(int, int, SORTBLK *, SORTBLK *, CHAR *, USHORT, SHORT (*)(CHAR *, CHAR *));
static	SHORT	Comp2(CHAR *, CHAR *);
static	VOID	WrtIndex(IDX *, int, LONG, SHORT);
static	VOID	WrtBlock(IDX *, int, SHORT, SHORT, CHAR *, LONG *);
static	VOID	SetExt(CHAR *, CHAR *);

/*--------------------------------------*/
/*		内部変数		*/
/*--------------------------------------*/
static	DB	*_dbTopPtr = 0;	/* データベース先頭ポインタ */
static	VOID	(*_dbErrHandler)(SHORT) = 0;	/* エラー処理関数ポインタ */
static	SHORT	compLength;
static	LONG	wnRec, wBno;
static	BOOL	_dbSetDeleted = FALSE;	/* 削除レコード無視フラグ */

/*--------------------------------------*/
/*		外部変数		*/
/*--------------------------------------*/
SHORT	_dbErrCode;			/* エラーコード */

/*=======================================================================
 |
 |		データベースオープン
 |
 |	DB	*DBOpen(fileName)
 |
 |		CHAR	*fileName;	データファイル名
 |
 |		返値			ＤＢポインタ
 |
 =======================================================================*/
DB	*DBOpen(CHAR *fileName)
{
	DB	*dbp;

	/* データベース管理情報エリア確保 */
	if ((dbp = malloc(sizeof(DB))) == NULL) {
		DBErr(IE_MEMORY);
		return(NULL);
	}
	memset(dbp, 0, sizeof(DB));
	dbp->sem.cb = 14;

	/* データファイル（ＤＢＦ）オープン */
	if ((dbp->dp = DBFOpen(fileName)) == NULL) {
		DBClose(dbp);
		return(NULL);
	}

	CheckBof(dbp, 1L);	/* ＢＯＦチェック*/

	/* チェイン処理 */
	dbp->chain = _dbTopPtr;
	_dbTopPtr = dbp;

	return(dbp);
}

/*=======================================================================
 |
 |		データベース作成
 |
 |	DB	*DBCreate(fileName, dip, nField)
 |
 |		CHAR	*fileName;	データファイル名
 |		DBF_I	*dip;		データファイル情報
 |		SHORT	nField;		フィールド数
 |
 |		返値			ＤＢポインタ
 |
 =======================================================================*/
DB	*DBCreate(CHAR *fileName, DBF_I *dip, SHORT nField)
{
	DBF	*dp;

	/* データファイル作成 */
	if ((dp = DBFCreate(fileName, dip, nField)) == NULL)
		return(NULL);

	DBFClose(dp);	/* データファイルクローズ */

	return(DBOpen(fileName));
}

/*=======================================================================
 |
 |		データベースクローズ
 |
 |	VOID	DBClose(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBClose(DB *dbp)
{
	DB	**dbpp;
	USHORT	i;

	/* データファイルクローズ */
	if (dbp->dp != NULL)
		DBFClose(dbp->dp);

	/* インデックスファイルクローズ */
	for (i = 1; i <= dbp->nIdx; i++) {
		if (dbp->ip[i] != NULL)
			IDXClose(dbp->ip[i]);
	}

	/* チェイン切り離し */
	for (dbpp = &_dbTopPtr; *dbpp != 0; dbpp = &(*dbpp)->chain) {
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
 |	VOID	DBCloseAll()
 |
 =======================================================================*/
VOID	DBCloseAll()
{
	while (_dbTopPtr != NULL)
		DBClose(_dbTopPtr);
}

/*=======================================================================
 |
 |		ファイル強制掃き出し
 |
 |	VOID	DBFlush(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBFlush(DB *dbp)
{
	USHORT	i;

	DBFFlush(dbp->dp);	/* データファイルフラッシュ */

	/* インデックスファイルフラッシュ */
	for (i = 1; i <= dbp->nIdx; i++) {
		if (dbp->ip[i] != NULL)
			IDXFlush(dbp->ip[i]);
	}
}

/*=======================================================================
 |
 |		全ファイル強制掃き出し
 |
 |	VOID	DBFlushAll()
 |
 =======================================================================*/
VOID	DBFlushAll()
{
	DB	*dbp;

	for (dbp = _dbTopPtr; dbp != NULL; dbp = dbp->chain) {
		DBLock(dbp);
		DBFlush(dbp);
		DBRelease(dbp);
	}
}

/*=======================================================================
 |
 |		インデックスファイルオープン
 |
 |	SHORT	DBIndex(dbp, fileName)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		CHAR	*fileName;	インデックスファイル名
 |
 |		返値			インデックスファイル番号
 |
 =======================================================================*/
SHORT	DBIndex(DB *dbp, CHAR *fileName)
{
	/* インデックスファイルオープン数チェック */
	if (dbp->nIdx >= MAX_IDX) {
		DBErr(IE_IDXOVER);
		return(0);
	}

	/* インデックスファイルオープン */
	if ((dbp->ip[dbp->nIdx+1] = IDXOpen(fileName)) == NULL)
		return(0);


	dbp->nIdx++;		/* インデックスファイル数更新 */

	/* マスターインデックスファイル番号セット */
	if (dbp->master == 0) {
		dbp->master = dbp->nIdx;
		DBTop(dbp);
	}

	return(dbp->nIdx);
}

/*=======================================================================
 |
 |		インデックスファイル作成
 |
 |	SHORT	DBIdxCreate(dbp, fileName, key, uniq)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		CHAR	*fileName;	インデックスファイル名
 |		CHAR	*key;		キー
 |		SHORT	uniq;		０：キーの重複を許す　１：許さない
 |
 |		返値			インデックスファイル番号
 |
 =======================================================================*/
SHORT	DBIdxCreate(DB *dbp, CHAR *fileName, CHAR *key, SHORT uniq)
{
	DBF	*dp;
	IDX	*ip;

	dp = dbp->dp;

	/* インデックスファイルオープン数チェック */
	if (dbp->nIdx >= MAX_IDX) {
		DBErr(IE_IDXOVER);
		return(0);
	}

	/* インデックスファイル作成 */
	if ((ip = IDXCreate(fileName, key, uniq)) == NULL)
		return(0);

	IDXMake(dp, ip);	/* インデックス構築 */

	dbp->ip[dbp->nIdx+1] = ip;	/* インデックスポインタセーブ */

	dbp->nIdx++;		/* インデックスファイル数更新 */

	/* マスターインデックスファイル番号セット */
	if (dbp->master == 0) {
		dbp->master = dbp->nIdx;
		DBTop(dbp);
	}

	return(dbp->nIdx);
}

/*=======================================================================
 |
 |		マスターインデックス切り替え
 |
 |	VOID	DBChgIdx(dbp, n)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		SHORT	n;		インデックスファイル番号
 |
 =======================================================================*/
VOID	DBChgIdx(DB *dbp, SHORT n)
{
	dbp->master = n;	/* マスターインデックス番号セット */
	DBTop(dbp);		/* 先頭レコード */
}

/*=======================================================================
 |
 |		インデックスファイルによる検索
 |
 |	VOID	DBSearch(dbp, key, len, index)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		CHAR	*key;		検索する文字列
 |		SHORT	len;		検索する文字列の長さ
 |		SHORT	index;		インデックスファイル番号
 |
 |		BOOL	返値		TRUE ：見つかった
 |					FALSE：見つからない
 |
 =======================================================================*/
BOOL	DBSearch(DB *dbp, CHAR *key, SHORT len, SHORT index)
{
	DBF	*dp;
	IDX	*ip;
	LONG	rno;

	dbp->master = index;

	/* ポインタセット */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	/* インデックスファイルがなければ何もしない */
	if (ip == NULL)
		return(FALSE);

	rno = IDXSearch(ip, key, len);		/* 検索 */

	if (_dbSetDeleted && rno != 0L) {
		/* 削除レコードをスキップする */
		while (DBFCheckDeleted(dp, rno)) {
			if ((rno = IDXNext(ip)) == 0)
				break;
			if (IDXCompare(ip, key, len) != 0) {
				rno = 0;
				break;
			}
		}
	}

	CheckEof(dbp, rno);	/* ＥＯＦチェック */

	return(!DBEof(dbp));
}

/*=======================================================================
 |
 |		キーに一致するレコード数を求める
 |
 |	LONG	DBCount(dbp, key, len, index)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		CHAR	*key;		検索する文字列
 |		SHORT	len;		検索する文字列の長さ
 |		SHORT	index;		インデックスファイル番号
 |
 |		返値			キーに一致したレコード数
 |
 =======================================================================*/
LONG	DBCount(DB *dbp, CHAR *key, SHORT len, SHORT index)
{
	DBF	*dp;
	IDX	*ip;
	LONG	count, rno;

	dbp->master = index;

	/* ポインタセット */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	/* インデックスファイルがなければ何もしない */
	if (ip == NULL)
		return(0L);

	count = 0;
	if ((rno = IDXSearch(ip, key, len)) != 0) {	/* 検索 */
		for (;;) {
			if (_dbSetDeleted == FALSE
					|| DBFCheckDeleted(dp, rno) == 0)
				count++;
			if ((rno = IDXNext(ip)) == 0)
				break;
			if (IDXCompare(ip, key, len) != 0)
				break;
		}
	}

	CheckEof(dbp, rno);	/* ＥＯＦチェック */

	return(count);
}

/*=======================================================================
 |
 |		レコード追加
 |
 |	VOID	DBStore(dbp, rec)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		VOID	*rec;		追加するレコードデータへのポインタ
 |
 =======================================================================*/
VOID	DBStore(DB *dbp, VOID *rec)
{
	DBF	*dp;
	IDX	*ip;
	USHORT	i;
	DBF_H	*dhp;
	IDX_H	*ihp;
	CHAR	kbuf[100];

	/* ポインタセット */
	dp = dbp->dp;
	dhp = dp->dhp;

	dp->rbp[0] = ' ';	/* 削除レコードフラグクリア */
	memcpy(dp->rbp+1, rec, dhp->lRec-1);	/* データコピー */

	/* データファイルに追加 */
	dbp->rno = ++dhp->nRec;
	DBFWrite(dp, dbp->rno);
	dp->wFlag = TRUE;

	/* インデックスファイル更新 */
	for (i = 1; i <= dbp->nIdx; i++) {
		ip = dbp->ip[i];
		ihp = ip->ihp;

		if (GetKey(dp, ihp, kbuf) != 0)
			IDXStore(ip, kbuf, dhp->nRec);
	}

	/* インデックスファイル内のポインタをセット */
	DBSet(dbp, dbp->rno);

	dbp->updateFlag = TRUE;
}

/*=======================================================================
 |
 |		レコード更新
 |
 |	VOID	DBUpdate(dbp, rec)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		VOID	*rec;		更新するレコードデータへのポインタ
 |
 =======================================================================*/
VOID	DBUpdate(DB *dbp, VOID *rec)
{
	DBF	*dp;
	IDX	*ip;
	USHORT	i;
	DBF_H	*dhp;
	IDX_H	*ihp;
	LONG	rno;
	CHAR	kbuf1[100], kbuf2[100];

	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		DBErr(IE_EOF);
		return;
	}

	/* ポインタセット */
	dp = dbp->dp;
	dhp = dp->dhp;

	DBFRead(dp, dbp->rno);		/* レコード読み込み */

	/* インデックスファイル更新 */
	for (i = 1; i <= dbp->nIdx; i++) {
		ip = dbp->ip[i];
		ihp = ip->ihp;

		if (GetKey(dp, ihp, kbuf1) != 0) {
			rno = IDXSearch(ip, kbuf1, ihp->lKey);
			while (rno != dbp->rno && rno != 0)
				rno = IDXNext(ip);
			if (rno != 0) {
				memcpy(dp->rbp+1, rec, dhp->lRec-1);
				GetKey(dp, ihp, kbuf2);
				if (memcmp(kbuf1, kbuf2, ihp->lKey) != 0) {
					IDXDelete(ip);
					IDXStore(ip, kbuf2, rno);
				}
				dp->rno = 0;
				DBFRead(dp, dbp->rno);	/* レコード読み込み */
			}
		}
	}

	/* データファイル更新 */
	memcpy(dp->rbp+1, rec, dhp->lRec-1);
	DBFWrite(dp, dbp->rno);

	/* インデックスファイル内のポインタをセット */
	DBSet(dbp, dbp->rno);

	dbp->updateFlag = TRUE;
}

/*=======================================================================
 |
 |		レコード削除
 |
 |	VOID	DBDelete(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBDelete(DB *dbp)
{
	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		DBErr(IE_EOF);
		return;
	}

	DBFDelete(dbp->dp, dbp->rno);	/* 削除マークセット */

	SkipDeleted(dbp, 1);		/* 削除レコードスキップ */
}

/*=======================================================================
 |
 |		レコード復元
 |
 |	VOID	DBRecall(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBRecall(DB *dbp)
{
	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		DBErr(IE_EOF);
		return;
	}

	DBFRecall(dbp->dp, dbp->rno);	/* 削除マーク取り消し */
}

/*=======================================================================
 |
 |		レコードの物理的削除
 |
 |	VOID	DBDelete2(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBDelete2(DB *dbp)
{
	DBF	*dp;
	DBF_H	*dhp;
	IDX	*ip;
	USHORT	i;
	CHAR	*rbufp;
	LONG	rno;
	CHAR	kbuf[100];

	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		DBErr(IE_EOF);
		return;
	}

	dp = dbp->dp;
	dhp = dp->dhp;

	/* レコード保存エリア確保 */
	if ((rbufp = malloc(dp->dhp->lRec - 1)) == 0) {
		DBErr(IE_MEMORY);
		return;
	}

	DBFRead(dp, dhp->nRec);	/* 最終レコード読み込み */

	/* 物理的最終レコードをインデックスファイルより削除 */
	for (i = 1; i <= dbp->nIdx; i++) {
		ip = dbp->ip[i];

		if (GetKey(dp, ip->ihp, kbuf) != 0) {
			rno = IDXSearch(ip, kbuf, ip->ihp->lKey);
			while (rno != dhp->nRec && rno != 0)
				rno = IDXNext(ip);
			if (rno != 0)
				IDXDelete(ip);		/* キーの削除 */
		}
	}

	/* 最終レコード以外の場合、削除するレコードに最終レコードを上書き */
	if (dbp->rno != dhp->nRec) {
		memcpy(rbufp, dp->rbp + 1, dhp->lRec - 1);
		DBUpdate(dbp, rbufp);
	}

	free(rbufp);

	dhp->nRec--;			/* レコード数－１ */
	dp->update = TRUE;		/* ファイル更新フラグセット */

	/* ファイルサイズを調整する */
	chsize(dp->fh, dhp->lHeader + dhp->lRec * dhp->nRec);

	dbp->updateFlag = TRUE;
}

/*=======================================================================
 |
 |		レコードの物理的削除（削除後カレントポインタは次レコード）
 |
 |	VOID	DBDelete3(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBDelete3(DB *dbp)
{
	IDX	*ip;
	LONG	nextRno;

	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		DBErr(IE_EOF);
		return;
	}

	/* 次レコード番号保存 */
	ip = dbp->ip[dbp->master];
	if (ip != NULL)
		nextRno = IDXNext(ip);

	/* レコードの物理的削除 */
	DBDelete2(dbp);

	/* 次レコードにレコードポインタをセット */
	if (ip != NULL) {
		if (nextRno != 0) {
			if (nextRno <= dbp->dp->dhp->nRec)
				DBSet(dbp, nextRno);
		} else
			CheckEof(dbp, 0L);
	}
}

/*=======================================================================
 |
 |		レコードの物理的削除（削除後カレントポインタは前レコード）
 |
 |	VOID	DBDelete4(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBDelete4(DB *dbp)
{
	IDX	*ip;
	LONG	backRno;

	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		DBErr(IE_EOF);
		return;
	}

	/* 前レコード番号保存 */
	ip = dbp->ip[dbp->master];
	if (ip != NULL)
		backRno = IDXBack(ip);

	/* レコードの物理的削除 */
	DBDelete2(dbp);

	/* 前レコードにレコードポインタをセット */
	if (ip != NULL) {
		if (backRno != 0) {
			if (backRno <= dbp->dp->dhp->nRec)
				DBSet(dbp, backRno);
		} else
			CheckBof(dbp, 0L);
	}
}

/*=======================================================================
 |
 |		レコードポインタを先頭レコードにセット
 |
 |	VOID	DBTop(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBTop(DB *dbp)
{
	IDX	*ip;

	ip = dbp->ip[dbp->master];

	CheckBof(dbp, (ip == NULL) ? 1L : IDXTop(ip));

	SkipDeleted(dbp, 1);	/* 削除レコードスキップ */
}

/*=======================================================================
 |
 |		レコードポインタを最終レコードにセット
 |
 |	VOID	DBBottom(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBBottom(DB *dbp)
{
	IDX	*ip;

	ip = dbp->ip[dbp->master];

	CheckEof(dbp, (ip == NULL) ? dbp->dp->dhp->nRec : IDXBottom(ip));

	SkipDeleted(dbp, -1);	/* 削除レコードスキップ */
}

/*=======================================================================
 |
 |		レコードポインタを指定されたレコードにセット
 |
 |	VOID	DBSet(dbp, recno)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		LONG	recno;		レコード番号
 |
 =======================================================================*/
VOID	DBSet(DB *dbp, LONG recno)
{
	DBF	*dp;
	IDX	*ip;
	LONG	rno;
	CHAR	kbuf[100];
	
	/* ポインタセット */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	if (ip != NULL) {
		/* インデックスファイルがある場合 */
		DBFRead(dp, recno);	/* レコード読み込み */

		/* インデックスファイルの指定されたレコード番号を探す */
		if (GetKey(dp, ip->ihp, kbuf) != 0) {
			rno = IDXSearch(ip, kbuf, ip->ihp->lKey);
			while (rno != recno && rno != 0)
				rno = IDXNext(ip);
		}
	}

	CheckEof(dbp, recno);	/* ＥＯＦチェック */
}

/*=======================================================================
 |
 |		レコードポインタの前後移動
 |
 |	VOID	DBSkip(dbp, n)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		LONG	n;		移動レコード数
 |
 =======================================================================*/
VOID	DBSkip(DB *dbp, LONG n)
{
	if (n != 0) {
		Skip(dbp, n);	/* レコードポインタ移動 */
		SkipDeleted(dbp, n > 0 ? 1 : -1); /* 削除レコードスキップ */
	}
}

/*=======================================================================
 |
 |		インデックスファイル再構築
 |
 |	VOID	DBReindex(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBReindex(DB *dbp)
{
	DBF	*dp;
	USHORT	i;

	dp = dbp->dp;

	for (i = 1; i <= dbp->nIdx; i++) {
		IDXMake(dp, dbp->ip[i]);	/* インデックス構築 */
	}

	DBTop(dbp);

	dbp->updateFlag = TRUE;
}

/*=======================================================================
 |
 |		削除レコードをファイル上から削除
 |
 |	VOID	DBPack(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBPack(DB *dbp)
{
	DBFPack(dbp->dp);	/* 削除レコードの削除 */
	DBReindex(dbp);	/* インデックスファイル再構築 */
}

/*=======================================================================
 |
 |		カレントレコード読み込み
 |
 |	VOID	DBRead(dbp, p)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		VOID	*p;		レコード読み込みバッファポインタ
 |
 =======================================================================*/
VOID	DBRead(DB *dbp, VOID *p)
{
	DBF	*dp;

	/* ＢＯＦまたはＥＯＦならエラー */
	if (dbp->bof || dbp->eof) {
		DBErr(IE_EOF);
		return;
	}

	dp = dbp->dp;
	DBFRead(dp, dbp->rno);		/* 読み込み */
	memcpy(p, dp->rbp + 1, dp->dhp->lRec - 1);	/* バッファにコピー */
}

/*=======================================================================
 |
 |		データベースファイルロック
 |
 |	VOID	DBLock(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBLock(DB *dbp)
{
	DosFSRamSemRequest(&dbp->sem, SEM_INDEFINITE_WAIT);
}

/*=======================================================================
 |
 |		データベースファイルロック解除
 |
 |	VOID	DBRelease(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBRelease(DB *dbp)
{
	DosFSRamSemClear(&dbp->sem);
}

/*=======================================================================
 |
 |		カレントレコードポインタの論理レコード番号取り出し
 |
 |	LONG	DBGetRecNo(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 |		LONG	返値		レコード番号
 |
 =======================================================================*/
LONG	DBGetRecNo(DB *dbp)
{
	LONG	pRecNo, recNo;

	pRecNo = DBRecNo(dbp);
	DBTop(dbp);
	for (recNo = 0; pRecNo != DBRecNo(dbp); recNo++)
		DBSkip(dbp, 1L);
	return(recNo);
}

/*=======================================================================
 |
 |		データファイルサイズチェック
 |
 |	BOOL	DBCheckSize(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 |		BOOL	返値		TRUE ：ファイルサイズを調整した
 |					FALSE：ファイルサイズは正しい
 |
 =======================================================================*/
BOOL	DBCheckSize(DB *dbp)
{
	DBF	*dp;
	DBF_H	*dhp;
	LONG	nRec;

	dp = dbp->dp;
	dhp = dp->dhp;

	lseek(dp->fh, 0L, SEEK_END);
	nRec = (tell(dp->fh) - dhp->lHeader) / dhp->lRec;
	if (nRec < 0)
		nRec = 0;
	if (nRec != dhp->nRec) {
		dhp->nRec = nRec;
		dp->update = TRUE;	/* ファイル更新フラグセット */
		return(TRUE);
	}
	return(FALSE);
}

/*=======================================================================
 |
 |		更新フラグチェック
 |
 |	BOOL	DBCheckUpdate(dbp)
 |
 |		DBX	*dbp;		ＤＢポインタ
 |
 |		BOOL	返値		TRUE ：更新された
 |					FALSE：更新されていない
 |
 =======================================================================*/
BOOL	DBCheckUpdate(DB *dbp)
{
	return(dbp->updateFlag);
}

/*=======================================================================
 |
 |		更新フラグリセット
 |
 |	VOID	DBResetUpdate(dbp)
 |
 |		DBX	*dbp;		ＤＢポインタ
 |
 =======================================================================*/
VOID	DBResetUpdate(DB *dbp)
{
	dbp->updateFlag = FALSE;
}

/*=======================================================================
 |
 |		削除レコードのスキップ
 |
 |	VOID	SkipDeleted(dbp, n)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		SHORT	n;		１：後ろへスキップ　－１：前へスキップ
 |
 =======================================================================*/
static	VOID	SkipDeleted(DB *dbp, SHORT n)
{
	DBF	*dp;

	if (_dbSetDeleted != FALSE && n != 0) {
		dp = dbp->dp;
		while (!(dbp->bof || dbp->eof) && DBFCheckDeleted(dp, dbp->rno)) {
			Skip(dbp, (LONG)n);
		}
	}
}

/*=======================================================================
 |
 |		レコードの前後移動
 |
 |	VOID	Skip(dbp, n)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		LONG	n;		移動レコード数
 |
 =======================================================================*/
static	VOID	Skip(DB *dbp, LONG n)
{
	DBF	*dp;
	IDX	*ip;
	LONG	rno;

	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	if (ip == NULL) {
		/* インデックスファイルがない場合 */
		rno = dbp->rno + n;
		if (rno <= 0)
			CheckBof(dbp, 0L);
		else if (rno > dp->dhp->nRec)
			CheckEof(dbp, 0L);
		else
			CheckEof(dbp, rno);
	} else {
		/* インデックスファイルがある場合 */
		if (n > 0) {
			if (dbp->bof)
				rno = IDXTop(ip);
			while (n-- != 0) {
				rno = IDXNext(ip);
				if (rno == 0)
					break;
			}
			CheckEof(dbp, rno);
		} else if (n < 0) {
			if (dbp->eof) {
				rno = IDXBottom(ip);
				n++;
			}
			while (n++ != 0) {
				rno = IDXBack(ip);
				if (rno == 0)
					break;
			}
			CheckBof(dbp, rno);
		}
	}
}

/*=======================================================================
 |
 |		ＢＯＦチェック
 |
 |	VOID	CheckBof(dbp, rno)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		LONG	rno;		レコード番号
 |
 =======================================================================*/
static	VOID	CheckBof(DB *dbp, LONG rno)
{
	if (dbp->dp->dhp->nRec == 0) {
		/* レコードが１件もない場合 */
		dbp->rno = 1;
		dbp->bof = TRUE;
		dbp->eof = TRUE;
	} else {
		if (rno == 0) {
			dbp->rno = 1;
			dbp->bof = TRUE;
			dbp->eof = FALSE;
		} else {
			dbp->rno = rno;
			dbp->bof = FALSE;
			dbp->eof = FALSE;
		}
	}
}

/*=======================================================================
 |
 |		ＥＯＦチェック
 |
 |	VOID	CheckEof(dbp, rno)
 |
 |		DB	*dbp;		ＤＢポインタ
 |		LONG	rno;		レコード番号
 |
 =======================================================================*/
static	VOID	CheckEof(DB *dbp, LONG rno)
{
	if (dbp->dp->dhp->nRec == 0) {
		/* レコードが１件もない場合 */
		dbp->rno = 1;
		dbp->bof = TRUE;
		dbp->eof = TRUE;
	} else {
		if (rno == 0) {
			dbp->rno = dbp->dp->dhp->nRec + 1;
			dbp->bof = FALSE;
			dbp->eof = TRUE;
		} else {
			dbp->rno = rno;
			dbp->bof = FALSE;
			dbp->eof = FALSE;
		}
	}
}


/*=======================================================================
 |
 |		データファイルオープン
 |
 |	DBF	*DBFOpen(fileName)
 |
 |		CHAR	*fileName;	データファイル名
 |
 |		返値			ＤＢＦポインタ
 |
 =======================================================================*/
DBF	*DBFOpen(CHAR *fileName)
{
	DBF	*dp;
	FIELD	*flp;
	CHAR	*rbp;
	USHORT	i;

	/* データファイル管理情報エリア確保 */
	if ((dp = malloc(sizeof(DBF))) == 0) {
		DBErr(IE_MEMORY);
		return(NULL);
	}
	memset(dp, 0, sizeof(DBF));

	/* データファイルオープン */
	if ((dp->fh = open(fileName, O_BINARY|O_RDWR)) == -1) {
		DBFClose(dp);
		DBErr(IE_NOFILE);
		return(NULL);
	}

	strcpy(dp->fileName, fileName);	/* ファイル名セーブ */

	/* ヘッダ情報エリア確保 */
	if ((dp->dhp = malloc(sizeof(DBF_H))) == 0) {
		DBFClose(dp);
		DBErr(IE_MEMORY);
		return(NULL);
	}

	/* ヘッダ情報読み込み */
	if (read(dp->fh, dp->dhp, sizeof(DBF_H)) != sizeof(DBF_H)) {
		DBFClose(dp);
		DBErr(IE_READ);
		return(NULL);
	}

	/* フィールド情報エリア確保 */
	dp->nField = (dp->dhp->lHeader - 33) / 32;
	if ((dp->flp = malloc(sizeof(FIELD) * dp->nField)) == 0) {
		DBFClose(dp);
		DBErr(IE_MEMORY);
		return(NULL);
	}

	/* フィールド情報読み込み */
	if (read(dp->fh, dp->flp, sizeof(FIELD) * dp->nField) !=
					(int)(sizeof(FIELD) * dp->nField)) {
		DBFClose(dp);
		DBErr(IE_READ);
		return(NULL);
	}

	/* レコードバッファ確保 */
	if ((dp->rbp = malloc(dp->dhp->lRec)) == 0) {
		DBFClose(dp);
		DBErr(IE_MEMORY);
		return(NULL);
	}

	/* フィールドポインタセット */
	flp = dp->flp;
	rbp = dp->rbp + 1;
	for (i = 0; i < dp->nField; i++) {
		flp->fad = rbp;
		rbp += flp->lField;
		flp++;
	}

	return(dp);
}

/*=======================================================================
 |
 |		データファイル作成
 |
 |	DBF	*DBFCreate(fileName, dip, nField)
 |
 |		CHAR	*fileName;	データファイル名
 |		DBF_I	*dip;		データファイル情報
 |		SHORT	nField;		フィールド数
 |
 |		返値			ＤＢＦポインタ
 |
 =======================================================================*/
DBF	*DBFCreate(CHAR *fileName, DBF_I *dip, SHORT nField)
{
	DBF	*dp;
	DBF_H	*dhp;
	FIELD	*flp;
	CHAR	*rbp;
	SHORT	i;
	static	CHAR	cr_eof[2] = {0x0d, 0x1a};

	/* データファイル管理情報エリア確保 */
	if ((dp = malloc(sizeof(DBF))) == 0) {
		DBErr(IE_MEMORY);
		return(NULL);
	}
	memset(dp, 0, sizeof(DBF));

	/* データファイルオープン */
	if ((dp->fh = open(fileName, O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		DBFClose(dp);
		DBErr(IE_FOPEN);
		return(NULL);
	}

	strcpy(dp->fileName, fileName);	/* ファイル名セーブ */

	/* ヘッダ情報エリア確保 */
	if ((dhp = malloc(sizeof(DBF_H))) == 0) {
		DBFClose(dp);
		DBErr(IE_MEMORY);
		return(NULL);
	}
	memset(dhp, 0, sizeof(DBF_H));
	dp->dhp = dhp;

	/* ヘッダ情報設定 */
	dhp->flag = 0x03;
	dhp->lHeader = (nField + 1) * 32 + 1;

	/* ヘッダ情報書き込み */
	if (write(dp->fh, dp->dhp, sizeof(DBF_H)) != sizeof(DBF_H)) {
		DBFClose(dp);
		DBErr(IE_WRITE);
		return(NULL);
	}

	/* フィールド情報エリア確保 */
	dp->nField = nField;
	if ((dp->flp = malloc(sizeof(FIELD) * nField)) == 0) {
		DBFClose(dp);
		DBErr(IE_MEMORY);
		return(NULL);
	}
	memset(dp->flp, 0, sizeof(FIELD) * nField);

	/* フィールド情報設定 */
	for (i = 0, flp = dp->flp, dhp->lRec = 1; i < nField; i++, flp++) {
		memcpy(flp->name, dip->name, 11);
		flp->type = dip->type;
		flp->lField = dip->lField;
		flp->lDec = dip->lDec;
		dhp->lRec += dip->lField;
		dip++;
	}

	/* フィールド情報書き込み */
	if (write(dp->fh, dp->flp, sizeof(FIELD) * dp->nField) !=
					(int)(sizeof(FIELD) * dp->nField)) {
		DBFClose(dp);
		DBErr(IE_WRITE);
		return(NULL);
	}

	/* 区切り文字＆ＥＯＦ書き込み */
	if (write(dp->fh, cr_eof, 2) != 2) {
		DBFClose(dp);
		DBErr(IE_WRITE);
		return(NULL);
	}

	/* レコードバッファ確保 */
	if ((dp->rbp = malloc(dhp->lRec)) == 0) {
		DBFClose(dp);
		DBErr(IE_MEMORY);
		return(NULL);
	}

	/* フィールドポインタセット */
	flp = dp->flp;
	rbp = dp->rbp + 1;
	for (i = 0; i < nField; i++) {
		flp->fad = rbp;
		rbp += flp->lField;
		flp++;
	}

	dp->update = TRUE;

	return(dp);
}

/*=======================================================================
 |
 |		レコード読み込み
 |
 |	VOID	DBFRead(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		LONG	rno;		レコード番号
 |
 =======================================================================*/
VOID	DBFRead(DBF *dp, LONG rno)
{
	DBF_H	*dhp;

	dhp = dp->dhp;

	/* レコード番号が範囲外ならエラー */
	if (rno <= 0 || rno > dhp->nRec) {
		DBErr(IE_RECNO);
		return;
	}

	if (rno != dp->rno) {
		/* 読み込み */
		lseek(dp->fh, dhp->lHeader + dhp->lRec*(rno-1), SEEK_SET);
		if (read(dp->fh, dp->rbp, dhp->lRec) != (int)dhp->lRec)
			DBErr(IE_READ);

		dp->rno = rno;	/* カレントレコード番号セット */
	}
}

/*=======================================================================
 |
 |		レコード書き込み
 |
 |	VOID	DBFWrite(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		LONG	rno;		レコード番号
 |
 =======================================================================*/
VOID	DBFWrite(DBF *dp, LONG rno)
{
	DBF_H	*dhp;

	dhp = dp->dhp;

	/* レコード番号が範囲外ならエラー */
	if (rno <= 0 || rno > dhp->nRec) {
		DBErr(IE_RECNO);
		return;
	}

	/* 書き込み */
	lseek(dp->fh, dhp->lHeader + dhp->lRec*(rno-1), SEEK_SET);
	if (write(dp->fh, dp->rbp, dhp->lRec) != (int)dhp->lRec)
		DBErr(IE_WRITE);

	dp->rno = rno;		/* カレントレコード番号セット */
	dp->update = TRUE;		/* ファイル更新フラグセット */
}

/*=======================================================================
 |
 |		データファイルクローズ
 |
 |	VOID	DBFClose(dp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |
 =======================================================================*/
VOID	DBFClose(DBF *dp)
{
	DBF_H	*dhp;
	LONG	ltime;
	struct	tm	*tmp;

	if (dp->fh != -1) {
		if (dp->update) {
			/* 最終更新日書き込み */
			dhp = dp->dhp;
			time(&ltime);
			tmp = localtime(&ltime);
			dhp->date[0] = (CHAR)tmp->tm_year;
			dhp->date[1] = (CHAR)(tmp->tm_mon + 1);
			dhp->date[2] = (CHAR)tmp->tm_mday;
			dp->wFlag = TRUE;
		}

		DBFFlush(dp);		/* ファイル書き込み */
		close(dp->fh);		/* ファイルクローズ */
	}

	if (dp->dhp != NULL)
		free(dp->dhp);		/* ヘッダ情報エリア解放 */

	if (dp->flp != NULL)
		free(dp->flp);		/* フィールド情報エリア解放 */

	if (dp->rbp != NULL)
		free(dp->rbp);		/* レコードバッファエリア解放 */

	free(dp);		/* データファイル管理情報エリア解放 */
}

/*=======================================================================
 |
 |		データファイル強制掃き出し
 |
 |	VOID	DBFFlush(dp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |
 =======================================================================*/
VOID	DBFFlush(DBF *dp)
{
	static	CHAR	eof[1] = {0x1a};
	DBF_H	*dhp;

	if (dp->wFlag) {
		dhp = dp->dhp;

		/* ヘッダ書き込み */
		lseek(dp->fh, 0L, SEEK_SET);
		if (write(dp->fh, dp->dhp, sizeof(DBF_H)) != sizeof(DBF_H))
			DBErr(IE_WRITE);

		/* ＥＯＦ書き込み */
		lseek(dp->fh, dhp->lHeader + dhp->lRec * dhp->nRec, SEEK_SET);
		if (write(dp->fh, eof, 1) != 1)
			DBErr(IE_WRITE);

		dp->wFlag = FALSE;
	}

	close(dp->fh);
	dp->fh = open(dp->fileName, O_BINARY|O_RDWR);
}

/*=======================================================================
 |
 |		削除マークセット
 |
 |	VOID	DBFDelete(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		LONG	rno;		レコード番号
 |
 =======================================================================*/
VOID	DBFDelete(DBF *dp, LONG rno)
{
	DBFRead(dp, rno);	/* レコード読み込み */
	*dp->rbp = '*';		/* 削除マークセット */
	DBFWrite(dp, rno);	/* レコード書き込み */
}

/*=======================================================================
 |
 |		削除マーク取り消し
 |
 |	VOID	DBFRecall(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		LONG	rno;		レコード番号
 |
 =======================================================================*/
VOID	DBFRecall(DBF *dp, LONG rno)
{
	DBFRead(dp, rno);	/* レコード読み込み */
	*dp->rbp = ' ';		/* 削除マーク取り消し */
	DBFWrite(dp, rno);	/* レコード書き込み */
}

/*=======================================================================
 |
 |		削除マークがついているレコードをファイルから削除
 |
 |	VOID	DBFPack(dp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |
 =======================================================================*/
VOID	DBFPack(DBF *dp)
{
	DBF_H	*dhp;
	LONG	rRno, wRno;

	dhp = dp->dhp;

	/* 削除マークが付いているレコードを削除する */
	for (rRno = wRno = 0; rRno < dhp->nRec; ) {
		DBFRead(dp, ++rRno);
		if (dp->rbp[0] != '*') {
			if (rRno != ++wRno) {
				DBFWrite(dp, wRno);
			}
		}
	}

	/* ファイルサイズを調整する */
	chsize(dp->fh, dhp->lHeader + dhp->lRec * wRno);

	/* レコード数セット */
	dhp->nRec = wRno;
	dp->wFlag = TRUE;

	dp->rno = 0;
}

/*=======================================================================
 |
 |		削除レコードチェック
 |
 |	SHORT	DBFCheckDeleted(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		long	rno;		レコード番号
 |
 |		返値			０：通常レコード　１：削除レコード
 |
 =======================================================================*/
SHORT	DBFCheckDeleted(DBF *dp, long rno)
{
	DBFRead(dp, rno);
	return(*dp->rbp == '*' ? 1 : 0);
}

/*=======================================================================
 |
 |		インデックスファイルオープン
 |
 |	IDX	*IDXOpen(fileName)
 |
 |		CHAR	*fileName;	インデックスファイル名
 |
 |		返値			ＩＤＸポインタ
 |
 =======================================================================*/
IDX	*IDXOpen(CHAR *fileName)
{
	IDX	*ip;

	/* インデックス管理情報エリア確保 */
	if ((ip = malloc(sizeof(IDX))) == 0) {
		DBErr(IE_MEMORY);
		return(NULL);
	}
	memset(ip, 0, sizeof(IDX));

	/* インデックスファイルオープン */
	if ((ip->fh = open(fileName, O_BINARY|O_RDWR)) == -1) {
		IDXClose(ip);
		DBErr(IE_NOFILE);
		return(NULL);
	}

	strcpy(ip->fileName, fileName);	/* ファイル名セーブ */

	/* ヘッダ情報エリア確保 */
	if ((ip->ihp = malloc(sizeof(IDX_H))) == 0) {
		IDXClose(ip);
		DBErr(IE_MEMORY);
		return(NULL);
	}

	/* ヘッダ情報読み込み */
	if (read(ip->fh, ip->ihp, sizeof(IDX_H)) != sizeof(IDX_H)) {
		IDXClose(ip);
		DBErr(IE_READ);
		return(NULL);
	}

	return(ip);
}

/*=======================================================================
 |
 |		インデックスファイル作成
 |
 |	IDX	*IDXCreate(fileName, key, uniq)
 |
 |		CHAR	*fileName;	インデックスファイル名
 |		CHAR	*key;		キー
 |		SHORT	uniq;		０：キーの重複を許す　１：許さない
 |
 |		返値			ＩＤＸポインタ
 |
 =======================================================================*/
IDX	*IDXCreate(CHAR *fileName, CHAR *key, SHORT uniq)
{
	IDX	*ip;
	IDX_H	*ihp;

	/* インデックス管理情報エリア確保 */
	if ((ip = malloc(sizeof(IDX))) == 0) {
		DBErr(IE_MEMORY);
		return(NULL);
	}
	memset(ip, 0, sizeof(IDX));

	/* インデックスファイルオープン */
	if ((ip->fh = open(fileName, O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		IDXClose(ip);
		DBErr(IE_NOFILE);
		return(NULL);
	}

	strcpy(ip->fileName, fileName);	/* ファイル名セーブ */

	/* ヘッダ情報エリア確保 */
	if ((ihp = malloc(sizeof(IDX_H))) == 0) {
		IDXClose(ip);
		DBErr(IE_MEMORY);
		return(NULL);
	}
	ip->ihp = ihp;

	/* ヘッダ情報設定 */
	memset(ihp, 0, sizeof(IDX_H));
	sprintf(ihp->index, "%s ", key);
	ihp->uniq = (CHAR)(uniq ? 1 : 0);

	return(ip);
}

/*=======================================================================
 |
 |		インデックスファイル情報設定
 |
 |	VOID	IDXSetInfo(ip, type, len)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		SHORT	type;		０：文字型　１：数値型
 |		SHORT	len;		キーの長さ
 |
 =======================================================================*/
VOID	IDXSetInfo(IDX *ip, SHORT type, SHORT len)
{
	IDX_H	*ihp;

	ihp = ip->ihp;

	ihp->type = type;		/* データタイプ設定 */
	ihp->lKey = len;		/* キー長設定 */
	ihp->lRec = ((ihp->lKey - 1) & ~3) + 12;	/* レコード長設定 */
	ihp->order = (BLOCK_SIZE - 4) / ihp->lRec;	/* 次数設定 */
}

/*=======================================================================
 |
 |		インデックスファイル読み込み
 |
 |	IDX_B	*IDXRead(ip, bno)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		LONG	bno;		ブロック番号
 |
 |		返値			ブロック情報へのポインタ
 |
 =======================================================================*/
IDX_B	*IDXRead(IDX *ip, LONG bno)
{
	IDX_B	**bpp, *bp;

	/* ブロックバッファポインタチェック */
	if (ip->bp == NULL)
		bpp = &ip->top;
	else
		bpp = &ip->bp->fwp;

	if (*bpp == NULL) {
		/* ブロック情報エリア確保 */
		if ((bp = malloc(sizeof(IDX_B))) == 0) {
			DBErr(IE_MEMORY);
			return(NULL);
		}
		memset(bp, 0, sizeof(IDX_B));

		/* チェイン処理 */
		*bpp = bp;
		bp->bwp = ip->bottom;
		ip->bottom = bp;
	} else
		bp = *bpp;

	if (bp->bno != bno) {
		if (bno != 0) {
			/* ファイル読み込み */
			bp->bno = bno;
			lseek(ip->fh, bno * BLOCK_SIZE, SEEK_SET);
			if (read(ip->fh, bp->block, BLOCK_SIZE) != BLOCK_SIZE)
				DBErr(IE_READ);
		}
	}

	ip->bp = bp;

	return(bp);
}

/*=======================================================================
 |
 |		ブロック情報エリア確保
 |
 |	IDX_B	*IDXGetBlk(ip, bno)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		LONG	bno;		ブロック番号
 |
 |		返値			ブロック情報へのポインタ
 |
 =======================================================================*/
IDX_B	*IDXGetBlk(IDX *ip, LONG bno)
{
	IDX_B	*bp;

	bp = IDXRead(ip, 0L);	/* エリア確保 */
	bp->bno = bno;		/* ブロック番号セット */

	return(bp);
}

/*=======================================================================
 |
 |		インデックスファイル書き込み
 |
 |	VOID	IDXWrite(ip, bp)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		IDX_B	*bp;		ブロック情報ポインタ
 |
 =======================================================================*/
VOID	IDXWrite(IDX *ip, IDX_B *bp)
{
	lseek(ip->fh, bp->bno * BLOCK_SIZE, SEEK_SET);
	if (write(ip->fh, bp->block, BLOCK_SIZE) != BLOCK_SIZE)
		DBErr(IE_WRITE);
}

/*=======================================================================
 |
 |		インデックスファイルクローズ
 |
 |	VOID	IDXClose(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
VOID	IDXClose(IDX *ip)
{
	IDX_B	*bp, *fwp;

	if (ip->fh != -1) {
		IDXFlush(ip);		/* ファイル書き込み */
		close(ip->fh);		/* ファイルクローズ */
	}

	/* ブロック情報エリア解放 */
	for (bp = ip->top; bp != NULL; bp = fwp) {
		fwp = bp->fwp;
		free(bp);
	}

	if (ip->ihp != NULL)
		free(ip->ihp);		/* ヘッダ情報エリア解放 */

	free(ip);		/* インデックス管理情報エリア解放 */
}

/*=======================================================================
 |
 |		インデックスファイル強制掃き出し
 |
 |	VOID	IDXFlush(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
VOID	IDXFlush(IDX *ip)
{
	if (ip->wFlag) {
		/* ヘッダ情報書き込み */
		lseek(ip->fh, 0L, SEEK_SET);
		if (write(ip->fh, ip->ihp, BLOCK_SIZE) != BLOCK_SIZE)
			DBErr(IE_WRITE);
		ip->wFlag = FALSE;
	}

	close(ip->fh);
	ip->fh = open(ip->fileName, O_BINARY|O_RDWR);
}

/*=======================================================================
 |
 |		検索
 |
 |	LONG	IDXSearch(ip, key, len)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		CHAR	*key;		検索する文字列
 |		SHORT	len;		検索する文字列の長さ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
LONG	IDXSearch(IDX *ip, VOID *key, SHORT len)
{
	IDX_B	*bp;
	CHAR	*bufp;
	SHORT	m;
	SHORT	f;
	IDX_H	*ihp;
	LONG	bno;
	LONG	rno;

	ip->bp = NULL;		/* ブロックポインタ初期化 */

	ihp = ip->ihp;		/* インデックスヘッダポインタセット */
	bno = ihp->root;	/* ルートブロックＮｏ．セット */
	for (;;) {
		bp = IDXRead(ip, bno);	/* ブロックデータ読み込み */

		bufp = bp->block;
		m = *(SHORT *)bufp;	/* ブロック内データ個数取り出し */
		bufp += 4;
		for (bp->cp = 0; bp->cp < m; bp->cp++) {
			ip->keyp = bufp + 8;
			f = IDXCompare(ip, key, len);	/* キーの比較 */
			if (f == 0) {
				/* 一致した場合 */
				rno = *(LONG *)(bufp+4);
				if (rno != 0)
					return(rno);
				else
					break;
			} else if (f < 0)
				break;
			bufp += ihp->lRec;
		}

		if ((bno = *(LONG *)bufp) == 0)
			break;
	}

	return(0L);	/* 一致するキーが存在しない */
}

/*=======================================================================
 |
 |		キーの比較
 |
 |	SHORT	IDXCompare(ip, key, len)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		CHAR	*key;		比較する文字列
 |		SHORT	len;		比較する文字列の長さ
 |
 |		返値			比較結果
 |
 =======================================================================*/
SHORT	IDXCompare(IDX *ip, VOID *key, SHORT len)
{
	return(memcmp(key, ip->keyp, len));
}

/*=======================================================================
 |
 |		キーの挿入
 |
 |	VOID	IDXStore(ip, key, rno)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		CHAR	*key;		挿入するキー
 |		LONG	rno;		レコード番号
 |
 =======================================================================*/
VOID	IDXStore(IDX *ip, CHAR *key, LONG rno)
{
	IDX_B	*bp;
	IDX_H	*ihp;
	LONG	bno;
	SHORT	m, m1, m2;
	CHAR	*bufp;
	CHAR	keyBuf[100];

	ihp = ip->ihp;

	/* 挿入位置検索 */
	if (IDXCount(ip, key, ihp->lKey) != 0) {
		if (ihp->uniq) {
			DBErr(IE_DBLKEY);
			return;
		}
	}

	bno = 0;
	bp = ip->bp;
	for (;;) {
		if (bp == NULL) {
			/* ルートブロック再構築 */
			bp = IDXGetBlk(ip, ihp->nBlock++);
			*(SHORT *)bp->block = 0;
			*(LONG *)(bp->block + 4) = ihp->root;
			ihp->root = bp->bno;
			bp->cp = 0;
		}
		bufp = bp->block;
		m = *(SHORT *)bufp;	/* ブロック内データ個数取り出し */
		bufp += 8 + bp->cp * ihp->lRec;

		/* ブロック内に挿入 */
		memcpyr(bufp + ihp->lRec, bufp, ihp->lRec * (m - bp->cp));
		*(LONG *)bufp = rno;
		memcpy(bufp + 4, key, ihp->lKey);
		*((LONG *)(bufp + ihp->lRec - 4)) = bno;

		if (++m > (SHORT)ihp->order) {
			/* ブロックの分割 */
			m1 = (m + 1) / 2;
			m2 = m - m1;
			bufp = bp->block + 4 + m1 * ihp->lRec;
			if (rno == 0)
				m1--;
			*(SHORT *)bp->block = m1;
			key = keyBuf;
			memcpy(key, bufp - ihp->lRec + 8, ihp->lKey);
			IDXWrite(ip, bp);

			bno = bp->bno = ihp->nBlock++;
			ip->wFlag = TRUE;
			*(SHORT *)bp->block = m2;
			memcpy(bp->block + 4, bufp, m2 * ihp->lRec + 4);
			rno = 0;
			IDXWrite(ip, bp);

			bp = bp->bwp;
		} else {
			*(SHORT *)bp->block = m;
			IDXWrite(ip, bp);
			break;
		}
	}
}

/*	上位アドレスからのメモリコピー */
static	VOID	memcpyr(CHAR *dst, CHAR *src, SHORT size)
{
	dst += size;
	src += size;
	while (size--)
		*(--dst) = *(--src);
}

/*=======================================================================
 |
 |		キーの削除
 |
 |	VOID	IDXDelete(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
VOID	IDXDelete(IDX *ip)
{
	IDX_H	*ihp;
	IDX_B	*bp;
	CHAR	*bufp, *key;
	SHORT	m;
	BOOL	loopSw;

	ihp = ip->ihp;
	key = NULL;
	bp = ip->bp;
	loopSw = TRUE;
	while (loopSw) {
		if (bp == NULL)
			break;

		bufp = bp->block;
		m = *(SHORT *)bufp;	/* ブロック内データ個数取り出し */
		bufp += 4 + bp->cp * ihp->lRec;
		if (key == NULL) {
			if (m == 0) {
				*(SHORT *)bufp = 0;
			} else {
				if (*(SHORT *)bufp == 0 && bp->cp == m-1) {
					if (m > 1)
						key = bufp - ihp->lRec + 8;
				} else if (bp->cp == m) {
					/* ブロック内最終キー */
					key = bufp - ihp->lRec + 8;
				} else {
					/* キー削除 */
					memcpy(bufp, bufp + ihp->lRec
					, ihp->lRec * (m - bp->cp - 1) + 4);
					loopSw = FALSE;
				}
				(*(SHORT *)bp->block)--;
			}
				
			IDXWrite(ip, bp);
		} else {
			if (bp->cp != m) {
				/* 上位ブロックのキー入れ替え */
				memcpy(bufp + 8, key, ihp->lKey);
				IDXWrite(ip, bp);
				loopSw = FALSE;
			}
		}
		bp = bp->bwp;
	}
}

/*=======================================================================
 |
 |		次レコード番号取り出し
 |
 |	LONG	IDXNext(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
LONG	IDXNext(IDX *ip)
{
	CHAR	*bufp;
	SHORT	m;
	LONG	bno;
	LONG	rno;
	IDX_H	*ihp;
	IDX_B	*bp;

	ihp = ip->ihp;
	for (;;) {
		if ((bp = ip->bp) == NULL)
			break;
		bp->cp++;		/* カレントポインタ更新 */
		bufp = bp->block;
		m = *(SHORT *)bufp;	/* ブロック内データ個数取り出し */

		bufp += 4 + bp->cp * ihp->lRec;
		for (; bp->cp < m; bp->cp++) {
			if ((rno = *(LONG *)(bufp+4)) != 0) {
				ip->keyp = bufp + 8;
				return(rno);
			}
			if (*(LONG *)bufp != 0)
				break;
			bufp += ihp->lRec;
		}

		if (bp->cp <= m && (bno = *(LONG *)bufp) != 0) {
			/* 下位ブロック読み込み */
			bp = IDXRead(ip, bno);
			bp->cp = -1;
		} else {
			/* 上位ブロックポインタセット */
			ip->bp = bp->bwp;
		}
	}
	return(0L);
}

/*=======================================================================
 |
 |		前レコード番号取り出し
 |
 |	LONG	IDXBack(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
LONG	IDXBack(IDX *ip)
{
	CHAR	*bufp;
	SHORT	m;
	LONG	bno;
	LONG	rno;
	IDX_H	*ihp;
	IDX_B	*bp;

	ihp = ip->ihp;
	for (;;) {
		if ((bp = ip->bp) == NULL)
			break;
		bp->cp--;		/* カレントポインタ更新 */
		bufp = bp->block;
		m = *(SHORT *)bufp;	/* ブロック内データ個数取り出し */

		bufp += 4 + bp->cp * ihp->lRec;
		for (; bp->cp >= 0; bp->cp--) {
			if (bp->cp < m) {
				if ((rno = *(LONG *)(bufp+4)) != 0) {
					ip->keyp = bufp + 8;
					return(rno);
				}
			}
			if (*(LONG *)bufp != 0)
				break;
			bufp -= ihp->lRec;
		}

		if (bp->cp >= 0 && (bno = *(LONG *)bufp) != 0) {
			/* 下位ブロック読み込み */
			bp = IDXRead(ip, bno);
			bp->cp = *(SHORT *)bp->block + 1;
		} else {
			/* 上位ブロックポインタセット */
			ip->bp = bp->bwp;
		}
	}
	return(0L);
}

/*=======================================================================
 |
 |		先頭レコード番号取り出し
 |
 |	LONG	IDXTop(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
LONG	IDXTop(IDX *ip)
{
	IDX_B	*bp;
	CHAR	*bufp;
	LONG	bno;

	ip->bp = NULL;		/* ブロックポインタ初期化 */

	bno = ip->ihp->root;	/* ルートブロック番号セット */
	for (;;) {
		bp = IDXRead(ip, bno);	/* ブロックデータ読み込み */

		bufp = bp->block;

		bp->cp = 0;	/* カレントポインタクリア */

		bufp += 4;
		if ((bno = *(LONG *)bufp) == 0)
			break;
	}

	ip->keyp = bufp + 8;
	return(*(LONG *)(bufp + 4));
}

/*=======================================================================
 |
 |		最終レコード番号取り出し
 |
 |	LONG	IDXBottom(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
LONG	IDXBottom(IDX *ip)
{
	IDX_B	*bp;
	CHAR	*bufp;
	LONG	bno;

	ip->bp = NULL;		/* ブロックポインタ初期化 */

	bno = ip->ihp->root;	/* ルートブロック番号セット */
	for (;;) {
		bp = IDXRead(ip, bno);	/* ブロックデータ読み込み */

		bufp = bp->block;

		bp->cp = *(SHORT *)bufp;	/* カレントポインタセット */

		bufp += 4 + bp->cp * ip->ihp->lRec;
		if ((bno = *(LONG *)bufp) == 0)
			break;
	}

	bp->cp--;
	bufp -= ip->ihp->lRec;
	
	ip->keyp = bufp + 8;
	return(*(LONG *)(bufp + 4));
}

/*=======================================================================
 |
 |		一致するキーの個数を求める
 |
 |	LONG	IDXCount(ip, key, len)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		CHAR	*key;		検索する文字列
 |		SHORT	len;		検索する文字列の長さ
 |
 |		返値			一致するキーの個数
 |
 =======================================================================*/
LONG	IDXCount(IDX *ip, VOID *key, SHORT len)
{
	IDX_H	*ihp;
	LONG	count;

	ihp = ip->ihp;
	count = 0;
	if (IDXSearch(ip, key, len) != 0) {
		for (;;) {
			count++;
			if (IDXNext(ip) == 0) {
				IDXBottom(ip);
				ip->bp->cp++;
				break;
			}
			if (IDXCompare(ip, key, len) != 0)
				break;
		}
	}
	return(count);
}

static	USHORT	Marge(int fh1, int fh2, SORTBLK *sbp1
		, SORTBLK *sbp2, CHAR *bufp, USHORT lRec
		, SHORT (*comp)(CHAR *, CHAR *))
{
	USHORT	nRec;
	USHORT	n1, n2, n3;
	LONG	offset1, offset2;
	CHAR	*buf1, *buf2, *buf3;
	CHAR	*p1, *p2, *p3;
	LONG	nRec1, nRec2;
	LONG	rec;

	nRec = MBLOCK / lRec;
	n1 = n2 = n3 = 0;
	nRec1 = sbp1->nRec;
	nRec2 = sbp2->nRec;
	offset1 = sbp1->offset;
	offset2 = sbp2->offset;
	buf1 = bufp;
	buf2 = buf1 + MBLOCK;
	buf3 = buf2 + MBLOCK;

	p3 = buf3;
	rec = nRec1 + nRec2;
	while (rec--) {
		if (n1 == 0 && nRec1 != 0) {
			n1 = (USHORT)min((LONG)nRec, nRec1);
			nRec1 -= n1;
			lseek(fh1, offset1, SEEK_SET);
			if (read(fh1, buf1, lRec * n1) != (int)(lRec * n1)) {
				DBErr(IE_READ);
				return(1);
			}
			offset1 += lRec * n1;
			p1 = buf1;
		}
		if (n2 == 0 && nRec2 != 0) {
			n2 = (USHORT)min((LONG)nRec, nRec2);
			nRec2 -= n2;
			lseek(fh1, offset2, SEEK_SET);
			if (read(fh1, buf2, lRec * n2) != (int)(lRec * n2)) {
				DBErr(IE_READ);
				return(1);
			}
			offset2 += lRec * n2;
			p2 = buf2;
		}

		if (n1 != 0 && (n2 == 0 || (*comp)(p1, p2) <= 0)) {
			memcpy(p3, p1, lRec);
			p1 += lRec;
			n1--;
		} else {
			memcpy(p3, p2, lRec);
			p2 += lRec;
			n2--;
		}

		p3 += lRec;
		if (++n3 == nRec) {
			if (write(fh2, buf3, lRec * n3) != (int)(lRec * n3)) {
				DBErr(IE_WRITE);
				return(1);
			}
			p3 = buf3;
			n3 = 0;
		}
	}
	if (write(fh2, buf3, lRec * n3) != (int)(lRec * n3)) {
		DBErr(IE_WRITE);
		return(1);
	}
	return(0);
}

/*=======================================================================
 |
 |		インデックスファイル構築
 |
 |	VOID	IDXMake(dp, ip)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
VOID	IDXMake(DBF *dp, IDX *ip)
{
	FIELD	*flp;
	int	fh[2];
	DBF_H	*dhp;
	IDX_H	*ihp;
	SORTBLK *sbp;
	USHORT	nRec, lRec, nBlock, block, block1, block2, n;
	USHORT	type, klen;
	CHAR	*bufp;
	CHAR	tmpFile[2][80];
	USHORT	tmp, tmp1, tmp2;
	LONG	rec;
	USHORT	i;
	CHAR	*p;
	CHAR	kbuf[100];
	SHORT	(*comp)();

	fh[0] = fh[1] = -1;
	bufp = NULL;
	sbp = NULL;

	ihp = ip->ihp;

	/* キー情報チェック */
	strcpy(kbuf, ihp->index);
	p = strtok(kbuf, "+ ");
	type = -1;
	klen = 0;
	while (p) {
		if ((flp = GetField(dp, p)) != NULL) {
			switch (flp->type) {
			case 'C':
				if (type == 1) {
					DBErr(IE_IDXKEY);
					return;
				}
				type = 0;
				klen += flp->lField;
				break;
			case 'D':
				if (type == 1) {
					DBErr(IE_IDXKEY);
					return;
				}
				type = 0;
				klen += 8;
				break;
			default:
				DBErr(IE_IDXKEY);
				return;
			}
		} else {
			DBErr(IE_IDXKEY);
			return;
		}

		p = strtok(NULL, "+ ");
	}

	IDXSetInfo(ip, type, klen);	/* インデックス情報セット */

	compLength = klen;

	strcpy(tmpFile[0], ip->fileName);
	SetExt(tmpFile[0], "$$1");
	if ((fh[0] = open(tmpFile[0], O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		DBErr(IE_TMPFILE);
		return;
	}

	strcpy(tmpFile[1], ip->fileName);
	SetExt(tmpFile[1], "$$2");
	if ((fh[1] = open(tmpFile[1], O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		DBErr(IE_TMPFILE);
		goto ret;
	}

	dhp = dp->dhp;

	comp = Comp2;
	lRec = klen + 4;
	nRec = SBLOCK / lRec;
	nBlock = (USHORT)((dhp->nRec - 1) / nRec + 1);
	if ((sbp = calloc(nBlock, sizeof(SORTBLK))) == NULL) {
		DBErr(IE_MEMORY);
		goto ret;
	}

	if ((bufp = malloc(SBLOCK)) == NULL) {
		DBErr(IE_MEMORY);
		goto ret;
	}

	rec = 0;
	lseek(dp->fh, (LONG)dhp->lHeader, SEEK_SET);
	for (block = 0; block < nBlock; block++) {
		n  = (USHORT)min((LONG)nRec, dhp->nRec - rec);
		sbp[block].nRec = n;
		sbp[block].offset = lRec * rec;
		for (i = 0, p = bufp; i < n; i++, p += lRec) {
			if (read(dp->fh, dp->rbp, dhp->lRec) !=
							(int)dhp->lRec) {
				DBErr(IE_READ);
				goto ret;
			}
			*(LONG *)p = ++rec;
			GetKey(dp, ihp, p+4);
		}
		qsort(bufp, n, lRec, comp);
		if (write(fh[0], bufp, lRec * n) != (int)(lRec * n)) {
			DBErr(IE_WRITE);
			goto ret;
		}
	}
	dp->rno = 0;

	tmp1 = 0;
	tmp2 = 1;
	for ( ; nBlock != 1; nBlock = (nBlock + 1) / 2) {
		lseek(fh[tmp2], 0L, SEEK_SET);

		for (block = 0; block < nBlock/2; block++) {
			block1 = block * 2;
			block2 = block1 + 1;
			if (Marge(fh[tmp1], fh[tmp2], &sbp[block1], &sbp[block2], bufp, lRec, comp) != 0)
				goto ret;
			sbp[block].nRec = sbp[block1].nRec
						+ sbp[block2].nRec;
			sbp[block].offset = sbp[block1].offset;
		}
		if (nBlock % 2 != 0) {
			block1 = block * 2;
			lseek(fh[tmp1], sbp[block1].offset, SEEK_SET);
			for (rec = sbp[block1].nRec; rec != 0; ) {
				n = (USHORT)min((LONG)nRec, rec);
				if (read(fh[tmp1], bufp, lRec * n) !=
							(int)(lRec * n)) {
					DBErr(IE_READ);
					goto ret;
				}
				if (write(fh[tmp2], bufp, lRec * n) !=
							(int)(lRec * n)) {
					DBErr(IE_WRITE);
					goto ret;
				}
				rec -= n;
			}
			sbp[block].nRec = sbp[block1].nRec;
			sbp[block].offset = sbp[block1].offset;
		}
		tmp = tmp1;
		tmp1 = tmp2;
		tmp2 = tmp;
	}

	lseek(fh[tmp1], 0L, SEEK_SET);
	WrtIndex(ip, fh[tmp1], dhp->nRec, lRec);
ret:
	if (bufp)
		free(bufp);
	if (sbp)
		free(sbp);

	if (fh[1] != -1) {
		close(fh[1]);
		remove(tmpFile[1]);
	}
	if (fh[0] != -1) {
		close(fh[0]);
		remove(tmpFile[0]);
	}
}

static	SHORT	Comp2(CHAR *p1, CHAR *p2)
{
	SHORT	i;
	LONG	l;

	if ((i = memcmp(p1+4, p2+4, compLength)) == 0) {
		l = *(LONG *)p1 - *(LONG *)p2;
		i = (l == 0) ? 0 : (l < 0) ? -1 : 1;
	}
	return(i);
}

static	VOID	WrtIndex(IDX *ip, int fh, LONG nRec, SHORT lRec)
{
	IDX_H	*ihp;
	LONG	bno;
	SHORT	level;
	CHAR	key[100];

	chsize(ip->fh, 0L);

	ihp = ip->ihp;

	memset(key, 0, ihp->lKey);
	wnRec = nRec;
	wBno = 1;
	level = 0;
	do {
		ip->bp = NULL;
		WrtBlock(ip, fh, level++, lRec, key, &bno);
	} while (wnRec != 0);

	ihp->root = bno;
	ihp->nBlock = wBno;
	ip->wFlag = TRUE;
}

static	VOID	WrtBlock(IDX *ip, int fh, SHORT level, SHORT lRec, CHAR *key
		, LONG *bno)
{
	IDX_H	*ihp;
	IDX_B	*bp;
	CHAR	*bufp;
	USHORT	i;

	ihp = ip->ihp;
	bp = IDXRead(ip, 0L);
	bufp = bp->block + 4;

	if (level == 0) {
		for (i = 0; i < ihp->order && wnRec != 0; wnRec--) {
			if (read(fh, bufp + 4, lRec) != lRec) {
				DBErr(IE_READ);
				return;
			}
			if (ihp->uniq) {
				if (memcmp(bufp + 8, key, ihp->lKey) == 0)
					continue;
			}
			*(LONG *)bufp = 0;
			memcpy(key, bufp + 8, ihp->lKey);
			bufp += ihp->lRec;
			i++;
		}
		*(LONG *)bufp = 0;
	} else {
		if (*bno == 0)
			WrtBlock(ip, fh, level-1, lRec, key, bno);

		for (i = 0; i < ihp->order && wnRec != 0; i++) {
			*(LONG *)bufp = *bno;
			*(LONG *)(bufp+4) = 0;
			memcpy(bufp+8, key, ihp->lKey);
			*bno = 0;
			WrtBlock(ip, fh, level-1, lRec, key, bno);
			bufp += ihp->lRec;
		}
		*(LONG *)bufp = *bno;
	}

	*(SHORT *)bp->block = i;
	bp->bno = wBno++;
	IDXWrite(ip, bp);
	*bno = bp->bno;
	ip->bp = bp->bwp;
}

static	VOID	SetExt(CHAR *name, CHAR *ext)
{
	CHAR	 *p;

	if ((p = strrchr(name, '.')) != NULL)
		strcpy(p+1, ext);
	else {
		strcat(name, ".");
		strcat(name, ext);
	}
}


/*=======================================================================
 |
 |		レコード数取り出し
 |
 |	LONG	DBRecCount(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 |		返値			レコード数
 |
 =======================================================================*/
LONG	DBRecCount(DB *dbp)
{
	return(dbp->dp->dhp->nRec);
}

/*=======================================================================
 |
 |		ＢＯＦチェック
 |
 |	BOOL	DBBof(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 |		BOOL	返値		TRUE ：ＢＯＦ
 |					FALSE：ＢＯＦでない
 |
 =======================================================================*/
BOOL	DBBof(DB *dbp)
{
	return(dbp->bof);
}

/*=======================================================================
 |
 |		ＥＯＦチェック
 |
 |	BOOL	DBEof(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 |		BOOL	返値		TRUE ：ＥＯＦ
 |					FALSE：ＥＯＦでない
 |
 =======================================================================*/
BOOL	DBEof(DB *dbp)
{
	return(dbp->eof);
}

/*=======================================================================
 |
 |		カレントレコード番号取り出し
 |
 |	LONG	DBRecNo(dbp)
 |
 |		DB	*dbp;		ＤＢポインタ
 |
 |		返値			カレントレコード番号
 |
 =======================================================================*/
LONG	DBRecNo(DB *dbp)
{
	return(dbp->rno);
}

/*=======================================================================
 |
 |		削除レコード処理モード設定
 |
 |	VOID	SetDeleted(flag)
 |
 |		BOOL	flag;		FALSE：削除レコード有効
 |					TRUE ：削除レコード無効
 |
 =======================================================================*/
VOID	SetDeleted(BOOL flag)
{
	_dbSetDeleted = flag;
}


/*=======================================================================
 |
 |		エラーハンドラ設定
 |
 |	VOID	DBErrHandler(func)
 |
 |		VOID	(*func)(SHORT);	エラー処理関数へのポインタ
 |
 =======================================================================*/
VOID	DBErrHandler(VOID (*func)(SHORT))
{
	_dbErrHandler = func;
}

/*=======================================================================
 |
 |		エラー処理
 |
 |	VOID	DBErr(errCode)
 |
 |		SHORT	errCode;	エラーコード
 |
 =======================================================================*/
VOID	DBErr(SHORT errCode)
{
	_dbErrCode = errCode;

	if (_dbErrHandler != NULL) {
		/* エラーハンドラが設定されていたら呼び出す */
		(*_dbErrHandler)(errCode);
	}
}

/*=======================================================================
 |
 |		文字列から整数への変換
 |
 |	SHORT	natoi(str, len)
 |
 |		CHAR	*str;		文字列
 |		SHORT	len;		長さ
 |
 |		返値			実数値
 |
 =======================================================================*/
SHORT	natoi(CHAR *str, SHORT len)
{
	CHAR	buf[32];

	memcpy(buf, str, len);
	buf[len] = '\0';
	return(atoi(buf));
}

/*=======================================================================
 |
 |		キー取り出し
 |
 |	SHORT	GetKey(dp, ihp, kp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		IDX_H	*ihp;		ＩＤＸ＿Ｈポインタ
 |		CHAR	*kp;		キーバッファ
 |
 |		返値			０：キーなし　１：キーあり
 |
 =======================================================================*/
SHORT	GetKey(DBF *dp, IDX_H *ihp, CHAR *kp)
{
	FIELD	*flp;
	CHAR	buf[100], *p;
	SHORT	flag;

	strcpy(buf, ihp->index);
	p = strtok(buf, "+ ");
	*kp = '\0';
	flag = 0;
	while (p) {
		if ((flp = GetField(dp, p)) != NULL) {
			memcpy(kp, flp->fad, flp->lField);
			kp += flp->lField;
			flag = 1;
		} else
			return(0);

		p = strtok(NULL, "+ ");
	}

	return(flag);
}

/*=======================================================================
 |
 |		フィールド情報取り出し
 |
 |	FIELD	*GetField(dp, field)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		CHAR	*field;		フィールド名
 |
 |		返値			フィールド情報へのポインタ
 |
 =======================================================================*/
FIELD	*GetField(DBF *dp, CHAR *field)
{
	USHORT	i;
	FIELD	*flp;

	for (i = 0, flp = dp->flp; i < dp->nField; i++, flp++) {
		if (strcmp(field, flp->name) == 0)
			return(flp);
	}
	return(NULL);
}
