/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �o�C�i���t�@�C������
 *		�t�@�C����	: bin.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <io.h>
#include <fcntl.h>
#include <sys/types.h>
#include <sys/stat.h>

#include "pcsdef.h"
#include "pcsdb.h"
#include "pcsdbsub.h"
#include "pcscom.h"

#define	MODE_NONE	0
#define	MODE_READ	1
#define	MODE_WRITE	2

static	BOOL	_ReadBlock(PBIN, PBIN_R, CHAR *);
static	BOOL	_GetNewBlock(PBIN, BOOL);
static	BOOL	_WriteBlock(PBIN, CHAR *);
static	LONG	_GetFreeBlock(PBIN);

/*=======================================================================
 |
 |		�o�C�i���t�@�C���I�[�v��
 |
 |	SHORT	BINOpen(fileName, &bfpp)
 |
 |		CHAR	*fileName;	�o�C�i���t�@�C����
 |		PBIN	*bfpp;		�a�h�m�|�C���^
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINOpen(CHAR *fileName, PBIN *bfpp)
{
	PBIN	bfp;
	PBIN_H	bhp;
	CHAR	pathName[256];
#if defined (OS_WNT)
	DWORD	numBytes;
#endif

	/* �t���p�X���擾 */
	_fullpath(pathName, fileName, sizeof(pathName));
	StringToUpper(pathName);

	/* �o�C�i���t�@�C���Ǘ����G���A�m�� */
	if ((bfp = malloc(sizeof(BIN))) == 0)
		return ERROR_DB_MEMORY;

	memset(bfp, 0, sizeof(BIN));

	/* �o�C�i���t�@�C���I�[�v�� */
	if ((bfp->fh = __OPEN(pathName)) == -1) {
		BINClose(bfp);
		return ERROR_DB_NOFILE;
	}

	strcpy(bfp->fileName, pathName);	/* �t�@�C�����Z�[�u */

	/* �w�b�_���G���A�m�� */
	if ((bhp = malloc(sizeof(BIN_H))) == 0) {
		BINClose(bfp);
		return ERROR_DB_MEMORY;
	}
	bfp->bhp = bhp;

	/* �w�b�_���ǂݍ��� */
	if (__READ(bfp->fh, (CHAR *)bhp, sizeof(BIN_H)) != sizeof(BIN_H)) {
		BINClose(bfp);
		return ERROR_DB_READ;
	}

	*bfpp = bfp;

	return 0;
}

/*=======================================================================
 |
 |		�o�C�i���t�@�C���쐬
 |
 |	SHORT	BINCreate(fileName, bfpp)
 |
 |		CHAR	*fileName;	�o�C�i���t�@�C����
 |		PBIN	*bfpp;		�a�h�m�|�C���^
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINCreate(CHAR *fileName, PBIN *bfpp)
{
	PBIN	bfp;
	PBIN_H	bhp;
	CHAR	pathName[256];
#if defined (OS_WNT) && !defined (_SERVER)
	DWORD	numBytes;
#endif

	/* �t���p�X���擾 */
	_fullpath(pathName, fileName, sizeof(pathName));
	StringToUpper(pathName);

	/* �o�C�i���t�@�C���Ǘ����G���A�m�� */
	if ((bfp = malloc(sizeof(BIN))) == 0)
		return ERROR_DB_MEMORY;

	memset(bfp, 0, sizeof(BIN));

	/* �o�C�i���t�@�C���I�[�v�� */
	if ((bfp->fh = __CREAT(pathName)) == -1) {
		BINClose(bfp);
		return ERROR_DB_FOPEN;
	}

	strcpy(bfp->fileName, pathName);	/* �t�@�C�����Z�[�u */

	/* �w�b�_���G���A�m�� */
	if ((bhp = malloc(sizeof(BIN_H))) == 0) {
		BINClose(bfp);
		return ERROR_DB_MEMORY;
	}
	memset(bhp, 0, sizeof(BIN_H));
	bfp->bhp = bhp;

	/* �w�b�_���ݒ� */
	bhp->nUsedBlock = 1;

	/* �w�b�_��񏑂����� */
	if (__WRITE(pathName, bfp->fh, (CHAR *)bfp->bhp, sizeof(BIN_H)) != sizeof(BIN_H)) {
		BINClose(bfp);
		return ERROR_DB_WRITE;
	}

	bfp->updateHeaderFlag = TRUE;	/* �w�b�_�X�V�t���O�Z�b�g */

	*bfpp = bfp;

	return 0;
}

