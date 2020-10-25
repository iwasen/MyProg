/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: ���[�U�Ǘ�����			*
 *		�t�@�C����	: user.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>

#define	MAXFIELDL	41	/* �t�B�[���h�ő咷 */

/*--------------------------------------*/
/*		�����֐�		*/
/*--------------------------------------*/
static	VOID	SetTitle(HWND);
static	VOID	UserWndPaint(HWND);
static	VOID	UserWndInitMenu(HWND, USHORT);
static	VOID	UserWndCommand(HWND, USHORT);
static	VOID	UserWndVScroll(HWND, MPARAM, MPARAM);
static	VOID	Scroll(HWND, SHORT);
static	VOID	Mouse1Down(HWND, MPARAM);
static	VOID	Mouse1Up(VOID);
static	VOID	Mouse2Down(HWND);
static	VOID	MouseDragCheck(HWND);
static	VOID	SelectLines(HWND, SHORT, BOOL);
static	VOID	EnableScrollBar(HWND);
static	BOOL	WriteUserFile(HWND, CHAR *);
static	BOOL	CreateUserDBF(VOID);
static	BOOL	UseUserIndex(BOOL);
static	VOID	DeleteUser(HWND);
static	VOID	DeleteSales(VOID);
MRESULT CALLBACK UserSearchDlg(HWND, USHORT, MPARAM, MPARAM);
MRESULT CALLBACK ComInfDlg(HWND, USHORT, MPARAM, MPARAM);

/*--------------------------------------*/
/*		�����ϐ�		*/
/*--------------------------------------*/
static	HWND	hwndClient;	/* �N���C�A���g�E�B���h�E�n���h�� */
static	HWND	hwndSB;		/* �X�N���[���o�[�E�B���h�E�n���h�� */
static	ULONG	flStyle =	/* �t���[���E�B���h�E�X�^�C�� */
	FCF_ACCELTABLE | FCF_MAXBUTTON | FCF_MENU | FCF_NOMOVEWITHOWNER |
	FCF_SIZEBORDER | FCF_SYSMENU | FCF_TITLEBAR | FCF_VERTSCROLL;
static	USERDBF	dspBuf;		/* �\���p���[�U�Ǘ��t�@�C���o�b�t�@ */
static	DISP_TBL	dispTbl[] = {	/* �\�����e�[�u�� */
	{IDM_USER_DSP_USERID,   TRUE,  "  ���[�U�h�c",
					10, dspBuf.userID, 8},
	{IDM_USER_DSP_PASSWORD, FALSE, "  �p�X���[�h",
					10, dspBuf.password, 8},
	{IDM_USER_DSP_GROUP,    FALSE, "  �O���[�v",
					8, dspBuf.group, 8},
	{IDM_USER_DSP_LEVEL,    FALSE, "  ���x��",
					 6, dspBuf.userLevel, 1},
	{IDM_USER_DSP_NAME,     TRUE,  "  ���O            ",
					16, dspBuf.name, 16},
	{IDM_USER_DSP_FURIGANA, FALSE, "  �t���K�i        ",
					16, dspBuf.furikana, 16},
	{IDM_USER_DSP_COMPANY,  FALSE, "  ��Ж�                                  ",
					40, dspBuf.company, 40},
	{IDM_USER_DSP_SDATE,    FALSE, "  �ڑ��J�n��",
					10, dspBuf.sDate, 8},
	{IDM_USER_DSP_EDATE,    TRUE,  "  �ŏI�ڑ���",
					10, dspBuf.eDate, 8},
	{IDM_USER_DSP_CCOUNT,   TRUE,  "  �ڑ���",
					 8, dspBuf.cCount, 6},
	{IDM_USER_DSP_CTIME,    TRUE,  "    �ʐM����",
					10, dspBuf.cTime, 10}
};
static	USERDBF	entryBuf;	/* ���͗p���[�U�Ǘ��t�@�C���o�b�t�@ */
static	ENTRY_TBL	entryTbl[] = {	/* ���͏��e�[�u�� */
	{ET_STRINGFIELD, IDD_USER_USERID,   8, entryBuf.userID},
	{ET_STRINGFIELD, IDD_USER_NAME,    16, entryBuf.name},
	{ET_STRINGFIELD, IDD_USER_FURIGANA,16, entryBuf.furikana},
	{ET_STRINGFIELD, IDD_USER_PASSWORD, 8, entryBuf.password},
	{ET_STRINGFIELD, IDD_USER_GROUP,    8, entryBuf.group},
	{ET_STRINGFIELD, IDD_USER_LEVEL,    1, entryBuf.userLevel},
	{ET_STRINGFIELD, IDD_USER_ZIPNO,    6, entryBuf.zipNo},
	{ET_STRINGFIELD, IDD_USER_TEL,     15, entryBuf.tel},
	{ET_STRINGFIELD, IDD_USER_ADRS1,   40, entryBuf.address1},
	{ET_STRINGFIELD, IDD_USER_ADRS2,   40, entryBuf.address2},
	{ET_STRINGFIELD, IDD_USER_COMPANY, 40, entryBuf.company},
	{ET_STRINGFIELD, IDD_USER_SECTION, 40, entryBuf.section},
	{ET_STRINGFIELD, IDD_USER_BIKOU,   40, entryBuf.bikou}
};
static	SHORT	pTop;		/* �\���擪���R�[�h�|�C���^ */
static	SHORT	pTopSv;		/* �O�\���擪���R�[�h�|�C���^ */
static	LONG	topRecNo;	/* �\���擪�������R�[�h�ԍ� */
static	SHORT	pSelect;	/* �I�����R�[�h�|�C���^ */
static	SHORT	pSelect1;	/* �I���J�n���R�[�h�|�C���^ */
static	SHORT	pSelect2;	/* �I���I�����R�[�h�|�C���^ */
static	BOOL	selectFlag;	/* �I�𒆃t���O */
static	LONG	selectRecNo;	/* �I�𕨗����R�[�h�ԍ� */
static	SHORT	nSelect;	/* �I�����R�[�h�� */
static	SHORT	index;		/* �g�p���C���f�b�N�X */
static	BOOL	windowOpenFlag;	/* ���[�U�Ǘ��E�B���h�E�I�[�v�����t���O */
static	SHORT	yMousePos;	/* �}�E�X�x���W */

/*=======================================================================
 |
 |		���[�U�Ǘ��E�B���h�E�I�[�v������
 |
 |	VOID	UserManagement()
 |
 =======================================================================*/
VOID	UserManagement(VOID)
{
	static	CHAR	title[] = "���[�U�Ǘ�";

	if (windowOpenFlag)
		return;

	hwndUser = WinCreateStdWindow(HWND_DESKTOP,
			WS_VISIBLE, &flStyle, szUserClassName,
			title, 0, 0, ID_RESOURCE_USER, &hwndClient);
}

/*=======================================================================
 |
 |		���[�U�Ǘ��E�B���h�E�v���V�[�W��
 |
 |	MRESULT CALLBACK UserWindowProc(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK UserWindowProc(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	HWND	hwndFrame;

	switch (msg) {
	case WM_CREATE:
		hwndFrame = WinQueryWindow(hwnd, QW_PARENT, FALSE);
		WinSetOwner(hwndFrame, hwndMain);
		hwndSB = WinWindowFromID(hwndFrame, FID_VERTSCROLL);
		SetTitle(hwndFrame);
		pTop = 0;
		nSelect = 0;
		windowOpenFlag = TRUE;
		SetWindowPos(hwndFrame, 70, 17);
		return(0);
	case WM_PAINT:
		UserWndPaint(hwnd);
		return(0);
	case WM_SIZE:
		EnableScrollBar(hwnd);
		break;
	case WM_INITMENU:
		UserWndInitMenu(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_COMMAND:
		UserWndCommand(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_VSCROLL:
		UserWndVScroll(hwnd, mp1, mp2);
		return(0);
	case WM_BUTTON1DOWN:
		Mouse1Down(hwnd, mp1);
		break;
	case WM_BUTTON1UP:
		Mouse1Up();
		break;
	case WM_BUTTON2DOWN:
		Mouse2Down(hwnd);
		break;
	case WM_MOUSEMOVE:
		yMousePos = SHORT2FROMMP(mp1);
		MouseDragCheck(hwnd);
		break;
	case WM_BUTTON1DBLCLK:
		if (nSelect == 1)
			WinPostMsg(hwnd, WM_COMMAND,
					MPFROMSHORT(IDM_USER_FILE_UPDATE), 0);
		break;
	case WM_CLOSE:
		windowOpenFlag = FALSE;
		WinDestroyWindow(hwndUser);
		WinSetActiveWindow(HWND_DESKTOP, hwndMain);
		DBFlush(userDBp);
		DBFlush(salesDBp);
		return(0);
	}
	return(WinDefWindowProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		�^�C�g���i�o�^���j�\������
 |
 |	VOID	SetTitle(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	SetTitle(HWND hwnd)
{
	CHAR	title[80];

	sprintf(title, "���[�U�Ǘ��@(�o�^��=%ld)", DBRecCount(userDBp));
	WinSetWindowText(hwnd, title);
}

/*=======================================================================
 |
 |		���[�U�Ǘ��E�B���h�E�`�揈��
 |
 |	VOID	UserWndPaint(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	UserWndPaint(HWND hwnd)
{
	HPS	hps;
	RECTL	rcl;
	POINTL	pt;
	USHORT	i, j;
	SHORT	pCurrent;
	SHORT	top, bottom;
	CHAR	strBuf[166], *strBufp;

	EnableScrollBar(hwnd);

	hps = WinBeginPaint(hwnd, NULL, &rcl);
	WinFillRect(hps, &rcl, CLR_WHITE);
	top = (SHORT)rcl.yTop;
	bottom = (SHORT)rcl.yBottom;

	WinQueryWindowRect(hwnd, &rcl);
	pt.x = 0;
	pt.y = rcl.yTop - gcyChar - gcyChar / 2;
	if (pt.y < top) {
		strBuf[0] = '\0';
		for (i = 0; i < sizeof(dispTbl)/sizeof(DISP_TBL); i++) {
			if (dispTbl[i].flag)
				strcat(strBuf, dispTbl[i].msg);
		}
		GpiSetColor(hps, SYSCLR_WINDOWSTATICTEXT);
		GpiCharStringAt(hps, &pt, (LONG)strlen(strBuf), strBuf);
		GpiSetColor(hps, SYSCLR_WINDOWTEXT);
	}
	pt.y = rcl.yTop - gcyChar * 3;

	DBLock(userDBp);
	DBChgIdx(userDBp, index);
	if (pTop == 0 || topRecNo == 0 || DBCheckUpdate(userDBp)) {
		DBSkip(userDBp, pTop);
		topRecNo = DBRecNo(userDBp);
		DBResetUpdate(userDBp);
	} else {
		DBSet(userDBp, topRecNo);
		if (pTop != pTopSv) {
			DBSkip(userDBp, pTop - pTopSv);
			topRecNo = DBRecNo(userDBp);
		}
	}
	pTopSv = pTop;

	memset(strBuf, ' ' , sizeof(strBuf));
	for (pCurrent = pTop; ; pCurrent++) {
		if (DBEof(userDBp))
			break;

		/* �I���s�̃��R�[�h�ԍ��擾 */
		if (nSelect != 0 && pCurrent == pSelect)
			selectRecNo = DBRecNo(userDBp);

		/* �ŉ��[�܂ŕ\��������I�� */
		if (pt.y + gcyChar <= bottom || pt.y + gcyChar < 0)
			break;

		if (pt.y < top) {
			DBRead(userDBp, &dspBuf);
			strBufp = strBuf;
			for (j = 0; j < sizeof(dispTbl)/sizeof(DISP_TBL); j++){
				if (dispTbl[j].flag) {
					strBufp += 2;
					memcpy(strBufp, dispTbl[j].ptr,
							dispTbl[j].fieldLeng);
					strBufp += dispTbl[j].length;
				}
			}
			GpiCharStringAt(hps, &pt, (LONG)(strBufp - strBuf),
								strBuf);
			if (pCurrent >= pSelect &&
					pCurrent < pSelect + nSelect) {
				rcl.yTop = pt.y + gcyChar;
				rcl.yBottom = pt.y;
				WinInvertRect(hps, &rcl);
			}
		}
		pt.y -= gcyChar;
		DBSkip(userDBp, 1L);
	}
	DBRelease(userDBp);
	WinEndPaint(hps);

	MouseDragCheck(hwnd);
}

