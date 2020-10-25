#include <windows.h>
#include "cmctl.h"

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>
#include <io.h>
#include <dos.h>
#include <winsock.h>

static	char	title[] = "CMTEST";
static	void	DispErr(int err);

extern	int	__argc;
extern	char	**__argv;

int	PASCAL	WinMain(HANDLE hInstance, HANDLE hPrevInstance,
					LPSTR lpszCmdLine, int cmdShow)
{
	int	handle;
	int	err;

	if (__argc != 2)
		return(0);

/*{
	WSADATA	WSAData;
	SOCKET	sock;
	SOCKADDR_IN socketAddr;
	LPHOSTENT hp;
	u_long	IPAddress;
	int	i;

	WSAStartup(0x0101, &WSAData);

	hp = gethostbyname("aizawa-note");
	if (hp == NULL)
		return 0;
	IPAddress = *(u_long FAR *)hp->h_addr;

	sock = socket(PF_INET, SOCK_STREAM, 0);
{
	char	msg[100];
	wsprintf(msg, "sock=%d", sock);
	MessageBox(0, msg, title, MB_OK);
}

	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_addr.s_addr = IPAddress;
	socketAddr.sin_port        = htons(5100);
	conxxxt(sock, (LPSOCKADDR)&socketAddr, sizeof(socketAddr));

	for (i = 0; i < 1000; i++) {
		if (send(sock, (const char FAR *)"0123456789", 10, 0) == -1) {
			MessageBox(0, "err", title, MB_OK);
			break;
		}
	}

	MessageBox(0, "end", title, MB_OK);

	closesocket(sock);
	WSACleanup();
	return 0;
}*/

{
	WSADATA	WSAData;
	SOCKET	sock;
	SOCKADDR_IN socketAddr;
	LPHOSTENT hp;
	u_long	IPAddress;
	long	i;

	WSAStartup(0x0101, &WSAData);

	hp = gethostbyname(__argv[1]);
	if (hp == NULL)
		return 0;
	IPAddress = *(u_long FAR *)hp->h_addr;

	sock = socket(PF_INET, SOCK_DGRAM, 0);
{
	char	msg[100];
	wsprintf(msg, "sock=%d", sock);
	MessageBox(0, msg, title, MB_OK);
}
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(5100);
	socketAddr.sin_addr.s_addr = 0;
	bind(sock, (LPSOCKADDR)&socketAddr, sizeof(socketAddr));

	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_addr.s_addr = IPAddress;
	socketAddr.sin_port        = htons(5100);
	for (i = 0; i < 1000; i++) {
		if (sendto(sock, (const char FAR *)"0123456789", 10, 0,
				(LPSOCKADDR)&socketAddr,
				sizeof(socketAddr)) == -1) {
			MessageBox(0, "err", title, MB_OK);
			break;
		}
	}

	MessageBox(0, "end", title, MB_OK);

	closesocket(sock);
	WSACleanup();
	return 0;
}
	err = CMOpen(__argv[1], "TEST", 0, &handle);
	DispErr(err);
	if (err != 0)
		return(0);

//	CMControl(handle, CM_HOME_POSITION);
	MessageBox(0, "OK?", title, MB_OK);

	err = CMClose(handle);
	DispErr(err);

	return 0;
}

static	void	DispErr(int err)
{
	char	msg[100];

	wsprintf(msg, "err = %d", err);
	MessageBox(0, msg, title, MB_OK);
}
