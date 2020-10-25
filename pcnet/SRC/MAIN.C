/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: メイン処理				*
 *		ファイル名	: main.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>
#include <io.h>

#define	MAX_RECORD	9999

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	BOOL	Initialize(VOID);
static	BOOL	CheckDiskSpace(VOID);
static	VOID	RegisterSwitchEntry(HWND);
static	VOID	GetDisplayValues(VOID);
static	BOOL	CreateLogDBF(VOID);
static	BOOL	UseLogIndex(BOOL);
static	VOID	MainWndCreate(HWND);
static	VOID	MainWndPaint(HWND);
static	VOID	MainWndInitMenu(HWND, USHORT);
static	VOID	MainWndCommand(HWND, USHORT);
static	VOID	ReadSelectData(LOGDATA *);
static	VOID	DeleteLogFile(HWND);
static	VOID	DeleteLog(HWND);
static	VOID	DeleteSaveFile(BOOL);
static	BOOL	PassWordCheck(HWND);
static	VOID	MainWndLogin(HWND, LOGIN *);
static	VOID	MainWndLogout(HWND, LOGOUT *);
static	VOID	MainWndVScroll(HWND, MPARAM, MPARAM);
static	VOID	Scroll(HWND, SHORT);
static	VOID	MainWndTimer(HWND, USHORT);
static	VOID	MainWndClose(VOID);
static	VOID	Mouse1Down(HWND, MPARAM);
static	VOID	Mouse1Up(VOID);
static	VOID	Mouse2Down(HWND);
static	VOID	MouseDragCheck(HWND);
static	VOID	SelectLines(HWND, SHORT, BOOL);
static	VOID	EnableScrollBar(HWND);
static	VOID	ISAMErrorProc(int);
MRESULT CALLBACK MainWindowProc(HWND, USHORT, MPARAM, MPARAM);
MRESULT CALLBACK PassWordDlg(HWND, USHORT, MPARAM, MPARAM);
MRESULT CALLBACK AboutDlg(HWND, USHORT, MPARAM, MPARAM);
static	VOID	TerminateHelp(HWND);
static	VOID	InitHelp(HWND, HAB);
static	VOID	SendHelpMsg(USHORT);
static	VOID	AssociateHelp(HWND);
static	VOID	DspHelpErr(CHAR *);

/*--------------------------------------*/
/*		内部変数		*/
/*--------------------------------------*/
static	HMQ	hmq;		/* メッセージキューハンドル */
static	QMSG	qmsg;		/* メッセージ */
static	ULONG	flStyle =	/* フレームウィンドウのスタイル */
	FCF_ACCELTABLE | FCF_TITLEBAR | FCF_SYSMENU | FCF_SIZEBORDER |
	FCF_MENU | FCF_MINMAX | FCF_ICON | FCF_VERTSCROLL;
static	HWND	hwndClient;	/* クライアントウィンドウハンドル */
static	HWND	hwndSB;		/* スクロールバーウィンドウハンドル */
static	SHORT	pTop;		/* 通信履歴表示先頭ポインタ */
static	SHORT	pTopSv;		/* 前表示先頭レコードポインタ */
static	LONG	topRecNo;	/* 表示先頭物理レコード番号 */
static	SHORT	pSelect;	/* 通信履歴選択位置ポインタ */
static	SHORT	pSelect1;	/* 通信履歴選択開始位置ポインタ */
static	SHORT	pSelect2;	/* 通信履歴選択終了位置ポインタ */
static	SHORT	nSelect;	/* 通信履歴選択数 */
static	BOOL	selectFlag;	/* 通信履歴選択中フラグ */
static	LONG	selectRecNo;	/* 選択物理レコード番号 */
static	BOOL	opt_m;		/* メンテナンスモードフラグ */
static	SHORT	yMousePos;	/* マウスＹ座標 */
static	HWND hwndHelpInstance;

/*=======================================================================
 |
 |		メイン関数
 |
 |	void	main(argc, argv)
 |
 |		int	argc;		コマンドパラメータ数
 |		char	*argv[];	コマンドパラメータ
 |
 =======================================================================*/
