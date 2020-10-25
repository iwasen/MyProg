// GraphTitle.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphTitle.h"
#include "General.h"


// CGraphTitle ダイアログ

IMPLEMENT_DYNAMIC(CGraphTitle, CDialog)

CGraphTitle::CGraphTitle(CWnd* pParent /*=NULL*/)
	: CDialog(CGraphTitle::IDD, pParent)
{

}

CGraphTitle::~CGraphTitle()
{
}

void CGraphTitle::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_TITLE, m_cEditTitle);
}


BEGIN_MESSAGE_MAP(CGraphTitle, CDialog)
	ON_BN_CLICKED(IDOK, &CGraphTitle::OnBnClickedOk)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDOK, "グラフの印刷を実行します。"},
	{IDCANCEL, "このダイアログを閉じます。"},
	{0, NULL}
};

BOOL CGraphTitle::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cEditTitle.SetWindowText(m_sTitle);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

void CGraphTitle::SetTitle(CString title)
{
	m_sTitle = title;
}

void CGraphTitle::GetTitle(CString& title)
{
	title = m_sTitle;
}

void CGraphTitle::OnBnClickedOk()
{
	m_cEditTitle.GetWindowText(m_sTitle);

	OnOK();
}

void CGraphTitle::OnDestroy()
{
	CDialog::OnDestroy();

	OnCancel();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphTitle::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウメッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		MSG*	pMsg			[I] MSG構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	親クラスの返値をそのまま返す
//*****************************************************************************************************
BOOL CGraphTitle::PreTranslateMessage(MSG* pMsg)
{
	// ツールチップを表示するための処理
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialog::PreTranslateMessage(pMsg);
}
