// vjb30050.cpp : アプリケーション用クラスの定義を行います。
//

#include "stdafx.h"
#include "vjb30050.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define DEVICE_FPD		"FPD"
#define DEVICE_MOD		"MOD"
#define DEVICE_CMT		"CMT"
#define DEVICE_DAT		"DAT"

/////////////////////////////////////////////////////////////////////////////
// CVjb30050App

BEGIN_MESSAGE_MAP(CVjb30050App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30050App)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30050App クラスの構築

CVjb30050App::CVjb30050App()
{
	// TODO: この位置に構築用のコードを追加してください。
	// ここに InitInstance 中の重要な初期化処理をすべて記述してください。
}

/////////////////////////////////////////////////////////////////////////////
// 唯一の CVjb30050App オブジェクト

CVjb30050App theApp;

CString g_sLogDir;
CString g_sInputDir;
CString g_sDatDir;
CString g_sBatchUNC;

/////////////////////////////////////////////////////////////////////////////
// CVjb30050App クラスの初期化

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
BOOL CVjb30050App::InitInstance()
{
	m_nRetCode = Main(__argc, __argv);

	return FALSE;
}

//
//	機能	：	ファイルコピーメイン処理
//
//	引数	：	argc - パラメータ数
//				argv - パラメータ内容
//
//	復帰値	：	プログラム終了コード
//
//	機能説明：	ファイルコピープログラムのメイン処理を行う。
//
//	備考	：	プログラム終了時にフレームワークより呼ばれる。
//
int CVjb30050App::Main(int argc, char *argv[])
{
	Param param;
	CString sSrcDir;
	CString sDstDir;
	char *pStatus;

	// パラメータ数チェック
	if (argc != 5)
		return RC_PARAMETER;

	// 各パラメータ取得
	param.sShubetsuCode = argv[1];
	param.sBatchServer = argv[2];
	param.sShikibetsuCode = argv[3];
	param.sVolLabel = argv[4];

	// iniファイル読み込み
	ReadIniFile();

	// コピー元パス名作成
	if (param.sShubetsuCode == DEVICE_DAT)
		sSrcDir = g_sDatDir;
	else
		sSrcDir = CreatePathName(g_sInputDir, param.sShikibetsuCode + param.sVolLabel);

	// バッチサーバのコピー先パス名(UNC)作成
	sDstDir = CreatePathName("\\\\" + param.sBatchServer + "\\" + g_sBatchUNC, param.sShikibetsuCode + param.sVolLabel);

	// 開始ログ書き込み
	WriteLogFile(&param, CreatePathName(g_sLogDir, "filecopystart"), NULL);

	// バッチサーバのコピー先ディレクトリ削除
	DeleteDirectory(sDstDir, FALSE);

	// バッチサーバのコピー先ディレクトリ作成
	CreateDirectory(sDstDir, NULL);

	// バッチサーバにファイルコピー
	if (CopyDirectory(sSrcDir, sDstDir, NULL)) {
		DispMessage(IDS_MSG_NORMAL_END, MB_OK, TRUE, param.sShubetsuCode);
		pStatus = "0";
	} else {
		DispMessage(IDS_MSG_COPY_ERROR, MB_OK, TRUE, param.sShubetsuCode, param.sBatchServer);
		pStatus = "7";
	}

	// コピー元ディレクトリの削除
	DeleteDirectory(sSrcDir, param.sShubetsuCode != DEVICE_DAT);

	// 終了ログ書き込み
	WriteLogFile(&param, CreatePathName(g_sLogDir, "filecopyend"), pStatus);

	return 0;
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
void CVjb30050App::ReadIniFile()
{
	// ログファイルディレクトリ名取得
	g_sLogDir = GetIniData("LogFile", "Dir", "");

	// 入力ディレクトリ名を取得
	g_sInputDir = GetIniData("InputFile", "InputDir", "dummy");

	// DAT用入力ディレクトリ名を取得
	g_sDatDir = GetIniData("InputFile", "DatDir", "dummy");

	// バッチサーバUNC名取得
	g_sBatchUNC = GetIniData("BatchServer", "UNC", "");
}

//
//	機能	：	ログファイル書き込み処理
//
//	引数	：	pParam - 入力パラメータ
//				pLogFile - ログファイル名
//
//	復帰値	：	無し
//
//	機能説明：	指定されたログファイルにログを書く込む。
//
//	備考	：	無し
//
void CVjb30050App::WriteLogFile(Param *pParam, LPCTSTR pLogFile, LPCTSTR pStatus)
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
