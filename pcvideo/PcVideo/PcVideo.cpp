// PcVideo.cpp : アプリケーション用クラスの機能定義を行います。
//

#include "stdafx.h"
#include "PcVideo.h"

#include "MainFrm.h"
#include "pcvcomm.h"
#include "voice.h"
#include "UpdateAp.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

extern "C" {
extern BOOL APIENTRY CheckLicense(LPCTSTR pAppName, HICON hIcon, LPCTSTR pRegistryKey, int nAppID, BOOL bNoDlgTrial);
}

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp

BEGIN_MESSAGE_MAP(CPcVideoApp, CWinApp)
	//{{AFX_MSG_MAP(CPcVideoApp)
	ON_COMMAND(ID_APP_ABOUT, OnAppAbout)
	ON_COMMAND(ID_HELP_USING, OnHelpUsing)
	ON_COMMAND(IDM_HELP_CONTENTS, OnHelpContents)
	ON_COMMAND(IDM_HELP_KEY, OnHelpKey)
	ON_COMMAND(IDM_ONLINE_UPDATE, OnOnlineUpdate)
	ON_UPDATE_COMMAND_UI(IDM_ONLINE_UPDATE, OnUpdateOnlineUpdate)
	//}}AFX_MSG_MAP
	// 標準のファイル基本ドキュメント コマンド
	ON_COMMAND(ID_HELP, OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp クラスの構築

CPcVideoApp::CPcVideoApp()
{
	// TODO: この位置に構築用コードを追加してください。
	// ここに InitInstance 中の重要な初期化処理をすべて記述してください。
}

/////////////////////////////////////////////////////////////////////////////
// 唯一の CPcVideoApp オブジェクト

CPcVideoApp theApp;

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp クラスの初期化

BOOL CPcVideoApp::InitInstance()
{
	LPHOSTENT hp;
	int	channelNo = 0;
	BOOL	bNoCapture = FALSE;
	BOOL	bStartChat = FALSE;
	BOOL	bStartVoice = FALSE;
	char	cmdLine[128], *p;

	AfxEnableControlContainer();

	// 標準的な初期化処理
	// もしこれらの機能を使用せず、実行ファイルのサイズを小さく
	// したければ以下の特定の初期化ルーチンの中から不必要なもの
	// を削除してください。

#ifdef _AFXDLL
	Enable3dControls();		// 共有 DLL の中で MFC を使用する場合にはここを呼び出してください。
#else
	Enable3dControlsStatic();	// MFC と静的にリンクしている場合にはここを呼び出してください。
#endif

	if (!CheckLicense("PC-VIDEO", LoadIcon(IDR_MAINFRAME), "PC-VIDEO", 0x01, FALSE))
		return FALSE;

	// 設定が保存される下のレジストリ キーを変更します。
	// TODO: この文字列を、会社名または所属など適切なものに
	// 変更してください。
//	SetRegistryKey(_T("Local AppWizard-Generated Applications"));

	LoadStdProfileSettings(0);  // 標準の INI ファイルのオプションをローﾄﾞします (MRU を含む)


	// コマンドパラメータ解析
	lstrcpy(cmdLine, m_lpCmdLine);
	p = strtok(cmdLine, " ");
	while (p != NULL) {
		if (*p == '/') {
			AnsiUpper(p);
			if (strncmp(p, "/CHNO=", 6) == 0)
				channelNo = atoi(p + 6);
			else if (strcmp(p, "/CHAT") == 0)
				bStartChat = TRUE;
			else if (strcmp(p, "/VOICE") == 0)
				bStartVoice = TRUE;
			else if (strcmp(p, "/NOCAPTURE") == 0)
				bNoCapture = TRUE;
			else if (strcmp(p, "/ADMIN") == 0)
				bAdmin = TRUE;
			else if (strncmp(p, "/CAPTURE=", 9) == 0)
				nCaptureDriver = atoi(p + 9);
			else if (strcmp(p, "/DEBUG") == 0)
				bDebug = TRUE;
		}

		p = strtok(NULL, " ");
	}

	ReadIniFile();		// PCVIDEO.INI 読み込み
	m_bReadIniFile = TRUE;

	if (!InitSocket())	// ソケット初期化
		return FALSE;

	// 名前がセットされていなかったらホスト名をセット
	if (iniData.settings.userName[0] == '\0') {
		if ((p = strchr(localHostName, '.')) != NULL)
			*p = '\0';
		strcpy(iniData.settings.userName, localHostName);
	}

	if (!InitVideoDecompress())	// ビデオ圧縮初期化
		return FALSE;

	hDD = DrawDibOpen();		// 描画処理オープン

	InitResources();		// リソース初期化
	InitializePalette();		// パレット初期化

	::GetModuleFileName(m_hInstance, m_ExeFileName.GetBuffer(MAX_PATH), MAX_PATH);
	m_ExeFileName.ReleaseBuffer();

	CFileStatus st;
	bOnlineUpdate = CFile::GetStatus(m_ExeFileName.Left(m_ExeFileName.ReverseFind('\\') + 1) + "YMUPDATE.INI", st);

	// ウィンドウクラス登録
//	videoWndClassName =
//			AfxRegisterWndClass(CS_BYTEALIGNWINDOW | CS_DBLCLKS,
//			LoadStandardCursor(IDC_ARROW), 0, 0);
	cameraFrameWndClassName = AfxRegisterWndClass(0, 0, 0, 0);

	// メインウィンドウ作成
	m_pMainWnd = pMainWnd = new CMainFrame();
	m_pMainWnd->ShowWindow(m_nCmdShow);
	m_pMainWnd->UpdateWindow();


	// キャプチャウィンドウ初期化
	if (!bNoCapture && iniData.settings.bVideoCapture && InitializeCapture()) {
		OpenCaptureWindow();
	}

	// ボイスウィンドウ初期化
	if (!InitializeVoice()) {
		bStartVoice = FALSE;
		bVoice = FALSE;
	}

	// コマンドパラメータで指定したホストに接続
	lstrcpy(cmdLine, m_lpCmdLine);
	p = strtok(cmdLine, " ");
	while (p != NULL) {
		if (*p != '/') {
			if ((hp = gethostbyname(p)) != NULL) {
				ConxxxtHost(p, channelNo);
			}
		}

		p = strtok(NULL, " ");
	}

	// コマンドパラメータで "/CHAT" があればチャットウィンドウ表示
	if (bStartChat)
		OpenChatWindow();

	// コマンドパラメータで "/VOICE" があればボイスウィンドウ表示
	if (bStartVoice)
		OpenVoiceWindow();

	// メインウィンドウのタイマ起動
	pMainWnd->SetTimer(IDT_MONITOR, 1000, NULL);

	return TRUE;
}


/////////////////////////////////////////////////////////////////////////////
// アプリケーションのバージョン情報で使われる CAboutDlg ダイアログ

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// ダイアログ データ
	//{{AFX_DATA(CAboutDlg)
	enum { IDD = IDD_ABOUTBOX };
	//}}AFX_DATA

	// ClassWizard 仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CAboutDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV のサポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	//{{AFX_MSG(CAboutDlg)
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
	//{{AFX_DATA_INIT(CAboutDlg)
	//}}AFX_DATA_INIT
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CAboutDlg)
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
	//{{AFX_MSG_MAP(CAboutDlg)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

