/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: 掲示板管理処理			*
 *		ファイル名	: bbs.c					*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>
#include <direct.h>
#include <io.h>
#include <sys\types.h>
#include <sys\stat.h>

#define	MAXFIELDL	41	/* フィールド最大長 */
#define	PRM_NEW		0
#define	PRM_UPDATE	1

typedef	struct	openBBS	{
	DB	*dbp;
	CHAR	name[13];
	USHORT	count;
	struct	openBBS	*chain;
} OPENBBS;

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	VOID	SetTitle(HWND);
static	VOID	BBSWndPaint(HWND);
static	VOID	BBSWndInitMenu(HWND, USHORT);
static	VOID	BBSWndCommand(HWND, USHORT);
static	VOID	GetBBSFile(HWND);
static	VOID	BBSWndVScroll(HWND, MPARAM, MPARAM);
static	VOID	Scroll(HWND, SHORT);
static	VOID	Mouse1Down(HWND, MPARAM);
static	VOID	Mouse1Up(VOID);
static	VOID	Mouse2Down(HWND);
static	VOID	MouseDragCheck(HWND);
static	VOID	SelectLines(HWND, SHORT, BOOL);
static	VOID	EnableScrollBar(HWND);
static	VOID	SetBBSList(HWND);
static	VOID	DeleteBBSAll(VOID);
static	BOOL	WriteBBSMFile(HWND, SHORT);
static	BOOL	WriteBBSFile(HWND, SHORT);
static	VOID	CopyBBSFile(HWND);
static	BOOL	CreateBBSMDBF(VOID);
static	BOOL	UseBBSMIndex(BOOL);
static	DB	*CreateBBSDBF(CHAR *);
static	BOOL	UseBBSIndex(CHAR *, DB *, BOOL);
static	VOID	DeleteBBS(HWND);
static	VOID	DeleteBBSFile(VOID);
MRESULT CALLBACK BBSSelectDlg(HWND, USHORT, MPARAM, MPARAM);
MRESULT CALLBACK BBSMDlg(HWND, USHORT, MPARAM, MPARAM);
MRESULT CALLBACK BBSDlg(HWND, USHORT, MPARAM, MPARAM);

/*--------------------------------------*/
/*		内部変数		*/
/*--------------------------------------*/
static	HWND	hwndClient;	/* クライアントウィンドウハンドル */
static	HWND	hwndSB;		/* スクロールバーウィンドウハンドル */
static	ULONG	flStyle =	/* フレームウィンドウスタイル */
	FCF_ACCELTABLE | FCF_MAXBUTTON | FCF_MENU | FCF_NOMOVEWITHOWNER |
	FCF_SIZEBORDER | FCF_SYSMENU | FCF_TITLEBAR | FCF_VERTSCROLL;
static	BBSDBF	dspBBSBuf;	/* 表示用掲示板ファイルバッファ */
static	DISP_TBL	dispTbl[] = {	/* 表示情報テーブル */
	{IDM_BBS_DSP_CREATE,  TRUE, "  登録日付 時刻    ",
						17, dspBBSBuf.date, 17},
	{IDM_BBS_DSP_USERID, FALSE, "  ユーザＩＤ",
						10, dspBBSBuf.userID, 8},
	{IDM_BBS_DSP_LEVEL,  FALSE, "  レベル",
						 6, dspBBSBuf.userLevel, 1},
	{IDM_BBS_DSP_FNAME,  FALSE, "  ファイル名  ",
						12, dspBBSBuf.name, 12},
	{IDM_BBS_DSP_SIZE,   FALSE, "  サイズ  ",
						 8, dspBBSBuf.size, 8},
	{IDM_BBS_DSP_TITLE,   TRUE, "  タイトル",
						40, dspBBSBuf.title, 40}
};
static	BBSDBF	entryBBSBuf;	/* 入力用掲示物管理ファイルバッファ */
static	ENTRY_TBL	entryBBS[] = {	/* 掲示物管理入力情報テーブル */
	{ET_STRINGFIELD, IDD_BBS_USERID, 8, entryBBSBuf.userID},
	{ET_STRINGFIELD, IDD_BBS_FNAME, 12, entryBBSBuf.name},
	{ET_STRINGFIELD, IDD_BBS_FSIZE,  8, entryBBSBuf.size},
	{ET_STRINGFIELD, IDD_BBS_DATE,  17, entryBBSBuf.date},
	{ET_STRINGFIELD, IDD_BBS_TITLE, 40, entryBBSBuf.title},
	{ET_STRINGFIELD, IDD_BBS_LEVEL,  1, entryBBSBuf.userLevel}
};
static	BBSMDBF	entryBBSMBuf;	/* 入力用掲示板管理ファイルバッファ */
static	ENTRY_TBL	entryBBSM[] = {	/* 掲示板管理入力情報テーブル */
	{ET_STRINGFIELD, IDD_BBS_NAME,       8, entryBBSMBuf.name},
	{ET_STRINGFIELD, IDD_BBS_TITLE,     20, entryBBSMBuf.title},
	{ET_STRINGFIELD, IDD_BBS_DATE,      17, entryBBSMBuf.date},
	{ET_RADIOBUTTON, IDD_BBS_TEXT,     'T', entryBBSMBuf.type},
	{ET_RADIOBUTTON, IDD_BBS_BINARY,   'B', entryBBSMBuf.type},
	{ET_RADIOBUTTON, IDD_BBS_LOLD,     'O', entryBBSMBuf.listSeq},
	{ET_RADIOBUTTON, IDD_BBS_LNEW,     'N', entryBBSMBuf.listSeq},
	{ET_CHECKBUTTON, IDD_BBS_USERWRITE,'*', entryBBSMBuf.userWrite}
};
static	OPENBBS	*openBBSp;	/* オープン中掲示物管理ポインタ */
static	DB	*BBSDBp;	/* 掲示物管理ファイルポインタ */
static	short	BBSType;	/* 掲示板タイプ */
static	SHORT	pTop;		/* 表示先頭レコードポインタ */
static	SHORT	pTopSv;		/* 前表示先頭レコードポインタ */
static	LONG	topRecNo;	/* 表示先頭物理レコード番号 */
static	SHORT	pSelect;	/* 選択レコードポインタ */
static	SHORT	pSelect1;	/* 選択開始レコードポインタ */
static	SHORT	pSelect2;	/* 選択終了レコードポインタ */
static	BOOL	selectFlag;	/* 選択中フラグ */
static	LONG	selectRecNo;	/* 選択物理レコード番号 */
static	SHORT	nSelect;	/* 選択レコード数 */
static	CHAR	BBSName[10];	/* カレント掲示板名 */
static	BOOL	windowOpenFlag;	/* 掲示板管理ウィンドウオープン中フラグ */
static	SHORT	yMousePos;	/* マウスＹ座標 */

