// vjb30010.cpp : アプリケーション用クラスの定義を行います。
//

#include "stdafx.h"
#include "vjb30010.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CVjb30010App

BEGIN_MESSAGE_MAP(CVjb30010App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30010App)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30010App クラスの構築

CVjb30010App::CVjb30010App()
{
	m_nRetCode = RC_NORMAL;
}

/////////////////////////////////////////////////////////////////////////////
// 唯一の CVjb30010App オブジェクト

CVjb30010App theApp;

/////////////////////////////////////////////////////////////////////////////
// CVjb30010App クラスの初期化

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
BOOL CVjb30010App::InitInstance()
{
	Param param;

	if ((m_nRetCode = GetParam(__argc, __argv, &param)) == 0)
		m_nRetCode = CreateQueueFile(&param);

	return FALSE;
}

//
//	機能	：	パラメータ取得処理
//
//	引数	：	argc - パラメータ数
//				argv - パラメータ内容
//				pParam - パラメータ格納エリア
//
//	復帰値	：	結果コード
//
//	機能説明：	コマンドラインよりパラメータを取得する。
//
//	備考	：	無し
//
int CVjb30010App::GetParam(int argc, char* argv[], Param *pParam)
{
	char *p;

	// パラメータ数チェック
	if (argc != 2) {
		DispMessage("パラメータの数が違います。");
		return RC_PARAMNUM;
	}

	// 媒体種別コード、媒体識別コード、Volラベル名取得
	if ((p = strtok(argv[1], ",")) == NULL) {
		DispMessage("パラメータに誤りがあります。");
		return RC_PARAMVAL;
	}

	// パラメータの文字数チェック
	if (strlen(p) != LEN_TOTAL) {
		DispMessage("パラメータの長さが違います。");
		return RC_PARAMLEN;
	}

	// 媒体種別コード、媒体識別コード、Volラベル名の分離
	strncpy(pParam->cShubetsuCode, p, LEN_SHUBETSU_CODE);
	pParam->cShubetsuCode[LEN_SHUBETSU_CODE] = '\0';
	strncpy(pParam->cShikibetsuCode, p + POS_SHIKIBETSU_CODE, LEN_SHIKIBETSU_CODE);
	pParam->cShikibetsuCode[LEN_SHIKIBETSU_CODE] = '\0';
	strncpy(pParam->cVolLabel[0], p + POS_VOLUME_LABEL, LEN_VOLUME_LABEL);
	pParam->cVolLabel[0][LEN_VOLUME_LABEL] = '\0';

	// 追加のVolラベル名取得
	pParam->nVolLabel = 1;
	while ((p = strtok(NULL, ",")) != NULL) {
		// Volラベル数チェック
		if (pParam->nVolLabel >= MAX_VOLUME_LABEL) {
			DispMessage("Volラベル名の数が多すぎます。");
			return RC_VOLNUM;
		}

		// Volラベル長チェック
		if (strlen(p) != LEN_VOLUME_LABEL) {
			DispMessage("Volラベル名の長さが違います。");
			return RC_VOLLEN;
		}

		// Volラベル取得
		strcpy(pParam->cVolLabel[pParam->nVolLabel++], p);
	}

	return RC_NORMAL;
}

//
//	機能	：	キューファイル作成処理
//
//	引数	：	pParam - パラメータ格納エリア
//
//	復帰値	：	結果コード
//
//	機能説明：	パラメータよりキューファイルを作成する。
//
//	備考	：	無し
//
int CVjb30010App::CreateQueueFile(Param *pParam)
{
	char iniFile[256];
	char queueDir[256];
	char queueFile[256];
	SYSTEMTIME st;
	int i;
	HANDLE hFile;

	// 環境変数よりINIファイル名取得
	if (GetEnvironmentVariable("VJBINIDIR", iniFile, sizeof(iniFile)) == 0)
		strcpy(iniFile, INI_FILE);
	else
		strcat(iniFile, "VJB30010.INI");

	// INIファイルよりキューファイルディレクトリ取得
	GetPrivateProfileString("QUEUE", "Dir", "", queueDir, sizeof(queueDir), iniFile);

	// システム日付取得
	GetLocalTime(&st);

	// キューファイル名作成（Volラベルを除く）
	sprintf(queueFile, "%s\\%04d%02d%02d%02d%02d%02d%s%sN",
			queueDir,
			st.wYear, st.wMonth, st.wDay, st.wHour, st.wMinute, st.wSecond,
			pParam->cShubetsuCode, pParam->cShikibetsuCode);

	// Volラベル付加
	for (i = 0; i < pParam->nVolLabel; i++) {
		if (i != 0)
			strcat(queueFile, ",");
		strcat(queueFile, pParam->cVolLabel[i]);
	}

	// キューファイル作成
	hFile = CreateFile(
		queueFile,				// pointer to name of the file
		GENERIC_WRITE,			// access (read-write) mode
		FILE_SHARE_READ,		// share mode
		NULL,					// pointer to security attributes
		CREATE_ALWAYS,			// how to create
		FILE_ATTRIBUTE_NORMAL,	// file attributes
		NULL					// handle to file with attributes to copy
	);
	if (hFile == NULL) {
		DispMessage("キューファイルが作成できません。");
		return RC_QUEUEFILE;
	}
	CloseHandle(hFile);

	return RC_NORMAL;
}

//
//	機能	：	メッセージ表示処理
//
//	引数	：	msg - 表示メッセージ
//
//	復帰値	：	無し
//
//	機能説明：	メッセージを表示する。
//
//	備考	：	デバッグ時のみ表示
//
void CVjb30010App::DispMessage(LPCTSTR msg)
{
#if _DEBUG
	AfxMessageBox(msg);
#endif
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
int CVjb30010App::ExitInstance() 
{
	return m_nRetCode;
}