/*=======================================================================
 |
 |		�o�C�i���t�@�C���N���[�Y
 |
 |	SHORT	BINClose(bfp)
 |
 |		PBIN	bfp;		�a�h�m�|�C���^
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINClose(PBIN bfp)
{
	if (bfp->fh != -1) {
		BINFlush(bfp, FALSE);	/* �t�@�C���������� */
		__CLOSE(bfp->fh);	/* �t�@�C���N���[�Y */
	}

	if (bfp->bhp != NULL)
		free(bfp->bhp);		/* �w�b�_���G���A��� */

	free(bfp);		/* �o�C�i���t�@�C���Ǘ����G���A��� */

	return 0;
}

/*=======================================================================
 |
 |		�o�C�i���t�@�C�������|���o��
 |
 |	SHORT	BINFlush(bfp, bReopen)
 |
 |		PBIN	bfp;		BIN �|�C���^
 |		BOOL	bReopen;	�ăI�[�v���t���O
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINFlush(PBIN bfp, BOOL bReopen)
{
#if defined (OS_WNT) && !defined (_SERVER)
	DWORD	numBytes;
#endif
	LONG	dataSize;

	/* ���������݂̃f�[�^���������� */
	if (!_WriteBlock(bfp, bfp->buf))
		return ERROR_DB_WRITE;

	/* �f�[�^�T�C�Y�������� */
	if (bfp->updateSizeFlag) {
		dataSize = max(bfp->dataSize, bfp->dataSize2);
		if (__WRITE2_64(bfp->fileName, bfp->fh, (CHAR *)&dataSize, 4, bfp->bnoTop * (LONG64)BIN_BLOCK_SIZE + 4) != 4)
			return ERROR_DB_WRITE;

		bfp->updateSizeFlag = FALSE;
	}

	if (bfp->updateHeaderFlag) {
		/* �w�b�_�������� */
		if (__WRITE2(bfp->fileName, bfp->fh, (CHAR *)bfp->bhp, sizeof(BIN_H), 0) != sizeof(BIN_H))
			return ERROR_DB_WRITE;

		bfp->updateHeaderFlag = FALSE;
	}

	if (bReopen) {
		__CLOSE(bfp->fh);
		bfp->fh = __OPEN(bfp->fileName);
	}

	return 0;
}

/*=======================================================================
 |
 |		�o�C�i���f�[�^�ǂݍ���
 |
 |	SHORT	BINRead(bfp, brp, bufp, bufSize, bno, readSize)
 |
 |		PBIN	bfp;		BIN �|�C���^
 |		PBIN_R	brp;		BIN_R �|�C���^
 |		HPSTR	bufp;		�o�b�t�@�|�C���^
 |		LONG	bufSize;	�o�b�t�@�T�C�Y
 |		LONG	bno;		�u���b�N�ԍ�
 |		LONG	*readSize;	�ǂݍ��݃T�C�Y
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINRead(PBIN bfp, PBIN_R brp, HPSTR bufp, LONG bufSize, LONG bno, LONG *readSize)
{
	BINFlush(bfp, FALSE);

	*readSize = 0;

	/* �u���b�N�ԍ����͈͊O�Ȃ�G���[ */
	if (bno <= 0 || bno > bfp->bhp->nUsedBlock)
		return ERROR_DB_RECNO;

	brp->bno = bno;

	if (!_ReadBlock(bfp, brp, brp->buf))
		return ERROR_DB_READ;

	brp->dataSize = *(LONG *)brp->buf;
	brp->offset = sizeof(LONG);
	brp->remain = BIN_DATA_SIZE - sizeof(LONG);

	return BINReadNext(bfp, brp, bufp, bufSize, readSize);
}

