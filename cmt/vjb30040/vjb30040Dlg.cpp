// vjb30040Dlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "vjb30040.h"
#include "InputDevice.h"
#include "vjb30040Dlg.h"
#include "InputFPD.h"
#include "InputMOD.h"
#include "InputCMT.h"
#include "InputDAT.h"

// 定数定義 

// コンボボックスインデックス
#define DEVICE_CMT  0
#define DEVICE_DAT  1
#define DEVICE_FPD  2
#define DEVICE_MOD  3

// テキスト内容消去処理(DeleteEditInfo)パラメータ
#define LABEL_ARINASHI_CHECK_STATUS  0
#define ELSE_STATUS  1

// 媒体識別コード入力可能文字列
#define DEVICE_SHIKIBETSU_CODE  "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890" 

// Volラベル名入力可能文字列
#define DEVICE_VOL_NAME  "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-" 

// Vol本数入力値チェック用
#define DEVICE_VOL_SU  "1234567890" 

// 定義文字列
#define DEFINE_SLASH  "/" 
#define DEFINE_COMMA  "," 

// 入力データ処理分岐値
#define CMT_LABEL_ARI_KANMA  0 
#define CMT_LABEL_NASHI_KANMA  1 

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CVjb30040Dlg ダイアログ

CVjb30040Dlg::CVjb30040Dlg(CWnd* pParent /*=NULL*/)
	: CDialog(CVjb30040Dlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CVjb30040Dlg)
	m_iRadioLabel = -1;
	m_iComboDeviceName = -1;
	m_sEditVolFile = _T("");
	m_sEditShikibetsuCode = _T("");
	m_sEditVolSu = _T("");
	//}}AFX_DATA_INIT
	// メモ: LoadIcon は Win32 の DestroyIcon のサブシーケンスを要求しません。
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CVjb30040Dlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CVjb30040Dlg)
	DDX_Control(pDX, IDC_EDIT_SHIKIBETSU_CODE, m_EDIT_SHIKIBETSU_CODE);
	DDX_Control(pDX, IDC_COMBO_SRV_NAME, m_COMBO_SRV_NAME);
	DDX_Control(pDX, IDC_COMBO_DEVICE_NAME, m_COMBO_DEVICE_NAME);
	DDX_Control(pDX, IDC_EDIT_VOL_SU, m_EDIT_VOL_SU);
	DDX_Control(pDX, IDC_LABEL_VOL_SU, m_LABEL_VOL_SU);
	DDX_Control(pDX, IDC_EDIT_VOL_FILE, m_EDIT_VOL_FILE);
	DDX_Control(pDX, IDC_LABEL_FILE_NAME, m_LABEL_FILE_NAME);
	DDX_Control(pDX, IDC_LABEL_VOL_NAME, m_LABEL_VOL_NAME);
	DDX_Control(pDX, IDC_RADIO_LABEL_ARI, m_RADIO_LABEL_ARI);
	DDX_Control(pDX, IDC_RADIO_LABEL_NASHI, m_RADIO_LABEL_NASHI);
	DDX_Radio(pDX, IDC_RADIO_LABEL_ARI, m_iRadioLabel);
	DDX_CBIndex(pDX, IDC_COMBO_DEVICE_NAME, m_iComboDeviceName);
	DDX_Text(pDX, IDC_EDIT_VOL_FILE, m_sEditVolFile);
	DDX_Text(pDX, IDC_EDIT_SHIKIBETSU_CODE, m_sEditShikibetsuCode);
	DDX_Text(pDX, IDC_EDIT_VOL_SU, m_sEditVolSu);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CVjb30040Dlg, CDialog)
	//{{AFX_MSG_MAP(CVjb30040Dlg)
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_CBN_SELCHANGE(IDC_COMBO_DEVICE_NAME, OnSelchangeComboDeviceName)
	ON_BN_CLICKED(IDC_RADIO_LABEL_ARI, OnRadioLabelAri)
	ON_BN_CLICKED(IDC_RADIO_LABEL_NASHI, OnRadioLabelNashi)
	ON_BN_CLICKED(IDC_BUTTON_ZIKOU, OnButtonZikou)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30040Dlg メッセージ ハンドラ

BOOL CVjb30040Dlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// このダイアログ用のアイコンを設定します。フレームワークはアプリケーションのメイン
	// ウィンドウがダイアログでない時は自動的に設定しません。
	SetIcon(m_hIcon, TRUE);			// 大きいアイコンを設定
	SetIcon(m_hIcon, FALSE);		// 小さいアイコンを設定
	
	// 初期表示画面設定処理
    SetInitInstance();
	
	// 入力媒体設定処理
	SetDeviceName();
	
	// 出力先サーバ名設定処理
	SetServerName();
	
	return TRUE;  // TRUE を返すとコントロールに設定したフォーカスは失われません。
}

