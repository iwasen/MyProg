// PcRef.cpp : コンソール アプリケーション用のエントリ ポイントの定義
//

#include "stdafx.h"
#include <direct.h>
#include "Common.h"
#include "PcRef.h"

static	char	serviceName[] = "PC-VIDEO Reflector";
static	HANDLE	hServDoneEvent = NULL;
static	SERVICE_STATUS		ssStatus;
static	SERVICE_STATUS_HANDLE	sshStatusHandle;
static	DWORD			dwGlobalErr;
static	LPUSER	lpTopUser;
static	LPSEND	lpTopSend;
static	int	sock;
static	struct	sockaddr_in	socketAddr;
static	u_long	localIPAddress;
static	INIDATA	iniData;
static	u_long	uniCastIPAddress[MAX_UNICAST];
static	u_long	autoConxxxtIPAddress[MAX_AUTOCONxxxT];
static	int	nUniCast;
static	int	nAutoConxxxt;
static	CRITICAL_SECTION	sem;
static	int	monitorSocket = -1;
static	BOOL bLoginReq;
static	int	nStartCounter;

/*	内部関数	*/
static	int	InstallService(char *);
static	int	RemoveService(VOID);
static	VOID	ServiceMain(DWORD, LPTSTR *);
static	VOID	WINAPI ServiceCtrl(DWORD);
static	BOOL	ReportStatusToSCMgr(DWORD, DWORD, DWORD, DWORD);
static	VOID	StopService(LPTSTR);
static	VOID	ReflectMain(LPVOID);
static	BOOL	Initialize(VOID);
static	VOID	Terminate(VOID);
static	VOID	Reflect(VOID);
static	BOOL	SendCheck(LPUSER, LPSEND, char *);
static BOOL AutoConxxxtCheck(u_long IPAddress);
static BOOL ReflectorCheck(u_long IPAddress, u_short portNo);
static	LPUSER	SearchUser(DWORD id, u_short portNo);
static	LPUSER	AddUser(PCONxxxT, u_long, u_short);
static	VOID	DeleteUser(LPUSER);
static	VOID	TimerProc(LPVOID);
static	VOID	SendData(u_long, u_short, VOID *, int);
static	VOID	SendConxxxt(LPSEND);
static	VOID	ReadIniFile(VOID);
static	VOID	WriteLog(LPUSER, int);
static	VOID	CommandProc(LPVOID);
static	VOID	ReceiveCommand(int);
static	BOOL	ExecCommand(int, char *);
static	VOID	sockprintf(int, char *, ...);
static	VOID	ErrorLog(char *, ...);
static	int	ConvertVideoData(char *, char *, int);
static	int	Decompress(char *, char *, int);

int main(int argc, char* argv[])
{
	SERVICE_TABLE_ENTRY dispatchTable[] = {
		{TEXT("PCREFRCT"), (LPSERVICE_MAIN_FUNCTION)ServiceMain},
		{NULL, NULL}
	};
	char	*p;
	int	i;

	for (i = 1; i < argc; i++) {
		p = argv[i];
		if (*p == '/' || *p == '-') {
			p++;
			CharUpper(p);
			if (strcmp(p, "INSTALL") == 0)
				return(InstallService(argv[0]));
			if (strcmp(p, "REMOVE") == 0)
				return(RemoveService());
		}
	}

	CharUpper(argv[0]);

	if (argv[0][1] == ':')
		_chdrive(toupper(argv[0][0]) - 0x40);
	if ((p = strrchr(argv[0], '\\')) != NULL) {
		*p = '\0';
		_chdir(argv[0]);
		*p = '\\';
	}

	if (strcmp(argv[0] + (strlen(argv[0]) - 4), ".EXE") == 0) {
		Initialize();
		ReflectMain(NULL);
		Terminate();
	} else {
		if (!StartServiceCtrlDispatcher(dispatchTable)) {
			StopService("StartServiceCtrlDispatcher failed.");
		}
	}

	return(0);
}

/*=======================================================================
 |
 |		サービスインストール
 |
 |	int	InstallService()
 |
 =======================================================================*/
static	int	InstallService(char *fileName)
{
	SC_HANDLE	hSC1, hSC2;
	char	pathName[256];

	_fullpath(pathName, fileName, sizeof(pathName));

	hSC1 = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);
	if (hSC1 == NULL) {
		printf("OpenSCManager error %d", GetLastError());
		return(1);
	}

	hSC2 = CreateService(
		hSC1,				// SCManager database
		serviceName,			// name of service
		serviceName,			// name to display
		SERVICE_ALL_ACCESS,		// desired access
		SERVICE_WIN32_OWN_PROCESS,	// service type
		SERVICE_AUTO_START,		// start type
		SERVICE_ERROR_NORMAL,		// error control type
		pathName,			// service's binary
		NULL,				// no load ordering group
		NULL,				// no tag identifier
		NULL,				// no dependencies
		NULL,				// LocalSystem account
		NULL);				// no password
	if (hSC2 == NULL) {
		printf("OpenService error %d", GetLastError());
		CloseServiceHandle(hSC1);
		return(1);
	}

	StartService(hSC2, 0, NULL);

	CloseServiceHandle(hSC2);
	CloseServiceHandle(hSC1);

	printf("\n%s サービスをインストールしました．\n", serviceName);

	return(0);
}

