/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: ソケット処理
 *		ファイル名	: socket.cpp
 *		作成者		: s.aizawa
 *
 ************************************************************************/

// receive.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "mainfrm.h"
#include "receive.h"
#include "LoginDlg.h"

static	BOOL	bInitWinSock;

static	BOOL	InitSocketUDP();
static	BOOL	InitSocketTCP();
static	void	ReceiveData(DWORD id, PSOCK pSockSend, char *recvBuf, int len);
static void ReceiveDeny(PDENY pDeny);

////////////////////////////////////////
//	ソケット初期化処理
////////////////////////////////////////
BOOL	InitSocket()
{
	WSADATA	WSAData;
	LPHOSTENT hp;

	if (WSAStartup(0x0101, &WSAData) != 0) {	// winsock.dll 初期化
		AfxMessageBox(IDS_ERR_WINSOCK, MB_OK);
		return FALSE;
	}

	bInitWinSock = TRUE;

	// ローカルホスト名の取得
	if (gethostname(localHostName, sizeof(localHostName)) != 0) {
		AfxMessageBox(IDS_ERR_LOCALHOST, MB_OK);
		return FALSE;
	}

	// ローカルＩＰアドレスの取得
	if ((hp = gethostbyname(localHostName)) == NULL) {
		AfxMessageBox(IDS_ERR_LOCAL_IP, MB_OK);
		return FALSE;
	}
	localIPAddress = *(u_long *)hp->h_addr;

	// UDP ソケット初期化
	if (!InitSocketUDP())
		return FALSE;

	// TCP ソケット初期化
//	if (!InitSocketTCP())
//		return FALSE;

	return TRUE;
}

////////////////////////////////////////
//	UDP ソケット初期化処理
////////////////////////////////////////
static	BOOL	InitSocketUDP()
{
	SOCKADDR_IN	socketAddr;
	BOOL	bOptVal;
//	int	bufSize;
	int	err;

	// ソケットオープン
	sockUDP = socket(PF_INET, SOCK_DGRAM, 0);
	if (sockUDP == INVALID_SOCKET) {
		CString msg;
		msg.Format(IDS_ERR_UDP, WSAGetLastError());
		AfxMessageBox(msg, MB_OK);
		return FALSE;
	}

	// ブロードキャスト許可
	bOptVal = TRUE;
	setsockopt(sockUDP, SOL_SOCKET, SO_BROADCAST, (char *)&bOptVal, sizeof(bOptVal));

	// 受信バッファを 4096 バイトに設定
//	bufSize = 8192;
//	setsockopt(sockUDP, SOL_SOCKET, SO_RCVBUF, (char *)&bufSize, sizeof(bufSize));

	// 送信バッファを 4096 バイトに設定
//	bufSize = 4096;
//	setsockopt(sockUDP, SOL_SOCKET, SO_SNDBUF, (char *)&bufSize, sizeof(bufSize));

	// ソケットのバインド
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(PORTNO2);
	socketAddr.sin_addr.s_addr = 0;
	err = bind(sockUDP, (LPSOCKADDR)&socketAddr, sizeof(socketAddr));
	if (err != 0) {
		socketAddr.sin_family      = AF_INET;
		socketAddr.sin_port        = htons(PORTNO + 1);
		socketAddr.sin_addr.s_addr = 0;
		err = bind(sockUDP, (LPSOCKADDR)&socketAddr, sizeof(socketAddr));
		if (err != 0) {
			AfxMessageBox(IDS_ERR_BIND, MB_OK);
			return FALSE;
		}
		bSecond = TRUE;
	}

	return TRUE;
}

