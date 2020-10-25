/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: �ʐM����				*
 *		�t�@�C����	: com.c					*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>

/*--------------------------------------*/
/*		�����֐�		*/
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
 |		�|�[�g�I�[�v������
 |
 |	BOOL	OpenPort(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�I�[�v�����s
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
 |		�ʐM�p�����[�^�ݒ菈��
 |
 |	BOOL	SetPortParameter(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�G���[����
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
 |		�`���l���O�ʐM����
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
 |		�`���l���P�ʐM����
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
 |		�`���l���Q�ʐM����
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
 |		�`���l���R�ʐM����
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
 |		�ʐM���ʏ���
 |
 |	VOID	ExecChannel(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
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
 |		����ڑ�����
 |
 |	BOOL	Conxxxt(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |
 |		BOOL	�Ԓl		TRUE �F�ڑ�����
 |					FALSE�F���ڑ�
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
 |		���f���ɑ΂���R�}���h���M����
 |
 |	VOID	SendModemCommand(chNo, command, CRFlag)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		CHAR	*command;	���M����R�}���h
 |		BOOL	CRFlag;		�O�F�b�q��t�����Ȃ��@�P�F�t������
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
 |		���f������̃��b�Z�[�W��M����
 |
 |	VOID	ReceiveModem(chNo, rcvBuf, bufSize)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		CHAR	*rcvBuf;	��M�o�b�t�@
 |		USHORT	bufSize;	��M�o�b�t�@�T�C�Y
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
 |		����ؒf����
 |
 |	VOID	Disconxxxt(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
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
 |		�P�s��M����
 |
 |	BOOL	ReceiveLine(chNo, rcvBuf, bufSize)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		CHAR	*rcvBuf;	��M�o�b�t�@
 |		USHORT	bufSize;	��M�o�b�t�@�T�C�Y
 |
 |		BOOL	�Ԓl		TRUE �F�����M
 |					FALSE�F�^�C���A�E�g�܂��͉���f���o
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
 |		�P������M����
 |
 |	BOOL	ReceiveChar(chNo, rcvChar)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		CHAR	*rcvChar;	��M����
 |
 |		BOOL	�Ԓl		TRUE �F�����M
 |					FALSE�F�^�C���A�E�g�܂��͉���f���o
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
 |		�G�R�[�o�b�N����
 |
 |	VOID	EchoBack(chNo, echoChar)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		CHAR	echoChar;	�G�R�[�o�b�N����
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
 |		�P�������M����
 |
 |	BOOL	SendLine(chNo, sendData, newLine)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		CHAR	*sendData;	���M�f�[�^
 |		USHORT	newLine;	���s��
 |
 |		BOOL	�Ԓl		TRUE �F���푗�M
 |					FALSE�F�^�C���A�E�g�܂��͉���f���o
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
 |		���s�������M����
 |
 |	VOID	SendCRLF(chNo, newLine)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		USHORT	newLine;	���s��
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
 |		�f�[�^���M����
 |
 |	BOOL	SendTerm(chNo, sendData, length)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		CHAR	*sendData;	���M�f�[�^
 |		USHORT	length;		���M�f�[�^��
 |
 |		BOOL	�Ԓl		TRUE �F���푗�M
 |					FALSE�F�^�C���A�E�g�܂��͉���f���o
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
 |		��M�L���[�N���A����
 |
 |	VOID	ClearRcvQue(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
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
 |		�^�[�~�i�����[�h����
 |
 |	VOID	TerminalProc(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
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