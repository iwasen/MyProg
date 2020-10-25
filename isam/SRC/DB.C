/************************************************************************
 *									*
 *		�h�r�`�l���C�u����					*
 *									*
 *		����		: �f�[�^�x�[�X����			*
 *		�t�@�C����	: db.c					*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "isam.h"

/*	���[�J���֐���`	*/
static	void	skip_deleted(DB *, int);
static	void	skip(DB *, long);
static	void	check_bof(DB *, long);
static	void	check_eof(DB *, long);

static	DB	*_db_top_ptr = 0;	/* �f�[�^�x�[�X�擪�|�C���^ */

/*=======================================================================
 |
 |		�f�[�^�x�[�X�I�[�v��
 |
 |	DB	*db_open(file_name)
 |
 |		char	*file_name;	�f�[�^�t�@�C����
 |
 |		�Ԓl			�c�a�|�C���^
 |
 =======================================================================*/
DB	*db_open(char *file_name)
{
	DB	*dbp;

	/* �f�[�^�x�[�X�Ǘ����G���A�m�� */
	if ((dbp = malloc(sizeof(DB))) == NULL) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dbp, 0, sizeof(DB));

	/* �f�[�^�t�@�C���i�c�a�e�j�I�[�v�� */
	if ((dbp->dp = dbf_open(file_name)) == NULL) {
		db_close(dbp);
		return(NULL);
	}

	check_bof(dbp, 1L);	/* �a�n�e�`�F�b�N*/

	/* �`�F�C������ */
	dbp->chain = _db_top_ptr;
	_db_top_ptr = dbp;

	return(dbp);
}

/*=======================================================================
 |
 |		�f�[�^�x�[�X�쐬
 |
 |	DB	*db_create(dbf_file_name, dip, n_field)
 |
 |		char	*dbf_file_name;	�f�[�^�t�@�C����
 |		DBF_I	*dip;		�f�[�^�t�@�C�����
 |		int	n_field;	�t�B�[���h��
 |
 |		�Ԓl			�c�a�|�C���^
 |
 =======================================================================*/
DB	*db_create(char *dbf_file_name, DBF_I *dip, int n_field)
{
	DBF	*dp;

	/* �f�[�^�t�@�C���쐬 */
	if ((dp = dbf_create(dbf_file_name, dip, n_field)) == NULL)
		return(NULL);

	dbf_close(dp);	/* �f�[�^�t�@�C���N���[�Y */

	return(db_open(dbf_file_name));
}

/*=======================================================================
 |
 |		�\���t�@�C�����f�[�^�x�[�X�쐬
 |
 |	DB	*db_create_from(dbf_file_name, str_file_name)
 |
 |		char	*dbf_file_name;	�f�[�^�t�@�C����
 |		char	*str_file_name;	�\���t�@�C����
 |
 |		�Ԓl			�c�a�|�C���^
 |
 =======================================================================*/
