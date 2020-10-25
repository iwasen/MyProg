/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: メイン処理
 *		ファイル名	: pcserve.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#if defined (OS_WNT)
#include <direct.h>
#include <io.h>
#endif
#if defined (OS_WINDOWS)
#include <direct.h>
#endif
#include <signal.h>
#include "pcssock.h"
#include "pcserve.h"
#include "pcscheck.h"

/*	外部変数	*/
CHAR	version[] = "Version 1.00";		/* バージョンＮｏ． */
CHAR	fnIniData[] = "_PCSERVE.INI";	/* 環境設定ファイル名 */
CHAR	fnERRLOG[] = "_ERROR.LOG";		/* エラーログファイル */
PDB	pLogDB;								/* ログファイルＤＢ */
PDB	pDirNameDB;
PDB	pPermissionDB;
PDB	pMultiServerDB;						/* 多重化サーバ管理ＤＢ */
PDB	pMultiFileDB;						/* 多重化ファイル管理ＤＢ */
PDB	pMultiErrorDB;						/* 多重化エラー管理ＤＢ */
int	logLastRec;
INIDATA	ini;							/* 環境設定データ */
PSCB	pTopSCB;						/* SCB 先頭ポインタ */
int	sock = -1;							/* TCP/IP ソケット */
SHORT	debugMode;
SHORT	remoteMente;
CRITICALSECTION	csSCB, csLogin, csSN;
STATISTICS	statistics;					/* 統計情報 */
BOOL	bLicense;
static WSADATA	WSAData;

static	BOOL	update = FALSE;
static	CHAR	fnLOGDBF[] = FN_LOG_DBF;
static	char	fnDirNameDBF[] = FN_DIRNAME_DBF;
static	char	fnDirNameNDX[] = FN_DIRNAME_NDX;
static	char	fnPermissionDBF[] = FN_PERM_DBF;
static	char	fnPermissionNDX[] = FN_PERM_NDX;
static	CHAR	fnMSERVERDBF[] = FN_MSERVER_DBF;
static	CHAR	fnMSERVERNDX[] = FN_MSERVER_NDX;
static	CHAR	fnMFILEDBF[] = FN_MFILE_DBF;
static	CHAR	fnMFILE1NDX[] = FN_MFILE1_NDX;
static	CHAR	fnMFILE2NDX[] = FN_MFILE2_NDX;
static	CHAR	fnMERRORDBF[] = FN_MERROR_DBF;
static	CHAR	fnMERRORNDX[] = FN_MERROR_NDX;

#if defined (OS_WINDOWS)
HWND	hWndPCSERVE;
#endif

#if defined (OS_WNT)
static	CHAR	serviceName[] = "PCSERVE";
HANDLE			hServDoneEvent = NULL;
static	SERVICE_STATUS	ssStatus;
static	SERVICE_STATUS_HANDLE	sshStatusHandle;
static	DWORD	dwGlobalErr;

/*	内部関数	*/
static	VOID	ServiceMain(DWORD, LPTSTR *);
static	VOID	WINAPI	ServiceCtrl(DWORD);
static	BOOL	ReportStatusToSCMgr(DWORD, DWORD, DWORD, DWORD);
static	VOID	StopService(LPTSTR);
#endif

static	VOID	ExitProc(int);
static	BOOL	Initialize(VOID);
static	VOID	InitCriticalSection(VOID);
static	BOOL	OpenLogDBF(VOID);
static	BOOL	OpenDirNameDBF(VOID);
static	BOOL	OpenPermissionDBF(VOID);
static	BOOL	OpenMultiServerDBF(VOID);

extern	CRITICALSECTION	csDB;
extern	CRITICALSECTION	csDBL;
extern	CRITICALSECTION	csDBF;
extern	CRITICALSECTION	csIDX;
extern	CRITICALSECTION	csLogFile;
extern	CRITICALSECTION	csDirName;
extern	CRITICALSECTION	csPermission;
extern	CRITICALSECTION	csMultiServer;
extern	CRITICALSECTION	csNetMsg;

#if defined (OS_WNT)
/*=======================================================================
 |
 |		PCSERVE.WNT 起動
 |
 |	VOID	StartPCSERVE()
 |
 =======================================================================*/
static	VOID	StartPCSERVE(VOID)
{
	SC_HANDLE	hSC1, hSC2;

	hSC1 = OpenSCManager(NULL, NULL, SC_MANAGER_CONxxxT);
	if (hSC1 == NULL) {
		ErrorLog(NULL, "OpenSCManager error %d", GetLastError());
		exit(1);
	}

	hSC2 = OpenService(hSC1, serviceName, SERVICE_START);
	if (hSC2 == NULL) {
		ErrorLog(NULL, "OpenService error %d", GetLastError());
		exit(1);
	}

	StartService(hSC2, 0, NULL);

	CloseServiceHandle(hSC2);
	CloseServiceHandle(hSC1);
}