/*=======================================================================
 |
 |		���̃o�C�i���f�[�^�ǂݍ���
 |
 |	SHORT	BINReadNext(bfp, brp, bufp, bufSize, readSize)
 |
 |		PBIN	bfp;		BIN �|�C���^
 |		PBIN_R	brp;		BIN_R �|�C���^
 |		HPSTR	bufp;		�o�b�t�@�|�C���^
 |		LONG	bufSize;	�o�b�t�@�T�C�Y
 |		LONG	*readSize;	�ǂݍ��݃T�C�Y
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINReadNext(PBIN bfp, PBIN_R brp, HPSTR bufp, LONG bufSize, LONG *readSize)
{
	LONG	lSize;
	int	len;

	lSize = min(brp->dataSize, bufSize);
	*readSize = lSize;

	if (lSize == 0)
		return 0;

	if (brp->remain != 0) {
		len = (int)min(brp->remain, lSize);
		HMEMCPY(bufp, brp->buf + brp->offset, len);
		brp->remain -= len;
		brp->offset += len;
		brp->dataSize -= len;
		lSize -= len;
		bufp += len;
	}

	if (brp->bno == 0)
		return 0;

	while (lSize >= BIN_DATA_SIZE) {
		/* �P�u���b�N�ǂݍ��� */
		if (!_ReadBlock(bfp, brp, bufp))
			return ERROR_DB_READ;

		brp->dataSize -= BIN_DATA_SIZE;
		lSize -= BIN_DATA_SIZE;
		bufp += BIN_DATA_SIZE;
	}

	if (lSize != 0) {
		/* �P�u���b�N�ǂݍ��� */
		if (!_ReadBlock(bfp, brp, brp->buf))
			return ERROR_DB_READ;

		len = (int)lSize;
		HMEMCPY(bufp, brp->buf, len);

		brp->dataSize -= len;
		lSize -= len;
		bufp += len;

		brp->remain = BIN_DATA_SIZE - len;
		brp->offset = len;
	}

	return 0;
}

static	BOOL	_ReadBlock(PBIN bfp, PBIN_R brp, CHAR *bufp)
{
#if defined (OS_WNT)
	DWORD	numBytes;
#endif

	/* ���̃u���b�N�ԍ��ǂݍ��� */
	__LSEEK_64(bfp->fh, brp->bno * (LONG64)BIN_BLOCK_SIZE, SEEK_SET);
	if (__READ(bfp->fh, &brp->bno, 4) != 4)
		return FALSE;

	/* �o�C�i���f�[�^�ǂݍ��� */
	if (__READ(bfp->fh, bufp, BIN_DATA_SIZE) != BIN_DATA_SIZE)
		return FALSE;

	return TRUE;
}

/*=======================================================================
 |
 |		�o�C�i���f�[�^��������
 |
 |	SHORT	BINWrite(bfp, bufp, bufSize, bno)
 |
 |		PBIN	bfp;		BIN �|�C���^
 |		HPSTR	bufp;		�o�b�t�@�|�C���^
 |		LONG	bufSize;	�o�b�t�@�T�C�Y
 |		LONG	*bno;		�u���b�N�ԍ�
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINWrite(PBIN bfp, HPSTR bufp, LONG bufSize, LONG *bno)
{
	SHORT	err;

	BINFlush(bfp, FALSE);

	if (bufSize == 0)
		return 0;

	bfp->bno = 0;
	bfp->bnoNext = *bno;

	if (!_GetNewBlock(bfp, TRUE))
		return ERROR_DB_READ;

	if (*bno != 0)
		bfp->dataSize2 = *(LONG *)bfp->buf;
	else
		bfp->dataSize2 = 0;

	*bno = bfp->bno;
	bfp->bnoTop = bfp->bno;

	*(LONG *)bfp->buf = 0;

	bfp->dataSize = 0;
	bfp->updateSizeFlag = TRUE;
	bfp->offset = sizeof(LONG);
	bfp->remain = BIN_DATA_SIZE - sizeof(LONG);

	if ((err = BINWriteNext(bfp, bufp, bufSize)) != 0)
		return err;

	return 0;
}

/*=======================================================================
 |
 |		���̃o�C�i���f�[�^��������
 |
 |	SHORT	BINWriteNext(bfp, bufp, bufSize)
 |
 |		PBIN	bfp;		BIN �|�C���^
 |		HPSTR	bufp;		�o�b�t�@�|�C���^
 |		LONG	bufSize;	�o�b�t�@�T�C�Y
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINWriteNext(PBIN bfp, HPSTR bufp, LONG bufSize)
{
	LONG	lSize;
	int	len;

	lSize = bufSize;

	if (lSize == 0)
		return 0;

	if (bfp->remain != 0) {
		len = (int)min(bfp->remain, lSize);
		HMEMCPY(bfp->buf + bfp->offset, bufp, len);
		bfp->remain -= len;
		bfp->offset += len;
		bfp->dataSize += len;
		bfp->updateSizeFlag = TRUE;
		lSize -= len;
		bufp += len;
		bfp->updateBlockFlag = TRUE;

		if (bfp->remain == 0) {
			if (!_WriteBlock(bfp, bfp->buf))
				return ERROR_DB_WRITE;
		}
	}

	/* �������� */
	while (lSize >= BIN_DATA_SIZE) {
		if (!_GetNewBlock(bfp, FALSE))
			return ERROR_DB_READ;

		if (!_WriteBlock(bfp, bufp))
			return ERROR_DB_WRITE;

		bfp->dataSize += BIN_DATA_SIZE;
		bfp->updateSizeFlag = TRUE;
		lSize -= BIN_DATA_SIZE;
		bufp += BIN_DATA_SIZE;
	}

	if (lSize != 0) {
		if (!_GetNewBlock(bfp, TRUE))
			return ERROR_DB_READ;

		len = (int)lSize;
		HMEMCPY(bfp->buf, bufp, len);

		bfp->dataSize += len;
		bfp->updateSizeFlag = TRUE;
		lSize -= len;
		bufp += len;

		bfp->remain = BIN_DATA_SIZE - len;
		bfp->offset = len;
		bfp->updateBlockFlag = TRUE;
	}

	return 0;
}

