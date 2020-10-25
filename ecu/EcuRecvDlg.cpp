// EcuRecvDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "EcuRecv.h"
#include "EcuRecvDlg.h"
#include "comm.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// アプリケーションのバージョン情報で使われている CAboutDlg ダイアログ

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// ダイアログ データ
	//{{AFX_DATA(CAboutDlg)
	enum { IDD = IDD_ABOUTBOX };
	//}}AFX_DATA

	// ClassWizard は仮想関数のオーバーライドを生成します
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
		// メッセージ ハンドラがありません。
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CEcuRecvDlg ダイアログ

CEcuRecvDlg::CEcuRecvDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CEcuRecvDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CEcuRecvDlg)
	m_sReceiveStatus = _T("");
	m_sSaveFolder = _T("");
	m_sTexxxount = _T("");
	m_sReceiveCount = _T("");
	//}}AFX_DATA_INIT
	// メモ: LoadIcon は Win32 の DestroyIcon のサブシーケンスを要求しません。
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CEcuRecvDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CEcuRecvDlg)
	DDX_Control(pDX, IDC_SAVE_FOLDER, m_cSaveFolder);
	DDX_Control(pDX, IDCANCEL, m_cExit);
	DDX_Control(pDX, IDC_STOP, m_cStop);
	DDX_Control(pDX, IDC_START, m_cStart);
	DDX_Text(pDX, IDC_RECEIVE_STATUS, m_sReceiveStatus);
	DDX_Text(pDX, IDC_SAVE_FOLDER, m_sSaveFolder);
	DDX_Text(pDX, IDC_TEST_COUNT, m_sTexxxount);
	DDX_Text(pDX, IDC_RECEIVE_COUNT, m_sReceiveCount);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CEcuRecvDlg, CDialog)
	//{{AFX_MSG_MAP(CEcuRecvDlg)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_BN_CLICKED(IDC_START, OnStart)
	ON_BN_CLICKED(IDC_STOP, OnStop)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_RECV_NOTIFY, OnReceiveNotify)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CEcuRecvDlg メッセージ ハンドラ

//===================================================================
//
//	機能　：メインダイアログの WM_INITDIALOG メッセージ処理
//
//	引数　：無し
//
//	戻り値：TRUE
//
//===================================================================
BOOL CEcuRecvDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// "バージョン情報..." メニュー項目をシステム メニューへ追加します。

	// IDM_ABOUTBOX はコマンド メニューの範囲でなければなりません。
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		CString strAboutMenu;
		strAboutMenu.LoadString(IDS_ABOUTBOX);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// このダイアログ用のアイコンを設定します。フレームワークはアプリケーションのメイン
	// ウィンドウがダイアログでない時は自動的に設定しません。
	SetIcon(m_hIcon, TRUE);			// 大きいアイコンを設定
	SetIcon(m_hIcon, FALSE);		// 小さいアイコンを設定

	// コントロールのイネーブル処理
	EnableControl(FALSE);

	// 通信ハンドラ起動
	StartCommHandler();
	
	return TRUE;  // TRUE を返すとコントロールに設定したフォーカスは失われません。
}

//===================================================================
//
//	機能　：システムコマンド処理
//
//	引数　：nID - コマンドID
//			lParam - メッセージパラメータ
//
//	戻り値：無し
//
//===================================================================
void CEcuRecvDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialog::OnSysCommand(nID, lParam);
	}
}

