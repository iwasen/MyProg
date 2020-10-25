/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: 二重化サーバ処理
 *		ファイル名	: duplex.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <process.h>
#include <time.h>
#include "pcserve.h"

#define	TIMER_DUPLEX	10000
#define	MAX_OPENDUPLEXFILE	100
#define	MAX_OPEN_TIME		30

#define	ERROR_DUPLEX_OPEN	5000
#define	ERROR_DUPLEX_CONFIG	5001
#define	ERROR_DUPLEX_CONxxxT	5002

static	VOID	ThreadDuplexMaster(LPVOID);
static	VOID	ThreadDuplexSlave(LPVOID);
static	VOID	ConxxxtDuplexServer(VOID);
static	VOID	DisconxxxtDuplexServer(VOID);
static	VOID	ExecDuplexReport(VOID);
static	int	CreateDuplexFile(int, char *);
static	int	WriteDuplexFile(BOOL, char *, char *, int, long);
static	int	ChangeDuplexFileSize(BOOL, char *, int);
static	int	DeleteDuplexFile(BOOL, char *);
static	int	CopyDuplexFile(int, char *, char *);
static	int	RenameDuplexFile(int, char *, char *);
static	VOID	DuplexError(CHAR *, int);
static	PDB	OpenDuplexErrorDBF(VOID);
static	BOOL	DuplexCreateDirectory(CHAR *);
static	PDUPLEXFILE	OpenDuplexFile(CHAR *);
static	VOID	CloseDuplexFile(CHAR *);
static	VOID	CloseDuplexFileAll(VOID);
static	VOID	SendDuplexError(CHAR *fileName, int);
static	VOID	ThreadSyncDuplexFile(CHAR *);
static	VOID	SyncErrorDuplexFile(VOID);
static	VOID	SearchSyncDuplexFile(CHAR *);
static	BOOL	SyncDuplexFile(CHAR *);
static	VOID	AutoRepair(VOID);

static	CHAR	fnDUPLEXERRORDBF[] = "_DERROR.DBF";
static	CHAR	fnDUPLEXERRORNDX[] = "_DERROR.EDX";
static	BOOL	bSyncDuplexFile;
static	DUPLEXSERVER	duplexServer;
static	PDUPLEXFILE	pTopDuplexFile;
static	CRITICALSECTION	csDuplexServer;
static	PDB	pDuplexErrorFCB;

int	bDuplexSlave;

VOID	StartDuplexServer(VOID)
{
	static	CHAR	funcName[] = "StartDuplexServer";

	if (ini.duplexMode == 0)
		return;

//	if ((pDuplexServer = malloc(sizeof(DUPLEXSERVER))) == NULL) {
//		ErrorLog(NULL, "malloc() AllocSize=%d (%s)",
//					sizeof(DUPLEXSERVER), funcName);
//		return;
//	}
//	memset(pDuplexServer, 0, sizeof(DUPLEXSERVER));

	INIT_CRITICAL_SECTION(&csDuplexServer);
	INIT_CRITICAL_SECTION(&duplexServer.csCommand);
	duplexServer.mode = ini.duplexMode;
	strcpy(duplexServer.serverName, ini.duplexServer);
	duplexServer.chType = CHT_NETBIOS;
	if (ini.duplexAutoRepairTime[0] != '\0') {
		duplexServer.autoRepairTime =
				natoi(ini.duplexAutoRepairTime, 2) * 60 +
				natoi(ini.duplexAutoRepairTime + 2, 2);
	} else
		duplexServer.autoRepairTime = -1;

	if (duplexServer.mode == DUPLEX_MASTER) {
		if (_beginthread(ThreadDuplexMaster, 0, NULL) == -1) {
			ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		}
	} if (duplexServer.mode == DUPLEX_SLAVE) {
		if (_beginthread(ThreadDuplexSlave, 0, NULL) == -1) {
			ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		}
	}
}

