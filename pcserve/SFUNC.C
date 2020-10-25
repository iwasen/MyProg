#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"

/*=======================================================================
 |
 |		接続コマンド処理
 |
 |	int	PSConxxxt(pCCB, chType, hostName, clientID, err)
 |
 |		PCCB	pCCB;		接続制御ブロックポインタ
 |		int	chType;		チャネル種別
 |		CHAR	*hostName;	ホスト名
 |		CHAR	*clientID;	クライアントＩＤ
 |
 |		int	返値		エラーコード
 |
 =======================================================================*/
int	PSConxxxt(PCCB pCCB, int chType, CHAR *hostName, CHAR *clientID)
{
	struct	PRM_CONxxxT	*pParam;
	DWORD	nameSize;
	int	err;

	if (pCCB->bConxxxt)
		PSDisconxxxt(pCCB);

	memset(pCCB, 0, sizeof(CCB));
	pCCB->handle = -1;

	pCCB->chType = chType;

	if ((err = NetConxxxt(pCCB, hostName)) != 0)
		return err;

	pCCB->bConxxxt = TRUE;
	INIT_CRITICAL_SECTION(&pCCB->csCCB);

	if (clientID != NULL) {
		pParam = (struct PRM_CONxxxT *)pCCB->command.param;
		strcpy(pParam->clientID, clientID);
		nameSize = sizeof(pParam->computerName);
		GetComputerName(pParam->computerName, &nameSize);

		if ((err = Requexxxommand(pCCB, ID_COMMAND, FC_CONxxxT,
				sizeof(struct PRM_CONxxxT), 0)) != 0) {
			NetDisconxxxt(pCCB);
			return err;
		}
	}

	return 0;
}


/*=======================================================================
 |
 |		切断コマンド処理
 |
 |	int	PSDisconxxxt(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 |		int	返値		エラーコード
 |
 =======================================================================*/
int	PSDisconxxxt(PCCB pCCB)
{
	if (pCCB->bConxxxt) {
		NetDisconxxxt(pCCB);
		pCCB->bConxxxt = FALSE;
		DELETE_CRITICAL_SECTION(&pCCB->csCCB);
	}

	return 0;
}

int	PSDuplexKeepAlive(PCCB pCCB, int id)
{
	return Requexxxommand(pCCB, id, FC_DUPLEX_KEEPALIVE, 0, 0);
}

int	PSDuplexError(PCCB pCCB, int id, CHAR *fileName, int errorCode)
{
	struct	PRM_DUPLEX_ERROR	*pParam;

	pParam = (struct PRM_DUPLEX_ERROR *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);
	pParam->errorCode = errorCode;

	return Requexxxommand(pCCB, id, FC_DUPLEX_ERROR,
					sizeof(struct PRM_DUPLEX_ERROR), 0);
}

int	PSDuplexCreate(PCCB pCCB, int id, CHAR *fileName)
{
	struct	PRM_DUPLEX_CREATE	*pParam;

	pParam = (struct PRM_DUPLEX_CREATE *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);

	return Requexxxommand(pCCB, id, FC_DUPLEX_CREATE,
					sizeof(struct PRM_DUPLEX_CREATE), 0);
}

int	PSDuplexWrite(PCCB pCCB, int id, char *fileName, char *buf,
							int size, long offset)
{
	struct	PRM_DUPLEX_WRITE	*pParam;

	pParam = (struct PRM_DUPLEX_WRITE *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);
	pParam->size = size;
	pParam->offset = offset;
	pCCB->pInData = buf;

	return Requexxxommand(pCCB, id, FC_DUPLEX_WRITE,
				sizeof(struct PRM_DUPLEX_WRITE), size);
}


int	PSDuplexNewSize(PCCB pCCB, int id, char *fileName, int size)
{
	struct	PRM_DUPLEX_NEWSIZE	*pParam;

	pParam = (struct PRM_DUPLEX_NEWSIZE *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);
	pParam->size = size;

	return Requexxxommand(pCCB, id, FC_DUPLEX_NEWSIZE,
				sizeof(struct PRM_DUPLEX_NEWSIZE), 0);
}