//===================================================================
//
//	機能　：メインダイアログの WM_PAINT メッセージ処理
//
//	引数　：無し
//
//	戻り値：無し
//
//===================================================================
void CEcuRecvDlg::OnPaint() 
{
	if (IsIconic())
	{
		CPaintDC dc(this); // 描画用のデバイス コンテキスト

		SendMessage(WM_ICONERASEBKGND, (WPARAM) dc.GetSafeHdc(), 0);

		// クライアントの矩形領域内の中央
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// アイコンを描画します。
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// システムは、ユーザーが最小化ウィンドウをドラッグしている間、
// カーソルを表示するためにここを呼び出します。
HCURSOR CEcuRecvDlg::OnQueryDragIcon()
{
	return (HCURSOR) m_hIcon;
}

//===================================================================
//
//	機能　：「開始」ボタン処理
//
//	引数　：無し
//
//	戻り値：無し
//
//===================================================================
void CEcuRecvDlg::OnStart() 
{
	UpdateData(TRUE);

	// 保存フォルダ名入力チェック
	if (m_sSaveFolder.IsEmpty()) {
		AfxMessageBox("保存フォルダ名を入力してください。");
		return;
	}

	//　保存ファイル存在チェック
	if (SaveFolderCheck(m_sSaveFolder)) {
		if (AfxMessageBox("指定したフォルダにすでにファイルがあります。上書きしますか？",
				MB_ICONQUESTION | MB_OKCANCEL) == IDCANCEL)
			return;

		DeleteSaveFile(m_sSaveFolder);
	}

	// 保存用フォルダ作成
	CreateDirectory(m_sSaveFolder, NULL);

	// コントロールのイネーブル状態設定
	EnableControl(TRUE);

	// 試験回数、受信件数、受信状態消去
	m_sTexxxount.Empty();
	m_sReceiveCount.Empty();
	m_sReceiveStatus.Empty();
	UpdateData(FALSE);

	// 通信開始
	::StartTest(this, m_sSaveFolder);
}

//===================================================================
//
//	機能　：「停止」ボタン処理
//
//	引数　：無し
//
//	戻り値：無し
//
//===================================================================
void CEcuRecvDlg::OnStop() 
{
	//確認メッセージ
	if (AfxMessageBox("データ受信を停止します。よろしいですか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// コントロールのイネーブル状態設定
	EnableControl(FALSE);

	// 通信終了
	::StopTest();

	m_sReceiveStatus.Empty();
	UpdateData(FALSE);
}

//===================================================================
//
//	機能　：「終了」ボタン処理
//
//	引数　：無し
//
//	戻り値：無し
//
//===================================================================
void CEcuRecvDlg::OnCancel() 
{
	// ダイアログを終了
	EndDialog(IDCANCEL);
}

//===================================================================
//
//	機能　：コントロールのイネーブル状態設定処理
//
//	引数　：bStart - TRUE:開始  FALSE:停止
//
//	戻り値：無し
//
//===================================================================
void CEcuRecvDlg::EnableControl(BOOL bStart)
{
	if (bStart) {
		// 開始
		m_cSaveFolder.SetReadOnly(TRUE);
		m_cStart.EnableWindow(FALSE);
		m_cStop.EnableWindow(TRUE);
		m_cExit.EnableWindow(FALSE);
	} else {
		// 停止
		m_cSaveFolder.SetReadOnly(FALSE);
		m_cStart.EnableWindow(TRUE);
		m_cStop.EnableWindow(FALSE);
		m_cExit.EnableWindow(TRUE);
	}
}

//===================================================================
//
//	機能　：保存用フォルダ存在チェック
//
//	引数　：pSaveFolder - フォルダ名
//
//	戻り値：TRUE 存在する, FALSE 存在しない
//
//===================================================================
BOOL CEcuRecvDlg::SaveFolderCheck(CString &sSaveFolder)
{
	HANDLE	hDir;
	WIN32_FIND_DATA	findBuf;
	BOOL bFind = FALSE;

	// フォルダの存在チェック
	hDir = FindFirstFile(sSaveFolder, &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return FALSE;

	// ファイルの存在チェック
	hDir = FindFirstFile(sSaveFolder + "\\*", &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return FALSE;
	do {
		if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
			bFind = TRUE;
			break;
		}
	} while (FindNextFile(hDir, &findBuf));

	FindClose(hDir);

	return bFind;
}

//===================================================================
//
//	機能　：既存のファイル削除処理
//
//	引数　：pSaveFolder - フォルダ名
//
//	戻り値：無し
//
//===================================================================
void CEcuRecvDlg::DeleteSaveFile(CString &sSaveFolder)
{
	HANDLE	hDir;
	WIN32_FIND_DATA	findBuf;

	// フォルダ存在チェック
	hDir = FindFirstFile(sSaveFolder, &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return;

	// ファイル検索
	hDir = FindFirstFile(sSaveFolder + "\\*", &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return;

	// 存在するファイルを削除する
	do {
		if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY))
			DeleteFile(sSaveFolder + "\\" + findBuf.cFileName);
	} while (FindNextFile(hDir, &findBuf));

	FindClose(hDir);
}

//===================================================================
//
//	機能　：データ受信通知処理
//
//	引数　：wParam - メッセージパラメータ１
//			lParam - メッセージパラメータ２
//
//	戻り値：常に０
//
//===================================================================
LONG CEcuRecvDlg::OnReceiveNotify(WPARAM wParam, LPARAM lParam)
{
	switch (wParam) {
	case NOTIFY_TExxxOUNT:
		// 試験回数を表示
		m_sTexxxount = (LPCTSTR)lParam;
		UpdateData(FALSE);
		break;
	case NOTIFY_RECVCOUNT:
		// 受信件数を表示
		m_sReceiveCount = (LPCTSTR)lParam;
		UpdateData(FALSE);
		break;
	case NOTIFY_RECVSTATUS:
		// 受信状態を表示
		m_sReceiveStatus = (LPCTSTR)lParam;
		UpdateData(FALSE);
		break;
	case NOTIFY_TESTEND:
		// コントロールのイネーブル処理
		EnableControl(FALSE);
		break;
	}

	return 0;
}
