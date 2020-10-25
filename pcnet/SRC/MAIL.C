/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: ���[���Ǘ�����			*
 *		�t�@�C����	: mail.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>

/*--------------------------------------*/
/*		�����֐�		*/
/*--------------------------------------*/
static	VOID	SetTitle(HWND);
static	VOID	MailWndPaint(HWND);
static	VOID	MailWndInitMenu(HWND, USHORT);
static	VOID	MailWndCommand(HWND, USHORT);
static	VOID	GetMailFile(HWND);
static	VOID	MailWndVScroll(HWND, MPARAM, MPARAM);
static	VOID	Scroll(HWND, SHORT);
static	VOID	CopyMailFile(HWND, CHAR *);
static	VOID	Mouse1Down(HWND, MPARAM);
static	VOID	Mouse1Up(VOID);
static	VOID	Mouse2Down(HWND);
static	VOID	MouseDragCheck(HWND);
static	VOID	SelectLines(HWND, SHORT, BOOL);
static	VOID	EnableScrollBar(HWND);
static	VOID	ReadMail(VOID);
static	BOOL	CreateMailDBF(VOID);
static	BOOL	UseMailIndex(BOOL);
static	VOID	DeleteMail(HWND);
static	VOID	DeleteMailFile(VOID);
static	SHORT	GetMailNum(VOID);
MRESULT CALLBACK MailSendDlg(HWND, USHORT, MPARAM, MPARAM);
MRESULT CALLBACK MailUpdateDlg(HWND, USHORT, MPARAM, MPARAM);

/*--------------------------------------*/
/*		�����ϐ�		*/
/*--------------------------------------*/
static	HWND	hwndClient;	/* �N���C�A���g�E�B���h�E�n���h�� */
static	HWND	hwndSB;		/* �X�N���[���o�[�E�B���h�E�n���h�� */
static	ULONG	flStyle =	/* �t���[���E�B���h�E�X�^�C�� */
	FCF_ACCELTABLE | FCF_MAXBUTTON | FCF_MENU | FCF_NOMOVEWITHOWNER |
	FCF_SIZEBORDER | FCF_SYSMENU | FCF_TITLEBAR | FCF_VERTSCROLL;
static	MAILDBF	dspBuf;		/* �\���p���[���Ǘ��t�@�C���o�b�t�@ */
static	CHAR	dspBufType[10];
static	DISP_TBL	dispTbl[] = {	/* �\�����e�[�u�� */
	{IDM_MAIL_DSP_SID,    TRUE, "  ���M�h�c",  8, dspBuf.sUserID,  8},
	{IDM_MAIL_DSP_RID,    TRUE, "  ��M�h�c",  8, dspBuf.rUserID,  8},
	{IDM_MAIL_DSP_SDATE,  TRUE, "  ���M���t",  8, dspBuf.sDate, 8},
	{IDM_MAIL_DSP_RDATE,  TRUE, "  ��M���t",  8, dspBuf.rDate, 8},
	{IDM_MAIL_DSP_TYPE,   TRUE, "  ���",  4, dspBufType, 4},
	{IDM_MAIL_DSP_TITLE,  TRUE, "  �^�C�g��", 40, dspBuf.title, 40}
};
static	MAILDBF	entryBuf;	/* ���͗p���[���Ǘ��t�@�C���o�b�t�@ */
static	ENTRY_TBL	entryTbl[] = {	/* ���͏��e�[�u�� */
	{ET_STRINGFIELD, IDD_MAIL_SID,    8, entryBuf.sUserID},
	{ET_STRINGFIELD, IDD_MAIL_RID,    8, entryBuf.rUserID},
	{ET_RADIOBUTTON, IDD_MAIL_TYPEA,'A', entryBuf.type},
	{ET_RADIOBUTTON, IDD_MAIL_TYPEC,'C', entryBuf.type},
	{ET_RADIOBUTTON, IDD_MAIL_TYPEM,'M', entryBuf.type},
	{ET_RADIOBUTTON, IDD_MAIL_TYPEQ,'Q', entryBuf.type},
	{ET_STRINGFIELD, IDD_MAIL_TITLE, 40, entryBuf.title},
	{ET_STRINGFIELD, IDD_MAIL_SDATE, 17, entryBuf.sDate},
	{ET_STRINGFIELD, IDD_MAIL_RDATE, 17, entryBuf.rDate},
	{ET_CHECKBUTTON, IDD_MAIL_SDF,  '*', entryBuf.sDelete},
	{ET_CHECKBUTTON, IDD_MAIL_RDF,  '*', entryBuf.rDelete}
};
static	CHAR	userID[10];	/* �\���Ώۃ��[�U�h�c */
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
 |		���[���Ǘ��E�B���h�E�I�[�v������
 |
 |	VOID	MailManagement()
 |
 =======================================================================*/
VOID	MailManagement(VOID)
{
	static	CHAR	title[] = "���[���Ǘ�";

	if (windowOpenFlag)
		return;

	strcpy(userID, sv.env.systemID);
	hwndMail = WinCreateStdWindow(HWND_DESKTOP,
				WS_VISIBLE, &flStyle, szMailClassName,
				title, 0, 0, ID_RESOURCE_MAIL, &hwndClient);
}

/*=======================================================================
 |
 |		���[���Ǘ��E�B���h�E�v���V�[�W��
 |
 |	MRESULT CALLBACK MailWindowProc(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK MailWindowProc(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
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
		MailWndPaint(hwnd);
		return(0);
	case WM_SIZE:
		EnableScrollBar(hwnd);
		break;
	case WM_INITMENU:
		MailWndInitMenu(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_COMMAND:
		MailWndCommand(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_VSCROLL:
		MailWndVScroll(hwnd, mp1, mp2);
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
		ReadMail();
		break;
	case WM_CLOSE:
		windowOpenFlag = FALSE;
		WinDestroyWindow(hwndMail);
		WinSetActiveWindow(HWND_DESKTOP, hwndMain);
		DBFlush(mailDBp);
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
	CHAR	*p;

	if (index == indexMailR1)
		p = "��M";
	else if (index == indexMailS1)
		p = "���M";
	else if (index == indexMailA)
		p = "�S";
	else
		return;
	
	sprintf(title, "���[���Ǘ��@(%s���[����=%d)", p, GetMailNum());
	WinSetWindowText(hwnd, title);
}

/*=======================================================================
 |
 |		���[���Ǘ��E�B���h�E�`�揈��
 |
 |	VOID	MailWndPaint(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	MailWndPaint(HWND hwnd)
{
	HPS	hps;
	RECTL	rcl;
	POINTL	pt;
	USHORT	i, j;
	SHORT	pCurrent;
	SHORT	top, bottom;
	CHAR	strBuf[100], *strBufp;
	static	LONG	nRec;

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

	DBLock(mailDBp);
	DBChgIdx(mailDBp, index);
	if (pTop == 0 || topRecNo == 0 || DBCheckUpdate(mailDBp)) {
		nRec = GetMailNum();
		if (index == indexMailA) {
			DBBottom(mailDBp);
			DBSkip(mailDBp, -pTop);
		} else
			DBSkip(mailDBp, -(pTop + 1));
		topRecNo = DBRecNo(mailDBp);
		DBResetUpdate(mailDBp);
	} else {
		DBSet(mailDBp, topRecNo);
		if (pTop != pTopSv) {
			DBSkip(mailDBp, pTopSv - pTop);
			topRecNo = DBRecNo(mailDBp);
		}
	}
	pTopSv = pTop;

	memset(strBuf, ' ' , sizeof(strBuf));
	for (pCurrent = pTop; ; pCurrent++) {
		if (DBBof(mailDBp))
			break;
		if (index != indexMailA) {
			if (IDXCompare(mailDBp->ip[index], userID, 8) != 0)
				break;
		}

		/* �I���s�̃��R�[�h�ԍ��擾 */
		if (nSelect != 0 && pCurrent == pSelect)
			selectRecNo = DBRecNo(mailDBp);

		/* �ŉ��[�܂ŕ\��������I�� */
		if (pt.y + gcyChar <= bottom || pt.y + gcyChar < 0)
			break;

		if (pt.y < top) {
			DBRead(mailDBp, &dspBuf);
			switch (dspBuf.type[0]) {
			case 'A':
				strcpy(dspBufType, "��");
				break;
			case 'C':
				strcpy(dspBufType, "����");
				break;
			case 'M':
				strcpy(dspBufType, "Ұ� ");
				break;
			case 'Q':
				strcpy(dspBufType, "����");
				break;
			default:
				strcpy(dspBufType, "    ");
				break;
			}
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
		DBSkip(mailDBp, -1L);
	}
	DBRelease(mailDBp);
	WinEndPaint(hps);

	EnableScrollBar(hwnd);

	MouseDragCheck(hwnd);
}

/*=======================================================================
 |
 |		���[���Ǘ��E�B���h�E�@���j���[����������
 |
 |	VOID	MailWndInitMenu(hwnd, menuID)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	menuID;		���j���[�h�c
 |
 =======================================================================*/
static	VOID	MailWndInitMenu(HWND hwnd, USHORT menuID)
{
	USHORT	i;
	MAILDBF	recBuf;

	switch (menuID) {
	case IDM_MAIL_FILE:
		if (nSelect == 1) {
			DBLock(mailDBp);
			DBSet(mailDBp, selectRecNo);
			DBRead(mailDBp, &recBuf);
			DBRelease(mailDBp);
		}
		WinSendDlgItemMsg(hwndMail, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_MAIL_FILE_SEND2, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect == 1 && (recBuf.type[0] == 'Q' ||
				recBuf.type[0] == 'M') ? 0 : MIA_DISABLED));
		WinSendDlgItemMsg(hwndMail, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_MAIL_FILE_UPDATE, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect == 1 ? 0 : MIA_DISABLED));
		WinSendDlgItemMsg(hwndMail, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_MAIL_FILE_DELETE, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect != 0 ? 0 : MIA_DISABLED));
		WinSendDlgItemMsg(hwndMail, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_MAIL_FILE_READ, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect == 1 ? 0 : MIA_DISABLED));
		break;
	case IDM_MAIL_DISPLAY:
		WinSendDlgItemMsg(hwndMail, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_MAIL_DSP_SMAIL, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				index == indexMailS1 ? MIA_CHECKED : 0));
		WinSendDlgItemMsg(hwndMail, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_MAIL_DSP_RMAIL, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				index == indexMailR1 ? MIA_CHECKED : 0));
		WinSendDlgItemMsg(hwndMail, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_MAIL_DSP_ALL, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				index == indexMailA ? MIA_CHECKED : 0));
		for (i = 0; i < sizeof(dispTbl)/sizeof(DISP_TBL); i++) {
			WinSendDlgItemMsg(hwndMail, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(dispTbl[i].menuID, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				dispTbl[i].flag ? MIA_CHECKED : 0));
		}
		break;
	}
}