/*=======================================================================
 |
 |		PCSERVE 直接起動
 |
 |	int	RunPCSERVE()
 |
 =======================================================================*/
static	int	RunPCSERVE(VOID)
{
	bLicense = TRUE;

	if (!Initialize())
		return 1;

	signal(SIGINT, SIG_IGN);
	signal(SIGTERM, SIG_IGN);

	if (ini.bNetBIOS)
		StartNetBIOS();

	if (ini.bTCPIP)
		StartTCPIP();

	StartRS232C();

	StartNetMessage();

	getchar();

	Terminate();
	return 0;
}

/*=======================================================================
 |
 |		PCSERVE サービスインストール
 |
 |	int	_InstallService()
 |
 =======================================================================*/
static	int	_InstallService(CHAR *fileName)
{
	SC_HANDLE	hSC1, hSC2;
	CHAR	pathName[256];

	_fullpath(pathName, fileName, sizeof(pathName));

	hSC1 = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);
	if (hSC1 == NULL) {
		printf("OpenSCManager error %d", GetLastError());
		return 1;
	}

	hSC2 = CreateService(
		hSC1,				// SCManager database
		serviceName,			// name of service
		serviceName,			// name to display
		SERVICE_ALL_ACCESS,		// desired access
		SERVICE_WIN32_OWN_PROCESS,	// service type
		SERVICE_AUTO_START,		// start type
		SERVICE_ERROR_NORMAL,		// error control type
		pathName,			// service's binary
		NULL,				// no load ordering group
		NULL,				// no tag identifier
		NULL,				// no dependencies
		NULL,				// LocalSystem account
		NULL);				// no password
	if (hSC2 == NULL) {
		printf("OpenService error %d", GetLastError());
		CloseServiceHandle(hSC1);
		return 1;
	}

	CloseServiceHandle(hSC2);
	CloseServiceHandle(hSC1);

	printf("\n%s サービスをインストールしました．\n", serviceName);

	return 0;
}

/*=======================================================================
 |
 |		PCSERVE サービス削除
 |
 |	int	_RemoveService()
 |
 =======================================================================*/
static	int	_RemoveService(VOID)
{
	SC_HANDLE	hSC1, hSC2;

	hSC1 = OpenSCManager(NULL, NULL, SC_MANAGER_ALL_ACCESS);
	if (hSC1 == NULL) {
		printf("OpenSCManager error %d", GetLastError());
		return 1;
	}

	hSC2 = OpenService(hSC1, serviceName, SERVICE_ALL_ACCESS);
	if (hSC2 == NULL) {
		printf("OpenService error %d", GetLastError());
		CloseServiceHandle(hSC1);
		return 1;
	}

	if (!DeleteService(hSC2))
		printf("DeleteService error %d", GetLastError());

	CloseServiceHandle(hSC2);
	CloseServiceHandle(hSC1);

	printf("\n%s サービスを削除しました．\n", serviceName);

	return 0;
}

/*=======================================================================
 |
 |		サービス開始
 |
 |	int	_StartService()
 |
 =======================================================================*/
static	int	_StartService(VOID)
{
	SC_HANDLE	hSC1, hSC2;
	BOOL	rc;

	hSC1 = OpenSCManager(NULL, NULL, GENERIC_EXECUTE);
	if (hSC1 == NULL) {
		printf("OpenSCManager error %d", GetLastError());
		exit(1);
	}

	hSC2 = OpenService(hSC1, serviceName, SERVICE_START);
	if (hSC2 == NULL) {
		CloseServiceHandle(hSC1);
		printf("OpenService error %d\n", GetLastError());
		exit(1);
	}

	rc = StartService(hSC2, 0, NULL);

	CloseServiceHandle(hSC2);
	CloseServiceHandle(hSC1);

	printf("\n%s サービスを開始しました．\n", serviceName);

	return 0;
}

/*=======================================================================
 |
 |		サービス停止
 |
 |	int	_StopService()
 |
 =======================================================================*/
