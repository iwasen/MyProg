/************************************************************************
 *									*
 *		�h�r�`�l���C�u����					*
 *									*
 *		����		: �f�[�^�t�@�C������			*
 *		�t�@�C����	: dbf.c					*
 *		�쐬��		: s.aizawa				*
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
 |		�f�[�^�t�@�C���I�[�v��
 |
 |	DBF	*dbf_open(file_name)
 |
 |		char	*file_name;	�f�[�^�t�@�C����
 |
 |		�Ԓl			�c�a�e�|�C���^
 |
 =======================================================================*/
DBF	*dbf_open(char *file_name)
{
	DBF	*dp;
	FIELD	*flp;
	char	*rbp;
	register int	i;

	/* �f�[�^�t�@�C���Ǘ����G���A�m�� */
	if ((dp = malloc(sizeof(DBF))) == 0) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dp, 0, sizeof(DBF));

	/* �f�[�^�t�@�C���I�[�v�� */
	if ((dp->fh = open(file_name, O_BINARY|O_RDWR)) == -1) {
		dbf_close(dp);
		db_err(IE_NOFILE);
		return(NULL);
	}

	strcpy(dp->file_name, file_name);	/* �t�@�C�����Z�[�u */

	/* �w�b�_���G���A�m�� */
	if ((dp->dhp = malloc(sizeof(DBF_H))) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* �w�b�_���ǂݍ��� */
	if (read(dp->fh, (char *)dp->dhp, sizeof(DBF_H)) != sizeof(DBF_H)) {
		dbf_close(dp);
		db_err(IE_READ);
		return(NULL);
	}

	/* �t�B�[���h���G���A�m�� */
	dp->n_field = (dp->dhp->l_header - 33) / 32;
	if ((dp->flp = malloc(sizeof(FIELD) * dp->n_field)) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* �t�B�[���h���ǂݍ��� */
	if (read(dp->fh, (char *)dp->flp, sizeof(FIELD) * dp->n_field) !=
					(int)(sizeof(FIELD) * dp->n_field)) {
		dbf_close(dp);
		db_err(IE_READ);
		return(NULL);
	}

	/* ���R�[�h�o�b�t�@�m�� */
	if ((dp->rbp = malloc(dp->dhp->l_rec)) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* �t�B�[���h�|�C���^�Z�b�g */
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
 |		�f�[�^�t�@�C���쐬
 |
 |	DBF	*dbf_create(file_name, dip, n_field)
 |
 |		char	*file_name;	�f�[�^�t�@�C����
 |		DBF_I	*dip;		�f�[�^�t�@�C�����
 |		int	n_field;	�t�B�[���h��
 |
 |		�Ԓl			�c�a�e�|�C���^
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

	/* �f�[�^�t�@�C���Ǘ����G���A�m�� */
	if ((dp = malloc(sizeof(DBF))) == 0) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dp, 0, sizeof(DBF));

	/* �f�[�^�t�@�C���I�[�v�� */
	if ((dp->fh = open(file_name, O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		dbf_close(dp);
		db_err(IE_FOPEN);
		return(NULL);
	}

	strcpy(dp->file_name, file_name);	/* �t�@�C�����Z�[�u */

	/* �w�b�_���G���A�m�� */
	if ((dhp = malloc(sizeof(DBF_H))) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dhp, 0, sizeof(DBF_H));
	dp->dhp = dhp;

	/* �w�b�_���ݒ� */
	dhp->flag = 0x03;
	dhp->l_header = (n_field + 1) * 32 + 1;

	/* �w�b�_��񏑂����� */
	if (write(dp->fh, (char *)dp->dhp, sizeof(DBF_H)) != sizeof(DBF_H)) {
		dbf_close(dp);
		db_err(IE_WRITE);
		return(NULL);
	}

	/* �t�B�[���h���G���A�m�� */
	dp->n_field = n_field;
	if ((dp->flp = malloc(sizeof(FIELD) * n_field)) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(dp->flp, 0, sizeof(FIELD) * n_field);

	/* �t�B�[���h���ݒ� */
	for (i = 0, flp = dp->flp, dhp->l_rec = 1; i < n_field; i++, flp++) {
		string_toupper(nbuf, dip->name, 11);	/* �啶���ϊ� */
		memcpy(flp->name, nbuf, 11);
		flp->type = dip->type;
		flp->l_field = dip->l_field;
		flp->l_dec = dip->l_dec;
		dhp->l_rec += dip->l_field;
		dip++;
	}

	/* �t�B�[���h��񏑂����� */
	if (write(dp->fh, (char *)dp->flp, sizeof(FIELD) * dp->n_field) !=
					sizeof(FIELD) * dp->n_field) {
		dbf_close(dp);
		db_err(IE_WRITE);
		return(NULL);
	}

	/* ��؂蕶�����d�n�e�������� */
	if (write(dp->fh, cr_eof, 2) != 2) {
		dbf_close(dp);
		db_err(IE_WRITE);
		return(NULL);
	}

	/* ���R�[�h�o�b�t�@�m�� */
	if ((dp->rbp = malloc(dhp->l_rec)) == 0) {
		dbf_close(dp);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* �t�B�[���h�|�C���^�Z�b�g */
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
 |		���R�[�h�ǂݍ���
 |
 |	void	dbf_read(dp, rno)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |		long	rno;		���R�[�h�ԍ�
 |
 =======================================================================*/
void	dbf_read(DBF *dp, long rno)
{
	DBF_H	*dhp;

	dhp = dp->dhp;

	/* ���R�[�h�ԍ����͈͊O�Ȃ�G���[ */
	if (rno <= 0 || rno > dhp->n_rec) {
		db_err(IE_RECNO);
		return;
	}

	if (rno != dp->rno) {
		/* �ǂݍ��� */
		lseek(dp->fh, dhp->l_header + dhp->l_rec*(rno-1), SEEK_SET);
		if (read(dp->fh, dp->rbp, dhp->l_rec) != dhp->l_rec)
			db_err(IE_READ);

		dp->rno = rno;	/* �J�����g���R�[�h�ԍ��Z�b�g */
	}
}

/*=======================================================================
 |
 |		���R�[�h��������
 |
 |	void	dbf_write(dp, rno)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |		long	rno;		���R�[�h�ԍ�
 |
 =======================================================================*/
void	dbf_write(DBF *dp, long rno)
{
	DBF_H	*dhp;

	dhp = dp->dhp;

	/* ���R�[�h�ԍ����͈͊O�Ȃ�G���[ */
	if (rno <= 0 || rno > dhp->n_rec) {
		db_err(IE_RECNO);
		return;
	}

	/* �������� */
	lseek(dp->fh, dhp->l_header + dhp->l_rec*(rno-1), SEEK_SET);
	if (write(dp->fh, dp->rbp, dhp->l_rec) != dhp->l_rec)
		db_err(IE_WRITE);

	dp->rno = rno;		/* �J�����g���R�[�h�ԍ��Z�b�g */
	dp->update = 1;		/* �t�@�C���X�V�t���O�Z�b�g */
}

/*=======================================================================
 |
 |		�f�[�^�t�@�C���N���[�Y
 |
 |	void	dbf_close(dp)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |
 =======================================================================*/
void	dbf_close(DBF *dp)
{
	DBF_H	*dhp;
	long	ltime;
	struct	tm	*tmp;

	if (dp->fh != -1) {
		if (dp->update != 0) {
			/* �ŏI�X�V���������� */
			dhp = dp->dhp;
			time(&ltime);
			tmp = localtime(&ltime);
			dhp->date[0] = tmp->tm_year;
			dhp->date[1] = tmp->tm_mon + 1;
			dhp->date[2] = tmp->tm_mday;
			dp->w_flag = 1;
		}

		dbf_flush(dp);		/* �t�@�C���������� */
		close(dp->fh);		/* �t�@�C���N���[�Y */
	}

	if (dp->dhp != NULL)
		free(dp->dhp);		/* �w�b�_���G���A��� */

	if (dp->flp != NULL)
		free(dp->flp);		/* �t�B�[���h���G���A��� */

	if (dp->rbp != NULL)
		free(dp->rbp);		/* ���R�[�h�o�b�t�@�G���A��� */

	free(dp);		/* �f�[�^�t�@�C���Ǘ����G���A��� */
}

/*=======================================================================
 |
 |		�f�[�^�t�@�C�������|���o��
 |
 |	void	dbf_flush(dp)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |
 =======================================================================*/
void	dbf_flush(DBF *dp)
{
	static	char	eof[1] = {0x1a};
	DBF_H	*dhp;

	if (dp->w_flag != 0) {
		dhp = dp->dhp;

		/* �w�b�_�������� */
		lseek(dp->fh, 0L, SEEK_SET);
		if (write(dp->fh, (char *)dp->dhp, sizeof(DBF_H)) !=
							sizeof(DBF_H))
			db_err(IE_WRITE);

		/* �d�n�e�������� */
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
 |		�폜�}�[�N�Z�b�g
 |
 |	void	dbf_delete(dp, rno)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |		long	rno;		���R�[�h�ԍ�
 |
 =======================================================================*/
void	dbf_delete(DBF *dp, long rno)
{
	dbf_read(dp, rno);	/* ���R�[�h�ǂݍ��� */
	*dp->rbp = '*';		/* �폜�}�[�N�Z�b�g */
	dbf_write(dp, rno);	/* ���R�[�h�������� */
}

/*=======================================================================
 |
 |		�폜�}�[�N������
 |
 |	void	dbf_recall(dp, rno)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |		long	rno;		���R�[�h�ԍ�
 |
 =======================================================================*/
void	dbf_recall(DBF *dp, long rno)
{
	dbf_read(dp, rno);	/* ���R�[�h�ǂݍ��� */
	*dp->rbp = ' ';		/* �폜�}�[�N������ */
	dbf_write(dp, rno);	/* ���R�[�h�������� */
}

/*=======================================================================
 |
 |		�폜�}�[�N�����Ă��郌�R�[�h���t�@�C������폜
 |
 |	void	dbf_pack(dp)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |
 =======================================================================*/
void	dbf_pack(DBF *dp)
{
	DBF_H	*dhp;
	long	r_rno, w_rno;

	dhp = dp->dhp;

	/* �폜�}�[�N���t���Ă��郌�R�[�h���폜���� */
	for (r_rno = w_rno = 0; r_rno < dhp->n_rec; ) {
		dbf_read(dp, ++r_rno);
		if (dp->rbp[0] != '*') {
			if (r_rno != ++w_rno) {
				dbf_write(dp, w_rno);
			}
		}
	}

	/* �t�@�C���T�C�Y�𒲐����� */
	chsize(dp->fh, dhp->l_header + dhp->l_rec * w_rno);

	/* ���R�[�h���Z�b�g */
	dhp->n_rec = w_rno;
	dp->w_flag = 1;

	dp->rno = 0;
}

/*=======================================================================
 |
 |		�폜���R�[�h�`�F�b�N
 |
 |	int	dbf_check_deleted(dp, rno)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |		long	rno;		���R�[�h�ԍ�
 |
 |		�Ԓl			�O�F�ʏ탌�R�[�h�@�P�F�폜���R�[�h
 |
 =======================================================================*/
int	dbf_check_deleted(DBF *dp, long rno)
{
	dbf_read(dp, rno);
	return(*dp->rbp == '*' ? 1 : 0);
}

/*=======================================================================
 |
 |		�f�[�^�t�@�C�����R�s�[����
 |
 |	void	dbf_copy(dp1, dp2)
 |
 |		DBF	*dp1;		�R�s�[���c�a�e�|�C���^
 |		DBF	*dp2;		�R�s�[��c�a�e�|�C���^
 |
 =======================================================================*/
void	dbf_copy(DBF *dp1, DBF *dp2)
{
	long	rno;
	int	i, j, len;
	FIELD	*flp1, *flp2;

	/* ���R�[�h���Z�b�g */
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

	/* �t�@�C���T�C�Y�𒲐����� */
	chsize(dp2->fh, dp2->dhp->l_header + dp2->dhp->l_rec * rno-1);

	dp2->w_flag = 1;
}