DB	*db_create_from(char *dbf_file_name, char *str_file_name)
{
	DBF	*dp;
	FIELD	*flp_name, *flp_type, *flp_len, *flp_dec;
	DBF_I	*dip;
	register int	i, j;
	int	n_field;

	/* �\���t�@�C���I�[�v�� */
	if ((dp = dbf_open(str_file_name)) == NULL) {
		return(NULL);
	}

	/* "FIELD_NAME" �t�B�[���h���o�� */
	if ((flp_name = get_field(dp, "FIELD_NAME")) == 0) {
		db_err(IE_STRFILE);
		dbf_close(dp);
		return(NULL);
	}

	/* "FIELD_TYPE" �t�B�[���h���o�� */
	if ((flp_type = get_field(dp, "FIELD_TYPE")) == 0) {
		db_err(IE_STRFILE);
		dbf_close(dp);
		return(NULL);
	}

	/* "FIELD_LEN" �t�B�[���h���o�� */
	if ((flp_len = get_field(dp, "FIELD_LEN")) == 0) {
		db_err(IE_STRFILE);
		dbf_close(dp);
		return(NULL);
	}

	/* "FIELD_DEC" �t�B�[���h���o�� */
	if ((flp_dec = get_field(dp, "FIELD_DEC")) == 0) {
		db_err(IE_STRFILE);
		dbf_close(dp);
		return(NULL);
	}

	/* �t�B�[���h���G���A�m�� */
	n_field = (int)dp->dhp->n_rec;
	if ((dip = calloc(n_field, sizeof(DBF_I))) == NULL) {
		db_err(IE_MEMORY);
		dbf_close(dp);
		return(NULL);
	}

	/* �t�B�[���h���Z�b�g */
	for (i = 0; i < n_field; i++) {
		dbf_read(dp, (long)(i + 1));
		for (j = 0; j < 10 && flp_name->fad[j] != ' '; j++)
			dip[i].name[j] = flp_name->fad[j];
		dip[i].name[j] = '\0';
		dip[i].type = flp_type->fad[0];
		dip[i].l_field = natoi(flp_len->fad, flp_len->l_field);
		dip[i].l_dec = natoi(flp_dec->fad, flp_dec->l_dec);
	}

	dbf_close(dp);	/* �\���t�@�C���N���[�Y */

	/* �f�[�^�t�@�C���쐬 */
	dp = dbf_create(dbf_file_name, dip, n_field);

	free(dip);	/* �t�B�[���h���G���A��� */

	if (dp == NULL)
		return(NULL);

	dbf_close(dp);	/* �f�[�^�t�@�C���N���[�Y */

	return(db_open(dbf_file_name));
}

/*=======================================================================
 |
 |		�f�[�^�x�[�X�N���[�Y
 |
 |	void	db_close(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
void	db_close(DB *dbp)
{
	DB	**dbpp;
	register int	i;

	/* �f�[�^�t�@�C���N���[�Y */
	if (dbp->dp != NULL)
		dbf_close(dbp->dp);

	/* �C���f�b�N�X�t�@�C���N���[�Y */
	for (i = 1; i <= dbp->n_idx; i++) {
		if (dbp->ip[i] != NULL)
			idx_close(dbp->ip[i]);
	}

	/* �`�F�C���؂藣�� */
	for (dbpp = &_db_top_ptr; *dbpp != 0; dbpp = &(*dbpp)->chain) {
		if ((*dbpp) == dbp) {
			*dbpp = (*dbpp)->chain;
			break;
		}
	}

	free(dbp);	/* �Ǘ����G���A��� */
}

/*=======================================================================
 |
 |		�S�f�[�^�x�[�X�N���[�Y
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
 |		�t�@�C�������|���o��
 |
 |	void	db_flush(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
void	db_flush(DB *dbp)
{
	register int	i;

	dbf_flush(dbp->dp);	/* �f�[�^�t�@�C���t���b�V�� */

	/* �C���f�b�N�X�t�@�C���t���b�V�� */
	for (i = 1; i <= dbp->n_idx; i++) {
		if (dbp->ip[i] != NULL)
			idx_flush(dbp->ip[i]);
	}
}

/*=======================================================================
 |
 |		�S�t�@�C�������|���o��
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
 |		�C���f�b�N�X�t�@�C���I�[�v��
 |
 |	int	db_index(dbp, file_name)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		char	*file_name;	�C���f�b�N�X�t�@�C����
 |
 |		�Ԓl			�C���f�b�N�X�t�@�C���ԍ�
 |
 =======================================================================*/