static	int	_StopService(VOID)
{
	SC_HANDLE	hSC1, hSC2;
	SERVICE_STATUS	ss;
	BOOL	rc;

	hSC1 = OpenSCManager(NULL, NULL, GENERIC_EXECUTE);
	if (hSC1 == NULL) {
		printf("OpenSCManager error %d", GetLastError());
		exit(1);
	}

	hSC2 = OpenService(hSC1, serviceName, SERVICE_STOP);
	if (hSC2 == NULL) {
		CloseServiceHandle(hSC1);
		printf("OpenService error %d\n", GetLastError());
		exit(1);
	}

	rc = ControlService(hSC2, SERVICE_CONTROL_STOP, &ss);

	CloseServiceHandle(hSC2);
	CloseServiceHandle(hSC1);

	printf("\n%s サービスを停止しました．\n", serviceName);

	return 0;
}

/*=======================================================================
 |
 |		メイン関数 (Windows NT)
 |
 |	int	main(argc, argv)
 |
 |		int	argc;		コマンドパラメータ数
 |		char	*argv[];	コマンドパラメータ
 |
 =======================================================================*/
main(int argc, char *argv[])
{
	SERVICE_TABLE_ENTRY dispatchTable[] = {
		{TEXT("PCSERVE"), (LPSERVICE_MAIN_FUNCTION)ServiceMain},
		{NULL, NULL}
	};
	CHAR	*p;
	STARTUPINFO	siStartupInfo;
	PROCESS_INFORMATION	piProcInfo;
	CHAR	*ap;
	int	i;

	/* カレントディレクトリをＥＸＥの存在するディレクトリに変更 */
	StringToUpper(argv[0]);
	if (argv[0][1] == ':')
		_chdrive(argv[0][0] - 0x40);
	if ((p = strrchr(argv[0], '\\')) != NULL) {
		*p = '\0';
		_chdir(argv[0]);
		*p = '\\';
	}

	for (i = 1; i < argc; i++) {
		ap = argv[i];
		if (*ap == '/' || *ap == '-') {
			ap++;
			StringToUpper(ap);
			if (strcmp(ap, "R") == 0)
				return RunPCSERVE();
			if (strcmp(ap, "INSTALL") == 0)
				return _InstallService(argv[0]);
			if (strcmp(ap, "REMOVE") == 0)
				return _RemoveService();
			if (strcmp(ap, "START") == 0)
				return _StartService();
			if (strcmp(ap, "STOP") == 0)
				return _StopService();
		}
	}

	if ((p = strrchr(argv[0], '.')) != NULL) {
		if (strcmp(p, ".TMP") == 0) {
			Sleep(5000);
			system("COPY PCSERVE.TMP PCSERVE.WNT");
			StartPCSERVE();
			exit(0);
		}
	}

	if (access("PCSERVE.XXX", 0) == 0)
		rename("PCSERVE.XXX", "PCSERVE.NEW");

	if (!StartServiceCtrlDispatcher(dispatchTable)) {
		StopService("StartServiceCtrlDispatcher failed.");
	}

	if (update) {
		rename("PCSERVE.NEW", "PCSERVE.TMP");
		memset(&siStartupInfo, 0, sizeof(siStartupInfo));
		siStartupInfo.cb = sizeof(siStartupInfo);
		CreateProcess("PCSERVE.TMP", NULL, NULL, NULL, FALSE,
				DETACHED_PROCESS|NORMAL_PRIORITY_CLASS,
				NULL, NULL, &siStartupInfo, &piProcInfo);
	}

	return 0;
}

/*=======================================================================
 |
 |		サービス関数 (Windows NT)
 |
 |	VOID	ServiceMain(dwArgc, lpszArgv)
 |
 |		DWORD	dwArgc;		コマンドパラメータ数
 |		LPTSTR	*lpszArgv;	コマンドパラメータ
 |
 =======================================================================*/
