//*****************************************************************************************************
//  1. ファイル名
//		MenuFS08.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自己位置・姿勢標定装置−発射制御器−電力分配器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS08.h"


// CMenuFS08 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS08, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08::CMenuFS08
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
CMenuFS08::CMenuFS08(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS08::IDD, pParent)
{
}

void CMenuFS08::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CMenuFS08, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS08::OnBnClickedButtonAction2)
END_MESSAGE_MAP()


// CMenuFS08 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS08::OnBnClickedButtonAction2
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
void CMenuFS08::OnBnClickedButtonAction2()
{
	// Flashへ操作指示送信
	SendAction(2);
}