////////////////////////////////////////
//	TCP ソケット初期化処理
////////////////////////////////////////
static	BOOL	InitSocketTCP()
{
	SOCKADDR_IN	socketAddr;
	int	err;

	// ソケットオープン
	sockTCP = socket(PF_INET, SOCK_STREAM, 0);
	if (sockTCP == INVALID_SOCKET) {
		CString msg;
		msg.Format(IDS_ERR_TCP, WSAGetLastError());
		AfxMessageBox(msg, MB_OK);
		return FALSE;
	}

	// ソケットのバインド
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(PORTNO);
	socketAddr.sin_addr.s_addr = INADDR_ANY;
	err = bind(sockTCP, (LPSOCKADDR)&socketAddr,
						sizeof(socketAddr));
	if (err != 0) {
		AfxMessageBox(IDS_ERR_BIND, MB_OK);
		return FALSE;
	}

	if (listen(sockTCP, 1) < 0) {
		AfxMessageBox(IDS_ERR_LISTEN, MB_OK);
		return FALSE;
	}

	return TRUE;
}

void	ExitSocket()
{
	if (sockUDP != INVALID_SOCKET) {
		closesocket(sockUDP);	// ソケットクローズ
		sockUDP = INVALID_SOCKET;
	}

	if (sockTCP != INVALID_SOCKET) {
		closesocket(sockTCP);	// ソケットクローズ
		sockTCP = INVALID_SOCKET;
	}

	if (bInitWinSock) {
		WSACleanup();		// winsock.dll クリーンアップ
		bInitWinSock = FALSE;
	}

}

void	ReadSocketUDP(SOCKET sock)
{
	SOCKADDR_IN	socketAddr;
	int	len, fromLen;
	u_long	IPAddress;
	SOCK	sockSend;
	PHEADER	pHeader;
	char	recvBuf[1024];

	WSAAsyncSelect(sock, pMainWnd->m_hWnd, WM_SOCKETUDP, 0);

	for (;;) {
		socketAddr.sin_family      = AF_INET;
		socketAddr.sin_port        = 0;
		socketAddr.sin_addr.s_addr = 0;
		fromLen = sizeof(socketAddr);
		len = recvfrom(sock, recvBuf, sizeof(recvBuf), 0, (LPSOCKADDR)&socketAddr, &fromLen);
		if (len == 0 || len == -1 || licenseReplyCount == -1)
			break;

		IPAddress = socketAddr.sin_addr.s_addr;

		if (IPAddress == guardIPAddress)
			continue;

		if (IPAddress == conxxxtIPAddress)
			conxxxtIPAddress = 0;

		sockSend.bTCP = FALSE;
		sockSend.sock = sock;
		sockSend.IPAddress = IPAddress;
		sockSend.portNo = socketAddr.sin_port;

		pHeader = (PHEADER)recvBuf;
		ReceiveData(pHeader->id, &sockSend, recvBuf, len);
	}

	WSAAsyncSelect(sock, pMainWnd->m_hWnd, WM_SOCKETUDP, FD_READ);
}


void	ReadSocketTCP(SOCKET sock)
{
/*
	SOCKADDR_IN socketAddr;
	int	addrLen;
	int	len;
	SOCK	sockSend, sockUser;
	char	recvBuf[1024], *p;
	int	size;

	len = recv(sock, recvBuf, sizeof(recvBuf), 0);
	if (len == 0 || len == -1)
		return;

	addrLen = sizeof(socketAddr);
	if (getpeername(sock, (SOCKADDR *)&socketAddr, &addrLen) != 0)
		return;

	sockSend.bTCP = TRUE;
	sockSend.sock = INVALID_SOCKET;
	sockSend.IPAddress = socketAddr.sin_addr.s_addr;
	sockSend.portNo = socketAddr.sin_port;

	sockUser.bTCP = TRUE;
	sockUser.sock = sock;
	sockUser.IPAddress = socketAddr.sin_addr.s_addr;
	sockUser.portNo = socketAddr.sin_port;

	p = recvBuf;
	while (len > 0) {
		size = *(int *)p;
		p += 2;
		len -= 2;

		ReceiveData(&sockSend, &sockUser, p, size);

		p += size;
		len -= size;
	}
*/
}

