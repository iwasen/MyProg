//*****************************************************************************************************
//  1. ファイル名
//		MenuFS04.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ブームモータ系（結線図）画面の操作メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuFS04.h"

// CMenuFS04 ダイアログ

IMPLEMENT_DYNCREATE(CMenuFS04, CMenuCommon)

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS04::CMenuFS04
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
CMenuFS04::CMenuFS04(CWnd* pParent /*=NULL*/)
	: CMenuCommon(CMenuFS04::IDD, pParent)
{
}

void CMenuFS04::DoDataExchange(CDataExchange* pDX)
{
	CMenuCommon::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CMenuFS04, CMenuCommon)
	ON_BN_CLICKED(IDC_BUTTON_ACTION_2, &CMenuFS04::OnBnClickedButtonAction2)
END_MESSAGE_MAP()


// CMenuFS04 メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuFS04::OnBnClickedButtonAction2
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
void CMenuFS04::OnBnClickedButtonAction2()
{
	// Flashへ操作指示送信
	SendAction(2);
}