static	VOID	ServiceMain(DWORD dwArgc, LPTSTR *lpszArgv)
{
	PSCB	pSCB;
	static	int	licenseCheckCounter;

	sshStatusHandle = RegisterServiceCtrlHandler(
					TEXT("PCSERVE"), ServiceCtrl);

	if (!sshStatusHandle)
		goto cleanup;

	ssStatus.dwServiceType = SERVICE_WIN32_OWN_PROCESS;
	ssStatus.dwServiceSpecificExitCode = 0;

	if (!ReportStatusToSCMgr(
			SERVICE_START_PENDING, // service state
			NO_ERROR,	       // exit code
			1,		       // checkpoint
			3000))		       // wait hint
		goto cleanup;

	hServDoneEvent = CreateEvent(
			NULL,	 // no security attributes
			TRUE,	 // manual reset event
			FALSE,	 // not-signalled
			NULL);	 // no name

	if (hServDoneEvent == (HANDLE)NULL)
		goto cleanup;

	if (!ReportStatusToSCMgr(
			SERVICE_START_PENDING, // service state
			NO_ERROR,	       // exit code
			2,		       // checkpoint
			3000))		       // wait hint
		goto cleanup;

	_fmode |= 0x8000;

	/* インストールチェック */
//	if (!CheckSystem("PCSERVEW", "PCSERVE")) {
//		ErrorLog(NULL, "Install check error");
//		goto cleanup;
//	}

	bLicense = TRUE;//LicenseCheck("PCSERVE", 14);

	if (!Initialize())
		goto cleanup;

	/* NetBIOS 監視スレッド起動 */
	if (ini.bNetBIOS)
		StartNetBIOS();

	/* TCP/IP 監視スレッド起動 */
	if (ini.bTCPIP)
		StartTCPIP();

	/* RS232C 監視スレッド起動 */
	StartRS232C();

	/* ネットワークメッセージ送信スレッド起動 */
	StartNetMessage();

	if (!ReportStatusToSCMgr(
			SERVICE_RUNNING, // service state
			NO_ERROR,	 // exit code
			0,		 // checkpoint
			0))		 // wait hint
		goto cleanup;

	while (access("PCSERVE.TMP", 0) == 0) {
		Sleep(1000);
		remove("PCSERVE.TMP");
	}

	while (WaitForSingleObject(hServDoneEvent, 60*1000) == WAIT_TIMEOUT) {
		if (access("PCSERVE.NEW", 0) == 0) {
			for (pSCB = pTopSCB; pSCB != NULL;
						pSCB = pSCB->chainSCB) {
				if (pSCB->login.clientID[0] != '.' &&
					    pSCB->login.clientID[0] != '\0')
					break;
			}
			if (pSCB == NULL) {
				update = TRUE;
				break;
			}
		}

		if (++licenseCheckCounter == 60) {
//			bLicense = LicenseCheck("PCSERVE", 14);
			licenseCheckCounter = 0;
		}
	}

	Terminate();
cleanup:
	if (hServDoneEvent != NULL)
		CloseHandle(hServDoneEvent);

	if (sshStatusHandle != 0)
		(VOID)ReportStatusToSCMgr(SERVICE_STOPPED, dwGlobalErr, 0, 0);

	return;
}

/*=======================================================================
 |
 |		サービスコントロール処理関数 (Windows NT)
 |
 |	VOID	ServiceCtrl(dwCtrlCode)
 |
 |		DWORD	dwCtrlCode;	コントロールコード
 |
 =======================================================================*/
static	VOID	WINAPI	ServiceCtrl(DWORD dwCtrlCode)
{
	DWORD  dwState = SERVICE_RUNNING;

	switch(dwCtrlCode) {

	case SERVICE_CONTROL_PAUSE:

	    if (ssStatus.dwCurrentState == SERVICE_RUNNING) {
//			SuspendThread(threadHandle);
			dwState = SERVICE_PAUSED;
	    }
	    break;

	case SERVICE_CONTROL_CONTINUE:
		if (ssStatus.dwCurrentState == SERVICE_PAUSED) {
//			ResumeThread(threadHandle);
			dwState = SERVICE_RUNNING;
		}
		break;

	case SERVICE_CONTROL_STOP:
		dwState = SERVICE_STOP_PENDING;

		ReportStatusToSCMgr(
				SERVICE_STOP_PENDING, // current state
				NO_ERROR,	      // exit code
				1,		      // checkpoint
				3000);		      // waithint

		SetEvent(hServDoneEvent);
		return;

	case SERVICE_CONTROL_INTERROGATE:
		break;

	case SERVICE_CONTROL_SHUTDOWN:
		SetEvent(hServDoneEvent);
		return;

	default:
		break;

	}

	ReportStatusToSCMgr(dwState, NO_ERROR, 0, 0);
}

/*=======================================================================
 |
 |		ステータス通知処理 (Windows NT)
 |
 |	BOOL	ReportStatusToSCMgr(dwCurrentState, dwWin32ExitCode,
 |						dwCheckPoint, dwWaitHint)
 |
 |		DWORD	dwCurrentState;		ステータス
 |		DWORD	dwWin32ExitCode;	エラーコード
 |		DWORD	dwCheckPoint;		チェックポイント
 |		DWORD	dwWaitHint;		完了予想時間
 |
 =======================================================================*/