int	db_index(DB *dbp, char *file_name)
{
	/* �C���f�b�N�X�t�@�C���I�[�v�����`�F�b�N */
	if (dbp->n_idx >= MAX_IDX) {
		db_err(IE_IDXOVER);
		return(0);
	}

	/* �C���f�b�N�X�t�@�C���I�[�v�� */
	if ((dbp->ip[dbp->n_idx+1] = idx_open(file_name)) == NULL)
		return(0);


	dbp->n_idx++;		/* �C���f�b�N�X�t�@�C�����X�V */

	/* �}�X�^�[�C���f�b�N�X�t�@�C���ԍ��Z�b�g */
	if (dbp->master == 0) {
		dbp->master = dbp->n_idx;
		db_top(dbp);
	}

	return(dbp->n_idx);
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���쐬
 |
 |	int	db_idx_create(dbp, file_name, key, uniq)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		char	*file_name;	�C���f�b�N�X�t�@�C����
 |		char	*key;		�L�[
 |		int	uniq;		�O�F�L�[�̏d���������@�P�F�����Ȃ�
 |
 |		�Ԓl			�C���f�b�N�X�t�@�C���ԍ�
 |
 =======================================================================*/
int	db_idx_create(DB *dbp, char *file_name, char *key, int uniq)
{
	DBF	*dp;
	IDX	*ip;

	dp = dbp->dp;

	/* �C���f�b�N�X�t�@�C���I�[�v�����`�F�b�N */
	if (dbp->n_idx >= MAX_IDX) {
		db_err(IE_IDXOVER);
		return(0);
	}

	/* �C���f�b�N�X�t�@�C���쐬 */
	if ((ip = idx_create(file_name, key, uniq)) == NULL)
		return(0);

	idx_make(dp, ip);	/* �C���f�b�N�X�\�z */

	dbp->ip[dbp->n_idx+1] = ip;	/* �C���f�b�N�X�|�C���^�Z�[�u */

	dbp->n_idx++;		/* �C���f�b�N�X�t�@�C�����X�V */

	/* �}�X�^�[�C���f�b�N�X�t�@�C���ԍ��Z�b�g */
	if (dbp->master == 0) {
		dbp->master = dbp->n_idx;
		db_top(dbp);
	}

	return(dbp->n_idx);
}

/*=======================================================================
 |
 |		�}�X�^�[�C���f�b�N�X�؂�ւ�
 |
 |	void	db_chgidx(dbp, n)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		int	n;		�C���f�b�N�X�t�@�C���ԍ�
 |
 =======================================================================*/
void	db_chgidx(DB *dbp, int n)
{
	dbp->master = n;	/* �}�X�^�[�C���f�b�N�X�ԍ��Z�b�g */
	db_top(dbp);		/* �擪���R�[�h */
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���ɂ�錟��
 |
 |	void	db_search(dbp, key, len)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		char	*key;		�������镶����
 |		int	len;		�������镶����̒���
 |
 =======================================================================*/
void	db_search(DB *dbp, char *key, int len)
{
	DBF	*dp;
	IDX	*ip;
	double	a;
	long	rno;

	/* �|�C���^�Z�b�g */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	/* �C���f�b�N�X�t�@�C�����Ȃ���Ή������Ȃ� */
	if (ip == NULL)
		return;

	if (ip->ihp->type != 0) {
		/* ������𐔒l�ɕϊ� */
		a = natof(key, len);
		key = (char *)&a;
		len = 8;
	}

	rno = idx_search(ip, key, len);		/* ���� */

	if (_db_set_deleted && rno != 0L) {
		/* �폜���R�[�h���X�L�b�v���� */
		while (dbf_check_deleted(dp, rno)) {
			if ((rno = idx_next(ip)) == 0)
				break;
			if (idx_compare(ip, key, len) != 0) {
				rno = 0;
				break;
			}
		}
	}

	check_eof(dbp, rno);	/* �d�n�e�`�F�b�N */
}

/*=======================================================================
 |
 |		�L�[�Ɉ�v���郌�R�[�h�������߂�
 |
 |	long	db_count(dbp, key, len)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		char	*key;		�������镶����
 |		int	len;		�������镶����̒���
 |
 |		�Ԓl			�L�[�Ɉ�v�������R�[�h��
 |
 =======================================================================*/
long	db_count(DB *dbp, char *key, int len)
{
	DBF	*dp;
	IDX	*ip;
	double	a;
	long	count, rno;

	/* �|�C���^�Z�b�g */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	/* �C���f�b�N�X�t�@�C�����Ȃ���Ή������Ȃ� */
	if (ip == NULL)
		return(0L);

	if (ip->ihp->type != 0) {
		/* ������𐔒l�ɕϊ� */
		a = natof(key, len);
		key = (char *)&a;
		len = 8;
	}

	count = 0;
	if ((rno = idx_search(ip, key, len)) != 0) {	/* ���� */
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

	check_eof(dbp, rno);	/* �d�n�e�`�F�b�N */

	return(count);
}

/*=======================================================================
 |
 |		���R�[�h�ǉ�
 |
 |	void	db_store(dbp, rec)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		char	*rec;		�ǉ����郌�R�[�h�f�[�^�ւ̃|�C���^
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

	/* �|�C���^�Z�b�g */
	dp = dbp->dp;
	dhp = dp->dhp;

	dp->rbp[0] = ' ';	/* �폜���R�[�h�t���O�N���A */
	memcpy(dp->rbp+1, rec, dhp->l_rec-1);	/* �f�[�^�R�s�[ */

	/* �f�[�^�t�@�C���ɒǉ� */
	dbp->rno = ++dhp->n_rec;
	dbf_write(dp, dbp->rno);
	dp->w_flag = 1;

	/* �C���f�b�N�X�t�@�C���X�V */
	for (i = 1; i <= dbp->n_idx; i++) {
		ip = dbp->ip[i];
		ihp = ip->ihp;

		if (get_key(dp, ihp, kbuf) != 0)
			idx_store(ip, kbuf, dhp->n_rec);
	}

	/* �C���f�b�N�X�t�@�C�����̃|�C���^���Z�b�g */
	db_set(dbp, dbp->rno);
}

/*=======================================================================
 |
 |		���R�[�h�X�V
 |
 |	void	db_update(dbp, rec)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		char	*rec;		�X�V���郌�R�[�h�f�[�^�ւ̃|�C���^
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

	/* �a�n�e�܂��͂d�n�e�Ȃ�G���[ */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	/* �|�C���^�Z�b�g */
	dp = dbp->dp;
	dhp = dp->dhp;

	dbf_read(dp, dbp->rno);		/* ���R�[�h�ǂݍ��� */

	/* �C���f�b�N�X�t�@�C���X�V */
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
				dbf_read(dp, dbp->rno);	/* ���R�[�h�ǂݍ��� */
			}
		}
	}

	/* �f�[�^�t�@�C���X�V */
	memcpy(dp->rbp+1, rec, dhp->l_rec-1);
	dbf_write(dp, dbp->rno);

	/* �C���f�b�N�X�t�@�C�����̃|�C���^���Z�b�g */
	db_set(dbp, dbp->rno);
}