int	PSDuplexDelete(PCCB pCCB, int id, char *fileName)
{
	struct	PRM_DUPLEX_DELETE	*pParam;

	pParam = (struct PRM_DUPLEX_DELETE *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);

	return Requexxxommand(pCCB, id, FC_DUPLEX_DELETE,
				sizeof(struct PRM_DUPLEX_DELETE), 0);
}

int	PSDuplexFileTime(PCCB pCCB, int id, char *fileName,
					LPFILETIME lpftCreation,
					LPFILETIME lpftLastAccess,
					LPFILETIME lpftLastWrite)
{
	struct	PRM_DUPLEX_FILETIME	*pParam;

	pParam = (struct PRM_DUPLEX_FILETIME *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);
	pParam->ftCreation = *lpftCreation;
	pParam->ftLastAccess = *lpftLastAccess;
	pParam->ftLastWrite = *lpftLastWrite;

	return Requexxxommand(pCCB, id, FC_DUPLEX_FILETIME,
				sizeof(struct PRM_DUPLEX_FILETIME), 0);
}

int	PSDuplexCopy(PCCB pCCB, int id, char *srcFileName, char *dstFileName)
{
	struct	PRM_DUPLEX_COPY	*pParam;

	pParam = (struct PRM_DUPLEX_COPY *)pCCB->command.param;
	strcpy(pParam->srcFileName, srcFileName);
	strcpy(pParam->dstFileName, dstFileName);

	return Requexxxommand(pCCB, id, FC_DUPLEX_COPY,
				sizeof(struct PRM_DUPLEX_COPY), 0);
}

int	PSDuplexRename(PCCB pCCB, int id, char *oldFileName, char *newFileName)
{
	struct	PRM_DUPLEX_RENAME	*pParam;

	pParam = (struct PRM_DUPLEX_RENAME *)pCCB->command.param;
	strcpy(pParam->oldFileName, oldFileName);
	strcpy(pParam->newFileName, newFileName);

	return Requexxxommand(pCCB, id, FC_DUPLEX_RENAME,
				sizeof(struct PRM_DUPLEX_RENAME), 0);
}

int	PSDBOpen(PCCB pCCB, int id, CHAR *fileName)
{
	struct	PRM_DB_OPEN	*pParam;
	DBINF	dbInf;

	pParam = (struct PRM_DB_OPEN *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);
	pCCB->pOutData = &dbInf;

	return Requexxxommand(pCCB, id, FC_DB_OPEN,
				sizeof(struct PRM_DB_OPEN), 0);
}

int	PSDBCreate(PCCB pCCB, int id, CHAR *fileName, PDBF_I dip, int nField)
{
	struct	PRM_DB_CREATE	*pParam;
	DBINF	dbInf;

	pParam = (struct PRM_DB_CREATE *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);
	pParam->nField = nField;
	pCCB->pInData = dip;
	pCCB->pOutData = &dbInf;

	return Requexxxommand(pCCB, id, FC_DB_CREATE,
			sizeof(struct PRM_DB_CREATE), sizeof(DBF_I) * nField);
}

int	PSDBClose(PCCB pCCB, int id, int handle)
{
	struct	PRM_DB_CLOSE	*pParam;

	pParam = (struct PRM_DB_CLOSE *)pCCB->command.param;
	pParam->handle = handle;

	return Requexxxommand(pCCB, id, FC_DB_CLOSE,
				sizeof(struct PRM_DB_CLOSE), 0);
}

int	PSDBFlush(PCCB pCCB, int id, int handle)
{
	struct	PRM_DB_FLUSH	*pParam;

	pParam = (struct PRM_DB_FLUSH *)pCCB->command.param;
	pParam->handle = handle;

	return Requexxxommand(pCCB, id, FC_DB_FLUSH,
				sizeof(struct PRM_DB_FLUSH), 0);
}

int	PSDBIndex(PCCB pCCB, int id, int handle, CHAR *fileName)
{
	struct	PRM_DB_INDEX	*pParam;

	pParam = (struct PRM_DB_INDEX *)pCCB->command.param;
	pParam->handle = handle;
	strcpy(pParam->fileName, fileName);

	return Requexxxommand(pCCB, id, FC_DB_INDEX,
				sizeof(struct PRM_DB_INDEX), 0);
}

