/************************************************************************
 *									*
 *		ＩＳＡＭライブラリ					*
 *									*
 *		名称		: インデックスファイル操作		*
 *		ファイル名	: idx.c					*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <io.h>
#include <fcntl.h>
#include <sys\types.h>
#include <sys\stat.h>
#include "isam.h"

static	void	memcpyr(char *, char *, int);

/*=======================================================================
 |
 |		インデックスファイルオープン
 |
 |	IDX	*idx_open(file_name)
 |
 |		char	*file_name;	インデックスファイル名
 |
 |		返値			ＩＤＸポインタ
 |
 =======================================================================*/
IDX	*idx_open(char *file_name)
{
	IDX	*ip;

	/* インデックス管理情報エリア確保 */
	if ((ip = malloc(sizeof(IDX))) == 0) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(ip, 0, sizeof(IDX));

	/* インデックスファイルオープン */
	if ((ip->fh = open(file_name, O_BINARY|O_RDWR)) == -1) {
		idx_close(ip);
		db_err(IE_NOFILE);
		return(NULL);
	}

	strcpy(ip->file_name, file_name);	/* ファイル名セーブ */

	/* ヘッダ情報エリア確保 */
	if ((ip->ihp = malloc(sizeof(IDX_H))) == 0) {
		idx_close(ip);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* ヘッダ情報読み込み */
	if (read(ip->fh, (char *)ip->ihp, sizeof(IDX_H)) != sizeof(IDX_H)) {
		idx_close(ip);
		db_err(IE_READ);
		return(NULL);
	}

	return(ip);
}

/*=======================================================================
 |
 |		インデックスファイル作成
 |
 |	IDX	*idx_create(file_name, key, uniq)
 |
 |		char	*file_name;	インデックスファイル名
 |		char	*key;		キー
 |		int	uniq;		０：キーの重複を許す　１：許さない
 |
 |		返値			ＩＤＸポインタ
 |
 =======================================================================*/
IDX	*idx_create(char *file_name, char *key, int uniq)
{
	IDX	*ip;
	IDX_H	*ihp;

	/* インデックス管理情報エリア確保 */
	if ((ip = malloc(sizeof(IDX))) == 0) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(ip, 0, sizeof(IDX));

	/* インデックスファイルオープン */
	if ((ip->fh = open(file_name, O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		idx_close(ip);
		db_err(IE_NOFILE);
		return(NULL);
	}

	strcpy(ip->file_name, file_name);	/* ファイル名セーブ */

	/* ヘッダ情報エリア確保 */
	if ((ihp = malloc(sizeof(IDX_H))) == 0) {
		idx_close(ip);
		db_err(IE_MEMORY);
		return(NULL);
	}
	ip->ihp = ihp;

	/* ヘッダ情報設定 */
	memset(ihp, 0, sizeof(IDX_H));
	sprintf(ihp->index, "%s ", key);
	ihp->uniq = uniq ? 1 : 0;

	return(ip);
}

/*=======================================================================
 |
 |		インデックスファイル情報設定
 |
 |	void	idx_setinfo(ip, type, len)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		int	type;		０：文字型　１：数値型
 |		inr	len;		キーの長さ
 |
 =======================================================================*/
void	idx_setinfo(IDX *ip, int type, int len)
{
	IDX_H	*ihp;

	ihp = ip->ihp;

	ihp->type = type;		/* データタイプ設定 */
	ihp->l_key = len;		/* キー長設定 */
	ihp->l_rec = ((ihp->l_key - 1) & ~3) + 12;	/* レコード長設定 */
	ihp->order = (BLOCK_SIZE - 4) / ihp->l_rec;	/* 次数設定 */
}

/*=======================================================================
 |
 |		インデックスファイル読み込み
 |
 |	IDX_B	*idx_read(ip, bno)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		long	bno;		ブロック番号
 |
 |		返値			ブロック情報へのポインタ
 |
 =======================================================================*/
IDX_B	*idx_read(IDX *ip, long bno)
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
			db_err(IE_MEMORY);
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
				db_err(IE_READ);
		}
	}

	ip->bp = bp;

	return(bp);
}

