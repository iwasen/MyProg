/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: RS-232C 入出力処理
 *		ファイル名	: rs232c.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcsclnt.h"

static	BOOL	SendModemCommand(int, CHAR *);

#ifdef	OS_MSDOS
static	int	intTbl[3] = {0x19, 0xd4, 0xd5};
static	CHAR	*bufTbl[3];
#endif
static	BOOL	fModem[3];

/*=======================================================================
 |
 |		RS-232C オープン
 |
 |	SHORT	OpenRS232C(portNo, speed, modem)
 |
 |		SHORT	portNo;		ポート番号
 |		SHORT	speed;		ボーレート
 |		BOOL	modem;		０：直結　１：モデム
 |
 |		SHORT	返値		ハンドル
 |
 =======================================================================*/
int	OpenRS232C(int portNo, int speed, BOOL modem)
{
#ifdef	OS_MSDOS
	static	CHAR	speedTbl[] = {2, 3, 4, 5, 6, 7, 8, 9};
	union	REGS	reg;
	struct	SREGS	sreg;
	CHAR	*bufp;

	if ((bufp = malloc(RS_BLOCKSIZE+20)) == NULL)
		return -1;

	reg.h.ah = 0;
	reg.h.al = speedTbl[speed];
	reg.h.bh = 6;			/* 3 Sec. */
	reg.h.bl = 6;			/* 3 Sec. */
	reg.h.ch = 0x4e;
	reg.h.cl = 0x37;
	reg.x.dx = RS_BLOCKSIZE;
	reg.x.di = FP_OFF(bufp);
	sreg.es = FP_SEG(bufp);

	int86x(intTbl[portNo], &reg, &reg, &sreg);

	if (reg.h.ah != 0) {
		free(bufp);
		return -1;
	}

	bufTbl[portNo] = bufp;
	fModem[portNo] = modem;

	return portNo;
#endif

#ifdef	OS_WINDOWS
	static	unsigned int	speedTbl[] = {300, 600, 1200, 2400, 4800, 9600,
								19200, 38400};
	CHAR	portName[6];
	SHORT	idComDev;
	DCB	dcb;

	wsprintf(portName, "COM%d", portNo + 1);
	if ((idComDev = OpenComm(portName, RS_BLOCKSIZE+6, RS_BLOCKSIZE+6))
									< 0)
		return -1;

	memset(&dcb, 0, sizeof(dcb));
	dcb.Id = (BYTE)idComDev;
	dcb.BaudRate = speedTbl[speed];
	dcb.ByteSize = 8;
//	dcb.RlsTimeout = 1000;
	dcb.CtsTimeout = 1000;
	if (!modem)
		dcb.DsrTimeout = 1000;
	dcb.fBinary = 1;

	if (SetCommState(&dcb) < 0) {
		CloseComm(idComDev);
		return -1;
	}

	fModem[portNo] = modem;

	return idComDev;
#endif

#ifdef	OS_OS2
	static	unsigned int	speedTbl[] = {300, 600, 1200, 2400, 4800, 9600,
								19200, 38400};
	HFILE		fileHndl;
	USHORT		ActionTaken;
	DCBINFO		DCBInfo;
	MODEMSTATUS	ctrlSignal;
	CHAR	portName[6];
	LIxxxONTROL	lixxxontrol;

	sprintf(portName, "COM%d", portNo + 1);
	if (DosOpen(portName, &fileHndl, &ActionTaken, 0L,
			FILE_NORMAL, FILE_OPEN,
			OPEN_ACCESS_READWRITE | OPEN_SHARE_DENYREADWRITE,
			0L) != 0)
		return -1;

	/* ボーレート設定 */
	if (DosDevIOCtl(0L, &speedTbl[speed], ASYNC_SETBAUDRATE,
					IOCTL_ASYNC, fileHndl) != 0) {
		DosClose(fileHndl);
		return -1;
	}

	/* データビット長、ストップビット長，パリティセット */
	lixxxontrol.bDataBits = 8;
	lixxxontrol.bParity = 0;
	lixxxontrol.bStopBits = 0;
	lixxxontrol.fTransBreak = 0x00;
	if (DosDevIOCtl(0L, &lixxxontrol, ASYNC_SETLIxxxTRL,
					IOCTL_ASYNC, fileHndl) != 0) {
		DosClose(fileHndl);
		return -1;
	}

	DCBInfo.usWriteTimeout = 300;
	DCBInfo.usReadTimeout = 300;
	DCBInfo.fbCtlHndShake = MODE_CTS_HANDSHAKE;
	DCBInfo.fbFlowReplace = 0;
	DCBInfo.fbTimeout = MODE_READ_TIMEOUT;
	DCBInfo.bErrorReplacementChar = 0;
	DCBInfo.bBreakReplacementChar = 0;
	DCBInfo.bXONChar = 0x11;
	DCBInfo.bXOFFChar = 0x13;

	/* set device control block info */
	if (DosDevIOCtl(0L, &DCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, fileHndl) != 0) {
		DosClose(fileHndl);
		return -1;
	}

	ctrlSignal.fbModemOn = DTR_ON | RTS_ON;
	ctrlSignal.fbModemOff = 0xff;
	if (DosDevIOCtl(&ActionTaken, &ctrlSignal, ASYNC_SETMODEMCTRL,
					IOCTL_ASYNC, fileHndl) != 0) {
		DosClose(fileHndl);
		return -1;
	}

	return fileHndl;
#endif

#ifdef	OS_WNT
	return -1;
#endif
}