static	BOOL	ReportStatusToSCMgr(DWORD dwCurrentState,
			DWORD dwWin32ExitCode,
			DWORD dwCheckPoint,
			DWORD dwWaitHint)
{
	BOOL	fResult;

	if (dwCurrentState == SERVICE_START_PENDING)
		ssStatus.dwControlsAccepted = 0;
	else
		ssStatus.dwControlsAccepted = SERVICE_ACCEPT_STOP |
				SERVICE_ACCEPT_PAUSE_CONTINUE;

	ssStatus.dwCurrentState = dwCurrentState;
	ssStatus.dwWin32ExitCode = dwWin32ExitCode;
	ssStatus.dwCheckPoint = dwCheckPoint;

	ssStatus.dwWaitHint = dwWaitHint;

	if (!(fResult = SetServiceStatus(
			sshStatusHandle,    // service reference handle
			&ssStatus))) {	    // SERVICE_STATUS structure
		StopService("SetServiceStatus");
	}
	return fResult;
}

/*=======================================================================
 |
 |		サービス停止処理 (Windows NT)
 |
 |	VOID	StopService(lpszMsg)
 |
 |		LPTSTR	lpszMsg;	エラーメッセージ
 |
 =======================================================================*/
static	VOID	StopService(LPTSTR lpszMsg)
{
	CHAR	chMsg[256];
	HANDLE	hEventSource;
	LPTSTR	lpszStrings[2];

	dwGlobalErr = GetLastError();

	hEventSource = RegisterEventSource(NULL, TEXT("PCSERVE"));

	sprintf(chMsg, "PCSERVE error: %d", dwGlobalErr);
	lpszStrings[0] = chMsg;
	lpszStrings[1] = lpszMsg;

	if (hEventSource != NULL) {
		ReportEvent(hEventSource, // handle of event source
				EVENTLOG_ERROR_TYPE,  // event type
				0,		      // event category
				0,		      // event ID
				NULL,		      // current user's SID
				2,		      // strings in lpszStrings
				0,		      // no bytes of raw data
				lpszStrings,	      // array of error strings
				NULL);		      // no raw data

		(VOID) DeregisterEventSource(hEventSource);
	}

	SetEvent(hServDoneEvent);
}
#endif

#if defined (OS_WINDOWS)
int	PASCAL	WinMain(HANDLE hInstance, HANDLE hPrevInstance,
					LPSTR lpszCmdLine, int cmdShow)
{
	long FAR PASCAL PcserveWndProc(HWND, WORD, WORD, LONG);
	static	CHAR	className[] = "PCSERVE";
	WNDCLASS	wndClass;
	MSG	msg;
	DWORD	style;
	char	dir[64];
	extern	int	__argc;
	extern	char	**__argv;

	if (hPrevInstance)
		return 0;

	GetWindowsDirectory(dir, sizeof(dir));
	StringToUpper(dir);
	_chdrive(dir[0] - 0x40);
	_chdir(dir + 2);

	SetErrorMode(SEM_FAILCRITICALERRORS);
	
	/* 初期化処理 */
	if (!Initialize())
		return 0;

	wndClass.style         = 0;
	wndClass.lpfnWndProc   = (WNDPROC)PcserveWndProc;
	wndClass.cbClsExtra    = 0;
	wndClass.cbWndExtra    = 0;
	wndClass.hInstance     = hInstance;
	wndClass.hIcon         = LoadIcon(hInstance, "PCSERVE");
	wndClass.hCursor       = LoadCursor(NULL, IDC_ARROW);
	wndClass.hbrBackground = GetStockObject(WHITE_BRUSH);
	wndClass.lpszMenuName  = NULL;
	wndClass.lpszClassName = (LPSTR)className;

	if (!RegisterClass(&wndClass)) {
		ErrorLog(NULL, "RegisterClass() error", 0);
		return 0;
	}

	style = WS_OVERLAPPED | WS_MINIMIZE | WS_SYSMENU;
	if (__argc == 2 && strcmp(__argv[1], "/ICON") == 0)
		style |= WS_VISIBLE;
	hWndPCSERVE = CreateWindow(
			className,
			"PCSERVE",
			style,
			CW_USEDEFAULT,
			CW_USEDEFAULT,
			CW_USEDEFAULT,
			CW_USEDEFAULT,
			HWND_DESKTOP,
			NULL,
			hInstance,
			NULL);
	if (!hWndPCSERVE) {
		ErrorLog(NULL, "CreateWindow() error", 0);
		UnregisterClass(className, hInstance);
		return 0;
	}

	while(GetMessage(&msg, NULL, NULL, NULL)) {
		TranslateMessage(&msg);
		DispatchMessage(&msg);
	}

	UnregisterClass(className, hInstance);
}