/*=======================================================================
 |
 |		掲示板管理ウィンドウオープン処理
 |
 |	VOID	BBSManagement()
 |
 =======================================================================*/
VOID	BBSManagement(VOID)
{
	static	CHAR	title[] = "掲示板管理";

	if (windowOpenFlag)
		return;

	hwndBBS = WinCreateStdWindow(HWND_DESKTOP,
				WS_VISIBLE, &flStyle, szBBSClassName,
				title, 0, 0, ID_RESOURCE_BBS, &hwndClient);
}

/*=======================================================================
 |
 |		掲示板管理ウィンドウプロシージャ
 |
 |	MRESULT CALLBACK BBSWindowProc(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK BBSWindowProc(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	HWND	hwndFrame;

	switch (msg) {
	case WM_CREATE:
		hwndFrame = WinQueryWindow(hwnd, QW_PARENT, FALSE);
		WinSetOwner(hwndFrame, hwndMain);
		hwndSB = WinWindowFromID(hwndFrame, FID_VERTSCROLL);
		SetTitle(hwndFrame);
		nSelect = 0;
		windowOpenFlag = TRUE;
		WinPostMsg(hwnd, WM_COMMAND,
				MPFROMSHORT(IDM_BBS_FILE_SELECT), 0);
		SetWindowPos(hwndFrame, 70, 17);
		return(0);
	case WM_PAINT:
		BBSWndPaint(hwnd);
		return(0);
	case WM_SIZE:
		EnableScrollBar(hwnd);
		break;
	case WM_INITMENU:
		BBSWndInitMenu(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_COMMAND:
		BBSWndCommand(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_VSCROLL:
		BBSWndVScroll(hwnd, mp1, mp2);
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
		if (nSelect == 1) {
			BBSDBF	BBSBuf;
			CHAR	fileName[13];
			CHAR	BBSFile[26];

			if (BBSType == 'T') {
				DBLock(BBSDBp);
				DBSet(BBSDBp, selectRecNo);
				DBRead(BBSDBp, &BBSBuf);
				DBRelease(BBSDBp);
				CopyFromDBF(fileName, BBSBuf.fileName,
						sizeof(BBSBuf.fileName));
				sprintf(BBSFile, "%s.BBS\\%s",
						BBSName, fileName);
				ExecEditor(BBSFile);
			} else {
				WinPostMsg(hwnd, WM_COMMAND,
					MPFROMSHORT(IDM_BBS_FILE_UPDATE), 0);
			}
		}
		break;
	case WM_CLOSE:
		windowOpenFlag = FALSE;
		if (BBSDBp != NULL) {
			CloseBBSDBF(BBSDBp);
			BBSDBp = NULL;
		}
		WinDestroyWindow(hwndBBS);
		WinSetActiveWindow(HWND_DESKTOP, hwndMain);
		DBFlush(BBSMDBp);
		return(0);
	}
	return(WinDefWindowProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		タイトル（登録数）表示処理
 |
 |	VOID	SetTitle(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	SetTitle(HWND hwnd)
{
	CHAR	title[80];

	if (BBSDBp != NULL)
		sprintf(title, "掲示板管理 [%s] (登録数=%ld)",
						BBSName, DBRecCount(BBSDBp));
	else
		strcpy(title, "掲示板管理");
	WinSetWindowText(hwnd, title);
}

/*=======================================================================
 |
 |		掲示板管理ウィンドウ描画処理
 |
 |	VOID	BBSWndPaint(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	BBSWndPaint(HWND hwnd)
{
	HPS	hps;
	RECTL	rcl;
	POINTL	pt;
	USHORT	i, j;
	SHORT	pCurrent;
	SHORT	top, bottom;
	CHAR	strBuf[108], *strBufp;

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
			if (dispTbl[i].flag && (BBSType == 'B' ||
							(i != 3 && i != 4)))
				strcat(strBuf, dispTbl[i].msg);
		}
		GpiSetColor(hps, SYSCLR_WINDOWSTATICTEXT);
		GpiCharStringAt(hps, &pt, (LONG)strlen(strBuf), strBuf);
		GpiSetColor(hps, SYSCLR_WINDOWTEXT);
	}
	pt.y = rcl.yTop - gcyChar * 3;

	if (BBSDBp != NULL) {
		DBLock(BBSDBp);
		if (pTop == 0 || topRecNo == 0 || DBCheckUpdate(BBSDBp)) {
			DBBottom(BBSDBp);
			DBSkip(BBSDBp, -pTop);
			topRecNo = DBRecNo(BBSDBp);
			DBResetUpdate(BBSDBp);
		} else {
			DBSet(BBSDBp, topRecNo);
			if (pTop != pTopSv) {
				DBSkip(BBSDBp, pTopSv - pTop);
				topRecNo = DBRecNo(BBSDBp);
			}
		}
		pTopSv = pTop;

		memset(strBuf, ' ' , sizeof(strBuf));
		for (pCurrent = pTop; ; pCurrent++) {
			if (DBBof(BBSDBp))
				break;

			/* 選択行のレコード番号取得 */
			if (nSelect != 0 && pCurrent == pSelect)
				selectRecNo = DBRecNo(BBSDBp);

			/* 最下端まで表示したら終了 */
			if (pt.y + gcyChar <= bottom || pt.y + gcyChar < 0)
				break;

			if (pt.y < top) {
				DBRead(BBSDBp, &dspBBSBuf);
				strBufp = strBuf;
				for (j = 0; j < sizeof(dispTbl) /
							sizeof(DISP_TBL); j++){
					if (dispTbl[j].flag &&
							(BBSType == 'B' ||
							(j != 3 && j != 4))) {
						strBufp += 2;
						memcpy(strBufp,
							dispTbl[j].ptr,
							dispTbl[j].fieldLeng);
						strBufp += dispTbl[j].length;
					}
				}
				GpiCharStringAt(hps, &pt,
					 (LONG)(strBufp - strBuf), strBuf);
				if (pCurrent >= pSelect &&
						pCurrent < pSelect+nSelect) {
					rcl.yTop = pt.y + gcyChar;
					rcl.yBottom = pt.y;
					WinInvertRect(hps, &rcl);
				}
			}
			pt.y -= gcyChar;
			DBSkip(BBSDBp, -1L);
		}
		DBRelease(BBSDBp);
	}
	WinEndPaint(hps);

	MouseDragCheck(hwnd);
}