static	VOID	ThreadDuplexMaster(LPVOID param)
{
	static	BOOL	bLinkDead;

	pDuplexErrorFCB = OpenDuplexErrorDBF();

	for (;;) {
		ConxxxtDuplexServer();

		if (duplexServer.ccb.bConxxxt) {
			if (PSDuplexKeepAlive(&duplexServer.ccb,
							ID_COMMAND) != 0) {
				DisconxxxtDuplexServer();

				if (ini.mailReportDuplexServer) {
					SendReportMail("二重化サーバ情報",
								"リンク切断");
					bLinkDead = TRUE;
				}
			} else if (bLinkDead) {
				if (ini.mailReportDuplexServer) {
					SendReportMail("二重化サーバ情報",
								"リンク回復");
					bLinkDead = FALSE;
				}
			}					
		}

		AutoRepair();

		Sleep(TIMER_DUPLEX);
	}
}

static	VOID	ThreadDuplexSlave(LPVOID param)
{
	PDUPLEXFILE	pDuplexFile, *ppDuplexFile;
	size_t	ltime;

	for (;;) {
		time(&ltime);
		ENTER_CRITICAL_SECTION(&csDuplexServer);
		for (ppDuplexFile = &pTopDuplexFile; *ppDuplexFile != 0; ) {
			pDuplexFile = *ppDuplexFile;
			if (ltime - pDuplexFile->accessTime > MAX_OPEN_TIME) {
				*ppDuplexFile = (*ppDuplexFile)->chain;
				CloseHandle(pDuplexFile->handle);
				free(pDuplexFile);
			} else
				ppDuplexFile = &(*ppDuplexFile)->chain;
		}
		LEAVE_CRITICAL_SECTION(&csDuplexServer);

		if (bDuplexSlave) {
			if (--bDuplexSlave == 0)
				CloseDuplexFileAll();
		}

		Sleep(TIMER_DUPLEX);
	}
}

static	VOID	ConxxxtDuplexServer(VOID)
{
	if (!duplexServer.ccb.bConxxxt) {
		PSConxxxt(&duplexServer.ccb, duplexServer.chType,
					duplexServer.serverName, NULL);
	}
}

static	VOID	DisconxxxtDuplexServer(VOID)
{
	if (duplexServer.ccb.bConxxxt)
		PSDisconxxxt(&duplexServer.ccb);
}

int	PSCreateFile(char *fileName, int accessMode, int shareMode)
{
	HANDLE	hFile;
	int	err;

	hFile = CreateFile(fileName, accessMode,
			shareMode, NULL, CREATE_ALWAYS,
			FILE_ATTRIBUTE_NORMAL, NULL);

	if (duplexServer.mode == DUPLEX_MASTER &&
					hFile != INVALID_HANDLE_VALUE) {
		if ((err = CreateDuplexFile(ID_COMMAND_ASYNC, fileName)) != 0)
			DuplexError(fileName, err);
	}

	return (int)hFile;
}

static	int	CreateDuplexFile(int id, char *fileName)
{
	PCCB	pCCB;
	int	err;

	pCCB = &duplexServer.ccb;

	ENTER_CRITICAL_SECTION(&duplexServer.csCommand);

	if (pCCB->bConxxxt)
		err = PSDuplexCreate(pCCB, id, fileName);
	else
		err = ERROR_DUPLEX_CONxxxT;

	LEAVE_CRITICAL_SECTION(&duplexServer.csCommand);

	return err;
}

int	PSWriteFile(char *fileName, int handle, char *buf, int size,
								long offset)
{
	DWORD	writeByte;
	int	err;

	if (offset != -1)
		SetFilePointer((HANDLE)handle, offset, NULL, FILE_BEGIN);
	else {
		if (duplexServer.mode == DUPLEX_MASTER)
			offset = SetFilePointer((HANDLE)handle, 0, NULL,
								FILE_CURRENT);
	}

	WriteFile((HANDLE)handle, buf, size, &writeByte, NULL);

	if (duplexServer.mode == DUPLEX_MASTER && writeByte != 0) {
		if ((err = WriteDuplexFile(ID_COMMAND_ASYNC, fileName, buf,
							size, offset)) != 0)
			DuplexError(fileName, err);
	}

	return (int)writeByte;
}

static	int	WriteDuplexFile(int id, char *fileName, char *buf,
							int size, long offset)
{
	PCCB	pCCB;
	int	err;

	pCCB = &duplexServer.ccb;

	ENTER_CRITICAL_SECTION(&duplexServer.csCommand);

	if (pCCB->bConxxxt)
		err = PSDuplexWrite(pCCB, id, fileName, buf, size, offset);
	else
		err = ERROR_DUPLEX_CONxxxT;

	LEAVE_CRITICAL_SECTION(&duplexServer.csCommand);

	return err;
}