long FAR PASCAL PcserveWndProc(HWND hWnd, WORD message,
					WORD wParam, LONG lParam)
{
	extern	BOOL	StartTCPIPWin(HWND);
	extern	VOID	AcceptTCPIP(HWND, int);
	extern	VOID	ReadTCPIP(HWND, int);
	extern	VOID	CloseTCPIP(HWND, int);

	switch (message) {
        case WM_CREATE:
		StartTCPIPWin(hWnd);
		break;
	case WM_DESTROY:
		PostQuitMessage(0);
		break;
	case WM_CLOSE:
		Terminate();
		return DefWindowProc(hWnd, message, wParam, lParam);
		break;
	case WM_USER:
		switch (WSAGETSELECTEVENT(lParam)) {
		case FD_ACCEPT:
			AcceptTCPIP(hWnd, wParam);
			break;
		case FD_READ:
			ReadTCPIP(hWnd, wParam);
			break;
		case FD_CLOSE:
			CloseTCPIP(hWnd, wParam);
			break;
		}
		break;
	default:
		return DefWindowProc(hWnd, message, wParam, lParam);
	}
	return 0L;
}
#endif

/*=======================================================================
 |
 |		初期化処理
 |
 |	BOOL	Initialize(VOID)
 |
 =======================================================================*/
BOOL	Initialize(VOID)
{
	active = TRUE;

#ifdef OS_WNT
	/* ソケット初期化 */
	WSAStartup(MAKEWORD(1,1), &WSAData);
#endif

	/* クリチカルセクションハンドル初期化 */
	InitCriticalSection();

	/* 統計情報初期化 */
	InitStatistics();

	/* 環境設定ファイルの読み込み */
	ReadIniFile();

	/* 二重化サーバ監視スレッド起動 */
	StartDuplexServer();

	if (ini.duplexMode != DUPLEX_SLAVE) {
		/* ログファイルのオープン */
		if (!OpenLogDBF()) {
			ErrorLog(NULL, "LogDB file open error");
			return FALSE;
		}
	}

	// ディレクトリ識別名管理ファイルオープン
	if (!OpenDirNameDBF()) {
		ErrorLog(NULL, "DirNameDB file open error");
		return FALSE;
	}

	// パーミッション管理ファイルオープン
	if (!OpenPermissionDBF()) {
		ErrorLog(NULL, "PermissionDBF file open error");
		return FALSE;
	}

	/* 多重化管理ファイルのオープン */
	if (ini.bMultiServer) {
		if (!OpenMultiServerDBF()) {
			ErrorLog(NULL, "MultiServerDBF file open error");
			return FALSE;
		}
	}

	/* アクセスログファイルのオープン */
	OpenLogFile();

#if defined (OS_WNT)
	SetErrorMode(SEM_FAILCRITICALERRORS | SEM_NOOPENFILEERRORBOX);
#endif

	if (ini.mailReportStartStop) {
		SendReportMail("開始／終了", "PCSERVE が起動しました．");
	}

	return TRUE;
}

static	VOID	InitCriticalSection(VOID)
{
	INIT_CRITICAL_SECTION(&csSCB);
	INIT_CRITICAL_SECTION(&csLogin);
	INIT_CRITICAL_SECTION(&csSN);
	INIT_CRITICAL_SECTION(&csSort);
	INIT_CRITICAL_SECTION(&csDB);
	INIT_CRITICAL_SECTION(&csDBL);
	INIT_CRITICAL_SECTION(&csDBF);
	INIT_CRITICAL_SECTION(&csIDX);
	INIT_CRITICAL_SECTION(&csLogFile);
	INIT_CRITICAL_SECTION(&csDirName);
	INIT_CRITICAL_SECTION(&csPermission);
	INIT_CRITICAL_SECTION(&csMultiServer);
	INIT_CRITICAL_SECTION(&csNetMsg);
}

/*=======================================================================
 |
 |		プログラム終了関数 (OS/2以外)
 |
 |	VOID	ExitProc(err)
 |
 |		int	err;		エラーコード
 |
 =======================================================================*/
static	VOID	ExitProc(int err)
{
	Terminate();
	exit(0);
}

/*=======================================================================
 |
 |		ログファイルオープン
 |
 |		BOOL	OpenLogDBF(VOID)
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：オープン失敗
 |
 =======================================================================*/
