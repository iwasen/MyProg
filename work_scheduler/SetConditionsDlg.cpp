//*****************************************************************************************************
//  1. ファイル名
//		SetConditionsDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CSetConditionsDlg クラスのインプリメンテーション
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

#include "SetConditionsDlg001.h"
#include "SetConditionsDlg.h"
#include "SetSeisanJoken1Dlg.h"
#include "SetSeisanJoken2Dlg.h"
#include "SetKittingDlg.h"
#include "SetKyotsuKoteiDlg.h"
#include "KatashikiKiseiMatrixDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

//*****************************************************************************************************
//  1. 関数名
//		CSetConditionsDlg::CSetConditionsDlg
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
CSetConditionsDlg::CSetConditionsDlg(CWnd* pParent /*=NULL*/)
	: CSubMenuDlg(CSetConditionsDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSetConditionsDlg)
	//}}AFX_DATA_INIT
}


void CSetConditionsDlg::DoDataExchange(CDataExchange* pDX)
{
	CSubMenuDlg::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetConditionsDlg)
	DDX_Control(pDX, IDC_BUTTON_SET_SEISAN_JOKEN, m_cButtonSetSeisanJoken);
	DDX_Control(pDX, IDC_BUTTON_SET_LINE_KISEI, m_cButtonSetLineKisei);
	DDX_Control(pDX, IDC_BUTTON_SET_KYOTU_KOTEI, m_cButtonSetKyotsuKotei);
	DDX_Control(pDX, IDC_BUTTON_SET_KATASHIKI_KISEI, m_cButtonSetKatashikiKisei);
	DDX_Control(pDX, IDC_BUTTON_SET_KITTING, m_cButtonSetKitting);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CSetConditionsDlg, CSubMenuDlg)
	//{{AFX_MSG_MAP(CSetConditionsDlg)
	ON_BN_CLICKED(IDC_BUTTON_SET_KITTING, OnButtonSetKitting)
	ON_BN_CLICKED(IDC_BUTTON_SET_SEISAN_JOKEN, OnButtonSetSeisanJoken)
	ON_BN_CLICKED(IDC_BUTTON_SET_LINE_KISEI, OnButtonSetLineKisei)
	ON_BN_CLICKED(IDC_BUTTON_SET_KATASHIKI_KISEI, OnButtonSetKatashikiKisei)
	ON_BN_CLICKED(IDC_BUTTON_SET_KYOTU_KOTEI, OnButtonSetKyotuKotei)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. 関数名
//		CSetConditionsDlg::OnInitDialog
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
//		ボタンの色を設定し、ボタン有効化する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CSetConditionsDlg::OnInitDialog()
{
	CSubMenuDlg::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_SetConditionsDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// ボタンの色を設定
	m_cButtonSetSeisanJoken.SetBackGroundColor(COLOR_SUBMENU_BUTTON);
	m_cButtonSetKitting.SetBackGroundColor(COLOR_SUBMENU_BUTTON);
	m_cButtonSetLineKisei.SetBackGroundColor(COLOR_SUBMENU_BUTTON);
	m_cButtonSetKatashikiKisei.SetBackGroundColor(COLOR_SUBMENU_BUTTON);
	m_cButtonSetKyotsuKotei.SetBackGroundColor(COLOR_SUBMENU_BUTTON);

	// メニュー有効化
	g_pSystem->EnableMenu();

// Modify ... ( ADD )
	// UP4用モジュール
//	GetDlgItem( IDC_BUTTON_SET_KITTING )->EnableWindow( FALSE );
// By Y.Itabashi (xxxxx) 2007.02.22

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CSetConditionsDlg::OnButtonSetSeisanJoken
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		生産条件設定ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		生産条件設定ダイアログを表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetConditionsDlg::OnButtonSetSeisanJoken()
{
	CWorkSchedulerDlg *pMainWnd = (CWorkSchedulerDlg *)AfxGetMainWnd();

	// メインウィンドウのサブメニューステータスをセット
	pMainWnd->SetSubMenuStatus(m_cButtonSetSeisanJoken);

	// 生産台数登録ダイアログ表示
	if (g_pSystem->m_cUserInfo.m_nJokenSetteiSosa == 1) {
		CSetSeisanJoken1Dlg dlg;
		dlg.DoModal();
	} else {
		CSetSeisanJoken2Dlg dlg;
		dlg.DoModal();
	}

	// メニュー有効化
	g_pSystem->EnableMenu();

	// メインウィンドウのサブメニューステータスを消去
	pMainWnd->ClearSubMenuStatus();
}

//*****************************************************************************************************
//  1. 関数名
//		CSetConditionsDlg::OnButtonSetKitting
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キット化設定ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		キット化設定ダイアログを表示する。既にキット化されている場合は解除する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetConditionsDlg::OnButtonSetKitting()
{
	CWorkSchedulerDlg *pMainWnd = (CWorkSchedulerDlg *)AfxGetMainWnd();

	// メインウィンドウのサブメニューステータスをセット
	pMainWnd->SetSubMenuStatus(m_cButtonSetKitting);

	// Undoバッファリセット
	g_pDataManager->ResetUndoBuf();

	// キット化設定ダイアログ表示
	CSetKittingDlg dlg;
	dlg.DoModal();

	// メニュー有効化
	g_pSystem->EnableMenu();

	// メインウィンドウのサブメニューステータスを消去
	pMainWnd->ClearSubMenuStatus();
}

void CSetConditionsDlg::OnButtonSetLineKisei()
{
	g_pDataManager->OpenLineKiseiMatrix();
}

void CSetConditionsDlg::OnButtonSetKatashikiKisei()
{
	CKatashikiKiseiMatrixDlg dlg;
	if (dlg.DoModal() == IDOK)
		g_pDataManager->OpenKatashikiKiseiMatrix();
}

void CSetConditionsDlg::OnButtonSetKyotuKotei()
{
	CWorkSchedulerDlg *pMainWnd = (CWorkSchedulerDlg *)AfxGetMainWnd();

	// メインウィンドウのサブメニューステータスをセット
	pMainWnd->SetSubMenuStatus(m_cButtonSetKyotsuKotei);

	// Undoバッファリセット
	g_pDataManager->ResetUndoBuf();

	// 共通工程設定ダイアログ表示
	CSetKyotsuKoteiDlg dlg;
	dlg.DoModal();

	// メニュー有効化
	g_pSystem->EnableMenu();

	// メインウィンドウのサブメニューステータスを消去
	pMainWnd->ClearSubMenuStatus();
}

//*****************************************************************************************************
//  1. 関数名
//		CSetConditionsDlg::EnableMenuButton
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
void CSetConditionsDlg::EnableMenuButton()
{
	m_cButtonSetLineKisei.EnableWindow(!g_pDataManager->m_bOpenLineKiseiMatrix);
	m_cButtonSetKatashikiKisei.EnableWindow(!g_pDataManager->m_bOpenKatashikiKiseiMatrix);
}
