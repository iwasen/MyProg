/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: 通信処理				*
 *		ファイル名	: com.c					*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	BOOL	OpenPort(USHORT);
static	VOID	ExecChannel(USHORT);
static	BOOL	Conxxxt(USHORT);
static	VOID	SendModemCommand(USHORT, CHAR *, BOOL);
static	VOID	ReceiveModem(USHORT, CHAR *, USHORT);
static	VOID	EchoBack(USHORT, CHAR);
static	BOOL	SendTerm(USHORT, CHAR *, USHORT);
static	VOID	TerminalProc(USHORT);

/*=======================================================================
 |
 |		ポートオープン処理
 |
 |	BOOL	OpenPort(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：オープン失敗
 |
 =======================================================================*/
static	BOOL	OpenPort(USHORT chNo)
{
	HFILE		fileHndl;
	USHORT		ActionTaken;
	DCBINFO		DCBInfo;
	MODEMSTATUS	ctrlSignal;

	if (chNo == CH0) {
		DosMakePipe(&portHndl[CH0], &ch0Hndl, 0);
		return(TRUE);
	}

	/* open the com port */
	if (DosOpen(portName[chNo], &fileHndl, &ActionTaken, 0L,
			FILE_NORMAL, FILE_OPEN,
			OPEN_ACCESS_READWRITE | OPEN_SHARE_DENYREADWRITE,
			0L) != 0)
		return(FALSE);

	portHndl[chNo] = fileHndl;

	SetPortParameter(chNo);

	DCBInfo.usWriteTimeout = 100;
	DCBInfo.usReadTimeout = 100;
	DCBInfo.fbCtlHndShake = MODE_CTS_HANDSHAKE;
	DCBInfo.fbFlowReplace = MODE_AUTO_TRANSMIT | MODE_AUTO_RECEIVE;
	DCBInfo.fbTimeout = MODE_READ_TIMEOUT;
	DCBInfo.bErrorReplacementChar = 0;
	DCBInfo.bBreakReplacementChar = 0;
	DCBInfo.bXONChar = 0x11;
	DCBInfo.bXOFFChar = 0x13;

	/* set device control block info */
	if (DosDevIOCtl(0L, &DCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, fileHndl) != 0) {
		DosClose(fileHndl);
		return(FALSE);
	}

	ctrlSignal.fbModemOn = DTR_ON | RTS_ON;
	ctrlSignal.fbModemOff = 0xff;
	if (DosDevIOCtl(&ActionTaken, &ctrlSignal, ASYNC_SETMODEMCTRL,
					IOCTL_ASYNC, fileHndl) != 0) {
		DosClose(fileHndl);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		通信パラメータ設定処理
 |
 |	BOOL	SetPortParameter(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：エラー発生
 |
 =======================================================================*/
BOOL	SetPortParameter(USHORT chNo)
{
	static	USHORT baudRateTable[6] = {
		300, 600, 1200, 2400, 4800, 9600
	};
	static	BYTE	dataBitsTable[2] = {7, 8};
	static	BYTE	parityTable[3] = {0, 1, 2};
	static	BYTE	stopBitsTable[3] = {0, 1, 2};
	COMPARAM	*cp = &sv.comParam[chNo];
	LIxxxONTROL	lixxxontrol;

	/* set the baud rate */
	if (DosDevIOCtl(0L, &baudRateTable[cp->baudRate], ASYNC_SETBAUDRATE,
				IOCTL_ASYNC, portHndl[chNo]) != 0) {
		return(FALSE);
	}

	/* set Data Bits, Stop Bits, Parity */
	lixxxontrol.bDataBits = dataBitsTable[cp->chDataBits];
	lixxxontrol.bParity = parityTable[cp->chParity];
	lixxxontrol.bStopBits = stopBitsTable[cp->chStopBits];
	lixxxontrol.fTransBreak = 0x00;
	if (DosDevIOCtl(0L, &lixxxontrol, ASYNC_SETLIxxxTRL,
				IOCTL_ASYNC, portHndl[chNo]) != 0) {
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		チャネル０通信処理
 |
 |	VOID	FAR CH0Thread()
 |
 =======================================================================*/
VOID	FAR CH0Thread(VOID)
{
	ExecChannel(CH0);
}

/*=======================================================================
 |
 |		チャネル１通信処理
 |
 |	VOID	FAR CH1Thread()
 |
 =======================================================================*/
VOID	FAR CH1Thread(VOID)
{
	ExecChannel(CH1);
}

/*=======================================================================
 |
 |		チャネル２通信処理
 |
 |	VOID	FAR CH2Thread()
 |
 =======================================================================*/
VOID	FAR CH2Thread(VOID)
{
	ExecChannel(CH2);
}

/*=======================================================================
 |
 |		チャネル３通信処理
 |
 |	VOID	FAR CH3Thread()
 |
 =======================================================================*/
VOID	FAR CH3Thread(VOID)
{
	ExecChannel(CH3);
}

/*=======================================================================
 |
 |		通信共通処理
 |
 |	VOID	ExecChannel(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	ExecChannel(USHORT chNo)
{
	CHAR	rcvBuf[5];

	for (;;) {
		while (!portOpenFlag[chNo]) {
			if (sv.comParam[chNo].conxxxt != PORT_NOTUSE &&
					 	OpenPort(chNo) == TRUE) {
				portOpenFlag[chNo] = TRUE;
			} else
				DosSuspendThread(threadID[chNo]);
		}

		if (Conxxxt(chNo)) {
			echoBackFlag[chNo] = TRUE;
			receiveTimer[chNo] = sv.env.timer * 60;
			for (;;) {
				if (ReceiveLine(chNo, rcvBuf,
						sizeof(rcvBuf)) == FALSE)
					break;
				if (rcvBuf[0] == '\0') {
					conxxxtFlag[chNo] = TRUE;
					if (Login(chNo)) {
						OnLine(chNo);
						Logout(chNo);
					}
					break;
				}
			}	
			Disconxxxt(chNo);
			conxxxtFlag[chNo] = FALSE;
		}

		while (closeRequest)
			DosSleep(1000);

		if (terminalMode[chNo] & TMODE_TERMINAL)
			TerminalProc(chNo);

		if (sv.comParam[chNo].conxxxt == PORT_NOTUSE) {
			portOpenFlag[chNo] = FALSE;
			DosClose(portHndl[chNo]);
		}
	}
}

/*=======================================================================
 |
 |		回線接続処理
 |
 |	BOOL	Conxxxt(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 |		BOOL	返値		TRUE ：接続した
 |					FALSE：未接続
 |
 =======================================================================*/
static	BOOL	Conxxxt(USHORT chNo)
{
	static	BOOL	cts[N_CHANNEL];
	BYTE	ctrlSignals;
	SHORT	i;
	BOOL	callFlag;
	BOOL	conxxxtFlag;
	CHAR	rcvBuf[11];

	if (portOpenFlag[chNo]) {
		DosDevIOCtl(0L, 0L, ASYNC_STARTTRANSMIT,
					IOCTL_ASYNC, portHndl[chNo]);
	}

	conxxxtFlag = FALSE;
	callFlag = FALSE;
	if (sv.comParam[chNo].conxxxt == PORT_MODEM) {
		ReceiveModem(chNo, rcvBuf, sizeof(rcvBuf));
		DosDevIOCtl(&ctrlSignals, 0L, ASYNC_GETMODEMINPUT,
					IOCTL_ASYNC, portHndl[chNo]);
		if (ctrlSignals & CTS_ON) {
			if (cts[chNo] == FALSE) {
				SendModemCommand(chNo,
					sv.modem[chNo].initCmd, TRUE);
			}
			cts[chNo] = TRUE;
		} else
			cts[chNo] = FALSE;

		switch (sv.modem[chNo].detectCall) {
		case 'A':
			if (ctrlSignals & DCD_ON)
				conxxxtFlag = TRUE;
			break;
		case 'R':
			if (ctrlSignals & RI_ON)
				callFlag = TRUE;
			break;
		case 'M':
			if (strcmp(rcvBuf, sv.modem[chNo].ringMsg) == 0)
				callFlag = TRUE;
			break;
		}

		if (callFlag) {
			DosSleep(1000);
			SendModemCommand(chNo, sv.modem[chNo].conxxxtCmd,
								TRUE);
			for (i = 0; i < 30; i++) {
				DosSleep(1000);
				DosDevIOCtl(&ctrlSignals, 0L,
						ASYNC_GETMODEMINPUT,
						IOCTL_ASYNC, portHndl[chNo]);
				if (ctrlSignals & DCD_ON)
					break;
			}
			ClearRcvQue(chNo);
			if (i < 30)
				conxxxtFlag = TRUE;
			else
				Disconxxxt(chNo);
		}
	} else
		conxxxtFlag = TRUE;

	return(conxxxtFlag);
}

/*=======================================================================
 |
 |		モデムに対するコマンド送信処理
 |
 |	VOID	SendModemCommand(chNo, command, CRFlag)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*command;	送信するコマンド
 |		BOOL	CRFlag;		０：ＣＲを付加しない　１：付加する
 |
 =======================================================================*/
static	VOID	SendModemCommand(USHORT chNo, CHAR *cp, BOOL CRFlag)
{
	static	CHAR	cr[1] = {0x0d};
	USHORT	numBytes;

	for (;;) {
		for ( ; *cp != '|' && *cp != '\0'; cp++) {
			DosWrite(portHndl[chNo], cp, 1, &numBytes);
			DosSleep(100);
		}
		if (CRFlag) {
			DosWrite(portHndl[chNo], cr, 1, &numBytes);
			DosSleep(1000);
		}
		ClearRcvQue(chNo);

		if (*cp == '|')
			cp++;
		else if (*cp == '\0')
			break;
	}
}

/*=======================================================================
 |
 |		モデムからのメッセージ受信処理
 |
 |	VOID	ReceiveModem(chNo, rcvBuf, bufSize)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*rcvBuf;	受信バッファ
 |		USHORT	bufSize;	受信バッファサイズ
 |
 =======================================================================*/
VOID	ReceiveModem(USHORT chNo, CHAR *rcvBuf, USHORT bufSize)
{
	USHORT	numBytes;
	USHORT	rcvCount;
	CHAR	rcvChar;

	rcvCount = 0;
	for (;;) {
		DosRead(portHndl[chNo], &rcvChar, 1, &numBytes);
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
 |		回線切断処理
 |
 |	VOID	Disconxxxt(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
VOID	Disconxxxt(USHORT chNo)
{
	USHORT		ActionTaken;
	MODEMSTATUS	ctrlSignal;
	BYTE	ctrlSignals;

	if (sv.comParam[chNo].conxxxt == PORT_MODEM) {
		DosDevIOCtl(&ctrlSignals, 0L, ASYNC_GETMODEMINPUT,
					IOCTL_ASYNC, portHndl[chNo]);
		if (ctrlSignals & DCD_ON)
			DosSleep(5000);

		switch (sv.modem[chNo].disconxxxt) {
		case 'D':
			ctrlSignal.fbModemOn = 0x00;
			ctrlSignal.fbModemOff = DTR_OFF;
			DosDevIOCtl(&ActionTaken, &ctrlSignal,
					ASYNC_SETMODEMCTRL,
					IOCTL_ASYNC, portHndl[chNo]);

			DosSleep(sv.modem[chNo].dtrOffTime * 1000L);

			ctrlSignal.fbModemOn = DTR_ON;
			ctrlSignal.fbModemOff = 0xff;
			DosDevIOCtl(&ActionTaken, &ctrlSignal,
					ASYNC_SETMODEMCTRL,
					IOCTL_ASYNC, portHndl[chNo]);
			break;
		case 'C':
			DosSleep(sv.modem[chNo].guardTime * 1000L);
			SendModemCommand(chNo, sv.modem[chNo].escCode, FALSE);
			DosSleep(sv.modem[chNo].guardTime * 1000L);
			SendModemCommand(chNo, sv.modem[chNo].discCmd, TRUE);
			DosSleep(1000);
			break;
		}
		ClearRcvQue(chNo);
	}
}

/*=======================================================================
 |
 |		１行受信処理
 |
 |	BOOL	ReceiveLine(chNo, rcvBuf, bufSize)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*rcvBuf;	受信バッファ
 |		USHORT	bufSize;	受信バッファサイズ
 |
 |		BOOL	返値		TRUE ：正常受信
 |					FALSE：タイムアウトまたは回線断検出
 |
 =======================================================================*/
BOOL	ReceiveLine(USHORT chNo, CHAR *rcvBuf, USHORT bufSize)
{
	USHORT	rcvCount;
	CHAR	rcvChar;
	BYTE	ctrlSignals;
	CHAR	*mp;

	if (sv.comParam[chNo].conxxxt == PORT_MODEM) {
		DosDevIOCtl(&ctrlSignals, 0L,
			ASYNC_GETMODEMINPUT, IOCTL_ASYNC,
			portHndl[chNo]);
		if (!(ctrlSignals & DCD_ON))
			return(FALSE);
	}

	rcvCount = 0;
	for (;;) {
		if (ReceiveChar(chNo, &rcvChar) == FALSE) {
			rcvBuf[0] = '\0';
			return(FALSE);
		}
		if (rcvChar == 0x0d) {
			EchoBack(chNo, rcvChar);
			break;
		} else if (rcvChar == 0x08) {
			if (rcvCount > 0) {
				EchoBack(chNo, rcvChar);
				rcvCount--;
			}
		} else {
			if (rcvChar >= 0x20 || rcvChar == 0x09) {
				if (rcvCount < bufSize-1) {
					EchoBack(chNo, rcvChar);
					rcvBuf[rcvCount++] = rcvChar;
				}
			}
		}
	}
	rcvBuf[rcvCount] = '\0';

	if (monitorFlag[chNo]) {
		if ((mp = malloc(rcvCount)) != NULL) {
			memcpy(mp, rcvBuf, rcvCount);
			while (WinPostMsg(clientMonitor[chNo], WM_USER_MONITOR,
			    (MPARAM)mp, MPFROM2SHORT(rcvCount, 1)) == FALSE);
		}
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		１文字受信処理
 |
 |	BOOL	ReceiveChar(chNo, rcvChar)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*rcvChar;	受信文字
 |
 |		BOOL	返値		TRUE ：正常受信
 |					FALSE：タイムアウトまたは回線断検出
 |
 =======================================================================*/
BOOL	ReceiveChar(USHORT chNo, CHAR *rcvChar)
{
	SHORT	timeOutCounter;
	USHORT	numBytes;
	BYTE	ctrlSignals;

	timeOutCounter = receiveTimer[chNo];
	for (;;) {
		DosRead(portHndl[chNo], rcvChar, 1, &numBytes);
		if (sv.comParam[chNo].conxxxt == PORT_NOTUSE || closeRequest)
			return(FALSE);
		if (terminalMode[chNo] & TMODE_TERMINAL)
			TerminalProc(chNo);
		if (numBytes == 0) {
			if (sv.comParam[chNo].conxxxt == PORT_MODEM) {
				DosDevIOCtl(&ctrlSignals, 0L,
					ASYNC_GETMODEMINPUT, IOCTL_ASYNC,
					portHndl[chNo]);
				if (!(ctrlSignals & DCD_ON))
					return(FALSE);
			} else if (sv.comParam[chNo].conxxxt == PORT_DIRECT) {
				DosDevIOCtl(&ctrlSignals, 0L,
					ASYNC_GETMODEMINPUT, IOCTL_ASYNC,
					portHndl[chNo]);
				if (!(ctrlSignals & DSR_ON)) {
					return(FALSE);}
			}
			if (timeOutCounter != 0) {
				if (--timeOutCounter == 0)
					return(FALSE);
			}
		} else {
			if (chNo == CH0 && *rcvChar == 0xff)
				return(FALSE);
			break;
		}
	}
	return(TRUE);
}

/*=======================================================================
 |
 |		エコーバック処理
 |
 |	VOID	EchoBack(chNo, echoChar)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	echoChar;	エコーバック文字
 |
 =======================================================================*/
static	VOID	EchoBack(USHORT chNo, CHAR echoChar)
{
	static	CHAR	LF[]="\x0a";

	if (echoBackFlag[chNo]) {
		SendTerm(chNo, &echoChar, 1);
		if (echoChar == 0x0d)
			SendTerm(chNo, LF, 1);
	}
}

/*=======================================================================
 |
 |		１文字送信処理
 |
 |	BOOL	SendLine(chNo, sendData, newLine)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*sendData;	送信データ
 |		USHORT	newLine;	改行数
 |
 |		BOOL	返値		TRUE ：正常送信
 |					FALSE：タイムアウトまたは回線断検出
 |
 =======================================================================*/
BOOL	SendLine(USHORT chNo, CHAR *sendData, USHORT newLine)
{
	CHAR	*mp;
	USHORT	msgLen;

	if (SendTerm(chNo, sendData, strlen(sendData)) == FALSE)
		return(FALSE);

	if (monitorFlag[chNo]) {
		msgLen = strlen(sendData);
		if ((mp = malloc(msgLen)) != NULL) {
			memcpy(mp, sendData, msgLen);
			while (WinPostMsg(clientMonitor[chNo], WM_USER_MONITOR,
				(MPARAM)mp, MPFROM2SHORT(msgLen, 0)) == FALSE);
		}
	}

	SendCRLF(chNo, newLine);

	return(TRUE);
}

/*=======================================================================
 |
 |		改行文字送信処理
 |
 |	VOID	SendCRLF(chNo, newLine)
 |
 |		USHORT	chNo;		チャネル番号
 |		USHORT	newLine;	改行数
 |
 =======================================================================*/
VOID	SendCRLF(USHORT chNo, USHORT newLine)
{
	static	CHAR	crlf[] = "\x0d\x0a";
	USHORT	i;

	for (i = 0; i != newLine; i++)
		SendTerm(chNo, crlf, 2);

	if (monitorFlag[chNo])
		while (WinPostMsg(clientMonitor[chNo], WM_USER_MONITOR,
				NULL, MPFROM2SHORT(0, newLine)) == FALSE);
}

/*=======================================================================
 |
 |		データ送信処理
 |
 |	BOOL	SendTerm(chNo, sendData, length)
 |
 |		USHORT	chNo;		チャネル番号
 |		CHAR	*sendData;	送信データ
 |		USHORT	length;		送信データ長
 |
 |		BOOL	返値		TRUE ：正常送信
 |					FALSE：タイムアウトまたは回線断検出
 |
 =======================================================================*/
static	BOOL	SendTerm(USHORT chNo, CHAR *sendData, USHORT length)
{
	USHORT	numBytes;
	BYTE	ctrlSignals;
	CHAR	*sp;
	SHORT	toCounter;

	if (logFp[chNo] != NULL)
		fwrite(sendData, length, 1, logFp[chNo]);

	if (chNo != CH0) {
		sp = sendData;
		for (toCounter = 0; toCounter < 60 ; toCounter++) {
			if (sv.comParam[chNo].conxxxt == PORT_MODEM) {
				DosDevIOCtl(&ctrlSignals, 0L,
						ASYNC_GETMODEMINPUT,
						IOCTL_ASYNC, portHndl[chNo]);
				if (!(ctrlSignals & DCD_ON))
					break;
			} else if (sv.comParam[chNo].conxxxt == PORT_DIRECT) {
				DosDevIOCtl(&ctrlSignals, 0L,
						ASYNC_GETMODEMINPUT,
						IOCTL_ASYNC, portHndl[chNo]);
				if (!(ctrlSignals & DSR_ON))
					break;
			}
			DosWrite(portHndl[chNo], sp, length, &numBytes);
			length -= numBytes;
			if (length == 0)
				break;
			sp += numBytes;
		}
		if (length != 0)
			return(FALSE);
	} else {
		if (terminalMode[CH0] & TMODE_TERMINAL) {
			if ((sp = malloc(length)) != NULL) {
				memcpy(sp, sendData, length);
				while (WinPostMsg(clientTerminal[chNo],
					WM_USER_RCVTERM2, sp,
					MPFROMSHORT(length)) == FALSE);
			}
		}
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		受信キュークリア処理
 |
 |	VOID	ClearRcvQue(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
VOID	ClearRcvQue(USHORT chNo)
{
	RXQUEUE	cReceiveQue;
	CHAR	rcvDummy;
	USHORT	numBytes;

	for (;;) {
		DosDevIOCtl(&cReceiveQue, 0L, ASYNC_GETINQUECOUNT,
					IOCTL_ASYNC, portHndl[chNo]);
		if (cReceiveQue.cch == 0)
			break;
		DosRead(portHndl[chNo], &rcvDummy, 1, &numBytes);
	}
}

/*=======================================================================
 |
 |		ターミナルモード処理
 |
 |	VOID	TerminalProc(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
static	VOID	TerminalProc(USHORT chNo)
{
	CHAR	rcvChar;
	USHORT	numBytes;

	if (chNo != CH0) {
		while (terminalMode[chNo] & TMODE_TERMINAL) {
			DosRead(portHndl[chNo], &rcvChar, 1, &numBytes);
			if (numBytes != 0) {
				while (WinPostMsg(clientTerminal[chNo],
					WM_USER_RCVTERM1,
					MPFROMSHORT(rcvChar), 0L) == FALSE);
			}

			if (terminalMode[chNo] & TMODE_XRCV) {
				RXmodem(chNo, termXmodemFP[chNo],
							TermBlockCount);
				TermBlockCount(chNo, -1);
			}

			if (terminalMode[chNo] & TMODE_XSND) {
				SXmodem(chNo, termXmodemFP[chNo],
							TermBlockCount);
				TermBlockCount(chNo, -1);
			}
		}
	}
}
