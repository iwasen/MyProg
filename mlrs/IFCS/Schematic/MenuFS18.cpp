//*****************************************************************************************************
//  1. ファイル名
//		MenuFS18.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ホイストモータ（機能スケマチック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS18.h"

// タイマーID
#define IDT_HOOK_UP_STOP		1
#define IDT_HOOK_DOWN_STOP		2

// CMenuFS18 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS18, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::CMenuFS18
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
CMenuFS18::CMenuFS18(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS18::IDD, pParent)
{
	m_bWorking = FALSE;
	m_bPause = FALSE;
}

void CMenuFS18::DoDataExchange(CDataExchange* pDX)
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
}


BEGIN_MESSAGE_MAP(CMenuFS18, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_1, &CMenuFS18::OnBnClickedButtonAction1)
	ON_CONTROL(BN_PUSHED, IDC_BUTTON_ACTION_2, &CMenuFS18::OnBnPushedButtonAction2)
	ON_CONTROL(BN_UNPUSHED, IDC_BUTTON_ACTION_2, &CMenuFS18::OnBnUnpushedButtonAction2)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_3, &CMenuFS18::OnBnClickedButtonAction3)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_4, &CMenuFS18::OnBnClickedButtonAction4)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_5, &CMenuFS18::OnBnClickedButtonAction5)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_6, &CMenuFS18::OnBnClickedButtonAction6)
	ON_CONTROL(BN_PUSHED, IDC_BUTTON_ACTION_7, &CMenuFS18::OnBnPushedButtonAction7)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_7, &CMenuFS18::OnBnClickedButtonAction7)
	ON_CONTROL(BN_PUSHED, IDC_BUTTON_ACTION_8, &CMenuFS18::OnBnPushedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_8, &CMenuFS18::OnBnClickedButtonAction8)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_9, &CMenuFS18::OnBnClickedButtonAction9)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_10, &CMenuFS18::OnBnClickedButtonAction10)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_11, &CMenuFS18::OnBnClickedButtonAction11)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_12, &CMenuFS18::OnBnClickedButtonAction12)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_13, &CMenuFS18::OnBnClickedButtonAction13)
	ON_WM_TIMER()
END_MESSAGE_MAP()