BOOL	PSChangeFileSize(CHAR *fileName, int handle, int size)
{
	int	err;

	if (SetFilePointer((HANDLE)handle, size, NULL, FILE_BEGIN) ==
							0xffffffff)
		return FALSE;

	if (!SetEndOfFile((HANDLE)handle))
		return FALSE;

	if (duplexServer.mode == DUPLEX_MASTER) {
		if ((err = ChangeDuplexFileSize(ID_COMMAND_ASYNC, fileName,
								size)) != 0)
			DuplexError(fileName, err);
	}

	return TRUE;
}

static	int	ChangeDuplexFileSize(int id, char *fileName, int size)
{
	PCCB	pCCB;
	int	err;

	pCCB = &duplexServer.ccb;

	ENTER_CRITICAL_SECTION(&duplexServer.csCommand);

	if (pCCB->bConxxxt)
		err = PSDuplexNewSize(pCCB, id, fileName, size);
	else
		err = ERROR_DUPLEX_CONxxxT;

	LEAVE_CRITICAL_SECTION(&duplexServer.csCommand);

	return err;
}

int	PSDeleteFile(char *fileName)
{
	int	err;

	if (!DeleteFile(fileName))
		return FALSE;

	if (duplexServer.mode == DUPLEX_MASTER) {
		if ((err = DeleteDuplexFile(ID_COMMAND_ASYNC, fileName)) != 0)
			DuplexError(fileName, err);
	}

	return TRUE;
}

static	int	DeleteDuplexFile(int id, char *fileName)
{
	PCCB	pCCB;
	int	err;

	pCCB = &duplexServer.ccb;

	ENTER_CRITICAL_SECTION(&duplexServer.csCommand);

	if (pCCB->bConxxxt)
		err = PSDuplexDelete(pCCB, id, fileName);
	else
		err = ERROR_DUPLEX_CONxxxT;

	LEAVE_CRITICAL_SECTION(&duplexServer.csCommand);

	return err;
}

int	PSCopyFile(char *srcFileName, char *dstFileName)
{
	int	err;

	if (!CopyFile(srcFileName, dstFileName, FALSE))
		return FALSE;

	if (duplexServer.mode == DUPLEX_MASTER) {
		if ((err = CopyDuplexFile(ID_COMMAND_ASYNC, srcFileName,
							dstFileName)) != 0)
			DuplexError(dstFileName, err);
	}

	return TRUE;
}

static	int	CopyDuplexFile(int id, char *srcFileName, char *dstFileName)
{
	PCCB	pCCB;
	int	err;

	pCCB = &duplexServer.ccb;

	ENTER_CRITICAL_SECTION(&duplexServer.csCommand);

	if (pCCB->bConxxxt)
		err = PSDuplexCopy(pCCB, id, srcFileName, dstFileName);
	else
		err = ERROR_DUPLEX_CONxxxT;

	LEAVE_CRITICAL_SECTION(&duplexServer.csCommand);

	return err;
}

int	PSRename(char *oldFileName, char *newFileName)
{
	int	err;

	if (!MoveFile(oldFileName, newFileName))
		return FALSE;

	if (duplexServer.mode == DUPLEX_MASTER) {
		if ((err = RenameDuplexFile(ID_COMMAND_ASYNC, oldFileName,
							newFileName)) != 0)
			DuplexError(newFileName, err);
	}

	return TRUE;
}

static	int	RenameDuplexFile(int id, char *oldFileName, char *newFileName)
{
	PCCB	pCCB;
	int	err;

	pCCB = &duplexServer.ccb;

	ENTER_CRITICAL_SECTION(&duplexServer.csCommand);

	if (pCCB->bConxxxt)
		err = PSDuplexRename(pCCB, id, oldFileName, newFileName);
	else
		err = ERROR_DUPLEX_CONxxxT;

	LEAVE_CRITICAL_SECTION(&duplexServer.csCommand);

	return err;
}