void	main(int argc, char *argv[])
{
	HSYSSEM	hSem;

	/* コマンドパラメータチェック */
	while (--argc) {
		argv++;
		if (strcmp(*argv, "/M") == 0 || strcmp(*argv, "/m") == 0)
			opt_m = TRUE;	/* メンテナンスモード */
		else
			exit(1);
	}

	/* ＰＭの初期化 */
	if ((hab = WinInitialize(0)) == NULL)
		FatalError(ER_WinInitialine);

	/* メッセージキューの作成 */
	if ((hmq = WinCreateMsgQueue(hab, DEFAULT_QUEUE_SIZE)) == NULL)
		FatalError(ER_WinCreateMsgQueue);

	/* 画面情報の取得 */
	GetDisplayValues();

	if (DosCreateSem(CSEM_PRIVATE, &hSem, "\\sem\\pc-net.sem") != 0) {
		/* 既にＹ－ＮＥＴが起動されている */
		WinMessageBox(HWND_DESKTOP, HWND_DESKTOP,
			"すでに起動されています。",
			systemName,
			ID_MB,
			MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		WinPostMsg(NULL, WM_QUIT, 0, 0);
	} else if (opt_m) {
		/* メンテナンスモード */
		hwndMain = WinLoadDlg(HWND_DESKTOP, HWND_DESKTOP,
					 MaintenanceDlg, 0, IDD_MAINTE, NULL);
	} else {
		/* 通常モード */
		if (Initialize() == FALSE)
			WinPostMsg(NULL, WM_QUIT, 0, 0);
	}

	InitHelp(hwndMain, hab);

	/* メッセージループ */
	while (WinGetMsg(hab, (PQMSG)&qmsg, (HWND)NULL, 0, 0))
		WinDispatchMsg(hab, (PQMSG)&qmsg);

	/* 終了処理 */
	TerminateHelp(hwndMain);
	if (hwndMain != NULL)
		WinDestroyWindow(hwndMain);
	WinDestroyMsgQueue(hmq);
	WinTerminate(hab);
	Terminate();

	DosExit(EXIT_PROCESS, 0);
}

/*=======================================================================
 |
 |		初期化処理
 |
 |	BOOL	Initialize()
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：中止
 |
 =======================================================================*/
static	BOOL	Initialize(VOID)
{
	static	VOID (FAR *thread[4])() = {
		Ch0Thread, Ch1Thread, Ch2Thread, Ch3Thread
	};
	PBYTE	stackPtr;
	SHORT	i;
	FILE	*fp;
	BOOL	fIndexCreate;

	/* メインウィンドウクラスの登録 */
	if (!WinRegisterClass(hab, (PCH)szMainClassName,
					(PFNWP)MainWindowProc, 0, 0))
		FatalError(ER_WinRegisterClass);

	/* ユーザ管理ウィンドウクラスの登録 */
	if (!WinRegisterClass(hab, (PCH)szUserClassName,
					(PFNWP)UserWindowProc, 0, 0))
		FatalError(ER_WinRegisterClass);

	/* 掲示板管理ウィンドウクラスの登録 */
	if (!WinRegisterClass(hab, (PCH)szBBSClassName,
					(PFNWP)BBSWindowProc, 0, 0))
		FatalError(ER_WinRegisterClass);

	/* メール管理ウィンドウクラスの登録 */
	if (!WinRegisterClass(hab, (PCH)szMailClassName,
					(PFNWP)MailWindowProc, 0, 0))
		FatalError(ER_WinRegisterClass);

	/* モニターウィンドウクラスの登録 */
	if (!WinRegisterClass(hab, (PCH)szMonitorClassName,
		(PFNWP)MonitorWindowProc, CS_SIZEREDRAW, sizeof(ULONG)))
		FatalError(ER_WinRegisterClass);

	/* ターミナルウィンドウクラスの登録 */
	if (!WinRegisterClass(hab, (PCH)szTerminalClassName,
			(PFNWP)TermWindowProc, CS_SIZEREDRAW, sizeof(ULONG)))
		FatalError(ER_WinRegisterClass);

	/* ＩＳＡＭライブラリエラーハンドラ設定 */
	DBErrHandler(ISAMErrorProc);

	/* 環境設定ファイルの読み込み */
	ReadEnvData();

	/* 残りディスク容量チェック */
	if (CheckDiskSpace() == FALSE)
		return(FALSE);

	/* 異常終了チェック */
	if (access(fnFCHECK, 0) == 0) {
		fIndexCreate = TRUE;
	} else {
		fIndexCreate = FALSE;
		if ((fp = fopen(fnFCHECK, "w")) != NULL)
			fclose(fp);
	}

	/* 操作定義ファイルの読み込み */
	if (ReadOperationFile() == FALSE)
		return(FALSE);

	/* 通信履歴ファイルのオープン */
	if (OpenLogDBF(fIndexCreate) == FALSE)
		return(FALSE);

	/* ユーザ管理ファイルのオープン */
	if (OpenUserDBF(fIndexCreate) == FALSE)
		return(FALSE);

	/* 販売情報ファイルのオープン */
	if (OpenSalesDBF(fIndexCreate) == FALSE)
		return(FALSE);

	/* 掲示板管理ファイルのオープン */
	if (OpenBBSMDBF(fIndexCreate) == FALSE)
		return(FALSE);

	/* メール管理ファイルのオープン */
	if (OpenMailDBF(fIndexCreate) == FALSE)
		return(FALSE);

	/* メインウィンドウの作成 */
	if (!(hwndMain = WinCreateStdWindow(HWND_DESKTOP, WS_VISIBLE,
			&flStyle, szMainClassName, systemName, 0, 
			0, ID_RESOURCE_MAIN, &hwndClient)))
		FatalError(ER_WinCreateStdWindow);

	/* スクロールバーウィンドウハンドルの取得 */
	hwndSB = WinWindowFromID(hwndMain, FID_VERTSCROLL);

	/* スイッチエントリへの追加 */
	RegisterSwitchEntry(hwndMain);

	/* 各スレッドの起動 */
	for (i = 0; i < 4; i++) {
		/* スタックエリア確保 */
		if ((stackPtr = malloc(STACKSIZE)) == NULL)
			FatalError(ER_malloc);

		stackPtr += STACKSIZE;

		/* スレッドの起動 */
		if (DosCreateThread(thread[i],
					&threadID[i], stackPtr) != 0)
			FatalError(ER_DosCreateThread);

		/* プライオリティーの設定 */
		DosSetPrty(PRTYS_THREAD, PRTYC_REGULAR, 10, threadID[i]);
	}

	/* メール受信チェック */
	CheckReceiveMail();

	/* ファイル強制書き込みタイマーセット */
	WinStartTimer(hab, hwndClient, TMID_FLUSH, (USHORT)60000);

	return(TRUE);
}

/*=======================================================================
 |
 |		残りディスク容量チェック
 |
 |	BOOL	CheckDiskSpace()
 |
 |		BOOL	返値		TRUE ：残りあり
 |					FALSE：残りなし
 |
 =======================================================================*/
static	BOOL	CheckDiskSpace(VOID)
{
	FSALLOCATE	fsAllocate;

	DosQFSInfo(0, 1, (PBYTE)&fsAllocate, sizeof(fsAllocate));

	if (fsAllocate.cSectorUnit * fsAllocate.cbSector *
					fsAllocate.cUnitAvail < 1024L*1024L) {
		if (WinMessageBox(HWND_DESKTOP, hwndMain,
				"続行しますか？",
				"ディスクの残り容量が不足しています。", ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
				) != MBID_YES) {
			return(FALSE);
		}
	}
	return(TRUE);
}

/*=======================================================================
 |
 |		スイッチエントリへの追加
 |
 |	VOID	RegisterSwitchEntry(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	RegisterSwitchEntry(HWND hwnd)
{
	PID	pid;
	TID	tid;
	SWCNTRL	swc;

	WinQueryWindowProcess(hwnd, (PPID)&pid, (PTID)&tid);
	WinQueryWindowText(hwnd, MAXNAMEL, (PSZ)swc.szSwtitle);
	swc.hwnd = hwnd;
	swc.hwndIcon = (ULONG)NULL;
	swc.hprog = (HPROGRAM)NULL;
	swc.idProcess = pid;
	swc.idSession = 0;
	swc.uchVisibility = SWL_VISIBLE;
	swc.fbJump = SWL_JUMPABLE;
	WinAddSwitchEntry((PSWCNTRL)&swc);
}

/*=======================================================================
 |
 |		画面情報取得処理
 |
 |	VOID	GetDisplayValues()
 |
 =======================================================================*/
static	VOID	GetDisplayValues(VOID)
{
	HPS	hps;
	FONTMETRICS	fm;

	/* 画面の大きさ */
	cxScreen = (SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CXSCREEN);
	cyScreen = (SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CYSCREEN);

	hps = WinGetPS(HWND_DESKTOP);
	GpiQueryFontMetrics(hps, (ULONG)sizeof(FONTMETRICS), &fm);
	WinReleasePS(hps);

	/* 文字の高さ取り出し*/
	gcyChar = (USHORT)(fm.lMaxBaselineExt + fm.lExternalLeading);

	/* 平均文字幅取り出し */
	gcxAveChar = (USHORT)fm.lAveCharWidth;

	/* 最大文字幅取り出し */
	gcxMaxChar = (USHORT)fm.lMaxCharInc;
}

/*=======================================================================
 |
 |		通信履歴ファイルオープン
 |
 |	BOOL	OpenLogDBF(fIndexCreate)
 |
 |		BOOL	fIndexCreate;	インデックスファイル作成フラグ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：オープン失敗
 |
 =======================================================================*/
BOOL	OpenLogDBF(BOOL fIndexCreate)
{
	if ((logDBp = DBOpen(fnLOGDBF)) == NULL) {
		if (WinMessageBox(HWND_DESKTOP, hwndMain,
				"新規に作成しますか？",
				"通信履歴ファイルがありません！", ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
				) == MBID_YES) {
			DosMkDir(dnLOGFILE, 0L);
			return(CreateLogDBF());	/* 新規作成 */
		} else
			return(FALSE);
	} else {
		if (DBCheckSize(logDBp))
			fIndexCreate = TRUE;
	}

	if (UseLogIndex(fIndexCreate) == FALSE) {
		DBClose(logDBp);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		通信履歴ファイル新規作成
 |
 |	BOOL	CreateLogDBF()
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：作成失敗
 |
 =======================================================================*/
static	BOOL	CreateLogDBF(VOID)
{
	static	DBF_I	fieldData[] = { /* 通信履歴ファイルフィールドデータ */
		{"チャネルNo", 'C',  1, 0},
		{"ユーザＩＤ", 'C',  8, 0},
		{"開始日付",   'C',  8, 0},
		{"開始時刻",   'C',  8, 0},
		{"終了時刻",   'C',  8, 0},
		{"使用時間",   'C',  8, 0},
		{"ファイル名", 'C', 12, 0}
	};

	if ((logDBp = DBCreate(fnLOGDBF, fieldData,
				sizeof(fieldData)/sizeof(DBF_I))) == NULL) {
		WinMessageBox(HWND_DESKTOP, hwndMain,
				"通信履歴ファイルがオープンできませんでした。",
				"ファイルアクセスエラー！",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		return(FALSE);
	}

	if (UseLogIndex(TRUE) == FALSE) {
		DBClose(logDBp);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		インデックスファイルオープン／作成
 |
 |	BOOL	UseLogIndex(fCreate)
 |
 |		BOOL	fCreate;	作成フラグ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：作成失敗
 |
 =======================================================================*/
static	BOOL	UseLogIndex(BOOL fCreate)
{
	if (fCreate || DBIndex(logDBp, fnLOGNDX) == 0) {
		if (DBIdxCreate(logDBp, fnLOGNDX,
					"開始日付+開始時刻", 0) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"インデックスファイルが作成できませんでした。",
				"通信履歴ファイルアクセスエラー！",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		メインウィンドウプロシージャ
 |
 |	MRESULT CALLBACK MainWindowProc(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK MainWindowProc(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	static	BOOL	mailFlag;

	switch (msg) {
	case WM_CREATE:
		MainWndCreate(hwnd);
		return(FALSE);
	case WM_PAINT:
		MainWndPaint(hwnd);
		return(0);
	case WM_INITMENU:
		MainWndInitMenu(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_COMMAND:
		MainWndCommand(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_SIZE:
		EnableScrollBar(hwnd);
		break;
	case WM_VSCROLL:
		MainWndVScroll(hwnd, mp1, mp2);
		return(0);
	case WM_BUTTON1DOWN:
		Mouse1Down(hwnd, mp1);
		break;
	case WM_BUTTON1UP:
		Mouse1Up();
		break;
	case WM_BUTTON1DBLCLK:
		if (nSelect == 1)
			WinPostMsg(hwnd, WM_COMMAND,
					MPFROMSHORT(IDM_MAIN_FILE_LOGDISP), 0);
		break;
	case WM_BUTTON2DOWN:
		Mouse2Down(hwnd);
		break;
	case WM_MOUSEMOVE:
		yMousePos = SHORT2FROMMP(mp1);
		MouseDragCheck(hwnd);
		break;
	case WM_TIMER:
		MainWndTimer(hwnd, SHORT1FROMMP(mp1));
		return(0);
	case WM_CLOSE:
		if (PassWordCheck(hwnd))	/* 管理者パスワードチェック */
			MainWndClose();
		return(0);
	case WM_USER_LOGIN:		/* ログイン通知 */
		MainWndLogin(hwnd, (LOGIN *)mp1);
		return(0);
	case WM_USER_LOGOUT:		/* ログアウト通知 */
		MainWndLogout(hwnd, (LOGOUT *)mp1);
		return(0);
	case WM_USER_MSGBOX:		/* メッセージボックスオープン */
		WinMessageBox(HWND_DESKTOP, hwnd, mp1, mp2,
						ID_MB, MB_NOICON | MB_OK);
		return(0);
	case WM_USER_MAIL:		/* メール受信通知 */
		WinAlxxx(HWND_DESKTOP, WA_NOTE);
		if (mailFlag == FALSE) {
			mailFlag = TRUE;
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"メールが到着しています。",
				"確　認", ID_MB,
				MB_OK | MB_ICONASTERISK | MB_APPLMODAL);
			mailFlag = FALSE;
		}
		return(0);
	case HM_QUERY_KEYS_HELP:
		return(MRFROMSHORT(KEYS_HELP_HP));
	}

	return(WinDefWindowProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		メインウィンドウ作成時処理
 |
 |	VOID	MainWndCreate(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	MainWndCreate(HWND hwnd)
{
	HWND	hwndFrame;

	/* ウィンドウの位置と大きさの設定 */
	hwndFrame = WinQueryWindow(hwnd, QW_PARENT, FALSE);
	SetWindowPos(hwndFrame, 67, 12);

	pTop = 0;		/* 通信履歴表示先頭ポインタ初期化 */
	nSelect = 0;		/* 通信履歴選択数初期化 */
}

/*=======================================================================
 |
 |		メインウィンドウ描画処理
 |
 |	VOID	MainWndPaint(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	MainWndPaint(HWND hwnd)
{
	static	CHAR	msg[] =
	"  Ｎｏ. ＣＨ  ユーザＩＤ  開始日付  開始時刻  終了時刻  通信時間";
	HPS	hps;
	RECTL	rcl;
	POINTL	pt;
	SHORT	pCurrent;
	SHORT	top, bottom;
	SHORT	nRec;
	LOGDATA	logBuf;
	CHAR	strBuf[80];

	/* スクロールバー表示 */
	EnableScrollBar(hwnd);

	hps = WinBeginPaint(hwnd, NULL, &rcl);
	WinFillRect(hps, &rcl, SYSCLR_WINDOW);
	top = (SHORT)rcl.yTop;
	bottom = (SHORT)rcl.yBottom;

	WinQueryWindowRect(hwnd, &rcl);
	pt.x = 0;
	pt.y = rcl.yTop - gcyChar - gcyChar / 2;
	if (pt.y < top) {
		GpiSetColor(hps, SYSCLR_WINDOWSTATICTEXT);
		GpiCharStringAt(hps, &pt, (LONG)strlen(msg), msg);
		GpiSetColor(hps, SYSCLR_WINDOWTEXT);
	}
	pt.y = rcl.yTop - gcyChar * 3;

	/* 通信履歴の表示 */
	if (pTop == 0 || topRecNo == 0 || DBCheckUpdate(logDBp)) {
		DBBottom(logDBp);
		DBSkip(logDBp, -pTop);
		topRecNo = DBRecNo(logDBp);
		DBResetUpdate(logDBp);
	} else {
		DBSet(logDBp, topRecNo);
		if (pTop != pTopSv) {
			DBSkip(logDBp, pTopSv - pTop);
			topRecNo = DBRecNo(logDBp);
		}
	}
	pTopSv = pTop;

	nRec = (SHORT)DBRecCount(logDBp);
	for (pCurrent = pTop; ;pCurrent++) {
		if (DBBof(logDBp))	/* ファイルの先頭なら終了 */
			break;

		/* 選択行のレコード番号取得 */
		if (nSelect != 0 && pCurrent == pSelect)
			selectRecNo = DBRecNo(logDBp);

		/* 最下端まで表示したら終了 */
		if (pt.y + gcyChar <= bottom || pt.y + gcyChar < 0)
			break;

		/* 通信履歴データの表示 */
		if (pt.y < top) {
			DBRead(logDBp, &logBuf);
			sprintf(strBuf,
			"  %4d   CH%c   %.8s   %.8s  %.8s  %.8s  %.8s %c",
				nRec - pCurrent,
				logBuf.chNo[0],
				logBuf.userID,
				logBuf.loginDate,
				logBuf.loginTime,
				logBuf.logoutTime,
				logBuf.usedTime,
				logBuf.fileName[0] != ' ' ? '*' : ' ');
			GpiCharStringAt(hps, &pt, (LONG)strlen(strBuf),
								 strBuf);

			/* 選択項目の反転 */
			if (pCurrent >= pSelect && pCurrent < pSelect+nSelect){
				rcl.yTop = pt.y + gcyChar;
				rcl.yBottom = pt.y;
				WinInvertRect(hps, &rcl);
			}
		}

		pt.y -= gcyChar;	/* 表示位置更新 */

		DBSkip(logDBp, -1L);	/* 次レコード */
	}

	WinEndPaint(hps);

	MouseDragCheck(hwnd);		/* マウスドラッグチェック */
}

/*=======================================================================
 |
 |		メインウィンドウ　メニュー初期化処理
 |
 |	VOID	MainWndInitMenu(hwnd, menuID)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	menuID;		メニューＩＤ
 |
 =======================================================================*/
static	VOID	MainWndInitMenu(HWND hwnd, USHORT menuID)
{
	static	USHORT	monitorID[3] = {
		IDM_MAIN_MON_CH1, IDM_MAIN_MON_CH2, IDM_MAIN_MON_CH3
	};
	static	USHORT	terminalID[4] = {
		IDM_MAIN_TERM_CH0, IDM_MAIN_TERM_CH1,
		IDM_MAIN_TERM_CH2, IDM_MAIN_TERM_CH3
	};
	SHORT	i;
	USHORT	chNo;
	LOGDATA	logBuf;
	BOOL	logFileFlag;

	switch (menuID) {
	case IDM_MAIN_FILE:
		WinSendDlgItemMsg(hwndMain, FID_MENU, MM_SETITEMATTR,
			MPFROM2SHORT(IDM_MAIN_FILE_USERINF, TRUE),
			MPFROM2SHORT(MIA_DISABLED,
			(nSelect == 1 ? 0 : MIA_DISABLED)));

		logFileFlag = FALSE;
		if (nSelect == 1) {
			ReadSelectData(&logBuf);
			if (logBuf.fileName[0] != ' ')
				logFileFlag = TRUE;
		}
		WinSendDlgItemMsg(hwndMain, FID_MENU, MM_SETITEMATTR,
			MPFROM2SHORT(IDM_MAIN_FILE_LOGDISP, TRUE),
			MPFROM2SHORT(MIA_DISABLED,
			(logFileFlag ? 0 : MIA_DISABLED)));

		WinSendDlgItemMsg(hwndMain, FID_MENU, MM_SETITEMATTR,
			MPFROM2SHORT(IDM_MAIN_FILE_LOGFDEL, TRUE),
			MPFROM2SHORT(MIA_DISABLED,
			(nSelect != 0 ? 0 : MIA_DISABLED)));
		WinSendDlgItemMsg(hwndMain, FID_MENU, MM_SETITEMATTR,
			MPFROM2SHORT(IDM_MAIN_FILE_LOGDEL, TRUE),
			MPFROM2SHORT(MIA_DISABLED,
			(nSelect != 0 ? 0 : MIA_DISABLED)));
		break;
	case IDM_MAIN_MONITOR:
		for (i = 0, chNo = CH1; i < 3; i++, chNo++) {
			WinSendDlgItemMsg(hwndMain, FID_MENU, MM_SETITEMATTR,
				MPFROM2SHORT(monitorID[i], TRUE),
				MPFROM2SHORT(MIA_DISABLED | MIA_CHECKED,
				(portOpenFlag[chNo] ? 0 : MIA_DISABLED) |
				(monitorFlag[chNo] ? MIA_CHECKED : 0)));
		}
		break;
	case IDM_MAIN_TERMINAL:
		for (i = 0, chNo = CH0; i < 4; i++, chNo++) {
			WinSendDlgItemMsg(hwndMain, FID_MENU, MM_SETITEMATTR,
				MPFROM2SHORT(terminalID[i], TRUE),
				MPFROM2SHORT(MIA_DISABLED | MIA_CHECKED,
				(portOpenFlag[chNo] ? 0 : MIA_DISABLED) |
				(terminalMode[chNo]  & TMODE_TERMINAL ?
				MIA_CHECKED : 0)));
		}
		break;
	}
}

/*=======================================================================
 |
 |		メインウィンドウ　コマンド処理
 |
 |	VOID	MainWndCommand(hwnd, command)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	command;	コマンドＩＤ
 |
 =======================================================================*/
static	VOID	MainWndCommand(HWND hwnd, USHORT command)
{
	LOGDATA	logBuf;		/* 通信履歴ファイルバッファ */
	CHAR	fileName[13];	/* ファイル名 */
	CHAR	pathName[26];	/* パス名 */

	if (command != IDM_ABOUT && command != IDM_EXIT) {
		/* 管理者パスワードチェック */
		if (PassWordCheck(hwnd) == FALSE)
			return;
	}

	switch (command) {
	case IDM_MAIN_FILE_USER:	/* ユーザ管理 */
		UserManagement();
		break;
	case IDM_MAIN_FILE_BBS:		/* 掲示板管理 */
		BBSManagement();
		break;
	case IDM_MAIN_FILE_MAIL:	/* メール管理 */
		MailManagement();
		break;
	case IDM_MAIN_FILE_USERINF:	/* ユーザ情報表示 */
		ReadSelectData(&logBuf);
		WinDlgBox(HWND_DESKTOP, hwnd, UserDlg,
					0, IDD_USER, logBuf.userID);
		break;
	case IDM_MAIN_FILE_LOGDISP:	/* 通信内容表示 */
		ReadSelectData(&logBuf);
		if (logBuf.fileName[0] != ' ' && logBuf.logoutTime[0] != '*') {
			CopyFromDBF(fileName, logBuf.fileName,
					sizeof(logBuf.fileName));
			sprintf(pathName, "%s\\%s", dnLOGFILE, fileName);
			if (access(pathName, 0) == 0)
				ExecEditor(pathName);
		}
		break;
	case IDM_MAIN_FILE_LOGFDEL:	/* 通信内容削除 */
		DeleteLogFile(hwnd);
		break;
	case IDM_MAIN_FILE_LOGDEL:	/* 通信履歴削除 */
		DeleteLog(hwnd);
		break;
	case IDM_ABOUT:		/* プロフィール */
		WinDlgBox(HWND_DESKTOP, hwnd, AboutDlg,
						0, IDD_ABOUT, NULL);
		break;
	case IDM_MAIN_SET_PORT:	/* 通信ポート設定 */
		WinDlgBox(HWND_DESKTOP, hwnd, SetPortDlg,
						0, IDD_PORT, NULL);
		break;
	case IDM_MAIN_SET_ENV:	/* 環境設定 */
		WinDlgBox(HWND_DESKTOP, hwnd, SetEnvDlg,
						0, IDD_ENV, NULL);
		break;
	case IDM_MAIN_SET_CMSG:	/* 接続メッセージ */
		ExecEditor(fnConxxxtMsg);
		break;
	case IDM_MAIN_SET_LMSG:	/* ログインメッセージ */
		ExecEditor(fnLoginMsg);
		break;
	case IDM_MAIN_MON_CH1:	/* チャネル１モニター */
		Monitor(CH1);
		break;
	case IDM_MAIN_MON_CH2:	/* チャネル２モニター */
		Monitor(CH2);
		break;
	case IDM_MAIN_MON_CH3:	/* チャネル３モニター */
		Monitor(CH3);
		break;
	case IDM_MAIN_TERM_CH0:	/* ローカルターミナル */
		Terminal(CH0);
		break;
	case IDM_MAIN_TERM_CH1:	/* チャネル１ターミナル */
		Terminal(CH1);
		break;
	case IDM_MAIN_TERM_CH2:	/* チャネル２ターミナル */
		Terminal(CH2);
		break;
	case IDM_MAIN_TERM_CH3:	/* チャネル３ターミナル */
		Terminal(CH3);
		break;
	case IDM_MAIN_HELP_HELP:
		SendHelpMsg(HM_DISPLAY_HELP);
		break;
	case IDM_MAIN_HELP_EXT:
		SendHelpMsg(HM_EXT_HELP);
		break;
	case IDM_MAIN_HELP_INDEX:
		SendHelpMsg(HM_HELP_INDEX);
		break;
	case IDM_MAIN_HELP_KEYS:
		SendHelpMsg(HM_KEYS_HELP);
		break;
	case IDM_EXIT:		/* 終了 */
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
 |		選択中通信履歴データ読み込み処理
 |
 |	VOID	ReadSelectData(logBufp)
 |
 |		LOGDATA	*logBufp;	通信履歴データバッファ
 |
 =======================================================================*/
static	VOID	ReadSelectData(LOGDATA *logBufp)
{
	DBSet(logDBp, selectRecNo);
	DBRead(logDBp, logBufp);
}

/*=======================================================================
 |
 |		通信内容保存ファイル削除処理
 |
 |	VOID	DeleteLogFile(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	DeleteLogFile(HWND hwnd)
{
	SHORT	i;

	if (WinMessageBox(HWND_DESKTOP, hwnd,
			"指定した通信内容を削除しますか？",
			"確　認", ID_MB,
			MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL)
			== MBID_NO) {
		return;
	}

	SetPointer(SPTR_WAIT);

	DBSet(logDBp, selectRecNo);
	for (i = 0; i < nSelect; i++) {
		if (DBBof(logDBp))
			break;
		DeleteSaveFile(TRUE);
		DBSkip(logDBp, -1L);
	}

	SetPointer(SPTR_ARROW);

	WinInvalidateRect(hwnd, NULL, FALSE);
}

/*=======================================================================
 |
 |		通信履歴削除処理
 |
 |	VOID	DeleteLog(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	DeleteLog(HWND hwnd)
{
	SHORT	i;
	SHORT	nRec;

	if (WinMessageBox(HWND_DESKTOP, hwnd,
			"指定した通信履歴を削除しますか？",
			"確　認", ID_MB,
			MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL)
			== MBID_NO) {
		return;
	}

	SetPointer(SPTR_WAIT);

	DBSet(logDBp, selectRecNo);
	if (nSelect < (SHORT)DBRecCount(logDBp) / 5) {
		for (i = 0; i < nSelect; i++) {
			if (DBBof(logDBp))
				break;
			DeleteSaveFile(FALSE);
			DBDelete4(logDBp);
		}
	} else {
		for (i = 0; i < nSelect; i++) {
			if (DBBof(logDBp))
				break;
			DeleteSaveFile(FALSE);
			DBDelete(logDBp);
			DBSkip(logDBp, -1L);
		}
		DBPack(logDBp);
	}

	nRec = (SHORT)DBRecCount(logDBp);
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
 |		通信内容保存ファイル削除処理
 |
 |	VOID	DeleteSaveFile(fUpdate)
 |
 |		BOOL	fUpdate;	保存マーク削除
 |
 =======================================================================*/
static	VOID	DeleteSaveFile(BOOL fUpdate)
{
	LOGDATA	logBuf;
	CHAR	fileName[13], pathName[26];

	DBRead(logDBp, &logBuf);
	if (logBuf.fileName[0] != ' ') {
		CopyFromDBF(fileName, logBuf.fileName,
					sizeof(logBuf.fileName));
		sprintf(pathName, "%s\\%s", dnLOGFILE, fileName);
		DosDelete(pathName, 0L);
		if (fUpdate) {
			memset(logBuf.fileName, ' ', 12);
			DBUpdate(logDBp, &logBuf);
		}
	}
}

/*=======================================================================
 |
 |		管理者パスワード入力処理
 |
 |	BOOL	PassWordCheck(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 |		BOOL	返値		TRUE ：パスワード入力ＯＫ
 |					FALSE：パスワード入力ＮＧ（中止）１
 |
 =======================================================================*/
static	BOOL	PassWordCheck(HWND hwnd)
{
	if (sv.env.passWord[0] != '\0') {
		if (WinDlgBox(HWND_DESKTOP, hwnd, PassWordDlg,
					0, IDD_PASSWORD, NULL) != DID_OK)
			return(FALSE);
	}
	return(TRUE);
}

/*=======================================================================
 |
 |		パスワード入力ウィンドウプロシージャ
 |
 |	MRESULT CALLBACK PassWordDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK PassWordDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	CHAR	passWord[9], *p;

	switch (msg) {
	case WM_INITDLG:
		WinSendDlgItemMsg(hwnd, IDD_PWD_PASSWD,
				EM_SETTEXTLIMIT, MPFROMSHORT(8), 0);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			WinQueryDlgItemText(hwnd, IDD_PWD_PASSWD,
						sizeof(passWord), passWord);
			if (strcmp(passWord, sv.env.passWord) != 0) {
				WinMessageBox(HWND_DESKTOP, hwnd,
				"パスワードが違います。",
				"",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
				WinSetFocus(HWND_DESKTOP,
				WinWindowFromID(hwnd, IDD_PWD_PASSWD));
				return(0);
			}
			break;
		case DID_CANCEL:
			break;
		}
		break;
	case WM_CONTROL:
		switch (SHORT1FROMMP(mp1)) {
		case IDD_PWD_PASSWD:
			switch (SHORT2FROMMP(mp1)) {
			case EN_CHANGE:
				WinQueryDlgItemText(hwnd, IDD_PWD_PASSWD,
					sizeof(passWord), passWord);
				for (p = passWord; *p != '\0'; p++)
					*p = '*';
				WinSetDlgItemText(hwnd,
					IDD_PWD_PASSWD2, passWord);
				return(0);
			}
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		ログインメッセージ処理
 |
 |	VOID	MainWndLogin(hwnd, mp)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		LOGIN	*mp;		ログインデータポインタ
 |
 =======================================================================*/
static	VOID	MainWndLogin(HWND hwnd, LOGIN *mp)
{
	RECTL	rcl;
	LOGDATA	logBuf;
	CHAR	strBuf[9];

	/* 通信履歴ファイルに書き込み */
	logBuf.chNo[0] = (CHAR)(mp->chNo + '0');
	CopyToDBF(logBuf.userID, mp->userID, sizeof(logBuf.userID));
	sprintf(strBuf, "%2u/%02u/%02u", mp->loginDate.year % 100,
			mp->loginDate.month, mp->loginDate.day);
	memcpy(logBuf.loginDate, strBuf, 8);
	sprintf(strBuf, "%2u:%02u:%02u", mp->loginTime.hour,
			mp->loginTime.minutes, mp->loginTime.seconds);
	memcpy(logBuf.loginTime, strBuf, 8);
	memset(logBuf.logoutTime, '*', 8);
	memset(logBuf.usedTime, '*', 8);
	CopyToDBF(logBuf.fileName, mp->logFile, sizeof(logBuf.fileName));
	DBStore(logDBp, &logBuf);

	/* ウィンドウ更新 */
	if (DBRecCount(logDBp) > MAX_RECORD) {
		DBTop(logDBp);
		DeleteSaveFile(FALSE);
		DBDelete2(logDBp);
		nSelect = 0;
		WinInvalidateRect(hwnd, NULL, FALSE);
	} else {
		if (pTop == 0) {
			WinQueryWindowRect(hwnd, &rcl);
			rcl.yTop -= gcyChar * 2;
			if (rcl.yTop < 0)
				rcl.yTop = 0;
			WinScrollWindow(hwnd, 0, -gcyChar, 0L, &rcl,
						NULL, NULL, SW_INVALIDATERGN);
		} else
			pTop++;

		if (nSelect != 0)
			pSelect++;
	}

	EnableScrollBar(hwnd);

	free(mp);
}

/*=======================================================================
 |
 |		ログアウトメッセージ処理
 |
 |	VOID	MainWndLogout(hwnd, mp)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		LOGOUT	*mp;		ログアウトデータポインタ
 |
 =======================================================================*/
static	VOID	MainWndLogout(HWND hwnd, LOGOUT *mp)
{
	RECTL	rcl;
	LOGDATA	logBuf;
	SHORT	i;
	BOOL	updateFlag;
	CHAR	strBuf[9];

	updateFlag = FALSE;
	DBBottom(logDBp);
	for (i = 0; ; i++) {
		if (DBBof(logDBp))
			break;
		DBRead(logDBp, &logBuf);
		if (logBuf.chNo[0] == (CHAR)(mp->chNo + '0')) {
			if (logBuf.logoutTime[0] != '*')
				break;

			/* 通信履歴ファイル更新 */
			sprintf(strBuf, "%2u:%02u:%02u",
					mp->logoutTime.hour,
					mp->logoutTime.minutes,
					mp->logoutTime.seconds);
			memcpy(logBuf.logoutTime, strBuf, 8);
			sprintf(strBuf, "%2u:%02u:%02u",
					(SHORT)(mp->usedTime / 3600),
					(SHORT)(mp->usedTime / 60 % 60),
					(SHORT)(mp->usedTime % 60));
			memcpy(logBuf.usedTime, strBuf, 8);
			DBUpdate(logDBp, &logBuf);
			updateFlag = TRUE;
			break;
		}
		DBSkip(logDBp, -1L);
	}
	if (updateFlag) {
		/* ウィンドウ更新 */
		WinQueryWindowRect(hwnd, &rcl);
		rcl.yTop -= gcyChar * 2;
		if (i >= pTop && i <= pTop +
				(SHORT)(rcl.yTop - rcl.yBottom) / gcyChar) {
			rcl.yTop -= (i - pTop) * gcyChar;
			rcl.yBottom = rcl.yTop - gcyChar;
			WinInvalidateRect(hwnd, &rcl, FALSE);
		}
	}
	free(mp);
}

/*=======================================================================
 |
 |		メインウィンドウ　スクロールコマンド処理
 |
 |	VOID	MainWndVScroll(hwnd, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
static	VOID	MainWndVScroll(HWND hwnd, MPARAM mp1, MPARAM mp2)
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
 |		メインウィンドウ　スクロール処理
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
		nRec = (SHORT)DBRecCount(logDBp);
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
 |		メインウィンドウ タイマー処理
 |
 |	VOID	MainWndTimer(hwnd, timerID)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	timerID;	タイマーＩＤ
 |
 =======================================================================*/
static	VOID	MainWndTimer(HWND hwnd, USHORT timerID)
{
	BOOL	flag;
	SHORT	i;

	switch (timerID) {
	case TMID_FLUSH:
		DBFlushAll();
		break;
	case TMID_CLOSE:
		for (i = 0, flag = FALSE; i < N_CHANNEL; i++)
			flag |= conxxxtFlag[i];
		if (flag == FALSE)
			WinPostMsg(hwndMain, WM_QUIT, 0, 0);
		break;
	}
}

/*=======================================================================
 |
 |		メインウィンドウ　クローズ処理
 |
 |	VOID	MainWndClose()
 |
 =======================================================================*/
static	VOID	MainWndClose(VOID)
{
	PSZ	title, text;
	SHORT	i;
	BOOL	flag;
	USHORT	numBytes;

	/* 接続中のチャネルがあるか調べる */
	for (i = 0, flag = FALSE; i < N_CHANNEL; i++)
		flag |= conxxxtFlag[i];

	if (flag) {
		title = "接続中のチャネルがあります";
		text = "切断しますか？";
	} else {
		title = systemName;
		text = "プログラムを終了しますか？";
	}

	if (WinMessageBox(HWND_DESKTOP, hwndMain,
			text, title, ID_MB,
			MB_YESNO | MB_CUAWARNING | MB_APPLMODAL)
			== MBID_YES) {
		if (flag) {
			/* 回線切断処理 */
			closeRequest = TRUE;
			DosWrite(ch0Hndl, "\xff", 1, &numBytes);
			WinStartTimer(hab, hwndClient, TMID_CLOSE, 1000);
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"しばらくお待ち下さい。",
				"切断処理中です", ID_MB,
				MB_CANCEL | MB_ICONHAND | MB_APPLMODAL);
			WinStopTimer(hab, hwndClient, TMID_CLOSE);
			closeRequest = FALSE;
		} else
			WinPostMsg(hwndMain, WM_QUIT, 0, 0);
	}
}

/*=======================================================================
 |
 |		アバウトダイアログウィンドウプロシージャ
 |
 |	MRESULT CALLBACK AboutDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK AboutDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	switch (msg) {
	case WM_INITDLG:
		CenterDlgBox(hwnd);
		break;
	case WM_SUBSTITUTESTRING:
		switch (SHORT1FROMMP(mp1)) {
		case 0:
			return((MRESULT)systemName);
		case 1:
			return((MRESULT)version);
		}
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			break;
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
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
	if (selectLine >= 0 && pTop + selectLine < (SHORT)DBRecCount(logDBp)) {
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
	SHORT	pMin, pMax;

	if (selectFlag == FALSE)
		return;

	WinQueryWindowRect(hwnd, &rcl);
	rcl.yTop -= gcyChar * 2;

	pCurrent = pTop +
			((SHORT)rcl.yTop - yMousePos) / gcyChar;
	if (((SHORT)rcl.yTop - yMousePos) % gcyChar < 0)
		pCurrent--;

	if (pCurrent < 0)
		pCurrent = 0;
	else if (pCurrent >= (SHORT)DBRecCount(logDBp))
		pCurrent = (SHORT)DBRecCount(logDBp) - 1;

	if (pCurrent != pSelect2) {
		if (pCurrent >= pSelect1) {
			nSelect = pCurrent - pSelect1 + 1;
			pSelect = pSelect1;
		} else {
			nSelect = pSelect1 - pCurrent + 1;
			pSelect = pCurrent;
		}

		pMin = min(pCurrent, pSelect2);
		if (pMin >= pSelect1)
			pMin++;
		pMax = max(pCurrent, pSelect2);
		if (pMax > pSelect1)
			pMax++;
		rcl2 = rcl;
		rcl2.yTop = rcl.yTop - (pMin - pTop) * gcyChar;
		rcl2.yBottom = rcl.yTop - (pMax - pTop) * gcyChar;
		WinInvalidateRect(hwnd, &rcl2, FALSE);
		pSelect2 = pCurrent;

		if ((nScroll = pTop - pCurrent) > 0) {
			WinScrollWindow(hwnd, 0,
				-(nScroll * gcyChar), 0L,
				&rcl, NULL, NULL, SW_INVALIDATERGN);
			pTop -= nScroll;
		} else if ((nScroll = pCurrent -
				(pTop + (SHORT)rcl.yTop / gcyChar) + 1) > 0){
			WinScrollWindow(hwnd, 0,
				nScroll * gcyChar, 0L,
				&rcl, NULL, NULL, SW_INVALIDATERGN);
			pTop += nScroll;
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

	nRec = (SHORT)DBRecCount(logDBp);
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
	if (pTop != 0 || swp.cy / gcyChar < (SHORT)DBRecCount(logDBp) + 2) {
		WinSendMsg(hwndSB, SBM_SETSCROLLBAR,
			MPFROMSHORT(pTop),
			MPFROM2SHORT(0, (SHORT)DBRecCount(logDBp)-1));
		WinEnableWindow(hwndSB, TRUE);
	} else
		WinEnableWindow(hwndSB, FALSE);
}

/*=======================================================================
 |
 |		プログラム終了処理
 |
 |	VOID	Terminate()
 |
 =======================================================================*/
VOID	Terminate(VOID)
{
	DBCloseAll();
	remove(fnFCHECK);
}

/*=======================================================================
 |
 |		ＩＳＡＭライブラリエラー表示処理
 |
 |	VOID	ISAMErrorProc(errCode)
 |
 |		int	errCode;	ＩＳＡＭライブラリエラーコード
 |
 =======================================================================*/
static	VOID	ISAMErrorProc(int errCode)
{
	static	char	msg[80];

	if (errCode != IE_NOFILE) {
		sprintf(msg, "ERROR CODE = %d", errCode);
		WinPostMsg(hwndClient, WM_USER_MSGBOX, msg,
					"ＩＳＡＭファイルエラー");
	}
}

/*=======================================================================
 |
 |		致命的エラー処理
 |
 |	VOID	FatalError(errCode)
 |
 |		USHORT	errCode;	エラーコード
 |
 =======================================================================*/
VOID	FatalError(USHORT errCode)
{
	Terminate();
	DosExit(EXIT_PROCESS, errCode);

}

static	VOID	TerminateHelp(HWND hwnd)
{
	if (hwndHelpInstance != (HWND)NULL) {
		WinAssociateHelpInstance(NULL, hwnd);
		WinDestroyHelpInstance(hwndHelpInstance);
	}
}


static	VOID	InitHelp(HWND hFrame, HAB hab)
{
	HELPINIT stHMInit;
	BOOL	bHelpLibFound=TRUE;

	stHMInit.cb			= sizeof(HELPINIT);
	stHMInit.ulReturnCode		= 0L;
	stHMInit.pszTutorialName	= NULL;
	stHMInit.phtHelpTable		= (PHELPTABLE)(0xffff0000 |
						(LONG)PCNET_HELP_TABLE);
	stHMInit.hmodHelpTableModule	= (HMODULE)NULL;
	stHMInit.hmodAccelActionBxxxodule = (HMODULE)NULL;
	stHMInit.idAccelTable		= 0;
	stHMInit.idActionBar		= 0;

	stHMInit.pszHelpWindowTitle	= "ＰＣ－ＮＥＴ／２ ヘルプ";
	stHMInit.usShowPanelId		= CMIC_HIDE_PANEL_ID;
	stHMInit.pszHelpLibraryName	= "PCNET.HLP";

	if (!(hwndHelpInstance = WinCreateHelpInstance(hab, &stHMInit))) {
		if (stHMInit.ulReturnCode == HMERR_OPEN_LIB_FILE)
			DspHelpErr("ヘルプファイルが見つかりません。");
		else
			DspHelpErr("ヘルプインスタンスを作成できません。");

		return;
	}

	if (!WinAssociateHelpInstance(hwndHelpInstance, hFrame)) {
		DspHelpErr("ヘルプインスタンスの関連付けができません。");
		return;
	}

	if (stHMInit.ulReturnCode) {
		DspHelpErr("ヘルプマネージャエラー");
		return;
	}
}


static	VOID	SendHelpMsg(USHORT usMsg)
{
	WinSendMsg(hwndHelpInstance, usMsg, NULL, NULL);
}

static	VOID	AssociateHelp(HWND hwnd)
{
	WinAssociateHelpInstance(hwndHelpInstance, hwnd);
}

static	VOID	DspHelpErr(CHAR *msg)
{
	WinMessageBox(HWND_DESKTOP,
		WinQueryActiveWindow( HWND_DESKTOP, FALSE),
		msg,
		"ヘルプのエラー",
		ID_MB,
		MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL | MB_MOVEABLE);
}