/*=======================================================================
 |
 |		RS-232C 受信
 |
 |	int	ReadRS232C(handle, data, length)
 |
 |		SHORT	handle;		ハンドル
 |		CHAR	*data;		受信バッファ
 |		int	length;		受信バッファ長
 |
 |		int	返値		受信データ長
 |
 =======================================================================*/
int	ReadRS232C(int handle, CHAR *data, int length)
{
#ifdef	OS_MSDOS
	union	REGS	reg;
	int	readBytes;

	readBytes = 0;

	while (length) {
		reg.h.ah = 0x04;
		int86(intTbl[handle], &reg, &reg);

		if (reg.h.ah == 0x01)
			return -1;

		if (reg.h.ah == 0x03)
			break;

		*data++ = reg.h.ch;
		readBytes++;
		length--;
	}

	return readBytes;
#endif

#ifdef	OS_WINDOWS
	int	readBytes;
	int	n;
	DWORD	tick;

	readBytes = 0;
	tick = GetTickCount();
	while (length) {
		if ((n = ReadComm(handle, data, length)) < 0)
			return -1;

		if (n != 0) {
			data += n;
			readBytes += n;
			length -= n;
			tick = GetTickCount();
		} else {
			if (GetTickCount() > tick + 3000)
				break;
		}

	}

	return readBytes;
#endif

#ifdef	OS_OS2
	int	readBytes;
	int	n;

	readBytes = 0;
	while (length) {
		if (DosRead(handle, data, length, &n) != 0)
			return -1;

		if (n != 0) {
			data += n;
			readBytes += n;
			length -= n;
		} else
			break;
	}

	return readBytes;
#endif

#ifdef	OS_WNT
	return 0;
#endif
}

/*=======================================================================
 |
 |		RS-232C 送信
 |
 |	int	WriteRS232C(handle, data, length)
 |
 |		int	handle;		ハンドル
 |		CHAR	*data;		送信データ
 |		int	length;		送信データ長
 |
 |		int	返値		送信データ長
 |
 =======================================================================*/
int	WriteRS232C(int handle, CHAR *data, int length)
{
#ifdef	OS_MSDOS
	union	REGS	reg;
	int	writeBytes;

	writeBytes = 0;

	while (length) {
		reg.h.ah = 0x03;
		reg.h.al = *data++;
		int86(intTbl[handle], &reg, &reg);

		if (reg.h.ah == 0x01)
			return -1;

		if (reg.h.ah == 0x03)
			break;

		writeBytes++;
		length--;
	}

	return writeBytes;
#endif

#ifdef	OS_WINDOWS
	int	writeBytes;

	if ((writeBytes = WriteComm(handle, data, length)) < 0)
		return -1;

	return writeBytes;
#endif

#ifdef	OS_OS2
	SHORT	writeBytes;

	if (DosWrite(handle, data, length, &writeBytes) != 0)
		return -1;

	return writeBytes;
#endif

#ifdef	OS_WNT
	return -1;
#endif
}

/*=======================================================================
 |
 |		RS-232C クローズ
 |
 |	VOID	CloseRS232C(handle)
 |
 |		int	handle;		ハンドル
 |
 =======================================================================*/
VOID	CloseRS232C(int handle)
{
#ifdef	OS_MSDOS
	union	REGS	reg;
	CHAR	buf[2];

	for (;;) {
		reg.h.ah = 0x06;
		int86(intTbl[handle], &reg, &reg);
		if (reg.h.ch & 0x04)
			break;
	}

	ReadRS232C(handle, buf, 1);

	reg.h.ah = 0x05;
	reg.h.al = 0x40;
	reg.h.cl = 0x40;
	int86(intTbl[handle], &reg, &reg);

	if (bufTbl[handle] != NULL)
		free(bufTbl[handle]);
#endif

#ifdef	OS_WINDOWS
	CHAR	buf[2];

	ReadRS232C(handle, buf, 1);
	CloseComm(handle);
#endif

#ifdef	OS_OS2
	MODEMSTATUS	ctrlSignal;
	USHORT		ActionTaken;
	CHAR	buf[2];

	ctrlSignal.fbModemOn = 0x00;
	ctrlSignal.fbModemOff = DTR_OFF;
	DosDevIOCtl(&ActionTaken, &ctrlSignal, ASYNC_SETMODEMCTRL,
						IOCTL_ASYNC, handle);
	ReadRS232C(handle, buf, 1);
	DosClose(handle);
#endif

#ifdef	OS_WNT
	return;
#endif
}