static	VOID	DuplexError(CHAR *fileName, int err)
{
	static	DWORD	idThread;
	DWORD	idCurrentThread;
	DUPLEXERROR buf;
	char	cbuf[16];
	struct	tm	*tm;
	size_t	ltime;

	idCurrentThread = GetCurrentThreadId();
	if (idCurrentThread == idThread)
		return;

	ENTER_CRITICAL_SECTION(&csDuplexServer);

	idThread = idCurrentThread;

	if (pDuplexErrorFCB != NULL) {
		CopyToDBF(buf.fileName, fileName, sizeof(buf.fileName));
		sprintf(cbuf, "%5d", err);
		CopyToDBF(buf.errorCode, cbuf, sizeof(buf.errorCode));
		time(&ltime);
		tm = localtime(&ltime);
		sprintf(cbuf, "%04d%02d%02d%02d%02d%02d",
				tm->tm_year + 1900, tm->tm_mon + 1, tm->tm_mday,
				tm->tm_hour, tm->tm_min, tm->tm_sec);
		CopyToDBF(buf.time, cbuf, sizeof(buf.time));

		DBStoreUniq(pDuplexErrorFCB, (CHAR *)&buf);
		DBFlush(pDuplexErrorFCB);
	}

	idThread = 0;

	LEAVE_CRITICAL_SECTION(&csDuplexServer);
}

static	PDB	OpenDuplexErrorDBF(VOID)
{
	static	DBF_I	fieldData[] = {
		{"FILENAME",   'C', LEN_FILENAME, 0},
		{"ERRORCODE",  'C',  5, 0},
		{"TIME",       'C', 14, 0}
	};
	PDB	dbp;
	BOOL	bCreate;
	SHORT	idxNo;
	SHORT	err;

	bCreate = FALSE;
	if ((err = DBOpen(fnDUPLEXERRORDBF, &dbp, PERMISSION_ALL)) != 0) {
		if (err == ERROR_DB_NOFILE) {
			if (DBCreate(fnDUPLEXERRORDBF, fieldData, sizeof(fieldData)/sizeof(DBF_I), &dbp, PERMISSION_ALL) != 0)
				return NULL;
			bCreate = TRUE;
		} else {
			return NULL;
		}
	}

	if (bCreate || DBIndex(dbp, fnDUPLEXERRORNDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(dbp, fnDUPLEXERRORNDX, "FILENAME", 1, &idxNo)) != 0) {
			DBClose(dbp);
			return NULL;
		}
	}

	return dbp;
}

VOID	ExecPSDuplexError(PSCB pSCB)
{
	struct	PRM_DUPLEX_ERROR	*pParam;

	pParam = (struct PRM_DUPLEX_ERROR *)pSCB->command.param;
	DuplexError(pParam->fileName, pParam->errorCode);

	pSCB->response.dataLength = 0;
	pSCB->response.status = NOERROR;
}

VOID	ExecPSDuplexKeepAlive(PSCB pSCB)
{
	if (duplexServer.mode == DUPLEX_SLAVE) {
		bDuplexSlave = 5;
		pSCB->response.status = NOERROR;
	} else
		pSCB->response.status = ERROR_DUPLEX_CONFIG;

	pSCB->response.dataLength = 0;
}

VOID	ExecPSDuplexCreate(PSCB pSCB)
{
	struct	PRM_DUPLEX_CREATE	*pParam;
	HANDLE	handle;

	pSCB->response.ret.sValue = 0;
	pSCB->response.dataLength = 0;

	pParam = (struct PRM_DUPLEX_CREATE *)pSCB->command.param;

	CloseDuplexFile(pParam->fileName);

	handle = CreateFile(pParam->fileName, GENERIC_WRITE, 0,
					NULL, CREATE_ALWAYS,
					FILE_ATTRIBUTE_NORMAL, NULL);

	if (handle == INVALID_HANDLE_VALUE &&
				GetLastError() == ERROR_PATH_NOT_FOUND) {
		if (DuplexCreateDirectory(pParam->fileName)) {
			handle = CreateFile(pParam->fileName, GENERIC_WRITE, 0,
					NULL, CREATE_ALWAYS,
					FILE_ATTRIBUTE_NORMAL, NULL);
		}
	}

	if (handle != INVALID_HANDLE_VALUE) {
		CloseHandle(handle);
		pSCB->response.status = 0;
	} else {
		pSCB->response.status = (SHORT)GetLastError();
		if (pSCB->command.id == ID_COMMAND_ASYNC) {
			SendDuplexError(pParam->fileName,
						pSCB->response.status);
		}
	}
}

