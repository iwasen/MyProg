//*****************************************************************************************************
//  1. ファイル名
//		MenuFS05.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		発射機部駆動装置（結線図）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS05.h"


// CMenuFS05 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS05, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::CMenuFS05
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
CMenuFS05::CMenuFS05(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS05::IDD, pParent)
{
}


void CMenuFS05::DoDataExchange(CDataExchange* pDX)
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
	DDX_Control(pDX, IDC_BUTTON_ACTION_12, m_cButtonAction12);
	DDX_Control(pDX, IDC_BUTTON_ACTION_13, m_cButtonAction13);
	DDX_Control(pDX, IDC_BUTTON_ACTION_14, m_cButtonAction14);
	DDX_Control(pDX, IDC_BUTTON_ACTION_15, m_cButtonAction15);
	DDX_Control(pDX, IDC_BUTTON_ACTION_16, m_cButtonAction16);
	DDX_Control(pDX, IDC_BUTTON_ACTION_17, m_cButtonAction17);
	DDX_Control(pDX, IDC_BUTTON_ACTION_18, m_cButtonAction18);
	DDX_Control(pDX, IDC_BUTTON_ACTION_19, m_cButtonAction19);
	DDX_Control(pDX, IDC_BUTTON_ACTION_20, m_cButtonAction20);
	DDX_Control(pDX, IDC_BUTTON_ACTION_21, m_cButtonAction21);
	DDX_Control(pDX, IDC_BUTTON_ACTION_22, m_cButtonAction22);
	DDX_Control(pDX, IDC_BUTTON_ACTION_23, m_cButtonAction23);
	DDX_Control(pDX, IDC_BUTTON_ACTION_24, m_cButtonAction24);
}


BEGIN_MESSAGE_MAP(CMenuFS05, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFS05::OnBnClickedButtonAction1)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS05::OnBnClickedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFS05::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFS05::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFS05::OnBnClickedButtonAction5)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_6, &CMenuFS05::OnBnClickedButtonAction6)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_7, &CMenuFS05::OnBnClickedButtonAction7)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_8, &CMenuFS05::OnBnClickedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_9, &CMenuFS05::OnBnClickedButtonAction9)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_10, &CMenuFS05::OnBnClickedButtonAction10)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_11, &CMenuFS05::OnBnClickedButtonAction11)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_12, &CMenuFS05::OnBnClickedButtonAction12)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_13, &CMenuFS05::OnBnClickedButtonAction13)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_14, &CMenuFS05::OnBnClickedButtonAction14)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_15, &CMenuFS05::OnBnClickedButtonAction15)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_16, &CMenuFS05::OnBnClickedButtonAction16)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_17, &CMenuFS05::OnBnClickedButtonAction17)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_18, &CMenuFS05::OnBnClickedButtonAction18)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_19, &CMenuFS05::OnBnClickedButtonAction19)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_20, &CMenuFS05::OnBnClickedButtonAction20)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_21, &CMenuFS05::OnBnClickedButtonAction21)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_22, &CMenuFS05::OnBnClickedButtonAction22)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_23, &CMenuFS05::OnBnClickedButtonAction23)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_24, &CMenuFS05::OnBnClickedButtonAction24)
END_MESSAGE_MAP()


