//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08_3.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置−発射制御器−電力分配器（トラベルロック）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS08_3.h"


// CMenuFS08_3 ダイアログ

IMPLEMENT_DYNAMIC(CMenuFS08_3, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_3::CMenuFS08_3
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
CMenuFS08_3::CMenuFS08_3(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS08_3::IDD, pParent)
{
}

void CMenuFS08_3::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_BUTTON_ACTION_28, m_cButtonAction28);
	DDX_Control(pDX, IDC_BUTTON_ACTION_29, m_cButtonAction29);
	DDX_Control(pDX, IDC_BUTTON_ACTION_30, m_cButtonAction30);
	DDX_Control(pDX, IDC_BUTTON_EXIT, m_cButtonExit);
}


BEGIN_MESSAGE_MAP(CMenuFS08_3, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_28, &CMenuFS08_3::OnBnClickedButtonAction28)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_29, &CMenuFS08_3::OnBnClickedButtonAction29)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_30, &CMenuFS08_3::OnBnClickedButtonAction30)
END_MESSAGE_MAP()


// CMenuFS08_3 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_3::OnInitDialog
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
BOOL CMenuFS08_3::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// 動作ステータス設定
	SetStatus(STATUS_INITIAL);

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_3::OnBnClickedButtonAction28
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
void CMenuFS08_3::OnBnClickedButtonAction28()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_28);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_3::OnBnClickedButtonAction29
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
void CMenuFS08_3::OnBnClickedButtonAction29()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_29);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_3::OnBnClickedButtonAction30
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
void CMenuFS08_3::OnBnClickedButtonAction30()
{
	// 動作ステータス設定
	SetStatus(STATUS_INITIAL);

	// Flashへ操作指示送信
	SendAction(ACTION_30);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_3::SetStatus
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
void CMenuFS08_3::SetStatus(EStatus eStatus)
{
	// 動作ステータス設定
	m_eStatus = eStatus;

	// メニューボタン設定
	SetMenuButton();
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_3::SetMenuButton
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
void CMenuFS08_3::SetMenuButton()
{
	switch (m_eStatus) {
	case STATUS_INITIAL:
		m_cButtonAction28.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction29.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction30.SetEnable(FALSE);
		m_cButtonExit.SetEnable(TRUE);
		break;
	case STATUS_WORKING:
		m_cButtonAction28.SetEnable(FALSE);
		m_cButtonAction29.SetEnable(FALSE);
		m_cButtonAction30.SetEnable(TRUE);
		m_cButtonExit.SetEnable(FALSE);
		break;
	}
}
