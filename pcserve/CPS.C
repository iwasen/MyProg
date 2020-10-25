/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: クライアント側接続／切断処理
 *		ファイル名	: cps.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "pcsclnt.h"
#include "pcswin32res.h"

/*=======================================================================
 |
 |		接続コマンド処理
 |
 |	PCCB	PSConxxxt(chType, hostName, clientID, err)
 |
 |		SHORT	chType;		チャネル種別
 |		CHAR	*hostName;	ホスト名
 |		CHAR	*clientID;	クライアントＩＤ
 |		SHORT	*err;		エラーコード
 |
 |		PCCB	返値		クライアント制御ブロックポインタ
 |
 =======================================================================*/
PCCB	PSENTRY PSConxxxt(SHORT chType, CHAR *hostName,
						CHAR *clientID, SHORT *err)
{
	PCCB	pCCB;
	struct	PRM_CONxxxT	*pParam;

	if (strlen(clientID) >= sizeof(pParam->clientID)) {
		*err = ERROR_CLIENT_PARAMETER;
		return NULL;
	}

	if ((pCCB = malloc(sizeof(CCB))) == NULL) {
		*err = ERROR_CLIENT_MEMORY;
		return NULL;
	}
	memset(pCCB, 0, sizeof(CCB));
	pCCB->handle = -1;
	if (hostName != NULL)
		strcpy(pCCB->serverName, hostName);

#ifdef	OS_WNT
	pCCB->hdir = INVALID_HANDLE_VALUE;
#endif

	pCCB->chType = chType;

	if (chType != CHT_LOCAL) {
		if ((*err = NetConxxxt(pCCB, hostName)) != 0) {
			NetDisconxxxt(pCCB);
			return NULL;
		}

		pParam = (struct PRM_CONxxxT *)pCCB->command.param;
		strcpy(pParam->clientID, clientID);
		strcpy(pParam->computerName, "");

		pCCB->id = ID_CCB;

		if ((*err = SendCommand(pCCB, FC_CONxxxT,
					sizeof(struct PRM_CONxxxT), 0)) != 0) {
			NetDisconxxxt(pCCB);
			pCCB->id = 0;
			free(pCCB);
			return NULL;
		}
	}

	*err = 0;
	return pCCB;
}

