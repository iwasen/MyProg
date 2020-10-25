/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �T�[�o���T�[�o�Ǘ��֐�
 *		�t�@�C����	: sad.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "pcserve.h"
#include "pcsio.h"
#include "pcscheck.h"

/*=======================================================================
 |
 |		PSADStop() �֐����s����
 |
 |	VOID	ExecPSADStop(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADStop(PSCB pSCB)
{
	active = FALSE;

#ifdef	OS_WNT
{
	extern	HANDLE	hServDoneEvent;
	SetEvent(hServDoneEvent);
}
#endif

#ifdef	OS_WINDOWS
{
	extern	HWND	hWndPCSERVE;
	PostMessage(hWndPCSERVE, WM_CLOSE, 0, 0L);
}
#endif

	pSCB->response.dataLength = 0;
	pSCB->response.status = 0;
}

/*=======================================================================
 |
 |		PSADLogin() �֐����s����
 |
 |	VOID	ExecPSADLogin(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADLogin(PSCB pSCB)
{
	PSCB	pSCB2;
	LOGIN	*p;
	SHORT	n;
	long	ltime;

	ENTER_CRITICAL_SECTION(&csSCB);

	time(&ltime);
	p = (LOGIN *)pSCB->lpData;
	n = 0;
	for (pSCB2 = pTopSCB; pSCB2 != NULL; pSCB2 = pSCB2->chainSCB) {
		if ((ini.bNoHideLog || pSCB2->login.clientID[0] != '.') &&
				pSCB2->login.clientID[0] != '\0') {
			*p = pSCB2->login;
			p->usedTime = ltime - pSCB2->login.usedTime;
			p++;
			n++;
		}
	}

	LEAVE_CRITICAL_SECTION(&csSCB);

	pSCB->response.dataLength = n * sizeof(LOGIN);
	pSCB->response.ret.sValue = n;
	pSCB->response.status = 0;
}

/*=======================================================================
 |
 |		PSADOpenFile() �֐����s����
 |
 |	VOID	ExecPSADOpenFile(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADOpenFile(PSCB pSCB)
{
	struct	PRM_AD_OPENFILE	*pParam;
	PSCB	pSCB2;
	PFCB	pFCB;
	PDB	dbp;
	PIDX	ip;
	int	i, j;
	CHAR	*p;
	SHORT	n;

	pParam = (struct PRM_AD_OPENFILE *)pSCB->command.param;

	ENTER_CRITICAL_SECTION(&csSCB);

	for (pSCB2 = pTopSCB; pSCB2 != NULL; pSCB2 = pSCB2->chainSCB) {
		if (pSCB2->login.clientNo == pParam->clientNo)
			break;
	}

	p = pSCB->lpData;
	n = 0;
	if (pSCB2 != NULL) {
		for (i = 0, pFCB = pSCB2->pFCB; i < pSCB2->nFCB; i++, pFCB++) {
			if (pFCB == NULL)
				break;
			switch (pFCB->fileType) {
			case FILETYPE_DB:
				dbp = pFCB->dbp;
				if (dbp == NULL)
					break;
				strcpy(p, dbp->dp->fileName);
				n++;
				p += strlen(dbp->dp->fileName) + 1;
				for (j = 1; j <= MAX_IDX; j++) {
					ip = dbp->ip[j];
					if (ip != NULL) {
						strcpy(p, ip->ifp->fileName);
						p += strlen(ip->ifp->fileName)
								    + 1;
						n++;
					}
				}
				break;
			case FILETYPE_RF:
				if (pFCB->pFileName == NULL)
					break;
				strcpy(p, pFCB->pFileName);
				p += strlen(pFCB->pFileName) + 1;
				n++;
				break;
			}
		}
	}

	LEAVE_CRITICAL_SECTION(&csSCB);

	pSCB->response.dataLength = p - pSCB->lpData;
	pSCB->response.ret.sValue = n;
	pSCB->response.status = 0;
}

/*=======================================================================
 |
 |		PSADDebug() �֐����s����
 |
 |	VOID	ExecPSADDebug(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADDebug(PSCB pSCB)
{
	extern	int	allocCount;
	struct	PRM_AD_DEBUG	*pParam;

	pParam = (struct PRM_AD_DEBUG *)pSCB->command.param;
	switch (pParam->code) {
	case 0:
		*(SHORT *)(pSCB->lpData) = allocCount;
		pSCB->response.status = 0;
		pSCB->response.dataLength = 2;
		break;
	case 2:
		{
			int	fh;
			int	i;
			char	*p;
			for (i = 0; i < 1000; i++) {
				p = malloc(64000);
				fh = __OPEN("test.dbf");
				__CLOSE(fh);
				free(p);
			}
		}
	default:
		pSCB->response.status = ERROR_SERVER_NOSUPPORT;
		pSCB->response.dataLength = 0;
		break;
	}
}

/*=======================================================================
 |
 |		PSADGetStatistic() �֐����s����
 |
 |	VOID	ExecPSADGetStatistic(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADGetStatistics(PSCB pSCB)
{
	int	i;

	statistics.conxxxtCount[0] = 0;
	statistics.sendBytes1[0] = 0;
	statistics.sendBytes2[0] = 0;
	statistics.receiveBytes1[0] = 0;
	statistics.receiveBytes2[0] = 0;
	statistics.sendErrors[0] = 0;
	statistics.receiveErrors[0] = 0;

	for (i = 1; i < 4; i++) {
		statistics.conxxxtCount[0] += statistics.conxxxtCount[i];
		statistics.sendBytes1[0] += statistics.sendBytes1[i];
		statistics.sendBytes2[0] += statistics.sendBytes2[i];
		statistics.receiveBytes1[0] += statistics.receiveBytes1[i];
		statistics.receiveBytes2[0] += statistics.receiveBytes2[i];
		statistics.sendErrors[0] += statistics.sendErrors[i];
		statistics.receiveErrors[0] += statistics.receiveErrors[i];
	}

	statistics.sendBytes2[0] += statistics.sendBytes1[0] / MEGABYTE;
	statistics.sendBytes1[0] = statistics.sendBytes1[0] % MEGABYTE;
	statistics.receiveBytes2[0] += statistics.receiveBytes1[0] / MEGABYTE;
	statistics.receiveBytes1[0] = statistics.receiveBytes1[0] % MEGABYTE;

	memcpy(pSCB->lpData, &statistics, sizeof(STATISTICS));

	pSCB->response.dataLength = sizeof(STATISTICS);
	pSCB->response.ret.sValue = 0;
	pSCB->response.status = 0;
}

/*=======================================================================
 |
 |		PSADClrStatistic() �֐����s����
 |
 |	VOID	ExecPSADClrStatistic(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADClrStatistics(PSCB pSCB)
{
	InitStatistics();

	pSCB->response.dataLength = 0;
	pSCB->response.ret.sValue = 0;
	pSCB->response.status = 0;
}

/*=======================================================================
 |
 |		���v��񏉊�������
 |
 |	VOID	InitStatistics()
 |
 =======================================================================*/
VOID	InitStatistics(VOID)
{
	long	ltime;
	struct	tm	*tm;

	memset(&statistics, 0, sizeof(STATISTICS));

	/* �J�n�����L�^ */
	time(&ltime);
	tm = localtime(&ltime);
	statistics.startTime[0] = (USHORT)tm->tm_year;
	statistics.startTime[1] = (USHORT)(tm->tm_mon + 1);
	statistics.startTime[2] = (USHORT)tm->tm_mday;
	statistics.startTime[3] = (USHORT)tm->tm_hour;
	statistics.startTime[4] = (USHORT)tm->tm_min;
	statistics.startTime[5] = (USHORT)tm->tm_sec;
}

/*=======================================================================
 |
 |		PSADDuplexStatus() �֐����s����
 |
 |	VOID	ExecPSADDuplexStatus(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADDuplexStatus(PSCB pSCB)
{
	pSCB->response.ret.sValue = GetDuplexStatus();

	pSCB->response.dataLength = 0;
	pSCB->response.status = 0;
}

/*=======================================================================
 |
 |		PSADDuplexSync() �֐����s����
 |
 |	VOID	ExecPSADDuplexSync(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADDuplexSync(PSCB pSCB)
{
	struct	PRM_AD_DUPLEXSYNC	*pParam;

	pParam = (struct PRM_AD_DUPLEXSYNC *)pSCB->command.param;

	if (StartSyncDuplexFile(pParam->fileName))
		pSCB->response.status = 0;
	else
		pSCB->response.status = -1;

	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSADDuplexGetInfo() �֐����s����
 |
 |	VOID	ExecPSADDuplexGetInfo(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADDuplexGetInfo(PSCB pSCB)
{
	GetDuplexInfo((PDUPLEXINFO)pSCB->lpData);

	pSCB->response.status = 0;
	pSCB->response.dataLength = sizeof(DUPLEXINFO);
}

/*=======================================================================
 |
 |		PSADDuplexSetInfo() �֐����s����
 |
 |	VOID	ExecPSADDuplexSetInfo(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADDuplexSetInfo(PSCB pSCB)
{
	SetDuplexInfo((PDUPLEXINFO)pSCB->lpData);

	pSCB->response.status = 0;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSADMultiSync() �֐����s����
 |
 |	VOID	ExecPSADMultiSync(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADMultiSync(PSCB pSCB)
{
	struct	PRM_AD_MULTISYNC	*pParam;

	pParam = (struct PRM_AD_MULTISYNC *)pSCB->command.param;

	if (StartSyncMultiFile(pParam->fileName))
		pSCB->response.status = 0;
	else
		pSCB->response.status = -1;

	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSADMultiCopyInfo() �֐����s����
 |
 |	VOID	ExecPSADMultiCopyInfo(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADMultiCopyInfo(PSCB pSCB)
{
	if (MultiCopyInfo())
		pSCB->response.status = 0;
	else
		pSCB->response.status = -1;

	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSADGetIniData() �֐����s����
 |
 |	VOID	ExecPSADGetIniData(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADGetIniData(PSCB pSCB)
{
	memcpy(pSCB->lpData, &ini, sizeof(INIDATA));

	pSCB->response.status = 0;
	pSCB->response.dataLength = sizeof(INIDATA);
}

/*=======================================================================
 |
 |		PSADSetIniData() �֐����s����
 |
 |	VOID	ExecPSADSetIniData(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADSetIniData(PSCB pSCB)
{
	extern	CRITICALSECTION	csLogFile;

	ENTER_CRITICAL_SECTION(&csLogFile);

	memcpy(&ini, pSCB->lpData, sizeof(INIDATA));

	CloseLogFile();
	OpenLogFile();

	LEAVE_CRITICAL_SECTION(&csLogFile);

	WriteIniFile();

	pSCB->response.status = 0;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSADGetLicenseData() �֐����s����
 |
 |	VOID	ExecPSADGetLicenseData(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADGetLicenseData(PSCB pSCB)
{
	if (GetLicenseData("PCSERVE", (LICENSE *)pSCB->lpData))
		pSCB->response.status = 0;
	else
		pSCB->response.status = -1;

	pSCB->response.dataLength = sizeof(LICENSE);
}

/*=======================================================================
 |
 |		PSADSetLicenseData() �֐����s����
 |
 |	VOID	ExecPSADSetLicenseData(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSADSetLicenseData(PSCB pSCB)
{
	if (SetLicenseData("PCSERVE", (LICENSE *)pSCB->lpData))
		pSCB->response.status = 0;
	else
		pSCB->response.status = -1;

	pSCB->response.dataLength = sizeof(LICENSE);
}