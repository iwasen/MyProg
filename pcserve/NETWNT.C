/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �l�b�g���[�N�A�N�Z�X���� (Windows NT)
 *		�t�@�C����	: netwnt.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <process.h>
#include "pcssock.h"
#include "pcserve.h"

static	USHORT	conxxxtCount[4];
static	LONG	hThreadTCPIP = -1;

static	VOID	ThreadNetBIOS(LPVOID);
static	VOID	ConxxxtNetBIOS(HANDLE);
static	VOID	ThreadTCPIP(LPVOID);
static	VOID	ConxxxtTCPIP(int);
static	VOID	ThreadRS232C(int);
static	BOOL	ConxxxtRS232C(PRS232C);
static	VOID	DisconxxxtRS232C(PRS232C);
static	HANDLE	OpenRS232C(int);
static	VOID	ExecRS232C(PRS232C);
static	BOOL	ReceiveRS232CChannel(PRS232C);
static	PSCB	LoginRS232C(PRS232C);
static	VOID	LogoutRS232C(PSCB);
static	VOID	SendModemCommand(PRS232C, CHAR *, BOOL);
static	VOID	ReceiveModem(PRS232C, CHAR *, int);
static	VOID	ClearRcvQue(PRS232C);
static	BOOL	SendRS232C(PRS232C, CHAR *, int);
static	BOOL	SendBlockRS232C(PRS232C, CHAR *, int);
static	BOOL	ReceiveRS232C(PRS232C, CHAR *, int);
static	BOOL	ReceiveBlockRS232C(PRS232C);
static	VOID	InitSCB(PSCB);
static	VOID	Command(PSCB);

/*=======================================================================
 |
 |		NetBIOS �����J�n
 |
 |	VOID	StartNetBIOS()
 |
 =======================================================================*/
VOID	StartNetBIOS(VOID)
{
	static	CHAR	funcName[] = "StartNetBIOS";

	if (_beginthread(ThreadNetBIOS, 0, NULL) == -1) {
		ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
	}

	if (_beginthread(ThreadNetBIOS, 0, NULL) == -1) {
		ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
	}
}

/*=======================================================================
 |
 |		NetBIOS �ڑ��Ď��X���b�h
 |
 |	VOID	ThreadNetBIOS(param)
 |
 |		LPVOID	param;		�_�~�[�p�����[�^
 |
 =======================================================================*/
static	VOID	ThreadNetBIOS(LPVOID param)
{
	static	CHAR	funcName[] = "ThreadNetBIOS";
	HANDLE	hPipe;
	CHAR	pipeName[128];
	PSECURITY_DESCRIPTOR	pSD;
	SECURITY_ATTRIBUTES	sa;
	DWORD	errorCode;

	pSD = (PSECURITY_DESCRIPTOR)LocalAlloc(LPTR,
					SECURITY_DESCRIPTOR_MIN_LENGTH);

	if (pSD == NULL) {
		ErrorLog(NULL, "LocalAlloc() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		return;
	}

	if (!InitializeSecurityDescriptor(pSD, SECURITY_DESCRIPTOR_REVISION)) {
		ErrorLog(NULL,
			"InitializeSecurityDescriptor() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		LocalFree((HLOCAL)pSD);
		return;
	}

	if (!SetSecurityDescriptorDacl(pSD, TRUE, (PACL) NULL, FALSE)) {
		ErrorLog(NULL, "SetSecurityDescriptorDacl() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		LocalFree((HLOCAL)pSD);
		return;
	}

	sa.nLength = sizeof(sa);
	sa.lpSecurityDescriptor = pSD;
	sa.bInheritHandle = TRUE;

	while (active) {
		sprintf(pipeName, "\\\\.%s", ini.pipeName);
		hPipe = CreateNamedPipe (pipeName,
				PIPE_ACCESS_DUPLEX,
				PIPE_WAIT | PIPE_READMODE_BYTE
				| PIPE_TYPE_BYTE, PIPE_UNLIMITED_INSTANCES,
				1024, 1024, 5000, &sa);
		if (hPipe == INVALID_HANDLE_VALUE) {
			ErrorLog(NULL, "CreateNamedPipe() ErrorCode=%d (%s)",
						GetLastError(), funcName);
			return;
		}

		if (!ConxxxtNamedPipe(hPipe, NULL)) {
			errorCode = GetLastError();
			if (errorCode != ERROR_PIPE_CONxxxTED) {
				ErrorLog(NULL,
					"ConxxxtNamedPipe() ErrorCode=%d (%s)",
						errorCode, funcName);
				CloseHandle(hPipe);
				continue;
			}
		}

		/* �X���b�h�̋N�� */
		if (_beginthread(ConxxxtNetBIOS, 0, hPipe) == -1) {
			ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		}
	}

	_endthread();
}

/*=======================================================================
 |
 |		NetBIOS �ڑ�����
 |
 |	VOID	ConxxxtNetBIOS(hPipe)
 |
 |		HANDLE	hPipe;		�p�C�v�n���h��
 |
 =======================================================================*/
static	VOID	ConxxxtNetBIOS(HANDLE hPipe)
{
	SCB	scb;

	InitSCB(&scb);
	scb.chType = CHT_NETBIOS;
	scb.handle = (int)hPipe;

	Command(&scb);

	FlushFileBuffers(hPipe);
	DisconxxxtNamedPipe(hPipe);
	CloseHandle(hPipe);

	_endthread();
}

/*=======================================================================
 |
 |		TCP/IP �����J�n
 |
 |	VOID	StartTCPIP()
 |
 =======================================================================*/
VOID	StartTCPIP(VOID)
{
	static	CHAR	funcName[] = "StartTCPIP";

	if ((hThreadTCPIP = _beginthread(ThreadTCPIP, 0, NULL)) == -1) {
		ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
	}
}

/*=======================================================================
 |
 |		TCP/IP �����I��
 |
 |	VOID	TerminateTCPIP()
 |
 =======================================================================*/
VOID	TerminateTCPIP(VOID)
{
//	if (hThreadTCPIP != -1)
//		TerminateThread((HANDLE)hThreadTCPIP, 0);

	if (sock != -1) {
		closesocket(sock);
		sock = -1;
	}
}

/*=======================================================================
 |
 |		TCP/IP �ڑ��Ď��X���b�h
 |
 |	VOID	ThradTCPIP(param)
 |
 |		LPVOID	param;		�_�~�[�p�����[�^
 |
 =======================================================================*/
static	VOID	ThreadTCPIP(LPVOID param)
{
	static	CHAR	funcName[] = "ThreadTCPIP";
	int	sock2;
	int	addrLen;
	struct	sockaddr_in	SocketAddr;
//	int	acceptErrorCount = 0;

	sock = socket(PF_INET, SOCK_STREAM, 0);
	if (sock < 0) {
		ErrorLog(NULL, "socket() ErrorCode=%d (%s)",
					WSAGetLastError(), funcName);
		return;
	}

	SocketAddr.sin_addr.s_addr = INADDR_ANY;
	SocketAddr.sin_family	= AF_INET;
	SocketAddr.sin_port	= htons(ini.portNo);
	if (bind(sock, (struct sockaddr *)&SocketAddr,
						sizeof(SocketAddr)) < 0) {
		closesocket(sock);
		sock = -1;
		ErrorLog(NULL, "bind() ErrorCode=%d (%s)",
					WSAGetLastError(), funcName);
		return;
	}

	if (listen(sock, 3) < 0) {
		closesocket(sock);
		sock = -1;
		ErrorLog(NULL, "listen() ErrorCode=%d (%s)",
					WSAGetLastError(), funcName);
		return;
	}

	while (active) {
		addrLen = sizeof(struct sockaddr);
		sock2 = accept(sock, (struct sockaddr *)&SocketAddr, &addrLen);
		if (sock2 < 0) {
			Sleep(1000);
			continue;
//			ErrorLog(NULL, "accept() ErrorCode=%d (%s)",
//						WSAGetLastError(), funcName);
//			if (++acceptErrorCount > 10)
//				break;
//			else
//				continue;
		}
//		acceptErrorCount = 0;

		/* �X���b�h�̋N�� */
		if (_beginthread((void(*)(void *))ConxxxtTCPIP, 0,
							(LPVOID)sock2) == -1) {
			ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		}
	}

	if (sock != -1) {
		closesocket(sock);
		sock = -1;
	}

	_endthread();
}

/*=======================================================================
 |
 |		TCP/IP �ڑ�����
 |
 |	VOID	ConxxxtTCPIP(s)
 |
 |		int	s;		�\�P�b�g�ԍ�
 |
 =======================================================================*/
static	VOID	ConxxxtTCPIP(int s)
{
	SCB	scb;
	int	opt;

	InitSCB(&scb);
	scb.chType = CHT_TCPIP;
	scb.handle = s;

	opt = 1;
	setsockopt(s, SOL_SOCKET, SO_KEEPALIVE, (CHAR *)&opt, sizeof(opt));

	Command(&scb);

	shutdown(s, 2);
	closesocket(s);

	_endthread();
}

/*=======================================================================
 |
 |		RS-232C �����J�n
 |
 |	VOID	StartRS232C()
 |
 =======================================================================*/
VOID	StartRS232C(VOID)
{
	static	CHAR	funcName[] = "StartRS232C";
	int	portNo;

	for (portNo = 0; portNo < N_RS232C; portNo++) {
		if (ini.rsParam[portNo].conxxxt == RS232C_NOTUSE)
			continue;

		if (_beginthread((void(*)(void *))ThreadRS232C, 0,
						(LPVOID)portNo) == -1) {
			ErrorLog(NULL, "_beginthread() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		}
	}
}

/*=======================================================================
 |
 |		RS-232C �����X���b�h
 |
 |	VOID	ThreadRS232C(port)
 |
 |		int	port;		�|�[�g�ԍ�
 |
 =======================================================================*/
static	VOID	ThreadRS232C(int portNo)
{
	static	CHAR	funcName[] = "ThreadRS232C";
	RS232C	rs232c;

	memset(&rs232c, 0, sizeof(rs232c));

	rs232c.portNo = portNo;

	if ((rs232c.handle = OpenRS232C(portNo)) == (HANDLE)-1)
		return;

	if ((rs232c.pRcvBuf = malloc(RS_BLOCKSIZE)) == NULL) {
		ErrorLog(NULL, "malloc() AllocSize=%d (%s)",
						RS_BLOCKSIZE, funcName);
		CloseHandle(rs232c.handle);
		return;
	}

	while (active) {
		if (ConxxxtRS232C(&rs232c)) {
			ExecRS232C(&rs232c);
			DisconxxxtRS232C(&rs232c);
		}
	}

	CloseHandle(rs232c.handle);

	free(rs232c.pRcvBuf);
}

/*=======================================================================
 |
 |		RS232C �������s
 |
 |	VOID	ExecRS232C(rsp)
 |
 |		PRS232C	rsp;		RS232C �Ǘ����
 |
 =======================================================================*/
static	VOID	ExecRS232C(PRS232C rsp)
{
	PSCB	pSCB;
	int	count;
	int	chNo;

	rsp->chNo = 0;
	rsp->rcvLength = 0;
	rsp->readCounter = 0;
	rsp->bStxRcvFlag = FALSE;

	count = 0;
	while (active) {
		if (!ReceiveRS232CChannel(rsp))
			break;

		if ((pSCB = rsp->pSCB[rsp->chNo]) == NULL) {
			if ((pSCB = LoginRS232C(rsp)) == NULL)
				continue;
			rsp->pSCB[rsp->chNo] = pSCB;
			count++;
		}

		if (!CommandService(pSCB)) {
			LogoutRS232C(pSCB);
			rsp->pSCB[rsp->chNo] = NULL;
			count--;
		}

		if (count == 0)
			break;
	}

	for (chNo = 0; chNo < MAX_RS232C_CHANNEL; chNo++) {
		if ((pSCB = rsp->pSCB[chNo]) != NULL) {
			LogoutRS232C(pSCB);
			rsp->pSCB[chNo] = NULL;
		}
	}
}

/*=======================================================================
 |
 |		RS232C �`���l���ԍ���M����
 |
 |	BOOL	ReceiveRS232CChannel(rsp)
 |
 |		PRS232C	rsp;		RS232C �Ǘ����
 |
 |		BOOL	�Ԓl		TRUE: ����@FALSE: �G���[
 |
 =======================================================================*/
static	BOOL	ReceiveRS232CChannel(PRS232C rsp)
{
	DWORD	fModemStatus;
	DWORD	numBytes;
	CHAR	buf[2];

	for (;;) {
		if (ini.rsParam[rsp->portNo].conxxxt == RS232C_MODEM) {
			GetCommModemStatus(rsp->handle, &fModemStatus);
			if (!(fModemStatus & MS_RLSD_ON))
				return FALSE;
		} else if (ini.rsParam[rsp->portNo].conxxxt == RS232C_DIRECT) {
			GetCommModemStatus(rsp->handle, &fModemStatus);
			if (!(fModemStatus & MS_DSR_ON))
				return FALSE;
		}

		if (!ReadFile(rsp->handle, buf, 1, &numBytes, NULL))
			return FALSE;

		if (numBytes == 0)
			continue;

		if (buf[0] == RS_SOH) {
			if (!ReadFile(rsp->handle, buf, 1, &numBytes, NULL))
				return FALSE;
			if (numBytes == 0)
				continue;
			if ((buf[0] & 0xf0) != 0x30)
				continue;

			if (!ReadFile(rsp->handle, &buf[1], 1, &numBytes,
									NULL))
				return FALSE;
			if (numBytes == 0)
				continue;
			if ((CHAR)~buf[0] != buf[1])
				continue;

			rsp->chNo = (buf[0] & 0x0f);

			buf[0] = RS_ACK;
			if (!WriteFile(rsp->handle, buf, 1, &numBytes, NULL))
				return FALSE;
			break;
		} else if (buf[0] == RS_STX) {
			rsp->bStxRcvFlag = TRUE;
			break;
		}
	}

	return TRUE;
}

/*=======================================================================
 |
 |		RS232C ���O�C������
 |
 |	PSCB	LoginRS232C(rsp)
 |
 |		PRS232C	rsp;		RS232C �Ǘ����
 |
 |		PSCB	�Ԓl		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
static	PSCB	LoginRS232C(PRS232C rsp)
{
	PSCB	pSCB;

	if ((pSCB = malloc(sizeof(SCB))) == NULL)
		return NULL;

	InitSCB(pSCB);

	/* �f�[�^�o�b�t�@(64KB)�m�� */
	if ((pSCB->lpData = malloc(1024*64 + sizeof(RESPONSE))) == NULL) {
		free(pSCB);
		return NULL;
	}

	pSCB->lpData += sizeof(RESPONSE);
	pSCB->chType = CHT_RS232C;
	pSCB->handle = rsp->chNo;
	pSCB->rsp = rsp;

	/* SCB�`�F�C�� */
	ENTER_CRITICAL_SECTION(&csSCB);

	pSCB->chainSCB = pTopSCB;
	pTopSCB = pSCB;

	statistics.conxxxtCount[pSCB->chType]++;

	if (++conxxxtCount[0] > statistics.maxConxxxt[0])
		statistics.maxConxxxt[0] = conxxxtCount[0];

	if (++conxxxtCount[pSCB->chType] > statistics.maxConxxxt[pSCB->chType])
		statistics.maxConxxxt[pSCB->chType] =
					conxxxtCount[pSCB->chType];

	LEAVE_CRITICAL_SECTION(&csSCB);

	return pSCB;
}

/*=======================================================================
 |
 |		RS232C ���O�A�E�g����
 |
 |	VOID	LogoutRS232C(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
static	VOID	LogoutRS232C(PSCB pSCB)
{
	PSCB	*pPSCB;

	/* SCB�`�F�C���؂藣�� */
	ENTER_CRITICAL_SECTION(&csSCB);
	for (pPSCB = &pTopSCB; *pPSCB != 0; pPSCB = &(*pPSCB)->chainSCB) {
		if (*pPSCB == pSCB) {
			*pPSCB = (*pPSCB)->chainSCB;
			break;
		}
	}
	conxxxtCount[0]--;
	conxxxtCount[pSCB->chType]--;
	LEAVE_CRITICAL_SECTION(&csSCB);

	DisconxxxtClient(pSCB);

	free(pSCB->lpData - sizeof(RESPONSE));
	free(pSCB);
}

/*=======================================================================
 |
 |		RS-232C �I�[�v������
 |
 |	HANDLE	OpenRS232C(portNo)
 |
 |		int	portNo;		�|�[�g�ԍ�
 |
 |		HANDLE	�Ԓl		�n���h��
 |
 =======================================================================*/
static	HANDLE	OpenRS232C(int portNo)
{
	static	CHAR	funcName[] = "OpenRS232C";
	HANDLE		fileHndl;
	CHAR	portName[6];
	DCB	dcb;
	COMMTIMEOUTS	ctmo;

	/* open the com port */
	sprintf(portName, "COM%d", portNo + 1);
	if ((fileHndl = CreateFile(portName, GENERIC_READ | GENERIC_WRITE, 0,
			NULL, OPEN_EXISTING, 0, NULL))
			== INVALID_HANDLE_VALUE) {
		ErrorLog(NULL, "CreateFile() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		return (HANDLE)-1;
	}

	if (!SetupComm(fileHndl, 1200, 1200)) {
		ErrorLog(NULL, "SetupComm() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		CloseHandle(fileHndl);
		return (HANDLE)-1;
	}

	memset(&dcb, 0, sizeof(dcb));
	dcb.DCBlength = sizeof(DCB);
	dcb.BaudRate = ini.rsParam[portNo].baudRate;
	dcb.fBinary = TRUE;
	dcb.fOutxCtsFlow = TRUE;
	dcb.fDtrControl = DTR_CONTROL_ENABLE;
	dcb.fRtsControl = RTS_CONTROL_ENABLE;
	dcb.ByteSize = 8;
	dcb.Parity = NOPARITY;
	dcb.StopBits = ONESTOPBIT;
	dcb.XonChar = 0x11;
	dcb.XoffChar = 0x13;
	if (!SetCommState(fileHndl, &dcb)) {
		ErrorLog(NULL, "SetCommState() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		CloseHandle(fileHndl);
		return (HANDLE)-1;
	}

	memset(&ctmo, 0, sizeof(ctmo));
	ctmo.ReadTotalTimeoutConstant = 3000;
	ctmo.WriteTotalTimeoutConstant = 3000;
	if (!SetCommTimeouts(fileHndl, &ctmo)) {
		ErrorLog(NULL, "SetCommTimeouts() ErrorCode=%d (%s)",
						GetLastError(), funcName);
		CloseHandle(fileHndl);
		return (HANDLE)-1;
	}

	return fileHndl;
}

/*=======================================================================
 |
 |		RS-232C ����ڑ�����
 |
 |	BOOL	ConxxxtRS232C(rsp)
 |
 |		PRS232C	rsp;		RS232C ���
 |
 |		BOOL	�Ԓl		TRUE �F�ڑ�����
 |					FALSE�F���ڑ�
 |
 =======================================================================*/
static	BOOL	ConxxxtRS232C(PRS232C rsp)
{
	static	BOOL	cts[N_RS232C];
	DWORD	fModemStatus;
	int	i;
	BOOL	callFlag;
	BOOL	conxxxtFlag;
	CHAR	rcvBuf[11];

	conxxxtFlag = FALSE;
	callFlag = FALSE;
	if (ini.rsParam[rsp->portNo].conxxxt == RS232C_MODEM) {
		GetCommModemStatus(rsp->handle, &fModemStatus);
		if (fModemStatus & MS_CTS_ON) {
			if (cts[rsp->portNo] == FALSE) {
				SendModemCommand(rsp->handle,
					ini.rsParam[rsp->portNo].modem.initCmd,
					TRUE);
			}
			cts[rsp->portNo] = TRUE;
		} else
			cts[rsp->portNo] = FALSE;

		ReceiveModem(rsp->handle, rcvBuf, sizeof(rcvBuf));

		switch (ini.rsParam[rsp->portNo].modem.detectCall) {
		case 'A':
			if (fModemStatus & MS_RLSD_ON)
				conxxxtFlag = TRUE;
			break;
		case 'R':
			if (fModemStatus & MS_RING_ON)
				callFlag = TRUE;
			break;
		case 'M':
			if (strcmp(rcvBuf,
					ini.rsParam[rsp->portNo].modem.ringMsg)
					== 0)
				callFlag = TRUE;
			break;
		}

		if (callFlag) {
			Sleep(1000);
			SendModemCommand(rsp->handle,
				ini.rsParam[rsp->portNo].modem.conxxxtCmd,
				TRUE);
			for (i = 0; i < 40; i++) {
				Sleep(1000);
				GetCommModemStatus(rsp->handle, &fModemStatus);
				if (fModemStatus & MS_RLSD_ON)
					break;
			}
			ClearRcvQue(rsp);
			if (i < 40)
				conxxxtFlag = TRUE;
			else
				DisconxxxtRS232C(rsp);
		}
	} else {
		Sleep(1000);
		GetCommModemStatus(rsp->handle, &fModemStatus);
		if (fModemStatus & MS_DSR_ON)
			conxxxtFlag = TRUE;
	}

	return conxxxtFlag;
}

/*=======================================================================
 |
 |		RS-232C ����ؒf����
 |
 |	VOID	DisconxxxtRS232C(rsp)
 |
 |		PRS232C	rsp;		RS232C ���
 |
 =======================================================================*/
VOID	DisconxxxtRS232C(PRS232C rsp)
{
	DWORD	fModemStatus;
	MODEM	*mp;

	mp = &ini.rsParam[rsp->portNo].modem;

	if (ini.rsParam[rsp->portNo].conxxxt == RS232C_MODEM) {
		GetCommModemStatus(rsp->handle, &fModemStatus);
		if (fModemStatus & MS_RLSD_ON)
			Sleep(5000);

		switch (mp->disconxxxt) {
		case 'D':
			EscapeCommFunction(rsp->handle, CLRDTR);
			Sleep(mp->dtrOffTime * 1000L);
			EscapeCommFunction(rsp->handle, SETDTR);
			break;
		case 'C':
			Sleep(mp->guardTime * 1000L);
			SendModemCommand(rsp->handle, mp->escCode, FALSE);
			Sleep(mp->guardTime * 1000L);
			SendModemCommand(rsp->handle, mp->discCmd, TRUE);
			Sleep(1000);
			break;
		}
		ClearRcvQue(rsp);
	}
}

/*=======================================================================
 |
 |		���f���ɑ΂���R�}���h���M����
 |
 |	VOID	SendModemCommand(rsp, command, CRFlag)
 |
 |		PRS232C	rsp;		RS232C ���
 |		CHAR	*command;	���M����R�}���h
 |		BOOL	CRFlag;		�O�F�b�q��t�����Ȃ��@�P�F�t������
 |
 =======================================================================*/
static	VOID	SendModemCommand(PRS232C rsp, CHAR *cp, BOOL CRFlag)
{
	static	CHAR	cr[1] = {0x0d};
	DWORD	numBytes;

	for (;;) {
		for ( ; *cp != '|' && *cp != '\0'; cp++) {
			WriteFile(rsp->handle, cp, 1, &numBytes, NULL);
			Sleep(100);
		}
		if (CRFlag) {
			WriteFile(rsp->handle, cr, 1, &numBytes, NULL);
			Sleep(1000);
		}
		ClearRcvQue(rsp);

		if (*cp == '|')
			cp++;
		else if (*cp == '\0')
			break;
	}
}

/*=======================================================================
 |
 |		���f������̃��b�Z�[�W��M����
 |
 |	VOID	ReceiveModem(rsp, rcvBuf, bufSize)
 |
 |		PRS232C	rsp;		RS232C ���
 |		CHAR	*rcvBuf;	��M�o�b�t�@
 |		int	bufSize;	��M�o�b�t�@�T�C�Y
 |
 =======================================================================*/
VOID	ReceiveModem(PRS232C rsp, CHAR *rcvBuf, int bufSize)
{
	DWORD	numBytes;
	USHORT	rcvCount;
	CHAR	rcvChar;

	rcvCount = 0;
	for (;;) {
		ReadFile(rsp->handle, &rcvChar, 1, &numBytes, NULL);
		if (numBytes == 0)
			break;

		if (rcvChar == 0x0d) {
			break;
		} else {
			if (rcvChar >= 0x20) {
				if (rcvCount < bufSize-1) {
					rcvBuf[rcvCount++] = rcvChar;
				}
			}
		}
	}
	rcvBuf[rcvCount] = '\0';
}

/*=======================================================================
 |
 |		��M�L���[�N���A����
 |
 |	VOID	ClearRcvQue(rsp)
 |
 |		PRS232C	rsp;		RS232C ���
 |
 =======================================================================*/
static	VOID	ClearRcvQue(PRS232C rsp)
{
	PurgeComm(rsp->handle, PURGE_RXABORT | PURGE_RXCLEAR);
}

/*=======================================================================
 |
 |		RS-232C �f�[�^���M����
 |
 |	BOOL	SendRS232C(rsp, data, length)
 |
 |		PRS232C	rsp;		RS232C �Ǘ����
 |		CHAR	*data;		���M�f�[�^
 |		int	length;		���M�f�[�^��
 |
 |		BOOL	�Ԓl		TRUE �F���푗�M
 |					FALSE�F�^�C���A�E�g�܂��͉���f���o
 |
 =======================================================================*/
static	BOOL	SendRS232C(PRS232C rsp, CHAR *data, int length)
{
	UCHAR	checkSum;
	int	i;
	int	blockSize;
	CHAR	buf[RS_BLOCKSIZE+6], *bufp;

	while (length != 0) {
		blockSize = min(length, RS_BLOCKSIZE);

		checkSum = 0;
		for (i = 0; i < (int)blockSize; i++)
			checkSum += (UCHAR)data[i];

		bufp = buf;
		*bufp++ = RS_STX;
		*((USHORT *)bufp)++ = blockSize;
		memcpy(bufp, data, blockSize);
		bufp += blockSize;
		*bufp++ = RS_ETX;
		*bufp = checkSum;

		if (!SendBlockRS232C(rsp, buf, blockSize + 5))
			return FALSE;

		data += blockSize;
		length -= blockSize;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		RS-232C �P�u���b�N���M����
 |
 |	BOOL	SendBlockRS232C(rsp, bufp, length)
 |
 |		PRS232C	rsp;		RS232C �Ǘ����
 |		CHAR	*bufp;		���M�u���b�N
 |		int	length;		���M�u���b�N��
 |
 |		BOOL	�Ԓl		TRUE �F���푗�M
 |					FALSE�F�^�C���A�E�g�܂��͉���f���o
 |
 =======================================================================*/
static	BOOL	SendBlockRS232C(PRS232C rsp, CHAR *bufp, int length)
{
	int	retry;
	CHAR	buf[2];
	DWORD	fModemStatus;
	DWORD	numBytes;

	for (retry = 0; retry < RS_RETRY; retry++) {
		if (ini.rsParam[rsp->portNo].conxxxt == RS232C_MODEM) {
			GetCommModemStatus(rsp->handle, &fModemStatus);
			if (!(fModemStatus & MS_RLSD_ON))
				return FALSE;
		} else if (ini.rsParam[rsp->portNo].conxxxt ==
							RS232C_DIRECT) {
			GetCommModemStatus(rsp->handle, &fModemStatus);
			if (!(fModemStatus & MS_DSR_ON))
				return FALSE;
		}

		if (!WriteFile(rsp->handle, bufp, length, &numBytes, NULL))
			return FALSE;

		for (;;) {
			if (!ReadFile(rsp->handle, buf, 1, &numBytes, NULL))
				return FALSE;
			if (numBytes == 1) {
				if (buf[0] == RS_ACK)
					return TRUE;
				else if (buf[0] == RS_NAK)
					break;
			} else
				break;
		}
	}

	return FALSE;
}

/*=======================================================================
 |
 |		RS-232C �f�[�^��M����
 |
 |	BOOL	ReceiveRS232C(rsp, bufp, length)
 |
 |		PRS232C	rsp;		RS232C �Ǘ����
 |		CHAR	*bufp;		��M�o�b�t�@
 |		int	length;		��M�f�[�^��
 |
 |		BOOL	�Ԓl		TRUE �F���푗�M
 |					FALSE�F�^�C���A�E�g�܂��͉���f���o
 |
 =======================================================================*/
static	BOOL	ReceiveRS232C(PRS232C rsp, CHAR *bufp, int length)
{
	int	readLength;

	while (length != 0) {
		if (rsp->rcvLength == 0) {
			if (!ReceiveBlockRS232C(rsp))
				return FALSE;
		}

		readLength = min(length, rsp->rcvLength);

		memcpy(bufp, rsp->pRcvBuf + rsp->readCounter,
					readLength);

		rsp->readCounter += readLength;
		rsp->rcvLength -= readLength;

		bufp += readLength;
		length -= readLength;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		RS-232C �P�u���b�N��M����
 |
 |	BOOL	ReceiveBlockRS232C(rsp)
 |
 |		PRS232C	rsp;		RS232C �Ǘ����
 |
 |		BOOL	�Ԓl		TRUE �F���푗�M
 |					FALSE�F�^�C���A�E�g�܂��͉���f���o
 |
 =======================================================================*/
static	BOOL	ReceiveBlockRS232C(PRS232C rsp)
{
	DWORD	fModemStatus;
	DWORD	numBytes;
	CHAR	buf[2];
	int	i;
	USHORT	blockLength;
	UCHAR	checkSum;

	for (;;) {
		if (ini.rsParam[rsp->portNo].conxxxt == RS232C_MODEM) {
			GetCommModemStatus(rsp->handle, &fModemStatus);
			if (!(fModemStatus & MS_RLSD_ON))
				return FALSE;
		} else if (ini.rsParam[rsp->portNo].conxxxt ==
							RS232C_DIRECT) {
			GetCommModemStatus(rsp->handle, &fModemStatus);
			if (!(fModemStatus & MS_DSR_ON))
				return FALSE;
		}

		if (rsp->bStxRcvFlag) {
			numBytes = 1;
			buf[0] = RS_STX;
			rsp->bStxRcvFlag = FALSE;
		} else {
			if (!ReadFile(rsp->handle, buf, 1, &numBytes, NULL))
				return FALSE;
		}

		if (numBytes != 1 || buf[0] != RS_STX)
			continue;

		if (!ReadFile(rsp->handle, &blockLength, 2, &numBytes, NULL))
			return FALSE;

		if (numBytes != 2)
			continue;

		if (blockLength > RS_BLOCKSIZE)
			continue;

		if (!ReadFile(rsp->handle, rsp->pRcvBuf,
						blockLength, &numBytes, NULL))
			return FALSE;

		if (numBytes != blockLength)
			continue;

		if (!ReadFile(rsp->handle, buf, 1, &numBytes, NULL))
			return FALSE;

		if (numBytes != 1 || buf[0] != RS_ETX)
			continue;

		if (!ReadFile(rsp->handle, &checkSum, 1, &numBytes, NULL))
			return FALSE;

		if (numBytes != 1)
			continue;

		for (i = 0; i < (int)blockLength; i++)
			checkSum -= (UCHAR)rsp->pRcvBuf[i];

		if (checkSum == 0) {
			buf[0] = RS_ACK;
			WriteFile(rsp->handle, buf, 1, &numBytes, NULL);
			break;
		} else {
			buf[0] = RS_NAK;
			WriteFile(rsp->handle, buf, 1, &numBytes, NULL);
		}
	}

	rsp->rcvLength = blockLength;
	rsp->readCounter = 0;

	return TRUE;
}

/*=======================================================================
 |
 |		�T�[�o����u���b�N������
 |
 |	VOID	InitSCB(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
static	VOID	InitSCB(PSCB pSCB)
{
	memset(pSCB, 0, sizeof(SCB));
	pSCB->hdir = -1;
}

/*=======================================================================
 |
 |		�T�[�o�R�}���h����
 |
 |	VOID	Command(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |
 =======================================================================*/
static	VOID	Command(PSCB pSCB)
{
	PSCB	*pPSCB;

	/* �f�[�^�o�b�t�@(64KB)�m�� */
	if ((pSCB->lpData = malloc(1024*64 + sizeof(RESPONSE))) == NULL)
		return;
	pSCB->lpData += sizeof(RESPONSE);

	/* SCB�`�F�C�� */
	ENTER_CRITICAL_SECTION(&csSCB);

	pSCB->chainSCB = pTopSCB;
	pTopSCB = pSCB;

	statistics.conxxxtCount[pSCB->chType]++;

	if (++conxxxtCount[0] > statistics.maxConxxxt[0])
		statistics.maxConxxxt[0] = conxxxtCount[0];

	if (++conxxxtCount[pSCB->chType] > statistics.maxConxxxt[pSCB->chType])
		statistics.maxConxxxt[pSCB->chType] =
					conxxxtCount[pSCB->chType];

	LEAVE_CRITICAL_SECTION(&csSCB);

	while (active) {
		if (!CommandService(pSCB))
			break;
	}

	/* SCB�`�F�C���؂藣�� */
	ENTER_CRITICAL_SECTION(&csSCB);
	for (pPSCB = &pTopSCB; *pPSCB != 0; pPSCB = &(*pPSCB)->chainSCB) {
		if (*pPSCB == pSCB) {
			*pPSCB = (*pPSCB)->chainSCB;
			break;
		}
	}
	conxxxtCount[0]--;
	conxxxtCount[pSCB->chType]--;

	LEAVE_CRITICAL_SECTION(&csSCB);

	DisconxxxtClient(pSCB);

	free(pSCB->lpData - sizeof(RESPONSE));
}

/*=======================================================================
 |
 |		�f�[�^���M����
 |
 |	BOOL	SendData(pSCB, data, length)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |		VOID	*data;		���M�f�[�^
 |		UINT	length;		���M�f�[�^��
 |
 |		BOOL	�Ԓl		TRUE: ����@FALSE: �G���[
 |
 =======================================================================*/
BOOL	SendData(PSCB pSCB, VOID *data, UINT length)
{
	DWORD	numBytes;
	UINT	len;
	int	ret;

	len = length;

	switch (pSCB->chType) {
	case CHT_TCPIP:
		while (len) {
			ret = send(pSCB->handle, data, len, 0);
			if (ret == 0 || ret == -1) {
				statistics.sendErrors[pSCB->chType]++;
				return FALSE;
			}

			((CHAR *)data) += ret;
			len -= ret;
		}
		break;
	case CHT_NETBIOS:
		while (len) {
			if (!WriteFile((HANDLE)pSCB->handle, data, len,
							&numBytes, NULL)) {
				statistics.sendErrors[pSCB->chType]++;
				return FALSE;
			}
			((CHAR *)data) += numBytes;
			len -= (USHORT)numBytes;
		}
		break;
	case CHT_RS232C:
		if (!SendRS232C(pSCB->rsp, data, len)) {
			statistics.sendErrors[pSCB->chType]++;
			return FALSE;
		}
		break;
	default:
		return FALSE;
	}

	statistics.sendBytes1[pSCB->chType] += length;
	if (statistics.sendBytes1[pSCB->chType] >= MEGABYTE) {
		statistics.sendBytes1[pSCB->chType] -= MEGABYTE;
		statistics.sendBytes2[pSCB->chType]++;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		�f�[�^��M����
 |
 |	BOOL	ReceiveData(pSCB, data, length)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
 |		VOID	*data;		��M�o�b�t�@
 |		UINT	length;		��M�f�[�^��
 |
 |		BOOL	�Ԓl		TRUE: ����@FALSE: �G���[
 |
 =======================================================================*/
BOOL	ReceiveData(PSCB pSCB, VOID *data, UINT length)
{
	DWORD	numBytes;
	UINT	len;
	int	ret;

	len = length;

	switch (pSCB->chType) {
	case CHT_TCPIP:
		while (len) {
			ret = recv(pSCB->handle, data, len, 0);
			if (ret == 0 || ret == -1) {
				statistics.receiveErrors[pSCB->chType]++;
				return FALSE;
			}

			((CHAR *)data) += ret;
			len -= ret;
		}
		break;
	case CHT_NETBIOS:
		while (len) {
			if (!ReadFile((HANDLE)pSCB->handle, data, len,
							&numBytes, NULL)) {
				statistics.receiveErrors[pSCB->chType]++;
				return FALSE;
			}

			if (numBytes == 0)
				return FALSE;
			((CHAR *)data) += numBytes;
			len -= (USHORT)numBytes;
		}
		break;
	case CHT_RS232C:
		if (!ReceiveRS232C(pSCB->rsp, data, len)) {
			statistics.receiveErrors[pSCB->chType]++;
			return FALSE;
		}
		break;
	default:
		return FALSE;
	}

	statistics.receiveBytes1[pSCB->chType] += length;
	if (statistics.receiveBytes1[pSCB->chType] >= MEGABYTE) {
		statistics.receiveBytes1[pSCB->chType] -= MEGABYTE;
		statistics.receiveBytes2[pSCB->chType]++;
	}

	return TRUE;
}