/*=======================================================================
 |
 |		ブロック情報エリア確保
 |
 |	IDX_B	*idx_getblk(ip, bno)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		long	bno;		ブロック番号
 |
 |		返値			ブロック情報へのポインタ
 |
 =======================================================================*/
IDX_B	*idx_getblk(IDX *ip, long bno)
{
	IDX_B	*bp;

	bp = idx_read(ip, 0L);	/* エリア確保 */
	bp->bno = bno;		/* ブロック番号セット */

	return(bp);
}

/*=======================================================================
 |
 |		インデックスファイル書き込み
 |
 |	void	idx_write(ip, bp)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		IDX_B	*bp;		ブロック情報ポインタ
 |
 =======================================================================*/
void	idx_write(IDX *ip, IDX_B *bp)
{
	lseek(ip->fh, bp->bno * BLOCK_SIZE, SEEK_SET);
	if (write(ip->fh, bp->block, BLOCK_SIZE) != BLOCK_SIZE)
		db_err(IE_WRITE);
}

/*=======================================================================
 |
 |		インデックスファイルクローズ
 |
 |	void	idx_close(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
void	idx_close(IDX *ip)
{
	IDX_B	*bp, *fwp;

	if (ip->fh != -1) {
		idx_flush(ip);		/* ファイル書き込み */
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
 |	void	idx_flush(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
void	idx_flush(IDX *ip)
{
	if (ip->w_flag != 0) {
		/* ヘッダ情報書き込み */
		lseek(ip->fh, 0L, SEEK_SET);
		if (write(ip->fh, (char *)ip->ihp, BLOCK_SIZE) != BLOCK_SIZE)
			db_err(IE_WRITE);
		ip->w_flag = 0;
	}

	close(ip->fh);
	ip->fh = open(ip->file_name, O_BINARY|O_RDWR);
}

/*=======================================================================
 |
 |		検索
 |
 |	long	idx_search(ip, key, len)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		char	*key;		検索する文字列
 |		int	len;		検索する文字列の長さ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
long	idx_search(IDX *ip, void *key, int len)
{
	IDX_B	*bp;
	char	*bufp;
	register int	m;
	int	f;
	IDX_H	*ihp;
	long	bno;
	long	rno;

	ip->bp = NULL;		/* ブロックポインタ初期化 */

	ihp = ip->ihp;		/* インデックスヘッダポインタセット */
	bno = ihp->root;	/* ルートブロックＮｏ．セット */
	for (;;) {
		bp = idx_read(ip, bno);	/* ブロックデータ読み込み */

		bufp = bp->block;
		m = *(int *)bufp;	/* ブロック内データ個数取り出し */
		bufp += 4;
		for (bp->cp = 0; bp->cp < m; bp->cp++) {
			ip->keyp = bufp + 8;
			f = idx_compare(ip, key, len);	/* キーの比較 */
			if (f == 0) {
				/* 一致した場合 */
				rno = *(long *)(bufp+4);
				if (rno != 0)
					return(rno);
				else
					break;
			} else if (f < 0)
				break;
			bufp += ihp->l_rec;
		}

		if ((bno = *(long *)bufp) == 0)
			break;
	}

	return(0L);	/* 一致するキーが存在しない */
}

/*=======================================================================
 |
 |		キーの比較
 |
 |	int	idx_compare(ip, key, len)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		char	*key;		比較する文字列
 |		int	len;		比較する文字列の長さ
 |
 |		返値			比較結果
 |
 =======================================================================*/
int	idx_compare(IDX *ip, void *key, int len)
{
	double	d;

	if (ip->ihp->type == 0) {
		/* 文字型の場合 */
		return(memcmp(key, ip->keyp, len));
	} else {
		/* 数値型の場合 */
		d = *(double *)key - *(double *)ip->keyp;
		if (d > 0.0)
			return(1);
		else if (d < 0.0)
			return(-1);
		else
			return(0);
	}
}

/*=======================================================================
 |
 |		キーの挿入
 |
 |	void	idx_store(ip, key, rno)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		char	*key;		挿入するキー
 |		long	rno;		レコード番号
 |
 =======================================================================*/