// もしダイアログボックスに最小化ボタンを追加するならば、アイコンを描画する
// コードを以下に記述する必要があります。MFC アプリケーションは document/view
// モデルを使っているので、この処理はフレームワークにより自動的に処理されます。

void CVjb30040Dlg::OnPaint() 
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
HCURSOR CVjb30040Dlg::OnQueryDragIcon()
{
	return (HCURSOR) m_hIcon;
}

//
//	機能	：	初期画面設定処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	媒体入力指示画面の初期表示状態を設定する。
//
//	備考	：	無し
//
void CVjb30040Dlg::SetInitInstance()
{
	// 媒体入力指示画面初期設定処理
	m_RADIO_LABEL_ARI.ShowWindow(SW_HIDE);
	m_RADIO_LABEL_NASHI.ShowWindow(SW_HIDE);
	m_LABEL_VOL_NAME.ShowWindow(SW_HIDE);
	m_LABEL_FILE_NAME.ShowWindow(SW_HIDE);
	m_EDIT_VOL_FILE.ShowWindow(SW_HIDE);
	m_LABEL_VOL_SU.ShowWindow(SW_HIDE);
	m_EDIT_VOL_SU.ShowWindow(SW_HIDE);
}

//
//	機能	：	入力媒体設定処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	入力媒体(コンボボックス)に、CMT、DAT、FPD、MOを設定する。
//
//	備考	：	無し
//
void CVjb30040Dlg::SetDeviceName()
{
	// 入力媒体設定処理
	m_COMBO_DEVICE_NAME.AddString("CMT");
	m_COMBO_DEVICE_NAME.AddString("DAT");
	m_COMBO_DEVICE_NAME.AddString("FPD");
	m_COMBO_DEVICE_NAME.AddString("MO");
}

//
//	機能	：	出力先バッチサーバ名取得処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	バッチサーバ指定ファイルを読込む。
//				出力先バッチサーバ(コンボボックス)に、バッチサーバ指定ファイルのデータを設定する。
//
//	備考	：	無し
//
void CVjb30040Dlg::SetServerName()
{	
	CStdioFile	csMyCStdio;
	CString		csFileData;

	// ファイルをオープン
	if (csMyCStdio.Open(g_BatchServerFile, CFile::modeRead, NULL)) {
		// NULLになるまでループ
		while(csMyCStdio.ReadString(csFileData) != NULL) {
			// 出力先バッチサーバ名をコンボボックスに設定する。
			m_COMBO_SRV_NAME.AddString(csFileData);
		}
		
		// ファイルをクローズ
		csMyCStdio.Close();
	}
}
	
//
//	機能	：	コンボボックス(入力媒体)セルチェンジ処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	コンボボックス(入力媒体)セルがチェンジした場合の一連の処理を行う。
//
//	備考	：	無し
//
void CVjb30040Dlg::OnSelchangeComboDeviceName() 
{
	int nIndex;
	
	nIndex = m_COMBO_DEVICE_NAME.GetCurSel();
	if (nIndex == CB_ERR)
		return;

	// DAT、FPD、MOの場合
	if (nIndex == DEVICE_DAT || nIndex == DEVICE_FPD || nIndex == DEVICE_MOD) {
		// 表示非表示設定
		m_LABEL_VOL_NAME.ShowWindow(nIndex == DEVICE_DAT ? SW_HIDE : SW_SHOW);
		m_EDIT_VOL_FILE.ShowWindow(nIndex == DEVICE_DAT ? SW_HIDE : SW_SHOW);
		m_RADIO_LABEL_ARI.ShowWindow(SW_HIDE);
		m_RADIO_LABEL_NASHI.ShowWindow(SW_HIDE);
		m_LABEL_FILE_NAME.ShowWindow(SW_HIDE);
		m_EDIT_VOL_SU.ShowWindow(SW_HIDE);
		m_LABEL_VOL_SU.ShowWindow(SW_HIDE);
		
		// ラジオ初期設定処理
		SetInitRadio();
		
		// テキスト内容を消去
		DeleteEditInfo(ELSE_STATUS);
	}
	// CMTの場合
	else{
		// 表示非表示設定
		m_RADIO_LABEL_ARI.ShowWindow(SW_SHOW);
		m_RADIO_LABEL_NASHI.ShowWindow(SW_SHOW);
		m_LABEL_VOL_NAME.ShowWindow(SW_HIDE);
		m_EDIT_VOL_FILE.ShowWindow(SW_HIDE);
		m_LABEL_FILE_NAME.ShowWindow(SW_HIDE);
		
		// ラジオ初期設定処理
		SetInitRadio();
		
		// テキスト内容を消去処理
		DeleteEditInfo(ELSE_STATUS);
	}
}

