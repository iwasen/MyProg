// vjb30020Dlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "vjb30020.h"
#include "vjb30020Dlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// 状態コード
#define STATUS_READY		'N'
#define STATUS_EXECUTE		'1'
#define STATUS_NONCHECK		'2'
#define STATUS_EXECUTING	'3'
#define STATUS_IOERROR		'7'
#define STATUS_EXECUTEERROR	'8'

// キューファイルデータ長
#define QF_LEN_DATE						14
#define QF_LEN_BAITAI_SHUBETSU_CODE		3
#define QF_LEN_BAITAI_SHIKIBETSU_CODE	6
#define QF_LEN_STATUS					1
#define QF_LEN_VOLUMELABEL				6

// キューファイルデータ位置
#define QF_POS_DATE						0
#define QF_POS_BAITAI_SHUBETSU_CODE		14
#define QF_POS_BAITAI_SHIKIBETSU_CODE	17
#define QF_POS_STATUS					23
#define QF_POS_VOLUMELABEL				24

// タイマーID
#define TIMER_QUEUE			1

/////////////////////////////////////////////////////////////////////////////
// CVjb30020Dlg ダイアログ

CVjb30020Dlg::CVjb30020Dlg(CWnd* pParent /*=NULL*/)
	: CDialog(CVjb30020Dlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CVjb30020Dlg)
		// メモ: この位置に ClassWizard によってメンバの初期化が追加されます。
	//}}AFX_DATA_INIT
	// メモ: LoadIcon は Win32 の DestroyIcon のサブシーケンスを要求しません。
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CVjb30020Dlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CVjb30020Dlg)
	DDX_Control(pDX, IDC_NONCHECK, m_cNonCheck);
	DDX_Control(pDX, IDC_QUEUE_LIST, m_cQueueList);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CVjb30020Dlg, CDialog)
	//{{AFX_MSG_MAP(CVjb30020Dlg)
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_BN_CLICKED(IDC_NONCHECK, OnNoncheck)
	ON_BN_CLICKED(IDC_RELOAD, OnReload)
	ON_WM_TIMER()
	ON_WM_DESTROY()
	ON_NOTIFY(NM_CLICK, IDC_QUEUE_LIST, OnClickQueueList)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30020Dlg メッセージ ハンドラ

//
//	機能	：	ダイアログ開始処理
//
//	引数	：	無し
//
//	復帰値	：	TRUE - フォーカスの設定
//
//	機能説明：	媒体出力管理ダイアログの初期化処理を行う。
//
//	備考	：	ダイアログ表示時にフレームワークより呼ばれる。
//
BOOL CVjb30020Dlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// このダイアログ用のアイコンを設定します。フレームワークはアプリケーションのメイン
	// ウィンドウがダイアログでない時は自動的に設定しません。
	SetIcon(m_hIcon, TRUE);			// 大きいアイコンを設定
	SetIcon(m_hIcon, FALSE);		// 小さいアイコンを設定

	// 初期化処理
	Initialize();

	// リストカラム設定
	SetLixxxolumn();

	// キューリストの表示
	SetQueueList(FALSE);
	
	return TRUE;  // TRUE を返すとコントロールに設定したフォーカスは失われません。
}

// もしダイアログボックスに最小化ボタンを追加するならば、アイコンを描画する
// コードを以下に記述する必要があります。MFC アプリケーションは document/view
// モデルを使っているので、この処理はフレームワークにより自動的に処理されます。

void CVjb30020Dlg::OnPaint() 
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
HCURSOR CVjb30020Dlg::OnQueryDragIcon()
{
	return (HCURSOR) m_hIcon;
}

//
//	機能	：	実行ボタンクリック処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	外部媒体出力を実行する。
//
//	備考	：	無し
//
void CVjb30020Dlg::OnOK() 
{
	// 実行処理
	Execute(STATUS_EXECUTE);
}