/*=======================================================================
 |
 |		���[�U�Ǘ��E�B���h�E�@���j���[����������
 |
 |	VOID	UserWndInitMenu(hwnd, menuID)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	menuID;		���j���[�h�c
 |
 =======================================================================*/
static	VOID	UserWndInitMenu(HWND hwnd, USHORT menuID)
{
	USHORT	i;

	switch (menuID) {
	case IDM_USER_FILE:
		WinSendDlgItemMsg(hwndUser, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_USER_FILE_UPDATE, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect == 1 ? 0 : MIA_DISABLED));
		WinSendDlgItemMsg(hwndUser, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_USER_FILE_DELETE, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect != 0 ? 0 : MIA_DISABLED));
		break;
	case IDM_USER_DISPLAY:
		for (i = 0; i < sizeof(dispTbl)/sizeof(DISP_TBL); i++) {
			WinSendDlgItemMsg(hwndUser, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(dispTbl[i].menuID, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				dispTbl[i].flag ? MIA_CHECKED : 0));
		}
		WinSendDlgItemMsg(hwndUser, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_USER_SEQ_USERID, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				index == indexUserID ? MIA_CHECKED : 0));
		WinSendDlgItemMsg(hwndUser, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_USER_SEQ_FURIGANA, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				index == indexFurigana ? MIA_CHECKED : 0));
		break;
	}
}