int	PSDBIdxCreate(PCCB pCCB, int id, int handle, CHAR *fileName,
						CHAR *key, int uniq)
{
	struct	PRM_DB_IDX_CREATE	*pParam;

	pParam = (struct PRM_DB_IDX_CREATE *)pCCB->command.param;
	pParam->handle = handle;
	strcpy(pParam->fileName, fileName);
	strcpy(pParam->key, key);
	pParam->uniq = uniq;

	return Requexxxommand(pCCB, id, FC_DB_IDX_CREATE,
				sizeof(struct PRM_DB_IDX_CREATE), 0);
}

int	PSDBChgIdx(PCCB pCCB, int id, int handle, int n)
{
	struct	PRM_DB_CHGIDX	*pParam;

	pParam = (struct PRM_DB_CHGIDX *)pCCB->command.param;
	pParam->handle = handle;
	pParam->n = n;

	return Requexxxommand(pCCB, id, FC_DB_CHGIDX,
				sizeof(struct PRM_DB_CHGIDX), 0);
}

int	PSDBSearchLock(PCCB pCCB, int id, int handle, CHAR *key, int len)
{
	struct	PRM_DB_SEARCHLOCK	*pParam;

	pParam = (struct PRM_DB_SEARCHLOCK *)pCCB->command.param;
	pParam->handle = handle;
	memcpy(pParam->key, key, len);
	pParam->len = len;

	return Requexxxommand(pCCB, id, FC_DB_SEARCHLOCK,
				sizeof(struct PRM_DB_SEARCHLOCK), 0);
}

int	PSDBStore(PCCB pCCB, int id, int handle, CHAR *rec, int recSize)
{
	struct	PRM_DB_STORE	*pParam;

	pParam = (struct PRM_DB_STORE *)pCCB->command.param;
	pParam->handle = handle;
	pCCB->pInData = rec;

	return Requexxxommand(pCCB, id, FC_DB_STORE,
				sizeof(struct PRM_DB_STORE), recSize);
}

int	PSDBStoreUniq(PCCB pCCB, int id, int handle, CHAR *rec, int recSize)
{
	struct	PRM_DB_STOREUNIQ	*pParam;

	pParam = (struct PRM_DB_STOREUNIQ *)pCCB->command.param;
	pParam->handle = handle;
	pCCB->pInData = rec;

	return Requexxxommand(pCCB, id, FC_DB_STOREUNIQ,
				sizeof(struct PRM_DB_STOREUNIQ), recSize);
}

int	PSDBUpdateEx(PCCB pCCB, int id, int handle, CHAR *rec, int recSize,
						LONG recNo, LONG recCount)
{
	struct	PRM_DB_UPDATE_EX	*pParam;

	pParam = (struct PRM_DB_UPDATE_EX *)pCCB->command.param;
	pParam->handle = handle;
	pParam->recNo = recNo;
	pParam->recCount = recCount;
	pCCB->pInData = rec;

	return Requexxxommand(pCCB, id, FC_DB_UPDATE_EX,
				sizeof(struct PRM_DB_UPDATE_EX), recSize);
}

int	PSDBUpdateKey(PCCB pCCB, int id, int handle, CHAR *key, int len,
						CHAR *rec, int recSize)
{
	struct	PRM_DB_UPDATEKEY	*pParam;

	pParam = (struct PRM_DB_UPDATEKEY *)pCCB->command.param;
	pParam->handle = handle;
	memcpy(pParam->key, key, len);
	pParam->len = len;
	pCCB->pInData = rec;

	return Requexxxommand(pCCB, id, FC_DB_UPDATEKEY,
				sizeof(struct PRM_DB_UPDATEKEY), recSize);
}

int	PSDBDeleteEx(PCCB pCCB, int id, int handle, LONG recNo, LONG recCount)
{
	struct	PRM_DB_DELETE_EX	*pParam;

	pParam = (struct PRM_DB_DELETE_EX *)pCCB->command.param;
	pParam->handle = handle;
	pParam->recNo = recNo;
	pParam->recCount = recCount;

	return Requexxxommand(pCCB, id, FC_DB_DELETE_EX,
				sizeof(struct PRM_DB_DELETE_EX), 0);
}

int	PSDBRecallEx(PCCB pCCB, int id, int handle, LONG recNo, LONG recCount)
{
	struct	PRM_DB_RECALL_EX	*pParam;

	pParam = (struct PRM_DB_RECALL_EX *)pCCB->command.param;
	pParam->handle = handle;
	pParam->recNo = recNo;
	pParam->recCount = recCount;

	return Requexxxommand(pCCB, id, FC_DB_RECALL_EX,
				sizeof(struct PRM_DB_RECALL_EX), 0);
}

