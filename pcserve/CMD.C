/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: コマンド実行処理
 *		ファイル名	: cmd.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <io.h>
#include <fcntl.h>
#include "pcssock.h"
#include "pcserve.h"
#include "pcsio.h"

static	BOOL	ExecCommand(PSCB);
static	VOID	FileCloseAll(PSCB);

static	VOID	WriteCommandLog(PSCB);
static	VOID	WriteResponseLog(PSCB);
static	VOID	CheckLogFileSize(VOID);

static	int	fdLog = -1;
CRITICALSECTION	csLogFile;	/* ログファイル書き込みクリチカルセクション */
static	long	logFileSize;

/*=======================================================================
 |
 |		コマンド実行処理
 |
 |	BOOL	CommandService(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 |		BOOL	返値		TRUE:正常　FALSE:エラーまたは終了
 |
 =======================================================================*/
BOOL	CommandService(PSCB pSCB)
{
	BOOL	rc;

	if (!ReceiveCommand(pSCB))
		return FALSE;

	if (!ExecCommand(pSCB))
		return FALSE;

	rc = SendResponse(pSCB, FALSE);

	if (pSCB->pMultiServerFunc != NULL) {
		if (pSCB->response.status == 0) {
			(*pSCB->pMultiServerFunc)(pSCB, pSCB->pCurrentFCB, TRUE);
		}
		pSCB->pMultiServerFunc = NULL;
	}

	if (!rc || pSCB->bCloseConxxxtion)
		return FALSE;

	return TRUE;
}

/*=======================================================================
 |
 |		コマンド実行処理
 |
 |	BOOL	ExecCommand(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 |		BOOL	返値		TRUE:正常　FALSE:エラー
 |
 =======================================================================*/
