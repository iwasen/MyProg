//*****************************************************************************************************
//  1. ファイル名
//		MenuFE07.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		変速操向器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFE07.h"

// タイマーイベントID
#define IDT_WAIT	0			// 準備完了待ち
#define IDT_BRAKE	1			// ブレーキ動作

// タイマー値
#define	TIMER_FORWARD	50000	// 50秒　前進
#define	TIMER_REVERSE	50000	// 50秒　後進
#define	TIMER_TURN		10000	// 10秒　超信地旋回
#define TIMER_TURN2		35000	// 35秒　超信地旋回後の左右旋回
#define TIMER_BRAKE		8000	//  8秒　ブレーキ

// CMenuFE07 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFE07, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::CMenuFE07
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
CMenuFE07::CMenuFE07(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFE07::IDD, pParent)
{
}

void CMenuFE07::DoDataExchange(CDataExchange* pDX)
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


BEGIN_MESSAGE_MAP(CMenuFE07, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFE07::OnBnClickedButtonAction1)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFE07::OnBnClickedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFE07::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFE07::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFE07::OnBnClickedButtonAction5)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_6, &CMenuFE07::OnBnClickedButtonAction6)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_7, &CMenuFE07::OnBnClickedButtonAction7)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_8, &CMenuFE07::OnBnClickedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_9, &CMenuFE07::OnBnClickedButtonAction9)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_10, &CMenuFE07::OnBnClickedButtonAction10)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_11, &CMenuFE07::OnBnClickedButtonAction11)
	ON_WM_TIMER()
END_MESSAGE_MAP()