static	BOOL	DuplexCreateDirectory(CHAR *pathName)
{
	CHAR	*p;

	if ((p = strchr(pathName, '\\')) != NULL) {
		while ((p = strchr(p + 1, '\\')) != NULL) {
			*p = '\0';
			if (GetFileAttributes(pathName) == 0xffffffff) {
				if (!CreateDirectory(pathName, NULL)) {
					*p = '\\';
					return FALSE;
				}
			}
			*p = '\\';
		}
	}

	return TRUE;
}

VOID	ExecPSDuplexWrite(PSCB pSCB)
{
	struct	PRM_DUPLEX_WRITE	*pParam;
	PDUPLEXFILE	pDuplexFile;
	DWORD	writeBytes;

	pSCB->response.ret.sValue = 0;
	pSCB->response.dataLength = 0;

	pParam = (struct PRM_DUPLEX_WRITE *)pSCB->command.param;
	pDuplexFile = OpenDuplexFile(pParam->fileName);
	if (pDuplexFile != NULL) {
		if (SetFilePointer(pDuplexFile->handle, pParam->offset, NULL,
					FILE_BEGIN) != 0xffffffff) {
			if (WriteFile(pDuplexFile->handle, pSCB->lpData,
					pParam->size, &writeBytes, NULL)) {
				pSCB->response.ret.sValue = (SHORT)writeBytes;
				pSCB->response.status = 0;
			} else
				pSCB->response.status = (SHORT)GetLastError();
		} else
			pSCB->response.status = (SHORT)GetLastError();
	} else
		pSCB->response.status = ERROR_DUPLEX_OPEN;

	if (pSCB->response.status != 0) {
		if (pSCB->command.id == ID_COMMAND_ASYNC) {
			SendDuplexError(pParam->fileName,
						pSCB->response.status);
		}
	}
}

VOID	ExecPSDuplexNewSize(PSCB pSCB)
{
	struct	PRM_DUPLEX_NEWSIZE	*pParam;
	PDUPLEXFILE	pDuplexFile;

	pSCB->response.ret.sValue = 0;
	pSCB->response.dataLength = 0;

	pParam = (struct PRM_DUPLEX_NEWSIZE *)pSCB->command.param;
	pDuplexFile = OpenDuplexFile(pParam->fileName);
	if (pDuplexFile != NULL) {
		if (SetFilePointer(pDuplexFile->handle, pParam->size, NULL,
					FILE_BEGIN) != 0xffffffff) {
			if (SetEndOfFile(pDuplexFile->handle))
				pSCB->response.status = 0;
			else
				pSCB->response.status = (SHORT)GetLastError();
		} else
			pSCB->response.status = (SHORT)GetLastError();
	} else
		pSCB->response.status = ERROR_DUPLEX_OPEN;

	if (pSCB->response.status != 0) {
		if (pSCB->command.id == ID_COMMAND_ASYNC) {
			SendDuplexError(pParam->fileName,
						pSCB->response.status);
		}
	}
}

VOID	ExecPSDuplexDelete(PSCB pSCB)
{
	struct	PRM_DUPLEX_DELETE	*pParam;

	pSCB->response.ret.sValue = 0;
	pSCB->response.dataLength = 0;

	pParam = (struct PRM_DUPLEX_DELETE *)pSCB->command.param;

	CloseDuplexFile(pParam->fileName);

	if (DeleteFile(pParam->fileName))
		pSCB->response.status = 0;
	else {
		pSCB->response.status = (SHORT)GetLastError();
		if (pSCB->command.id == ID_COMMAND_ASYNC) {
			SendDuplexError(pParam->fileName,
						pSCB->response.status);
		}
	}
}

VOID	ExecPSDuplexFileTime(PSCB pSCB)
{
	struct	PRM_DUPLEX_FILETIME	*pParam;
	PDUPLEXFILE	pDuplexFile;

	pSCB->response.ret.sValue = 0;
	pSCB->response.dataLength = 0;

	pParam = (struct PRM_DUPLEX_FILETIME *)pSCB->command.param;
	pDuplexFile = OpenDuplexFile(pParam->fileName);
	if (pDuplexFile != NULL) {
		if (SetFileTime(pDuplexFile->handle, &pParam->ftCreation,
				&pParam->ftLastAccess, &pParam->ftLastWrite))
			pSCB->response.status = 0;
		else
			pSCB->response.status = (SHORT)GetLastError();
	} else
		pSCB->response.status = ERROR_DUPLEX_OPEN;

	if (pSCB->response.status != 0) {
		if (pSCB->command.id == ID_COMMAND_ASYNC) {
			SendDuplexError(pParam->fileName,
						pSCB->response.status);
		}
	}
}