static	BOOL	ExecCommand(PSCB pSCB)
{
	extern	int	bDuplexSlave;

	WriteCommandLog(pSCB);

	statistics.callCount++;

	if (ini.duplexMode == DUPLEX_SLAVE && bDuplexSlave) {
		switch (pSCB->command.code) {
		case FC_DUPLEX_KEEPALIVE:
			ExecPSDuplexKeepAlive(pSCB);
			break;
		case FC_DUPLEX_CREATE:
			ExecPSDuplexCreate(pSCB);
			break;
		case FC_DUPLEX_WRITE:
			ExecPSDuplexWrite(pSCB);
			break;
		case FC_DUPLEX_NEWSIZE:
			ExecPSDuplexNewSize(pSCB);
			break;
		case FC_DUPLEX_DELETE:
			ExecPSDuplexDelete(pSCB);
			break;
		case FC_DUPLEX_FILETIME:
			ExecPSDuplexFileTime(pSCB);
			break;
		case FC_DUPLEX_COPY:
			ExecPSDuplexCopy(pSCB);
			break;
		case FC_DUPLEX_RENAME:
			ExecPSDuplexRename(pSCB);
			break;
		default:
			pSCB->response.dataLength = 0;
			pSCB->response.status = ERROR_SERVER_DUPLEX;
			pSCB->bCloseConxxxtion = TRUE;
			break;
		}
	} else {
		switch (pSCB->command.code) {
		case FC_CONxxxT:
			ExecPSConxxxt(pSCB);
			break;
		case FC_DISCONxxxT:
			ExecPSDisconxxxt(pSCB);
			break;
		case FC_LOGIN:
			ExecPSLogin(pSCB);
			break;
		case FC_DB_OPEN:
			ExecPSDBOpen(pSCB);
			break;
		case FC_DB_CREATE:
			ExecPSDBCreate(pSCB);
			break;
		case FC_DB_CLOSE:
			ExecPSDBClose(pSCB);
			break;
		case FC_DB_FLUSH:
			ExecPSDBFlush(pSCB);
			break;
		case FC_DB_INDEX:
			ExecPSDBIndex(pSCB);
			break;
		case FC_DB_IDX_CREATE:
			ExecPSDBIdxCreate(pSCB);
			break;
		case FC_DB_CHGIDX:
			ExecPSDBChgIdx(pSCB);
			break;
		case FC_DB_SEARCH:
			ExecPSDBSearch(pSCB);
			break;
		case FC_DB_SEARCH2:
			ExecPSDBSearch2(pSCB);
			break;
		case FC_DB_SEARCHLOCK:
			ExecPSDBSearchLock(pSCB);
			break;
		case FC_DB_COUNT:
			ExecPSDBCount(pSCB);
			break;
		case FC_DB_STORE:
			ExecPSDBStore(pSCB);
			break;
		case FC_DB_STOREUNIQ:
			ExecPSDBStoreUniq(pSCB);
			break;
		case FC_DB_UPDATE:
			ExecPSDBUpdate(pSCB);
			break;
		case FC_DB_UPDATEKEY:
			ExecPSDBUpdateKey(pSCB);
			break;
		case FC_DB_DELETE:
			ExecPSDBDelete(pSCB);
			break;
		case FC_DB_RECALL:
			ExecPSDBRecall(pSCB);
			break;
		case FC_DB_DELETE2:
			ExecPSDBDelete2(pSCB);
			break;
		case FC_DB_DELETE3:
			ExecPSDBDelete3(pSCB);
			break;
		case FC_DB_DELETEKEY:
			ExecPSDBDeleteKey(pSCB);
			break;
		case FC_DB_TOP:
			ExecPSDBTop(pSCB);
			break;
		case FC_DB_BOTTOM:
			ExecPSDBBottom(pSCB);
			break;
		case FC_DB_SET:
			ExecPSDBSet(pSCB);
			break;
		case FC_DB_SKIP:
			ExecPSDBSkip(pSCB);
			break;
		case FC_DB_REINDEX:
			ExecPSDBReindex(pSCB);
			break;
		case FC_DB_PACK:
			ExecPSDBPack(pSCB);
			break;
		case FC_DB_READ:
			ExecPSDBRead(pSCB);
			break;
		case FC_DB_READNEXT:
			ExecPSDBReadNext(pSCB);
			break;
		case FC_DB_READBACK:
			ExecPSDBReadBack(pSCB);
			break;
		case FC_DB_READKEY:
			ExecPSDBReadKey(pSCB);
			break;
		case FC_DB_CLRRECORD:
			ExecPSDBClrRecord(pSCB);
			break;
		case FC_DB_GETRECORD:
			ExecPSDBGetRecord(pSCB);
			break;
		case FC_DB_GETFIELD:
			ExecPSDBGetField(pSCB);
			break;
		case FC_DB_SETFIELD:
			ExecPSDBSetField(pSCB);
			break;
		case FC_DB_ADDRECORD:
			ExecPSDBAddRecord(pSCB);
			break;
		case FC_DB_UPDRECORD:
			ExecPSDBUpdRecord(pSCB);
			break;
		case FC_DB_COPY:
			ExecPSDBCopy(pSCB);
			break;
		case FC_DB_CHECK_DELETED:
			ExecPSDBCheckDeleted(pSCB);
			break;
		case FC_DB_SET_FILTER:
			ExecPSDBSetFilter(pSCB);
			break;
		case FC_DB_SET_DELETED:
			ExecPSDBSetDeleted(pSCB);
			break;
		case FC_DB_LRECNO:
			ExecPSDBLRecNo(pSCB);
			break;
		case FC_DB_LRECCOUNT:
			ExecPSDBLRecCount(pSCB);
			break;
		case FC_DB_LSET:
			ExecPSDBLSet(pSCB);
			break;
		case FC_DB_ZIP:
			ExecPSDBZip(pSCB);
			break;
		case FC_DB_CHECK_UPDATE:
			ExecPSDBCheckUpdate(pSCB);
			break;
		case FC_DB_LOCK:
			ExecPSDBLock(pSCB);
			break;
		case FC_DB_UNLOCK:
			ExecPSDBUnlock(pSCB);
			break;
		case FC_DB_RECCOUNT:
			ExecPSDBRecCount(pSCB);
			break;
		case FC_DB_BOF:
			ExecPSDBBof(pSCB);
			break;
		case FC_DB_DBF:
			ExecPSDBDbf(pSCB);
			break;
		case FC_DB_EOF:
			ExecPSDBEof(pSCB);
			break;
		case FC_DB_NFIELD:
			ExecPSDBNField(pSCB);
			break;
		case FC_DB_FIELD:
			ExecPSDBField(pSCB);
			break;
		case FC_DB_NDX:
			ExecPSDBNdx(pSCB);
			break;
		case FC_DB_RECNO:
			ExecPSDBRecNo(pSCB);
			break;
		case FC_DB_RECSIZE:
			ExecPSDBRecSize(pSCB);
			break;
		case FC_DB_SET_READFIELD:
			ExecPSDBSetReadField(pSCB);
			break;
		case FC_DB_SET_SCRAMBLE:
			ExecPSDBSetScramble(pSCB);
			break;
		case FC_DB_UPDATEORACLE:
			ExecPSDBUpdateOracle(pSCB);
			break;
		case FC_DB_DELETEORACLE:
			ExecPSDBDeleteOracle(pSCB);
			break;
		case FC_DB_SET_PASSWORD:
			ExecPSDBSetPassword(pSCB);
			break;
		case FC_DB_UPDATE_EX:
			ExecPSDBUpdateEx(pSCB);
			break;
		case FC_DB_DELETE_EX:
			ExecPSDBDeleteEx(pSCB);
			break;
		case FC_DB_RECALL_EX:
			ExecPSDBDeleteEx(pSCB);
			break;
		case FC_DB_DELETE2_EX:
			ExecPSDBDelete2Ex(pSCB);
			break;
		case FC_DB_LOCK_EX:
			ExecPSDBLockEx(pSCB);
			break;
		case FC_DB_SELECT:
			ExecPSDBSelect(pSCB);
			break;
		case FC_DB_PACKINDEX:
			ExecPSDBPackIndex(pSCB);
			break;
		case FC_DB_READBINARY:
			ExecPSDBReadBinary(pSCB);
			break;
		case FC_DB_WRITEBINARY:
			ExecPSDBWriteBinary(pSCB);
			break;
		case FC_DB_WRITEBINARY_EX:
			ExecPSDBWriteBinaryEx(pSCB);
			break;
		case FC_DB_GETBINARYSIZE:
			ExecPSDBGetBinarySize(pSCB);
			break;
		case FC_RF_OPEN:
			ExecPSRFOpen(pSCB);
			break;
		case FC_RF_CREATE:
			ExecPSRFCreate(pSCB);
			break;
		case FC_RF_CLOSE:
			ExecPSRFClose(pSCB);
			break;
		case FC_RF_READ:
			ExecPSRFRead(pSCB);
			break;
		case FC_RF_WRITE:
			ExecPSRFWrite(pSCB);
			break;
		case FC_RF_SEEK:
			ExecPSRFSeek(pSCB);
			break;
		case FC_RF_LOCK:
			ExecPSRFLock(pSCB);
			break;
		case FC_RF_UNLOCK:
			ExecPSRFUnlock(pSCB);
			break;
		case FC_RF_NEWSIZE:
			ExecPSRFNewSize(pSCB);
			break;
		case FC_RF_WRITE_EX:
			ExecPSRFWriteEx(pSCB);
			break;
		case FC_RF_FILETIME:
			ExecPSRFFileTime(pSCB);
			break;
		case FC_CF_COPY:
			ExecPSCFCopy(pSCB);
			break;
		case FC_CF_DELETE:
			ExecPSCFDelete(pSCB);
			break;
		case FC_CF_FINDFIRST:
			ExecPSCFFindFirst(pSCB);
			break;
		case FC_CF_FINDNEXT:
			ExecPSCFFindNext(pSCB);
			break;
		case FC_CF_RENAME:
			ExecPSCFRename(pSCB);
			break;
		case FC_CF_STAT:
			ExecPSCFStat(pSCB);
			break;
		case FC_CF_CREATEDIRECTORY:
			ExecPSCFCreateDirectory(pSCB);
			break;
		case FC_CF_REMOVEDIRECTORY:
			ExecPSCFRemoveDirectory(pSCB);
			break;
		case FC_SN_CREATE:
			ExecPSSNCreate(pSCB);
			break;
		case FC_SN_GETNO:
			ExecPSSNGetNo(pSCB);
			break;
		case FC_SN_SETNO:
			ExecPSSNSetNo(pSCB);
			break;
		case FC_SN_DELETE:
			ExecPSSNDelete(pSCB);
			break;
		case FC_CL_GETDATE:
			ExecPSCLGetDate(pSCB);
			break;
		case FC_CL_GETCALENDAR:
			ExecPSCLGetCalendar(pSCB);
			break;
		case FC_MS_SEND_REQUEST:
			ExecPSMSSendRequest(pSCB);
			break;
		case FC_MS_SET_MEMBER:
			ExecPSMSSetMember(pSCB);
			break;
		case FC_MS_DELETE_MEMBER:
			ExecPSMSDeleteMember(pSCB);
			break;
		case FC_MS_GET_MEMBER:
			ExecPSMSGetMember(pSCB);
			break;
		case FC_AD_STOP:
			ExecPSADStop(pSCB);
			break;
		case FC_AD_LOGIN:
			ExecPSADLogin(pSCB);
			break;
		case FC_AD_OPENFILE:
			ExecPSADOpenFile(pSCB);
			break;
		case FC_AD_DEBUG:
			ExecPSADDebug(pSCB);
			break;
		case FC_AD_GETSTATISTICS:
			ExecPSADGetStatistics(pSCB);
			break;
		case FC_AD_CLRSTATISTICS:
			ExecPSADClrStatistics(pSCB);
			break;
		case FC_AD_DUPLEXSTATUS:
			ExecPSADDuplexStatus(pSCB);
			break;
		case FC_AD_DUPLEXSYNC:
			ExecPSADDuplexSync(pSCB);
			break;
		case FC_AD_DUPLEXGETINFO:
			ExecPSADDuplexGetInfo(pSCB);
			break;
		case FC_AD_DUPLEXSETINFO:
			ExecPSADDuplexSetInfo(pSCB);
			break;
		case FC_AD_MULTISYNC:
			ExecPSADMultiSync(pSCB);
			break;
		case FC_AD_GETINIDATA:
			ExecPSADGetIniData(pSCB);
			break;
		case FC_AD_SETINIDATA:
			ExecPSADSetIniData(pSCB);
			break;
		case FC_AD_GETLICENSEDATA:
			ExecPSADGetLicenseData(pSCB);
			break;
		case FC_AD_SETLICENSEDATA:
			ExecPSADSetLicenseData(pSCB);
			break;
		case FC_EXEC_COMMAND:
			ExecPSExecCommand(pSCB);
			break;
		case FC_GET_TIME:
			ExecPSGetTime(pSCB);
			break;
		case FC_EXEC_SQL:
			ExecPSExecSQL(pSCB);
			break;
		case FC_GET_SQL:
			ExecPSGetSQL(pSCB);
			break;
		case FC_GET_ASYNC_STATUS:
			ExecPSGetAsyncStatus(pSCB);
			break;
		case FC_GW_CHECK_USER:
			ExecPSGWCheckUser(pSCB);
			break;
		case FC_GW_CHECK_ACCESS:
			ExecPSGWCheckAccess(pSCB);
			break;
		case FC_GW_SET_PASSWORD:
			ExecPSGWSetPassword(pSCB);
			break;
		case FC_GW_CHECK_TERMINAL:
			ExecPSGWCheckTerminal(pSCB);
			break;
		case FC_DUPLEX_KEEPALIVE:
			ExecPSDuplexKeepAlive(pSCB);
			break;
		case FC_DUPLEX_ERROR:
			ExecPSDuplexError(pSCB);
			break;
		default:
			pSCB->response.dataLength = 0;
			pSCB->response.status = ERROR_SERVER_NOSUPPORT;
			break;
		}
	}

	return TRUE;
}

