//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08_4.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置−発射制御器−電力分配器（方位・高低位置検出器及びサーボモータ）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS08_4.h"


// CMenuFS08_4 ダイアログ

IMPLEMENT_DYNAMIC(CMenuFS08_4, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::CMenuFS08_4
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
CMenuFS08_4::CMenuFS08_4(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS08_4::IDD, pParent)
{
}

void CMenuFS08_4::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_BUTTON_ACTION_31, m_cButtonAction31);
	DDX_Control(pDX, IDC_BUTTON_ACTION_32, m_cButtonAction32);
	DDX_Control(pDX, IDC_BUTTON_ACTION_33, m_cButtonAction33);
	DDX_Control(pDX, IDC_BUTTON_ACTION_34, m_cButtonAction34);
	DDX_Control(pDX, IDC_BUTTON_ACTION_35, m_cButtonAction35);
	DDX_Control(pDX, IDC_BUTTON_ACTION_36, m_cButtonAction36);
	DDX_Control(pDX, IDC_BUTTON_ACTION_37, m_cButtonAction37);
	DDX_Control(pDX, IDC_BUTTON_EXIT, m_cButtonExit);
}


BEGIN_MESSAGE_MAP(CMenuFS08_4, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_31, &CMenuFS08_4::OnBnClickedButtonAction31)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_32, &CMenuFS08_4::OnBnClickedButtonAction32)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_33, &CMenuFS08_4::OnBnClickedButtonAction33)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_34, &CMenuFS08_4::OnBnClickedButtonAction34)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_35, &CMenuFS08_4::OnBnClickedButtonAction35)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_36, &CMenuFS08_4::OnBnClickedButtonAction36)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_37, &CMenuFS08_4::OnBnClickedButtonAction37)
END_MESSAGE_MAP()


// CMenuFS08_4 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::OnInitDialog
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
BOOL CMenuFS08_4::OnInitDialog()
{
	CMenuCommon::OnInitDialog();

	// 動作ステータス設定
	SetStatus(STATUS_INITIAL);

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::OnBnClickedButtonAction31
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「高低位置検出器」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuFS08_4::OnBnClickedButtonAction31()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_31);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::OnBnClickedButtonAction32
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
void CMenuFS08_4::OnBnClickedButtonAction32()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_32);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::OnBnClickedButtonAction33
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
void CMenuFS08_4::OnBnClickedButtonAction33()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_33);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::OnBnClickedButtonAction34
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
void CMenuFS08_4::OnBnClickedButtonAction34()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_34);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::OnBnClickedButtonAction35
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
void CMenuFS08_4::OnBnClickedButtonAction35()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_35);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::OnBnClickedButtonAction36
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
// 
void CMenuFS08_4::OnBnClickedButtonAction36()
{
	// 動作ステータス設定
	SetStatus(STATUS_WORKING);

	// Flashへ操作指示送信
	SendAction(ACTION_36);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::OnBnClickedButtonAction37
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
void CMenuFS08_4::OnBnClickedButtonAction37()
{
	// 動作ステータス設定
	SetStatus(STATUS_INITIAL);

	// Flashへ操作指示送信
	SendAction(ACTION_37);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::SetStatus
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
void CMenuFS08_4::SetStatus(EStatus eStatus)
{
	// 動作ステータス設定
	m_eStatus = eStatus;

	// メニューボタン設定
	SetMenuButton();
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08_4::SetMenuButton
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
void CMenuFS08_4::SetMenuButton()
{
	switch (m_eStatus) {
	case STATUS_INITIAL:
		m_cButtonAction31.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction32.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction33.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction34.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction35.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction36.SetEnableAndCheck(TRUE, FALSE);
		m_cButtonAction37.SetEnable(FALSE);
		m_cButtonExit.SetEnable(TRUE);
		break;
	case STATUS_WORKING:
		m_cButtonAction31.SetEnable(FALSE);
		m_cButtonAction32.SetEnable(FALSE);
		m_cButtonAction33.SetEnable(FALSE);
		m_cButtonAction34.SetEnable(FALSE);
		m_cButtonAction35.SetEnable(FALSE);
		m_cButtonAction36.SetEnable(FALSE);
		m_cButtonAction37.SetEnable(TRUE);
		m_cButtonExit.SetEnable(FALSE);
		break;
	}
}