VOID	ExecPSDuplexCopy(PSCB pSCB)
{
	struct	PRM_DUPLEX_COPY	*pParam;

	pSCB->response.ret.sValue = 0;
	pSCB->response.dataLength = 0;

	pParam = (struct PRM_DUPLEX_COPY *)pSCB->command.param;

	CloseDuplexFile(pParam->srcFileName);
	CloseDuplexFile(pParam->dstFileName);

	if (CopyFile(pParam->srcFileName, pParam->dstFileName, FALSE))
		pSCB->response.status = 0;
	else {
		pSCB->response.status = (SHORT)GetLastError();
		if (pSCB->command.id == ID_COMMAND_ASYNC) {
			SendDuplexError(pParam->dstFileName,
						pSCB->response.status);
		}
	}
}

VOID	ExecPSDuplexRename(PSCB pSCB)
{
	struct	PRM_DUPLEX_RENAME	*pParam;

	pSCB->response.ret.sValue = 0;
	pSCB->response.dataLength = 0;

	pParam = (struct PRM_DUPLEX_RENAME *)pSCB->command.param;

	CloseDuplexFile(pParam->oldFileName);
	CloseDuplexFile(pParam->newFileName);

	if (MoveFile(pParam->oldFileName, pParam->newFileName))
		pSCB->response.status = 0;
	else {
		pSCB->response.status = (SHORT)GetLastError();
		if (pSCB->command.id == ID_COMMAND_ASYNC) {
			SendDuplexError(pParam->newFileName,
						pSCB->response.status);
		}
	}
}

static	PDUPLEXFILE	OpenDuplexFile(CHAR *fileName)
{
	PDUPLEXFILE	pDuplexFile = NULL, *ppDuplexFile;
	PDUPLEXFILE	*ppDuplexFile2 = NULL;
	int	count;

	ENTER_CRITICAL_SECTION(&csDuplexServer);
	for (ppDuplexFile = &pTopDuplexFile, count = 0; *ppDuplexFile != 0;
			ppDuplexFile = &(*ppDuplexFile)->chain, count++) {
		if (strcmp((*ppDuplexFile)->fileName, fileName) == 0) {
			pDuplexFile = *ppDuplexFile;
			*ppDuplexFile = (*ppDuplexFile)->chain;
			pDuplexFile->chain = pTopDuplexFile;
			pTopDuplexFile = pDuplexFile;
			break;
		}
		ppDuplexFile2 = ppDuplexFile;
	}
	LEAVE_CRITICAL_SECTION(&csDuplexServer);

	if (pDuplexFile == NULL) {
		if (count >= MAX_OPENDUPLEXFILE && ppDuplexFile2 != NULL) {
			pDuplexFile = *ppDuplexFile2;
			CloseHandle(pDuplexFile->handle);
			*ppDuplexFile2 = NULL;
			free(pDuplexFile);
		}

		if ((pDuplexFile = (PDUPLEXFILE)malloc(sizeof(DUPLEXFILE)))
								!= NULL) {
			memset(pDuplexFile, 0, sizeof(DUPLEXFILE));
			if ((pDuplexFile->handle = CreateFile(fileName,
					GENERIC_READ | GENERIC_WRITE,
					FILE_SHARE_READ, NULL, OPEN_EXISTING,
					FILE_ATTRIBUTE_NORMAL, NULL)) !=
					INVALID_HANDLE_VALUE) {
				strcpy(pDuplexFile->fileName, fileName);
				ENTER_CRITICAL_SECTION(&csDuplexServer);
				pDuplexFile->chain = pTopDuplexFile;
				pTopDuplexFile = pDuplexFile;
				LEAVE_CRITICAL_SECTION(&csDuplexServer);
			} else {
				free(pDuplexFile);
				pDuplexFile = NULL;
			}
		}
	}

	if (pDuplexFile != NULL)
		time(&pDuplexFile->accessTime);

	return pDuplexFile;
}

