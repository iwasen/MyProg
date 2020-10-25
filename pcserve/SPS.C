/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: コマンド実行処理
 *		ファイル名	: sps.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "pcserve.h"

/*=======================================================================
 |
 |		接続コマンド処理
 |
 |	VOID	ExecPSConxxxt(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSConxxxt(PSCB pSCB)
{
	struct	PRM_CONxxxT	*pParam;

	pParam = (struct PRM_CONxxxT *)pSCB->command.param;
	pParam->clientID[sizeof(pParam->clientID) - 1] = '\0';

	if (bLicense || strcmp(pParam->clientID, "PCSADMIN") == 0) {
		strcpy(pSCB->login.clientID, pParam->clientID);
		if (pSCB->command.paramLength == sizeof(struct PRM_CONxxxT))
			strcpy(pSCB->login.computerName, pParam->computerName);

		ConxxxtClient(pSCB);

		pSCB->response.status = 0;
	} else {
		pSCB->response.status = ERROR_SERVER_LICENSE;
		pSCB->bCloseConxxxtion = TRUE;
	}

	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		切断コマンド処理
 |
 |	VOID	ExecPSDisconxxxt(PSCB pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSDisconxxxt(PSCB pSCB)
{
	DisconxxxtClient(pSCB);
	pSCB->bCloseConxxxtion = TRUE;

	pSCB->response.dataLength = 0;
	pSCB->response.status = NORMAL;
}

/*=======================================================================
 |
 |		ログイン処理
 |
 |	VOID	ExecPSLogin(PSCB pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSLogin(PSCB pSCB)
{
	struct	PRM_LOGIN	*pParam;
	SHORT result;
	SHORT err;

	pParam = (struct PRM_LOGIN *)pSCB->command.param;
	pParam->userID[sizeof(pParam->userID) - 1] = '\0';
	pParam->password[sizeof(pParam->password) - 1] = '\0';

	if ((err = _CheckUser(pParam->userID, pParam->password, &result)) != 0)
		pSCB->response.status = err;
	else if (result != 1)
		pSCB->response.status = ERROR_USER_PASSWORD;
	else {
		pSCB->bLoginUser = TRUE;
		strcpy(pSCB->userID, pParam->userID);
		pSCB->response.status = NORMAL;
	}

	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		サーバコマンド実行処理
 |
 |	VOID	ExecPSExecCommand(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSExecCommand(PSCB pSCB)
{
	struct	PRM_EXEC_COMMAND	*pParam;

	pParam = (struct PRM_EXEC_COMMAND *)pSCB->command.param;

#if defined OS_WINDOWS
	if (WinExec(pParam->command, SW_SHOW) >= 32)
		pSCB->response.status = 0;
	else
		pSCB->response.status = ERROR_EXEC_COMMAND;
#else
	if (system(pParam->command) == 0)
		pSCB->response.status = 0;
	else
		pSCB->response.status = ERROR_EXEC_COMMAND;
#endif

	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		サーバ時刻取得処理
 |
 |	VOID	ExecPSGetTime(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSGetTime(PSCB pSCB)
{
	long	ltime;
	struct	tm	*tp;
	PSTIME	pSTime;

	time(&ltime);
	tp = localtime(&ltime);

	pSTime = (PSTIME)pSCB->lpData;
	pSTime->year = tp->tm_year + 1900;
	pSTime->month = tp->tm_mon + 1;
	pSTime->day = tp->tm_mday;
	pSTime->hour = tp->tm_hour;
	pSTime->minute = tp->tm_min;
	pSTime->second = tp->tm_sec;
	pSTime->week = tp->tm_wday;

	pSCB->response.dataLength = sizeof(STIME);
	pSCB->response.status = 0;
}

/*=======================================================================
 |
 |		非同期コマンド実行結果取得
 |
 |	VOID	ExecPSGetAsyncStatus(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSGetAsyncStatus(PSCB pSCB)
{
	pSCB->response.dataLength = 0;
	pSCB->response.ret.sValue = pSCB->asyncStatus;
	pSCB->response.status = 0;
	pSCB->asyncStatus = 0;
}

/*=======================================================================
 |
 |		ＳＱＬ実行処理
 |
 |	VOID	ExecPSExecSQL(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSExecSQL(PSCB pSCB)
{
	pSCB->response.status = ERROR_SERVER_NOSUPPORT;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		ＳＱＬ実行結果取得
 |
 |	VOID	ExecPSGetSQL(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSGetSQL(PSCB pSCB)
{
	pSCB->response.status = ERROR_SERVER_NOSUPPORT;
	pSCB->response.dataLength = 0;
}