/*=======================================================================
 |
 |		サービス削除
 |
 |	int	RemoveService()
 |
 =======================================================================*/
static	int	RemoveService(VOID)
{
	SC_HANDLE	hSC1, hSC2;

	hSC1 = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);
	if (hSC1 == NULL) {
		printf("OpenSCManager error %d", GetLastError());
		return(1);
	}

	hSC2 = OpenService(hSC1, serviceName, SERVICE_ALL_ACCESS);
	if (hSC2 == NULL) {
		printf("OpenService error %d", GetLastError());
		CloseServiceHandle(hSC1);
		return(1);
	}

	if (!DeleteService(hSC2))
		printf("DeleteService error %d", GetLastError());

	CloseServiceHandle(hSC2);
	CloseServiceHandle(hSC1);

	printf("\n%s サービスを削除しました．\n", serviceName);

	return(0);
}

/*=======================================================================
 |
 |		サービス関数 (Windows NT)
 |
 |	VOID	ServiceMain(dwArgc, lpszArgv)
 |
 |		DWORD	dwArgc;		コマンドパラメータ数
 |		LPTSTR	*lpszArgv;	コマンドパラメータ
 |
 =======================================================================*/
static	VOID	ServiceMain(DWORD dwArgc, LPTSTR *lpszArgv)
{
	sshStatusHandle = RegisterServiceCtrlHandler(
					TEXT("PCREFRCT"), ServiceCtrl);

	if (!sshStatusHandle)
		goto cleanup;

	ssStatus.dwServiceType = SERVICE_WIN32_OWN_PROCESS;
	ssStatus.dwServiceSpecificExitCode = 0;

	if (!ReportStatusToSCMgr(
			SERVICE_START_PENDING, // service state
			NO_ERROR,	       // exit code
			1,		       // checkpoint
			3000))		       // wait hint
		goto cleanup;

	hServDoneEvent = CreateEvent(
			NULL,	 // no security attributes
			TRUE,	 // manual reset event
			FALSE,	 // not-signalled
			NULL);	 // no name

	if (hServDoneEvent == (HANDLE)NULL)
		goto cleanup;

	if (!ReportStatusToSCMgr(
			SERVICE_START_PENDING, // service state
			NO_ERROR,	       // exit code
			2,		       // checkpoint
			3000))		       // wait hint
		goto cleanup;

	if (!Initialize())
		goto cleanup;

	if (_beginthread(ReflectMain, 0, NULL) == -1)
		goto cleanup;

	if (!ReportStatusToSCMgr(
			SERVICE_RUNNING, // service state
			NO_ERROR,	 // exit code
			0,		 // checkpoint
			0))		 // wait hint
		goto cleanup;

	WaitForSingleObject(hServDoneEvent,INFINITE);

	Terminate();
cleanup:
	if (hServDoneEvent != NULL)
		CloseHandle(hServDoneEvent);

	if (sshStatusHandle != 0)
		(VOID)ReportStatusToSCMgr(
				SERVICE_STOPPED,
				dwGlobalErr,
				0,
				0);

	return;
}

/*=======================================================================
 |
 |		サービスコントロール処理関数 (Windows NT)
 |
 |	VOID	ServiceCtrl(dwCtrlCode)
 |
 |		DWORD	dwCtrlCode;	コントロールコード
 |
 =======================================================================*/
static	VOID	WINAPI ServiceCtrl(DWORD dwCtrlCode)
{
	DWORD  dwState = SERVICE_RUNNING;

	switch(dwCtrlCode) {

	case SERVICE_CONTROL_PAUSE:

	    if (ssStatus.dwCurrentState == SERVICE_RUNNING) {
			dwState = SERVICE_PAUSED;
	    }
	    break;

	case SERVICE_CONTROL_CONTINUE:
		if (ssStatus.dwCurrentState == SERVICE_PAUSED) {
			dwState = SERVICE_RUNNING;
		}
		break;

	case SERVICE_CONTROL_STOP:
		dwState = SERVICE_STOP_PENDING;

		ReportStatusToSCMgr(
				SERVICE_STOP_PENDING, // current state
				NO_ERROR,	      // exit code
				1,		      // checkpoint
				3000);		      // waithint

		SetEvent(hServDoneEvent);
		return;

	case SERVICE_CONTROL_INTERROGATE:
		break;

	default:
		break;

	}

	ReportStatusToSCMgr(dwState, NO_ERROR, 0, 0);
}

/*=======================================================================
 |
 |		ステータス通知処理 (Windows NT)
 |
 |	BOOL	ReportStatusToSCMgr(dwCurrentState, dwWin32ExitCode,
 |						dwCheckPoint, dwWaitHint)
 |
 |		DWORD	dwCurrentState;		ステータス
 |		DWORD	dwWin32ExitCode;	エラーコード
 |		DWORD	dwCheckPoint;		チェックポイント
 |		DWORD	dwWaitHint;		完了予想時間
 |
 =======================================================================*/
