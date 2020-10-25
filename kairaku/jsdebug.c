#include <stdio.h>
#include <winsock.h>

int main()
{
	WSADATA	WSAData;
	SOCKET sock;
	LPHOSTENT hp;
	SOCKADDR_IN	socketAddr;
	char buf[2048];
	int len;
	int err;

	if (WSAStartup(0x0101, &WSAData) != 0) {
		printf("ソケット初期化エラー\n");
		return 1;
	}

	sock = socket(PF_INET, SOCK_DGRAM, 0);
	if (sock == INVALID_SOCKET) {
		printf("ソケットが作成できません。\n");
		return 1;
	}

	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(7890);
	socketAddr.sin_addr.s_addr = 0;
	err = bind(sock, (LPSOCKADDR)&socketAddr, sizeof(socketAddr));
	if (err != 0) {
		printf("ソケットがバインドできません。\n");
		return 1;
	}

	printf("Ready !!\n\n");

	for (;;) {
		len = recv(sock, buf, sizeof(buf) - 1, 0);
		buf[len] = '\0';
		printf("%s\n", buf);
	}

	return 0;
}
