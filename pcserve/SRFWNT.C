/************************************************************************
 *
 *			ＰＣＳＥＲＶＥ
 *
 *		名称		: サーバ側ランダムファイル処理 (Windows NT)
 *		ファイル名	: srfwnt.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"
#include "pcsio.h"

static	DWORD	GetAccessMode(USHORT);
static	DWORD	GetShareMode(USHORT);
static SHORT CheckAcessMode(PSCB pSCB, char *pPathName, USHORT accessMode);

/*=======================================================================
 |
 |		PSRFOpen() 関数実行処理
 |
 |	VOID	ExecPSRFOpen(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFOpen(SCB *pSCB)
{
	struct	PRM_RF_OPEN	*pParam;
	HANDLE	handle;
	PFCB	pFCB;
	int	nFCB;
	SHORT	status, status2;
	CHAR	pathName[FILENAME_MAX];
	CHAR	fileName[FILENAME_MAX];

	nFCB = AllocFCB(pSCB);

	if (nFCB != -1) {
		pFCB = &pSCB->pFCB[nFCB];
		pParam = (struct PRM_RF_OPEN *)pSCB->command.param;
		pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';
		if ((status = ConvertPathName(pParam->fileName, pathName)) == 0) {
			if ((status = CheckAcessMode(pSCB, pathName, pParam->accessMode)) == 0) {
				status = MRFOpen(pSCB, pFCB, pathName);
				if ((status == 0 || status == ERROR_SERVER_MULTI) && pFCB->fileType != FILETYPE_FORWARD) {
					status2 = status;
					_fullpath(fileName, pathName, sizeof(fileName));
					StringToUpper(fileName);
					handle = CreateFile(fileName,
							GetAccessMode(pParam->accessMode),
							GetShareMode(pParam->shareMode),
							NULL, OPEN_EXISTING,
							FILE_ATTRIBUTE_NORMAL, NULL);
					if (handle != INVALID_HANDLE_VALUE) {
						if ((pFCB->pFileName = malloc(strlen(fileName) + 1)) != NULL) {
							pFCB->fileType = FILETYPE_RF;
							pFCB->fileHandle = (int)handle;
							strcpy(pFCB->pFileName, fileName);
							pSCB->login.openFile++;
							status = status2;
						} else
							status = ERROR_SERVER_MEMORY;
					} else
						status = (SHORT)GetLastError();
				}
			}
		}
	} else
		status = ERROR_SERVER_MAXFILES;

	pSCB->response.dataLength = 0;
	pSCB->response.ret.sValue = nFCB;
	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSRFCreate() 関数実行処理
 |
 |	VOID	ExecPSRFCreate(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFCreate(SCB *pSCB)
{
	struct	PRM_RF_CREATE	*pParam;
	HANDLE	handle;
	PFCB	pFCB;
	int	nFCB;
	SHORT	status, status2;
	CHAR	pathName[FILENAME_MAX];
	CHAR	fileName[FILENAME_MAX];

	nFCB = AllocFCB(pSCB);

	if (nFCB != -1) {
		pFCB = &pSCB->pFCB[nFCB];
		pParam = (struct PRM_RF_CREATE *)pSCB->command.param;
		pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';
		if ((status = ConvertPathName(pParam->fileName, pathName)) == 0) {
			if ((status = CheckAcessMode(pSCB, pathName, pParam->accessMode)) == 0) {
					status = MRFCreate(pSCB, pFCB, pathName);
			if ((status == 0 || status == ERROR_SERVER_MULTI) && pFCB->fileType != FILETYPE_FORWARD) {
					status2 = status;
					_fullpath(fileName, pathName, sizeof(fileName));
					StringToUpper(fileName);
#if defined (_SERVER)
					handle = (HANDLE)PSCreateFile(fileName,
							GetAccessMode(pParam->accessMode),
							GetShareMode(pParam->shareMode));
#else
					handle = CreateFile(fileName,
							GetAccessMode(pParam->accessMode),
							GetShareMode(pParam->shareMode),
							NULL, CREATE_ALWAYS,
							FILE_ATTRIBUTE_NORMAL, NULL);
#endif
					if (handle != INVALID_HANDLE_VALUE) {
						if ((pFCB->pFileName = malloc(strlen(fileName)+1)) != NULL) {
							pFCB->fileType = FILETYPE_RF;
							pFCB->fileHandle = (int)handle;
							strcpy(pFCB->pFileName, fileName);
							pSCB->login.openFile++;
							status = status2;
						} else
							status = ERROR_SERVER_MEMORY;
					} else
						status = (SHORT)GetLastError();
				}
			}
		}
	} else
		status = ERROR_SERVER_MAXFILES;

	pSCB->response.dataLength = 0;
	pSCB->response.ret.sValue = nFCB;
	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSRFClose() 関数実行処理
 |
 |	VOID	ExecPSRFClose(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFClose(PSCB pSCB)
{
	struct	PRM_RF_CLOSE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_RF_CLOSE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_RF:
			MRFClose(pSCB, pFCB, FALSE);
			pSCB->login.openFile--;
			status = RFClose(pFCB);
			pFCB->fileType = FILETYPE_NULL;
			break;
		case FILETYPE_FORWARD:
			ForwardCommand(pSCB, pFCB);
			if (pFCB->pMFCB != NULL) {
				free(pFCB->pMFCB);
				pFCB->pMFCB = NULL;
			}
			pFCB->fileType = FILETYPE_NULL;
			return;
		default:
			status = ERROR_FILE_TYPE;
			break;
		}
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSRFRead() 関数実行処理
 |
 |	VOID	ExecPSRFRead(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFRead(PSCB pSCB)
{
	struct	PRM_RF_READ	*pParam;
	PFCB	pFCB;
	DWORD	numBytes;
	SHORT	status;
	USHORT	dataLength = 0;
	SHORT	sValue = 0;

	pParam = (struct PRM_RF_READ *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_RF:
			if (ReadFile((HANDLE)pFCB->fileHandle, pSCB->lpData,
					pParam->length, &numBytes, NULL)) {
				dataLength = (USHORT)numBytes;
				sValue = (SHORT)numBytes;
				status = 0;
			} else
				status = (SHORT)GetLastError();
			break;
		case FILETYPE_FORWARD:
			ForwardCommand(pSCB, pFCB);
			return;
		default:
			status = ERROR_FILE_TYPE;
			break;
		}
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = dataLength;
	pSCB->response.ret.sValue = sValue;
}

/*=======================================================================
 |
 |		PSRFWrite() 関数実行処理
 |
 |	VOID	ExecPSRFWrite(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFWrite(PSCB pSCB)
{
	struct	PRM_RF_WRITE	*pParam;
	PFCB	pFCB;
	DWORD	numBytes;
	SHORT	status;
	SHORT	sValue = 0;

	pParam = (struct PRM_RF_WRITE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_RF:
			if ((status = MRFWrite(pSCB, pFCB, FALSE)) == 0) {
#if defined (_SERVER)
				if ((numBytes = PSWriteFile(pFCB->pFileName,
						pFCB->fileHandle, pSCB->lpData,
						pParam->length, -1)) != 0) {
#else
				if (WriteFile((HANDLE)pFCB->fileHandle,
						pSCB->lpData, pParam->length,
						&numBytes, NULL)) {
#endif
					sValue = (SHORT)numBytes;
					status = 0;
				} else
					status = (SHORT)GetLastError();
			}
			break;
		case FILETYPE_FORWARD:
			ForwardCommand(pSCB, pFCB);
			return;
		default:
			status = ERROR_FILE_TYPE;
			break;
		}
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
	pSCB->response.ret.sValue = sValue;
}

/*=======================================================================
 |
 |		PSRFSeek() 関数実行処理
 |
 |	VOID	ExecPSRFSeek(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFSeek(PSCB pSCB)
{
	struct	PRM_RF_SEEK	*pParam;
	PFCB	pFCB;
	DWORD	filePtr;
	SHORT	status;

	pParam = (struct PRM_RF_SEEK *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_RF:
			filePtr = SetFilePointer((HANDLE)pFCB->fileHandle,
					pParam->offset, NULL, pParam->origin);
			if (filePtr != 0xffffffff) {
				pSCB->response.ret.lValue = filePtr;
				status = 0;
			} else {
				pSCB->response.ret.lValue = filePtr;
				status = (SHORT)GetLastError();
			}
			break;
		case FILETYPE_FORWARD:
			ForwardCommand(pSCB, pFCB);
			return;
		default:
			status = ERROR_FILE_TYPE;
			break;
		}
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSRFLock() 関数実行処理
 |
 |	VOID	ExecPSRFLock(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFLock(PSCB pSCB)
{
	struct	PRM_RF_LOCK	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_RF_LOCK *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_RF:
			if ((status = MRFLock(pSCB, pFCB, FALSE)) == 0) {
				if (LockFile((HANDLE)pFCB->fileHandle,
						pParam->offset, 0,
						pParam->range, 0)) {
					status = 0;
				} else {
					status = (SHORT)GetLastError();
				}
			}
			break;
		case FILETYPE_FORWARD:
			ForwardCommand(pSCB, pFCB);
			return;
		default:
			status = ERROR_FILE_TYPE;
			break;
		}
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSRFUnlock() 関数実行処理
 |
 |	VOID	ExecPSRFUnlock(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFUnlock(PSCB pSCB)
{
	struct	PRM_RF_UNLOCK	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_RF_UNLOCK *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_RF:
			if ((status = MRFUnlock(pSCB, pFCB, FALSE)) == 0) {
				if (UnlockFile((HANDLE)pFCB->fileHandle,
						pParam->offset, 0,
						pParam->range, 0)) {
					status = 0;
				} else {
					status = (SHORT)GetLastError();
				}
			}
			break;
		case FILETYPE_FORWARD:
			ForwardCommand(pSCB, pFCB);
			return;
		default:
			status = ERROR_FILE_TYPE;
			break;
		}
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSRFNewSize() 関数実行処理
 |
 |	VOID	ExecPSRFNewSize(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFNewSize(PSCB pSCB)
{
	struct	PRM_RF_NEWSIZE	*pParam;
	PFCB	pFCB;
	SHORT	status;
#if !defined (_SERVER)
	DWORD	filePtr;
#endif

	pParam = (struct PRM_RF_NEWSIZE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_RF:
			if ((status = MRFNewSize(pSCB, pFCB, FALSE)) == 0) {
#if defined (_SERVER)
				if (PSChangeFileSize(pFCB->pFileName,
							pFCB->fileHandle,
							pParam->fileSize))
					status = 0;
				else
					status = (SHORT)GetLastError();
#else
				filePtr = SetFilePointer(
						(HANDLE)pFCB->fileHandle,
						pParam->fileSize, NULL,
						FILE_BEGIN);
				if (filePtr != 0xffffffff) {
					if (SetEndOfFile((HANDLE)
							pFCB->fileHandle))
						status = 0;
					else
						status = (SHORT)GetLastError();
				} else
					status = (SHORT)GetLastError();
#endif
			}
			break;
		case FILETYPE_FORWARD:
			ForwardCommand(pSCB, pFCB);
			return;
		default:
			status = ERROR_FILE_TYPE;
			break;
		}
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSRFWriteEx() 関数実行処理
 |
 |	VOID	ExecPSRFWriteEx(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSRFWriteEx(PSCB pSCB)
{
	struct	PRM_RF_WRITE_EX	*pParam;
	PFCB	pFCB;
	SHORT	status;
	DWORD	numBytes;
	SHORT	sValue = 0;

	pParam = (struct PRM_RF_WRITE_EX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);
	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		if (SetFilePointer((HANDLE)pFCB->fileHandle, pParam->offset,
					NULL, FILE_BEGIN) != 0xffffffff) {
			if (WriteFile((HANDLE)pFCB->fileHandle, pSCB->lpData,
						pParam->length,
						&numBytes, NULL)) {
				sValue = (SHORT)numBytes;
				status = 0;
			} else
				status = (SHORT)GetLastError();
		} else
			status = (SHORT)GetLastError();
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
	pSCB->response.ret.sValue = sValue;
}

VOID	ExecPSRFFileTime(PSCB pSCB)
{
	struct	PRM_RF_FILETIME	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_RF_FILETIME *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);
	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		if (SetFileTime((HANDLE)pFCB->fileHandle, &pParam->ftCreation,
				&pParam->ftLastAccess, &pParam->ftLastWrite))
			status = 0;
		else
			status = (SHORT)GetLastError();
	}

	pSCB->response.status = status;
	pSCB->response.ret.sValue = 0;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		ランダムファイルクローズ処理
 |
 |	SHORT	RFClose(pFCB)
 |
 |		PFCB	pFCB;		ファイル制御ブロックポインタ
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	RFClose(PFCB pFCB)
{
	if (pFCB->pFileName != NULL) {
		free(pFCB->pFileName);
		pFCB->pFileName = NULL;
	}

	if (!CloseHandle((HANDLE)pFCB->fileHandle))
		return (SHORT)GetLastError();

	return 0;
}

/*=======================================================================
 |
 |		アクセスモード取り出し処理
 |
 |	DWORD	GetAccessMode(USHORT accessMode)
 |
 |		USHORT	accessMode;	アクセスモード
 |
 |		DWORD	返値		CreateFile() のアクセスモード
 |
 =======================================================================*/