// ダイアログを実行するためのアプリケーション コマンド
void CPcVideoApp::OnAppAbout()
{
	CAboutDlg aboutDlg;
	aboutDlg.DoModal();
}

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp メッセージ ハンドラ

int CPcVideoApp::ExitInstance() 
{
	PUSER	pUser;
	PSEND	pSend;
	HEADER	header;

	CloseCaptureWindow();

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
		if (!bRestart) {
			header.flag = FLAG_DISCONxxxT;
			header.id = localIPAddress;
			SendData(&pSend->sock, &header, sizeof(header));
		}
	}

	while ((pUser = GetUser(0)) != NULL)
		DeleteUser(pUser);

	ExitSocket();

	if (hDD != 0) {
		DrawDibClose(hDD);	// 描画処理クローズ
		hDD = 0;
	}

	if (m_bReadIniFile) {
		WriteIniFile();		// PCVIDEO.INI 書き込み
		m_bReadIniFile = FALSE;
	}

	ExitVideoCompress();
	ExitVideoDecompress();

	ExitAudioCompress();

	// 再起動処理
	if (bRestart) {
		char	command[128];
		char	*p;

		lstrcpy(command, m_pszHelpFilePath);
		if ((p = strrchr(command, '\\')) != NULL)
			strcpy(p, "\\PCVSTART.EXE");
		else
			strcat(command, "PCVSTART.EXE");

		strcat(command, " ");
		strcat(command, m_pszExeName);

		if (m_lpCmdLine[0] != '\0')
			strcat(command, " ");
		strcat(command, m_lpCmdLine);

		if (downLoadFile.GetLength() != 0) {
			strcat(command, " ");
			strcat(command, "/DL=");
			strcat(command, downLoadFile);
		}

		STARTUPINFO	siStartupInfo;
		PROCESS_INFORMATION	piProcInfo;
		memset(&siStartupInfo, 0, sizeof(siStartupInfo));
		siStartupInfo.cb = sizeof(siStartupInfo);
		CreateProcess(NULL, command, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, ".", &siStartupInfo, &piProcInfo);
	}

	return CWinApp::ExitInstance();
}