int	PSDBDelete2Ex(PCCB pCCB, int id, int handle, LONG recNo, LONG recCount)
{
	struct	PRM_DB_DELETE2_EX	*pParam;

	pParam = (struct PRM_DB_DELETE2_EX *)pCCB->command.param;
	pParam->handle = handle;
	pParam->recNo = recNo;
	pParam->recCount = recCount;

	return Requexxxommand(pCCB, id, FC_DB_DELETE2_EX,
				sizeof(struct PRM_DB_DELETE2_EX), 0);
}

int	PSDBDeleteKey(PCCB pCCB, int id, int handle, CHAR *key, int len,
								int flag)
{
	struct	PRM_DB_DELETEKEY	*pParam;

	pParam = (struct PRM_DB_DELETEKEY *)pCCB->command.param;
	pParam->handle = handle;
	memcpy(pParam->key, key, len);
	pParam->len = len;
	pParam->flag = flag;

	return Requexxxommand(pCCB, id, FC_DB_DELETEKEY,
				sizeof(struct PRM_DB_DELETEKEY), 0);
}

int	PSDBReindex(PCCB pCCB, int id, int handle)
{
	struct	PRM_DB_REINDEX	*pParam;

	pParam = (struct PRM_DB_REINDEX *)pCCB->command.param;
	pParam->handle = handle;

	return Requexxxommand(pCCB, id, FC_DB_REINDEX,
				sizeof(struct PRM_DB_REINDEX), 0);
}

int	PSDBPack(PCCB pCCB, int id, int handle)
{
	struct	PRM_DB_PACK	*pParam;

	pParam = (struct PRM_DB_PACK *)pCCB->command.param;
	pParam->handle = handle;

	return Requexxxommand(pCCB, id, FC_DB_PACK,
				sizeof(struct PRM_DB_PACK), 0);
}

int	PSDBReadKey(PCCB pCCB, int id, int handle, CHAR *key, int len,
							CHAR *buf, int lock)
{
	struct	PRM_DB_READKEY	*pParam;

	pParam = (struct PRM_DB_READKEY *)pCCB->command.param;
	pParam->handle = handle;
	memcpy(pParam->key, key, len);
	pParam->len = len;
	pParam->lock = lock;
	pCCB->pOutData = buf;

	return Requexxxommand(pCCB, id, FC_DB_READKEY,
				sizeof(struct PRM_DB_READKEY), 0);
}

int	PSDBSetFilter(PCCB pCCB, int id, int handle, CHAR *filter)
{
	struct	PRM_DB_SET_FILTER	*pParam;

	pParam = (struct PRM_DB_SET_FILTER *)pCCB->command.param;
	pParam->handle = handle;
	pCCB->pInData = filter;

	return Requexxxommand(pCCB, id, FC_DB_SET_FILTER,
			sizeof(struct PRM_DB_SET_FILTER), strlen(filter) + 1);
}

int	PSDBSetDeleted(PCCB pCCB, int id, int handle, int flag)
{
	struct	PRM_DB_SET_DELETED	*pParam;

	pParam = (struct PRM_DB_SET_DELETED *)pCCB->command.param;
	pParam->handle = handle;
	pParam->flag = flag;

	return Requexxxommand(pCCB, id, FC_DB_SET_DELETED,
				sizeof(struct PRM_DB_SET_DELETED), 0);
}

int	PSDBZip(PCCB pCCB, int id, int handle)
{
	struct	PRM_DB_ZIP	*pParam;

	pParam = (struct PRM_DB_ZIP *)pCCB->command.param;
	pParam->handle = handle;

	return Requexxxommand(pCCB, id, FC_DB_ZIP,
				sizeof(struct PRM_DB_ZIP), 0);
}

int	PSDBLockEx(PCCB pCCB, int id, int handle, LONG recNo, LONG recCount,
								int lock)
{
	struct	PRM_DB_LOCK_EX	*pParam;

	pParam = (struct PRM_DB_LOCK_EX *)pCCB->command.param;
	pParam->handle = handle;
	pParam->recNo = recNo;
	pParam->recCount = recCount;
	pParam->lock = lock;

	return Requexxxommand(pCCB, id, FC_DB_LOCK_EX,
				sizeof(struct PRM_DB_LOCK_EX), 0);
}