/*=======================================================================
 |
 |		���R�[�h�폜
 |
 |	void	db_delete(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
void	db_delete(DB *dbp)
{
	/* �a�n�e�܂��͂d�n�e�Ȃ�G���[ */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	dbf_delete(dbp->dp, dbp->rno);	/* �폜�}�[�N�Z�b�g */

	skip_deleted(dbp, 1);		/* �폜���R�[�h�X�L�b�v */
}

/*=======================================================================
 |
 |		���R�[�h����
 |
 |	void	db_recall(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
void	db_recall(DB *dbp)
{
	/* �a�n�e�܂��͂d�n�e�Ȃ�G���[ */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	dbf_recall(dbp->dp, dbp->rno);	/* �폜�}�[�N������ */
}

/*=======================================================================
 |
 |		���R�[�h�̕����I�폜
 |
 |	void	db_delete2(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
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

	/* �a�n�e�܂��͂d�n�e�Ȃ�G���[ */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	dp = dbp->dp;
	dhp = dp->dhp;

	/* ���R�[�h�ۑ��G���A�m�� */
	if ((rbufp = malloc(dp->dhp->l_rec - 1)) == 0) {
		db_err(IE_MEMORY);
		return;
	}

	dbf_read(dp, dhp->n_rec);	/* �ŏI���R�[�h�ǂݍ��� */

	/* �����I�ŏI���R�[�h���C���f�b�N�X�t�@�C�����폜 */
	for (i = 1; i <= dbp->n_idx; i++) {
		ip = dbp->ip[i];

		if (get_key(dp, ip->ihp, kbuf) != 0) {
			rno = idx_search(ip, kbuf, ip->ihp->l_key);
			while (rno != dhp->n_rec && rno != 0)
				rno = idx_next(ip);
			if (rno != 0)
				idx_delete(ip);		/* �L�[�̍폜 */
		}
	}

	/* �ŏI���R�[�h�ȊO�̏ꍇ�A�폜���郌�R�[�h�ɍŏI���R�[�h���㏑�� */
	if (dbp->rno != dhp->n_rec) {
		memcpy(rbufp, dp->rbp + 1, dhp->l_rec - 1);
		db_update(dbp, rbufp);
	}

	free(rbufp);

	dhp->n_rec--;	/* ���R�[�h���|�P */
	dp->update = 1;		/* �t�@�C���X�V�t���O�Z�b�g */

	if (dhp->n_rec == 0)	/* �O���ɂȂ����ꍇ�͂a�n�e�A�d�n�e���Z�b�g */
		dbp->eof = dbp->bof = 1;

	/* �t�@�C���T�C�Y�𒲐����� */
	chsize(dp->fh, dhp->l_header + dhp->l_rec * dhp->n_rec);
}

