/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: クライアント側ネットワークアクセス処理
 *		ファイル名	: ioc.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#ifdef	OS_VMS
#include <unixio.h>
#include <file.h>
#define min(a,b)	(((a) < (b)) ? (a) : (b))
#define	O_BINARY	0x0000
#else
#include <io.h>
#include <fcntl.h>
#endif

#include "pcssock.h"
#include "pcsclnt.h"

#define	SOCKET_xxx	1
#define	SOCKET_W95	2
#define	SOCKET_WNT	3

int	tcpipPort = TCPIP_PORT;
char	pipeName[32] = "\\PIPE\\PCSERVE";

static	BOOL	SendRS232CChannel(PRS232C, int);
static	BOOL	SendRS232C(PRS232C, CHAR *, UINT);
static	BOOL	SendBlockRS232C(PRS232C, CHAR *, UINT);
static	BOOL	ReceiveRS232C(PRS232C, CHAR *, UINT);
static	BOOL	ReceiveBlockRS232C(PRS232C);
static	BOOL	GetRS232CParam(CHAR *, SHORT *, SHORT *, CHAR *);

static	RS232C	rs232c[MAX_RS232C_PORT];

#ifdef	OS_WINDOWS
#define	WSAStartup	(*_WSAStartup)
#define	WSACleanup	(*_WSACleanup)
#define	WSAGetLastError	(*_WSAGetLastError)
#define	WSASetBlockingHook	(*_WSASetBlockingHook)
#define	WSAUnhookBlockingHook	(*_WSAUnhookBlockingHook)
#define	socket		(*_socket)
#define	closesocket	(*_closesocket)
#define	conxxxt		(*_conxxxt)
#define	ioctlsocket	(*_ioctlsocket)
#define	htons		(*_htons)
#define	inet_addr	(*_inet_addr)
#define	recv		(*_recv)
#define	send		(*_send)
#define	setsockopt	(*_setsockopt)
#define	shutdown	(*_shutdown)
#define	gethostbyname	(*_gethostbyname)

HANDLE	hWinSock;
static	int	socketVersion;
static	int (PASCAL FAR *_WSAStartup)(WORD, LPWSADATA);
static	int (PASCAL FAR *_WSACleanup)(void);
static	int (PASCAL FAR *_WSAGetLastError)(void);
static	FARPROC (PASCAL FAR *_WSASetBlockingHook)(FARPROC lpBlockFunc);
static	int (PASCAL FAR *_WSAUnhookBlockingHook)(void);
static	SOCKET (PASCAL FAR *_socket)(int af, int type, int protocol);
static	int (PASCAL FAR *_closesocket)(SOCKET);
static	int (PASCAL FAR *_conxxxt)(SOCKET, const struct sockaddr FAR *, int);
static	int (PASCAL FAR *_ioctlsocket)(SOCKET, long, u_long FAR *);
static	u_short (PASCAL FAR *_htons)(u_short);
static	unsigned long (PASCAL FAR *_inet_addr)(const char FAR *);
static	int (PASCAL FAR *_recv)(SOCKET, char FAR *, int, int);
static	int (PASCAL FAR *_send)(SOCKET, const char FAR *, int, int);
static	int (PASCAL FAR *_setsockopt)(SOCKET, int, int, const char FAR *, int);
static	int (PASCAL FAR *_shutdown)(SOCKET, int);
static	struct hostent FAR *(PASCAL FAR *_gethostbyname)(const char FAR *);

PASCAL FAR	BlockingHook(VOID)
{
	return 1;
}

static	BOOL	LoadWinSock(VOID);
#endif

