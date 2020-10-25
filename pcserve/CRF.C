/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �N���C�A���g�������_���t�@�C������
 *		�t�@�C����	: crf.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>

#ifdef	OS_VMS
#include <unixio.h>
#include <file.h>
#include <types.h>
#include <stat.h>
#define	O_BINARY	0x0000
#else
#include <io.h>
#include <fcntl.h>
#include <sys/types.h>
#include <sys/stat.h>
#endif

#include "pcsclnt.h"

#if defined OS_WINDOWS || defined OS_WNT
#define	read(p1,p2,p3)		_lread(p1,p2,p3)
#define	write(p1,p2,p3)		_lwrite(p1,p2,p3)
#define	lseek(p1,p2,p3)		_llseek(p1,p2,p3)
#define	close(p1)		_lclose(p1)
#endif

static	USHORT	GetOpenMode(USHORT, USHORT);

/*=======================================================================
 |
 |		�t�@�C���I�[�v��
 |
 |	PFCB	PSRFOpen(pCCB, fileName, accessMode, shareMode)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*fileName;	�t�@�C����
 |		USHORT	accessMode;	�A�N�Z�X���[�h
 |					�@�O�FREAD ��p
 |					�@�P�FWRITE ��p
 |					�@�Q�FREAD/WRITE
 |		USHORT	shareMode;	���L���[�h
 |					�@�O�F�֎~����
 |					�@�P�FREAD �֎~
 |					�@�Q�FWRITE �֎~
 |					�@�R�FREAD/WRITE �֎~
 |
 |		PFCB	�Ԓl		�t�@�C������u���b�N�|�C���^
 |
 =======================================================================*/