//
//	機能	：	ラジオボタンありクリック処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	ラジオボタンありをクリックした場合の一連の処理を行う。
//
//	備考	：	無し
//
void CVjb30040Dlg::OnRadioLabelAri() 
{
	// 表示非表示設定
	m_LABEL_VOL_NAME.ShowWindow(SW_SHOW);
	m_EDIT_VOL_FILE.ShowWindow(SW_SHOW);		
	m_LABEL_FILE_NAME.ShowWindow(SW_HIDE);
	m_EDIT_VOL_SU.ShowWindow(SW_HIDE);
	m_LABEL_VOL_SU.ShowWindow(SW_HIDE);

	// テキスト内容を消去
	DeleteEditInfo(LABEL_ARINASHI_CHECK_STATUS);
}

//
//	機能	：	ラジオボタン無しクリック処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	ラジオボタン無しをクリックした場合の一連の処理を行う。
//
//	備考	：	無し
//
void CVjb30040Dlg::OnRadioLabelNashi() 
{
	// 表示非表示設定
	m_LABEL_VOL_NAME.ShowWindow(SW_HIDE);
	m_EDIT_VOL_FILE.ShowWindow(SW_SHOW);		
	m_LABEL_FILE_NAME.ShowWindow(SW_SHOW);
	m_EDIT_VOL_SU.ShowWindow(SW_SHOW);
	m_LABEL_VOL_SU.ShowWindow(SW_SHOW);

	// テキスト内容を消去
	DeleteEditInfo(LABEL_ARINASHI_CHECK_STATUS);
}

//
//	機能	：	実行ボタン押下処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	実行ボタン押下後の処理を実行、制御する。
//
//	備考	：	無し
//
void CVjb30040Dlg::OnButtonZikou() 
{
	// 入力媒体、出力先バッチサーバ、媒体識別コード入力チェック処理 
	if (CheckData() == false)
		return;

	// DEVICEごとの入力チェック処理
	if (CheckDeviceInfo() == false)
		return;
	
	// 媒体識別コード入力値妥当性チェック処理
	if (CheckDeviceCodeData() == false)
		return;

	// Volラベル名、ファイル名/レコード長、Vol本数入力値妥当性チェック処理
	if (CheckVolFileVolsuData() == false)
		return;

	Execute();
}

//
//	機能	：	各オブジェクト内容チェック処理
//
//	引数	：	無し
//
//	復帰値	：		bool   TRUE : チェックOK
//			：		       FALSE : チェックNG
//
//	機能説明：	各オブジェクトの状態をチェックする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckData() 
{
	// 入力媒体をチェック
	if (CheckNyuryokuData(&m_COMBO_DEVICE_NAME) == false) {
		// メッセージ表示処理
		DispMessage(IDS_MSG_301, MB_OK, FALSE);
		
		m_COMBO_DEVICE_NAME.SetFocus();
		return false;
	}

	// 出力先バッチサーバをチェック
	if (CheckNyuryokuData(&m_COMBO_SRV_NAME) == false) {
		
		// メッセージ表示処理
		DispMessage(IDS_MSG_302, MB_OK, FALSE);

		m_COMBO_SRV_NAME.SetFocus();
		return false;
	}

	// 媒体識別コードをチェック
	if (CheckNyuryokuData(&m_EDIT_SHIKIBETSU_CODE) == false) {
	
		// メッセージ表示処理
		DispMessage(IDS_MSG_303, MB_OK, FALSE);
		
		m_EDIT_SHIKIBETSU_CODE.SetFocus();
		return false;
	}

	return true;
}

