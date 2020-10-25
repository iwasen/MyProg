//*****************************************************************************************************
//  1. ファイル名
//		Schematic.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図／機能系統図／機能説明図表示メインクラス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "SchematicDlg.h"
#include "SchematicFrm.h"
#include "CGMenuDlg.h"
#include "MenuCS01.h"
#include "MenuFS01.h"
#include "MenuFS02.h"
#include "MenuFS03.h"
#include "MenuFS04.h"
#include "MenuFS05.h"
#include "MenuFS06.h"
#include "MenuFS07.h"
#include "MenuFS08.h"
#include "MenuFS09.h"
#include "MenuFS10.h"
#include "MenuFS11.h"
#include "MenuFS12.h"
#include "MenuFS13.h"
#include "MenuFS14.h"
#include "MenuFS15.h"
#include "MenuFS16.h"
#include "MenuFS17.h"
#include "MenuFS18.h"
#include "MenuFS19.h"
#include "MenuFE01.h"
#include "MenuFE02.h"
#include "MenuFE03.h"
#include "MenuFE04.h"
#include "MenuFE05.h"
#include "MenuFE06.h"
#include "MenuFE07.h"
#include "shlwapi.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CSchematicApp

BEGIN_MESSAGE_MAP(CSchematicApp, CWinApp)
	ON_COMMAND(ID_HELP, &CWinApp::OnHelp)
END_MESSAGE_MAP()


// CSchematicApp コンストラクション

//*****************************************************************************************************
//  1. 関数名
//		CSchematicApp::CSchematicApp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CSchematicApp::CSchematicApp()
{
	// TODO: この位置に構築用コードを追加してください。
	// ここに InitInstance 中の重要な初期化処理をすべて記述してください。
}


// 唯一の CSchematicApp オブジェクトです。

CSchematicApp theApp;


// CSchematicApp 初期化

//*****************************************************************************************************
//  1. 関数名
//		CSchematicApp::InitInstance
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		インスタンス初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CSchematicApp::InitInstance()
{
	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	CWinApp::InitInstance();

	AfxEnableControlContainer();

	// 起動パラメータ解析
	int nModeParam = 0;
	for (int i = 1; i < __argc; i++) {
		if (_strnicmp(__argv[i], "P=", 2) == 0)
			nModeParam = atoi(__argv[i] + 2);
		else if (_strnicmp(__argv[i], "F=", 2) == 0)
			CSchematic::m_sFlashDir = __argv[i] + 2;
	}

	// Flashのパス指定が無ければ"..\flash"とする
	if (CSchematic::m_sFlashDir.IsEmpty()) {
		char path[MAX_PATH];
		strcpy_s(path, m_pszHelpFilePath);
		PathRemoveFileSpec(path);
		CSchematic::m_sFlashDir.Format("%s\\..\\flash", path);
	}

	switch (nModeParam) {
	case 1:
		CSchematic::OpenSchematicWindow("CS01");
		break;
	case 2:
		(new CCGMenuDlg)->DoModeless();
		break;
	default:
		(new CSchematicDlg)->DoModeless();
		break;
	}

	return TRUE;
}

