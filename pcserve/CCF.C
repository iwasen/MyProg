/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: クライアント側ファイル共通処理
 *		ファイル名	: ccf.c	
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>
#include <time.h>
#include <direct.h>

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

/*=======================================================================
 |
 |		ファイルコピー
 |
 |	SHORT	PSCFCopy(pCCB, srcFile, dstFile)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*srcFile;	コピー元ファイル名
 |		CHAR	*dstFile;	コピー先ファイル名
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSCFCopy(PCCB pCCB, CHAR *srcFile, CHAR *dstFile)
{
	struct	PRM_CF_COPY	*pParam;
	int	srcFH, dstFH, len;
	CHAR	*bufp;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		if ((bufp = malloc(8192)) == NULL) {
			err = ERROR_CLIENT_MEMORY;
			goto ret;
		}
		if ((srcFH = open(srcFile, O_RDONLY|O_BINARY, 0)) == -1) {
			err = errno;
			free(bufp);
			goto ret;
		}
		if ((dstFH = open(dstFile, O_BINARY|O_RDWR|O_CREAT|O_TRUNC,
						S_IREAD|S_IWRITE)) == -1) {
			err = errno;
			free(bufp);
			close(srcFH);
			goto ret;
		}
		err = 0;
		while ((len = read(srcFH, bufp, 8192)) != 0) {
			if (len == -1) {
				err = errno;
				break;
			}
			if (write(dstFH, bufp, len) != len) {
				err = errno;
				break;
			}
		}
		free(bufp);
		close(srcFH);
		close(dstFH);
	} else {
		if (strlen(srcFile) >= sizeof(pParam->srcFileName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		if (strlen(dstFile) >= sizeof(pParam->dstFileName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_CF_COPY *)pCCB->command.param;
		strcpy(pParam->srcFileName, srcFile);
		strcpy(pParam->dstFileName, dstFile);

		err = SendCommand(pCCB, FC_CF_COPY,
					sizeof(struct PRM_CF_COPY), 0);
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		ファイル削除
 |
 |	SHORT	PSCFDelete(pCCB, fileName)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*fileName;	ファイル名
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSCFDelete(PCCB pCCB, CHAR *fileName)
{
	struct	PRM_CF_DELETE	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		if (remove(fileName) == -1)
			err = errno;
		else
			err = 0;
	} else {
		if (strlen(fileName) >= sizeof(pParam->fileName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_CF_DELETE *)pCCB->command.param;
		strcpy(pParam->fileName, fileName);

		err = SendCommand(pCCB, FC_CF_DELETE,
					sizeof(struct PRM_CF_DELETE), 0);
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		最初のファイル名取得
 |
 |	SHORT	PSCFFindFirst(pCCB, pathName, fileName, attribute)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		char	*pathName;	検索するファイル名（ワイルドカード可）
 |		char	*fileName;	検索されたファイル名
 |		short	*attribute;	ファイル属性
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSCFFindFirst(PCCB pCCB, CHAR *pathName, CHAR *fileName,
							USHORT *attribute)
{
	struct	PRM_CF_FINDFIRST	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
#if defined OS_MSDOS || defined OS_WINDOWS
		err = _dos_findfirst(pathName, *attribute, &pCCB->find);
		if (err == 0) {
			strcpy(fileName, pCCB->find.name);
			*attribute = pCCB->find.attrib;
		}
#elif defined OS_WNT
		WIN32_FIND_DATA	findBuf;

		if (pCCB->hdir != INVALID_HANDLE_VALUE) {
			FindClose(pCCB->hdir);
			pCCB->hdir = INVALID_HANDLE_VALUE;
		}

		pCCB->hdir = FindFirstFile(pathName, &findBuf);
		if (pCCB->hdir != INVALID_HANDLE_VALUE) {
			strcpy(fileName, findBuf.cFileName);
			*attribute =
				(SHORT)(findBuf.dwFileAttributes & 0x7f);
			err = 0;
		} else {
			err = (SHORT)GetLastError();
			if (err == 2)
				err = 18;
		}
#else
		err = ERROR_CLIENT_NOSUPPORT;
#endif
	} else {
		if (strlen(pathName) >= sizeof(pParam->pathName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_CF_FINDFIRST *)pCCB->command.param;
		strcpy(pParam->pathName, pathName);
		pParam->attribute = *attribute;

		pCCB->pOutData = fileName;

		err = SendCommand(pCCB, FC_CF_FINDFIRST,
					sizeof(struct PRM_CF_FINDFIRST), 0);
		if (err == 0)
			*attribute = pCCB->response.ret.sValue;
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		次のファイル名取得
 |
 |	SHORT	PSCFFindNext(pCCB, fileName, attribute)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		char	*fileName;	検索されたファイル名
 |		short	*attribute;	ファイル属性
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSCFFindNext(PCCB pCCB, CHAR *fileName, USHORT *attribute)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
#if defined OS_MSDOS || defined OS_WINDOWS
		err = _dos_findnext(&pCCB->find);
		if (err == 0) {
			strcpy(fileName, pCCB->find.name);
			*attribute = pCCB->find.attrib;
		}
#elif defined OS_WNT
		WIN32_FIND_DATA	findBuf;

		if (FindNextFile((HANDLE)pCCB->hdir, &findBuf)) {
			strcpy(fileName, findBuf.cFileName);
			*attribute = (SHORT)(findBuf.dwFileAttributes & 0x7f);
			err = 0;
		} else {
			err = (SHORT)GetLastError();
		}
#else
		err = ERROR_CLIENT_NOSUPPORT;
#endif
	} else {
		pCCB->pOutData = fileName;

		err = SendCommand(pCCB, FC_CF_FINDNEXT, 0, 0);
		*attribute = pCCB->response.ret.sValue;
	}

	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		ファイル名変更
 |
 |	SHORT	PSCFRename(pCCB, oldFileName, newFileName)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		char	*oldFileName;	変更前ファイル名
 |		char	*newFileName;	変更後ファイル名
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSCFRename(PCCB pCCB, CHAR *oldFileName, CHAR *newFileName)
{
	struct	PRM_CF_RENAME	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		if (rename(oldFileName, newFileName) == -1)
			err = errno;
		else
			err = 0;
	} else {
		if (strlen(oldFileName) >= sizeof(pParam->oldFileName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		if (strlen(newFileName) >= sizeof(pParam->newFileName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_CF_RENAME *)pCCB->command.param;
		strcpy(pParam->oldFileName, oldFileName);
		strcpy(pParam->newFileName, newFileName);

		err = SendCommand(pCCB, FC_CF_RENAME,
					sizeof(struct PRM_CF_RENAME), 0);
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		ファイル情報取得
 |
 |	SHORT	PSCFStat(pCCB, fileName, pStat)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		char	*FileName;	ファイル名
 |		PSTAT	pStat;		ファイル情報ポインタ
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSCFStat(PCCB pCCB, CHAR *fileName, PSTAT pStat)
{
	struct	PRM_CF_STAT	*pParam;
	struct	stat	st;
	struct	tm	*tm;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		if (stat(fileName, &st) == -1)
			err = errno;
		else {
			pStat->mode = st.st_mode;
			pStat->size = st.st_size;

			tm = localtime(&st.st_mtime);
			pStat->year = tm->tm_year;
			pStat->month = tm->tm_mon + 1;
			pStat->day = tm->tm_mday;
			pStat->hour = tm->tm_hour;
			pStat->minute = tm->tm_min;
			pStat->second = tm->tm_sec;

			err = 0;
		}
	} else {
		if (strlen(fileName) >= sizeof(pParam->fileName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_CF_STAT *)pCCB->command.param;
		strcpy(pParam->fileName, fileName);
		pCCB->pOutData = pStat;

		err = SendCommand(pCCB, FC_CF_STAT,
					sizeof(struct PRM_CF_STAT), 0);
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		ディレクトリ作成
 |
 |	SHORT	PSCFCreateDirectory(pCCB, dirName)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*dirName;	ディレクトリ名
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSCFCreateDirectory(PCCB pCCB, CHAR *dirName)
{
	struct	PRM_CF_CREATEDIRECTORY	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		if (mkdir(dirName) == -1)
			err = errno;
		else
			err = 0;
	} else {
		if (strlen(dirName) >= sizeof(pParam->dirName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_CF_CREATEDIRECTORY *)pCCB->command.param;
		strcpy(pParam->dirName, dirName);

		err = SendCommand(pCCB, FC_CF_CREATEDIRECTORY,
				sizeof(struct PRM_CF_CREATEDIRECTORY), 0);
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		ディレクトリ削除
 |
 |	SHORT	PSCFRemoveDirectory(pCCB, dirName)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*dirName;	ディレクトリ名
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSCFRemoveDirectory(PCCB pCCB, CHAR *dirName)
{
	struct	PRM_CF_REMOVEDIRECTORY	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		if (rmdir(dirName) == -1)
			err = errno;
		else
			err = 0;
	} else {
		if (strlen(dirName) >= sizeof(pParam->dirName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_CF_REMOVEDIRECTORY *)pCCB->command.param;
		strcpy(pParam->dirName, dirName);

		err = SendCommand(pCCB, FC_CF_REMOVEDIRECTORY,
				sizeof(struct PRM_CF_REMOVEDIRECTORY), 0);
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}