int	PSDBUnlock(PCCB pCCB, int id, int handle, int lock)
{
	struct	PRM_DB_UNLOCK	*pParam;

	pParam = (struct PRM_DB_UNLOCK *)pCCB->command.param;
	pParam->handle = handle;
	pParam->lock = lock;

	return Requexxxommand(pCCB, id, FC_DB_UNLOCK,
				sizeof(struct PRM_DB_UNLOCK), 0);
}

int	PSDBSetScramble(PCCB pCCB, int id, int handle)
{
	struct	PRM_DB_SET_SCRAMBLE	*pParam;

	pParam = (struct PRM_DB_SET_SCRAMBLE *)pCCB->command.param;
	pParam->handle = handle;

	return Requexxxommand(pCCB, id, FC_DB_SET_SCRAMBLE,
				sizeof(struct PRM_DB_SET_SCRAMBLE), 0);
}

int	PSDBSetPassword(PCCB pCCB, int id, int handle, CHAR *password)
{
	struct	PRM_DB_SET_PASSWORD	*pParam;

	pParam = (struct PRM_DB_SET_PASSWORD *)pCCB->command.param;
	pParam->handle = handle;
	strcpy(pParam->password, password);

	return Requexxxommand(pCCB, id, FC_DB_SET_PASSWORD,
				sizeof(struct PRM_DB_SET_PASSWORD), 0);
}

int	PSDBPackIndex(PCCB pCCB, int id, CHAR *fileName)
{
	struct	PRM_DB_PACKINDEX	*pParam;

	pParam = (struct PRM_DB_PACKINDEX *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);

	return Requexxxommand(pCCB, id, FC_DB_PACKINDEX,
				sizeof(struct PRM_DB_PACKINDEX), 0);
}

int	PSDBWriteBinaryEx(PCCB pCCB, int id, int handle, CHAR *fieldName,
				CHAR *pBuf, LONG dataSize, LONG bufSize,
				LONG recNo, LONG recCount)
{
	struct	PRM_DB_WRITEBINARY_EX	*pParam;

	pParam = (struct PRM_DB_WRITEBINARY_EX *)pCCB->command.param;
	pParam->handle = handle;
	strcpy(pParam->field, fieldName);
	pParam->len = dataSize;
	pParam->recNo = recNo;
	pParam->recCount = recCount;

	pCCB->pInData = pBuf;

	return Requexxxommand(pCCB, id, FC_DB_WRITEBINARY_EX,
				sizeof(struct PRM_DB_WRITEBINARY_EX), bufSize);
}

int	PSRFOpen(PCCB pCCB, int id, CHAR *fileName,
					int accessMode, int shareMode)
{
	struct	PRM_RF_OPEN	*pParam;

	pParam = (struct PRM_RF_OPEN *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);
	pParam->accessMode = accessMode;
	pParam->shareMode = shareMode;

	return Requexxxommand(pCCB, id, FC_RF_OPEN,
				sizeof(struct PRM_RF_OPEN), 0);
}

int	PSRFCreate(PCCB pCCB, int id, CHAR *fileName,
					int accessMode, int shareMode)
{
	struct	PRM_RF_CREATE	*pParam;

	pParam = (struct PRM_RF_CREATE *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);
	pParam->accessMode = accessMode;
	pParam->shareMode = shareMode;

	return Requexxxommand(pCCB, id, FC_RF_CREATE,
				sizeof(struct PRM_RF_CREATE), 0);
}

int	PSRFClose(PCCB pCCB, int id, int handle)
{
	struct	PRM_RF_CLOSE	*pParam;

	pParam = (struct PRM_RF_CLOSE *)pCCB->command.param;
	pParam->handle = handle;

	return Requexxxommand(pCCB, id, FC_RF_CLOSE,
				sizeof(struct PRM_RF_CLOSE), 0);
}

