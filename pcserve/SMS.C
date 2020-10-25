/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �T�[�o���l�b�g���[�N���b�Z�[�W���M�ݒ菈��
 *		�t�@�C����	: sms.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#ifdef	OS_WNT
#include <process.h>
#include <lm.h>
#endif
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "pcserve.h"

#define	FN_MSDBF	"_NETMSG.DBF"
#define	FN_MSNDX	"_NETMSG.NDX"

typedef	struct	_NETMSG {
	CHAR	*message;
	int	msgLen;
	CHAR	name[42];
	long	timeout;
	struct	_NETMSG	*chain;
} NETMSG, *PNETMSG;

typedef	struct	{
	CHAR	flag[1];
	CHAR	groupName[20];
	CHAR	member[20];
} NETMSGDBF;

CRITICALSECTION	csNetMsg;
static	PNETMSG	pNewMsg;
static	PNETMSG	pSendMsg;

#ifdef	OS_WNT
static	HANDLE	hNetMsgEvent;
#endif

static	VOID	AddNetMessageQueue(CHAR *, CHAR *, long);
#ifdef	OS_WNT
static	VOID	ThreadNetMessage(LPVOID);
#endif
static	PDB	OpenMSDBF(VOID);

/*=======================================================================
 |
 |		PSMSSendRequest() �֐����s����
 |
 |	VOID	ExecPSMSSendRequest(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSMSSendRequest(PSCB pSCB)
{
	struct	PRM_MS_SEND_REQUEST	*pParam;
	long	ltime;
	PDB	dbp;
	LONG	nRec;
	SHORT	n;
	CHAR	group[22];
	CHAR	name[22];
	NETMSGDBF	recBuf;
	SHORT	err = 0;

	pParam = (struct PRM_MS_SEND_REQUEST *)pSCB->command.param;
	pParam->name[sizeof(pParam->name) - 1] = '\0';

	time(&ltime);
	ltime += pParam->timeout * 60;

	if (pParam->name[0] == '*') {
		if ((dbp = OpenMSDBF()) != NULL) {
			CopyToDBF(group, pParam->name, 21);
			nRec = 0;
			DBCount(dbp, group, 21, &nRec);
			if (nRec != 0) {
				DBSearch(dbp, group, 21);
				while (nRec != 0) {
					DBReadNext(dbp, 1, (CHAR *)&recBuf,
									&n);
					if (n != 1)
						break;
					CopyFromDBF(name, recBuf.member, 20);
					AddNetMessageQueue(pSCB->lpData,
								name, ltime);
					nRec--;
				}
			}
		} else
			err = ERROR_MS_GRPFILE;
	} else
		AddNetMessageQueue(pSCB->lpData, pParam->name, ltime);

#ifdef	OS_WNT
	SetEvent(hNetMsgEvent);
#endif

	pSCB->response.status = err;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSMSSetMember() �֐����s����
 |
 |	VOID	ExecPSMSSetMember(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSMSSetMember(PSCB pSCB)
{
	struct	PRM_MS_SET_MEMBER	*pParam;
	PDB	dbp;
	NETMSGDBF	recBuf;
	SHORT	err = 0;

	pParam = (struct PRM_MS_SET_MEMBER *)pSCB->command.param;
	pParam->groupName[sizeof(pParam->groupName) - 1] = '\0';
	pParam->member[sizeof(pParam->member) - 1] = '\0';

	if ((dbp = OpenMSDBF()) != NULL) {
		memset(&recBuf, ' ', sizeof(recBuf));
		if (pParam->groupName[0] == '*') {
			CopyToDBF(recBuf.groupName, pParam->groupName + 1,
						sizeof(recBuf.groupName));
		} else {
			CopyToDBF(recBuf.groupName, pParam->groupName,
						sizeof(recBuf.groupName));
		}

		DBStoreUniq(dbp, (CHAR *)&recBuf);
		recBuf.flag[0] = '*';
		CopyToDBF(recBuf.member, pParam->member,
						sizeof(recBuf.member));
		DBStoreUniq(dbp, (CHAR *)&recBuf);
	} else
		err = ERROR_MS_GRPFILE;

	pSCB->response.status = err;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSMSDeleteMember() �֐����s����
 |
 |	VOID	ExecPSMSDeleteMember(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSMSDeleteMember(PSCB pSCB)
{
	struct	PRM_MS_DELETE_MEMBER	*pParam;
	PDB	dbp;
	NETMSGDBF	recBuf;
	SHORT	find;
	SHORT	err = 0;

	pParam = (struct PRM_MS_DELETE_MEMBER *)pSCB->command.param;
	pParam->groupName[sizeof(pParam->groupName) - 1] = '\0';
	pParam->member[sizeof(pParam->member) - 1] = '\0';

	if ((dbp = OpenMSDBF()) != NULL) {
		recBuf.flag[0] = '*';
		if (pParam->groupName[0] == '*') {
			CopyToDBF(recBuf.groupName, pParam->groupName + 1,
						sizeof(recBuf.groupName));
		} else {
			CopyToDBF(recBuf.groupName, pParam->groupName,
						sizeof(recBuf.groupName));
		}
		CopyToDBF(recBuf.member, pParam->member,
						sizeof(recBuf.member));

		DBDeleteKey(dbp, (CHAR *)&recBuf, 41, 0);
		DBSearch2(dbp, (CHAR *)&recBuf, 21, &find);
		if (find != 1) {
			recBuf.flag[0] = ' ';
			DBDeleteKey(dbp, (CHAR *)&recBuf, 21, 0);
		}
	} else
		err = ERROR_MS_GRPFILE;

	pSCB->response.status = err;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSMSGetMember() �֐����s����
 |
 |	VOID	ExecPSMSGetMember(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
VOID	ExecPSMSGetMember(PSCB pSCB)
{
	struct	PRM_MS_GET_MEMBER	*pParam;
	PDB	dbp;
	CHAR	group[22];
	CHAR	name[22];
	NETMSGDBF	recBuf;
	SHORT	n;
	CHAR	*bufp;
	int	len;
	int	size;
	int	count = 0;
	BOOL	bFirst;
	LONG	nRec = 0;
	SHORT	err = 0;

	pParam = (struct PRM_MS_GET_MEMBER *)pSCB->command.param;
	pParam->groupName[sizeof(pParam->groupName) - 1] = '\0';

	if ((dbp = OpenMSDBF()) != NULL) {
		if (pParam->groupName[0] == '*')
			CopyToDBF(group, pParam->groupName, 21);
		else {
			group[0] = '*';
			CopyToDBF(group + 1, pParam->groupName, 20);
		}

		DBCount(dbp, group, 21, &nRec);
		if (nRec != 0) {
			bufp = pSCB->lpData;
			size = pParam->size;
			bFirst = TRUE;
			DBSearch(dbp, group, 21);
			while (nRec != 0) {
				DBReadNext(dbp, 1, (CHAR *)&recBuf, &n);
				if (n != 1)
					break;
				CopyFromDBF(name, recBuf.member, 20);
				if ((len = strlen(name)) + count + 2 >= size) {
					err = ERROR_MS_BUFSIZE;
					break;
				}

				if (bFirst)
					bFirst = FALSE;
				else
					bufp[count++] = ' ';

				memcpy(&bufp[count], name, len);
				count += len;
			}
			bufp[count++] = '\0';
		}
	} else
		err = ERROR_MS_GRPFILE;

	pSCB->response.ret.sValue = (SHORT)nRec;
	pSCB->response.status = err;
	pSCB->response.dataLength = count;
}

/*=======================================================================
 |
 |		���b�Z�[�W�L���[�ǉ�����
 |
 |	VOID	AddNetMessageQueue(message, name, timeout)
 |
 |		CHAR	*message;	���M���b�Z�[�W
 |		CHAR	*name;		���M���於
 |		long	timeout;	�^�C���A�E�g����
 |
 =======================================================================*/
static	VOID	AddNetMessageQueue(CHAR *message, CHAR *name, long timeout)
{
	PNETMSG	pNetMsg, *ppNetMsg;
	int	msgLen;
#ifdef	OS_WNT
	int	nameLen;
#endif

	if ((pNetMsg = malloc(sizeof(NETMSG))) == NULL)
		return;

#ifdef	OS_WNT
	msgLen = strlen(message) * 2;
#else
	msgLen = strlen(message);
#endif

	if ((pNetMsg->message = malloc(msgLen)) == NULL) {
		free(pNetMsg);
		return;
	}

#ifdef	OS_WNT
	msgLen = MultiByteToWideChar(CP_ACP, 0, (LPCSTR)message,
				strlen(message), (LPWSTR)pNetMsg->message,
				msgLen) * 2;
	nameLen = MultiByteToWideChar(CP_ACP, 0, (LPCSTR)name,
				strlen(name), (LPWSTR)pNetMsg->name,
				sizeof(pNetMsg->name)) * 2;
	pNetMsg->name[nameLen] = '\0';
#else
	memcpy(pNetMsg->message, message, msgLen);
	strcpy(pNetMsg->name, name);
#endif

	pNetMsg->msgLen = msgLen;
	pNetMsg->timeout = timeout;
	pNetMsg->chain = NULL;

	ENTER_CRITICAL_SECTION(&csNetMsg);
	for (ppNetMsg = &pNewMsg; *ppNetMsg != NULL; 
					ppNetMsg = &(*ppNetMsg)->chain)
		;
	*ppNetMsg = pNetMsg;
	LEAVE_CRITICAL_SECTION(&csNetMsg);
}

/*=======================================================================
 |
 |		�l�b�g���[�N���b�Z�[�W���M�����J�n
 |
 |	VOID	StartNetMessage()
 |
 =======================================================================*/
VOID	StartNetMessage(VOID)
{
	static	CHAR	funcName[] = "StartNetMessage";

#ifdef	OS_WNT
	hNetMsgEvent = CreateEvent(NULL, FALSE, FALSE, NULL);

	if (_beginthread(ThreadNetMessage, 0, NULL) == -1) {
		ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
	}
#endif
}

/*=======================================================================
 |
 |		�l�b�g���[�N���b�Z�[�W���M�X���b�h
 |
 |	VOID	ThreadNetMessage(param)
 |
 |		LPVOID	param;		�_�~�[�p�����[�^
 |
 =======================================================================*/
#ifdef	OS_WNT
static	VOID	ThreadNetMessage(LPVOID param)
#endif
{
	PNETMSG	pNetMsg, *ppNetMsg;
	long	ltime;
	BOOL	bFree;

	for (;;) {
#ifdef	OS_WNT
		WaitForSingleObject(hNetMsgEvent, 60 * 1000);
		Sleep(100);
#endif

		ENTER_CRITICAL_SECTION(&csNetMsg);
		if (pNewMsg != NULL) {
			for (ppNetMsg = &pSendMsg; *ppNetMsg != NULL; 
						ppNetMsg = &(*ppNetMsg)->chain)
				;
			*ppNetMsg = pNewMsg;
			pNewMsg = NULL;
		}
		LEAVE_CRITICAL_SECTION(&csNetMsg);

		time(&ltime);

		for (ppNetMsg = &pSendMsg; *ppNetMsg != NULL; ) {
			pNetMsg = *ppNetMsg;
#ifdef	OS_WNT
			if (NetMessageBufferSend(NULL, (USHORT *)pNetMsg->name, NULL,
							pNetMsg->message,
							pNetMsg->msgLen) == 0)
#endif
				bFree = TRUE;
			else {
				if (ltime >= pNetMsg->timeout)
					bFree = TRUE;
				else
					bFree = FALSE;
			}

			if (bFree) {
				*ppNetMsg = (*ppNetMsg)->chain;
				free(pNetMsg->message);
				free(pNetMsg);
			} else
				ppNetMsg = &(*ppNetMsg)->chain;
		}
	}
}

/*=======================================================================
 |
 |		���b�Z�[�W�O���[�v�Ǘ��t�@�C���I�[�v��
 |
 |	PDB	OpenMSDBF()
 |
 |		SHORT	�Ԓl		�c�a�|�C���^
 |
 =======================================================================*/
static	PDB	OpenMSDBF(VOID)
{
	PDB	dbp;
	SHORT	idxNo;
	SHORT	err;
	static	DBF_I	fieldData[] = {
		{"FLAG",      'C',  1, 0},
		{"GROUPNAME", 'C', 20, 0},
		{"MEMBER"   , 'C', 20, 0}
	};

	if ((err = DBOpen(FN_MSDBF, &dbp, PERMISSION_ALL)) != 0) {
		if (err != ERROR_DB_NOFILE ||
				(err = DBCreate(FN_MSDBF, fieldData,
				sizeof(fieldData)/sizeof(DBF_I), &dbp, PERMISSION_ALL)) != 0)
			return NULL;
	} else {
		if (DBIndex(dbp, FN_MSNDX, &idxNo) == 0)
			return dbp;
	}

	if ((err = DBIdxCreate(dbp, FN_MSNDX, "FLAG+GROUPNAME+MEMBER", 1, &idxNo)) != 0) {
		DBClose(dbp);
		return NULL;
	}

	return dbp;
}