/*=======================================================================
 |
 |		���[�U�Ǘ��E�B���h�E�@�R�}���h����
 |
 |	VOID	UserWndCommand(hwnd, command)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	command;	�R�}���h�h�c
 |
 =======================================================================*/
static	VOID	UserWndCommand(HWND hwnd, USHORT command)
{
	USHORT	i;
	USERDBF	recBuf;

	switch (command) {
	case IDM_USER_FILE_NEW:
		if (WinDlgBox(HWND_DESKTOP, hwnd, UserDlg, 0, IDD_USER,
						NULL) == DID_OK) {
			SetTitle(hwndUser);
			nSelect = 0;
			WinInvalidateRect(hwnd, NULL, FALSE);
		}
		break;
	case IDM_USER_FILE_UPDATE:
		DBLock(userDBp);
		DBSet(userDBp, selectRecNo);
		DBRead(userDBp, &recBuf);
		DBRelease(userDBp);
		if (WinDlgBox(HWND_DESKTOP, hwnd, UserDlg, 0, IDD_USER,
					(PVOID)recBuf.userID) == DID_OK) {
			nSelect = 0;
			WinInvalidateRect(hwnd, NULL, FALSE);
		}
		break;
	case IDM_USER_FILE_DELETE:
		DeleteUser(hwnd);
		SetTitle(hwndUser);
		break;
	case IDM_USER_DSP_USERID:
	case IDM_USER_DSP_PASSWORD:
	case IDM_USER_DSP_GROUP:
	case IDM_USER_DSP_LEVEL:
	case IDM_USER_DSP_NAME:
	case IDM_USER_DSP_FURIGANA:
	case IDM_USER_DSP_COMPANY:
	case IDM_USER_DSP_SDATE:
	case IDM_USER_DSP_EDATE:
	case IDM_USER_DSP_CCOUNT:
	case IDM_USER_DSP_CTIME:
		for (i = 0; i < sizeof(dispTbl)/sizeof(DISP_TBL); i++) {
			if (command == dispTbl[i].menuID) {
				dispTbl[i].flag = !dispTbl[i].flag;
				break;
			}
		}
		WinInvalidateRect(hwnd, NULL, FALSE);
		break;
	case IDM_USER_SEQ_USERID:
		if (index != indexUserID) {
			index = indexUserID;
			pTop = 0;
			nSelect = 0;
			WinInvalidateRect(hwnd, NULL, FALSE);
		}
		break;
	case IDM_USER_SEQ_FURIGANA:
		if (index != indexFurigana) {
			index = indexFurigana;
			pTop = 0;
			nSelect = 0;
			WinInvalidateRect(hwnd, NULL, FALSE);
		}
		break;
	case IDM_USER_SEARCH:
		WinDlgBox(HWND_DESKTOP, hwndUser, UserSearchDlg, 0,
			index == indexUserID ? IDD_USER_SRID : IDD_USER_SRFURI,
			NULL);
		break;
	case IDM_EXIT:
		WinPostMsg(hwnd, WM_CLOSE, 0, 0);
		break;
	case IDK_LINEUP:
		Scroll(hwnd, -1);
		break;
	case IDK_LINEDOWN:
		Scroll(hwnd, 1);
		break;
	case IDK_PAGEUP:
		Scroll(hwnd, PAGE_UP);
		break;
	case IDK_PAGEDOWN:
		Scroll(hwnd, PAGE_DOWN);
		break;
	case IDK_UP:
		SelectLines(hwnd, -1, FALSE);
		break;
	case IDK_DOWN:
		SelectLines(hwnd, 1, FALSE);
		break;
	case IDK_DRAG_UP:
		SelectLines(hwnd, -1, TRUE);
		break;
	case IDK_DRAG_DOWN:
		SelectLines(hwnd, 1, TRUE);
		break;
	case IDK_CANCEL:
		SelectLines(hwnd, 0, FALSE);
		break;
	}
}