// CMenuFS18 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnInitDialog
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
BOOL CMenuFS18::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// 動作ステータス設定
	SetStatus(STATUS_INITIAL, FALSE);

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction1
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
void CMenuFS18::OnBnClickedButtonAction1()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_CONTROL, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnPushedButtonAction2
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
void CMenuFS18::OnBnPushedButtonAction2()
{
	// Flashへ操作指示送信
	SendAction(ACTION_2, 1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnUnpushedButtonAction2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「使用可表示灯」ボタンリリース処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::OnBnUnpushedButtonAction2()
{
	// Flashへ操作指示送信
	SendAction(ACTION_2, 0);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction3
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ブーム選択スイッチ」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction3()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_SELECT, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_3);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction4
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
void CMenuFS18::OnBnClickedButtonAction4()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_LEFT, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_4);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction5
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
void CMenuFS18::OnBnClickedButtonAction5()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_BOTH, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_5);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction6
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
void CMenuFS18::OnBnClickedButtonAction6()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_RIGHT, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_6);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnPushedButtonAction7
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ブーム縮」ボタンプッシュ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::OnBnPushedButtonAction7()
{
	// Flashへ操作指示送信
	SendAction(ACTION_7, 1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction7
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
void CMenuFS18::OnBnClickedButtonAction7()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_SHORT, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_7);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnPushedButtonAction8
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「ブーム伸」ボタンプッシュ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::OnBnPushedButtonAction8()
{
	// Flashへ操作指示送信
	SendAction(ACTION_8, 1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction8
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
void CMenuFS18::OnBnClickedButtonAction8()
{
	// 動作ステータス設定
	SetStatus(STATUS_BOOM_LONG, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_8);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction9
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
void CMenuFS18::OnBnClickedButtonAction9()
{
	// 動作ステータス設定
	SetStatus(STATUS_HOOK_UP_1, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_9);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction10
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「フック上停止」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction10()
{
	// 動作ステータス設定
	SetStatus(STATUS_HOOK_STOP);

	// Flashへ操作指示送信
	SendAction(ACTION_10);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction11
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
// 
void CMenuFS18::OnBnClickedButtonAction11()
{
	// 動作ステータス設定
	SetStatus(STATUS_HOOK_DOWN_1, TRUE);

	// Flashへ操作指示送信
	SendAction(ACTION_11);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction12
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「フック下停止」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction12()
{
	// 動作ステータス設定
	SetStatus(STATUS_HOOK_STOP);

	// Flashへ操作指示送信
	SendAction(ACTION_12);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnBnClickedButtonAction13
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「一時停止」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::OnBnClickedButtonAction13()
{
	// 「低速」「通常」ボタンの有効化
	EnableSpeedButton(m_bPause);

	// 一時停止ステータス設定
	m_bPause = !m_bPause;
	SetMenuButton();

	// Flashへ操作指示送信
	SendAction(ACTION_13);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::SendAction
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
void CMenuFS18::SendAction(int nActionNo, int nParam2)
{
	CUIntArray aParams;

	aParams.Add(m_bPause);
	aParams.Add(nParam2);
	CMenuCommon::SendAction(nActionNo, &aParams);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::ActionEnd
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Flashからの動作終了通知受信処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		int		nActionNo		[I] アクション番号
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::ActionEnd(int nActionNo)
{
	if (m_bWorking) {
		switch (nActionNo) {
		case ACTION_9:
			if (m_eStatus == STATUS_HOOK_UP_1)
				SetTimer(IDT_HOOK_UP_STOP, 5000, NULL);
			break;
		case ACTION_11:
			if (m_eStatus == STATUS_HOOK_DOWN_1)
				SetTimer(IDT_HOOK_DOWN_STOP, 5000, NULL);
			break;
		}

		m_bWorking = FALSE;

		SetMenuButton();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::SetStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		動作ステータス設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		EStatus		eStatus		[I] 設定ステータス
//		BOOL		bWorking	[I] 動作中フラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS18::SetStatus(EStatus eStatus, BOOL bWorking)
{
	// 動作ステータス設定
	m_eStatus = eStatus;
	m_bWorking |= bWorking;

	// メニューボタン設定
	SetMenuButton();
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::SetMenuButton
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
void CMenuFS18::SetMenuButton()
{
	switch (m_eStatus) {
	case STATUS_INITIAL:
		m_cButtonAction1.SetEnableAndCheck(TRUE);
		m_cButtonAction2.SetEnable(TRUE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnableAndCheck(FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_CONTROL:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnableAndCheck(FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_SELECT:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnableAndCheck(TRUE);
		m_cButtonAction5.SetEnableAndCheck(TRUE);
		m_cButtonAction6.SetEnableAndCheck(TRUE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_LEFT:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction5.SetEnableAndCheck(FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(TRUE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_BOTH:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnableAndCheck(FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction6.SetEnableAndCheck(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_RIGHT:
		m_cButtonAction1.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(TRUE);
		m_cButtonAction4.SetEnableAndCheck(FALSE);
		m_cButtonAction5.SetEnableAndCheck(FALSE);
		m_cButtonAction6.SetEnableAndCheck(FALSE, TRUE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_SHORT:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(TRUE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_BOOM_LONG:
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
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_UP_1:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(TRUE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_UP_2:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(TRUE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(FALSE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_DOWN_1:
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
		m_cButtonAction12.SetEnable(TRUE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_DOWN_2:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(TRUE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(FALSE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	case STATUS_HOOK_STOP:
		m_cButtonAction1.SetEnable(FALSE);
		m_cButtonAction2.SetEnable(FALSE);
		m_cButtonAction3.SetEnable(FALSE);
		m_cButtonAction4.SetEnable(FALSE);
		m_cButtonAction5.SetEnable(FALSE);
		m_cButtonAction6.SetEnable(FALSE);
		m_cButtonAction7.SetEnable(FALSE);
		m_cButtonAction8.SetEnable(FALSE);
		m_cButtonAction9.SetEnable(TRUE);
		m_cButtonAction10.SetEnable(FALSE);
		m_cButtonAction11.SetEnable(TRUE);
		m_cButtonAction12.SetEnable(FALSE);
		m_cButtonAction13.SetEnable(FALSE);
		break;
	}

	if (m_bWorking) {
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
		m_cButtonAction13.SetEnable(TRUE);
	}

	m_cButtonAction13 = m_bPause ? "停止解除" : "一時停止";
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS18::OnTimer
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
void CMenuFS18::OnTimer(UINT_PTR nIDEvent)
{
	switch (nIDEvent) {
	case IDT_HOOK_UP_STOP:
		if (m_eStatus == STATUS_HOOK_UP_1) {
			// 動作ステータス設定
			SetStatus(STATUS_HOOK_UP_2, TRUE);

			// Flashへ操作指示送信
			SendAction(ACTION_9, 1);
		}
		break;
	case IDT_HOOK_DOWN_STOP:
		if (m_eStatus == STATUS_HOOK_DOWN_1) {
			// 動作ステータス設定
			SetStatus(STATUS_HOOK_DOWN_2, TRUE);

			// Flashへ操作指示送信
			SendAction(ACTION_11, 1);
		}
		break;
	}

	// タイマー停止
	KillTimer(nIDEvent);

	CMenuCommon::OnTimer(nIDEvent);
}