static	VOID	CloseDuplexFile(CHAR *fileName)
{
	PDUPLEXFILE	pDuplexFile, *ppDuplexFile;

	ENTER_CRITICAL_SECTION(&csDuplexServer);
	for (ppDuplexFile = &pTopDuplexFile; *ppDuplexFile != 0;
				ppDuplexFile = &(*ppDuplexFile)->chain) {
		pDuplexFile = *ppDuplexFile;
		if (strcmp(fileName, pDuplexFile->fileName) == 0) {
			*ppDuplexFile = (*ppDuplexFile)->chain;
			CloseHandle(pDuplexFile->handle);
			free(pDuplexFile);
			break;
		}
	}
	LEAVE_CRITICAL_SECTION(&csDuplexServer);
}

static	VOID	CloseDuplexFileAll(VOID)
{
	PDUPLEXFILE	pDuplexFile;

	ENTER_CRITICAL_SECTION(&csDuplexServer);
	while (pTopDuplexFile != NULL) {
		pDuplexFile = pTopDuplexFile;
		pTopDuplexFile = pTopDuplexFile->chain;

		CloseHandle(pDuplexFile->handle);
		free(pDuplexFile);
	}
	LEAVE_CRITICAL_SECTION(&csDuplexServer);
}

static	VOID	SendDuplexError(CHAR *fileName, int errorCode)
{
	ConxxxtDuplexServer();
	if (duplexServer.ccb.bConxxxt) {
		PSDuplexError(&duplexServer.ccb, ID_COMMAND_ASYNC, fileName, errorCode);

		DisconxxxtDuplexServer();
	}
}

BOOL	StartSyncDuplexFile(CHAR *fileName)
{
	static	char	sFileName[FILENAME_MAX];

	if (!duplexServer.ccb.bConxxxt)
		return FALSE;

	if (bSyncDuplexFile)
		return FALSE;

	strcpy(sFileName, fileName);
	if (_beginthread(ThreadSyncDuplexFile, 0, sFileName) == -1)
		return FALSE;

	return TRUE;
}

static	VOID	ThreadSyncDuplexFile(CHAR *fileName)
{
	bSyncDuplexFile = TRUE;

	if (strcmp(fileName, "&ERROR&") == 0)
		SyncErrorDuplexFile();
	else
		SearchSyncDuplexFile(fileName);

	bSyncDuplexFile = FALSE;
}

static	VOID	SyncErrorDuplexFile(VOID)
{
	PDB	dbp;
	DUPLEXERROR buf;
	CHAR	fileName[LEN_FILENAME + 1];

	if ((dbp = OpenDuplexErrorDBF()) != NULL) {
		while (DBRead(dbp, (CHAR *)&buf) == 0) {
			CopyFromDBF(fileName, buf.fileName,
						sizeof(buf.fileName));
			if (SyncDuplexFile(fileName))
				DBDelete3(dbp);
			else
				DBSkip(dbp, 1);
		}
		DBReindex(dbp);
		DBClose(dbp);
	}
}

static	VOID	SearchSyncDuplexFile(CHAR *fileName)
{
	HANDLE	hDir;
	WIN32_FIND_DATA	findBuf;
	CHAR	pathName[256], *p;

	hDir = FindFirstFile(fileName, &findBuf);
	if (hDir != INVALID_HANDLE_VALUE) {
		if ((p = strrchr(fileName, '\\')) != NULL)
			*p = '\0';

		do {
			if (findBuf.dwFileAttributes &
						FILE_ATTRIBUTE_DIRECTORY) {
				if (strcmp(findBuf.cFileName, ".") != 0 &&
						strcmp(findBuf.cFileName, "..")
									!= 0) {
					sprintf(pathName, "%s\\%s\\*",
							fileName,
							findBuf.cFileName);
					SearchSyncDuplexFile(pathName);
				}
			} else {
				sprintf(pathName, "%s\\%s", fileName,
							findBuf.cFileName);
				SyncDuplexFile(pathName);
			}
		} while (FindNextFile(hDir, &findBuf));

		if (p != NULL)
			*p = '\\';

		FindClose(hDir);
	}
}