/*=======================================================================
 |
 |		���[�U�Ǘ��E�B���h�E�@�X�N���[���R�}���h����
 |
 |	VOID	UserWndVScroll(hwnd, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
static	VOID	UserWndVScroll(HWND hwnd, MPARAM mp1, MPARAM mp2)
{
	switch (SHORT2FROMMP(mp2)) {
	case SB_LINEUP:
		Scroll(hwnd, -1);
		break;
	case SB_LINEDOWN:
		Scroll(hwnd, 1);
		break;
	case SB_PAGEUP:
		Scroll(hwnd, PAGE_UP);
		break;
	case SB_PAGEDOWN:
		Scroll(hwnd, PAGE_DOWN);
		break;
	case SB_SLIDERTRACK:
	case SB_SLIDERPOSITION:
		Scroll(hwnd, SHORT1FROMMP(mp2) - pTop);
		break;
	}
}

/*=======================================================================
 |
 |		���[�U�Ǘ��E�B���h�E�@�X�N���[������
 |
 |	VOID	Scroll(hwnd, n)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		SHORT	n;		�X�N���[���s��
 |
 =======================================================================*/
static	VOID	Scroll(HWND hwnd, SHORT n)
{
	RECTL	rcl;
	SHORT	pageLine;
	SHORT	nRec;

	WinQueryWindowRect(hwnd, &rcl);
	rcl.yTop -= gcyChar * 2;
	pageLine = (SHORT)(rcl.yTop - rcl.yBottom) / gcyChar;

	if (n == PAGE_UP)
		n = -pageLine;
	else if (n == PAGE_DOWN)
		n = pageLine;

	if (pTop + n < 0)
		n = -pTop;
	else {
		nRec = (SHORT)DBRecCount(userDBp);
		if (pTop + n >= nRec)
			n = nRec - pTop - 1;
	}

	if (n == 0)
		return;

	pTop += n;
	if ((n < 0 ? -n : n) < pageLine)
		WinScrollWindow(hwnd, 0, n * gcyChar, 0L, &rcl,
					NULL, NULL, SW_INVALIDATERGN);
	else
		WinInvalidateRect(hwnd, &rcl, FALSE);
}

