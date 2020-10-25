// vjb30030.cpp : アプリケーション用クラスの定義を行います。
//

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputDevice.h"
#include "OutputFPD.h"
#include "OutputMOD.h"
#include "OutputCMT.h"
#include "OutputDAT.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define DEVICE_FPD		"FPD"
#define DEVICE_MOD		"MOD"
#define DEVICE_CMT		"CMT"
#define DEVICE_DAT		"DAT"

#define STATUS_READY		'N'
#define STATUS_EXECUTE		'1'
#define STATUS_NONCHECK		'2'
#define STATUS_EXECUTING	'3'
#define STATUS_IOERROR		'7'
#define STATUS_EXECUTEERROR	'8'

#define QF_LEN_DATE						14
#define QF_LEN_BAITAI_SHUBETSU_CODE		3
#define QF_LEN_BAITAI_SHIKIBETSU_CODE	6
#define QF_LEN_STATUS					1
#define QF_LEN_VOLUMELABEL				6

#define QF_POS_DATE						0
#define QF_POS_BAITAI_SHUBETSU_CODE		14
#define QF_POS_BAITAI_SHIKIBETSU_CODE	17
#define QF_POS_STATUS					23
#define QF_POS_VOLUMELABEL				24

// メッセージ
#define	MSG_KAKUNIN			"確認メッセージ"
#define MSG_ERROR			"エラーメッセージ"

/////////////////////////////////////////////////////////////////////////////
// CVjb30030App

BEGIN_MESSAGE_MAP(CVjb30030App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30030App)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30030App クラスの構築

CVjb30030App::CVjb30030App()
{
	m_nRetCode = RC_NORMAL;
}

/////////////////////////////////////////////////////////////////////////////
// 唯一の CVjb30030App オブジェクト

CVjb30030App theApp;

CString g_sPathFPD;
CString g_sPathMOD;
CString g_sOutputDir;
CString g_sLogDir;

/////////////////////////////////////////////////////////////////////////////
// CVjb30030App クラスの初期化

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
BOOL CVjb30030App::InitInstance()
{
	m_nRetCode = Main(__argc, __argv);

	return FALSE;
}

//
//	機能	：	インスタンス終了処理
//
//	引数	：	無し
//
//	復帰値	：	プログラム終了コード
//
//	機能説明：	プログラム終了コードを返す。
//
//	備考	：	プログラム終了時にフレームワークより呼ばれる。
//
int CVjb30030App::ExitInstance() 
{
	return m_nRetCode;
}