void	idx_store(IDX *ip, char *key, long rno)
{
	IDX_B	*bp;
	IDX_H	*ihp;
	long	bno;
	int	m, m1, m2;
	char	*bufp;
	char	key_buf[100];

	ihp = ip->ihp;

	/* 挿入位置検索 */
	if (idx_count(ip, key, ihp->l_key) != 0) {
		if (ihp->uniq) {
			db_err(IE_DBLKEY);
			return;
		}
	}

	bno = 0;
	bp = ip->bp;
	for (;;) {
		if (bp == NULL) {
			/* ルートブロック再構築 */
			bp = idx_getblk(ip, ihp->n_block++);
			*(int *)bp->block = 0;
			*(long *)(bp->block + 4) = ihp->root;
			ihp->root = bp->bno;
			bp->cp = 0;
		}
		bufp = bp->block;
		m = *(int *)bufp;	/* ブロック内データ個数取り出し */
		bufp += 8 + bp->cp * ihp->l_rec;

		/* ブロック内に挿入 */
		memcpyr(bufp + ihp->l_rec, bufp, ihp->l_rec * (m - bp->cp));
		*(long *)bufp = rno;
		memcpy(bufp + 4, key, ihp->l_key);
		*((long *)(bufp + ihp->l_rec - 4)) = bno;

		if (++m > ihp->order) {
			/* ブロックの分割 */
			m1 = (m + 1) / 2;
			m2 = m - m1;
			bufp = bp->block + 4 + m1 * ihp->l_rec;
			if (rno == 0)
				m1--;
			*(int *)bp->block = m1;
			key = key_buf;
			memcpy(key, bufp - ihp->l_rec + 8, ihp->l_key);
			idx_write(ip, bp);

			bno = bp->bno = ihp->n_block++;
			ip->w_flag = 1;
			*(int *)bp->block = m2;
			memcpy(bp->block + 4, bufp, m2 * ihp->l_rec + 4);
			rno = 0;
			idx_write(ip, bp);

			bp = bp->bwp;
		} else {
			*(int *)bp->block = m;
			idx_write(ip, bp);
			break;
		}
	}

	/*idx_search(ip, key, ihp->l_key);*/
}