/*=======================================================================
 |
 |		�����_�C�A���O�E�B���h�E�v���V�[�W��
 |
 |	MRESULT CALLBACK UserSearchDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK UserSearchDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	CHAR	fieldBuf[MAXFIELDL];
	CHAR	msgBuf[80];
	SWP	swp;

	switch (msg) {
	case WM_INITDLG:
		WinSendDlgItemMsg(hwnd, IDD_USER_SEARCH, EM_SETTEXTLIMIT,
			MPFROMSHORT(index == indexUserID ? 8 : 15), 0);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			WinQueryDlgItemText(hwnd, IDD_USER_SEARCH,
						sizeof(fieldBuf), fieldBuf);
			if (DBSearch(userDBp, fieldBuf,
						strlen(fieldBuf), index)) {
				pSelect = pSelect2 =
						(SHORT)DBGetRecNo(userDBp);
				nSelect = 1;
				WinQueryWindowPos(hwndClient, &swp);
				pTop = pSelect - min((swp.cy - 2 * gcyChar)
						/ gcyChar / 2, pSelect);
				topRecNo = 0;
				WinInvalidateRect(hwndClient, NULL, FALSE);
			} else {
				sprintf(msgBuf, "[%s] �͂���܂���B",
								fieldBuf);
				WinMessageBox(HWND_DESKTOP, hwnd,
					msgBuf, NULL, ID_MB,
					MB_OK | MB_ICONEXCLAMATION
					| MB_APPLMODAL);
				WinSetFocus(HWND_DESKTOP,
					WinWindowFromID(hwnd,
					IDD_USER_SEARCH));
				return(0);
			}
			break;
		case DID_CANCEL:
			break;
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		���}�E�X�{�^���N���b�N����
 |
 |	VOID	Mouse1Down(hwnd, mp1)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |
 =======================================================================*/
static	VOID	Mouse1Down(HWND hwnd, MPARAM mp1)
{
	RECTL	rcl, rcl2;
	SHORT	selectLine;

	WinQueryWindowRect(hwnd, &rcl);
	rcl.yTop -= gcyChar * 2;

	if (nSelect != 0) {
		rcl2 = rcl;
		rcl2.yTop -= (pSelect - pTop) * gcyChar;
		rcl2.yBottom = rcl2.yTop - nSelect * gcyChar;
		WinInvalidateRect(hwnd, &rcl2, FALSE);
		nSelect = 0;
	}

	selectLine = ((SHORT)rcl.yTop - (SHORT)SHORT2FROMMP(mp1)) / gcyChar;
	if (selectLine >= 0 && pTop + selectLine <
				(SHORT)DBRecCount(userDBp)) {
		nSelect = 1;
		pSelect = pTop + selectLine;
		rcl.yTop -= (pSelect - pTop) * gcyChar;
		rcl.yBottom = rcl.yTop - gcyChar;
		WinInvalidateRect(hwnd, &rcl, FALSE);
		if (WinSetCapture(HWND_DESKTOP, hwnd) == TRUE) {
			pSelect1 = pSelect2 = pSelect;
			selectFlag = TRUE;
		}

	}
}

/*=======================================================================
 |
 |		���}�E�X�{�^���J������
 |
 |	VOID	Mouse1Up()
 |
 =======================================================================*/
static	VOID	Mouse1Up(VOID)
{
	if (selectFlag) {
		WinSetCapture(HWND_DESKTOP, NULL);
		selectFlag = FALSE;
	}
}

/*=======================================================================
 |
 |		���}�E�X�{�^���N���b�N����
 |
 |	VOID	Mouse2Down(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	Mouse2Down(HWND hwnd)
{
	RECTL	rcl;

	if (nSelect != 0) {
		WinQueryWindowRect(hwnd, &rcl);
		rcl.yTop -= (2 + (pSelect - pTop)) * gcyChar;
		rcl.yBottom = rcl.yTop - nSelect * gcyChar;
		WinInvalidateRect(hwnd, &rcl, FALSE);
		nSelect = 0;
	}
}

/*=======================================================================
 |
 |		�}�E�X�h���b�O�`�F�b�N����
 |
 |	VOID	MouseDragCheck(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	MouseDragCheck(HWND hwnd)
{
	RECTL	rcl, rcl2;
	SHORT	nScroll;
	SHORT	pCurrent;
	SHORT	minPtr, maxPtr;
	SHORT	nRec;

	if (selectFlag == FALSE)
		return;

	WinQueryWindowRect(hwnd, &rcl);
	rcl.yTop -= gcyChar * 2;

	pCurrent = pTop + ((SHORT)rcl.yTop - yMousePos) / gcyChar;
	if (((SHORT)rcl.yTop - yMousePos) % gcyChar < 0)
		pCurrent--;

	nRec = (SHORT)DBRecCount(userDBp);
	if (pCurrent < 0)
		pCurrent = 0;
	else if (pCurrent >= nRec)
		pCurrent = nRec - 1;

	if (pCurrent != pSelect2) {
		if (pCurrent >= pSelect1) {
			nSelect = pCurrent - pSelect1 + 1;
			pSelect = pSelect1;
		} else {
			nSelect = pSelect1 - pCurrent + 1;
			pSelect = pCurrent;
		}

		minPtr = min(pCurrent, pSelect2);
		if (minPtr >= pSelect1)
			minPtr++;
		maxPtr = max(pCurrent, pSelect2);
		if (maxPtr > pSelect1)
			maxPtr++;
		rcl2 = rcl;
		rcl2.yTop = rcl.yTop - (minPtr - pTop) * gcyChar;
		rcl2.yBottom = rcl.yTop - (maxPtr - pTop) * gcyChar;
		WinInvalidateRect(hwnd, &rcl2, FALSE);
		pSelect2 = pCurrent;

		if ((nScroll = pTop - pCurrent) > 0) {
			Scroll(hwnd, -nScroll);
		} else if ((nScroll = pCurrent -
				(pTop + (SHORT)rcl.yTop / gcyChar) + 1) > 0){
			Scroll(hwnd, nScroll);
		}
	}
}

/*=======================================================================
 |
 |		�L�[�{�[�h�ɂ��s�I������
 |
 |	VOID	SelectLines(hwnd, n, drag)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		SHORT	n;		�|�P�F����L�[
 |					�@�P�F�����L�[
 |					�@�O�F�I������
 |		BOOL	drag;		TRUE  : �����I��
 |					FALSE : �P��I��
 |
 =======================================================================*/
