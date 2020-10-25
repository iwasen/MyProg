/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: �^�[�~�i������			*
 *		�t�@�C����	: terminal.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#define	INCL_NLS
#include "pcnet.h"
#include <stdlib.h>
#include <string.h>
#include <jctype.h>
#include <io.h>

#define	MAX_LINE	30
#define	MAX_COLUMN	80

typedef	struct	{
	USHORT	chNo;
	HPS	hps;
	SHORT	nLine;
	SHORT	topLine;
	SHORT	nColumn;
	CHAR	lineBuf[MAX_LINE][MAX_COLUMN+1];
} TERMINAL;

/*--------------------------------------*/
/*		�����֐�		*/
/*--------------------------------------*/
static	VOID	TermWndPaint(HWND);
static	VOID	TermWndInitMenu(HWND, USHORT);
static	VOID	TermWndCommand(HWND, USHORT);
static	VOID	TermWndClose(HWND);
static	VOID	ReceiveTextFile(HWND, USHORT);
static	VOID	SendTextFile(HWND, USHORT);
static	VOID	SendTextLine(USHORT);
static	VOID	ReceiveXmodem(HWND, USHORT);
static	VOID	SendXmodem(HWND, USHORT);
static	VOID	TermWndChar(HWND, MPARAM, MPARAM);
static	VOID	TermWndRcvTerm(HWND, CHAR *, USHORT);
MRESULT CALLBACK XmodemDlg(HWND, USHORT, MPARAM, MPARAM);

/*--------------------------------------*/
/*		�����ϐ�		*/
/*--------------------------------------*/
static	HWND	hwndTerminal[N_CHANNEL]; /* �^�[�~�i���E�B���h�E�n���h�� */
static	ULONG	flStyle =		/* �t���[���E�B���h�E�X�^�C�� */
	FCF_DBE_APPSTAT | FCF_MAXBUTTON | FCF_MENU | FCF_NOMOVEWITHOWNER |
	FCF_SHELLPOSITION | FCF_SIZEBORDER | FCF_SYSMENU | FCF_TITLEBAR;
static	USHORT	termChNo;		/* �^�[�~�i�����[�h�J�n�`���l���ԍ� */
static	FILE	*rfp[N_CHANNEL];	/* ��M�t�@�C���|�C���^ */
static	FILE	*sfp[N_CHANNEL];	/* ���M�t�@�C���|�C���^ */
static	HWND	hwndXmodem[N_CHANNEL];	/* XMODEM����M�E�B���h�E�n���h�� */

/*=======================================================================
 |
 |		�^�[�~�i�����[�h�J�n����
 |
 |	VOID	Terminal(chNo)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
VOID	Terminal(USHORT chNo)
{
	static	CHAR	*title[N_CHANNEL] = {
		"���[�J���^�[�~�i��",
		"�`���l���P�^�[�~�i��",
		"�`���l���Q�^�[�~�i��",
		"�`���l���R�^�[�~�i��"
	};

	if (terminalMode[chNo] & TMODE_TERMINAL) {
		WinSendMsg(hwndTerminal[chNo], WM_CLOSE, 0L, 0L);
	} else {
		if (conxxxtFlag[chNo]) {
			if (WinMessageBox(HWND_DESKTOP, hwndMain,
				"�ڑ����܂����H",
				"���̉���͎g�p���ł��B", ID_MB,
				MB_YESNO | MB_ICONEXCLAMATION | MB_APPLMODAL
					) == MBID_NO)
				return;
		}

		termChNo = chNo;
		hwndTerminal[chNo] = WinCreateStdWindow(HWND_DESKTOP,
				WS_VISIBLE, &flStyle, szTerminalClassName,
				title[chNo], 0, 0, ID_RESOURCE_TERMINAL,
				&clientTerminal[chNo]);
	}
}

/*=======================================================================
 |
 |		�^�[�~�i���E�B���h�E�v���V�[�W��
 |
 |	MRESULT CALLBACK TermWindowProc(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK TermWindowProc(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	TERMINAL	*tp;

	switch (msg) {
	case WM_CREATE:
		WinSetOwner(WinQueryWindow(hwnd, QW_PARENT, FALSE), hwndMain);
		if ((tp = malloc(sizeof(TERMINAL))) == NULL)
			return(MRFROMSHORT(TRUE));
		tp->chNo = termChNo;
		tp->nLine = 1;
		tp->topLine = 0;
		tp->nColumn = 0;
		tp->lineBuf[0][0] = '\0';
		terminalMode[tp->chNo] |= TMODE_TERMINAL;
		WinSetWindowPtr(hwnd, 0, tp);
		tp->hps = WinGetPS(hwnd);
		GpiSetBackMix(tp->hps, BM_OVERPAINT);
		WinSendDlgItemMsg(WinQueryWindow(hwnd, QW_PARENT, FALSE),
				FID_MENU, MM_SETITEMATTR,
				MPFROM2SHORT(IDM_TERM_RESET, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				(tp->chNo == CH0 ? MIA_DISABLED : 0)));
		return(0);
	case WM_PAINT:
		TermWndPaint(hwnd);
		return(0);
	case WM_INITMENU:
		TermWndInitMenu(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_COMMAND:
		TermWndCommand(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_CLOSE:
		TermWndClose(hwnd);
		return(0);
	case WM_CHAR:
		TermWndChar(hwnd, mp1, mp2);
		return(MRFROMSHORT(TRUE));
	case WM_SETFOCUS:
		if (SHORT1FROMMP(mp2)) {
			tp = WinQueryWindowPtr(hwnd, 0);
			WinCreateCursor(hwnd,
				tp->nColumn * gcxAveChar, 0,
				gcxAveChar, gcyChar, CURSOR_FLASH, NULL);
			WinShowCursor(hwnd, TRUE);
		} else {
			WinDestroyCursor(hwnd);
		}
		return(0);
	case WM_USER_RCVTERM1:
		TermWndRcvTerm(hwnd, (CHAR *)&SHORT1FROMMP(mp1), 1);
		return(0);
	case WM_USER_RCVTERM2:
		TermWndRcvTerm(hwnd, (CHAR *)mp1, SHORT1FROMMP(mp2));
		free(mp1);
		return(0);
	}
	return(WinDefWindowProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		�^�[�~�i���E�B���h�E�`�揈��
 |
 |	VOID	TerminalWndPaint(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	TermWndPaint(HWND hwnd)
{
	HPS	hps;
	RECTL	rcl;
	POINTL	pt;
	SHORT	i, lbp;
	TERMINAL	*tp;

	hps = WinBeginPaint(hwnd, NULL, &rcl);
        WinFillRect(hps, &rcl, SYSCLR_WINDOW);
	tp = WinQueryWindowPtr(hwnd, 0);
	pt.x = 0;
	pt.y = 0;
	lbp = tp->topLine;
	for (i = 0; i < tp->nLine; i++) {
		if (pt.y + gcyChar > rcl.yBottom)
			GpiCharStringAt(hps, &pt,
				(LONG)strlen(tp->lineBuf[lbp]),
				tp->lineBuf[lbp]);
		if (--lbp < 0)
			lbp += MAX_LINE;
		pt.y += gcyChar;
		if (pt.y > rcl.yTop)
			break;
	}
	WinEndPaint(hps);
}

/*=======================================================================
 |
 |		�^�[�~�i���E�B���h�E�@���j���[����������
 |
 |	VOID	TermWndInitMenu(hwnd, menuID)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	menuID;		���j���[�h�c
 |
 =======================================================================*/
static	VOID	TermWndInitMenu(HWND hwnd, USHORT menuID)
{
	TERMINAL	*tp;

	switch (menuID) {
	case IDM_TERM_FILE:
		tp = WinQueryWindowPtr(hwnd, 0);
		WinSendDlgItemMsg(hwndTerminal[tp->chNo], FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_TERM_FILE_TRCV, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				terminalMode[tp->chNo] & TMODE_TRCV ?
				MIA_CHECKED : 0));
		WinSendDlgItemMsg(hwndTerminal[tp->chNo], FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_TERM_FILE_TSND, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				terminalMode[tp->chNo] & TMODE_TSND ?
				MIA_CHECKED : 0));
		WinSendDlgItemMsg(hwndTerminal[tp->chNo], FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_TERM_FILE_XRCV, TRUE),
				MPFROM2SHORT(MIA_DISABLED|MIA_CHECKED,
				(tp->chNo == CH0 ? MIA_DISABLED : 0) |
				(terminalMode[tp->chNo] & TMODE_XRCV ?
				MIA_CHECKED : 0)));
		WinSendDlgItemMsg(hwndTerminal[tp->chNo], FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_TERM_FILE_XSND, TRUE),
				MPFROM2SHORT(MIA_DISABLED|MIA_CHECKED,
				(tp->chNo == CH0 ? MIA_DISABLED : 0) |
				(terminalMode[tp->chNo] & TMODE_XSND ?
				MIA_CHECKED : 0)));
		break;
	}
}