static	BOOL	ReportStatusToSCMgr(DWORD dwCurrentState,
			DWORD dwWin32ExitCode,
			DWORD dwCheckPoint,
			DWORD dwWaitHint)
{
	BOOL	fResult;

	if (dwCurrentState == SERVICE_START_PENDING)
		ssStatus.dwControlsAccepted = 0;
	else
		ssStatus.dwControlsAccepted = SERVICE_ACCEPT_STOP |
				SERVICE_ACCEPT_PAUSE_CONTINUE;

	ssStatus.dwCurrentState = dwCurrentState;
	ssStatus.dwWin32ExitCode = dwWin32ExitCode;
	ssStatus.dwCheckPoint = dwCheckPoint;

	ssStatus.dwWaitHint = dwWaitHint;

	if (!(fResult = SetServiceStatus(
			sshStatusHandle,    // service reference handle
			&ssStatus))) {	    // SERVICE_STATUS structure
		StopService("SetServiceStatus");
	}
	return fResult;
}

/*=======================================================================
 |
 |		サービス停止処理 (Windows NT)
 |
 |	VOID	StopService(lpszMsg)
 |
 |		LPTSTR	lpszMsg;	エラーメッセージ
 |
 =======================================================================*/
static	VOID	StopService(LPTSTR lpszMsg)
{
	char	chMsg[256];
	HANDLE	hEventSource;
	LPCTSTR	lpszStrings[2];

	dwGlobalErr = GetLastError();

	hEventSource = RegisterEventSource(NULL, TEXT("PCREFRCT"));

	sprintf(chMsg, "PCREFRCT error: %d", dwGlobalErr);
	lpszStrings[0] = chMsg;
	lpszStrings[1] = lpszMsg;

	if (hEventSource != NULL) {
		ReportEvent(hEventSource, // handle of event source
				EVENTLOG_ERROR_TYPE,  // event type
				0,		      // event category
				0,		      // event ID
				NULL,		      // current user's SID
				2,		      // strings in lpszStrings
				0,		      // no bytes of raw data
				lpszStrings,	      // array of error strings
				NULL);		      // no raw data

		(VOID) DeregisterEventSource(hEventSource);
	}

	SetEvent(hServDoneEvent);
}

VOID	ExitProc(int n)
{
	SetEvent(hServDoneEvent);
}

VOID	ReflectMain(LPVOID lpVoid)
{
	HANDLE	hProcess;

	hProcess = GetCurrentProcess();
	if (!SetPriorityClass(hProcess, HIGH_PRIORITY_CLASS)) {
		ErrorLog("SetPriorityClass() ErrorCode=%d", GetLastError());
	}

	if (_beginthread(TimerProc, 0, NULL) == -1) {
		ErrorLog("_beginthread() ErrorCode=%d", GetLastError());
	} else if (_beginthread(CommandProc, 0, NULL) == -1) {
		ErrorLog("_beginthread() ErrorCode=%d", GetLastError());
	} else {
		Reflect();
	}
}

static	BOOL	Initialize(VOID)
{
	WSADATA	WSAData;
	struct	hostent	*hp;
	int	status;
	int	err;
	char	hostName[64];
	int	bufSize;

	InitializeCriticalSection(&sem);

	if ((status = WSAStartup(MAKEWORD(1,1), &WSAData)) != 0) {
		ErrorLog("WSAStartup() ErrorCode=%d", status);
		return(FALSE);
	}

	sock = socket(PF_INET, SOCK_DGRAM, 0);
	if (sock < 0) {
		ErrorLog("socket() ErrorCode=%d", WSAGetLastError());
		return(FALSE);
	}

	bufSize = 8192;
	setsockopt(sock, SOL_SOCKET, SO_RCVBUF, (char *)&bufSize, sizeof(bufSize));
	setsockopt(sock, SOL_SOCKET, SO_SNDBUF, (char *)&bufSize, sizeof(bufSize));

	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(PORTNO);
	socketAddr.sin_addr.s_addr = 0;
	err = bind(sock, (struct sockaddr *)&socketAddr, sizeof(socketAddr));
	if (err != 0) {
		closesocket(sock);
		ErrorLog("bind() ErrorCode=%d", WSAGetLastError());
		return(FALSE);
	}

	if (gethostname(hostName, sizeof(hostName)) != 0) {
		closesocket(sock);
		ErrorLog("gethostname() ErrorCode=%d", WSAGetLastError());
		return(FALSE);
	}

	hp = gethostbyname(hostName);
	localIPAddress = *(u_long *)hp->h_addr;

	ReadIniFile();

	return(TRUE);
}

static	VOID	Terminate(VOID)
{
	WSACleanup();
}