static	DWORD	GetAccessMode(USHORT accessMode)
{
	switch (accessMode) {
	case PSOPEN_ACCESS_READONLY:
		return GENERIC_READ;
	case PSOPEN_ACCESS_WRITEONLY:
		return GENERIC_WRITE;
	case PSOPEN_ACCESS_READWRITE:
		return GENERIC_READ | GENERIC_WRITE;
	}
	return 0;
}

/*=======================================================================
 |
 |		共有モード取り出し処理
 |
 |	DWORD	GetShareMode(USHORT shareMode)
 |
 |		USHORT	shareMode;	共有モード
 |
 |		DWORD	返値		CreateFile() の共有モード
 |
 =======================================================================*/
static	DWORD	GetShareMode(USHORT shareMode)
{
	switch (shareMode) {
	case PSOPEN_SHARE_DENYNONE:
		return FILE_SHARE_READ | FILE_SHARE_WRITE;
	case PSOPEN_SHARE_DENYREAD:
		return FILE_SHARE_WRITE;
	case PSOPEN_SHARE_DENYWRITE:
		return FILE_SHARE_READ;
	case PSOPEN_SHARE_DENYREADWRITE:
		return 0;
	}
	return 0;
}

static SHORT CheckAcessMode(PSCB pSCB, char *pPathName, USHORT accessMode)
{
	SHORT status;
	SHORT permission;

	if ((status = CheckPermission(pSCB, pPathName, &permission)) == 0) {
		switch (accessMode) {
		case PSOPEN_ACCESS_READONLY:
			if ((permission & PERMISSION_READ) == 0)
				status = ERROR_FILE_PERMISSION;
			break;
		case PSOPEN_ACCESS_WRITEONLY:
			if ((permission & PERMISSION_WRITE) == 0)
				status = ERROR_FILE_PERMISSION;
			break;
		case PSOPEN_ACCESS_READWRITE:
			if ((permission & (PERMISSION_READ | PERMISSION_WRITE)) != (PERMISSION_READ | PERMISSION_WRITE))
				status = ERROR_FILE_PERMISSION;
			break;
		default:
			status = ERROR_FILE_PERMISSION;
			break;
		}
	}

	return status;
}