/*=======================================================================
 |
 |		�^�[�~�i���E�B���h�E�@�R�}���h����
 |
 |	VOID	TermWndCommand(hwnd, command)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	command;	�R�}���h�h�c
 |
 =======================================================================*/
static	VOID	TermWndCommand(HWND hwnd, USHORT command)
{
	TERMINAL	*tp;

	switch (command) {
	case IDM_TERM_FILE_TRCV:
		tp = WinQueryWindowPtr(hwnd, 0);
		ReceiveTextFile(hwnd, tp->chNo);
		break;
	case IDM_TERM_FILE_TSND:
		tp = WinQueryWindowPtr(hwnd, 0);
		SendTextFile(hwnd, tp->chNo);
		break;
	case IDM_TERM_FILE_XRCV:
		tp = WinQueryWindowPtr(hwnd, 0);
		ReceiveXmodem(hwnd, tp->chNo);
		break;
	case IDM_TERM_FILE_XSND:
		tp = WinQueryWindowPtr(hwnd, 0);
		SendXmodem(hwnd, tp->chNo);
		break;
	case IDM_TERM_RESET:
		tp = WinQueryWindowPtr(hwnd, 0);
		DosDevIOCtl(0L, 0L, ASYNC_STARTTRANSMIT,
					IOCTL_ASYNC, portHndl[tp->chNo]);
		break;
	case IDM_EXIT:
		WinPostMsg(hwnd, WM_CLOSE, 0, 0);
		break;
	}
}