/*=======================================================================
 |
 |		RS-232C DSR チェック
 |
 |	BOOL	CheckDSRRS232C(handle)
 |
 |		int	handle;		ハンドル
 |
 |		BOOL	返値		TRUE:DSR ON, FALSE:DSR OFF
 |
 =======================================================================*/
BOOL	CheckDSRRS232C(int handle)
{
#ifdef	OS_MSDOS
	union	REGS	reg;

	reg.h.ah = 0x06;
	int86(intTbl[handle], &reg, &reg);

	if (reg.h.ah == 0x01)
		return FALSE;

	if ((reg.h.ch & 0x80) == 0)
		return FALSE;

	return TRUE;
#endif

#ifdef	OS_WINDOWS
	if (GetCommError(handle, NULL) & CE_DSRTO)
		return FALSE;

	return TRUE;
#endif

#ifdef	OS_OS2
	BYTE	ctrlSignals;

	DosDevIOCtl(&ctrlSignals, 0L, ASYNC_GETMODEMINPUT,
						IOCTL_ASYNC, handle);
	if (!(ctrlSignals & DSR_ON))
		return FALSE;

	return TRUE;
#endif

#ifdef	OS_WNT
	return FALSE;
#endif
}

/*=======================================================================
 |
 |		RS-232C CD チェック
 |
 |	BOOL	CheckCDRS232C(handle)
 |
 |		int	handle;		ハンドル
 |
 |		BOOL	返値		TRUE:CD ON, FALSE:CD OFF
 |
 =======================================================================*/
BOOL	CheckCDRS232C(int handle)
{
#ifdef	OS_MSDOS
	union	REGS	reg;

	reg.h.ah = 0x06;
	int86(intTbl[handle], &reg, &reg);

	if (reg.h.ah == 0x01)
		return FALSE;

	if ((reg.h.cl & 0x20) != 0)
		return FALSE;

	return TRUE;
#endif

#ifdef	OS_WINDOWS
	if (GetCommError(handle, NULL) & CE_RLSDTO)
		return FALSE;

	return TRUE;
#endif

#ifdef	OS_OS2
	BYTE	ctrlSignals;

	DosDevIOCtl(&ctrlSignals, 0L, ASYNC_GETMODEMINPUT,
						IOCTL_ASYNC, handle);
	if (!(ctrlSignals & DCD_ON))
		return FALSE;

	return TRUE;
#endif

#ifdef	OS_WNT
	return FALSE;
#endif
}

/*=======================================================================
 |
 |		RS-232C 回線接続
 |
 |	SHORT	Telephoxxxall(handle, modemCommand)
 |
 |		int	handle;		ハンドル
 |		CHAR	*modemCommand;	モデムコマンド
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	Telephoxxxall(int handle, CHAR *modemCommand)
{
	CHAR	buf[30];
	int	i, j;
	int	len;

	if (!SendModemCommand(handle, modemCommand)) {
		if (!SendModemCommand(handle, modemCommand))
			return ERROR_CLIENT_MODEM;
	}

	for (i = 0; i < 20; i++) {
		if ((len = ReadRS232C(handle, buf, sizeof(buf) - 1)) == -1)
			return ERROR_CLIENT_MODEM;
		for (j = 0; j < len; j++) {
			if (strncmp(buf+j, "CONxxxT", 7) == 0)
				return 0;
			else if (strncmp(buf+j, "BUSY", 4) == 0)
				return ERROR_CLIENT_BUSY;
			else if (strncmp(buf+j, "NO CARRIER", 10) == 0)
				return ERROR_CLIENT_NOCARRIER;
		}
	}

	return ERROR_CLIENT_NOCARRIER;
}

/*=======================================================================
 |
 |		モデムに対するコマンド送信処理
 |
 |	BOOL	SendModemCommand(pSCB, command)
 |
 |		int	handle;
 |		CHAR	*command;	送信するコマンド
 |
 =======================================================================*/
static	BOOL	SendModemCommand(int handle, CHAR *cp)
{
	int	rc;
	CHAR	buf[128], ch, *bufp;

	for (;;) {
		for (bufp = buf; *cp != '|' && *cp != '\0'; cp++)
			*bufp++ = *cp;
		*bufp++ = 0x0d;

		if (WriteRS232C(handle, buf, bufp - buf) == -1)
			return FALSE;

		if (*cp == '\0')
			break;

		rc = 0;
		for (;;) {
			if (ReadRS232C(handle, &ch, 1) <= 0) {
				if (ReadRS232C(handle, &ch, 1) <= 0)
					return FALSE;
			}

			if (rc == 0) {
				if (ch == 'O')
					rc = 1;
			} else {
				if (ch == 'K')
					break;
				else
					rc = 0;
			}
		}

		cp++;
	}

	return TRUE;
}