PFCB	PSENTRY PSRFOpen(PCCB pCCB, CHAR *fileName,
					USHORT accessMode, USHORT shareMode)
{
	PFCB	pFCB;
	struct	PRM_RF_OPEN	*pParam;
	USHORT	openMode;
#if defined OS_WINDOWS || defined OS_WNT
	OFSTRUCT	openBuff;
#endif

	if ((pFCB = malloc(sizeof(FCB))) == NULL) {
		pCCB->errorCode = ERROR_CLIENT_MEMORY;
		return NULL;
	}

	memset(pFCB, 0, sizeof(FCB));
	pFCB->fileType = FILETYPE_RF;
	pFCB->pCCB = pCCB;

	if (pCCB->chType == CHT_LOCAL) {
		openMode = GetOpenMode(accessMode, shareMode);
#if defined OS_WINDOWS || defined OS_WNT
		if ((pFCB->fileHandle = OpenFile(fileName, &openBuff, openMode)) == HFILE_ERROR) {
			pCCB->errorCode = openBuff.nErrCode;
#else
		if ((pFCB->fileHandle = open(fileName, openMode, 0)) == -1) {
			pCCB->errorCode = errno;
#endif
			free(pFCB);
			return NULL;
		}
	} else {
		if (strlen(fileName) >= sizeof(pParam->fileName)) {
			pCCB->errorCode = ERROR_CLIENT_PARAMETER;
			return NULL;
		}
retry:
		pParam = (struct PRM_RF_OPEN *)pCCB->command.param;
		strcpy(pParam->fileName, fileName);
		pParam->accessMode = accessMode;
		pParam->shareMode = shareMode;

		if ((pCCB->errorCode = SendCommand(pCCB, FC_RF_OPEN, sizeof(struct PRM_RF_OPEN), 0)) == ERROR_NOLOGIN) {
			if (LoginDialog(pCCB))
				goto retry;
		}

		if (pCCB->errorCode != 0) {
			free(pFCB);
			return NULL;
		}

		pFCB->fileHandle = pCCB->response.ret.sValue;
	}

	pFCB->chain = pCCB->pFCB;
	pCCB->pFCB = pFCB;

	return pFCB;
}

/*=======================================================================
 |
 |		�t�@�C���쐬
 |
 |	PFCB	PSRFCreate(pCCB, fileName, accessMode, shareMode)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*fileName;	�t�@�C����
 |		USHORT	accessMode;	�A�N�Z�X���[�h
 |					�@�O�FREAD ��p
 |					�@�P�FWRITE ��p
 |					�@�Q�FREAD/WRITE
 |		USHORT	shareMode;	���L���[�h
 |					�@�O�F�֎~����
 |					�@�P�FREAD �֎~
 |					�@�Q�FWRITE �֎~
 |					�@�R�FREAD/WRITE �֎~
 |
 |		PFCB	�Ԓl		�t�@�C������u���b�N�|�C���^
 |
 =======================================================================*/
PFCB	PSENTRY PSRFCreate(PCCB pCCB, CHAR *fileName,
					USHORT accessMode, USHORT shareMode)
{
	PFCB	pFCB;
	struct	PRM_RF_CREATE	*pParam;
#if defined OS_WINDOWS || defined OS_WNT
	USHORT	openMode;
	OFSTRUCT	openBuff;
#endif

	if ((pFCB = malloc(sizeof(FCB))) == NULL) {
		pCCB->errorCode = ERROR_CLIENT_MEMORY;
		return NULL;
	}

	memset(pFCB, 0, sizeof(FCB));
	pFCB->fileType = FILETYPE_RF;
	pFCB->pCCB = pCCB;

	if (pCCB->chType == CHT_LOCAL) {
#if defined OS_MSDOS
		if ((pFCB->fileHandle = open(fileName,
					O_BINARY|O_RDWR|O_CREAT|O_TRUNC,
					S_IREAD|S_IWRITE)) == -1) {
			pCCB->errorCode = errno;
#elif	defined OS_WINDOWS || defined OS_WNT
		openMode = GetOpenMode(accessMode, shareMode);
		if ((pFCB->fileHandle = OpenFile(fileName, &openBuff,
				openMode | OF_CREATE)) == HFILE_ERROR) {
			pCCB->errorCode = openBuff.nErrCode;
#else
		if ((pFCB->fileHandle = creat(fileName, S_IREAD|S_IWRITE)) == -1) {
			pCCB->errorCode = errno;
#endif
			free(pFCB);
			return NULL;
		}
	} else {
		if (strlen(fileName) >= sizeof(pParam->fileName)) {
			pCCB->errorCode = ERROR_CLIENT_PARAMETER;
			return NULL;
		}
retry:
		pParam = (struct PRM_RF_CREATE *)pCCB->command.param;
		strcpy(pParam->fileName, fileName);
		pParam->accessMode = accessMode;
		pParam->shareMode = shareMode;

		if ((pCCB->errorCode = SendCommand(pCCB, FC_RF_CREATE, sizeof(struct PRM_RF_CREATE), 0)) == ERROR_NOLOGIN) {
			if (LoginDialog(pCCB))
				goto retry;
		}

		if (pCCB->errorCode != 0) {
			free(pFCB);
			return NULL;
		}

		pFCB->fileHandle = pCCB->response.ret.sValue;
	}

	pFCB->chain = pCCB->pFCB;
	pCCB->pFCB = pFCB;

	return pFCB;
}

/*=======================================================================
 |
 |		�t�@�C���N���[�Y
 |
 |	SHORT	PSRFClose(pFCB)
 |
 |		PFCB	pFCB;		�t�@�C������u���b�N�|�C���^
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY PSRFClose(PFCB pFCB)
{
	PCCB	pCCB;
	PFCB	*pPFCB;
	struct	PRM_RF_CLOSE	*pParam;
	SHORT	err;

	pCCB = pFCB->pCCB;

	if (pCCB->chType == CHT_LOCAL) {
#ifdef	OS_WONDOWS
		if (_lclose(pFCB->fileHandle) == -1)
			err = -1;
#else
		if (close(pFCB->fileHandle) == -1)
			err = errno;
#endif
		else
			err = 0;
	} else {
		pParam = (struct PRM_RF_CLOSE *)pCCB->command.param;
		pParam->handle = pFCB->fileHandle;

		err = SendCommand(pCCB, FC_RF_CLOSE, sizeof(struct PRM_RF_CLOSE), 0);
	}

	for (pPFCB = &pCCB->pFCB; *pPFCB != 0; pPFCB = &(*pPFCB)->chain) {
		if ((*pPFCB) == pFCB) {
			*pPFCB = (*pPFCB)->chain;
			break;
		}
	}

	if (err != 0)
		pCCB->errorCode = err;

	free(pFCB);

	return err;
}

/*=======================================================================
 |
 |		�t�@�C���ǂݍ���
 |
 |	SHORT	PSRFRead(pFCB, bufp, length)
 |
 |		PFCB	pFCB;		�t�@�C������u���b�N�|�C���^
 |		VOID	*bufp;		�o�b�t�@�|�C���^
 |		USHORT	length;		�ǂݍ��ރo�C�g��
 |
 |		SHORT	�Ԓl		�ǂݍ��񂾃o�C�g���i�G���[�̏ꍇ�� -1�j
 |
 =======================================================================*/
SHORT	PSENTRY PSRFRead(PFCB pFCB, VOID *bufp, USHORT length)
{
	PCCB	pCCB;
	struct	PRM_RF_READ	*pParam;
	SHORT	err;
	SHORT	rc;

	pCCB = pFCB->pCCB;

	if (pCCB->chType == CHT_LOCAL) {
#if defined OS_WINDOWS || defined OS_WNT
		if ((rc = _lread(pFCB->fileHandle, bufp, length)) == -1)
			err = -1;
#else
		if ((rc = read(pFCB->fileHandle, bufp, length)) == -1)
			err = errno;
#endif
		else
			err = 0;
	} else {
		pParam = (struct PRM_RF_READ *)pCCB->command.param;
		pParam->handle = pFCB->fileHandle;
		pParam->length = length;

		pCCB->pOutData = bufp;

		err = SendCommand(pCCB, FC_RF_READ,
					sizeof(struct PRM_RF_READ), 0);
		rc = (err == 0) ? pCCB->response.ret.sValue : -1;
	}

	if (err != 0) {
		pCCB->errorCode = err;
		pFCB->errorCode = err;
	}

	return rc;
}

/*=======================================================================
 |
 |		�t�@�C����������
 |
 |	SHORT	PSRFWrite(pFCB, bufp, length)
 |
 |		PFCB	pFCB;		�t�@�C������u���b�N�|�C���^
 |		VOID	*bufp;		�o�b�t�@�|�C���^
 |		USHORT	length;		�������ރo�C�g��
 |
 |		SHORT	�Ԓl		�������񂾃o�C�g���i�G���[�̏ꍇ�� -1�j
 |
 =======================================================================*/
SHORT	PSENTRY PSRFWrite(PFCB pFCB, VOID *bufp, USHORT length)
{
	PCCB	pCCB;
	struct	PRM_RF_WRITE	*pParam;
	SHORT	err;
	SHORT	rc;

	pCCB = pFCB->pCCB;

	if (pCCB->chType == CHT_LOCAL) {
#if defined OS_WINDOWS || defined OS_WNT
		if ((rc = _lwrite(pFCB->fileHandle, bufp, length)) == -1)
			err = -1;
#else
		if ((rc = write(pFCB->fileHandle, bufp, length)) == -1)
			err = errno;
#endif
		else
			err = 0;
	} else {
		pParam = (struct PRM_RF_WRITE *)pCCB->command.param;
		pParam->handle = pFCB->fileHandle;
		pParam->length = length;

		pCCB->pInData = bufp;

		err = SendCommand(pCCB, FC_RF_WRITE,
					sizeof(struct PRM_RF_WRITE), length);
		rc = (err == 0) ? pCCB->response.ret.sValue : -1;
	}

	if (err != 0) {
		pCCB->errorCode = err;
		pFCB->errorCode = err;
	}

	return rc;
}

/*=======================================================================
 |
 |		�t�@�C���|�C���^�ړ�
 |
 |	LONG	PSRFSeek(pFCB, offset, origin)
 |
 |		PFCB	pFCB;		�t�@�C������u���b�N�|�C���^
 |		LONG	offset;		�I�t�Z�b�g
 |		SHORT	origin;		��ʒu
 |
 |		LONG	�Ԓl		�ړ���̃J�����g�|�C���^
 |					�i�G���[�̏ꍇ�� -1�j
 |
 =======================================================================*/
LONG	PSENTRY PSRFSeek(PFCB pFCB, LONG offset, SHORT origin)
{
	PCCB	pCCB;
	struct	PRM_RF_SEEK	*pParam;
	SHORT	err;
	LONG	rc;

	pCCB = pFCB->pCCB;

	if (pCCB->chType == CHT_LOCAL) {
#if defined OS_WINDOWS || defined OS_WNT
		if ((rc = _llseek(pFCB->fileHandle, offset, origin)) == -1)
			err = -1;
#else
		if ((rc = lseek(pFCB->fileHandle, offset, origin)) == -1)
			err = errno;
#endif
		else
			err = 0;
	} else {
		pParam = (struct PRM_RF_SEEK *)pCCB->command.param;
		pParam->handle = pFCB->fileHandle;
		pParam->offset = offset;
		pParam->origin = origin;

		err = SendCommand(pCCB, FC_RF_SEEK,
					sizeof(struct PRM_RF_SEEK), 0);
		rc = (err == 0) ? pCCB->response.ret.lValue : -1L;
	}

	if (err != 0) {
		pCCB->errorCode = err;
		pFCB->errorCode = err;
	}

	return rc;
}

/*=======================================================================
 |
 |		�t�@�C�����b�N
 |
 |	SHORT	PSRFLock(pFCB, offset, range)
 |
 |		PFCB	pFCB;		�t�@�C������u���b�N�|�C���^
 |		LONG	offset;		���b�N��������͈͂̐擪�I�t�Z�b�g
 |		LONG	range;		���b�N��������͈͂̃o�C�g��
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY PSRFLock(PFCB pFCB, LONG offset, LONG range)
{
	PCCB	pCCB;
	struct	PRM_RF_LOCK	*pParam;
	SHORT	err;

	pCCB = pFCB->pCCB;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pParam = (struct PRM_RF_LOCK *)pCCB->command.param;
		pParam->handle = pFCB->fileHandle;
		pParam->offset = offset;
		pParam->range = range;

		err = SendCommand(pCCB, FC_RF_LOCK,
					sizeof(struct PRM_RF_LOCK), 0);
	}

	if (err != 0) {
		pCCB->errorCode = err;
		pFCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		�t�@�C�����b�N����
 |
 |	SHORT	PSRFUnlock(pFCB, offset, range)
 |
 |		PFCB	pFCB;		�t�@�C������u���b�N�|�C���^
 |		LONG	offset;		���b�N����������͈͂̐擪�I�t�Z�b�g
 |		LONG	range;		���b�N����������͈͂̃o�C�g��
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY PSRFUnlock(PFCB pFCB, LONG offset, LONG range)
{
	PCCB	pCCB;
	struct	PRM_RF_UNLOCK	*pParam;
	SHORT	err;

	pCCB = pFCB->pCCB;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pParam = (struct PRM_RF_UNLOCK *)pCCB->command.param;
		pParam->handle = pFCB->fileHandle;
		pParam->offset = offset;
		pParam->range = range;

		err = SendCommand(pCCB, FC_RF_UNLOCK,
					sizeof(struct PRM_RF_UNLOCK), 0);
	}

	if (err != 0) {
		pCCB->errorCode = err;
		pFCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		�t�@�C���T�C�Y�ύX
 |
 |	SHORT	PSRFNewSize(pFCB, fileSize)
 |
 |		PFCB	pFCB;		�t�@�C������u���b�N�|�C���^
 |		LONG	fileSize;	�t�@�C���T�C�Y
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY PSRFNewSize(PFCB pFCB, LONG fileSize)
{
	PCCB	pCCB;
	struct	PRM_RF_NEWSIZE	*pParam;
	SHORT	err;

	pCCB = pFCB->pCCB;

	if (pCCB->chType == CHT_LOCAL) {
#if defined OS_WINDOWS || defined OS_WNT
		_llseek(pFCB->fileHandle, fileSize, 0);
		if (_lwrite(pFCB->fileHandle, NULL, 0) == -1)
			err = -1;
#else
		if (chsize(pFCB->fileHandle, fileSize) == -1)
			err = errno;
#endif
		else
			err = 0;
	} else {
		pParam = (struct PRM_RF_NEWSIZE *)pCCB->command.param;
		pParam->handle = pFCB->fileHandle;
		pParam->fileSize = fileSize;

		err = SendCommand(pCCB, FC_RF_NEWSIZE,
					sizeof(struct PRM_RF_NEWSIZE), 0);
	}

	if (err != 0) {
		pCCB->errorCode = err;
		pFCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		�I�[�v�����[�h�擾
 |
 |	USHORT	GetOpenMode(accessMode, shareMode)
 |
 |		USHORT	accessMode;	�A�N�Z�X���[�h
 |		USHORT	shareMode;	���L���[�h
 |
 |		USHORT	�Ԓl		�I�[�v�����[�h
 |
 =======================================================================*/
static	USHORT	GetOpenMode(USHORT accessMode, USHORT shareMode)
{
	USHORT	openMode = 0;

#if defined OS_WINDOWS || defined OS_WNT
	switch (accessMode) {
	case PSOPEN_ACCESS_READONLY:
		openMode = OF_READ;
		break;
	case PSOPEN_ACCESS_WRITEONLY:
		openMode = OF_WRITE;
		break;
	case PSOPEN_ACCESS_READWRITE:
		openMode = OF_READWRITE;
		break;
	}

	switch (shareMode) {
	case PSOPEN_SHARE_DENYNONE:
		openMode |= OF_SHARE_DENY_NONE;
		break;
	case PSOPEN_SHARE_DENYREAD:
		openMode |= OF_SHARE_DENY_READ;
		break;
	case PSOPEN_SHARE_DENYWRITE:
		openMode |= OF_SHARE_DENY_WRITE;
		break;
	case PSOPEN_SHARE_DENYREADWRITE:
		openMode |= OF_SHARE_EXCLUSIVE;
		break;
	}
#else
	switch (accessMode) {
	case PSOPEN_ACCESS_READONLY:
		openMode = O_RDONLY;
		break;
	case PSOPEN_ACCESS_WRITEONLY:
		openMode = O_WRONLY;
		break;
	case PSOPEN_ACCESS_READWRITE:
		openMode = O_RDWR;
		break;
	}

	openMode |= O_BINARY;
#endif

	return openMode;
}