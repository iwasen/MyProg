/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: サーバ側ファイル共通処理 (Windows NT)
 *		ファイル名	: scfwnt.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <sys\types.h>
#include <sys\stat.h>
#include "pcserve.h"
#include "pcsio.h"

/*=======================================================================
 |
 |		PSCFCopy() 関数実行処理
 |
 |	VOID	ExecPSCFCopy(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSCFCopy(PSCB pSCB)
{
	struct	PRM_CF_COPY	*pParam;
	SHORT	status;
	CHAR	srcPathName[256];
	CHAR	dstPathName[256];
	SHORT	permission;

	pParam = (struct PRM_CF_COPY *)pSCB->command.param;
	pParam->srcFileName[sizeof(pParam->srcFileName) - 1] = '\0';
	pParam->dstFileName[sizeof(pParam->dstFileName) - 1] = '\0';
	if ((status = ConvertPathName(pParam->srcFileName, srcPathName)) == 0) {
		if ((status = CheckPermission(pSCB, srcPathName, &permission)) == 0) {
			if (permission & PERMISSION_READ) {
				if ((status = ConvertPathName(pParam->dstFileName, dstPathName)) == 0) {
					if ((status = CheckPermission(pSCB, dstPathName, &permission)) == 0) {
						if (permission & PERMISSION_WRITE) {
							if (__COPYFILE(srcPathName, dstPathName))
								status = 0;
							else
								status = (SHORT)GetLastError();
						} else
							status = ERROR_FILE_PERMISSION;
					}
				}
			} else
				status = ERROR_FILE_PERMISSION;
		}
	}

	pSCB->response.dataLength = 0;
	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSCFDelete() 関数実行処理
 |
 |	VOID	ExecPSCFDelete(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSCFDelete(PSCB pSCB)
{
	struct	PRM_CF_DELETE	*pParam;
	SHORT	status;
	CHAR	fileName[256];
	SHORT	permission;

	pParam = (struct PRM_CF_DELETE *)pSCB->command.param;
	pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';
	if ((status = ConvertPathName(pParam->fileName, fileName)) == 0) {
		if ((status = CheckPermission(pSCB, fileName, &permission)) == 0) {
			if (permission & PERMISSION_WRITE) {
				if (__DELETE(fileName)) {
					MCFDelete(pSCB, NULL, FALSE);
					status = 0;
				} else
					status = (SHORT)GetLastError();
			} else
				status = ERROR_FILE_PERMISSION;
		}
	}

	pSCB->response.dataLength = 0;
	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSCFFindFirst() 関数実行処理
 |
 |	VOID	ExecPSCFFindFirst(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSCFFindFirst(PSCB pSCB)
{
	struct	PRM_CF_FINDFIRST	*pParam;
	WIN32_FIND_DATA	findBuf;
	SHORT	status;
	CHAR	pathName[256];

	if (pSCB->hdir != -1) {
		FindClose((HANDLE)pSCB->hdir);
		pSCB->hdir = -1;
	}

	pParam = (struct PRM_CF_FINDFIRST *)pSCB->command.param;
	pParam->pathName[sizeof(pParam->pathName) - 1] = '\0';
	if ((status = ConvertPathName(pParam->pathName, pathName)) == 0) {
		pSCB->hdir = (int)FindFirstFile(pathName, &findBuf);
		if (pSCB->hdir != -1) {
			strcpy(pSCB->lpData, findBuf.cFileName);
			pSCB->response.ret.sValue = (SHORT)(findBuf.dwFileAttributes & 0x7f);
			pSCB->response.dataLength = strlen(findBuf.cFileName) + 1;
			status = 0;
		} else {
			pSCB->response.ret.sValue = 0;
			pSCB->response.dataLength = 0;
			status = (SHORT)GetLastError();

			if (status == 2)
				status = 18;
		}
	}

	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSCFFindNext() 関数実行処理
 |
 |	VOID	ExecPSCFFindNext(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSCFFindNext(PSCB pSCB)
{
	WIN32_FIND_DATA	findBuf;
	SHORT	status;

	if (FindNextFile((HANDLE)pSCB->hdir, &findBuf)) {
		strcpy(pSCB->lpData, findBuf.cFileName);
		pSCB->response.ret.sValue = (SHORT)(findBuf.dwFileAttributes & 0x7f);
		pSCB->response.dataLength = strlen(findBuf.cFileName) + 1;
		status = 0;
	} else {
		pSCB->response.ret.sValue = 0;
		pSCB->response.dataLength = 0;
		status = (SHORT)GetLastError();
	}

	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSCFRename() 関数実行処理
 |
 |	VOID	ExecPSCFRename(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSCFRename(PSCB pSCB)
{
	struct	PRM_CF_RENAME	*pParam;
	SHORT	status;
	CHAR	oldPathName[256];
	CHAR	newPathName[256];
	SHORT	permission;

	pParam = (struct PRM_CF_RENAME *)pSCB->command.param;
	pParam->oldFileName[sizeof(pParam->oldFileName) - 1] = '\0';
	pParam->newFileName[sizeof(pParam->newFileName) - 1] = '\0';
	if ((status = ConvertPathName(pParam->oldFileName, oldPathName)) == 0) {
		if ((status = CheckPermission(pSCB, oldPathName, &permission)) == 0) {
			if (permission & PERMISSION_WRITE) {
				if ((status = ConvertPathName(pParam->newFileName, newPathName)) == 0) {
					if (__RENAME(oldPathName, newPathName))
						status = 0;
					else
						status = (SHORT)GetLastError();
				}
			} else
				status = ERROR_FILE_PERMISSION;
		}
	}

	pSCB->response.dataLength = 0;
	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSCFStat() 関数実行処理
 |
 |	VOID	ExecPSCFStat(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSCFStat(PSCB pSCB)
{
	struct	PRM_CF_STAT	*pParam;
	CHAR	fileName[256];
	SHORT	status;
	PSTAT	pStat;
	struct	stat	st;
	struct	tm	*tm;

	pSCB->response.dataLength = 0;

	pParam = (struct PRM_CF_STAT *)pSCB->command.param;
	pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';
	if ((status = ConvertPathName(pParam->fileName, fileName)) == 0) {
		if (stat(fileName, &st) == 0) {
			pStat = (PSTAT)pSCB->lpData;

			pStat->mode = st.st_mode;
			pStat->size = st.st_size;

			tm = localtime(&st.st_mtime);
			pStat->year = tm->tm_year;
			pStat->month = tm->tm_mon + 1;
			pStat->day = tm->tm_mday;
			pStat->hour = tm->tm_hour;
			pStat->minute = tm->tm_min;
			pStat->second = tm->tm_sec;

			pSCB->response.dataLength = sizeof(STAT);
		} else
			status = errno;
	}

	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSCFCreateDirectory() 関数実行処理
 |
 |	VOID	ExecPSCFCreateDirectory(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSCFCreateDirectory(PSCB pSCB)
{
	struct	PRM_CF_CREATEDIRECTORY	*pParam;
	CHAR	dirName[256];
	SHORT	status;

	pSCB->response.dataLength = 0;

	pParam = (struct PRM_CF_CREATEDIRECTORY *)pSCB->command.param;
	pParam->dirName[sizeof(pParam->dirName) - 1] = '\0';
	if ((status = ConvertPathName(pParam->dirName, dirName)) == 0) {
		if (CreateDirectory(dirName, NULL))
			status = 0;
		else
			status = (SHORT)GetLastError();
	}

	pSCB->response.dataLength = 0;
	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSCFRemoveDirectory() 関数実行処理
 |
 |	VOID	ExecPSCFRemoveDirectory(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSCFRemoveDirectory(PSCB pSCB)
{
	struct	PRM_CF_REMOVEDIRECTORY	*pParam;
	CHAR	dirName[256];
	SHORT	status;

	pSCB->response.dataLength = 0;

	pParam = (struct PRM_CF_REMOVEDIRECTORY *)pSCB->command.param;
	pParam->dirName[sizeof(pParam->dirName) - 1] = '\0';
	if ((status = ConvertPathName(pParam->dirName, dirName)) == 0) {
		if (RemoveDirectory(dirName))
			status = 0;
		else
			status = (SHORT)GetLastError();
	}

	pSCB->response.dataLength = 0;
	pSCB->response.status = status;
}
