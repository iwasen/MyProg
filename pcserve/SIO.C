/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: 他サーバアクセス処理
 *		ファイル名	: sio.c	
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcssock.h"
#include "pcserve.h"

/*=======================================================================
 |
 |		接続処理
 |
 |	int	NetConxxxt(pCCB, serverName)
 |
 |		PCCB	pCCB;		接続制御ブロックポインタ
 |		CHAR	*serverName;	サーバ名
 |
 |		int	返値		エラーコード
 |
 =======================================================================*/
int	NetConxxxt(PCCB pCCB, CHAR *serverName)
{
	int	s;
	HANDLE	fh;
	struct	hostent	*hp;
	struct	sockaddr_in	socketaddr;
	CHAR	pipe[128];
	SHORT	err;

	switch (pCCB->chType) {
	case CHT_TCPIP:
		hp = gethostbyname(serverName);
		if (hp == NULL)
			return -1;

		s = socket(PF_INET, SOCK_STREAM, 0);
		if (s < 0)
			return -1;

		socketaddr.sin_family      = AF_INET;
		socketaddr.sin_port        = htons((u_short)ini.portNo);
		socketaddr.sin_addr.s_addr = *(u_long *)hp->h_addr;
		err = conxxxt(s, (struct sockaddr *)&socketaddr,
							sizeof(socketaddr));
		if (err != 0) {
			closesocket(s);
			return -1;
		}

		pCCB->handle = s;
		break;
	case CHT_NETBIOS:
		sprintf(pipe, "\\\\%s%s", serverName, ini.pipeName);
		fh = CreateFile(pipe,GENERIC_READ | GENERIC_WRITE,
					0, NULL, OPEN_EXISTING,
					FILE_ATTRIBUTE_NORMAL, NULL);
		if (fh == INVALID_HANDLE_VALUE)
			return -1;
		pCCB->handle = (int)fh;
		break;
	default:
		return -1;
	}

	return 0;
}

/*=======================================================================
 |
 |		切断処理
 |
 |	BOOL	NetDisconxxxt(pCCB)
 |
 |		PCCB	pCCB;		接続制御ブロックポインタ
 |
 |		BOOL	返値		TRUE:正常　FLASE:エラー
 |
 =======================================================================*/
BOOL	NetDisconxxxt(PCCB pCCB)
{
	if (pCCB->handle == -1)
		return TRUE;

	switch (pCCB->chType) {
	case CHT_TCPIP:
		shutdown(pCCB->handle, 2);
		closesocket(pCCB->handle);
		break;
	case CHT_NETBIOS:
		CloseHandle((HANDLE)pCCB->handle);
		break;
	}

	return TRUE;
}

/*=======================================================================
 |
 |		データ送信処理
 |
 |	BOOL	SendData2(pCCB, data, length)
 |
 |		PCCB	pCCB;		接続制御ブロックポインタ
 |		VOID	*data;		送信データ
 |		int	length;		送信データ長
 |
 |		BOOL	返値		TRUE: 正常　FALSE: エラー
 |
 =======================================================================*/
BOOL	SendData2(PCCB pCCB, VOID *data, int length)
{
	DWORD	numBytes;
	int	len;
	int	ret;

	if (pCCB->handle == -1)
		return FALSE;

	len = length;

	switch (pCCB->chType) {
	case CHT_TCPIP:
		while (len) {
			ret = send(pCCB->handle, data, len, 0);
			if (ret == 0 || ret == -1) {
				return FALSE;
			}

			((CHAR *)data) += ret;
			len -= ret;
		}
		break;
	case CHT_NETBIOS:
		while (len) {
			if (!WriteFile((HANDLE)pCCB->handle, data, len,
							&numBytes, NULL)) {
				return FALSE;
			}
			((CHAR *)data) += numBytes;
			len -= numBytes;
		}
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
 |	BOOL	ReceiveData2(pCCB, data, length)
 |
 |		PCCB	pCCB;		接続制御ブロックポインタ
 |		VOID	*data;		受信バッファ
 |		int	length;		受信データ長
 |
 |		BOOL	返値		TRUE: 正常　FALSE: エラー
 |
 =======================================================================*/
BOOL	ReceiveData2(PCCB pCCB, VOID *data, int length)
{
	DWORD	numBytes;
	int	len;
	int	ret;

	if (pCCB->handle == -1)
		return FALSE;

	len = length;

	switch (pCCB->chType) {
	case CHT_TCPIP:
		while (len) {
			ret = recv(pCCB->handle, data, len, 0);
			if (ret == 0 || ret == -1) {
				return FALSE;
			}

			((CHAR *)data) += ret;
			len -= ret;
		}
		break;
	case CHT_NETBIOS:
		while (len) {
			if (!ReadFile((HANDLE)pCCB->handle, data, len,
							&numBytes, NULL)) {
				return FALSE;
			}

			if (numBytes == 0)
				return FALSE;

			((CHAR *)data) += numBytes;
			len -= numBytes;
		}
		break;
	default:
		return FALSE;
	}

	return TRUE;
}

int	Requexxxommand(PCCB pCCB, UINT id, UINT code, UINT paramLength,
							UINT dataLength)
{
	int	err = 0;

	if (pCCB->handle == -1)
		return -1;

	if (paramLength < MIN_PARAM_SIZE)
		paramLength = MIN_PARAM_SIZE;

	ENTER_CRITICAL_SECTION(&pCCB->csCCB);

	pCCB->command.id = id;
	(pCCB->command.seqNo)++;
	pCCB->command.code = code;
	pCCB->command.paramLength = paramLength;
	pCCB->command.dataLength = dataLength;

	if (SendCommand(pCCB)) {
		if (!(pCCB->command.id & ID_CONTINUE)) {
			if (ReceiveResponse(pCCB))
				err = pCCB->response.status;
			else
				err = ERROR_SERVER_RECEIVE;
		}
	} else
		err = ERROR_SERVER_SEND;

	LEAVE_CRITICAL_SECTION(&pCCB->csCCB);

	return err;
}

BOOL	SendCommand(PCCB pCCB)
{
	if (!SendData2(pCCB, &pCCB->command, 9 + pCCB->command.paramLength))
		return FALSE;

	if (pCCB->command.dataLength != 0 && pCCB->pInData != NULL) {
		if (!SendData2(pCCB, pCCB->pInData, pCCB->command.dataLength))
			return FALSE;
	}

	return TRUE;
}

BOOL	ReceiveResponse(PCCB pCCB)
{
	if (pCCB->command.id != ID_COMMAND_ASYNC) {
		if (!ReceiveData2(pCCB, &pCCB->response, sizeof(RESPONSE)))
			return FALSE;

		if (pCCB->response.id != ID_RESPONSE ||
				pCCB->response.seqNo != pCCB->command.seqNo)
			return FALSE;

		if (pCCB->response.dataLength != 0 && pCCB->pOutData != NULL) {
			if (!ReceiveData2(pCCB, pCCB->pOutData,
						pCCB->response.dataLength))
				return FALSE;
		}
	}

	return TRUE;
}