// CMenuFE07 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnInitDialog
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
BOOL CMenuFE07::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	m_eStatus = STATUS_STOP;
	m_nClutch = 1;
	m_bArrow = TRUE;
	m_bWait = FALSE;
	m_bTurn = FALSE;
	m_bBrake = FALSE;

	// メニューボタン設定
	SetMenuButton();

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「中立」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction1()
{
	m_eStatus = STATUS_NEUTRAL;

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「前進」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction2()
{
	m_eStatus = STATUS_FORWARD;
	m_bWait = TRUE;

	// タイマー設定
	SetTimer(IDT_WAIT, m_bTurn ? TIMER_TURN : TIMER_FORWARD, NULL);

	m_bTurn = FALSE;

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction3
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「後進」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction3()
{
	m_eStatus = STATUS_REVERSE;
	m_bWait = TRUE;

	// タイマー設定
	SetTimer(IDT_WAIT, m_bTurn ? TIMER_TURN : TIMER_REVERSE, NULL);

	m_bTurn = FALSE;

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_3);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction4
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「超信地旋回」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction4()
{
	m_eStatus = STATUS_STATIONARY_TURN;
	m_bWait = TRUE;

	// タイマー設定
	SetTimer(IDT_WAIT, TIMER_TURN, NULL);

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_4);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction5
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「矢印消去」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction5()
{
	m_bArrow = FALSE;

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_5);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction6
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「速度ＵＰ］ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction6()
{
	if (m_nClutch < 3)
		m_nClutch++;

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_6);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction7
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「速度ＤＯＷＮ］ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction7()
{
	if (m_nClutch > 1)
		m_nClutch--;

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_7);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction8
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「右旋回」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction8()
{
	m_bTurn = TRUE;

	if (m_eStatus == STATUS_STATIONARY_TURN) {
		m_bWait = TRUE;
		SetTimer(IDT_WAIT, TIMER_TURN2, NULL);
	}

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_8);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction9
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「左旋回」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction9()
{
	m_bTurn = TRUE;

	if (m_eStatus == STATUS_STATIONARY_TURN) {
		m_bWait = TRUE;
		SetTimer(IDT_WAIT, TIMER_TURN2, NULL);
	}

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_9);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction10
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ブレーキ」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction10()
{
	m_bBrake = TRUE;

	// タイマー設定
	SetTimer(IDT_BRAKE, TIMER_BRAKE, NULL);

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_10);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnBnClickedButtonAction11
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「停止」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnBnClickedButtonAction11()
{
	m_eStatus = STATUS_STOP;
	m_nClutch = 1;
	m_bArrow = TRUE;
	m_bWait = FALSE;
	m_bTurn = FALSE;
	m_bBrake = FALSE;

	// メニューボタン設定
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_11);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::SendAction
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メニューボタン設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		int		nActionNo		[I] アクション番号
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::SendAction(int nActionNo)
{
	CUIntArray aParams;

	aParams.Add(m_nClutch);
	CMenuCommon::SendAction(nActionNo, &aParams);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::SetMenuButton
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
void CMenuFE07::SetMenuButton()
{
	m_cButtonAction1.SetCheck(m_eStatus == STATUS_NEUTRAL ? 1 : 0);
	m_cButtonAction2.SetCheck(m_eStatus == STATUS_FORWARD ? 1 : 0);
	m_cButtonAction3.SetCheck(m_eStatus == STATUS_REVERSE ? 1 : 0);
	m_cButtonAction4.SetCheck(m_eStatus == STATUS_STATIONARY_TURN ? 1 : 0);

	if (m_bWait && m_eStatus != STATUS_STATIONARY_TURN) {
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.ShowWindow(SW_HIDE);
		m_cButtonAction6.ShowWindow(SW_HIDE);
		m_cButtonAction7.ShowWindow(SW_HIDE);
		m_cButtonAction8.ShowWindow(SW_HIDE);
		m_cButtonAction9.ShowWindow(SW_HIDE);
		m_cButtonAction10.ShowWindow(SW_HIDE);
		m_cButtonAction11.ShowWindow(SW_HIDE);
	} else {
		switch (m_eStatus) {
		case STATUS_STOP:
			m_cButtonAction1.SetEnable(TRUE);
			m_cButtonAction2.SetEnable(TRUE);
			m_cButtonAction3.SetEnable(TRUE);
			m_cButtonAction4.SetEnable(TRUE);
			m_cButtonAction5.SetEnable(FALSE);
			m_cButtonAction6.SetEnable(FALSE);
			m_cButtonAction7.SetEnable(FALSE);
			m_cButtonAction8.SetEnable(FALSE);
			m_cButtonAction9.SetEnable(FALSE);
			m_cButtonAction10.SetEnable(FALSE);
			m_cButtonAction11.SetEnable(FALSE);
			break;
		case STATUS_NEUTRAL:
			m_cButtonAction1.SetEnable(FALSE);
			m_cButtonAction2.SetEnable(FALSE);
			m_cButtonAction3.SetEnable(FALSE);
			m_cButtonAction4.SetEnable(FALSE);
			m_cButtonAction5.SetEnable(m_bArrow);
			m_cButtonAction6.SetEnable(FALSE);
			m_cButtonAction7.SetEnable(FALSE);
			m_cButtonAction8.SetEnable(FALSE);
			m_cButtonAction9.SetEnable(FALSE);
			m_cButtonAction10.SetEnable(FALSE);
			m_cButtonAction11.SetEnable(TRUE);
			break;
		case STATUS_FORWARD:
			m_cButtonAction1.SetEnable(FALSE);
			m_cButtonAction2.SetEnableAndCheck(m_bTurn, !m_bTurn);
			m_cButtonAction3.SetEnable(FALSE);
			m_cButtonAction4.SetEnable(FALSE);
			m_cButtonAction5.SetEnable(m_bArrow);
			m_cButtonAction6.SetEnable(!m_bTurn && m_nClutch < 3);
			m_cButtonAction7.SetEnable(!m_bTurn && m_nClutch > 1);
			m_cButtonAction8.SetEnable(!m_bTurn);
			m_cButtonAction9.SetEnable(!m_bTurn);
			m_cButtonAction10.SetEnable(!m_bBrake);
			m_cButtonAction11.SetEnable(TRUE);
			break;
		case STATUS_REVERSE:
			m_cButtonAction1.SetEnable(FALSE);
			m_cButtonAction2.SetEnable(FALSE);
			m_cButtonAction3.SetEnableAndCheck(m_bTurn, !m_bTurn);
			m_cButtonAction4.SetEnable(FALSE);
			m_cButtonAction5.SetEnable(m_bArrow);
			m_cButtonAction6.SetEnable(FALSE);
			m_cButtonAction7.SetEnable(FALSE);
			m_cButtonAction8.SetEnable(!m_bTurn);
			m_cButtonAction9.SetEnable(!m_bTurn);
			m_cButtonAction10.SetEnable(!m_bBrake);
			m_cButtonAction11.SetEnable(TRUE);
			break;
		case STATUS_STATIONARY_TURN:
			m_cButtonAction1.SetEnable(FALSE);
			m_cButtonAction2.SetEnable(FALSE);
			m_cButtonAction3.SetEnable(FALSE);
			m_cButtonAction4.SetEnable(FALSE);
			m_cButtonAction5.SetEnable(m_bArrow && m_bTurn);
			m_cButtonAction6.SetEnable(FALSE);
			m_cButtonAction7.SetEnable(FALSE);
			m_cButtonAction8.SetEnable(!m_bTurn && !m_bWait);
			m_cButtonAction9.SetEnable(!m_bTurn && !m_bWait);
			m_cButtonAction10.SetEnable(FALSE);
			m_cButtonAction11.SetEnable(m_bTurn);
			break;
		}

		BOOL bShow = (m_eStatus == STATUS_STATIONARY_TURN && (!m_bTurn || m_bWait)) ? SW_HIDE : SW_SHOW;
		m_cButtonAction5.ShowWindow(bShow);
		m_cButtonAction6.ShowWindow(bShow);
		m_cButtonAction7.ShowWindow(bShow);
		m_cButtonAction8.ShowWindow(SW_SHOW);
		m_cButtonAction9.ShowWindow(SW_SHOW);
		m_cButtonAction10.ShowWindow(bShow);
		m_cButtonAction11.ShowWindow(bShow);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFE07::OnTimer
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メニューボタン設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT_PTR	nIDEvent		[I] タイマーイベントID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFE07::OnTimer(UINT_PTR nIDEvent)
{
	switch (nIDEvent) {
	case IDT_WAIT:		// 動作準備完了
		m_bWait = FALSE;
		SetMenuButton();
		KillTimer(nIDEvent);
		break;
	case IDT_BRAKE:		// ブレーキ
		m_bBrake = FALSE;
		SetMenuButton();
		SendAction(m_eStatus);
		KillTimer(nIDEvent);
		break;
	}

	CMenuCommon::OnTimer(nIDEvent);
}