static	VOID	Reflect(VOID)
{
	int	fromLen, len, i;
	PHEADER	lpHeader;
	PCONxxxT	lpConxxxt;
	LPUSER	lpUser;
	LPSEND	lpSend;
	u_long	IPAddress;
	u_short	portNo;
	char	recvBuf[2048];
	CONxxxT	conxxxt;
	char	flag;
	FILE	*fp;
	int	vLen;
	char	vBuf[2048];

	for (i = 0; i < nAutoConxxxt; i++) {
		if (autoConxxxtIPAddress[i] != 0) {
			conxxxt.header.flag = FLAG_CONxxxT;
			conxxxt.header.id = localIPAddress;
			strcpy(conxxxt.userName, iniData.settings.serverName);
			conxxxt.modeFlag = (char)(MODE_REFLECT | MODE_VIDEOREQ);
			conxxxt.channelNo = 0;
			SendData(autoConxxxtIPAddress[i], htons(PORTNO2), &conxxxt, sizeof(conxxxt));
			SendData(autoConxxxtIPAddress[i], htons(PORTNO), &conxxxt, sizeof(conxxxt));
		}
	}

	for (;;) {
		socketAddr.sin_family      = AF_INET;
		socketAddr.sin_port        = 0;
		socketAddr.sin_addr.s_addr = 0;
		fromLen = sizeof(socketAddr);
		len = recvfrom(sock, recvBuf, sizeof(recvBuf), 0,
				(struct sockaddr *)&socketAddr,
				&fromLen);
		if (len == -1) {
			int err = WSAGetLastError();
			ErrorLog("recvfrom() ErrorCode=%d", err);
			if (err == WSAECONNRESET) {
				Sleep(1000);
				continue;
			} else
				break;
		}

		if (len < sizeof(HEADER))
			continue;

		IPAddress = socketAddr.sin_addr.s_addr;
		portNo = socketAddr.sin_port;

		lpHeader = (PHEADER)recvBuf;
		flag = (lpHeader->flag & ~FLAG_SECOND);

		if (flag == FLAG_LICENSE)
			continue;

		EnterCriticalSection(&sem);

		lpUser = SearchUser(lpHeader->id, portNo);

		if (monitorSocket != -1 && lpUser != NULL) {
			char	*p, buf[64];
			switch (flag) {
			case FLAG_CONxxxT:
				p = "CONN ";
				break;
			case FLAG_DISCONxxxT:
				p = "DISC ";
				break;
			case FLAG_REMOTE:
				p = "REMOT";
				break;
			case FLAG_VIDEO:
				p = "VIDEO";
				break;
			case FLAG_LOGINREQ:
				p = "LOGRQ";
				break;
			case FLAG_LOGIN:
				p = "LOGIN";
				break;
			case FLAG_DENY:
				p = "DENY ";
				break;
			case FLAG_CHAT:
				p = "CHAT ";
				break;
			case FLAG_VOICE:
				p = "VOICE";
				break;
			case FLAG_CALL:
				p = "CALL ";
				break;
			default:
				p = "?????";
				break;
			}
			sprintf(buf, "%s %s\r\n", p, lpUser->userName);
			send(monitorSocket, buf, strlen(buf), 0);
		}

		if (flag == FLAG_CONxxxT) {
			lpConxxxt = (PCONxxxT)recvBuf;
			if (lpUser == NULL) {
if (monitorSocket != -1) {
char buf[100];
char a1[20], a2[20];
strcpy(a1, inet_ntoa(*(struct in_addr *)&lpHeader->id));
strcpy(a2, inet_ntoa(*(struct in_addr *)&IPAddress));
sprintf(buf, "%s %s %d ---\r\n", a1, a2, ntohs(portNo));
send(monitorSocket, buf, strlen(buf), 0);
}
				if (bLoginReq && nStartCounter == USER_CHECK_COUNT &&
						!(lpConxxxt->modeFlag & MODE_REFLECT) && !AutoConxxxtCheck(IPAddress) &&
						!ReflectorCheck(IPAddress, portNo)) {
					PLOGINREQ pLoginReq;
					pLoginReq = (PLOGINREQ)vBuf;
					pLoginReq->header.flag = FLAG_LOGINREQ;
					pLoginReq->header.id = localIPAddress;
					strcpy(pLoginReq->serverName, iniData.settings.serverName);
					SendData(IPAddress, portNo, vBuf, sizeof(LOGINREQ));
				} else
					lpUser = AddUser(lpConxxxt, IPAddress, portNo);

				if (lpUser != NULL) {
					SendConxxxt(lpUser->lpSend);
				}
			}

			if (lpUser != NULL) {
				strcpy(lpUser->userName, lpConxxxt->userName);
				lpUser->modeFlag = lpConxxxt->modeFlag;
				lpUser->channelNo = lpConxxxt->channelNo;
				if (lpUser->modeFlag & MODE_REFLECT) {
					lpUser->timer = 0;
					LeaveCriticalSection(&sem);
					continue;
				}

				lpUser->nSendVideo = (len - sizeof(CONxxxT)) / sizeof(long);
				if (lpUser->nSendVideo < 0)
					lpUser->nSendVideo = 0;
				if (lpUser->nSendVideo >= MAX_VIDEO_WINDOW)
					lpUser->nSendVideo = MAX_VIDEO_WINDOW;
				memcpy(lpUser->sendVideo, &recvBuf[sizeof(CONxxxT)], lpUser->nSendVideo * sizeof(long));
			}
		}

		if (flag == FLAG_LOGIN) {
			if (lpUser == NULL) {
				PLOGIN pLogin = (PLOGIN)recvBuf;
				HANDLE hToken = NULL;
				if ((strcmp(pLogin->loginName, "super") == 0 && strcmp(pLogin->password, "pc!video") == 0) ||
						(pLogin->password[0] != '\0' && LogonUser(pLogin->loginName, NULL, pLogin->password,
						LOGON32_LOGON_NETWORK, LOGON32_PROVIDER_DEFAULT, &hToken))) {
					if (hToken != NULL)
						CloseHandle(hToken);

					lpConxxxt = (PCONxxxT)recvBuf;
					lpConxxxt->header.flag = FLAG_CONxxxT;
					len = sizeof(CONxxxT);

					if ((lpUser = AddUser(lpConxxxt, IPAddress, portNo)) != NULL)
						SendConxxxt(lpUser->lpSend);
				} else {
					PDENY lpDeny;

					lpDeny = (PDENY)vBuf;
					lpDeny->header.flag = FLAG_DENY;
					lpDeny->header.id = localIPAddress;
					strcpy(lpDeny->message, "ｻｰﾊﾞにｱｶｳﾝﾄが無いか、ﾕｰｻﾞIDまたはﾊﾟｽﾜｰﾄﾞが間違っています。");
					SendData(IPAddress, portNo, vBuf, sizeof(DENY));
				}
			}
		}

		if (lpUser != NULL) {
			lpUser->timer = 0;
			vLen = 0;
			for (lpSend = lpTopSend; lpSend != NULL; lpSend = lpSend->lpChain) {
				if ((IPAddress != lpSend->IPAddress || portNo != lpSend->portNo) && SendCheck(lpUser, lpSend, recvBuf)) {
					if (flag == FLAG_VIDEO && !lpSend->bReflector && !(lpSend->lpUser->modeFlag & MODE_NEWVERSION)) {
						if (vLen == 0)
							vLen = ConvertVideoData(recvBuf, vBuf, len);
						SendData(lpSend->IPAddress, lpSend->portNo, vBuf, vLen);
					} else {
						SendData(lpSend->IPAddress, lpSend->portNo, recvBuf, len);
					}
				}
			}

			if (flag == FLAG_CHAT) {
				if (iniData.settings.chatLogFile[0] != '\0') {
					if ((fp = fopen(iniData.settings.chatLogFile, "ab")) != NULL) {
						recvBuf[len] = '\0';
						sprintf(vBuf, "%d,[%s]%s\r\n",
							lpUser->channelNo,
							lpUser->userName,
							recvBuf + sizeof(HEADER));

						len = strlen(vBuf);
						for (i = 0; i < len; i++)
							vBuf[i] ^= 0xe5;
						fwrite(vBuf, len, 1, fp);
						fclose(fp);
					}
				}
			}

			if (flag == FLAG_DISCONxxxT)
				DeleteUser(lpUser);
		}

		LeaveCriticalSection(&sem);
	}
}