#define	BUFSIZE	32768
static	BOOL	SyncDuplexFile(CHAR *fileName)
{
	HANDLE	hFile;
	CHAR	*pBuf;
	DWORD	numBytes, offset;
	int	err;
	FILETIME ftCreation;
	FILETIME ftLastAccess;
	FILETIME ftLastWrite;

	if ((pBuf = malloc(BUFSIZE)) == NULL)
		return FALSE;

	hFile = CreateFile(fileName, GENERIC_READ,
			FILE_SHARE_READ | FILE_SHARE_WRITE, NULL,
			OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
	if (hFile == INVALID_HANDLE_VALUE) {
		free(pBuf);
		return FALSE;
	}

	if ((err = CreateDuplexFile(ID_COMMAND, fileName)) != 0) {
		DuplexError(fileName, err);
		free(pBuf);
		CloseHandle(hFile);
		return FALSE;
	}

	offset = 0;
	for (;;) {
		if (!ReadFile(hFile, pBuf, BUFSIZE, &numBytes, NULL))
			break;

		if (numBytes == 0)
			break;

		if ((err = WriteDuplexFile(ID_COMMAND, fileName, pBuf,
						numBytes, offset)) != 0) {
			DuplexError(fileName, err);
			break;
		}

		offset += numBytes;
	}

	if (GetFileTime(hFile, &ftCreation, &ftLastAccess, &ftLastWrite)) {
		PSDuplexFileTime(&duplexServer.ccb, ID_COMMAND, fileName,
				&ftCreation, &ftLastAccess, &ftLastWrite);
	}

	CloseHandle(hFile);
	free(pBuf);

	return TRUE;
}

int	GetDuplexStatus(VOID)
{
	int	status;

	if (duplexServer.mode == DUPLEX_MASTER) {
		if (duplexServer.ccb.bConxxxt) {
			if (bSyncDuplexFile)
				status = DUPLEX_STATUS_SYNC;
			else
				status = DUPLEX_STATUS_NORMAL;
		} else {
				status = DUPLEX_STATUS_UNLINK;
		}
	} else
		status = DUPLEX_STATUS_NODUAL;

	return status;
}

VOID	GetDuplexInfo(PDUPLEXINFO pDuplexInfo)
{
	pDuplexInfo->duplexMode = duplexServer.mode;
	strcpy(pDuplexInfo->serverName, duplexServer.serverName);
	if (duplexServer.autoRepairTime != -1) {
		sprintf(pDuplexInfo->autoRepairTime, "%02d%02d",
				duplexServer.autoRepairTime / 60,
				duplexServer.autoRepairTime % 60);
	} else
		pDuplexInfo->autoRepairTime[0] = '\0';
}

VOID	SetDuplexInfo(PDUPLEXINFO pDuplexInfo)
{
	static	CHAR	iniFile[] = ".\\PCSERVE.INI";
	char	buf[10];

	ini.duplexMode = pDuplexInfo->duplexMode;
	sprintf(buf, "%d", ini.duplexMode);
	WritePrivateProfileString("DUPLEX", "Mode", buf, iniFile);

	strcpy(ini.duplexServer, pDuplexInfo->serverName);
	WritePrivateProfileString("DUPLEX", "Server", ini.duplexServer,
				iniFile);

	strcpy(ini.duplexAutoRepairTime, pDuplexInfo->autoRepairTime);
	WritePrivateProfileString("DUPLEX", "AutoRepairTime",
				ini.duplexAutoRepairTime, iniFile);
	if (ini.duplexAutoRepairTime[0] != '\0') {
		duplexServer.autoRepairTime =
				natoi(ini.duplexAutoRepairTime, 2) * 60 +
				natoi(ini.duplexAutoRepairTime + 2, 2);
	} else
		duplexServer.autoRepairTime = -1;
}

static	VOID	AutoRepair(VOID)
{
	SYSTEMTIME	st;

	if (duplexServer.autoRepairTime == -1)
		return;

	GetLocalTime(&st);

	if ((st.wHour * 60 + st.wMinute) == duplexServer.autoRepairTime) {
		if (!duplexServer.bAutoRepair) {
			if (StartSyncDuplexFile("&ERROR&"))
				duplexServer.bAutoRepair = TRUE;
		}
	} else
		duplexServer.bAutoRepair = FALSE;
}