//
//	機能	：	ラベルノンチェック実行ボタンクリック処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	ラベルノンチェックで外部媒体出力を実行する。
//
//	備考	：	無し
//
void CVjb30020Dlg::OnNoncheck() 
{
	// ラベルノンチェック実行処理
	Execute(STATUS_NONCHECK);
}

//
//	機能	：	最新の状態に更新ボタンクリック処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	キューファイルを読み込み、表示を最新の状態に更新する。
//
//	備考	：	無し
//
void CVjb30020Dlg::OnReload() 
{
	// キューリスト表示
	SetQueueList(FALSE);
}

//
//	機能	：	タイマー処理
//
//	引数	：	nIDEvent - タイマーID
//
//	復帰値	：	無し
//
//	機能説明：	キューファイルを読み込み、表示を最新の状態に更新する。
//
//	備考	：	無し
//
void CVjb30020Dlg::OnTimer(UINT nIDEvent) 
{
	// キューリスト表示
	SetQueueList(TRUE);

	CDialog::OnTimer(nIDEvent);
}

//
//	機能	：	ダイアログ終了処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	キューリストを削除する。（メモリ解放のため）
//
//	備考	：	無し
//
void CVjb30020Dlg::OnDestroy() 
{
	// キューリスト削除
	DeleteQueueList();

	CDialog::OnDestroy();
}

//
//	機能	：	初期化処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	自動更新タイマーをセットする。
//
//	備考	：	無し
//
void CVjb30020Dlg::Initialize()
{
	// キューファイル監視タイマー設定
	if (g_nTimer != 0)
		SetTimer(TIMER_QUEUE, g_nTimer * 60 * 1000, NULL);
}

//
//	機能	：	キューリストカラム設定処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	キューリストのカラム情報を設定する。
//
//	備考	：	無し
//
void CVjb30020Dlg::SetLixxxolumn()
{
	struct LIxxxOLUMN {
		char *text;
		int fmt;
		int width;
	} tColumn[] = {
		{"状態", LVCFMT_LEFT, 100},
		{"媒体識別コード", LVCFMT_LEFT, 100},
		{"Volラベル名", LVCFMT_LEFT, 100},
		{"媒体種別", LVCFMT_LEFT, 100},
		{"作成日時", LVCFMT_LEFT, 130}
	};
	int i;
	LV_COLUMN lvc;

	// 行選択スタイル
	m_cQueueList.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// 各カラムを設定
	for (i = 0; i < sizeof(tColumn) / sizeof(LIxxxOLUMN); i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = tColumn[i].fmt;
		lvc.pszText = tColumn[i].text;
		lvc.cx = tColumn[i].width;
		m_cQueueList.InsertColumn(i, &lvc);
	}
}