static	BOOL	OpenLogDBF(VOID)
{
	static	DBF_I	fieldData[] = { /* ログファイルフィールドデータ */
		{"CLIENTID",   'C',  8, 0},
		{"COMPUTER",   'C', 16, 0},
		{"CHTYPE",     'C',  1, 0},
		{"LOGINDATE",  'C',  8, 0},
		{"LOGINTIME",  'C',  8, 0},
		{"LOGOUTTIME", 'C',  8, 0},
		{"USEDTIME",   'C',  8, 0},
		{"RESERVED",   'C', 12, 0}
	};
	SHORT	err;
	LOGDATA	logBuf;

	logLastRec = 0;
	if ((err = DBOpen(fnLOGDBF, &pLogDB, PERMISSION_ALL)) == 0) {
		if (DBRead(pLogDB, (CHAR *)&logBuf) == 0) {
			logBuf.reserved[4] = '\0';
			logLastRec = atoi(logBuf.reserved);
		}
	} else if (err == ERROR_DB_NOFILE) {
		if (DBCreate(fnLOGDBF, fieldData, sizeof(fieldData)/sizeof(DBF_I), &pLogDB, PERMISSION_ALL) != 0)
			return FALSE;
		DBFlush(pLogDB);
	} else
		return FALSE;

	return TRUE;
}

/*=======================================================================
 |
 |		ディレクトリ識別名管理ファイルオープン
 |
 |		BOOL	OpenDirNameDBF(VOID)
 |
 |		BOOL	返値	TRUE ：正常
 |						FALSE：オープン失敗
 |
 =======================================================================*/
static	BOOL	OpenDirNameDBF(VOID)
{
	static	DBF_I	fieldData[] = { /* フィールドデータ */
		{"DIRNAME",   'C',   8, 0},
		{"DIRECTORY", 'C', 128, 0}
	};
	BOOL	bCreate;
	SHORT	idxNo;
	SHORT	err;

	bCreate = FALSE;
	if ((err = DBOpen(fnDirNameDBF, &pDirNameDB, PERMISSION_ALL)) != 0) {
		if (err == ERROR_DB_NOFILE) {
			if (DBCreate(fnDirNameDBF, fieldData, sizeof(fieldData)/sizeof(DBF_I), &pDirNameDB, PERMISSION_ALL) != 0)
				return FALSE;
			bCreate = TRUE;
		} else
			return FALSE;
	}

	if (bCreate || DBIndex(pDirNameDB, fnDirNameNDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(pDirNameDB, fnDirNameNDX, "DIRNAME", 1, &idxNo)) != 0) {
			DBClose(pDirNameDB);
			return FALSE;
		}
		DBFlush(pDirNameDB);
	}

	return TRUE;
}

/*=======================================================================
 |
 |		パーミッション管理ファイルオープン
 |
 |		BOOL	OpenPermissionDBF(VOID)
 |
 |		BOOL	返値	TRUE ：正常
 |						FALSE：オープン失敗
 |
 =======================================================================*/
static	BOOL	OpenPermissionDBF(VOID)
{
	static	DBF_I	fieldData[] = { /* フィールドデータ */
		{"DIRECTORY",  'C', 80, 0},
		{"USERGRPFLG", 'C',  1, 0},
		{"USERID",     'C', 10, 0},
		{"PERMISSION", 'C',  5, 0}
	};
	BOOL	bCreate;
	SHORT	idxNo;
	SHORT	err;

	bCreate = FALSE;
	if ((err = DBOpen(fnPermissionDBF, &pPermissionDB, PERMISSION_ALL)) != 0) {
		if (err == ERROR_DB_NOFILE) {
			if (DBCreate(fnPermissionDBF, fieldData, sizeof(fieldData)/sizeof(DBF_I), &pPermissionDB, PERMISSION_ALL) != 0)
				return FALSE;
			bCreate = TRUE;
		} else
			return FALSE;
	}

	if (bCreate || DBIndex(pPermissionDB, fnPermissionNDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(pPermissionDB, fnPermissionNDX, "DIRECTORY+USERGRPFLG+USERID", 1, &idxNo)) != 0) {
			DBClose(pPermissionDB);
			return FALSE;
		}
		DBFlush(pPermissionDB);
	}

	return TRUE;
}

/*=======================================================================
 |
 |		多重化管理ファイルオープン
 |
 |	BOOL	OpenMultiServerDBF(VOID)
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：オープン失敗
 |
 =======================================================================*/
