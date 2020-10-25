/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: 分散サーバ処理
 *		ファイル名	: multi.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <process.h>
#include "pcserve.h"

#define	COPYBUFSIZE	32768

CRITICALSECTION	csMultiServer;
static	BOOL	bSyncMultiFile;

static	PMFCB	OpenMultiServer(PSCB, CHAR *);
static	PMSCB	GetMSCB(PSCB, CHAR *);
static	BOOL	CheckMultiFile(CHAR *, PMULTIFILE);
static	int	GetMultiError(CHAR *, CHAR *);
static	BOOL	ConxxxtMultiServer(PMSCB);
static	int	MDBOpen1(PSCB, PFCB, BOOL);
static	VOID	MDBOpen2(PMFCB);
static	int	MDBCreate1(PSCB,PFCB,  BOOL);
static	VOID	MDBCreate2(PSCB, PFCB, PMFCB);
static	int	MRFOpen1(PSCB, PFCB, BOOL);
static	VOID	MRFOpen2(PSCB, PMFCB);
static	int	MRFCreate1(PSCB, PFCB, BOOL);
static	VOID	MRFCreate2(PSCB, PFCB, PMFCB);
static	VOID	MultiServerError(PFCB);
static	VOID	WriteErrorFile(CHAR *, CHAR *, SHORT);
static	VOID	ThreadSyncMultiFile(CHAR *);
static	VOID	SyncErrorMultiFile(CHAR *);
static	VOID	SearchSyncMultiFile(CHAR *);
static	VOID	SearchSyncMultiFile1(PCCB, CHAR *, CHAR *, CHAR *);
static	BOOL	SyncMultiFile(PCCB, CHAR *, CHAR *);
static	BOOL	NetCreateDirectory(PCCB, CHAR *);
static	VOID	MultiCopyInfo1(PMULTISERVER);

/*=======================================================================
 |
 |		分散サーバオープン
 |
 |	PMFCB	OpenMultiServer(pSCB, fileName)
 |
 |		PSCB	pSCB;		サーバ管理ブロック
 |		CHAR	*fileName;	自サーバファイル名
 |
 |		PMFCB	返値		分散ファイル制御ブロックポインタ
 |
 =======================================================================*/
static	PMFCB	OpenMultiServer(PSCB pSCB, CHAR *fileName)
{
	MULTIFILE	multiFileBuf;
	PMSCB	pMSCB;
	PMFCB	pMFCB;
	SHORT	err = 0;

	if (!ini.bMultiServer)
		return NULL;

	if (!CheckMultiFile(fileName, &multiFileBuf))
		return NULL;

	if ((pMFCB = malloc(sizeof(MFCB))) == NULL)
		return NULL;

	memset(pMFCB, 0, sizeof(MFCB));
	CopyFromDBF(pMFCB->fileName1, multiFileBuf.fileName1, sizeof(multiFileBuf.fileName1));
	CopyFromDBF(pMFCB->fileName2, multiFileBuf.fileName2, sizeof(multiFileBuf.fileName2));
	pMFCB->handle = -1;

	if ((pMSCB = GetMSCB(pSCB, multiFileBuf.serverName)) == NULL) {
		free(pMFCB);
		return NULL;
	}

	pMFCB->pMSCB = pMSCB;

	return pMFCB;
}

static	PMSCB	GetMSCB(PSCB pSCB, CHAR *serverNameBuf)
{
	PMSCB	pMSCB;
	SHORT	find;
	CHAR	serverName[LEN_SERVERNAME + 1];
	MULTISERVER	multiServerBuf;

	CopyFromDBF(serverName, serverNameBuf, LEN_SERVERNAME);
	for (pMSCB = pSCB->pMSCB; pMSCB != NULL; pMSCB = pMSCB->chain) {
		if (strcmp(pMSCB->serverName, serverName) == 0)
			break;
	}

	if (pMSCB == NULL) {
		if (DBReadKey(pMultiServerDB, serverNameBuf,
					LEN_SERVERNAME,
					(CHAR *)&multiServerBuf,
					0, &find) != 0)
			return NULL;

		if ((pMSCB = malloc(sizeof(MSCB))) == NULL)
			return NULL;

		memset(pMSCB, 0, sizeof(MSCB));
		strcpy(pMSCB->serverName, serverName);
		pMSCB->chType = multiServerBuf.chType[0] - '0';
		pMSCB->bMaster = (multiServerBuf.master[0] == '*');
		pMSCB->id = pMSCB->bMaster ?
				ID_COMMAND_MASTER : ID_COMMAND_SLAVE;
		pMSCB->lpData = pSCB->lpData;

		pMSCB->chain = pSCB->pMSCB;
		pSCB->pMSCB = pMSCB;
	}

	return pMSCB;
}

/*=======================================================================
 |
 |		分散ファイルチェック
 |
 |	BOOL	CheckMultiFile(fileName, pMultiFileBuf)
 |
 |		CHAR	*fileName;	自サーバファイル名
 |		PMULTIFILE	pMultiFileBuf;	分散ファイルバッファ
 |
 |		BOOL	返値		TRUE:分散ファイル
 |					FALSE:非分散ファイル
 |
 =======================================================================*/
static	BOOL	CheckMultiFile(CHAR *fileName, PMULTIFILE pMultiFileBuf)
{
	CHAR	buf[LEN_FILENAME + 1];
	SHORT	find;
	int	i;

	if ((i = strlen(fileName)) == 0)
		return FALSE;

	CopyToDBF(buf, fileName, LEN_FILENAME);

	for (;;) {
		if (DBReadKey(pMultiFileDB, buf, LEN_FILENAME,
				(CHAR *)pMultiFileBuf, 0, &find) == 0)
			break;

		for (;;) {
			if (i == 0)
				return FALSE;

			buf[--i] = ' ';

			if (fileName[i] == '\\')
				break;
		}
	}

	CopyToDBF(pMultiFileBuf->fileName1, fileName, LEN_FILENAME);
	CopyFromDBF(buf, pMultiFileBuf->fileName2, LEN_FILENAME);
	strcat(buf, fileName + i);
	CopyToDBF(pMultiFileBuf->fileName2, buf, LEN_FILENAME);

	return TRUE;
}