// 接続系統図／機能系統図／機能説明図データ
SSchematicWindow CSchematic::m_aSchematicWindow[] = {
	{"CS01", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuCS01), "ICS_01.swf", "接続系統図"},
	{"FS01", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS01), "IFS_01.swf", "電源系"},
	{"FS02", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS02), "IFS_02.swf", "イーサーネットバス系"},
	{"FS03", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS03), "IFS_03.swf", "1553バス系"},
	{"FS04", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS04), "IFS_04.swf", "射統表示器系統-射統処理器"},
	{"FS05", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS05), "IFS_05.swf", "発射機制御器－通信処理器"},
	{"FS06", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS06), "IFS_06.swf", "弾薬制御器－LP/C（1）"},
	{"FS07", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS07), "IFS_07.swf", "収納コネクタ（1）"},
	{"FS08", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS08), "IFS_08.swf", "弾薬制御器－LP/C（2）"},
	{"FS09", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS09), "IFS_09.swf", "収納コネクタ（2）"},
	{"FS10", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS10), "IFS_10.swf", "W330、W331系"},
	{"FS11", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS11), "IFS_11.swf", "エンコーダー－位置姿勢標定器－GPSアンテナ"},
	{"FS12", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS12), "IFS_12.swf", "電力制御器－発射機制御器－射統表示器"},
	{"FS13", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS13), "IFS_13.swf", "発射機駆動装置（機能スケマティック）（1）"},
	{"FS14", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS14), "IFS_14.swf", "リミットスイッチ（機能スケマティック）（1）"},
	{"FS14_1", RUNTIME_CLASS(CSchematicView), NULL, "IFS_14_matrix.swf", "リミットスイッチ（機能スケマティック）（1）"},
	{"FS14_2", RUNTIME_CLASS(CSchematicView), NULL, "IFS_14_AtoZ.swf", "リミットスイッチ（機能スケマティック）（1）"},
	{"FS15", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS15), "IFS_15.swf", "トラベルロック（機能スケマティック）"},
	{"FS16", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS16), "IFS_16.swf", "リミットスイッチ（機能スケマティック）（2）"},
	{"FS17", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS17), "IFS_17.swf", "ブームモータ（機能スケマティック）"},
	{"FS18", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS18), "IFS_18.swf", "ホイストモータ（機能スケマティック）"},
	{"FS19", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS19), "IFS_19.swf", "発射機駆動装置（機能スケマティック）（2）"},
	{"FE01", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE01), "FE_01.swf", "ＬＰ／Ｃ固定装置"},
	{"FE02", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE02), "FE_02.swf", "トラベルロック装置"},
	{"FE03", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE03), "FE_03.swf", "ホイストＡｓｓｙ"},
	{"FE04", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE04), "FE_04.swf", "ブーム装置"},
	{"FE05", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE05), "FE_05.swf", "爆風遮蔽装置"},
	{"FE06", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE06), "FE_06.swf", "発射機部油圧装置"},
	{"FE07", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE07), "FE_07.swf", "変速操向機"}
};

// Flashファイルディレクトリ
CString CSchematic::m_sFlashDir;

//*****************************************************************************************************
//  1. 関数名
//		CSchematic::CSchematic
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CSchematic::CSchematic()
{
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematic::OpenSchematicWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図／機能系統図／機能説明図オープン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		LPCTSTR		pSchematicCode		[I] 図表コード
//		int			nActionCode			[I] アクションコード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematic::OpenSchematicWindow(LPCTSTR pSchematicCode, int nActionCode)
{
	SSchematicWindow *pSchematicWindow = GetSchematicWindowData(pSchematicCode);
	if (pSchematicWindow != NULL)
		OpenFrameWindow(pSchematicWindow, nActionCode);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematic::GetSchematicWindowData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図／機能系統図／機能説明図ウィンドウデータ取得処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		LPCTSTR		pSchematicCode		[I] 図表コード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		SSchematicWindow*	図表ウィンドウデータへのポインタ
//*****************************************************************************************************
SSchematicWindow *CSchematic::GetSchematicWindowData(LPCTSTR pSchematicCode)
{
	// 指定された図表コードのデータを検索する
	for (int i = 0; i < sizeof(m_aSchematicWindow) / sizeof(SSchematicWindow); i++) {
		if (pSchematicCode == m_aSchematicWindow[i].sSchematicCode)
			return &m_aSchematicWindow[i];
	}

	return NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematic::RenzokuFE
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機能説明図連続表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematic::RenzokuFE()
{
	// 連続表示する図表コード
	static LPCTSTR tSchematicCode[] = {"FE07", "FE06", "FE05", "FE01", "FE02", "FE03", "FE04"};

	CSchematicFrame *pFrame = new CSchematicFrame;

	// 図表データをフレームウィンドウの図表リストに設定する
	for (int i = 0; i < sizeof(tSchematicCode) / sizeof(LPCTSTR); i++)
		pFrame->m_lSchematicWindow.AddTail(GetSchematicWindowData(tSchematicCode[i]));

	// フレームウィンドウ表示
	pFrame->CreateFrameWindow(0);
}

//*****************************************************************************************************
//  1. 関数名
//		CSchematic::OpenFrameWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フレームウィンドウオープン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		SSchematicWindow	*pSchematicClass	[I] 図表ウィンドウデータへのポインタ
//		int					nActionCode			[I] アクションコード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CSchematic::OpenFrameWindow(SSchematicWindow *pSchematicClass, int nActionCode)
{
	CSchematicFrame *pFrame = new CSchematicFrame;

	// 指定された図表データをフレームウィンドウの図表リストに設定する
	pFrame->m_lSchematicWindow.AddTail(pSchematicClass);

	// フレームウィンドウ表示
	pFrame->CreateFrameWindow(nActionCode);
}