/*=======================================================================
 |
 |		切断コマンド処理
 |
 |	SHORT	PSDisconxxxt(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSDisconxxxt(PCCB pCCB)
{
	PFCB	pFCB;
	SHORT	async;
	SHORT	err;

	if (pCCB == NULL)
		return 0;

	async = pCCB->async;

	while ((pFCB = pCCB->pFCB) != NULL) {
		switch (pFCB->fileType) {
		case FILETYPE_DB:
			PSDBClose(pFCB);
			break;
		case FILETYPE_RF:
			PSRFClose(pFCB);
			break;
		}
		pCCB->async = async;
	}

	if (pCCB->chType != CHT_LOCAL) {
		err = SendCommand(pCCB, FC_DISCONxxxT, 0, 0);
		NetDisconxxxt(pCCB);
	} else {
#ifdef	OS_WNT
		if (pCCB->hdir != INVALID_HANDLE_VALUE)
			FindClose(pCCB->hdir);
#endif
		err = 0;
	}

	pCCB->id = 0;

	free(pCCB);

	return err;
}

/*=======================================================================
 |
 |		ログインコマンド処理
 |
 |	SHORT	PSLogin(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSLogin(PCCB pCCB, CHAR *pUserID, CHAR *pPassword)
{
	struct	PRM_LOGIN	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pParam = (struct PRM_LOGIN *)pCCB->command.param;
		strcpy(pParam->userID, pUserID);
		strcpy(pParam->password, pPassword);
		err = SendCommand(pCCB, FC_LOGIN, sizeof(struct PRM_LOGIN), 0);
	}

	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		エラーコード取り出しコマンド処理
 |
 |	SHORT	PSGetErrCode(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSGetErrCode(PCCB pCCB)
{
	SHORT	err;

	err = pCCB->errorCode;
	pCCB->errorCode = 0;

	return err;
}

/*=======================================================================
 |
 |		サーバコマンド実行処理
 |
 |	SHORT	PSExecCommand(pCCB, command)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*command;	実行コマンド
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSExecCommand(PCCB pCCB, CHAR *command)
{
	struct	PRM_EXEC_COMMAND	*pParam;
	SHORT	err;

	if (strlen(command) >= sizeof(pParam->command)) {
		err = ERROR_CLIENT_PARAMETER;
		goto err;
	}

	if (pCCB->chType == CHT_LOCAL) {
#if	defined OS_WINDOWS || defined OS_WNT
		if (WinExec(command, SW_SHOWNORMAL) >= 32)
			err = 0;
		else
			err = ERROR_EXEC_COMMAND;
#else
		if (system(command) == 0)
			err = 0;
		else
			err = ERROR_EXEC_COMMAND;
#endif
		err = 0;
	} else {
		pParam = (struct PRM_EXEC_COMMAND *)pCCB->command.param;
		strcpy(pParam->command, command);
		err = SendCommand(pCCB, FC_EXEC_COMMAND,
					sizeof(struct PRM_EXEC_COMMAND), 0);
	}
err:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		サーバ時刻取得処理
 |
 |	SHORT	PSGetTime(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY PSGetTime(PCCB pCCB, PSTIME pSTime)
{
	long	ltime;
	struct	tm	*tp;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		time(&ltime);
		tp = localtime(&ltime);

		pSTime->year = tp->tm_year + 1900;
		pSTime->month = tp->tm_mon + 1;
		pSTime->day = tp->tm_mday;
		pSTime->hour = tp->tm_hour;
		pSTime->minute = tp->tm_min;
		pSTime->second = tp->tm_sec;
		pSTime->week = tp->tm_wday;
		err = 0;
	} else {
		pCCB->pOutData = pSTime;
		err = SendCommand(pCCB, FC_GET_TIME, 0, 0);
	}

	if (err != 0) {
		pCCB->errorCode = err;
	}

	return err;
}

/*=======================================================================
 |
 |		TCP/IP ポート番号設定
 |
 |	VOID	PSSetTCPIPPort(portNo)
 |
 |		SHORT	portNo;		ポート番号
 |
 =======================================================================*/
VOID	PSENTRY PSSetTCPIPPort(SHORT portNo)
{
	tcpipPort = portNo;
}

/*=======================================================================
 |
 |		ＳＱＬ実行処理
 |
 |	SHORT	PSExecSQL(pCCB, SQLCommand)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*SQLCommand;	ＳＱＬコマンド
 |
 |		SHORT	返値		レコード長
 |
 =======================================================================*/
SHORT	PSENTRY PSExecSQL(PCCB pCCB, CHAR *SQLCommand)
{
	SHORT	err;
	SHORT	lRec = 0;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pCCB->pInData = SQLCommand;
		err = SendCommand(pCCB, FC_EXEC_SQL, 0,
						strlen(SQLCommand) + 1);
		lRec = pCCB->response.ret.sValue;
	}

	if (err != 0) {
		pCCB->errorCode = err;
	}

	return lRec;
}

/*=======================================================================
 |
 |		ＳＱＬ実行結果取得処理
 |
 |	SHORT	PSGetSQL(pCCB, nRec, buffer)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		SHORT	nRec;		読み込みレコード数
 |		CHAR	*buffer;	読み込みバッファ
 |
 |		SHORT	返値		実際に読み込んだレコード数
 |
 =======================================================================*/
SHORT	PSENTRY PSGetSQL(PCCB pCCB, SHORT nRec, CHAR *buffer)
{
	struct	PRM_GET_SQL	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
		nRec = 0;
	} else {
		pParam = (struct PRM_GET_SQL *)pCCB->command.param;
		pParam->nRec = nRec;
		pCCB->pOutData = buffer;
		err = SendCommand(pCCB, FC_GET_SQL,
					sizeof(struct PRM_GET_SQL), 0);
		nRec = pCCB->response.ret.sValue;
	}

	if (err != 0) {
		pCCB->errorCode = err;
	}

	return nRec;
}