/*=======================================================================
 |
 |		���[���Ǘ��E�B���h�E�@�R�}���h����
 |
 |	VOID	MailWndCommand(hwnd, command)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	command;	�R�}���h�h�c
 |
 =======================================================================*/
static	VOID	MailWndCommand(HWND hwnd, USHORT command)
{
	USHORT	i;

	switch (command) {
	case IDM_MAIL_FILE_SEND:
		WinDlgBox(HWND_DESKTOP, hwnd, MailSendDlg, 0,
						IDD_MAIL_SEND, (PVOID)0);
		SetTitle(hwndMail);
		break;
	case IDM_MAIL_FILE_SEND2:
		WinDlgBox(HWND_DESKTOP, hwnd, MailSendDlg, 0,
						IDD_MAIL_SEND, (PVOID)1);
		SetTitle(hwndMail);
		break;
	case IDM_MAIL_FILE_UPDATE:
		WinDlgBox(HWND_DESKTOP, hwnd, MailUpdateDlg, 0,
						IDD_MAIL_UPDATE, NULL);
		break;
	case IDM_MAIL_FILE_DELETE:
		DeleteMail(hwnd);
		SetTitle(hwndMail);
		break;
	case IDM_MAIL_FILE_READ:
		GetMailFile(hwnd);
		break;
	case IDM_MAIL_DSP_SID:
	case IDM_MAIL_DSP_RID:
	case IDM_MAIL_DSP_SDATE:
	case IDM_MAIL_DSP_RDATE:
	case IDM_MAIL_DSP_TYPE:
	case IDM_MAIL_DSP_TITLE:
		for (i = 0; i < sizeof(dispTbl)/sizeof(DISP_TBL); i++) {
			if (command == dispTbl[i].menuID) {
				dispTbl[i].flag = !dispTbl[i].flag;
				break;
			}
		}
		WinInvalidateRect(hwnd, NULL, FALSE);
		break;
	case IDM_MAIL_DSP_SMAIL:
		if (index != indexMailS1) {
			index = indexMailS1;
			pTop = 0;
			nSelect = 0;
			SetTitle(hwndMail);
			WinInvalidateRect(hwnd, NULL, FALSE);
		}
		break;
	case IDM_MAIL_DSP_RMAIL:
		if (index != indexMailR1) {
			index = indexMailR1;
			pTop = 0;
			nSelect = 0;
			SetTitle(hwndMail);
			WinInvalidateRect(hwnd, NULL, FALSE);
		}
		break;
	case IDM_MAIL_DSP_ALL:
		if (index != indexMailA) {
			index = indexMailA;
			pTop = 0;
			nSelect = 0;
			SetTitle(hwndMail);
			WinInvalidateRect(hwnd, NULL, FALSE);
		}
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
	case IDK_CR:
		ReadMail();
		break;
	}
}