static	BOOL	_GetNewBlock(PBIN bfp, BOOL bRead)
{
#if defined (OS_WNT)
	DWORD	numBytes;
#endif
	LONG	bno;

	bno = bfp->bno;

	if (bfp->bnoNext == 0) {
		if ((bfp->bno = _GetFreeBlock(bfp)) == 0)
			return FALSE;
		bfp->bnoNext = 0;
	} else {
		bfp->bno = bfp->bnoNext;

		__LSEEK_64(bfp->fh, bfp->bnoNext * (LONG64)BIN_BLOCK_SIZE, SEEK_SET);
		if (__READ(bfp->fh, &bfp->bnoNext, 4) != 4)
			return FALSE;

		/* �o�C�i���f�[�^�ǂݍ��� */
		if (bRead) {
			if (__READ(bfp->fh, bfp->buf, BIN_DATA_SIZE) != BIN_DATA_SIZE)
				return FALSE;
		}
	}

	if (bno != 0) {
		if (__WRITE2_64(bfp->fileName, bfp->fh, (CHAR *)&bfp->bno, 4, bno * (LONG64)BIN_BLOCK_SIZE) != 4)
			return FALSE;
	}

	bfp->updateBlockFlag = TRUE;

	return TRUE;
}

static	BOOL	_WriteBlock(PBIN bfp, CHAR *bufp)
{
#if defined (OS_WNT) && !defined (_SERVER)
	DWORD	numBytes;
#endif

	if (!bfp->updateBlockFlag)
		return TRUE;

	if (bfp->bno == 0)
		return FALSE;

	if (__WRITE2_64(bfp->fileName, bfp->fh, (CHAR *)&bfp->bnoNext, 4, bfp->bno * (LONG64)BIN_BLOCK_SIZE) != 4)
		return FALSE;

	if (__WRITE2_64(bfp->fileName, bfp->fh, bufp, BIN_DATA_SIZE, bfp->bno * (LONG64)BIN_BLOCK_SIZE + 4) != BIN_DATA_SIZE)
		return FALSE;

	bfp->updateBlockFlag = FALSE;

	return TRUE;
}