/*=======================================================================
 |
 |		非同期コマンド設定
 |
 |	VOID	PSSetAsyncMode(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 =======================================================================*/
VOID	PSENTRY	PSSetAsyncMode(PCCB pCCB)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pCCB->async = 1;
		err = 0;
	}

	if (err != 0) {
		pCCB->errorCode = err;
	}
}

/*=======================================================================
 |
 |		非同期コマンド実行結果取得
 |
 |	SHORT	PSGetAsyncStatus(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 |		SHORT	返値		直前の非同期コマンドのエラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSGetAsyncStatus(PCCB pCCB)
{
	SHORT	status;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
		status = 0;
	} else {
		err = SendCommand(pCCB, FC_GET_ASYNC_STATUS, 0, 0);
		status = pCCB->response.ret.sValue;
	}

	if (err != 0) {
		pCCB->errorCode = err;
	}

	return status;
}

/*=======================================================================
 |
 |		エラーテキスト取得処理
 |
 |	char	*PSGetErrorText(nErrorCode)
 |
 |		SHORT	nErrorCode;	エラーコード
 |
 |		char	*返値		エラーテキスト
 |
 =======================================================================*/
char *PSENTRY PSGetErrorText(SHORT nErrorCode)
{
	struct ERROR_TBL {
		int errorCode;
		UINT strID;
	} errorTbl[] = {
		{NORMAL					, IDS_NORMAL},
		{ERROR_SERVER			, IDS_ERROR_SERVER},
		{ERROR_SERVER_MEMORY	, IDS_ERROR_SERVER_MEMORY},
		{ERROR_SERVER_MAXFILES	, IDS_ERROR_SERVER_MAXFILES},
		{ERROR_SERVER_SEND		, IDS_ERROR_SERVER_SEND},
		{ERROR_SERVER_RECEIVE	, IDS_ERROR_SERVER_RECEIVE},
		{ERROR_SERVER_NOSUPPORT	, IDS_ERROR_SERVER_NOSUPPORT},
		{ERROR_SERVER_MAXCONxxxT, IDS_ERROR_SERVER_MAXCONxxxT},
		{ERROR_SERVER_CONxxxT	, IDS_ERROR_SERVER_CONxxxT},
		{ERROR_SERVER_DUPLEX	, IDS_ERROR_SERVER_DUPLEX},
		{ERROR_SERVER_MULTI		, IDS_ERROR_SERVER_MULTI},
		{ERROR_SERVER_LICENSE	, IDS_ERROR_SERVER_LICENSE},

		{ERROR_CLIENT_MEMORY	, IDS_ERROR_CLIENT_MEMORY},
		{ERROR_CLIENT_SEND		, IDS_ERROR_CLIENT_SEND},
		{ERROR_CLIENT_RECEIVE	, IDS_ERROR_CLIENT_RECEIVE},
		{ERROR_CLIENT_NOHOST	, IDS_ERROR_CLIENT_NOHOST},
		{ERROR_CLIENT_SOCKET	, IDS_ERROR_CLIENT_SOCKET},
		{ERROR_CLIENT_CONxxxT	, IDS_ERROR_CLIENT_CONxxxT},
		{ERROR_CLIENT_NOSUPPORT	, IDS_ERROR_CLIENT_NOSUPPORT},
		{ERROR_CLIENT_PARAMETER	, IDS_ERROR_CLIENT_PARAMETER},
		{ERROR_CLIENT_BUSY		, IDS_ERROR_CLIENT_BUSY},
		{ERROR_CLIENT_NOCARRIER	, IDS_ERROR_CLIENT_NOCARRIER},
		{ERROR_CLIENT_MODEM		, IDS_ERROR_CLIENT_MODEM},
		{ERROR_CLIENT_CCB		, IDS_ERROR_CLIENT_CCB},
		{ERROR_CLIENT_MAXCONxxxT, IDS_ERROR_CLIENT_MAXCONxxxT},
		{ERROR_CLIENT_WINSOCK	, IDS_ERROR_CLIENT_WINSOCK},

		{ERROR_DB_MEMORY		, IDS_ERROR_DB_MEMORY},
		{ERROR_DB_FOPEN			, IDS_ERROR_DB_FOPEN},
		{ERROR_DB_NOFILE		, IDS_ERROR_DB_NOFILE},
		{ERROR_DB_IDXOVER		, IDS_ERROR_DB_IDXOVER},
		{ERROR_DB_TMPFILE		, IDS_ERROR_DB_TMPFILE},
		{ERROR_DB_NOKEY			, IDS_ERROR_DB_NOKEY},
		{ERROR_DB_DBLKEY		, IDS_ERROR_DB_DBLKEY},
		{ERROR_DB_RECNO			, IDS_ERROR_DB_RECNO},
		{ERROR_DB_EOF			, IDS_ERROR_DB_EOF},
		{ERROR_DB_STRFILE		, IDS_ERROR_DB_STRFILE},
		{ERROR_DB_READ			, IDS_ERROR_DB_READ},
		{ERROR_DB_WRITE			, IDS_ERROR_DB_WRITE},
		{ERROR_DB_IDXKEY		, IDS_ERROR_DB_IDXKEY},
		{ERROR_DB_NOINDEX		, IDS_ERROR_DB_NOINDEX},
		{ERROR_DB_ARDYOPEN		, IDS_ERROR_DB_ARDYOPEN},
		{ERROR_DB_LOCK			, IDS_ERROR_DB_LOCK},
		{ERROR_DB_NOSUPPORT		, IDS_ERROR_DB_NOSUPPORT},
		{ERROR_DB_PARAMETER		, IDS_ERROR_DB_PARAMETER},
		{ERROR_DB_TIMEOUT		, IDS_ERROR_DB_TIMEOUT},
		{ERROR_DB_SIZEOVER		, IDS_ERROR_DB_SIZEOVER},
		{ERROR_DB_NOOPEN		, IDS_ERROR_DB_NOOPEN},
		{ERROR_DB_PASSWORD		, IDS_ERROR_DB_PASSWORD},
		{ERROR_DB_INDEXFILE		, IDS_ERROR_DB_INDEXFILE},
		{ERROR_DB_FIELD			, IDS_ERROR_DB_FIELD},
		{ERROR_DB_BUFSIZE		, IDS_ERROR_DB_BUFSIZE},

		{ERROR_SN_PARAMETER		, IDS_ERROR_SN_PARAMETER},
		{ERROR_SN_EXIST			, IDS_ERROR_SN_EXIST},
		{ERROR_SN_NOTFOUND		, IDS_ERROR_SN_NOTFOUND},

		{ERROR_CL_PARAMETER		, IDS_ERROR_CL_PARAMETER},
		{ERROR_CL_NOTFOUND		, IDS_ERROR_CL_NOTFOUND},

		{ERROR_MS_PARAMETER		, IDS_ERROR_MS_PARAMETER},
		{ERROR_MS_GRPFILE		, IDS_ERROR_MS_GRPFILE},
		{ERROR_MS_BUFSIZE		, IDS_ERROR_MS_BUFSIZE},

		{ERROR_EXEC_COMMAND		, IDS_ERROR_EXEC_COMMAND},
		{ERROR_DIRNAME			, IDS_ERROR_DIRNAME},
		{ERROR_FILE_TYPE		, IDS_ERROR_FILE_TYPE},
		{ERROR_FILE_HANDLE		, IDS_ERROR_FILE_HANDLE},
		{ERROR_FILE_PERMISSION	, IDS_ERROR_FILE_PERMISSION},
		{ERROR_USER_PASSWORD	, IDS_ERROR_USER_PASSWORD},
		{ERROR_NOLOGIN			, IDS_ERROR_NOLOGIN}
	};
	int i;
	static char errMsg[128];
	extern HINSTANCE hInst;

	for (i = 0; i < sizeof(errorTbl) / sizeof(struct ERROR_TBL); i++) {
		if (nErrorCode == errorTbl[i].errorCode) {
			LoadString(hInst, errorTbl[i].strID, errMsg, sizeof(errMsg));
			return errMsg;
		}
	}

	LoadString(hInst, IDS_ERROR_UNKNOWN, errMsg, sizeof(errMsg));
	return errMsg;
}
