//*****************************************************************************************************
//  1. ファイル名
//		MenuFS07.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		制御演算器−電源制御器−発射制御器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS07.h"


// CMenuFS07 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS07, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS07::CMenuFS07
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
CMenuFS07::CMenuFS07(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS07::IDD, pParent)
{
}

void CMenuFS07::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CMenuFS07, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS07::OnBnClickedButtonAction2)
END_MESSAGE_MAP()


// CMenuFS07 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS07::OnBnClickedButtonAction2
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
void CMenuFS07::OnBnClickedButtonAction2()
{
	// Flashへ操作指示送信
	SendAction(2);
}
