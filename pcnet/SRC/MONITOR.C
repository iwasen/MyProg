/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: ���j�^�[����				*
 *		�t�@�C����	: monitor.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>

#define	MAX_LINE	30
#define	MAX_COLUMN	80

/*--------------------------------------*/
/*		�����֐�		*/
/*--------------------------------------*/
static	VOID	MonitorWndPaint(HWND);
static	VOID	DisplayStr(HWND, MPARAM, MPARAM);

/*--------------------------------------*/
/*		�����ϐ�		*/
/*--------------------------------------*/
static	HWND	hwndMonitor[N_CHANNEL];	/* ���j�^�[�E�B���h�E�n���h�� */
static	ULONG	flStyle =		/* �t���[���E�B���h�E�X�^�C�� */
	FCF_MAXBUTTON | FCF_MENU | FCF_NOMOVEWITHOWNER | FCF_SHELLPOSITION |
	FCF_SIZEBORDER | FCF_SYSMENU | FCF_TITLEBAR;
typedef	struct	{
	USHORT	chNo;
	HPS	hps;
	SHORT	nLine;
	SHORT	topLine;
	SHORT	nColumn;
	CHAR	lineBuf[MAX_LINE][MAX_COLUMN+1];
} MONITOR;			/* ���j�^�[�Ǘ���� */
static	USHORT	monitorChNo;	/* ���j�^�[�J�n�`���l���ԍ� */

/*=======================================================================
 |
 |		���j�^�[�J�n����
 |
 |	VOID	Monitor(chNo)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
VOID	Monitor(USHORT chNo)
{
	static	CHAR	*title[N_CHANNEL] = {
		"�`���l���O���j�^�[",
		"�`���l���P���j�^�[",
		"�`���l���Q���j�^�[",
		"�`���l���R���j�^�["
	};

	if (monitorFlag[chNo]) {
		WinSendMsg(hwndMonitor[chNo], WM_CLOSE, 0L, 0L);
	} else {
		monitorChNo = chNo;
		hwndMonitor[chNo] = WinCreateStdWindow(HWND_DESKTOP,
				WS_VISIBLE, &flStyle, szMonitorClassName,
				title[chNo], 0, 0, ID_RESOURCE_MONITOR,
				&clientMonitor[chNo]);
	}
}

/*=======================================================================
 |
 |		���j�^�[�E�B���h�E�v���V�[�W��
 |
 |	MRESULT CALLBACK MonitorWindowProc(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK MonitorWindowProc(HWND hwnd, USHORT msg,
						MPARAM mp1, MPARAM mp2)
{
	MONITOR	*mp;

	switch (msg) {
	case WM_CREATE:
		WinSetOwner(WinQueryWindow(hwnd, QW_PARENT, FALSE), hwndMain);
		if ((mp = malloc(sizeof(MONITOR))) == NULL)
			return(MRFROMSHORT(TRUE));
		mp->chNo = monitorChNo;
		mp->nLine = 1;
		mp->topLine = 0;
		mp->nColumn = 0;
		mp->lineBuf[0][0] = '\0';
		monitorFlag[mp->chNo] = TRUE;
		WinSetWindowPtr(hwnd, 0, mp);
		mp->hps = WinGetPS(hwnd);
		GpiSetBackMix(mp->hps, BM_OVERPAINT);
		return(0);
	case WM_PAINT:
		MonitorWndPaint(hwnd);
		return(0);
	case WM_COMMAND:
		switch (SHORT1FROMMP(mp1)) {
		case IDM_EXIT:
			WinPostMsg(hwnd, WM_CLOSE, 0, 0);
			break;
		}
		return(0);
	case WM_CLOSE:
		mp = WinQueryWindowPtr(hwnd, 0);
		monitorFlag[mp->chNo] = FALSE;
		WinReleasePS(mp->hps);
		WinDestroyWindow(hwndMonitor[mp->chNo]);
		free(mp);
		return(0);
	case WM_USER_MONITOR:
		DisplayStr(hwnd, mp1, mp2);
		return(0);
	}
	return(WinDefWindowProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		���j�^�[�E�B���h�E�`�揈��
 |
 |	VOID	MonitorWndPaint(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	MonitorWndPaint(HWND hwnd)
{
	HPS	hps;
	RECTL	rcl;
	POINTL	pt;
	MONITOR	*mp;
	SHORT	i, lbp;

	hps = WinBeginPaint(hwnd, NULL, &rcl);
        WinFillRect(hps, &rcl, SYSCLR_WINDOW);
	mp = WinQueryWindowPtr(hwnd, 0);
	pt.x = 0;
	pt.y = 0;
	lbp = mp->topLine;
	for (i = 0; i < mp->nLine; i++) {
		if (pt.y + gcyChar > rcl.yBottom)
			GpiCharStringAt(hps, &pt,
				(LONG)strlen(mp->lineBuf[lbp]),
				mp->lineBuf[lbp]);
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
 |		�����\������
 |
 |	VOID	DisplayStr(hwnd, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
static	VOID	DisplayStr(HWND hwnd, MPARAM mp1, MPARAM mp2)
{
	RECTL	rcl;
	MONITOR	*mp;
	POINTL	pt;
	SHORT	length, copyLength, newLine, i;

	mp = WinQueryWindowPtr(hwnd, 0);

	if (mp1 != NULL) {
		pt.x = mp->nColumn * gcxAveChar;
		pt.y = 0;
		length = SHORT1FROMMP(mp2);
		GpiCharStringAt(mp->hps, &pt, (LONG)length, mp1);
		copyLength = min(length, MAX_COLUMN - mp->nColumn);
		if (copyLength > 0) {
			memcpy(&mp->lineBuf[mp->topLine][mp->nColumn],
							mp1, copyLength);
			mp->lineBuf[mp->topLine][mp->nColumn+copyLength]
				= '\0';
		}
		mp->nColumn += length;
		free(mp1);
	}

	if ((newLine = SHORT2FROMMP(mp2)) != 0) {
		WinQueryWindowRect(hwnd, &rcl);
		WinScrollWindow(hwnd, 0, gcyChar * newLine,
				 0L, &rcl, NULL, &rcl, 0);
		if (rcl.yTop > gcyChar * newLine)
			WinInvalidateRect(hwnd, &rcl, FALSE);
		else
			WinFillRect(mp->hps, &rcl, SYSCLR_WINDOW);
		mp->nColumn = 0;
		for (i = 0; i < newLine; i++) {
			if (mp->nLine < MAX_LINE)
				mp->nLine++;
			if (++mp->topLine >= MAX_LINE)
				mp->topLine = 0;
			mp->lineBuf[mp->topLine][0] = '\0';
		}
	}
}