/*=======================================================================
 |
 |		接続処理
 |
 |	SHORT	NetConxxxt(pCCB, hostName)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*hostName;	ホスト名
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	NetConxxxt(PCCB pCCB, CHAR *hostName)
{
	int	s, fh;
	SHORT	handle;
	struct	hostent	*hp;
	struct	sockaddr_in	socketaddr;
	u_long	addr;
	CHAR	pipe[128];
	SHORT	portNo, speed;
	CHAR	modemCommand[128];
	PRS232C	rsp;
	int	chNo;
	SHORT	err;

	switch (pCCB->chType) {
	case CHT_TCPIP:
#ifdef	OS_WINDOWS
{
		WSADATA	WSAData;

		if (hWinSock == 0) {
			if (!LoadWinSock())
				return ERROR_CLIENT_WINSOCK;
		}

		memset(&WSAData, 0, sizeof(WSAData));
		WSAStartup(0x0101, &WSAData);
		if (strstr(WSAData.szDescription, "ASCII") != NULL)
			socketVersion = SOCKET_xxx;
		else if (strstr(WSAData.szDescription, "NT") != NULL)
			socketVersion = SOCKET_WNT;
		else if (strstr(WSAData.szSystemStatus, "95") != NULL)
			socketVersion = SOCKET_W95;

		if (socketVersion == SOCKET_xxx)
			WSASetBlockingHook(BlockingHook);
}
#endif

		hp = gethostbyname(hostName);
		if (hp == NULL) {
			addr = inet_addr(hostName);
			if (addr == INADDR_NONE)
				return ERROR_CLIENT_NOHOST;
		} else
			addr = *(u_long *)hp->h_addr;

		s = socket(PF_INET, SOCK_STREAM, 0);
		if (s < 0)
			return ERROR_CLIENT_SOCKET;

		socketaddr.sin_family      = AF_INET;
		socketaddr.sin_port        = htons((u_short)tcpipPort);
		socketaddr.sin_addr.s_addr = addr;
		err = conxxxt(s, (struct sockaddr *)&socketaddr,
							sizeof(socketaddr));
		if (err != 0) {
			closesocket(s);
			return ERROR_CLIENT_CONxxxT;
		}

#ifdef	OS_WINDOWS
{
		struct	linger	linger;
		u_long	cmd;

		linger.l_onoff = 1;
		linger.l_linger = 5;
		setsockopt(s, SOL_SOCKET, SO_LINGER, (char *)&linger,
							sizeof(linger));

		if (socketVersion == SOCKET_W95) {
			cmd = 1;
			ioctlsocket(s, FIONBIO, &cmd);
		}
}
#endif

		pCCB->handle = s;
		break;
	case CHT_NETBIOS:
		if (hostName != NULL && hostName[0] != '\0')
			sprintf(pipe, "\\\\%s%s", hostName, pipeName);
		else
			strcpy(pipe, pipeName);
#if defined OS_WINDOWS || defined OS_WNT
		if ((fh = _lopen(pipe, OF_READWRITE)) == -1)
#else
		if ((fh = open(pipe, O_RDWR|O_BINARY, 0)) == -1)
#endif
			return ERROR_CLIENT_CONxxxT;
		pCCB->handle = fh;
		break;
	case CHT_RS232C:
		if (!GetRS232CParam(hostName, &portNo, &speed, modemCommand))
			return ERROR_CLIENT_PARAMETER;

		if (portNo >= MAX_RS232C_PORT)
			return ERROR_CLIENT_PARAMETER;

		rsp = &rs232c[portNo];

		for (chNo = 0; chNo < MAX_RS232C_CHANNEL; chNo++) {
			if (!rsp->fChannel[chNo])
				break;
		}
		if (chNo >= MAX_RS232C_CHANNEL)
			return ERROR_CLIENT_MAXCONxxxT;

		if (rs232c[portNo].openCount != 0) {
			rsp->openCount++;
			rsp->fChannel[chNo] = TRUE;
			pCCB->handle = chNo;
			pCCB->rsp = rsp;
			break;
		}

		if ((rsp->pRcvBuf = malloc(RS_BLOCKSIZE)) == NULL)
			return ERROR_CLIENT_MEMORY;

		if ((rsp->pSndBuf = malloc(RS_BLOCKSIZE+6)) == NULL) {
			free(rsp->pRcvBuf);
			return ERROR_CLIENT_MEMORY;
		}

		if (modemCommand[0] != '\0')
			rsp->fModem = TRUE;
		else
			rsp->fModem = FALSE;

		if ((handle = OpenRS232C(portNo, speed, rsp->fModem)) == -1) {
			free(rsp->pRcvBuf);
			free(rsp->pSndBuf);
			return ERROR_CLIENT_CONxxxT;
		}

		if (rsp->fModem) {
			if ((err = Telephoxxxall(handle, modemCommand)) != 0) {
				free(rsp->pRcvBuf);
				free(rsp->pSndBuf);
				CloseRS232C(handle);
				return err;
			}
		}

		rsp->handle = handle;
		rsp->openCount++;
		rsp->fChannel[chNo] = TRUE;

		pCCB->handle = chNo;
		pCCB->rsp = rsp;
		break;
	default:
		return ERROR_CLIENT_PARAMETER;
	}

	return 0;
}

/*=======================================================================
 |
 |		切断処理
 |
 |	BOOL	NetDisconxxxt(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
BOOL	NetDisconxxxt(PCCB pCCB)
{
	PRS232C	rsp;

	if (pCCB->handle == -1)
		return TRUE;

	switch (pCCB->chType) {
	case CHT_TCPIP:
#ifdef	OS_WINDOWS
		if (socketVersion == SOCKET_W95) {
			u_long	cmd;

			cmd = 0;
			ioctlsocket(pCCB->handle, FIONBIO, &cmd);
		}
#endif
		shutdown(pCCB->handle, 2);
#ifdef	OS_WINDOWS
		if (socketVersion == SOCKET_xxx)
			WSAUnhookBlockingHook();
		closesocket(pCCB->handle);
		if (socketVersion == SOCKET_xxx)
			WSASetBlockingHook(BlockingHook);
		WSACleanup();
#else
		closesocket(pCCB->handle);
#endif
		pCCB->handle = -1;
		break;
	case CHT_NETBIOS:
#if defined OS_WINDOWS || defined OS_WNT
		_lclose(pCCB->handle);
#else
		close(pCCB->handle);
#endif
		pCCB->handle = -1;
		break;
	case CHT_RS232C:
		rsp = pCCB->rsp;

		rsp->fChannel[pCCB->handle] = FALSE;
		if (--(rsp->openCount) == 0) {
			free(rsp->pRcvBuf);
			free(rsp->pSndBuf);
			CloseRS232C(rsp->handle);
			memset(rsp, 0, sizeof(RS232C));
		}
		pCCB->handle = -1;
		break;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		データ送信処理
 |
 |	BOOL	SendData(pCCB, data, length)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		VOID	*data;		送信データ
 |		UINT	length;		送信データ長
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
BOOL	SendData(PCCB pCCB, VOID *data, UINT length)
{
	int	ret;

	switch (pCCB->chType) {
	case CHT_TCPIP:
		while (length) {
			ret = (int)send(pCCB->handle, data,
					length > 32767 ? 32767 : length, 0);
#ifdef	OS_WINDOWS
			if (socketVersion == SOCKET_W95 &&  ret == -1) {
				if (WSAGetLastError() == WSAEWOULDBLOCK)
					continue;
			}
#endif
			if (ret == 0 || ret == -1)
				return FALSE;

			((CHAR *)data) += (unsigned int)ret;
			length -= (unsigned int)ret;
		}
		break;
	case CHT_NETBIOS:
		while (length) {
#if defined OS_WINDOWS || defined OS_WNT
			ret = _lwrite(pCCB->handle, data, length);
#else
			ret = write(pCCB->handle, data, length);
#endif
			if (ret == -1 || ret == 0)
				return FALSE;
			((CHAR *)data) += (unsigned int)ret;
			length -= (unsigned int)ret;
		}
		break;
	case CHT_RS232C:
		if (pCCB->handle != pCCB->rsp->chNo) {
			if (!SendRS232CChannel(pCCB->rsp, pCCB->handle))
				return FALSE;
		}
		pCCB->rsp->chNo = pCCB->handle;

		if (!SendRS232C(pCCB->rsp, data, length))
			return FALSE;
		break;
	default:
		return FALSE;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		データ受信処理
 |
 |	BOOL	ReceiveData(pCCB, data, length)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		VOID	*data;		受信データバッファ
 |		UINT	length;		受信データ長
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
BOOL	ReceiveData(PCCB pCCB, VOID *data, UINT length)
{
	int	ret;
/*
	fd_set	fdset;
	struct	timeval	timeout;
*/
	switch (pCCB->chType) {
	case CHT_TCPIP:
		while (length) {
/*
			FD_ZERO(&fdset);
			FD_SET(pCCB->handle, &fdset);
			timeout.tv_sec = 5;
			timeout.tv_usec = 0;
			ret = select(32, &fdset, NULL, NULL, &timeout);
			if (ret == -1)
				return FALSE;
			else if (ret == 0)
				continue;
*/
			ret = (int)recv(pCCB->handle, data,
					length > 32767 ? 32767 : length, 0);
#ifdef	OS_WINDOWS
			if (socketVersion == SOCKET_W95 &&  ret == -1) {
				if (WSAGetLastError() == WSAEWOULDBLOCK)
					continue;
			}
#endif
			if (ret == 0 || ret == -1)
				return FALSE;

			((CHAR *)data) += (unsigned int)ret;
			length -= (unsigned int)ret;
		}
		break;
	case CHT_NETBIOS:
		while (length) {
#if defined OS_WINDOWS || defined OS_WNT
			if ((ret = _lread(pCCB->handle, data, length)) == -1)
#else
			if ((ret = read(pCCB->handle, data, length)) == -1)
#endif
				return FALSE;

			((CHAR *)data) += (unsigned int)ret;
			length -= (unsigned int)ret;
		}
		break;
	case CHT_RS232C:
		if (!ReceiveRS232C(pCCB->rsp, data, length))
			return FALSE;
		break;
	default:
		return FALSE;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		コマンド送信処理
 |
 |	SHORT	SendCommand(pCCB, code, paramLength, dataLength)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		UINT	code;		コマンドコード
 |		UINT	paramLength;	パラメータ長
 |		UINT	dataLength;	データ長
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	SendCommand(PCCB pCCB, UINT code, UINT paramLength, UINT dataLength)
{
	SHORT	async;

	if (pCCB->id != ID_CCB)
		return ERROR_CLIENT_CCB;

	if (paramLength < MIN_PARAM_SIZE)
		paramLength = MIN_PARAM_SIZE;

	async = pCCB->async;

	for (;;) {
		if (pCCB->extSendDataLength != 0) {
			dataLength = (UINT)min(pCCB->extSendDataLength, 16384);
			pCCB->extSendDataLength -= dataLength;
		}

		if (pCCB->extSendDataLength != 0)
			pCCB->command.id = ID_COMMAND | ID_CONTINUE;
		else {
			if (async) {
				pCCB->async = FALSE;
				pCCB->command.id = ID_COMMAND_ASYNC;
			} else
				pCCB->command.id = ID_COMMAND;
		}

		(pCCB->command.seqNo)++;
		pCCB->command.code = code;
		pCCB->command.paramLength = paramLength;
		pCCB->command.dataLength = dataLength;

		if (!SendData(pCCB, &pCCB->command, 9 + paramLength))
			return ERROR_CLIENT_SEND;

		if (dataLength != 0) {
			if (!SendData(pCCB, pCCB->pInData, dataLength))
				return ERROR_CLIENT_SEND;
		}

		if (pCCB->extSendDataLength != 0)
			(HPSTR)(pCCB->pInData) += dataLength;
		else
			break;
	}

	if (async) {
		pCCB->response.ret.lValue = 0L;
		return 0;
	}

	pCCB->extReceiveDataLength = 0;
	for (;;) {
		if (!ReceiveData(pCCB, &pCCB->response, sizeof(RESPONSE)))
			return ERROR_CLIENT_RECEIVE;

		if ((pCCB->response.id & ~ID_CONTINUE) != ID_RESPONSE ||
				pCCB->response.seqNo != pCCB->command.seqNo)
			return ERROR_CLIENT_RECEIVE;

		if (pCCB->response.dataLength != 0) {
			if (!ReceiveData(pCCB, pCCB->pOutData,
						pCCB->response.dataLength))
				return ERROR_CLIENT_RECEIVE;

			pCCB->extReceiveDataLength +=
						pCCB->response.dataLength;
		}

		if (pCCB->response.id & ID_CONTINUE)
			(HPSTR)(pCCB->pOutData) += pCCB->response.dataLength;
		else
			break;
	}

	return pCCB->response.status;
}

/*=======================================================================
 |
 |		RS-232C チャネル番号送信処理
 |
 |	BOOL	SendRS232CChannel(rsp, chNo)
 |
 |		PRS232C	rsp;		RS232C 管理情報
 |		int	chNo;		チャネル番号
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
static	BOOL	SendRS232CChannel(PRS232C rsp, int chNo)
{
	int	retry;
	CHAR	sbuf[3];
	CHAR	rbuf[1];
	SHORT	numBytes;

	sbuf[0] = RS_SOH;
	sbuf[1] = (CHAR)(chNo | 0x30);
	sbuf[2] = (CHAR)(~sbuf[1]);

	for (retry = 0; retry < RS_RETRY; retry++) {
		if (rsp->fModem) {
			if (!CheckCDRS232C(rsp->handle))
				return FALSE;
		} else {
			if (!CheckDSRRS232C(rsp->handle))
				return FALSE;
		}

		if ((numBytes = WriteRS232C(rsp->handle, sbuf, 3)) == -1)
			return FALSE;

		if ((numBytes = ReadRS232C(rsp->handle, rbuf, 1)) == -1)
			return FALSE;

		if (numBytes == 1) {
			if (rbuf[0] == RS_ACK)
				return TRUE;
		}
	}

	return FALSE;
}

/*=======================================================================
 |
 |		RS-232C データ送信処理
 |
 |	BOOL	SendRS232C(rsp, data, length)
 |
 |		PRS232C	rsp;		RS232C 管理情報
 |		CHAR	*data;		送信データバッファ
 |		UINT	length;		送信データ長
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
static	BOOL	SendRS232C(PRS232C rsp, CHAR *data, UINT length)
{
	UCHAR	checkSum;
	int	i;
	UINT	blockSize;
	CHAR	*bufp;

	while (length != 0) {
		blockSize = min(length, RS_BLOCKSIZE);

		checkSum = 0;
		for (i = 0; i < (int)blockSize; i++)
			checkSum += (UCHAR)data[i];

		bufp = rsp->pSndBuf;
		*bufp++ = RS_STX;
		*((USHORT *)bufp)++ = blockSize;
		memcpy(bufp, data, blockSize);
		bufp += blockSize;
		*bufp++ = RS_ETX;
		*bufp = checkSum;

		if (!SendBlockRS232C(rsp, rsp->pSndBuf, blockSize+5))
			return FALSE;

		data += blockSize;
		length -= blockSize;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		RS-232C １ブロック送信処理
 |
 |	BOOL	SendBlockRS232C(rsp, bufp, length)
 |
 |		PRS232C	rsp;		RS232C 管理情報
 |		CHAR	*bufp;		送信ブロックバッファ
 |		UINT	length;		送信ブロック長
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
static	BOOL	SendBlockRS232C(PRS232C rsp, CHAR *bufp, UINT length)
{
	int	retry;
	CHAR	buf[2];
	SHORT	numBytes;

	for (retry = 0; retry < RS_RETRY; retry++) {
		if (rsp->fModem) {
			if (!CheckCDRS232C(rsp->handle))
				return FALSE;
		} else {
			if (!CheckDSRRS232C(rsp->handle))
				return FALSE;
		}

		if ((numBytes = WriteRS232C(rsp->handle, bufp, length)) == -1)
			return FALSE;

		for (;;) {
			if ((numBytes = ReadRS232C(rsp->handle, buf, 1))
									== -1)
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
 |		RS-232C データ受信処理
 |
 |	BOOL	ReceiveRS232C(rsp, bufp, length)
 |
 |		PRS232C	rsp;		RS232C 管理情報
 |		CHAR	*data;		受信データバッファ
 |		UINT	length;		受信データ長
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
static	BOOL	ReceiveRS232C(PRS232C rsp, CHAR *bufp, UINT length)
{
	UINT	readLength;

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
 |		RS-232C １ブロック受信処理
 |
 |	BOOL	ReceiveBlockRS232C(rsp)
 |
 |		PRS232C	rsp;		RS232C 管理情報
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
static	BOOL	ReceiveBlockRS232C(PRS232C rsp)
{
	UINT	numBytes;
	CHAR	buf[2];
	int	i;
	UINT	blockLength;
	UCHAR	checkSum;

	for (;;) {
		if (rsp->fModem) {
			if (!CheckCDRS232C(rsp->handle))
				return FALSE;
		} else {
			if (!CheckDSRRS232C(rsp->handle))
				return FALSE;
		}

		if ((numBytes = ReadRS232C(rsp->handle, buf, 1)) == -1)
			return FALSE;

		if (numBytes != 1 || buf[0] != RS_STX)
			continue;

		if ((numBytes = ReadRS232C(rsp->handle,
					(CHAR *)&blockLength, 2)) == -1)
			return FALSE;

		if (numBytes != 2)
			continue;

		if (blockLength > RS_BLOCKSIZE)
			continue;

		if ((numBytes = ReadRS232C(rsp->handle, rsp->pRcvBuf,
						blockLength)) == -1)
			return FALSE;

		if (numBytes != blockLength)
			continue;

		if ((numBytes = ReadRS232C(rsp->handle, buf, 1)) == -1)
			return FALSE;

		if (numBytes != 1 || buf[0] != RS_ETX)
			continue;

		if ((numBytes = ReadRS232C(rsp->handle, &checkSum, 1)) == -1)
			return FALSE;

		if (numBytes != 1)
			continue;

		for (i = 0; i < (int)blockLength; i++)
			checkSum -= (UCHAR)rsp->pRcvBuf[i];

		if (checkSum == 0) {
			buf[0] = RS_ACK;
			WriteRS232C(rsp->handle, buf, 1);
			break;
		} else {
			buf[0] = RS_NAK;
			WriteRS232C(rsp->handle, buf, 1);
		}
	}

	rsp->rcvLength = blockLength;
	rsp->readCounter = 0;

	return TRUE;
}

/*=======================================================================
 |
 |		RS-232C パラメータ取得
 |
 |	BOOL	GetRS232CParam(hostName, portNo, speed, modemCommand)
 |
 |		CHAR	*hostName;	RS232C パラメータ
 |		SHORT	*portNo;	ポート番号
 |		SHORT	*speed;		ボーレート
 |		CHAR	*modemCommand;	モデムコマンド
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
static	BOOL	GetRS232CParam(CHAR *hostName, SHORT *portNo, SHORT *speed,
							CHAR *modemCommand)
{
	CHAR	buf[256], *p;

	strcpy(buf, hostName);
	if ((p = strtok(buf, ":")) == NULL)
		return FALSE;
	if (strncmp(p, "COM", 3) != 0)
		return FALSE;
	if (p[3] < 1 || p[3] > '9')
		return FALSE;
	*portNo = p[3] - '1';

	if ((p = strtok(NULL, ":")) == NULL)
		return FALSE;
	switch ((unsigned int)atoi(p)) {
	case 300:
		*speed = 0;
		break;
	case 600:
		*speed = 1;
		break;
	case 1200:
		*speed = 2;
		break;
	case 2400:
		*speed = 3;
		break;
	case 4800:
		*speed = 4;
		break;
	case 9600:
		*speed = 5;
		break;
	case 19200:
		*speed = 6;
		break;
	case 38400:
		*speed = 7;
		break;
	default:
		return FALSE;
	}

	if ((p = strtok(NULL, ":")) == NULL) {
		*modemCommand = '\0';
		return TRUE;
	}

	strcpy(modemCommand, p);

	return TRUE;
}

#ifdef	OS_WINDOWS
static	BOOL	LoadWinSock(VOID)
{
	hWinSock = LoadLibrary("WINSOCK.DLL");
	if (hWinSock > 32) {
		(FARPROC)_WSAStartup = GetProcAddress(hWinSock, "WSAStartup");
		(FARPROC)_WSACleanup = GetProcAddress(hWinSock, "WSACleanup");
		(FARPROC)_WSAGetLastError =
				GetProcAddress(hWinSock, "WSAGetLastError");
		(FARPROC)_WSASetBlockingHook =
				GetProcAddress(hWinSock, "WSASetBlockingHook");
		(FARPROC)_WSAUnhookBlockingHook =
			GetProcAddress(hWinSock, "WSAUnhookBlockingHook");
		(FARPROC)_socket = GetProcAddress(hWinSock, "socket");
		(FARPROC)_closesocket =
				GetProcAddress(hWinSock, "closesocket");
		(FARPROC)_conxxxt = GetProcAddress(hWinSock, "conxxxt");
		(FARPROC)_ioctlsocket =
				GetProcAddress(hWinSock, "ioctlsocket");
		(FARPROC)_htons = GetProcAddress(hWinSock, "htons");
		(FARPROC)_inet_addr = GetProcAddress(hWinSock, "inet_addr");
		(FARPROC)_recv = GetProcAddress(hWinSock, "recv");
		(FARPROC)_send = GetProcAddress(hWinSock, "send");
		(FARPROC)_setsockopt = GetProcAddress(hWinSock, "setsockopt");
		(FARPROC)_shutdown = GetProcAddress(hWinSock, "shutdown");
		(FARPROC)_gethostbyname =
				GetProcAddress(hWinSock, "gethostbyname");
		return TRUE;
	} else {
		hWinSock = 0;
		return FALSE;
	}
}
#endif