static	BOOL	OpenMultiServerDBF(VOID)
{
	static	DBF_I	fieldData1[] = { /* 多重化サーバフィールドデータ */
		{"SERVERNAME", 'C', LEN_SERVERNAME, 0},
		{"CHTYPE",     'C',  1, 0},
		{"MASTER",     'C',  1, 0},
		{"COMMENT",    'C', 50, 0}
	};
	static	DBF_I	fieldData2[] = { /* 多重化ファイルフィールドデータ */
		{"SERVERNAME", 'C', LEN_SERVERNAME, 0},
		{"NAME1",      'C', LEN_FILENAME, 0},
		{"NAME2",      'C', LEN_FILENAME, 0}
	};
	static	DBF_I	fieldData3[] = {
		{"SERVERNAME", 'C', LEN_SERVERNAME, 0},
		{"FILENAME",   'C', LEN_FILENAME, 0},
		{"ERRORCODE",  'C',  5, 0},
		{"TIME",       'C', 14, 0}
	};
	BOOL	bCreate;
	SHORT	idxNo;
	SHORT	err;

	bCreate = FALSE;
	if ((err = DBOpen(fnMSERVERDBF, &pMultiServerDB, PERMISSION_ALL)) != 0) {
		if (err == ERROR_DB_NOFILE) {
			if (DBCreate(fnMSERVERDBF, fieldData1, sizeof(fieldData1)/sizeof(DBF_I), &pMultiServerDB, PERMISSION_ALL) != 0)
				return FALSE;
			bCreate = TRUE;
		} else
			return FALSE;
	}

	if (bCreate || DBIndex(pMultiServerDB, fnMSERVERNDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(pMultiServerDB, fnMSERVERNDX, "SERVERNAME", 1, &idxNo)) != 0) {
			DBClose(pMultiServerDB);
			return FALSE;
		}
	}

	bCreate = FALSE;
	if ((err = DBOpen(fnMFILEDBF, &pMultiFileDB, PERMISSION_ALL)) != 0) {
		if (err == ERROR_DB_NOFILE) {
			if (DBCreate(fnMFILEDBF, fieldData2, sizeof(fieldData2)/sizeof(DBF_I), &pMultiFileDB, PERMISSION_ALL) != 0)
				return FALSE;
			bCreate = TRUE;
		} else {
			DBClose(pMultiServerDB);
			return FALSE;
		}
	}

	if (bCreate || DBIndex(pMultiFileDB, fnMFILE1NDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(pMultiFileDB, fnMFILE1NDX, "NAME1+SERVERNAME", 0, &idxNo)) != 0) {
			DBClose(pMultiServerDB);
			DBClose(pMultiFileDB);
			return FALSE;
		}
	}

	if (bCreate || DBIndex(pMultiFileDB, fnMFILE2NDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(pMultiFileDB, fnMFILE2NDX, "SERVERNAME+NAME1", 1, &idxNo)) != 0) {
			DBClose(pMultiServerDB);
			DBClose(pMultiFileDB);
			return FALSE;
		}
	}

	bCreate = FALSE;
	if ((err = DBOpen(fnMERRORDBF, &pMultiErrorDB, PERMISSION_ALL)) != 0) {
		if (err == ERROR_DB_NOFILE) {
			if (DBCreate(fnMERRORDBF, fieldData3, sizeof(fieldData3)/sizeof(DBF_I), &pMultiErrorDB, PERMISSION_ALL) != 0)
				return FALSE;
			bCreate = TRUE;
		} else {
			DBClose(pMultiServerDB);
			DBClose(pMultiFileDB);
			return FALSE;
		}
	}

	if (bCreate || DBIndex(pMultiErrorDB, fnMERRORNDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(pMultiErrorDB, fnMERRORNDX, "SERVERNAME+FILENAME", 1, &idxNo)) != 0) {
			DBClose(pMultiServerDB);
			DBClose(pMultiFileDB);
			DBClose(pMultiErrorDB);
			return FALSE;
		}
	}

	return TRUE;
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
	PSCB	pSCB;
	static	BOOL	flag;

	if (flag)
		return;

	flag = TRUE;
	active = FALSE;

	// 他のスレッドの処理が終了するのを待つ
	Sleep(10);

	/* ファイルクローズ，コネクション切断 */
	for (pSCB = pTopSCB; pSCB != NULL; pSCB = pSCB->chainSCB) {
		DisconxxxtClient(pSCB);
	}

	/* ログファイルクローズ */
	if (pLogDB != NULL)
		DBClose(pLogDB);

	if (pDirNameDB != NULL)
		DBClose(pDirNameDB);

	if (pPermissionDB != NULL)
		DBClose(pPermissionDB);

	/* 多重化管理ファイルクローズ */
	if (pMultiServerDB != NULL)
		DBClose(pMultiServerDB);

	if (pMultiFileDB != NULL)
		DBClose(pMultiFileDB);

	if (pMultiErrorDB != NULL)
		DBClose(pMultiErrorDB);

	/* アクセスログファイルのクローズ */
	CloseLogFile();

	if (ini.mailReportStartStop) {
		SendReportMail("開始／終了", "PCSERVE が終了しました．");
	}

#if defined (OS_WNT)
	{
		extern VOID TerminateTCPIP(void);

		TerminateTCPIP();

		WSACleanup();
	}
#endif
}
