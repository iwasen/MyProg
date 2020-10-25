//*****************************************************************************************************
//  1. ファイル名
//		MenuFS03.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ホイストモータ系（結線図）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS03.h"

// CMenuFS03 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS03, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::CMenuFS03
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
CMenuFS03::CMenuFS03(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS03::IDD, pParent)
{

}

void CMenuFS03::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_BUTTON_ACTION_1, m_cButtonAction1);
	DDX_Control(pDX, IDC_BUTTON_ACTION_2, m_cButtonAction2);
	DDX_Control(pDX, IDC_BUTTON_ACTION_3, m_cButtonAction3);
	DDX_Control(pDX, IDC_BUTTON_ACTION_4, m_cButtonAction4);
	DDX_Control(pDX, IDC_BUTTON_ACTION_5, m_cButtonAction5);
	DDX_Control(pDX, IDC_BUTTON_ACTION_6, m_cButtonAction6);
	DDX_Control(pDX, IDC_BUTTON_ACTION_7, m_cButtonAction7);
	DDX_Control(pDX, IDC_BUTTON_ACTION_8, m_cButtonAction8);
	DDX_Control(pDX, IDC_BUTTON_ACTION_9, m_cButtonAction9);
	DDX_Control(pDX, IDC_BUTTON_ACTION_10, m_cButtonAction10);
	DDX_Control(pDX, IDC_BUTTON_ACTION_11, m_cButtonAction11);
}


BEGIN_MESSAGE_MAP(CMenuFS03, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFS03::OnBnClickedButtonAction1)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS03::OnBnClickedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFS03::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFS03::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFS03::OnBnClickedButtonAction5)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_6, &CMenuFS03::OnBnClickedButtonAction6)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_7, &CMenuFS03::OnBnClickedButtonAction7)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_8, &CMenuFS03::OnBnClickedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_9, &CMenuFS03::OnBnClickedButtonAction9)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_10, &CMenuFS03::OnBnClickedButtonAction10)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_11, &CMenuFS03::OnBnClickedButtonAction11)
END_MESSAGE_MAP()


// CMenuFS03 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnInitDialog
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
BOOL CMenuFS03::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// 動作ステータス設定
	SetStatus(STATUS_TRAVEL_INITIAL, STATUS_HOIST_INITIAL);

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「CAGE UP」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS03::OnBnClickedButtonAction1()
{
	// 動作ステータス設定
	SetStatus(STATUS_TRAVEL_CAGE_UP, m_eHoistStatus);

	// Flashへ操作指示送信
	SendAction(1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「CAGE DOWN」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS03::OnBnClickedButtonAction2()
{
	// 動作ステータス設定
	SetStatus(STATUS_TRAVEL_CAGE_DOWN, m_eHoistStatus);

	// Flashへ操作指示送信
	SendAction(2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction3
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
void CMenuFS03::OnBnClickedButtonAction3()
{
	// 動作ステータス設定
	SetStatus(STATUS_TRAVEL_INITIAL, m_eHoistStatus);

	// Flashへ操作指示送信
	SendAction(3);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction4
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
void CMenuFS03::OnBnClickedButtonAction4()
{
	// 動作ステータス設定
	SetStatus(m_eTravelStatus, STATUS_HOIST_BOOM_MENU);

	// Flashへ操作指示送信
	SendAction(4);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction5
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
void CMenuFS03::OnBnClickedButtonAction5()
{
	// 動作ステータス設定
	SetStatus(m_eTravelStatus, STATUS_HOIST_READY_LAMP);

	// Flashへ操作指示送信
	SendAction(5);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction6
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
void CMenuFS03::OnBnClickedButtonAction6()
{
	// 動作ステータス設定
	SetStatus(m_eTravelStatus, STATUS_HOIST_LEFT);

	// Flashへ操作指示送信
	SendAction(6);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction7
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
void CMenuFS03::OnBnClickedButtonAction7()
{
	// 動作ステータス設定
	SetStatus(m_eTravelStatus, STATUS_HOIST_BOTH);

	// Flashへ操作指示送信
	SendAction(7);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction8
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
void CMenuFS03::OnBnClickedButtonAction8()
{
	// 動作ステータス設定
	SetStatus(m_eTravelStatus, STATUS_HOIST_RIGHT);

	// Flashへ操作指示送信
	SendAction(8);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction9
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
void CMenuFS03::OnBnClickedButtonAction9()
{
	// 動作ステータス設定
	SetStatus(m_eTravelStatus, STATUS_HOIST_HOOK_UP);

	// Flashへ操作指示送信
	SendAction(9);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction10
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
void CMenuFS03::OnBnClickedButtonAction10()
{
	// 動作ステータス設定
	SetStatus(m_eTravelStatus, STATUS_HOIST_HOOK_DOWN);

	// Flashへ操作指示送信
	SendAction(10);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::OnBnClickedButtonAction11
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
void CMenuFS03::OnBnClickedButtonAction11()
{
	// 動作ステータス設定
	SetStatus(m_eTravelStatus, STATUS_HOIST_INITIAL);

	// Flashへ操作指示送信
	SendAction(11);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::SetStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		動作ステータス設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		ETravelStatus	eTravelStatus		[I] トラベルロック動作ステータス
//		EHoistStatus	eHoistStatus		[I] ホイストモータ動作ステータス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS03::SetStatus(ETravelStatus eTravelStatus, EHoistStatus eHoistStatus)
{
	// 動作ステータス設定
	m_eTravelStatus = eTravelStatus;
	m_eHoistStatus = eHoistStatus;

	// メニューボタン設定
	SetMenuButton();
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS03::SetMenuButton
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
void CMenuFS03::SetMenuButton()
{
	switch (m_eTravelStatus) {
	case STATUS_TRAVEL_INITIAL:
		m_cButtonAction1.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction2.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction3.SetEnableAndCheck(FALSE, FALSE);
		break;
	case STATUS_TRAVEL_CAGE_UP:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		break;
	case STATUS_TRAVEL_CAGE_DOWN:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		break;
	}

	switch (m_eHoistStatus) {
	case STATUS_HOIST_INITIAL:
		m_cButtonAction4.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction5.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction6.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction7.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction8.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction9.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction10.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		break;
	case STATUS_HOIST_BOOM_MENU:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		break;
	case STATUS_HOIST_READY_LAMP:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		break;
	case STATUS_HOIST_LEFT:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(TRUE);
		m_cButtonAction10.SetEnable(TRUE);
		m_cButtonAction11.SetEnable(TRUE);
		break;
	case STATUS_HOIST_BOTH:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(TRUE);
		m_cButtonAction10.SetEnable(TRUE);
		m_cButtonAction11.SetEnable(TRUE);
		break;
	case STATUS_HOIST_RIGHT:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(TRUE);
		m_cButtonAction10.SetEnable(TRUE);
		m_cButtonAction11.SetEnable(TRUE);
		break;
	case STATUS_HOIST_HOOK_UP:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		break;
	case STATUS_HOIST_HOOK_DOWN:
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		break;
	}
}