// CMenuFS05 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnInitDialog
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
BOOL CMenuFS05::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// 動作ステータス設定
	SetStatus(STATUS_SWITCH_INITIAL, STATUS_MOTOR_INITIAL, STATUS_BULB_INITIAL);

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「スタート」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction1()
{
	// 動作ステータス設定
	SetStatus(STATUS_SWITCH_WORKING, m_eMotorStatus, m_eBulbStatus);

	// 検知スイッチ初期値設定（プレッシャスイッチのみ異常、他は正常）
	m_cButtonAction2.SetCheck(TRUE);
	m_cButtonAction3.SetCheck(FALSE);
	m_cButtonAction4.SetCheck(TRUE);
	m_cButtonAction5.SetCheck(FALSE);
	m_cButtonAction6.SetCheck(TRUE);
	m_cButtonAction7.SetCheck(FALSE);
	m_cButtonAction8.SetCheck(TRUE);
	m_cButtonAction9.SetCheck(FALSE);
	m_cButtonAction10.SetCheck(TRUE);
	m_cButtonAction11.SetCheck(FALSE);
	m_cButtonAction12.SetCheck(TRUE);
	m_cButtonAction13.SetCheck(FALSE);
	m_cButtonAction14.SetCheck(FALSE);
	m_cButtonAction15.SetCheck(TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「温度スイッチ：正常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction2()
{
	// Flashへ操作指示送信
	SendAction(ACTION_2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction3
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「温度スイッチ：異常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction3()
{
	// Flashへ操作指示送信
	SendAction(ACTION_3);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction4
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「圧力スイッチ：正常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction4()
{
	// Flashへ操作指示送信
	SendAction(ACTION_4);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction5
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「圧力スイッチ：異常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction5()
{
	// Flashへ操作指示送信
	SendAction(ACTION_5);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction6
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ﾌｨﾙﾀ(ﾌﾟﾚｯｼｬ)ｽｲｯﾁ：正常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction6()
{
	// Flashへ操作指示送信
	SendAction(ACTION_6);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction7
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ﾌｨﾙﾀ(ﾌﾟﾚｯｼｬ)ｽｲｯﾁ：異常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction7()
{
	// Flashへ操作指示送信
	SendAction(ACTION_7);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction8
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ﾌｨﾙﾀ(ﾘﾀｰﾝ)ｽｲｯﾁ：正常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction8()
{
	// Flashへ操作指示送信
	SendAction(ACTION_8);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction9
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ﾌｨﾙﾀ(ﾘﾀｰﾝ)ｽｲｯﾁ：異常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction9()
{
	// Flashへ操作指示送信
	SendAction(ACTION_9);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction10
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「油量低下スイッチ：正常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction10()
{
	// Flashへ操作指示送信
	SendAction(ACTION_10);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction11
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「油量低下スイッチ：異常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction11()
{
	// Flashへ操作指示送信
	SendAction(ACTION_11);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction12
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「モータ高温スイッチ：正常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction12()
{
	// Flashへ操作指示送信
	SendAction(ACTION_12);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction13
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「モータ高温スイッチ：異常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction13()
{
	// Flashへ操作指示送信
	SendAction(ACTION_13);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction14
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「プレッシャスイッチ：正常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction14()
{
	// Flashへ操作指示送信
	SendAction(ACTION_14);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction15
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「プレッシャスイッチ：異常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction15()
{
	// Flashへ操作指示送信
	SendAction(ACTION_15);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction16
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
void CMenuFS05::OnBnClickedButtonAction16()
{
	// 動作ステータス設定
	SetStatus(STATUS_SWITCH_INITIAL, m_eMotorStatus, m_eBulbStatus);

	// Flashへ操作指示送信
	SendAction(ACTION_16);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction17
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「方位位置検出器」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction17()
{
	// 動作ステータス設定
	SetStatus(m_eSwitchStatus, STATUS_MOTOR_WORKING, m_eBulbStatus);

	// Flashへ操作指示送信
	SendAction(ACTION_17);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction18
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「発射機部　右」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction18()
{
	// 動作ステータス設定
	SetStatus(m_eSwitchStatus, STATUS_MOTOR_WORKING, m_eBulbStatus);

	// Flashへ操作指示送信
	SendAction(ACTION_18);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction19
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「発射機部　左」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction19()
{
	// 動作ステータス設定
	SetStatus(m_eSwitchStatus, STATUS_MOTOR_WORKING, m_eBulbStatus);

	// Flashへ操作指示送信
	SendAction(ACTION_19);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction20
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「発射機部　上」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction20()
{
	// 動作ステータス設定
	SetStatus(m_eSwitchStatus, STATUS_MOTOR_WORKING, m_eBulbStatus);

	// Flashへ操作指示送信
	SendAction(ACTION_20);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction21
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「発射機部　下」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction21()
{
	// 動作ステータス設定
	SetStatus(m_eSwitchStatus, STATUS_MOTOR_WORKING, m_eBulbStatus);

	// Flashへ操作指示送信
	SendAction(ACTION_21);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction22
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
void CMenuFS05::OnBnClickedButtonAction22()
{
	// 動作ステータス設定
	SetStatus(m_eSwitchStatus, STATUS_MOTOR_INITIAL, m_eBulbStatus);

	// Flashへ操作指示送信
	SendAction(ACTION_22);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction23
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「収納（高低角３５ミル以下）」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::OnBnClickedButtonAction23()
{
	// 動作ステータス設定
	SetStatus(m_eSwitchStatus, m_eMotorStatus, STATUS_BULB_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_23);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::OnBnClickedButtonAction24
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
void CMenuFS05::OnBnClickedButtonAction24()
{
	// 動作ステータス設定
	SetStatus(m_eSwitchStatus, m_eMotorStatus, STATUS_BULB_INITIAL);

	// Flashへ操作指示送信
	SendAction(ACTION_24);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::SetStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		動作ステータス設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		ESwitchStatus	eSwitchStatus		[I] 検知スイッチ動作ステータス
//		EMotorStatus	eMotorStatus		[I] 駆動サーボモータ動作ステータス
//		EBulbStatus		eBulbStatus			[I] 方位・高低油圧バルブ動作ステータス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS05::SetStatus(ESwitchStatus eSwitchStatus, EMotorStatus eMotorStatus, EBulbStatus eBulbStatus)
{
	// 動作ステータス設定
	m_eSwitchStatus = eSwitchStatus;
	m_eMotorStatus = eMotorStatus;
	m_eBulbStatus = eBulbStatus;

	// メニューボタン設定
	SetMenuButton();
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS05::SetMenuButton
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
void CMenuFS05::SetMenuButton()
{
	switch (m_eSwitchStatus) {
	case STATUS_SWITCH_INITIAL:		// 初期状態
		m_cButtonAction1.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction2.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction3.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction4.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction7.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction8.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction9.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction10.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction11.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction12.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction13.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction14.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction15.SetEnableAndCheck(FALSE, FALSE);
		m_cButtonAction16.SetEnable(FALSE);
		break;
	case STATUS_SWITCH_WORKING:		// 検知スイッチ動作中
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(TRUE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnable(TRUE);
		m_cButtonAction5.SetEnable(TRUE);
		m_cButtonAction6.SetEnable(TRUE);
		m_cButtonAction7.SetEnable(TRUE);
		m_cButtonAction8.SetEnable(TRUE);
		m_cButtonAction9.SetEnable(TRUE);
		m_cButtonAction10.SetEnable(TRUE);
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(TRUE);
		m_cButtonAction13.SetEnable(TRUE);
		m_cButtonAction14.SetEnable(TRUE);
		m_cButtonAction15.SetEnable(TRUE);
		m_cButtonAction16.SetEnable(TRUE);
		break;
	}

	switch (m_eMotorStatus) {
	case STATUS_MOTOR_INITIAL:		// 初期状態
		m_cButtonAction17.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction18.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction19.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction20.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction21.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction22.SetEnable(FALSE);
		break;
	case STATUS_MOTOR_WORKING:		// 駆動サーボモータ動作中
		m_cButtonAction17.SetEnable(TRUE);
		m_cButtonAction18.SetEnable(TRUE);
		m_cButtonAction19.SetEnable(TRUE);
		m_cButtonAction20.SetEnable(TRUE);
		m_cButtonAction21.SetEnable(TRUE);
		m_cButtonAction22.SetEnable(TRUE);
		break;
	}

	switch (m_eBulbStatus) {
	case STATUS_BULB_INITIAL:		// 初期状態
		m_cButtonAction23.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction24.SetEnable(FALSE);
		break;
	case STATUS_BULB_WORKING:		// 方位・高低油圧バルブ動作中
		m_cButtonAction23.SetEnable(FALSE);
		m_cButtonAction24.SetEnable(TRUE);
		break;
	}
}