//
//	機能	：	外部媒体出力メイン処理
//
//	引数	：	argc - パラメータ数
//				argv - パラメータ内容
//
//	復帰値	：	プログラム終了コード
//
//	機能説明：	外部媒体出力のメイン処理を行う。
//
//	備考	：	プログラム終了時にフレームワークより呼ばれる。
//
int CVjb30030App::Main(int argc, char *argv[])
{
	Queue *pQueue;
	COutputDevice *pOutputDevice;
	int nErrorCode;
	CString sErrorMsg;
	int nRetCode = RC_NORMAL;
	char cStatus;

	// パラメータ数チェック
	if (argc != 2)
		return RC_PARAMETER;

	// iniファイル読み込み
	ReadIniFile();

	// キューデータ取得
	pQueue = GetQueueData(argv[1]);

	// 実行またはラベルノンチェック実行でなければエラー
	if (!(pQueue->cStatus == STATUS_EXECUTE || pQueue->cStatus == STATUS_NONCHECK))
		return RC_STATUS;

	// 開始ログ書き込み
	WriteLogFile(pQueue, CreatePathName(g_sLogDir, "syuturyokustart"));

	// 状態コードを実行中に設定
	cStatus = pQueue->cStatus;
	ChangeStatus(pQueue, STATUS_EXECUTING);

	// 出力デバイス別のクラスを生成
	if (pQueue->sShubetsuCode == DEVICE_FPD)
		pOutputDevice = new COutputFPD;
	else if (pQueue->sShubetsuCode == DEVICE_MOD)
		pOutputDevice = new COutputMOD;
	else if (pQueue->sShubetsuCode == DEVICE_CMT)
		pOutputDevice = new COutputCMT;
	else if (pQueue->sShubetsuCode == DEVICE_DAT)
		pOutputDevice = new COutputDAT;
	else
		return RC_PARAMETER;

	// コピー元ディレクトリ／ファイル存在チェック
	nRetCode = pOutputDevice->CheckDstFile(pQueue);

	// ボリューム名チェック
	if (nRetCode == RC_NORMAL && cStatus != STATUS_NONCHECK)
		nRetCode = pOutputDevice->CheckVolumeName(pQueue);

	// ファイル出力
	if (nRetCode == RC_NORMAL)
		nRetCode = pOutputDevice->OutputFiles(pQueue);

	// 後処理
	switch (nRetCode) {
	case RC_NORMAL:
		pQueue->cStatus = '0';
		DeleteSrcFile(pQueue);
		NotifyChangeStatus();
		DispMessage(IDS_MSG_NORMAL_END, MB_OK, TRUE,
				(LPCTSTR)pQueue->sShubetsuCode, pQueue->sShikibetsuCode);
		break;
	case RC_VOLUMENAME:
		ChangeStatus(pQueue, STATUS_EXECUTEERROR);
		DispMessage(IDS_MSG_VOLLABEL_ERROR, MB_OK, TRUE, pQueue->sShubetsuCode);
		break;
	case RC_CANCEL:
		ChangeStatus(pQueue, STATUS_READY);
		break;
	case RC_MULTIFILEVOL:
		ChangeStatus(pQueue, STATUS_IOERROR);
		DispMessage(IDS_MSG_MULTIFILEVOL, MB_OK, TRUE);
		break;
	case RC_NODIRECTORY:
		ChangeStatus(pQueue, STATUS_IOERROR);
		DispMessage(IDS_MSG_NODIRECTORY, MB_OK, TRUE, pQueue->sShubetsuCode, pQueue->sShikibetsuCode);
		break;
	case RC_NOFILE:
		ChangeStatus(pQueue, STATUS_IOERROR);
		DispMessage(IDS_MSG_NOFILE, MB_OK, TRUE, pQueue->sShubetsuCode, pQueue->sShikibetsuCode);
		break;
	case RC_ILLEGALFILENAME:
		ChangeStatus(pQueue, STATUS_IOERROR);
		break;
	default:
		ChangeStatus(pQueue, STATUS_IOERROR);
		pOutputDevice->GetError(nErrorCode, sErrorMsg);
		if (nErrorCode == 0)
			DispMessage(IDS_MSG_IO_ERROR, MB_OK, TRUE, pQueue->sShubetsuCode);
		else
			DispMessage(IDS_MSG_IO_ERROR2, MB_OK, TRUE, pQueue->sShubetsuCode, -nErrorCode, sErrorMsg);
		break;
	}

	// 媒体出力クラス開放
	delete pOutputDevice;

	// 終了ログ書き込み
	WriteLogFile(pQueue, CreatePathName(g_sLogDir, "syuturyokuend"));

	// キュー情報開放
	delete pQueue;

	return nRetCode;
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
void CVjb30030App::ReadIniFile()
{
	// INIファイルよりキューファイルディレクトリ取得
	m_sQueueDir = GetIniData("Queue", "Dir", "");

	// FPDのパス名を取得
	g_sPathFPD = GetIniData("Device", "FPD", "A:\\");

	// MODのパス名を取得
	g_sPathMOD = GetIniData("Device", "MOD", "M:\\");

	// 出力ファイルディレクトリを取得
	g_sOutputDir = GetIniData("OutputFile", "Dir", "Z:\\");

	// ログファイルディレクトリ名取得
	g_sLogDir = GetIniData("LogFile", "Dir", "");
}

//
//	機能	：	キュー情報取得処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	キューファイルからキュー情報を作成する。
//
//	備考	：	無し
//
Queue *CVjb30030App::GetQueueData(CString sFileName)
{
	Queue *pQueue = new Queue;

	// キューファイル名から各パラメータ取得
	pQueue->sFileName = sFileName;
	pQueue->sShubetsuCode = sFileName.Mid(QF_POS_BAITAI_SHUBETSU_CODE, QF_LEN_BAITAI_SHUBETSU_CODE);
	pQueue->sShikibetsuCode = sFileName.Mid(QF_POS_BAITAI_SHIKIBETSU_CODE, QF_LEN_BAITAI_SHIKIBETSU_CODE);
	pQueue->sVolLabel = sFileName.Mid(QF_POS_VOLUMELABEL);
	pQueue->sCreateDate = sFileName.Left(QF_LEN_DATE);
	pQueue->cStatus = sFileName[QF_POS_STATUS];

	return pQueue;
}

//
//	機能	：　状態コード変更処理
//
//	引数	：	pQueue - キュー情報
//				status - 状態コード
//
//	復帰値	：	無し
//
//	機能説明：	キューファイルの状態コードを変更する。
//
//	備考	：	無し
//
void CVjb30030App::ChangeStatus(Queue *pQueue, char status)
{
	CString sOldFileName, sNewFileName;

	// キューファイルのパス名を取得
	sOldFileName = CreatePathName(m_sQueueDir, pQueue->sFileName);

	// 状態コードを変更
	pQueue->sFileName.SetAt(QF_POS_STATUS, status);
	pQueue->cStatus = status;

	// 変更後のキューファイルのパス名を取得
	sNewFileName = CreatePathName(m_sQueueDir, pQueue->sFileName);

	// ファイル名変更
	MoveFile(sOldFileName, sNewFileName);

	// 外部媒体出力管理プログラムに通知
	NotifyChangeStatus();
}

//
//	機能	：　出力元ファイル削除処理
//
//	引数	：	pQueue - キュー情報
//
//	復帰値	：	無し
//
//	機能説明：	出力の終わったファイルを削除する。
//
//	備考	：	無し
//
void CVjb30030App::DeleteSrcFile(Queue *pQueue)
{
	// キューファイルの削除
	DeleteFile(CreatePathName(m_sQueueDir, pQueue->sFileName));

	// コピー元ディレクトリ、ファイル削除
	DeleteDirectory(CreatePathName(g_sOutputDir, pQueue->sShikibetsuCode + pQueue->sVolLabel.SpanExcluding(",")), TRUE);
}

//
//	機能	：　キューファイル変更通知処理
//
//	引数	：	無し
//
//	復帰値	：	無し
//
//	機能説明：	外部媒体管理プログラムにキューファイルの変更を通知する。
//
//	備考	：	無し
//
void CVjb30030App::NotifyChangeStatus()
{
	CWnd *pWnd;

	pWnd = CWnd::FindWindow(NULL, "媒体出力指示画面(vjb30020)");
	if (pWnd != NULL)
		pWnd->SendMessage(WM_TIMER, 1, 0);
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
void CVjb30030App::WriteLogFile(Queue *pQueue, LPCTSTR pLogFile)
{
	CStdioFile file;

	// ログファイルをオープン
	if (file.Open(pLogFile, CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareDenyWrite)) {
		SYSTEMTIME st;
		CString str;

		// 現在時刻を取得
		GetLocalTime(&st);

		// ログのフォーマット
		str.Format("%04d/%02d/%02d %02d:%02d:%02d,%s/%s/%s %s:%s:%s,%s,%s,%s,%c\n",
				st.wYear, st.wMonth, st.wDay, st.wHour, st.wMinute, st.wSecond,
				pQueue->sCreateDate.Mid(0, 4),
				pQueue->sCreateDate.Mid(4, 2),
				pQueue->sCreateDate.Mid(6, 2),
				pQueue->sCreateDate.Mid(8, 2),
				pQueue->sCreateDate.Mid(10, 2),
				pQueue->sCreateDate.Mid(12, 2),
				pQueue->sShubetsuCode,
				pQueue->sShikibetsuCode,
				pQueue->sVolLabel,
				pQueue->cStatus);

		// 最終位置に追加書き込み
		file.Seek(0, CFile::end);
		file.WriteString(str);
		file.Close();
	}
}