static	VOID	SelectLines(HWND hwnd, SHORT n, BOOL drag)
{
	RECTL	rcl, rcl2;
	SHORT	nRec, pageLine;
	SHORT	oldpSelect2, oldnSelect;

	nRec = (SHORT)DBRecCount(userDBp);
	if (nRec == 0)
		return;

	oldpSelect2 = pSelect2;
	oldnSelect = nSelect;

	WinQueryWindowRect(hwnd, &rcl);
	rcl.yTop -= gcyChar * 2;
	pageLine = (SHORT)(rcl.yTop - rcl.yBottom) / gcyChar;

	if (nSelect != 0) {
		if (pSelect2 + n < 0)
			pSelect2 = 0;
		else if (pSelect2 + n >= nRec)
			pSelect2 = nRec - 1;
		else
			pSelect2 += n;

		if (!drag) {
			rcl2 = rcl;
			rcl2.yTop -= (pSelect - pTop) * gcyChar;
			rcl2.yBottom = rcl2.yTop - nSelect * gcyChar;
			WinInvalidateRect(hwnd, &rcl2, FALSE);
			pSelect1 = pSelect2;
			oldnSelect = 0;
		}
	} else
		pSelect1 = pSelect2 = pTop;

	if (pSelect1 >= pSelect2) {
		pSelect = pSelect2;
		nSelect = pSelect1 - pSelect2 + 1;
	} else {
		pSelect = pSelect1;
		nSelect = pSelect2 - pSelect1 + 1;
	}

	rcl2 = rcl;
	rcl2.yTop -= ((nSelect >= oldnSelect ? pSelect2 : oldpSelect2) - pTop)
								* gcyChar;
	rcl2.yBottom = rcl2.yTop - gcyChar;
	WinInvalidateRect(hwnd, &rcl2, FALSE);

	if (n != 0) {
		if (pSelect2 < pTop)
			Scroll(hwnd, pSelect2 - pTop);
		else if (pSelect2 >= pTop + pageLine)
			Scroll(hwnd, pSelect2 - (pTop + pageLine - 1));
	} else
		nSelect = 0;
}

