//*****************************************************************************************************
//  1. ファイル名
//		FftBaseDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		解析グラフダイアログ基底クラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2008.10.15 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "FftBaseDlg.h"
#include "Define.h"

// CFftBaseDlg ダイアログ

IMPLEMENT_DYNAMIC(CFftBaseDlg, CDialogEx)

CFftBaseDlg::CFftBaseDlg(UINT nID, CWnd* pParent)
	: CDialogEx(nID, pParent)
{

}

CFftBaseDlg::~CFftBaseDlg()
{
}

void CFftBaseDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CFftBaseDlg, CDialogEx)
	ON_WM_DESTROY()
END_MESSAGE_MAP()


// CFftBaseDlg メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CFftBaseDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CFftBaseDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// 解析グラフウィンドウオープン通知
	::SendMessage(m_hWndParent, WM_USER_OPEN_GRAPH, (WPARAM)GetSafeHwnd(), 0);

	// 親ウィンドウの中央の位置に表示
	CenterWindow(CWnd::FromHandle(m_hWndParent));

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftBaseDlg::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ廃棄処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftBaseDlg::OnDestroy()
{
	CDialogEx::OnDestroy();

	// 解析グラフウィンドウクローズ通知
	::SendMessage(m_hWndParent, WM_USER_CLOSE_GRAPH, (WPARAM)GetSafeHwnd(), 0);
}