/*=======================================================================
 |
 |		掲示板管理ウィンドウ　メニュー初期化処理
 |
 |	VOID	BBSWndInitMenu(hwnd, menuID)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	menuID;		メニューＩＤ
 |
 =======================================================================*/
static	VOID	BBSWndInitMenu(HWND hwnd, USHORT menuID)
{
	USHORT	i;
	BBSMDBF	BBSMBuf;

	switch (menuID) {
	case IDM_BBS_FILE:
		WinSendDlgItemMsg(hwndBBS, FID_MENU, MM_SETITEMATTR,
				MPFROM2SHORT(IDM_BBS_FILE_NEW, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				BBSDBp == NULL ? MIA_DISABLED : 0));
		WinSendDlgItemMsg(hwndBBS, FID_MENU, MM_SETITEMATTR,
				MPFROM2SHORT(IDM_BBS_FILE_UPDATE, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect == 1 ? 0 : MIA_DISABLED));
		WinSendDlgItemMsg(hwndBBS, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(IDM_BBS_FILE_DELETE, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect != 0 ? 0 : MIA_DISABLED));
		WinSendDlgItemMsg(hwndBBS, FID_MENU, MM_SETITEMATTR,
				MPFROM2SHORT(IDM_BBS_FILE_READ, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				nSelect == 1 ? 0 : MIA_DISABLED));
		break;
	case IDM_BBS_DISPLAY:
		for (i = 0; i < sizeof(dispTbl)/sizeof(DISP_TBL); i++) {
			WinSendDlgItemMsg(hwndBBS, FID_MENU,
				MM_SETITEMATTR,
				MPFROM2SHORT(dispTbl[i].menuID, TRUE),
				MPFROM2SHORT(MIA_CHECKED,
				dispTbl[i].flag ? MIA_CHECKED : 0));
		}

		GetBBSM(BBSName, &BBSMBuf);
		WinSendDlgItemMsg(hwndBBS, FID_MENU, MM_SETITEMATTR,
				MPFROM2SHORT(IDM_BBS_DSP_FNAME, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				BBSType == 'B' ? 0 : MIA_DISABLED));
		WinSendDlgItemMsg(hwndBBS, FID_MENU, MM_SETITEMATTR,
				MPFROM2SHORT(IDM_BBS_DSP_SIZE, TRUE),
				MPFROM2SHORT(MIA_DISABLED,
				BBSType == 'B' ? 0 : MIA_DISABLED));
		break;
	}
}

/*=======================================================================
 |
 |		掲示板管理ウィンドウ　コマンド処理
 |
 |	VOID	BBSWndCommand(hwnd, command)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	command;	コマンドＩＤ
 |
 =======================================================================*/