static	BOOL	SendCheck(LPUSER lpUser, LPSEND lpSend, char *recvBuf)
{
	int	i;
	DWORD	id;

	if (lpSend->bDisconxxxt)
		return(FALSE);

	if (!(lpUser->modeFlag & MODE_NEWVERSION))
		return(FALSE);

	if (!lpSend->bReflector && lpUser->channelNo != lpSend->lpUser->channelNo)
		return(FALSE);

	switch (((PHEADER)recvBuf)->flag & ~FLAG_SECOND) {
	case FLAG_VIDEO:
		id = ((PHEADER)recvBuf)->id;
		for (lpUser = lpSend->lpUser; lpUser != NULL; lpUser = lpUser->lpSendUser) {
			if ((lpUser->modeFlag & MODE_VIDEOREQ) == 0)
				return(TRUE);

			for (i = 0; i < lpUser->nSendVideo; i++) {
				if (lpUser->sendVideo[i] == id)
					return(TRUE);
			}
		}
		return(FALSE);
	case FLAG_VOICE:
		for (lpUser = lpSend->lpUser; lpUser != NULL; lpUser = lpUser->lpSendUser) {
			if (lpUser->modeFlag & MODE_VOICEREQ)
				return(TRUE);
		}
		return(FALSE);
	case FLAG_REMOTE:
		id = ((PREMOTE)recvBuf)->id;
		for (lpUser = lpSend->lpUser; lpUser != NULL; lpUser = lpUser->lpSendUser) {
			if (lpUser->id == id)
				return(TRUE);
		}
		return(FALSE);
	case FLAG_LOGINREQ:
	case FLAG_LOGIN:
		return FALSE;
	default:
		return(TRUE);
	}
}

static BOOL AutoConxxxtCheck(u_long IPAddress)
{
	int i;

	for (i = 0; i < nAutoConxxxt; i++) {
		if (autoConxxxtIPAddress[i] == IPAddress)
			return TRUE;
	}

	return FALSE;
}

static BOOL ReflectorCheck(u_long IPAddress, u_short portNo)
{
	LPSEND lpSend;

	for (lpSend = lpTopSend; lpSend != NULL; lpSend = lpSend->lpChain) {
		if (lpSend->IPAddress == IPAddress && lpSend->portNo == portNo)
			return lpSend->bReflector;
	}

	return FALSE;
}

static	LPUSER	SearchUser(DWORD id, u_short portNo)
{
	LPUSER	lpUser;

	for (lpUser = lpTopUser; lpUser != NULL; lpUser = lpUser->lpChain) {
		if (id == lpUser->id && portNo == lpUser->portNo)
			return(lpUser);
	}

	return(NULL);
}

