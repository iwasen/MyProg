#include <windows.h>
#include <stdio.h>
#include <stdlib.h>
#include <memory.h>
#include <process.h>

#define	IND_WILL	251
#define	IND_WONT	252
#define	IND_DO		253
#define	IND_DONT	254

static	VOID	Listen(LPVOID);
static	int	TelnetService(int);
static	VOID	Telnet(int);
static	VOID	SendConxxxtMessage(int);
static	HANDLE	Login(int);
static	VOID	Receive(LPVOID);
static	int	RecvChar(int, BOOL);
static	int	RecvLine(int, char *, int, BOOL);
static	VOID	ErrorMsg(char *);

static	HANDLE	hThread;
static	HANDLE	hRead1, hWrite1;
static	HANDLE	hRead2, hWrite2;
static	HANDLE	hProcess;
static	char	*exeFile;
static	char	cmdBuf[256], *cmdPtr;
static	int	cmdLen;

/*=======================================================================
 |
 |		telnetdサービス関数
 |
 |	VOID	StartTelnetd(dwArgc, lpszArgv)
 |
 =======================================================================*/
VOID	StartTelnetd()
{
	static	CHAR	funcName[] = "StartTelnetd";
	HANDLE hThreadTCPIP;

	if ((hThreadTCPIP = _beginthread(Listen, 0, NULL)) == -1) {
		ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
	}
}

static	VOID	Listen(LPVOID lpVoid)
{
	int	err;
	struct	sockaddr_in	SocketAddr;
	int	addrLen;
	int	s, s2;
	STARTUPINFO	siStartupInfo;
	PROCESS_INFORMATION	piProcInfo;
	char	param[20];

	s = socket(PF_INET, SOCK_STREAM, IPPROTO_TCP);
	if (s < 0) {
		ErrorMsg("err : socket");
		return;
	}

	SocketAddr.sin_addr.s_addr = INADDR_ANY;
	SocketAddr.sin_family      = AF_INET;
	SocketAddr.sin_port        = htons(23);
	err = bind(s, (struct sockaddr *)&SocketAddr, sizeof(SocketAddr));
	if (err != 0) {
		ErrorMsg("err : bind");
		closesocket(s);
		return;
	}

	if (listen(s, 3) < 0) {
		ErrorMsg("err : listen");
		closesocket(s);
		return;
	}

	for (;;) {
		addrLen = sizeof(SocketAddr);
		s2 = accept(s, (struct sockaddr *)&SocketAddr, &addrLen);
		if (s2 < 0) {
			ErrorMsg("err : accept");
			break;
		}
		memset(&siStartupInfo, 0, sizeof(siStartupInfo));
		siStartupInfo.cb = sizeof(siStartupInfo);
		sprintf(param, "%s -SOCKET %d", exeFile, s2);
		if (CreateProcess(NULL, param, NULL, NULL, TRUE,
				NORMAL_PRIORITY_CLASS,
				NULL, NULL, &siStartupInfo, &piProcInfo)) {
			CloseHandle(piProcInfo.hProcess);
			CloseHandle(piProcInfo.hThread);
		}
		closesocket(s2);
	}
	closesocket(s);
}

static	int	TelnetService(int s)
{
	WSADATA	WSAData;

	SetErrorMode(SEM_FAILCRITICALERRORS | SEM_NOOPENFILEERRORBOX);

	WSAStartup(MAKEWORD(1, 1), &WSAData);

	Telnet(s);
	closesocket(s);

	WSACleanup();

	return(0);
}

static	VOID	Telnet(int s)
{
	STARTUPINFO	siStartupInfo;
	PROCESS_INFORMATION	piProcInfo;
	SECURITY_ATTRIBUTES	sa;
	char	buf[80];
	DWORD	numBytes;
	HANDLE	hTaken;
	char	homePath[128];
	int	i;

	SendConxxxtMessage(s);

	if ((hTaken = Login(s)) == NULL)
		return;

	sa.nLength = sizeof(sa);
	sa.lpSecurityDescriptor = NULL;
	sa.bInheritHandle = TRUE;

	if (!CreatePipe(&hRead1, &hWrite1, &sa, 0))
		return;
	if (!CreatePipe(&hRead2, &hWrite2, &sa, 0))
		return;

	if (_beginthread(Receive, 0, (LPVOID)s) == -1)
		return;

	memset(&siStartupInfo, 0, sizeof(siStartupInfo));
	siStartupInfo.cb = sizeof(siStartupInfo);
	siStartupInfo.dwFlags = STARTF_USESTDHANDLES;
	siStartupInfo.hStdInput = hRead1;
	siStartupInfo.hStdOutput = hWrite2;
	siStartupInfo.hStdError = hWrite2;
	GetEnvironmentVariable("SystemDrive", homePath, sizeof(homePath));
	strcat(homePath, "\\");
	if (!CreateProcessAsUser(hTaken, NULL, "CMD.EXE", NULL, NULL, TRUE,
				NORMAL_PRIORITY_CLASS | CREATE_NEW_CONSOLE,
				NULL, homePath, &siStartupInfo, &piProcInfo))
		return;

	hProcess = piProcInfo.hProcess;

	CloseHandle(hRead1);
	CloseHandle(hWrite2);

	for (;;) {
		if (ReadFile(hRead2, buf, sizeof(buf), &numBytes, NULL)) {
			for (i = 0; i < cmdLen && i < (int)numBytes; i++) {
				if (*cmdPtr++ != buf[i]) {
					cmdLen = 0;
					break;
				}
			}
			cmdLen -= i;
			numBytes -= i;
			if (numBytes != 0) {
				if (send(s, buf + i, numBytes, 0) !=
								(int)numBytes)
					break;
			}
		} else
			break;
	}

	hProcess = NULL;
	CloseHandle(piProcInfo.hProcess);
	CloseHandle(piProcInfo.hThread);
	CloseHandle(hTaken);
}