static	int	GetMultiError(CHAR *fileName, CHAR *serverName)
{
	CHAR	buf[LEN_SERVERNAME + LEN_FILENAME];
	MULTIERROR multiErrorBuf;
	SHORT	find;
	CHAR	errorCode[6];

	CopyToDBF(buf, serverName, LEN_SERVERNAME);
	CopyToDBF(buf + LEN_SERVERNAME, fileName, LEN_FILENAME);
	if (DBReadKey(pMultiErrorDB, buf, sizeof(buf),
				(CHAR *)&multiErrorBuf, 0, &find) != 0)
		return 0;

	if (find == 1) {
		CopyFromDBF(errorCode, multiErrorBuf.errorCode,
					sizeof(multiErrorBuf.errorCode));
		return atoi(errorCode);
	} else
		return 0;
}

VOID	CloseMultiServer(PSCB pSCB)
{
	PMSCB	pMSCB;

	while (pSCB->pMSCB != NULL) {
		pMSCB = pSCB->pMSCB;
		PSDisconxxxt(&pMSCB->ccb);
		pSCB->pMSCB = pMSCB->chain;
		free(pMSCB);
	}
}

static	BOOL	ConxxxtMultiServer(PMSCB pMSCB)
{
	if (pMSCB->ccb.bConxxxt)
		return TRUE;

	if (PSConxxxt(&pMSCB->ccb, pMSCB->chType, pMSCB->serverName, MULTI_SERVER_CLIENTID) != 0)
		return FALSE;

	return TRUE;
}

SHORT	MDBOpen(PSCB pSCB, PFCB pFCB, CHAR *fileName)
{
	CHAR	fileName2[256];
	CHAR	pathName[256];
	CHAR	*p;
	PMSCB	pMSCB;
	PMFCB	pMFCB;

	if (!ini.bMultiServer)
		return 0;

	if (pSCB->command.id == ID_COMMAND_MASTER)
		return 0;

	strcpy(fileName2, fileName);
	if ((p = strchr(fileName2, ',')) != NULL)
		*p++ = '\0';

	_fullpath(pathName, fileName2, sizeof(pathName));
	StringToUpper(pathName);

	if (pSCB->command.id == ID_COMMAND_SLAVE) {
		if (GetMultiError(pathName, pSCB->login.computerName) != 0)
			return ERROR_SERVER_MULTI;
		else
			return 0;
	}

	if ((pMFCB = OpenMultiServer(pSCB, pathName)) == NULL)
		return 0;

	pFCB->pMFCB = pMFCB;
	pMSCB = pMFCB->pMSCB;

	if (p != NULL) {
		strncpy(pMFCB->password, p, 8);
		pMFCB->password[8] = '\0';
	}

	if (pMSCB->bMaster) {
		if (GetMultiError(pathName, pMSCB->serverName) == 0) {
			pSCB->pMultiServerFunc = MDBOpen1;
			pSCB->pCurrentFCB = pFCB;
		} else {
			free(pMFCB);
			pFCB->pMFCB = NULL;
		}

		return 0;
	} else {
		MDBOpen2(pMFCB);

		if (pMFCB->errorCode == ERROR_SERVER_MULTI) {
			pFCB->fileType = FILETYPE_FORWARD;
			return 0;
		} else
			return pMFCB->errorCode;
	}
}

static	int	MDBOpen1(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;

	pMFCB = pFCB->pMFCB;
	if (pMFCB->errorCode != 0)
		return 0;

	MDBOpen2(pMFCB);

	return 0;
}

static	VOID	MDBOpen2(PMFCB pMFCB)
{
	PMSCB	pMSCB;
	CHAR	fileName[FILENAME_MAX];
	SHORT	err;

	pMSCB = pMFCB->pMSCB;

	if (!ConxxxtMultiServer(pMSCB)) {
		pMFCB->errorCode = ERROR_SERVER_CONxxxT;
		pMFCB->bErrorPending = TRUE;
		return;
	}

	strcpy(fileName, pMFCB->fileName2);
	if (pMFCB->password[0] != 0) {
		strcat(fileName, ",");
		strcat(fileName, pMFCB->password);
	}

	err = PSDBOpen(&pMSCB->ccb, pMSCB->id, fileName);
	if (err != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	pMFCB->handle = pMSCB->ccb.response.ret.sValue;
}

SHORT	MDBCreate(PSCB pSCB, PFCB pFCB, CHAR *fileName)
{
	CHAR	fileName2[256];
	CHAR	pathName[256];
	CHAR	*p;
	PMSCB	pMSCB;
	PMFCB	pMFCB;
	DBINF	dbinf;
	USHORT	dataLength;

	if (!ini.bMultiServer)
		return 0;

	if (pSCB->command.id == ID_COMMAND_MASTER ||
				pSCB->command.id == ID_COMMAND_SLAVE)
		return 0;

	strcpy(fileName2, fileName);
	if ((p = strchr(fileName2, ',')) != NULL)
		*p++ = '\0';

	_fullpath(pathName, fileName2, sizeof(pathName));
	StringToUpper(pathName);

	if ((pMFCB = OpenMultiServer(pSCB, pathName)) == NULL)
		return 0;

	pFCB->pMFCB = pMFCB;
	pMSCB = pMFCB->pMSCB;

	if (p != NULL) {
		strncpy(pMFCB->password, p, 8);
		pMFCB->password[8] = '\0';
	}

	if (pMSCB->bMaster) {
		pSCB->pMultiServerFunc = MDBCreate1;
		pSCB->pCurrentFCB = pFCB;
		return 0;
	} else {
		dbinf = *(PDBINF)pSCB->lpData;
		dataLength = pSCB->command.dataLength;

		MDBCreate2(pSCB, pFCB, pMFCB);
		if (pMFCB->errorCode == 0) {
			*(PDBINF)pSCB->lpData = dbinf;
			pSCB->command.dataLength = dataLength;
		}

		return pMFCB->errorCode;
	}
}

static	int	MDBCreate1(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;

	pMFCB = pFCB->pMFCB;
	if (pMFCB->errorCode != 0)
		return 0;

	MDBCreate2(pSCB, pFCB, pMFCB);

	return 0;
}

static	VOID	MDBCreate2(PSCB pSCB, PFCB pFCB, PMFCB pMFCB)
{
	struct	PRM_DB_CREATE	*pParam;
	PMSCB	pMSCB;
	CHAR	fileName[FILENAME_MAX];
	SHORT	err;

	pMSCB = pMFCB->pMSCB;

	if (!ConxxxtMultiServer(pMSCB)) {
		pMFCB->errorCode = ERROR_SERVER_CONxxxT;
		MultiServerError(pFCB);
		return;
	}

	strcpy(fileName, pMFCB->fileName2);
	if (pMFCB->password[0] != 0) {
		strcat(fileName, ",");
		CopyFromDBF(fileName + strlen(fileName), pMFCB->password, 8);
	}

	pParam = (struct PRM_DB_CREATE *)&pSCB->command.param;

	err = PSDBCreate(&pMSCB->ccb, pMSCB->id, fileName,
				(PDBF_I)pSCB->lpData, pParam->nField);
	if (err == 0) {
		pMFCB->handle = pMSCB->ccb.response.ret.sValue;
	} else {
		pMFCB->errorCode = err;
	}

	MultiServerError(pFCB);
}

int	MDBClose(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMSCB	pMSCB;
	PMFCB	pMFCB;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		pSCB->pMultiServerFunc = MDBClose;
		pSCB->pCurrentFCB = pFCB;
		return 0;
	}

	PSDBClose(&pMSCB->ccb, pMSCB->id, pMFCB->handle);

	free(pMFCB);
	pFCB->pMFCB = NULL;

	return 0;
}