/*	上位アドレスからのメモリコピー */
static	void	memcpyr(char *dst, char *src, int size)
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
 |	void	idx_delete(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
void	idx_delete(IDX *ip)
{
	IDX_H	*ihp;
	IDX_B	*bp;
	char	*bufp, *key;
	int	loop_sw, m;

	ihp = ip->ihp;
	key = NULL;
	bp = ip->bp;
	loop_sw = 1;
	while (loop_sw) {
		if (bp == NULL)
			break;

		bufp = bp->block;
		m = *(int *)bufp;	/* ブロック内データ個数取り出し */
		bufp += 4 + bp->cp * ihp->l_rec;
		if (key == NULL) {
			if (m == 0) {
				*(int *)bufp = 0;
			} else {
				if (*(int *)bufp == 0 && bp->cp == m-1) {
					if (m > 1)
						key = bufp - ihp->l_rec + 8;
				} else if (bp->cp == m) {
					/* ブロック内最終キー */
					key = bufp - ihp->l_rec + 8;
				} else {
					/* キー削除 */
					memcpy(bufp, bufp + ihp->l_rec
					, ihp->l_rec * (m - bp->cp - 1) + 4);
					loop_sw = 0;
				}
				(*(int *)bp->block)--;
			}
				
			idx_write(ip, bp);
		} else {
			if (bp->cp != m) {
				/* 上位ブロックのキー入れ替え */
				memcpy(bufp + 8, key, ihp->l_key);
				idx_write(ip, bp);
				loop_sw = 0;
			}
		}
		bp = bp->bwp;
	}
}

/*=======================================================================
 |
 |		次レコード番号取り出し
 |
 |	long	idx_next(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
long	idx_next(IDX *ip)
{
	char	*bufp;
	register int	m;
	long	bno;
	long	rno;
	IDX_H	*ihp;
	IDX_B	*bp;

	ihp = ip->ihp;
	for (;;) {
		if ((bp = ip->bp) == NULL)
			break;
		bp->cp++;		/* カレントポインタ更新 */
		bufp = bp->block;
		m = *(int *)bufp;	/* ブロック内データ個数取り出し */

		bufp += 4 + bp->cp * ihp->l_rec;
		for (; bp->cp < m; bp->cp++) {
			if ((rno = *(long *)(bufp+4)) != 0) {
				ip->keyp = bufp + 8;
				return(rno);
			}
			if (*(long *)bufp != 0)
				break;
			bufp += ihp->l_rec;
		}

		if (bp->cp <= m && (bno = *(long *)bufp) != 0) {
			/* 下位ブロック読み込み */
			bp = idx_read(ip, bno);
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
 |	long	idx_back(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
long	idx_back(IDX *ip)
{
	char	*bufp;
	register int	m;
	long	bno;
	long	rno;
	IDX_H	*ihp;
	IDX_B	*bp;

	ihp = ip->ihp;
	for (;;) {
		if ((bp = ip->bp) == NULL)
			break;
		bp->cp--;		/* カレントポインタ更新 */
		bufp = bp->block;
		m = *(int *)bufp;	/* ブロック内データ個数取り出し */

		bufp += 4 + bp->cp * ihp->l_rec;
		for (; bp->cp >= 0; bp->cp--) {
			if (bp->cp < m) {
				if ((rno = *(long *)(bufp+4)) != 0) {
					ip->keyp = bufp + 8;
					return(rno);
				}
			}
			if (*(long *)bufp != 0)
				break;
			bufp -= ihp->l_rec;
		}

		if (bp->cp >= 0 && (bno = *(long *)bufp) != 0) {
			/* 下位ブロック読み込み */
			bp = idx_read(ip, bno);
			bp->cp = *(int *)bp->block + 1;
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
 |	long	idx_top(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
long	idx_top(IDX *ip)
{
	IDX_B	*bp;
	char	*bufp;
	long	bno;

	ip->bp = NULL;		/* ブロックポインタ初期化 */

	bno = ip->ihp->root;	/* ルートブロック番号セット */
	for (;;) {
		bp = idx_read(ip, bno);	/* ブロックデータ読み込み */

		bufp = bp->block;

		bp->cp = 0;	/* カレントポインタクリア */

		bufp += 4;
		if ((bno = *(long *)bufp) == 0)
			break;
	}

	ip->keyp = bufp + 8;
	return(*(long *)(bufp + 4));
}

/*=======================================================================
 |
 |		最終レコード番号取り出し
 |
 |	long	idx_bottom(ip)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |
 |		返値			レコード番号
 |
 =======================================================================*/
long	idx_bottom(IDX *ip)
{
	IDX_B	*bp;
	char	*bufp;
	long	bno;

	ip->bp = NULL;		/* ブロックポインタ初期化 */

	bno = ip->ihp->root;	/* ルートブロック番号セット */
	for (;;) {
		bp = idx_read(ip, bno);	/* ブロックデータ読み込み */

		bufp = bp->block;

		bp->cp = *(int *)bufp;	/* カレントポインタセット */

		bufp += 4 + bp->cp * ip->ihp->l_rec;
		if ((bno = *(long *)bufp) == 0)
			break;
	}

	bp->cp--;
	bufp -= ip->ihp->l_rec;
	
	ip->keyp = bufp + 8;
	return(*(long *)(bufp + 4));
}

/*=======================================================================
 |
 |		一致するキーの個数を求める
 |
 |	long	idx_count(ip, key, len)
 |
 |		IDX	*ip;		ＩＤＸポインタ
 |		char	*key;		検索する文字列
 |		int	len;		検索する文字列の長さ
 |
 |		返値			一致するキーの個数
 |
 =======================================================================*/
long	idx_count(IDX *ip, void *key, int len)
{
	long	count;

	count = 0;
	if (idx_search(ip, key, len) != 0) {
		for (;;) {
			count++;
			if (idx_next(ip) == 0) {
				idx_bottom(ip);
				ip->bp->cp++;
				break;
			}
			if (idx_compare(ip, key, len) != 0)
				break;
		}
	}
	return(count);
}