/*=======================================================================
 |
 |		���[���t�@�C�����o������
 |
 |	VOID	GetMailFile(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	GetMailFile(HWND hwnd)
{
	INPUTFN	inputfn;
	CHAR	fileName[65];
	CHAR	fName[13];
	CHAR	mailFile[26];
	MAILDBF	recBuf;

	inputfn.title = "���o����t�@�C�����̓���";
	inputfn.bufp = fileName;
	inputfn.bufSize = sizeof(fileName);
	if (SHORT1FROMMR(WinDlgBox(HWND_DESKTOP, hwnd,
			InputFileNameDlg, 0,
			IDD_FILENAME, (PVOID)&inputfn)) != DID_OK)
		return;

	DBLock(mailDBp);
	DBSet(mailDBp, selectRecNo);
	DBRead(mailDBp, &recBuf);
	DBRelease(mailDBp);
	CopyFromDBF(fName, recBuf.fileName, sizeof(recBuf.fileName));
	sprintf(mailFile, "%s\\%s", dnMAIL, fName);

	FileCopy(hwnd, fileName, mailFile);
}

/*=======================================================================
 |
 |		���[���Ǘ��E�B���h�E�@�X�N���[���R�}���h����
 |
 |	VOID	MailWndVScroll(hwnd, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
static	VOID	MailWndVScroll(HWND hwnd, MPARAM mp1, MPARAM mp2)
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
 |		���C���E�B���h�E�@�X�N���[������
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
		nRec = GetMailNum();
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
 |		���[�����M�_�C�A���O�E�B���h�E�v���V�[�W��
 |
 |	MRESULT CALLBACK MailSendDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK MailSendDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	CHAR	sUserID[9], rUserID[9], title[41], msgBuf[80];
	static	CHAR	fileName[13];
	static	CHAR	pathName[MAXPATHL];
	MAILDBF	recBuf;

	switch (msg) {
	case WM_INITDLG:
		memset(&entryBuf, ' ', sizeof(entryBuf));
		if (SHORT1FROMMP(mp2) == 0) {
			CopyToDBF(entryBuf.sUserID, userID,
					sizeof(entryBuf.sUserID));
			entryBuf.type[0] = 'M';
			WinSetFocus(HWND_DESKTOP,
					WinWindowFromID(hwnd, IDD_MAIL_RID));
		} else {
			DBLock(mailDBp);
			DBSet(mailDBp, selectRecNo);
			DBRead(mailDBp, &recBuf);
			DBRelease(mailDBp);
			memcpy(entryBuf.sUserID, recBuf.rUserID, 8);
			memcpy(entryBuf.rUserID, recBuf.sUserID, 8);
			if (recBuf.type[0] == 'Q')
				entryBuf.type[0] = 'A';
			else
				entryBuf.type[0] = 'M';
			memcpy(entryBuf.title, recBuf.title, 40);
			WinSetFocus(HWND_DESKTOP,
					WinWindowFromID(hwnd, IDD_MAIL_TITLE));
		}
		SetEntryData(hwnd, entryTbl, 7);

		MakeMailFile(fileName, pathName);
		CenterDlgBox(hwnd);
		return(MRFROMSHORT(TRUE));
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			GetEntryData(hwnd, entryTbl, 7);
			CopyFromDBF(sUserID, entryBuf.sUserID,
					sizeof(entryBuf.sUserID));
			CopyFromDBF(rUserID, entryBuf.rUserID,
					sizeof(entryBuf.rUserID));
			CopyFromDBF(title, entryBuf.title,
					sizeof(entryBuf.title));
			if (SendMail(sUserID, rUserID, title, fileName,
						entryBuf.type[0]) == FALSE) {
				sprintf(msgBuf,
					"[%s]�͓o�^����Ă��܂���B", rUserID);
				WinMessageBox(HWND_DESKTOP, hwnd,
				    msgBuf, NULL, ID_MB,
				    MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
				WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_MAIL_RID));
				return(0);
			}
			WinInvalidateRect(hwndClient, NULL, FALSE);
			break;
		case DID_CANCEL:
			DosDelete(pathName, 0L);
			break;
		case IDD_CMD_COPY:
			CopyMailFile(hwnd, pathName);
			return(0);
		case IDD_CMD_EDIT:
			ExecEditor(pathName);
			return(0);
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		���M�ς݃��[���ύX�_�C�A���O�E�B���h�E�v���V�[�W��
 |
 |	MRESULT CALLBACK MailUpdateDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |		USHORT	msg;		���b�Z�[�W���ʎq
 |		MPARAM	mp1;		���b�Z�[�W�p�����[�^�P
 |		MPARAM	mp2;		���b�Z�[�W�p�����[�^�Q
 |
 =======================================================================*/