static	LPUSER	AddUser(PCONxxxT lpConxxxt, u_long IPAddress, u_short portNo)
{
	LPUSER	lpUser, *lppUser;
	LPSEND	lpSend;

	if ((lpUser = new USER) == NULL)
		return(NULL);

	memset(lpUser, 0, sizeof(USER));
	lpUser->id = lpConxxxt->header.id;
	lpUser->portNo = portNo;
	lpUser->bSecond = ((lpConxxxt->header.flag & FLAG_SECOND) != 0);
	lstrcpy(lpUser->userName, lpConxxxt->userName);
	lpUser->modeFlag = lpConxxxt->modeFlag;
	lpUser->channelNo = lpConxxxt->channelNo;

	for (lpSend = lpTopSend; lpSend != NULL; lpSend = lpSend->lpChain) {
		if (lpSend->IPAddress == IPAddress && lpSend->portNo == portNo)
			break;
	}
	if (lpSend == NULL) {
		if ((lpSend = new SEND) == NULL) {
			delete lpUser;
			return(NULL);
		}
		memset(lpSend, 0, sizeof(SEND));
		lpSend->IPAddress = IPAddress;
		lpSend->portNo = portNo;

		lpSend->lpChain = lpTopSend;
		lpTopSend = lpSend;
	}

	if (lpConxxxt->modeFlag & MODE_REFLECT)
		lpSend->bReflector = TRUE;

	lpUser->lpSend = lpSend;
	lpUser->lpSendUser = lpSend->lpUser;
	lpSend->lpUser = lpUser;

	for (lppUser = &lpTopUser; *lppUser != 0; lppUser = &(*lppUser)->lpChain)
		;
	*lppUser = lpUser;

	WriteLog(lpUser, 'C');

	return(lpUser);
}

static	VOID	DeleteUser(LPUSER lpUser)
{
	LPUSER	*lppUser;
	LPSEND	lpSend, *lppSend;

	WriteLog(lpUser, 'D');

	if ((lpSend = lpUser->lpSend) != NULL) {
		for (lppUser = &lpSend->lpUser; *lppUser != NULL; lppUser = &(*lppUser)->lpSendUser) {
			if ((*lppUser) == lpUser) {
				*lppUser = (*lppUser)->lpSendUser;
				break;
			}
		}

		if (lpSend->lpUser == NULL) {
			for (lppSend = &lpTopSend; *lppSend != NULL; lppSend = &(*lppSend)->lpChain) {
				if ((*lppSend) == lpSend) {
					*lppSend = (*lppSend)->lpChain;
					break;
				}
			}

			delete lpSend;
		}
	}

	for (lppUser = &lpTopUser; *lppUser != 0; lppUser = &(*lppUser)->lpChain) {
		if ((*lppUser) == lpUser) {
			*lppUser = (*lppUser)->lpChain;
			break;
		}
	}

	delete lpUser;
}

static	VOID	TimerProc(LPVOID param)
{
	LPUSER	lpUser;
	LPSEND	lpSend;
	CONxxxT	conxxxt;
	int	i;

	for (;;) {
		EnterCriticalSection(&sem);

		for (lpUser = lpTopUser; lpUser != NULL; lpUser = lpUser->lpChain) {
			if (++lpUser->timer > TIMEOUT_USER / 10) {
				DeleteUser(lpUser);
				break;
			}
		}

		conxxxt.header.flag = FLAG_CONxxxT;
		conxxxt.header.id = localIPAddress;
		strcpy(conxxxt.userName, iniData.settings.serverName);
		conxxxt.modeFlag = (char)(MODE_REFLECT | MODE_VIDEOREQ);

		for (lpSend = lpTopSend; lpSend != NULL; lpSend = lpSend->lpChain) {
			if (!lpSend->bDisconxxxt) {
				conxxxt.channelNo = lpSend->lpUser->channelNo;
				SendData(lpSend->IPAddress,lpSend->portNo, &conxxxt, sizeof(conxxxt));
			}
		}

		for (i = 0; i < nUniCast; i++) {
			if (uniCastIPAddress[i] != 0) {
				for (lpSend = lpTopSend; lpSend != NULL; lpSend = lpSend->lpChain) {
					if (uniCastIPAddress[i] == lpSend->IPAddress)
						break;
				}
				if (lpSend == NULL) {
					conxxxt.channelNo = 0;
					SendData(uniCastIPAddress[i], htons(PORTNO), &conxxxt, sizeof(conxxxt));
				}
			}
		}

		if (nStartCounter < USER_CHECK_COUNT)
			nStartCounter++;

		LeaveCriticalSection(&sem);

		Sleep(10000);
	}
}

static	VOID	SendData(u_long IPAddress, u_short portNo, VOID *buf, int len)
{
	socketAddr.sin_addr.s_addr = IPAddress;
	socketAddr.sin_port = portNo;
	if (sendto(sock, (LPCTSTR)buf, len, 0, (struct sockaddr *)&socketAddr, sizeof(struct sockaddr)) == SOCKET_ERROR) {
		socketAddr.sin_addr.s_addr = IPAddress;
		socketAddr.sin_port = portNo;
		sendto(sock, (LPCTSTR)buf, len, 0, (struct sockaddr *)&socketAddr, sizeof(struct sockaddr));
	}
}

static	VOID	SendConxxxt(LPSEND lpSend)
{
	CONxxxT	conxxxt;

	conxxxt.header.flag = FLAG_CONxxxT;
	conxxxt.header.id = localIPAddress;
	strcpy(conxxxt.userName, iniData.settings.serverName);
	conxxxt.modeFlag = (char)(MODE_REFLECT | MODE_VIDEOREQ);

	conxxxt.channelNo = lpSend->lpUser->channelNo;
	SendData(lpSend->IPAddress,lpSend->portNo, &conxxxt, sizeof(conxxxt));
}

