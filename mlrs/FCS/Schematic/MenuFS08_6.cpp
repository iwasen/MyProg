//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08_6.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置−発射制御器−電力分配器（ブームモータ・ホイストモータ）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS08_6.h"


// CMenuFS08_6 ダイアログ

IMPLEMENT_DYNAMIC(CMenuFS08_6, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::CMenuFS08_6
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		CWnd*	pParent		[I] 親ウィンドウ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CMenuFS08_6::CMenuFS08_6(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS08_6::IDD, pParent)
{
}

void CMenuFS08_6::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_BUTTON_ACTION_40, m_cButtonAction40);
	DDX_Control(pDX, IDC_BUTTON_ACTION_41, m_cButtonAction41);
	DDX_Control(pDX, IDC_BUTTON_ACTION_42, m_cButtonAction42);
	DDX_Control(pDX, IDC_BUTTON_ACTION_43, m_cButtonAction43);
	DDX_Control(pDX, IDC_BUTTON_ACTION_44, m_cButtonAction44);
	DDX_Control(pDX, IDC_BUTTON_ACTION_45, m_cButtonAction45);
	DDX_Control(pDX, IDC_BUTTON_ACTION_46, m_cButtonAction46);
	DDX_Control(pDX, IDC_BUTTON_ACTION_47, m_cButtonAction47);
	DDX_Control(pDX, IDC_BUTTON_ACTION_48, m_cButtonAction48);
	DDX_Control(pDX, IDC_BUTTON_ACTION_49, m_cButtonAction49);
	DDX_Control(pDX, IDC_BUTTON_ACTION_50, m_cButtonAction50);
	DDX_Control(pDX, IDC_BUTTON_EXIT, m_cButtonExit);
}


BEGIN_MESSAGE_MAP(CMenuFS08_6, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_40, &CMenuFS08_6::OnBnClickedButtonAction40)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_41, &CMenuFS08_6::OnBnClickedButtonAction41)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_42, &CMenuFS08_6::OnBnClickedButtonAction42)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_43, &CMenuFS08_6::OnBnClickedButtonAction43)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_44, &CMenuFS08_6::OnBnClickedButtonAction44)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_45, &CMenuFS08_6::OnBnClickedButtonAction45)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_46, &CMenuFS08_6::OnBnClickedButtonAction46)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_47, &CMenuFS08_6::OnBnClickedButtonAction47)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_48, &CMenuFS08_6::OnBnClickedButtonAction48)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_49, &CMenuFS08_6::OnBnClickedButtonAction49)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_50, &CMenuFS08_6::OnBnClickedButtonAction50)
END_MESSAGE_MAP()


// CMenuFS08_6 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CMenuFS08_6::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// 動作ステータス設定
	SetStatus(STATUS_INITIAL);

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction40
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ブーム制御メニュー」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction40()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_MENU);

	// Flashへ操作指示送信
	SendAction(ACTION_40);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction41
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「発射機部　収納」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction41()
{
	// 動作ステータス設定
	SetStatus(STATUS_STRAGE);

	// Flashへ操作指示送信
	SendAction(ACTION_41);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction42
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「使用可表示灯」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction42()
{
	// 動作ステータス設定
	SetStatus(STATUS_READY_LAMP);

	// Flashへ操作指示送信
	SendAction(ACTION_42);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction43
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「左」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction43()
{
	// 動作ステータス設定
	SetStatus(STATUS_LEFT);

	// Flashへ操作指示送信
	SendAction(ACTION_43);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction44
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「同時」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction44()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOTH);

	// Flashへ操作指示送信
	SendAction(ACTION_44);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction45
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「右」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction45()
{
	// 動作ステータス設定
	SetStatus(STATUS_RIGHT);

	// Flashへ操作指示送信
	SendAction(ACTION_45);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction46
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ブーム縮」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction46()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_SHORT);

	// Flashへ操作指示送信
	SendAction(ACTION_46);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction47
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ブーム伸」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction47()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_LONG);

	// Flashへ操作指示送信
	SendAction(ACTION_47);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction48
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「フック上」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction48()
{
	// 動作ステータス設定
	SetStatus(STATUS_HOOK_UP);

	// Flashへ操作指示送信
	SendAction(ACTION_48);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction49
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「フック下」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction49()
{
	// 動作ステータス設定
	SetStatus(STATUS_HOOK_DOWN);

	// Flashへ操作指示送信
	SendAction(ACTION_49);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::OnBnClickedButtonAction50
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「リセット」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::OnBnClickedButtonAction50()
{
	// 動作ステータス設定
	SetStatus(STATUS_INITIAL);

	// Flashへ操作指示送信
	SendAction(ACTION_50);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::SetStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		動作ステータス設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		EStatus		eStatus		[I] 設定ステータス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::SetStatus(EStatus eStatus)
{
	// 動作ステータス設定
	m_eStatus = eStatus;

	// メニューボタン設定
	SetMenuButton();
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_6::SetMenuButton
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メニューボタン設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_6::SetMenuButton()
{
	switch (m_eStatus) {
	case STATUS_INITIAL:
		m_cButtonAction40.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction41.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction42.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction43.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction44.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction45.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction46.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction47.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction48.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction49.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction50.SetEnable(FALSE);
		break;
	case STATUS_BOOM_MENU:
	case STATUS_STRAGE:
	case STATUS_READY_LAMP:
		m_cButtonAction40.SetEnable(FALSE);
		m_cButtonAction41.SetEnable(FALSE);
		m_cButtonAction42.SetEnable(FALSE);
		m_cButtonAction43.SetEnable(FALSE);
		m_cButtonAction44.SetEnable(FALSE);
		m_cButtonAction45.SetEnable(FALSE);
		m_cButtonAction46.SetEnable(FALSE);
		m_cButtonAction47.SetEnable(FALSE);
		m_cButtonAction48.SetEnable(FALSE);
		m_cButtonAction49.SetEnable(FALSE);
		m_cButtonAction50.SetEnable(TRUE);
		break;
	case STATUS_LEFT:
	case STATUS_BOTH:
	case STATUS_RIGHT:
		m_cButtonAction40.SetEnable(FALSE);
		m_cButtonAction41.SetEnable(FALSE);
		m_cButtonAction42.SetEnable(FALSE);
		m_cButtonAction43.SetEnable(FALSE);
		m_cButtonAction44.SetEnable(FALSE);
		m_cButtonAction45.SetEnable(FALSE);
		m_cButtonAction46.SetEnable(TRUE);
		m_cButtonAction47.SetEnable(TRUE);
		m_cButtonAction48.SetEnable(TRUE);
		m_cButtonAction49.SetEnable(TRUE);
		m_cButtonAction50.SetEnable(TRUE);
		break;
	case STATUS_BOOM_SHORT:
	case STATUS_BOOM_LONG:
	case STATUS_HOOK_UP:
	case STATUS_HOOK_DOWN:
		m_cButtonAction40.SetEnable(FALSE);
		m_cButtonAction41.SetEnable(FALSE);
		m_cButtonAction42.SetEnable(FALSE);
		m_cButtonAction43.SetEnable(FALSE);
		m_cButtonAction44.SetEnable(FALSE);
		m_cButtonAction45.SetEnable(FALSE);
		m_cButtonAction46.SetEnable(FALSE);
		m_cButtonAction47.SetEnable(FALSE);
		m_cButtonAction48.SetEnable(FALSE);
		m_cButtonAction49.SetEnable(FALSE);
		m_cButtonAction50.SetEnable(TRUE);
		break;
	}

	m_cButtonExit.SetEnable(m_eStatus == STATUS_INITIAL);
}
