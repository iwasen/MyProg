// vjb30020.cpp : アプリケーション用クラスの定義を行います。
//

#include "stdafx.h"
#include "vjb30020.h"
#include "vjb30020Dlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CVjb30020App

BEGIN_MESSAGE_MAP(CVjb30020App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30020App)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30020App クラスの構築

CVjb30020App::CVjb30020App()
{
	// TODO: この位置に構築用のコードを追加してください。
	// ここに InitInstance 中の重要な初期化処理をすべて記述してください。
}

/////////////////////////////////////////////////////////////////////////////
// 唯一の CVjb30020App オブジェクト

CVjb30020App theApp;

CString g_sQueueDir;
int g_nTimer;

/////////////////////////////////////////////////////////////////////////////
// CVjb30020App クラスの初期化

//
//	機能	：	インスタンス開始処理
//
//	引数	：	無し
//
//	復帰値	：	FALSE - プログラムを終了する
//
//	機能説明：	プログラム本体の処理。
//
//	備考	：	プログラム開始時にフレームワークより呼ばれる。
//
BOOL CVjb30020App::InitInstance()
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

	//* プログラムの複数起動抑止
	m_hMutex = ::CreateMutex(NULL, FALSE, "MUTEX_vjb30020");
	if (::GetLastError() == ERROR_ALREADY_EXISTS)
		return FALSE;

	ReadIniFile();				// iniファイル読み込み

	CVjb30020Dlg dlg;
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
void CVjb30020App::ReadIniFile()
{	
	// INIファイルよりキューファイルディレクトリ取得
	g_sQueueDir = GetIniData("Queue", "Dir", "");

	// キューファイル監視タイマー設定
	g_nTimer = GetIniData("QUEUE", "Timer", 0);
}