//
//	機能	：	キューリスト情報設定処理
//
//	引数	：	bSendMessage - 運用管理クライアントに通知フラグ
//
//	復帰値	：	無し
//
//	機能説明：	キューファイルを読み込み、キューリストに表示する。
//
//	備考	：	無し
//
void CVjb30020Dlg::SetQueueList(BOOL bSendMessage)
{
	HANDLE hDir;
	WIN32_FIND_DATA findBuf;
	Queue *pQueue, *pQueueNew;
	int nItem;
	int i;

	// ちらつきを防止するため再描画を禁止
	//m_cQueueList.LockWindowUpdate();

	// 有効フラグをクリア
	nItem = m_cQueueList.GetItemCount();
	for (i = 0; i < nItem; i++) {
		pQueue = (Queue *)m_cQueueList.GetItemData(i);
		pQueue->bAvail = FALSE;
	}

	// キューファイルの検索
	hDir = FindFirstFile(g_sQueueDir + "\\*", &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return;

	do {
		// リストに追加
		if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
			pQueueNew = GetQueueData(findBuf.cFileName);
			nItem = m_cQueueList.GetItemCount();
			for (i = 0; i < nItem; i++) {
				pQueue = (Queue *)m_cQueueList.GetItemData(i);
				if (pQueue->sCreateDate == pQueueNew->sCreateDate
						&& pQueue->sShubetsuCode == pQueueNew->sShubetsuCode
						&& pQueue->sShikibetsuCode == pQueueNew->sShikibetsuCode) {
					pQueue->sFileName = pQueueNew->sFileName;
					pQueue->cStatus = pQueueNew->cStatus;
					pQueue->bAvail = TRUE;
					m_cQueueList.SetItemText(i, 0, GetStatusText(pQueue->cStatus));
					delete pQueueNew;
					break;
				}
			}
			if (i == nItem) {
				// キューリストに追加
				AddQueueList(pQueueNew);

				// メッセージ表示
				if (bSendMessage) {
					DispMessage(IDS_MSG_OUTPUT_REQUEST, MB_OK, TRUE,
							pQueueNew->sShikibetsuCode, pQueueNew->sVolLabel, pQueueNew->sShubetsuCode,
							FormatDate(pQueueNew));
				}
			}
		}
	} while (FindNextFile(hDir, &findBuf));

	// ハンドルのクローズ
	FindClose(hDir);

	// 有効でない項目を削除
	nItem = m_cQueueList.GetItemCount();
	for (i = 0; i < nItem; i++) {
		pQueue = (Queue *)m_cQueueList.GetItemData(i);
		if (!pQueue->bAvail) {
			delete pQueue;
			m_cQueueList.DeleteItem(i);
			i--;
			nItem--;
		}
	}

	// 再描画を許可
	//m_cQueueList.UnlockWindowUpdate();

	// ラベルノンチェック実行の有効／無効設定
	EnableNonCheck();
}

//
//	機能	：	キュー情報取得処理
//
//	引数	：	sFileName - キューファイル名
//
//	復帰値	：	キュー情報
//
//	機能説明：	キューファイルファイル名からキュー情報を取得する。
//
//	備考	：	無し
//
Queue *CVjb30020Dlg::GetQueueData(CString sFileName)
{
	Queue *pQueue = new Queue;

	// キューファイル名から各パラメータを取得
	pQueue->sFileName = sFileName;
	pQueue->sShubetsuCode = sFileName.Mid(QF_POS_BAITAI_SHUBETSU_CODE, QF_LEN_BAITAI_SHUBETSU_CODE);
	pQueue->sShikibetsuCode = sFileName.Mid(QF_POS_BAITAI_SHIKIBETSU_CODE, QF_LEN_BAITAI_SHIKIBETSU_CODE);
	pQueue->sVolLabel = sFileName.Mid(QF_POS_VOLUMELABEL);
	pQueue->sCreateDate = sFileName.Left(QF_LEN_DATE);
	pQueue->cStatus = sFileName[QF_POS_STATUS];
	pQueue->bAvail = TRUE;

	return pQueue;
}

//
//	機能	：	キューリスト削除処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	キューリストの内容を全て削除する。
//
//	備考	：	無し
//
void CVjb30020Dlg::DeleteQueueList()
{
	// キューリストの全削除
	while (m_cQueueList.GetItemCount() != 0) {
		delete (Queue *)m_cQueueList.GetItemData(0);
		m_cQueueList.DeleteItem(0);
	}
}

//
//	機能	：	キューリスト追加処理
//
//	引数	：	pQueue - キュー情報
//
//	復帰値	：	無し
//
//	機能説明：	キュー情報からキューリストに追加する。
//
//	備考	：	無し
//
void CVjb30020Dlg::AddQueueList(Queue *pQueue)
{
	// キューリストに項目を追加
	m_cQueueList.InsertItem(0, GetStatusText(pQueue->cStatus));
	m_cQueueList.SetItemText(0, 1, pQueue->sShikibetsuCode);
	m_cQueueList.SetItemText(0, 2, pQueue->sVolLabel.Left(LEN_VOLUME_LABEL));
	m_cQueueList.SetItemText(0, 3, pQueue->sShubetsuCode);
	m_cQueueList.SetItemText(0, 4, FormatDate(pQueue));

	// キューデータの保存
	m_cQueueList.SetItemData(0, (DWORD)pQueue);
}