/*=======================================================================
 |
 |		コマンド受信処理
 |
 |	BOOL	ReceiveCommand(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 |		BOOL	返値		TRUE:正常　FALSE:エラー
 |
 =======================================================================*/
BOOL	ReceiveCommand(PSCB pSCB)
{
	for (;;) {
		if (!ReceiveData(pSCB, &pSCB->command, 9 + MIN_PARAM_SIZE))
			return FALSE;

		if (pSCB->command.id & ID_CONTINUE) {
			pSCB->bContinue = TRUE;
			pSCB->command.id &= ~ID_CONTINUE;
		} else
			pSCB->bContinue = FALSE;

		switch (pSCB->command.id) {
		case ID_COMMAND:
		case ID_COMMAND_ASYNC:
		case ID_COMMAND_MASTER:
		case ID_COMMAND_SLAVE:
			break;
		default:
			continue;
		}

		if (pSCB->command.paramLength > MAX_PARAM_SIZE)
			continue;

		if (pSCB->command.paramLength > MIN_PARAM_SIZE) {
			if (!ReceiveData(pSCB, pSCB->command.param + MIN_PARAM_SIZE,
					(USHORT)(pSCB->command.paramLength - MIN_PARAM_SIZE)))
				return FALSE;
		}

		if (pSCB->command.dataLength != 0) {
			if (!ReceiveData(pSCB, pSCB->lpData, pSCB->command.dataLength))
				return FALSE;
		}

		if (pSCB->command.seqNo == pSCB->response.seqNo) {
			if (!SendResponse(pSCB, FALSE))
				return FALSE;
		} else
			break;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		レスポンス送信処理
 |
 |	BOOL	SendResponse(pSCB, bContinue)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |		BOOL	bContinue;	連続データフラグ
 |
 |		BOOL	返値		TRUE:正常　FALSE:エラー
 |
 =======================================================================*/
BOOL	SendResponse(PSCB pSCB, BOOL bContinue)
{
	if (pSCB->command.id == ID_COMMAND_ASYNC) {
		pSCB->asyncStatus = pSCB->response.status;
		return TRUE;
	}

	pSCB->response.id = ID_RESPONSE;
	if (bContinue)
		pSCB->response.id |= ID_CONTINUE;
		
	pSCB->response.seqNo = pSCB->command.seqNo;

	if (pSCB->response.dataLength == 0) {
		if (!SendData(pSCB, &pSCB->response, sizeof(RESPONSE)))
			return FALSE;
	} else {
		if (pSCB->lpData2 != NULL) {
			if (!SendData(pSCB, &pSCB->response, sizeof(RESPONSE)))
				return FALSE;

			if (!SendData(pSCB, pSCB->lpData2, pSCB->response.dataLength)) {
				pSCB->lpData2 = NULL;
				return FALSE;
			}
			pSCB->lpData2 = NULL;
		} else {
			CHAR *lpData = pSCB->lpData - sizeof(RESPONSE);
			memcpy(lpData, &pSCB->response, sizeof(RESPONSE));
			if (!SendData(pSCB, lpData, sizeof(RESPONSE) + pSCB->response.dataLength))
				return FALSE;
		}
	}

	WriteResponseLog(pSCB);

	return TRUE;
}

/*=======================================================================
 |
 |		クライアント接続処理
 |
 |	VOID	ConxxxtClient(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ConxxxtClient(PSCB pSCB)
{
	int	namelen;
	struct	sockaddr_in	name;
	struct	hostent *hp;
	LOGIN	*mp;
	long	ltime;
	struct	tm	*tm;
	LOGDATA	logBuf;
	CHAR	strBuf[9];
	LONG	nRec;
//	char	*p;

	if (pSCB->bConxxxtClient)
		DisconxxxtClient(pSCB);

	pSCB->bConxxxtClient = TRUE;

	switch (pSCB->chType) {
	case CHT_TCPIP:
		namelen = sizeof(name);
		getpeername(pSCB->handle, (struct sockaddr *)&name, &namelen);
		hp = gethostbyaddr((char *)&name.sin_addr.s_addr, 4, AF_INET);
		if (hp != NULL) {
//			if ((p = strchr(hp->h_name, '.')) != NULL)
//				*p = '\0';
			strncpy(pSCB->login.computerName, hp->h_name, 16);
			pSCB->login.computerName[16] = '\0';
		} else {
			strcpy(pSCB->login.computerName, inet_ntoa(*(struct in_addr *)&name.sin_addr.s_addr));
		}

		pSCB->login.chType[0] = '1';
		break;
	case CHT_NETBIOS:
#if defined (OS_WNT)
		if (pSCB->login.computerName[0] == '\0') {
			if (!GetNamedPipeHandleState((HANDLE)pSCB->handle,
					NULL, NULL,
					NULL, NULL, pSCB->login.computerName,
					sizeof(pSCB->login.computerName)))
				strcpy(pSCB->login.computerName, "???");
		}
#endif
		pSCB->login.chType[0] = '2';
		break;
	case CHT_RS232C:
		pSCB->login.chType[0] = '3';
		if (pSCB->login.computerName[0] == '\0') {
			sprintf(pSCB->login.computerName, "COM%d-%d",
					pSCB->rsp->portNo + 1, pSCB->handle);
		}
			break;
	}

	time(&ltime);
	tm = localtime(&ltime);
	pSCB->login.loginDate.year = (USHORT)tm->tm_year;
	pSCB->login.loginDate.month = (CHAR)(tm->tm_mon + 1);
	pSCB->login.loginDate.day = (CHAR)tm->tm_mday;
	pSCB->login.loginTime.hour = (CHAR)tm->tm_hour;
	pSCB->login.loginTime.minutes = (CHAR)tm->tm_min;
	pSCB->login.loginTime.seconds = (CHAR)tm->tm_sec;
	pSCB->login.usedTime = ltime;

	mp = &pSCB->login;
	if (strcmp(mp->clientID, MULTI_SERVER_CLIENTID) == 0)
		pSCB->bMultiPertner = TRUE;

	/* 接続履歴ファイルに書き込み */
	if (pLogDB == NULL)
		return;

	if (!ini.bNoHideLog && mp->clientID[0] == '.')
		return;

	CopyToDBF(logBuf.clientID, mp->clientID, sizeof(logBuf.clientID));
	CopyToDBF(logBuf.computerName, mp->computerName, sizeof(logBuf.computerName));
	logBuf.chType[0] = mp->chType[0];
	sprintf(strBuf, "%2u/%02u/%02u", mp->loginDate.year % 100,
			mp->loginDate.month, mp->loginDate.day);
	memcpy(logBuf.loginDate, strBuf, 8);
	sprintf(strBuf, "%2u:%02u:%02u", mp->loginTime.hour,
			mp->loginTime.minutes, mp->loginTime.seconds);
	memcpy(logBuf.loginTime, strBuf, 8);
	memset(logBuf.logoutTime, '*', 8);
	memset(logBuf.usedTime, '*', 8);
	memset(logBuf.reserved, ' ', 12);

	ENTER_CRITICAL_SECTION(&csLogin);
	DBRecCount(pLogDB, &nRec);
	if (nRec < MAX_LOG) {
		DBStore(pLogDB, (CHAR *)&logBuf);
		mp->clientNo = (SHORT)nRec + 1;
	} else {
		if (++logLastRec > MAX_LOG)
			logLastRec = 1;
		DBSet(pLogDB, logLastRec);
		DBUpdate(pLogDB, (CHAR *)&logBuf);

		DBTop(pLogDB);
		DBRead(pLogDB, (CHAR *)&logBuf);
		sprintf(strBuf, "%4d", logLastRec);
		memcpy(logBuf.reserved, strBuf, 4);
		DBUpdate(pLogDB, (CHAR *)&logBuf);
		mp->clientNo = logLastRec;
	}
	DBFlush(pLogDB);

	LEAVE_CRITICAL_SECTION(&csLogin);
}

/*=======================================================================
 |
 |		クライアント切断処理
 |
 |	VOID	DisconxxxtClient(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	DisconxxxtClient(PSCB pSCB)
{
	LOGIN	*mp;
	LOGDATA	logBuf;
	long	ltime;
	struct	tm	*tm;
	CHAR	strBuf[9];

	if (!pSCB->bConxxxtClient)
		return;

	mp = &pSCB->login;

	if (pLogDB != NULL && mp->clientNo != 0) {
		time(&ltime);
		tm = localtime(&ltime);
		pSCB->login.logoutTime.hour = (CHAR)tm->tm_hour;
		pSCB->login.logoutTime.minutes = (CHAR)tm->tm_min;
		pSCB->login.logoutTime.seconds = (CHAR)tm->tm_sec;
		pSCB->login.usedTime = ltime - pSCB->login.usedTime;

		CopyToDBF(logBuf.clientID, mp->clientID,
						sizeof(logBuf.clientID));
		CopyToDBF(logBuf.computerName, mp->computerName,
					sizeof(logBuf.computerName));
		logBuf.chType[0] = mp->chType[0];
		sprintf(strBuf, "%2u/%02u/%02u", mp->loginDate.year % 100,
				mp->loginDate.month, mp->loginDate.day);
		memcpy(logBuf.loginDate, strBuf, 8);
		sprintf(strBuf, "%2u:%02u:%02u", mp->loginTime.hour,
				mp->loginTime.minutes, mp->loginTime.seconds);
		memcpy(logBuf.loginTime, strBuf, 8);

		ENTER_CRITICAL_SECTION(&csLogin);
		DBSet(pLogDB, mp->clientNo);
		DBRead(pLogDB, (CHAR *)&logBuf);

		/* 接続履歴ファイル更新 */
		sprintf(strBuf, "%2u:%02u:%02u",
				mp->logoutTime.hour,
				mp->logoutTime.minutes,
				mp->logoutTime.seconds);
		memcpy(logBuf.logoutTime, strBuf, 8);
		sprintf(strBuf, "%2u:%02u:%02u",
				(SHORT)(mp->usedTime / 3600),
				(SHORT)(mp->usedTime / 60 % 60),
				(SHORT)(mp->usedTime % 60));
		memcpy(logBuf.usedTime, strBuf, 8);
		DBUpdate(pLogDB, (CHAR *)&logBuf);
		DBFlush(pLogDB);

		LEAVE_CRITICAL_SECTION(&csLogin);
	}

	FileCloseAll(pSCB);

	pSCB->bConxxxtClient = FALSE;
	memset(mp, 0, sizeof(LOGIN));
}