static	void	ReceiveData(DWORD id, PSOCK pSockSend, char *recvBuf, int len)
{
	int	n;
	PHEADER	pHeader;
	PCONxxxT	pConxxxt;
	PUSER	pUser;
	PVIDEO	pVideo;
	u_long	*pLong;
	BOOL	bSendVideo;

	pHeader = (PHEADER)recvBuf;
	pUser = SearchUser(id, pSockSend);

	switch (pHeader->flag & ~FLAG_SECOND) {
	case FLAG_CONxxxT:
		pConxxxt = (PCONxxxT)recvBuf;
		if (pUser == NULL) {
			pUser = AddUser(pSockSend, pConxxxt);
			if (pUser != NULL)
				SendConxxxt1(pUser->pSend);
		}

		if (pUser != NULL) {
			pUser->timer = 0;
			if (pUser->modeFlag != pConxxxt->modeFlag ||
					pUser->channelNo != pConxxxt->channelNo ||
					lstrcmp(pUser->userName, pConxxxt->userName) != 0) {
				lstrcpy(pUser->userName, pConxxxt->userName);
				pUser->modeFlag = pConxxxt->modeFlag;
				pUser->channelNo = pConxxxt->channelNo;
				pMainListWnd->Invalidate();
			}

			n = (len - sizeof(CONxxxT)) / sizeof(long);
			pLong = (u_long *)&recvBuf[sizeof(CONxxxT)];
			if (pUser->modeFlag & MODE_VIDEOREQ) {
				bSendVideo = FALSE;
				while (--n >= 0) {
					if (*pLong++ == localIPAddress) {
						bSendVideo = TRUE;
						break;
					}
				}
			} else
				bSendVideo = TRUE;

			if (!pUser->bSendVideo && bSendVideo)
				bNewVideoUser = TRUE;
			pUser->bSendVideo = bSendVideo;
		}
		break;
	case FLAG_DISCONxxxT:
		if (pUser != NULL)
			DeleteUser(pUser);
		break;
	case FLAG_VIDEO:
		if (pUser != NULL && pUser->bMaster) {
			pUser->timer = 0;

			if ((pVideo = pUser->pVideo) != NULL)
				pVideo->ReceiveVideoData(recvBuf, len);
		}
		break;
	case FLAG_CHAT:
		if (pUser != NULL && pUser->bMaster)
			ReceiveChat(pUser, recvBuf + sizeof(HEADER), len - sizeof(HEADER));
		break;
	case FLAG_VOICE:
		if (pUser != NULL && pUser->bMaster)
			ReceiveVoice(pUser, recvBuf, len);
		break;
	case FLAG_CALL:
		if (pUser != NULL && pUser->bMaster)
			ReceiveCall(pUser, recvBuf, len);
		break;
	case FLAG_REMOTE:
		if (pUser != NULL && pUser->bMaster)
			ReceiveRemote(pUser, (PREMOTE)recvBuf);
		break;
	case FLAG_LICENSE:
		ReceiveLicense((PLICENSE)recvBuf);
		break;
	case FLAG_LOGINREQ:
		if (pUser == NULL)
			ReceiveLoginRequest((PLOGINREQ)recvBuf, pSockSend);
		break;
	case FLAG_DENY:
		if (pUser == NULL)
			ReceiveDeny((PDENY)recvBuf);
		break;
	}
}

void	AcceptSocket(SOCKET sock)
{
	SOCKET	sock2;
	SOCKADDR_IN	socketAddr;
	int	addrLen;
	int	opt;

	addrLen = sizeof(socketAddr);
	sock2 = accept(sock, (LPSOCKADDR)&socketAddr, &addrLen);
	if (sock2 == INVALID_SOCKET)
		return;

	opt = 1;
	setsockopt(sock2, SOL_SOCKET, SO_KEEPALIVE, (char *)&opt, sizeof(opt));
	WSAAsyncSelect(sock2, pMainWnd->m_hWnd, WM_SOCKETTCP, FD_READ | FD_WRITE | FD_CLOSE);
}