//
//	機能	：	「実行」ボタン処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	媒体からファイルを入力する。
//
//	備考	：	無し
//
void CVjb30040Dlg::Execute()
{
	Param param;
	CInputDevice *pInputDevice;
	int nRetCode;
	char *pStatus;
	int nErrorCode;
	CString sErrorMsg;

	// 入力データを取得
	UpdateData(TRUE);

	// パラメータ構造体にセット
	param.nBaitaiShubetsu = m_iComboDeviceName;
	m_COMBO_DEVICE_NAME.GetWindowText(param.sShubetsuCode);
	param.sShikibetsuCode = m_sEditShikibetsuCode;
	m_COMBO_SRV_NAME.GetWindowText(param.sBatchServer);
	param.sVolLabel = m_sEditVolFile;
	param.bLabel = m_iRadioLabel == 0 ? TRUE : FALSE;
	param.nVolCount = atoi(m_sEditVolSu);

	// 入力媒体クラスを生成
	switch (param.nBaitaiShubetsu) {
	case DEVICE_FPD:
		pInputDevice = new CInputFPD;
		break;
	case DEVICE_MOD:
		pInputDevice = new CInputMOD;
		break;
	case DEVICE_CMT:
		pInputDevice = new CInputCMT;
		break;
	case DEVICE_DAT:
		pInputDevice = new CInputDAT;
		break;
	default:
		return;
	}

	// 開始ログ書き込み
	WriteLogFile(&param, CreatePathName(g_sLogDir, "nyuryokustart"), NULL);

	// 媒体入力処理
	nRetCode = InputDevice(&param, pInputDevice);
	switch (nRetCode) {
	case RC_NORMAL:			// 正常終了
		ExecuteFileCopyPrg(&param);
		pStatus = "0";
		break;
	case RC_VOLUMENAME:		// ボリュームラベルが違う
		DispMessage(IDS_MSG_DIFF_VOLLABEL, MB_OK, TRUE);
		pStatus = "7";
		break;
	case RC_IOERROR:		// I/O エラー
		pInputDevice->GetError(nErrorCode, sErrorMsg);
		if (nErrorCode == 0)
			DispMessage(IDS_MSG_COPY_ERROR, MB_OK, TRUE, param.sShubetsuCode);
		else
			DispMessage(IDS_MSG_COPY_ERROR2, MB_OK, TRUE, param.sShubetsuCode, -nErrorCode, sErrorMsg);
		pStatus = "8";
		break;
	default:
		pStatus = "7";
		break;
	}

	// 終了ログ書き込み
	WriteLogFile(&param, CreatePathName(g_sLogDir, "nyuryokuend"), pStatus);

	// 入力媒体クラスの破棄
	delete pInputDevice;
}

//
//	機能	：	媒体入力処理
//
//	引数	：	pParam - 入力パラメータ
//				pInputDevice - 入力媒体クラス
//
//	復帰値	：	処理結果コード
//
//	機能説明：	媒体からファイルを入力する。
//
//	備考	：	無し
//
int CVjb30040Dlg::InputDevice(Param *pParam, CInputDevice *pInputDevice)
{
	int nRetCode;

	pInputDevice->SetUpdateWindow(this);

	// 確認メッセージ表示
	if ((nRetCode = pInputDevice->KakuninMessage(pParam)) != RC_NORMAL)
		return nRetCode;

	// ボリュームラベルチェック
	if ((nRetCode = pInputDevice->CheckVolumeName(pParam)) != RC_NORMAL)
		return nRetCode;

	// 媒体から入力
	if ((nRetCode = pInputDevice->InputFiles(pParam)) != RC_NORMAL)
		return nRetCode;

	return RC_NORMAL;
}