static	VOID	ReadIniFile(VOID)
{
	static	char	iniFile[] = ".\\PCREF.INI";
	static	char	sectionSettings[] = "Settings";
	static	char	sectionUniCast[] = "Reflector";
	static	char	sectionAutoConxxxt[] = "AutoConxxxt";
	int	i;
	char	entry[32];
	struct	hostent	*hp;

	GetPrivateProfileString(sectionSettings, "ServerName", "PC-REFECT",
			iniData.settings.serverName,
			sizeof(iniData.settings.serverName),
			iniFile);

	GetPrivateProfileString(sectionSettings, "LogFile", "",
			iniData.settings.logFile,
			sizeof(iniData.settings.logFile),
			iniFile);

	GetPrivateProfileString(sectionSettings, "ChatLog", "",
			iniData.settings.chatLogFile,
			sizeof(iniData.settings.chatLogFile),
			iniFile);

	for (i = 0; i < MAX_UNICAST; i++) {
		sprintf(entry, "Host%d", i + 1);
		GetPrivateProfileString(sectionUniCast, entry, "",
				iniData.uniCast.host[i],
				sizeof(iniData.uniCast.host[0]),
				iniFile);
		if (iniData.uniCast.host[i][0] == '\0')
			break;
		hp = gethostbyname(iniData.uniCast.host[i]);
		if (hp != NULL)
			uniCastIPAddress[i] = *(u_long *)hp->h_addr;
	}
	nUniCast = i;

	for (i = 0; i < MAX_AUTOCONxxxT; i++) {
		sprintf(entry, "Host%d", i + 1);
		GetPrivateProfileString(sectionAutoConxxxt, entry, "",
				iniData.autoConxxxt.host[i],
				sizeof(iniData.autoConxxxt.host[0]),
				iniFile);
		if (iniData.autoConxxxt.host[i][0] == '\0')
			break;
		hp = gethostbyname(iniData.autoConxxxt.host[i]);
		if (hp != NULL)
			autoConxxxtIPAddress[i] = *(u_long *)hp->h_addr;
	}
	nAutoConxxxt = i;

	bLoginReq = GetPrivateProfileInt(sectionSettings, "Login", 0, iniFile);
}

static	VOID	WriteLog(LPUSER lpUser, int flag)
{
	FILE	*fp;
	long	ltime;
	struct	tm	*tp;
	char	ip1[20], ip2[20];

	if (iniData.settings.logFile[0] == '\0')
		return;

	if ((fp = fopen(iniData.settings.logFile, "a")) == NULL)
		return;

	time(&ltime);
	tp = localtime(&ltime);
	strcpy(ip1, inet_ntoa(*(struct in_addr *)&lpUser->id));
	strcpy(ip2, lpUser->lpSend ? inet_ntoa(*(struct in_addr *)&lpUser->lpSend->IPAddress) : "null");
	fprintf(fp, "%02u/%02u/%02u %02u:%02u:%02u [%c] %s %s %d %s %d\n",
			tp->tm_year, tp->tm_mon+1, tp->tm_mday,
			tp->tm_hour, tp->tm_min, tp->tm_sec,
			flag, ip1, ip2, ntohs(lpUser->portNo), lpUser->userName, lpUser->channelNo);
	fclose(fp);
}

static	VOID	CommandProc(LPVOID param)
{
	int	s, s2;
	int	addrLen;
	struct	sockaddr_in	SocketAddr;

	s = socket(PF_INET, SOCK_STREAM, 0);
	if (s < 0) {
		ErrorLog("socket() ErrorCode=%d", WSAGetLastError());
		return;
	}

	SocketAddr.sin_addr.s_addr = INADDR_ANY;
	SocketAddr.sin_family	= AF_INET;
	SocketAddr.sin_port	= htons(PORTNO);
	if (bind(s, (struct sockaddr *)&SocketAddr, sizeof(SocketAddr)) < 0) {
		closesocket(s);
		ErrorLog("bind() ErrorCode=%d", WSAGetLastError());
		return;
	}

	if (listen(s, 1) < 0) {
		closesocket(s);
		ErrorLog("listen() ErrorCode=%d", WSAGetLastError());
		return;
	}

	for (;;) {
		addrLen = sizeof(struct sockaddr);
		s2 = accept(s, (struct sockaddr *)&SocketAddr, &addrLen);
		if (s2 < 0)
			break;
		ReceiveCommand(s2);
		monitorSocket = -1;
		closesocket(s2);
	}
	closesocket(s);
}

static	VOID	ReceiveCommand(int s)
{
	int	n, count;
	char	buf;
	char	commandBuf[2048];
	BOOL	execFlag;
	static	char	msg[] = "Conxxxt PCREFLECT\r\n";

//	send(s, "\xff\xfd\x03", 3, 0);
	send(s, msg, strlen(msg), 0);
	send(s, "$ ", 2, 0);

	execFlag = FALSE;
	count = 0;
	while ((n = recv(s, &buf, 1, 0)) == 1) {
		send(s, &buf, 1, 0);
		if (buf == 0x0d || buf == 0x0a) {
			commandBuf[count] = '\0';
			count = 0;
			execFlag = TRUE;
		} else if (buf == 0x08) {
			if (count != 0)
				count--;
		} else if (buf < 0xf0 && buf >= 0x20 && count < sizeof(commandBuf)) {
			commandBuf[count++] = buf;
		}

		if (execFlag) {
			if (!ExecCommand(s, commandBuf))
				break;
			execFlag = FALSE;
			send(s, "\r\n$ ", 4, 0);
		}
	}
}