static	VOID	Receive(LPVOID param)
{
	int	s;
	int	n;
	DWORD	numBytes;

	s = (int)param;

	for (;;) {
		if ((n = RecvLine(s, cmdBuf, sizeof(cmdBuf) - 2, TRUE)) == -1)
			break;

		cmdBuf[n++] = 0x0d;
		cmdBuf[n++] = 0x0a;
		cmdPtr = cmdBuf;
		cmdLen = n;

		if (!WriteFile(hWrite1, cmdBuf, n, &numBytes, NULL))
			break;
	}

	if (hProcess != NULL)
		TerminateProcess(hProcess, 0);
}

static	VOID	SendConxxxtMessage(int s)
{
	char	computerName[32];
	DWORD	nameSize;
	char	buf[100];


	nameSize = sizeof(computerName);
	GetComputerName(computerName, &nameSize);
	sprintf(buf, "\x0d\x0aWindows NT telnet server (%s)\x0d\x0a\x0d\x0a",
							computerName);

	send(s, buf, strlen(buf), 0);
	send(s, "\xff\xfe\x01", 3, 0);
	send(s, "\xff\xfb\x01", 3, 0);
}

static	HANDLE	Login(int s)
{
	int	i;
	HANDLE	hToken;
	char	login[80];
	char	password[80];

	for (i = 0; i < 3; i++) {
		send(s, "login: ", 8, 0);
		if (RecvLine(s, login, sizeof(login), TRUE) == -1)
			return(NULL);

		send(s, "Password:", 9, 0);
		if (RecvLine(s, password, sizeof(password), FALSE) == -1)
			return(NULL);

		if (LogonUser(login, NULL, password, LOGON32_LOGON_INTERACTIVE,
					LOGON32_PROVIDER_DEFAULT, &hToken)) {
			send(s, "\x0d\x0a", 2, 0);
			return(hToken);
		}

		send(s, "Login incorrect\x0d\x0a", 17, 0);
	}

	return(NULL);
}

static	int	RecvChar(int s, BOOL echo)
{
	int	j;
	char	c;
	char	indication;
	char	command;
	char	sendBuf[32];

	for (;;) {
		if (recv(s, &c, 1, 0) != 1)
			return(-1);

		if (c != 0xff) {
			if (echo)
				send(s, &c, 1, 0);
			return(c);
		} else {
			j = 0;

			if (recv(s, &indication, 1, 0) != 1)
				return(-1);
			if (indication == 0xff) {
				if (echo)
					send(s, &c, 1, 0);
				return(c);
			}

			if (recv(s, &command, 1, 0) != 1)
				return(-1);
			switch (command) {
			case 0x01:	// echo
				break;
			case 0x03:
				sendBuf[j++] = 0xff;
				switch (indication) {
				case IND_WILL:
				case IND_WONT:
					sendBuf[j++] = IND_DO;
					break;
				case IND_DO:
				case IND_DONT:
					sendBuf[j++] = IND_WILL;
					break;
				}
				sendBuf[j++] = command;
				break;
			default:
				sendBuf[j++] = 0xff;
				switch (indication) {
				case IND_WILL:
				case IND_WONT:
					sendBuf[j++] = IND_DONT;
					break;
				case IND_DO:
				case IND_DONT:
					sendBuf[j++] = IND_WONT;
					break;
				}
				sendBuf[j++] = command;
				break;
			}

			if (j != 0)
				send(s, sendBuf, j, 0);
		}
	}
}

static	int	RecvLine(int s, char *buf, int bufSize, BOOL echo)
{
	int	n;
	int	c;

	n = 0;
	for (;;) {
		if ((c = RecvChar(s, FALSE)) == -1)
			return(-1);
		switch (c) {
		case 0x08:		// BS
			if (n != 0) {
				n--;
				if (echo)
					send(s, "\x08 \x08", 3, 0);
			}
			break;
		case 0x00:		// NULL
		case 0x0a:		// LF
			break;
		case 0x0d:		// CR
			send(s, "\x0d\x0a", 2, 0);
			buf[n] = '\0';
			return(n);
		default:
			if (n < bufSize - 1) {
				if (echo)
					send(s, (char *)&c, 1, 0);
				buf[n++] = c;
			}
			break;
		}
	}
}

static	VOID	ErrorMsg(char *msg)
{
	FILE	*fp;

	if ((fp = fopen("telnetd.log", "a")) != NULL) {
		fprintf(fp, "%s %d\n", msg, WSAGetLastError());
		fclose(fp);
	}
	SetEvent(hServDoneEvent);
}