/*=======================================================================
 |
 |		���R�[�h�|�C���^��擪���R�[�h�ɃZ�b�g
 |
 |	void	db_top(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
void	db_top(DB *dbp)
{
	IDX	*ip;

	ip = dbp->ip[dbp->master];

	check_bof(dbp, (ip == NULL) ? 1L : idx_top(ip));

	skip_deleted(dbp, 1);	/* �폜���R�[�h�X�L�b�v */
}

/*=======================================================================
 |
 |		���R�[�h�|�C���^���ŏI���R�[�h�ɃZ�b�g
 |
 |	void	db_bottom(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
void	db_bottom(DB *dbp)
{
	IDX	*ip;

	ip = dbp->ip[dbp->master];

	check_eof(dbp, (ip == NULL) ? dbp->dp->dhp->n_rec : idx_bottom(ip));

	skip_deleted(dbp, -1);	/* �폜���R�[�h�X�L�b�v */
}

/*=======================================================================
 |
 |		���R�[�h�|�C���^���w�肳�ꂽ���R�[�h�ɃZ�b�g
 |
 |	void	db_set(dbp, recno)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		long	recno;		���R�[�h�ԍ�
 |
 =======================================================================*/
void	db_set(DB *dbp, long recno)
{
	DBF	*dp;
	IDX	*ip;
	long	rno;
	char	kbuf[100];
	
	/* �|�C���^�Z�b�g */
	dp = dbp->dp;
	ip = dbp->ip[dbp->master];

	if (ip != NULL) {
		/* �C���f�b�N�X�t�@�C��������ꍇ */
		dbf_read(dp, recno);	/* ���R�[�h�ǂݍ��� */

		/* �C���f�b�N�X�t�@�C���̎w�肳�ꂽ���R�[�h�ԍ���T�� */
		if (get_key(dp, ip->ihp, kbuf) != 0) {
			rno = idx_search(ip, kbuf, ip->ihp->l_key);
			while (rno != recno && rno != 0)
				rno = idx_next(ip);
		}
	}

	check_eof(dbp, recno);	/* �d�n�e�`�F�b�N */
}

/*=======================================================================
 |
 |		���R�[�h�|�C���^�̑O��ړ�
 |
 |	void	db_skip(dbp, n)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		long	n;		�ړ����R�[�h��
 |
 =======================================================================*/
