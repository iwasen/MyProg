//*****************************************************************************************************
//  1. ファイル名
//		MenuFS09.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		制御演算器−発射制御器−ＬＰ／Ｃ画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS09.h"


// CMenuFS09 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS09, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS09::CMenuFS09
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
CMenuFS09::CMenuFS09(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS09::IDD, pParent)
{
}

void CMenuFS09::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CMenuFS09, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS09::OnBnClickedButtonAction2)
END_MESSAGE_MAP()


// CMenuFS09 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS09::OnBnClickedButtonAction2
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
void CMenuFS09::OnBnClickedButtonAction2()
{
	// Flashへ操作指示送信
	SendAction(2);
}