/*=======================================================================
 |
 |		FCB アロケート処理
 |
 |	int	AllocFCB(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
int	AllocFCB(PSCB pSCB)
{
	int	i;
	PFCB	pFCB;
	int	nFCB;

	for (i = 0, pFCB = pSCB->pFCB; i < pSCB->nFCB; i++, pFCB++) {
		if (pFCB->fileType == FILETYPE_NULL) {
			if (pFCB->pMFCB != NULL)
				free(pFCB->pMFCB);

			if (pFCB->pFileName != NULL)
				free(pFCB->pFileName);

			memset(pFCB, 0, sizeof(FCB));
			return i;
		}
	}

	nFCB = pSCB->nFCB + 10;

	if ((pFCB = malloc(sizeof(FCB) * nFCB)) == NULL)
		return -1;

	memset(pFCB, 0, sizeof(FCB) * nFCB);

	if (pSCB->pFCB != NULL) {
		memcpy(pFCB, pSCB->pFCB, sizeof(FCB) * pSCB->nFCB);
		free(pSCB->pFCB);
	}

	pSCB->pFCB = pFCB;
	pSCB->nFCB = nFCB;

	return i;
}

/*=======================================================================
 |
 |		FCB ポインタ取得
 |
 |	FCB	GetFCB(pSCB, nFCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |		int	nFCB;		FCB 番号
 |
 =======================================================================*/
