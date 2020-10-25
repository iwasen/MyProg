/************************************************************************
 *									*
 *		ＩＳＡＭライブラリ					*
 *									*
 *		名称		: データファイル操作			*
 *		ファイル名	: dbf.c					*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <io.h>
#include <fcntl.h>
#include <ctype.h>
#include <jctype.h>
#include <sys\types.h>
#include <sys\stat.h>
#include "isam.h"

static	void	string_toupper(char *, char *, int);

/*=======================================================================
 |
 |		データファイルオープン
 |
 |	DBF	*dbf_open(file_name)
 |
 |		char	*file_name;	データファイル名
 |
 |		返値			ＤＢＦポインタ
 |
 =======================================================================*/
DBF	*dbf_open(char *file_name)
{
	DBF	*dp;
	FIELD	*flp;
	char	*rbp;
	register int	i;

	/* データファイル管理情報エリア確保 */
	if ((dp = malloc(sizeof(DBF))) == 0) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dp, 0, sizeof(DBF));

	/* データファイルオープン */
	if ((dp->fh = open(file_name, O_BINARY|O_RDWR)) == -1) {
		dbf_close(dp);
		db_err(IE_NOFILE);
		return(NULL);
	}

	strcpy(dp->file_name, file_name);	/* ファイル名セーブ */

	/* ヘッダ情報エリア確保 */
	if ((dp->dhp = malloc(sizeof(DBF_H))) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* ヘッダ情報読み込み */
	if (read(dp->fh, (char *)dp->dhp, sizeof(DBF_H)) != sizeof(DBF_H)) {
		dbf_close(dp);
		db_err(IE_READ);
		return(NULL);
	}

	/* フィールド情報エリア確保 */
	dp->n_field = (dp->dhp->l_header - 33) / 32;
	if ((dp->flp = malloc(sizeof(FIELD) * dp->n_field)) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* フィールド情報読み込み */
	if (read(dp->fh, (char *)dp->flp, sizeof(FIELD) * dp->n_field) !=
					(int)(sizeof(FIELD) * dp->n_field)) {
		dbf_close(dp);
		db_err(IE_READ);
		return(NULL);
	}

	/* レコードバッファ確保 */
	if ((dp->rbp = malloc(dp->dhp->l_rec)) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* フィールドポインタセット */
	flp = dp->flp;
	rbp = dp->rbp + 1;
	for (i = 0; i < dp->n_field; i++) {
		flp->fad = rbp;
		rbp += flp->l_field;
		flp++;
	}

	return(dp);
}

/*=======================================================================
 |
 |		データファイル作成
 |
 |	DBF	*dbf_create(file_name, dip, n_field)
 |
 |		char	*file_name;	データファイル名
 |		DBF_I	*dip;		データファイル情報
 |		int	n_field;	フィールド数
 |
 |		返値			ＤＢＦポインタ
 |
 =======================================================================*/
DBF	*dbf_create(char *file_name, DBF_I *dip, int n_field)
{
	DBF	*dp;
	DBF_H	*dhp;
	FIELD	*flp;
	char	*rbp, nbuf[12];
	register int	i;
	static	char	cr_eof[2] = {0x0d, 0x1a};

	/* データファイル管理情報エリア確保 */
	if ((dp = malloc(sizeof(DBF))) == 0) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dp, 0, sizeof(DBF));

	/* データファイルオープン */
	if ((dp->fh = open(file_name, O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		dbf_close(dp);
		db_err(IE_FOPEN);
		return(NULL);
	}

	strcpy(dp->file_name, file_name);	/* ファイル名セーブ */

	/* ヘッダ情報エリア確保 */
	if ((dhp = malloc(sizeof(DBF_H))) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dhp, 0, sizeof(DBF_H));
	dp->dhp = dhp;

	/* ヘッダ情報設定 */
	dhp->flag = 0x03;
	dhp->l_header = (n_field + 1) * 32 + 1;

	/* ヘッダ情報書き込み */
	if (write(dp->fh, (char *)dp->dhp, sizeof(DBF_H)) != sizeof(DBF_H)) {
		dbf_close(dp);
		db_err(IE_WRITE);
		return(NULL);
	}

	/* フィールド情報エリア確保 */
	dp->n_field = n_field;
	if ((dp->flp = malloc(sizeof(FIELD) * n_field)) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dp->flp, 0, sizeof(FIELD) * n_field);

	/* フィールド情報設定 */
	for (i = 0, flp = dp->flp, dhp->l_rec = 1; i < n_field; i++, flp++) {
		string_toupper(nbuf, dip->name, 11);	/* 大文字変換 */
		memcpy(flp->name, nbuf, 11);
		flp->type = dip->type;
		flp->l_field = dip->l_field;
		flp->l_dec = dip->l_dec;
		dhp->l_rec += dip->l_field;
		dip++;
	}

	/* フィールド情報書き込み */
	if (write(dp->fh, (char *)dp->flp, sizeof(FIELD) * dp->n_field) !=
					sizeof(FIELD) * dp->n_field) {
		dbf_close(dp);
		db_err(IE_WRITE);
		return(NULL);
	}

	/* 区切り文字＆ＥＯＦ書き込み */
	if (write(dp->fh, cr_eof, 2) != 2) {
		dbf_close(dp);
		db_err(IE_WRITE);
		return(NULL);
	}

	/* レコードバッファ確保 */
	if ((dp->rbp = malloc(dhp->l_rec)) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* フィールドポインタセット */
	flp = dp->flp;
	rbp = dp->rbp + 1;
	for (i = 0; i < n_field; i++) {
		flp->fad = rbp;
		rbp += flp->l_field;
		flp++;
	}

	dp->update = 1;

	return(dp);
}

static	void	string_toupper(char *buf, char *name, int len)
{
	while (len-- > 0) {
		if (iskanji(*name)) {
			*buf++ = *name++;
			*buf++ = *name++;
			len--;
		} else
			*buf++ = toupper(*name++);
	}
}

/*=======================================================================
 |
 |		レコード読み込み
 |
 |	void	dbf_read(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		long	rno;		レコード番号
 |
 =======================================================================*/
void	dbf_read(DBF *dp, long rno)
{
	DBF_H	*dhp;

	dhp = dp->dhp;

	/* レコード番号が範囲外ならエラー */
	if (rno <= 0 || rno > dhp->n_rec) {
		db_err(IE_RECNO);
		return;
	}

	if (rno != dp->rno) {
		/* 読み込み */
		lseek(dp->fh, dhp->l_header + dhp->l_rec*(rno-1), SEEK_SET);
		if (read(dp->fh, dp->rbp, dhp->l_rec) != dhp->l_rec)
			db_err(IE_READ);

		dp->rno = rno;	/* カレントレコード番号セット */
	}
}

/*=======================================================================
 |
 |		レコード書き込み
 |
 |	void	dbf_write(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		long	rno;		レコード番号
 |
 =======================================================================*/
void	dbf_write(DBF *dp, long rno)
{
	DBF_H	*dhp;

	dhp = dp->dhp;

	/* レコード番号が範囲外ならエラー */
	if (rno <= 0 || rno > dhp->n_rec) {
		db_err(IE_RECNO);
		return;
	}

	/* 書き込み */
	lseek(dp->fh, dhp->l_header + dhp->l_rec*(rno-1), SEEK_SET);
	if (write(dp->fh, dp->rbp, dhp->l_rec) != dhp->l_rec)
		db_err(IE_WRITE);

	dp->rno = rno;		/* カレントレコード番号セット */
	dp->update = 1;		/* ファイル更新フラグセット */
}

/*=======================================================================
 |
 |		データファイルクローズ
 |
 |	void	dbf_close(dp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |
 =======================================================================*/
void	dbf_close(DBF *dp)
{
	DBF_H	*dhp;
	long	ltime;
	struct	tm	*tmp;

	if (dp->fh != -1) {
		if (dp->update != 0) {
			/* 最終更新日書き込み */
			dhp = dp->dhp;
			time(&ltime);
			tmp = localtime(&ltime);
			dhp->date[0] = tmp->tm_year;
			dhp->date[1] = tmp->tm_mon + 1;
			dhp->date[2] = tmp->tm_mday;
			dp->w_flag = 1;
		}

		dbf_flush(dp);		/* ファイル書き込み */
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
 |	void	dbf_flush(dp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |
 =======================================================================*/
void	dbf_flush(DBF *dp)
{
	static	char	eof[1] = {0x1a};
	DBF_H	*dhp;

	if (dp->w_flag != 0) {
		dhp = dp->dhp;

		/* ヘッダ書き込み */
		lseek(dp->fh, 0L, SEEK_SET);
		if (write(dp->fh, (char *)dp->dhp, sizeof(DBF_H)) !=
							sizeof(DBF_H))
			db_err(IE_WRITE);

		/* ＥＯＦ書き込み */
		lseek(dp->fh, dhp->l_header + dhp->l_rec * dhp->n_rec,
								SEEK_SET);
		if (write(dp->fh, eof, 1) != 1)
			db_err(IE_WRITE);

		dp->w_flag = 0;
	}

	close(dp->fh);
	dp->fh = open(dp->file_name, O_BINARY|O_RDWR);
}

/*=======================================================================
 |
 |		削除マークセット
 |
 |	void	dbf_delete(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		long	rno;		レコード番号
 |
 =======================================================================*/
void	dbf_delete(DBF *dp, long rno)
{
	dbf_read(dp, rno);	/* レコード読み込み */
	*dp->rbp = '*';		/* 削除マークセット */
	dbf_write(dp, rno);	/* レコード書き込み */
}

/*=======================================================================
 |
 |		削除マーク取り消し
 |
 |	void	dbf_recall(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		long	rno;		レコード番号
 |
 =======================================================================*/
void	dbf_recall(DBF *dp, long rno)
{
	dbf_read(dp, rno);	/* レコード読み込み */
	*dp->rbp = ' ';		/* 削除マーク取り消し */
	dbf_write(dp, rno);	/* レコード書き込み */
}

/*=======================================================================
 |
 |		削除マークがついているレコードをファイルから削除
 |
 |	void	dbf_pack(dp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |
 =======================================================================*/
void	dbf_pack(DBF *dp)
{
	DBF_H	*dhp;
	long	r_rno, w_rno;

	dhp = dp->dhp;

	/* 削除マークが付いているレコードを削除する */
	for (r_rno = w_rno = 0; r_rno < dhp->n_rec; ) {
		dbf_read(dp, ++r_rno);
		if (dp->rbp[0] != '*') {
			if (r_rno != ++w_rno) {
				dbf_write(dp, w_rno);
			}
		}
	}

	/* ファイルサイズを調整する */
	chsize(dp->fh, dhp->l_header + dhp->l_rec * w_rno);

	/* レコード数セット */
	dhp->n_rec = w_rno;
	dp->w_flag = 1;

	dp->rno = 0;
}

/*=======================================================================
 |
 |		削除レコードチェック
 |
 |	int	dbf_check_deleted(dp, rno)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		long	rno;		レコード番号
 |
 |		返値			０：通常レコード　１：削除レコード
 |
 =======================================================================*/
int	dbf_check_deleted(DBF *dp, long rno)
{
	dbf_read(dp, rno);
	return(*dp->rbp == '*' ? 1 : 0);
}

/*=======================================================================
 |
 |		データファイルをコピーする
 |
 |	void	dbf_copy(dp1, dp2)
 |
 |		DBF	*dp1;		コピー元ＤＢＦポインタ
 |		DBF	*dp2;		コピー先ＤＢＦポインタ
 |
 =======================================================================*/
void	dbf_copy(DBF *dp1, DBF *dp2)
{
	long	rno;
	int	i, j, len;
	FIELD	*flp1, *flp2;

	/* レコード数セット */
	dp2->dhp->n_rec = dp1->dhp->n_rec;

	for (rno = 1; rno <= dp1->dhp->n_rec; rno++) {
		dbf_read(dp1, rno);
		memset(dp2->rbp, ' ', dp2->dhp->l_rec);
		for (i = 0; i < dp2->n_field; i++) {
			flp2 = &dp2->flp[i];
			for (j = 0; j < dp1->n_field; j++) {
				flp1 = &dp1->flp[j];
				if (strcmp(flp1->name, flp2->name) == 0)
					break;
			}
			if (j < dp1->n_field) {
				len = min(flp1->l_field, flp2->l_field);
				if (flp2->type == 'N') {
					memcpy(flp2->fad+(flp2->l_field-len)
					,flp1->fad+(flp1->l_field-len), len);
				} else {
					memcpy(flp2->fad, flp1->fad, len);
				}
			}
		}
		dbf_write(dp2, rno);
	}

	/* ファイルサイズを調整する */
	chsize(dp2->fh, dp2->dhp->l_header + dp2->dhp->l_rec * rno-1);

	dp2->w_flag = 1;
}
