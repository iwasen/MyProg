// LoginDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "LoginDlg.h"
#include "LoginDlg001.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CLoginDlg ダイアログ


CLoginDlg::CLoginDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CLoginDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CLoginDlg)
	//}}AFX_DATA_INIT
}


void CLoginDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CLoginDlg)
	DDX_Control(pDX, IDC_EDIT_SHOKUBAN, m_cEditShokuban);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CLoginDlg, CDialogEx)
	//{{AFX_MSG_MAP(CLoginDlg)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLoginDlg メッセージ ハンドラ

BOOL CLoginDlg::OnInitDialog() 
{
	CDialogEx::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_LoginDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

#ifdef _DEBUG
	m_cEditShokuban = "test";
#endif

	m_cEditShokuban.SetMaxLength(8);

	return TRUE;
}

void CLoginDlg::OnOK()
{
	CString sShokuban = m_cEditShokuban;

	// 英数字チェック
	if (!CDataCheck::IsAlphaNum(sShokuban)) {
		g_pSystem->DispMessage("E001004");
		return;
	}

	// ログインチェック
	if (!g_pSystem->ReadUserInfo(sShokuban)) {
		EndDialog(IDCANCEL);
		return;
	}

	EndDialog(IDOK);
}
