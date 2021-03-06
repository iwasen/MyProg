//*****************************************************************************************************
//  1. ファイル名
//		StartMenuDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CStartMenuDlg クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"
#include "WorkSchedulerDlg.h"

#include "MenuDispCtrl.h"

#include "StartMenuDlg001.h"
#include "StartMenuDlg.h"
#include "DataManager.h"
#include <shlobj.h>

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

//*****************************************************************************************************
//  1. 関数名
//		CStartMenuDlg::CStartMenuDlg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CWnd		*pParent			[I] 親ウィンドウ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		無し
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CStartMenuDlg::CStartMenuDlg(CWnd* pParent /*=NULL*/)
	: CSubMenuDlg(CStartMenuDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CStartMenuDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT
}


void CStartMenuDlg::DoDataExchange(CDataExchange* pDX)
{
	CSubMenuDlg::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CStartMenuDlg)
	DDX_Control(pDX, IDC_BUTTON_READ_DATA, m_cButtonReadData);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CStartMenuDlg, CSubMenuDlg)
	//{{AFX_MSG_MAP(CStartMenuDlg)
	ON_BN_CLICKED(IDC_BUTTON_READ_DATA, OnButtonReadData)
	ON_BN_CLICKED(IDC_BUTTON_DEBUG1, OnButtonDebug1)
	ON_BN_CLICKED(IDC_BUTTON_DEBUG2, OnButtonDebug2)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. 関数名
//		CStartMenuDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		メニューボタンの背景色を設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CStartMenuDlg::OnInitDialog()
{
	CSubMenuDlg::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_StartMenuDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// メニューボタンの背景色を設定
	m_cButtonReadData.SetBackGroundColor(COLOR_SUBMENU_BUTTON);

#ifdef _DEBUG
	GetDlgItem(IDC_BUTTON_DEBUG1)->ShowWindow(SW_SHOW);
	GetDlgItem(IDC_BUTTON_DEBUG2)->ShowWindow(SW_SHOW);
#endif

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CStartMenuDlg::OnButtonReadData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ読み込みボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		フォルダ選択ダイアログを表示し、フォルダが選択されたら各ファイルからデータを読み込む。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CStartMenuDlg::OnButtonReadData()
{
	CWorkSchedulerDlg *pMainWnd = (CWorkSchedulerDlg *)AfxGetMainWnd();

	// メインウィンドウのサブメニューステータスをセット
	pMainWnd->SetSubMenuStatus(m_cButtonReadData);

	if (CGlobal::SelectFolderDialog(g_pSystem->m_cIniUser.m_sDataPath, g_pSystem->m_cIniUser.m_sDataPath)) {
		// データ読み込み
		ReadData(g_pSystem->m_cIniUser.m_sDataPath);
	}

	// メインウィンドウのサブメニューステータスを消去
	pMainWnd->ClearSubMenuStatus();
}

//*****************************************************************************************************
//  1. 関数名
//		CStartMenuDlg::ReadData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ読み込み処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pFolderName			[I] フォルダ名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたフォルダにある各エクセルファイルを読み込む。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CStartMenuDlg::ReadData(LPCTSTR pFolderName)
{
	// 既に一度読んでいる場合はデータ管理オブジェクトを作り直す
	if (g_pDataManager != NULL && g_pDataManager->m_nStatus != STATUS_NOREAD) {
		delete g_pDataManager;
		g_pDataManager = new CDataManager;
	}

	// エクセルからデータを読み込み
	if (g_pDataManager->ReadFile(pFolderName)) {
#ifndef _DEBUG
//		CGlobal::Information("機種データを読み込みました。");
#endif

		// メニューボタンを有効化
		g_pSystem->EnableMenu();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CStartMenuDlg::OnButtonDebug
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		デバッグ用ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		デフォルトフォルダからデータを読み、生産台数、体制、目標タクトの設定まで自動で行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//		デバッグ時の手間を省くため。
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CStartMenuDlg::OnButtonDebug1()
{
	OnButtonDebug(1);
}

void CStartMenuDlg::OnButtonDebug2()
{
	OnButtonDebug(2);
}

void CStartMenuDlg::OnButtonDebug(int nJokenSetteiSosa)
{
	g_pSystem->m_cIniUser.m_sDataPath = "..\\Project";

	ReadData(g_pSystem->m_cIniUser.m_sDataPath);
	g_pSystem->m_cUserInfo.m_nJokenSetteiSosa = nJokenSetteiSosa;

	if (g_pDataManager->m_nStatus != STATUS_NOREAD) {
/*
		int nSubLineNum = g_pDataManager->GetSublineNum();
		for (int nSublineId = 0; nSublineId < nSubLineNum; nSublineId++) {
			SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
			pSubline->cSeisanJoken.fTact = 1.7;
			pSubline->cSeisanJoken.fJogen = 5;
			pSubline->cSeisanJoken.fKagen = 5;
		}

		SSeisanDaisu *pSeisanDaisu = g_pDataManager->GetSeisanDaisuData(0);
		int nKishuNum = g_pDataManager->GetKishuNum();
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
			SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
			pKishu->nSeisanDaisu = pSeisanDaisu->aSeisanDaisu[nKishuId];
		}
*/

		g_pSystem->EnableMenu();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CStartMenuDlg::EnableMenuButton
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メニューボタンの有効化設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		設定状態によりサブメニューのボタンを有効化する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CStartMenuDlg::EnableMenuButton()
{
	m_cButtonReadData.EnableWindow(!g_pDataManager->m_bOpenLineKiseiMatrix && !g_pDataManager->m_bOpenKatashikiKiseiMatrix);
}