//
//	機能	：	ファイルコピープログラム起動処理
//
//	引数	：	pParam - 入力パラメータ
//
//	復帰値	：	無し
//
//	機能説明：	ファイルコピープログラム(VJB30050.EXE)を起動する。
//
//	備考	：	無し
//
void CVjb30040Dlg::ExecuteFileCopyPrg(Param *pParam)
{
	CString sCmdLine;
	CString sVolLabel;
	char cCurrentDir[256];
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

	// ボリュームラベル名取得
	switch (pParam->nBaitaiShubetsu) {
	case DEVICE_CMT:
		if (pParam->bLabel)
			sVolLabel = pParam->sVolLabel.SpanExcluding(",");
		else
			sVolLabel = "000000";
		break;
	case DEVICE_DAT:
		sVolLabel = "000000";
		break;
	default:
		sVolLabel = pParam->sVolLabel;
		break;
	}

	// コマンドラインを生成
#if _DEBUG
	sCmdLine.Format("%s %s %s %s %s", "vjb30050\\debug\\vjb30050.exe",
			(LPCTSTR)pParam->sShubetsuCode,
			(LPCTSTR)pParam->sBatchServer,
			(LPCTSTR)pParam->sShikibetsuCode,
			(LPCTSTR)sVolLabel);
#else
	sCmdLine.Format("%s %s %s %s %s", "vjb30050.exe",
			(LPCTSTR)pParam->sShubetsuCode,
			(LPCTSTR)pParam->sBatchServer,
			(LPCTSTR)pParam->sShikibetsuCode,
			(LPCTSTR)sVolLabel);
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
//	機能	：	入力媒体・出力先バッチサーバ名・ラジオボタンのチェック処理
//
//	引数	：	無し
//
//	復帰値	：		bool   TRUE : チェックOK
//			：		       FALSE : チェックNG
//
//	機能説明：	上記のオブジェクトの状態をチェックする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckDeviceInfo()
{	
	int nIndex, nCheckLabelAri, nCheckLabelNashi;

	nIndex = m_COMBO_DEVICE_NAME.GetCurSel();
	if (nIndex == CB_ERR)
		return false;

	if (nIndex == DEVICE_FPD || nIndex == DEVICE_MOD) {
		
		if (CheckNyuryokuData(&m_EDIT_VOL_FILE) == false) {
			
			// メッセージ表示処理
			DispMessage(IDS_MSG_304, MB_OK, FALSE);

			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	else if (nIndex == DEVICE_CMT) {
		if ((nCheckLabelAri=m_RADIO_LABEL_ARI.GetCheck()) == 0) {
			if ((nCheckLabelNashi = m_RADIO_LABEL_NASHI.GetCheck()) == 0) {
				// メッセージ表示処理
				DispMessage(IDS_MSG_305, MB_OK, FALSE);
				
				return false;
			}
			else {
				if (CheckNyuryokuData(&m_EDIT_VOL_FILE) == false) {
					// メッセージ表示処理
					DispMessage(IDS_MSG_306, MB_OK, FALSE);
					
					m_EDIT_VOL_FILE.SetFocus();
					return false;
				}
			
				if (CheckNyuryokuData(&m_EDIT_VOL_SU) == false) {
				
					// メッセージ表示処理
					DispMessage(IDS_MSG_307, MB_OK, FALSE);
										
					m_EDIT_VOL_FILE.SetFocus();
					return false;
				}
			}
		}
		else {
			if (CheckNyuryokuData(&m_EDIT_VOL_FILE) == false) {
				// メッセージ表示処理
				DispMessage(IDS_MSG_304, MB_OK, FALSE);
				
				m_EDIT_VOL_FILE.SetFocus();
				return false;
			}
		}
	}
	return true;
}

//
//	機能	：	エディット値消去処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	各エディットに空文字列をセットする。
//
//	備考	：	無し
//
void CVjb30040Dlg::DeleteEditInfo(int p_iCode) 
{
	// テキストデータを消去
	switch (p_iCode) {
	case ELSE_STATUS:
		m_EDIT_SHIKIBETSU_CODE.SetWindowText("");
		m_EDIT_VOL_FILE.SetWindowText("");
		m_EDIT_VOL_SU.SetWindowText("");

	case LABEL_ARINASHI_CHECK_STATUS:
		m_EDIT_VOL_FILE.SetWindowText("");
		m_EDIT_VOL_SU.SetWindowText("");
	}
}

//
//	機能	：	ラジオボタン初期設定処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	ラジオボタンを未チェックに設定する。
//
//	備考	：	無し
//
void CVjb30040Dlg::SetInitRadio() 
{
	// ラジオボタンをチェック無しに設定
	m_RADIO_LABEL_ARI.SetCheck(0);
	m_RADIO_LABEL_NASHI.SetCheck(0);	
}

//
//	機能	：	各データ入力値取得・チェック処理
//
//	引数	：	p_uObjectID - オブジェクトＩＤ
//
//	復帰値	：	bool   TRUE : チェックOK
//			：		   FALSE : チェックNG
//
//	機能説明：	各データ入力値取得し、空文字列の場合、復帰値にFALSEをセットする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckNyuryokuData(CWnd *p_Wnd)
{
	CString csTemp;

	p_Wnd -> GetWindowText(csTemp);

	if (csTemp == "") {
		return false;
	}

	return true;
}

//
//	機能	：	媒体識別コード入力値チェック処理
//
//	引数	：	無し
//
//	復帰値	：	bool   TRUE : チェックOK
//			：		   FALSE : チェックNG
//
//	機能説明：	媒体識別コード入力値をチェックする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckDeviceCodeData()
{
	CString csShikibetsuCode, csSpanIncludeData;
	int iLen;

	// 桁数チェック
	if (m_EDIT_SHIKIBETSU_CODE.GetWindowTextLength() !=6) {
		// メッセージ表示処理
		DispMessage(IDS_MSG_308, MB_OK, FALSE);
		
		m_EDIT_SHIKIBETSU_CODE.SetFocus();	
		return false;
	}

	// 媒体識別コードを取得
	m_EDIT_SHIKIBETSU_CODE.GetWindowText(csShikibetsuCode);
	
	// 入力文字列チェック
	csSpanIncludeData=csShikibetsuCode.SpanIncluding(DEVICE_SHIKIBETSU_CODE);
	
	// 取得した文字列の桁数をチェック
	if (iLen = csSpanIncludeData.GetLength() != 6) {
		// メッセージ表示処理
		DispMessage(IDS_MSG_309, MB_OK, FALSE);
		
		m_EDIT_SHIKIBETSU_CODE.SetFocus();	
		return false;
	}

	return true;
}


//
//	機能	：	Volラベル名・ファイル名レコード長・Vol本数入力値チェック処理
//
//	引数	：	無し
//
//	復帰値	：	bool   TRUE : チェックOK
//			：		   FALSE : チェックNG
//
//	機能説明：	各入力値をチェックする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckVolFileVolsuData()
{
	int nIndex, nCheck, nKanmaLen;
	CString csVolLabelData;

	nIndex = m_COMBO_DEVICE_NAME.GetCurSel();
	
	// Volラベル名データを取得
	m_EDIT_VOL_FILE.GetWindowText(csVolLabelData);

	// FPD、MO、DATの場合
	if (nIndex == DEVICE_FPD || nIndex == DEVICE_MOD) {
		// ","文字列を検索する。
		nKanmaLen = csVolLabelData.Find(DEFINE_COMMA);		

		// カンマ文字列の存在チェック
		if (nKanmaLen > 0) {
				DispMessage(IDS_MSG_310, MB_OK, FALSE);
				m_EDIT_VOL_FILE.SetFocus();
				return false;
		}
		
		// 入力値"SpanInclude"チェック処理
		if (CheckVolNyuryokuData(csVolLabelData) == false) {
			DispMessage(IDS_MSG_310, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}	
	// CMTの場合
	else if (nIndex == DEVICE_CMT) {
		// ラベルありがチェックされている場合
		if ((nCheck=m_RADIO_LABEL_ARI.GetCheck()) == 1) {
			
			// Volラベル名入力値妥当性チェック
			if (CheckLabelAriVolData(csVolLabelData) == false) {
				return false;
			}
		}
		else {
			// ファイル名/レコード長入力値妥当性チェック
			if (CheckLabelNashiFileNameRecord() == false) {
				return false;
			}
			
			// Vol本数入力値妥当性チェック
			if (CheckLabelNashiVolSu() == false) {
				return false;
			}
		}
	}
	return true;
}

//
//	機能	：	Volラベル名入力値チェック処理
//
//	引数	：	p_csVolLabelData - Volラベル入力値
//
//	復帰値	：	bool : true - チェックOK
//					   false - チェックNG
//
//	機能説明：	Volラベル名の入力文字列の妥当性・桁数をチェックする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckVolNyuryokuData(CString &p_csVolLabelData)
{
	CString csCheckData;
	
	// パラメータVolラベル名の桁数チェック
	if (p_csVolLabelData.GetLength() != 6) {
		return false;
	}

	// Volラベル名入力値、チェック
	csCheckData = p_csVolLabelData.SpanIncluding(DEVICE_VOL_NAME);

	// チェック後の値の桁数をチェック
	if ((csCheckData.GetLength()) != 6) {
		return false;
	}
	
	return true;	
}

//
//	機能	：	Volラベル名入力値妥当性チェック
//
//	引数	：	&p_csVolLabelData - Volラベル名データ
//
//	復帰値	：	bool : true - チェックOK
//					 : false - チェックNG	
//
//	機能説明：	入力媒体CMT、ラベルありチェック、Volラベル名の入力データの妥当性をチェックする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckLabelAriVolData(CString &p_csVolLabelData)
{
	int nKanmaLen;

	// ","文字列を検索する。
	nKanmaLen=p_csVolLabelData.Find(DEFINE_COMMA);

	// カンマが文字列が含まれない場合
	if (nKanmaLen < 0) {
		// 入力値妥当性チェック処理
		if (CheckVolNyuryokuData(p_csVolLabelData) == false) {
			DispMessage(IDS_MSG_310, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	// カンマが文字列に含まれる場合
	else {
		if (CheckLabelAriNashiKanmaData(p_csVolLabelData, CMT_LABEL_ARI_KANMA) == false) {
			DispMessage(IDS_MSG_310, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	return true;
}


//
//	機能	：	Vol本数入力値妥当性チェック
//
//	引数	：	無し
//
//	復帰値	：	bool : true - チェックOK
//					 : false - チェックNG	
//
//	機能説明：	Vol本数の入力値が数値かどうかチェックする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckLabelNashiVolSu()
{
	CString csVolSu, csTemp, csCheckData;
	int iLength, iLoopIndex;

	// Vol本数データを取得
	m_EDIT_VOL_SU.GetWindowText(csVolSu);

	// 桁数取得
	iLength = csVolSu.GetLength();

	// 文字列の桁数分ループする。
	for (iLoopIndex = 0; iLoopIndex <= (iLength - 1); iLoopIndex++) {
		// 一文字ずつ取得する。
		csTemp = csVolSu.Mid(iLoopIndex, 1);
		
		// 先頭文字列が0の場合、エラー
		if (iLoopIndex == 0) {
			if (csTemp == "0") {
				DispMessage(IDS_MSG_312, MB_OK, FALSE);
				m_EDIT_VOL_SU.SetFocus();
				return false;
			}
		}

		// Volラベル名入力値、チェック
		csCheckData = csTemp.SpanIncluding(DEVICE_VOL_SU);

		if (csCheckData.GetLength() != 1) {
			DispMessage(IDS_MSG_312, MB_OK, FALSE);
			m_EDIT_VOL_SU.SetFocus();
			return false;
		}
	}
	
	return true;
}

//
//	機能	：	ファイル名/レコード長入力値妥当性チェック
//
//	引数	：	無し
//
//	復帰値	：	bool : true - チェックOK
//					 : false - チェックNG	
//
//	機能説明：	ファイル名/レコード長の入力値の妥当性チェックを行う。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckLabelNashiFileNameRecord()
{
	CString csFileNameRecord;
	int nKanmaLen;

	// ファイル名/レコード長データを取得
	m_EDIT_VOL_FILE.GetWindowText(csFileNameRecord);
	
	// ","文字列を検索する。
	nKanmaLen = csFileNameRecord.Find(DEFINE_COMMA);

	// カンマが文字列が含まれない場合
	if (nKanmaLen < 0) {
		// 入力値妥当性チェック処理
		if (CheckSlashData(csFileNameRecord) == false) {
			DispMessage(IDS_MSG_311, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	else {
		// 入力値妥当性チェック処理
		if (CheckLabelAriNashiKanmaData(csFileNameRecord, CMT_LABEL_NASHI_KANMA) == false)	{
			DispMessage(IDS_MSG_311, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	return true;
}

//
//	機能	：	"/"文字列検索処理
//
//	引数	：	p_csFileNameRecord - ファイル名/レコード長入力値
//
//	復帰値	：	bool : true - チェックOK
//					 : false - チェックNG	
//
//	機能説明：	パラメータの値より"/"文字列を検索
//              "/"が文字列の先頭、及び、末尾の場合、"/"文字列が含まれない場合、falseを返す
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckSlashData(CString &p_csFileNameRecord)
{
	int nFileLen, nIndex,nCount;
	CString csCheckData;

	// ファイル名/レコード長の桁数を取得
	nFileLen = p_csFileNameRecord.GetLength();
	
	// カウンタに0をセット
	nCount = 0;

	// 先頭、末尾、"/"文字列チェック処理
	if (CheckStartEndSlashData(p_csFileNameRecord, nFileLen) == false) {
		return false;
	}
	
	// 文字列の桁数分ループする。
	for (nIndex = 0; nIndex <= nFileLen - 1 ; nIndex++) {
		// 1バイトずつ文字を取得
		csCheckData = p_csFileNameRecord.Mid(nIndex, 1);

		// 文字が"/"の場合
		if (csCheckData == DEFINE_SLASH) {
			// カウンタにプラス１
			nCount = nCount + 1;
		}
	}
	
	// カウンタが1以外の場合
	if (nCount != 2) {
		return false;
	}
	
	return true;
}

//
//	機能	：	媒体種別(CMT)。VOLラベル名、ファイル名/レコード長の入力値(カンマあり)をチェックする。
//
//	引数	：	p_csFileNameRecord - ファイル名/レコード長入力値
//				n_pFlg - 処理分岐フラグ
//
//	復帰値	：	bool : true - チェックOK
//					 : false - チェックNG	
//
//	機能説明：	処理分岐フラグが0の場合、Volラベル名の入力値チェックを実行し、
//				1 の場合、ファイル名/レコード長の入力値チェックを実行する
//				チェック処理がNGの場合、復帰値FALSEをセットする。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckLabelAriNashiKanmaData(CString &p_csFileNameRecord, int p_nFlg)
{
	int nFileLen, nKanmaLen ;
	CString csTemp1, csFilenameRecord ;
	
	// パラメータの桁数を取得
	nFileLen = p_csFileNameRecord.GetLength();
	
	// CMTラベル無しの場合
	if (p_nFlg == CMT_LABEL_NASHI_KANMA) {
		// 先頭、末尾、"/"文字列チェック処理
		if (CheckStartEndSlashData(p_csFileNameRecord, nFileLen) == false) {
			return false;
		}
	}

	// 一時格納用にパラメータ値をセット
	csTemp1 = p_csFileNameRecord;

	while (true) {
		// 一時格納データのカンマまでの桁数が-1の場合
		if ((nKanmaLen = csTemp1.Find(DEFINE_COMMA)) == -1) {
			if (p_nFlg == CMT_LABEL_NASHI_KANMA) {
				// ファイル名/レコード長入力値妥当性チェック処理
				if (CheckSlashData(csTemp1) == false) {
					return false;
				}
				else {
					break;
				}
			}
			else {
				// Volラベル入力値妥当性チェック処理
				if (CheckVolNyuryokuData(csTemp1) == false) {
					return false;
				}
				else {
					break;
				}
			}
		}
		
		// カンマまでの文字列を取得
		csFilenameRecord = csTemp1.Mid(0, nKanmaLen);	

		if ((nKanmaLen = csTemp1.Find(DEFINE_COMMA)) == -1) {
			
			if (p_nFlg == CMT_LABEL_NASHI_KANMA) {
				// ファイル名/レコード長入力値妥当性チェック処理
				if (CheckSlashData(csFilenameRecord) == false) {
					return false;
				}
			}
			else {
				// VOLラベル名入力値妥当性チェック処理
				if (CheckVolNyuryokuData(csFilenameRecord) == false) {
					return false;
				}
			}
		}

		// 一時格納用にカンマ以降の文字列をセット
		csTemp1 = csTemp1.Mid(nKanmaLen + 1, nFileLen - nKanmaLen + 1);
	}

	return true;
}

//
//	機能	：	媒体(CMT)、ラベル無しチェック時、ファイル名/レコード長入力値の
//				先頭、及び、末尾文字列が"/"文字列かどうかチェックする。
//
//	引数	：	p_csNyuryokuData - ファイル名/レコード長入力値
//				p_nFileLen - ファイル名/レコード長入力値桁数
//
//	復帰値	：	bool : true - チェックOK
//					 : false - チェックNG	
//
//	機能説明：	パラメータ値の先頭、及び、末尾文字列が"/"文字列の場合、復帰値にFALSEを返す。
//
//	備考	：	無し
//
bool CVjb30040Dlg::CheckStartEndSlashData(CString &p_csNyuryokuData,int &p_nFileLen)
{
	CString csTemp ;
	
	// 先頭文字をチェック
	if ((csTemp = p_csNyuryokuData.Mid(0,1)) == DEFINE_SLASH) {
		return false;
	}
	
	// 末尾文字をチェック
	if ((csTemp = p_csNyuryokuData.Mid(p_nFileLen -1, 1)) == DEFINE_SLASH) {
		return false;
	}
	
	return true;
}

//
//	機能	：	ログファイル書き込み処理
//
//	引数	：	pParam - 入力パラメータ
//				pLogFile - ログファイル名
//				pStatus - ステータスコード
//
//	復帰値	：	無し
//
//	機能説明：	指定されたログファイルにログを書く込む。
//
//	備考	：	無し
//
void CVjb30040Dlg::WriteLogFile(Param *pParam, LPCTSTR pLogFile, LPCTSTR pStatus)
{
	CStdioFile file;

	// ログファイルをオープン
	if (file.Open(pLogFile, CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareDenyWrite)) {
		SYSTEMTIME st;
		CString str;

		// 現在時刻を取得
		GetLocalTime(&st);

		// ログのフォーマット
		str.Format("%04d/%02d/%02d %02d:%02d:%02d,%s,%s,%s,%s%s\n",
				st.wYear, st.wMonth, st.wDay, st.wHour, st.wMinute, st.wSecond,
				pParam->sShubetsuCode,
				pParam->sBatchServer,
				pParam->sShikibetsuCode,
				pParam->sVolLabel,
				pStatus == NULL ? "" : (CString)"," + pStatus);

		// 最終位置に追加書き込み
		file.Seek(0, CFile::end);
		file.WriteString(str);
		file.Close();
	}
}