int	MDBFlush(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBFlush;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	err = PSDBFlush(&pMSCB->ccb, pMSCB->id, pMFCB->handle);

	return err;
}

int	MDBIndex(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_INDEX	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	MULTIFILE	multiFileBuf;
	CHAR	fileName[FILENAME_MAX];
	CHAR	pathName[FILENAME_MAX];
	SHORT	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		pSCB->pMultiServerFunc = MDBIndex;
		pSCB->pCurrentFCB = pFCB;
		return 0;
	}

	pParam = (struct PRM_DB_INDEX *)pSCB->command.param;
	ConvertPathName(pParam->fileName, fileName);
	_fullpath(pathName, fileName, sizeof(pathName));
	StringToUpper(pathName);

	if (CheckMultiFile(pathName, &multiFileBuf)) {
		CopyFromDBF(fileName, multiFileBuf.fileName2,
							LEN_FILENAME);
		if ((err = PSDBIndex(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle, fileName)) != 0) {
			pMFCB->errorCode = err;
			pMFCB->bErrorPending = TRUE;
		}
	} else
		err = 0;

	return err;
}

int	MDBIdxCreate(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_IDX_CREATE	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	MULTIFILE	multiFileBuf;
	CHAR	fileName[FILENAME_MAX];
	CHAR	pathName[FILENAME_MAX];
	SHORT	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		pSCB->pMultiServerFunc = MDBIdxCreate;
		pSCB->pCurrentFCB = pFCB;
		return 0;
	}

	pParam = (struct PRM_DB_IDX_CREATE *)pSCB->command.param;
	ConvertPathName(pParam->fileName, fileName);
	_fullpath(pathName, fileName, sizeof(pathName));
	StringToUpper(pathName);

	if (CheckMultiFile(pathName, &multiFileBuf)) {
		CopyFromDBF(fileName, multiFileBuf.fileName2, LEN_FILENAME);

		if ((err = PSDBIdxCreate(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle, fileName, pParam->key,
					pParam->uniq)) != 0) {
			pMFCB->errorCode = err;
			pMFCB->bErrorPending = TRUE;
		}
	} else
		err = 0;

	return err;
}