/*=======================================================================
 |
 |		�e�L�X�g�t�@�C����M����
 |
 |	VOID	ReceiveTextFile(hwnd, chNo)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	chNo;		�`���l���ԍ�
 |
 =======================================================================*/
static	VOID	ReceiveTextFile(HWND hwnd, USHORT chNo)
{
	static	CHAR	title[] = "�e�L�X�g�t�@�C����M";
	INPUTFN	inputfn;
	CHAR	fileName[65];
	CHAR	*openMode;

	if (terminalMode[chNo] & TMODE_TRCV) {
		if (WinMessageBox(HWND_DESKTOP, hwnd,
				"�t�@�C����M���I�����܂����H",
				title, ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL)
				== MBID_YES) {
			terminalMode[chNo] &= ~TMODE_TRCV;
			fclose(rfp[chNo]);
		}
	} else {
retry:
		inputfn.title = title;
		inputfn.bufp = fileName;
		inputfn.bufSize = sizeof(fileName);
		if (SHORT1FROMMR(WinDlgBox(HWND_DESKTOP, hwnd,
				InputFileNameDlg, 0,
				IDD_FILENAME, (PVOID)&inputfn)) != DID_OK)
			return;

		if (access(fileName, 0) == 0) {
			switch (WinMessageBox(HWND_DESKTOP, hwnd,
				"�㏑�����܂����H �i�������͒ǉ��j",
				"�t�@�C���͊��ɑ��݂��܂��B",
				ID_MB, MB_YESNOCANCEL | MB_ICONQUESTION |
				MB_APPLMODAL)) {
			case MBID_YES:
				openMode = "wb";
				break;
			case MBID_NO:
				openMode = "ab";
				break;
			default:
				goto retry;
			}
		} else
			openMode = "wb";

		if ((rfp[chNo] = fopen(fileName, openMode)) == NULL) {
			WinMessageBox(HWND_DESKTOP, hwnd,
				"�t�@�C�����I�[�v���ł��܂���B",
				title, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_FILE_NAME));
			goto retry;
		}

		terminalMode[chNo] |= TMODE_TRCV;
	}
}

/*=======================================================================
 |
 |		�e�L�X�g�t�@�C�����M����
 |
 |	VOID	SendTextFile(hwnd, chNo)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	chNo;		�`���l���ԍ�
 |
 =======================================================================*/