/*=======================================================================
 |
 |		�X�N���[���o�[�\������
 |
 |	VOID	EnableScrollBar(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	EnableScrollBar(HWND hwnd)
{
	SWP	swp;

	WinQueryWindowPos(hwnd, &swp);
	if (pTop != 0 || swp.cy / gcyChar < (SHORT)DBRecCount(userDBp) + 2) {
		WinSendMsg(hwndSB, SBM_SETSCROLLBAR,
			MPFROMSHORT(pTop),
			MPFROM2SHORT(0, (SHORT)DBRecCount(userDBp)-1));
		WinEnableWindow(hwndSB, TRUE);
	} else
		WinEnableWindow(hwndSB, FALSE);
}

/*=======================================================================
 |
 |		���[�U�o�^�_�C�A���O�v���V�[�W��
 |
 |	MRESULT CALLBACK UserDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK UserDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	static	CHAR	*userIDp;
	CHAR	fieldBuf[MAXFIELDL];
	SHORT	nSales;
	CHAR	strBuf[20];
	CHAR	userID[8];

	switch (msg) {
	case WM_INITDLG:
		userIDp = mp2;
		if (userIDp == NULL) {
			memset(&entryBuf, ' ', sizeof(entryBuf));
			entryBuf.userLevel[0] = '0';
			nSales = 0;
		} else {
			DBLock(userDBp);
			if (DBSearch(userDBp, userIDp, 8, indexUserID)
								== FALSE) {
				DBRelease(userDBp);
				WinDismissDlg(hwnd, DID_CANCEL);
				break;
			}

			DBRead(userDBp, &entryBuf);
			DBRelease(userDBp);
			nSales = (SHORT)DBCount(salesDBp, entryBuf.userID,
								8, indexSales);
		}

		SetEntryData(hwnd, entryTbl,
				sizeof(entryTbl) / sizeof(ENTRY_TBL));

		sprintf(strBuf, "%d ��", nSales);
		WinSetDlgItemText(hwnd, IDD_USER_NSALES, strBuf);

		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			if (WriteUserFile(hwnd, userIDp) == FALSE)
				return(0);
			break;
		case DID_CANCEL:
			break;
		case IDD_CMD_SALES:
			WinQueryDlgItemText(hwnd, IDD_USER_USERID,
						sizeof(fieldBuf), fieldBuf);
			if (fieldBuf[0] != '\0') {
				CopyToDBF(userID, fieldBuf, 8);
				WinDlgBox(HWND_DESKTOP, hwnd, SalesDlg, 0,
						IDD_SALES, (PVOID)userID);
				nSales = (SHORT)DBCount(salesDBp, userID,
								8, indexSales);
				sprintf(strBuf, "%d ��", nSales);
				WinSetDlgItemText(hwnd, IDD_USER_NSALES,
						strBuf);
			}
			return(0);
		case IDD_CMD_CINF:
			WinDlgBox(HWND_DESKTOP, hwnd, ComInfDlg, 0,
						IDD_COMINF, userIDp);
			return(0);
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		���[�U�ʐM���_�C�A���O�v���V�[�W��
 |
 |	MRESULT CALLBACK ComInfDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK ComInfDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	static	ENTRY_TBL	comInfTbl[] = {
		{ET_STRINGFIELD, IDD_USER_SDATE,   8, entryBuf.sDate},
		{ET_STRINGFIELD, IDD_USER_EDATE,   8, entryBuf.eDate},
		{ET_NUMSTRFIELD, IDD_USER_CCOUNT,  6, entryBuf.cCount},
		{ET_STRINGFIELD, IDD_USER_CTIME,  10, entryBuf.cTime},
		{ET_CHECKBUTTON, IDD_USER_LOGSAVE, '*', entryBuf.logSave}
	};

	switch (msg) {
	case WM_INITDLG:
		SetEntryData(hwnd, comInfTbl,
				sizeof(comInfTbl) / sizeof(ENTRY_TBL));
		CenterDlgBox(hwnd);
		if (mp2 == NULL) {
			WinEnableWindow(WinWindowFromID(hwnd, IDD_USER_SDATE),
					FALSE);
			WinEnableWindow(WinWindowFromID(hwnd, IDD_USER_EDATE),
					FALSE);
			WinEnableWindow(WinWindowFromID(hwnd, IDD_USER_CCOUNT),
					FALSE);
			WinEnableWindow(WinWindowFromID(hwnd, IDD_USER_CTIME),
					FALSE);
			WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_USER_LOGSAVE));
			return(MRFROMSHORT(TRUE));
		}
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			GetEntryData(hwnd, comInfTbl,
				sizeof(comInfTbl) / sizeof(ENTRY_TBL));
			break;
		case DID_CANCEL:
			break;
		}
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		���[�U�Ǘ��t�@�C���������ݏ���
 |
 |	BOOL	WriteUserFile(hwnd, userIDp)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		CHAR	*userIDp;	���[�U�h�c
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F���~
 |
 =======================================================================*/
static	BOOL	WriteUserFile(HWND hwnd, CHAR *userIDp)
{
	CHAR	msg[80];

	GetEntryData(hwnd, entryTbl, sizeof(entryTbl) / sizeof(ENTRY_TBL));

	if (strncmp(entryBuf.userID, "        ", 8) == 0)
		return(TRUE);

	DBLock(userDBp);

	if (userIDp == NULL) {
		if (DBSearch(userDBp, entryBuf.userID, 8, indexUserID)) {
			DBRelease(userDBp);
			sprintf(msg, "[%.8s] �͂��łɓo�^�ς݂ł��B",
							entryBuf.userID);
			WinMessageBox(HWND_DESKTOP, hwnd,
				msg, NULL, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
				WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_USER_USERID));
			return(FALSE);
		} else {
			DBStore(userDBp, &entryBuf);
		}
	} else {
		if (DBSearch(userDBp, userIDp, 8, indexUserID))
			DBUpdate(userDBp, &entryBuf);
	}
	DBRelease(userDBp);
	return(TRUE);
}

/*=======================================================================
 |
 |		���[�U�Ǘ��t�@�C���I�[�v������
 |
 |	BOOL	OpenUserDBF(fIndexCreate)
 |
 |		BOOL	fIndexCreate;	�C���f�b�N�X�t�@�C���쐬�t���O
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�I�[�v�����s�i���~�j
 |
 =======================================================================*/