MRESULT CALLBACK MailUpdateDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	CHAR	fileName[13];
	CHAR	pathName[MAXPATHL];

	switch (msg) {
	case WM_INITDLG:
		DBLock(mailDBp);
		DBSet(mailDBp, selectRecNo);
		DBRead(mailDBp, &entryBuf);
		DBRelease(mailDBp);
		SetEntryData(hwnd, entryTbl,
				sizeof(entryTbl)/sizeof(ENTRY_TBL));
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			GetEntryData(hwnd, entryTbl,
				sizeof(entryTbl)/sizeof(ENTRY_TBL));
			DBLock(mailDBp);
			DBSet(mailDBp, selectRecNo);
			DBUpdate(mailDBp, &entryBuf);
			DBRelease(mailDBp);
			WinInvalidateRect(hwndClient, NULL, FALSE);
			break;
		case DID_CANCEL:
			break;
		case IDD_CMD_COPY:
			CopyFromDBF(fileName, entryBuf.fileName,
						sizeof(entryBuf.fileName));
			sprintf(pathName, "%s\\%s", dnMAIL, fileName);
			CopyMailFile(hwnd, pathName);
			return(0);
		case IDD_CMD_EDIT:
			CopyFromDBF(fileName, entryBuf.fileName,
						sizeof(entryBuf.fileName));
			sprintf(pathName, "%s\\%s", dnMAIL, fileName);
			ExecEditor(pathName);
			return(0);
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		���[���t�@�C���R�s�[����
 |
 |	VOID	CopyMilFile(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	CopyMailFile(HWND hwnd, CHAR *mailFile)
{
	CHAR	pathName[MAXPATHL];

	if (SelectFile(hwnd, "���[���t�@�C���̃R�s�[", pathName) == FALSE)
		return;

	FileCopy(hwnd, mailFile, pathName);
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
	if (selectLine >= 0 && pTop + selectLine < GetMailNum()) {
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

	pCurrent = pTop +
			((SHORT)rcl.yTop - yMousePos) / gcyChar;
	if (((SHORT)rcl.yTop - yMousePos) % gcyChar < 0)
		pCurrent--;

	nRec = GetMailNum();
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

	nRec = GetMailNum();
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
	SHORT	nRec;

	WinQueryWindowPos(hwnd, &swp);
	nRec = GetMailNum();
	if (pTop != 0 || swp.cy / gcyChar < nRec + 2) {
		WinSendMsg(hwndSB, SBM_SETSCROLLBAR,
			MPFROMSHORT(pTop), MPFROM2SHORT(0, nRec-1));
		WinEnableWindow(hwndSB, TRUE);
	} else
		WinEnableWindow(hwndSB, FALSE);
}

/*=======================================================================
 |
 |		���[�����M����
 |
 |	BOOL	SendMail(sUserID, rUserID, title, fname, type)
 |
 |		CHAR	*sUserID;	���M�����[�U�h�c
 |		CHAR	*rUserID;	���M�惆�[�U�h�c
 |		CHAR	*title;		�^�C�g��
 |		CHAR	*fname;		�t�@�C����
 |		USHORT	type;		���
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F���M�惆�[�U�h�c���o�^
 |
 =======================================================================*/
BOOL	SendMail(CHAR *sUserID, CHAR *rUserID, CHAR *title,
						CHAR *fname, USHORT type)
{
	MAILDBF	recBuf;
	DATETIME	dateTime;
	CHAR	sDate[18];

	DBLock(mailDBp);
	if (ReadUserData(rUserID, NULL) == FALSE) {
		DBRelease(mailDBp);
		return(FALSE);
	}

	memset(&recBuf, ' ', sizeof(recBuf));
	CopyToDBF(recBuf.sUserID, sUserID, sizeof(recBuf.sUserID));
	CopyToDBF(recBuf.rUserID, rUserID, sizeof(recBuf.rUserID));
	CopyToDBF(recBuf.title, title, sizeof(recBuf.title));
	DosGetDateTime(&dateTime);
	sprintf(sDate,"%2u/%02u/%02u %2u:%02u:%02u",
			dateTime.year % 100,
			dateTime.month,
			dateTime.day,
			dateTime.hours,
			dateTime.minutes,
			dateTime.seconds);
	CopyToDBF(recBuf.sDate, sDate, sizeof(recBuf.sDate));
	memset(recBuf.rDate, '-', 17);
	CopyToDBF(recBuf.fileName, fname, sizeof(recBuf.fileName));
	recBuf.type[0] = (CHAR)type;
	DBStore(mailDBp, &recBuf);
	DBRelease(mailDBp);

	if (sv.env.mailFlag == '*' && strcmp(sv.env.systemID, rUserID) == 0)
		WinPostMsg(hwndMain, WM_USER_MAIL, 0, 0);

	return(TRUE);
}

/*=======================================================================
 |
 |		���[���Q�Ə���
 |
 |	VOID	ReadMail()
 |
 =======================================================================*/
static	VOID	ReadMail(VOID)
{
	CHAR	fileName[13];
	CHAR	editFile[MAXPATHL];
	MAILDBF	recBuf;
	CHAR	rUserID[9];

	if (nSelect == 1) {
		DBLock(mailDBp);
		DBSet(mailDBp, selectRecNo);
		DBRead(mailDBp, &recBuf);
		CopyFromDBF(rUserID, recBuf.rUserID, sizeof(recBuf.rUserID));
		if (strcmp(rUserID, userID) == 0)
			SetMailReadDate();
		DBRelease(mailDBp);
		CopyFromDBF(fileName, recBuf.fileName,
					sizeof(recBuf.fileName));
		sprintf(editFile, "%s\\%s", dnMAIL, fileName);
		ExecEditor(editFile);
	}
}

/*=======================================================================
 |
 |		��M���t�ݒ菈��
 |
 |	VOID	SetMailReadDate()
 |
 =======================================================================*/
VOID	SetMailReadDate(VOID)
{
	MAILDBF	recBuf;
	DATETIME	dateTime;
	CHAR	rDate[18];

	if (DBEof(mailDBp))
		return;

	DBLock(mailDBp);
	DBRead(mailDBp, &recBuf);

	if (recBuf.rDate[0] == '-') {
		DosGetDateTime(&dateTime);
		sprintf(rDate,"%2u/%02u/%02u %2u:%02u:%02u",
				dateTime.year % 100,
				dateTime.month,
				dateTime.day,
				dateTime.hours,
				dateTime.minutes,
				dateTime.seconds);
		CopyToDBF(recBuf.rDate, rDate, sizeof(recBuf.rDate));
		DBUpdate(mailDBp, &recBuf);
	}
	DBRelease(mailDBp);
}

/*=======================================================================
 |
 |		���M���[���폜����
 |
 |	VOID	DeleteSMail()
 |
 =======================================================================*/
VOID	DeleteSMail(VOID)
{
	MAILDBF	recBuf;

	if (DBEof(mailDBp))
		return;

	DBLock(mailDBp);
	DBRead(mailDBp, &recBuf);

	if (recBuf.rDate[0] == '-' || recBuf.rDelete[0] != ' ') {
		DeleteMailFile();
		DBDelete2(mailDBp);
	} else {
		recBuf.sDelete[0] = '*';
		DBUpdate(mailDBp, &recBuf);
	}
	DBRelease(mailDBp);
}

/*=======================================================================
 |
 |		��M���[���폜����
 |
 |	VOID	DeleteRMail()
 |
 =======================================================================*/
VOID	DeleteRMail(VOID)
{
	MAILDBF	recBuf;

	if (DBEof(mailDBp))
		return;

	DBLock(mailDBp);
	DBRead(mailDBp, &recBuf);

	if (recBuf.sDelete[0] != ' ') {
		DeleteMailFile();
		DBDelete2(mailDBp);
	} else {
		recBuf.rDelete[0] = '*';
		DBUpdate(mailDBp, &recBuf);
	}
	DBRelease(mailDBp);
}

/*=======================================================================
 |
 |		���[���Ǘ��t�@�C���I�[�v������
 |
 |	BOOL	OpenMailDBF(fIndexCreate)
 |
 |		BOOL	fIndexCreate;	�C���f�b�N�X�t�@�C���쐬�t���O
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�I�[�v�����s�i���~�j
 |
 =======================================================================*/
BOOL	OpenMailDBF(BOOL fIndexCreate)
{
	if ((mailDBp = DBOpen(fnMAILDBF)) == NULL) {
		if (WinMessageBox(HWND_DESKTOP, hwndMain,
				"�V�K�ɍ쐬���܂����H",
				"���[���Ǘ��t�@�C��������܂���I", ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
				) == MBID_YES)
			return(CreateMailDBF());
		else
			return(FALSE);
	} else {
		if (DBCheckSize(mailDBp))
			fIndexCreate = TRUE;
	}

	if (UseMailIndex(fIndexCreate) == FALSE) {
		DBClose(mailDBp);
		return(FALSE);
	}

	index = indexMailR1;

	return(TRUE);
}

/*=======================================================================
 |
 |		���[���Ǘ��t�@�C���V�K�쐬����
 |
 |	BOOL	CreateMailDBF()
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�쐬���s
 |
 =======================================================================*/
static	BOOL	CreateMailDBF(VOID)
{
	static	DBF_I	fieldData[] = {
		{"���M�҂h�c", 'C',  8, 0},
		{"��M�҂h�c", 'C',  8, 0},
		{"�^�C�g��",   'C', 40, 0},
		{"���M���t",   'C', 17, 0},
		{"��M���t",   'C', 17, 0},
		{"���폜ϰ�",  'C',  1, 0},
		{"��폜ϰ�",  'C',  1, 0},
		{"���",       'C',  1, 0},
		{"�t�@�C����", 'C', 12, 0}
	};

	if ((mailDBp = DBCreate(fnMAILDBF, fieldData,
				sizeof(fieldData)/sizeof(DBF_I))) == NULL) {
		WinMessageBox(HWND_DESKTOP, hwndMain,
				"�Ǘ��t�@�C�����쐬�ł��܂���ł����B",
				"���[���Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		return(FALSE);
	}

	if (UseMailIndex(TRUE) == FALSE) {
		DBClose(mailDBp);
		return(FALSE);
	}

	index = indexMailR1;

	DosMkDir(dnMAIL, 0L);

	return(TRUE);
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���I�[�v���^�쐬����
 |
 |	BOOL	UseMailIndex(fCreate)
 |
 |		BOOL	fCreate;	�쐬�t���O
 |
 |		BOOL	�Ԓl		TRUE �F����
 |					FALSE�F�쐬���s
 |
 =======================================================================*/
static	BOOL	UseMailIndex(BOOL fCreate)
{
	if (fCreate || (indexMailA = DBIndex(mailDBp, fnMAILANDX)) == 0) {
		if ((indexMailA = DBIdxCreate(mailDBp, fnMAILANDX,
					"���M���t", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"�C���f�b�N�X�t�@�C�����쐬�ł��܂���ł����B",
				"���[���Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}

	if (fCreate || (indexMailS1 = DBIndex(mailDBp, fnMAILS1NDX)) == 0) {
		if ((indexMailS1 = DBIdxCreate(mailDBp, fnMAILS1NDX,
				"���M�҂h�c+���M���t", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"�C���f�b�N�X�t�@�C�����쐬�ł��܂���ł����B",
				"���[���Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}

	if (fCreate || (indexMailR1 = DBIndex(mailDBp, fnMAILR1NDX)) == 0) {
		if ((indexMailR1 = DBIdxCreate(mailDBp, fnMAILR1NDX,
				"��M�҂h�c+���M���t", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"�C���f�b�N�X�t�@�C�����쐬�ł��܂���ł����B",
				"���[���Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}

	if (fCreate || (indexMailS2 = DBIndex(mailDBp, fnMAILS2NDX)) == 0) {
		if ((indexMailS2 = DBIdxCreate(mailDBp, fnMAILS2NDX,
			    "���M�҂h�c+���+���폜ϰ�+���M���t", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"�C���f�b�N�X�t�@�C�����쐬�ł��܂���ł����B",
				"���[���Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}

	if (fCreate || (indexMailR2 = DBIndex(mailDBp, fnMAILR2NDX)) == 0) {
		if ((indexMailR2 = DBIdxCreate(mailDBp, fnMAILR2NDX,
			    "��M�҂h�c+���+��폜ϰ�+���M���t", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"�C���f�b�N�X�t�@�C�����쐬�ł��܂���ł����B",
				"���[���Ǘ��t�@�C���A�N�Z�X�G���[�I",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		���[����M�`�F�b�N����
 |
 |	VOID	CheckReceiveMail(VOID)
 |
 =======================================================================*/
VOID	CheckReceiveMail(VOID)
{
	MAILDBF	recBuf;
	CHAR	systemID[10];

	CopyToDBF(systemID, sv.env.systemID, 8);
	DBLock(mailDBp);
	DBSearch(mailDBp, systemID, 8, indexMailR1);
	for (;;) {
		if (DBEof(mailDBp))
			break;
		if (IDXCompare(mailDBp->ip[indexMailR1], systemID, 8) != 0)
			break;
		DBRead(mailDBp, &recBuf);
		if (recBuf.rDelete[0] == ' ' && recBuf.rDate[0] == '-') {
			WinPostMsg(hwndMain, WM_USER_MAIL, 0, 0);
			break;
		}
		DBSkip(mailDBp, 1L);
	}
	DBRelease(mailDBp);
}

/*=======================================================================
 |
 |		���[���폜����
 |
 |	VOID	DeleteMail(hwnd)
 |
 |		HWND	hwnd;		�E�B���h�E�n���h��
 |
 =======================================================================*/
static	VOID	DeleteMail(HWND hwnd)
{
	SHORT	i;
	SHORT	nRec;

	if (WinMessageBox(HWND_DESKTOP, hwnd,
			"�w�肵�����[�����폜���܂����H",
			"�m�@�F", ID_MB,
			MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
			) == MBID_NO)
		return;

	SetPointer(SPTR_WAIT);

	DBLock(mailDBp);
	DBSet(mailDBp, selectRecNo);
	if (nSelect < (SHORT)DBRecCount(mailDBp) / 5) {
		for (i = 0; i < nSelect; i++) {
			if (DBBof(mailDBp))
				break;
			DeleteMailFile();
			DBDelete4(mailDBp);
		}
	} else {
		for (i = 0; i < nSelect; i++) {
			if (DBBof(mailDBp))
				break;
			DeleteMailFile();
			DBDelete(mailDBp);
			DBSkip(mailDBp, -1L);
		}
		DBPack(mailDBp);
	}
	DBRelease(mailDBp);

	nRec = GetMailNum();
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
 |		���[���t�@�C���폜����
 |
 |	VOID	DeleteMailFile()
 |
 =======================================================================*/
static	VOID	DeleteMailFile(VOID)
{
	MAILDBF	recBuf;
	CHAR	fileName[13], pathName[26];

	DBRead(mailDBp, &recBuf);
	CopyFromDBF(fileName, recBuf.fileName, sizeof(recBuf.fileName));
	sprintf(pathName, "%s\\%s", dnMAIL, fileName);
	DosDelete(pathName, 0L);
}

/*=======================================================================
 |
 |		���[�������o��
 |
 |	SHORT	GetMailNum()
 |
 |		SHORT	�Ԓl		���[����
 |
 =======================================================================*/
static	SHORT	GetMailNum(VOID)
{
	if (index == indexMailA)
		return((SHORT)DBRecCount(mailDBp));
	else
		return((SHORT)DBCount(mailDBp, userID, 8, index));
}

/*=======================================================================
 |
 |		���[���t�@�C���쐬����
 |
 |	VOID	MakeMailFile(fileName, pathName)
 |
 |		CHAR	*fileName;	�쐬�����t�@�C����
 |		CHAR	*pathName;	�쐬�����p�X��
 |
 =======================================================================*/
VOID	MakeMailFile(CHAR *fileName, CHAR *pathName)
{
	FILE	*fp;

	if ((fp = MakeNewFile(dnMAIL, fileName, pathName, "w")) != NULL)
		fclose(fp);
}