////////////////////////////////////////
//	リソース初期化処理
////////////////////////////////////////
void	CPcVideoApp::InitResources()
{
	static	LOGBRUSH brush;

	// システムフォント（固定幅）取得
	systemFont.CreateStockObject(SYSTEM_FIXED_FONT);

	// ＭＳゴシックフォント取得
	infoFont.CreateFont(-11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
					0x04|FIXED_PITCH, "MS Sans Serif");

	hSpaceCursor = LoadCursor(IDC_MOVE);	// カーソル取得

	// ブラシの作成
	brush.lbStyle = BS_HATCHED;
	brush.lbColor = DIB_RGB_COLORS;
	brush.lbHatch = HS_BDIAGONAL;
	cameraBrush.CreateBrushIndirect(&brush);

	// ペンの作成
	cameraPen.CreatePen(PS_SOLID, 2, RGB(255, 0, 0));

	//アイコンのロード
	hIconApp = LoadIcon(IDR_MAINFRAME);
	hIconVideo = LoadIcon(IDI_VIDEO);
	hIconMic = LoadIcon(IDI_MICROPHONE);
	hIconMicOff = LoadIcon(IDI_MICROPHONE_OFF);
	hIconSpeaker = LoadIcon(IDI_SPEAKER);
	hIconSpeakerOff = LoadIcon(IDI_SPEAKER_OFF);
	hIconChat = LoadIcon(IDI_CHAT);
}

////////////////////////////////////////
//	パレット初期化処理
////////////////////////////////////////
void	CPcVideoApp::InitializePalette()
{
	int	i;
	BITMAPINFO	*bip;

	palette.palVersion = 0x300;
	palette.palNumEntries = 256;

	for (i = 0; i < 256; i++) {
		palette.palPalEntry[i].peRed = i;
		palette.palPalEntry[i].peGreen = i;
		palette.palPalEntry[i].peBlue = i;
	}

	bip = (BITMAPINFO *)bitmapInfo;
	bip->bmiHeader.biSize = sizeof(BITMAPINFOHEADER);
	bip->bmiHeader.biPlanes = 1;
	bip->bmiHeader.biCompression = BI_RGB;
	for (i = 0; i < 256; i++) {
		bip->bmiColors[i].rgbRed = i;
		bip->bmiColors[i].rgbGreen = i;
		bip->bmiColors[i].rgbBlue = i;
	}
}

////////////////////////////////////////
//	ヘルプ表示（F1キー）
////////////////////////////////////////
void CPcVideoApp::OnHelp()
{
	CWnd *pWnd = pMainWnd->GetActiveWindow();
	if (pWnd != NULL)
		pWnd->OnHelp();
}

void CPcVideoApp::OnHelpUsing() 
{
	CWinApp::OnHelpUsing();
}

void CPcVideoApp::OnHelpContents() 
{
	WinHelp(0, HELP_CONTENTS);
}

void CPcVideoApp::OnHelpKey() 
{
	WinHelp((DWORD)(LPSTR)"", HELP_PARTIALKEY);
}

void CPcVideoApp::OnOnlineUpdate() 
{
	static char registry[] = "SoftWare\\xxxxxxxxx Electronic Inc.\\PC-VIDEO";
	OnlineUpdateFunc pOnlineUpdate;
	HINSTANCE hModule;

	if ((hModule = LoadLibrary("YMUPDATE.DLL")) == NULL) {
		AfxMessageBox(IDS_NO_YMUPDATE);
		return;
	}

	if ((pOnlineUpdate = (OnlineUpdateFunc)GetProcAddress(hModule, "OnlineUpdate")) == NULL) {
		AfxMessageBox(IDS_NO_ONLINEUPDATE);
		::FreeLibrary(hModule);
		return;
	}

	pOnlineUpdate(GetString(IDS_ONLINE_UPDATE), m_ExeFileName, m_lpCmdLine, hIconApp, "PC-VIDEO", registry);
}

void CPcVideoApp::OnUpdateOnlineUpdate(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(bOnlineUpdate);
}