int	MDBChgIdx(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_CHGIDX	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	SHORT	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBChgIdx;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_DB_CHGIDX *)pSCB->command.param;

	if ((err = PSDBChgIdx(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
							pParam->n)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MDBSearchLock(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_SEARCHLOCK	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	SHORT	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBSearchLock;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_DB_SEARCHLOCK *)pSCB->command.param;

	if ((err = PSDBSearchLock(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
					pParam->key, pParam->len)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MDBStore(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBStore;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBStore(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
			pSCB->lpData, pSCB->command.dataLength)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBStoreUniq(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBStoreUniq;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBStoreUniq(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
			pSCB->lpData, pSCB->command.dataLength)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBUpdate(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag) {
		pMFCB->recNo = pFCB->dbp->rno;
		pMFCB->recCount = pFCB->dbp->dp->dhp->nRec;

		if (pMSCB->bMaster) {
			if (pMFCB->errorCode == 0) {
				pSCB->pMultiServerFunc = MDBUpdate;
				pSCB->pCurrentFCB = pFCB;
			}
			return 0;
		}
	}

	if ((err = PSDBUpdateEx(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
					pSCB->lpData, pSCB->command.dataLength,
					pMFCB->recNo, pMFCB->recCount)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBUpdateKey(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_UPDATEKEY	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBUpdateKey;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_DB_UPDATEKEY *)pSCB->command.param;

	if ((err = PSDBUpdateKey(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle, pParam->key,
					pParam->len, pSCB->lpData,
					pSCB->command.dataLength)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBDelete(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag) {
		pMFCB->recNo = pFCB->dbp->rno;
		pMFCB->recCount = pFCB->dbp->dp->dhp->nRec;

		if (pMSCB->bMaster) {
			if (pMFCB->errorCode == 0) {
				pSCB->pMultiServerFunc = MDBDelete;
				pSCB->pCurrentFCB = pFCB;
			}
			return 0;
		}
	}

	if ((err = PSDBDeleteEx(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
				pMFCB->recNo, pMFCB->recCount)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBRecall(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag) {
		pMFCB->recNo = pFCB->dbp->rno;
		pMFCB->recCount = pFCB->dbp->dp->dhp->nRec;

		if (pMSCB->bMaster) {
			if (pMFCB->errorCode == 0) {
				pSCB->pMultiServerFunc = MDBRecall;
				pSCB->pCurrentFCB = pFCB;
			}
			return 0;
		}
	}

	if ((err = PSDBRecallEx(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
				pMFCB->recNo, pMFCB->recCount)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBDelete2(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag) {
		pMFCB->recNo = pFCB->dbp->rno;
		pMFCB->recCount = pFCB->dbp->dp->dhp->nRec;

		if (pMSCB->bMaster) {
			if (pMFCB->errorCode == 0) {
				pSCB->pMultiServerFunc = MDBDelete2;
				pSCB->pCurrentFCB = pFCB;
			}
			return 0;
		}
	}

	if ((err = PSDBDelete2Ex(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
				pMFCB->recNo, pMFCB->recCount)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBDelete3(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag) {
		pMFCB->recNo = pFCB->dbp->rno;
		pMFCB->recCount = pFCB->dbp->dp->dhp->nRec;

		if (pMSCB->bMaster) {
			if (pMFCB->errorCode == 0) {
				pSCB->pMultiServerFunc = MDBDelete3;
				pSCB->pCurrentFCB = pFCB;
			}
			return 0;
		}
	}

	if ((err = PSDBDelete2Ex(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
				pMFCB->recNo, pMFCB->recCount)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBDeleteKey(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_DELETEKEY	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBDeleteKey;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_DB_DELETEKEY *)pSCB->command.param;

	if ((err = PSDBDeleteKey(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
			pParam->key, pParam->len, pParam->flag)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBReindex(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBReindex;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBReindex(&pMSCB->ccb, pMSCB->id, pMFCB->handle)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MDBPack(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBPack;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBPack(&pMSCB->ccb, pMSCB->id, pMFCB->handle)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBReadKey(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_READKEY	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	pParam = (struct PRM_DB_READKEY *)pSCB->command.param;
	if (pParam->lock == 0)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBReadKey;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBReadKey(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
				pParam->key, pParam->len, pSCB->lpData,
				pParam->lock)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MDBAddRecord(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBAddRecord;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBStore(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
					pFCB->dbp->rbp + 1,
					pFCB->dbp->dp->dhp->lRec - 1)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBUpdRecord(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag) {
		pMFCB->recNo = pFCB->dbp->rno;
		pMFCB->recCount = pFCB->dbp->dp->dhp->nRec;

		if (pMSCB->bMaster) {
			if (pMFCB->errorCode == 0) {
				pSCB->pMultiServerFunc = MDBUpdRecord;
				pSCB->pCurrentFCB = pFCB;
			}
			return 0;
		}
	}

	if ((err = PSDBUpdateEx(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
					pFCB->dbp->rbp + 1,
					pFCB->dbp->dp->dhp->lRec - 1,
					pMFCB->recNo, pMFCB->recCount)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBSetFilter(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBSetFilter;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBSetFilter(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle, pSCB->lpData)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MDBSetDeleted(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_SET_DELETED	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBSetDeleted;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_DB_SET_DELETED *)pSCB->command.param;

	if ((err = PSDBSetDeleted(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle, pParam->flag)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MDBZip(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBZip;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBZip(&pMSCB->ccb, pMSCB->id, pMFCB->handle)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBLock(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_LOCK	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag) {
		pMFCB->recNo = pFCB->dbp->rno;
		pMFCB->recCount = pFCB->dbp->dp->dhp->nRec;

		if (pMSCB->bMaster) {
			if (pMFCB->errorCode == 0) {
				pSCB->pMultiServerFunc = MDBLock;
				pSCB->pCurrentFCB = pFCB;
			}
			return 0;
		}
	}

	pParam = (struct PRM_DB_LOCK *)pSCB->command.param;

	if ((err = PSDBLockEx(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
			pMFCB->recNo, pMFCB->recCount, pParam->lock)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MDBUnlock(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_UNLOCK	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBUnlock;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_DB_UNLOCK *)pSCB->command.param;

	if ((err = PSDBUnlock(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle, pParam->lock)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MDBSetScramble(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBSetScramble;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	if ((err = PSDBSetScramble(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBSetPassword(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_SET_PASSWORD	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MDBSetPassword;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_DB_SET_PASSWORD *)pSCB->command.param;

	if ((err = PSDBSetPassword(&pMSCB->ccb, pMSCB->id,
				pMFCB->handle, pParam->password)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MDBPackIndex(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMSCB	pMSCB;
	struct	PRM_DB_PACKINDEX	*pParam;
	CHAR	fileName[256];
	CHAR	pathName[256];
	MULTIFILE	multiFileBuf;
	int	err = 0;

	if (!ini.bMultiServer)
		return 0;

	if (pSCB->command.id == ID_COMMAND_MASTER ||
				pSCB->command.id == ID_COMMAND_SLAVE)
		return 0;

	if (!flag) {
		pSCB->pMultiServerFunc = MDBPackIndex;
		pSCB->pCurrentFCB = pFCB;
		return 0;
	}

	pParam = (struct PRM_DB_PACKINDEX *)pSCB->command.param;
	if (ConvertPathName(pParam->fileName, fileName) == 0) {
		_fullpath(pathName, fileName, sizeof(pathName));
		StringToUpper(pathName);
		if (CheckMultiFile(pathName, &multiFileBuf)) {
			if ((pMSCB = GetMSCB(pSCB, multiFileBuf.serverName))
								!= NULL) {
				if (ConxxxtMultiServer(pMSCB)) {
					CopyFromDBF(fileName,
						multiFileBuf.fileName2,
					    sizeof(multiFileBuf.fileName2));
					err = PSDBPackIndex(&pMSCB->ccb,
							pMSCB->id, fileName);
				}
			}
		}
	}

	return err;
}

int	MDBWriteBinary(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_DB_WRITEBINARY	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	id;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	pMFCB->recNo = pFCB->dbp->rno;
	pMFCB->recCount = pFCB->dbp->dp->dhp->nRec;

	pParam = (struct PRM_DB_WRITEBINARY *)pSCB->command.param;

	id = pMSCB->id;
	if (pSCB->bContinue)
		id |= ID_CONTINUE;

	if ((err = PSDBWriteBinaryEx(&pMSCB->ccb, id, pMFCB->handle,
				pParam->field, pSCB->lpData, pParam->len,
				pSCB->command.dataLength,
				pMFCB->recNo, pMFCB->recCount)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

SHORT	MRFOpen(PSCB pSCB, PFCB pFCB, CHAR *fileName)
{
	CHAR	pathName[256];
	PMSCB	pMSCB;
	PMFCB	pMFCB;

	if (!ini.bMultiServer)
		return 0;

	if (pSCB->command.id == ID_COMMAND_MASTER)
		return 0;

	_fullpath(pathName, fileName, sizeof(pathName));
	StringToUpper(pathName);

	if (pSCB->command.id == ID_COMMAND_SLAVE) {
		if (GetMultiError(pathName, pSCB->login.computerName) != 0)
			return ERROR_SERVER_MULTI;
		else
			return 0;
	}

	if ((pMFCB = OpenMultiServer(pSCB, pathName)) == NULL)
		return 0;

	pFCB->pMFCB = pMFCB;
	pMSCB = pMFCB->pMSCB;

	if (pMSCB->bMaster) {
		if (GetMultiError(pathName, pMSCB->serverName) == 0) {
			pSCB->pMultiServerFunc = MRFOpen1;
			pSCB->pCurrentFCB = pFCB;
		} else {
			free(pMFCB);
			pFCB->pMFCB = NULL;
		}

		return 0;
	} else {
		MRFOpen2(pSCB, pMFCB);

		if (pMFCB->errorCode == ERROR_SERVER_MULTI) {
			pFCB->fileType = FILETYPE_FORWARD;
			return 0;
		} else
			return pMFCB->errorCode;
	}
}

static	int	MRFOpen1(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;

	pMFCB = pFCB->pMFCB;
	if (pMFCB->errorCode != 0)
		return 0;

	MRFOpen2(pSCB, pMFCB);

	return 0;
}

static	VOID	MRFOpen2(PSCB pSCB, PMFCB pMFCB)
{
	struct	PRM_RF_OPEN	*pParam;
	PMSCB	pMSCB;
	SHORT	err;

	pMSCB = pMFCB->pMSCB;

	if (!ConxxxtMultiServer(pMSCB)) {
		pMFCB->errorCode = ERROR_SERVER_CONxxxT;
		pMFCB->bErrorPending = TRUE;
		return;
	}

	pParam = (struct PRM_RF_OPEN *)&pSCB->command.param;
	err = PSRFOpen(&pMSCB->ccb, pMSCB->id, pMFCB->fileName2,
				pParam->accessMode, pParam->shareMode);
	if (err != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	pMFCB->handle = pMSCB->ccb.response.ret.sValue;
}

SHORT	MRFCreate(PSCB pSCB, PFCB pFCB, CHAR *fileName)
{
	CHAR	pathName[256];
	PMSCB	pMSCB;
	PMFCB	pMFCB;

	if (!ini.bMultiServer)
		return 0;

	if (pSCB->command.id == ID_COMMAND_MASTER ||
				pSCB->command.id == ID_COMMAND_SLAVE)
		return 0;

	_fullpath(pathName, fileName, sizeof(pathName));
	StringToUpper(pathName);

	if ((pMFCB = OpenMultiServer(pSCB, pathName)) == NULL)
		return 0;

	pFCB->pMFCB = pMFCB;
	pMSCB = pMFCB->pMSCB;

	if (pMSCB->bMaster) {
		pSCB->pMultiServerFunc = MRFCreate1;
		pSCB->pCurrentFCB = pFCB;
		return 0;
	} else {
		MRFCreate2(pSCB, pFCB, pMFCB);
		return pMFCB->errorCode;
	}
}

static	int	MRFCreate1(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMFCB	pMFCB;

	pMFCB = pFCB->pMFCB;
	if (pMFCB->errorCode != 0)
		return 0;

	MRFCreate2(pSCB, pFCB, pMFCB);

	return 0;
}

static	VOID	MRFCreate2(PSCB pSCB, PFCB pFCB, PMFCB pMFCB)
{
	struct	PRM_RF_CREATE	*pParam;
	PMSCB	pMSCB;
	SHORT	err;

	pMSCB = pMFCB->pMSCB;

	if (!ConxxxtMultiServer(pMSCB)) {
		pMFCB->errorCode = ERROR_SERVER_CONxxxT;
		MultiServerError(pFCB);
		return;
	}

	pParam = (struct PRM_RF_CREATE *)&pSCB->command.param;

	err = PSRFCreate(&pMSCB->ccb, pMSCB->id, pMFCB->fileName2,
				pParam->accessMode, pParam->shareMode);
	if (err == 0) {
		pMFCB->handle = pMSCB->ccb.response.ret.sValue;
	} else {
		pMFCB->errorCode = err;
	}

	MultiServerError(pFCB);
}

int	MRFClose(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMSCB	pMSCB;
	PMFCB	pMFCB;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		pSCB->pMultiServerFunc = MRFClose;
		pSCB->pCurrentFCB = pFCB;
		return 0;
	}

	PSRFClose(&pMSCB->ccb, pMSCB->id, pMFCB->handle);

	free(pMFCB);
	pFCB->pMFCB = NULL;

	return 0;
}

int	MRFWrite(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_RF_WRITE	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag) {
		pMFCB->offset = SetFilePointer((HANDLE)pFCB->fileHandle,
					0, NULL, FILE_CURRENT);
		if (pMSCB->bMaster) {
			if (pMFCB->errorCode == 0) {
				pSCB->pMultiServerFunc = MRFWrite;
				pSCB->pCurrentFCB = pFCB;
			}
			return 0;
		}
	}

	pParam = (struct PRM_RF_WRITE *)pSCB->command.param;

	if ((err = PSRFWriteEx(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
			pSCB->lpData, pMFCB->offset, pParam->length)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MRFLock(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_RF_LOCK	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MRFLock;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_RF_LOCK *)pSCB->command.param;

	if ((err = PSRFLock(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle, pParam->offset,
					pParam->range)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MRFUnlock(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_RF_UNLOCK	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MRFUnlock;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_RF_UNLOCK *)pSCB->command.param;

	if ((err = PSRFUnlock(&pMSCB->ccb, pMSCB->id,
					pMFCB->handle, pParam->offset,
					pParam->range)) != 0) {
		pMFCB->errorCode = err;
		pMFCB->bErrorPending = TRUE;
	}

	return err;
}

int	MRFNewSize(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	struct	PRM_RF_NEWSIZE	*pParam;
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	int	err;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return 0;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return 0;

	if (pMFCB->bErrorPending) {
		MultiServerError(pFCB);
		pMFCB->bErrorPending = FALSE;
	}

	if (!flag && pMSCB->bMaster) {
		if (pMFCB->errorCode == 0) {
			pSCB->pMultiServerFunc = MRFNewSize;
			pSCB->pCurrentFCB = pFCB;
		}
		return 0;
	}

	pParam = (struct PRM_RF_NEWSIZE *)pSCB->command.param;

	if ((err = PSRFNewSize(&pMSCB->ccb, pMSCB->id, pMFCB->handle,
						pParam->fileSize)) != 0) {
		pMFCB->errorCode = err;
		MultiServerError(pFCB);
	}

	return err;
}

int	MCFDelete(PSCB pSCB, PFCB pFCB, BOOL flag)
{
	PMSCB	pMSCB;
	struct	PRM_CF_DELETE	*pParam;
	CHAR	fileName[256];
	CHAR	pathName[256];
	MULTIFILE	multiFileBuf;
	int	err = 0;

	if (!ini.bMultiServer)
		return 0;

	if (pSCB->command.id == ID_COMMAND_MASTER ||
				pSCB->command.id == ID_COMMAND_SLAVE)
		return 0;

	if (!flag) {
		pSCB->pMultiServerFunc = MCFDelete;
		pSCB->pCurrentFCB = pFCB;
		return 0;
	}

	pParam = (struct PRM_CF_DELETE *)pSCB->command.param;
	if (ConvertPathName(pParam->fileName, fileName) == 0) {
		_fullpath(pathName, fileName, sizeof(pathName));
		StringToUpper(pathName);
		if (CheckMultiFile(pathName, &multiFileBuf)) {
			if ((pMSCB = GetMSCB(pSCB, multiFileBuf.serverName))
								!= NULL) {
				if (ConxxxtMultiServer(pMSCB)) {
					CopyFromDBF(fileName,
						multiFileBuf.fileName2,
					    sizeof(multiFileBuf.fileName2));
					err = PSCFDelete(&pMSCB->ccb,
							pMSCB->id, fileName);
				}
			}
		}
	}

	return err;
}

VOID	ForwardCommand(PSCB pSCB, PFCB pFCB)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	PCCB	pCCB;

	pMFCB = pFCB->pMFCB;
	pMSCB = pMFCB->pMSCB;
	pCCB = &pMSCB->ccb;

	for (;;) {
		pCCB->command.id = pMSCB->id;
		if (pSCB->bContinue)
			pCCB->command.id |= ID_CONTINUE;
		pCCB->command.seqNo = pSCB->command.seqNo;
		pCCB->command.code = pSCB->command.code;
		pCCB->command.paramLength = pSCB->command.paramLength;
		pCCB->command.dataLength = pSCB->command.dataLength;
		memcpy(pCCB->command.param, pSCB->command.param,
						pCCB->command.paramLength);

		pCCB->pInData = pSCB->lpData;
		pCCB->pOutData = pSCB->lpData;

		*(SHORT *)&pCCB->command.param = pMFCB->handle;

		if (!SendCommand(pCCB)) {
			pCCB->response.status = ERROR_SERVER_SEND;
			return;
		}

		if (pSCB->bContinue) {
			if (!ReceiveCommand(pSCB)) {
				pCCB->response.status = ERROR_SERVER_RECEIVE;
				return;
			}
		} else
			break;
	}

	for (;;) {
		if (!ReceiveResponse(pCCB)) {
			pCCB->response.status = ERROR_SERVER_RECEIVE;
			return;
		}

		pSCB->response = pCCB->response;

		if (pCCB->response.id & ID_CONTINUE)
			SendResponse(pSCB, TRUE);
		else {
//			SendResponse(pSCB, FALSE);
			break;
		}
	}
}

static	VOID	MultiServerError(PFCB pFCB)
{
	PMFCB	pMFCB;
	PMSCB	pMSCB;
	PDB	dbp;
	int	i;

	if ((pMFCB = pFCB->pMFCB) == NULL)
		return;

	if ((pMSCB = pMFCB->pMSCB) == NULL)
		return;

	if (!pMSCB->bMaster)
		return;

	if (pMFCB->errorCode == 0)
		return;

	ENTER_CRITICAL_SECTION(&csMultiServer);

	WriteErrorFile(pMFCB->fileName1, pMSCB->serverName, pMFCB->errorCode);

	if (pFCB->fileType == FILETYPE_DB) {
		dbp = pFCB->dbp;
		for (i = 1; i <= dbp->nIdx; i++) {
			WriteErrorFile(dbp->ip[i]->ifp->fileName,
					pMSCB->serverName, pMFCB->errorCode);
		}

		if (dbp->dp->bfp != NULL) {
			WriteErrorFile(dbp->dp->bfp->fileName,
					pMSCB->serverName, pMFCB->errorCode);
		}
	}

	LEAVE_CRITICAL_SECTION(&csMultiServer);

	if (ini.mailReportMultiServer) {
		SendReportMail("分割サーバエラー", "FileName=%s\r\nServerName=%s\r\nErrorCode=%d",
				pMFCB->fileName1, pMSCB->serverName,
				pMFCB->errorCode);
	}
}

static	VOID	WriteErrorFile(CHAR *fileName, CHAR *serverName,
							SHORT errorCode)
{
	MULTIERROR buf;
	CHAR	cbuf[16];
	SYSTEMTIME	st;

	CopyToDBF(buf.fileName, fileName, sizeof(buf.fileName));
	CopyToDBF(buf.serverName, serverName, sizeof (buf.serverName));
	sprintf(cbuf, "%5d", errorCode);
	CopyToDBF(buf.errorCode, cbuf, sizeof(buf.errorCode));
	GetLocalTime(&st);
	sprintf(cbuf, "%04d%02d%02d%02d%02d%02d",
			st.wYear, st.wMonth, st.wDay,
			st.wHour, st.wMinute, st.wSecond);
	CopyToDBF(buf.time, cbuf, sizeof(buf.time));

	DBStoreUniq(pMultiErrorDB, (CHAR *)&buf);
}

BOOL	StartSyncMultiFile(CHAR *fileName)
{
	static	CHAR	sFileName[FILENAME_MAX];

	if (!ini.bMultiServer)
		return FALSE;

	if (bSyncMultiFile)
		return FALSE;

	strcpy(sFileName, fileName);
	if (_beginthread(ThreadSyncMultiFile, 0, &sFileName) == -1)
		return FALSE;

	return TRUE;
}

static	VOID	ThreadSyncMultiFile(CHAR *fileName)
{
	bSyncMultiFile = TRUE;

	if (fileName[0] == '*')
		SyncErrorMultiFile(fileName + 1);
	else
		SearchSyncMultiFile(fileName);

	bSyncMultiFile = FALSE;
}

static	VOID	SyncErrorMultiFile(CHAR *serverName)
{
	MULTIFILE	multiFileBuf;
	MULTISERVER	multiServerBuf;
	MULTIERROR	multiErrorBuf;
	CHAR	keyBuf[LEN_SERVERNAME];
	CHAR	fileName[LEN_FILENAME + 1];
	CHAR	fileName2[LEN_FILENAME + 1];
	int	chType;
	SHORT	find;
	CCB	ccb;

	if (pMultiErrorDB == NULL)
		return;

	CopyToDBF(keyBuf, serverName, LEN_SERVERNAME);
	if (DBReadKey(pMultiServerDB, keyBuf, LEN_SERVERNAME,
				(CHAR *)&multiServerBuf, 0, &find) != 0)
		return;

	if (find == 0)
		return;

	chType = multiServerBuf.chType[0] - '0';
	memset(&ccb, 0, sizeof(ccb));
	if (PSConxxxt(&ccb, chType, serverName, NULL) != 0)
		return;

	ENTER_CRITICAL_SECTION(&csMultiServer);

	DBSearch2(pMultiErrorDB, keyBuf, LEN_SERVERNAME, &find);
	if (find == 1) {
		for (;;) {
			if (DBRead(pMultiErrorDB, (CHAR *)&multiErrorBuf) != 0)
				break;

			CopyFromDBF(fileName, multiErrorBuf.fileName,
					sizeof(multiErrorBuf.fileName));

			if (memcmp(keyBuf, multiErrorBuf.serverName,
						LEN_SERVERNAME) != 0)
				break;

			if (!CheckMultiFile(fileName, &multiFileBuf)) {
				DBDelete3(pMultiErrorDB);
				continue;
			}

			CopyFromDBF(fileName2, multiFileBuf.fileName2,
								LEN_FILENAME);
			if (SyncMultiFile(&ccb, fileName, fileName2))
				DBDelete3(pMultiErrorDB);
			else
				DBSkip(pMultiErrorDB, 1);
		}

		DBReindex(pMultiErrorDB);
	}

	LEAVE_CRITICAL_SECTION(&csMultiServer);

	PSDisconxxxt(&ccb);
}

static	VOID	SearchSyncMultiFile(CHAR *fileName)
{
	MULTIFILE	multiFileBuf;
	MULTISERVER	multiServerBuf;
	CHAR	serverName[LEN_SERVERNAME + 1];
	CHAR	fileName1[LEN_FILENAME + 1];
	CHAR	fileName2[LEN_FILENAME + 1];
	int	chType;
	SHORT	find;
	CCB	ccb;

	if (!CheckMultiFile(fileName, &multiFileBuf))
		return;

	if (DBReadKey(pMultiServerDB, multiFileBuf.serverName,
					LEN_SERVERNAME,
					(CHAR *)&multiServerBuf,
					0, &find) != 0)
		return;

	if (find == 0)
		return;

	CopyFromDBF(serverName, multiFileBuf.serverName, LEN_SERVERNAME);
	CopyFromDBF(fileName1, multiFileBuf.fileName1, LEN_FILENAME);
	CopyFromDBF(fileName2, multiFileBuf.fileName2, LEN_FILENAME);
	chType = multiServerBuf.chType[0] - '0';

	memset(&ccb, 0, sizeof(ccb));
	if (PSConxxxt(&ccb, chType, serverName, NULL) == 0) {
		SearchSyncMultiFile1(&ccb, fileName1, fileName2, serverName);
		PSDisconxxxt(&ccb);
	}
}

static	VOID	SearchSyncMultiFile1(PCCB pCCB, CHAR *fileName1,
					CHAR *fileName2, CHAR *serverName)
{
	DWORD	attr;
	HANDLE	hDir;
	WIN32_FIND_DATA	findBuf;
	CHAR	pathName1[256], pathName2[256];

	if ((attr = GetFileAttributes(fileName1)) != 0xFFFFFFFF) {
		if (attr & FILE_ATTRIBUTE_DIRECTORY) {
			sprintf(pathName1, "%s\\*", fileName1);
			hDir = FindFirstFile(pathName1, &findBuf);
			if (hDir != INVALID_HANDLE_VALUE) {
				do {
					if (strcmp(findBuf.cFileName, ".") == 0
							|| strcmp(findBuf.
							cFileName, "..") == 0)
						continue;

					sprintf(pathName1, "%s\\%s", fileName1,
							findBuf.cFileName);
					sprintf(pathName2, "%s\\%s", fileName2,
							findBuf.cFileName);
					SearchSyncMultiFile1(pCCB, pathName1,
							pathName2, serverName);
				} while (FindNextFile(hDir, &findBuf));

				FindClose(hDir);
			}
		} else {
			if (!SyncMultiFile(pCCB, fileName1, fileName2)) {
				WriteErrorFile(fileName1, serverName, -1);
			}
		}
	}
}

static	BOOL	SyncMultiFile(PCCB pCCB, CHAR *fileName1, CHAR *fileName2)
{
	HANDLE	hFile;
	CHAR	*pBuf;
	DWORD	numBytes;
	int	remoteHandle;
	BOOL	status;
	BOOL	bRetry;
	int	err;
 	FILETIME ftCreation;
	FILETIME ftLastAccess;
	FILETIME ftLastWrite;

	if ((pBuf = malloc(COPYBUFSIZE)) == NULL)
		return FALSE;

	hFile = CreateFile(fileName1, GENERIC_READ,
			FILE_SHARE_READ | FILE_SHARE_WRITE, NULL,
			OPEN_EXISTING, FILE_ATTRIBUTE_NORMAL, NULL);
	if (hFile == INVALID_HANDLE_VALUE) {
		free(pBuf);
		return FALSE;
	}

	bRetry = FALSE;

CreateFile:
	err = PSRFCreate(pCCB, ID_COMMAND_MASTER, fileName2,
			PSOPEN_ACCESS_WRITEONLY,PSOPEN_SHARE_DENYNONE);

	if (err == ERROR_PATH_NOT_FOUND && !bRetry) {
		if (!NetCreateDirectory(pCCB, fileName2)) {
			CloseHandle(hFile);
			free(pBuf);
			return FALSE;
		}

		bRetry = TRUE;
		goto CreateFile;
	} else if (err != 0) {
		CloseHandle(hFile);
		free(pBuf);
		return FALSE;
	}

	remoteHandle = pCCB->response.ret.sValue;

	for (;;) {
		if (!ReadFile(hFile, pBuf, COPYBUFSIZE, &numBytes, NULL)) {
			status = FALSE;
			break;
		}

		if (numBytes == 0) {
			status = TRUE;
			break;
		}

		if (PSRFWrite(pCCB, ID_COMMAND_MASTER, remoteHandle,
							pBuf, numBytes) != 0) {
			status = FALSE;
			break;
		}
	}

	if (GetFileTime(hFile, &ftCreation, &ftLastAccess, &ftLastWrite)) {
		PSRFFileTime(pCCB, ID_COMMAND_MASTER, remoteHandle,
				&ftCreation, &ftLastAccess, &ftLastWrite);
	}

	CloseHandle(hFile);
	free(pBuf);

	if (PSRFClose(pCCB, ID_COMMAND_MASTER, remoteHandle) != 0) {
		status = FALSE;
	}

	return status;
}

static	BOOL	NetCreateDirectory(PCCB pCCB, CHAR *pathName)
{
	CHAR	*p;
	SHORT	status;

	if ((p = strchr(pathName, '\\')) != NULL) {
		while ((p = strchr(p + 1, '\\')) != NULL) {
			*p = '\0';

			status = PSCFCreateDirectory(pCCB, ID_COMMAND_MASTER,
								pathName);
			if (status != 0 && status != ERROR_ALREADY_EXISTS) {
				*p = '\\';
				return FALSE;
			}

			*p = '\\';
		}
	}

	return TRUE;
}

BOOL	MultiCopyInfo(VOID)
{
	MULTISERVER	multiServerBuf;
	SHORT	nRec;

	ENTER_CRITICAL_SECTION(&csMultiServer);

	DBTop(pMultiServerDB);
	for (;;) {
		DBReadNext(pMultiServerDB, 1, (CHAR *)&multiServerBuf, &nRec);
		if (nRec == 0)
			break;

		MultiCopyInfo1(&multiServerBuf);
	}

	LEAVE_CRITICAL_SECTION(&csMultiServer);

	return TRUE;
}

static	VOID	MultiCopyInfo1(PMULTISERVER pMultiServer)
{
	CCB	ccb;
	int	chType;
	SHORT	find;
	SHORT	nRec;
	DWORD	nameSize;
	MULTIFILE multiFileBuf;
	CHAR	serverName[LEN_SERVERNAME + 1];
	CHAR	fileName[LEN_FILENAME];

	CopyFromDBF(serverName, pMultiServer->serverName, LEN_SERVERNAME);
	chType = pMultiServer->chType[0] - '0';

	memset(&ccb, 0, sizeof(ccb));
	if (PSConxxxt(&ccb, chType, serverName, NULL) != 0)
		return;

	nameSize = sizeof(pMultiServer->serverName);
	GetComputerName(pMultiServer->serverName, &nameSize);
	pMultiServer->master[0] = (pMultiServer->master[0] == '*') ? ' ' : '*';
	memset(pMultiServer->comment, ' ', sizeof(pMultiServer->comment));

	if (PSMultiCopyInfo(&ccb, ID_COMMAND, 0, pMultiServer,
						sizeof(MULTISERVER)) != 0) {
		PSDisconxxxt(&ccb);
		return;
	}

	DBSearch2(pMultiFileDB, pMultiServer->serverName, LEN_SERVERNAME,
									&find);
	if (find == 1) {
		for (;;) {
			DBReadNext(pMultiFileDB, 1, (CHAR *)&multiFileBuf,
									&nRec);
			if (nRec == 0)
				break;

			if (memcmp(multiFileBuf.serverName,
						pMultiServer->serverName,
						LEN_SERVERNAME) != 0)
			break;

			memcpy(multiFileBuf.serverName,
					pMultiServer->serverName,
					LEN_SERVERNAME);
			memcpy(fileName, multiFileBuf.fileName1, LEN_FILENAME);
			memcpy(multiFileBuf.fileName1, multiFileBuf.fileName2,
							LEN_FILENAME);
			memcpy(multiFileBuf.fileName2, fileName, LEN_FILENAME);

			if (PSMultiCopyInfo(&ccb, ID_COMMAND, 1, &multiFileBuf,
						sizeof(MULTIFILE)) != 0)
				break;
		}
	}

	PSDisconxxxt(&ccb);
}