int	PSRFWrite(PCCB pCCB, int id, int handle, VOID *bufp, int length)
{
	struct	PRM_RF_WRITE	*pParam;

	pParam = (struct PRM_RF_WRITE *)pCCB->command.param;
	pParam->handle = handle;
	pParam->length = length;
	pCCB->pInData = bufp;

	return Requexxxommand(pCCB, id, FC_RF_WRITE,
				sizeof(struct PRM_RF_WRITE), length);
}

int	PSRFWriteEx(PCCB pCCB, int id, int handle, CHAR *buf,
						LONG offset, int length)
{
	struct	PRM_RF_WRITE_EX	*pParam;

	pParam = (struct PRM_RF_WRITE_EX *)pCCB->command.param;
	pParam->handle = handle;
	pParam->offset = offset;
	pParam->length = length;
	pCCB->pInData = buf;

	return Requexxxommand(pCCB, id, FC_RF_WRITE_EX,
				sizeof(struct PRM_RF_WRITE_EX), length);
}

int	PSRFLock(PCCB pCCB, int id, int handle, LONG offset, LONG range)
{
	struct	PRM_RF_LOCK	*pParam;

	pParam = (struct PRM_RF_LOCK *)pCCB->command.param;
	pParam->handle = handle;
	pParam->offset = offset;
	pParam->range = range;

	return Requexxxommand(pCCB, id, FC_RF_LOCK,
				sizeof(struct PRM_RF_LOCK), 0);
}

int	PSRFUnlock(PCCB pCCB, int id, int handle, LONG offset, LONG range)
{
	struct	PRM_RF_UNLOCK	*pParam;

	pParam = (struct PRM_RF_UNLOCK *)pCCB->command.param;
	pParam->handle = handle;
	pParam->offset = offset;
	pParam->range = range;

	return Requexxxommand(pCCB, id, FC_RF_UNLOCK,
				sizeof(struct PRM_RF_UNLOCK), 0);
}

int	PSRFNewSize(PCCB pCCB, int id, int handle, LONG fileSize)
{
	struct	PRM_RF_NEWSIZE	*pParam;

	pParam = (struct PRM_RF_NEWSIZE *)pCCB->command.param;
	pParam->handle = handle;
	pParam->fileSize = fileSize;

	return Requexxxommand(pCCB, id, FC_RF_NEWSIZE,
				sizeof(struct PRM_RF_NEWSIZE), 0);
}

int	PSRFFileTime(PCCB pCCB, int id, int handle,
					LPFILETIME lpftCreation,
					LPFILETIME lpftLastAccess,
					LPFILETIME lpftLastWrite)
{
	struct	PRM_RF_FILETIME	*pParam;

	pParam = (struct PRM_RF_FILETIME *)pCCB->command.param;
	pParam->handle = handle;
	pParam->ftCreation = *lpftCreation;
	pParam->ftLastAccess = *lpftLastAccess;
	pParam->ftLastWrite = *lpftLastWrite;

	return Requexxxommand(pCCB, id, FC_RF_FILETIME,
				sizeof(struct PRM_RF_FILETIME), 0);
}

int	PSCFDelete(PCCB pCCB, int id, CHAR *fileName)
{
	struct	PRM_CF_DELETE	*pParam;

	pParam = (struct PRM_CF_DELETE *)pCCB->command.param;
	strcpy(pParam->fileName, fileName);

	return Requexxxommand(pCCB, id, FC_CF_DELETE,
				sizeof(struct PRM_CF_DELETE), 0);
}

int	PSCFCreateDirectory(PCCB pCCB, int id, CHAR *dirName)
{
	struct	PRM_CF_CREATEDIRECTORY	*pParam;

	pParam = (struct PRM_CF_CREATEDIRECTORY *)pCCB->command.param;
	strcpy(pParam->dirName, dirName);

	return Requexxxommand(pCCB, id, FC_CF_CREATEDIRECTORY,
				sizeof(struct PRM_CF_CREATEDIRECTORY), 0);
}

int	PSMultiCopyInfo(PCCB pCCB, int id, int dataType, VOID *data,
								int dataSize)
{
	struct	PRM_MULTI_COPYINFO	*pParam;

	pParam = (struct PRM_MULTI_COPYINFO *)pCCB->command.param;
	pParam->dataType = dataType;
	pCCB->pInData = data;

	return Requexxxommand(pCCB, id, FC_MULTI_COPYINFO,
				sizeof(struct PRM_MULTI_COPYINFO), dataSize);
}