static	VOID	SendTextFile(HWND hwnd, USHORT chNo)
{
	static	CHAR	title[] = "�e�L�X�g�t�@�C�����M";
	INPUTFN	inputfn;
	CHAR	fileName[65];

	if (terminalMode[chNo] & TMODE_TSND) {
		if (WinMessageBox(HWND_DESKTOP, hwnd,
				"�t�@�C�����M���I�����܂����H",
				title, ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL)
				== MBID_YES) {
			terminalMode[chNo] &= ~TMODE_TSND;
			fclose(sfp[chNo]);
		}
	} else {
retry:
		inputfn.title = title;
		inputfn.bufp = fileName;
		inputfn.bufSize = sizeof(fileName);
		if (SHORT1FROMMR(WinDlgBox(HWND_DESKTOP, hwnd,
				InputFileNameDlg, 0,
				IDD_FILENAME, (PVOID)&inputfn)) != DID_OK)
			return;

		if ((sfp[chNo] = fopen(fileName, "r")) == NULL) {
			WinMessageBox(HWND_DESKTOP, hwnd,
				"�t�@�C�����I�[�v���ł��܂���B",
				title, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_FILE_NAME));
			goto retry;
		}

		terminalMode[chNo] |= TMODE_TSND;

		SendTextLine(chNo);
	}
}

/*=======================================================================
 |
 |		�e�L�X�g�t�@�C�����P�s���M����
 |
 |	VOID	SendTextLine(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |
 =======================================================================*/
static	VOID	SendTextLine(USHORT chNo)
{
	CHAR	buf[1024];
	SHORT	len;
	USHORT	numBytes;

	if (!(terminalMode[chNo] & TMODE_TSND))
		return;

	if (fgets(buf, sizeof(buf), sfp[chNo]) != NULL) {
		len = strlen(buf);
		if (buf[len - 1] == '\n')
			buf[len - 1] = 0x0d;
		DosWrite(chNo == CH0 ? ch0Hndl : portHndl[chNo],
						buf, len, &numBytes);
	} else {
		terminalMode[chNo] &= ~TMODE_TSND;
		fclose(sfp[chNo]);
	}
}

/*=======================================================================
 |
 |		�w�l�n�c�d�l��M����
 |
 |	VOID	ReceiveXmodem(hwnd, chNo)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	chNo;		�`���l���ԍ�
 |
 =======================================================================*/
