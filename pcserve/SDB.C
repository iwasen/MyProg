/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: サーバ側ＤＢ関数処理
 *		ファイル名	: sdb.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"

/*=======================================================================
 |
 |		PSDBOpen() 関数実行処理
 |
 |	VOID	ExecPSDBOpen(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBOpen(PSCB pSCB)
{
	struct	PRM_DB_OPEN	*pParam;
	PDB	dbp;
	PFCB	pFCB;
	int	nFCB;
	SHORT	status, status2;
	CHAR	pathName[256];
	SHORT	permission;

	nFCB = AllocFCB(pSCB);

	if (nFCB != -1) {
		pFCB = &pSCB->pFCB[nFCB];
		pParam = (struct PRM_DB_OPEN *)pSCB->command.param;
		pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';

		if ((status = ConvertPathName(pParam->fileName, pathName)) == 0) {
			if ((status = CheckPermission(pSCB, pathName, &permission)) == 0) {
				status = MDBOpen(pSCB, pFCB, pathName);
				if ((status == 0 || status == ERROR_SERVER_MULTI) && pFCB->fileType != FILETYPE_FORWARD) {
					status2 = status;
					if ((status = DBOpen(pathName, &dbp, permission)) == 0) {
						pFCB->fileType = FILETYPE_DB;
						pFCB->dbp = dbp;
						pFCB->DBInf.recSize = dbp->dp->lRec;
						pSCB->lpData2 = (CHAR *)&pFCB->DBInf;
						pSCB->login.openFile++;
						status = status2;
					}
				}
			}
		}
	} else
		status = ERROR_SERVER_MAXFILES;

	pSCB->response.dataLength = sizeof(DBINF);
	pSCB->response.ret.sValue = nFCB;
	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSDBCreate() 関数実行処理
 |
 |	VOID	ExecPSDBCreate(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBCreate(PSCB pSCB)
{
	struct	PRM_DB_CREATE	*pParam;
	PDB	dbp;
	PFCB	pFCB;
	int	nFCB;
	SHORT	status, status2;
	CHAR	pathName[256];
	SHORT	permission;

	nFCB = AllocFCB(pSCB);

	if (nFCB != -1) {
		pFCB = &pSCB->pFCB[nFCB];
		pParam = (struct PRM_DB_CREATE *)pSCB->command.param;
		pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';

		if ((status = ConvertPathName(pParam->fileName, pathName)) == 0) {
			if ((status = CheckPermission(pSCB, pathName, &permission)) == 0) {
				status = MDBCreate(pSCB, pFCB, pathName);
				if ((status == 0 || status == ERROR_SERVER_MULTI) && pFCB->fileType != FILETYPE_FORWARD) {
					status2 = status;
					if ((status = DBCreate(pathName, (PDBF_I)pSCB->lpData, pParam->nField, &dbp, permission)) == 0) {
						pFCB->fileType = FILETYPE_DB;
						pFCB->dbp = dbp;
						pFCB->DBInf.recSize = dbp->dp->lRec;
						pSCB->lpData2 = (CHAR *)&pFCB->DBInf;
						pSCB->login.openFile++;
						status = status2;
					}
				}
			}
		}
	} else
		status = ERROR_SERVER_MAXFILES;

	pSCB->response.dataLength = sizeof(DBINF);
	pSCB->response.ret.sValue = nFCB;
	pSCB->response.status = status;
}

/*=======================================================================
 |
 |		PSDBClose() 関数実行処理
 |
 |	VOID	ExecPSDBClose(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBClose(PSCB pSCB)
{
	struct	PRM_DB_CLOSE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_CLOSE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			MDBClose(pSCB, pFCB, FALSE);

			status = DBClose(pFCB->dbp);
			pSCB->login.openFile--;
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
 |		PSDBFlush() 関数実行処理
 |
 |	VOID	ExecPSDBFlush(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBFlush(PSCB pSCB)
{
	struct	PRM_DB_FLUSH	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_FLUSH *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBFlush(pSCB, pFCB, FALSE)) == 0)
				status = DBFlush(pFCB->dbp);
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
 |		PSDBIndex() 関数実行処理
 |
 |	VOID	ExecPSDBIndex(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBIndex(PSCB pSCB)
{
	struct	PRM_DB_INDEX	*pParam;
	PFCB	pFCB;
	CHAR	pathName[FILENAME_MAX];
	SHORT	status;

	pParam = (struct PRM_DB_INDEX *)pSCB->command.param;
	pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';

	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		status = ConvertPathName(pParam->fileName, pathName);
		if (status == 0) {
			switch (pFCB->fileType) {
			case FILETYPE_DB:
				if ((status = MDBIndex(pSCB, pFCB,
							FALSE)) == 0) {
					status = DBIndex(pFCB->dbp, pathName,
						&pSCB->response.ret.sValue);
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
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSDBIdxCreate() 関数実行処理
 |
 |	VOID	ExecPSDBIdxCreate(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBIdxCreate(PSCB pSCB)
{
	struct	PRM_DB_IDX_CREATE	*pParam;
	PFCB	pFCB;
	CHAR	pathName[FILENAME_MAX];
	SHORT	status;

	pParam = (struct PRM_DB_IDX_CREATE *)pSCB->command.param;
	pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';
	pParam->key[sizeof(pParam->key) - 1] = '\0';

	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		status = ConvertPathName(pParam->fileName, pathName);
		if (status == 0) {
			switch (pFCB->fileType) {
			case FILETYPE_DB:
				if ((status = MDBIdxCreate(pSCB, pFCB, FALSE))
								== 0) {
					status = DBIdxCreate(pFCB->dbp,
						pathName, pParam->key,
						pParam->uniq,
						&pSCB->response.ret.sValue);
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
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSDBChgIdx() 関数実行処理
 |
 |	VOID	ExecPSDBChgIdx(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBChgIdx(PSCB pSCB)
{
	struct	PRM_DB_CHGIDX	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_CHGIDX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBChgIdx(pSCB, pFCB, FALSE)) == 0)
				status = DBChgIdx(pFCB->dbp, pParam->n);
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
 |		PSDBSearch() 関数実行処理
 |
 |	VOID	ExecPSDBSearch(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSearch(PSCB pSCB)
{
	struct	PRM_DB_SEARCH	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SEARCH *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBSearch(pFCB->dbp, pParam->key, pParam->len);
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
 |		PSDBSearch2() 関数実行処理
 |
 |	VOID	ExecPSDBSearch2(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSearch2(PSCB pSCB)
{
	struct	PRM_DB_SEARCH2	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SEARCH2 *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBSearch2(pFCB->dbp, pParam->key, pParam->len,
						&pSCB->response.ret.sValue);
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
 |		PSDBSearchLock() 関数実行処理
 |
 |	VOID	ExecPSDBSearchLock(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSearchLock(PSCB pSCB)
{
	struct	PRM_DB_SEARCHLOCK	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SEARCHLOCK *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBSearchLock(pSCB, pFCB, FALSE)) == 0) {
				status = DBSearchLock(pFCB->dbp,
						pParam->key, pParam->len,
						&pSCB->response.ret.lValue);
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
 |		PSDBCount() 関数実行処理
 |
 |	VOID	ExecPSDBCount(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBCount(PSCB pSCB)
{
	struct	PRM_DB_COUNT	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_COUNT *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBCount(pFCB->dbp, pParam->key, pParam->len,
						&pSCB->response.ret.lValue);
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
 |		PSDBStore() 関数実行処理
 |
 |	VOID	ExecPSDBStore(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBStore(PSCB pSCB)
{
	struct	PRM_DB_STORE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_STORE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBStore(pSCB, pFCB, FALSE)) == 0)
				status = DBStore(pFCB->dbp, pSCB->lpData);
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
 |		PSDBStoreUniq() 関数実行処理
 |
 |	VOID	ExecPSDBStoreUniq(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBStoreUniq(PSCB pSCB)
{
	struct	PRM_DB_STOREUNIQ	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_STOREUNIQ *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBStoreUniq(pSCB, pFCB, FALSE)) == 0)
				status = DBStoreUniq(pFCB->dbp, pSCB->lpData);
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
 |		PSDBUpdate() 関数実行処理
 |
 |	VOID	ExecPSDBUpdate(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBUpdate(PSCB pSCB)
{
	struct	PRM_DB_UPDATE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_UPDATE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBUpdate(pSCB, pFCB, FALSE)) == 0)
				status = DBUpdate(pFCB->dbp, pSCB->lpData);
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
 |		PSDBUpdateKey() 関数実行処理
 |
 |	VOID	ExecPSDBUpdateKey(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBUpdateKey(PSCB pSCB)
{
	struct	PRM_DB_UPDATEKEY	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_UPDATEKEY *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBUpdateKey(pSCB, pFCB, FALSE)) == 0) {
				status = DBUpdateKey(pFCB->dbp, pParam->key,
						pParam->len, pSCB->lpData);
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
 |		PSDBDelete() 関数実行処理
 |
 |	VOID	ExecPSDBDelete(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBDelete(PSCB pSCB)
{
	struct	PRM_DB_DELETE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_DELETE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBDelete(pSCB, pFCB, FALSE)) == 0)
				status = DBDelete(pFCB->dbp);
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
 |		PSDBRecall() 関数実行処理
 |
 |	VOID	ExecPSDBRecall(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBRecall(PSCB pSCB)
{
	struct	PRM_DB_RECALL	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_RECALL *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBRecall(pSCB, pFCB, FALSE)) == 0)
				status = DBRecall(pFCB->dbp);
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
 |		PSDBDelete2() 関数実行処理
 |
 |	VOID	ExecPSDBDelete2(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBDelete2(PSCB pSCB)
{
	struct	PRM_DB_DELETE2	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_DELETE2 *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBDelete2(pSCB, pFCB, FALSE)) == 0)
				status = DBDelete2(pFCB->dbp);
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
 |		PSDBDelete3() 関数実行処理
 |
 |	VOID	ExecPSDBDelete3(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBDelete3(PSCB pSCB)
{
	struct	PRM_DB_DELETE3	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_DELETE3 *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBDelete3(pSCB, pFCB, FALSE)) == 0)
				status = DBDelete3(pFCB->dbp);
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
 |		PSDBDeleteKey() 関数実行処理
 |
 |	VOID	ExecPSDBDeleteKey(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBDeleteKey(PSCB pSCB)
{
	struct	PRM_DB_DELETEKEY	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_DELETEKEY *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBDeleteKey(pSCB, pFCB, FALSE)) == 0) {
				status = DBDeleteKey(pFCB->dbp,
						pParam->key, pParam->len,
						pParam->flag);
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
 |		PSDBTop() 関数実行処理
 |
 |	VOID	ExecPSDBTop(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBTop(PSCB pSCB)
{
	struct	PRM_DB_TOP	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_TOP *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBTop(pFCB->dbp);
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
 |		PSDBBottom() 関数実行処理
 |
 |	VOID	ExecPSDBBottom(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBBottom(PSCB pSCB)
{
	struct	PRM_DB_BOTTOM	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_BOTTOM *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBBottom(pFCB->dbp);
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
 |		PSDBSet() 関数実行処理
 |
 |	VOID	ExecPSDBSet(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSet(PSCB pSCB)
{
	struct	PRM_DB_SET	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SET *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBSet(pFCB->dbp, pParam->recNo);
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
 |		PSDBSkip() 関数実行処理
 |
 |	VOID	ExecPSDBSkip(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSkip(PSCB pSCB)
{
	struct	PRM_DB_SKIP	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SKIP *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBSkip(pFCB->dbp, pParam->n);
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
 |		PSDBReindex() 関数実行処理
 |
 |	VOID	ExecPSDBReindex(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBReindex(PSCB pSCB)
{
	struct	PRM_DB_REINDEX	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_REINDEX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBReindex(pSCB, pFCB, FALSE)) == 0)
				status = DBReindex(pFCB->dbp);
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
 |		PSDBPack() 関数実行処理
 |
 |	VOID	ExecPSDBPack(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBPack(PSCB pSCB)
{
	struct	PRM_DB_PACK	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_PACK *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBPack(pSCB, pFCB, FALSE)) == 0)
				status = DBPack(pFCB->dbp);
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
 |		PSDBRead() 関数実行処理
 |
 |	VOID	ExecPSDBRead(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBRead(PSCB pSCB)
{
	struct	PRM_DB_READ	*pParam;
	PFCB	pFCB;
	SHORT	status;
	USHORT	dataLength = 0;

	pParam = (struct PRM_DB_READ *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBRead(pFCB->dbp, pSCB->lpData);
			if (status == 0)
				dataLength = pFCB->dbp->readSize;
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
}

/*=======================================================================
 |
 |		PSDBReadNext() 関数実行処理
 |
 |	VOID	ExecPSDBReadNext(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBReadNext(PSCB pSCB)
{
	struct	PRM_DB_READNEXT	*pParam;
	PFCB	pFCB;
	SHORT	status;
	USHORT	dataLength = 0;

	pParam = (struct PRM_DB_READNEXT *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBReadNext(pFCB->dbp, pParam->nRec,
					pSCB->lpData,
					&pSCB->response.ret.sValue);
			if (status == 0) {
				dataLength = pFCB->dbp->readSize *
						pSCB->response.ret.sValue;
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
	pSCB->response.dataLength = dataLength;
}

/*=======================================================================
 |
 |		PSDBReadBack() 関数実行処理
 |
 |	VOID	ExecPSDBReadBack(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBReadBack(PSCB pSCB)
{
	struct	PRM_DB_READBACK	*pParam;
	PFCB	pFCB;
	SHORT	status;
	USHORT	dataLength = 0;

	pParam = (struct PRM_DB_READBACK *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBReadBack(pFCB->dbp,
					pParam->nRec, pSCB->lpData,
					&pSCB->response.ret.sValue);
			if (status == 0) {
				dataLength = pFCB->dbp->readSize *
						pSCB->response.ret.sValue;
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
	pSCB->response.dataLength = dataLength;
}

/*=======================================================================
 |
 |		PSDBReadKey() 関数実行処理
 |
 |	VOID	ExecPSDBReadKey(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBReadKey(PSCB pSCB)
{
	struct	PRM_DB_READKEY	*pParam;
	PFCB	pFCB;
	SHORT	status;
	USHORT	dataLength = 0;

	pParam = (struct PRM_DB_READKEY *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			MDBReadKey(pSCB, pFCB, FALSE);
			status = DBReadKey(pFCB->dbp,
					pParam->key, pParam->len, pSCB->lpData,
					pParam->lock,
					&pSCB->response.ret.sValue);
			if (status == 0)
				dataLength = pFCB->dbp->readSize;
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
}

/*=======================================================================
 |
 |		PSDBReadBinary() 関数実行処理
 |
 |	VOID	ExecPSDBReadBinary(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBReadBinary(PSCB pSCB)
{
	static	SHORT	PSDBReadBinaryServer(PSCB, PFCB, CHAR *, LONG);
	struct	PRM_DB_READBINARY	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_READBINARY *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = PSDBReadBinaryServer(pSCB, pFCB,
						pParam->field, pParam->len);
			break;
		case FILETYPE_FORWARD:
			ForwardCommand(pSCB, pFCB);
			return;
		default:
			status = ERROR_FILE_TYPE;
			pSCB->response.dataLength = 0;
			break;
		}
	}

	pSCB->response.status = status;
}

static	SHORT	PSDBReadBinaryServer(PSCB pSCB, PFCB pFCB, CHAR *field, LONG len)
{
	PDB	dbp;
	LONG	readSize, readLen;
	SHORT	err;

	dbp = pFCB->dbp;

	ENTER_CRITICAL_SECTION(&dbp->dp->csDBF);

	readLen = min(16384L, len);
	err = DBReadBinary(dbp, field, pSCB->lpData, readLen, &readSize);
	len -= readLen;
	pSCB->response.dataLength = (USHORT)readSize;
	if (err == 0) {
		while (len != 0 && readSize == readLen) {
			pSCB->response.status = 0;
			if (!SendResponse(pSCB, TRUE)) {
				err = ERROR_SERVER_SEND;
				break;
			}

			readLen = min(16384L, len);
			err = DBReadBinary(dbp, NULL, pSCB->lpData, readLen,
								&readSize);
			len -= readLen;
			pSCB->response.dataLength = (USHORT)readSize;
			if (err != 0)
				break;
		}
	}

	LEAVE_CRITICAL_SECTION(&dbp->dp->csDBF);

	return err;
}

/*=======================================================================
 |
 |		PSDBWriteBinary() 関数実行処理
 |
 |	VOID	ExecPSDBWriteBinary(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBWriteBinary(PSCB pSCB)
{
	static	SHORT	PSDBWriteBinaryServer(PSCB, PFCB, CHAR *, LONG, LONG);
	struct	PRM_DB_WRITEBINARY	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_WRITEBINARY *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = PSDBWriteBinaryServer(pSCB, pFCB,
					pParam->field, pParam->len, 0);
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
 |		PSDBWriteBinaryEx() 関数実行処理
 |
 |	VOID	ExecPSDBWriteBinaryEx(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBWriteBinaryEx(PSCB pSCB)
{
	static	SHORT	PSDBWriteBinaryServer(PSCB, PFCB, CHAR *, LONG, LONG);
	struct	PRM_DB_WRITEBINARY_EX	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_WRITEBINARY_EX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if (pParam->recCount == pFCB->dbp->dp->dhp->nRec) {
				status = PSDBWriteBinaryServer(pSCB, pFCB,
						pParam->field, pParam->len,
						pParam->recNo);
			} else
				status = ERROR_SERVER_MULTI;
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

static	SHORT	PSDBWriteBinaryServer(PSCB pSCB, PFCB pFCB, CHAR *field,
							LONG len, LONG recNo)
{
	PDB	dbp;
	SHORT	err;

	dbp = pFCB->dbp;

	ENTER_CRITICAL_SECTION(&dbp->dp->csDBF);

	if (recNo != 0)
		DBSet(dbp, recNo);

	MDBWriteBinary(pSCB, pFCB, FALSE);

	err = DBWriteBinary(dbp, field, pSCB->lpData,
					pSCB->command.dataLength);
	while (pSCB->bContinue) {
		if (!ReceiveCommand(pSCB)) {
			err = ERROR_SERVER_RECEIVE;
			break;
		}

		if (err == 0) {
			MDBWriteBinary(pSCB, pFCB, FALSE);

			err = DBWriteBinary(dbp, NULL, pSCB->lpData,
						pSCB->command.dataLength);
		}
	}

	LEAVE_CRITICAL_SECTION(&dbp->dp->csDBF);

	return err;
}

/*=======================================================================
 |
 |		PSDBGetBinarySize() 関数実行処理
 |
 |	VOID	ExecPSDBGetBinarySize(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBGetBinarySize(PSCB pSCB)
{
	struct	PRM_DB_GETBINARYSIZE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_GETBINARYSIZE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBGetBinarySize(pFCB->dbp, pParam->field,
						&pSCB->response.ret.lValue);
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
 |		PSDBClrRecord() 関数実行処理
 |
 |	VOID	ExecPSDBClrRecord(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBClrRecord(PSCB pSCB)
{
	struct	PRM_DB_CLRRECORD	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_CLRRECORD *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBClrRecord(pFCB->dbp);
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
 |		PSDBGetRecord() 関数実行処理
 |
 |	VOID	ExecPSDBGetRecord(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBGetRecord(PSCB pSCB)
{
	struct	PRM_DB_GETRECORD	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_GETRECORD *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBGetRecord(pFCB->dbp);
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
 |		PSDBGetField() 関数実行処理
 |
 |	VOID	ExecPSDBGetField(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBGetField(PSCB pSCB)
{
	struct	PRM_DB_GETFIELD	*pParam;
	PFCB	pFCB;
	SHORT	status;
	USHORT	dataLength = 0;

	pParam = (struct PRM_DB_GETFIELD *)pSCB->command.param;
	pParam->field[sizeof(pParam->field) - 1] = '\0';

	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBGetField(pFCB->dbp,
					pParam->field, pSCB->lpData,
					&pSCB->response.ret.sValue,
					&dataLength);
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
}

/*=======================================================================
 |
 |		PSDBSetField() 関数実行処理
 |
 |	VOID	ExecPSDBSetField(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSetField(PSCB pSCB)
{
	struct	PRM_DB_SETFIELD	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SETFIELD *)pSCB->command.param;
	pParam->field[sizeof(pParam->field) - 1] = '\0';

	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBSetField(pFCB->dbp,
						pParam->field, pSCB->lpData);
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
 |		PSDBAddRecord() 関数実行処理
 |
 |	VOID	ExecPSDBAddRecord(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBAddRecord(PSCB pSCB)
{
	struct	PRM_DB_ADDRECORD	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_ADDRECORD *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBAddRecord(pSCB, pFCB, FALSE)) == 0)
				status = DBAddRecord(pFCB->dbp);
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
 |		PSDBUpdRecord() 関数実行処理
 |
 |	VOID	ExecPSDBUpdRecord(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBUpdRecord(PSCB pSCB)
{
	struct	PRM_DB_UPDRECORD	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_UPDRECORD *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBUpdRecord(pSCB, pFCB, FALSE)) == 0)
				status = DBUpdRecord(pFCB->dbp);
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
 |		PSDBCopy() 関数実行処理
 |
 |	VOID	ExecPSDBCopy(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBCopy(PSCB pSCB)
{
	pSCB->response.status = ERROR_DB_NOSUPPORT;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSDBCheckDeleted() 関数実行処理
 |
 |	VOID	ExecPSDBCheckDeleted(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBCheckDeleted(PSCB pSCB)
{
	struct	PRM_DB_CHECK_DELETED	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_CHECK_DELETED *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBCheckDeleted(pFCB->dbp,
						&pSCB->response.ret.sValue);
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
 |		PSDBSetFilter() 関数実行処理
 |
 |	VOID	ExecPSDBSetFilter(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSetFilter(PSCB pSCB)
{
	struct	PRM_DB_SET_FILTER	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SET_FILTER *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBSetFilter(pSCB, pFCB, FALSE)) == 0)
				status = DBSetFilter(pFCB->dbp, pSCB->lpData);
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
 |		PSDBSetDeleted() 関数実行処理
 |
 |	VOID	ExecPSDBSetDeleted(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSetDeleted(PSCB pSCB)
{
	struct	PRM_DB_SET_DELETED	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SET_DELETED *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBSetDeleted(pSCB, pFCB, FALSE)) == 0)
				status = DBSetDeleted(pFCB->dbp, pParam->flag);
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
 |		PSDBLRecNo() 関数実行処理
 |
 |	VOID	ExecPSDBLRecNo(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBLRecNo(PSCB pSCB)
{
	struct	PRM_DB_LRECNO	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_LRECNO *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBLRecNo(pFCB->dbp,
					&pSCB->response.ret.lValue);
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
 |		PSDBLRecCount() 関数実行処理
 |
 |	VOID	ExecPSDBLRecCount(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBLRecCount(PSCB pSCB)
{
	struct	PRM_DB_LRECCOUNT	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_LRECCOUNT *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBLRecCount(pFCB->dbp,
					&pSCB->response.ret.lValue);
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
 |		PSDBLSet() 関数実行処理
 |
 |	VOID	ExecPSDBLSet(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBLSet(PSCB pSCB)
{
	struct	PRM_DB_LSET	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_LSET *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBLSet(pFCB->dbp, pParam->lRecNo);
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
 |		PSDBZip() 関数実行処理
 |
 |	VOID	ExecPSDBZip(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBZip(PSCB pSCB)
{
	struct	PRM_DB_ZIP	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_ZIP *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBZip(pSCB, pFCB, FALSE)) == 0)
				status = DBZip(pFCB->dbp);
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
 |		PSDBCheckUpdate() 関数実行処理
 |
 |	VOID	ExecPSDBCheckUpdate(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBCheckUpdate(PSCB pSCB)
{
	struct	PRM_DB_CHECK_UPDATE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_CHECK_UPDATE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBCheckUpdate(pFCB->dbp,
					&pSCB->response.ret.sValue);
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
 |		PSDBLock() 関数実行処理
 |
 |	VOID	ExecPSDBLock(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBLock(PSCB pSCB)
{
	struct	PRM_DB_LOCK	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_LOCK *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBLock(pSCB, pFCB, FALSE)) == 0)
				status = DBLock(pFCB->dbp, pParam->lock);
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
 |		PSDBUnlock() 関数実行処理
 |
 |	VOID	ExecPSDBUnlock(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBUnlock(PSCB pSCB)
{
	struct	PRM_DB_UNLOCK	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_UNLOCK *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBUnlock(pSCB, pFCB, FALSE)) == 0)
				status = DBUnlock(pFCB->dbp, pParam->lock);
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
 |		PSDBRecCount() 関数実行処理
 |
 |	VOID	ExecPSDBRecCount(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBRecCount(PSCB pSCB)
{
	struct	PRM_DB_RECCOUNT	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_RECCOUNT *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBRecCount(pFCB->dbp,
					&pSCB->response.ret.lValue);
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
 |		PSDBBof() 関数実行処理
 |
 |	VOID	ExecPSDBBof(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBBof(PSCB pSCB)
{
	struct	PRM_DB_BOF	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_BOF *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBBof(pFCB->dbp, &pSCB->response.ret.sValue);
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
 |		PSDBDbf() 関数実行処理
 |
 |	VOID	ExecPSDBDbf(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBDbf(PSCB pSCB)
{
	struct	PRM_DB_DBF	*pParam;
	PFCB	pFCB;
	SHORT	status;
	USHORT	dataLength = 0;

	pParam = (struct PRM_DB_DBF *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBDbf(pFCB->dbp, pSCB->lpData);
			if (status == 0)
				dataLength = strlen(pSCB->lpData) + 1;
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
}

/*=======================================================================
 |
 |		PSDBEof() 関数実行処理
 |
 |	VOID	ExecPSDBEof(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBEof(PSCB pSCB)
{
	struct	PRM_DB_EOF	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_EOF *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBEof(pFCB->dbp, &pSCB->response.ret.sValue);
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
 |		PSDBNField() 関数実行処理
 |
 |	VOID	ExecPSDBNField(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBNField(PSCB pSCB)
{
	struct	PRM_DB_NFIELD	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_NFIELD *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBNField(pFCB->dbp,
					&pSCB->response.ret.sValue);
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
 |		PSDBField() 関数実行処理
 |
 |	VOID	ExecPSDBField(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBField(PSCB pSCB)
{
	struct	PRM_DB_FIELD	*pParam;
	PFCB	pFCB;
	SHORT	status;
	USHORT	dataLength = 0;

	pParam = (struct PRM_DB_FIELD *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBField(pFCB->dbp, pParam->n,
							(PDBF_I)pSCB->lpData);
			if (status == 0)
				dataLength = sizeof(DBF_I);
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
}

/*=======================================================================
 |
 |		PSDBNdx() 関数実行処理
 |
 |	VOID	ExecPSDBNdx(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBNdx(PSCB pSCB)
{
	struct	PRM_DB_NDX	*pParam;
	PFCB	pFCB;
	SHORT	status;
	USHORT	dataLength = 0;

	pParam = (struct PRM_DB_NDX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBNdx(pFCB->dbp, pParam->n, pSCB->lpData);
			if (status == 0)
				dataLength = strlen(pSCB->lpData) + 1;
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
}

/*=======================================================================
 |
 |		PSDBRecNo() 関数実行処理
 |
 |	VOID	ExecPSDBRecNo(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBRecNo(PSCB pSCB)
{
	struct	PRM_DB_RECNO	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_RECNO *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBRecNo(pFCB->dbp,
					&pSCB->response.ret.lValue);
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
 |		PSDBRecSize() 関数実行処理
 |
 |	VOID	ExecPSDBRecSize(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBRecSize(PSCB pSCB)
{
	struct	PRM_DB_RECSIZE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_RECSIZE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBRecSize(pFCB->dbp,
						&pSCB->response.ret.sValue);
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
 |		PSDBSetReadField() 関数実行処理
 |
 |	VOID	ExecPSDBSetReadField(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSetReadField(PSCB pSCB)
{
	struct	PRM_DB_SET_READFIELD	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SET_READFIELD *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBSetReadField(pFCB->dbp,
					pSCB->lpData,
					&pSCB->response.ret.sValue);
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
 |		PSDBSetScramble() 関数実行処理
 |
 |	VOID	ExecPSDBSetScramble(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSetScramble(PSCB pSCB)
{
	struct	PRM_DB_SET_SCRAMBLE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SET_SCRAMBLE *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBSetScramble(pSCB, pFCB, FALSE)) == 0)
				status = DBSetScramble(pFCB->dbp);
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
 |		PSDBSetPassword() 関数実行処理
 |
 |	VOID	ExecPSDBSetPassword(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSetPassword(PSCB pSCB)
{
	struct	PRM_DB_SET_PASSWORD	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SET_PASSWORD *)pSCB->command.param;
	pParam->password[sizeof(pParam->password) - 1] = '\0';

	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			if ((status = MDBSetPassword(pSCB, pFCB, FALSE)) == 0)
				status = DBSetPassword(pFCB->dbp,
							pParam->password);
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
 |		PSDBUpdateOracle() 関数実行処理
 |
 |	VOID	ExecPSDBUpdateOracle(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBUpdateOracle(PSCB pSCB)
{
	struct	PRM_DB_UPDATEORACLE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_UPDATEORACLE *)pSCB->command.param;
	pParam->key[sizeof(pParam->key) - 1] = '\0';

	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = ERROR_SERVER_NOSUPPORT;
			break;
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
 |		PSDBDeleteOracle() 関数実行処理
 |
 |	VOID	ExecPSDBDeleteOracle(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBDeleteOracle(PSCB pSCB)
{
	struct	PRM_DB_DELETEORACLE	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_DELETEORACLE *)pSCB->command.param;
	pParam->key[sizeof(pParam->key) - 1] = '\0';

	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = ERROR_SERVER_NOSUPPORT;
			break;
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
 |		PSDBUpdateEx() 関数実行処理
 |
 |	VOID	ExecPSDBUpdateEx(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBUpdateEx(PSCB pSCB)
{
	struct	PRM_DB_UPDATE_EX	*pParam;
	PFCB	pFCB;
	PDB	dbp;
	SHORT	status;

	pParam = (struct PRM_DB_UPDATE_EX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			/* レコード数チェック */
			dbp = pFCB->dbp;
			if (pParam->recCount == dbp->dp->dhp->nRec)
				status = DBUpdateEx(dbp, pParam->recNo,
								pSCB->lpData);
			else
				status = ERROR_SERVER_MULTI;
			break;
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
 |		PSDBDeleteEx() 関数実行処理
 |
 |	VOID	ExecPSDBDeleteEx(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBDeleteEx(PSCB pSCB)
{
	struct	PRM_DB_DELETE_EX	*pParam;
	PFCB	pFCB;
	PDB	dbp;
	SHORT	status;

	pParam = (struct PRM_DB_DELETE_EX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			/* レコード数チェック */
			dbp = pFCB->dbp;
			if (pParam->recCount == dbp->dp->dhp->nRec)
				status = DBDeleteEx(dbp, pParam->recNo);
			else
				status = ERROR_SERVER_MULTI;
			break;
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
 |		PSDBRecallEx() 関数実行処理
 |
 |	VOID	ExecPSDBRecallEx(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBRecallEx(PSCB pSCB)
{
	struct	PRM_DB_RECALL_EX	*pParam;
	PFCB	pFCB;
	PDB	dbp;
	SHORT	status;

	pParam = (struct PRM_DB_RECALL_EX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			/* レコード数チェック */
			dbp = pFCB->dbp;
			if (pParam->recCount == dbp->dp->dhp->nRec)
				status = DBRecallEx(dbp, pParam->recNo);
			else
				status = ERROR_SERVER_MULTI;
			break;
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
 |		PSDBDelete2Ex() 関数実行処理
 |
 |	VOID	ExecPSDBDelete2Ex(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBDelete2Ex(PSCB pSCB)
{
	struct	PRM_DB_DELETE2_EX	*pParam;
	PFCB	pFCB;
	PDB	dbp;
	SHORT	status;

	pParam = (struct PRM_DB_DELETE2_EX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			/* レコード数チェック */
			dbp = pFCB->dbp;
			if (pParam->recCount == dbp->dp->dhp->nRec)
				status = DBDelete2Ex(dbp, pParam->recNo);
			else
				status = ERROR_SERVER_MULTI;
			break;
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
 |		PSDBLockEx() 関数実行処理
 |
 |	VOID	ExecPSDBLockEx(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBLockEx(PSCB pSCB)
{
	struct	PRM_DB_LOCK_EX	*pParam;
	PFCB	pFCB;
	PDB	dbp;
	SHORT	status;

	pParam = (struct PRM_DB_LOCK_EX *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			/* レコード数チェック */
			dbp = pFCB->dbp;
			if (pParam->recCount == dbp->dp->dhp->nRec)
				status =DBLockEx(dbp, pParam->recNo,
							pParam->lock);
			else
				status = ERROR_SERVER_MULTI;
			break;
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
 |		PSDBSelect() 関数実行処理
 |
 |	VOID	ExecPSDBSelect(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBSelect(PSCB pSCB)
{
	struct	PRM_DB_SELECT	*pParam;
	PFCB	pFCB;
	SHORT	status;

	pParam = (struct PRM_DB_SELECT *)pSCB->command.param;
	pFCB = GetFCB(pSCB, pParam->handle);

	if (pFCB == NULL)
		status = ERROR_FILE_HANDLE;
	else {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			status = DBSelect(pFCB->dbp, pSCB->lpData,
					&pSCB->response.ret.lValue);
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
 |		PSDBPackIndex() 関数実行処理
 |
 |	VOID	ExecPSDBPackIndex(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDBPackIndex(PSCB pSCB)
{
	struct	PRM_DB_PACKINDEX	*pParam;
	SHORT	status;
	CHAR	fileName[256];

	pParam = (struct PRM_DB_PACKINDEX *)pSCB->command.param;
	pParam->fileName[sizeof(pParam->fileName) - 1] = '\0';
	if ((status = ConvertPathName(pParam->fileName, fileName)) == 0) {
		if ((status = MDBPackIndex(pSCB, NULL, FALSE)) == 0)
			status = DBPackIndex(pParam->fileName);
	}

	pSCB->response.status = status;
	pSCB->response.dataLength = 0;
}
