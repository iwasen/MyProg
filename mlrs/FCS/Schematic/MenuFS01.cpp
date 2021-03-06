//*****************************************************************************************************
//  1. ファイル名
//		MenuFS01.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機能系統図（概要）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS01.h"

// CMenuFS01 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS01, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::CMenuFS01
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
CMenuFS01::CMenuFS01(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS01::IDD, pParent)
{
}

void CMenuFS01::DoDataExchange(CDataExchange* pDX)
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
}


BEGIN_MESSAGE_MAP(CMenuFS01, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFS01::OnBnClickedButtonAction1)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS01::OnBnClickedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFS01::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFS01::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFS01::OnBnClickedButtonAction5)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_6, &CMenuFS01::OnBnClickedButtonAction6)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_7, &CMenuFS01::OnBnClickedButtonAction7)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_8, &CMenuFS01::OnBnClickedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_9, &CMenuFS01::OnBnClickedButtonAction9)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_10, &CMenuFS01::OnBnClickedButtonAction10)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_11, &CMenuFS01::OnBnClickedButtonAction11)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_12, &CMenuFS01::OnBnClickedButtonAction12)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_13, &CMenuFS01::OnBnClickedButtonAction13)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_14, &CMenuFS01::OnBnClickedButtonAction14)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_15, &CMenuFS01::OnBnClickedButtonAction15)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_16, &CMenuFS01::OnBnClickedButtonAction16)
END_MESSAGE_MAP()


// CMenuFS01 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnInitDialog
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
BOOL CMenuFS01::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// 動作ステータス設定
	SetStatus(STATUS_INITIAL);

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「方位駆動系：ＦＣＰ操作」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction1()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「方位駆動系：ＢＣ操作」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
// 「方位駆動系：ＢＣ操作」ボタンクリック処理
void CMenuFS01::OnBnClickedButtonAction2()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction3
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「高低駆動系：ＦＣＰ操作」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction3()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_3);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction4
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「高低駆動系：ＢＣ操作」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction4()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_4);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction5
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「トラベルロック」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction5()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_5);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction6
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ブーム駆動」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction6()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_6);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction7
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ホイスト駆動」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction7()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_7);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction8
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「電力供給系」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction8()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_8);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction9
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「擬製弾制御信号」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction9()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_9);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction10
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「LP/Cｲﾝﾀｰﾌｪｰｽ信号信管測合信号」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction10()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_10);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction11
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「電圧点検器信号」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction11()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_11);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction12
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「補正データ」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction12()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_12);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction13
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「高低、方位及び位置ﾃﾞｰﾀ」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction13()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_13);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction14
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「データの受信」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction14()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_14);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction15
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「データの送信」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS01::OnBnClickedButtonAction15()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_15);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::OnBnClickedButtonAction16
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
void CMenuFS01::OnBnClickedButtonAction16()
{
	// 動作ステータス設定
	SetStatus(STATUS_INITIAL);

	// Flashへ操作指示送信
	SendAction(ACTION_16);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::SetStatus
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
void CMenuFS01::SetStatus(EStatus eStatus)
{
	// 動作ステータス設定
	m_eStatus = eStatus;

	// メニューボタン設定
	SetMenuButton();
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS01::SetMenuButton
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
void CMenuFS01::SetMenuButton()
{
	switch (m_eStatus) {
	case STATUS_INITIAL:
		m_cButtonAction1.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction2.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction3.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction4.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction5.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction6.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction7.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction8.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction9.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction10.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction11.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction12.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction13.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction14.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction15.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction16.SetEnable(FALSE);
		break;
	case STATUS_WORKING:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		m_cButtonAction14.SetEnable(FALSE);
		m_cButtonAction15.SetEnable(FALSE);
		m_cButtonAction16.SetEnable(TRUE);
		break;
	}
}