void	db_skip(DB *dbp, long n)
{
	if (_db_set_deleted == 0)
		skip(dbp, n);	/* ���R�[�h�|�C���^�ړ� */
	else {
		if (n < 0) {
			while (n++) {
				skip(dbp, -1L);
				skip_deleted(dbp, -1);/* �폜���R�[�h�X�L�b�v*/
			}
		} else if (n > 0) {
			while (n--) {
				skip(dbp, 1L);
				skip_deleted(dbp, 1);/* �폜���R�[�h�X�L�b�v */
			}
		}
	}
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���č\�z
 |
 |	void	db_reindex(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
void	db_reindex(DB *dbp)
{
	DBF	*dp;
	register int	i;

	dp = dbp->dp;

	for (i = 1; i <= dbp->n_idx; i++) {
		idx_make(dp, dbp->ip[i]);	/* �C���f�b�N�X�\�z */
	}

	db_top(dbp);
}

/*=======================================================================
 |
 |		�폜���R�[�h���t�@�C���ォ��폜
 |
 |	void	db_pack(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
void	db_pack(DB *dbp)
{
	dbf_pack(dbp->dp);	/* �폜���R�[�h�̍폜 */
	db_reindex(dbp);	/* �C���f�b�N�X�t�@�C���č\�z */
}

/*=======================================================================
 |
 |		�J�����g���R�[�h�ǂݍ���
 |
 |	void	db_read(dbp, p)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		char	*p;		���R�[�h�ǂݍ��݃o�b�t�@�|�C���^
 |
 =======================================================================*/
void	db_read(DB *dbp, char *p)
{
	DBF	*dp;

	/* �a�n�e�܂��͂d�n�e�Ȃ�G���[ */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return;
	}

	dp = dbp->dp;
	dbf_read(dp, dbp->rno);		/* �ǂݍ��� */
	memcpy(p, dp->rbp + 1, dp->dhp->l_rec - 1);	/* �o�b�t�@�ɃR�s�[ */
}

/*=======================================================================
 |
 |		�f�[�^�t�@�C�����R�s�[����
 |
 |	void	db_copy(dbp1, dbp2)
 |
 |		DB	*dbp1;		�R�s�[���c�a�|�C���^
 |		DB	*dbp2;		�R�s�[��c�a�|�C���^
 |
 =======================================================================*/
void	db_copy(DB *dbp1, DB *dbp2)
{
	dbf_copy(dbp1->dp, dbp2->dp);	/* �f�[�^�t�@�C���R�s�[ */
	db_reindex(dbp2);		/* �C���f�b�N�X�t�@�C���č\�z */
}

/*=======================================================================
 |
 |		�폜���R�[�h�`�F�b�N
 |
 |	int	db_check_deleted(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 |		�Ԓl			�O�F�ʏ탌�R�[�h�@�P�F�폜���R�[�h
 |					-1�F�G���[
 |
 =======================================================================*/
int	db_check_deleted(DB *dbp)
{
	/* �a�n�e�܂��͂d�n�e�Ȃ�G���[ */
	if (dbp->bof || dbp->eof) {
		db_err(IE_EOF);
		return(-1);
	}

	return(dbf_check_deleted(dbp->dp, dbp->rno));
}

/*=======================================================================
 |
 |		�폜���R�[�h�̃X�L�b�v
 |
 |	void	skip_deleted(dbp, n)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		int	n;		�P�F���փX�L�b�v�@�|�P�F�O�փX�L�b�v
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
 |		���R�[�h�̑O��ړ�
 |
 |	void	skip(dbp, n)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		long	n;		�ړ����R�[�h��
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
		/* �C���f�b�N�X�t�@�C�����Ȃ��ꍇ */
		rno = dbp->rno + n;
		if (rno <= 0)
			check_bof(dbp, 0L);
		else if (rno > dp->dhp->n_rec)
			check_eof(dbp, 0L);
		else
			check_eof(dbp, rno);
	} else {
		/* �C���f�b�N�X�t�@�C��������ꍇ */
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
 |		�a�n�e�`�F�b�N
 |
 |	void	check_bof(dbp, rno)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		long	rno;		���R�[�h�ԍ�
 |
 =======================================================================*/
static	void	check_bof(DB *dbp, long rno)
{
	if (dbp->dp->dhp->n_rec == 0) {
		/* ���R�[�h���P�����Ȃ��ꍇ */
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
 |		�d�n�e�`�F�b�N
 |
 |	void	check_eof(dbp, rno)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		long	rno;		���R�[�h�ԍ�
 |
 =======================================================================*/
static	void	check_eof(DB *dbp, long rno)
{
	if (dbp->dp->dhp->n_rec == 0) {
		/* ���R�[�h���P�����Ȃ��ꍇ */
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