/************************************************************************
 *									*
 *		ＩＳＡＭライブラリ					*
 *									*
 *		名称		: ソート関連処理			*
 *		ファイル名	: sort.c				*
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

#define	MBLOCK	8192
#define	SBLOCK	(u_int)(MBLOCK*3)

struct	sort_block	{
	long	n_rec;
	long	offset;
};

static	int	comp1(char *, char *);
static	int	comp2(char *, char *);
static	int	comp3(char *, char *);
static	int	comp_offset, comp_len;
static	int	marge(int, int , struct sort_block *, struct sort_block *, char *, int, int (*)(char *, char *));
static	long	w_n_rec, w_bno;
static	void	wrt_index(IDX *, int, long, int);
static	void	wrt_block(IDX *, int, int, int, char *, long *);
static	void	setext(char *, char *);

/*=======================================================================
 |
 |		データファイルソート
 |
 |	void	dbf_sort(dp, file_name, key)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		char	*file_name;	出力ファイル名
 |		char	*key;		ソートするキー
 |
 =======================================================================*/
void	dbf_sort(DBF *dp, char *file_name, char *key)
{
	FIELD	*flp;
	int	fh[2];
	DBF_H	*dhp;
	struct	sort_block *sbp;
	int	n_rec, l_rec, n_block, block, block1, block2, n;
	char	*bufp;
	char	tmp_file[2][80];
	int	tmp, tmp1, tmp2;
	long	rec;
	static	char	eof[1] = {0x1a};
	static	char	cr[1] = {0x0d};

	fh[0] = fh[1] = -1;
	bufp = NULL;
	sbp = NULL;

	/* フィールド情報取り出し */
	if ((flp = get_field(dp, key)) == NULL) {
		db_err(IE_NOKEY);
		return;
	}

	comp_offset = flp->fad - dp->rbp;
	comp_len = flp->l_field;

	strcpy(tmp_file[0], file_name);
	setext(tmp_file[0], "$$1");
	if ((fh[0] = open(tmp_file[0], O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		db_err(IE_TMPFILE);
		return;
	}

	strcpy(tmp_file[1], file_name);
	setext(tmp_file[1], "$$2");
	if ((fh[1] = open(tmp_file[1], O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		db_err(IE_TMPFILE);
		goto ret;
	}

	dhp = dp->dhp;
	l_rec = dhp->l_rec;
	n_rec = SBLOCK / l_rec;
	n_block = (dhp->n_rec - 1) / n_rec + 1;

	if ((sbp = calloc(n_block, sizeof(struct sort_block))) == NULL) {
		db_err(IE_MEMORY);
		goto ret;
	}

	if ((bufp = malloc(SBLOCK)) == NULL) {
		free(sbp);
		db_err(IE_MEMORY);
		goto ret;
	}

	if (write(fh[0], (char *)dp->dhp, sizeof(DBF_H)) != sizeof(DBF_H)) {
		db_err(IE_WRITE);
		goto ret;
	}
	if (write(fh[0], (char *)dp->flp, sizeof(FIELD) * dp->n_field) !=
					sizeof(FIELD) * dp->n_field) {
		db_err(IE_WRITE);
		goto ret;
	}
	if (write(fh[0], cr, 1) != 1) {
		db_err(IE_WRITE);
		goto ret;
	}

	lseek(dp->fh, (long)dhp->l_header, SEEK_SET);
	for (block = 0, rec = 0; block < n_block; block++, rec += n_rec) {
		n  = min(n_rec, dhp->n_rec - rec);
		if (read(dp->fh, bufp, l_rec * n) != l_rec * n) {
			db_err(IE_READ);
			goto ret;
		}
		qsort(bufp, n, l_rec, (int (*)(const void *, const void *))comp1);
		if (write(fh[0], bufp, l_rec * n) != l_rec * n) {
			db_err(IE_WRITE);
			goto ret;
		}
		sbp[block].n_rec = n;
		sbp[block].offset = dhp->l_header + l_rec * rec;
	}

	tmp1 = 0;
	tmp2 = 1;
	for ( ; n_block != 1; n_block = (n_block + 1) / 2) {
		lseek(fh[tmp2], 0L, SEEK_SET);
		if (write(fh[tmp2], (char *)dp->dhp, sizeof(DBF_H)) !=
							sizeof(DBF_H)) {
			db_err(IE_WRITE);
			goto ret;
		}
		if (write(fh[tmp2], (char *)dp->flp,
					sizeof(FIELD) * dp->n_field) !=
					sizeof(FIELD) * dp->n_field) {
			db_err(IE_WRITE);
			goto ret;
		}
		if (write(fh[tmp2], cr, 1) != 1) {
			db_err(IE_WRITE);
			goto ret;
		}

		for (block = 0; block < n_block/2; block++) {
			block1 = block * 2;
			block2 = block1 + 1;
			if (marge(fh[tmp1], fh[tmp2], &sbp[block1], &sbp[block2], bufp, l_rec, comp1) != 0)
				goto ret;
			sbp[block].n_rec = sbp[block1].n_rec
						+ sbp[block2].n_rec;
			sbp[block].offset = sbp[block1].offset;
		}
		if (n_block % 2 != 0) {
			block1 = block * 2;
			lseek(fh[tmp1], sbp[block1].offset, SEEK_SET);
			for (rec = sbp[block1].n_rec; rec != 0; ) {
				n = min(n_rec, rec);
				if (read(fh[tmp1], bufp, l_rec * n) !=
								l_rec * n) {
					db_err(IE_READ);
					goto ret;
				}
				if (write(fh[tmp2], bufp, l_rec * n) !=
								l_rec * n) {
					db_err(IE_WRITE);
					goto ret;
				}
				rec -= n;
			}
			sbp[block].n_rec = sbp[block1].n_rec;
			sbp[block].offset = sbp[block1].offset;
		}
		tmp = tmp1;
		tmp1 = tmp2;
		tmp2 = tmp;
	}

	if (write(fh[tmp1], eof, 1) != 1) {
		db_err(IE_WRITE);
		goto ret;
	}

	close(fh[tmp1]);
	fh[tmp1] = -1;
	remove(file_name);
	rename(tmp_file[tmp1], file_name);
ret:
	if (bufp)
		free(bufp);
	if (sbp)
		free(sbp);

	if (fh[0] != -1) {
		close(fh[0]);
		remove(tmp_file[0]);
	}
	if (fh[1] != -1) {
		close(fh[1]);
		remove(tmp_file[1]);
	}
}

static	int	comp1(char *p1, char *p2)
{
	return(memcmp(p1+comp_offset, p2+comp_offset, comp_len));
}

static	int	marge(int fh1, int fh2, struct sort_block *sbp1
		, struct sort_block *sbp2, char *bufp, int l_rec
		, int (*comp)(char *, char *))
{
	int	n_rec;
	int	n1, n2, n3;
	long	offset1, offset2;
	char	*buf1, *buf2, *buf3;
	char	*p1, *p2, *p3;
	long	n_rec1, n_rec2;
	long	rec;

	n_rec = MBLOCK / l_rec;
	n1 = n2 = n3 = 0;
	n_rec1 = sbp1->n_rec;
	n_rec2 = sbp2->n_rec;
	offset1 = sbp1->offset;
	offset2 = sbp2->offset;
	buf1 = bufp;
	buf2 = buf1 + MBLOCK;
	buf3 = buf2 + MBLOCK;

	p3 = buf3;
	rec = n_rec1 + n_rec2;
	while (rec--) {
		if (n1 == 0 && n_rec1 != 0) {
			n1 = min(n_rec, n_rec1);
			n_rec1 -= n1;
			lseek(fh1, offset1, SEEK_SET);
			if (read(fh1, buf1, l_rec * n1) != l_rec * n1) {
				db_err(IE_READ);
				return(1);
			}
			offset1 += l_rec * n1;
			p1 = buf1;
		}
		if (n2 == 0 && n_rec2 != 0) {
			n2 = min(n_rec, n_rec2);
			n_rec2 -= n2;
			lseek(fh1, offset2, SEEK_SET);
			if (read(fh1, buf2, l_rec * n2) != l_rec * n2) {
				db_err(IE_READ);
				return(1);
			}
			offset2 += l_rec * n2;
			p2 = buf2;
		}

		if (n1 != 0 && (n2 == 0 || (*comp)(p1, p2) <= 0)) {
			memcpy(p3, p1, l_rec);
			p1 += l_rec;
			n1--;
		} else {
			memcpy(p3, p2, l_rec);
			p2 += l_rec;
			n2--;
		}

		p3 += l_rec;
		if (++n3 == n_rec) {
			if (write(fh2, buf3, l_rec * n3) != l_rec * n3) {
				db_err(IE_WRITE);
				return(1);
			}
			p3 = buf3;
			n3 = 0;
		}
	}
	if (write(fh2, buf3, l_rec * n3) != l_rec * n3) {
		db_err(IE_WRITE);
		return(1);
	}
	return(0);
}

/*=======================================================================
 |
 |		インデックスファイル構築
 |
 |	void	idx_make(dp, ip)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		IDX	*ip;		ＩＤＸポインタ
 |
 =======================================================================*/
void	idx_make(DBF *dp, IDX *ip)
{
	FIELD	*flp;
	int	fh[2];
	DBF_H	*dhp;
	IDX_H	*ihp;
	struct	sort_block *sbp;
	int	n_rec, l_rec, n_block, block, block1, block2, n;
	int	type, klen;
	char	*bufp;
	char	tmp_file[2][80];
	int	tmp, tmp1, tmp2;
	long	rec;
	register int	i;
	char	*p;
	char	kbuf[100];
	int	(*comp)();

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
		if ((flp = get_field(dp, p)) != NULL) {
			switch (flp->type) {
			case 'C':
				if (type == 1) {
					db_err(IE_IDXKEY);
					return;
				}
				type = 0;
				klen += flp->l_field;
				break;
			case 'D':
				if (type == 1) {
					db_err(IE_IDXKEY);
					return;
				}
				type = 0;
				klen += 8;
				break;
			case 'N':
				if (type == 0) {
					db_err(IE_IDXKEY);
					return;
				}
				type = 1;
				klen = 8;
				break;
			default:
				db_err(IE_IDXKEY);
				return;
			}
		} else {
			db_err(IE_IDXKEY);
			return;
		}

		p = strtok(NULL, "+ ");
	}

	idx_setinfo(ip, type, klen);	/* インデックス情報セット */

	comp_offset = 4;
	comp_len = klen;

	strcpy(tmp_file[0], ip->file_name);
	setext(tmp_file[0], "$$1");
	if ((fh[0] = open(tmp_file[0], O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		db_err(IE_TMPFILE);
		return;
	}

	strcpy(tmp_file[1], ip->file_name);
	setext(tmp_file[1], "$$2");
	if ((fh[1] = open(tmp_file[1], O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		db_err(IE_TMPFILE);
		goto ret;
	}

	dhp = dp->dhp;

	comp = (ihp->type == 0) ? comp2 : comp3;
	l_rec = klen + 4;
	n_rec = SBLOCK / l_rec;
	n_block = (dhp->n_rec - 1) / n_rec + 1;
	if ((sbp = calloc(n_block, sizeof(struct sort_block))) == NULL) {
		db_err(IE_MEMORY);
		goto ret;
	}

	if ((bufp = malloc(SBLOCK)) == NULL) {
		db_err(IE_MEMORY);
		goto ret;
	}

	rec = 0;
	lseek(dp->fh, (long)dhp->l_header, SEEK_SET);
	for (block = 0; block < n_block; block++) {
		n  = min(n_rec, dhp->n_rec - rec);
		sbp[block].n_rec = n;
		sbp[block].offset = l_rec * rec;
		for (i = 0, p = bufp; i < n; i++, p += l_rec) {
			if (read(dp->fh, dp->rbp, dhp->l_rec) != dhp->l_rec) {
				db_err(IE_READ);
				goto ret;
			}
			*(long *)p = ++rec;
			get_key(dp, ihp, p+4);
		}
		qsort(bufp, n, l_rec, comp);
		if (write(fh[0], bufp, l_rec * n) != l_rec * n) {
			db_err(IE_WRITE);
			goto ret;
		}
	}
	dp->rno = 0;

	tmp1 = 0;
	tmp2 = 1;
	for ( ; n_block != 1; n_block = (n_block + 1) / 2) {
		lseek(fh[tmp2], 0L, SEEK_SET);

		for (block = 0; block < n_block/2; block++) {
			block1 = block * 2;
			block2 = block1 + 1;
			if (marge(fh[tmp1], fh[tmp2], &sbp[block1], &sbp[block2], bufp, l_rec, comp) != 0)
				goto ret;
			sbp[block].n_rec = sbp[block1].n_rec
						+ sbp[block2].n_rec;
			sbp[block].offset = sbp[block1].offset;
		}
		if (n_block % 2 != 0) {
			block1 = block * 2;
			lseek(fh[tmp1], sbp[block1].offset, SEEK_SET);
			for (rec = sbp[block1].n_rec; rec != 0; ) {
				n = min(n_rec, rec);
				if (read(fh[tmp1], bufp, l_rec * n) !=
								l_rec * n) {
					db_err(IE_READ);
					goto ret;
				}
				if (write(fh[tmp2], bufp, l_rec * n) !=
								l_rec * n) {
					db_err(IE_WRITE);
					goto ret;
				}
				rec -= n;
			}
			sbp[block].n_rec = sbp[block1].n_rec;
			sbp[block].offset = sbp[block1].offset;
		}
		tmp = tmp1;
		tmp1 = tmp2;
		tmp2 = tmp;
	}

	lseek(fh[tmp1], 0L, SEEK_SET);
	wrt_index(ip, fh[tmp1], dhp->n_rec, l_rec);
ret:
	if (bufp)
		free(bufp);
	if (sbp)
		free(sbp);

	if (fh[1] != -1) {
		close(fh[1]);
		remove(tmp_file[1]);
	}
	if (fh[0] != -1) {
		close(fh[0]);
		remove(tmp_file[0]);
	}
}

static	int	comp2(char *p1, char *p2)
{
	int	i;
	long	l;

	if ((i = memcmp(p1+4, p2+4, comp_len)) == 0) {
		l = *(long *)p1 - *(long *)p2;
		i = (l == 0) ? 0 : (l < 0) ? -1 : 1;
	}
	return(i);
}

static	int	comp3(char *p1, char *p2)
{
	int	i;
	long	l;

	if (*(double *)(p1+4) > *(double *)(p2+4))
		i = 1;
	else if (*(double *)(p1+4) < *(double *)(p2+4))
		i = -1;
	else {
		l = *(long *)p1 - *(long *)p2;
		i = (l == 0) ? 0 : (l < 0) ? -1 : 1;
	}
	return(i);
}

static	void	wrt_index(IDX *ip, int fh, long n_rec, int l_rec)
{
	IDX_H	*ihp;
	long	bno;
	int	level;
	char	key[100];

	chsize(ip->fh, 0L);

	ihp = ip->ihp;

	memset(key, 0, ihp->l_key);
	w_n_rec = n_rec;
	w_bno = 1;
	level = 0;
	do {
		ip->bp = NULL;
		wrt_block(ip, fh, level++, l_rec, key, &bno);
	} while (w_n_rec != 0);

	ihp->root = bno;
	ihp->n_block = w_bno;
	ip->w_flag = 1;
}

static	void	wrt_block(IDX *ip, int fh, int level, int l_rec, char *key
		, long *bno)
{
	IDX_H	*ihp;
	IDX_B	*bp;
	char	*bufp;
	register int	i;

	ihp = ip->ihp;
	bp = idx_read(ip, 0L);
	bufp = bp->block + 4;

	if (level == 0) {
		for (i = 0; i < ihp->order && w_n_rec != 0; w_n_rec--) {
			if (read(fh, bufp + 4, l_rec) != l_rec) {
				db_err(IE_READ);
				return;
			}
			if (ihp->uniq) {
				if (memcmp(bufp + 8, key, ihp->l_key) == 0)
					continue;
			}
			*(long *)bufp = 0;
			memcpy(key, bufp + 8, ihp->l_key);
			bufp += ihp->l_rec;
			i++;
		}
		*(long *)bufp = 0;
	} else {
		if (*bno == 0)
			wrt_block(ip, fh, level-1, l_rec, key, bno);

		for (i = 0; i < ihp->order && w_n_rec != 0; i++) {
			*(long *)bufp = *bno;
			*(long *)(bufp+4) = 0;
			memcpy(bufp+8, key, ihp->l_key);
			*bno = 0;
			wrt_block(ip, fh, level-1, l_rec, key, bno);
			bufp += ihp->l_rec;
		}
		*(long *)bufp = *bno;
	}

	*(int *)bp->block = i;
	bp->bno = w_bno++;
	idx_write(ip, bp);
	*bno = bp->bno;
	ip->bp = bp->bwp;
}

static	void	setext(char *name, char *ext)
{
	char	 *p;

	if ((p = strrchr(name, '.')) != NULL)
		strcpy(p+1, ext);
	else {
		strcat(name, ".");
		strcat(name, ext);
	}
}