static	VOID	BBSWndCommand(HWND hwnd, USHORT command)
{
	USHORT	i;

	switch (command) {
	case IDM_BBS_FILE_SELECT:
		if (SHORT1FROMMR(WinDlgBox(HWND_DESKTOP, hwnd, BBSSelectDlg,
				0, IDD_BBS_SELECT, NULL)) == DID_OK) {
			SetTitle(hwndBBS);
			pTop = 0;
			nSelect = 0;
			WinInvalidateRect(hwnd, NULL, FALSE);
		}
		if (BBSDBp == NULL)
			WinPostMsg(hwnd, WM_CLOSE, 0, 0);
		break;
	case IDM_BBS_FILE_NEW:
		WinDlgBox(HWND_DESKTOP, hwnd, BBSDlg, 0, IDD_BBS,
							(PVOID)PRM_NEW);
		SetTitle(hwndBBS);
		break;
	case IDM_BBS_FILE_UPDATE:
		WinDlgBox(HWND_DESKTOP, hwnd, BBSDlg, 0, IDD_BBS,
							(PVOID)PRM_UPDATE);
		break;
	case IDM_BBS_FILE_DELETE:
		DeleteBBS(hwnd);
		SetTitle(hwndBBS);
		break;
	case IDM_BBS_FILE_READ:
		GetBBSFile(hwnd);
		break;
	case IDM_BBS_DSP_CREATE:
	case IDM_BBS_DSP_USERID:
	case IDM_BBS_DSP_LEVEL:
	case IDM_BBS_DSP_FNAME:
	case IDM_BBS_DSP_SIZE:
	case IDM_BBS_DSP_TITLE:
		for (i = 0; i < sizeof(dispTbl)/sizeof(DISP_TBL); i++) {
			if (command == dispTbl[i].menuID) {
				dispTbl[i].flag = !dispTbl[i].flag;
				break;
			}
		}
		WinInvalidateRect(hwnd, NULL, FALSE);
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
 |		掲示ファイル取り出し処理
 |
 |	VOID	GetBBSFile(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	GetBBSFile(HWND hwnd)
{
	INPUTFN	inputfn;
	CHAR	fileName[65];
	CHAR	fName[13];
	CHAR	BBSFile[26];
	BBSDBF	recBuf;

	inputfn.title = "取り出し先ファイル名の入力";
	inputfn.bufp = fileName;
	inputfn.bufSize = sizeof(fileName);
	if (SHORT1FROMMR(WinDlgBox(HWND_DESKTOP, hwnd,
			InputFileNameDlg, 0,
			IDD_FILENAME, (PVOID)&inputfn)) != DID_OK)
		return;

	DBLock(BBSDBp);
	DBSet(BBSDBp, selectRecNo);
	DBRead(BBSDBp, &recBuf);
	DBRelease(BBSDBp);
	CopyFromDBF(fName, recBuf.fileName, sizeof(recBuf.fileName));
	sprintf(BBSFile, "%s.BBS\\%s", BBSName, fName);

	FileCopy(hwnd, fileName, BBSFile);
}

/*=======================================================================
 |
 |		掲示板管理ウィンドウ　スクロールコマンド処理
 |
 |	VOID	BBSWndVScroll(hwnd, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
static	VOID	BBSWndVScroll(HWND hwnd, MPARAM mp1, MPARAM mp2)
{
	if (BBSDBp == NULL)
		return;

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
 |		掲示板管理ウィンドウ　スクロール処理
 |
 |	VOID	Scroll(hwnd, n)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		SHORT	n;		スクロール行数
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
		nRec = (SHORT)DBRecCount(BBSDBp);
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
 |		第一マウスボタンクリック処理
 |
 |	VOID	Mouse1Down(hwnd, mp1)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		MPARAM	mp1;		メッセージパラメータ１
 |
 =======================================================================*/
static	VOID	Mouse1Down(HWND hwnd, MPARAM mp1)
{
	RECTL	rcl, rcl2;
	SHORT	selectLine;

	if (BBSDBp == NULL)
		return;

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
				(SHORT)DBRecCount(BBSDBp)) {
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
 |		第一マウスボタン開放処理
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
 |		第二マウスボタンクリック処理
 |
 |	VOID	Mouse2Down(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
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
 |		マウスドラッグチェック処理
 |
 |	VOID	MouseDragCheck(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
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

	nRec = (SHORT)DBRecCount(BBSDBp);
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
 |		キーボードによる行選択処理
 |
 |	VOID	SelectLines(hwnd, n, drag)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		SHORT	n;		－１：上矢印キー
 |					　１：下矢印キー
 |					　０：選択解除
 |		BOOL	drag;		TRUE  : 複数選択
 |					FALSE : 単一選択
 |
 =======================================================================*/
static	VOID	SelectLines(HWND hwnd, SHORT n, BOOL drag)
{
	RECTL	rcl, rcl2;
	SHORT	nRec, pageLine;
	SHORT	oldpSelect2, oldnSelect;

	nRec = (SHORT)DBRecCount(BBSDBp);
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
 |		スクロールバー表示処理
 |
 |	VOID	EnableScrollBar(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	EnableScrollBar(HWND hwnd)
{
	SWP	swp;

	WinQueryWindowPos(hwnd, &swp);
	if (BBSDBp != NULL && (pTop != 0 ||
			swp.cy / gcyChar < (SHORT)DBRecCount(BBSDBp) + 2)) {
		WinSendMsg(hwndSB, SBM_SETSCROLLBAR,
			MPFROMSHORT(pTop),
			MPFROM2SHORT(0, (SHORT)DBRecCount(BBSDBp)-1));
		WinEnableWindow(hwndSB, TRUE);
	} else
		WinEnableWindow(hwndSB, FALSE);
}

/*=======================================================================
 |
 |		掲示板選択ダイアログプロシージャ
 |
 |	MRESULT CALLBACK BBSSelectDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK BBSSelectDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	BBSMDBF	recBuf;
	SHORT	item;
	CHAR	fieldBuf[MAXFIELDL];
	CHAR	msgBuf[80];
	static	CHAR	svBBSName[10];

	switch (msg) {
	case WM_INITDLG:
		strcpy(svBBSName, BBSName);
		WinSendDlgItemMsg(hwnd, IDD_BBS_NAME,
				EM_SETTEXTLIMIT, MPFROMSHORT(8), 0);
		SetBBSList(hwnd);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			WinQueryDlgItemText(hwnd, IDD_BBS_NAME,
						sizeof(BBSName), BBSName);
			CopyToDBF(fieldBuf, BBSName, 8);
			if (!DBSearch(BBSMDBp, fieldBuf, 8, indexBBSM))
				return(0);
			if (BBSDBp != NULL)
				CloseBBSDBF(BBSDBp);
			if ((BBSDBp = OpenBBSDBF(BBSName, FALSE)) == NULL)
				return(0);
			GetBBSM(BBSName, &recBuf);
			BBSType = recBuf.type[0];
			break;
		case DID_CANCEL:
			strcpy(BBSName, svBBSName);
			break;
		case IDD_CMD_NEW:
			if (WinDlgBox(HWND_DESKTOP, hwnd, BBSMDlg, 0,
					IDD_BBSM, (PVOID)PRM_NEW) == DID_OK) {
				WinSendDlgItemMsg(hwnd, IDD_BBS_LIST,
						LM_DELETEALL, 0L, 0L);
				SetBBSList(hwnd);
			}
			return(0);
		case IDD_CMD_CHANGE:
			WinQueryDlgItemText(hwnd, IDD_BBS_NAME,
						sizeof(BBSName), BBSName);
			CopyToDBF(fieldBuf, BBSName, 8);
			if (DBSearch(BBSMDBp, fieldBuf, 8, indexBBSM)) {
				if (WinDlgBox(HWND_DESKTOP, hwnd, BBSMDlg,
						0, IDD_BBSM,
						(PVOID)PRM_UPDATE) == DID_OK) {
					WinSendDlgItemMsg(hwnd,
						IDD_BBS_LIST, LM_DELETEALL,
						0L, 0L);
					SetBBSList(hwnd);
				}
			}
			return(0);
		case IDD_CMD_DELETE:
			WinQueryDlgItemText(hwnd, IDD_BBS_NAME,
						sizeof(BBSName), BBSName);
			CopyToDBF(fieldBuf, BBSName, 8);
			DBLock(BBSMDBp);
			if (DBSearch(BBSMDBp, fieldBuf, 8, indexBBSM)) {
				sprintf(msgBuf, "[%s] を削除しますか？",
								BBSName);
				if (WinMessageBox(HWND_DESKTOP, hwnd,
						msgBuf, "確　認", ID_MB,
						MB_YESNO | MB_ICONQUESTION |
						MB_APPLMODAL) == MBID_YES) {
					DeleteBBSAll();
					DBDelete2(BBSMDBp);
					WinSendDlgItemMsg(hwnd,
						IDD_BBS_LIST, LM_DELETEALL,
						0L, 0L);
					SetBBSList(hwnd);
				}
			}
			DBRelease(BBSMDBp);
			return(0);
		}
		break;
	case WM_CONTROL:
		switch (SHORT1FROMMP(mp1)) {
		case IDD_BBS_LIST:
			switch (SHORT2FROMMP(mp1)) {
			case LN_ENTER:
				WinQueryDlgItemText(hwnd, IDD_BBS_NAME,
						sizeof(BBSName), BBSName);
				if (BBSDBp != NULL)
					CloseBBSDBF(BBSDBp);
				BBSDBp = OpenBBSDBF(BBSName, FALSE);
				GetBBSM(BBSName, &recBuf);
				BBSType = recBuf.type[0];
				WinDismissDlg(hwnd, DID_OK);
				break;
			case LN_SELECT:
				item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd,
						IDD_BBS_LIST,
						LM_QUERYSELECTION, 0L, 0L));
				WinSendDlgItemMsg(hwnd, IDD_BBS_LIST,
						LM_QUERYITEMTEXT,
						MPFROM2SHORT(item,
						sizeof(fieldBuf)), fieldBuf);
				GetBBSM(fieldBuf, &recBuf);
				CopyFromDBF(fieldBuf, recBuf.name,
						sizeof(recBuf.name));
				WinSetDlgItemText(hwnd,
						IDD_BBS_NAME, fieldBuf);
				CopyFromDBF(fieldBuf, recBuf.title,
						sizeof(recBuf.title));
				WinSetDlgItemText(hwnd,
						IDD_BBS_TITLE, fieldBuf);
				CopyFromDBF(fieldBuf, recBuf.date,
						sizeof(recBuf.date));
				WinSetDlgItemText(hwnd,
						IDD_BBS_CREATE, fieldBuf);
				break;
			}
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		掲示板リスト表示処理
 |
 |	VOID	SetBBSList(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	SetBBSList(HWND hwnd)
{
	BBSMDBF	recBuf;
	CHAR	name[10];
	SHORT	i;

	DBLock(BBSMDBp);
	DBTop(BBSMDBp);
	for (i = 0; !DBEof(BBSMDBp); i++) {
		DBRead(BBSMDBp, &recBuf);
		memcpy(name, recBuf.name, 8);
		name[8] = '\0';
		WinSendDlgItemMsg(hwnd, IDD_BBS_LIST,
				LM_INSERTITEM, MPFROMSHORT(i), name);
		DBSkip(BBSMDBp, 1L);
	}
	DBRelease(BBSMDBp);
}

/*=======================================================================
 |
 |		掲示板削除処理
 |
 |	VOID	DeleteBBSAll()
 |
 =======================================================================*/
static	VOID	DeleteBBSAll(VOID)
{
	HDIR	hdir = 0xffff;
	FILEFINDBUF	findBuf;
	USHORT	count = 1;
	CHAR	pathName[26];
	CHAR	dirName[13];

	sprintf(pathName, "%s.BBS\\*.*", BBSName);
	DosFindFirst(pathName, &hdir, FILE_NORMAL, &findBuf,
					sizeof(findBuf), &count, 0);
	while (count != 0) {
		sprintf(pathName, "%s.BBS\\%s", BBSName, findBuf.achName);
		DosDelete(pathName, 0L);
		DosFindNext(hdir, &findBuf, sizeof(findBuf), &count);
	}
	DosFindClose(hdir);

	sprintf(dirName, "%s.BBS", BBSName);
	DosRmDir(dirName, 0L);
}

/*=======================================================================
 |
 |		掲示板登録／変更ダイアログプロシージャ
 |
 |	MRESULT CALLBACK BBSMDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK BBSMDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	CHAR	date[18];
	DATETIME	dateTime;
	CHAR	fieldBuf[MAXFIELDL];
	static	SHORT	writeMode;

	switch (msg) {
	case WM_INITDLG:
		writeMode = SHORT1FROMMP(mp2);
		if (writeMode == PRM_NEW) {
			memset(&entryBBSMBuf, ' ', sizeof(entryBBSMBuf));
			DosGetDateTime(&dateTime);
			sprintf(date, "%2u/%02u/%02u %2u:%02u:%02u",
					dateTime.year % 100,
					dateTime.month,
					dateTime.day,
					dateTime.hours,
					dateTime.minutes,
					dateTime.seconds);
			CopyToDBF(entryBBSMBuf.date, date,
					sizeof(entryBBSMBuf.date));
			entryBBSMBuf.type[0] = 'T';
			entryBBSMBuf.listSeq[0] = 'O';
		} else {
			CopyToDBF(fieldBuf, BBSName, 8);
			DBLock(BBSMDBp);
			DBSearch(BBSMDBp, fieldBuf, 8, indexBBSM);
			DBRead(BBSMDBp, &entryBBSMBuf);
			DBRelease(BBSMDBp);
		}

		SetEntryData(hwnd, entryBBSM,
				sizeof(entryBBSM)/sizeof(ENTRY_TBL));

		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			if (WriteBBSMFile(hwnd, writeMode) == FALSE)
				return(0);
			break;
		case DID_CANCEL:
			break;
		}
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		掲示板管理ファイル書き込み処理
 |
 |	BOOL	WriteBBSMFile(hwnd, writeMode)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		SHORT	writeMode;	０：新規作成　１：変更
 |
 =======================================================================*/
static	BOOL	WriteBBSMFile(HWND hwnd, SHORT writeMode)
{
	CHAR	name[9];
	CHAR	msg[80];
	CHAR	dirName[13], dirName2[13];
	DB	*dbp;
	USHORT	errorCode;
	CHAR	fieldBuf[MAXFIELDL];

	GetEntryData(hwnd, entryBBSM, sizeof(entryBBSM)/sizeof(ENTRY_TBL));
	ToUpperStr(entryBBSMBuf.name, 8);

	CopyFromDBF(name, entryBBSMBuf.name, sizeof(entryBBSMBuf.name));

	DBLock(BBSMDBp);
	if (writeMode == PRM_NEW || strcmp(BBSName, name) != 0) {
		if (DBSearch(BBSMDBp, entryBBSMBuf.name, 8, indexBBSM)) {
			DBRelease(BBSMDBp);
			sprintf(msg, "[%s] はすでに登録済みです。", name);
			WinMessageBox(HWND_DESKTOP, hwnd, msg, NULL, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_BBS_NAME));
			return(FALSE);
		}
	}

	if (writeMode == PRM_NEW) {
		sprintf(dirName, "%s.BBS", name);
		errorCode = DosMkDir(dirName, 0L);
		if (errorCode != 0) {
			DBRelease(BBSMDBp);
			WinMessageBox(HWND_DESKTOP, hwnd,
				"ディレクトリが作成できません。",
				NULL, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
		DBStore(BBSMDBp, &entryBBSMBuf);
		if ((dbp = CreateBBSDBF(name)) != NULL)
			CloseBBSDBF(dbp);
	} else {
		if (strcmp(BBSName, name) != 0) {
			sprintf(dirName, "%s.BBS", name);
			sprintf(dirName2, "%s.BBS", BBSName);
			if (rename(dirName2, dirName) != 0) {
				DBRelease(BBSMDBp);
				WinMessageBox(HWND_DESKTOP, hwnd,
					"ディレクトリ名を変更できません。",
					NULL, ID_MB,
					MB_OK | MB_ICONEXCLAMATION |
					MB_APPLMODAL);
				return(FALSE);
			}
		}
		CopyToDBF(fieldBuf, BBSName, 8);
		DBSearch(BBSMDBp, fieldBuf, 8, indexBBSM);
		DBUpdate(BBSMDBp, &entryBBSMBuf);
	}
	DBRelease(BBSMDBp);

	return(TRUE);
}

/*=======================================================================
 |
 |		掲示ファイル登録／変更ダイアログプロシージャ
 |
 |	MRESULT CALLBACK BBSDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK BBSDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	CHAR	fieldBuf[MAXFIELDL];
	CHAR	fileName[13];
	CHAR	pathName[26];
	CHAR	date[18];
	DATETIME	dateTime;
	static	SHORT	writeMode;

	switch (msg) {
	case WM_INITDLG:
		writeMode = SHORT1FROMMP(mp2);
		if (writeMode == PRM_NEW) {
			memset(&entryBBSBuf, ' ', sizeof(entryBBSBuf));
			CopyToDBF(entryBBSBuf.userID, sv.env.systemID,
					sizeof(entryBBSBuf.userID));
			DosGetDateTime(&dateTime);
			sprintf(date, "%2u/%02u/%02u %2u:%02u:%02u",
					dateTime.year % 100,
					dateTime.month,
					dateTime.day,
					dateTime.hours,
					dateTime.minutes,
					dateTime.seconds);
			CopyToDBF(entryBBSBuf.date, date,
					sizeof(entryBBSBuf.date));
			entryBBSBuf.userLevel[0] = '0';
			MakeBBSFile(BBSName, fileName, pathName);
			CopyToDBF(entryBBSBuf.fileName, fileName,
					sizeof(entryBBSBuf.fileName));
			if (BBSType == 'T') {
				CopyToDBF(entryBBSBuf.name, "            ",
						sizeof(entryBBSBuf.name));
			} else {
				CopyToDBF(entryBBSBuf.name, "************",
						sizeof(entryBBSBuf.name));
			}
		} else {
			DBLock(BBSDBp);
			DBSet(BBSDBp, selectRecNo);
			DBRead(BBSDBp, &entryBBSBuf);
			DBRelease(BBSDBp);
		}

		SetEntryData(hwnd, entryBBS,
				sizeof(entryBBS)/sizeof(ENTRY_TBL));

		if (BBSType == 'T') {
			WinEnableWindow(WinWindowFromID(hwnd, IDD_BBS_TFILE),
					FALSE);
			WinEnableWindow(WinWindowFromID(hwnd, IDD_BBS_FNAME),
					FALSE);
			WinEnableWindow(WinWindowFromID(hwnd, IDD_BBS_FSIZE),
					FALSE);
			WinEnableWindow(WinWindowFromID(hwnd, IDD_BBS_TFSIZE),
					FALSE);
		} else {
			WinEnableWindow(WinWindowFromID(hwnd, IDD_CMD_EDIT),
					FALSE);
		}
		WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_BBS_TITLE));
		CenterDlgBox(hwnd);
		return(MRFROMSHORT(TRUE));
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			if (WriteBBSFile(hwnd, writeMode) == FALSE)
				return(0);
			break;
		case DID_CANCEL:
			if (writeMode == PRM_NEW) {
				CopyFromDBF(fieldBuf, entryBBSBuf.fileName,
						sizeof(entryBBSBuf.fileName));
				sprintf(pathName, "%s.BBS\\%s",
						BBSName, fieldBuf);
				DosDelete(pathName, 0L);
			}
			break;
		case IDD_CMD_COPY:
			CopyBBSFile(hwnd);
			return(0);
		case IDD_CMD_EDIT:
			CopyFromDBF(fieldBuf, entryBBSBuf.fileName,
						sizeof(entryBBSBuf.fileName));
			sprintf(pathName, "%s.BBS\\%s", BBSName, fieldBuf);
			ExecEditor(pathName);
			return(0);
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		掲示物管理ファイル書き込み処理
 |
 |	BOOL	WriteBBSFile(hwnd, writeMode)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		SHORT	writeMode;	０：新規作成　１：変更
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：掲示ファイル無し
 |
 =======================================================================*/
static	BOOL	WriteBBSFile(HWND hwnd, SHORT writeMode)
{
	CHAR	fieldBuf[MAXFIELDL];
	CHAR	pathName[40];
	struct	stat	st;

	GetEntryData(hwnd, entryBBS, sizeof(entryBBS)/sizeof(ENTRY_TBL));

	CopyFromDBF(fieldBuf, entryBBSBuf.fileName,
				sizeof(entryBBSBuf.fileName));
	sprintf(pathName, "%s.BBS\\%s", BBSName, fieldBuf);
	if (stat(pathName, &st) == 0) {
		sprintf(fieldBuf, "%8ld", st.st_size);
		CopyToDBF(entryBBSBuf.size, fieldBuf,
				sizeof(entryBBSBuf.size));
	} else
		return(FALSE);

	DBLock(BBSDBp);
	if (writeMode == PRM_NEW) {
		DBStore(BBSDBp, &entryBBSBuf);
	} else {
		DBSet(BBSDBp, selectRecNo);
		DBUpdate(BBSDBp, &entryBBSBuf);
	}
	DBRelease(BBSDBp);
	nSelect = 0;
	WinInvalidateRect(hwndClient, NULL, FALSE);
	return(TRUE);
}

/*=======================================================================
 |
 |		掲示ファイルコピー処理
 |
 |	VOID	CopyBBSFile(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	CopyBBSFile(HWND hwnd)
{
	CHAR	pathName[MAXPATHL];
	CHAR	fileName[MAXPATHL];
	CHAR	BBSFile[26];
	CHAR	fileSize[9];
	struct	stat	st;

	if (SelectFile(hwnd, "掲示ファイルのコピー", pathName) == FALSE)
		return;
	CopyFromDBF(fileName, entryBBSBuf.fileName,
				sizeof(entryBBSBuf.fileName));
	sprintf(BBSFile, "%s.BBS\\%s", BBSName, fileName);

	if (FileCopy(hwnd, BBSFile, pathName)) {
		stat(BBSFile, &st);
		WinSetDlgItemText(hwnd, IDD_BBS_FNAME,
					strrchr(pathName, '\\') + 1);
		sprintf(fileSize, "%8ld", st.st_size);
		WinSetDlgItemText(hwnd, IDD_BBS_FSIZE, fileSize);
	}
}

/*=======================================================================
 |
 |		掲示板管理情報読み込み処理
 |
 |	BOOL	GetBBSM(name, recBuf)
 |
 |		CHAR	*name;		掲示板名
 |		BBSMDBF	*recBuf;	掲示板管理情報バッファ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：掲示板無し
 |
 =======================================================================*/
BOOL	GetBBSM(CHAR *name, BBSMDBF *recBuf)
{
	CHAR	nameBuf[8];

	CopyToDBF(nameBuf, name, 8);
	DBLock(BBSMDBp);
	if (!DBSearch(BBSMDBp, nameBuf, 8, indexBBSM)) {
		DBRelease(BBSMDBp);
		return(FALSE);
	}
	DBRead(BBSMDBp, recBuf);
	DBRelease(BBSMDBp);

	return(TRUE);
}

/*=======================================================================
 |
 |		掲示板管理ファイルオープン処理
 |
 |	BOOL	OpenBBSMDBF(fIndexCreate)
 |
 |		BOOL	fIndexCreate;	インデックスファイル作成フラグ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：オープン失敗（中止）
 |
 =======================================================================*/
BOOL	OpenBBSMDBF(BOOL fIndexCreate)
{
	if ((BBSMDBp = DBOpen(fnBBSMDBF)) == NULL) {
		if (WinMessageBox(HWND_DESKTOP, hwndMain,
				"新規に作成しますか？",
				"掲示板管理ファイルがありません！", ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
				) == MBID_YES)
			return(CreateBBSMDBF());
		else
			return(FALSE);
	} else {
		if (DBCheckSize(BBSMDBp))
			fIndexCreate = TRUE;
	}

	if (UseBBSMIndex(fIndexCreate) == FALSE) {
		DBClose(BBSMDBp);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		掲示板管理ファイル新規作成処理
 |
 |	BOOL	CreateBBSMDBF()
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：作成失敗
 |
 =======================================================================*/
static	BOOL	CreateBBSMDBF(VOID)
{
	static	DBF_I	fieldData[] = {
		{"識別名"    , 'C',  8, 0},
		{"タイトル"  , 'C', 20, 0},
		{"日付"      , 'C', 17, 0},
		{"タイプ"    , 'C',  1, 0},
		{"一覧順序"  , 'C',  1, 0},
		{"書込み許可", 'C',  1, 0}
	};

	if ((BBSMDBp = DBCreate(fnBBSMDBF, fieldData,
				sizeof(fieldData)/sizeof(DBF_I))) == NULL) {
		WinMessageBox(HWND_DESKTOP, hwndMain,
				"管理ファイルが作成できませんでした。",
				"掲示板管理ファイルアクセスエラー！",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		return(FALSE);
	}

	if (UseBBSMIndex(TRUE) == FALSE) {
		DBClose(BBSMDBp);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		掲示板管理ファイルインデックスオープン／作成
 |
 |	BOOL	UseBBSMIndex(fCreate)
 |
 |		BOOL	fCreate;	作成フラグ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：作成失敗
 |
 =======================================================================*/
static	BOOL	UseBBSMIndex(BOOL fCreate)
{
	if (fCreate || (indexBBSM = DBIndex(BBSMDBp, fnBBSMNDX)) == 0) {
		if ((indexBBSM = DBIdxCreate(BBSMDBp, fnBBSMNDX,
						"識別名", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"インデックスファイルが作成できませんでした。",
				"掲示板管理ファイルアクセスエラー！",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}
	return(TRUE);
}

/*=======================================================================
 |
 |		掲示物管理ファイルオープン処理
 |
 |	DB	*OpenBBSDBF(CHAR *name, fIndexCreate)
 |
 |		CHAR	*name;		掲示板名
 |		BOOL	fIndexCreate;	インデックスファイル作成フラグ
 |
 |		DB	*返値		掲示物管理ファイルポインタ
 |					（オープン失敗時はＮＵＬＬ）
 |
 =======================================================================*/
DB	*OpenBBSDBF(CHAR *name, BOOL fIndexCreate)
{
	CHAR	fileName[26];
	DB	*dbp;
	OPENBBS	*BBSp;

	for (BBSp = openBBSp; BBSp != NULL; BBSp = BBSp->chain) {
		if (strcmp(name, BBSp->name) == 0) {
			BBSp->count++;
			return(BBSp->dbp);
		}
	}

	sprintf(fileName, "%s.BBS\\%s", name, fnBBSDBF);
	if ((dbp = DBOpen(fileName)) == NULL)
		return(NULL);

	if (DBCheckSize(dbp))
		fIndexCreate = TRUE;

	if (UseBBSIndex(name, dbp, fIndexCreate) == FALSE) {
		DBClose(dbp);
		return(NULL);
	}

	BBSp = malloc(sizeof(OPENBBS));
	BBSp->dbp = dbp;
	strcpy(BBSp->name, name);
	BBSp->count = 1;
	BBSp->chain = openBBSp;
	openBBSp = BBSp;

	return(dbp);
}

/*=======================================================================
 |
 |		掲示物管理ファイル新規作成処理
 |
 |	DB	*CreateBBSDBF(CHAR *name)
 |
 |		CHAR	*name;		掲示板名
 |
 |		DB	*返値		掲示物管理ファイルポインタ
 |					（作成失敗時はＮＵＬＬ）
 |
 =======================================================================*/
static	DB	*CreateBBSDBF(CHAR *name)
{
	static	DBF_I	fieldData[] = {
		{"ユーザＩＤ", 'C',  8, 0},
		{"名前",       'C', 12, 0},
		{"日付",       'C', 17, 0},
		{"タイトル",   'C', 40, 0},
		{"許可レベル", 'C',  1, 0},
		{"サイズ",     'C',  8, 0},
		{"ファイル名", 'C', 12, 0}
	};
	DB	*dbp;
	OPENBBS	*BBSp;
	CHAR	fileName[26];

	sprintf(fileName, "%s.BBS\\%s", name, fnBBSDBF);
	if ((dbp = DBCreate(fileName, fieldData,
				sizeof(fieldData)/sizeof(DBF_I))) == NULL)
		return(NULL);

	if (UseBBSIndex(name, dbp, TRUE) == FALSE) {
		DBClose(dbp);
		return(NULL);
	}

	BBSp = malloc(sizeof(OPENBBS));
	BBSp->dbp = dbp;
	strcpy(BBSp->name, name);
	BBSp->count = 1;
	BBSp->chain = openBBSp;
	openBBSp = BBSp;

	return(dbp);
}

/*=======================================================================
 |
 |		掲示物管理ファイルインデックスオープン／作成
 |
 |	BOOL	UseBBSIndex(CHAR *name, DB *dbp, fCreate)
 |
 |		CHAR	*name;		掲示板名
 |		DB	*dbp		掲示物管理ファイルポインタ
 |		BOOL	fCreate;	作成フラグ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：作成失敗
 |
 =======================================================================*/
static	BOOL	UseBBSIndex(CHAR *name, DB *dbp, BOOL fCreate)
{
	CHAR	fileName[26];

	sprintf(fileName, "%s.BBS\\%s", name, fnBBSNDX);

	if (fCreate || DBIndex(dbp, fileName) == 0) {
		if (DBIdxCreate(dbp, fileName, "日付", 0) == 0)
			return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		掲示物管理ファイルクローズ処理
 |
 |	VOID	CloseBBSDBF(DB *dbp)
 |
 |		DB	*dbp		掲示物管理ファイルポインタ
 |
 =======================================================================*/
VOID	CloseBBSDBF(DB *dbp)
{
	OPENBBS	**BBSpp, *chain;

	for (BBSpp = &openBBSp; *BBSpp != 0; BBSpp = &(*BBSpp)->chain) {
		if ((*BBSpp)->dbp == dbp) {
			if (--(*BBSpp)->count == 0) {
				DBClose((*BBSpp)->dbp);
				chain = (*BBSpp)->chain;
				free(*BBSpp);
				*BBSpp = chain;
			}
			break;
		}
	}
}

/*=======================================================================
 |
 |		掲示物削除処理
 |
 |	VOID	DeleteBBS(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	DeleteBBS(HWND hwnd)
{
	SHORT	i;
	SHORT	nRec;

	if (WinMessageBox(HWND_DESKTOP, hwnd,
			"指定したファイルを削除しますか？",
			"確　認", ID_MB,
			MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
			) == MBID_NO)
		return;

	SetPointer(SPTR_WAIT);

	DBLock(BBSDBp);
	DBSet(BBSDBp, selectRecNo);
	if (nSelect < (SHORT)DBRecCount(BBSDBp) / 5) {
		for (i = 0; i < nSelect; i++) {
			if (DBBof(BBSDBp))
				break;
			DeleteBBSFile();
			DBDelete4(BBSDBp);
		}
	} else {
		for (i = 0; i < nSelect; i++) {
			if (DBBof(BBSDBp))
				break;
			DeleteBBSFile();
			DBDelete(BBSDBp);
			DBSkip(BBSDBp, -1L);
		}
		DBPack(BBSDBp);
	}
	DBRelease(BBSDBp);

	nRec = (SHORT)DBRecCount(BBSDBp);
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
 |		掲示ファイル削除処理
 |
 |	VOID	DeleteBBSFile()
 |
 =======================================================================*/
static	VOID	DeleteBBSFile(VOID)
{
	BBSDBF	recBuf;
	CHAR	fileName[13], pathName[26];

	DBRead(BBSDBp, &recBuf);
	CopyFromDBF(fileName, recBuf.fileName, sizeof(recBuf.fileName));
	sprintf(pathName, "%s.BBS\\%s", BBSName, fileName);
	DosDelete(pathName, 0L);
}
/*=======================================================================
 |
 |		掲示ファイル作成処理
 |
 |	VOID	MakeBBSFile(name, fileName, pathName)
 |
 |		CHAR	*name;		掲示板識別名
 |		CHAR	*fileName;	作成したファイル名
 |		CHAR	*pathName;	作成したパス名
 |
 =======================================================================*/
VOID	MakeBBSFile(CHAR *name, CHAR *fileName, CHAR *pathName)
{
	FILE	*fp;
	CHAR	dirName[13];

	sprintf(dirName, "%s.BBS", name);
	if ((fp = MakeNewFile(dirName, fileName, pathName, "w")) != NULL)
		fclose(fp);
}