void	CloseSocket(SOCKET sock)
{
	SOCKADDR_IN	socketAddr;
	int	addrLen;
	SOCK	sockUser;
//	PUSER	pUser;

	addrLen = sizeof(socketAddr);
	if (getpeername(sock, (SOCKADDR *)&socketAddr, &addrLen) == 0) {
		sockUser.bTCP = TRUE;
		sockUser.sock = sock;
		sockUser.IPAddress = socketAddr.sin_addr.s_addr;
		sockUser.portNo = socketAddr.sin_port;

//		if ((pUser = SearchUser(&sockUser)) != NULL) {
//			DeleteUser(pUser);
//			return;
//		}
	}

	closesocket(sock);
}

int	SendData(PSOCK pSock, LPVOID buf, int len)
{
	int	rc;

	if (pSock->sock == INVALID_SOCKET)
		return 0;

	if (bSecond)
		((HEADER *)buf)->flag |= FLAG_SECOND;

	if (pSock->bTCP) {
		rc = send(pSock->sock, (LPSTR)buf, len, 0);
	} else {
		rc = SendUDP(pSock->sock, pSock->IPAddress, pSock->portNo, (LPSTR)buf, len);
	}

	return rc;
}

int	SendUDP(SOCKET sock, u_long IPAddress, u_short portNo, LPSTR buf, int len)
{
	SOCKADDR_IN socketAddr;
	int	rc;
	int	err;

	for (;;) {
		socketAddr.sin_family      = AF_INET;
		socketAddr.sin_addr.s_addr = IPAddress;
		socketAddr.sin_port        = portNo;
		if ((rc = sendto(sock,
				(const char *)buf, len, 0,
				(LPSOCKADDR)&socketAddr,
				sizeof(socketAddr))) == SOCKET_ERROR) {
			err = WSAGetLastError();
			if (err != WSAEWOULDBLOCK)
				break;
		} else
			break;
	}

	return rc;
}

void	WriteSocketTCP(SOCKET sock)
{
	SOCKADDR_IN	socketAddr;
	int	addrLen;
	SOCK	sockUser;
//	PUSER	pUser;

	addrLen = sizeof(socketAddr);
	if (getpeername(sock, (SOCKADDR *)&socketAddr, &addrLen) == 0) {
		sockUser.bTCP = TRUE;
		sockUser.sock = sock;
		sockUser.IPAddress = socketAddr.sin_addr.s_addr;
		sockUser.portNo = socketAddr.sin_port;

//		if ((pUser = SearchUser(&sockUser)) != NULL)
//			SendCaptureData(pUser);
	}
}

void	SendCaptureData(PUSER pUser)
{
/*
	PCAPTUREBUF pCaptureBuf;
	int	len;

	pCaptureBuf = pUser->pCaptureBuf;
	if (pCaptureBuf == NULL)
		return;

	while (pCaptureBuf->sendSize != 0) {
		len = (int)min(pCaptureBuf->sendSize, 1024);
		if (SendData(&pUser->sock, pCaptureBuf->lpSendBuf, len) == -1) {
			if (WSAGetLastError() != WSAEWOULDBLOCK) {
				if (pUser->sock.bTCP)
					shutdown(pUser->sock.sock, 2);
				pCaptureBuf->sendSize = 0;
			}
			break;
		}

		pCaptureBuf->lpSendBuf += len;
		pCaptureBuf->sendSize -= len;
	}
*/
}

static void ReceiveDeny(PDENY pDeny)
{
	CString msg;

	msg.Format(IDS_DENY_CONxxxT, pDeny->userName, pDeny->message);
	AfxMessageBox(msg);
}