//
//	機能	：	状態表示文字取得処理
//
//	引数	：	status - 状態コード
//
//	復帰値	：	状態表示文字
//
//	機能説明：	状態コードから状態表示文字を取得する。
//
//	備考	：	無し
//
CString CVjb30020Dlg::GetStatusText(char status)
{
	CString str;

	// 状態コードに対応する文字列を取得
	switch (status) {
	case STATUS_READY:
		str = "未";
		break;
	case STATUS_EXECUTE:
		str = "実行";
		break;
	case STATUS_NONCHECK:
		str = "ﾉﾝﾁｪｯｸ実行";
		break;
	case STATUS_EXECUTING:
		str = "実行中";
		break;
	case STATUS_IOERROR:
		str = "I/Oエラー";
		break;
	case STATUS_EXECUTEERROR:
		str = "実行エラー";
		break;
	default:
		str = "不明";
		break;
	}

	return str;
}

//
//	機能	：	外部媒体出力実行処理
//
//	引数	：	status - 状態コード
//
//	復帰値	：	状態表示文字
//
//	機能説明：	キュー情報を更新し、外部媒体出力プログラムを起動する。
//
//	備考	：	無し
//
void CVjb30020Dlg::Execute(char status)
{
	// キューリストから選択されている項目を取得
	POSITION pos = m_cQueueList.GetFirstSelectedItemPosition();
	if (pos == NULL) {
		DispMessage(IDS_MSG_DEVICE_SELECT, MB_OK, FALSE);
		return;
	}

	// キューデータを取得
	int nItem = m_cQueueList.GetNextSelectedItem(pos);
	Queue *pQueue = (Queue *)m_cQueueList.GetItemData(nItem);

	// すでに実行中の場合はメッセージ表示
	if (pQueue->cStatus == STATUS_EXECUTE || pQueue->cStatus == STATUS_NONCHECK || pQueue->cStatus == STATUS_EXECUTING) {
		DispMessage(IDS_MSG_EXECUTING, MB_OK, FALSE);
		return;
	}

	// 実行確認メッセージ表示
	if (status == STATUS_EXECUTE) {
		if (DispMessage(IDS_MSG_DEVICE_SET, MB_OKCANCEL, FALSE,
					pQueue->sShikibetsuCode, pQueue->sVolLabel, pQueue->sShubetsuCode) == IDCANCEL)
			return;
	} else {
		if (DispMessage(IDS_MSG_LABEL_NONCHECK, MB_OKCANCEL, FALSE,
					pQueue->sShikibetsuCode, pQueue->sVolLabel, pQueue->sShubetsuCode,
					FormatDate(pQueue), pQueue->sShubetsuCode) == IDCANCEL)
			return;
	}

	// 状態コードを1（実行）に設定
	ChangeStatus(pQueue, status, nItem);

	// 外部媒体出力プログラムを起動
	ExecuteCopyProgram(pQueue);
}

