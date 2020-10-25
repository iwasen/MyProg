/************************************************************************
 *									*
 *			カメラコントロールＤＬＬ			*
 *									*
 *		名称		: リモートカメラコントロール処理	*
 *		ファイル名	: remote.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <windows.h>
#include <winsock.h>
#include <mmsystem.h>
#include <memory.h>
#include "cmctldll.h"
#include "..\common.h"
#include "..\cmcmd.h"

#include <stdio.h>
#include <time.h>

#define	TCPPORT	5100

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

static	int	NetConxxxt(PCCB, LPSTR, LPSTR);
static	int	SendData(SOCKET, LPVOID, int);
static	int	ReceiveData(SOCKET, LPVOID, int);
static	BOOL	SendCommand(PCCB, CAMERA_COMMAND *);
static	BOOL	ReceiveStatus(PCCB, CAMERA_STATUS *);
static	BOOL	LoadWinSock(VOID);

int	NetOpen(PCCB pCCB, LPSTR hostName, LPSTR userName, int option)
{
	WSADATA	WSAData;
	CONxxxT	conxxxtBuf;
	CAMERA_COMMAND	command;
	CAMERA_STATUS	status;
	int	err;

	memset(pCCB, 0, sizeof(CCB));

	if (hWinSock == 0) {
		if (!LoadWinSock())
			return ERROR_CM_WINSOCK;
	}

	WSAStartup(0x0101, &WSAData);	// winsock.dll 初期化

	if ((err = NetConxxxt(pCCB, hostName, userName)) != 0) {
		WSACleanup();
		return err;
	}

	conxxxtBuf.header.flag = FLAG_CONxxxT;
	conxxxtBuf.header.IPAddress = 0L;
	lstrcpy(conxxxtBuf.userName, userName);
	conxxxtBuf.modeFlag = MODE_DLLCALL | MODE_VIDEOREQ;
	conxxxtBuf.channelNo = 0;

	if (SendData(pCCB->sock, &conxxxtBuf, sizeof(conxxxtBuf)) == -1) {
		closesocket(pCCB->sock);
		WSACleanup();
		return ERROR_CM_SEND;
	}

	command.command = COMMAND_Open;
	if (!SendCommand(pCCB, &command)) {
		closesocket(pCCB->sock);
		WSACleanup();
		return ERROR_CM_SEND;
	}

	if (!ReceiveStatus(pCCB, &status)) {
		closesocket(pCCB->sock);
		WSACleanup();
		return ERROR_CM_RECEIVE;
	}

	if (status.status & STATUS_NOSUPPORT)
		pCCB->remoteError = ERROR_CM_NOSUPPORT;
	else if (status.status & STATUS_DENY)
		pCCB->remoteError = ERROR_CM_DENY;
	else if (status.status & STATUS_PORTERR)
		pCCB->remoteError = ERROR_CM_PORT_OPEN;
	else
		pCCB->remoteError = 0;

	return 0;
}

static	int	NetConxxxt(PCCB pCCB, LPSTR hostName, LPSTR userName)
{
	int	sock;
	u_long	addr;
	struct	hostent	*hp;
	struct	sockaddr_in	socketaddr;
	struct	linger	linger;
	u_long	cmd;
	int	err;

	addr = inet_addr(hostName);
	if (addr == INADDR_NONE) {
		hp = gethostbyname(hostName);
		if (hp == NULL)
			return ERROR_CM_HOSTNAME;
		else
			addr = *(u_long FAR *)hp->h_addr;
	}

	sock = socket(PF_INET, SOCK_STREAM, IPPROTO_TCP);
	if (sock < 0)
		return ERROR_CM_SOCKET;

	socketaddr.sin_family      = AF_INET;
	socketaddr.sin_port        = htons((u_short)TCPPORT);
	socketaddr.sin_addr.s_addr = addr;
	err = conxxxt(sock, (struct sockaddr *)&socketaddr, sizeof(socketaddr));
	if (err != 0) {
		closesocket(sock);
		return ERROR_CM_CONxxxT;
	}

	linger.l_onoff = 1;
	linger.l_linger = 5;
	setsockopt(sock, SOL_SOCKET, SO_LINGER, (char *)&linger,
							sizeof(linger));

	cmd = 1;
	ioctlsocket(sock, FIONBIO, &cmd);

	pCCB->sock= sock;

	return 0;
}

int	NetClose(PCCB pCCB)
{
	closesocket(pCCB->sock);
	WSACleanup();

	return 0;
}

int	NetControl(PCCB pCCB, int commandCode)
{
	CAMERA_COMMAND	command;

	if (pCCB->remoteError != 0)
		return pCCB->remoteError;

	switch (commandCode) {
	case CM_RESET:
		command.command = COMMAND_Reset;
		break;
	case CM_HOME_POSITION:
		command.command = COMMAND_Home;
		break;
	case CM_PAN_RIGHT:
		command.command = COMMAND_PanRight;
		break;
	case CM_PAN_LEFT:
		command.command = COMMAND_PanLeft;
		break;
	case CM_TILT_UP:
		command.command = COMMAND_TiltUp;
		break;
	case CM_TILT_DOWN:
		command.command = COMMAND_TiltDown;
		break;
	case CM_STOP:
		command.command = COMMAND_Stop;
		break;
	case CM_ZOOM_WIDE:
		command.command = COMMAND_ZoomWide;
		break;
	case CM_ZOOM_TELE:
		command.command = COMMAND_ZoomTele;
		break;
	case CM_ZOOM_STOP:
		command.command = COMMAND_ZoomStop;
		break;
	case CM_FOCUS_AUTO:
		command.command = COMMAND_FocusAf;
		break;
	case CM_FOCUS_MANUAL:
		command.command = COMMAND_FocusManual;
		break;
	case CM_FOCUS_NEAR:
		command.command = COMMAND_FocusNear;
		break;
	case CM_FOCUS_FAR:
		command.command = COMMAND_FocusFar;
		break;
	case CM_POWER_ON:
		command.command = COMMAND_PowerOn;
		break;
	case CM_POWER_OFF:
		command.command = COMMAND_PowerOff;
		break;
	default:
		return ERROR_CM_INVALID_COMMAND;
	}

	if (!SendCommand(pCCB, &command))
		return ERROR_CM_SEND;

	return 0;
}

int	NetGetCameraOpt(PCCB pCCB, LPCAMERAOPT opt)
{
	CAMERA_COMMAND	command;
	CAMERA_STATUS	status;

	command.command = COMMAND_GetStatus;
	command.param1 = 1;
	if (!SendCommand(pCCB, &command))
		return ERROR_CM_SEND;

	if (!ReceiveStatus(pCCB, &status))
		return ERROR_CM_RECEIVE;

	opt->panSpeed = status.vcc1.panSpeed;
	opt->tiltSpeed = status.vcc1.tiltSpeed;
	opt->zoomSpeed = status.vcc1.zoomSpeed;
	opt->shutterSpeed = status.vcc1.shutterSpeed;
	opt->autoFocus = status.vcc1.bAutoFocus;
	opt->lightAdjust = status.vcc1.bLightAdjust;
	opt->autoWhiteBalance = status.vcc1.bAutoWhiteBalance;
	opt->wideConverter = status.vcc1.bWideConverter;

	return 0;
}

int	NetSetCameraOpt(PCCB pCCB, LPCAMERAOPT opt)
{
	CAMERA_COMMAND	command;
	CAMERA_STATUS	status;
	REMOTE_CAMERA_SET	remote;

	command.command = COMMAND_GetStatus;
	command.param1 = 1;
	if (!SendCommand(pCCB, &command))
		return ERROR_CM_SEND;

	if (!ReceiveStatus(pCCB, &status))
		return ERROR_CM_RECEIVE;

	remote.remote.header.flag = FLAG_REMOTE;
	remote.remote.header.IPAddress = 0;
	remote.remote.type = REMOTE_CAMERA_SETINFO;
	remote.remote.remoteIPAddress = 0;
	remote.flag = INFO_INIT;
	remote.vcc1 = status.vcc1;
	remote.vcc1.panSpeed = opt->panSpeed;
	remote.vcc1.tiltSpeed = opt->tiltSpeed;
	remote.vcc1.zoomSpeed = opt->zoomSpeed;
	remote.vcc1.shutterSpeed = opt->shutterSpeed;
	remote.vcc1.bAutoFocus = opt->autoFocus;
	remote.vcc1.bLightAdjust = opt->lightAdjust;
	remote.vcc1.bAutoWhiteBalance = opt->autoWhiteBalance;
	remote.vcc1.bWideConverter = opt->wideConverter;

	if (!SendData(pCCB->sock, &remote, sizeof(remote)))
		return ERROR_CM_SEND;

	return 0;
}

int	NetGetPreset(PCCB pCCB, LPPRESET preset)
{
	CAMERA_COMMAND	command;
	CAMERA_STATUS	status;

	command.command = COMMAND_GetStatus;
	command.param1 = 1;
	if (!SendCommand(pCCB, &command))
		return ERROR_CM_SEND;

	if (!ReceiveStatus(pCCB, &status))
		return ERROR_CM_RECEIVE;

	_fmemcpy(preset, status.vcc1.preset, sizeof(PRESET) * 8);

	return 0;
}

int	NetSetPreset(PCCB pCCB, LPPRESET preset, int presetNo)
{
	CAMERA_COMMAND	command;
	CAMERA_STATUS	status;
	REMOTE_CAMERA_SET	remote;

	command.command = COMMAND_GetStatus;
	command.param1 = 1;
	if (!SendCommand(pCCB, &command))
		return ERROR_CM_SEND;

	if (!ReceiveStatus(pCCB, &status))
		return ERROR_CM_RECEIVE;

	remote.remote.header.flag = FLAG_REMOTE;
	remote.remote.header.IPAddress = 0;
	remote.remote.type = REMOTE_CAMERA_SETINFO;
	remote.remote.remoteIPAddress = 0;
	remote.flag = INFO_INIT;

	remote.vcc1 = status.vcc1;
	_fmemcpy(&remote.vcc1.preset[presetNo], preset, sizeof(PRESET));

	if (!SendData(pCCB->sock, &remote, sizeof(remote)))
		return ERROR_CM_SEND;

	return 0;
}

int	NetMovePreset(PCCB pCCB, int presetNo)
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_MovePreset;
	command.param1 = presetNo;
	if (!SendCommand(pCCB, &command))
		return ERROR_CM_SEND;

	return 0;
}

int	NetGetCameraPos(PCCB pCCB, LPCAMERAPOS pos)
{
	CAMERA_COMMAND	command;
	CAMERA_STATUS	status;

	command.command = COMMAND_GetStatus;
	command.param1 = 0;
	if (!SendCommand(pCCB, &command))
		return ERROR_CM_SEND;

	if (!ReceiveStatus(pCCB, &status))
		return ERROR_CM_RECEIVE;

	pos->pan = status.panPos;
	pos->tilt = status.tiltPos;
	pos->zoom = status.zoomPos;

	return 0;
}

int	NetSetCameraPos(PCCB pCCB, LPCAMERAPOS pos)
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_SetPos;
	command.param1 = pos->pan;
	command.param2 = pos->tilt;
	command.param3 = pos->zoom;

	if (!SendCommand(pCCB, &command))
		return ERROR_CM_SEND;

	return 0;
}

int	NetLock(PCCB pCCB, int lockFlag)
{
	CAMERA_COMMAND	command;
	CAMERA_STATUS	status;

	if (lockFlag != 0) {
		command.command = COMMAND_Lock;
		command.param1 = 1;
		if (!SendCommand(pCCB, &command))
			return ERROR_CM_SEND;

		command.command = COMMAND_GetStatus;
		command.param1 = 0;
		if (!SendCommand(pCCB, &command))
			return ERROR_CM_SEND;

		if (!ReceiveStatus(pCCB, &status))
			return ERROR_CM_RECEIVE;

		if (status.status & STATUS_LOCKED)
			return ERROR_CM_LOCK;
	} else {
		command.command = COMMAND_Lock;
		command.param1 = 0;
		if (!SendCommand(pCCB, &command))
			return ERROR_CM_SEND;
	}

	return 0;
}

int	NetCaptureVideo(PCCB pCCB, LPCAPTUREMODE lpCaptureMode, HPSTR buf)
{
	REMOTE_CAPTURE_CMD command;
	REMOTE_CAPTURE_RSP response;
	long	size;
	int	len;

	command.remote.header.flag = FLAG_REMOTE;
	command.remote.header.IPAddress = 0L;

	command.remote.type = REMOTE_CAPTURE_COMMAND;
	command.remote.remoteIPAddress = 0L;

	command.size = lpCaptureMode->size;
	command.color = lpCaptureMode->color;
	command.compressMode = lpCaptureMode->compressMode;
	command.compressQuality = lpCaptureMode->compressQuality;

	if (SendData(pCCB->sock, &command, sizeof(command)) == -1)
		return ERROR_CM_SEND;

	if (ReceiveData(pCCB->sock, &response, sizeof(response)) !=
							sizeof(response))
		return ERROR_CM_RECEIVE;

	if (response.dataSize != 0) {
		size = response.dataSize;
		while (size != 0) {
			len = (int)min(size, 1024);
			len = ReceiveData(pCCB->sock, buf, len);
			if (len == -1 || len == 0)
				return ERROR_CM_RECEIVE;

			buf += len;
			size -= len;
		}
	}

	return 0;
}

static	int	SendData(SOCKET sock, LPVOID buf, int len)
{
	char	sendBuf[512];
	MSG	msg;
	int	ret;

	*(int *)sendBuf = len;
	_fmemcpy(sendBuf + 2, buf, len);
	len += 2;

	for (;;) {
		ret = send(sock, (LPSTR)sendBuf, len, 0);
		if (ret == -1 && WSAGetLastError() == WSAEWOULDBLOCK) {
			PeekMessage(&msg, NULL, 0, 0, PM_NOREMOVE);
			continue;
		} else
			return ret;
	}
}

static	int	ReceiveData(SOCKET sock, LPVOID buf, int len)
{
	MSG	msg;
	int	ret;

	for (;;) {
		ret = recv(sock, (LPSTR)buf, len, 0);
		if (ret == -1 && WSAGetLastError() == WSAEWOULDBLOCK) {
			PeekMessage(&msg, NULL, 0, 0, PM_NOREMOVE);
			continue;
		} else
			return ret;
	}
}

static	BOOL	SendCommand(PCCB pCCB, CAMERA_COMMAND *command)
{
	REMOTE_CAMERA_CMD	remote;

	remote.remote.header.flag = FLAG_REMOTE;
	remote.remote.header.IPAddress = 0;
	remote.remote.type = REMOTE_CAMERA_COMMAND;
	remote.remote.remoteIPAddress = 0;
	remote.command = *command;
	if (SendData(pCCB->sock, &remote, sizeof(remote)) != -1)
		return TRUE;
	else
		return FALSE;
}

static	BOOL	ReceiveStatus(PCCB pCCB, CAMERA_STATUS *status)
{
	REMOTE_CAMERA_STS	remote;

	if (ReceiveData(pCCB->sock, &remote, sizeof(remote)) == -1)
		return FALSE;

	if (remote.remote.type != REMOTE_CAMERA_STATUS)
		return FALSE;

	*status = remote.status;

	return TRUE;
}

static	BOOL	LoadWinSock(VOID)
{
	hWinSock = LoadLibrary("WINSOCK.DLL");
	if (hWinSock > 32) {
		*(FARPROC *)&_WSAStartup = GetProcAddress(hWinSock, "WSAStartup");
		*(FARPROC *)&_WSACleanup = GetProcAddress(hWinSock, "WSACleanup");
		*(FARPROC *)&_WSAGetLastError =
				GetProcAddress(hWinSock, "WSAGetLastError");
		*(FARPROC *)&_WSASetBlockingHook =
				GetProcAddress(hWinSock, "WSASetBlockingHook");
		*(FARPROC *)&_WSAUnhookBlockingHook =
			GetProcAddress(hWinSock, "WSAUnhookBlockingHook");
		*(FARPROC *)&_socket = GetProcAddress(hWinSock, "socket");
		*(FARPROC *)&_closesocket =
				GetProcAddress(hWinSock, "closesocket");
		*(FARPROC *)&_conxxxt = GetProcAddress(hWinSock, "conxxxt");
		*(FARPROC *)&_ioctlsocket =
				GetProcAddress(hWinSock, "ioctlsocket");
		*(FARPROC *)&_htons = GetProcAddress(hWinSock, "htons");
		*(FARPROC *)&_inet_addr = GetProcAddress(hWinSock, "inet_addr");
		*(FARPROC *)&_recv = GetProcAddress(hWinSock, "recv");
		*(FARPROC *)&_send = GetProcAddress(hWinSock, "send");
		*(FARPROC *)&_setsockopt = GetProcAddress(hWinSock, "setsockopt");
		*(FARPROC *)&_shutdown = GetProcAddress(hWinSock, "shutdown");
		*(FARPROC *)&_gethostbyname =
				GetProcAddress(hWinSock, "gethostbyname");
		return TRUE;
	} else {
		hWinSock = 0;
		return FALSE;
	}
}
