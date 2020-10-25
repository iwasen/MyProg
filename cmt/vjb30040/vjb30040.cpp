// vjb30040.cpp : アプリケーション用クラスの定義を行います。
//

#include "stdafx.h"
#include "vjb30040.h"
#include "vjb30040Dlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CVjb30040App

BEGIN_MESSAGE_MAP(CVjb30040App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30040App)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30040App クラスの構築

CVjb30040App::CVjb30040App()
{
	// TODO: この位置に構築用のコードを追加してください。
	// ここに InitInstance 中の重要な初期化処理をすべて記述してください。
}

/////////////////////////////////////////////////////////////////////////////
// 唯一の CVjb30040App オブジェクト

CVjb30040App theApp;

CString g_sPathFPD;
CString g_sPathMOD;
CString g_sInputDir;
CString g_sDatDir;
CString g_sLogDir;
CString g_BatchServerFile;

/////////////////////////////////////////////////////////////////////////////
// CVjb30040App クラスの初期化

BOOL CVjb30040App::InitInstance()
{
	AfxEnableControlContainer();

	// 標準的な初期化処理
	// もしこれらの機能を使用せず、実行ファイルのサイズを小さくしたけ
	//  れば以下の特定の初期化ルーチンの中から不必要なものを削除して
	//  ください。

#ifdef _AFXDLL
	Enable3dControls();			// 共有 DLL 内で MFC を使う場合はここをコールしてください。
#else
	Enable3dControlsStatic();	// MFC と静的にリンクする場合はここをコールしてください。
#endif

	ReadIniFile();				// iniファイル読み込み

	CVjb30040Dlg dlg;
	m_pMainWnd = &dlg;
	int nResponse = dlg.DoModal();
	if (nResponse == IDOK)
	{
		// TODO: ダイアログが <OK> で消された時のコードを
		//       記述してください。
	}
	else if (nResponse == IDCANCEL)
	{
		// TODO: ダイアログが <ｷｬﾝｾﾙ> で消された時のコードを
		//       記述してください。
	}

	// ダイアログが閉じられてからアプリケーションのメッセージ ポンプを開始するよりは、
	// アプリケーションを終了するために FALSE を返してください。
	return FALSE;
}

//
//	機能	：	iniファイル読み込み処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	iniファイルより必要な情報を読み込む。
//
//	備考	：	無し
//
void CVjb30040App::ReadIniFile()
{	
	// FPDのパス名を取得
	g_sPathFPD = GetIniData("Device", "FPD", "A:\\");

	// MODのパス名を取得
	g_sPathMOD = GetIniData("Device", "MOD", "M:\\");

	// 入力ディレクトリ名を取得
	g_sInputDir = GetIniData("InputFile", "InputDir", "dummy");

	// DAT用入力ディレクトリ名を取得
	g_sDatDir = GetIniData("InputFile", "DatDir", "dummy");

	// ログファイルディレクトリ名取得
	g_sLogDir = GetIniData("LogFile", "Dir", "");

	// INIファイルよりバッチサーバ指定ファイル名取得
	g_BatchServerFile = GetIniData("BatchServer", "Server", "");
}