//
//	機能	：	外部媒体出力プログラム起動処理
//
//	引数	：	pQueue - キュー情報
//
//	復帰値	：	無し
//
//	機能説明：	キュー情報に従って外部媒体出力プログラムを起動する。
//
//	備考	：	無し
//
void CVjb30020Dlg::ExecuteCopyProgram(Queue *pQueue)
{
	CString sCmdLine;
	char cCurrentDir[256];
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

#if _DEBUG
	sCmdLine.Format("%s %s", "vjb30030\\debug\\vjb30030.exe", (LPCTSTR)pQueue->sFileName);
#else
	sCmdLine.Format("%s %s", "vjb30030.exe", (LPCTSTR)pQueue->sFileName);
#endif
	memset(&si, 0, sizeof(si));
	si.cb = sizeof(si);
	si.dwFlags = STARTF_USESHOWWINDOW;
	si.wShowWindow = SW_SHOW;
	GetCurrentDirectory(sizeof(cCurrentDir), cCurrentDir);
	CreateProcess(NULL, (LPTSTR)(LPCTSTR)sCmdLine, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, cCurrentDir, &si, &pi);
	CloseHandle(pi.hProcess);
	CloseHandle(pi.hThread);
}

//
//	機能	：　状態コード変更処理
//
//	引数	：	pQueue - キュー情報
//				status - 状態コード
//				nItem - キューリスト位置
//
//	復帰値	：	無し
//
//	機能説明：	キューファイルおよびキューリストの状態コードを変更する。
//
//	備考	：	無し
//
void CVjb30020Dlg::ChangeStatus(Queue *pQueue, char status, int nItem)
{
	CString sOldFileName, sNewFileName;

	// キューファイルのパス名を取得
	sOldFileName = CreatePathName(g_sQueueDir, pQueue->sFileName);

	// 状態コードを変更
	pQueue->sFileName.SetAt(QF_POS_STATUS, status);
	pQueue->cStatus = status;

	// 変更後のキューファイルのパス名を取得
	sNewFileName = CreatePathName(g_sQueueDir, pQueue->sFileName);

	// ファイル名変更
	if (MoveFile(sOldFileName, sNewFileName))
		m_cQueueList.SetItemText(nItem, 0, GetStatusText(status));
}

//
//	機能	：　日付データフォーマット処理
//
//	引数	：	pQueue - キュー情報
//
//	復帰値	：	表示用日付文字
//
//	機能説明：	キューファイルの日付データを表示用に編集する。
//
//	備考	：	無し
//
CString CVjb30020Dlg::FormatDate(Queue *pQueue)
{
	// 日付を表示用に編集
	return pQueue->sCreateDate.Mid(0, 4) + "/"
				+ pQueue->sCreateDate.Mid(4, 2) + "/"
				+ pQueue->sCreateDate.Mid(6, 2) + " "
				+ pQueue->sCreateDate.Mid(8, 2) + ":"
				+ pQueue->sCreateDate.Mid(10, 2) + ":"
				+ pQueue->sCreateDate.Mid(12, 2);
}

//
//	機能	：　キューリストクリック処理
//
//	引数	：	pNMHDR - 未使用
//				pResult - 結果
//
//	復帰値	：	無し
//
//	機能説明：	ラベルノンチェック実行ボタンの有効／無効を設定する。
//
//	備考	：	無し
//
void CVjb30020Dlg::OnClickQueueList(NMHDR* pNMHDR, LRESULT* pResult) 
{
	// ラベルノンチェック実行ボタンの有効／無効の設定
	EnableNonCheck();
	
	*pResult = 0;
}

//
//	機能	：　ラベルノンチェック実行ボタンの有効／無効設定処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	キューリストで選択されているデータにより、
//				ラベルノンチェック実行ボタンの有効／無効を設定する。
//
//	備考	：	無し
//
void CVjb30020Dlg::EnableNonCheck()
{
	int i, nItem;
	BOOL bEnable = FALSE;

	// キューリストから選択されている項目を取得
	nItem = m_cQueueList.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (m_cQueueList.GetItemState(i, LVIS_SELECTED) != 0)
			break;
	}

	// 状態コードが8（実行エラー）であれば有効
	if (i < nItem) {
		Queue *pQueue = (Queue *)m_cQueueList.GetItemData(i);
		if (pQueue->cStatus == STATUS_EXECUTEERROR)
			bEnable = TRUE;
	}
	m_cNonCheck.EnableWindow(bEnable);
}
