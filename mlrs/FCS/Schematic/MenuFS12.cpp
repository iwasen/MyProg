//*****************************************************************************************************
//  1. ファイル名
//		MenuFS12.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ＬＰ／Ｃ−弾頭連接器画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS12.h"


// CMenuFS12 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS12, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS12::CMenuFS12
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
CMenuFS12::CMenuFS12(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS12::IDD, pParent)
{
}

void CMenuFS12::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CMenuFS12, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS12::OnBnClickedButtonAction2)
END_MESSAGE_MAP()


// CMenuFS12 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS12::OnBnClickedButtonAction2
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
void CMenuFS12::OnBnClickedButtonAction2()
{
	// Flashへ操作指示送信
	SendAction(2);
}