static	BOOL	ExecCommand(int s, char *command)
{
	LPUSER	lpUser;
	CONxxxT	conxxxt;
	char	*p;
	struct	hostent	*hp;
	char	ip1[20], ip2[20];
	u_long	addr;

	if ((p = strtok(command, " ")) == NULL)
		return(TRUE);

	CharUpper(p);

	EnterCriticalSection(&sem);

	if (strcmp(p, "CONxxxT") == 0) {
		while ((p = strtok(NULL, " ")) != NULL) {
			if ((hp = gethostbyname(p)) != NULL) {
				conxxxt.header.flag = FLAG_CONxxxT;
				conxxxt.header.id = localIPAddress;
				strcpy(conxxxt.userName, iniData.settings.serverName);
				conxxxt.modeFlag = (char)(MODE_REFLECT | MODE_VIDEOREQ);
				conxxxt.channelNo = 0;
				SendData(*(u_long *)hp->h_addr, htons(PORTNO2), &conxxxt, sizeof(conxxxt));
				SendData(*(u_long *)hp->h_addr, htons(PORTNO), &conxxxt, sizeof(conxxxt));
			}
		}
	} else if (strcmp(p, "DISCONxxxT") == 0) {
		while ((p = strtok(NULL, " ")) != NULL) {
			if ((addr = inet_addr(p)) != INADDR_NONE) {
				for (lpUser = lpTopUser; lpUser != NULL;
						lpUser = lpUser->lpChain) {
					if (lpUser->id == addr) {
						lpUser->lpSend->bDisconxxxt = TRUE;
						break;
					}
				}
			}
		}
	} else if (strcmp(p, "EXIT") == 0) {
		LeaveCriticalSection(&sem);
		return(FALSE);
	} else if (strcmp(p, "USER") == 0) {
		for (lpUser = lpTopUser; lpUser != NULL; lpUser = lpUser->lpChain) {
			strcpy(ip1, inet_ntoa(*(struct in_addr *)&lpUser->id));
			strcpy(ip2, inet_ntoa(*(struct in_addr *)&lpUser->lpSend->IPAddress));
			sockprintf(s, "%s\t%s\t%s\r\n", ip1, ip2, lpUser->userName);
		}
	} else if (strcmp(p, "MONITOR") == 0) {
		if (monitorSocket == -1)
			monitorSocket = s;
		else
			monitorSocket = -1;
	}
	LeaveCriticalSection(&sem);

	return(TRUE);
}

static	VOID	sockprintf(int s, char *fmt, ...)
{
	va_list vl;
	char	buf[2048];

	va_start(vl, fmt);
	vsprintf(buf, fmt, vl);
	send(s, buf, strlen(buf), 0);
}

static	VOID	ErrorLog(char *fmt, ...)
{
	va_list vl;
	FILE	*fp;
	long	ltime;
	struct	tm	*tm;

	va_start(vl, fmt);

	if ((fp = fopen("PCREF.LOG", "a")) != NULL) {
		time(&ltime);
		tm = localtime(&ltime);

		fprintf(fp, "%2u/%02u/%02u %2u:%02u:%02u [E] ",
				tm->tm_year, tm->tm_mon+1, tm->tm_mday,
				tm->tm_hour, tm->tm_min, tm->tm_sec);
		vfprintf(fp, fmt, vl);
		fprintf(fp, "\n");
		fclose(fp);
	}
}

static	int	ConvertVideoData(char *p1, char *p2, int len)
{
	char	*p, *sp2;
	int	blockWidth, n, pos, block;
	char	posBuf[256];

	if (!(p1[sizeof(HEADER)] & VIDEO_COMPXY)) {
		memcpy(p2, p1, len);
		return(len);
	}

	sp2 = p2;

	memcpy(p2, p1, sizeof(HEADER));
	p1 += sizeof(HEADER);
	p2 += sizeof(HEADER);

	*p2++ = (*p1++ & ~VIDEO_COMPXY);
	blockWidth = *p2++ = *p1++;
	*p2++ = *p1++;
	*p2++ = *p1++;
	block = *p2++ = *p1++;

	n = *p1++;
	Decompress(p1, posBuf, n);
	p1 += n;
	len -= 6 + n;
	p = posBuf;
	pos = -1;
	while (block > 0) {
		if ((n = *p++) == 0) {
			n = *((short *)p);
			p += 2;
		}
		pos += n;
		*p2++ = (pos % blockWidth);
		*p2++ = (pos / blockWidth);
		block--;
	}

	memcpy(p2, p1, len);
	p2 += len;

	return(p2 - sp2);
}

static	int	Decompress(char *src, char *dst, int len)
{
	int	count;
	char	*sp;

	sp = dst;
	while (--len >= 0) {
		if ((*src & 0xf0) == 0xf0) {
			count = *src++ & 0x0f;
			while (--count >= 0)
				*dst++ = *src;
			src++;
			len--;
		} else
			*dst++ = *src++;
	}

	return(dst - sp);
}