static	VOID	ReceiveXmodem(HWND hwnd, USHORT chNo)
{
	static	CHAR	title[] = "�w�l�n�c�d�l��M";
	INPUTFN	inputfn;
	CHAR	fileName[65];
	FILE	*fp;

	if (!(terminalMode[chNo] & TMODE_XRCV)) {
retry:
		inputfn.title = title;
		inputfn.bufp = fileName;
		inputfn.bufSize = sizeof(fileName);
		if (SHORT1FROMMR(WinDlgBox(HWND_DESKTOP, hwnd,
				InputFileNameDlg, 0,
				IDD_FILENAME, (PVOID)&inputfn)) != DID_OK)
			return;

		if (access(fileName, 0) == 0) {
			if (WinMessageBox(HWND_DESKTOP, hwnd,
					"�㏑�����܂����H",
					"�t�@�C���͊��ɑ��݂��܂��B",
					ID_MB, MB_OKCANCEL | MB_ICONQUESTION |
					MB_APPLMODAL) != MBID_OK)
				goto retry;
		}

		if ((fp = fopen(fileName, "wb")) == NULL) {
			WinMessageBox(HWND_DESKTOP, hwnd,
				"�t�@�C�����I�[�v���ł��܂���B",
				title, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_FILE_NAME));
			goto retry;
		}
		termXmodemFP[chNo] = fp;
		terminalMode[chNo] |= TMODE_XRCV;
		WinDlgBox(HWND_DESKTOP, hwnd, XmodemDlg, 0,
					IDD_XMODEM, MPFROM2SHORT(chNo, 0));
		terminalMode[chNo] &= ~TMODE_XRCV;
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		�w�l�n�c�d�l���M����
 |
 |	VOID	SendXmodem(hwnd, chNo)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	chNo;		�`���l���ԍ�
 |
 =======================================================================*/
static	VOID	SendXmodem(HWND hwnd, USHORT chNo)
{
	static	CHAR	title[] = "�w�l�n�c�d�l���M";
	INPUTFN	inputfn;
	CHAR	fileName[65];
	FILE	*fp;

	if (!(terminalMode[chNo] & TMODE_XSND)) {
retry:
		inputfn.title = title;
		inputfn.bufp = fileName;
		inputfn.bufSize = sizeof(fileName);
		if (SHORT1FROMMR(WinDlgBox(HWND_DESKTOP, hwnd,
				InputFileNameDlg, 0,
				IDD_FILENAME, (PVOID)&inputfn)) != DID_OK)
			return;

		if ((fp = fopen(fileName, "rb")) == NULL) {
			WinMessageBox(HWND_DESKTOP, hwnd,
				"�t�@�C�����I�[�v���ł��܂���B",
				title, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_FILE_NAME));
			goto retry;
		}
		termXmodemFP[chNo] = fp;
		terminalMode[chNo] |= TMODE_XSND;
		WinDlgBox(HWND_DESKTOP, hwnd, XmodemDlg, 0,
					IDD_XMODEM, MPFROM2SHORT(chNo, 1));
		terminalMode[chNo] &= ~TMODE_XSND;
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		�w�l�n�c�d�l����M���E�B���h�E�v���V�[�W��
 |
 |	MRESULT CALLBACK XmodemDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK XmodemDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	static	CHAR	*title[2] = {
		"�w�l�n�c�d�l��M��",
		"�w�l�n�c�d�l���M��"
	};
	USHORT	chNo;
	SHORT	blkNo;

	switch (msg) {
	case WM_INITDLG:
		chNo = SHORT1FROMMP(mp2);
		WinSetWindowUShort(hwnd, 0, chNo);
		hwndXmodem[chNo] = hwnd;
		WinSetWindowText(hwnd, title[SHORT2FROMMP(mp2)]);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_CANCEL:
			chNo = WinQueryWindowUShort(hwnd, 0);
			hwndXmodem[chNo] = 0;
			AbortXmodem(chNo);
			break;
		}
		break;
	case WM_USER_XMODEM:
		blkNo = SHORT1FROMMP(mp1);
		if (blkNo != -1)
			WinSetDlgItemShort(hwnd, IDD_XMODEM_COUNT,
							blkNo, FALSE);
		else {
			chNo = WinQueryWindowUShort(hwnd, 0);
			hwndXmodem[chNo] = 0;
			WinDismissDlg(hwnd, DID_OK);
		}
		return(0);
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		�w�l�n�c�d�l�u���b�N�ԍ��\������
 |
 |	VOID	TermBlockCount(chNo, blkNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		USHORT	blkNo;		�u���b�N�ԍ�
 |
 =======================================================================*/
VOID	TermBlockCount(USHORT chNo, USHORT blkNo)
{
	if (hwndXmodem[chNo] != 0)
		WinPostMsg(hwndXmodem[chNo], WM_USER_XMODEM,
						MPFROMSHORT(blkNo), 0);
}

/*=======================================================================
 |
 |		�^�[�~�i���E�B���h�E�@�N���[�Y����
 |
 |	VOID	TermWndClose(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	TermWndClose(HWND hwnd)
{
	TERMINAL	*tp;
	USHORT	numBytes;

	tp = WinQueryWindowPtr(hwnd, 0);
	terminalMode[tp->chNo] &= ~TMODE_TERMINAL;

	if (tp->chNo == CH0)
		DosWrite(ch0Hndl, "\xff", 1, &numBytes);

	if (terminalMode[tp->chNo] & TMODE_TRCV) {
		terminalMode[tp->chNo] &= ~TMODE_TRCV;
		fclose(rfp[tp->chNo]);
	}

	if (terminalMode[tp->chNo] & TMODE_TSND) {
		terminalMode[tp->chNo] &= ~TMODE_TSND;
		fclose(sfp[tp->chNo]);
	}

	if (terminalMode[tp->chNo] & TMODE_XRCV) {
		terminalMode[tp->chNo] &= ~TMODE_XRCV;
		AbortXmodem(tp->chNo);
	}

	if (terminalMode[tp->chNo] & TMODE_XSND) {
		terminalMode[tp->chNo] &= ~TMODE_XSND;
		AbortXmodem(tp->chNo);
	}

	WinReleasePS(tp->hps);
	WinDestroyWindow(hwndTerminal[tp->chNo]);
	free(tp);
}

/*=======================================================================
 |
 |		���͕������M����
 |
 |	VOID	TermWndChar(hwnd, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
static	VOID	TermWndChar(HWND hwnd, MPARAM mp1, MPARAM mp2)
{
	USHORT	kf, ch;
	TERMINAL	*tp;
	USHORT	numBytes;

	kf = SHORT1FROMMP(mp1);

	ch = 0;
	if (!(kf & KC_KEYUP)) {
		if (kf & KC_CHAR)
			ch = SHORT1FROMMP(mp2);
		else if (kf & KC_VIRTUALKEY) {
			switch (SHORT2FROMMP(mp2)) {
			case VK_ESC:
				ch = 0x1b;
				break;
			}
		} else if (kf & KC_CTRL) {
			if (!(kf & KC_VIRTUALKEY))
				ch = SHORT1FROMMP(mp2) & 0x1f;
		}
	}

	if (ch != 0) {
		tp = WinQueryWindowPtr(hwnd, 0);
		DosWrite(tp->chNo != CH0 ? portHndl[tp->chNo] : ch0Hndl, &ch,
				(ch & 0xff00) ? 2 : 1, &numBytes);
	}
}

/*=======================================================================
 |
 |		��M�����\������
 |
 |	VOID	TermWndRcvTerm(hwnd, datap, dataLength)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		CHAR	*datap;		��M�f�[�^
 |		USHORT	dataLength;	��M�f�[�^��
 |
 =======================================================================*/
static	VOID	TermWndRcvTerm(HWND hwnd, CHAR *datap, USHORT dataLength)
{
	RECTL	rcl;
	POINTL	pt;
	TERMINAL	*tp;
	USHORT	i;
	SHORT	length;
	CHAR	rcvChar;
	CHAR	cBuf[2];
	static	CHAR	sc[N_CHANNEL];

	WinShowCursor(hwnd, FALSE);

	tp = WinQueryWindowPtr(hwnd, 0);
	for (i = 0; i < dataLength; i++) {
		rcvChar = datap[i];
		switch (rcvChar) {
		case 0x08:
			if (tp->nColumn != 0) {
				pt.x = --tp->nColumn * gcxAveChar;
				pt.y = 0;
				cBuf[0] = ' ';
				GpiCharStringAt(tp->hps, &pt, (LONG)1, cBuf);
			}
			break;
		case 0x0a:
			WinQueryWindowRect(hwnd, &rcl);
			WinScrollWindow(hwnd, 0, gcyChar, 0L, &rcl, NULL,
								&rcl, 0);
			if (rcl.yTop > gcyChar)
				WinInvalidateRect(hwnd, &rcl, FALSE);
			else
				WinFillRect(tp->hps, &rcl, SYSCLR_WINDOW);
			if (tp->nLine < MAX_LINE)
				tp->nLine++;
			if (++tp->topLine >= MAX_LINE)
				tp->topLine = 0;
			tp->nColumn = 0;
			tp->lineBuf[tp->topLine][0] = '\0';
			SendTextLine(tp->chNo);
			break;
		case 0x0d:
			tp->nColumn = 0;
			break;
		default:
			if (rcvChar < 0x20)
				break;
			if (sc[tp->chNo]) {
				cBuf[0] = sc[tp->chNo];
				cBuf[1] = (CHAR)rcvChar;
				length = 2;
				sc[tp->chNo] = 0;
			} else {
				if (iskanji(rcvChar)) {
					sc[tp->chNo] = (CHAR)rcvChar;
					length = 0;
				} else {
					cBuf[0] = (CHAR)rcvChar;
					length = 1;
				}
			}
			if (length != 0) {
				pt.x = tp->nColumn * gcxAveChar;
				pt.y = 0;
				GpiCharStringAt(tp->hps, &pt,
						(LONG)length, cBuf);
				if (tp->nColumn + length < MAX_COLUMN) {
					memcpy(&tp->lineBuf[tp->topLine]
						[tp->nColumn], cBuf, length);
					tp->lineBuf[tp->topLine]
						[tp->nColumn+length] = '\0';
					tp->nColumn += length;
				}
			}
			break;
		}
	}

	WinCreateCursor(hwnd, tp->nColumn * gcxAveChar, 0, 0, 0,
						CURSOR_SETPOS, NULL);
	WinShowCursor(hwnd, TRUE);

	if (terminalMode[tp->chNo] & TMODE_TRCV)
		fwrite(datap, dataLength, 1, rfp[tp->chNo]);
}