PFCB	GetFCB(PSCB pSCB, int nFCB)
{
	if (nFCB < pSCB->nFCB)
		return &pSCB->pFCB[nFCB];
	else
		return NULL;
}

/*=======================================================================
 |
 |		全ファイルクローズ処理
 |
 |	VOID	FileCloseAll(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
static	VOID	FileCloseAll(PSCB pSCB)
{
	SHORT	i;
	PFCB	pFCB;

	pFCB = pSCB->pFCB;
	if (pFCB != NULL) {
		for (i = 0 ; i < pSCB->nFCB; i++, pFCB++) {
			switch (pFCB->fileType) {
			case FILETYPE_DB:
				DBClose(pFCB->dbp);
				break;
			case FILETYPE_RF:
				RFClose(pFCB);
				break;
			}

			if (pFCB->pMFCB != NULL)
				free(pFCB->pMFCB);
		}

		free(pSCB->pFCB);
		pSCB->pFCB = NULL;
	}

#if defined (OS_WNT)
	if (pSCB->hdir != -1) {
		FindClose((HANDLE)pSCB->hdir);
		pSCB->hdir = -1;
	}
#endif

	CloseMultiServer(pSCB);
}

VOID	OpenLogFile(VOID)
{
	char	fileName1[128];
	char	fileName2[128];
	int	i;

	if (ini.serverLog == 0)
		return;

	if (access("_DEBUG00.LOG", 0) == 0) {
		remove("_DEBUG05.LOG");
		for (i = 4; i >= 0; i--) {
			sprintf(fileName1, "_DEBUG%02d.LOG", i);
			sprintf(fileName2, "_DEBUG%02d.LOG", i+1);
			rename(fileName1, fileName2);
		}
	}

	logFileSize = 0;

	fdLog = __CREAT_LOCAL("_DEBUG00.LOG");

	if (ini.serverLog == 2)
		CloseLogFile();
}

VOID	CloseLogFile(VOID)
{
	if (fdLog != -1) {
		__CLOSE(fdLog);
		fdLog = -1;
	}
}

static	VOID	WriteCommandLog(PSCB pSCB)
{
	long	ltime;
	struct	tm	*tm;
	USHORT	startTime[6];
#if defined (OS_WNT)
	DWORD	numBytes;
#endif

	if (ini.serverLog == 0)
		return;

	ENTER_CRITICAL_SECTION(&csLogFile);

	if (ini.serverLog == 2) {
		if ((fdLog = __OPEN("_DEBUG00.LOG")) != -1)
			__LSEEK(fdLog, 0, SEEK_END);
	}

	if (fdLog != -1) {
		CheckLogFileSize();

		time(&ltime);
		tm = localtime(&ltime);
		startTime[0] = (USHORT)tm->tm_year;
		startTime[1] = (USHORT)(tm->tm_mon + 1);
		startTime[2] = (USHORT)tm->tm_mday;
		startTime[3] = (USHORT)tm->tm_hour;
		startTime[4] = (USHORT)tm->tm_min;
		startTime[5] = (USHORT)tm->tm_sec;
		__WRITE_LOCAL(fdLog, startTime, sizeof(startTime));

		__WRITE_LOCAL(fdLog, &pSCB->login, sizeof(LOGIN));

		__WRITE_LOCAL(fdLog, &pSCB->command,
				sizeof(COMMAND) - MAX_PARAM_SIZE +
				pSCB->command.paramLength);

		logFileSize += sizeof(startTime) + sizeof(LOGIN) +
						pSCB->command.paramLength;
	}

	if (ini.serverLog == 2)
		CloseLogFile();

	LEAVE_CRITICAL_SECTION(&csLogFile);
}

static	VOID	WriteResponseLog(PSCB pSCB)
{
	long	ltime;
	struct	tm	*tm;
	USHORT	startTime[6];
#if defined (OS_WNT)
	DWORD	numBytes;
#endif

	if (ini.serverLog == 0)
		return;

	ENTER_CRITICAL_SECTION(&csLogFile);

	if (ini.serverLog == 2) {
		if ((fdLog = __OPEN("_DEBUG00.LOG")) != -1)
			__LSEEK(fdLog, 0, SEEK_END);
	}

	if (fdLog != -1) {
		CheckLogFileSize();

		time(&ltime);
		tm = localtime(&ltime);
		startTime[0] = (USHORT)tm->tm_year;
		startTime[1] = (USHORT)(tm->tm_mon + 1);
		startTime[2] = (USHORT)tm->tm_mday;
		startTime[3] = (USHORT)tm->tm_hour;
		startTime[4] = (USHORT)tm->tm_min;
		startTime[5] = (USHORT)tm->tm_sec;
		__WRITE_LOCAL(fdLog, startTime, sizeof(startTime));

		__WRITE_LOCAL(fdLog, &pSCB->login, sizeof(LOGIN));

		__WRITE_LOCAL(fdLog, &pSCB->response, sizeof(RESPONSE));

		logFileSize += sizeof(startTime) + sizeof(LOGIN) +
							sizeof(RESPONSE);
	}

	if (ini.serverLog == 2)
		CloseLogFile();

	LEAVE_CRITICAL_SECTION(&csLogFile);
}

static	VOID	CheckLogFileSize(VOID)
{
	if (logFileSize > ini.maxLogFileSize) {
		CloseLogFile();
/*		remove("_DEBUG00.LOG");*/
		OpenLogFile();
	}
}
