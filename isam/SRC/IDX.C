/************************************************************************
 *									*
 *		�h�r�`�l���C�u����					*
 *									*
 *		����		: �C���f�b�N�X�t�@�C������		*
 *		�t�@�C����	: idx.c					*
 *		�쐬��		: s.aizawa				*
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
 |		�C���f�b�N�X�t�@�C���I�[�v��
 |
 |	IDX	*idx_open(file_name)
 |
 |		char	*file_name;	�C���f�b�N�X�t�@�C����
 |
 |		�Ԓl			�h�c�w�|�C���^
 |
 =======================================================================*/
IDX	*idx_open(char *file_name)
{
	IDX	*ip;

	/* �C���f�b�N�X�Ǘ����G���A�m�� */
	if ((ip = malloc(sizeof(IDX))) == 0) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(ip, 0, sizeof(IDX));

	/* �C���f�b�N�X�t�@�C���I�[�v�� */
	if ((ip->fh = open(file_name, O_BINARY|O_RDWR)) == -1) {
		idx_close(ip);
		db_err(IE_NOFILE);
		return(NULL);
	}

	strcpy(ip->file_name, file_name);	/* �t�@�C�����Z�[�u */

	/* �w�b�_���G���A�m�� */
	if ((ip->ihp = malloc(sizeof(IDX_H))) == 0) {
		idx_close(ip);
		db_err(IE_MEMORY);
		return(NULL);
	}

	/* �w�b�_���ǂݍ��� */
	if (read(ip->fh, (char *)ip->ihp, sizeof(IDX_H)) != sizeof(IDX_H)) {
		idx_close(ip);
		db_err(IE_READ);
		return(NULL);
	}

	return(ip);
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���쐬
 |
 |	IDX	*idx_create(file_name, key, uniq)
 |
 |		char	*file_name;	�C���f�b�N�X�t�@�C����
 |		char	*key;		�L�[
 |		int	uniq;		�O�F�L�[�̏d���������@�P�F�����Ȃ�
 |
 |		�Ԓl			�h�c�w�|�C���^
 |
 =======================================================================*/
IDX	*idx_create(char *file_name, char *key, int uniq)
{
	IDX	*ip;
	IDX_H	*ihp;

	/* �C���f�b�N�X�Ǘ����G���A�m�� */
	if ((ip = malloc(sizeof(IDX))) == 0) {
		db_err(IE_MEMORY);
		return(NULL);
	}
	memset(ip, 0, sizeof(IDX));

	/* �C���f�b�N�X�t�@�C���I�[�v�� */
	if ((ip->fh = open(file_name, O_BINARY|O_RDWR|O_CREAT,
						S_IREAD|S_IWRITE)) == -1) {
		idx_close(ip);
		db_err(IE_NOFILE);
		return(NULL);
	}

	strcpy(ip->file_name, file_name);	/* �t�@�C�����Z�[�u */

	/* �w�b�_���G���A�m�� */
	if ((ihp = malloc(sizeof(IDX_H))) == 0) {
		idx_close(ip);
		db_err(IE_MEMORY);
		return(NULL);
	}
	ip->ihp = ihp;

	/* �w�b�_���ݒ� */
	memset(ihp, 0, sizeof(IDX_H));
	sprintf(ihp->index, "%s ", key);
	ihp->uniq = uniq ? 1 : 0;

	return(ip);
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C�����ݒ�
 |
 |	void	idx_setinfo(ip, type, len)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |		int	type;		�O�F�����^�@�P�F���l�^
 |		inr	len;		�L�[�̒���
 |
 =======================================================================*/
void	idx_setinfo(IDX *ip, int type, int len)
{
	IDX_H	*ihp;

	ihp = ip->ihp;

	ihp->type = type;		/* �f�[�^�^�C�v�ݒ� */
	ihp->l_key = len;		/* �L�[���ݒ� */
	ihp->l_rec = ((ihp->l_key - 1) & ~3) + 12;	/* ���R�[�h���ݒ� */
	ihp->order = (BLOCK_SIZE - 4) / ihp->l_rec;	/* �����ݒ� */
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���ǂݍ���
 |
 |	IDX_B	*idx_read(ip, bno)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |		long	bno;		�u���b�N�ԍ�
 |
 |		�Ԓl			�u���b�N���ւ̃|�C���^
 |
 =======================================================================*/
IDX_B	*idx_read(IDX *ip, long bno)
{
	IDX_B	**bpp, *bp;

	/* �u���b�N�o�b�t�@�|�C���^�`�F�b�N */
	if (ip->bp == NULL)
		bpp = &ip->top;
	else
		bpp = &ip->bp->fwp;

	if (*bpp == NULL) {
		/* �u���b�N���G���A�m�� */
		if ((bp = malloc(sizeof(IDX_B))) == 0) {
			db_err(IE_MEMORY);
			return(NULL);
		}
		memset(bp, 0, sizeof(IDX_B));

		/* �`�F�C������ */
		*bpp = bp;
		bp->bwp = ip->bottom;
		ip->bottom = bp;
	} else
		bp = *bpp;

	if (bp->bno != bno) {
		if (bno != 0) {
			/* �t�@�C���ǂݍ��� */
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
 |		�u���b�N���G���A�m��
 |
 |	IDX_B	*idx_getblk(ip, bno)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |		long	bno;		�u���b�N�ԍ�
 |
 |		�Ԓl			�u���b�N���ւ̃|�C���^
 |
 =======================================================================*/
IDX_B	*idx_getblk(IDX *ip, long bno)
{
	IDX_B	*bp;

	bp = idx_read(ip, 0L);	/* �G���A�m�� */
	bp->bno = bno;		/* �u���b�N�ԍ��Z�b�g */

	return(bp);
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C����������
 |
 |	void	idx_write(ip, bp)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |		IDX_B	*bp;		�u���b�N���|�C���^
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
 |		�C���f�b�N�X�t�@�C���N���[�Y
 |
 |	void	idx_close(ip)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |
 =======================================================================*/
void	idx_close(IDX *ip)
{
	IDX_B	*bp, *fwp;

	if (ip->fh != -1) {
		idx_flush(ip);		/* �t�@�C���������� */
		close(ip->fh);		/* �t�@�C���N���[�Y */
	}

	/* �u���b�N���G���A��� */
	for (bp = ip->top; bp != NULL; bp = fwp) {
		fwp = bp->fwp;
		free(bp);
	}

	if (ip->ihp != NULL)
		free(ip->ihp);		/* �w�b�_���G���A��� */

	free(ip);		/* �C���f�b�N�X�Ǘ����G���A��� */
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C�������|���o��
 |
 |	void	idx_flush(ip)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |
 =======================================================================*/
void	idx_flush(IDX *ip)
{
	if (ip->w_flag != 0) {
		/* �w�b�_��񏑂����� */
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
 |		����
 |
 |	long	idx_search(ip, key, len)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |		char	*key;		�������镶����
 |		int	len;		�������镶����̒���
 |
 |		�Ԓl			���R�[�h�ԍ�
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

	ip->bp = NULL;		/* �u���b�N�|�C���^������ */

	ihp = ip->ihp;		/* �C���f�b�N�X�w�b�_�|�C���^�Z�b�g */
	bno = ihp->root;	/* ���[�g�u���b�N�m���D�Z�b�g */
	for (;;) {
		bp = idx_read(ip, bno);	/* �u���b�N�f�[�^�ǂݍ��� */

		bufp = bp->block;
		m = *(int *)bufp;	/* �u���b�N���f�[�^�����o�� */
		bufp += 4;
		for (bp->cp = 0; bp->cp < m; bp->cp++) {
			ip->keyp = bufp + 8;
			f = idx_compare(ip, key, len);	/* �L�[�̔�r */
			if (f == 0) {
				/* ��v�����ꍇ */
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

	return(0L);	/* ��v����L�[�����݂��Ȃ� */
}

/*=======================================================================
 |
 |		�L�[�̔�r
 |
 |	int	idx_compare(ip, key, len)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |		char	*key;		��r���镶����
 |		int	len;		��r���镶����̒���
 |
 |		�Ԓl			��r����
 |
 =======================================================================*/
int	idx_compare(IDX *ip, void *key, int len)
{
	double	d;

	if (ip->ihp->type == 0) {
		/* �����^�̏ꍇ */
		return(memcmp(key, ip->keyp, len));
	} else {
		/* ���l�^�̏ꍇ */
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
 |		�L�[�̑}��
 |
 |	void	idx_store(ip, key, rno)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |		char	*key;		�}������L�[
 |		long	rno;		���R�[�h�ԍ�
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

	/* �}���ʒu���� */
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
			/* ���[�g�u���b�N�č\�z */
			bp = idx_getblk(ip, ihp->n_block++);
			*(int *)bp->block = 0;
			*(long *)(bp->block + 4) = ihp->root;
			ihp->root = bp->bno;
			bp->cp = 0;
		}
		bufp = bp->block;
		m = *(int *)bufp;	/* �u���b�N���f�[�^�����o�� */
		bufp += 8 + bp->cp * ihp->l_rec;

		/* �u���b�N���ɑ}�� */
		memcpyr(bufp + ihp->l_rec, bufp, ihp->l_rec * (m - bp->cp));
		*(long *)bufp = rno;
		memcpy(bufp + 4, key, ihp->l_key);
		*((long *)(bufp + ihp->l_rec - 4)) = bno;

		if (++m > ihp->order) {
			/* �u���b�N�̕��� */
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

/*	��ʃA�h���X����̃������R�s�[ */
static	void	memcpyr(char *dst, char *src, int size)
{
	dst += size;
	src += size;
	while (size--)
		*(--dst) = *(--src);
}

/*=======================================================================
 |
 |		�L�[�̍폜
 |
 |	void	idx_delete(ip)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
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
		m = *(int *)bufp;	/* �u���b�N���f�[�^�����o�� */
		bufp += 4 + bp->cp * ihp->l_rec;
		if (key == NULL) {
			if (m == 0) {
				*(int *)bufp = 0;
			} else {
				if (*(int *)bufp == 0 && bp->cp == m-1) {
					if (m > 1)
						key = bufp - ihp->l_rec + 8;
				} else if (bp->cp == m) {
					/* �u���b�N���ŏI�L�[ */
					key = bufp - ihp->l_rec + 8;
				} else {
					/* �L�[�폜 */
					memcpy(bufp, bufp + ihp->l_rec
					, ihp->l_rec * (m - bp->cp - 1) + 4);
					loop_sw = 0;
				}
				(*(int *)bp->block)--;
			}
				
			idx_write(ip, bp);
		} else {
			if (bp->cp != m) {
				/* ��ʃu���b�N�̃L�[����ւ� */
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
 |		�����R�[�h�ԍ����o��
 |
 |	long	idx_next(ip)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |
 |		�Ԓl			���R�[�h�ԍ�
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
		bp->cp++;		/* �J�����g�|�C���^�X�V */
		bufp = bp->block;
		m = *(int *)bufp;	/* �u���b�N���f�[�^�����o�� */

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
			/* ���ʃu���b�N�ǂݍ��� */
			bp = idx_read(ip, bno);
			bp->cp = -1;
		} else {
			/* ��ʃu���b�N�|�C���^�Z�b�g */
			ip->bp = bp->bwp;
		}
	}
	return(0L);
}

/*=======================================================================
 |
 |		�O���R�[�h�ԍ����o��
 |
 |	long	idx_back(ip)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |
 |		�Ԓl			���R�[�h�ԍ�
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
		bp->cp--;		/* �J�����g�|�C���^�X�V */
		bufp = bp->block;
		m = *(int *)bufp;	/* �u���b�N���f�[�^�����o�� */

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
			/* ���ʃu���b�N�ǂݍ��� */
			bp = idx_read(ip, bno);
			bp->cp = *(int *)bp->block + 1;
		} else {
			/* ��ʃu���b�N�|�C���^�Z�b�g */
			ip->bp = bp->bwp;
		}
	}
	return(0L);
}

/*=======================================================================
 |
 |		�擪���R�[�h�ԍ����o��
 |
 |	long	idx_top(ip)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |
 |		�Ԓl			���R�[�h�ԍ�
 |
 =======================================================================*/
long	idx_top(IDX *ip)
{
	IDX_B	*bp;
	char	*bufp;
	long	bno;

	ip->bp = NULL;		/* �u���b�N�|�C���^������ */

	bno = ip->ihp->root;	/* ���[�g�u���b�N�ԍ��Z�b�g */
	for (;;) {
		bp = idx_read(ip, bno);	/* �u���b�N�f�[�^�ǂݍ��� */

		bufp = bp->block;

		bp->cp = 0;	/* �J�����g�|�C���^�N���A */

		bufp += 4;
		if ((bno = *(long *)bufp) == 0)
			break;
	}

	ip->keyp = bufp + 8;
	return(*(long *)(bufp + 4));
}

/*=======================================================================
 |
 |		�ŏI���R�[�h�ԍ����o��
 |
 |	long	idx_bottom(ip)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |
 |		�Ԓl			���R�[�h�ԍ�
 |
 =======================================================================*/
long	idx_bottom(IDX *ip)
{
	IDX_B	*bp;
	char	*bufp;
	long	bno;

	ip->bp = NULL;		/* �u���b�N�|�C���^������ */

	bno = ip->ihp->root;	/* ���[�g�u���b�N�ԍ��Z�b�g */
	for (;;) {
		bp = idx_read(ip, bno);	/* �u���b�N�f�[�^�ǂݍ��� */

		bufp = bp->block;

		bp->cp = *(int *)bufp;	/* �J�����g�|�C���^�Z�b�g */

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
 |		��v����L�[�̌������߂�
 |
 |	long	idx_count(ip, key, len)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |		char	*key;		�������镶����
 |		int	len;		�������镶����̒���
 |
 |		�Ԓl			��v����L�[�̌�
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