/*=======================================================================
 |
 |		�o�C�i���f�[�^�T�C�Y���o��
 |
 |	SHORT	BINGetDataSize(dp, bno, dataSize)
 |
 |		PBIN	bfp;		�a�h�m�|�C���^
 |		LONG	bno;		�u���b�N�ԍ�
 |		LONG	*dataSize;	�f�[�^�T�C�Y
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINGetDataSize(PBIN bfp, LONG bno, LONG *dataSize)
{
#if defined (OS_WNT)
	DWORD	numBytes;
#endif

	BINFlush(bfp, FALSE);

	__LSEEK_64(bfp->fh, bno * (LONG64)BIN_BLOCK_SIZE + 4, SEEK_SET);
	if (__READ(bfp->fh, dataSize, 4) != 4)
		return ERROR_DB_READ;

	return 0;
}

/*=======================================================================
 |
 |		�o�C�i���f�[�^�폜
 |
 |	SHORT	BINDelete(dp, bno)
 |
 |		PBIN	bfp;		�a�h�m�|�C���^
 |		LONG	bno;		�u���b�N�ԍ�
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINDelete(PBIN bfp, LONG bno)
{
	PBIN_H	bhp;
	LONG	bno2, bno3;
	LONG nCount = 0;
#if defined (OS_WNT)
	DWORD	numBytes;
#endif

	bhp = bfp->bhp;

	bno2 = bno;
	for (;;) {
		if (++nCount > bhp->nUsedBlock)
			return 0;

		__LSEEK_64(bfp->fh, bno2 * (LONG64)BIN_BLOCK_SIZE, SEEK_SET);
		if (__READ(bfp->fh, &bno3, 4) != 4)
			return ERROR_DB_READ;
		if (bno3 == 0L)
			break;
		bno2 = bno3;
	}

	if (__WRITE2_64(bfp->fileName, bfp->fh, (CHAR *)&bhp->freeBlock, 4, bno2 * (LONG64)BIN_BLOCK_SIZE) != 4)
		return ERROR_DB_WRITE;

	bhp->freeBlock = bno;
	bfp->updateHeaderFlag = TRUE;	/* �w�b�_�X�V�t���O�Z�b�g */

	return 0;
}

/*=======================================================================
 |
 |		�o�C�i���f�[�^�S�폜
 |
 |	SHORT	BINZip(bfp)
 |
 |		PBIN	bfp;		�a�h�m�|�C���^
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINZip(PBIN bfp)
{
	PBIN_H	bhp;

	bhp = bfp->bhp;

	bhp->nUsedBlock = 1;
	bhp->freeBlock = 0;

	/* �t�@�C���T�C�Y�𒲐����� */
	__CHSIZE_64(bfp->fileName, bfp->fh, (LONG64)BIN_BLOCK_SIZE);

	bfp->updateHeaderFlag = TRUE;	/* �w�b�_�X�V�t���O�Z�b�g */

	return 0;
}

static	LONG	_GetFreeBlock(PBIN bfp)
{
	PBIN_H	bhp;
	LONG	bno;
#if defined (OS_WNT)
	DWORD	numBytes;
#endif

	bhp = bfp->bhp;

	if (bhp->freeBlock == 0) {
		bno = bhp->nUsedBlock;
		bhp->nUsedBlock++;
	} else {
		bno = bhp->freeBlock;
		__LSEEK_64(bfp->fh, bhp->freeBlock * (LONG64)BIN_BLOCK_SIZE, SEEK_SET);
		if (__READ(bfp->fh, &bhp->freeBlock, 4) != 4)
			return 0;
	}

	bfp->updateHeaderFlag = TRUE;	/* �w�b�_�X�V�t���O�Z�b�g */

	return bno;
}

/*=======================================================================
 |
 |		�o�C�i���f�[�^�R�s�[
 |
 |	SHORT	BINCopy(bfpSrc, bfpDst, bnoSrc, bnoDst)
 |
 |		PBIN	bfpSrc;		�R�s�[���a�h�m�|�C���^
 |		PBIN	bfpDst;		�R�s�[��a�h�m�|�C���^
 |		LONG	bnoSrc;		�R�s�[���u���b�N�ԍ�
 |		LONG	*bnoDst;	�R�s�[��u���b�N�ԍ�
 |
 |		�Ԓl			�G���[�R�[�h
 |
 =======================================================================*/
SHORT	BINCopy(PBIN bfpSrc, PBIN bfpDst, LONG bnoSrc, LONG *bnoDst)
{
	BIN_R br;
	LONG readSize;
	char buf[16384];
	BOOL bFirst = TRUE;
	SHORT err;

	*bnoDst = 0;

	if ((err = BINRead(bfpSrc, &br, buf, sizeof(buf), bnoSrc, &readSize)) != 0)
		return err;

	while (readSize != 0) {
		if (bFirst) {
			if ((err = BINWrite(bfpDst, buf, readSize, bnoDst)) != 0)
				return err;
			bFirst = FALSE;
		} else {
			if ((err = BINWriteNext(bfpDst, buf, readSize)) != 0)
				return err;
		}

		if ((err = BINReadNext(bfpSrc, &br, buf, sizeof(buf), &readSize)) != 0)
			return err;
	}

	return 0;
}