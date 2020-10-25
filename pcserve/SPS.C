/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �R�}���h���s����
 *		�t�@�C����	: sps.c
 *		�쐬��		: s.aizawa
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
 |		�ڑ��R�}���h����
 |
 |	VOID	ExecPSConxxxt(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		�ؒf�R�}���h����
 |
 |	VOID	ExecPSDisconxxxt(PSCB pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		���O�C������
 |
 |	VOID	ExecPSLogin(PSCB pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		�T�[�o�R�}���h���s����
 |
 |	VOID	ExecPSExecCommand(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		�T�[�o�����擾����
 |
 |	VOID	ExecPSGetTime(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		�񓯊��R�}���h���s���ʎ擾
 |
 |	VOID	ExecPSGetAsyncStatus(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		�r�p�k���s����
 |
 |	VOID	ExecPSExecSQL(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSExecSQL(PSCB pSCB)
{
	pSCB->response.status = ERROR_SERVER_NOSUPPORT;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		�r�p�k���s���ʎ擾
 |
 |	VOID	ExecPSGetSQL(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSGetSQL(PSCB pSCB)
{
	pSCB->response.status = ERROR_SERVER_NOSUPPORT;
	pSCB->response.dataLength = 0;
}