BOOL	OpenUserDBF(BOOL fIndexCreate)
{
	if ((userDBp = DBOpen(fnUSERDBF)) == NULL) {
		if (WinMessageBox(HWND_DESKTOP, hwndMain,
				"�V�K�ɍ쐬���܂����H",
				"���[�U�Ǘ��t�@�C��������܂���I", ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
				) == MBID_YES)
			return(CreateUserDBF());
		else
			return(FALSE);
	} else {
		if (DBCheckSize(userDBp))
			fIndexCreate = TRUE;
	}

	if (UseUserIndex(fIndexCreate) == FALSE) {
		DBClose(userDBp);
		return(FALSE);
	}

	index = indexUserID;

	return(TRUE);
}

/*=======================================================================
 |
 |		���[�U�Ǘ��t�@�C���V�K�쐬����
 |
 |	BOOL	CreateUserDBF()
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�쐬���s
 |
 =======================================================================*/
static	BOOL	CreateUserDBF(VOID)
{
	static	DBF_I	fieldData[] = {
		{"���[�U�h�c", 'C',  8, 0},
		{"�p�X���[�h", 'C',  8, 0},
		{"�O���[�v",   'C',  8, 0},
		{"�����x��", 'C',  1, 0},
		{"�t���K�i",   'C', 16, 0},
		{"���O",       'C', 16, 0},
		{"���l",       'C', 40, 0},
		{"�ڑ��J�n��", 'C',  8, 0},
		{"�ŏI�ڑ���", 'C',  8, 0},
		{"�ڑ���",   'C',  6, 0},
		{"�ʐM����",   'C', 10, 0},
		{"���O�ۑ�",   'C',  1, 0},
		{"�X�֔ԍ�",   'C',  6, 0},
		{"�Z���P",     'C', 40, 0},
		{"�Z���Q",     'C', 40, 0},
		{"��Ж�",     'C', 40, 0},
		{"������",     'C', 40, 0},
		{"�d�b�ԍ�",   'C', 15, 0}
	};

	if ((userDBp = DBCreate(fnUSERDBF, fieldData,
				sizeof(fieldData)/sizeof(DBF_I))) == NULL) {
		WinMessageBox(HWND_DESKTOP, hwndMain,
				"�Ǘ��t�@�C�����쐬�ł��܂���ł����B",
				"���[�U�Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		return(FALSE);
	}

	if (UseUserIndex(TRUE) == FALSE) {
		DBClose(userDBp);
		return(FALSE);
	}

	index = indexUserID;

	return(TRUE);
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���I�[�v���^�쐬
 |
 |	BOOL	UseUserIndex(fCreate)
 |
 |		BOOL	fCreate;	�쐬�t���O
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�쐬���s
 |
 =======================================================================*/
static	BOOL	UseUserIndex(BOOL fCreate)
{
	if (fCreate || (indexUserID = DBIndex(userDBp, fnUSERIDNDX)) == 0) {
		if ((indexUserID = DBIdxCreate(userDBp, fnUSERIDNDX,
						"���[�U�h�c", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"�C���f�b�N�X�t�@�C�����쐬�ł��܂���ł����B",
				"���[�U�Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}

	if (fCreate || (indexFurigana = DBIndex(userDBp, fnFURIGANANDX)) == 0){
		if ((indexFurigana = DBIdxCreate(userDBp, fnFURIGANANDX,
						"�t���K�i", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"�C���f�b�N�X�t�@�C�����쐬�ł��܂���ł����B",
				"���[�U�Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		���[�U���폜����
 |
 |	VOID	DeleteUser(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	DeleteUser(HWND hwnd)
{
	SHORT	i;
	SHORT	nRec;

	if (WinMessageBox(HWND_DESKTOP, hwnd,
			"�w�肵�����[�U�����폜���܂����H",
			"�m�@�F", ID_MB,
			MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
			) == MBID_NO)
		return;

	SetPointer(SPTR_WAIT);

	DBLock(userDBp);
	DBSet(userDBp, selectRecNo);
	if (nSelect < (SHORT)DBRecCount(userDBp) / 5) {
		for (i = 0; i < nSelect; i++) {
			if (DBEof(userDBp))
				break;
			DeleteSales();
			DBDelete3(userDBp);
		}
	} else {
		for (i = 0; i < nSelect; i++) {
			if (DBEof(userDBp))
				break;
			DeleteSales();
			DBDelete(userDBp);
			DBSkip(userDBp, 1L);
		}
		DBPack(userDBp);
	}
	DBRelease(userDBp);

	nRec = (SHORT)DBRecCount(userDBp);
	if (pTop >= nRec)
		pTop = (nRec != 0) ? nRec - 1 : 0;

	if (pTop > pSelect)
		pTop = pSelect;

	SetPointer(SPTR_ARROW);

	nSelect = 0;
	WinInvalidateRect(hwnd, NULL, FALSE);
}

/*=======================================================================
 |
 |		�̔����폜����
 |
 |	VOID	DeleteSales()
 |
 =======================================================================*/
static	VOID	DeleteSales(VOID)
{
	USERDBF	userData;

	SetPointer(SPTR_WAIT);

	DBRead(userDBp, &userData);
	for (;;) {
		if (DBSearch(salesDBp, userData.userID, 8, indexSales))
			DBDelete2(salesDBp);
		else
			break;
	}

	SetPointer(SPTR_ARROW);
}